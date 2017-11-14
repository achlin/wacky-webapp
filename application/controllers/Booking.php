<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
* The controller for the Fleet view.
*/
class Booking extends Application
{

    private $adj = array();
    private $visted = array();
    private $allAirports;
    const MAX_PATH_LENGTH = 3;
    private $timezone;

    function __construct()
    {
        parent::__construct();
        $this->timezone =  new DateTimeZone('America/Vancouver');
    }

    /**
     * Search for paths to and from the user selected airports.
     */
    public function search() {
        $form = $this->input->post();
        $this->allAirports = $this->airports->airportsWeService();
        $this->initAdj();
        $this->data['availableFlights'] = array();
        $flights = $this->scheduleModel->all();

        foreach($flights as $flight) {
            array_push($this->adj[$flight->departsFrom][$flight->arrivesAt], array($flight));
        }

        $result = $this->findPaths();

        foreach ($result as $key => $value) {
            $processedPath = $this->processPaths($form, $key, $value);
            if (!empty($processedPath['flights'])) {
                array_push($this->data['availableFlights'], $processedPath);
            }
        }

        $this->data['departsFrom'] = $form['startAirport'];
        $this->data['departureCity'] = $this->allAirports[$form['startAirport']]->community;
        $this->data['arrivesAt'] = $form['endAirport'];
        $this->data['arrivalCity'] = $this->allAirports[$form['endAirport']]->community;

        $this->showit();
    }

    /**
     * Process found paths into a format that's displayable.
     */
    private function processPaths($form, $key, $value) {
        $flights = array();
        $airports = $this->airports->airportsWeService();
        $arrow = '<i class="fa fa-long-arrow-right" aria-hidden="true"></i>';
        $tempflights = $value[$form['startAirport']][$form['endAirport']];

        foreach($tempflights as $tempflight) {
            $cityPath = '';
            $flightPathId = '';
            $flight = array();
            $totalDepartureDate;
            $lastSegmentArrivalTime = '00:00';
            $daysCount = 0;
            foreach($tempflight as $segment) {
                array_push($flight, (array) $segment);
                if ($cityPath) {
                    $cityPath .= " " . $arrow . " " . $this->allAirports[$segment->arrivesAt]->community;
                } else {
                    $departureCity = $this->allAirports[$segment->departsFrom]->community;
                    $arrivalCity = $this->allAirports[$segment->arrivesAt]->community;
                    $cityPath .= $departureCity . " " . $arrow . " " . $arrivalCity;
                    $totalDepartureDate = DateTime::createFromFormat('H:i', $segment->departureTime, $this->timezone);
                }

                $segmentDepartDate = DateTime::createFromFormat('H:i', $segment->departureTime, $this->timezone);
                $lastSegmentArrivalDate = DateTime::createFromFormat('H:i', $lastSegmentArrivalTime, $this->timezone);

                if ($lastSegmentArrivalDate >= $segmentDepartDate) {
                    $daysCount++;
                }
                $lastSegmentArrivalTime = $segment->arrivalTime;
                $flightPathId .= $segment->id;
            }

            $totalArrivalDate = DateTime::createFromFormat('H:i', $lastSegmentArrivalTime, $this->timezone);

            $totalDepartureDateF = $this->dateFormatting($totalDepartureDate, $daysCount);
            $totalArrivalDateF = $this->dateFormatting($totalArrivalDate, ++$daysCount);

            array_push($flights, array('flight' => $flight,
                            'totalDepartureDate' => $totalDepartureDateF,
                            'totalArrivalTime' => $totalArrivalDateF,
                            'cityPath' => $cityPath,
                            'flightPathId' => $flightPathId));
        }
        return array('flights' => $flights,
                    'NoOfStops' => $key-1);
    }

    private function dateFormatting($date, $daysToAdd) {
        $date->add(new DateInterval('P' . $daysToAdd . 'D'));
        return $date->format('Y-m-d H:i T');
    }

    /**
     * Initialize adjancency matrix with empty arrays.
     */
    private function initAdj() {
        foreach($this->allAirports as $startAirport) {
            foreach($this->allAirports as $endAirport) {
                $this->adj[$startAirport->id][$endAirport->id] = array();
            }
        }
    }

    /**
     * Find the paths using the adjancency matrix.
     */
    private function findPaths() {
        $combinedResult = array();
        $combinedResult[1] = $this->adj;
        for ($pathLength = 2; $pathLength <= self::MAX_PATH_LENGTH; $pathLength++) {
            $result = $this->adj;
            for ($i = 2; $i <= $pathLength; $i++) {
                $result = $this->matrixMult($result, $this->adj);
            }
            $combinedResult[$pathLength] = $result;
        }
        return $combinedResult;
    }

    private function matrixMult($A, $B) {
        $result = array();
        foreach($this->allAirports as $startAirport) {
            foreach($this->allAirports as $endAirport) {
                $combine = array();
                foreach($this->allAirports as $midAirport) {
                    $combine = array_merge($combine, $this->combinePaths($A[$startAirport->id][$midAirport->id], $B[$midAirport->id][$endAirport->id]));
                }
                $result[$startAirport->id][$endAirport->id] = $combine;
            }
        }
        return $result;
    }

    private function combinePaths($a, $b) {
        $result = array();
        foreach($a as $pathA) {
            foreach($b as $pathB) {
                array_push($result, array_merge($pathA, $pathB));
            }
        }
        return $result;
    }

    // Render the current DTO
    private function showit()
    {
        $this->data['pagebody'] = 'booking';
        $this->render();
    }
}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
* The controller for the Fleet view.
*/
class Booking extends Application
{
    private $allAirports;
    const MAX_PATH_LENGTH = 3;

    function __construct()
    {
        parent::__construct();
        $this->allAirports = $this->airports->airportsWeService();
    }

    /**
     * Search for paths to and from the user selected airports.
     */
    public function search() {
        $form = $this->input->post();
        if (isset($form['startAirport']) && isset($form['endAirport'])) {
            $this->session->set_userdata('search', $form);
        } else {
            $form = $this->session->userdata("search");
            if (!isset($form['startAirport']) || !isset($form['endAirport'])) {
                redirect('/');
            }
        }

        $adj = array();
        $this->initAdj($adj);
        $availableFlights = array();

        $result = $this->findPaths($form, $adj);

        foreach ($result as $key => $value) {
            $processedFlights = $this->processFlights($form, $key, $value);
            if (!empty($processedFlights['flights'])) {
                array_push($availableFlights, $processedFlights);
            }
        }

        $this->data['departsFrom'] = $form['startAirport'];
        $this->data['departureCity'] = $this->allAirports[$form['startAirport']]->community;
        $this->data['arrivesAt'] = $form['endAirport'];
        $this->data['arrivalCity'] = $this->allAirports[$form['endAirport']]->community;
        $this->data['availableFlights'] = $availableFlights;
        $this->showit();
    }

    /**
     * Process found flights into a format that's displayable.
     */
    private function processFlights($form, $key, $value) {
        $flights = array();
        $tempflights = $value[$form['startAirport']][$form['endAirport']];

        foreach($tempflights as $tempflight) {
            $processedFlight = $this->processFlightInfo($tempflight);
            array_push($flights, $processedFlight);
        }

        return array('flights' => $flights,
                    'NoOfStops' => $key);
    }

    /**
     * Process found flight into a format that's displayable.
     */
    private function processFlightInfo($tempflight) {
        $timezone =  new DateTimeZone('America/Vancouver');
        $cityPath = '';
        $flightPathId = '';
        $flight = array();
        $lastSegmentArrivalTime = '00:00';
        $daysCount = 0;
        foreach($tempflight as $segment) {
            array_push($flight, (array) $segment);
            $this->formatCityPath($segment, $cityPath);

            $segmentDepartDate = DateTime::createFromFormat('H:i', $segment->departureTime, $timezone);
            $lastSegmentArrivalDate = DateTime::createFromFormat('H:i', $lastSegmentArrivalTime, $timezone);

            $this->checkForNextDayFlight($lastSegmentArrivalDate, $segmentDepartDate, $daysCount);

            $lastSegmentArrivalTime = $segment->arrivalTime;
            $flightPathId .= $segment->id;
        }

        $totalDepartureDate = DateTime::createFromFormat('H:i', $tempflight[0]->departureTime, $timezone);
        $totalArrivalDate = DateTime::createFromFormat('H:i', $lastSegmentArrivalTime, $timezone);
        $totalDepartureDateF = $this->dateFormatting($totalDepartureDate, 1);
        $totalArrivalDateF = $this->dateFormatting($totalArrivalDate, ++$daysCount);
        $totalTime = $totalDepartureDate->diff($totalArrivalDate)->format('%dd %Hh %Im');

        return array('flight' => $flight,
                        'totalDepartureDate' => $totalDepartureDateF,
                        'totalArrivalTime' => $totalArrivalDateF,
                        'totalTime' => $totalTime,
                        'cityPath' => $cityPath,
                        'flightPathId' => $flightPathId);
    }

    private function formatCityPath($segment, &$cityPath) {
        $arrow = '<i class="fa fa-long-arrow-right" aria-hidden="true"></i>';
        if ($cityPath) {
            $cityPath .= " " . $arrow . " " . $this->allAirports[$segment->arrivesAt]->community;
        } else {
            $departureCity = $this->allAirports[$segment->departsFrom]->community;
            $arrivalCity = $this->allAirports[$segment->arrivesAt]->community;
            $cityPath .= $departureCity . " " . $arrow . " " . $arrivalCity;
        }
    }

    private function dateFormatting($date, $daysToAdd) {
        $date->add(new DateInterval('P' . $daysToAdd . 'D'));
        return $date->format('Y-m-d H:i T');
    }

    private function checkForNextDayFlight($lastSegmentArrivalDate, $segmentDepartDate, &$daysCount) {
        if ($lastSegmentArrivalDate >= $segmentDepartDate) {
            $daysCount++;
        }
    }

    /**
     * Initialize adjancency matrix with array of flights.
     */
    private function initAdj(&$adj) {
        foreach($this->allAirports as $startAirport) {
            foreach($this->allAirports as $endAirport) {
                $adj[$startAirport->id][$endAirport->id] = array();
            }
        }

        $flights = $this->scheduleModel->all();

        foreach($flights as $flight) {
            array_push($adj[$flight->departsFrom][$flight->arrivesAt], array($flight));
        }
    }

    /**
     * Find the paths using the adjancency matrix.
     */
    private function findPaths($form, $adj) {
        $combinedResult = array();
        $combinedResult[0] = $adj;
        $result = $adj;
        for ($i = 1; $i <= self::MAX_PATH_LENGTH - 1; $i++) {
            $result = $this->matrixMult($form, $result, $adj);
            $combinedResult[$i] = $result;
        }
        return $combinedResult;
    }

    private function matrixMult($form, $A, $B) {
        $result = array();
        foreach($this->allAirports as $startAirport) {
            foreach($this->allAirports as $endAirport) {
                $combine = array();
                foreach($this->allAirports as $midAirport) {
                    if ($midAirport->id != $form['endAirport'] && $startAirport->id != $endAirport->id) {
                        $combine = array_merge($combine, $this->combinePaths($A[$startAirport->id][$midAirport->id], $B[$midAirport->id][$endAirport->id]));
                    }
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

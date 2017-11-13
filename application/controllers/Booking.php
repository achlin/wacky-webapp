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

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Search for paths to and from the user selected airports.
     */
    public function search() {
        $this->allAirports = $this->airports->airportsWeService();
        $this->initAdj();
        $this->data['availableFlights'] = array();
        $flights = $this->scheduleModel->all();

        foreach($flights as $flight) {
            array_push($this->adj[$flight->departsFrom][$flight->arrivesAt], array($flight));
        }

        $result = $this->findPaths();

        foreach ($result as $key => $value) {
            $processedPath = $this->processPaths($key, $value);
            if (!empty($processedPath['flights'])) {
                array_push($this->data['availableFlights'], $processedPath);
            }
        }
        $this->showit();
    }

    /**
     * Process found paths into a format that's displayable.
     */
    private function processPaths($key, $value) {
        $form = $this->input->post();
        $flights = array();
        $tempflights = $value[$form['startAirport']][$form['endAirport']];
        foreach($tempflights as $tempflight) {
            $segmentIds = '';
            $flightPathId = '';
            $flight = array();
            foreach($tempflight as $segment) {
                array_push($flight, (array) $segment);
                if ($segmentIds) {
                    $segmentIds .= " -> ";
                }
                $segmentIds .= $segment->id;
                $flightPathId .= $segment->id;
            }
            array_push($flights, array('flight' => $flight,
                            'segmentIds' => $segmentIds,
                            'flightPathId' => $flightPathId));
        }
        return array('flights' => $flights,
                    'departsFrom' => $form['startAirport'],
                    'arrivesAt' => $form['endAirport'],
                    'NoOfStops' => $key-1);
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

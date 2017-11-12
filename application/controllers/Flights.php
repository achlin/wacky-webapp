<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
* The controller for the Flights view.
*/
class Flights extends Application
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->data['pagebody'] = 'flights';

        $this->loadFlightSchedule();

        $this->render();
    }

    /*
    * Loops through all the flights and finds the flight code, depature
    * location,and arrival location to display in a table.  It finds the
    * departure time, arrival time and airport codes to disaply in a mouseover.
    */
    private function loadFlightSchedule() {
        $flights =  $this->scheduleModel->all();
        $airports = $this->airports->all();
        $this->data['flightSchedule'] = array();

        // Loop through all the flights and find the locations of the airports
        foreach($flights as $key => $value) {
            $departsFrom = $value->departsFrom;
            $arrivesAt = $value->arrivesAt;
            $departure_airport = $airports[$departsFrom];
            $arrival_airport = $airports[$arrivesAt];
            $details = 'From: ' . $arrival_airport->id . ' At: '
                . $value->departureTime . "\n" . 'To: ' . $departure_airport->id
                . ' At: ' . $value->arrivalTime;
            //Adds the items to the array that will be displayed
            array_push($this->data['flightSchedule'],
                array('flightNo' => $key,
                'departsFrom' => $departure_airport->community,
                'arrivesAt' => $arrival_airport->community,
                'details' => $details
            ));
        }
    }

}

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
        $result = '';

        foreach($flights as $flight) {
            $departsFrom = $flight->departsFrom;
            $arrivesAt = $flight->arrivesAt;
            $departure_airport = $airports[$departsFrom];
            $arrival_airport = $airports[$arrivesAt];
            $details = 'From: ' . $arrival_airport->id . ' At: '
                       . $flight->departureTime . "\n" . 'To: ' . $departure_airport->id
                       . ' At: ' . $flight->arrivalTime;
            $flight->details = $details;
            $result .= $this->parser->parse('flightrow', (array) $flight, true);
        }
        $this->data['display_flights'] = $result;
    }

    public function show($id = null) {
        if($id === null)
            redirect('/flights');
        $role = $this->session->userdata("userrole");
        $flight = $this->scheduleModel->get($id);
        echo $flight->id;
        $this->session->set_userdata('flight', $flight);
        if($role === ROLE_ADMIN)
            $this->editFlight($id);
        else
            $this->displayFlight($flight);

    }

    private function displayFlight($flight) {
        $this->data['pagebody'] = 'flightdisplay';
        $this->data = array_merge($this->data, (array) $flight);

        $this->render();
    }

    private function editFlight() {
        $this->load->helper('form');
        $flight = $this->session->userdata("flight");
        // if no errors, pass an empty message
        if ( ! isset($this->data['error']))
            $this->data['error'] = '';

        $fields = array(
            'fid'           => form_input('id', $flight->id),
            'fdepartsFrom'  => form_dropdown('departsFrom', $this->loadAvailableAirports(), $flight->departsFrom),
            'farrivesAt'    => form_dropdown('arrivesAt', $this->loadAvailableAirports(), $flight->arrivesAt),
            'fdepartureTime'=> form_input('departureTime', $flight->departureTime),
            'farrivalTime'  => form_input('arrivalTime', $flight->arrivalTime),
            'fplane'        => form_dropdown('plane', $this->loadAvailablePlanes(), $flight->plane),
            'zsubmit'       => form_submit('submit', 'Save'),
        );
        $this->data = array_merge($this->data, $fields);

        $this->data['pagebody'] = 'flightedit';
        $this->render();
    }

    public function submit() {
        $flight = (array) $this->session->userdata('flight');
        $flight = array_merge($flight, $this->input->post());
        $flight = (object) $flight;  // convert back to object
        $this->session->set_userdata('flight', $flight);
        $newFlight = new FlightModel();

        try {
            $newFlight->__set('departsFrom', $flight->departsFrom);
            $newFlight->__set('arrivesAt', $flight->arrivesAt);
            $newFlight->__set('departureTime', $flight->departureTime);
            $newFlight->__set('arrivalTime', $flight->arrivalTime);
            $newFlight->__set('plane', $flight->plane);

            $existingFlight = $this->scheduleModel->get($flight->id);
            if ($existingFlight === null) {
                //New flight
                $newFlight->__set('id', $flight->id);
                $this->scheduleModel->addFlight($newFlight);
                $this->alert('Flight ' . $flight->id . ' added', 'success');
            } else {
                //Update flight
                $this->scheduleModel->updateFlight($flight);
                $this->alert('Flight ' . $flight->id . ' updated', 'success');
            }
        } catch (Exception $e) {
            $this->alert('<strong>Validation errors!<strong><br>' . $e->getMessage(), 'danger');
        }
        $this->editFlight();
    }

    private function loadAvailablePlanes() {
        $fleet = $this->fleetModel->all();
        $planeIds = array();
        foreach($fleet as $plane)
            $planeIds[$plane->id] = $plane->id;
        return $planeIds;
    }

    private function loadAvailableAirports() {
        $airports = $this->airports->airportsWeService();
        $airportCodes = array();
        foreach($airports as $airport)
            $airportCodes[$airport->id] = $airport->id;
        return $airportCodes;
    }

    private function alert($message) {
        $this->load->helper('html');
        $this->data['error'] = heading($message,3);
    }

    public function cancel() {
        redirect('/flights');
    }

}

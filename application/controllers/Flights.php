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
        $role = $this->session->userdata("userrole");
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
        if ($role === ROLE_ADMIN)
            $this->data['add_flight'] = $this->parser->parse('addflight', (array) $flight, true);
        else
            $this->data['add_flight'] ='';
        $this->data['display_flights'] = $result;
    }

    public function show($id = null) {
        if($id === null)
            redirect('/flights');
        $role = $this->session->userdata("userrole");
        $flight = $this->scheduleModel->get($id);
        $this->session->set_userdata('flight', $flight);
        if($role === ROLE_ADMIN)
            $this->editFlight();
        else
            $this->displayFlight($flight);

    }

    private function displayFlight($flight) {
        $this->data['pagebody'] = 'flightdisplay';
        $this->data = array_merge($this->data, (array) $flight);

        $this->render();
    }

    public function add() {
        $role = $this->session->userdata('userrole');
        if ($role != ROLE_ADMIN)
            redirect('/fleet');

        $this->load->helper('form');
        // if no errors, pass an empty message
        if ( ! isset($this->data['error']))
            $this->data['error'] = '';

        $fields = array(
            'fid'           => form_input('id', '', 'class="form-control form-control-sm"'),
            'fdepartsFrom'  => form_dropdown('departsFrom', $this->loadAvailableAirports(), '', 'class="form-control form-control-sm"'),
            'farrivesAt'    => form_dropdown('arrivesAt', $this->loadAvailableAirports(), '', 'class="form-control form-control-sm"'),
            'fdepartureTime'=> form_input('departureTime', '', 'class="form-control form-control-sm"'),
            'farrivalTime'  => form_input('arrivalTime', '', 'class="form-control form-control-sm"'),
            'fplane'        => form_dropdown('plane', $this->loadAvailablePlanes(), '', 'class="form-control form-control-sm"'),
            'zsubmit'       => form_submit('submit', 'Save', 'class="btn btn-primary"'),
        );
        $this->data = array_merge($this->data, $fields);

        $this->data['pagebody'] = 'flightedit';
        $this->render();
    }

    private function editFlight() {
        $role = $this->session->userdata('userrole');
        if ($role != ROLE_ADMIN)
            redirect('/flights');

        $this->load->helper('form');
        $flight = $this->session->userdata("flight");
        // if no errors, pass an empty message
        if ( ! isset($this->data['error']))
            $this->data['error'] = '';

        $fields = array(
            'fid'           => form_input('id', $flight->id, 'class="form-control form-control-sm"'),
            'fdepartsFrom'  => form_dropdown('departsFrom', $this->loadAvailableAirports(), $flight->departsFrom, 'class="form-control form-control-sm"'),
            'farrivesAt'    => form_dropdown('arrivesAt', $this->loadAvailableAirports(), $flight->arrivesAt, 'class="form-control form-control-sm"'),
            'fdepartureTime'=> form_input('departureTime', $flight->departureTime, 'class="form-control form-control-sm"'),
            'farrivalTime'  => form_input('arrivalTime', $flight->arrivalTime, 'class="form-control form-control-sm"'),
            'fplane'        => form_dropdown('plane', $this->loadAvailablePlanes(), $flight->plane, 'class="form-control form-control-sm"'),
            'zsubmit'       => form_submit('submit', 'Save', 'class="btn btn-primary"'),
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

            $existingFlight = $this->scheduleModel->get($flight->id);
            if($existingFlight === null)
                $newFlight->id = $flight->id;

            $newFlight->departsFrom = $flight->departsFrom;
            $newFlight->arrivesAt = $flight->arrivesAt;
            $newFlight->departureTime = $flight->departureTime;
            $newFlight->arrivalTime = $flight->arrivalTime;
            $newFlight->plane = $flight->plane;

            if ($existingFlight === null) {
                //New flight
                $this->scheduleModel->addFlight($newFlight);
                $this->alert('Flight ' . $flight->id . ' added', 'success');
            } else {
                //Update flight
                $this->scheduleModel->updateFlight($flight);
                $this->alert('Flight ' . $flight->id . ' updated', 'success');
            }
        } catch (Exception $e) {
            $this->alert('<strong>Validation errors!</strong><br>' . $e->getMessage(), 'danger');
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
        $this->data['error'] = $message;
    }

    public function cancel() {
        redirect('/flights');
    }

}

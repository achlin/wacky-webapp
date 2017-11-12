<?php

require_once(APPPATH.'models/WackyModel.php');

/*
* Flight entity class.
*/
class FlightModel extends CI_Model {

    /*
    * ctor
    */
    public function __construct()
    {
        parent::__construct();
    }

    // If this class has a setProp method, use it, else modify the property directly
    public function __set($key, $value)
    {
        // if a set* method exists for this key,
        // use that method to insert this value.
        // For instance, setName(...) will be invoked by $object->name = ...
        // and setLastName(...) for $object->last_name =
        $method = 'set' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));
        if (method_exists($this, $method))
        {
                $this->$method($value);
                return $this;
        }

        //otherwise validate the property
        if (!$this->validate($key, $value)) {
            throw new Exception('Property does not validate: ' . $key . ' => ' . $value);
        }

        // Otherwise, just set the property value directly.
        $this->$key = $value;
        return $this;
    }

    public function validate($key, $value)
    {
        $rules = $this->rules();
        return call_user_func(array($this, $rules[$key]), $value);
    }

    // provide entity validation rules
    public function rules()
    {
        $config = array(
            'id' => 'validId',
            'departsFrom' => 'validDepartsFrom',
            'arrivesAt' => 'validArrivesAt',
            'departureTime' => 'validDepartureTime',
            'arrivalTime' => 'validArrivalTime',
            'plane' => 'validPlane',
        );
        return $config;
    }

    /*
    * Validator for flight unique Id
    * param $value - String representing the flight's unique id.
    * An Id will be considered invalid and returns false if:
    *   - The Id already exists in the fleet.
    */
    private function validId(String $value) {
        $this->load->model('scheduleModel');
        $source = $this->scheduleModel->all();

        $flightIds = array_column($source, 'id');
        return !in_array($value, $flightIds);
    }

    /*
    * Validator for flight departure time.
    * param $value - The flight's departure Time
    * A departure time will be considered invalid and returns false if:
    *   - The number is not a valid time.
    *   - The time is before 8 AM
    */
    private function validDepartureTime($value)
    {
        $startTime = DateTime::createFromFormat('H:i', DEPART_START_TIME);
        $departTime = DateTime::createFromFormat('H:i', $value);
        return $departTime && $departTime > $startTime;
    }

    /*
    * Validator for flight arrival time.
    * param $value - The flight's arrival Time
    * An arrival time will be considered invalid and returns false if:
    *   - The number is not a valid time.
    *   - The time is after 10 PM
    */
    private function validArrivalTime($value)
    {
        $endTime = DateTime::createFromFormat('H:i', ARRIVAL_END_TIME);
        $arrivalTime = DateTime::createFromFormat('H:i', $value);
        return $arrivalTime && $arrivalTime < $endTime;
    }

    /*
    * Validator for flight departure airport.
    * param $value - The flight's departure Time
    * An airport code will be considered invalid and returns false if:
    *   - The departure airport code does not exist
    */
    private function validDepartsFrom($value)
    {
        $wackyModel = new WackyModel();
        $airport = $wackyModel->getAirport($value);
        return $airport && $airport !== 'null';
    }

    /*
    * Validator for flight arrival airport.
    * param $value - The flight's arrival Time
    * An airport code will be considered invalid and returns false if:
    *   - The arrival airport code does not exist
    */
    private function validArrivesAt($value)
    {
        $wackyModel = new WackyModel();
        $airport = $wackyModel->getAirport($value);
        return $airport && $airport !== 'null';
    }

    /*
    * Validator for plane.
    * param $value - The flight's plane
    * A plane will be considered invalid and returns false if:
    *   - The plane does not exist in our fleet
    */
    private function validPlane($value)
    {
        $this->load->model('fleetModel');
        $fleet = $this->fleetModel->all();
        $planeIds = array_column($fleet, 'id');
        return in_array($value, $planeIds);
    }


}

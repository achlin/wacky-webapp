<?php

require_once(APPPATH.'models/WackyModel.php');

/*
* Flight entity class.
*/
class FlightModel extends CI_Model {

    private $id;
    private $departsFrom;
    private $arrivesAt;
    private $departureTime;
    private $arrivalTime;
    private $plane;

    /*
    * ctor
    */
    public function __construct()
    {
        parent::__construct();
    }

    /*
    * Setter for flight unique Id
    * param $value - String representing the flight's unique id.
    * An Id will be considered invalid and throws and exception if:
    *   - The Id already exists in the fleet.
    */
    public function setId(String $value)
    {
        $this->load->model('flightsModel');
        $source = $this->flightsModel->all();
        foreach ($source as $flight) {
            if ($flight->id === $value) {
                throw new Exception("Flight Id already exists");
            }
        }
        $this->id = $value;
    }

    /*
    * Setter for flight departure time.
    * param $value - The flight's departure Time
    * An Id will be considered invalid and throws and exception if:
    *   - The number is not a valid time.
    *   - The time is before 8 AM
    */
    public function setDepartureTime($value)
    {
        $startTime = DateTime::createFromFormat('H:i', DEPART_START_TIME);
        $departTime = DateTime::createFromFormat('H:i', $value);
        if(!$departTime) {
            throw new Exception("Invalid Date");
        }
        if ($departTime < $startTime) {
            throw new Exception("Departure Times can not be set before 0800");
        }
        $this->departureTime = $value;
    }

    /*
    * Setter for flight arrival time.
    * param $value - The flight's arrival Time
    * An arrival time will be considered invalid and throws and exception if:
    *   - The number is not a valid time.
    *   - The time is after 10 PM
    */
    public function setArrivalTime($value)
    {
        $endTime = DateTime::createFromFormat('H:i', ARRIVAL_END_TIME);
        $arrivalTime = DateTime::createFromFormat('H:i', $value);
        if(!$arrivalTime) {
            throw new Exception("Invalid Date");
        }
        if ($arrivalTime > $endTime) {
            throw new Exception("Arrival Times can not be set after 2200");
        }
        $this->arrivalTime = $value;
    }

    /*
    * Setter for flight departure airport.
    * param $value - The flight's departure Time
    * An airport code will be considered invalid and throws and exception if:
    *   - The departure airport code does not exist
    */
    public function setDepartsFrom($value)
    {
        $wackyModel = new WackyModel();
        $airport = $wackyModel->getAirport($value);
        if (!$airport || $airport === 'null') {
            throw new Exception("Departure Airport not found");
        }
        $this->departsFrom = $value;
    }

    /*
    * Setter for flight arrival airport.
    * param $value - The flight's arrival Time
    * An airport code will be considered invalid and throws and exception if:
    *   - The arrival airport code does not exist
    */
    public function setArrivesAt($value)
    {
        $wackyModel = new WackyModel();
        $airport = $wackyModel->getAirport($value);
        if (!$airport || $airport === 'null') {
            throw new Exception("Arrival Airport not found");
        }
        $this->arrivesAt = $value;
    }

    /*
    * Setter for the plane.
    */
    public function setPlane($value)
    {
        $this->plane = $value;
    }

    /*
    * Getter for Id.
    */
    public function getId()
    {
        return $this->id;
    }

    /*
    * Getter for departsFrom.
    */
    public function getDepartsFrom()
    {
        return $this->departsFrom;
    }

    /*
    * Getter for arrivesAt.
    */
    public function getArrivesAt()
    {
        return $this->arrivesAt;
    }

    /*
    * Getter for departureTime.
    */
    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    /*
    * Getter for arrivalTime.
    */
    public function getArrivalTime()
    {
        return $this->arrivalTime;
    }

    /*
    * Getter for plane.
    */
    public function getPlane()
    {
        return $this->plane;
    }

}

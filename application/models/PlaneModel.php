<?php

require_once(APPPATH.'models/WackyModel.php');

/*
* Plane entity class
*/
class PlaneModel extends CI_Model {

    private $id;
    private $airplaneCode;
    private $manufacturer;
    private $model;
    private $price;
    private $seats;
    private $reach;
    private $cruise;
    private $takeoff;
    private $hourly;

    /*
    * ctor
    */
    public function __construct()
    {
        parent::__construct();
    }

    /*
    * Setter for plane unique Id
    * param $value - String representing the plane's unique id.
    * An Id will be considered invalid and throws and exception if:
    *   - The Id contains non-alphanumeric characters
    *   - The Id is an empty String
    *   - The Id does not start with 'k' or 'K'
    *   - The Id already exists in the fleet.
    */
    public function setId(String $value)
    {
        if (!ctype_alnum($value)) {
            throw new Exception("Airplane ID must be Alphanumeric");
        }

        if (strlen($value) === 0) {
            throw new Exception("Airplane can not be empty");
        }

        if($value[0] != 'k' && $value[0] != 'K') {
            throw new Exception("Airplane ID must start with 'k' or 'K'");
        }
        $this->load->model("fleetModel");
        $source = $this->fleetModel->all();
        foreach($source as $plane) {
            if ($plane->id === $value) {
                throw new Exception("Airplane ID already exists");
            }
        }
        $this->id = $value;
    }

    /*
    * Setter for plane airplaneCode. Used to retrieve the rest of the plane
    * info from the wacky server.
    * param $value - String representing the plane's airplane Code.
    * An Airplane Code will be considered invalid and throws and exception if:
    *   - The Airplane code does not exist on the wacky server.
    */
    public function setAirplaneCode(String $value)
    {
        $wackyModel = new WackyModel();
        $plane = $wackyModel.getAirplane($value);
        if(!$plane || $plane === 'null') {
            throw new Exception("Airplane Code not found");
        }
        parsePlaneJson($plane);
        $this->airplaneCode = value;
    }

    /*
    * Takes the json string returned from the wacky servers and converts it into
    * a php object.  Assigns the rest of the values to the plane properties.
    */
    private function parsePlaneJson($result)
    {
        $plane = json_decode($result);
        $this->manufacturer=$plane->manufacturer;
        $this->model=$plane->model;
        $this->price=$plane->price;
        $this->seats=$plane->seats;
        $this->reach=$plane->reach;
        $this->cruise=$plane->cruise;
        $this->takeoff=$plane->takeoff;
        $this->hourly=$plane->hourly;
    }

    /*
    * Getter for id.
    */
    public function getId()
    {
        return $this->id;
    }

    /*
    * Getter for airplane code.
    */
    public function getAirplaneCode()
    {
        return $this->airplaneCode;
    }

    /*
    * Getter for manufacturer.
    */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /*
    * Getter for model.
    */
    public function getModel()
    {
        return $this->model;
    }

    /*
    * Getter for price.
    */
    public function getPrice()
    {
        return $this->price;
    }

    /*
    * Getter for seats.
    */
    public function getSeats()
    {
        return $this->seats;
    }

    /*
    * Getter for reach.
    */
    public function getReach()
    {
        return $this->reach;
    }

    /*
    * Getter for cruise.
    */
    public function getCruise()
    {
        return $this->cruise;
    }

    /*
    * Getter for takeoff.
    */
    public function getTakeoff()
    {
        return $this->takeoff;
    }

    /*
    * Getter for hours.
    */
    public function getHourly()
    {
        return $this->hourly;
    }
}

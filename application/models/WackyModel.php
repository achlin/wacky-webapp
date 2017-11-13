<?php

/*
* A class for retrieving information from the Wacky servers
*/
class WackyModel extends CI_Model {

    /*
    * ctor.
    */
    public function __construct()
    {
        parent::__construct();
    }

    /*
    * Returns a full list of airlines from wacky servers.
    */
    public function getAirlines()
    {
        $response = json_decode(file_get_contents(WACKY_AIRLINES_URL));
    }

    /*
    * Returns a full list of airports from wacky servers.
    */
    public function getAirports()
    {
        $response = json_decode(file_get_contents(WACKY_AIRPORTS_URL));
        return $response;
    }

    /*
    * Returns a full list of airplanes from wacky servers.
    */
    public function getAirplanes()
    {
        $response = json_decode(file_get_contents(WACKY_AIRPLANES_URL));
        return $response;
    }

    /*
    * Returns a full list of regions from wacky servers.
    */
    public function getRegions()
    {
        $response = json_decode(file_get_contents(WACKY_REGIONS_URL));
        return $response;
    }

    /*
    * Retrieves the information for a single airline.
    * param $id - The String id of the airline.
    */
    public function getAirline(String $id)
    {
        $response = json_decode(file_get_contents(WACKY_AIRLINES_URL . $id));
    }

    /*
    * Retrieves the information for a single airport.
    * param $id - The String id of the airport.
    */
    public function getAirport(String $id)
    {
        $response = json_decode(file_get_contents(WACKY_AIRPORTS_URL . $id));
        return $response;
    }

    /*
    * Retrieves the information for a single plane.
    * param $id - The String id of the plane.
    */
    public function getAirplane(String $id)
    {
        $response = json_decode(file_get_contents(WACKY_AIRPLANES_URL . $id));
        return $response;
    }

    /*
    * Retrieves the information for a single region.
    * param $id - The String id of the region.
    */
    public function getRegion(String $id)
    {
        $response = json_decode(file_get_contents(WACKY_REGIONS_URL . $id));
        return $response;
    }
}

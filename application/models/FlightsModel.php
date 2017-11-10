<?php

/**
 * Dummy model class that represent our airline's flight schedule
 */
class FlightsModel extends CSV_Model
{

    // Constructor
    public function __construct()
    {
        parent::__construct(APPPATH . DATA_FLIGHTS, 'id');
    }

    // Gets the departure airport
    // requires that the airport model has been loaded
    public function getDepartureAirport($key)
    {
        if (isset($this->data[$key]))
        {
            $record = $this->data[$key];
            return $this->airports->get($record['departsFrom']);
        }
        return null;
    }

    // Gets the arrival airport
    // requires that the airport model has been loaded
    public function getArrivalAirport($key)
    {
        if (isset($this->data[$key]))
        {
            $record = $this->data[$key];
            return $this->airports->get($record['arrivesAt']);
        }
        return null;
    }

    // Gets the plane
    // Requires that the fleet model has been loaded
    public function getPlane($key)
    {
        if (isset($this->data[$key]))
        {
            $record = $this->data[$key];
            return $this->fleet->get($record['plane']);
        }
        return null;
    }

}

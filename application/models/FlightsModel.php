<?php

/**
 * Dummy model class that represent our airline's flight schedule
 */
class FlightsModel extends CI_Model
{
    // The mock flight data
    var $data = array(
        // L_caravan routes
        'L_YPRZMT' => array(
            'departsFrom' => 'L_YPR', //airport id
            'arrivesAt' => 'L_ZMT',   //airport id
            'departureTime' => '0800',
            'arrivalTime' => '0900',
            'plane' => 'L_caravan'),  //plane id
        'L_ZMTYZP' => array(          //... and so on
            'departsFrom' => 'L_ZMT',
            'arrivesAt' => 'L_YZP',
            'departureTime' => '1300',
            'arrivalTime' => '1400',
            'plane' => 'L_caravan'),
        'L_YZPYPR' => array(
            'departsFrom' => 'L_YZP',
            'arrivesAt' => 'L_YPR',
            'departureTime' => '1800',
            'arrivalTime' => '1900',
            'plane' => 'L_caravan'),

        // L_kingair routes
        'L_YPRYZP' => array(
            'departsFrom' => 'L_YPR',
            'arrivesAt' => 'L_YZP',
            'departureTime' => '0900',
            'arrivalTime' => '1000',
            'plane' => 'L_kingair'),
        'L_YZPZMT' => array(
            'departsFrom' => 'L_YZP',
            'arrivesAt' => 'L_ZMT',
            'departureTime' => '1400',
            'arrivalTime' => '1500',
            'plane' => 'L_kingair'),
        'L_ZMTYPR' => array(
            'departsFrom' => 'L_ZMT',
            'arrivesAt' => 'L_YPR',
            'departureTime' => '1900',
            'arrivalTime' => '2000',
            'plane' => 'L_kingair'),

        // pc12ng routes
        'L_YPRYXT1' => array(
            'departsFrom' => 'L_YPR',
            'arrivesAt' => 'L_YXT',
            'departureTime' => '0800',
            'arrivalTime' => '0900',
            'plane' => 'L_pc12ng'),
        'L_YXTYPR1' => array(
            'departsFrom' => 'L_YXT',
            'arrivesAt' => 'L_YPR',
            'departureTime' => '1300',
            'arrivalTime' => '1400',
            'plane' => 'L_pc12ng'),
        'L_YPRYXT2' => array(
            'departsFrom' => 'L_YPR',
            'arrivesAt' => 'L_YXT',
            'departureTime' => '1800',
            'arrivalTime' => '1900',
            'plane' => 'L_pc12ng'),
        'L_YXTYPR2' => array(
            'departsFrom' => 'L_YXT',
            'arrivesAt' => 'L_YPR',
            'departureTime' => '2000',
            'arrivalTime' => '2100',
            'plane' => 'L_pc12ng'),
    );

    // Constructor
    public function __construct()
    {
        parent::__construct();

        // inject each "record" key into the record itself, for ease of presentation
        foreach ($this->data as $key => $record)
        {
            $record['key'] = $key;
            $this->data[$key] = $record;
        }
    }

    // Get a single flight based off its id, or null if not found
    public function get($which)
    {
        return !isset($this->data[$which]) ? null : $this->data[$which];
    }

    // Get all the flights
    public function all()
    {
        return $this->data;
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

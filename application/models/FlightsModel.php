<?php

/**
 * Dummy model class that represent our airline's flight schedule
 */
class FlightsModel extends CI_Model
{
    // The mock flight data
    var $data = array(
        // Kcaravan routes
        'KYPRZMT' => array(
            'departsFrom' => 'KYPR', //airport id
            'arrivesAt' => 'KZMT',   //airport id
            'departureTime' => '0800',
            'arrivalTime' => '0900',
            'plane' => 'Kcaravan'),  //plane id
        'KZMTYZP' => array(          //... and so on
            'departsFrom' => 'KZMT',
            'arrivesAt' => 'KYZP',
            'departureTime' => '1300',
            'arrivalTime' => '1400',
            'plane' => 'Kcaravan'),
        'KYZPYPR' => array(
            'departsFrom' => 'KYZP',
            'arrivesAt' => 'KYPR',
            'departureTime' => '1800',
            'arrivalTime' => '1900',
            'plane' => 'Kcaravan'),

        // Kkingair routes
        'KYPRYZP' => array(
            'departsFrom' => 'KYPR',
            'arrivesAt' => 'KYZP',
            'departureTime' => '0900',
            'arrivalTime' => '1000',
            'plane' => 'Kkingair'),
        'KYZPZMT' => array(
            'departsFrom' => 'KYZP',
            'arrivesAt' => 'KZMT',
            'departureTime' => '1400',
            'arrivalTime' => '1500',
            'plane' => 'Kkingair'),
        'KZMTYPR' => array(
            'departsFrom' => 'KZMT',
            'arrivesAt' => 'KYPR',
            'departureTime' => '1900',
            'arrivalTime' => '2000',
            'plane' => 'Kkingair'),

        // Kpc12ng routes
        'KYPRYXT1' => array(
            'departsFrom' => 'KYPR',
            'arrivesAt' => 'KYXT',
            'departureTime' => '0800',
            'arrivalTime' => '0900',
            'plane' => 'Kpc12ng'),
        'KYXTYPR1' => array(
            'departsFrom' => 'KYXT',
            'arrivesAt' => 'KYPR',
            'departureTime' => '1300',
            'arrivalTime' => '1400',
            'plane' => 'Kpc12ng'),
        'KYPRYXT2' => array(
            'departsFrom' => 'KYPR',
            'arrivesAt' => 'KYXT',
            'departureTime' => '1800',
            'arrivalTime' => '1900',
            'plane' => 'Kpc12ng'),
        'KYXTYPR2' => array(
            'departsFrom' => 'KYXT',
            'arrivesAt' => 'KYPR',
            'departureTime' => '2000',
            'arrivalTime' => '2100',
            'plane' => 'Kpc12ng'),
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

    // Get number of flights
    public function size()
    {
        return count($this->data);
    }
}

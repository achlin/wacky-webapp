<?php

/**
 * Dummy model class that represent our airline's fleet
 */
class Fleet extends CI_Model
{

    // The mock plane data
    var $data = array(
        'L_kingair' => array(
            'manufacturer' => 'Beechcraft',
            'model' => 'King Air C90',
            'price' => '3900',
            'seats' => '12',
            'reach' => '2446',
            'cruise' => '500',
            'takeoff' => '1402',
            'hourly' => '990'),
        'L_pc12ng' => array(
            'manufacturer' => 'Pilatus',
            'model' => 'PC-12 NG',
            'price' => '3300',
            'seats' => '9',
            'reach' => '4147',
            'cruise' => '500',
            'takeoff' => '450',
            'hourly' => '727'),
        'L_caravan' => array(
            'manufacturer' => 'Cessna',
            'model' => 'Grand Caravan EX',
            'price' => '2300',
            'seats' => '14',
            'reach' => '1689',
            'cruise' => '340',
            'takeoff' => '660',
            'hourly' => '389')
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

    // Get a single plane based off its id, or null if not found
    public function get($which)
    {
        return !isset($this->data[$which]) ? null : $this->data[$which];
    }

    // Get all the planes
    public function all()
    {
        return $this->data;
    }

}

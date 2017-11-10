<?php

/**
 * Dummy model class that represents the airports our airline services
 */
class Airports extends CSV_Model
{

    // Constructor
    public function __construct()
    {
        parent::__construct(APPPATH . DATA_AIRPORTS, 'id');
    }

    // Get all the airports in an array
    public function allAsArray()
    {
        $airports = array();

        foreach ($this->all() as $airport)
        {
            $airports[$airport->id] = (array)$airport;
        }

        return $airports;
    }

}

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

}

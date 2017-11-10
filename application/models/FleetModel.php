<?php

/**
 * Dummy model class that represent our airline's fleet
 */
class FleetModel extends CSV_Model
{

    // Constructor
    public function __construct()
    {
        parent::__construct(APPPATH . DATA_FLEET, 'id');
    }

    // Get all the planes
    public function allAsArray()
    {
        $fleet = array();

        foreach ($this->all() as $plane)
        {
            $fleet[$plane->id] = (array)$plane;
        }

        return $fleet;
    }

    // Get a single plane based off its id, or null if not found
    public function getPlane($id)
    {
       foreach($this->allAsArray()  as $fleet)
       {
           if ($fleet['id'] == $id)
           {
               return $fleet;
           }
       }

       return null;
    }

}

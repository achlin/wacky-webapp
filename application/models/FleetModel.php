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

    /**
     * Returns all planes in Lighning Air's fleet
     */
    public function all()
    {
        $planes = parent::all();
        $planeIds = array_column($planes, 'airplaneCode');
        $wackyPlanes = $this->wackyModel->getAirplanes();

        $filteredPlanes = array_filter($wackyPlanes, function($p) use ($planeIds) {
            return in_array($p->id, $planeIds);
        });

        $fleet = array();

        foreach ($filteredPlanes as $wackyPlane)
        {
            foreach ($planes as $plane)
            {
                if ($plane->airplaneCode == $wackyPlane->id)
                {
                    $tempPlane = array();
                    $tempPlane['id']           = $plane->id;
                    $tempPlane['key']          = $plane->airplaneCode;
                    $tempPlane['model']        = $wackyPlane->model;
                    $tempPlane['manufacturer'] = $wackyPlane->manufacturer;
                    array_push($fleet, $tempPlane);
                }
            }
        }

        return $fleet;
    }

    /**
     * Returns one plane in Lightning Air's fleet
     */
    public function get($id, $key2 = null)
    {
        $plane = parent::get($id, $key2);
        $wackyPlane = $this->wackyModel->getAirplane($plane->airplaneCode);
        $wackyPlane->id = $id;
        return $wackyPlane;
    }

    public function validFleet($plane) {
        return $this->validBudget($plane);
    }

    /*
    * Validator for the planes in the fleet.
    * param $fleet - Array representing the airline's fleet
    * A fleet will be considered invalid and returns false if:
    *   - The sum of the cost the planes in the fleet is overbudget (10 million)
    */
    public function validBudget($plane) {
        $fleet = $this->all();
        $fleetPrice = 0;

        foreach($fleet as $kitePlane)
        {
            $wackyPlane = $this->wackyModel->getAirplane($kitePlane[key]);
            $fleetPrice += $wackyPlane->price;
        }
        $fleetPrice += $plane->price;
        
        return $fleetPrice <= 10000;
    }

}

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

        // can't update the planes returned by parent::all
        // or else the new properties get written to the CSV
        $result = array();

        foreach ($filteredPlanes as $wackyPlane)
        {
            foreach ($planes as $plane)
            {
                if ($plane->airplaneCode == $wackyPlane->id)
                {
                    $resPlane = $this->planeModel->create($plane->id, $plane->airplaneCode);
                    foreach (get_object_vars($wackyPlane) as $prop => $val)
                        if (!property_exists($plane, $prop))
                            $resPlane->$prop = $val;
                    $result[] = $resPlane;
                }
            }
        }
        return $result;
    }

    /**
     * Returns one plane in Lightning Air's fleet
     */
    public function get($id, $key2 = null)
    {
        $plane = parent::get($id, $key2);
        $wackyPlane = $this->wackyModel->getAirplane($plane->airplaneCode);

        // can't update the plane returned by parent::get
        // or else the new properties get written to the CSV
        $resPlane = $this->planeModel->create($plane->id, $plane->airplaneCode);

        foreach (get_object_vars($wackyPlane) as $prop => $val)
            if (!property_exists($plane, $prop))
                $resPlane->$prop = $val;

        return $resPlane;
    }

    /**
     * Gets a PlaneModel with the id provided
     */
    public function getDTO($id, $key2 = null)
    {
        $plane = parent::get($id, $key2 = null);
        return $this->planeModel->create($plane->id, $plane->airplaneCode);
    }

    /**
     * Validates the fleet
     */
    public function validFleet($plane) {
        return $this->validBudget($plane);
    }

    /**
     * Updates the provided plane in the CSV
     */
    public function update($plane)
    {
        if (!$this->validateUpdate($plane))
            throw new Exception("Plane change pushes us over budget");
        parent::update($plane);
    }

    /**
     * Adds the provided plane to the CSV
     */
    public function add($plane)
    {
        if (!$this->validateBudget($plane))
            throw new Exception("New plane puts us over budget");

        if (!$this->validateId($plane))
            throw new Exception("The id " . $plane->id . " already exists!");

        parent::add($plane);
    }

    /**
     * Validates that we would still be within our budget
     * if the provided plane was acquired
     */
    public function validateBudget($plane) {
        $fleet = $this->all();
        $newPlane = $this->wackyModel->getAirplane($plane->airplaneCode);
        $total = $newPlane->price;
        foreach ($fleet as $p)
        {
            $total += $p->price;
        }
        return $total < 10000;
    }

    /**
     * Validates that we would still be within our budget
     * if the provided plane was updated
     */
    public function validateUpdate($plane) {
        $fleet = $this->all();
        $newPlane = $this->wackyModel->getAirplane($plane->airplaneCode);
        $fleetPrice = 0;
        foreach ($fleet as $p)
        {
            if ($p->airplaneCode == $newPlane->id)
                $fleetPrice += $newPlane->price;
            else
                $fleetPrice += $p->price;
        }
        return $fleetPrice < 10000;
    }

    /**
     * Validates the that ID of the provided plane
     * does not exist in the database
     */
    public function validateId($plane)
    {
        $fleet = $this->all();
        foreach ($fleet as $p)
        {
            if ($p->id == $plane->id)
                return false;
        }
        return true;
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
            $fleetPrice += $kitePlane->price;
        }
        $fleetPrice += $plane->price;

        return $fleetPrice <= 10000;
    }
}

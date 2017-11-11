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
        $wackyPlanes = json_decode(file_get_contents('https://wacky.jlparry.com/info/airplanes'));
        $filteredPlanes = array_filter($wackyPlanes, function($p) use ($planeIds) {
            return in_array($p->id, $planeIds);
        });

        foreach ($filteredPlanes as $wackyPlane)
        {
            foreach ($planes as $plane)
            {
                if ($plane->airplaneCode == $wackyPlane->id) 
                {
                    foreach (get_object_vars($wackyPlane) as $prop => $val)
                        if (!property_exists($plane, $prop))
                            $plane->$prop = $val;
                }
            }
        }
        return $planes;
    }

    /**
     * Returns one plane in Lightning Air's fleet
     */
    public function get($id, $key2)
    {
        $plane = parent::get($id, $key2);
        $wackyPlane = json_decode(file_get_contents('https://wacky.jlparry.com/info/airplanes/' . $id));

        foreach (get_object_vars($wackyPlane) as $prop => $val)
            if (!property_exists($plane, $prop))
                $plane->$prop = $val;

        return $plane;
    }
}

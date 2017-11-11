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

        $this->loadPlanes();
    }

    private function loadPlanes()
    {
        $wackyPlanes = json_decode(file_get_contents('https://wacky.jlparry.com/info/airplanes'));

        foreach ($wackyPlanes as $wackyPlane)
        {
            foreach ($this->all() as $plane)
            {
                if ($plane->airplaneCode == $wackyPlane->id) {
                    foreach (get_object_vars($wackyPlane) as $prop => $val)
                        if ($prop != 'id')
                            $plane->$prop = $val;
                }
            }
        }
    }
}

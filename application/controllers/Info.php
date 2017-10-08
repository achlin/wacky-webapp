<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* The controller for the Info service.
*/
class Info extends Application
{

    /**
     * Flight info for this controller.
     */
    public function flights()
    {
        $record = $this->flightsModel->all();
        header("Content-type: application/json");
        echo json_encode($record);
    }

    /**
     * Flight info for this controller.
     */
    public function fleet()
    {
        $record = $this->fleetModel->all();
        header("Content-type: application/json");
        echo json_encode($record);
    }


}

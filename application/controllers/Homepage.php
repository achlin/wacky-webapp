<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Homepage extends Application
{

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->data['pagebody'] = 'homepage';
        $this->data['fleetsize'] = $this->fleetModel->size();
        $this->data['flightsize'] = $this->flightsModel->size();

        $baseid = $this->airports->getBaseAirportId();
        $airports = $this->airports->airportsWeService();
        $this->data['basecode'] = $airports[$baseid]->id;
        $this->data['baselocation'] = $airports[$baseid]->community;
        unset($airports[$baseid]);
        $this->data['airports'] = $airports;

        $this->render();
    }

}

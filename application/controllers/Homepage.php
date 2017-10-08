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
        $this->data['fleetsize'] = $this->fleet->size();
        $this->data['flightsize'] = $this->flights->size();

        $baseid = "L_YPR";
        $airports = $this->airports->all();
        $this->data['basecode'] = $airports[$baseid]['code'];
        $this->data['baselocation'] = $airports['L_YPR']['location'];
        unset($airports[$baseid]);
        $this->data['airports'] = $airports;
        
        $this->render();
    }

}

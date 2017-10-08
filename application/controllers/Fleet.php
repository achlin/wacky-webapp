<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
* The controller for the Fleet view.
*/
class Fleet extends Application
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->data['pagebody'] = 'fleet';
        $source = $this->fleetModel->all();
        $this->data['fleet'] = $source;
        $this->render();
    }

    public function show($key) {

        $this->data['pagebody'] = 'plane';

        // build the list of authors, to pass on to our view
        $source = $this->fleetModel->get($key);

        // pass on the data to present, adding the author record's fields
        $this->data = array_merge($this->data, (array) $source);

        $this->render();
    }
}

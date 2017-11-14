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

        $role = $this->session->userdata('userrole');

        $this->data['fleet'] = $source;
        $this->data['addLink'] = ($role == ROLE_ADMIN) ? $this->parser->parse('addplane', array(), true) : '';
        $this->render();
    }

    public function getPlane() {
        $get = $this->input->post();
        $response = $this->wackyModel->getAirplane($get['airplaneCode']);
        header("Content-type: application/json");
        echo json_encode($response);
    }

    /**
    * Changes the view to that of a single plane.  Adds the plane's information
    * to the available data.
    */
    public function show($id = null) {
        if($id === null)
            redirect('/flights');

        $role = $this->session->userdata('userrole');

        if($role === ROLE_ADMIN)
            $this->edit($id);
        else
            $this->displayPlane($id);
    }

    private function displayPlane($id) {
        $source = $this->fleetModel->get($id);
        $this->data = array_merge($this->data, (array) $source);

        $this->data['pagebody'] = 'plane';
        $this->render();
    }

    /**
     * GET for editing a plane.
     */
    public function edit($id = null)
    {
        $role = $this->session->userdata('userrole');
        if ($id == null || $role != ROLE_ADMIN)
            redirect('/fleet');
        $plane = $this->fleetModel->getDTO($id);

        $this->load->helper('form');

        // if no errors, pass an empty message
        if ( ! isset($this->data['error']))
            $this->data['error'] = '';

        $airplaneCodes = array_column($this->wackyModel->getAirplanes(), 'id');
        $this->session->set_userdata('codes', $airplaneCodes);
        $index = array_search($plane->airplaneCode, $airplaneCodes);

        $fields = array(
            'fid'      => form_input('id', $plane->id, 'class="form-control form-control-sm" readonly'),
            'fairplaneCode' => form_dropdown('airplaneCode', $airplaneCodes, $index, 'id="planeCodeSelect" class="form-control form-control-sm"'),
            'zsubmit'       => form_submit('submit', 'Save', 'class="btn btn-primary"'),
        );
        $this->data = array_merge($this->data, $fields);

        $this->data['action'] = '/flights/submit';
        $this->data['pagebody'] = 'editplane';
        $this->render();
    }

    /**
     * POST for handling edit plane
     */
    public function submit()
    {
        // retrieve & update data transfer buffer
        $post = $this->input->post();
        $codes = $this->session->userdata('codes');
        $code = $codes[$post['airplaneCode']];
        $plane = $this->planeModel->create($post['id'], $code);

        try {
            $this->fleetModel->update($plane);
        } catch (Exception $ex) {
            $this->data['error'] = "Error updating the record: " . $ex->getMessage();
            return $this->edit($plane->id);
        }

        redirect('/fleet');
    }

    /**
     * POST for adding new plane
     */
    public function submitAdd()
    {
        // retrieve & update data transfer buffer
        $post = $this->input->post();
        $codes = $this->session->userdata('codes');
        $code = $codes[$post['airplaneCode']];
        $plane = $this->planeModel->create($post['id'], $code);

        try {
            $this->fleetModel->add($plane);
        } catch (Exception $ex) {
            $this->data['error'] = "Error updating the record: " . $ex->getMessage();
            return $this->add($plane->id);
        }

        redirect('/fleet');
    }

    /**
     * GET for adding new plane
     */
    public function add()
    {
        $role = $this->session->userdata('userrole');
        if ($role != ROLE_ADMIN)
            redirect('/fleet');
        $plane = $this->data['plane'] ?? $this->planeModel->create();
        $this->session->set_userdata('plane', $plane);

        $this->load->helper('form');
        $task = $this->session->userdata('plane');

        // if no errors, pass an empty message
        if ( ! isset($this->data['error']))
            $this->data['error'] = '';

        $airplaneCodes = array_column($this->wackyModel->getAirplanes(), 'id');
        $this->session->set_userdata('codes', $airplaneCodes);
        $index = array_search($plane->airplaneCode, $airplaneCodes);

        $fields = array(
            'fid'      => form_input('id', $plane->id, 'class="form-control form-control-sm"'),
            'fairplaneCode' => form_dropdown('airplaneCode', $airplaneCodes, $index, 'id="planeCodeSelect" class="form-control form-control-sm"'),
            'zsubmit'       => form_submit('submit', 'Save', 'class="btn btn-primary"'),
        );
        $this->data = array_merge($this->data, $fields);
        $this->data['action'] = '/fleet/submitAdd';
        $this->data['pagebody'] = 'editplane';
        $this->render();
    }

    public function cancel() {
        redirect('/fleet');
    }
}

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
		//$this->render();
		$this->render();
	}

}

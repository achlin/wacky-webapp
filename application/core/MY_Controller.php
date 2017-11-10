<?php

/**
 * core/MY_Controller.php
 *
 * Default application controller
 *
 * @author		JLP
 * @copyright           2010-2016, James L. Parry
 * ------------------------------------------------------------------------
 */
class Application extends CI_Controller
{

	/**
	 * Constructor.
	 * Establish view parameters & load common helpers
	 */
	function __construct()
	{
		parent::__construct();

		//  Set basic view parameters
		$this->data = array();
		$this->data['pagetitle'] = 'Lightning Air';
		$this->data['ci_version'] = (ENVIRONMENT === 'development') ? 'CodeIgniter Version <strong>'.CI_VERSION.'</strong>' : '';
	}

    /**
     * Render this page
     */
    function render($template = 'template')
    {
        $menubar = $this->config->item('menu_choices');
        $menubar['rolesdropdown'] = $this->roleParam();
        $this->data['menubar'] = $this->parser->parse('_menubar', $menubar, true);
        $this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);
        $this->data['footer'] = $this->parser->parse('_footer', $this->data, true);
        $this->parser->parse('template', $this->data);
    }

    private function roleParam() {
        $roles = array();
        $curr_role = $this->session->userdata('userrole');
        if ($curr_role != ROLE_ADMIN) {
            $this->session->set_userdata('userrole',ROLE_GUEST);
            $roles['current_role'] = ROLE_GUEST;
            $roles['available_role'] = ROLE_ADMIN;
        } else {
            $roles['current_role'] = ROLE_ADMIN;
            $roles['available_role'] = ROLE_GUEST;
        }
        return $this->parser->parse('rolesdropdown', $roles, true);;
    }

}

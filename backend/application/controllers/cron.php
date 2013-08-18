<?php

class Cron extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...		
		$this->load->helper(array('language', 'url'));
		$this->load->library(array('account/authentication'));
		$this->load->model(array('tripdocs/acl_subdomain_model'));
		
	}

	function index()
	{
	    // Scan for new/deleted subdomains
		$this->acl_subdomain_model->scan_for_subdomains();
		
		// Delete cookies that are older than the defined time limit for the subdomains
		$this->acl_subdomain_model->clean_cookies();		
		
		$this->load->view('cron', NULL);
	}

}


/* End of file cron.php */
/* Location: ./system/application/controllers/cron.php */
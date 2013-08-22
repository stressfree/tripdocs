<?php
/*
 * Sign_out Controller
 */
class Sign_out extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('url'));
		$this->load->config('account/account');
		$this->load->library(array('account/authentication', 'account/authorization'));
	}

	// --------------------------------------------------------------------

	/**
	 * Account sign out
	 *
	 * @access public
	 * @return void
	 */
	function index()
	{
		// Redirect signed out users to homepage
		if ( ! $this->authentication->is_signed_in()) redirect('');

		// Run sign out routine
		$this->authentication->sign_out();

		// Redirect to homepage
		redirect('');
	}

}


/* End of file sign_out.php */
/* Location: ./application/account/controllers/sign_out.php */
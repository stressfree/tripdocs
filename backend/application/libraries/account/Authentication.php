<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authentication {

	var $CI;

	/**
	 * Constructor
	 */
	function __construct()
	{
		// Obtain a reference to the ci super object
		$this->CI =& get_instance();
		
		$this->CI->load->library('session');
    	$this->CI->load->config('tripdocs');
	}

	// --------------------------------------------------------------------

	/**
	 * Check user signin status
	 *
	 * @access public
	 * @return bool
	 */
	function is_signed_in()
	{
		return $this->CI->session->userdata('account_id') ? TRUE : FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Sign user in
	 *
	 * @access public
	 * @param int  $account_id
	 * @param bool $remember
	 * @return void
	 */
	function sign_in($account_id, $remember = FALSE)
	{		
		$remember ? $this->CI->session->cookie_monster(TRUE) : $this->CI->session->cookie_monster(FALSE);
		
		$this->tripdocs_login($account_id);

		$this->CI->session->set_userdata('account_id', $account_id);

		$this->CI->load->model('account/account_model');

		$this->CI->account_model->update_last_signed_in_datetime($account_id);

		// Redirect signed in user with session redirect
		if ($redirect = $this->CI->session->userdata('sign_in_redirect'))
		{
			$this->CI->session->unset_userdata('sign_in_redirect');
			redirect($redirect);
		}
		// Redirect signed in user with GET continue
		elseif ($this->CI->input->get('continue'))
		{
			redirect($this->CI->input->get('continue'));
		}

		redirect('');
	}

	// --------------------------------------------------------------------

	/**
	 * Sign user out
	 *
	 * @access public
	 * @return void
	 */
	function sign_out()
	{
		$this->CI->session->unset_userdata('account_id');
		
		$this->tripdocs_logout();
	}

	// --------------------------------------------------------------------

	/**
	 * Check password validity
	 *
	 * @access public
	 * @param object $account
	 * @param string $password
	 * @return bool
	 */
	function check_password($password_hash, $password)
	{
		$this->CI->load->helper('account/phpass');

		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);

		return $hasher->CheckPassword($password, $password_hash) ? TRUE : FALSE;
	}

    function tripdocs_login($account_id)
    {
		
		$cookie_value = hash('ripemd160', $_SERVER['SERVER_ADDR'] . '_' . $this->CI->session->userdata('session_id'));
		
    	$this->CI->load->model('tripdocs/acl_subdomain_model');
    	$subdomains = $this->CI->acl_subdomain_model->get_by_account_id($account_id);
    			
		$ds = DIRECTORY_SEPARATOR;
		
		foreach ($subdomains as $subdomain) {
			$dir = $this->CI->config->item('tripdocs_cookiedir') . $ds . $subdomain->name;
			
			// If a file exists with the same name but it is not a directory then delete		
			if (file_exists($dir) and is_file($dir)) {
    			unlink($dir);         
			}
			
			// If the name directory does not exist create it	
			if (!file_exists($dir)) {
    			mkdir($dir);         
			}
			
			// Touch a file in the specified directories with the cookie value
			touch($dir . $ds . $cookie_value);
		}
		
		// Store the cookie details
		$cookie = array(
    		'name'   => 'tripdocs_login',
    		'value'  => $cookie_value,
    		'expire' => $this->CI->config->item('tripdocs_expire'),
    		'domain' => $this->CI->config->item('tripdocs_domain'),
    		'path'   => '/',
    		'prefix' => '',
    		'secure' => FALSE
		);
		$this->CI->input->set_cookie($cookie);
    }
    
    function tripdocs_logout()
    {    	
    	$this->CI->load->config('tripdocs');
    	
    	$cookie_value = $this->CI->input->cookie('tripdocs_login', TRUE);
    	
    	// Delete all instances of this file in the cookie filesystem
    	if ( !empty($cookie_value) ) {
			$path = $this->CI->config->item('tripdocs_cookiedir');
			$results = scandir($path);
		
			foreach ($results as $result) {
    			if ($result === '.' or $result === '..') continue;

                $ds = DIRECTORY_SEPARATOR;
    	
    			if (is_dir($path . $ds . $result)) {
    				if (file_exists($path . $ds . $result . $ds . $cookie_value)) {
    				    unlink($path . $ds . $result . $ds . $cookie_value);
    				}
    			}
    		}
    	}
    	
    	// Expire the cookie
		$cookie = array(
    		'name'   => 'tripdocs_login',
    		'value'  => '',
    		'expire' => '',
    		'domain' => $this->CI->config->item('tripdocs_domain'),
    		'path'   => '/',
    		'prefix' => '',
    		'secure' => FALSE
		);
		$this->CI->input->set_cookie($cookie);	 	
    }

}


/* End of file Authentication.php */
/* Location: ./application/account/libraries/Authentication.php */
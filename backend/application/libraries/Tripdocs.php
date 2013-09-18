<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tripdocs {

	var $CI;

	/**
	 * Constructor
	 */
	function __construct()
	{
		// Obtain a reference to the ci super object
		$this->CI =& get_instance();
		
    	$this->CI->load->config('tripdocs');
		$this->CI->load->library('session');
		$this->CI->load->model('tripdocs/acl_subdomain_model');
	}
	
	function redirect($redirect_name = NULL, $account_id = NULL)
	{
    	if (isset($redirect_name) && isset($account_id))
    	{
    	    if ($this->CI->acl_subdomain_model->has_subdomain($redirect_name, $account_id))
            {
    	        // The user has been granted access to this subdomain
                $redirect = $this->CI->config->item("tripdocs_protocol") . "://" . $redirect_name 
                    . $this->CI->config->item("tripdocs_domain");
                redirect($redirect);
            }
            else
            {
                // The user has not been granted access to this subdomain
                $this->CI->session->set_flashdata('flash_error', lang('website_share_access_denied'));
            }
        }
    	redirect('');
	}

    function sign_in($account_id)
    {
        // Remove any existing Tripdoc cookies before signing in
        $this->sign_out();
		
		$cookie_value = hash('ripemd160', $_SERVER['SERVER_ADDR'] . '_' . $this->CI->session->userdata('session_id'));
		
    	$this->CI->load->model('account/account_model');
    	$this->CI->load->model('tripdocs/acl_subdomain_model');
    	
    	$account = $this->CI->account_model->get_by_id($account_id);
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
		$auth_cookie = array(
    		'name'   => 'tripdocs_login',
    		'value'  => $cookie_value,
    		'expire' => $this->CI->config->item('tripdocs_expire'),
    		'domain' => $this->CI->config->item('tripdocs_domain'),
    		'path'   => '/',
    		'prefix' => '',
    		'secure' => FALSE
		);		
		$this->CI->input->set_cookie($auth_cookie);
		
		$id_cookie = array(
    		'name'   => 'tripdocs_id',
    		'value'  => $account->username,
    		'expire' => $this->CI->config->item('tripdocs_expire'),
    		'domain' => $this->CI->config->item('tripdocs_domain'),
    		'path'   => '/',
    		'prefix' => '',
    		'secure' => FALSE
		);
		$this->CI->input->set_cookie($id_cookie);
    }
    
    function sign_out()
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
		$auth_cookie = array(
    		'name'   => 'tripdocs_login',
    		'value'  => '',
    		'expire' => '',
    		'domain' => $this->CI->config->item('tripdocs_domain'),
    		'path'   => '/',
    		'prefix' => '',
    		'secure' => FALSE
		);
		$this->CI->input->set_cookie($auth_cookie);
				
        $id_cookie = array(
    		'name'   => 'tripdocs_id',
    		'value'  => '',
    		'expire' => '',
    		'domain' => $this->CI->config->item('tripdocs_domain'),
    		'path'   => '/',
    		'prefix' => '',
    		'secure' => FALSE
		);
		$this->CI->input->set_cookie($id_cookie);
    }

}


/* End of file Authentication.php */
/* Location: ./application/account/libraries/Authentication.php */
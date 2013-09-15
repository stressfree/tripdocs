<?php

class Share extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->config('account/account', 'tripdocs');
		$this->load->helper(array('language', 'url', 'form', 'account/ssl'));
		$this->load->library(array('account/authentication', 'account/authorization', 'typography'));
		$this->load->model(array('tripdocs/acl_subdomain_model', 'tripdocs/rel_account_subdomain_model', 'account/account_model'));
		$this->load->language(array('share'));
	}

	function key($key=null)
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));

		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('account/sign_in/?continue='.urlencode(base_url()));
		}

		$account = $this->account_model->get_by_id($this->session->userdata('account_id'));
        $salt = $this->config->item('tripdocs_salt');  
        $secret = $this->config->item('tripdocs_secret');
		
		// Determine the subdomain to connect with this user
		if (isset($key))
		{
    		$result = $this->acl_subdomain_model->decryptShareKey($key, $salt, $secret);
    		
    		if ($result['subdomain_id'] > 0 && $result['account_id'] > 0)
    		{
    		    // Load the subdomain
    		    $subdomain = $this->acl_subdomain_model->get_by_id($result['subdomain_id']);
    		        		    
    		    // Load the sharer account
    		    $sharer = $this->account_model->get_by_id($result['account_id']);
    		    
    		    if (isset($subdomain) && isset($sharer))
    		    {
    		        if ( $this->rel_account_subdomain_model->exists($sharer->id, $subdomain->id) > 0 )
    		        {
        		        $this->rel_account_subdomain_model->update($account->id, $subdomain->id);
        		        
                        // Redirect to the subdomain
                        $protocol = $this->config->item('tripdocs_protocol');
                        $domain = $this->config->item('tripdocs_domain');
        
                        redirect(strtolower($protocol . '://' . $subdomain->name . $domain));
                    }
    		        else
    		        {
        		        $this->session->set_flashdata('flash_info', lang('share_invalid_key'));
    		        }
    		    }
    		    else
    		    {
        	        $this->session->set_flashdata('flash_info', lang('share_outdated'));
    		    }
    		}
    		else
    		{
        	    $this->session->set_flashdata('flash_info', lang('share_invalid_key'));
    		}	
		}
		
		redirect('');
	}

}


/* End of file share.php */
/* Location: ./application/controllers/share.php */
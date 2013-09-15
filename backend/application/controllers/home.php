<?php

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->config('account/account', 'tripdocs');
		$this->load->helper(array('language', 'url', 'form', 'account/ssl'));
		$this->load->library(array('account/authentication', 'account/authorization', 'typography'));
		$this->load->model(array('tripdocs/acl_subdomain_model', 'account/account_model', 'account/account_details_model', 'account/account_facebook_model', 'account/account_twitter_model', 'account/account_openid_model'));
		$this->load->language(array('home', 'account/settings', 'account/connect_third_party', 'account/sign_in'));
	}

	function index()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));

		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('account/sign_in/?continue='.urlencode(base_url()));
		}

        $account_id = $this->session->userdata('account_id');
        
		$data['account'] = $this->account_model->get_by_id($account_id);
		$data['account_details'] = $this->account_details_model->get_by_account_id($account_id);
        
        // Get the subdomains for the user
        $subdomains = $this->acl_subdomain_model->get_by_account_id($account_id);
        $protocol = $this->config->item('tripdocs_protocol');
        $domain = $this->config->item('tripdocs_domain');
        $subdomaindir = $this->config->item('tripdocs_subdomaindir');
        $salt = $this->config->item('tripdocs_salt');  
        $secret = $this->config->item('tripdocs_secret');
        
        $data['upload_server'] = $this->config->item('tripdocs_upload_server');
        
        $data['subdomains'] = array();
        
        foreach ( $subdomains as $sub )
        {
          $current_sub = array();
          $current_sub['id'] = $sub->id;
          $current_sub['name'] = $sub->name;
          $current_sub['description'] = $this->typography->nl2br_except_pre($sub->description);
          $current_sub['url'] = strtolower($protocol . '://' . $sub->name . $domain);
          $current_sub['dir'] = $subdomaindir . DIRECTORY_SEPARATOR . $sub->name;
          $current_sub['shareUrl'] = base_url('share/key/' . $this->acl_subdomain_model->generateShareKey($sub->id, $account_id, $salt, $secret));
              
          // Append to the array
          $data['subdomains'][] = $current_sub;
        }
        
        // Check for linked accounts
		$data['num_of_linked_accounts'] = 0;

		// Get Facebook accounts
		if ($data['facebook_links'] = $this->account_facebook_model->get_by_account_id($account_id))
		{
			foreach ($data['facebook_links'] as $index => $facebook_link)
			{
				$data['num_of_linked_accounts'] ++;
			}
		}

		// Get Twitter accounts
		if ($data['twitter_links'] = $this->account_twitter_model->get_by_account_id($account_id))
		{
			$this->load->config('account/twitter');
			$this->load->helper('account/twitter');
			foreach ($data['twitter_links'] as $index => $twitter_link)
			{
				$data['num_of_linked_accounts'] ++;
				$epiTwitter = new EpiTwitter($this->config->item('twitter_consumer_key'), $this->config->item('twitter_consumer_secret'), $twitter_link->oauth_token, $twitter_link->oauth_token_secret);
				$data['twitter_links'][$index]->twitter = $epiTwitter->get_usersShow(array('user_id' => $twitter_link->twitter_id));
			}
		}

		// Get OpenID accounts
		if ($data['openid_links'] = $this->account_openid_model->get_by_account_id($account_id))
		{
			foreach ($data['openid_links'] as $index => $openid_link)
			{
				if (strpos($openid_link->openid, 'google.com')) $data['openid_links'][$index]->provider = 'google';
				elseif (strpos($openid_link->openid, 'yahoo.com')) $data['openid_links'][$index]->provider = 'yahoo';
				elseif (strpos($openid_link->openid, 'myspace.com')) $data['openid_links'][$index]->provider = 'myspace';
				elseif (strpos($openid_link->openid, 'aol.com')) $data['openid_links'][$index]->provider = 'aol';
				else $data['openid_links'][$index]->provider = 'openid';

				$data['num_of_linked_accounts'] ++;
			}
		}

		$this->load->view('home', isset($data) ? $data : NULL);
	}

}


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
<?php
/*
 * Connect_create Controller
 */
class Create extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->config('account/account');
		$this->load->helper(array('language', 'account/ssl', 'url'));
		$this->load->library(array('account/authentication', 'account/authorization', 'form_validation'));
		$this->load->model(array('account/account_model', 'account/account_details_model', 'account/account_facebook_model', 'account/account_twitter_model', 'account/account_openid_model'));
		$this->load->language(array('general', 'account/connect_third_party'));
	}

	/**
	 * Complete facebook's authentication process
	 *
	 * @access public
	 * @return void
	 */
	function index()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));

		// Redirect user to home if sign ups are disabled
		if ( ! ($this->config->item("sign_up_enabled"))) redirect('');

		// Redirect user to home if 'connect_create' session data doesn't exist
		if ( ! $this->session->userdata('connect_create')) redirect('');

		$data['connect_create'] = $this->session->userdata('connect_create');
		
		if ( !isset($data['connect_create'][1]['fullname']))
		{
    		if ( isset($data['connect_create'][1]['firstname']) && isset($data['connect_create'][1]['lastname']))
    		{
        	    $data['connect_create'][1]['fullname'] = $data['connect_create'][1]['firstname'] . ' ' . $data['connect_create'][1]['lastname'];
    		}    		
		}
		
		// Setup form validation
		$this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
		$this->form_validation->set_rules(array(array('field' => 'connect_create_fullname', 'label' => 'lang:connect_create_fullname', 'rules' => 'trim|required|max_length[160]'), array('field' => 'connect_create_email', 'label' => 'lang:connect_create_email', 'rules' => 'trim|required|valid_email|max_length[160]')));

		// Run form validation
		if ($this->form_validation->run())
		{
			// Check if email already exist
			if ($this->email_check($this->input->post('connect_create_email'), TRUE) === TRUE)
			{
				$data['connect_create_email_error'] = lang('connect_create_email_exist');
			}
			else
			{
				// Destroy 'connect_create' session data
				$this->session->unset_userdata('connect_create');

				// Create user
				$user_id = $this->account_model->create($this->generateUsername(), $this->input->post('connect_create_email', TRUE));

                $accountDetails = array();                
                $accountDetails['fullname'] = $this->input->post('connect_create_fullname', TRUE);

				// Add user details
				$this->account_details_model->update($user_id, $accountDetails);

				// Connect third party account to user
				switch ($data['connect_create'][0]['provider'])
				{
					case 'facebook':
						$this->account_facebook_model->insert($user_id, $data['connect_create'][0]['provider_id']);
						break;
					case 'twitter':
						$this->account_twitter_model->insert($user_id, $data['connect_create'][0]['provider_id'], $data['connect_create'][0]['token'], $data['connect_create'][0]['secret']);
						break;
					case 'openid':
						$this->account_openid_model->insert($data['connect_create'][0]['provider_id'], $user_id);
						break;
				}

				// Run sign in routine
				$this->authentication->sign_in($user_id);
			}
		}

		$this->load->view('account/create', isset($data) ? $data : NULL);
	}
	
	/**
	 *
	 * Generate a random username
	 *
	 * @access public
	 * @return string
	 */
    function generateUsername()
    {
        return hash('ripemd160', $_SERVER['SERVER_NAME'] . '_' . time());
    }

	/**
	 * Check if an email exist
	 *
	 * @access public
	 * @param string
	 * @return bool
	 */
	function email_check($email)
	{
		return $this->account_model->get_by_email($email) ? TRUE : FALSE;
	}

}

/* End of file connect_create.php */
/* Location: ./application/account/controllers/connect_create.php */

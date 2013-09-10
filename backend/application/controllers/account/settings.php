<?php
/*
 * Account_settings Controller
 */
class Settings extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->config('account/account');
		$this->load->helper(array('date', 'language', 'account/ssl', 'url'));
		$this->load->library(array('account/authentication', 'account/authorization', 'form_validation'));
		$this->load->model(array('account/account_model', 'account/account_details_model', 'account/ref_country_model', 'account/ref_language_model', 'account/ref_zoneinfo_model'));
		$this->load->language(array('general', 'account/settings'));
	}

	/**
	 * Account settings
	 */
	function index()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));

		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('');
		}
        
        $account_id = $this->session->userdata('account_id');
        $delete = $this->input->post('settings_delete', TRUE);
        
        if( empty($delete) )
        {
            // Update the account
            
		    // Retrieve signed in user
            $data['account'] = $this->account_model->get_by_id($account_id);
            $data['account_details'] = $this->account_details_model->get_by_account_id($account_id);
            
            // Setup form validation
    		$this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
    		$this->form_validation->set_rules(array(
    		    array('field' => 'settings_email', 'label' => 'lang:settings_email', 'rules' => 'trim|required|valid_email|max_length[160]|edit_unique[a3m_account.email.'. $account_id .']'), 
    		    array('field' => 'settings_fullname', 'label' => 'lang:settings_fullname', 'rules' => 'trim|required|max_length[160]'))
            );
    
    		// Run form validation
    		if ($this->form_validation->run())
    		{
    			// Update account email
                $this->account_model->update_email($data['account']->id, $this->input->post('settings_email', TRUE) ? $this->input->post('settings_email', TRUE) : NULL);

				// Update account details
				$attributes['fullname'] = $this->input->post('settings_fullname', TRUE) ? $this->input->post('settings_fullname', TRUE) : NULL;
				$this->account_details_model->update($data['account']->id, $attributes);

                $this->session->set_flashdata('flash_info', lang('settings_details_updated'));
    		}
    		
    		if (validation_errors()) {
    		    $this->session->set_flashdata('flash_error', validation_errors());   		
    		}
        }
        else
        {
            // Delete the account
            
            // Run sign out routine
            $this->authentication->sign_out();
            
            // Delete the account entry
            $this->account_model->delete($account_id);
        }

		redirect('');
	}
}


/* End of file account_settings.php */
/* Location: ./application/account/controllers/account_settings.php */
<?php
/*
 * Account_linked Controller
 */
class Account_linked extends CI_Controller {

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
		$this->load->model(array('account/account_model', 'account/account_facebook_model', 'account/account_twitter_model', 'account/account_openid_model'));
		$this->load->language(array('general', 'account/account_linked', 'account/connect_third_party'));
	}

	/**
	 * Linked accounts
	 */
	function index()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));

		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('account/sign_in/?continue='.urlencode(base_url().'account/account_linked'));
		}

		// Retrieve sign in user
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

		// Delete a linked account
		if ($this->input->post('facebook_id') || $this->input->post('twitter_id') || $this->input->post('openid'))
		{
			if ($this->input->post('facebook_id')) $this->account_facebook_model->delete($this->input->post('facebook_id', TRUE));
			elseif ($this->input->post('twitter_id')) $this->account_twitter_model->delete($this->input->post('twitter_id', TRUE));
			elseif ($this->input->post('openid')) $this->account_openid_model->delete($this->input->post('openid', TRUE));
			$this->session->set_flashdata('flash_info', lang('linked_linked_account_deleted'));
		}
		redirect('');
	}

}


/* End of file account_linked.php */
/* Location: ./application/account/controllers/account_linked.php */
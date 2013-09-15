<?php
/*
 * Manage_subdomains Controller
 */
class Manage_subdomains extends CI_Controller {

  /**
   * Constructor
   */
  function __construct()
  {
    parent::__construct();

    // Load the necessary stuff...
    $this->load->config('account/account');
    $this->load->helper(array('date', 'language', 'account/ssl', 'url'));
    $this->load->library(array('account/authentication', 'account/authorization', 'typography', 'form_validation'));
    $this->load->model(array('account/account_model', 'tripdocs/acl_subdomain_model'));
    $this->load->language(array('general', 'admin/manage_subdomains', 'account/settings'));
  }

  /**
   * Manage Subdomains
   */
  function index()
  {
    // Enable SSL?
    maintain_ssl($this->config->item("ssl_enabled"));

    // Redirect unauthenticated users to signin page
    if ( ! $this->authentication->is_signed_in())
    {
      redirect('account/sign_in/?continue='.urlencode(base_url().'account/manage_users'));
    }

    // Redirect unauthorized users to account profile page
    if ( ! $this->authorization->is_permitted('retrieve_subdomains'))
    {
      redirect('');
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

    // Get all subdomain information
    $all_subdomains = $this->acl_subdomain_model->get();

    // Compile an array for the view to use
    $data['all_subdomains'] = array();
    foreach ( $all_subdomains as $sub )
    {
      $current_sub = array();
      $current_sub['id'] = $sub->id;
      $current_sub['name'] = $sub->name;
      $current_sub['description'] = $this->typography->nl2br_except_pre($sub->description);
      $current_sub['all_access'] = $sub->all_access;

      // Append to the array
      $data['all_subdomains'][] = $current_sub;
    }

    // Load manage subdomains view
    $this->load->view('admin/manage_subdomains', $data);
  }

  /**
   * Create/Update Subdomains
   */
  function save($id=null)
  {
    // Keep track if this is a new subdomain
    $is_new = empty($id);

    // Enable SSL?
    maintain_ssl($this->config->item("ssl_enabled"));

    // Redirect unauthenticated users to signin page
    if ( ! $this->authentication->is_signed_in())
    {
      redirect('account/sign_in/?continue='.urlencode(base_url().'account/manage_users'));
    }

    // Check if they are allowed to Update Subdomains
    if ( ! $this->authorization->is_permitted('update_subdomains') && ! empty($id) )
    {
      redirect('admin/manage_subdomains');
    }

    // Check if they are allowed to Create Subdomains
    if ( ! $this->authorization->is_permitted('create_subdomains') && empty($id) )
    {
      redirect('admin/manage_subdomains');
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

    // Set action type (create or update subdomain)
    $data['action'] = 'create';

    // Get the subdomain to update
    if( ! $is_new )
    {
      $data['subdomain'] = $this->acl_subdomain_model->get_by_id($id);
      $data['action'] = 'update';
    }
    
    $delete_domain = $this->input->post('manage_subdomain_delete', TRUE); 
    if( ! empty($delete_domain) )
    {
        if( ! empty($id) && $this->authorization->is_permitted('delete_subdomains') ) 
        {
        	// Delete the subdomain
        	$this->acl_subdomain_model->delete($id);
        	
        	$this->session->set_flashdata('flash_info', lang('subdomains_delete_success'));
        	
			// Redirect to view the manage subdomain page
			redirect("admin/manage_subdomains");
        }
    }
    else
    {
     	// Create or update the subdomain
		// Setup form validation
		$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
		$this->form_validation->set_rules(
		  array(
			array(
			  'field' => 'subdomain_name',
			  'label' => 'lang:subdomains_name',
			  'rules' => 'trim|required|alpha_dash|max_length[50]|edit_unique[tripdocs_acl_subdomain.name.'. $id .']')
		  ));

		// Run form validation
		if ($this->form_validation->run())
		{
            // Update or create the subdomain record
			$attributes = array(
				'name' => $this->input->post('subdomain_name', TRUE),
				'description' => $this->input->post('subdomain_description', TRUE), 
				'all_access' => $this->input->post('subdomain_all_access'),       	
			);
			$id = $this->acl_subdomain_model->update($id, $attributes);
		
            if ( $is_new )
            {
                $this->session->set_flashdata('flash_info', lang('subdomains_create_success'));
            }
            else
            {
                $this->session->set_flashdata('flash_info', lang('subdomains_update_success'));
            }
            
			// Redirect to view the manage subdomain page
            redirect("admin/manage_subdomains");
		}
	}

    // Load manage subdomains view
    $this->load->view('admin/manage_subdomains_save', $data);
  }

  /**
   * Filter the user list by permission or role.
   *
   * @access public
   * @param string $type (permission, role)
   * @param int $id (permission_id, role_id)
   * @return void
   */
  function filter($type=null,$id=null)
  {
    $this->index();
  }
}

/* End of file manage_subdomains.php */
/* Location: ./application/controllers/admin/manage_subdomains.php */

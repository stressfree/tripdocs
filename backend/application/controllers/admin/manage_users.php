<?php
/*
 * Manage_users Controller
 */
class Manage_users extends CI_Controller {

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
    $this->load->model(array('account/account_model', 'account/account_details_model', 'account/acl_permission_model', 'account/acl_role_model', 'account/rel_account_permission_model', 'account/rel_account_role_model', 'account/rel_role_permission_model', 'tripdocs/acl_subdomain_model', 'tripdocs/rel_account_subdomain_model'));
    $this->load->language(array('general', 'admin/manage_users', 'account/settings'));
  }

  /**
   * Manage Users
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
    if ( ! $this->authorization->is_permitted('retrieve_users'))
    {
      redirect('');
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

    // Get all user information
    $all_accounts = $this->account_model->get();
    $all_account_details = $this->account_details_model->get();
    $all_account_roles = $this->rel_account_role_model->get();
    $all_account_subdomains = $this->rel_account_subdomain_model->get();
    $admin_role = $this->acl_role_model->get_by_name('Admin');

    // Compile an array for the view to use
    $data['all_accounts'] = array();
    foreach ( $all_accounts as $acc )
    {
      $current_user = array();
      $current_user['id'] = $acc->id;
      $current_user['username'] = $acc->username;
      $current_user['email'] = $acc->email;
      $current_user['fullname'] = '';
      $current_user['is_admin'] = FALSE;

      foreach( $all_account_details as $det ) 
      {
        if( $det->account_id == $acc->id ) 
        {
          $current_user['fullname'] = $det->fullname;
        }
      }

      foreach( $all_account_roles as $acrole ) 
      {
        if( $acrole->account_id == $acc->id && $acrole->role_id == $admin_role->id ) 
        {
          $current_user['is_admin'] = TRUE;
          break;
        }
      }

      // Append to the array
      $data['all_accounts'][] = $current_user;
    }

    // Load manage users view
    $this->load->view('admin/manage_users', $data);
  }

  /**
   * Update Users
   */
  function save($id=null)
  {
    // Enable SSL?
    maintain_ssl($this->config->item("ssl_enabled"));

    // Redirect unauthenticated users to signin page
    if ( ! $this->authentication->is_signed_in())
    {
      redirect('account/sign_in/?continue='.urlencode(base_url().'account/manage_users'));
    }

    // Check if they are allowed to Update Users
    if ( ! $this->authorization->is_permitted('update_users') && ! empty($id) )
    {
      redirect('admin/manage_users');
    }
    
    // Only support updating users, as it is assumed new users will always self register.
    if ( empty($id) )
    {
      redirect('admin/manage_users');        
    }

    // Check if they are allowed to Create Users
    if ( ! $this->authorization->is_permitted('create_users') && empty($id) )
    {
      redirect('admin/manage_users');
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

    // Get all the roles
    $data['roles'] = $this->acl_role_model->get();
    
    // Get all the unrestricted subdomains
    $data['unrestricted_subdomains'] = $this->acl_subdomain_model->get_unrestricted();
    
    // Get all the restricted subdomains
    $data['restricted_subdomains'] = $this->acl_subdomain_model->get_restricted();

    // Set action type (create or update user)
    $data['action'] = 'create';

    // Get the account to update
    $data['update_account'] = $this->account_model->get_by_id($id);
    $data['update_account_details'] = $this->account_details_model->get_by_account_id($id);
    $data['update_account_roles'] = $this->acl_role_model->get_by_account_id($id);
    $data['update_account_subdomains'] = $this->acl_subdomain_model->get_by_account_id($id);
    $data['action'] = 'update';

    // Setup form validation
    $this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
    $this->form_validation->set_rules(
      array(
        array(
          'field' => 'users_email', 
          'label' => 'lang:settings_email', 
          'rules' => 'trim|required|valid_email|max_length[160]|edit_unique[a3m_account.email.'. $id .']'), 
        array(
          'field' => 'users_fullname', 
          'label' => 'lang:settings_fullname', 
          'rules' => 'trim|max_length[160]'), 
        ));

    $user_delete = $this->input->post('manage_user_delete', TRUE);
    
    if ( !empty($user_delete) )
    {
        // Delete the user if allowed
        if( !empty($id) && $this->authorization->is_permitted('delete_users') )
        {
            $this->account_model->delete($id);
            
            $this->session->set_flashdata('flash_info', lang('users_delete_success'));
                    
            // Redirect to view the manage users page
            redirect("admin/manage_users");
        }
    }
    else
    {
        // Run form validation
        if ($this->form_validation->run())
        {
            // Modify the user
            
            // Update account email
            $this->account_model->update_email($id, 
                $this->input->post('users_email', TRUE) ? $this->input->post('users_email', TRUE) : NULL);
    
            // Update account details
            $attributes = array();
            $attributes['fullname'] = $this->input->post('users_fullname', TRUE) ? $this->input->post('users_fullname', TRUE) : NULL;
            $this->account_details_model->update($id, $attributes);
    
            // Apply roles
            $roles = array();
            foreach($data['roles'] as $r)
            {
              if( $this->input->post("account_role_{$r->id}", TRUE) )
              {
                $roles[] = $r->id;
              }
            }
            $this->rel_account_role_model->delete_update_batch($id, $roles);
            
            // Apply subdomains
            $subdomains = array();
            foreach($data['restricted_subdomains'] as $s)
            {
              if( $this->input->post("account_subdomain_{$s->id}", TRUE) )
              {
                $subdomains[] = $s->id;
              }
            }
            $this->rel_account_subdomain_model->delete_update_batch($id, $subdomains);
            
            $this->session->set_flashdata('flash_info', lang('users_update_success'));
        
            // Redirect to view the manage users page
            redirect("admin/manage_users");
        }
    }

    // Load manage users view
    $this->load->view('admin/manage_users_save', $data);
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

/* End of file manage_users.php */
/* Location: ./application/account/controllers/manage_users.php */

<?php
/*
 * Manage_roles Controller
 */
class Manage_roles extends CI_Controller {

  /**
   * Constructor
   */
  function __construct()
  {
    parent::__construct();

    // Load the necessary stuff...
    $this->load->config('account/account');
    $this->load->helper(array('date', 'language', 'url'));
    $this->load->library(array('account/authentication', 'account/authorization', 'form_validation'));
    $this->load->model(array('account/account_model', 'account/account_details_model', 'account/acl_permission_model', 'account/acl_role_model', 'account/rel_account_permission_model', 'account/rel_account_role_model', 'account/rel_role_permission_model'));
    $this->load->language(array('general', 'admin/manage_roles', 'account/settings'));
  }

  /**
   * Manage Roles
   */
  function index()
  {
    // Redirect unauthenticated users to signin page
    if ( ! $this->authentication->is_signed_in())
    {
      redirect('account/sign_in/?continue='.urlencode(base_url().'account/manage_roles'));
    }

    // Redirect unauthorized users to account profile page
    if ( ! $this->authorization->is_permitted('retrieve_roles'))
    {
      redirect('account/account_profile');
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

    // Get all permossions, roles, and role_permissions
    $roles = $this->acl_role_model->get();
    $permissions = $this->acl_permission_model->get();
    $role_permissions = $this->rel_role_permission_model->get();

    // Combine all these elements for display
    $data['roles'] = array();
    foreach( $roles as $role )
    {
      $current_role = array();
      $current_role['id'] = $role->id;
      $current_role['name'] = $role->name;
      $current_role['description'] = $role->description;
      $current_role['perm_list'] = array();
      $current_role['user_count'] = $this->acl_role_model->get_user_count($role->id);

      foreach( $role_permissions as $rperm )
      {
        if( $rperm->role_id == $role->id )
        {
          foreach( $permissions as $perm )
          {
            if( $rperm->permission_id == $perm->id )
            {
              $current_role['perm_list'][] = array(
                'id' => $perm->id, 
                'key' => $perm->key,
                'title' => $perm->description );
            }
          }
        }
      }

      $data['roles'][] = $current_role;
    }


    // Load manage roles view
    $this->load->view('admin/manage_roles', $data);
  }


  /**
   * Manage Roles
   */
  function save($id=null)
  {
    // Keep track if this is a new role
    $is_new = empty($id);

    // Redirect unauthenticated users to signin page
    if ( ! $this->authentication->is_signed_in())
    {
      redirect('account/sign_in/?continue='.urlencode(base_url().'account/manage_roles'));
    }

    // Redirect unauthorized users to account profile page
    if ( ! $this->authorization->is_permitted('retrieve_roles'))
    {
      redirect('account/account_profile');
    }

    // Set action type (create or update role)
    $data['action'] = 'create';

    // Get all the permissions
    $data['permissions'] = $this->acl_permission_model->get();

    // Is this a System Role?
    $data['is_system'] = FALSE;

    //Get the role
    if( ! $is_new )
    {
      $data['role'] = $this->acl_role_model->get_by_id($id);
      $data['role_permissions'] = $this->rel_role_permission_model->get_by_role_id($id);
      $data['action'] = 'update';
      $data['is_system'] = ($data['role']->is_system == 1);
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

    // Setup form validation
    $this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
    $this->form_validation->set_rules(
      array(
        array(
          'field' => 'role_name',
          'label' => 'lang:roles_name',
          'rules' => 'trim|required|max_length[80]|edit_unique[a3m_acl_role.name.' . $id . ']'),
        array(
          'field' => 'role_description',
          'label' => 'lang:roles_description',
          'rules' => 'trim|max_length[160]')
      ));
      
      
    $role_delete = $this->input->post('manage_role_delete', TRUE);
    
    if( !empty($role_delete) )
    {
        if( ! empty($id) && $this->authorization->is_permitted('delete_roles') ) 
        {
            $id = $this->acl_role_model->delete($id);
            
            $this->session->set_flashdata('flash_info', lang('roles_delete_success'));
            
            // Redirect to view the manage roles page
            redirect("admin/manage_roles");
        }
    }
    else
    {

        // Run form validation
        if ($this->form_validation->run())
        {
            // Create/Update role
            $attributes = array();
    
            // Now allowed to update the Admin role name
            if( ! $data['is_system'] )
            {
              $attributes['name'] = $this->input->post('role_name', TRUE) ? $this->input->post('role_name', TRUE) : NULL;
            }
    
            $attributes['description'] = $this->input->post('role_description', TRUE) ? $this->input->post('role_description', TRUE) : NULL;
    
            $id = $this->acl_role_model->update($id, $attributes);
    
            // Apply the checked permissions
            $perms = array();
            foreach( $data['permissions'] as $perm )
            {
              if( $this->input->post("role_permission_{$perm->id}", TRUE) )
              {
                $perms[] = $perm->id;
              }
            }
            $this->rel_role_permission_model->delete_update_batch($id, $perms);
            
            if ( $is_new )
            {
                $this->session->set_flashdata('flash_info', lang('roles_create_success'));
            }
            else
            {
                $this->session->set_flashdata('flash_info', lang('roles_update_success'));
            }
    
            // Redirect to view the manage roles page
            redirect("admin/manage_roles");
        }
    }
    // Load manage roles view
    $this->load->view('admin/manage_roles_save', $data);
  }
}


/* End of file manage_roles.php */
/* Location: ./application/account/controllers/manage_roles.php */

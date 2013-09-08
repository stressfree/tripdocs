<?php echo $this->load->view('_subviews/head', array('title' => lang('users_page_name'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'admin/manage_users')); ?>

<div class="administration administration-form">
      <?php echo form_open(uri_string()); ?>
      
        <h1><?php echo lang("users_{$action}_page_name"); ?></h1>

        <?php echo form_fieldset(); ?>
        
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('settings_fullname'), 'users_fullname'); ?>
                </div>
                <?php echo form_error('settings_fullname'); ?>
                <div class="field-value <?php if (isset($users_fullname_error)) : ?>field-error<?php endif; ?>">
    				<?php echo form_input(array('name' => 'users_fullname', 'id' => 'users_fullname', 'value' => set_value('users_fullname') ? set_value('users_fullname') : (isset($update_account_details->fullname) ? $update_account_details->fullname : ''), 'class' => 'text', 'size' => '45', 'maxlength' => '160')); ?>
    				<?php if (isset($users_fullname_error)) : ?>
                    <span class="help-text"><?php echo $users_fullname_error; ?></span>
    				<?php endif; ?>
                </div>
            </div>
            
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('settings_email'), 'users_email'); ?>
                </div>
                <?php echo form_error('users_email'); ?>
                <div class="field-value <?php if (isset($users_email_error)) : ?>field-error<?php endif; ?>">
    				<?php echo form_input(array('name' => 'users_email', 'id' => 'users_email', 'value' => set_value('users_email') ? set_value('users_email') : (isset($update_account->email) ? $update_account->email : ''), 'class' => 'text', 'size' => '45', 'maxlength' => '160')); ?>
    				<?php if (isset($users_email_error)) : ?>
                    <span class="help-text"><?php echo $users_email_error; ?></span>
    				<?php endif; ?>
                </div>
            </div>
            
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('users_roles')); ?>
                </div>
                <div class="field-value">
                    <?php foreach($roles as $role) : ?>
                    <?php 
                    $check_it = FALSE;
                    
                    if( isset($update_account_roles) ) 
                    {
                      foreach($update_account_roles as $acrole) 
                      {
                        if($role->id == $acrole->id)
                        {
                          $check_it = TRUE; break;
                        }
                      }
                    }
                    ?>
                    <div class="field-checkbox">
                        <?php echo form_checkbox('account_role_' . $role->id, 'apply', $check_it); ?>
                        <?php echo form_label( $role->name, 'account_role_' . $role->id ); ?>
                    </div>
                  <?php endforeach; ?>
                </div>
            </div>
            
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('users_restricted_subdomains')); ?>
                </div>
                <div class="field-value">
                    <?php foreach($restricted_subdomains as $subdomain) : ?>
                    <?php 
                    $check_it = FALSE;
                    
                    if( isset($update_account_subdomains) ) 
                    {
                      foreach($update_account_subdomains as $acsubdomain) 
                      {
                        if($subdomain->id == $acsubdomain->id)
                        {
                          $check_it = TRUE; break;
                        }
                      }
                    }
                    ?>                    
                    <div class="field-checkbox">
                        <?php echo form_checkbox( 'account_subdomain_' . $subdomain->id, 'apply', $check_it ); ?>
                        <?php echo form_label( $subdomain->name, 'account_subdomain_' . $subdomain->id ); ?>
                    </div>
                  <?php endforeach; ?>
                </div>
            </div>
    
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('users_unrestricted_subdomains')); ?>
                </div>
                <div class="field-value">
                    <?php foreach($unrestricted_subdomains as $subdomain) : ?>
                    <p><?php echo $subdomain->name; ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
      
        <?php echo form_fieldset_close(); ?>
      
        <div class="form-controls">
            <?php echo anchor('admin/manage_users', lang('website_cancel'), 'class="button"'); ?>
            <?php echo form_submit('manage_user_update', lang('settings_save'), 'class="submit"'); ?>
            <button class="btn-danger"><?php echo lang('users_delete'); ?></button>
        </div>
    
        <div class="form-controls-confirm">
              <p><?php echo lang('users_delete_question'); ?></p>
              <?php echo anchor('admin/manage_users', lang('website_cancel'), 'class="button"'); ?>
              <?php echo form_submit('manage_user_delete', lang('users_delete_confirm'), 'class="submit btn-danger"'); ?>                    
        </div>

      <?php echo form_close(); ?>
</div>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => true)); ?>
<?php echo $this->load->view('_subviews/head', array('title' => lang('roles_page_name'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'admin/manage_roles')); ?>

<div class="administration administration-form">
      <?php echo form_open(uri_string()); ?>

      <h1><?php echo lang("roles_{$action}_page_name"); ?></h1>
      
      <?php echo form_fieldset(); ?>

            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('roles_name'), 'role_name'); ?>
                </div>
                <div class="field-value <?php if (form_error('role_name')) : ?>field-error<?php endif; ?>">
                    <?php if( $is_system ) : ?>
                        <?php echo form_hidden('role_name', set_value('role_name') ? set_value('role_name') : (isset($role->name) ? $role->name : '')); ?>
                        <p><?php echo $role->name; ?></p>
                        <p class="help-text"><?php echo lang('roles_system_name'); ?></p>
                    <?php else : ?>                
        				<?php echo form_input(array('name' => 'role_name', 'id' => 'role_name', 'value' => set_value('role_name') ? set_value('role_name') : (isset($role->name) ? $role->name : ''), 'class' => 'text', 'size' => '45', 'maxlength' => 80)); ?>
        				<?php echo form_error('role_name'); ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('roles_description'), 'role_description'); ?>
                </div>
                <div class="field-value <?php if (form_error('role_description')) : ?>field-error<?php endif; ?>">
                    <?php echo form_textarea(array('name' => 'role_description', 'id' => 'role_description', 'value' => set_value('role_description') ? set_value('role_description') : (isset($role->description) ? $role->description : ''), 'maxlength' => 160, 'rows'=>'4')); ?>
                    <?php echo form_error('role_description'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('roles_permission')); ?>
                </div>
                <div class="field-value">
                    <?php foreach( $permissions as $perm ) : ?>
                    <?php
                        $check_it = FALSE;
        
                        if( isset($role_permissions) )
                        {
                          foreach( $role_permissions as $rperm )
                          {
                            if( $rperm->id == $perm->id )
                            {
                              $check_it = TRUE; break;
                            }
                          }
                        }
                      ?>
                      <div class="field-checkbox">
                        <?php echo form_checkbox('role_permission_' . $perm->id, 'apply', $check_it); ?>
                        <?php echo form_label( $perm->key, 'role_permission_' . $perm->id ); ?>
                      </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php echo form_fieldset_close(); ?>
      
        <div class="form-controls">
            <?php echo anchor('admin/manage_roles', lang('website_cancel'), 'class="btn"'); ?>
            <?php echo form_submit('manage_role_update', lang('settings_save'), 'class="submit"'); ?>
            <button class="btn-danger"><?php echo lang('roles_delete'); ?></button>
        </div>
    
        <div class="form-controls-confirm">
              <p><?php echo lang('roles_delete_question'); ?></p>
              <?php echo anchor('admin/manage_roles', lang('website_cancel'), 'class="btn"'); ?>
              <?php echo form_submit('manage_role_delete', lang('roles_delete_confirm'), 'class="submit btn-danger"'); ?>                    
        </div>

      <?php echo form_close(); ?>
</div>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => true)); ?>
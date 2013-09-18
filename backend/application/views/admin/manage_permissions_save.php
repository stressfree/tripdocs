<?php echo $this->load->view('_subviews/head', array('title' => lang('permissions_page_name'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'admin/manage_permissions')); ?>

<div class="administration administration-form">
      <?php echo form_open(uri_string()); ?>

      <h1><?php echo lang("permissions_{$action}_page_name"); ?></h1>
      
      <?php echo form_fieldset(); ?>

            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('permissions_key'), 'permission_key'); ?>
                </div>                
                <div class="field-value <?php if (form_error('permission_key')) : ?>field-error<?php endif; ?>">
                    <?php if( $is_system ) : ?>
                        <?php echo form_hidden('permission_key', set_value('permission_key') ? set_value('permission_key') : (isset($permission->key) ? $permission->key : '')); ?>
                        <p><?php echo $permission->key; ?></p>
                        <p class="help-text"><?php echo lang('permissions_system_name'); ?></p>
                    <?php else : ?>                
        				<?php echo form_input(array('name' => 'permission_key', 'id' => 'permission_key', 'value' => set_value('permission_key') ? set_value('permission_key') : (isset($permission->key) ? $permission->key : ''), 'class' => 'text', 'size' => '45', 'maxlength' => 80)); ?>
        				<?php echo form_error('permission_key'); ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('permissions_description'), 'permission_description'); ?>
                </div>
                <div class="field-value <?php if (form_error('permission_description')) : ?>field-error<?php endif; ?>">
                    <?php echo form_textarea(array('name' => 'permission_description', 'id' => 'permission_description', 'value' => set_value('permission_description') ? set_value('permission_description') : (isset($permission->description) ? $permission->description : ''), 'maxlength' => 160, 'rows'=>'4')); ?>
                    <?php echo form_error('permission_description'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('permissions_role')); ?>
                </div>
                <div class="field-value">
                    <?php foreach( $roles as $role ) : ?>
                    <?php
                        $check_it = FALSE;

                        if( isset($role_permissions) )
                        {
                          foreach( $role_permissions as $rperm )
                          {
                            if( $rperm->id == $role->id )
                            {
                              $check_it = TRUE; break;
                            }
                          }
                        }
                      ?>
                      <div class="field-checkbox">
                        <?php echo form_checkbox('role_permission_' . $role->id, 'apply', $check_it); ?>
                        <?php echo form_label( $role->name, 'role_permission_' . $role->id ); ?>
                      </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php echo form_fieldset_close(); ?>
      
        <div class="form-controls">
            <?php echo anchor('admin/manage_permissions', lang('website_cancel'), 'class="btn"'); ?>
            <?php echo form_submit('manage_permission_update', lang('settings_save'), 'class="submit"'); ?>
            <button class="btn-danger"><?php echo lang('permissions_delete'); ?></button>
        </div>
    
        <div class="form-controls-confirm">
              <p><?php echo lang('permissions_delete_question'); ?></p>
              <?php echo anchor('admin/manage_permissions', lang('website_cancel'), 'class="btn"'); ?>
              <?php echo form_submit('manage_permission_delete', lang('permissions_delete_confirm'), 'class="submit btn-danger"'); ?>                    
        </div>

      <?php echo form_close(); ?>
</div>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => true)); ?>
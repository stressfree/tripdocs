<div class="contact-details-edit">
    <div class="contact-details-edit-inner">
        <h3><?php echo lang('settings_page_name') ?></h3>
        
        <?php echo form_open('account/settings'); ?>
            <?php echo form_fieldset(); ?>
                <div class="field-row">
                    <div class="field-label">
                        <?php echo form_label(lang('settings_fullname'), 'settings_fullname'); ?>
                    </div>
                    <div class="field-value <?php if (form_error('settings_fullname')) : ?>field-error<?php endif; ?>">
        				<?php echo form_input(array('name' => 'settings_fullname', 'id' => 'settings_fullname', 'value' => set_value('settings_fullname') ? set_value('settings_fullname') : (isset($account_details->fullname) ? $account_details->fullname : ''), 'class' => 'text', 'size' => '45', 'maxlength' => '160')); ?>
        				<?php echo form_error('settings_fullname'); ?>
                    </div>
                </div>
                
                <div class="field-row">
                    <div class="field-label">
                        <?php echo form_label(lang('settings_email'), 'settings_email'); ?>
                    </div>
                    <div class="field-value <?php if (form_error('settings_email')) : ?>field-error<?php endif; ?>">
        				<?php echo form_input(array('name' => 'settings_email', 'id' => 'settings_email', 'value' => set_value('settings_email') ? set_value('settings_email') : (isset($account->email) ? $account->email : ''), 'class' => 'text', 'size' => '45', 'maxlength' => '160')); ?>
        				<?php echo form_error('settings_email'); ?>
                    </div>
                </div>
                
            <?php echo form_fieldset_close(); ?>
            
            <div class="form-controls">
			    <button class="cancel"><?php echo lang('website_cancel'); ?></button>
			    <?php echo form_submit('settings_update', lang('settings_save'), 'class="submit"'); ?>
			    <button class="btn-danger"><?php echo lang('settings_delete'); ?></button>
            </div>
            
            <div class="form-controls-confirm">
                <p><?php echo lang('settings_delete_question'); ?></p>
			    <button class="cancel"><?php echo lang('website_cancel'); ?></button>
			    <?php echo form_submit('settings_delete', lang('settings_delete_confirm'), 'class="submit btn-danger"'); ?>                    
            </div>
        <?php echo form_close(); ?>
    </div>
</div>
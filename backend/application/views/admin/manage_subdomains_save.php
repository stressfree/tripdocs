<?php echo $this->load->view('_subviews/head', array('title' => lang('subdomains_page_name'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'admin/manage_subdomains')); ?>

<div class="administration administration-form">
      <?php echo form_open(uri_string()); ?>

      <h1><?php echo lang("subdomains_{$action}_page_name"); ?></h1>
      
      <?php echo form_fieldset(); ?>

            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('subdomains_name'), 'subdomain_name'); ?>
                </div>                    
                <div class="field-value <?php if (form_error('subdomain_name')) : ?>field-error<?php endif; ?>">              
    				<?php echo form_input(array('name' => 'subdomain_name', 'id' => 'subdomain_name', 'value' => set_value('subdomain_name') ? set_value('subdomain_name') : (isset($subdomain->name) ? $subdomain->name : ''), 'class' => 'text', 'size' => '45', 'maxlength' => 50)); ?>
    				<?php echo form_error('subdomain_name'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('subdomains_description'), 'subdomain_description'); ?>
                </div>
                <div class="field-value <?php if (form_error('subdomain_description')) : ?>field-error<?php endif; ?>">
                    <?php echo form_textarea(array('name' => 'subdomain_description', 'id' => 'subdomain_description', 'value' => set_value('subdomain_description') ? set_value('subdomain_description') : (isset($subdomain->description) ? $subdomain->description : ''), 'maxlength' => 160, 'rows'=>'4')); ?>
                    <?php echo form_error('subdomain_description'); ?>
                </div>
            </div>
        
            <div class="field-row">
                <div class="field-value field-value-inline">
                    <?php echo form_checkbox(array('name' => 'subdomain_all_access', 'id' => 'subdomain_all_access', 'value' => '1', 'checked' => set_value('subdomain_all_access') ? set_value('subdomain_all_access') : (isset($subdomain->all_access) ? $subdomain->all_access : ''))); ?>
                </div>
                <div class="field-label">
                    <?php echo form_label(lang('subdomains_all_access'), 'subdomain_all_access'); ?>
                </div>
            </div>
            
            <?php echo form_fieldset_close(); ?>
      
        <div class="form-controls">
            <?php echo anchor('admin/manage_subdomains', lang('website_cancel'), 'class="btn"'); ?>
            <?php echo form_submit('manage_subdomain_update', lang('settings_save'), 'class="submit"'); ?>
            <button class="btn-danger"><?php echo lang('subdomains_delete'); ?></button>
        </div>
    
        <div class="form-controls-confirm">
              <p><?php echo lang('subdomains_delete_question'); ?></p>
              <?php echo anchor('admin/manage_subdomains', lang('website_cancel'), 'class="btn"'); ?>
              <?php echo form_submit('manage_subdomain_delete', lang('subdomains_delete_confirm'), 'class="submit btn-danger"'); ?>                    
        </div>

      <?php echo form_close(); ?>
</div>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => true)); ?>
<?php echo $this->load->view('head', array('title' => lang('subdomains_page_name'))); ?>

<?php echo $this->load->view('header', array('current' => 'admin/manage_subdomains')); ?>

<div class="container">
  <div class="row">

    <div class="span10">

      <h2><?php echo lang("subdomains_{$action}_page_name"); ?></h2>

      <div class="well">
        <?php echo lang("subdomains_{$action}_description"); ?>
      </div>

      <?php echo form_open(uri_string(), 'class="form-horizontal"'); ?>

      <div class="control-group <?php echo (form_error('subdomains_name') || isset($subdomains_name_error)) ? 'error' : ''; ?>">
          <label class="control-label" for="subdomains_name"><?php echo lang('subdomains_name'); ?></label>

          <div class="controls">
            <?php echo form_input(array('name' => 'subdomains_name', 'id' => 'subdomains_name', 'value' => set_value('subdomains_name') ? set_value('subdomains_name') : (isset($update_subdomain->name) ? $update_subdomain->name : ''), 'maxlength' => 50)); ?>

            <?php if (form_error('subdomains_name') || isset($subdomains_name_error)) : ?>
              <span class="help-inline">
              <?php
                echo form_error('subdomains_name');
                echo isset($subdomains_name_error) ? $subdomains_name_error : '';
              ?>
              </span>
            <?php endif; ?>
          </div>
      </div>

      <div class="control-group">
          <label class="control-label" for="subdomains_description"><?php echo lang('subdomains_about'); ?></label>

          <div class="controls">
            <?php echo form_textarea(array('name' => 'subdomains_description', 'id' => 'subdomains_description', 'value' => set_value('subdomains_description') ? set_value('subdomains_description') : (isset($update_subdomain->description) ? $update_subdomain->description : ''), 'cols' => 50, 'rows' => 5)); ?>
          </div>
      </div>
      
      <div class="control-group">
          <label class="control-label" for="subdomains_all_access"><?php echo lang('subdomains_all_access'); ?></label>

          <div class="controls">
            <?php echo form_checkbox(array('name' => 'subdomains_all_access', 'id' => 'subdomains_all_access', 'value' => '1', 'checked' => $update_subdomain->all_access)); ?>
          </div>
      </div>

      <div class="form-actions">
        <?php echo form_submit('manage_subdomain_submit', lang('settings_save'), 'class="btn btn-primary"'); ?>
        <?php echo anchor('admin/manage_subdomains', lang('website_cancel'), 'class="btn"'); ?>
        <?php if( $this->authorization->is_permitted('delete_subdomains') && $action == 'update' ): ?>
          <span><?php echo lang('admin_or');?></span>
          <?php echo form_submit('manage_subdomain_delete', lang('subdomains_delete_description'), 'class="btn btn-danger"'); ?>
        <?php endif; ?>
      </div>

      <?php echo form_close(); ?>

    </div>
  </div>
</div>

<?php echo $this->load->view('footer'); ?>

<?php echo $this->load->view('foot', array('javascript' => true)); ?>
<?php echo $this->load->view('_subviews/head', array('title' => lang('connect_create_account'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'account/create')); ?>

<div class="connect-create">
    <h1><?php echo lang('connect_create_heading'); ?></h1>
    <p><?php echo lang('connect_create_introduction'); ?></p>

    <?php echo form_open(uri_string()); ?>
        <?php echo form_fieldset(); ?>            
            <?php if (isset($connect_create_error)) : ?>
            <div class="form-error">
                <?php echo $connect_create_error; ?>
            </div>
    		<?php endif; ?>
            
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('connect_create_fullname'), 'connect_create_fullname'); ?>
                </div>
                <?php echo form_error('connect_create_fullname'); ?>
                <div class="field-value <?php if (isset($connect_create_fullname_error)) : ?>field-error<?php endif; ?>">
    				<?php echo form_input(array('name' => 'connect_create_fullname', 'id' => 'connect_create_fullname', 'value' => set_value('connect_create_fullname') ? set_value('connect_create_fullname') : (isset($connect_create[1]['fullname']) ? $connect_create[1]['fullname'] : ''), 'class' => 'text', 'size' => '45', 'maxlength' => '160')); ?>
    				<?php if (isset($connect_create_fullname_error)) : ?>
                    <span class="help-text"><?php echo $connect_create_fullname_error; ?></span>
    				<?php endif; ?>
                </div>
            </div>
            
            <div class="field-row">
                <div class="field-label">
                    <?php echo form_label(lang('connect_create_email'), 'connect_create_email'); ?>
                </div>
                <?php echo form_error('connect_create_email'); ?>
                <div class="field-value <?php if (isset($connect_create_email_error)) : ?>field-error<?php endif; ?>">
    				<?php echo form_input(array('name' => 'connect_create_email', 'id' => 'connect_create_email', 'value' => set_value('connect_create_email') ? set_value('connect_create_email') : (isset($connect_create[0]['email']) ? $connect_create[0]['email'] : ''), 'class' => 'text', 'size' => '45', 'maxlength' => '160')); ?>
    				<?php if (isset($connect_create_email_error)) : ?>
                    <span class="help-text"><?php echo $connect_create_email_error; ?></span>
    				<?php endif; ?>
                </div>
            </div>
            
            <div class="form-controls">
    			<?php echo form_submit('connect_create', lang('connect_create_button'), 'class="submit"'); ?>
            </div>
        <?php echo form_fieldset_close(); ?>
    <?php echo form_close(); ?>
</div>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => false)); ?>

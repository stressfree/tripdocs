<?php echo $this->load->view('head', array('title' => lang('forgot_password_page_name'))); ?>

<?php echo $this->load->view('header'); ?>

<div class="container">
    <div class="row">
        <div class="span12">
			<?php echo sprintf(lang('reset_password_sent_instructions'), anchor('account/forgot_password', lang('reset_password_resend_the_instructions'))); ?>
        </div>
    </div>
</div>

<?php echo $this->load->view('footer'); ?>

<?php echo $this->load->view('foot'); ?>

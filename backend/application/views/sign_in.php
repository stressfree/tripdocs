<?php echo $this->load->view('head', array('title' => lang('sign_in_page_name'))); ?>

<?php echo $this->load->view('header'); ?>

<div class="welcome-introduction">
    <h1><?php echo lang('sign_in_welcome_heading'); ?></h1>
    <?php echo lang('sign_in_welcome'); ?>
</div>

<div class="login-thirdparty">
	<?php if ($this->config->item('third_party_auth_providers')) : ?>
    <h2><?php echo sprintf(lang('sign_in_third_party_heading')); ?></h2>
    <ul>
		<?php foreach ($this->config->item('third_party_auth_providers') as $provider) : ?>
        <li class="third_party <?php echo $provider; ?>"><?php echo anchor('account/connect_'.$provider, lang('connect_'.$provider), array('title' => sprintf(lang('sign_in_with'), lang('connect_'.$provider)))); ?></li>
		<?php endforeach; ?>
    </ul>
	<?php endif; ?>
	<div class="login-help">
	    <p><?php echo lang('sign_in_help'); ?></p>
	</div>
</div>

<?php echo $this->load->view('footer'); ?>

<?php echo $this->load->view('foot'); ?>
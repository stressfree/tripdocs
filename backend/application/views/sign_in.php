<?php echo $this->load->view('_subviews/head', array('title' => lang('sign_in_page_name'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'sign_in')); ?>

<div class="welcome-introduction">
    <div class="welcome-introduction-inner">
        <?php echo lang('sign_in_welcome'); ?>
    </div>
</div>

<div class="login-thirdparty">
    <div class="login-thirdparty-inner">
    	<?php if ($this->config->item('third_party_auth_providers')) : ?>
        <h1><?php echo sprintf(lang('sign_in_third_party_heading')); ?></h1>
        <ul>
    		<?php foreach ($this->config->item('third_party_auth_providers') as $provider) : ?>
            <li class="third_party <?php echo $provider; ?>"><?php echo anchor('account/connect_'.$provider, '<span>' . lang('connect_'.$provider) . '</span>', array('title' => sprintf(lang('sign_in_with'), lang('connect_'.$provider)))); ?></li>
    		<?php endforeach; ?>
        </ul>
    	<?php endif; ?>
	    <div class="login-help">
	        <p><?php echo lang('sign_in_help'); ?></p>
        </div>
    </div>
</div>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => false)); ?>
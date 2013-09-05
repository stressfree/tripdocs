<div class="linked-accounts-add">
    <div class="linked-accounts-add-inner">
        <h3><?php echo lang('home_add_linked_account') ?></h3>
        <ul class="third_party">
            <?php foreach ($this->config->item('third_party_auth_providers') as $provider) : ?>
            <li class="third_party <?php echo $provider; ?>"><?php echo anchor('account/connect_'.$provider, '<span>' . lang('connect_' . $provider) . '</span>', array('title' => sprintf(lang('connect_with_x'), lang('connect_'.$provider)))); ?></li>
            <?php endforeach; ?>
        </ul>
        
	    <div class="login-help">
	        <p><?php echo lang('sign_in_help'); ?></p>
        </div>
        
        <div class="form-controls">
            <button class="cancel"><?php echo lang('website_cancel'); ?></button>
        </div>
    </div>
</div>
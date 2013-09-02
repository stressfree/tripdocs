<?php echo $this->load->view('head'); ?>

<?php echo $this->load->view('header', array('current' => 'home')); ?>

<?php if ($this->authentication->is_signed_in()) : ?>

<div class="subdomains">
    <h1><?php echo lang('home_subdomains'); ?></h1>
    <?php if (count($subdomains) > 0) : ?>
        <ul>
        <?php foreach ($subdomains as $subdomain) : ?>
            <li>
                <p class="link"><?php echo anchor($subdomain['url'], $subdomain['url']); ?></p>
                <?php if (!empty($subdomain['description'])) : ?>
                <p class="description"><?php echo $subdomain['description'] ?></p>
                <?php endif ?>    
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p><?php echo lang('home_subdomains_empty'); ?></p>
    <?php endif; ?>
</div>

<div class="user-details">
    <div class="contact-details">
        <h3><?php echo lang('home_contact'); ?></h3>
        <p class="account-name"><?php echo $account_details->fullname; ?></p>
        <p class="account-email"><?php echo $account->email; ?></p>
        <p class="edit-link">
            <span class="link-indicator">&raquo;</span>
            <a class="edit-contact-link"><?php echo lang('home_contact_edit'); ?></a></span>
        </p>
    </div>
    
    <div class="linked-accounts">
        <h3><?php echo lang('home_linked_accounts'); ?></h3>
        <ul>
        <?php if ($facebook_links) : ?>
			<?php foreach ($facebook_links as $facebook_link) : ?>
                <li class="facebook">
                    <?php echo anchor('http://facebook.com/profile.php?id='.$facebook_link->facebook_id, lang('connect_facebook'), array('target' => '_blank', 'title' => 'http://facebook.com/profile.php?id='.$facebook_link->facebook_id)); ?>
                    
					<?php if ($num_of_linked_accounts > 1) : ?>
						<?php echo form_open('account/account_linked'); ?>
						<?php echo form_fieldset(); ?>
						<?php echo form_hidden('facebook_id', $facebook_link->facebook_id); ?>
						<span class="link-indicator">&raquo;</span>
						<?php echo form_button(array('type' => 'submit', 'content' => lang('connect_remove'))); ?>
						<?php echo form_fieldset_close(); ?>
						<?php echo form_close(); ?>
                    <?php endif; ?>
                </li>
				<?php endforeach; ?>
			<?php endif; ?>

		<?php if ($twitter_links) : ?>
			<?php foreach ($twitter_links as $twitter_link) : ?>
                <li class="twitter">
                    <?php echo anchor('http://twitter.com/'.$twitter_link->twitter->screen_name, lang('connect_twitter') . ' (' . $twitter_link->twitter->screen_name . ')', array('target' => '_blank', 'title' => 'http://twitter.com/'.$twitter_link->twitter->screen_name)); ?>
                    
                    <?php if ($num_of_linked_accounts > 1) : ?>
						<?php echo form_open('account/account_linked'); ?>
						<?php echo form_fieldset(); ?>
						<?php echo form_hidden('twitter_id', $twitter_link->twitter_id); ?>
						<span class="link-indicator">&raquo;</span>
						<?php echo form_button(array('type' => 'submit', 'class' => 'btn', 'content' => lang('connect_remove'))); ?>
						<?php echo form_fieldset_close(); ?>
						<?php echo form_close(); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>

		<?php if ($openid_links) : ?>
			<?php foreach ($openid_links as $openid_link) : ?>
                <li class="<?php echo $openid_link->provider; ?>">
                    <?php echo lang('connect_' . $openid_link->provider); ?>
                    <?php if ($num_of_linked_accounts > 1) : ?>
						<?php echo form_open('account/account_linked'); ?>
						<?php echo form_fieldset(); ?>
						<?php echo form_hidden('openid', $openid_link->openid); ?>
						<span class="link-indicator">&raquo;</span>
						<?php echo form_button(array('type' => 'submit', 'class' => 'btn', 'content' => lang('connect_remove'))); ?>
						<?php echo form_fieldset_close(); ?>
						<?php echo form_close(); ?>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
			<?php endif; ?>
		<?php endif; ?>
        </ul>
        
        <p class="edit-link">
            <span class="link-indicator">&raquo;</span>
            <a class="add-account-link"><?php echo lang('home_add_linked_account_link'); ?></a></span>
        </p>
    </div>
</div>

<div class="contact-details-edit">
    <div class="contact-details-edit-inner">
        <h3><?php echo lang('home_contact_edit') ?></h3>
        
        <?php echo form_open('account/settings'); ?>
            <?php echo form_fieldset(); ?>
                <div class="field-row">
                    <div class="field-label">
                        <?php echo form_label(lang('settings_fullname'), 'settings_fullname'); ?>
                    </div>
                    <?php echo form_error('settings_fullname'); ?>
                    <div class="field-value <?php if (isset($settings_fullname_error)) : ?>field-error<?php endif; ?>">
        				<?php echo form_input(array('name' => 'settings_fullname', 'id' => 'settings_fullname', 'value' => set_value('settings_fullname') ? set_value('settings_fullname') : (isset($account_details->fullname) ? $account_details->fullname : ''), 'class' => 'text', 'size' => '45', 'maxlength' => '160')); ?>
        				<?php if (isset($settings_fullname_error)) : ?>
                        <span class="help-text"><?php echo $settings_fullname_error; ?></span>
        				<?php endif; ?>
                    </div>
                </div>
                
                <div class="field-row">
                    <div class="field-label">
                        <?php echo form_label(lang('settings_email'), 'settings_email'); ?>
                    </div>
                    <?php echo form_error('settings_email'); ?>
                    <div class="field-value <?php if (isset($settings_email_error)) : ?>field-error<?php endif; ?>">
        				<?php echo form_input(array('name' => 'settings_email', 'id' => 'settings_email', 'value' => set_value('settings_email') ? set_value('settings_email') : (isset($account->email) ? $account->email : ''), 'class' => 'text', 'size' => '45', 'maxlength' => '160')); ?>
        				<?php if (isset($settings_email_error)) : ?>
                        <span class="help-text"><?php echo $settings_email_error; ?></span>
        				<?php endif; ?>
                    </div>
                </div>
                
                <div class="form-controls">
    			    <button class="cancel"><?php echo lang('website_cancel'); ?></button>
    			    <?php echo form_submit('settings_update', lang('settings_save'), 'class="submit"'); ?>
    			    <?php echo form_submit('settings_delete', lang('settings_delete'), 'class="submit btn-danger"'); ?>
                </div>
            <?php echo form_fieldset_close(); ?>
        <?php echo form_close(); ?>
    </div>
</div>

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

<?php echo $this->load->view('footer'); ?>

<?php echo $this->load->view('foot', array('javascript' => true)); ?>
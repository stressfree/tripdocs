<?php echo $this->load->view('head'); ?>

<?php echo $this->load->view('header'); ?>

<?php if ($this->authentication->is_signed_in()) : ?>
<div class="subdomains">

</div>

<div class="user-details">
    <div class="contact-details">
        <h3><?php echo lang('home_contact'); ?></h3>
        <p class="account-name"><?php echo $account_details->fullname; ?></p>
        <p class="account-email"><?php echo $account->email; ?></p>
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
						<?php echo form_button(array('type' => 'submit', 'class' => 'btn', 'content' => lang('connect_remove'))); ?>
						<?php echo form_fieldset_close(); ?>
						<?php echo form_close(); ?>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
			<?php endif; ?>
		<?php endif; ?>
    </div>
</div>

<div class="linked-accounts-add">
    <h3><?php echo lang('home_add_linked_account') ?></h3>
    <ul class="third_party">
        <?php foreach ($this->config->item('third_party_auth_providers') as $provider) : ?>
        <li class="third_party <?php echo $provider; ?>"><?php echo anchor('account/connect_'.$provider, lang('connect_' . $provider), array('title' => sprintf(lang('connect_with_x'), lang('connect_'.$provider)))); ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<?php echo $this->load->view('footer'); ?>

<?php echo $this->load->view('foot'); ?>
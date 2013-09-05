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
						<?php echo form_open('account/linked'); ?>
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
						<?php echo form_open('account/linked'); ?>
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
						<?php echo form_open('account/linked'); ?>
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
        </ul>
        
        <p class="edit-link">
            <span class="link-indicator">&raquo;</span>
            <a class="add-account-link"><?php echo lang('home_add_linked_account_link'); ?></a></span>
        </p>
    </div>
</div>

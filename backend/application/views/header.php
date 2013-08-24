<div class="header">
    <div class="header-inner">
        <div class="title">
            <h3><?php echo anchor('', lang('website_title')); ?></h3>
        </div>
        
        <?php if ($this->authentication->is_signed_in()) : ?>
        <div class="user-menu">
            <ul>
                <li><span><?php echo $account->email; ?></span></li>
			    <li><?php echo anchor('account/sign_out', lang('website_sign_out')); ?></li>
            </ul>
        </div>
        
        <div class="navigation-menu">
            <ul>
                <li><?php echo anchor('', lang('website_welcome'), 'class="selected"'); ?></li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="content">
    <div class="content-inner">
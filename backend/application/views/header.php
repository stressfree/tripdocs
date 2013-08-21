<div class="container">
    <div class="container-inner">
        <div class="header">
            <div class="header-inner">
                <div class="title">
                    <h3><?php echo anchor('', lang('website_title')); ?></h3>
                </div>
                
                <div class="usermenu">
                    <ul>
                    <?php if ($this->authentication->is_signed_in()) : ?>
                        <li><span><?php echo $account->email; ?></span></li>
        			    <li><?php echo anchor('account/sign_out', lang('website_sign_out')); ?></li>
                    <?php else : ?>
                        <li><?php echo anchor('account/sign_in', lang('website_sign_in')); ?></li>
                    <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="content">
            <div class="content-inner">
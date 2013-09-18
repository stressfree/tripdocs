<?php if ($this->authentication->is_signed_in()) : ?>
    <div class="usermenu">
        <div class="usermenu-inner">
            <ul>
                <li><span><?php echo $account->email; ?></span></li>
                <li><?php echo anchor('account/sign_out', lang('website_sign_out')); ?></li>
            </ul>
        </div>
    </div>
          
    <div class="navigation">
        <div class="navigation-inner">
            <ul>
                <?php if ($this->authorization->is_permitted( 
                        array('retrieve_users', 'retrieve_subdomains', 'retrieve_roles', 'retrieve_permissions') )) : ?>  
                <li class="<?php echo (strpos($current, 'admin/') === 0) ? 'active' : ''; ?>">
                    <a class="admin-link"><?php echo lang('website_manage'); ?></a>
                    <ul>
                        <?php if ($this->authorization->is_permitted('retrieve_users')) : ?>
                        <li class="<?php echo ($current == 'admin/manage_users') ? 'active' : ''; ?>">
                            <?php echo anchor('admin/manage_users', lang('website_manage_users')); ?>
                        </li>
                        <?php endif; ?>    
                        <?php if ($this->authorization->is_permitted('retrieve_subdomains')) : ?>
                        <li class="<?php echo ($current == 'admin/manage_subdomains') ? 'active' : ''; ?>">
                            <?php echo anchor('admin/manage_subdomains', lang('website_manage_subdomains')); ?>
                        </li>
                        <?php endif; ?>
                        <?php if ($this->authorization->is_permitted('retrieve_roles')) : ?>
                        <li class="<?php echo ($current == 'admin/manage_roles') ? 'active' : ''; ?>">
                            <?php echo anchor('admin/manage_roles', lang('website_manage_roles')); ?>
                        </li>
                        <?php endif; ?>
                        <?php if ($this->authorization->is_permitted('retrieve_permissions')) : ?>
                        <li class="<?php echo ($current == 'admin/manage_permissions') ? 'active' : ''; ?>">
                            <?php echo anchor('admin/manage_permissions', lang('website_manage_permissions')); ?>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
                <li class="<?php echo ($current == 'home') ? 'active' : ''; ?>">
                    <?php echo anchor('', lang('website_home')); ?>
                </li>
            </ul>
        </div>
    </div>
<?php endif; ?>
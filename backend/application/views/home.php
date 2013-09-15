<?php echo $this->load->view('_subviews/head'); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'home')); ?>

<?php if ($this->authentication->is_signed_in()) : ?>

<div class="subdomains">
    <h1><?php echo lang('home_subdomains'); ?></h1>
    <?php if (count($subdomains) > 0) : ?>
        <ul>
        <?php foreach ($subdomains as $subdomain) : ?>
            <li>
                <div class="action">
                    <a class="btn btn-small" href="mailto: ?subject=<?php echo $subdomain['name']; ?> - <?php echo lang('home_share_subject'); ?>&body=<?php echo lang('home_share_body') ?>%0D%0A%0D%0A<?php echo $subdomain['shareUrl']; ?>"><?php echo lang('home_share'); ?></a>
                    <?php if( $this->authorization->is_permitted('update_subdomains') ): ?>
                    <?php echo anchor('admin/manage_subdomains/save/'.$subdomain['id'], lang('website_modify'), 'class="btn btn-small"'); ?>
                    <?php endif; ?>
                </div>
                <p class="link"><?php echo anchor($subdomain['url'], $subdomain['url']); ?></p>
                <?php if (!empty($subdomain['description'])) : ?>
                <p class="description"><?php echo $subdomain['description'] ?></p>
                <?php endif ?>
                <?php if ($this->authorization->is_permitted('retrieve_subdomains')): ?>
                <div class="upload-details">
                    <p class="server"><span><?php echo lang('home_server') ?>:</span> <?php echo $upload_server ?></p>
                    <p class="path"><span><?php echo lang('home_path') ?>:</span> <?php echo $subdomain['dir'] ?></p>
                </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p><?php echo lang('home_subdomains_empty'); ?></p>
    <?php endif; ?>
</div>

<?php echo $this->load->view('_subviews/user-details'); ?>

<?php echo $this->load->view('_subviews/contact-details-edit'); ?>

<?php echo $this->load->view('_subviews/linked-accounts-add'); ?>

<?php endif; ?>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => true)); ?>
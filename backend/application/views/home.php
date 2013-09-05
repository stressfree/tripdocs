<?php echo $this->load->view('_subviews/head'); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'home')); ?>

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

<?php echo $this->load->view('_subviews/user-details'); ?>

<?php echo $this->load->view('_subviews/contact-details-edit'); ?>

<?php echo $this->load->view('_subviews/linked-accounts-add'); ?>

<?php endif; ?>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => true)); ?>
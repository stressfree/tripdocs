<div class="header">
    <div class="header-inner">
        <div class="title">
            <h3><?php echo anchor('', lang('website_title')); ?></h3>
        </div>
        
        <?php echo $this->load->view('menu', array('current' => $current)); ?>
    </div>    
</div>

<?php if ($this->session->flashdata('flash_info')) : ?>
<div class="alert-success">
    <button type="button" class="close">&times;</button>
	<?php echo $this->session->flashdata('flash_info'); ?>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('flash_error')) : ?>
<div class="alert-error">
    <button type="button" class="close">&times;</button>
	<?php echo $this->session->flashdata('flash_error'); ?>
</div>
<?php endif; ?>

<div class="content">
    <div class="content-inner">
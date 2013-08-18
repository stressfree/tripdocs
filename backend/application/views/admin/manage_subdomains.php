<!DOCTYPE html>
<html>
<head>
  <?php echo $this->load->view('head', array('title' => lang('subdomains_page_name'))); ?>
</head>
<body>

<?php echo $this->load->view('header'); ?>

<div class="container">
  <div class="row">

    <div class="span2">
      <?php echo $this->load->view('account/account_menu', array('current' => 'manage_subdomains')); ?>
    </div>

    <div class="span10">

      <h2><?php echo lang('subdomains_page_name'); ?></h2>

      <div class="well">
        <?php echo lang('subdomains_description'); ?>
      </div>

      <?php if( count($all_subdomains) > 0 ) : ?>
        <table class="table table-condensed table-hover">
          <thead>
            <tr>
              <th><?php echo lang('subdomains_name'); ?></th>
              <th><?php echo lang('subdomains_about'); ?></th>
              <th><?php echo lang('subdomains_access'); ?></th>
              <th>
                <?php if( $this->authorization->is_permitted('create_subdomains') ): ?>
                  <a href="admin/manage_subdomains/save" class="btn btn-primary btn-small"><?php echo lang('website_create'); ?><a>
                <?php endif; ?>
              </th>
            </tr>
          </thead>
          <tbody>

            <?php foreach( $all_subdomains as $sub ) : ?>
              <tr>
                <td><?php echo $sub['name']; ?></td>
                <td><?php echo $sub['description']; ?></td>
                <td>
                  <?php if ( $sub['all_access'] ): ?>
                  <?php echo lang('subdomains_access_all_users'); ?>
                  <?php else: ?>
                  <?php echo lang('subdomains_access_restricted'); ?>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if( $this->authorization->is_permitted('update_subdomains') ): ?>
                    <a href="admin/manage_subdomains/save/<?php echo $sub['id']; ?>" class="btn btn-small"><?php echo lang('website_update'); ?><a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php echo $this->load->view('footer'); ?>

</body>
</html>
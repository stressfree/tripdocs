<?php echo $this->load->view('_subviews/head', array('title' => lang('subdomains_page_name'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'admin/manage_subdomains')); ?>

<div class="container">
  <div class="row">

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
                  <?php echo anchor('admin/manage_subdomains/save', lang('website_create'), 'class="btn btn-small"'); ?>
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
                    <?php echo anchor('admin/manage_subdomains/save/'.$sub['id'], lang('website_modify'), 'class="btn btn-small"'); ?>
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

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => true)); ?>
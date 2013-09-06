<?php echo $this->load->view('_subviews/head', array('title' => lang('subdomains_page_name'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'admin/manage_subdomains')); ?>

<div class="administration">
      <h1><?php echo lang('subdomains_page_name'); ?></h1>

      <?php if( count($all_subdomains) > 0 ) : ?>
        <table>
          <thead>
            <tr>
              <th class="name first-child"><?php echo lang('subdomains_name'); ?></th>
              <th class="description"><?php echo lang('subdomains_about'); ?></th>
              <th class="access"><?php echo lang('subdomains_access'); ?></th>
              <th class="action last-child">
                <?php if( $this->authorization->is_permitted('create_subdomains') ): ?>
                  <?php echo anchor('admin/manage_subdomains/save', lang('website_create')); ?>
                <?php endif; ?>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php $class = 'even'; ?>
            <?php foreach( $all_subdomains as $sub ) : ?>
                <?php $class = ($class=='even' ? 'odd' : 'even'); ?>
                <tr class="<?php echo $class ?>">
                    <td class="name first-child"><?php echo $sub['name']; ?></td>
                    <td class="description"><?php echo $sub['description']; ?></td>
                    <td class="access">
                        <?php if ( $sub['all_access'] ): ?>
                        <?php echo lang('subdomains_access_all_users'); ?>
                        <?php else: ?>
                        <?php echo lang('subdomains_access_restricted'); ?>
                        <?php endif; ?>
                    </td>
                    <td class="action last-child">
                        <?php if( $this->authorization->is_permitted('update_subdomains') ): ?>
                            <?php echo anchor('admin/manage_subdomains/save/'.$sub['id'], lang('website_modify')); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
</div>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => true)); ?>
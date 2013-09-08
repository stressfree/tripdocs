<?php echo $this->load->view('_subviews/head', array('title' => lang('users_page_name'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'admin/manage_users')); ?>

<div class="administration">
      <h1><?php echo lang('users_page_name'); ?></h1>

      <?php if( count($all_accounts) > 0 ) : ?>
        <table>
          <thead>
            <tr>
              <th class="id first-child">#</th>
              <th class="name"><?php echo lang('users_fullname'); ?></th>
              <th class="email"><?php echo lang('settings_email'); ?></th>
              <th class="action last-child"></th>
            </tr>
          </thead>
          <tbody>
            <?php 
                $class = 'even';
            ?>
            <?php foreach( $all_accounts as $acc ) : ?>
                <?php 
                    $class = ($class=='even' ? 'odd' : 'even');                   
                ?>                
                <tr class="<?php echo $class ?>">
                    <td class="id first-child"><?php echo $acc['id']; ?></td>
                    <td class="name">
                        <p><?php echo $acc['fullname']; ?></p>
                        <p class="info">
                            <?php echo $acc['username']; ?>
                        </p>
                    </td>
                    <td class="email"><?php echo $acc['email']; ?></td>
                    <td class="action last-child">
                        <?php if( $this->authorization->is_permitted('update_users') ): ?>
                        <?php echo anchor('admin/manage_users/save/'.$acc['id'], lang('website_modify')); ?>
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
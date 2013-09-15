<?php echo $this->load->view('_subviews/head', array('title' => lang('permissions_page_name'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'admin/manage_permissions')); ?>

<div class="administration">
      <h1><?php echo lang('permissions_page_name'); ?></h1>

      <table>
        <thead>
          <tr>
            <th class="id first-child">#</th>
            <th class="name"><?php echo lang('permissions_column_permission'); ?></th>
            <th class="description"><?php echo lang('permissions_description'); ?></th>
            <th class="roles"><?php echo lang('permissions_column_inroles'); ?></th>
            <th class="action last-child">
              <?php if( $this->authorization->is_permitted('create_users') ): ?>
                <?php echo anchor('admin/manage_permissions/save', lang('website_create'), 'class="btn btn-small"'); ?>
              <?php endif; ?>
            </th>
          </tr>
        </thead>
        <tbody>
            <?php $class = 'even'; ?>
            <?php foreach( $permissions as $perm ) : ?>
                <?php $class = ($class=='even' ? 'odd' : 'even'); ?>
                <tr class="<?php echo $class ?>">
                    <td class="id first-child"><?php echo $perm['id']; ?></td>
                    <td class="name">
                        <?php echo $perm['key']; ?>
                    </td>
                    <td class="description"><?php echo $perm['description']; ?></td>
                    <td class="roles">
                        <?php if( count($perm['role_list']) == 0 ) : ?>
                        <span class="label"><?php echo lang('website_none'); ?></span>
                        <?php else : ?>
                        <ul class="inline">
                            <?php foreach( $perm['role_list'] as $itm ) : ?>
                            <li>
                                <span class="link-indicator">&raquo;</span>
                                <?php echo anchor('admin/manage_roles/save/'.$itm['id'], $itm['name'], 'title="'.$itm['title'].'"'); ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </td>
                    <td class="action last-child">
                        <?php if( $this->authorization->is_permitted('update_permissions') ): ?>
                            <?php echo anchor('admin/manage_permissions/save/'.$perm['id'], lang('website_modify'), 'class="btn btn-small"'); ?>
                        <?php endif; ?>
                    </td>
                </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
</div>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => true)); ?>
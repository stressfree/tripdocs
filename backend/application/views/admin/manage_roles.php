<?php echo $this->load->view('_subviews/head', array('title' => lang('roles_page_name'))); ?>

<?php echo $this->load->view('_subviews/header', array('current' => 'admin/manage_roles')); ?>

<div class="administration">
    <h2><?php echo lang('roles_page_name'); ?></h2>

      <table class="table table-condensed table-hover">
        <thead>
          <tr>
            <th class="id first-child">#</th>
            <th class="name"><?php echo lang('roles_column_role'); ?></th>
            <th class="users"><?php echo lang('roles_column_users'); ?></th>
            <th class="permissions"><?php echo lang('roles_permission'); ?></th>
            <th class="action last-child">
              <?php if( $this->authorization->is_permitted('create_roles') ): ?>
                <?php echo anchor('admin/manage_roles/save', lang('website_create'), 'class="btn btn-primary btn-small"'); ?>
              <?php endif; ?>
            </th>
          </tr>
        </thead>
        <tbody>
            <?php $class = 'even'; ?>
            <?php foreach( $roles as $role ) : ?>
                <?php $class = ($class=='even' ? 'odd' : 'even'); ?>
                <tr class="<?php echo $class ?>">
                    <td class="id first-child"><?php echo $role['id']; ?></td>
                    <td class="name">
                        <?php echo $role['name']; ?>
                        <?php if( $role['is_disabled'] ): ?>
                            <span class="label label-important"><?php echo lang('roles_banned'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="users">
                        <?php if( $role['user_count'] > 0 ) : ?>
                            <?php echo anchor('admin/manage_users/filter/role/'.$role['id'], $role['user_count'], 'class="badge badge-info"'); ?>
                        <?php else : ?>
                            <span class="badge">0</span>
                        <?php endif; ?>
                    </td>
                    <td class="permissions">
                        <?php if( count($role['perm_list']) == 0 ) : ?>
                            <span class="label"><?php echo lang('website_none') ?></span>
                        <?php else : ?>
                        <ul>
                            <?php foreach( $role['perm_list'] as $itm ) : ?>
                            <li>
                                <span class="link-indicator">&raquo;</span>
                                <?php echo anchor('admin/manage_permissions/save/'.$itm['id'], $itm['key'], 'title="'.$itm['title'].'"'); ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </td>
                    <td class="action last-child">
                    <?php if( $this->authorization->is_permitted('update_roles') ): ?>
                        <?php echo anchor('admin/manage_roles/save/'.$role['id'], lang('website_modify'), 'class="btn btn-small"'); ?>
                    <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
      </table>
</div>

<?php echo $this->load->view('_subviews/footer'); ?>

<?php echo $this->load->view('_subviews/foot', array('javascript' => true)); ?>
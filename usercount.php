<?php
/*
Plugin Name: User Count
Plugin URI: http://wordpress.org/plugins/user-count/
Description: Simply adds the number of users who created an account on your blog to the admin dashboard.
Version: .04
Author: Kirby Witmer
Author URI: http://kirb.it
*/

/*  This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


function users_rightnow() {

global $wp_roles;

$role_links = array();
$avail_roles = array();
$users_of_blog = get_users_of_blog();
$total_users = count( $users_of_blog );
foreach ( (array) $users_of_blog as $b_user ) {
	$b_roles = unserialize($b_user->meta_value);
	foreach ( (array) $b_roles as $b_role => $val ) {
		if ( !isset($avail_roles[$b_role]) )
			$avail_roles[$b_role] = 0;
		$avail_roles[$b_role]++;
	}
}
unset($users_of_blog);

$current_role = false;
$class = empty($role) ? ' class="current"' : '';
$role_links[] = "<a href='users.php'$class>" . sprintf( _nx( ' <strong>Users:</strong> All <span>(%s)</span>', '<strong>Users:</strong> All <span>(%s)</span>', $total_users, 'users' ), number_format_i18n( $total_users ) ) . '</a>';
foreach ( $wp_roles->get_names() as $this_role => $name ) {
	if ( !isset($avail_roles[$this_role]) )
		continue;

	$class = '';

	if ( $this_role == $role ) {
		$current_role = $role;
		$class = ' class="current"';
	}

	$name = translate_user_role( $name );
	/* translators: User role name with count */
	$name = sprintf( __('%1$s <span>(%2$s)</span>'), $name, $avail_roles[$this_role] );
	$role_links[] = "<a href='users.php?role=$this_role'$class>$name</a>";
}
echo implode( " |\n", $role_links);
unset($role_links);
}
		add_action('rightnow_end', 'users_rightnow');

?>
<?php
/**
 * Admin Screen
 */
function admin_css() {
	echo '<link rel="stylesheet" type="text/css" charset="utf-8" href="'.get_bloginfo("stylesheet_directory").'/admin.css">';
}
add_action('admin_head', 'admin_css', 100);


/**
 * delete admin-bar : show only admin
 */
function my_function_admin_bar($content) {
return ( current_user_can("administrator") ) ? $content : false;
}
add_filter( 'show_admin_bar' , 'my_function_admin_bar');
?>
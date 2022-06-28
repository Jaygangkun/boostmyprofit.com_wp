<?php 
/**
 * Add new ad page template
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/*Global variables*/
global $current_user;
$first_name = get_user_meta( $current_user->ID, 'first_name', true );
$last_name  = get_user_meta( $current_user->ID, 'last_name', true );
$post_id 	= !empty( $_GET['id'] ) ? intval( sanitize_text_field( $_GET['id'] ) ) : 0;
$user_id    = !empty( $_GET['identity'] ) ? intval( sanitize_text_field( $_GET['identity'] ) ) : '';
//Enquqe scripts
wp_enqueue_script('jquery-ui');
require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/edit-home');

?>

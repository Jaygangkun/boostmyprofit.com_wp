<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      https://codesquare.co/
 * @since 1.0
 */

global $current_user, $wp_roles, $userdata, $post;

$reference 		 = isset( $_GET['rule'] ) && !empty( $_GET['rule'] ) ? sanitize_text_field($_GET['rule']) : '';
$mode 			 = isset( $_GET['source'] ) && !empty( $_GET['source'] ) ? sanitize_text_field($_GET['source']) : '';
$user_identity 	 = $current_user->ID;

$url_identity = $user_identity;
if (isset($_GET['identity']) && !empty($_GET['identity'])) {
	$url_identity = sanitize_text_field($_GET['identity']);
}

$profile_page = codesquare_workintry_get_profile_url();
if (is_user_logged_in()) { ?>
	<li><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"><i class="lnr lnr-exit"></i><span><?php esc_html_e('Logout', 'workintry'); ?></span></a></li>
<?php }

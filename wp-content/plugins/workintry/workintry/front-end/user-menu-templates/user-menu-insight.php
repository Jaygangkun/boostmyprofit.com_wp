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
?>
	<li class="<?php echo ( $reference === 'insight' ? '' : ''); ?>">
		<a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'insight', $user_identity, '', 'profile'); ?>"><i class="lnr lnr-home"></i>
			<span><?php esc_html_e('Insight', 'workintry'); ?></span>
		</a> 		
	</li>
<?php 
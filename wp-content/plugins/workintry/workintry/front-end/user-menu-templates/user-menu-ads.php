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
$reference 		 = (isset($_GET['rule']) && $_GET['rule'] <> '') ? sanitize_text_field($_GET['rule']) : '';
$mode 			 = (isset($_GET['source']) && $_GET['source'] <> '') ? sanitize_text_field($_GET['source']) : '';
$user_identity 	 = $current_user->ID;

$url_identity = $user_identity;
if (isset($_GET['identity']) && !empty($_GET['identity'])) {
	$url_identity = sanitize_text_field($_GET['identity']);
}

$profile_page = codesquare_workintry_get_profile_url();
?>
	
	<li class="<?php echo ( $reference === 'listings' ? '' : ''); ?>">
		<a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'listings', $user_identity, '', 'all'); ?>"><i class="lnr lnr-list"></i>			
			<span><?php esc_html_e('Gig Listings', 'workintry'); ?></span>
		</a> 		
	</li>		
	<li>
		<a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'add', $user_identity, '', 'ads', ''); ?>"><i class="lnr lnr-pencil"></i>			
			<span><?php esc_html_e('Post Gig', 'workintry'); ?></span>
		</a> 		
	</li>	
<?php 
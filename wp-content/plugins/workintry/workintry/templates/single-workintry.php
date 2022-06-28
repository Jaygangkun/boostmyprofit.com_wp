<?php 
/**
 *
 * Detail Page
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
global $post;
$post_id = $post->ID;
do_action('codesquare_workintry_add_ad_view', $post->ID, 'ad_views','ads_view_count');

//User details
$wishlist = array();
$commenter_id = '';
if( is_user_logged_in() ){
    global $current_user;
    $wishlist = get_user_meta( $current_user->ID, 'cl_wishlist', true );
    $wishlist = !empty( $wishlist ) ? $wishlist : array();
    $commenter_id = $current_user->ID;
}

//Set class
$class      = 'cf-ad-to-fav cf-detail-wishlist cp-liked';
$save_text = esc_html__('Save this Gig now for future', 'workintry');
if( in_array($post->ID, $wishlist ) ){ 
    $class      = 'cf-detail-wishlist';
    $save_text  = esc_html__('Added to favourites', 'workintry');
}
get_header();
require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/breadcrumb');
?>
<?php
define( 'CSC_WORKINTRY_PLUGIN_SINGLE', plugin_dir_path( __FILE__ ) );
$is_post_live = get_post_status( $post->ID );
if( $is_post_live == 'publish'){
//Page Layout
$page_layout = codesquare_workintry_get_settings_option('details_layout');
$page_layout = !empty( $page_layout ) ? $page_layout : 'default';
while ( have_posts() ) {
	the_post(); global $post;
	require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/home-detail');
}
?>
<div class="clearfix"></div>
<?php
} else {
	?>
	<div id="cp-wrapper" class="cp-wrapper cp-haslayout">
		<div class="container">
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12">
					<div class="alert alert-danger" role="alert">
						<b><?php esc_html_e('Nothing here: ', 'workintry'); ?></b><?php esc_html_e('Gig is not approved yet', 'workintry'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php 
}
get_footer();


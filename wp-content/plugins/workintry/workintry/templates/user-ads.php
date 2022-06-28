<?php
/**
 * Template Name: Author Ads
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/* Define Global Variables */
global $paged, $wp_query, $query_args, $showposts;
get_header();
require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/breadcrumb');	
//Slider
wp_enqueue_script('owl-carousel');
wp_enqueue_style( 'owl-carousel' );
wp_enqueue_style( 'cl-responsive' );
//Get parameter
$posts_author_id 	= !empty( $_GET['author-id'] ) ? sanitize_text_field($_GET['author-id']) : '';
if( !empty( $posts_author_id ) ){
//Prepare query
$pg_page        = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged for single while, page - works on homepage
$paged = max($pg_page, $pg_paged);
$post_type_args = '';
$post_type = 'workintry';
$showposts = 9;
$query_args = array(
	'post_type' 		=> $post_type,	
	'posts_per_page' 	=> $showposts,
	'paged'          	=> $paged,
	'author'			=> $posts_author_id,
);
$ad_posts 				= new WP_Query($query_args);
$total_posts			= $ad_posts->found_posts;
?>
<div class="clearfix"></div>
<section class="wi-freelancersection wi-section-wrap">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-5 col-xl-4">
                <?php require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/author-profile'); ?>
            </div>
            <!-- col-4 ends -->
            <div class="col-12 col-lg-7 col-xl-8 order-first">
            	<div class="row">
	            	<?php if ( $ad_posts->have_posts() ) { ?>
	            		<div class="wi-freelacners">
		                    <?php 
		                        while( $ad_posts->have_posts() ){ 
		                        $ad_posts->the_post();
		                        global $post;
		                        $post_id    = $post->ID;          
		                    ?>
		                    <div class="col-12 col-md-6 col-lg-4 col-xl-4">
		                       <?php do_action('codesquare_workintry_print_ad_grid', $post->ID, 255, 180); ?>
		                    </div>
		                    <?php } wp_reset_postdata(); ?>
		                    <div class="col-12 wi-pagepagination">
								<?php codesquare_workintry_print_pagination_hmtl($total_posts, $showposts); ?>
							</div>
	                	</div>
					<?php } else { 	
					  	do_action('codesquare_workintry_show_warning_message', 'Info!', 'No gig found matching your criteria');	
					} ?>
				</div>
            </div>
            <!-- col-8 ends -->
        </div>
        <!-- row ends -->
    </div>
    <!-- container ends -->
</section>
<!-- section ends -->
<?php } else { ?>
	<section class="wi-freelancersection wi-section-wrap">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<?php do_action('codesquare_workintry_show_warning_message', 'Info!', 'No gig found matching your criteria'); ?>
				</div>
			</div>
		</div>
	</section>
<?php } ?>
<?php get_footer(); ?>
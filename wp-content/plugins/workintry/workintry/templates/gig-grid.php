<?php
/**
 *
 * Search template for ads grid view
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
global $paged, $wp_query, $query_args, $showposts;
get_header();
require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/breadcrumb');
$ad_posts 				= new WP_Query($query_args);
$total_posts			= $ad_posts->found_posts;
$search_url = codesquare_workintry_get_settings_option('homes_search_page');
$search_url = !empty( $search_url ) ? get_the_permalink( $search_url ) : '';?>
<div class="clearfix"></div>
<!-- Gigs Search -->
<main id="wi-main" class="wi-main">
	<!-- ==== Search Result Start ==== -->
		<section class="wi-resultsection wi-section-wrap">
			<form class="wi-search-form" method="get" action="<?php echo esc_url( $search_url ); ?>">
				<div class="container">
					<div class="row">
						<div class="col-12 col-lg-4 col-xl-3">
							<aside>
								<?php do_action( 'codesquare_workintry_print_homes_search_form' ); ?>
							</aside>
						</div>
						<div class="col-12 col-lg-8 col-xl-9">
							<div class="wi-sresult">
								<div class="wi-sresult-title">
									<h3><?php esc_html_e('Your Search Result', 'workintry'); ?></h3>
									<span><?php echo esc_html( $total_posts ); ?>&nbsp;<?php esc_html_e('results available', 'workintry'); ?>&nbsp;<?php esc_html_e('in your search criteria', 'workintry'); ?>
								</div>								
							</div>
							<div class="wi-searchfilder">
								<div class="wi-filterlist">
									<div class="wi-filter">
										<input type="checkbox" id="seller" name="">
										<label for="seller"><span class="wi-checkboxbtn"><em></em></span> <?php esc_html_e('Online seller', 'workintry'); ?></label>
									</div>
									<div class="wi-filter">
										<input type="checkbox" id="profile" name="">
										<label for="profile"><span class="wi-checkboxbtn"><em></em></span> <?php esc_html_e('With profile photo', 'workintry'); ?></label>
									</div>								
								</div>
								<div class="wi-sortfilter">
									<span class="wi-select">
										<select name="orderby">
											<option value=""><?php esc_html_e('Sort By', 'workintry'); ?></option>
											<option value="latest"><?php esc_html_e('Most Recent', 'workintry'); ?></option>
											<option value="featured" <?php selected( $orderby, 'featured'); ?>><?php esc_html_e('Featured', 'workintry'); ?></option>
											<option value="price" <?php selected( $orderby, 'price'); ?>><?php esc_html_e('Price high to low', 'workintry'); ?></option>
											<option value="price-low" <?php selected( $orderby, 'price-low'); ?>><?php esc_html_e('Price low to high', 'workintry'); ?></option>
											<option value="rating" <?php selected( $orderby, 'rating'); ?>><?php esc_html_e('Ratings', 'workintry'); ?></option>
										</select>
									</span>
								</div>
							</div>
							<div class="wi-searchitems">
								<?php if($ad_posts->have_posts()) { ?>	<?php 			
									while($ad_posts->have_posts()) {
									$ad_posts->the_post();
									global $post;
								?>
								<div class="col-12 col-md-6 col-xl-4">
									<?php do_action('codesquare_workintry_print_ad_grid', $post->ID, 255, 180); ?>
								</div>
								<?php } wp_reset_postdata(); ?>
								<div class="col-12 wi-pagepagination">
									<?php codesquare_workintry_print_pagination_hmtl($total_posts, $showposts); ?>
								</div>
								<?php } else { 
								do_action('codesquare_workintry_show_warning_message', 'Info!', 'No gig found matching your criteria');
								} ?>
							</div>
						</div>
					</div>
				</div>
			</form>
		</section>
	</main>
<!-- Gigs Search -->
<?php get_footer(); ?>


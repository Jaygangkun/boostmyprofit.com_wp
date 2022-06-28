<?php 
/**
 * Listings page template
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/*Global variables*/
global $current_user;
$user_identity 	 = $current_user->ID;
$profile_page = codesquare_workintry_get_profile_url();

$favourites = get_user_meta( $current_user->ID, 'cl_wishlist', true );
$favourites = !empty( $favourites ) ? $favourites : array('0');

//Timestamp 
$current_time = new DateTime();					
$current_time_stamp = $current_time->getTimestamp();

//Prepare query
$pg_page        = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged for single while, page - works on homepage
$paged = max($pg_page, $pg_paged);
$args = array(
	'post_type' 		=> 'workintry',	
	'posts_per_page' 	=> 5,
	'paged'          	=> $paged,
	'post__in'   		=> $favourites,
);

$ads = new WP_Query( $args );
$total_posts = $ads->found_posts;
?>
<!-- dashboard Info Start -->
<div class="pc-haslayout">
	<div class="row">		
		<div class="pc-dashboardinfo-holder d-flex">
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<?php do_action('codesquare_workintry_print_dash_count', $current_user->ID, 'total'); ?>
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<?php do_action('codesquare_workintry_print_dash_count', $current_user->ID, 'featured'); ?>									
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<?php do_action('codesquare_workintry_print_dash_count', $current_user->ID, 'inactive'); ?>					
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">				
				<?php do_action('codesquare_workintry_print_dash_count', $current_user->ID, 'active'); ?>
			</div>
		</div>
	</div>
</div>
<!-- dashboard Info Start -->
<div class="pc-divhaslayout">
	<div class="row">		
		<div class="col-12">
			<!-- My Account Section Start -->
			<div class="pc-dashboardbox pc-listings-holder">
				<div class="pc-dashboardbox-title">
					<h3><?php esc_html_e('My Favourites Gigs', 'workintry'); ?></h3>
				</div>				
				<ul id="filter-masonry" class="pc-listings isotope">
					<?php 
					if( $ads->have_posts() ){
						while( $ads->have_posts() ){
							$ads->the_post();
							global $post;
							$width 	= intval(100);
							$height = intval(100);
							$thumbnail  = codesquare_workintry_get_post_thumbnail($post->ID, $width, $height);
							if( empty( $thumbnail ) ) {
		                    	$thumbnail = CSC_WORKINTRY_PLUGIN_URL .'assets/images/100X100.jpg';
		                	}

		                	//get terms
							$term_args 	= array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all', 'parent' => '0');
							$terms 		= wp_get_post_terms( $post->ID, 'gig_category', $term_args );
							$category = '';
							if( !empty( $terms ) ){
								foreach ($terms as $key => $value) {
									$category = $value->name;				
								}					
							}							

			      			//Date and Address
			      			$address 	= get_post_meta($post->ID, 'cl_address', true);
    						$date 		= get_the_date( 'F j, Y ', $post->id );
			      			
						?>
						<li>
							<div class="pc-listings-item">
								<figure class="pc-listings-img">
									<img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>">
								</figure>
								<div class="pc-listings-content">
									<h3><?php the_title(); ?></h3>
									<span><?php echo esc_attr( substr(get_the_content($post->ID), 0, 200 ) ); ?>[...]</span>
									<?php if( !empty( $address ) ){ ?>
										<span>
											<i class="lnr lnr-map-marker"></i> 
											<?php echo esc_html( $address ); ?>
										</span>
									<?php } ?>								
									<span>
										<i class="lnr lnr-calendar-full"></i> 
										<?php echo esc_html( $date ); ?>
									</span>
								</div>
								<div class="pc-listings-option">
									<a href="<?php the_permalink(); ?>"><i class="lnr lnr-eye"></i> <?php esc_html_e('View', 'workintry'); ?></a>
									<a href="javascript:void(0);" class="cf-delete-from-wish" data-id="<?php echo esc_attr( $post->ID ); ?>" data-user="<?php echo esc_attr( $current_user->ID ); ?>"><i class="lnr lnr-trash"></i> <?php esc_html_e('Remove', 'workintry'); ?></a>
								</div>
							</div>
						</li>
					<?php } wp_reset_postdata(); ?>
					</ul>
					<?php codesquare_workintry_print_pagination_hmtl($total_posts, 5); ?>
					<?php } else { ?>
					<ul class="pc-listings isotope">
						<li class="alert alert-danger" role="alert"><strong><?php esc_html_e('No Gig Found ', 'workintry'); ?></strong><?php esc_html_e ('no gig found in your favourites', 'workintry'); ?></li>
					</ul>
					<?php } ?>	
			</div>
			<!-- My Account Section End -->
		</div>
	</div>
</div>
<!-- dashboard Info End -->
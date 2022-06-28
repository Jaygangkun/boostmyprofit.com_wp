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
$source = !empty( $_GET['source'] ) ? sanitize_text_field($_GET['source']) : 'all';

//Timestamp 
$current_time = new DateTime();					
$current_time_stamp = $current_time->getTimestamp();

//Prepare query
$pg_page        = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged for single while, page - works on homepage
$paged = max($pg_page, $pg_paged);
$args = array(
	'post_type' => 'workintry',
	'author'	=> $current_user->ID,
	'posts_per_page' => 5,
	'paged'          => $paged,
);
//Check as per need
if( $source == 'all' ){
	$args['post_status'] = 'any';
} elseif( $source == 'featured' ){
	$args['post_status'] = 'publish';
	$args['meta_query']  = array(
        array(
           'key' => 'cl_timestamp',
           'value' => $current_time_stamp,
           'compare' => '>',
        )
	);
} elseif( $source == 'active' ){
	$args['post_status'] = 'publish';
} elseif( $source == 'inactive' ){
	$args['post_status'] = 'draft';
} else {
	$args['post_status'] = 'publish';
}

$ads 			= new WP_Query( $args );
$total_posts	= $ads->found_posts;
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
					<h3><?php esc_html_e('My Gigs', 'workintry'); ?></h3>
				</div>
				<ul id="pc-listings-tabs" class="pc-listings-tabs">
					<li class="<?php $active_class = codesquare_workintry_print_active_class('all', $source ); echo esc_attr( $active_class ); ?>"><a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'listings', $user_identity, '', 'all'); ?>" class="<?php $active_class = codesquare_workintry_print_active_class('all', $source ); echo esc_attr( $active_class ); ?>"><?php esc_html_e('All', 'workintry'); ?></a></li>
					<li class="<?php $active_class = codesquare_workintry_print_active_class('featured', $source ); echo esc_attr( $active_class ); ?>"><a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'listings', $user_identity, '', 'featured'); ?>" class="<?php $active_class = codesquare_workintry_print_active_class('featured', $source ); echo esc_attr( $active_class ); ?>"><?php esc_html_e('Featured', 'workintry'); ?></a></li>
					<li class="<?php $active_class = codesquare_workintry_print_active_class('active', $source ); echo esc_attr( $active_class ); ?>"><a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'listings', $user_identity, '', 'active'); ?>" class="<?php $active_class = codesquare_workintry_print_active_class('active', $source ); echo esc_attr( $active_class ); ?>"><?php esc_html_e('Active', 'workintry'); ?></a>
					</li>
					<li class="<?php $active_class = codesquare_workintry_print_active_class('inactive', $source ); echo esc_attr( $active_class ); ?>"><a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'listings', $user_identity, '', 'inactive'); ?>" class="<?php $active_class = codesquare_workintry_print_active_class('inactive', $source ); echo esc_attr( $active_class ); ?>"><?php esc_html_e('Inactive', 'workintry'); ?></a>
					</li>					
				</ul>
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
									<a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'edit', $user_identity, '', 'ads', $post->ID); ?>"><i class="lnr lnr-undo"></i> <?php esc_html_e('Edit', 'workintry'); ?></a>
									<a href="javascript:void(0);" class="cf-delete-user-ad" data-id="<?php echo esc_attr( $post->ID ); ?>" data-user="<?php echo esc_attr( $current_user->ID ); ?>"><i class="lnr lnr-trash"></i> <?php esc_html_e('Remove', 'workintry'); ?></a>
								</div>
							</div>
						</li>
					<?php } wp_reset_postdata(); ?>
					</ul>
					<?php codesquare_workintry_print_pagination_hmtl($total_posts, 5); ?>
					<?php } else { ?>
					<ul class="pc-listings isotope">
						<li class="alert alert-danger" role="alert"><?php esc_html_e('No gig found in your selected status', 'workintry'); ?></li>
					</ul>
					<?php } ?>	
			</div>
			<!-- My Account Section End -->
		</div>
	</div>
</div>
<!-- dashboard Info End -->

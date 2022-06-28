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
	'post_type' => 'gig-order',
	'posts_per_page' => 50,
	'paged'          => $paged,
	'post_status' 	 => 'publish',
);
$meta_query_args = array();
$meta_query_args[] = array(
	'key' 		=> 'buyer_id',
	'value'		=> $current_user->ID,
	'compare'	=> '=',
);

//Check as per need
if( $source == 'expenses' ){
	$meta_query_args[] = array(
		'key' 		=> 'status',
		'value'		=> 'pending',
		'compare'	=> '=',
	);
} elseif( $source == 'awaiting' ){
	$meta_query_args[] = array(
		'key' 		=> 'status',
		'value'		=> 'un-paid',
		'compare'	=> '=',
	);
} elseif( $source == 'completed' ){
	$meta_query_args[] = array(
		'key' 		=> 'status',
		'value'		=> 'paid',
		'compare'	=> '=',
	);
} 

//Meta Query Mixing
$query_relation = array('relation' => 'AND',);
$meta_query_args = array_merge($query_relation, $meta_query_args);
$args['meta_query'] = $meta_query_args;
//Run Query
$ads 			= new WP_Query( $args );
$total_posts	= $ads->found_posts;
//Earnings
$currency 					= codesquare_workintry_default_system_currency_sign();
?>
<!-- dashboard Info Start -->
<div class="pc-divhaslayout">
	<div class="row">		
		<div class="col-12">
			<!-- My Account Section Start -->
			<div class="pc-dashboardbox pc-listings-holder">
				<div class="pc-dashboardbox-title">
					<h3><?php esc_html_e('My Purchases', 'workintry'); ?></h3>
				</div>
				<ul id="pc-listings-tabs" class="pc-listings-tabs">
					<li class="<?php $active_class = codesquare_workintry_print_active_class('expenses', $source ); echo esc_attr( $active_class ); ?>"><a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'expenses', $user_identity, '', 'expenses'); ?>" class="<?php $active_class = codesquare_workintry_print_active_class('earnings', $source ); echo esc_attr( $active_class ); ?>"><?php esc_html_e('In Progress', 'workintry'); ?></a>
					</li>
					<li class="<?php $active_class = codesquare_workintry_print_active_class('awaiting', $source ); echo esc_attr( $active_class ); ?>"><a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'expenses', $user_identity, '', 'awaiting'); ?>" class="<?php $active_class = codesquare_workintry_print_active_class('awaiting', $source ); echo esc_attr( $active_class ); ?>"><?php esc_html_e('Awaiting Response', 'workintry'); ?></a></li>
					<li class="<?php $active_class = codesquare_workintry_print_active_class('completed', $source ); echo esc_attr( $active_class ); ?>"><a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'expenses', $user_identity, '', 'completed'); ?>" class="<?php $active_class = codesquare_workintry_print_active_class('completed', $source ); echo esc_attr( $active_class ); ?>"><?php esc_html_e('Completed Jobs', 'workintry'); ?></a></li>						
				</ul>
				<ul id="filter-masonry" class="pc-listings isotope">
					<?php 
					if( $ads->have_posts() ){
						while( $ads->have_posts() ){
							$ads->the_post();
							global $post;		
			      			//Date 
    						$date 		= get_the_date( 'F j, Y g:i a ', $post->id );		
    						$date_from = strtotime( $date );
    						$timestamp = get_post_meta( $post->ID, 'delivery_time', true );
    						$delivery = !empty( $timestamp ) ? human_time_diff( $timestamp, $date_from ) : '';    
    						$expected = !empty( $timestamp ) ? date("F j, Y g:i a", $timestamp ) : '';		
						?>
						<li>
							<div class="pc-listings-item">		
								<div class="pc-listings-content">
									<h3>		
										<a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'order', $user_identity, '', 'buyer', $post->ID); ?>">
											<?php the_title(); ?>
										</a>
									</h3>	
									<span>
										<i class="lnr lnr-calendar-full"></i> 
										<?php echo esc_html( $date ); ?>
									</span>
								</div>
								<div class="pc-listings-option">
									<strong><?php esc_html_e('Expiry Time', 'workintry'); ?></strong>
									<a href="<?php the_permalink(); ?>"><?php echo esc_html( $expected ); ?></a>
										
								</div>
								<div class="pc-listings-option">
									<strong><?php esc_html_e('Delivery Time', 'workintry'); ?></strong>
									<?php if( $source == 'earnings' ){ ?>		<a href="<?php the_permalink(); ?>"><?php echo esc_html( $delivery ); ?> <?php esc_html_e('Expected Delivery Time', 'workintry'); ?></a>
									<?php }elseif( $source == 'awaiting' ){ ?>
										<a href="<?php the_permalink(); ?>"><?php esc_html_e('Delivered', 'workintry'); ?></a>
									<?php } else{ ?>
										<a href="<?php the_permalink(); ?>"><?php esc_html_e('Completed and closed', 'workintry'); ?></a>
									<?php } ?>
								</div>
							</div>
						</li>
					<?php } wp_reset_postdata(); ?>
					</ul>
					<?php codesquare_workintry_print_pagination_hmtl($total_posts, 5); ?>
					<?php } else { ?>
					<ul class="pc-listings isotope">
						<li class="alert alert-danger" role="alert"><?php esc_html_e('Nothing here, ', 'workintry'). esc_html_e('no purchase found in your selection', 'workintry'); ?></li>
					</ul>
					<?php } ?>	
			</div>
			<!-- My Account Section End -->
		</div>
	</div>
</div>
<!-- dashboard Info End -->

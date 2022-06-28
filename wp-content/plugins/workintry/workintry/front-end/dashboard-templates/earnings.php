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
	'key' 		=> 'seller_id',
	'value'		=> $current_user->ID,
	'compare'	=> '=',
);

//Check as per need
if( $source == 'earnings' ){
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
$total_earnings_available 	= codesquare_workintry_get_user_earnings( $current_user->ID );
//Total Pending
$total_earnings_pending 	= codesquare_workintry_get_user_pending_earnings( $current_user->ID );
//User Obtained
$total_income 				= codesquare_workintry_get_user_got_earnings( $current_user->ID );
$currency 					= codesquare_workintry_default_system_currency_sign();
?>
<!-- dashboard Info Start -->
<div class="pc-haslayout">
	<div class="row">		
		<div class="pc-dashboardinfo-holder d-flex">			
			<div class="col-12 col-sm-6 col-md-4 col-lg-4">
				<div class="pc-dashboardinfo pc-dashboardinfo-gig">
					<h4><?php esc_html_e('Earnings in Progress', 'workintry'); ?></h4>
					<h2><strong><span><?php echo esc_html( $currency ) ?></span><?php echo esc_html( $total_earnings_pending ); ?></strong></h2>
				</div>					
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-4">
				<div class="pc-dashboardinfo pc-dashboardinfo-gig">
					<h4><?php esc_html_e('Available Earnings in Account', 'workintry'); ?></h4>
					<h2><strong><span><?php echo esc_html( $currency ) ?></span><?php echo esc_html( $total_earnings_available ); ?></strong></h2>
				</div>	
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-4">
				<div class="pc-dashboardinfo pc-dashboardinfo-gig">
					<h4><?php esc_html_e('Earnings Withdrawaled', 'workintry'); ?></h4>
					<h2><strong><span><?php echo esc_html( $currency ) ?></span><?php echo esc_html( $total_income ); ?></strong></h2>
				</div>					
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
					<h3><?php esc_html_e('My Orders', 'workintry'); ?></h3>
				</div>
				<ul id="pc-listings-tabs" class="pc-listings-tabs">
					<li class="<?php $active_class = codesquare_workintry_print_active_class('earnings', $source ); echo esc_attr( $active_class ); ?>"><a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'earnings', $user_identity, '', 'earnings'); ?>" class="<?php $active_class = codesquare_workintry_print_active_class('earnings', $source ); echo esc_attr( $active_class ); ?>"><?php esc_html_e('In Progress', 'workintry'); ?></a>
					</li>
					<li class="<?php $active_class = codesquare_workintry_print_active_class('awaiting', $source ); echo esc_attr( $active_class ); ?>"><a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'earnings', $user_identity, '', 'awaiting'); ?>" class="<?php $active_class = codesquare_workintry_print_active_class('awaiting', $source ); echo esc_attr( $active_class ); ?>"><?php esc_html_e('Awaiting Response', 'workintry'); ?></a></li>
					<li class="<?php $active_class = codesquare_workintry_print_active_class('completed', $source ); echo esc_attr( $active_class ); ?>"><a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'earnings', $user_identity, '', 'completed'); ?>" class="<?php $active_class = codesquare_workintry_print_active_class('completed', $source ); echo esc_attr( $active_class ); ?>"><?php esc_html_e('Completed Jobs', 'workintry'); ?></a></li>						
				</ul>
				<ul id="filter-masonry2" class="pc-listings isotope">
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
										<a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'order', $user_identity, '', 'seller', $post->ID); ?>">
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
						<li class="alert alert-danger" role="alert"><?php esc_html_e('No gig found in your selected status', 'workintry'); ?></li>
					</ul>
					<?php } ?>	
			</div>
			<!-- My Account Section End -->
		</div>
	</div>
</div>
<!-- dashboard Info End -->
<?php 
	$month = !empty( $_POST['month'] ) ? $_POST['month'] : '';
	$year  = !empty( $_POST['year'] ) ? $_POST['year'] : '';
	$filtertype  = !empty( $_POST['filter-type'] ) ? $_POST['filter-type'] : '';
	//Custom Search
	$custom_args = array(
		'post_type' => 'gig-order',
		'posts_per_page' => 500,
		'post_status' 	 => 'publish',
	);
	$custom_meta_query_args = array();
	$custom_meta_query_args[] = array(
		'key' 		=> 'seller_id',
		'value'		=> $current_user->ID,
		'compare'	=> '=',
	);

	//Check as per need
	if( !empty( $month ) ){
		$custom_meta_query_args[] = array(
			'key' 		=> 'month',
			'value'		=> $month,
			'compare'	=> '=',
		);
	}

	//Year
	if( !empty( $year ) ){
		$custom_meta_query_args[] = array(
			'key' 		=> 'year',
			'value'		=> $year,
			'compare'	=> '=',
		);
	}

	//Type
	if( !empty( $filtertype ) ){
		$custom_meta_query_args[] = array(
			'key' 		=> 'status',
			'value'		=> $filtertype,
			'compare'	=> '=',
		);
	}

	//Meta Query Mixing
	$custom_query_relation = array('relation' => 'AND',);
	$custom_meta_query_args = array_merge($custom_query_relation, $custom_meta_query_args);
	$custom_args['meta_query'] = $custom_meta_query_args;
	//Run Query
	$custom_ads 		= new WP_Query( $custom_args );	
	$total_amount = 0;
	$total_amount_before = 0;
	$paypal_id = get_user_meta( $user_identity, 'paypal_id', true );
?>
<!-- dashboard Info Start -->
<div class="pc-divhaslayout stats-earnings">
	<div class="row">		
		<div class="col-12">
			<!-- My Account Section Start -->			
			<div class="pc-dashboardbox pc-listings-holder">
				<div class="pc-dashboardbox-title">
					<h3><?php esc_html_e('Analytics', 'workintry'); ?></h3>
				</div>
				<div class="form-data">			
					<form class="stat-form" method="POST" action="">
						<div class="form-group">
							<label for="type"><?php esc_html_e('Type', 'workintry'); ?></label>
							<select id="type" name="filter-type">
								<option value="">
									<?php esc_html_e('Select', 'workintry'); ?>
								</option>
								<option value="pending" <?php selected( $filtertype, 'pending' ); ?>>
									<?php esc_html_e('In Progress', 'workintry'); ?>
								</option>
								<option value="un-paid" <?php selected( $filtertype, 'un-paid' ); ?>>
									<?php esc_html_e('Pending', 'workintry'); ?>
								</option>
								<option value="completed" <?php selected( $filtertype, 'completed' ); ?>>
									<?php esc_html_e('Completed', 'workintry'); ?>
								</option>
							</select>
						</div>
						<div class="form-group">
							<label for="month"><?php esc_html_e('Month', 'workintry'); ?></label>
							<select id="month" name="month">
								<option value="" <?php selected( $month, '' ); ?>>
									<?php esc_html_e('Select', 'workintry'); ?>
								</option>
								<option value="01" <?php selected( $month, '01' ); ?>>
									<?php esc_html_e('January', 'workintry'); ?>
								</option>
								<option value="02" <?php selected( $month, '02' ); ?>>
									<?php esc_html_e('February', 'workintry'); ?>
								</option>
								<option value="03" <?php selected( $month, '03' ); ?>>
									<?php esc_html_e('March', 'workintry'); ?>
								</option>
								<option value="04" <?php selected( $month, '04' ); ?>>
									<?php esc_html_e('April', 'workintry'); ?>
								</option>
								<option value="05" <?php selected( $month, '05' ); ?>>
									<?php esc_html_e('May', 'workintry'); ?>
								</option>
								<option value="06" <?php selected( $month, '06' ); ?>>
									<?php esc_html_e('June', 'workintry'); ?>
								</option>
								<option value="07" <?php selected( $month, '07' ); ?>>
									<?php esc_html_e('July', 'workintry'); ?>
								</option>
								<option value="08" <?php selected( $month, '08' ); ?>>
									<?php esc_html_e('August', 'workintry'); ?>
								</option>
								<option value="09" <?php selected( $month, '09' ); ?>>
									<?php esc_html_e('September', 'workintry'); ?>
								</option>
								<option value="10" <?php selected( $month, '10' ); ?>>
									<?php esc_html_e('October', 'workintry'); ?>
								</option>
								<option value="11" <?php selected( $month, '11' ); ?>>
									<?php esc_html_e('November', 'workintry'); ?>
								</option>
								<option value="12" <?php selected( $month, '12' ); ?>>
									<?php esc_html_e('December', 'workintry'); ?>
								</option>
							</select>
						</div>
						<div class="form-group">
							<label for="year"><?php esc_html_e('Year', 'workintry'); ?></label>
							<select id="year" name="year">
								<option value="">
									<?php esc_html_e('Select', 'workintry'); ?>
								</option>
								<option value="2021" <?php selected( $year, '2021' ); ?>>
									<?php esc_html_e('2021', 'workintry'); ?>
								</option>							
							</select>
						</div>
						<div class="form-group relative-group">
							<a href="javascript:void(0);" class="filter-earnings"><?php esc_html_e('Fitler', 'workintry'); ?></a>
						</div>
					</form>
				</div>
				<div id="filter-masonry" class="pc-listings isotope">
					<ul class="pc-listings isotope">
						<li class="list">
							<div class="title">
								<h2><?php esc_html_e('Gig title', 'workintry'); ?></h2>
							</div>
							<div class="date">
								<h2><?php esc_html_e('Order date', 'workintry');  ?></h2>
							</div>
							<div class="total">
								<h2><?php esc_html_e('Gig amount', 'workintry'); ?></h2>
							</div>
							<div class="total">
								<h2><?php esc_html_e('Earnings', 'workintry'); ?></h2>
							</div>
						</li>
						<?php 
						if( $custom_ads->have_posts() ){	
						while( $custom_ads->have_posts() ){
							$custom_ads->the_post();					
							global $post;
							$earning = get_post_meta( $post->ID, 'seller_amount', true );
							$total_amount = $total_amount + $earning;
							$total = get_post_meta( $post->ID, 'amount', true );
							$total_amount_before = $total_amount_before + $total;
							$timestamp = get_post_meta( $post->ID, 'timestamp', true );
							$expected = !empty( $timestamp ) ? date("F j, Y g:i a", $timestamp ) : '';
							?>
							<li class="list">
								<div class="title">
									<p><?php the_title(); ?></p>
								</div>
								<div class="date">
									<p><?php echo esc_html( $expected ); ?></p>
								</div>
								<div class="total">
									<p><?php echo esc_html( $currency ); ?><?php echo esc_html( $total ); ?></p>
								</div>
								<div class="total">
									<p><?php echo esc_html( $currency ); ?><?php echo esc_html( $earning ); ?></p>
								</div>
							</li>
							<?php 
						} wp_reset_postdata(); ?>
						<?php 
							if( !empty( $total_amount ) ){ ?>
								<li class="list">
									<div class="title"></div>
									<div class="date"></div>	
									<div class="total"><strong><?php echo esc_html( $currency ); ?><?php echo esc_html( $total_amount ); ?></strong></div>
									<div class="total"><strong><?php echo esc_html( $currency ); ?><?php echo esc_html( $total_amount_before ); ?></strong></div>
								</li>
								<?php 
							}
						?>

						<?php } else { ?>
						<li class="alert alert-danger" role="alert">	<?php esc_html_e('No gig found in your selected status', 'workintry'); ?></li>
						<?php } ?>
					</ul>					
				</div>
				<!-- Paypal Details -->
				<div class="pc-paypal">
					<h4><?php esc_html_e('Paypal for payouts', 'workintry'); ?></h4>
					<p><?php esc_html_e('Provide your PayPal account email for payouts', 'workintry'); ?></p>
					<div class="paypal-input">
						<input type="email" name="paypal-id" value="<?php echo esc_attr( $paypal_id ); ?>" id="paypal-id">
						<a href="#" class="wc-update-paypal-account"><?php esc_html_e('Save', 'workintry'); ?></a>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- Paypal Details -->
			</div>
			<!-- My Account Section End -->
		</div>
	</div>
</div>
<!-- dashboard stats End -->

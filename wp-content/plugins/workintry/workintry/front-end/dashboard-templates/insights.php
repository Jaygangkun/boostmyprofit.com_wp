<?php 
/**
 * Insights page template
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/*Global variables*/
global $current_user;
$username 	= codesquare_workintry_get_full_username( $current_user->ID );
wp_enqueue_script('chart');
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
<!-- dashboard Info End -->
<!-- Chart Section Start -->
<div class="pc-haslayout">
	<div class="row">		
		<div class="col-12 col-lg-4 pc-lg-mt">
			<div class="pc-dashboardbox">
				<div class="pc-dashboardbox-title">
					<h3><?php esc_html_e('Recent Approved Ads', 'workintry'); ?></h3>
				</div>
				<div class="pc-recent-activities">
					<ul>
						<?php 
						//Timestamp 
						$current_time = new DateTime();					
						$current_time_stamp = $current_time->getTimestamp();
							$args = array(
								'post_type' => array('workintry','workintry_home', 'workintry_vehicle', 'workintry_mobile'),
								'author'	=> $current_user->ID,
								'posts_per_page' => 6,
							);
							$ads = new WP_Query( $args );
						?>
						<?php 
							if( $ads->have_posts() ){
							while( $ads->have_posts() ){
							$ads->the_post();
							global $post;
						?>
						<li class="alert fade show">
							<span><?php esc_html_e('Your Ad', 'workintry'); ?>&nbsp;<em><a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a></em>&nbsp;<?php esc_html_e('has been approved!', 'workintry'); ?></span>
							<a href="javascript:void(0);" class="pc-closebtn" data-dismiss="alert" aria-label="Close"></a>
						</li>
						<?php } wp_reset_postdata(); } else{ ?>
							<li class="alert fade show">
								<span><?php esc_html_e('No approved ads yet', 'workintry'); ?></span>
								<a href="javascript:void(0);" class="pc-closebtn" data-dismiss="alert" aria-label="Close">									
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-8">
			<div class="pc-dashboardbox">
				<div class="pc-dashboardbox-title">
					<h3>
						<?php esc_html_e('Last 7 Days', 'workintry'); ?>
					</h3>
				</div>
				<div class="pc-chartsheet-holder">
					<canvas id="pc-chartsheet" class="pc-chartsheet"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Chart Section End -->
<?php
//Get current day
$current_day = date('l', time());
//Get today's timestamp
$today_stamp = strtotime( date('Y-m-d') );
//Now get today's views count and also check if its not passed yet
$get_current_day_stamp = '';
$workintry_user_views = get_user_meta( $current_user->ID, 'workintry_ad_views', true );
$workintry_user_views = !empty( $workintry_user_views ) ? $workintry_user_views : array();

//Verify if data is there
$get_current_day = !empty( $workintry_user_views[strtolower( $current_day ) ] ) ? $workintry_user_views[strtolower( $current_day ) ] : array();
//As we have got data for current day if there now its time to verify it
if( is_array( $get_current_day ) ){
	//Get saved stamp in db for the user
	$get_current_day_stamp = !empty( $get_current_day['timestamp'] ) ? $get_current_day['timestamp'] : '';	
}
//If today's stam is equal to current stamp its OK else date is passed
if( $get_current_day_stamp == $today_stamp ){
	//it means both are same so its today no need to update any thing
} elseif( $today_stamp > $get_current_day_stamp ) {
	//It means day has passed we have to set it to 0	
	$workintry_user_views[strtolower( $current_day ) ]['timestamp'] = $today_stamp;
	$workintry_user_views[strtolower( $current_day ) ]['view'] = '0';
	update_user_meta( $current_user->ID, 'workintry_ad_views', $workintry_user_views );
}

//As we have updated data fetch again to show counts now
//Get meta now
$workintry_user_views = get_user_meta( $current_user->ID, 'workintry_ad_views', true );
$monday = !empty( $workintry_user_views['monday']['view'] ) ? $workintry_user_views['monday']['view'] : '0';
$tuesday = !empty( $workintry_user_views['tuesday']['view'] ) ? $workintry_user_views['tuesday']['view'] : '0';
$wednesday = !empty( $workintry_user_views['wednesday']['view'] ) ? $workintry_user_views['wednesday']['view'] : '0';
$thursday = !empty( $workintry_user_views['thursday']['view'] ) ? $workintry_user_views['thursday']['view'] : '0';
$friday = !empty( $workintry_user_views['friday']['view'] ) ? $workintry_user_views['friday']['view'] : '0';
$saturday = !empty( $workintry_user_views['saturday']['view'] ) ? $workintry_user_views['saturday']['view'] : '0';
$sunday = !empty( $workintry_user_views['sunday']['view'] ) ? $workintry_user_views['sunday']['view'] : '0';
$script = '
		jQuery(document).ready(function(){
		var speedCanvas = document.getElementById("pc-chartsheet");
		Chart.defaults.global.defaultFontFamily = "Source Sans Pro";
		Chart.defaults.global.defaultFontSize = 14;		
		var dataSecond = {
		    label: "'.esc_html__("Daily Ad Views","workintry").'",
		    data: [ '.esc_attr($monday).', '.esc_attr($tuesday).',  '.esc_attr($wednesday).', '.esc_attr($thursday).',  '.esc_attr($friday).', '.esc_attr($saturday).', '.esc_attr($sunday).'],
		    fill: true,		    
		    backgroundColor: ["rgba(255, 99, 132, 0.3)", "rgba(255, 159, 64, 0.3)", "rgba(255, 205, 86, 0.3)", "rgba(75, 192, 192, 0.3)", "rgba(54, 162, 235, 0.3)", "rgba(153, 102, 255, 0.3)", "rgba(168, 50, 160, 0.3)"],			
			 borderColor: ["rgb(255, 99, 132)", "rgb(255, 159, 64)", "rgb(255, 205, 86)", "rgb(75, 192, 192)", "rgb(54, 162, 235)", "rgb(153, 102, 255)", "rgba(168, 50, 160)"],
			 borderWidth: 1,
		};
		var speedData = {
		  labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
		  datasets: [dataSecond],
		};

		var chartOptions = {
		  legend: {
		    display: true,
		    position: "top",
		    labels: {
		      boxWidth: 80,
		      fontColor: "black",
		    }
		  }
		};

		var barChart = new Chart(speedCanvas, {
		  type: "bar",
		  data: speedData,
		  options: chartOptions
		});
	});
	';
	wp_add_inline_script('cl-dashboard', $script,'after');
?>
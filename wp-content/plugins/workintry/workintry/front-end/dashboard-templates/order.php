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
wp_enqueue_script('order-page');
$user_identity 	 = $current_user->ID;
$profile_page = codesquare_workintry_get_profile_url();
$id = !empty( $_GET['id'] ) ? sanitize_text_field($_GET['id']) : '';
$source = !empty( $_GET['source'] ) ? sanitize_text_field($_GET['source']) : '';
if( empty( $source ) || empty( $id ) ){
	esc_html_e('No kiddies please', 'workintry');
	die();
}

$seller_id 	= '';
$buyer_id 	= '';
$status 	= '';
//Check Seller
if( $source == 'seller' ){
	$seller_id 	= get_post_meta( $id, 'seller_id', true );
	$status 	= get_post_meta( $id, 'result', true );
	if( $seller_id != $user_identity ){
		esc_html_e('No kiddies please', 'workintry');
		die();
	}
}

//Check Buyer
if( $source == 'buyer' ){
	$buyer_id 	= get_post_meta( $id, 'buyer_id', true );
	$status 	= get_post_meta( $id, 'result', true );
	if( $buyer_id != $user_identity ){
		esc_html_e('No kiddies please', 'workintry');
		die();
	}
}

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
//Script
wp_enqueue_script( 'counter', CSC_WORKINTRY_PLUGIN_URL .'assets/js/jquery.countdown.min.js', array(), '');	

//DATE
$date 		= get_the_date( 'F j, Y ', $id );		
$date_from = strtotime( $date );
$timestamp = get_post_meta( $id, 'delivery_time', true );
$delivery = !empty( $timestamp ) ? human_time_diff( $timestamp, $date_from ) : '';    
$expected = !empty( $timestamp ) ? date("m/d/y H:i:s", $timestamp ) : '';
//Price
$price = get_post_meta( $id, 'price', true );

//Revisions
$gig_revisions = get_post_meta( $id, 'gig_revisions', true );
$used_gig_revisions = get_post_meta( $id, 'used_gig_revisions', true );
$used_gig_revisions = !empty( $used_gig_revisions ) ? $used_gig_revisions : 0;
$available_revisions = $gig_revisions - $used_gig_revisions;
$available_revisions = !empty( $available_revisions ) ? $available_revisions : 0;

//Get Chat
$chat_table_name = $wpdb->prefix . 'gig_chat_message';
$messages = $wpdb->get_results($wpdb->prepare("SELECT * from $chat_table_name WHERE post_id = %s ORDER BY gig_message_id ASC ", $id
) );

?>
<!-- dashboard Info Start -->
<div class="pc-haslayout" id="post-id" data-id="<?php echo esc_attr( $id ); ?>">
	<div class="row">		
		<div class="pc-dashboardinfo-holder d-flex">			
			<div class="col-12 col-sm-6 col-md-8 col-lg-8">
				<div class="pc-dashboardinfo pc-dashboardinfo-gig">
					<ul id="gig-timer">
						<li>
							<span class="days">00</span>
							<p class="days_text">
								<?php esc_html_e('Days', 'workintry'); ?>
							</p>
						</li>
						<li class="seperator">:</li>
						<li>
							<span class="hours">00</span>
							<p class="hours_text"><?php esc_html_e('Hours', 'workintry'); ?></p>
						</li>
						<li class="seperator">:</li>
						<li>
							<span class="minutes">00</span>
							<p class="minutes_text"><?php esc_html_e('Minutes', 'workintry'); ?></p>
						</li>
						<li class="seperator">:</li>
						<li>
							<span class="seconds">00</span>
							<p class="seconds_text"><?php esc_html_e('Seconds', 'workintry'); ?></p>
						</li>
					</ul>
					<?php 					
					$script = "jQuery(document).ready(function($){			jQuery('#gig-timer').countdown({
							date: '".esc_js($expected)."', 	
							day: 'Day',
							days: 'Days',
							hideOnComplete: false
						}, function (container) {
							alert('Time Passed!');
						});
					});";
					wp_add_inline_script('cl-dashboard', $script,'after');
					?>			
				</div>					
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-4">
				<div class="pc-dashboardinfo pc-dashboardinfo-gig">
					<h4><?php esc_html_e('Price of the Gig', 'workintry'); ?></h4>
					<h2><strong><span><?php echo esc_html( $currency ) ?></span><?php echo esc_html( $price ); ?></strong></h2>
				</div>	
			</div>	
		</div>
	</div>
</div>
<!-- dashboard Info Start -->
<div class="pc-divhaslayout pc-div-order-gig">	
	<div class="row">		
		<div class="col-9 col-xl-9">
			<ul class="chat-messages">
				<?php 
				if( !empty( $messages ) ){				
				foreach ( $messages as $value ) { 
				$message  	= $value->chat_message;
				$message_id = $value->gig_message_id;
				$user_id    = !empty( $value->user_id ) ? $value->user_id : '';
				$time  	= !empty( $value->message_time ) ? $value->message_time : '';
				$files  	= !empty( $value->chat_files ) ? $value->chat_files : '';
				$expected = !empty( $time ) ? date("F j, Y g:i a", $time ) : '';    			
				$username = '';
				$profile_image = '';
				$chat_files = array();
				if( !empty( $files ) ){	
					$chat_files = explode( ',', $files );				
				}
				if( $user_id != '-1' && $user_id != '-2' && $user_id != '-3' ){
					$username 	= codesquare_workintry_get_full_username( $user_id );
					$profile_images = get_user_meta( $user_id, 'profile_image', true);
					$social_picture = get_user_meta( $user_id, 'picture', true );
					$images 		= !empty( $profile_images['image_data'] ) ? $profile_images['image_data'] : array();
					$profile_id  	= !empty( $profile_images['default_image'] ) ? $profile_images['default_image'] : '';
					$profile_image 	= !empty( $profile_id ) ? wp_get_attachment_image_url( $profile_id, 'thumbnail', true, true ) : $social_picture; 
					$profile_image = !empty( $profile_image ) ? $profile_image : CSC_WORKINTRY_PLUGIN_URL .'assets/images/150X150.jpg';
				}

				if( $user_id != '-1' && $user_id != '-2' && $user_id != '-3' ){	
					?>
					<li class="pc-dashboardbox" message-id="<?php echo esc_attr( $message_id ); ?>">
						<div class="pc-listings-item">
							<figure class="pc-listings-img">
								<img src="<?php echo esc_url( $profile_image ); ?>" alt="<?php echo esc_attr( $username ); ?>">
							</figure>
							<div class="pc-listings-content">
								<h5><?php echo esc_html( $username ); ?></h5>
								<span><?php echo esc_html( $message ); ?></span>
								<?php if( !empty( $chat_files ) ){ ?>
								<h6><?php esc_html_e('Attachments', 'workintry'); ?></h6>
								<?php foreach ( $chat_files as $key => $value) {
									$attachment_url = wp_get_attachment_url( $value );
							            $filetype = wp_check_filetype($attachment_url);
							            $ext = $filetype['ext'];
							            $file_name = get_the_title( $value ).'.'.$ext;
								?>
									<a download href="<?php echo esc_url( $attachment_url ); ?>" class="gig-file"><span class="lnr lnr-download"></span><?php echo esc_html( $file_name ); ?></a>
								<?php } ?>
								<?php } ?>
								<span class="date">
									<?php echo esc_html( $expected ); ?>
								</span>
							</div>
						</div>
					</li>
					<?php 
				} elseif( $user_id == '-1' ){ ?>				
					<li class="pc-dashboardbox delivered" message-id="<?php echo esc_attr( $message_id ); ?>">
						<div class="pc-listings-item">	
							<div class="pc-listings-content">	
								<span><?php esc_html_e('Order marked as completed/delivered', 'workintry'); ?></span>
							</div>
						</div>
					</li>
				<?php } elseif( $user_id == '-2' ){ ?>
					<li class="pc-dashboardbox revision" message-id="<?php echo esc_attr( $message_id ); ?>">
						<div class="pc-listings-item">	
							<div class="pc-listings-content">	
								<span><?php esc_html_e('Client demanded a revision', 'workintry'); ?></span>
							</div>
						</div>
					</li>
				<?php } elseif( $user_id == '-3' ){
                        $order_status = 'done';
                    ?>
                        <li class="pc-dashboardbox done" message-id="<?php echo esc_attr( $message_id ); ?>">
                            <div class="pc-listings-item">  
                                <div class="pc-listings-content">   
                                    <span><?php esc_html_e('Job Completed', 'workintry'); ?></span>
                                    <i class="fa fa-handshake" aria-hidden="true" style=""></i>
                                    <em><?php esc_html_e('Seller delivered and buyer accepted', 'workintry'); ?></em>
                                </div>
                            </div>
                        </li>
                    <?php } ?>

				<?php } ?>
				<?php } else{ ?>
					<li class="pc-dashboardbox hidden-must" message-id="none"></li>
				<?php } ?>
			</ul>			
			<!-- My Account Section Start -->
			<div class="pc-dashboardbox pc-listings-holder pc-gig-chat-form">
				<div class="pc-dashboardbox-title">
					<h3><?php esc_html_e('Send a message', 'workintry'); ?></h3>
				</div>
				<div class="form-wrapper">
					<textarea id="message-box" placeholder="<?php esc_attr_e('Message', 'workintry'); ?>"></textarea>
					<div class="bottom-row">
						<a href="javascript:void(0)" class="wi-btn wi-send-gig-file" id="cl-upload-chat-file">
							<span class="lnr lnr-paperclip"></span>
							<?php esc_html_e('Select file', 'workintry'); ?>
						</a>						
						<div id="plupload-chat-container"></div>
						<a href="javascript:void(0)" class="wi-btn wi-send-gig-message">
							<?php esc_html_e('Send', 'workintry'); ?>
						</a>
					</div>
					<div id="myBar1"></div>
					<ul class="cf-chat-files"></ul>
				</div>							
			</div>
			<!-- My Account Section End -->
		</div>
		<div class="col-3 col-xl-3">
			<?php if( $status != 'done' ){ ?>
               <aside>
                  <div class="wi-dbboxsteps">
					<div class="wi-stepstitle">						
						<h3>
							<?php esc_html_e('Project Status', 'workintry'); ?> 
						</h3>
					</div>              
					<?php if( $user_identity == $seller_id ){ ?>
						<?php 
							$result = get_post_meta( $id, 'result', true );
							$class = 'wi-submit-gig-order';
							if( $result == 'awaiting' ){
								$class = '';
							}
						?>
	                    <div class="wi-stepsfooter">
	                        <a href="#" class="cp-btn  wi-gig-order-btn <?php echo esc_attr( $class ); ?>" data-id="<?php echo esc_attr( $id ); ?>"><?php esc_html_e('Mark as Delivered', 'workintry'); ?></a>
	                        <em><?php esc_html_e('Click above button to send your buyer notice about the delivery', 'workintry'); ?></em>
	                    </div>
                	<?php } else { ?>
                		<?php 
                			$result = get_post_meta( $id, 'result', true );
                			$title = esc_html__('Waiting Response', 'workintry');
                			$description = esc_html__('Your seller has not sent this order yet', 'workintry');
							$class = 'wi-gig-wait-btn';
							$class_revision = 'wi-gig-wait-btn hidden-must';
							$disabled = 'disabled';
							if( $result == 'awaiting' ){
								$class = 'wi-gig-wait-btn wi-make-order-done';
								$class_revision = 'wi-gig-wait-btn wi-ask-gig-revision';
								$title = esc_html__('Accept As Complete', 'workintry');
								$description = esc_html__('Your seller delivered order, take action', 'workintry');
								$disabled = 'false';
							}
                		?>
                		<div class="wi-stepsfooter">
	                        <a href="javascript:void(0)" class="wi-btn <?php echo esc_attr( $class ); ?>" disabled="<?php echo esc_attr( $disabled ); ?>" data-id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></a>
	                        <a href="javascript:void(0)" class="wi-btn <?php echo esc_attr( $class_revision ); ?>" disabled="<?php echo esc_attr( $disabled ); ?>" data-id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html_e('Ask for revision', 'workintry'); ?></a>
	                        <em class="order-arrived"><?php echo esc_html( $description ); ?></em>
	                    </div>
                	<?php } ?>
                  </div>
               </aside>
           <?php } else { ?>
           		<aside>
                  	<div class="wi-dbboxsteps">
						<div class="wi-stepstitle">		
							<h3>
								<?php esc_html_e('Project Status (closed)', 'workintry'); ?> 
							</h3>
						</div>  
	                    <div class="wi-stepsfooter">
	                        <h3 class="wi-btn wi-gig-order-btn">
	                        	<?php esc_html_e('Completed and accepted', 'workintry'); ?>
	                        </h3>
	                        <em><?php esc_html_e('This project have been delivered by seller and accepted by the buyer', 'workintry'); ?></em>
	                    </div>
                  	</div>
               </aside>
               <?php if( $user_identity == $buyer_id ){ ?>
               		<?php 
               			$message 	= '';
               			$rating 	= '';
               			$postid 	= get_post_meta( $id, 'gig_id', true );
               			//Get if user already rated  
				        $args = array(
				            'post_id' => $id,
				            'user_id' => $buyer_id,
				            'count' => false
				        );
				        $comments = get_comments( $args );	
				        if( !empty( $comments[0]->comment_ID ) ){
				            //Exists         
				            $message = !empty( $comments[0]->comment_content ) ? $comments[0]->comment_content : '';
				            $rating  = get_comment_meta( $comments[0]->comment_ID, 'rating', true );
				        }  
               		?>
	               	<aside class="wi-review-form">                  
	                  	<div class="wi-dbboxsteps">
							<div class="wi-stepstitle">		
								<h3>
									<?php esc_html_e('Seller Performance', 'workintry'); ?> 
								</h3>
							</div>  
		                    <div class="wi-stepsfooter">
		                        <em><?php esc_html_e('Rate your seller performance to help others', 'workintry'); ?></em>
		                    </div>
		                    <form class="wi-gig-review-form">
		                    	<select name="rating">
									<option value="" <?php selected( $rating, '' ); ?>>
										<?php esc_html_e('Add Your Rating:', 'workintry'); ?>
									</option>
									<option value="1" <?php selected( $rating, '1' ); ?>><?php esc_html_e('Not Satisfied: 1 star', 'workintry'); ?></option>
									<option value="2" <?php selected( $rating, '2' ); ?>><?php esc_html_e('A Bit Satisfied: 2 stars', 'workintry'); ?></option>
									<option value="3" <?php selected( $rating, '3' ); ?>><?php esc_html_e('Satisfied: 3 stars', 'workintry'); ?></option>
									<option value="4" <?php selected( $rating, '4' ); ?>><?php esc_html_e('Happy: 4 stars', 'workintry'); ?></option>
									<option value="5" <?php selected( $rating, '5' ); ?>><?php esc_html_e('Excellent: 5 stars', 'workintry'); ?></option>
								</select>
								<?php wp_nonce_field('add_new_ad_comment', 'add_new_ad_comment'); ?>
								<textarea name="message" placeholder="<?php esc_attr_e('Message', 'workintry'); ?>"><?php echo esc_html( $message ); ?></textarea>
								<a href="#" class="wi-btn wi-submit-gig-review" data-id="<?php echo esc_attr( $id ); ?>"><?php esc_html_e('Submit', 'workintry'); ?></a>
		                    </form>
	                  	</div>
	               	</aside>
           		<?php } ?>
           <?php } ?>
        </div>
	</div>
</div>
<!-- dashboard Info End -->
<script type="text/template" id="tmpl-append-chat-file">
	<li class="cf-check cf-cross">
		<a href="#" class="cf-cross-sign cf-delete-gallery-image"><i class="fa fa-times"></i></a>
		<span class="file-name">[{{data.response.title}}]</span>
		<input type="hidden" name="gallery[{{data.count}}][id]" value="{{data.response.attachment_id}}" class="get-gig-gallery">
	</li>
</script>

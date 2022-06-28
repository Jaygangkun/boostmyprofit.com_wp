<?php 
/**
 * @Read images from temp folder and upload
 * @return {}
 */
if ( ! function_exists( 'codesquare_workintry_get_dashboard_menu_list' ) ) {
	function codesquare_workintry_get_dashboard_menu_list() {
		global $current_user;			
		
		$menu	= array(
			'insight'	=> array(
				'title' => esc_html__('Insight','workintry')
			),			
			'profile'	=> array(
				'title' => esc_html__('Profile Settings','workintry')
			),							
			'ads'	=> array(
				'title' => esc_html__('Manage Gigs','workintry')
			),
			'earnings'	=> array(
				'title' => esc_html__('Earnings','workintry')
			),
			'expenses'	=> array(
				'title' => esc_html__('Expenses','workintry')
			),			
			'favorite'	=> array(
				'title' => esc_html__('Favourite Gigs','workintry')
			),
			'chat'	=> array(
				'title' => esc_html__('Messages','workintry')
			),
			'packages'	=> array(
				'title' => esc_html__('Update Package','workintry')
			),
			'security'	=> array(
				'title' => esc_html__('Security Settings','workintry')
			),							
			'logout'	=> array(
				'title' => esc_html__('Logout','workintry')
			)
		);
		
		$final_menu	= !empty( $menu_settings ) ? $menu_settings : $menu;
		$menu_list 	= apply_filters('classified_add_custom_dashboard_menu',$final_menu);
		return $menu_list;
	}
	add_filter('codesquare_workintry_get_dashboard_menu_list', 'codesquare_workintry_get_dashboard_menu_list',10,1);
}

/**
 * @get profile URL
 * @return array()
 */
if( !function_exists( 'codesquare_workintry_get_profile_url' ) ){
	function codesquare_workintry_get_profile_url(){
		$profile_page = get_option('workintry');
		$profile_page = !empty( $profile_page['profile_page'] ) ? $profile_page['profile_page'] : '';
		if( !empty( $profile_page ) ){
			return $profile_page = get_the_permalink($profile_page);
		} else {
			return $profile_page = '';
		}
		return '';
	}
}

/**
 * @get profile page ID
 * @return array()
 */
if( !function_exists( 'codesquare_workintry_get_profile_page_id' ) ){
	function codesquare_workintry_get_profile_page_id(){
		$profile_page = get_option('workintry');
		$profile_page = !empty( $profile_page['profile_page'] ) ? $profile_page['profile_page'] : '';
		if( !empty( $profile_page ) ){
			return $profile_page;
		} else {
			return '';
		}
		return '';
	}
}

/**
 * @thumbnail from post id
 * @return url
 */
if (!function_exists('codesquare_workintry_get_post_thumbnail')) {
    function codesquare_workintry_get_post_thumbnail($post_id, $width = '300', $height = '300') {
    	global $post;
    	if( empty( $post_id ) ){
    		return;
    	}       
    	//Proceed if not empty post ID
        if (has_post_thumbnail()) {
            get_the_post_thumbnail();
            $post_thumb_id = get_post_thumbnail_id($post_id);
            $post_thumb_url = wp_get_attachment_image_src($post_thumb_id, array(
                $width,
                $height
                    ), true);
            if ($post_thumb_url[1] == $width and $post_thumb_url[2] == $height) {
                return !empty($post_thumb_url[0]) ? $post_thumb_url[0] : '';
            } else {
                $post_thumb_url = wp_get_attachment_image_src($post_thumb_id, 'full', true);
                return !empty($post_thumb_url[0]) ? $post_thumb_url[0] : '';
            }
        } else {
            return;
        }
    }
}

//Add Custom Admin Earnings page
if( !function_exists( 'codesquare_workintry_create_admin_earnings_page' ) ){
	function codesquare_workintry_create_admin_earnings_page() {
		add_menu_page(__( 'Earnings', 'workintry' ),__( 'Earnings', 'workintry' ),'manage_options','earnings-page','codesquare_workintry_create_earnings_page','dashicons-schedule',3);

	}
	add_action( 'admin_menu', 'codesquare_workintry_create_admin_earnings_page' );
}

//Add actual page
if( !function_exists( 'codesquare_workintry_create_earnings_page' ) ){
	function codesquare_workintry_create_earnings_page() {
	?>
	<h1><?php esc_html_e( 'Earnings/Analytics', 'workintry' ); ?></h1>
	<?php
	//Get current user details
	$user_id = get_current_user_id();
	//In progress all earnings
	$total_in_progress_for_all = codesquare_workintry_get_all_in_progress_earnings();
	//Comission in progress
	$comission_in_progress 		= codesquare_workintry_get_all_comission_in_progress_earnings();
	//Comission available
	$comission_available = codesquare_workintry_get_all_comission_earnings_available();
	//Lifetime earnings from comission
	$life_time_comission_earned = codesquare_workintry_get_all_comission_earnings_lifetime();
	//Owed to users
	$users_owed_earnings = codesquare_workintry_get_all_user_owed_earnings();
	//Earnings
	$total_earnings_available 	= codesquare_workintry_get_user_earnings( $user_id );
	//Total Pending
	$total_earnings_pending 	= codesquare_workintry_get_user_pending_earnings( $user_id );
	$currency 					= codesquare_workintry_default_system_currency_sign();
	//In progress
	$total_in_progress 	= codesquare_workintry_get_earnings_in_progress( $user_id );
	?>
	<!-- dashboard Info Start -->
	<div class="w-haslayout">
		<div class="row">		
			<div class="pc-dashboardinfo-holder d-flex">
				<div class="col-12 col-sm-6 col-md-4 col-lg-4">
					<div class="pc-dashboardinfo pc-dashboardinfo-gig">
						<h4><?php esc_html_e('Earnings in Progress', 'workintry'); ?></h4>
						<p><?php esc_html_e('This amount means total amount of gigs in the progress including comission', 'workintry'); ?></p>
						<h2><strong><span><?php echo esc_html( $currency ) ?></span><?php echo esc_html( $total_in_progress_for_all ); ?></strong></h2>
					</div>					
				</div>
				<div class="col-12 col-sm-6 col-md-4 col-lg-4">
					<div class="pc-dashboardinfo pc-dashboardinfo-gig">
						<h4><?php esc_html_e('Comission in Progress', 'workintry'); ?></h4>
						<p><?php esc_html_e('This amount means total amount of your comission in the progress', 'workintry'); ?></p>
						<h2><strong><span><?php echo esc_html( $currency ) ?></span><?php echo esc_html( $comission_in_progress ); ?></strong></h2>
					</div>					
				</div>
				<div class="col-12 col-sm-6 col-md-4 col-lg-4">
					<div class="pc-dashboardinfo pc-dashboardinfo-gig">
						<h4><?php esc_html_e('Available comission', 'workintry'); ?></h4>
						<p><?php esc_html_e('This amount means total available amount you have earned as comission', 'workintry'); ?></p>
						<h2><strong><span><?php echo esc_html( $currency ) ?></span><?php echo esc_html( $comission_available ); ?></strong></h2>
					</div>					
				</div>
				<div class="col-12 col-sm-6 col-md-4 col-lg-4">
					<div class="pc-dashboardinfo pc-dashboardinfo-gig">
						<h4><?php esc_html_e('Lifetime earnings', 'workintry'); ?></h4>
						<p><?php esc_html_e('This amount means total amount you have earned so far as comission', 'workintry'); ?></p>
						<h2><strong><span><?php echo esc_html( $currency ) ?></span><?php echo esc_html( $life_time_comission_earned ); ?></strong></h2>
					</div>	
				</div>
				<div class="col-12 col-sm-6 col-md-4 col-lg-4">
					<div class="pc-dashboardinfo pc-dashboardinfo-gig">
						<h4><?php esc_html_e('Income you owe', 'workintry'); ?></h4>
						<p><?php esc_html_e('This amount means total amount you owe to users which they earned through gigs', 'workintry'); ?></p>
						<h2><strong><span><?php echo esc_html( $currency ) ?></span><?php echo esc_html( $users_owed_earnings ); ?></strong></h2>
					</div>					
				</div>	
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<!-- Users List -->
	<div class="users-list">
		<?php
			global $wpdb;		
			$total_amount = 0;
			$total_amount_before = 0;
			$amount 	= codesquare_workintry_get_settings_option('minimum');
			$amount 	= !empty( $amount ) ? $amount : 50;
			$fee 		= codesquare_workintry_get_settings_option('fee');
			$fee 		= !empty( $fee ) ? $fee : 0;			
			$payment 	= $amount + $fee;
			$limit 		= 300;		
			$table 		= $wpdb->prefix . 'workintry_earnings';
			//Get fee here by now we setting it to 2			
			//Earnings plus fee			
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT DISTINCT user_id, SUM(CASE WHEN type = 'sell' AND status = 'un-paid' THEN user_amount ELSE 0 END) -
					SUM(CASE WHEN type = 'buy' AND status = 'un-paid' THEN user_amount ELSE 0 END)
					AS final_result 
					FROM $table WHERE ( user_id > 0 )
					GROUP BY user_id
					HAVING final_result >= %d  LIMIT %d", $payment, $limit					
			), ARRAY_A);
			$currency = codesquare_workintry_default_system_currency_sign();
		?>
		<div id="filter-masonry" class="pc-listings isotope">
			<ul class="pc-listings isotope">
				<li class="list">
					<div class="title">
						<h2><?php esc_html_e('User Name', 'workintry'); ?></h2>
					</div>	
					<div class="total">
						<h2><?php esc_html_e('Earnings in progress', 'workintry'); ?></h2>
					</div>				
					<div class="total">
						<h2><?php esc_html_e('Available earnings', 'workintry'); ?></h2>
					</div>
				</li>
				<?php 
				if( !empty( $earnings_query ) ){	
				foreach ($earnings_query as $key => $value) {
					$id  = !empty( $value['user_id'] ) ? $value['user_id'] : '';
						/*We can set here if we want to extract user admin from here or only others can see earnings*/
						$total_earnings_available 	= codesquare_workintry_get_user_earnings( $id );	
						$total_earnings_pending 	= codesquare_workintry_get_user_pending_earnings( $id );
						$total_amount = $total_amount + $total_earnings_available;
						$total_amount_before = $total_amount_before + $total_earnings_pending;
					?>
						<li class="list">
							<div class="title">
								<p><?php echo codesquare_workintry_get_full_username($id); ?></p>
							</div>							
							<div class="total">
								<p><?php echo esc_html( $currency ); ?><?php echo esc_html( $total_earnings_pending ); ?></p>
							</div>	
							<div class="date">
								<p><?php echo esc_html( $currency ); ?><?php echo esc_html( $total_earnings_available ); ?></p>
							</div>					
						</li>
					<?php 					
				}  ?>
				<?php 
				if( !empty( $total_amount ) ){ ?>
					<li class="list">
						<div class="title"><p><strong><?php esc_html_e('Total', 'workintry'); ?></strong></p></div>	
						<div class="total"><p><strong><?php echo esc_html( $currency ); ?><?php echo esc_html( $total_amount_before ); ?></strong></p></div>
						<div class="total"><p><strong><?php echo esc_html( $currency ); ?><?php echo esc_html( $total_amount ); ?></strong></p></div>
					</li>
					<?php 
				}
			?>
				<?php } else { ?>
				<li class="alert alert-danger" role="alert">	<?php esc_html_e('No user Found ', 'workintry'). esc_html_e('No user found with available funds greater than', 'workintry'); ?>&nbsp;<b><?php echo esc_html( $currency ); ?><?php echo esc_html( $amount ); ?></b></li>
				<?php } ?>
			</ul>
			<div class="release-payments">					
				<a href="#" class="rwmb-button cl-make-payments button" data-section="advanced-section"><?php esc_html_e('Release Payments', 'workintry'); ?></a>
			</div>
		</div>
	</div>
	<!-- Users List -->
	<?php 


	}
}

//Make payments
if( is_admin() ){
require_once codesquare_workintry_addon_template_exsits('includes/paypal/config');
require_once codesquare_workintry_addon_template_exsits('includes/paypal/autoload');
require_once codesquare_workintry_addon_template_exsits('includes/paypal/MassPay');
}
if( !function_exists( 'codesquare_workintry_process_payment' ) ){
    function codesquare_workintry_process_payment(){        
        //Earnings Processings
        global $current_user, $wpdb;
        $response = array();
        $month  = date('m');
        $year   = date('Y');

        //Start Payments
        $amount 	= codesquare_workintry_get_settings_option('minimum');
		$amount 	= !empty( $amount ) ? $amount : 50;
		$fee 		= codesquare_workintry_get_settings_option('fee');
		$fee 		= !empty( $fee ) ? $fee : 0;			
		$payment 	= $amount + $fee;
		$limit 		= 300;
		$table 		= $wpdb->prefix . 'workintry_earnings';
		//Get fee here by now we setting it to 2			
		//Earnings plus fee			
		$users_query = $wpdb->get_results(
		$wpdb->prepare(
				"SELECT DISTINCT user_id, SUM(CASE WHEN type = 'sell' AND status = 'un-paid' THEN user_amount ELSE 0 END) -
				SUM(CASE WHEN type = 'buy' AND status = 'un-paid' THEN user_amount ELSE 0 END)
				AS final_result 
				FROM $table WHERE ( user_id > 0 )
				GROUP BY user_id
				HAVING final_result >= %d  LIMIT %d", $payment, $limit					
		), ARRAY_A);

        if( !empty( $users_query ) ){        	
        $processed_users 	= 0;
        $failed_users 		= 0;         
        foreach ( $users_query as $key => $value ) { 
            $user_id    = !empty( $value['user_id'] ) ? $value['user_id'] : '';            
            $offset     = get_option('gmt_offset') * intval(60) * intval(60);
            $price_symbol    	= get_woocommerce_currency_symbol();
            $paypal_type 		= codesquare_workintry_get_settings_option('api_status');
            $paypal_id 			= codesquare_workintry_get_settings_option('paypal_user');
            $paypal_password 	= codesquare_workintry_get_settings_option('paypal_password');
            $paypal_signature 	= codesquare_workintry_get_settings_option('paypal_signature');
            //Check settings           
            if( empty( $paypal_id ) || empty( $paypal_password ) || empty( $paypal_signature ) ){
                $response['message'] = 'Your Paypal settings are empty';
                wp_send_json( $response );
            }
            $minamount      = codesquare_workintry_get_settings_option('minimum'); 
            $minamount 		= !empty( $minamount ) ? $minamount : 50;
            //Set to paypal we can add others later
            $payment_type = 'paypal';
            //Get User Payment Details
            $user_paypal_id = get_user_meta( $user_id, 'paypal_id', true );        
            //Proceed            
            if( is_email( $user_paypal_id ) ){
            	$total_users = array();
            	//We can move on now to process payment
            	if( !empty( $payment_type ) ){
	                $status_unpaid  = 'un-paid';
	                $table = $wpdb->prefix . 'workintry_earnings';
	                $type = 'sell';
	                //Get user test if it is available
	                $earnings_query = $wpdb->get_results(
					$wpdb->prepare(
							"SELECT *, SUM(CASE WHEN type = 'sell' AND status = 'un-paid' THEN user_amount ELSE 0 END) -
							SUM(CASE WHEN type = 'buy' AND status = 'un-paid' THEN user_amount ELSE 0 END)
							AS final_result 
							FROM $table WHERE ( user_id = %d )
							GROUP BY user_id
							HAVING final_result >= %d", $user_id, $minamount					
					), ARRAY_A);

	                //Process countings
	                $amount_to_process  = 0;
	                if( !empty( $earnings_query ) ){
	                    foreach( $earnings_query as $kye => $value ){
	                        $amount_to_process  = $amount_to_process + $value['final_result'];
	                        //Update record
	                        $update_data = array(
	                            'status' => 'paid',
	                        );
	                        $where_ids['id'] = intval($value['id']);
	                    }   
	                }

	                //Send query to pay now for this user
	                if( !empty( $user_paypal_id ) && isset( $amount_to_process ) && $amount_to_process > $minamount ){
                        $users = array();
                        $users['id']        = $user_id;
                        $users['amount']    = $amount_to_process;
                        
                        $item = array(
                            'l_email' => $user_paypal_id,                            
                        	// Required.  Email address
                            'l_receiverid' => '',
                           	//Amount
                            'l_amt' => $amount_to_process,
                            // Required.  Payment amount.
                            'l_uniqueid' => $user_id,
                            // Transaction-specific ID number for tracking in an accounting system.
                            'l_note' => esc_html__('Payment from the Earnings', 'workintry'),                   
                        );
                        $total_users[] = $item;
	                }
                	
                	//Send payment request and get result
                	$reply = pay_to_my_clinets($paypal_type, $paypal_id, $paypal_password, $paypal_signature, $total_users );
					                	
                	//Next Take action based on the result from above
	                if( !empty( $reply ) && $reply == 'Success' ){
	                	$processed_users++;
	                    //Update earning table
	                    $earnings_table  = $wpdb->prefix . 'withdrawal_earnings';
	                    $earnings_data   = array();
	                    $earnings_data['user_id']       = $user_id;
	                    $earnings_data['amount']        = $amount_to_process;
	                    $earnings_data['currency_symbol']   = $price_symbol;
	                    $earnings_data['payment_method']    = esc_html__('PayPal', 'workintry').'&nbsp;'.esc_html__('to','workintry').'&nbsp;'.'paypal';

	                    $earnings_data['processed_date'] = current_time('mysql');
	                    $earnings_data['timestamp'] = time();
	                    $earnings_data['status']    = 'paid';
	                    $earnings_data['year']      = date('Y');
	                    $earnings_data['month']     = date('m');

	                    $is_update  = $wpdb->insert($earnings_table, $earnings_data); //update

	                    if( $is_update ){
	                        //update table
	                        $status_unpaid  = 'un-paid';
	                        $table = $wpdb->prefix . 'workintry_earnings';

	                        $earnings_query = $wpdb->get_results(
	                        $wpdb->prepare(
	                                "SELECT * FROM $table 
	                                WHERE $table.user_id = %d
	                                AND $table.status = %s ", 
	                                $user_id,
	                                $status_unpaid
	                        ), ARRAY_A);
	                        if( !empty( $earnings_query ) ){
	                            $amount_to_process = 0;
	                            foreach( $earnings_query as $kye => $value ){
	                                $amount_to_process  = $amount_to_process + $value['user_amount'];

	                                //Update record
	                                $update_data = array(
	                                    'status' => 'paid',
	                                );

	                                $wpdb->update($table, $update_data, intval($value['id']));

	                                $wpdb->update( 
	                                    $table, 
	                                    array( 
	                                        'status' => 'paid',  
	                                    ), 
	                                    array( 'ID' => $value['id'] ), 
	                                    array( 
	                                        '%s',   
	                                        '%d'    
	                                    ), 
	                                    array( '%d' ) 
	                                );
	                            }  
	                        }
	                    }
	                } elseif( $reply == 'Failure' ){
	                	$failed_users++; 
	                }       
            	} 
            } else {
            	/*We can just ignore it or can send an email to let client know that payment is rejected as no payment settings added by client*/
            	$failed_users++;
            }                      
        }
    	} else {
    		$response['message'] = 'No users left to get paid';
            wp_send_json( $response );
    	}
        if( $processed_users || $failed_users ){ 
        	$response['message'] = $processed_users. ' '.  esc_html__('users paid ', 'workintry') . ' '. $failed_users . ' ' . esc_html__('users failed', 'workintry');
        	wp_send_json( $response );
        }
        $response['message'] = esc_html__('Something went wrong', 'workintry');
        wp_send_json( $response );
    }
    add_action('wp_ajax_codesquare_workintry_process_payment', 'codesquare_workintry_process_payment');
}

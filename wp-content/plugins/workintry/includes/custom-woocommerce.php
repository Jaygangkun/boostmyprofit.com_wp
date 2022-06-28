<?php

/**
 * @Woocommerce Custom Settings
 * return {}
 */
if (!class_exists('Codesquare_Workintry_Woocommerce_Custom_Class')) {

    class Codesquare_Workintry_Woocommerce_Custom_Class {
        //Construct method (to include filters)
        function __construct() {                      
			add_action( 'codesquare_workintry_custom_add_to_cart', array(&$this,'codesquare_workintry_custom_add_to_cart'), 10 );
			add_action( 'woocommerce_checkout_fields', array( &$this, 'codesquare_workintry_update_customer_at_checkout' ), 10);          
        }		
		
		/**
		 * @Add fields to checkout
		 * @return {}
		 */
		public function codesquare_workintry_update_customer_at_checkout( $fields ){
			$user = wp_get_current_user();
			$first_name 	= $user ? $user->user_firstname : ''; 
			$last_name 		= $user ? $user->user_lastname : '';
			$phone 			= $user ? $user->phone : '';
			$user_email 	= $user ? $user->user_email : '';
			$address 		= $user ? $user->address : '';
			$city 			= $user ? $user->city : '';
			
			if( !empty( $city ) ){
				$city_obj 	= get_term_by('slug', $city, 'ad_city');
				$city		= !empty( $city_obj->name ) ? $city_obj->name : $city;
			}

			$fields['billing']['billing_first_name']['default'] = $first_name;
			$fields['billing']['billing_last_name']['default']  = $last_name;
			$fields['billing']['billing_phone']['default']  	= $phone;
			$fields['billing']['billing_email']['default']  	= $user_email;
			$fields['billing']['billing_address_1']['default']  = $address;
			$fields['billing']['billing_city']['default']  		= $city;

			return $fields;
		}

		/**
		 * @Add Cart Button
		 * @return {}
		 */
		public function codesquare_workintry_custom_add_to_cart(){
			global $product;
			echo apply_filters( 'woocommerce_loop_add_to_cart_link',
				sprintf( '<a href="%s" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s product_type_%s ajax_add_to_cart  cl-add-to-cart"><i class="lnr lnr-cart"></i><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></a>',
					esc_url( $product->add_to_cart_url() ),
					esc_attr( $product->get_id() ),
					esc_attr( $product->get_sku() ),
					esc_attr( isset( $quantity ) ? $quantity : 1 ),
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					esc_attr( $product->get_type() ),
					esc_html( $product->add_to_cart_text() )
				),
			$product );
		}
		
        /**
         * @Rename Product Menu
         * return {}
         */
        public function codesquare_workintry_custom_label($args) {
            $labels = array(
                'name' => esc_html__('Packages', 'workintry'),
                'singular_name' => esc_html__('Packages', 'workintry'),
                'menu_name' => esc_html__('Packages', 'workintry'),
                'add_new' => esc_html__('Add Package', 'workintry'),
                'add_new_item' => esc_html__('Add New Package', 'workintry'),
                'edit' => esc_html__('Edit Package', 'workintry'),
                'edit_item' => esc_html__('Edit Package', 'workintry'),
                'new_item' => esc_html__('New Package', 'workintry'),
                'view' => esc_html__('View Package', 'workintry'),
                'view_item' => esc_html__('View Package', 'workintry'),
                'search_items' => esc_html__('Search Package', 'workintry'),
                'not_found' => esc_html__('No Packages found', 'workintry'),
                'not_found_in_trash' => esc_html__('No Packages found in trash', 'workintry'),
                'parent' => esc_html__('Parent Package', 'workintry')
            );

            $args['labels'] = $labels;
            $args['description'] = esc_html__('This is where you can add new packages to your online store.', 'workintry');
            return $args;
        }        

    }

    new Codesquare_Workintry_Woocommerce_Custom_Class();
}

/**
 * @Update User meta based on purchased package
 * @return 
 */
if (!function_exists('codesquare_workintry_update_user_data_upon_payment')) {
    add_action('woocommerce_payment_complete', 'codesquare_workintry_update_user_data_upon_payment');
    function codesquare_workintry_update_user_data_upon_payment($order_id) {
        global $current_user, $wpdb;  
        $current_date = current_time('mysql');      
        $order = wc_get_order($order_id);                       
        $user = $order->get_user();
        $items = $order->get_items();
        $offset = get_option('gmt_offset') * intval(60) * intval(60);
        $invoice_id = $order_id;
        //Get dat
        $gig_data = get_post_meta( $order_id, 'workintry_gig_details', true );        
        $payment_type = !empty( $gig_data['payment_type'] ) ? $gig_data['payment_type'] : '';       
        if( $payment_type == 'subscription' ){
            //For package
            foreach ($items as $key => $item) {
                $product_id = $item['product_id'];
                //Check status of the package
                $product_status = get_post_status( $product_id );
                if( $product_status == 'package' ){
                    //if its package proceed
                    $product_qty = !empty($item['qty']) ? $item['qty'] : 1;

                    if ($user) {                                        
                        $package_duration   = get_post_meta($product_id, 'package_duration', true);
                        $featured_duration  = get_post_meta($product_id, 'featured_duration', true);
                        $total_ads          = get_post_meta($product_id, 'total_ads', true);
                        $featured_ads       = get_post_meta($product_id, 'featured_ads', true);
                        $bump_ads           = get_post_meta($product_id, 'bump_ads', true);
                        $highlight_ads      = get_post_meta($product_id, 'highlight_ads', true);               
                        
                        //Prepare data as per cart                
                        $package_duration   = $package_duration * $product_qty;
                        $featured_duration  = $featured_duration * $product_qty;
                        $total_ads          = $total_ads * $product_qty;
                        $featured_ads       = $featured_ads * $product_qty;
                        $bump_ads           = $bump_ads * $product_qty;
                        $highlight_ads      = $highlight_ads * $product_qty;                
                        
                        //Append User Data   
                        $remaining_total_ads    = get_user_meta( $user->ID, 'total_ads', true );
                        $remaining_featured_ads = get_user_meta( $user->ID, 'featured_ads', true );
                        $remaining_bump_ads     = get_user_meta( $user->ID, 'bump_ads', true );
                        $remaining_highlight_ads= get_user_meta( $user->ID, 'highlight_ads', true );

                        $remaining_total_ads           = !empty( $remaining_total_ads ) ? $remaining_total_ads : 0;
                        $remaining_featured_ads        = !empty( $remaining_featured_ads ) ? $remaining_featured_ads : 0;
                        $remaining_bump_ads            = !empty( $remaining_bump_ads ) ? $remaining_bump_ads : 0;
                        $remaining_highlight_ads       = !empty( $remaining_highlight_ads ) ? $remaining_highlight_ads : 0;

                        //Prepare Final Query Data
                        $total_ads            = $total_ads + $remaining_total_ads;
                        $featured_ads         = $featured_ads + $remaining_featured_ads;
                        $bump_ads             = $bump_ads + $remaining_bump_ads;
                        $highlight_ads        = $highlight_ads + $remaining_highlight_ads;

                        //Featured
                        if (!empty($user_featured_date) && $user_featured_date > strtotime($current_date)) {
                            $duration = $featured_duration; //no of days for a featured listings
                            if ($duration > 0) {
                                $featured_date = strtotime("+" . $duration . " days", $user_featured_date);
                                $featured_date = date('Y-m-d H:i:s', $featured_date);
                            }
                        } else {
                            $duration = $featured_duration; //no of days for a featured listings
                            if ($duration > 0) {
                                $featured_date = strtotime("+" . $duration . " days", strtotime($current_date));
                                $featured_date = date('Y-m-d H:i:s', $featured_date);
                            }
                        }
                        
                        /*We are not using featured date (but can be used in future to allow monthly or timely based subscriptions)*/
                        $featured_date      = strtotime($featured_date);             
                        if( $featured_date > strtotime($current_date) ){
                            $is_featured    = '1';
                        } else{
                            $is_featured    = '0';
                        }

                        //update data
                        $package_data = array(
                            'subscription_id'                   => $product_id, 
                            'featured_expiry'                   => $featured_duration,                   
                            'is_featured'                       => $is_featured,
                            'total_ads'                         => intval($total_ads),                    
                            'featured_ads'                      => intval($featured_ads),
                            'bump_ads'                          => intval($bump_ads),
                            'highlight_ads'                     => intval($highlight_ads),
                        );
                                            
                        foreach ($package_data as $key => $value) {
                            update_user_meta($user->ID, $key, $value);
                        }
                            
                        //Prepare Email Data.
                        $product = wc_get_product($product_id);
                        $invoice_id = esc_html__('Order #','workintry') . '&nbsp;' . $order_id;
                        $package_name = $product->get_title();
                        $amount = $product->get_price();
                        $status = $order->get_status();
                        $method = $order->payment_method;
                        $name   = $order->billing_first_name . '&nbsp;' . $order->billing_last_name;

                        //Get UTC Time Format
                        $featured_date = date_i18n('Y-m-d H:i:s', $featured_date);

                        //Get UTC Time Format
                        $order_timestamp = strtotime($order->order_date);
                        $order_local_timestamp = $order_timestamp;
                        $order_date = date_i18n('Y-m-d H:i:s', $order_local_timestamp);

                        $billing_address = $order->get_formatted_billing_address();
                        $mail_to = $order->billing_email;

                        //Send Invoice Email
                        //Email data will go here               
                    }
                }
            } 
        } elseif( $payment_type == 'gig' ) {
            //For gig only
            $new_order_id = codesquare_workintry_create_gig_order_post( $gig_data );
            update_post_meta( $new_order_id, 'order_id', $new_order_id );
            update_post_meta( $order_id, 'gig_id', $new_order_id );
            $order_url = '';                
            $profile_page = codesquare_workintry_get_profile_url();
            if( !empty( $new_order_id ) ){
                $order_url = add_query_arg( array(
                    'rule' => 'order',
                    'source' => 'buyer',
                    'id' => $new_order_id,
                    'identity' => $current_user->ID,
                ), $profile_page );                    
            }
                       
            //SQL
            //Update earnings table
            $invoice_id     = $new_order_id;
            $earnings_table = $wpdb->prefix . 'workintry_earnings';
            $earnings_data                  = array();
            $order_id                       = $invoice_id;
            $earnings_data['user_id']       = $gig_data['seller_id'];
            $earnings_data['amount']        = $gig_data['price'];
            $earnings_data['user_amount']   = $gig_data['user_amount'];
            $earnings_data['admin_amount']  = $gig_data['admin_amount'];;
            $earnings_data['order_id']      = $order_id;
            $earnings_data['gig_id']        = $gig_data['gig_id'];
            $earnings_data['process_date']  = current_time('mysql');
            $earnings_data['timestamp']     = time();
            $earnings_data['order_date']    = date('Y-m-d H:i:s',time());
            $earnings_data['year']          = date('Y', time());
            $earnings_data['month']         = date('m', time());
            $earnings_data['status']        = 'un-paid';
            $earnings_data['type']          = 'sell';
            $wpdb->insert($earnings_table, $earnings_data);
            //Send Email to seller here 

            //Then redirect to that gig order post URL

            //Set response
            $response['type']       = 'success';    
            $response['message']    = esc_html__('Order created successfully.', 'workintry');  
            $response['checkout_url'] = $order_url;            
        }
    }
}


/**
 * @Add http from URL
 * @return {}
 */
if (!function_exists('codesquare_workintry_count_cart_items')) {

    function codesquare_workintry_count_cart_items($product_id) {
        // Initialise the count
        $count = 0;

        if (!WC()->cart->is_empty()) {
            foreach (WC()->cart->get_cart() as $cart_item):
                $items_id = $cart_item['product_id'];

                // for a unique product ID (integer or string value)
                if ($product_id == $items_id) {
                    $count++; // incrementing the counted items
                }
            endforeach;
            // returning counted items 
            return $count;
        }

        return $count;
    }

}

/**
 * Add product for appointments/bookings [For customisations/to make it like booking product also]
 * @since 1.0
 */
if (!function_exists('codesquare_workintry_woo_product_type_options')) {
    add_filter('product_type_options', 'codesquare_workintry_woo_product_type_options', 10, 1);
    function codesquare_workintry_woo_product_type_options( $options ) {
        $options['workintry_woo_appointment'] = array(
            'id' => '_workintry_woo_appointment',
            'wrapper_class' => 'show_if_simple show_if_variable',
            'label' => esc_html__('Allow Bookings', 'workintry'),
            'description' => esc_html__('Allow bookings will work as woo commerce bookings for any booking items etc', 'workintry'),
            'default' => 'no'
        );

        return $options;
    }
}

/**
 * save product type [for our custom bookings ]
 * @since 1.0 set for recurrings if required
 */
if (!function_exists('codesquare_workintry_update_booking_product_meta')) {
    add_action('woocommerce_process_product_meta_variable', 'codesquare_workintry_update_booking_product_meta', 10, 1);
    add_action('woocommerce_process_product_meta_simple', 'codesquare_workintry_update_booking_product_meta', 10, 1);
    function codesquare_workintry_update_booking_product_meta( $post_id ) {
        codesquare_workintry_set_default_booking_value(); 
        $workintry_woo_appointment    = isset($_POST['_workintry_woo_appointment']) ? 'yes' : 'no';
        update_post_meta( $post_id, '_workintry_woo_appointment', $workintry_woo_appointment );
        
    }
}

/**
 * @update to default no value
 * @return 
 */
if (!function_exists('codesquare_workintry_set_default_booking_value')) {

    function codesquare_workintry_set_default_booking_value() {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'order' => 'DESC',
            'orderby' => 'ID',
            'post_status' => 'publish'          
        );

        //Append meta query
        $meta_query_args[] = array(
            'key'           => '_workintry_woo_appointment',
            'value'         => 'yes',
            'compare'       => '=',
        );
        
        $query_relation         = array('relation' => 'AND',);
        $meta_query_args        = array_merge($query_relation, $meta_query_args);
        $args['meta_query']     = $meta_query_args;
        
        $booking_product_id = get_posts($args);
        
        if (!empty( $booking_product_id ) ) {
            $counter = 0;
            foreach ( $booking_product_id as $key => $product ) {
                update_post_meta( $product->ID, '_workintry_woo_appointment', 'no' );
            }
        }
        
    }
}

/*Buy gig*/
if( !function_exists('codesquare_workintry_buy_gig') ){
    function codesquare_workintry_buy_gig(){
        global $current_user, $wpdb;
        $profile_page = codesquare_workintry_get_profile_url();
        $user_id = $current_user->ID;
        $response = array();
        if( !is_user_logged_in() ){
            $response['type']       = 'error';
            $response['message']   = esc_html__('No kiddies please', 'workintry');
            wp_send_json( $response );
        }

        //Validation
        if( empty( $_POST['post_id'] ) || empty( $_POST['pkg'] ) || empty( $_POST['type'] ) ){
            $response['type']       = 'error';
            $response['message']   = esc_html__('Select proper gig fields', 'workintry');
            wp_send_json( $response );
        }

        //POST DATA
        $post_id    = $_POST['post_id'];
        $pkg        = $_POST['pkg'];
        $type       = $_POST['type'];
        $seller_id  = get_post_field( 'post_author', $post_id );
        //Get pkg type        
        $gig_price      = '';
        $gig_delivery   = '';
        $gig_revisions  = '';        
        if( $pkg == 'basic' ){
            $gig_price = get_post_meta( $post_id, 'cl_basic_price', true );            
            $gig_delivery = get_post_meta( $post_id, 'cl_basic_delivery', true );
            $gig_revisions = get_post_meta( $post_id, 'cl_basic_revision', true );
        } elseif( $pkg == 'basicfast' ){
            $gig_price = get_post_meta( $post_id, 'cl_basic_price', true );            
            $gig_price_fast = get_post_meta( $post_id, 'cl_basic_fast_price', true );
            $gig_price = $gig_price + $gig_price_fast;
            $gig_delivery = get_post_meta( $post_id, 'cl_basic_fast_delivery', true );
            $gig_revisions = get_post_meta( $post_id, 'cl_basic_revision', true );            
        } elseif( $pkg == 'gold' ){
            $gig_price = get_post_meta( $post_id, 'cl_gold_price', true );            
            $gig_delivery = get_post_meta( $post_id, 'cl_gold_delivery', true );
            $gig_revisions = get_post_meta( $post_id, 'cl_gold_revision', true );
        } elseif( $pkg == 'goldfast' ){
            $gig_price = get_post_meta( $post_id, 'cl_gold_price', true ); 
            $gig_price_fast = get_post_meta( $post_id, 'cl_gold_fast_price', true ); 
            $gig_price = $gig_price + $gig_price_fast;
            $gig_delivery = get_post_meta( $post_id, 'cl_gold_fast_delivery', true );
            $gig_revisions = get_post_meta( $post_id, 'cl_gold_revision', true );
        } elseif( $pkg == 'diamond' ){
            $gig_price = get_post_meta( $post_id, 'cl_diamond_price', true );   
            $gig_delivery = get_post_meta( $post_id, 'cl_diamond_delivery', true ); 
            $gig_revisions = get_post_meta( $post_id, 'cl_diamond_revision', true );         
        } elseif( $pkg == 'diamondfast' ){
            $gig_price = get_post_meta( $post_id, 'cl_diamond_price', true );
            $gig_price_fast = get_post_meta( $post_id, 'cl_diamond_fast_price', true );
            $gig_price = $gig_price + $gig_price_fast;
            $gig_delivery = get_post_meta( $post_id, 'cl_diamond_fast_delivery', true );
            $gig_revisions = get_post_meta( $post_id, 'cl_diamond_revision', true );
        }
        //Create Timestamp from the delivery days
        $featured_days = intval( $gig_delivery );
        $time_stamp = time() + ( 60 * 60 * 24 * $featured_days );
        //All setup now time to make payment

        //Shares
        $user_amount    = 0;
        $admin_amount   = 0;
        $percentage     = codesquare_workintry_get_settings_option('percent');
        $percentage     = !empty( $percentage ) ? $percentage : 0;
        if( !empty( $gig_price ) ){
            if( isset( $percentage ) && $percentage > 0 ){
                $admin_amount       = $gig_price/100*$percentage;
                $user_amount        = $gig_price - $admin_amount;
                $admin_amount       = number_format($admin_amount, 2);
                $user_amount        = number_format($user_amount , 2);
            }
        }
        if( $type == 'earnings' ){
            //Get total cost of the gig

            //Check if user has enough balance
            $user_earnings = codesquare_workintry_get_user_earnings( $user_id );
            if( $user_earnings >= $gig_price ){                
                //Then Create new gig order post               
                //Add all details to cart data now                
                $cart_meta['price']         = $gig_price;
                $cart_meta['admin_amount']  = $admin_amount;
                $cart_meta['user_amount']   = $user_amount;
                $cart_meta['gig_id']        = $post_id;
                $cart_meta['amount']        = $gig_price;
                $cart_meta['timestamp']     = time();
                $cart_meta['year']          = date('Y', time());;
                $cart_meta['month']         = date('m', time());;
                $cart_meta['status']        = 'pending';
                $cart_meta['type']          = 'payment';
                $cart_meta['seller_id']     = $seller_id;
                $cart_meta['buyer_id']      = $user_id;
                $cart_meta['gig_type']      = $pkg;
                $cart_meta['gig_delivery']  = $gig_delivery;
                $cart_meta['gig_revisions'] = $gig_revisions;
                $cart_meta['delivery_time'] = $time_stamp;
                $new_order_id = codesquare_workintry_create_gig_order_post( $cart_meta );
                update_post_meta( $new_order_id, 'order_id', $new_order_id );
                $order_url = '';                
                if( !empty( $new_order_id ) ){
                    $order_url = add_query_arg( array(
                        'rule' => 'order',
                        'source' => 'buyer',
                        'id' => $new_order_id,
                        'identity' => $current_user->ID,
                    ), $profile_page );                    
                }
                
                //User has money we can charge him here
                //SQL
                //Update earnings table
                $invoice_id     = $new_order_id;
                $earnings_table = $wpdb->prefix . 'workintry_earnings';
                $earnings_data                  = array();
                $order_id                       = $invoice_id;
                $earnings_data['user_id']       = $user_id;
                $earnings_data['amount']        = $gig_price;
                $earnings_data['user_amount']   = $gig_price;
                $earnings_data['admin_amount']  = 0;
                $earnings_data['order_id']      = $order_id;
                $earnings_data['gig_id']        = $post_id;
                $earnings_data['process_date']  = current_time('mysql');
                $earnings_data['timestamp']     = time();
                $earnings_data['order_date']    = date('Y-m-d H:i:s',time());
                $earnings_data['year']          = date('Y', time());
                $earnings_data['month']         = date('m', time());
                $earnings_data['status']        = 'un-paid';
                $earnings_data['type']          = 'buy';
                $wpdb->insert($earnings_table, $earnings_data);
                //SQL
                //For seller
                $earnings_data                  = array();
                $order_id                       = $invoice_id;
                $earnings_data['user_id']       = $seller_id;
                $earnings_data['amount']        = $gig_price;
                $earnings_data['user_amount']   = $user_amount;
                $earnings_data['admin_amount']  = $admin_amount;
                $earnings_data['order_id']      = $order_id;
                $earnings_data['gig_id']        = $post_id;
                $earnings_data['process_date']  = current_time('mysql');
                $earnings_data['timestamp']     = time();
                $earnings_data['order_date']    = date('Y-m-d H:i:s',time());
                $earnings_data['year']          = date('Y', time());
                $earnings_data['month']         = date('m', time());
                $earnings_data['status']        = 'pending';
                $earnings_data['type']          = 'sell';
                $wpdb->insert($earnings_table, $earnings_data);
                //Send Email to seller here 

                //Then redirect to that gig order post URL
                //Set response
                $response['type']       = 'success';    
                $response['message']    = esc_html__('Order created successfully.', 'workintry');  
                $response['checkout_url'] = $order_url;
                wp_send_json( $response );       
            } else {
                //Earnings are less or user kidding
                $response['type']       = 'error';    
                $response['message']    = esc_html__('Your earnings are not that much chose other method to pay.', 'workintry');
                wp_send_json( $response ); 
            }
        } else {
            //Add to cart now
            global $woocommerce;
            $cart_meta      = array();
            $product_id     = codesquare_workintry_get_bookings_item_id();
            //Add all details to cart data now                
            $cart_meta['price']         = $gig_price;
            $cart_meta['admin_amount']  = $admin_amount;
            $cart_meta['user_amount']   = $user_amount;
            $cart_meta['gig_id']        = $post_id;
            $cart_meta['amount']        = $gig_price;
            $cart_meta['timestamp']     = time();
            $cart_meta['year']          = date('Y', time());
            $cart_meta['month']         = date('m', time());
            $cart_meta['status']        = 'pending';
            $cart_meta['type']          = 'payment';
            $cart_meta['seller_id']     = $seller_id;
            $cart_meta['buyer_id']      = $user_id;
            $cart_meta['gig_type']      = $pkg;
            $cart_meta['gig_delivery']  = $gig_delivery;
            $cart_meta['gig_revisions'] = $gig_revisions;
            $cart_meta['payment_type']  = 'gig';
            $cart_meta['delivery_time'] = $time_stamp;
            //Cart Data
            $cart_data = array(
                'product_id'        => $product_id,
                'cart_data'         => $cart_meta,
                'payment_type'      => 'gig',
            );

            //Set woocommerce cart
            $woocommerce->cart->empty_cart();
            $cart_item_data = $cart_data;       
            WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data); 
            //Then redirect to that gig order post URL
            //Set response
            $response['type']       = 'success';    
            $response['message']    = esc_html__('You are redirecting to checkout for payments.', 'workintry');
            $response['checkout_url'] = esc_url($woocommerce->cart->get_checkout_url());        
            wp_send_json( $response ); 
        }
    }
    add_action('wp_ajax_codesquare_workintry_buy_gig', 'codesquare_workintry_buy_gig');
    add_action('wp_ajax_nopriv_codesquare_workintry_buy_gig', 'codesquare_workintry_buy_gig');
}


/**
 * @get booking product ID
 * @return number
 */
if (!function_exists('codesquare_workintry_get_bookings_item_id')) {

    function codesquare_workintry_get_bookings_item_id() {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'order' => 'DESC',
            'orderby' => 'ID',
            'post_status' => 'package',
            'ignore_sticky_posts' => 1
        );
               
        
        $product = get_posts($args);
        
        if (!empty($product)) {
            return (int) $product[0]->ID;
        } else{
            return 0;
        }       
    }
}


/**
 * @Update order meta
 * @return 
 */
if( !function_exists( 'codesquare_workintry_update_cart_meta_data_as_order_meta' ) ){
    function codesquare_workintry_update_cart_meta_data_as_order_meta( $item, $cart_item_key, $values, $order ) {
        
        //Return If not booking post
        if( empty( $values['cart_data'] ) ){
            return;
        }

        //Get meta data 
        $cart_data = $values['cart_data'];
        $order->add_meta_data('workintry_gig_details', $cart_data );
    }

    //Hook in to woocommerce
    add_action( 'woocommerce_checkout_create_order_line_item', 'codesquare_workintry_update_cart_meta_data_as_order_meta', 10, 4 );
}

//Update Price of the cart
if (!function_exists('codesquare_workintry_update_cart_price')) {    
    add_action( 'woocommerce_before_calculate_totals', 'codesquare_workintry_update_cart_price', 99 );
    function codesquare_workintry_update_cart_price( $cart_object ) {     
        //Proceed if cart exists
        if( !WC()->session->__isset( "reload_checkout" )) {
            foreach ( $cart_object->cart_contents as $key => $value ) {
                if( !empty( $value['payment_type'] ) && $value['payment_type'] == 'gig' ){
                    if( isset( $value['cart_data']['price'] ) ){
                        $new_price = floatval( $value['cart_data']['price'] );
                        $value['data']->set_price($new_price);
                    }
                }
            }   
        }
        
    }
}

//Make gig product sellable
add_filter( 'woocommerce_is_purchasable', 'codesquare_classipro_add_package_sellable', 10, 2 );
function codesquare_classipro_add_package_sellable( $value, $product ) {
        //get data
        $value = array();
        $value[] = 'true';        
        $product_id = $product->get_id();
        $status = get_post_status( $product_id );
        if( $status == 'package' ){
            $value[] = 'true';  
            return $value;  
        }        
        return $value;
}

//Create gig order post
if( !function_exists( 'codesquare_workintry_create_gig_order_post' ) ){
    function codesquare_workintry_create_gig_order_post( $gig_data ){
        global $current_user;
        if( !empty( $gig_data ) ){
            extract( $gig_data );            

            //Create Data
            $title      = get_the_title( $gig_id );
            $content    = '';
            $post_data = array(
                'post_title'        => $title,
                'post_status'       => 'publish',
                'post_content'      => $content,
                'post_author'       => $current_user->ID,
                'post_type'         => 'gig-order',
                'post_date'         => current_time('Y-m-d H:i:s')
            );
            //Check user account
            $post_id = wp_insert_post( $post_data );
            foreach ($gig_data as $key => $value) {                
                if( $key == 'admin_amount' ){
                    $key = 'admin_price';
                }
                if( $key == 'user_amount' ){
                    $key = 'seller_amount';
                }
                update_post_meta( $post_id, $key, $value );
            }
            return $post_id;
        }
        return '';
    }
}

//Redirect to order page after payment
if( !function_exists( 'codesquare_workintry_go_to_order_page_dashboard' ) ){
function codesquare_workintry_go_to_order_page_dashboard( $order_get_id  = '' ){
        global $current_user;
        $gig_data = get_post_meta( $order_get_id, 'workintry_gig_details', true );        
        $payment_type = !empty( $gig_data['payment_type'] ) ? $gig_data['payment_type'] : '';
        if( $payment_type == 'gig' ){
            $gig_id = get_post_meta( $order_get_id, 'gig_id', true );
            $order_url = '';                
            $profile_page = codesquare_workintry_get_profile_url();       
            $order_url = add_query_arg( array(
                'rule'      => 'order',
                'source'    => 'buyer',
                'id'        => $gig_id,
                'identity'  => $current_user->ID,
            ), $profile_page );                    
            $url = $order_url;             
            wp_safe_redirect( $url );
            exit;
        }
    }
}

//add this newly created function to the thank you page
add_action( 'woocommerce_thankyou', 'codesquare_workintry_go_to_order_page_dashboard', 10, 1 );
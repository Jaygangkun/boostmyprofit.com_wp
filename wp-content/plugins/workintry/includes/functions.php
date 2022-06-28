<?php 
/**
 * @User registeration
 * Return{}
 */
if (!function_exists('codesquare_workintry_process_registration')) {

    function codesquare_workintry_process_registration($atts = '') {
        global $wpdb;   
		$response = array();
        //Validations        
    	$do_check = check_ajax_referer('register_user_request', 'register_user_request', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        }
         $user_data = array();
        //Show/Hide Fields
        $show_first     =  codesquare_workintry_get_settings_option('show_first_name');
        $show_last      =  codesquare_workintry_get_settings_option('show_last_name');
        $show_gender    =  codesquare_workintry_get_settings_option('show_gender');
        $show_phone     =  codesquare_workintry_get_settings_option('show_register_phone');
        //Add warnings based on settings
        if( $show_first ){
            $user_data['first_name'] = esc_html__('First name is required.', 'workintry');    
        }
        if( $show_last ){
            $user_data['last_name'] = esc_html__('Last name is required.', 'workintry');    
        }
        $user_data['username']         = esc_html__('Username is required.', 'workintry');
        $user_data['email']             = esc_html__('Email is required.', 'workintry');
        if( $show_gender ){
            $user_data['gender'] = esc_html__('Gender is required.', 'workintry');    
            
        }
        if( $show_phone ){
            $user_data['phone'] = esc_html__('Phone is required.', 'workintry');    
            
        }
        $user_data['password']          = esc_html__('Password is required.', 'workintry');
        $user_data['confirm_password']  = esc_html__('Confirm password is required.', 'workintry');        
        $user_role = 'workintry';
         			
        $emailData = array();
        foreach ($user_data as $key => $value) {
            if (empty($_POST['register'][$key])) {
                $response['type'] = 'error';
                $response['message'] = $value;
                wp_send_json($response);                
            }

            if ($key === 'email') {
                if (!is_email($_POST['register'][$key])) {
                    $response['type'] = 'error';
                    $response['message'] = esc_html__('Please add a valid email address.', 'workintry');
                   wp_send_json($response);
                }
            }

            if ($key === 'phone') {
                if (!is_numeric($_POST['register'][$key])) {
                    $response['type'] = 'error';
                    $response['message'] = esc_html__('Please add a valid phone number.', 'workintry');
                   wp_send_json($response);
                }
            }

            if ($key === 'confirm_password') {
                if ($_POST['register']['password'] != $_POST['register']['confirm_password']) {
                    $response['type'] = 'error';
                    $response['message'] = esc_html__('Password does not match.', 'workintry');
                    wp_send_json($response);
                }
            }
        }

        //Agree to terms
        if (empty( $_POST['terms'] ) ) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('You should agree to terms and conditions', 'workintry');
            wp_send_json($response);
        }                
        
        $register_data = wp_unslash( $_POST['register'] );        
        //extract post data
        extract( $register_data );       	
		$user_nicename	= !empty( $user_nicename ) ? $user_nicename : $username;
        $username       = sanitize_text_field($username);
        $password       = !empty( $password ) ? sanitize_text_field($password) : '';
        $email          = sanitize_email( $email );
        $first_name     = sanitize_text_field( $first_name );
        $last_name      = sanitize_text_field( $last_name );
        $phone          = sanitize_text_field( $phone );
        $gender         = sanitize_text_field( $gender );
        $random_password = $password;		
		$userdata = array(
			'user_login'  		=> $username,
			'user_pass'    		=> $random_password,
			'user_email'   		=> $email,  
			'user_nicename'     => $user_nicename,  
		);
		
        $user_identity 	 = wp_insert_user($userdata);
		
        if (is_wp_error($user_identity)) {
            $response['type'] = "error";
            $response['message'] = esc_html__("User already exists. Please try another one.", 'workintry');
            wp_send_json($response);
        } else {
            global $wpdb;
            //Get default settings data
            $total_ads          = codesquare_workintry_get_settings_option('total_ads');
            $featured_ads       = codesquare_workintry_get_settings_option('featured_ads');
            $highlight_ads      = codesquare_workintry_get_settings_option('highlight_ads');
            $bump_ads           = codesquare_workintry_get_settings_option('bump_ads');
            $feature_duration   = codesquare_workintry_get_settings_option('feature_duration');
            wp_update_user(array('ID' => esc_sql($user_identity), 'role' => esc_sql($user_role), 'user_status' => 1));
            $wpdb->update(
                $wpdb->prefix . 'users', array('user_status' => 1), array('ID' => $user_identity)
            );
       								
            update_user_meta($user_identity, 'show_admin_bar_front', false);
            update_user_meta($user_identity, 'email', $email);
            update_user_meta($user_identity, 'activation_status', 'active');
			update_user_meta($user_identity, 'workintry_featured_expiry', '0');
			update_user_meta($user_identity, 'rich_editing', 'true' );
			update_user_meta($user_identity, 'set_profile_view', 0);	

            //Set default user account data
            update_user_meta( $user_identity, 'total_ads', $total_ads);						
            update_user_meta( $user_identity, 'featured_ads', $featured_ads);
            update_user_meta( $user_identity, 'highlight_ads', $highlight_ads);
            update_user_meta( $user_identity, 'bump_ads', $bump_ads);                      
            update_user_meta( $user_identity, 'featured_expiry', $feature_duration);  

            //Set User Data
            update_user_meta( $user_identity, 'first_name', $first_name );
            update_user_meta( $user_identity, 'last_name', $last_name );
            update_user_meta( $user_identity, 'phone', $phone );
            update_user_meta( $user_identity, 'gender', $gender );

			$response_message = esc_html__("Your account has created. You can now login to use your account.", 'workintry');
			
			//Send email admin
            do_action('codesquare_workintry_send_registration_email', $user_identity);        
			$profile_page_id = codesquare_workintry_get_profile_page_id();           
            $profile_url = '';           
			if( !empty($profile_page_id) ) {
                $profile_url = get_the_permalink( $profile_page_id );
				$profile_url	= codesquare_workintry_profile_menu_link($profile_url, 'insight', $user_identity, true);
			}           			                    
			$user_array = array();
			$user_array['user_login'] = $email;
        	$user_array['user_password'] = $random_password;
				
			$status = wp_signon($user_array, false);            

            //Prepare and send final response
            $response['type'] 		= "success";
            $response['message']	= $response_message;
            $response['redirect']   = $profile_url;
            wp_send_json($response);
        }
        die();
    }

    add_action('wp_ajax_codesquare_workintry_process_registration', 'codesquare_workintry_process_registration');
    add_action('wp_ajax_nopriv_codesquare_workintry_process_registration', 'codesquare_workintry_process_registration');
}

/**
 * @Wp Login
 * @return 
 */
if (!function_exists('codesquare_workintry_process_user_login')) {

    function codesquare_workintry_process_user_login() { 
    global $current_user;       
        $user_array = array();
        $response = array();
        $profile_url = '';
        $user_array['user_login']       = sanitize_text_field($_POST['email']);
        $user_array['user_password']    = sanitize_text_field($_POST['password']);                    
        //Validations        
        $do_check = check_ajax_referer('login_user_request', 'login_user_request', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        }

        //Remember Logged in user
        if (isset($_POST['rememberme'])) {
            $remember = sanitize_text_field($_POST['rememberme']);
        } else {
            $remember = '';
        }

        if ($remember) {
            $user_array['remember'] = true;
        } else {
            $user_array['remember'] = false;
        }

        if ($user_array['user_login'] == '') {
            $response['type'] = 'error';
            $response['message'] = esc_html__('All the fields are required', 'workintry');
            wp_send_json($response);                        
        } elseif ($user_array['user_password'] == '') {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Password is required', 'workintry');
            wp_send_json($response);            
        } else {            
            $user = wp_signon($user_array, false);            
            if (is_wp_error($user)) {
                $response['type'] = 'error';
                $response['message'] = esc_html__('Wrong Login/password combination', 'workintry');
                wp_send_json($response);                 
            } else {
                $profile_page_url = codesquare_workintry_get_profile_url();                           
                if( !empty($profile_page_url) ) {
                    $profile_url    = codesquare_workintry_profile_menu_link($profile_page_url, 'insight', $user->ID, true);
                }
                $response['type'] = 'success';
                $response['message'] = esc_html__('Successfully Logged in, redirecting...', 'workintry');
                $response['redirect'] = $profile_url;
                wp_send_json($response);                 
            }            
        }
        die();
    }
    add_action('wp_ajax_codesquare_workintry_process_user_login', 'codesquare_workintry_process_user_login');
    add_action('wp_ajax_nopriv_codesquare_workintry_process_user_login', 'codesquare_workintry_process_user_login');
}

/**
 * @Ad to wishlist
 * @return 
 */
if( !function_exists( 'codesquare_workintry_add_wo_wishlist' ) ){
    function codesquare_workintry_add_wo_wishlist(){
        global $current_user;
        $response = array();
        //Verify user
        if( empty( $_POST['id'] ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please', 'workintry');
            wp_send_json( $response );
        }

        $wishlist = get_user_meta( $current_user->ID, 'cl_wishlist', true);          
        $wishlist = !empty( $wishlist ) ? $wishlist : array();
        $wishlist[] = sanitize_text_field($_POST['id']);

        $wishlist = array_unique( $wishlist );
        update_user_meta( $current_user->ID, 'cl_wishlist', $wishlist );

        //Send Response
        $response['type'] = 'success';
        $response['message'] = esc_html__('Added to your wishlist', 'workintry');
        wp_send_json( $response );
    }
    add_action('wp_ajax_codesquare_workintry_add_wo_wishlist', 'codesquare_workintry_add_wo_wishlist');
    add_action('wp_ajax_nopriv_codesquare_workintry_add_wo_wishlist', 'codesquare_workintry_add_wo_wishlist');
}

/**
 * @Lost Password action
 * @return 
 */
if (!function_exists('codesquare_workintry_lost_password')) {

    function codesquare_workintry_lost_password() {        
        global $wpdb;
        $response = array();    
        $user_input = !empty($_POST['email']) ? sanitize_email($_POST['email']) : '';    
         
        //Validations        
        $do_check = check_ajax_referer('lost_password_request', 'lost_password_request', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        }

        if (empty($user_input)) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Email field is required.', 'workintry');
            wp_send_json( $response );
        } else if (!is_email($user_input)) {
            $response['type'] = "error";
            $response['message'] = esc_html__('Please add a valid email address.', 'workintry');
            wp_send_json( $response );
        }    

        $user_data = get_user_by_email($user_input);
        if ( empty( $user_data ) ) {        
            $response['type'] = 'error';
            $response['message'] = esc_html__('Invalid E-mail address!', 'workintry');
            wp_send_json( $response );
        }

        $user_id    = $user_data->ID;
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));

        if ( empty( $key ) ) {
            //generate reset key
            $key = wp_generate_password(20, false);
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
        }

        $protocol = is_ssl() ? 'https' : 'http';
        //Register Page Link
        $workintry_settings = get_option('workintry');         
        $register_page          = !empty( $workintry_settings['register_page'] ) ? $workintry_settings['register_page'] : '';
        $register_page = !empty( $register_page ) ? esc_url(get_permalink((int) $register_page)) : '';
        $reset_link = 
            add_query_arg(array(
                'action' => 'reset', 
                'secret' => $key, 
                'user' => $user_login,
            ), $register_page )
        ;               

        //Send email to user
        do_action('codesquare_workintry_get_password_email', $user_id, $reset_link);        

        //Prepare and send response
        $response['type'] = "success";
        $response['message'] = esc_html__("A link has been sent, please check your email.", 'workintry');       
        wp_send_json( $response );
    }

    add_action('wp_ajax_codesquare_workintry_lost_password', 'codesquare_workintry_lost_password');
    add_action('wp_ajax_nopriv_codesquare_workintry_lost_password', 'codesquare_workintry_lost_password');
}

/**
 * @Reset Password
 * @return 
 */
if (!function_exists('codesquare_workintry_get_user_password')) {
    function codesquare_workintry_get_user_password() {
        global $wpdb;
        $response = array();          
        //Validations        
        $do_check = check_ajax_referer('change_password_request', 'change_password_request', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        }
        if (isset($_POST['password'])) {
            if ($_POST['password'] != $_POST['retype_password']) {
                // Passwords don't match
                $response['type'] = "error";
                $response['message'] = esc_html__("Password mismatched, try again", 'workintry');
                wp_send_json( $response );
            }

            if (empty($_POST['password'])) {
                $response['type'] = "error";
                $response['message'] = esc_html__("Password should not be empty", 'workintry');
                wp_send_json( $response );
            }
        } else {
            $response['type'] = "error";
            $response['message'] = esc_html__("Something went wrong, no kiddies please", 'workintry');
            wp_send_json( $response );
        }

        if (!empty($_POST['secret']) &&
                ( isset($_POST['reset_action']) && $_POST['reset_action'] == "reset" ) &&
                (!empty($_POST['login']) )
        ) {

            $reset_key = sanitize_text_field($_POST['secret']);
            $user_login = sanitize_text_field($_POST['login']);

            $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;

            if (!empty($reset_key) && !empty($user_data)) {
                $new_password = sanitize_text_field( $_POST['password'] );
                wp_set_password($new_password, $user_data->ID);

                $response['redirect'] = home_url('/');
                $response['type'] = "success";
                $response['message'] = esc_html__("Password changed Successfully", 'workintry');
               wp_send_json( $response );
            } else {
                $response['type'] = "error";
                $response['message'] = esc_html__("No kiddies please", 'workintry');
                wp_send_json( $response );
            }
        }
    }

    add_action('wp_ajax_codesquare_workintry_get_user_password', 'codesquare_workintry_get_user_password');
    add_action('wp_ajax_nopriv_codesquare_workintry_get_user_password', 'codesquare_workintry_get_user_password');
}

/**
 * @Post User Message to Author
 * @return 
 */
if (!function_exists('codesquare_workintry_submit_user_message')) {
    function codesquare_workintry_submit_user_message() { 
        global $wpdb;       
        $response = array();    
        $current_user_id    = sanitize_text_field($_POST['current']);
        $author_id          = sanitize_text_field($_POST['author_id']);
        //Validations        
        $do_check = check_ajax_referer('user_ad_chat_request', 'user_ad_chat_request', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        }

        //Validation
        if( empty( $_POST['id'] ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);
        }
        
        //Form data validation
        if( empty( $_POST['sender-name'] ) ||
            empty( $_POST['sender-email'] ) ||
            empty( $_POST['sender-phone'] ) ||
            empty( $_POST['sender-msg'] ) ){

            //Response
            $response['type '] = 'error';
            $response['message'] = esc_html__('All fields are required', 'workintry');
            wp_send_json( $response );
        }

        //Name validation
        if( strlen( $_POST['sender-name'] ) < 4 ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Name should be atleast 4 characters.', 'workintry');
            wp_send_json($response);
        }

        //Email validation
        if ( !is_email( $_POST['sender-email'] ) ) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Please add a valid email address.', 'workintry');
            wp_send_json($response);
        }

        //Verify Number         
        if ( !is_numeric( $_POST['sender-phone'] ) ) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Phone number should be a valid.', 'workintry');
           wp_send_json($response);
        }

        //Message validation
        if( strlen( $_POST['sender-msg'] ) < 10 ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Message should be atleast 10 characters.', 'workintry');
            wp_send_json($response);
        }

        //Get data
        $name    = sanitize_text_field( $_POST['sender-name'] );
        $email   = sanitize_email( $_POST['sender-email'] );
        $phone   = sanitize_text_field($_POST['sender-phone']);
        $message = sanitize_textarea_field( $_POST['sender-msg'] );
        $post_id = sanitize_text_field($_POST['id']);
        //Submit Message too
        if( !empty( $author_id ) && !empty( $current_user_id ) ){
            $chat_table_name = $wpdb->prefix . 'chat_message';   
            $to_user_id   = $author_id;
            $from_user_id = $current_user_id;
            $chat_message = $message;
            $status       = '1';
            $chat_id = $wpdb->insert( 
                $chat_table_name, 
                array( 
                    'to_user_id' => $to_user_id, 
                    'from_user_id'  => $from_user_id,
                    'chat_message' => $chat_message,
                    'message_time' =>  date("Y-m-d h:i:sa"),
                    'status' => $status
                )          
            );

            if( $chat_id ){
                //Response
                $response['type '] = 'success';
                $response['message'] = esc_html__('Message sent, thank you', 'workintry');
                wp_send_json( $response );
            }
        }        
        do_action('codesquare_workintry_send_ad_author_email', $name, $email, $phone, $message, $post_id );
        //Update Message Counter
        $workintry_messages = get_post_meta( $post_id, 'workintry_messages', true );
        $workintry_messages = !empty( $workintry_messages ) ? $workintry_messages : 0;
        $workintry_messages = $workintry_messages + 1;
        update_post_meta( $post_id, 'workintry_messages', $workintry_messages );

    }

    add_action('wp_ajax_codesquare_workintry_submit_user_message', 'codesquare_workintry_submit_user_message');
    add_action('wp_ajax_nopriv_codesquare_workintry_submit_user_message', 'codesquare_workintry_submit_user_message');
}

/**
 * @Post Comment
 * @return 
 */
if (!function_exists('codesquare_workintry_submit_user_comment')) {
    function codesquare_workintry_submit_user_comment() {
        global $current_user;        
        $response = array();          
        //Validations        
        $do_check = check_ajax_referer('add_new_ad_comment', 'add_new_ad_comment', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        }

        //Validation
        if(  empty( $_POST['post_id'] ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);
        }               
        
        //User Data
        $user_id    = $current_user->ID;
        $user_email = $current_user->user_email;       
        $post_id    = sanitize_text_field($_POST['post_id']);
        $message    = sanitize_textarea_field($_POST['message']);
        $rating     = sanitize_text_field($_POST['rating']);                   
        //Rating
        if( empty( $rating ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Rating field is required', 'workintry');
            wp_send_json($response);
        }

        //comment
        if( empty( $message ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Comment field is required', 'workintry');
            wp_send_json($response);
        }

        //Rating validation
        $ratings_array = array('1', '2', '3', '4', '5' );
        if( in_array( $rating, $ratings_array ) ){
           //OK 
        } else {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Choose only from available options', 'workintry');
            wp_send_json($response);
        }

        //Message
        if( strlen( $_POST['message'] ) < 20 ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Comment should be atleast 20 characters', 'workintry');
            wp_send_json($response);
        }
        
        //Get comments
        $args = array(
            'post_id' => $post_id,
            'user_id' => $user_id,
            'count' => false
        );
        $comments = get_comments( $args );
        if( !empty( $comments[0]->comment_ID ) ){
            wp_delete_comment( $comments[0]->comment_ID, true );
            delete_comment_meta( $comments[0]->comment_ID, 'rating' );
            delete_comment_meta( $comments[0]->comment_ID, 'gig_id' );
        }           
       
        //Delete comment for the respective post
        $gig_id = get_post_meta( $post_id, 'gig_id', true );

        $main_args = array(
            'post_id'   => $gig_id,
            'user_id'   => $user_id,
            'count'     => false
        );
        $main_args['meta_query'] = array(
            array(
                'key'     => 'order_id',
                'value'   => $post_id,
                'compare' => '='
            ),
        ); 
        $post_comments = get_comments( $main_args );
        if( !empty( $post_comments[0]->comment_ID ) ){
            wp_delete_comment( $post_comments[0]->comment_ID, true );
            delete_comment_meta( $post_comments[0]->comment_ID, 'rating' );
            delete_comment_meta( $post_comments[0]->comment_ID, 'order_id' );           
        } 
        //Update main comment
        //current time
        $time = current_time('mysql');
        $data_main = array(
            'comment_post_ID' => $gig_id,
            'comment_author' => codesquare_workintry_get_full_username( $user_id ),
            'comment_author_email' => $user_email,
            'comment_author_url' => 'http://',
            'comment_content' => $message,
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => $user_id,
            'comment_author_IP' => '127.0.0.1',
            'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
            'comment_date' => $time,
            'comment_approved' => 1,
        );

        $comment_id_main = wp_insert_comment( $data_main );
        if( $comment_id_main ){            
            //Update rating
            update_comment_meta( $comment_id_main, 'rating', $rating );
            //Update rating
            update_comment_meta( $comment_id_main, 'order_id', $post_id );

            //Update all comments rating
            $total_ratings = codesquare_workintry_get_comment_average_ratings( $gig_id );
            update_post_meta( $gig_id, 'cl_rating', $total_ratings );
            
        } else {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Some thing went wrong try again', 'workintry');
            wp_send_json($response);
        }

        //current time
        $time = current_time('mysql');
        $data = array(
            'comment_post_ID' => $post_id,
            'comment_author' => codesquare_workintry_get_full_username( $user_id ),
            'comment_author_email' => $user_email,
            'comment_author_url' => 'http://',
            'comment_content' => $message,
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => $user_id,
            'comment_author_IP' => '127.0.0.1',
            'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
            'comment_date' => $time,
            'comment_approved' => 1,
        );

        $comment_id = wp_insert_comment( $data );
        if( $comment_id ){            
            //Update rating
            update_comment_meta( $comment_id, 'rating', $rating );
            //Update rating
            update_comment_meta( $comment_id, 'gig_id', $gig_id );
            $response['type'] = 'success';
            $response['message'] = esc_html__('Your review posted, thanks', 'workintry');
            wp_send_json($response);
        } else {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Some thing went wrong try again', 'workintry');
            wp_send_json($response);
        }
    }

    add_action('wp_ajax_codesquare_workintry_submit_user_comment', 'codesquare_workintry_submit_user_comment');
    add_action('wp_ajax_nopriv_codesquare_workintry_submit_user_comment', 'codesquare_workintry_submit_user_comment');
}

/**
 * @Update paypal ID
 * @return 
 */
if (!function_exists('codesquare_workintry_update_paypal_id')) {
    function codesquare_workintry_update_paypal_id() {
        global $current_user;        
        $response = array();          
        //Validations        
        $user_id = $current_user->ID;
        if( empty( $_POST['paypal_id'] ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Email id required', 'workintry');
            wp_send_json($response);
        }

        //Verify email
        if( !empty( $_POST['paypal_id'] ) && !is_email($_POST['paypal_id'] ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Valid email id required', 'workintry');
            wp_send_json($response);
        }
        $id = $_POST['paypal_id'];
        //Proceed
        update_user_meta( $user_id, 'paypal_id', $id );
        $response['type'] = 'success';
        $response['message'] = esc_html__('Email updated', 'workintry');
        wp_send_json($response);
    }

    add_action('wp_ajax_codesquare_workintry_update_paypal_id', 'codesquare_workintry_update_paypal_id');
    add_action('wp_ajax_nopriv_codesquare_workintry_update_paypal_id', 'codesquare_workintry_update_paypal_id');
}
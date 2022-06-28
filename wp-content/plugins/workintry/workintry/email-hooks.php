<?php 
/**
 * @Registration Email
 * @return 
 */
if( !function_exists( 'codesquare_workintry_send_registration_email' ) ){
	function codesquare_workintry_send_registration_email( $user_id = '' ){
		if( !empty( $user_id ) ){
			add_filter('wp_mail_content_type', function( $content_type ) {
			            return 'text/html';
			});			
			$edit_link = add_query_arg( 'user_id', $user_id, self_admin_url( 'user-edit.php'));	
            $subject = get_option( 'blogname' );
            $body = esc_html__('A new user registered, here is the edit page link', 'workintry');
            $body .= '<br><a href="'.esc_url($edit_link).'">'.esc_html__("View User", 'workintry').'</a>';            
            $user_email = get_option( 'admin_email' );
            wp_mail($user_email, $subject, $body);
        }		
	}
	add_action('codesquare_workintry_send_registration_email', 'codesquare_workintry_send_registration_email', 10,1);
}

/**
 * @Lost Password Email
 * @return 
 */
if( !function_exists( 'codesquare_workintry_get_password_email' ) ){
	function codesquare_workintry_get_password_email( $user_id = '', $reset_link = '' ){
		if( !empty( $user_id ) && !empty( $reset_link ) ){
			$user_data 	= get_userdata($user_id);
			$user_email = $user_data->user_email;
			add_filter('wp_mail_content_type', function ( $content_type ) {
			            return 'text/html';
			});			
			$subject 	= get_option( 'blogname' );
			$body 		= esc_html__('Someone requested for password change here is link to change password', 'workintry');
        	$body .= '<br><a href="'.esc_url($reset_link).'">'.esc_html__("Reset Password", 'workintry').'</a>';        	
        	wp_mail($user_email, $subject, $body);		
        }		
	}
	add_action('codesquare_workintry_get_password_email', 'codesquare_workintry_get_password_email', 10, 2);
}

/**
 * @Delete Account Email
 * @return 
 */
if( !function_exists( 'codesquare_workintry_delete_user_email' ) ){
	function codesquare_workintry_delete_user_email( $user_email = '', $reason = '', $description = ''){
		if( !empty( $user_email ) && !empty( $reason ) && !empty( $description ) ){	
			$description .= "\r\n";
			$description .= esc_html__('Here are my further details', 'workintry') . "\r\n";
			$description .= esc_html__('User Email:', 'workintry') . ' ' . $user_email . "\r\n";
			//Email Admin
			$admin_email = get_option( 'admin_email' );
	        $headers = 'From: '. $user_email . "\r\n" .
	        'Reply-To: ' . $user_email . "\r\n";
	        $sent = wp_mail($admin_email, $reason, strip_tags( $description ), $headers);        
	        if( $sent ) {
	            $response['type'] = 'success';
	            $response['message'] = esc_html__('Request sent, admin will reach you shortly', 'workintry');
	            wp_send_json( $response );
	        } else {
	            $response['type'] = 'error';
	            $response['message'] = esc_html__('Something went wrong please try again', 'workintry');
	            wp_send_json( $response );
	        }
	    }
	}
	add_action('codesquare_workintry_delete_user_email', 'codesquare_workintry_delete_user_email', 10, 3);
}

/**
 * @Delete Account Email
 * @return 
 */
if( !function_exists( 'codesquare_workintry_new_ad_email' ) ){
	function codesquare_workintry_new_ad_email( $post_id = '', $user_id = ''){
		if( !empty( $post_id ) && !empty( $user_id ) ){	
			add_filter('wp_mail_content_type', function ( $content_type ) {
			    return 'text/html';
			});	
			$user_data = get_userdata( $user_id );
			$user_email = $user_data->user_email;
			$edit_link = get_edit_post_link( $post_id );
			$view_link = get_the_permalink( $post_id );
			//Email Admin
			$admin_email = get_option( 'admin_email' );
			$subject = get_option( 'blogname' );
	        $description = 'New Gig Posted' . "<br>";
	        $description .= 'A new gig posted on your website and here is the URL to view that Gig' . "\r\n";
	        $description .= '<br><a href="'.esc_url($view_link).'">View Gig</a>';
	        $description .= '<br><a href="'.esc_url($edit_link).'">Edit Gig</a>';
	        //Send mail
	        wp_mail( $admin_email, $subject, $description);        	       
	    }
	}
	add_action('codesquare_workintry_new_ad_email', 'codesquare_workintry_new_ad_email', 10, 2);
}

/**
 * @Ad Report Email
 * @return 
 */
if( !function_exists( 'codesquare_workintry_send_ad_author_email' ) ){
	function codesquare_workintry_send_ad_author_email( $name = '', $email = '', $phone = '', $message = '', $post_id = '' ){
		if( !empty( $post_id ) && !empty( $message ) ){	
			add_filter('wp_mail_content_type', function ( $content_type ) {
			    return 'text/html';
			});	
						
			$view_link = get_the_permalink( $post_id );
			//Email Admin			
			$user_id 	  = get_post_field( 'post_author', $post_id );
			$author_email = get_the_author_meta( 'user_email', $user_id );
			$subject 	  = get_option( 'blogname' );
	        $description  = 'User contacted on the Gig' . "<br>";
	        $description .= 'A user contacted you on the following Gig' . "\r\n";
	        $description .= '<br><a href="'.esc_url($view_link).'">View Gig</a>';

	        $description .= '<br><br><div><h3>Sender Details:</h3></div>';
	        $description .= '<p>Name: '.esc_html($name).'</p>';
	        $description .= '<p>Email: '.esc_html($email).'</p>';
	        $description .= '<p>Phone: '.esc_html($phone).'</p>';
	        $description .= '<p>Message: '.esc_html($message).'</p>';

	        //Send mail
	        $sent = wp_mail( $author_email, $subject, $description);        	       
	        if( $sent ){
	        	$response['type'] = 'success';
	        	$response['message'] = esc_html__('Message Sent, thank you', 'workintry');
	        	wp_send_json( $response );
	        } else {
	        	$response['type'] = 'error';
	        	$response['message'] = esc_html__('Something went wrong, please try again', 'workintry');
	        	wp_send_json( $response );
	        }
	    } else {
	    	$response['type'] = 'error';
	        $response['message'] = esc_html__('Make sure all fields are provided, please try again', 'workintry');
	        wp_send_json( $response );
	    }
	}
	add_action('codesquare_workintry_send_ad_author_email', 'codesquare_workintry_send_ad_author_email', 10, 5);
}

/**
 * @Ad Report Email
 * @return 
 */
if( !function_exists( 'codesquare_codesquare_workintry_send_ad_report_email' ) ){
	function codesquare_codesquare_workintry_send_ad_report_email( $name = '', $email = '', $reason = '', $message = '', $post_id = '' ){
		if( !empty( $post_id ) && !empty( $reason ) ){	
			add_filter('wp_mail_content_type', function ( $content_type ) {
			    return 'text/html';
			});	
			
			$edit_link = get_edit_post_link( $post_id );
			$view_link = get_the_permalink( $post_id );
			//Email Admin
			$admin_email = get_option( 'admin_email' );
			$subject = get_option( 'blogname' );
	        $description = 'Gig Reported' . "<br>";
	        $description .= 'An gig have been reported on your website and here is the URL to view that Gig' . "\r\n";
	        $description .= '<br><a href="'.esc_url($view_link).'">View Gig</a>';
	        $description .= '<br><a href="'.esc_url($edit_link).'">Edit Gig</a>';

	        $description .= '<br><br><div><h3>Gig Reported By:</h3></div>';
	        $description .= '<p>Name: '.esc_html($name).'</p>';
	        $description .= '<p>Email: '.esc_html($email).'</p>';
	        $description .= '<p>Reason: '.esc_html($reason).'</p>';
	        $description .= '<p>Message: '.esc_html($message).'</p>';
	        //Send mail
	        $sent = wp_mail( $admin_email, $subject, $description);        	       
	        if( $sent ){
	        	$response['type'] = 'success';
	        	$response['message'] = esc_html__('Gig reported, thank you', 'workintry');
	        	wp_send_json( $response );
	        } else {
	        	$response['type'] = 'error';
	        	$response['message'] = esc_html__('Something went wrong, please try again', 'workintry');
	        	wp_send_json( $response );
	        }
	    }
	}
	add_action('codesquare_codesquare_workintry_send_ad_report_email', 'codesquare_codesquare_workintry_send_ad_report_email', 10, 5);
}

<?php 
/**
 * @updaet cart
 * @return 
 */
if (!function_exists('codesquare_workintry_update_user_cart')) {

    function codesquare_workintry_update_user_cart() {
        global $current_user, $woocommerce;       
        
        if (!empty($_POST['id'])) {
            $product_id = sanitize_text_field( $_POST['id'] );
            $product_id = intval($product_id);            
            $woocommerce->cart->empty_cart(); //empty cart before update cart
            
            $is_cart_matched    = codesquare_workintry_count_cart_items($product_id);
            
            if ( isset( $is_cart_matched ) && $is_cart_matched > 0) {
                $json = array();
                $json['type'] = 'success';
                $json['message'] = esc_html__('You have already in cart, We are redirecting to checkout', 'workintry');
                $json['checkout_url'] = esc_url($woocommerce->cart->get_checkout_url());
                wp_send_json($json);                
            }                             

            $cart_data = array(
                'product_id'        => $product_id,                
                'payment_type'      => 'subscription',
            );
            
            if (class_exists('WooCommerce')) {                
                $woocommerce->cart->empty_cart();
                $cart_item_data = $cart_data;
                WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);

                $json = array();
                $json['type'] = 'success';
                $json['message'] = esc_html__('Please wait you are redirecting to checkout page.', 'workintry');
                $json['checkout_url'] = esc_url($woocommerce->cart->get_checkout_url());
                wp_send_json($json);                
            } else {
                $json = array();
                $json['type'] = 'error';
                $json['message'] = esc_html__('Please install WooCommerce plugin to process this order', 'workintry');
            }
        }

        $json = array();
        $json['type'] = 'error';
        $json['message'] = esc_html__('Oops! something is going wrong.', 'workintry');
        wp_send_json($json);        
    }

    add_action('wp_ajax_codesquare_workintry_update_user_cart', 'codesquare_workintry_update_user_cart');
    add_action('wp_ajax_nopriv_codesquare_workintry_update_user_cart', 'codesquare_workintry_update_user_cart');
}

/*
* Profile Updation
*/
if( !function_exists( 'codesquare_workintry_update_user_profile' ) ){
	function codesquare_workintry_update_user_profile(){
		global $current_user;
		$response = array();
		//Validations        
    	$do_check = check_ajax_referer('profile_update_user_request', 'profile_update_user_request', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        }

        $first_name = !empty( $_POST['first-name'] ) ? sanitize_text_field( $_POST['first-name'] ) : '';
        $last_name  = !empty( $_POST['last-name'] ) ? sanitize_text_field( $_POST['last-name'] ) : '';
        $country  = !empty( $_POST['country'] ) ? sanitize_text_field( $_POST['country'] ) : '';
        $city  = !empty( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] ) : '';
        $phone 		= !empty( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
        $description= !empty( $_POST['description'] ) ? sanitize_textarea_field( $_POST['description'] ) : '';
        //Further validations
        //First Name
        if( !empty( $first_name ) ){
            if( strlen( $first_name ) < 2 ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('First name should be atleast 2 characters', 'workintry');
                wp_send_json($response); 
            }
        } else {
            $response['type'] = 'error';
            $response['message'] = esc_html__('First name should be atleast 2 characters', 'workintry');
            wp_send_json($response);
        }
        //Last Name
        if( !empty( $last_name ) ){
            if( strlen( $last_name ) < 2 ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Last name should be atleast 2 characters', 'workintry');
                wp_send_json($response); 
            }
        } else {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Last name should be atleast 2 characters', 'workintry');
            wp_send_json($response);
        }
        //Country
        if( empty( $country ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Country is required', 'workintry');
            wp_send_json($response); 
        }
        //City
        if( empty( $city ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('City is required', 'workintry');
            wp_send_json($response); 
        }
        //Phone
        if( !empty( $phone ) ){
            if(!is_numeric( $phone ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('In phone number only numeric digits allowed!', 'workintry');
                wp_send_json($response); 
            }
        } else {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Phone is required!', 'workintry');
            wp_send_json($response); 
        }

        $website    = !empty( $_POST['website'] ) ? esc_url_raw( $_POST['website'] ) : '';
        //Website
        if( !empty( $website ) ){
            if( $website === $_POST['website'] ){                
                //Nothing here
            } else{
                $response['type'] = 'error';
                $response['message'] = esc_html__('Proper URL with http or https required', 'workintry');
                wp_send_json($response);          
            }                       
        }

        $gender		= !empty( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
        $available_gender = array( 'male', 'female' );

        //Gender
        if( !empty( $gender ) ){
            if( in_array( $gender, $available_gender ) ){

            } else {
                $response['type'] = 'error';
                $response['message'] = esc_html__('Only allowed values can be used!', 'workintry');
                wp_send_json($response); 
            }
        }       

        $address    = !empty( $_POST['address'] ) ? sanitize_text_field( $_POST['address'] ) : '';
        if( !empty( $address ) ){
            if( strlen( $address ) < 10 ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Address must be atleast 10 characters!', 'workintry');
                wp_send_json($response); 
            }
        }
        update_user_meta( $current_user->ID, 'first_name', $first_name );
        update_user_meta( $current_user->ID, 'last_name', $last_name );
        update_user_meta( $current_user->ID, 'phone', $phone );
        update_user_meta( $current_user->ID, 'gender', $gender );
        update_user_meta( $current_user->ID, 'address', $address );
        wp_update_user( array( 'ID' => $current_user->ID, 'user_url' => $website ) );
        update_user_meta( $current_user->ID, 'description', $description );
        //Term Names
        $term       = get_term_by('slug', $country, 'gig_country'); 
        $country    = $term->name;
        $city_term  = get_term_by('slug', $city, 'gig_city'); 
        $city       = $city_term->name;

        update_user_meta( $current_user->ID, 'w_country', $country );
        update_user_meta( $current_user->ID, 'w_city', $city );

        $response['type'] = 'success';
        $response['message'] = esc_html__('Profile Updated!', 'workintry');
        wp_send_json($response); 
	}
	add_action('wp_ajax_codesquare_workintry_update_user_profile', 'codesquare_workintry_update_user_profile');
    add_action('wp_ajax_nopriv_codesquare_workintry_update_user_profile', 'codesquare_workintry_update_user_profile');
}

/**
 * @Reset Password action
 * @return success/error
 */
if (!function_exists('codesquare_workintry_change_user_password')) {

    function codesquare_workintry_change_user_password() {
    	$user 		= wp_get_current_user();              	
        $response 	= array(); 

        //Verification
        $do_check = check_ajax_referer('profile_change_password', 'profile_change_password', false);
        if ( $do_check == false ) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please.', 'workintry');
            wp_send_json($response);            
        }

        //Field Validation
        if( empty( $_POST['password'] ) || empty( $_POST['new-password'] ) || empty( $_POST['retype-password'] ) ){
        	$response['type'] = 'error';
            $response['message'] = esc_html__('All the fields are required!', 'workintry');
            wp_send_json($response);  
        }

        //Verify Old Password
        $password 			= sanitize_text_field( $_POST['password'] );  
        $new_password 		= sanitize_text_field( $_POST['new-password'] );
        $confirm_password	= sanitize_text_field( $_POST['retype-password'] );
        $password_matched	= wp_check_password($password, $user->user_pass, $user->data->ID);
        if ( $password_matched ) {
        	//Password Matched
        	if( $new_password != $confirm_password ){
        		$response['type'] = 'error';
            	$response['message'] = esc_html__('New password did not match!', 'workintry');
            	wp_send_json($response); 
        	}

        	//Update password
        	wp_update_user( array( 'ID' => $user->data->ID, 'user_pass' => esc_attr( $new_password ) ) );
        	$response['type'] = 'success';
            $response['message'] = esc_html__('Password Updated', 'workintry');
            wp_send_json($response); 

        } else {
        	//Warning
        	$response['type'] = 'error';
            $response['message'] = esc_html__('Old password did not match', 'workintry');
            wp_send_json($response);  
        }
            
        $response['type'] = 'error';
        $response['message'] = esc_html__('Something went wrong, please try again', 'workintry');
        wp_send_json($response);   
        
    }

    add_action('wp_ajax_codesquare_workintry_change_user_password', 'codesquare_workintry_change_user_password');
    add_action('wp_ajax_nopriv_codesquare_workintry_change_user_password', 'codesquare_workintry_change_user_password');
}

/**
 * @Upload Profile Photo
 * @return {}
 */
if (!function_exists('codesquare_workintry_profile_image_uploader')) {

    function codesquare_workintry_profile_image_uploader() {

        global $current_user;        
		$response	=  array();

        //Validation		  
        $type  = $_REQUEST['type'];
        if( empty( $type ) ){
            $response['type']     = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json( $response );
        }

        //Handle submitted media files
        $submitted_media    = $_FILES['cf_profile_uploader'];    
        $uploaded_media     = wp_handle_upload($submitted_media, array( 'test_form' => false ) );

        if ( isset( $uploaded_media['file'] ) ) {
            //Getting submitted media file name and type like .jpg, .png
            $file_name = basename( $submitted_media['name'] );
            $file_type = wp_check_filetype( $uploaded_media['file'] );

            // Attachment post data array
            $file_args = array(
                'guid' => $uploaded_media['url'],
                'post_mime_type' => $file_type['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename( $file_name ) ),                
                'post_status' => 'inherit',
                'post_content' => ''
            );

            $attachment_id      = wp_insert_attachment( $file_args, $uploaded_media['file'] );
            $attach_data        = wp_generate_attachment_metadata( $attachment_id, $uploaded_media['file'] );
            wp_update_attachment_metadata( $attachment_id, $attach_data );

            //Image Size
            $size_type = 'thumbnail';
            if ( $type === 'profile_photo') {
                $size_type = 'profile';
            } 

            $attachment_json = codesquare_workintry_generate_url_from_file( $attach_data, $size_type, $file_name ); //get image url            

            if ( $type === 'profile_photo' ) {
                $profile_meta = get_user_meta( $current_user->ID, 'profile_image', true);
                $data_array = array();
                if ( !empty( $profile_meta['image_data'] ) ) {

                    $attach_array[$attachment_id] = array(
                        'thumb' 	=> $attachment_json['thumbnail'],                       
                        'image_id'  => $attachment_id
                    );                    

                    $profile_meta['image_data'] = $profile_meta['image_data'] + $attach_array;
                    update_user_meta( $current_user->ID, 'profile_image', $profile_meta);
                } else {
                    $data_array = array(                        
                        'default_image' => $attachment_id,
                        'image_data' => array(
                            $attachment_id => array(                                
                                'thumb' => $attachment_json['thumbnail'],                                
                                'image_id' => $attachment_id
                            ),
                        )
                    );                    
                    update_user_meta($current_user->ID, 'profile_image', $data_array);
                }							
				
            }  
            $response = array(               
                'type' 			=> 'success',
				'message' 		=> esc_html__('Image uploaded!', 'workintry'),
                'thumbnail' 	=> $attachment_json['thumbnail'],                
                'attachment_id' => $attachment_id
            );
            wp_send_json( $response );            
        } else {			
			$response['message'] = esc_html__('Image upload failed!', 'workintry');
			$response['type'] 	  = 'error';
            wp_send_json( $response );
        }
    }

    add_action('wp_ajax_codesquare_workintry_profile_image_uploader', 'codesquare_workintry_profile_image_uploader');
    add_action('wp_ajax_nopriv_codesquare_workintry_profile_image_uploader', 'codesquare_workintry_profile_image_uploader');
}

/**
 * Generate Profile URL fomr file
 */
if (!function_exists('codesquare_workintry_generate_url_from_file')) {

    /**
     * Get thumbnail url based on attachment data
     *
     * @param Image data
     * @return json
     */
    function codesquare_workintry_generate_url_from_file($attach_data, $type = 'profile', $basename) {

        $upload_dir = wp_upload_dir();
        $image_path_data = explode('/', $attach_data['file']);
        $count = '';
        if( is_array($image_path_data) ){
			$count = count($image_path_data);
		} else{
			$count = 0;
		}
        $image_path_array = array_slice($image_path_data, 0, $count - 1);
        $image_path = implode('/', $image_path_array);

        $thumbnail_name = null;
        $response = array();
        $path = $upload_dir['baseurl'] . '/' . $image_path . '/';

        if ( $type === 'profile' ) {
            if (!empty($attach_data['sizes']['thumbnail']['file'])) {
                $response['thumbnail'] = $path . $attach_data['sizes']['thumbnail']['file'];
            } else {
                $response['thumbnail'] = $path . $basename;
            }                      
        } elseif( $type === 'gallery' ){            
            if (!empty($attach_data['sizes']['full']['file'])) {
                $response['full'] = $path . $attach_data['sizes']['full']['file'];
            } else {
                $response['full'] = $path . $basename;
            } 
        }

        return $response;
    }

}

/**
 * @Set Profile Photo
 * @return {}
 */
if( !function_exists( 'codesquare_workintry_update_profile_image' ) ){
	function codesquare_workintry_update_profile_image(){
		global $current_user;
		$response = array();

		if( empty( $_POST['id'] ) ){
			$response['type'] 	  = 'error';
			$response['message'] = esc_html__('No kiddies please', 'workintry');
			wp_send_json( $response );
		}

		$profile_image = get_user_meta( $current_user->ID, 'profile_image', true);
		$profile_image['default_image'] = sanitize_text_field($_POST['id']);
		update_user_meta( $current_user->ID, 'profile_image', $profile_image );
        $response['type']     = 'success';
        $response['message'] = esc_html__('Profile image updated', 'workintry');
        wp_send_json( $response );
	}

	add_action('wp_ajax_codesquare_workintry_update_profile_image', 'codesquare_workintry_update_profile_image');
    add_action('wp_ajax_nopriv_codesquare_workintry_update_profile_image', 'codesquare_workintry_update_profile_image');
}

/**
 * @Delete Profile Photo
 * @return {}
 */
if( !function_exists( 'codesquare_workintry_delete_profile_image' ) ){
    function codesquare_workintry_delete_profile_image(){
        global $current_user;
        $response = array();

        if( empty( $_POST['id'] ) ){
            $response['type']     = 'error';
            $response['message'] = esc_html__('No kiddies please', 'workintry');
            wp_send_json( $response );
        }
        $user_id = sanitize_text_field( $_POST['id'] );
        $profile_image = get_user_meta( $current_user->ID, 'profile_image', true);               
        if( !empty( $profile_image['image_data'][ $user_id ] ) ){
            //Exists
            unset( $profile_image['image_data'][ $user_id ] );  
            wp_delete_attachment( $user_id, true );
            update_user_meta( $current_user->ID, 'profile_image', $profile_image);
            if( $profile_image['default_image'] == $user_id ){               
                //Same image
               $new_array = array_keys( $profile_image['image_data'] );
               $new_key = $new_array[0];
                if( !empty( $new_array ) ){                     
                    $profile_image['default_image'] = $new_array[0];
                    update_user_meta( $current_user->ID, 'profile_image', $profile_image );
                    $response['type']     = 'success';
                    $response['message']  = esc_html__('Thumbnail Deleted', 'workintry');
                    $response['thumb']    = $profile_image['image_data'][$new_key]['thumb'];
                    wp_send_json( $response );
                } else {                   
                    $profile_image = array();
                    update_user_meta( $current_user->ID, 'profile_image', $profile_image );
                    $response['type']     = 'success';
                    $response['message']  = esc_html__('Thumbnail Deleted', 'workintry');
                    $response['thumb']    = CSC_WORKINTRY_PLUGIN_URL .'assets/images/150X150.jpg';
                    wp_send_json( $response );
                }
            } else {
                //Do nothing
                $response['type']     = 'success';
                $response['message']  = esc_html__('Thumbnail Deleted', 'workintry');
                wp_send_json( $response );
            }
        } else {
            $response['type']     = 'error';
            $response['message']  = esc_html__('No kiddies please', 'workintry');
            wp_send_json( $response );
        }        

        $response['type']     = 'error';
        $response['message']  = esc_html__('Something went wrong, try again', 'workintry');
        wp_send_json( $response );      
    }
    add_action('wp_ajax_codesquare_workintry_delete_profile_image', 'codesquare_workintry_delete_profile_image');
    add_action('wp_ajax_nopriv_codesquare_workintry_delete_profile_image', 'codesquare_workintry_delete_profile_image');
}

/**
 * @Upload Profile Photo
 * @return {}
 */
if (!function_exists('codesquare_workintry_ad_gallery_uploader')) {
    function codesquare_workintry_ad_gallery_uploader() {

        global $current_user;        
        $response   =  array();

        //Validation          
        $type  = $_REQUEST['type'];
        if( empty( $type ) ){
            $response['type']     = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json( $response );
        }

        //Handle submitted media files
        $submitted_media    = $_FILES['cf_gallery_uploader'];    
        $uploaded_media     = wp_handle_upload($submitted_media, array( 'test_form' => false ) );

        if ( isset( $uploaded_media['file'] ) ) {
            //Getting submitted media file name and type like .jpg, .png
            $file_name = basename( $submitted_media['name'] );
            $file_name = str_replace(' ', '-', $file_name );
            $file_type = wp_check_filetype( $uploaded_media['file'] );

            // Attachment post data array
            $file_args = array(
                'guid' => $uploaded_media['url'],
                'post_mime_type' => $file_type['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename( $file_name ) ),                
                'post_status' => 'inherit',
                'post_content' => ''
            );

            $attachment_id      = wp_insert_attachment( $file_args, $uploaded_media['file'] );
            $attach_data        = wp_generate_attachment_metadata( $attachment_id, $uploaded_media['file'] );
            wp_update_attachment_metadata( $attachment_id, $attach_data );

            //Image Size
            $size_type = 'full';
            if ( $type === 'gallery') {
                $size_type = 'gallery';
            } 

            $attachment_json = codesquare_workintry_generate_url_from_file( $attach_data, $size_type, $file_name ); //get image url                                                           
            $response = array(               
                'type'          => 'success',
                'message'       => esc_html__('Image uploaded!', 'workintry'),
                'thumbnail'     => $attachment_json['full'],                
                'attachment_id' => $attachment_id
            );
            wp_send_json( $response );            
        } else {            
            $response['message'] = esc_html__('Image upload failed!', 'workintry');
            $response['type']     = 'error';
            wp_send_json( $response );
        }
    }

    add_action('wp_ajax_codesquare_workintry_ad_gallery_uploader', 'codesquare_workintry_ad_gallery_uploader');
    add_action('wp_ajax_nopriv_codesquare_workintry_ad_gallery_uploader', 'codesquare_workintry_ad_gallery_uploader');
}

/**
 * @Upload Chat Files
 * @return {}
 */
if (!function_exists('codesquare_workintry_chat_gallery_uploader')) {
    function codesquare_workintry_chat_gallery_uploader() {
        global $current_user;        
        $response   =  array();

        //Validation          
        $type  = $_REQUEST['type'];
        if( empty( $type ) ){
            $response['type']     = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json( $response );
        }

        //Handle submitted media files
        $submitted_media    = $_FILES['cf_gallery_uploader'];    
        $uploaded_media     = wp_handle_upload($submitted_media, array( 'test_form' => false ) );

        if ( isset( $uploaded_media['file'] ) ) {
            //Getting submitted media file name and type like .jpg, .png
            $file_name = basename( $submitted_media['name'] );
            $file_name = str_replace(' ', '-', $file_name );
            $file_type = wp_check_filetype( $uploaded_media['file'] );

            // Attachment post data array
            $file_args = array(
                'guid' => $uploaded_media['url'],
                'post_mime_type' => $file_type['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename( $file_name ) ),                
                'post_status' => 'inherit',
                'post_content' => ''
            );

            $attachment_id      = wp_insert_attachment( $file_args, $uploaded_media['file'] );
            $attach_data        = wp_generate_attachment_metadata( $attachment_id, $uploaded_media['file'] );
            wp_update_attachment_metadata( $attachment_id, $attach_data );

            //Image Size
            $size_type = 'full';
            if ( $type === 'gallery') {
                $size_type = 'gallery';
            } 

            $attachment_json = codesquare_workintry_generate_url_from_file( $attach_data, $size_type, $file_name ); 
            //get image url            
            $attachment_url = wp_get_attachment_url( $attachment_id );
            $filetype = wp_check_filetype($attachment_url);
            $ext = $filetype['ext'];
            $file_name = get_the_title( $attachment_id ).'.'.$ext;
            $response = array(               
                'type'          => 'success',
                'message'       => esc_html__('File uploaded!', 'workintry'),
                'thumbnail'     => $attachment_url,                
                'attachment_id' => $attachment_id,
                'title'         => $file_name               
            );
            wp_send_json( $response );            
        } else {            
            $response['message'] = esc_html__('File upload failed!', 'workintry');
            $response['type']     = 'error';
            wp_send_json( $response );
        }
    }

    add_action('wp_ajax_codesquare_workintry_chat_gallery_uploader', 'codesquare_workintry_chat_gallery_uploader');
    add_action('wp_ajax_nopriv_codesquare_workintry_chat_gallery_uploader', 'codesquare_workintry_chat_gallery_uploader');
}

/**
 * @Insert Property Ad
 * @return {}
 */
if( !function_exists( 'codesquare_workintry_insert_user_property_ad' ) ){
    function codesquare_workintry_insert_user_property_ad(){
        //Get User       
        global $current_user;         
        $response       = array();
        $type           = sanitize_text_field($_POST['type']);
        $current        = sanitize_text_field($_POST['current']);
        $post_status    = 'publish';
        $gig_approval = codesquare_workintry_get_settings_option( 'approve_post' );
        if( $gig_approval == 'admin' ){
            $post_status = 'draft';
        }

        //Validations        
        $do_check = check_ajax_referer('add_new_ad_form', 'add_new_ad_form', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        }        

        //Further Validations
        if( strlen( $_POST['title']  ) < 10){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Title must be atleast 10 characters long', 'workintry');
            wp_send_json($response);          
        }

        if( strlen( $_POST['description']  ) < 20){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Content must be atleast 20 characters long', 'workintry');
            wp_send_json($response);          
        }

        //Gallery Validation
        if( empty( $_POST['gallery'] ) ) {            
            $response['type'] = 'error';
            $response['message'] = esc_html__('Atleast one image is required', 'workintry');
            wp_send_json($response);          
        }
                
        //Category Validation
        if( empty( $_POST['gig-category'] ) ) {            
            $response['type'] = 'error';
            $response['message'] = esc_html__('Category is required', 'workintry');
            wp_send_json($response);          
        }

        //Category must be a valid one
        if( !empty( $_POST['gig-category'] ) ) {          
            $term = term_exists( $_POST['gig-category'], 'gig_category' );
            if ( $term !== 0 && $term !== null ) {
                //Nothing needed here
            } else {
                $response['type'] = 'error';
                $response['message'] = esc_html__('Valid category is required', 'workintry');
                wp_send_json($response);          
            }               
        }
        
        //Sub Category Validation
        if( empty( $_POST['sub-category'] ) ) {            
            $response['type'] = 'error';
            $response['message'] = esc_html__('Sub category is required', 'workintry');
            wp_send_json($response);          
        }

        //Sub Category must be a valid one
        if( !empty( $_POST['sub-category'] ) ) {          
            $term = term_exists( $_POST['sub-category'], 'gig_sub_category' );
            if ( $term !== 0 && $term !== null ) {
                //Nothing needed here
            } else {
                $response['type'] = 'error';
                $response['message'] = esc_html__('Valid sub category is required', 'workintry');
                wp_send_json($response);          
            }               
        }

        //Service Validation
        if( empty( $_POST['gig-service'] ) ) {            
            $response['type'] = 'error';
            $response['message'] = esc_html__('Service is required', 'workintry');
            wp_send_json($response);          
        }

        //Service must be a valid one
        if( !empty( $_POST['gig-service'] ) ) {          
            $term = term_exists( $_POST['gig-service'], 'gig_service' );
            if ( $term !== 0 && $term !== null ) {
                //Nothing needed here
            } else {
                $response['type'] = 'error';
                $response['message'] = esc_html__('Valid service is required', 'workintry');
                wp_send_json($response);          
            }               
        }

        //Tags Validation
        if( empty( $_POST['tags'] ) ) {            
            $response['type'] = 'error';
            $response['message'] = esc_html__('Atlease one tag is required', 'workintry');
            wp_send_json($response);          
        }

        //Gigs
        $basic      = $_POST['basic'];
        $gold       = $_POST['gold'];
        $diamond    = $_POST['diamond'];

        //Basic
        if( !empty( $basic ) ){
            $basic_title    = !empty( $basic['title'] ) ? $basic['title'] : '';
            $basic_description = !empty( $basic['description'] ) ? $basic['description'] : '';
            $delivery       = !empty( $basic['delivery'] ) ? $basic['delivery'] : '';
            $basic_revision    = !empty( $basic['revisions'] ) ? $basic['revisions'] : '';
            $basic_price    = !empty( $basic['price'] ) ? $basic['price'] : '';

            //Validate gig title
            if( empty( $basic_title ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Basic gig title is required', 'workintry');
                wp_send_json($response); 
            }

            //Validate gig description
            if( empty( $basic_description ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Basic gig description is required', 'workintry');
                wp_send_json($response);
            }

            //Validate gig delivery
            if( empty( $delivery ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Basic gig delivery day(s) required', 'workintry');
                wp_send_json($response);
            }

            //Validate gig revisions
            if( empty( $basic_revision ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Basic gig revision is required', 'workintry');
                wp_send_json($response);
            }

            //Validate gig price
            if( empty( $basic_price ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Basic gig price is required', 'workintry');
                wp_send_json($response);
            }
        }

        //Gold
        if( !empty( $gold ) ){
            $gold_title    = !empty( $gold['title'] ) ? $gold['title'] : '';
            $gold_description = !empty( $gold['description'] ) ? $gold['description'] : '';
            $gold_delivery = !empty( $gold['delivery'] ) ? $gold['delivery'] : '';
            $gold_revision    = !empty( $gold['revisions'] ) ? $gold['revisions'] : '';
            $gold_price    = !empty( $gold['price'] ) ? $gold['price'] : '';

            //Validate gig title
            if( empty( $gold_title ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Gold gig title is required', 'workintry');
                wp_send_json($response); 
            }

            //Validate gig description
            if( empty( $gold_description ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Gold gig description is required', 'workintry');
                wp_send_json($response);
            }

            //Validate gig delivery
            if( empty( $gold_delivery ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Gold gig delivery day(s) required', 'workintry');
                wp_send_json($response);
            }

            //Validate gig revisions
            if( empty( $gold_revision ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Gold gig revision is required', 'workintry');
                wp_send_json($response);
            }

            //Validate gig price
            if( empty( $gold_price ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Gold gig price is required', 'workintry');
                wp_send_json($response);
            }
        }

        //Dimaond
        if( !empty( $diamond ) ){
            $diamond_title    = !empty( $diamond['title'] ) ? $diamond['title'] : '';
            $diamond_description = !empty( $diamond['description'] ) ? $diamond['description'] : '';
            $diamond_delivery = !empty( $diamond['delivery'] ) ? $diamond['delivery'] : '';
            $diamond_revision    = !empty( $diamond['revisions'] ) ? $diamond['revisions'] : '';
            $diamond_price    = !empty( $diamond['price'] ) ? $diamond['price'] : '';

            //Validate gig title
            if( empty( $diamond_title ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Diamond gig title is required', 'workintry');
                wp_send_json($response); 
            }

            //Validate gig description
            if( empty( $diamond_description ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Diamond gig description is required', 'workintry');
                wp_send_json($response);
            }

            //Validate gig delivery
            if( empty( $diamond_delivery ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Diamond gig delivery day(s) required', 'workintry');
                wp_send_json($response);
            }

            //Validate gig revisions
            if( empty( $diamond_revision ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Diamond gig revision is required', 'workintry');
                wp_send_json($response);
            }

            //Validate gig price
            if( empty( $diamond_price ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Diamond gig price is required', 'workintry');
                wp_send_json($response);
            }
        }

        //Fast delivery
        $fast = $_POST['fast'];
        if( $fast == 'on' ){
            //Basic
            $basic      = $_POST['basicfast'];
            $gold       = $_POST['goldfast'];
            $diamond    = $_POST['diamondfast'];
            if( empty( $basic['delivery'] ) || empty( $basic['price'] ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Basic gig fast delivery time and price required', 'workintry');
                wp_send_json($response); 
            }

            //Gold           
            if( empty( $gold['delivery'] ) || empty( $gold['price'] ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Gold gig fast delivery time and price required', 'workintry');
                wp_send_json($response); 
            }

            //Diamond           
            if( empty( $diamond['delivery'] ) || empty( $diamond['price'] ) ){
                $response['type'] = 'error';
                $response['message'] = esc_html__('Diamond gig fast delivery time and price required', 'workintry');
                wp_send_json($response); 
            }
        } else { $fast = 'off'; }


        //FAQ
        $faq = array();
        if( !empty( $_POST['faq'] ) ){
            $faq = array_values( $_POST['faq'] );
            if( is_array( $faq ) ){
                foreach ( $faq as $key => $value ) {
                    if( empty( $value['title'] ) || empty( $value['description'] ) ){
                        $response['type'] = 'error';
                        $response['message'] = esc_html__('FAQ question and answer required', 'workintry');
                        wp_send_json($response);
                    }
                }
            }
        }                      
                
        //Get From Data
        $title          = sanitize_text_field( $_POST['title'] ); 
        $content        = wp_kses_post($_POST['description']);
        $gallery        = isset( $_POST['gallery'] ) ? wp_unslash($_POST['gallery']) : array();                        
        $category       = sanitize_text_field($_POST['gig-category']);
        $subcategory    = sanitize_text_field($_POST['sub-category']);
        $service        = sanitize_text_field($_POST['gig-service']);
        //Gigs
        $basic          = $_POST['basic'];
        $gold           = $_POST['gold'];
        $diamond        = $_POST['diamond'];
        //Basic
        $basic_title         = $basic['title'];
        $basic_description   = $basic['description'];
        $basic_delivery      = $basic['delivery'];
        $basic_revision      = $basic['revisions'];
        $basic_price         = $basic['price'];
        //Gold
        $gold_title         = $gold['title'];
        $gold_description   = $gold['description'];
        $gold_delivery      = $gold['delivery'];
        $gold_revision      = $gold['revisions'];
        $gold_price         = $gold['price'];
        //Diamond
        $diamond_title         = $diamond['title'];
        $diamond_description   = $diamond['description'];
        $diamond_delivery      = $diamond['delivery'];
        $diamond_revision      = $diamond['revisions'];
        $diamond_price         = $diamond['price'];

        //Fast delivery
        $fast = $_POST['fast'];
        $basic_fast_delivery        = '';
        $basic_fast_price           = '';
        $gold_fast_delivery         = '';
        $gold_fast_price            = '';
        $diamond_fast_delivery      = '';                
        $diamond_fast_price         = '';
        if( $fast == 'on' ){
            //Basic
            $basic_fast      = $_POST['basicfast'];
            $gold_fast       = $_POST['goldfast'];
            $diamond_fast    = $_POST['diamondfast'];
            $basic_fast_delivery        = $basic_fast['delivery'];
            $basic_fast_price           = $basic_fast['price'];
            $gold_fast_delivery         = $gold_fast['delivery'];
            $gold_fast_price            = $gold_fast['price'];
            $diamond_fast_delivery      = $diamond_fast['delivery'];
            $diamond_fast_price         = $diamond_fast['price'];
        }

        //Featured    
        $featured       = isset( $_POST['featured'] ) ? sanitize_text_field($_POST['featured']) : 'no';          
        //Tags
        $tags = !empty( $_POST['tags'] ) ? sanitize_text_field( $_POST['tags'] ) : '';                        
           
        //Insert/Update Add
        if( $type == 'add' ){
            //Prepare post array        
            $ad_post = array(
                'post_title'        => $title,
                'post_status'       => $post_status,
                'post_content'      => $content,
                'post_author'       => $current_user->ID,
                'post_type'         => 'workintry',
                'post_date'         => current_time('Y-m-d H:i:s')
            );
            //Check user account
            $user_can_post = codesquare_workintry_can_user_post_ad( $current_user->ID );            
            if( $user_can_post == 'allowed' ){
                $post_id = wp_insert_post( $ad_post ); 
                //Send Email               
                do_action('codesquare_workintry_new_ad_email', $post_id, $current_user->ID);
            } else {
                $response['type'] = 'error';
                $response['message'] = esc_html__("You can't create gig, delete old gigs to create new one", 'workintry' );
                wp_send_json( $response );
            }                                                
        } else {
            $post_date = get_the_date('Y-m-d H:i:s', $current );
            //Check if bump up allowed                    
            if( isset( $_POST['bump'] ) && $_POST['bump'] == 'yes' ){
                $can_user_bump = codesquare_workintry_can_user_add_featured( 'bump_ads' );
                if( !empty( $can_user_bump ) && $can_user_bump == 'allowed' ){
                    $post_date = current_time('Y-m-d H:i:s');

                    //Once date set remove from user package
                    $bump_ads_count = get_user_meta( $current_user->ID, 'bump_ads', true );
                    $bump_ads_count = $bump_ads_count - 1;
                    update_user_meta( $current_user->ID, 'bump_ads', $bump_ads_count );
                } else {
                    $response['type'] = 'error';
                    $response['message'] = esc_html__("You can't bump your gig, update your package to bump gigs", 'workintry' );
                    wp_send_json( $response );
                }          
            } 
            //Ad post array
            $ad_post = array(
                'ID'            => $current,
                'post_title'    => $title,
                'post_content'  => $content,
                'post_date'     => $post_date,
                'post_status'   => $post_status,
            );

            $new_post_id = wp_update_post( $ad_post, true );
            $post_id = $current;
        }                                                                    
        //Add revisions and delivery
        //Set Revisions 
        $cl_revisions = 'no';
        if( !empty( $basic_revision ) ){
            $cl_revisions = 'yes';
        }
        //Set Delivery 
        $cl_delivery = 'no';
        if( !empty( $delivery ) ){
            $cl_delivery = 'yes';
        }
        //update meta data           
        $ad_meta = array(
            'cl_gig_basic'      => $basic,
            'cl_gig_gold'       => $gold,
            'cl_gig_diamond'    => $diamond,
            //Basic
            'cl_basic_title'    => $basic_title,
            'cl_basic_desc'     => $basic_description,
            'cl_basic_delivery' => $delivery,
            'cl_basic_revision' => $basic_revision,
            'cl_basic_price'    => $basic_price,
            //Gold
            'cl_gold_title'    => $gold_title,
            'cl_gold_desc'     => $gold_description,
            'cl_gold_delivery' => $gold_delivery,
            'cl_gold_revision' => $gold_revision,
            'cl_gold_price'    => $gold_price,
            //Diamond
            'cl_diamond_title'    => $diamond_title,
            'cl_diamond_desc'     => $diamond_description,
            'cl_diamond_delivery' => $diamond_delivery,
            'cl_diamond_revision' => $diamond_revision,
            'cl_diamond_price'    => $diamond_price, 
            //Fast
            'cl_fast'                     => $fast, 
            'cl_basic_fast_delivery'      => $basic_fast_delivery, 
            'cl_basic_fast_price'         => $basic_fast_price, 
            'cl_gold_fast_delivery'       => $gold_fast_delivery, 
            'cl_gold_fast_price'          => $gold_fast_price, 
            'cl_diamond_fast_delivery'    => $diamond_fast_delivery, 
            'cl_diamond_fast_price'       => $diamond_fast_price, 
            //FAQs
            'cl_faq' => $faq,
            'cl_revisions'        => $cl_revisions,
            'cl_delivery'         => $cl_delivery
        );

        //Update ad post meta 
        foreach ( $ad_meta as $key => $value ) {
            update_post_meta( $post_id, $key, $value );
        }

        //Set tags
        wp_set_post_terms( $post_id, $tags, 'gig_tags' );  

        //Set rating and view to 0 at insert
        if( $type == 'add' ){
            update_post_meta( $post_id, 'cl_rating', 0);
            update_post_meta( $post_id, 'ad_view', 0);
        }

        //Gallery Updation
        if( $type == 'add' ){
            if( !empty( $gallery ) ){
                foreach ( $gallery as $key => $value ) {
                    $value['id'] = sanitize_text_field( $value['id'] );
                    add_post_meta( $post_id, 'cl_galleryc', $value['id'] );
                }
            }
        } else {
            if( !empty( $gallery ) ){
                delete_post_meta( $post_id, 'cl_galleryc' );
                foreach ( $gallery as $key => $value ) {
                    $value['id'] = sanitize_text_field( $value['id'] );
                    add_post_meta( $post_id, 'cl_galleryc', $value['id'] );
                }
            }            
        }       

        //Set post thumbnail
        $gallery = array_values( $gallery );
        set_post_thumbnail( $post_id, $gallery[0]['id'] );
        
        //Get term id from slug               
        $category_id        = codesquare_workintry_get_term_id_by_slug( $category, 'gig_category' );
        $sub_category_id    = codesquare_workintry_get_term_id_by_slug( $subcategory, 'gig_sub_category' );
        $service_id         = codesquare_workintry_get_term_id_by_slug( $service, 'gig_service' );        
        
        //Set taxonomies             
        wp_set_post_terms( $post_id, $category_id, 'gig_category');
        wp_set_post_terms( $post_id, $sub_category_id, 'gig_sub_category');
        wp_set_post_terms( $post_id, $service_id, 'gig_service');

        //Make ad as featured
        if( !empty( $featured ) && $featured == 'yes' ){            
            $can_user = codesquare_workintry_can_user_add_featured( 'featured_ads' );
            if( !empty( $can_user ) && $can_user == 'allowed' ) {
                $current_time = new DateTime();                             
                $current_time_stamp = $current_time->getTimestamp();
                //User can add featured Ads
                $featured_days = get_user_meta( $current_user->ID, 'featured_expiry', true );
                $featured_days = !empty( $featured_days ) && intval( $featured_days ) > 0 ? $featured_days : 30;
                $time_stamp = time() + ( 60 * 60 * 24 * $featured_days );
                //Get if its featured already
                $is_already_featured = get_post_meta( $post_id, 'cl_timestamp', true );
                $is_already_featured = !empty( $is_already_featured ) ? $is_already_featured : 0;            
                if( $is_already_featured > $current_time_stamp ){
                    //Its already featured            
                } else {                    
                    update_post_meta( $post_id, 'cl_timestamp', $time_stamp ); 
                    update_post_meta( $post_id, 'cl_featured', 'yes' );
                    //As we set ad as featured now its time to remove one featured ad from user account
                    $feature_count = get_user_meta( $current_user->ID, 'featured_ads', true );
                    $feature_count = $feature_count - 1;
                    update_user_meta( $current_user->ID, 'featured_ads', $feature_count ); 
                }     
            }
        } elseif( !empty( $featured ) && $featured == 'no' ) {
            update_post_meta( $post_id, 'cl_timestamp', 0 );
            update_post_meta( $post_id, 'cl_featured', 'no' ); 
        }         
              
        //Prepare response
        $response['type'] = 'success';
        $response['message'] = esc_html__('Gig post updated successfully', 'workintry');
        wp_send_json( $response );
    }
    add_action('wp_ajax_codesquare_workintry_insert_user_property_ad', 'codesquare_workintry_insert_user_property_ad');
    add_action('wp_ajax_nopriv_codesquare_workintry_insert_user_property_ad', 'codesquare_workintry_insert_user_property_ad');
}

/**
 * @Verify Required Fields
 * @return {}
 */
if( !function_exists( 'codesquare_workintry_varify_required_ad_fields' ) ){
    function codesquare_workintry_varify_required_ad_fields( $warnings = array() ){
        $dash_show_price        = codesquare_workintry_get_settings_option( 'dash_price' );
        $dash_show_sign         = codesquare_workintry_get_settings_option( 'dash_price_sign' );
        $dash_item_type         = codesquare_workintry_get_settings_option( 'dash_item_type' );
        $dash_category          = codesquare_workintry_get_settings_option( 'dash_category' );
        $dash_item_condition    = codesquare_workintry_get_settings_option( 'dash_item_condition' );
        $dash_item_warranty     = codesquare_workintry_get_settings_option( 'dash_item_warranty' );
        $dash_p_type            = codesquare_workintry_get_settings_option( 'dash_p_type' );
        $dash_negotiable        = codesquare_workintry_get_settings_option( 'dash_negotiable' );
        $dash_exchange          = codesquare_workintry_get_settings_option( 'dash_exchange' );
        $dash_urgent            = codesquare_workintry_get_settings_option( 'dash_urgent' );
        $dash_video            = codesquare_workintry_get_settings_option( 'dash_video' );
        //Fields for specifics       
        //Location and User
        $dash_name              = codesquare_workintry_get_settings_option( 'dash_name' );       
        $dash_country           = codesquare_workintry_get_settings_option( 'dash_country' );
        $dash_city              = codesquare_workintry_get_settings_option( 'dash_city' );
        $dash_address           = codesquare_workintry_get_settings_option( 'dash_address' );
        $dash_location          = codesquare_workintry_get_settings_option( 'dash_location' );                
        //Form Validations        
        $warnings = array(
            'title'         => esc_html__('Title field is required', 'workintry'),
            'description'   => esc_html__('Description field is required', 'workintry'),                                                    
        );

        //Add required fields to the warnings array
        if( $dash_show_price == 'required' ){
            $warnings['price'] = esc_html__('Price field is required', 'workintry');
        }
        if( $dash_show_sign == 'required' ){
            $warnings['price-sign'] = esc_html__('Price / Currency sign is required', 'workintry');
        }           
        if( $dash_item_type == 'required' ){
            $warnings['item-type'] = esc_html__('Item type is required', 'workintry');            
        }
        if( $dash_category == 'required' ){
            $warnings['category'] = esc_html__('Category field is required', 'workintry');
        }
        if( $dash_item_condition == 'required' ){
            $warnings['condition'] = esc_html__('Condition is required', 'workintry');
        }
        if( $dash_item_warranty == 'required' ){
            $warnings['warranty'] = esc_html__('Warranty is required', 'workintry');
        }
        if( $dash_p_type == 'required' ){
            $warnings['price-nature'] = esc_html__('Price type is required', 'workintry');
        }
        if( $dash_negotiable == 'required' ){
            $warnings['negotiable'] = esc_html__('Negotiable field is required', 'workintry');
        }
        if( $dash_exchange == 'required' ){
            $warnings['exchange'] = esc_html__('Exchange field is required', 'workintry');
        }
        if( $dash_urgent == 'required' ){
            $warnings['urgent-sell'] = esc_html__('Urgent field is required', 'workintry');
        }   
        if( $dash_video == 'required' ){
            $warnings['video'] = esc_html__('Video field is required', 'workintry');
        }      
        //User details
        if( $dash_name == 'required' ){
            $warnings['first-name'] = esc_html__('First Name is required', 'workintry');
            $warnings['last-name'] = esc_html__('Last Name is required', 'workintry');
        }
        if( $dash_country == 'required' ){
            $warnings['country'] = esc_html__('Country is required', 'workintry');
        }
        if( $dash_city == 'required' ){
            $warnings['city'] = esc_html__('City is required', 'workintry');
        }
        if( $dash_address == 'required' ){
            $warnings['address'] = esc_html__('Address is required', 'workintry');
        } 
        if( $dash_location == 'required' ){
            $warnings['latitude'] = esc_html__('Latitude is required', 'workintry');
            $warnings['longitude'] = esc_html__('Longitude is required', 'workintry');
        }     
        //Return
        $warnings['gallery'] = esc_html__('Atleast one image is required', 'workintry');
        return $warnings;
    }
}

/**
 * @Delete Gallery Photo
 * @return {}
 */
if( !function_exists( 'codesquare_workintry_delete_gallery_image' ) ){
    function codesquare_workintry_delete_gallery_image(){
        global $current_user;
        $response = array();

        if( empty( $_POST['id'] ) ){
            $response['type']     = 'error';
            $response['message'] = esc_html__('No kiddies please', 'workintry');
            wp_send_json( $response );
        }

        //Delete Media File     
        $file_id = sanitize_text_field( $_POST['id'] );
        wp_delete_attachment( $file_id, true );       
        $response['type']     = 'success';
        $response['message']  = esc_html__('Thumbnail deleted', 'workintry');
        wp_send_json( $response );      
    }
    add_action('wp_ajax_codesquare_workintry_delete_gallery_image', 'codesquare_workintry_delete_gallery_image');
    add_action('wp_ajax_nopriv_codesquare_workintry_delete_gallery_image', 'codesquare_workintry_delete_gallery_image');
}

/**
 * @Delete Ad
 * @return {}
 */
if( !function_exists( 'codesquare_workintry_delete_user_ad' ) ){
    function codesquare_workintry_delete_user_ad(){
        global $current_user;       
        $response = array();
        $post_id     = sanitize_text_field($_POST['post_id']);    
        $user_id     = sanitize_text_field($_POST['user_id']);
        if( empty( $post_id ) || empty( $user_id ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies Please', 'workintry');
            wp_send_json( $response );
        }
        
        //User Match
        if( $user_id != $current_user->ID ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies Please', 'workintry');
            wp_send_json( $response );
        }

        //Delete Post
        wp_delete_post( $post_id );

        //Prepare response
        $response['type'] = 'success';
        $response['message'] = esc_html__('Ad deleted successfully', 'workintry');
        wp_send_json( $response );
    }
    add_action('wp_ajax_codesquare_workintry_delete_user_ad', 'codesquare_workintry_delete_user_ad');
    add_action('wp_ajax_nopriv_codesquare_workintry_delete_user_ad', 'codesquare_workintry_delete_user_ad');
}

/**
 * @Delete Ad from Wishlist
 * @return {}
 */
if( !function_exists( 'codesquare_workintry_delete_ad_from_wishlist' ) ){
    function codesquare_workintry_delete_ad_from_wishlist(){
        global $current_user;       
        $response = array();
        $post_id     = sanitize_text_field($_POST['post_id']);    
        $user_id     = sanitize_text_field($_POST['user_id']);
        if( empty( $post_id ) || empty( $user_id ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies Please', 'workintry');
            wp_send_json( $response );
        }
        
        //User Match
        if( $user_id != $current_user->ID ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies Please', 'workintry');
            wp_send_json( $response );
        }

        //Remove from wishlist
        $wishlist = get_user_meta( $user_id, 'cl_wishlist', true);          
        $wishlist = !empty( $wishlist ) ? $wishlist : array();
        //Remove from wishlist     
        $wishlist = array_diff( $wishlist, array( $post_id ) );
        update_user_meta( $user_id, 'cl_wishlist', $wishlist );
        
        //Prepare response
        $response['type'] = 'success';
        $response['message'] = esc_html__('Removed from wishlist successfully', 'workintry');
        wp_send_json( $response );
    }
    add_action('wp_ajax_codesquare_workintry_delete_ad_from_wishlist', 'codesquare_workintry_delete_ad_from_wishlist');
    add_action('wp_ajax_nopriv_codesquare_workintry_delete_ad_from_wishlist', 'codesquare_workintry_delete_ad_from_wishlist');
}

/**
 * @Update Social Settings
 * @return {}
 */
if( !function_exists( 'codesquare_workintry_update_social_settings' ) ){
    function codesquare_workintry_update_social_settings(){       
        global $current_user;
        $response = array();
        //Validations        
        $do_check = check_ajax_referer('profile_social_user_request', 'profile_social_user_request', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        } 

        //Update settings
        $facebook   = !empty($_POST['facebook']) ? esc_url_raw( $_POST['facebook'] ) : '';
        $twitter    = !empty($_POST['twitter']) ? esc_url_raw( $_POST['twitter'] ) : '';
        $google     = !empty($_POST['google']) ? esc_url_raw( $_POST['google'] ) : '';
        $pinterest  = !empty($_POST['pinterest']) ? esc_url_raw( $_POST['pinterest'] ) : '';
        $linkedin   = !empty($_POST['linkedin']) ? esc_url_raw( $_POST['linkedin'] ) : '';
        $instagram   = !empty($_POST['instagram']) ? esc_url_raw( $_POST['instagram'] ) : '';

        //Facebook
        if( !empty( $facebook ) ){
            if( $facebook === $_POST['facebook'] ){                
                //Nothing here
            } else{
                $response['type'] = 'error';
                $response['message'] = esc_html__('Proper facebook URL with http or https required', 'workintry');
                wp_send_json($response);          
            }                       
        }

        //Twitter
        if( !empty( $twitter ) ){
            if( $twitter === $_POST['twitter'] ){                
                //Nothing here
            } else{
                $response['type'] = 'error';
                $response['message'] = esc_html__('Proper twitter URL with http or https required', 'workintry');
                wp_send_json($response);          
            }                       
        }

        //Gmail
        if( !empty( $google ) ){
            if( $google === $_POST['google'] ){                
                //Nothing here
            } else{
                $response['type'] = 'error';
                $response['message'] = esc_html__('Proper google URL with http or https required', 'workintry');
                wp_send_json($response);          
            }                       
        }

        //Pinterest
        if( !empty( $pinterest ) ){
            if( $pinterest === $_POST['pinterest'] ){                
                //Nothing here
            } else{
                $response['type'] = 'error';
                $response['message'] = esc_html__('Proper pinterest URL with http or https required', 'workintry');
                wp_send_json($response);          
            }                       
        }

        //Linkedin
        if( !empty( $linkedin ) ){
            if( $linkedin === $_POST['linkedin'] ){                
                //Nothing here
            } else{
                $response['type'] = 'error';
                $response['message'] = esc_html__('Proper linkedin URL with http or https required', 'workintry');
                wp_send_json($response);          
            }                       
        }

        //Instagram
        if( !empty( $instagram ) ){
            if( $instagram === $_POST['instagram'] ){                
                //Nothing here
            } else{
                $response['type'] = 'error';
                $response['message'] = esc_html__('Proper instagram URL with http or https required', 'workintry');
                wp_send_json($response);          
            }                       
        }

        update_user_meta( $current_user->ID, 'facebook', $facebook );
        update_user_meta( $current_user->ID, 'twitter', $twitter );
        update_user_meta( $current_user->ID, 'google', $google );
        update_user_meta( $current_user->ID, 'pinterest', $pinterest );
        update_user_meta( $current_user->ID, 'linkedin', $linkedin );
        update_user_meta( $current_user->ID, 'instagram', $instagram );        

        //Response
        $response['type'] = 'success';
        $response['message'] = esc_html__('Settings saved successfully', 'workintry');
        wp_send_json( $response );

    }
    add_action('wp_ajax_codesquare_workintry_update_social_settings', 'codesquare_workintry_update_social_settings');
    add_action('wp_ajax_nopriv_codesquare_workintry_update_social_settings', 'codesquare_workintry_update_social_settings');
}

/**
 * @Delete User Account
 * @return {}
 */
if( !function_exists( 'codesquare_workintry_delete_user_account' ) ){
    function codesquare_workintry_delete_user_account(){
        global $current_user;
        $admin_email = get_option( 'admin_email' );
        $blog_title  = get_option( 'blogname' );
        $edit_user_link = get_edit_user_link( $current_user->ID );
        $user_email = $current_user->user_email; 

        $response = array();
        //Validations        
        $do_check = check_ajax_referer('profile_delete_account', 'profile_delete_account', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        }

        //Form Validations
        if( empty( $_POST['reason'] ) || empty( $_POST['description'] ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Reason and description are required', 'workintry');
            wp_send_json( $response );
        }

        //Email Admin
        $user_email = sanitize_email( $user_email );
        $description = sanitize_textarea_field( $_POST['description'] );
        $reason = sanitize_text_field( $_POST['reason'] );
        //Reason title
        if( strlen( $reason ) < 10 ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Reason must be 10 characters long', 'workintry');
            wp_send_json( $response );
        }
        //Reason Description
        if( strlen( $description ) < 10 ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('Reason description must be 30 characters long', 'workintry');
            wp_send_json( $response );
        }
        
        do_action('codesquare_workintry_delete_user_email', $user_email, $reason, $description );
    }
    add_action('wp_ajax_codesquare_workintry_delete_user_account', 'codesquare_workintry_delete_user_account');
    add_action('wp_ajax_nopriv_codesquare_workintry_delete_user_account', 'codesquare_workintry_delete_user_account');
}

/**
 * @get Cities
 * @return html
 */
if( !function_exists( 'codesquare_workintry_get_cities' ) ){
    function codesquare_workintry_get_cities(){
        $response = array();
        $country = sanitize_text_field($_POST['country']); 
        $taxonomy = sanitize_text_field($_POST['taxonomy']);
        if( empty( $country ) || empty( $taxonomy ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('No country found', 'workintry');
            wp_send_json( $response );
        }
       
        //Get ID
        $ID = get_term_by( 'slug', $country, $taxonomy );              
        $term_id = '';
        if( $ID ){
            $term_id = $ID->term_id;
        }

        //City name
        $city_name = 'gig_city';
        $taxonomy == 'gig_country';

        if( !empty( $term_id ) ){        
        //Get terms
            $args = array(
                'hide_empty' => false, // also retrieve terms which are not used yet
                'meta_query' => array(
                    array(
                       'key'       => 'city_meta',
                       'value'     => $term_id,
                       'compare'   => '='
                    )
                ),
                'taxonomy'  => $city_name,
            );
            $terms = get_terms( $args );               
            //If terms object set
            if( !empty( $terms ) ){ 
                ob_start();
                ?>
                    <option value=""><?php esc_html_e('Select City', 'workintry'); ?></option>
                <?php 
                foreach ( $terms as $key => $value ) { ?>
                    <option value="<?php echo esc_attr( $value->slug ); ?>" <?php selected( $value->slug, $selected, true); ?>><?php echo esc_html( $value->name ); ?></option>
                <?php }
                $data = ob_get_clean();
                $response['type'] = 'success';
                $response['message'] = esc_html__('Cities Loaded', 'workintry');
                $response['data'] = $data;
                wp_send_json( $response );
            }
        } else {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Nothing Found', 'workintry');
            wp_send_json( $response );
        }
    }
    add_action('wp_ajax_codesquare_workintry_get_cities', 'codesquare_workintry_get_cities');
    add_action('wp_ajax_nopriv_codesquare_workintry_get_cities', 'codesquare_workintry_get_cities');
}


/**
 * @send ad report
 * @return html
 */
if( !function_exists( 'codesquare_workintry_send_ad_report' ) ){
    function codesquare_workintry_send_ad_report(){
        $response = array();
        $reasons = codesquare_workintry_get_settings_option('reasons');
        //Validations        
        $do_check = check_ajax_referer('user_ad_report_request', 'user_ad_report_request', false);
        if ($do_check == false) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);            
        }

        //Further check
        if( empty( $_POST['id'] ) ){
            $response['type'] = 'error';
            $response['message'] = esc_html__('No kiddies please!', 'workintry');
            wp_send_json($response);
        }
        //Form data validation
        if( empty( $_POST['name'] ) ||
            empty( $_POST['email'] ) ||
            empty( $_POST['reason'] ) ||
            empty( $_POST['message'] ) ){
            //Response
            $response['type '] = 'error';
            $response['message'] = esc_html__('All fields are required', 'workintry');
            wp_send_json( $response );
        }

        //Get data
        $name    = sanitize_text_field( $_POST['name'] );
        $email   = sanitize_email( $_POST['email'] );
        $reason  = sanitize_text_field($_POST['reason']);
        $message = sanitize_textarea_field( $_POST['message'] );
        $post_id = sanitize_text_field($_POST['id']);

        //Name validation
        if ( strlen( $_POST['name'] ) < 4 ) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Name should be atleast 4 characters.', 'workintry');
           wp_send_json($response);
        }

        //Email validation
        if ( !is_email( $_POST['email'] ) ) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Please add a valid email address.', 'workintry');
            wp_send_json($response);
        }

        //Reason validation
        $reasons_array = array();
        if( !empty( $reasons ) ){
            foreach ( $reasons as $key => $value ) {
                $reasons_array[] = $value;
            }
        }

        //Reason validation
        if( in_array( $reason, $reasons_array ) ){
            //Ok
        } else {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Choose from allowed reasons only.', 'workintry');
            wp_send_json($response);
        }
        
        //Message validation
        if ( strlen( $_POST['message'] ) < 15 ) {
            $response['type'] = 'error';
            $response['message'] = esc_html__('Message should be atleast 15 characters.', 'workintry');
           wp_send_json($response);
        }

        do_action('codesquare_codesquare_workintry_send_ad_report_email', $name, $email, $reason, $message, $post_id );
    }
    add_action('wp_ajax_codesquare_workintry_send_ad_report', 'codesquare_workintry_send_ad_report');
    add_action('wp_ajax_nopriv_codesquare_workintry_send_ad_report', 'codesquare_workintry_send_ad_report');
}


/*
* User Can Post Ad or Not
*/
if( !function_exists('codesquare_workintry_can_user_post_ad') ){
    function codesquare_workintry_can_user_post_ad( $user_id = '' ){
        global $current_user;
        if( empty( $user_id ) ){
            $user_id = $current_user->ID;
        }
        //Check if directory type is free
        $max_gigs = codesquare_workintry_get_settings_option('max_gigs');
        $args = array(
            'post_type' => 'workintry',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'author' => $current_user->ID
        );
        $user_posts = new WP_Query( $args );
        $total = $user_posts->found_posts;
        if( $max_gigs > $total ){
            return 'allowed';
        } else {
            return 'not allowed';
        }        
    }
}

/*
* Print Editor
*/
if( !function_exists( 'codesquare_workintry_print_post_editor' ) ){
    function codesquare_workintry_print_post_editor( $content = ''){
        ob_start();
            $content = !empty( $content ) ? $content : '';
            $settings = array('media_buttons' => false);
            wp_editor($content, 'description', $settings);
        echo ob_get_clean();
    }
    add_action('codesquare_workintry_print_post_editor', 'codesquare_workintry_print_post_editor', 10, 1);
}


/*Gigs data*/
if( !function_exists( 'codesquare_workintry_print_gig_revisions' ) ){
    function codesquare_workintry_print_gig_revisions( $name = 'basic', $selected = '' ){
        ob_start();
        ?>
        <select name="<?php echo esc_attr($name); ?>[revisions]">
            <option value="">
            <?php esc_html_e('Select', 'workintry'); ?> 
            </option>
            <option value="1" <?php selected( $selected, '1') ?>>
            <?php esc_html_e('1', 'workintry'); ?>
            </option>
           <option value="2" <?php selected( $selected, '2') ?>>
            <?php esc_html_e('2', 'workintry'); ?>
            </option>
           <option value="3" <?php selected( $selected, '3') ?>>
            <?php esc_html_e('3', 'workintry'); ?>
            </option>
            <option value="4" <?php selected( $selected, '4') ?>>
            <?php esc_html_e('4', 'workintry'); ?>
            </option>
            <option value="5" <?php selected( $selected, '5') ?>>
            <?php esc_html_e('5', 'workintry'); ?>
            </option>            
            <option value="6" <?php selected( $selected, '6') ?>>
            <?php esc_html_e('6', 'workintry'); ?>
            </option>
            <option value="7" <?php selected( $selected, '7') ?>>
            <?php esc_html_e('7', 'workintry'); ?>
            </option>
            <option value="8" <?php selected( $selected, '8') ?>>
            <?php esc_html_e('8', 'workintry'); ?>
            </option>
            <option value="9" <?php selected( $selected, '9') ?>>
            <?php esc_html_e('9', 'workintry'); ?>
            </option>
            <option value="10" <?php selected( $selected, '10') ?>>
            <?php esc_html_e('10', 'workintry'); ?>
            </option>
        </select>
        <?php 
        echo ob_get_clean();
    }
    add_action( 'codesquare_workintry_print_gig_revisions', 'codesquare_workintry_print_gig_revisions', 10, 2 );
}

/*Gig delivery*/
if( !function_exists( 'codesquare_workintry_print_gig_delivery' ) ){
    function codesquare_workintry_print_gig_delivery( $name = 'basic', $selected = '' ){
        ob_start();
        ?>
        <select name="<?php echo esc_attr($name); ?>[delivery]">
            <option value="">
            <?php esc_html_e('Select', 'workintry'); ?> 
            </option>
            <option value="1" <?php selected( $selected, '1') ?>>
            <?php esc_html_e('1 Day', 'workintry'); ?>
            </option>
           <option value="2" <?php selected( $selected, '2') ?>>
            <?php esc_html_e('2 Days', 'workintry'); ?>
            </option>
           <option value="3" <?php selected( $selected, '3') ?>>
            <?php esc_html_e('3 Days', 'workintry'); ?>
            </option>
            <option value="4" <?php selected( $selected, '4') ?>>
            <?php esc_html_e('4 Days', 'workintry'); ?>
            </option>
            <option value="5" <?php selected( $selected, '5') ?>>
            <?php esc_html_e('5 Days', 'workintry'); ?>
            </option>            
            <option value="6" <?php selected( $selected, '6') ?>>
            <?php esc_html_e('6 Days', 'workintry'); ?>
            </option>
            <option value="7" <?php selected( $selected, '7') ?>>
            <?php esc_html_e('7 Days', 'workintry'); ?>
            </option>
            <option value="8" <?php selected( $selected, '8') ?>>
            <?php esc_html_e('8 Days', 'workintry'); ?>
            </option>
            <option value="9" <?php selected( $selected, '9') ?>>
            <?php esc_html_e('9 Days', 'workintry'); ?>
            </option>
            <option value="10" <?php selected( $selected, '10') ?>>
            <?php esc_html_e('10 Days', 'workintry'); ?>
            </option>
            <option value="14" <?php selected( $selected, '14') ?>>
            <?php esc_html_e('14 Days', 'workintry'); ?>
            </option>
            <option value="21" <?php selected( $selected, '21') ?>>
            <?php esc_html_e('21 Days', 'workintry'); ?>
            </option>
            <option value="28" <?php selected( $selected, '28') ?>>
            <?php esc_html_e('28 Days', 'workintry'); ?>
            </option>
            <option value="35" <?php selected( $selected, '35') ?>>
            <?php esc_html_e('35 Days', 'workintry'); ?>
            </option>
            <option value="45" <?php selected( $selected, '45') ?>>
            <?php esc_html_e('45 Days', 'workintry'); ?>
            </option>
            <option value="60" <?php selected( $selected, '60') ?>>
            <?php esc_html_e('60 Days', 'workintry'); ?>
            </option>
            <option value="75" <?php selected( $selected, '75') ?>>
            <?php esc_html_e('75 Days', 'workintry'); ?>
            </option>
            <option value="90" <?php selected( $selected, '90') ?>>
            <?php esc_html_e('90 Days', 'workintry'); ?>
            </option>
        </select>
        <?php 
        echo ob_get_clean();
    }
    add_action( 'codesquare_workintry_print_gig_delivery', 'codesquare_workintry_print_gig_delivery', 10, 2 );
}

/*Print price*/
if( !function_exists( 'codesquare_workintry_print_gig_price' ) ){
    function codesquare_workintry_print_gig_price( $name = '', $value, $selected = '' ){
        ob_start();
        ?>
        <input type="text" name="<?php echo esc_attr( $name ); ?>[price]" placeholder="<?php echo esc_attr( $value ); ?>" value="<?php echo esc_attr( $selected ); ?>">
        <?php 
        echo ob_get_clean();
    }
    add_action( 'codesquare_workintry_print_gig_price', 'codesquare_workintry_print_gig_price', 10, 3 );
}

/*Get chat*/
if( !function_exists( 'codesquare_workintry_get_order_chat_message' ) ){
    function codesquare_workintry_get_order_chat_message(){  
        global $wpdb, $current_user;
        $response = array();  
        $user_id = $current_user->ID;
        $sender_id = $_POST['userID'];
        $id = $_POST['id'];    
        $message_id = $_POST['messageId'];
        if( $message_id == 'none' ){
            $message_id = '0';
        }
        //Verification
        if( empty( $id ) || empty( $sender_id ) ){
            $response['type']       = 'error';
            $response['message']    = esc_html__('No kiddies please', 'workintry');
            wp_send_json($response);
        }

        //Verify user ID
        if( $sender_id != $user_id ){
            $response['type']       = 'error';
            $response['message']    = esc_html__('No kiddies please', 'workintry');
            wp_send_json($response);
        }
        //Find Message
        
        $user_id = $current_user->ID;
        $id = $_POST['id'];    
        $message_id = $_POST['messageId'];            
        $order_status = '';
        //Get Chat
        $chat_table_name = $wpdb->prefix . 'gig_chat_message';
        $messages = $wpdb->get_results($wpdb->prepare("SELECT * from $chat_table_name WHERE gig_message_id > %s AND post_id = %s ORDER BY gig_message_id ASC LIMIT 1", $message_id, $id
        ) );       
        //Prepare Message
        if( !empty( $messages ) ){              
                foreach ( $messages as $value ) { 
                ob_start();
                $message    = $value->chat_message;
                $message_id = $value->gig_message_id;
                $user_id    = !empty( $value->user_id ) ? $value->user_id : '';                  
                $time   = !empty( $value->message_time ) ? $value->message_time : '';
                $files      = !empty( $value->chat_files ) ? $value->chat_files : '';
                $expected = !empty( $time ) ? date("F j, Y g:i a", $time ) : '';    
                $chat_files = array();
                if( !empty( $files ) ){ 
                    $chat_files = explode( ',', $files );               
                }            
                $username = '';
                $profile_image = '';    
                if( $user_id != '-1' && $user_id != '-2' && $user_id != '-3' ){
                    $username   = codesquare_workintry_get_full_username( $user_id );
                    $profile_images = get_user_meta( $user_id, 'profile_image', true);
                    $social_picture = get_user_meta( $user_id, 'picture', true );
                    $images         = !empty( $profile_images['image_data'] ) ? $profile_images['image_data'] : array();
                    $profile_id     = !empty( $profile_images['default_image'] ) ? $profile_images['default_image'] : '';
                    $profile_image  = !empty( $profile_id ) ? wp_get_attachment_image_url( $profile_id, 'thumbnail', true, true ) : $social_picture; 
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
                } elseif( $user_id == '-1' ){
                    $order_status = 'completed';
                ?>                
                    <li class="pc-dashboardbox delivered" message-id="<?php echo esc_attr( $message_id ); ?>">
                        <div class="pc-listings-item">  
                            <div class="pc-listings-content">   
                                <span><?php esc_html_e('Order marked as completed/delivered', 'workintry'); ?></span>
                            </div>
                        </div>
                    </li>
                <?php } elseif( $user_id == '-2' ){
                    $order_status = 'revision';
                ?>
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
               
                <?php } 
                $chat = ob_get_clean();

            $exact = count( $messages );
            $total = count( $messages );
            $total = $total - 1;
            //Get new Chat ID
            $chat_id = $messages[$total]->chat_message_id;
            //Find if message is there
            if( $exact > 0 ){
                $response['type'] = 'success';
                $response['message'] = $chat_id;
                $response['status'] = $order_status;
                $response['data'] = $chat;
                wp_send_json($response);                               
            } 
        }                            
    }
    add_action( 'wp_ajax_codesquare_workintry_get_order_chat_message', 'codesquare_workintry_get_order_chat_message' );
}

//Post New Gig Chat Message
if( !function_exists( 'codesquare_workintry_post_order_chat_message' ) ){
    function codesquare_workintry_post_order_chat_message(){
        global $wpdb, $current_user;
        $response = array();  
        $user_id    = $current_user->ID;
        $sender_id  = $_POST['userID'];
        $id         = $_POST['id'];    
        $message    = $_POST['message'];
        $gallery    = $_POST['gallery'];
        //SET Gallery
        if( empty( $gallery ) ){
            $gallery = NULL;
        }

        //Verification
        if( empty( $id ) || empty( $sender_id ) || empty($message) ){
            $response['type']       = 'error';
            $response['message']    = esc_html__('No kiddies please', 'workintry');
            wp_send_json($response);
        }

        //Verify user ID
        if( $sender_id != $user_id ){
            $response['type']       = 'error';
            $response['message']    = esc_html__('No kiddies please', 'workintry');
            wp_send_json($response);
        }

        //All Looks OK to submit 
        $chat_table_name    = $wpdb->prefix . 'gig_chat_message'; 
        $user_id            = $current_user->ID;       
        $chat_message       = $message;
        $status             = '1';
        $chat_id = $wpdb->insert( 
            $chat_table_name, 
            array( 
                'user_id'       => $user_id, 
                'chat_message'  => $chat_message,
                'message_time'  => time(),
                'status'        => $status,
                'chat_files'    => $gallery,
                'post_id'       => $id
            )          
        );
        //Verify Result
        if( $chat_id ){
            //Response
            $response['type'] = 'success';
            $response['message'] = esc_html__('Message sent', 'workintry');
            wp_send_json( $response );
        } else {
            //Response
            $response['type'] = 'error';
            $response['message'] = esc_html__('Something went wrong, try again', 'workintry');
            wp_send_json( $response );
        }
    }
    add_action( 'wp_ajax_codesquare_workintry_post_order_chat_message', 'codesquare_workintry_post_order_chat_message' );
}

//Make Order Complete
if( !function_exists( 'codesquare_workintry_make_order_complete' ) ){
    function codesquare_workintry_make_order_complete(){
        global $wpdb, $current_user;
        $response = array();          
        $user_id   = '-1';
        $id         = $_POST['id'];    
        $message    = 'done';
        //Verification
        if( empty( $id ) ){
            $response['type']       = 'error';
            $response['message']    = esc_html__('No kiddies please', 'workintry');
            wp_send_json($response);
        }        

        //All Looks OK to submit 
        $chat_table_name    = $wpdb->prefix . 'gig_chat_message'; 
        $user_id            = '-1';       
        $chat_message       = $message;
        $status             = '1';
        $chat_id = $wpdb->insert( 
            $chat_table_name, 
            array( 
                'user_id'       => $user_id, 
                'chat_message'  => $chat_message,
                'message_time'  => time(),
                'status'        => $status,
                'chat_files'    => NULL,
                'post_id'       => $id
            )          
        );
        //Verify Result
        if( $chat_id ){
            //Update Post Meta
            update_post_meta( $id, 'result', 'awaiting' );
            //Update Post and user level
            $get_user_earnings = codesquare_codesquare_workintry_get_user_earnings_unpaid_and_paid( $current_user->ID );
            //Get Levels            
            $level_1  = codesquare_workintry_get_settings_option('seller_one');
            $level_2  = codesquare_workintry_get_settings_option('seller_two');
            $level_3  = codesquare_workintry_get_settings_option('seller_three');
            $level_4  = codesquare_workintry_get_settings_option('seller_four');
            $level_5  = codesquare_workintry_get_settings_option('seller_five');
            $level_top = codesquare_workintry_get_settings_option('seller_top');
            $level_1 = !empty( $level_1 ) ? $level_1 : '';
            $level_2 = !empty( $level_1 ) ? $level_2 : '';
            $level_3 = !empty( $level_3 ) ? $level_3 : '';
            $level_4 = !empty( $level_4 ) ? $level_4 : '';
            $level_5 = !empty( $level_5 ) ? $level_5 : '';
            $level_top = !empty( $level_top ) ? $level_top : '';
            
            $user_level = '';
            if( !empty( $get_user_earnings ) && $get_user_earnings > $level_top ){
                $user_level = 'top';
            } elseif( !empty( $get_user_earnings ) && $get_user_earnings > $level_5 ) {
                $user_level = '5';
            } elseif( !empty( $get_user_earnings ) && $get_user_earnings > $level_4 ) {
                $user_level = '4';
            } elseif( !empty( $get_user_earnings ) && $get_user_earnings > $level_3 ) {
                $user_level = '3';
            } elseif( !empty( $get_user_earnings ) && $get_user_earnings > $level_2 ) {
                $user_level = '2';
            } elseif( !empty( $get_user_earnings ) && $get_user_earnings > $level_1 ) {
                $user_level = '1';
            } else {               
                $user_level = 'fresh';
            }  
            
            //Get all posts of the user and update this
            $args = array(
                'post_type' => 'workintry',
                'posts_per_page' => -1,               
                'post_status'    => 'publish',
                'author'        => $current_user->ID,
            );
            
            //Run Query
            $gigs            = new WP_Query( $args );  
            if( $gigs->have_posts() ){
                while( $gigs->have_posts() ){
                    $gigs->the_post();
                    global $post;
                    update_post_meta( $post->ID, 'cl_level', $user_level );
                }
            }

            //Response
            $response['type'] = 'success';
            $response['message'] = esc_html__('Message sent', 'workintry');
            wp_send_json( $response );
        } else {
            //Response
            $response['type'] = 'error';
            $response['message'] = esc_html__('Something went wrong, try again', 'workintry');
            wp_send_json( $response );
        }
    }
    add_action( 'wp_ajax_codesquare_workintry_make_order_complete', 'codesquare_workintry_make_order_complete' );
}

//Ask for revision
if( !function_exists( 'codesquare_workintry_ask_for_order_revision' ) ){
    function codesquare_workintry_ask_for_order_revision(){
        global $wpdb, $current_user;
        $response = array();          
        $user_id   = '-2';
        $id         = $_POST['id'];    
        $message    = 'done';
        //Verification
        if( empty( $id ) ){
            $response['type']       = 'error';
            $response['message']    = esc_html__('No kiddies please', 'workintry');
            wp_send_json($response);
        }        

        //All Looks OK to submit 
        $chat_table_name    = $wpdb->prefix . 'gig_chat_message'; 
        $user_id            = '-2';       
        $chat_message       = $message;
        $status             = '1';
        $chat_id = $wpdb->insert( 
            $chat_table_name, 
            array( 
                'user_id'       => $user_id, 
                'chat_message'  => $chat_message,
                'message_time'  => time(),
                'status'        => $status,
                'chat_files'    => NULL,
                'post_id'       => $id
            )          
        );

        //Update earnings table as well
        $gig_id = get_post_meta( $id, 'gig_id', true );
        //Set status to done by update meta
        //Set result to done by update meta
        //Send final emails to both buyer and seller :)
        $wpdb->update(
            $wpdb->prefix . 'workintry_earnings', array('status' => 'un-paid'), 
            array(
                'order_id'  => $id,
                'gig_id'    => $gig_id,
                'type'      => 'sell'
            )
        );
        //Update earnings table ends here
        //Verify Result
        if( $chat_id ){
            //Get revisions
            $used_gig_revisions = get_post_meta( $id, 'used_gig_revisions', true );
            $used_gig_revisions = !empty( $used_gig_revisions ) ? $used_gig_revisions : 0;
            $used_gig_revisions = $used_gig_revisions + 1;
            update_post_meta( $id, 'used_gig_revisions', $used_gig_revisions );
            //Update Post Meta
            delete_post_meta( $id, 'result' );
            //Response
            $response['type'] = 'success';
            $response['message'] = esc_html__('Message sent', 'workintry');
            wp_send_json( $response );
        } else {
            //Response
            $response['type'] = 'error';
            $response['message'] = esc_html__('Something went wrong, try again', 'workintry');
            wp_send_json( $response );
        }
    }
    add_action( 'wp_ajax_codesquare_workintry_ask_for_order_revision', 'codesquare_workintry_ask_for_order_revision' );
}

//Ask for revision
if( !function_exists( 'codesquare_workintry_make_order_as_done' ) ){
    function codesquare_workintry_make_order_as_done(){
        global $wpdb, $current_user;
        $response = array();          
        $user_id   = '-3';
        $id         = $_POST['id'];    
        $message    = 'done';
        //Verification
        if( empty( $id ) ){
            $response['type']       = 'error';
            $response['message']    = esc_html__('No kiddies please', 'workintry');
            wp_send_json($response);
        }        

        //All Looks OK to submit 
        $chat_table_name    = $wpdb->prefix . 'gig_chat_message'; 
        $user_id            = '-3';       
        $chat_message       = $message;
        $status             = '1';
        $chat_id = $wpdb->insert( 
            $chat_table_name, 
            array( 
                'user_id'       => $user_id, 
                'chat_message'  => $chat_message,
                'message_time'  => time(),
                'status'        => $status,
                'chat_files'    => NULL,
                'post_id'       => $id
            )          
        );
        
        //Verify Result
        if( $chat_id ){
            //Update earnings table as well
            $gig_id = get_post_meta( $id, 'gig_id', true );
            //Send final emails to both buyer and seller :)
            $wpdb->update(
                $wpdb->prefix . 'workintry_earnings', array('status' => 'un-paid'), 
                array(
                    'order_id'  => $id,
                    'gig_id'    => $gig_id,
                    'type'      => 'sell'
                )
            );
            //Update earnings table ends here
            //Update Post Meta
            update_post_meta( $id, 'result', 'done' ); 
            update_post_meta( $id, 'status', 'un-paid' );
            //Response
            $response['type'] = 'success';
            $response['message'] = esc_html__('Message sent', 'workintry');
            wp_send_json( $response );
        } else {
            //Response
            $response['type'] = 'error';
            $response['message'] = esc_html__('Something went wrong, try again', 'workintry');
            wp_send_json( $response );
        }
    }
    add_action( 'wp_ajax_codesquare_workintry_make_order_as_done', 'codesquare_workintry_make_order_as_done' );
}

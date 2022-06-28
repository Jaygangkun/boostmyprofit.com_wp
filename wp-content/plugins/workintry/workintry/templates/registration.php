<?php
/**
 * Template Name: Registration
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/* Define Global Variables */
if(is_user_logged_in() ){
	global $current_user;
	$user_identity = $current_user->ID;
	$profile_page_id = codesquare_workintry_get_profile_page_id();           
    $profile_url = '';
	if( !empty($profile_page_id) ) {
		$profile_url =  get_the_permalink( $profile_page_id );
		$profile_url	= codesquare_workintry_profile_menu_link($profile_url, 'insight', $user_identity, true);
	}
    wp_safe_redirect( $profile_url );
    exit();
}
$workintry_settings = get_option('workintry');
$enable_registration 	= !empty( $workintry_settings['login_registration'] ) ? $workintry_settings['login_registration'] : '';
$terms_link  		    = !empty( $workintry_settings['terms_page'] ) ? $workintry_settings['terms_page'] : '';
$terms_link	= !empty( $terms_link ) ? get_the_permalink($terms_link) : '';
$protocol = is_ssl() ? 'https' : 'http';			
//For future use only									
$redirect	= !empty( $_GET['redirect'] ) ? esc_url_raw( $_GET['redirect'] ) : '';

$main_banner 	= codesquare_workintry_get_settings_option('main_banner');
$lower_banner 	= codesquare_workintry_get_settings_option('lower_banner');
$main_banner 	= !empty( $main_banner ) ? $main_banner : CSC_WORKINTRY_PLUGIN_URL .'assets/images/banner.png';
$lower_banner 	= !empty( $lower_banner ) ? $lower_banner : CSC_WORKINTRY_PLUGIN_URL .'assets/images/banner.png';
$title 			= get_the_title( get_the_ID() );
$login_page_url	= codesquare_workintry_get_settings_option('register_page');
if( !empty($login_page_url) ) {
	$login_page_url	= get_the_permalink( $login_page_url );
}

//Show/Hide Fields
$show_first 			=  codesquare_workintry_get_settings_option('show_first_name');
$show_last 				=  codesquare_workintry_get_settings_option('show_last_name');
$show_gender 			=  codesquare_workintry_get_settings_option('show_gender');
$show_phone 			=  codesquare_workintry_get_settings_option('show_register_phone');
$google_client_id 		= codesquare_workintry_get_settings_option('google_client_id');
$google_client_secret 	= codesquare_workintry_get_settings_option('google_client_secret');
$facebook_client_id 		= codesquare_workintry_get_settings_option('facebook_client_id');
$facebook_client_secret 	= codesquare_workintry_get_settings_option('facebook_client_secret');
$facebook_login_page_url	= codesquare_workintry_get_settings_option('facebook_register_page');
if( !empty($facebook_login_page_url) ) {
	$facebook_login_page_url	= get_the_permalink( $facebook_login_page_url );
}
//Google Auth Login Library 
require_once codesquare_workintry_addon_template_exsits('/vendor/autoload');
$google_client = new Google_Client();
$google_client->setClientId($google_client_id);
$google_client->setClientSecret($google_client_secret);
$google_client->setRedirectUri($login_page_url);
$google_client->addScope('email');
$google_client->addScope('profile');
//If Google sent us authentication data
if( isset($_GET['code']) ){
	$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
	if( !isset($token["error"] ) ){
		//We are safe here
		$google_client->setAccessToken($token['access_token']);
		//Set token to session
		$google_service = new Google_Service_Oauth2($google_client);

		$data = $google_service->userinfo->get();		
		$profile_page_id = codesquare_workintry_get_profile_page_id();           
        $profile_url = '';
		if( !empty($profile_page_id) ) {
			$profile_url =  get_the_permalink( $profile_page_id );
			$profile_url	= codesquare_workintry_profile_menu_link($profile_url, 'insight', $user->ID, true);
		}		
		//Get Data
		$first_name = '';
		$email = '';
		$gender = '';
		$image = '';
		$user = array();
		if( !empty( $data['given_name'] ) ){
			$first_name = $data['given_name'];
		}
		if( !empty( $data['email'] ) ){
			$email 		= $data['email'];
		}
		if( !empty( $data['gender'] ) ){
			$gender 	= $data['gender'];
		}
		if( !empty( $data['picture'] ) ){
			$image 		= $data['picture'];
		}
		if( !empty( $email ) ){
			$user = get_user_by( 'email', $email );
		}
		if( !empty( $user->ID ) ){
			//Clear user if there any            	
			wp_clear_auth_cookie();
		    $user = wp_set_current_user( $user->ID );
		    wp_set_auth_cookie( $user->ID );		   
    		$user_identity = $user->ID;
    		do_action( 'wp_login', $user->user_login, $user );
    		$profile_page_id = codesquare_workintry_get_profile_page_id();           
            $profile_url = '';
			if( !empty($profile_page_id) ) {
				$profile_url =  get_the_permalink( $profile_page_id );
				$profile_url	= codesquare_workintry_profile_menu_link($profile_url, 'insight', $user_identity, true);
			}
		    wp_safe_redirect( $profile_url );
		    exit();
		} else {
			$random_password = rand(9);
			$userdata = array(
				'user_login'  		=> $email,
				'user_pass'    		=> $random_password,
				'user_email'   		=> $email,  
				'user_nicename'     => $first_name,  
			);
	
    		$user_identity 	 = wp_insert_user($userdata);
    		global $wpdb;
    		$user_role = 'workintry';
            //Get default settings data
            $total_ads          = codesquare_workintry_get_settings_option('total_ads');
            $featured_ads       = codesquare_workintry_get_settings_option('featured_ads');
            $highlight_ads      = codesquare_workintry_get_settings_option('highlight_ads');
            $bump_ads           = codesquare_workintry_get_settings_option('bump_ads');
            $feature_duration   = codesquare_workintry_get_settings_option('feature_duration');
            wp_update_user(array('ID' => esc_sql($user_identity), 'role' => esc_sql($user_role), 'user_status' => 1));
       								
            update_user_meta($user_identity, 'show_admin_bar_front', false);
            update_user_meta($user_identity, 'verify_user', $verify_user);
            update_user_meta($user_identity, 'email', esc_attr($email));
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
            update_user_meta( $user_identity, 'phone', $phone );
            update_user_meta( $user_identity, 'gender', $gender );
            update_user_meta( $user_identity, 'picture', $image );
            //Clear user if there any            	
			wp_clear_auth_cookie();
		    $user = wp_set_current_user( $user_identity );
		    wp_set_auth_cookie( $user->ID );		   
    		$user_identity = $user->ID;
    		do_action( 'wp_login', $user->user_login, $user );
    		$profile_page_id = codesquare_workintry_get_profile_page_id();           
            $profile_url = '';
			if( !empty($profile_page_id) ) {
				$profile_url =  get_the_permalink( $profile_page_id );
				$profile_url	= codesquare_workintry_profile_menu_link($profile_url, 'insight', $user_identity, true);
			}
		    wp_safe_redirect( $profile_url );
		    exit();
		}		
	}
}
//Facebook Authentication Process
if( !empty( $facebook_client_secret ) && !empty( $facebook_client_id ) ){
$facebook = new \Facebook\Facebook([
	'app_id' 				=> $facebook_client_id,
	'app_secret' 			=> $facebook_client_secret,
	'default_graph_version' => 'v2.10'
]);
$facebook_helper = $facebook->getRedirectLoginHelper();
$facebook_permissions = ['email'];
	$facebook_login_url = $facebook_helper->getLoginUrl($facebook_login_page_url, $facebook_permissions);
} else {
	$facebook_login_url = '';
}
?>
<?php 
get_header();
wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), '');
wp_enqueue_style( 'register', CSC_WORKINTRY_PLUGIN_URL .'assets/css/register.css', array(), '');
?>
<!-- Register Section -->
<div id="cp-wrapper" class="cp-wrapper cp-full-width-inside-parent">	
	<div class="cp-inner-banner-wrap" style="background: url(<?php echo esc_url( $main_banner ); ?>)">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12 col-lg-6">					
					<div class="cp-inner-bannertitle">
						<h4><?php echo esc_html( $title ); ?></h4>
					</div>
				</div>
			</div>
		</div>
	</div>					
	<!-- main login section -->
	<section class="cp-section-wrap">
		<div class="container">
			<div class="row">					
				<?php 
					if( is_user_logged_in() ){ 
					global $current_user;
					$user_identity = $current_user->ID;
					$profile_page_id = codesquare_workintry_get_profile_page_id();           
		            $profile_url = '';
					if( !empty($profile_page_id) ) {
						$profile_url = get_the_permalink( $profile_page_id );
						$profile_url	= codesquare_workintry_profile_menu_link($profile_url, 'insight', $user_identity, true);
					}
					$profile_url = !empty( $profile_url ) ? $profile_url : home_url();
					wp_safe_redirect( $profile_url );
		    		exit();
				?>
				<?php } else { ?>
					<?php if( !empty( $enable_registration ) && $enable_registration == 'enable' ){ ?>	
						<!-- Login Starts -->
						<div class="col-12 col-md-5 col-lg-4">
							<div class="cp-signin">
								<div class="cp-signin-title">
									<h5><?php esc_html_e('Welcome Back', 'workintry'); ?></h5>
								</div>
								<div class="cp-widget-content">
									<form class="cp-form cp-signin-form" action="#" method="post">
										<fieldset>
											<div class="form-group">
												<label class="form-title">
													<?php esc_html_e('Your Email*', 'workintry'); ?>
												</label>
												<input type="email" class="form-control" name="email" placeholder="<?php esc_attr_e('Web Developer', 'workintry'); ?>">
											</div>
											<div class="form-group">
												<label class="form-title"><?php esc_html_e('Password*', 'workintry'); ?></label>
												<input type="password" class="form-control" name="password" placeholder="<?php esc_attr_e('Password', 'workintry'); ?>">
											</div>
											<div class="form-group">
												<span class="cp-checkbox">
													<input id="signin" type="checkbox" name="rememberme" value="">
													<label for="signin"><?php esc_html_e('Remember me on this PC', 'workintry'); ?></label>
												</span>
											</div>
											<div class="form-group cp-formbtns">
												<a href="#" class="wi-btn active process-user-login"><?php esc_html_e('Login', 'workintry'); ?></a>	
											</div>
											<div class="form-group">
												<a href="#" data-toggle="modal" data-target="#clPwdModal">
													<?php esc_html_e('Forgot Password?', 'workintry'); ?>	
												</a>
											</div>
											<?php wp_nonce_field('login_user_request', 'login_user_request'); ?>
										</fieldset>
									</form>
									<div class="cp-signin-footer">	
										<a href="<?php echo esc_url( $facebook_login_url ); ?>" class="cp-btn cp-facebookbtn"><?php esc_html_e('Sign in with facebook', 'workintry'); ?></a>
										<?php if( !empty( $google_client_secret ) && !empty( $google_client_id ) ){ ?>	
											<a href="<?php echo esc_url($google_client->createAuthUrl() ); ?>" class="cp-btn cp-gmailbtn"><?php esc_html_e('Sign in with Gmail', 'workintry'); ?></a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<!-- Login Ends -->	
						<!-- Registration Starts -->											
						<div class="col-12 col-md-7 col-lg-8">						
							<div class="cp-register-banner" style="background: url(<?php echo esc_url( $lower_banner ); ?>)">
								<h2><span><?php esc_html_e('We Are Growing Daily', 'workintry'); ?></span> <?php esc_html_e('List Your Service Today. It's Free!', 'workintry'); ?></h2>
							</div>
							<form class="cp-form cp-register-form process-registration-form" action="#" method="post">
								<fieldset>
									<div class="form-group cp-bannerform-title">
										<h2><?php esc_html_e('Start Smart Today', 'workintry'); ?></h2>
									</div>
									<?php if( $show_first ){ ?>
										<div class="form-group form-group-half">
											<label class="form-title"><?php esc_html_e('First Name', 'workintry'); ?></label>
											<input type="text" class="form-control" name="register[first_name]" placeholder="<?php esc_attr_e('First Name', 'workintry'); ?>">
										</div>
									<?php } ?>
									<?php if( $show_last ){ ?>
										<div class="form-group form-group-half">
											<label class="form-title"><?php esc_html_e('Last Name', 'workintry'); ?></label>
											<input type="text" class="form-control" name="register[last_name]" placeholder="<?php esc_attr_e('Last Name', 'workintry'); ?>">
										</div>
									<?php } ?>
									<div class="form-group form-group-half">
										<label class="form-title"><?php esc_html_e('Username', 'workintry'); ?></label>
										<input type="text" class="form-control" name="register[username]" placeholder="<?php esc_attr_e('Ash02518', 'workintry'); ?>">
									</div>
									<div class="form-group form-group-half">
										<label class="form-title"><?php esc_html_e('Your Email','workintry'); ?></label>
										<input type="email" class="form-control" name="register[email]" placeholder="<?php esc_attr_e('Email', 'workintry'); ?>">
									</div>
									<?php if( $show_gender ){ ?>
										<div class="form-group form-group-half">
											<label class="form-title"><?php esc_html_e('Your Gender', 'workintry'); ?></label>
											<span class="cp-select">
												<select name="register[gender]">
													<option value="male"><?php esc_html_e('Male', 'workintry'); ?></option>
													<option value="female"><?php esc_html_e('Female', 'workintry'); ?></option>
													<option value="other"><?php esc_html_e('Other', 'workintry'); ?></option>
												</select>
											</span>
										</div>
									<?php } ?>
									<?php if( $show_phone ){ ?>
										<div class="form-group form-group-half">
											<label class="form-title"><?php esc_html_e('Your Phone', 'workintry'); ?></label>
											<input type="text" class="form-control" name="register[phone]" placeholder="<?php esc_attr_e('+1 2345 673', 'workintry'); ?>">
										</div>
									<?php } ?>
									<div class="form-group form-group-half">
										<label class="form-title"><?php esc_html_e('password', 'workintry'); ?></label>
										<input type="password" class="form-control" name="register[password]" placeholder="***********">
									</div>
									<div class="form-group form-group-half">
										<label class="form-title"><?php esc_html_e('Confirm Password', 'workintry'); ?></label>
										<input type="password" class="form-control" name="register[confirm_password]" placeholder="***********">
									</div>
									<?php wp_nonce_field('register_user_request', 'register_user_request'); ?>
									<div class="form-group cp-formbtns">
										<a href="#" class="wi-btn active process-register"><?php esc_html_e('Register Now', 'workintry'); ?></a>
										<span class="cp-checkbox">
											<input id="register" type="checkbox" name="terms">
											<?php if (!empty($terms_link)) { ?>
												<label for="register">
													<?php esc_html_e('Check here to indicate that you read and agree to the', 'workintry'); ?>&nbsp;<a target="_blank" href="<?php echo esc_url($terms_link); ?>">
														<?php esc_html_e('Terms & Conditions', 'workintry'); ?></a>&nbsp;<?php esc_html_e('and privacy policy offered by us!', 'workintry'); ?></label>
											<?php } else { ?>
											<label for="register"><?php esc_html_e('Check here to indicate that you read and agree to the terms & conditions and privacy policy offered by us!', 'workintry'); ?>
											<?php } ?></label>											
										</span>
									</div>
								</fieldset>
							</form>												
						</div>
						<!-- Registration Ends -->	
					<?php } else { ?>									
						<div class="alert alert-danger" role="alert">
							<?php esc_html_e('Login/Registration disbaled by admin', 'workintry'); ?>	
						</div>
					<?php } ?>
				<?php } ?>
				<!-- </div>	 -->				
			</div>
		</div>
	</section>
	<!-- main login section -->			
</div>
<!-- Trigger the modal with a button -->
<!-- Modal -->
<div id="clPwdModal" class="modal fade cf-registerpopup" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close cp-btn" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php esc_html_e('Retrieve Password', 'workintry'); ?></h4>
      </div>
      <div class="modal-body">
      	<form class="cl-get-password cf-formtheme" method="post">
	        <div class="from-data form-group">
	        	<label for="useremail" class="uname"> <?php esc_html_e('Email', 'workintry'); ?> </label>
				<input id="useremail" name="email" required type="email" placeholder="<?php esc_attr_e('Email*', 'workintry'); ?>" value="">
	        </div>
	        <?php wp_nonce_field('lost_password_request', 'lost_password_request'); ?>
	        <a type="button" class="wi-btn btn btn-default cl-get-user-password"><?php esc_html_e('Submit', 'workintry'); ?></a>
    	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="cp-btn btn btn-default" data-dismiss="modal"><?php esc_html_e('Close', 'workintry'); ?></button>
      </div>
    </div>

  </div>
</div>
<!-- Password Change Block -->
<?php 
 if (!empty($_GET['secret']) &&
                ( isset($_GET['action']) && $_GET['action'] == "reset" ) &&
                (!empty($_GET['user']) )
        ) {
            $reset_key 		= sanitize_text_field($_GET['secret']);
            $user 			= sanitize_text_field($_GET['user']);
            $action 		= sanitize_text_field($_GET['action']);
            $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user));
            if ($reset_key === $key) {
                $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user));

                $user = $user_data->user_login;
                $user_email = $user_data->user_email;

                if (!empty($user_data)) {                   
                    ?>
                    <div id="cl-lost-password" class="modal fade" role="dialog">
                        <div class="modal-dialog cl-modaldialog">
                            <div class="modal-content cl-modalcontent cl-password-change-box">
                                <div class="panel-lostps">
                                    <form class="cl-get-password-form">
                                        <div class="form-group">
                                            <div class="cl-modalhead">
                                                <h2><?php esc_html_e('Reset Password', 'workintry'); ?></h2>
                                            </div>
                                            <p><?php echo wp_get_password_hint(); ?></p>
                                            <div class="forgot-fields">
                                                <div class="form-group">
                                                    <label for="password"><?php esc_html_e('New password', 'workintry') ?></label>
                                                    <input type="password"  name="password" id="password" class="input" value="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="retype_password"><?php esc_html_e('Repeat password', 'workintry') ?></label>
                                                    <input type="password" name="retype_password" id="retype_password" class="input" value="">   
                                                </div>
                                            </div>   
                                            <?php wp_nonce_field('change_password_request', 'change_password_request'); ?>   
                                            <button class="cp-btn cl-btn-lg cl-resest-password" type="button"><?php esc_html_e('Submit', 'workintry'); ?></button>

                                            <input type="hidden" name="secret" value="<?php echo esc_attr( $reset_key ); ?>">
                                            <input type="hidden" name="reset_action" value="<?php echo esc_attr( $action ); ?>">
                                            <input type="hidden" name="login" value="<?php echo esc_attr( $user ); ?>">
                                        </div>
                                    </form>    
                                </div>
                            </div>
                        </div>
                    </div>                 
                    <a href="#" class="open-reset-password-box" data-toggle="modal" data-target="#cl-lost-password"></a>
                    <?php                   
					$cl_run_box = "jQuery(document).ready(function ($) {setTimeout(function() {jQuery('.open-reset-password-box').trigger('click');},300);});";
            		wp_add_inline_script('workintry-script', $cl_run_box, 'after');
                }
            }
        }        
        ?>
<!--Password Change Block -->
<?php 
get_footer();
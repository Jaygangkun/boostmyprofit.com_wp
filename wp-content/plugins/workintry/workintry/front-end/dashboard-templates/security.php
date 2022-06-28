<?php 
/**
 * Profile page template
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/*Global variables*/
global $current_user;
$email 			= $current_user->data->user_email;
$username 		= $current_user->data->user_login;
$website    	= $current_user->data->user_url;
$first_name 	= get_user_meta( $current_user->ID, 'first_name', true);
$last_name  	= get_user_meta( $current_user->ID, 'last_name', true);
$phone 			= get_user_meta( $current_user->ID, 'phone', true);
$gender 		= get_user_meta( $current_user->ID, 'gender', true);
$profile_images = get_user_meta( $current_user->ID, 'profile_image', true);
$images 		= !empty( $profile_images['image_data'] ) ? $profile_images['image_data'] : array();
$address  		= get_user_meta($current_user->ID, 'address', true );

//Social Details
$facebook_url 	= get_user_meta( $current_user->ID, 'facebook', true );
$twitter 		= get_user_meta( $current_user->ID, 'twitter', true );
$google 		= get_user_meta( $current_user->ID, 'google', true );
$pinterest 	 	= get_user_meta( $current_user->ID, 'pinterest', true );
$linkedin 		= get_user_meta( $current_user->ID, 'linkedin', true );
$instagram 		= get_user_meta( $current_user->ID, 'instagram', true );
?>
<div class="pc-divhaslayout cp-insights">	
	<!-- My Account Section Start -->
	<div class="row">		
		<div class="col-12 col-sm-12 col-md-12 col-lg-12">			
			<div class="pc-dashboardbox">
				<div class="pc-dashboardbox-title">
					<h3><?php esc_html_e('Change Password', 'workintry'); ?></h3>
				</div>
				<div class="pc-myaccount">	
					<form class="pc-formtheme cf-jobform cf-profileform cf-update-password-form">
						<fieldset>											
							<div class="form-group">
								<input type="password" name="password" class="form-control" placeholder="<?php esc_attr_e('Current Password', 'workintry'); ?>">
							</div>
							<div class="form-group">
								<input type="password" name="new-password" class="form-control" placeholder="<?php esc_attr_e('New Password', 'workintry'); ?>">
							</div>
							<div class="form-group">
								<input type="password" name="retype-password" class="form-control" placeholder="<?php esc_attr_e('Confirm New Password', 'workintry'); ?>">
							</div>	
							<div class="form-group">
								<div class="pc-savebtn">
									<a href="javascript:void(0);" class="pc-btn cf-update-password"><?php esc_html_e('Update', 'workintry'); ?></a>
								</div>
							</div>
							<?php wp_nonce_field('profile_change_password', 'profile_change_password'); ?>
						</fieldset>
					</form>	
				</div>
			</div>			
		</div>	
		<div class="col-12 col-sm-12 col-md-12 col-lg-12">			
			<div class="pc-dashboardbox">
				<div class="pc-dashboardbox-title">
					<h3><?php esc_html_e('Delete Account', 'workintry'); ?></h3>
				</div>
				<div class="pc-myaccount">	
					<form class="pc-formtheme cf-jobform cf-profileform cf-delete-account-form">
						<fieldset>											
							<div class="form-group">
								<input type="text" name="reason" class="form-control" placeholder="<?php esc_attr_e('Reason', 'workintry'); ?>">
							</div>									
							<div class="form-group">
								<textarea type="description" name="description" class="form-control" placeholder="<?php esc_attr_e('Description', 'workintry'); ?>"></textarea>
							</div>	
							<div class="form-group">
								<div class="pc-savebtn">
									<a href="javascript:void(0);" class="pc-btn cf-delete-account"><?php esc_html_e('Delete Now', 'workintry'); ?></a>
								</div>
							</div>
							<?php wp_nonce_field('profile_delete_account', 'profile_delete_account'); ?>
						</fieldset>
					</form>	
				</div>
			</div>			
		</div>			
	</div>
	<!-- My Account Section End -->									
</div>
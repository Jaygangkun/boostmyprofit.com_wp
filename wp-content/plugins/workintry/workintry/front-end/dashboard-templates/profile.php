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
$email 		= !empty( $current_user->data->user_email ) ? $current_user->data->user_email : '';
$username 	= $current_user->data->user_login;
$website    = $current_user->data->user_url;
$first_name = get_user_meta( $current_user->ID, 'first_name', true);
$last_name  = get_user_meta( $current_user->ID, 'last_name', true);
$description= get_user_meta( $current_user->ID, 'description', true);
$phone 		= get_user_meta( $current_user->ID, 'phone', true);
$gender 	= get_user_meta( $current_user->ID, 'gender', true);
$profile_images = get_user_meta( $current_user->ID, 'profile_image', true);
$images 		= !empty( $profile_images['image_data'] ) ? $profile_images['image_data'] : array();
$address  	= get_user_meta($current_user->ID, 'address', true );
$checked 	= 'checked';

//Social Details
$facebook_url 	= get_user_meta( $current_user->ID, 'facebook', true );
$twitter 		= get_user_meta( $current_user->ID, 'twitter', true );
$google 		= get_user_meta( $current_user->ID, 'google', true );
$pinterest 	 	= get_user_meta( $current_user->ID, 'pinterest', true );
$linkedin 		= get_user_meta( $current_user->ID, 'linkedin', true );
$instagram 		= get_user_meta( $current_user->ID, 'instagram', true );
//Country
$selected_country = get_user_meta( $current_user->ID, 'w_country', true );
$selected_country = !empty( $selected_country )  ? $selected_country : '';
//City
$selected_city = get_user_meta( $current_user->ID, 'w_city', true );
$selected_city = !empty( $selected_city )  ? $selected_city : '';
?>
<div class="pc-divhaslayout cp-insights">	
	<!-- My Account Section Start -->
	<div class="row">		
		<div class="col-12 col-sm-6 col-md-8 col-lg-8">			
			<div class="pc-dashboardbox">
				<div class="pc-dashboardbox-title">
					<h3><?php esc_html_e('Personal Details', 'workintry'); ?></h3>
				</div>
				<div class="pc-myaccount">	
					<form class="pc-formtheme cf-jobform cf-profileform cf-update-profile-form">
						<fieldset>	
							<div class="form-group">
								<input type="text" name="first-name" class="form-control" placeholder="<?php esc_attr_e('First Name', 'workintry'); ?>" value="<?php echo esc_attr( $first_name ); ?>">
							</div>
							<div class="form-group">
								<input type="text" name="last-name" class="form-control" placeholder="<?php esc_attr_e('Last Name', 'workintry'); ?>" value="<?php echo esc_attr( $last_name ); ?>">
							</div>
							<div class="form-group half-form-group">

								<div class="pc-select cf-country-to-city">	
									<?php do_action('codesquare_workintry_print_taxonomy_options', 'gig_country', esc_html__('Select Country', 'workintry'),'country', $selected_country); ?>
								</div>
								<input type="hidden" value="gig_country" class="get-country-name">
							</div>
							<div class="form-group half-form-group">
								<div class="pc-select cf-add-cities">
									<?php do_action('codesquare_workintry_print_taxonomy_options', 'gig_city', esc_html__('Select City', 'workintry'),'city', $selected_city); ?>
								</div>
							</div>
							<div class="form-group">
								<input type="text" name="phone" class="form-control" placeholder="<?php esc_attr_e('Phone No', 'workintry'); ?>" value="<?php echo esc_attr( $phone ); ?>">
							</div>				
							<div class="form-group">
								<input type="text" name="address" class="form-control" placeholder="<?php esc_attr_e('Address', 'workintry'); ?>" value="<?php echo esc_attr( $address ); ?>">
							</div>	
							<div class="form-group">
								<input type="text" name="website" class="form-control" placeholder="<?php esc_attr_e('Website', 'workintry'); ?>" value="<?php echo esc_attr( $website ); ?>">
							</div>
							<div class="form-group">
								<span class="cf-gender-type"><?php esc_html_e('I am a', 'workintry'); ?></span>
								<div class="cf-radiobox-holder">
									<div class="pc-radio">
										<input type="radio" name="gender" id="cf-male" value="male" <?php if( $gender == 'male' ){ echo esc_attr( $checked ); }?>>
										<label for="cf-male"><span><?php esc_html_e('Male', 'workintry'); ?></span></label>
									</div>
									<div class="pc-radio">
										<input type="radio" name="gender" id="cf-female" value="female" <?php if( $gender == 'female' ){ echo esc_attr( $checked ); }?>>
										<label for="cf-female"><span><?php esc_html_e('Female', 'workintry'); ?></span></label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<textarea name="description"><?php echo esc_html( $description ); ?></textarea>
							</div>									
							<div class="form-group">
								<div class="pc-savebtn">
									<a href="javascript:void(0);" class="pc-btn cf-update-profile"><?php esc_html_e('Update', 'workintry'); ?></a>
								</div>
							</div>
							<?php wp_nonce_field('profile_update_user_request', 'profile_update_user_request'); ?>
						</fieldset>
					</form>													
				</div>
			</div>			
		</div>	
		<div class="col-12 col-sm-6 col-md-4 col-lg-4">
			<div class="pc-dashboardbox">
				<div class="cp-usersetting cp-user-profile-box">
					<?php do_action('codesquare_workintry_print_user_profile_image'); ?>
					<form class="cp-formtheme cp-jobform">
						<fieldset class="cp-uploadfile-holder">
							<div class="cp-uploadfile">
								<div class="cp-title">
									<h3><?php esc_html_e('Attachments', 'workintry'); ?></h3>
								</div>
								<div class="form-group cf-clickupload">
									<label for="file">							
										<a href="javascript:;" id="cl-upload-profile-photo" class="cl-fileinput">
											<span><?php esc_html_e('Select Files', 'workintry'); ?></span>
										</a> 
										<div id="plupload-profile-container"></div>
									</label>	
								</div>			
								<div class="form-group cp-upload-imgs">
									<ul class="cf-hscrollbar">
										<?php 
										if( !empty( $images ) ) {
											foreach ( $images as $key => $value ) {
												$thumbnail = !empty( $value['thumb'] ) ? $value['thumb'] : '';
												$img_id = !empty( $value['image_id'] ) ? $value['image_id'] : '';
												$imgs = array();
												if( !empty( $img_id ) ){
													$imgs = wp_get_attachment_image_src( $img_id, 'full' );
												}
												$thumbnail = !empty( $imgs[0] ) ? $imgs[0] : '';
												if( !empty( $thumbnail ) ){
												?>
													<li class="cf-check cf-cross">
														<figure>
															<img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php esc_attr_e('Profile Image', 'workintry'); ?>">
															<a href="#" class="cf-tick-sign cf-add-profile-photo" data-id="<?php echo esc_attr( $value['image_id'] ); ?>" data-url="<?php echo esc_url( $thumbnail ); ?>"><i class="lnr lnr-checkmark-circle"></i></a>
															<a href="#" class="cf-cross-sign cf-delete-profile-photo" data-id="<?php echo esc_attr( $value['image_id'] ); ?>"><i class="lnr lnr-cross-circle"></i></a>
														</figure>
													</li>
												<?php 
												}
											}
										}
										?>							
									</ul>
								</div>
							</div>
						</fieldset>
					</form>
					<!-- Progress Bar -->
					<div id="myProgress">
					    <div id="myBar"></div>
					</div>
					<!-- Progress Bar -->
				</div>
			</div>
		</div>					
	</div>
	<!-- My Account Section End -->									
</div>
<script type="text/template" id="tmpl-append-profile-photo">
	<li class="cf-check cf-cross">
		<figure>
			<img src="{{data.thumbnail}}">
			<a href="#" class="cf-tick-sign cf-add-profile-photo" data-id="{{data.attachment_id}}" data-url="{{data.thumbnail}}"><i class="lnr lnr-checkmark-circle"></i></a>
			<a href="#" class="cf-cross-sign cf-delete-profile-photo" data-id="{{data.attachment_id}}"><i class="lnr lnr-cross-circle"></i></a>
		</figure>				
	</li>  
</script>
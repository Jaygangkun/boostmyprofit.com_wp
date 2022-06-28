<?php 
 /* Detail Page Video
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
 global $post;
 $post_author_id = get_post_field( 'post_author', $post->ID );
 $current_user_id = '';
 $user_name 		= '';
 $user_email 		= '';
 $user_phone 		= '';

 if( is_user_logged_in() ){
 	global $current_user;
 	$current_user_id = $current_user->ID;
 	$user_phone 		= get_user_meta( $current_user_id, 'phone', true );
 	$user_info = get_userdata( $current_user_id );
	$user_name = $user_info->display_name;
	$user_email = $user_info->user_email;
 }
 if( $current_user_id != $post_author_id ){
 ?>
 <aside>
	<div class="wi-profilehead">	  
	    <div class="wi-profile-content wi-contact">
	        <div class="wi-protitle">
				<h4>
					<?php esc_html_e('Contact Seller', 'workintry'); ?>
				</h4>
			</div>
			<div class="wi-form-content">
				<form class="wi-form wi-contactform cp-seller-form cp-send-author-msg">
					<fieldset>
						<div class="form-group">	
							<input type="text" class="form-control" name="sender-name" placeholder="<?php esc_attr_e('Your Name*', 'workintry'); ?>" value="<?php echo esc_attr( $user_name ); ?>">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="sender-phone" placeholder="<?php esc_attr_e('Phone*', 'workintry'); ?>" value="<?php echo esc_attr( $user_phone ); ?>">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="sender-email" placeholder="<?php esc_attr_e('Your Email*', 'workintry'); ?>" value="<?php echo esc_attr( $user_email ); ?>">
						</div>				
						<div class="form-group">
							<textarea class="form-control" name="sender-msg" placeholder="<?php esc_attr_e('Your Message*', 'workintry'); ?>"></textarea>
						</div>
						<div class="form-group wi-formbtns">
							<a href="javascript:void(0);" class="wi-btn active cp-send-msg-to-user" data-id="<?php echo esc_attr( $post->ID ); ?>" data-author="<?php echo esc_attr(  $post_author_id ); ?>" data-current="<?php echo esc_attr( $current_user_id ); ?>">
								<?php esc_html_e('Contact Seller', 'workintry'); ?>	
							</a>
						</div>
					</fieldset>
					<?php wp_nonce_field('user_ad_chat_request', 'user_ad_chat_request'); ?>
				</form>
			</div>
	    </div>
	</div>
</aside>
<?php } ?>
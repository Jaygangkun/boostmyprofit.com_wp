<?php 
 /* Detail Page Report Form
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
global $post;
$show_report = codesquare_workintry_get_settings_option('show_report');
$reasons = codesquare_workintry_get_settings_option('reasons');
$tips    = codesquare_workintry_get_settings_option('tips');
if( $show_report ){
if( !empty( $tips ) ){ 
?>
<div class="wi-profile-content wi-box-style">
	<div class="wi-protitle">
		<h4><?php esc_html_e('How it works ?', 'workintry'); ?></h4>
	</div>
	<div class="widgets-content">
		<ul class="hp-safetytips">
			<?php foreach ( $tips as $key => $value ) { ?>
				<li><span><?php echo esc_html( $value ); ?></span></li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php } ?>
<div class="wi-profile-content wi-box-style no-margin">
	<div class="wi-protitle">
		<h4><?php esc_html_e('Report Spam', 'workintry'); ?></h4>
	</div>
	<div class="widgets-content">
		<form class="hp-form hp-contactform cp-seller-form cf-report-form">
			<fieldset>
				<div class="form-group">					
					<input type="text" name="name" class="form-control workintry-report" placeholder="<?php esc_attr_e('Name*', 'workintry'); ?>">
				</div>
				<div class="form-group">					
					<input type="email" name="email" class="form-control" placeholder="<?php esc_attr_e('Email*', 'workintry'); ?>">
				</div>
				<div class="form-group">
					<span class="cp-select">						
						<select name="reason">
							<option value="">
								<?php esc_html_e('Select Reason*', 'workintry'); ?>		
							</option>
							<?php 
							if( !empty( $reasons ) ){
								foreach ( $reasons as $key => $value ) {
									?>
									<option value="<?php echo esc_attr( $value ); ?>">
										<?php echo esc_html( $value ); ?>		
									</option>
									<?php 
								}
							}
							?>
						</select>	
					</span>
				</div>
				<div class="form-group">					
					<textarea class="form-control" name="message" placeholder="<?php esc_attr_e('Your Message', 'workintry'); ?>"></textarea>
				</div>
				<div class="form-group hp-formbtns">
					<a href="javascript:void(0);" class="wi-btn active cf-send-ad-report" data-id="<?php echo esc_attr( $post->ID ); ?>"><?php esc_html_e('Report Now', 'workintry'); ?></a>
				</div>
			</fieldset>
			<?php wp_nonce_field('user_ad_report_request', 'user_ad_report_request'); ?>
		</form>
	</div>
</div>
<?php } ?>
<?php
/**
 * Chat Template
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/*Global variables*/
global $current_user;
$first_name = get_user_meta( $current_user->ID, 'first_name', true );
$last_name  = get_user_meta( $current_user->ID, 'last_name', true );
$user_id    = !empty( $_GET['identity'] ) ? intval( sanitize_text_field( $_GET['identity'] ) ) : '';
wp_enqueue_script( 'user-chat' );
wp_enqueue_style( 'lightgallery' );
wp_enqueue_script( 'lightgallery' );
wp_enqueue_script( 'moments' );
wp_enqueue_script( 'timeago' );
?>
<!-- dashboard Info Start -->
<div class="pc-haslayout">
	<div class="row">		
		<div class="col-12">
			<!-- My Account Section Start -->
			<div class="pc-dashboardbox">
				<div class="pc-messages-holder">
					<ul>
						<li>
							<form class="pc-formtheme pc-inboxsearch">
								<input type="hidden" class="from-user" value="<?php echo esc_attr( $current_user->ID ); ?>">
								<fieldset>
									<div class="form-group">
										<input type="text" class="form-control" placeholder="<?php esc_attr_e('Search', 'workintry'); ?>">
										<a href="javascript:void(0);"><i class="lnr lnr-magnifier"></i></a> 
									</div>
								</fieldset>
							</form>
							<?php 
								global $wpdb;
						        $current_user_id = $current_user->ID;
						        $chat_table_name = $wpdb->prefix . 'chat_message';
						        $users = $wpdb->get_results($wpdb->prepare( "SELECT to_user_id AS sender_id FROM $chat_table_name WHERE( to_user_id = %s OR from_user_id = %s) UNION SELECT from_user_id AS receiver_id FROM $chat_table_name WHERE( from_user_id = %s OR to_user_id = %s )", $current_user_id, $current_user_id, $current_user_id, $current_user_id ), "ARRAY_A" );			       
						        ob_start(); 						       
						        $users = array_values( $users );
						        $users_list = array();
						        if( !empty( $users ) ){
						            foreach ( $users as $user ) {
						                if( $user['sender_id'] == $current_user_id ){
						                    //remove current user form the list
						                } else {
						                	$is_user_online = get_user_meta( $user['sender_id'], 'cl_last_seen', true );
					                		$online_class = 'pc-useroffline';
					                		if(  $is_user_online == 'online' ){
					                			array_unshift( $users_list , $user['sender_id'] );
						                	} else {
						                		$users_list[] = $user['sender_id'];
						                	}
						                }
						            }
						        } 
						        ?>
						         <?php 
					            if( !empty( $users_list ) ){ ?>
					            	<div class="pc-inboxname-holder">
					            		<?php 
					                		foreach ( $users_list as $value ) { 
					                		$get_chat_count = codesquare_workintry_get_total_unseen_count_by_id( $value ); 
					                		if( empty( $get_chat_count ) || $get_chat_count == '0' ){
					                			$get_chat_count = '';
					                		}
					                		$user_thumb = codesquare_workintry_provide_author_thumbnail( $value, '56', '56' );
					                		$is_user_online = get_user_meta( $value, 'cl_last_seen', true );
					                		$online_class = 'pc-useroffline';
					                		if( $is_user_online == 'online' ){
					                			$online_class = 'pc-useronline';
					                		}
					                		$last_seen_time = '';
											$last_seen = get_user_meta( $value, 'cl_last_seen', true );
											if( !empty( $last_seen ) && $last_seen != 'online' ){
												$last_seen_time = human_time_diff( $last_seen, current_time( 'timestamp' ) );
											}

											//Get last message
											$messages = $wpdb->get_results( $wpdb->prepare( "SELECT * from $chat_table_name WHERE ( from_user_id = %s AND to_user_id = %s ) OR ( from_user_id = %s AND to_user_id = %s) ORDER BY message_time DESC LIMIT 1", $current_user_id ,$value, $value ,  $current_user_id ) );
											$last_message = '';
											if( !empty( $messages ) ){
							                    foreach ( $messages as $message ) {
							                    	if( !empty( $message->chat_message ) ){
							                    		$last_message = $message->chat_message;
							                    	}
							                    }
							                }
					                    ?>
											<div class="pc-inboxname pc-user-logged-in-<?php echo esc_attr( $value );?>">
												<figure class="pc-inboxname-img <?php echo esc_attr( $online_class ); ?>">
													<?php if( !empty( $get_chat_count ) ){ ?>
														<em>
														<?php echo esc_html( $get_chat_count ); ?>	
														</em>
													<?php } ?>
													<img src="<?php echo esc_url( $user_thumb ); ?>" alt="<?php esc_attr_e('img', 'workintry'); ?>">
												</figure>
												<div class="pc-inboxname-content">
													<h5><a href="javascript:void(0);" data-id="<?php echo esc_attr( $value ); ?>"><?php echo codesquare_workintry_get_full_username( $value ); ?></a></h5>
													<span>
														<?php if( !empty( $last_message ) ){ echo esc_html( $last_message ); } ?>
													</span>
												</div>
											</div>	
					                    <?php } ?>
					                </div>
					            <?php } else {
						        	esc_html_e('No chat found', 'workintry');
						        } ?>			            	
						</li>
						<li>
							<div class="pc-chatarea">								
								<div class="pc-chatuser pc-update-online-user"></div>	
								<div class="pc-messages-section pc-messages-section-<?php echo esc_attr( $current_user->ID ); ?> pc-load-chat">
									<div class="pc-inboxname-content">	
										<h2><?php esc_html_e('Choose desired user from left side [users box] to start conversation', 'workintry'); ?></h2>
									</div>
								</div>
								<form class="pc-formtheme pc-replaybox">
									<fieldset >
										<div class="form-group">
											<div class="pc-user-is-typing">
												<span></span>
											</div>
										</div>
										<div class="form-group pc-btn-form-box">
											<a href="javascript:;" id="cl-upload-chat-gallery" class="pc-btn cl-fileinput">	
														<i class="fa fa-image"></i>
													</a> 
											<input type="text" class="msg form-control" placeholder="<?php esc_attr_e('Type message...', 'workintry'); ?>">
											<a href="javascript:void(0);" class="pc-btn send"><?php esc_html_e('Send', 'workintry'); ?><i class="lnr lnr-location"></i></a>
										</div>										
									</fieldset>
									<div id="custom-progress-bar">
									    <div id="custom-progress"></div>
									</div>
									<!-- Image Uplaod -->
									<div class="cp-uploadfile-holder">
										<div class="cp-uploadfile clearfix">
											<div id="plupload-gallery-container"><ul class="cf-hscrollbar cf-gallery-images">					
												</ul></div>				
										</div>
									</div>
									<!-- Image Upload -->
								</form>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<!-- My Account Section End -->
		</div>
	</div>
</div>
<!-- dashboard Info End -->
<script type="text/template" id="tmpl-append-gallery-photo">
	<li class="cf-check cf-cross">
		<figure>
			<img src="{{data.response.thumbnail}}">			
			<a href="#" class="cf-cross-sign cf-delete-gallery-image" data-id="{{data.response.attachment_id}}"><i class="lnr lnr-cross-circle"></i></a>
		</figure>	
		<input type="hidden" class="galleryIds" name="gallery[{{data.count}}][id]" value="{{data.response.attachment_id}}">
		<input type="hidden" class="galleryImages" name="galleryImages[{{data.count}}][id]" value="{{data.response.thumbnail}}">
	</li>  
</script>
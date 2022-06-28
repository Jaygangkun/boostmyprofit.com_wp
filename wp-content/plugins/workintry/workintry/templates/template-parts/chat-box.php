<?php 
wp_enqueue_script('chat-single');
wp_enqueue_script( 'moments' );
wp_enqueue_script( 'timeago' );
wp_enqueue_script( 'scrollbar' );
wp_enqueue_style( 'scrollbar' ); 
wp_enqueue_style( 'cl-single-chat' );
global $wpdb, $current_user, $post;
$author_id 	  	= get_post_field( 'post_author', $post->ID );
if( $current_user->ID != $author_id ){
									$response = array();
									global $wpdb, $current_user;
									$from_user_id = $current_user->ID;
									$sender_id = $from_user_id;
									$to_user_id = $author_id;		
							        $chat_table_name = $wpdb->prefix . 'chat_message';
							        $messages = $wpdb->get_results($wpdb->prepare("SELECT * from $chat_table_name WHERE ( from_user_id = %s AND to_user_id = %s ) OR ( from_user_id = %s AND to_user_id = %s ) ORDER BY chat_message_id ASC ", $from_user_id, $to_user_id, $to_user_id, $from_user_id
							    		) );
							        	$receiver_content = '';
							        	$user_thumb = codesquare_workintry_provide_author_thumbnail( $to_user_id, '56', '56' );
							        	$user_full_name = codesquare_workintry_get_full_username( $to_user_id );
							        	$is_user_online = get_user_meta( $to_user_id, 'cl_last_seen', true );
							    		$online_class = 'pc-useroffline';
							    		if( $is_user_online == 'online' ){
							    			$online_class = 'pc-useronline';
							    		}           		
										$user_last_seen = get_user_meta( $to_user_id, 'cl_last_seen', true );
										$user_last_seen_time = '';
										if( $user_last_seen != 'online' ){
											if( is_numeric( $user_last_seen ) ){
												$user_last_seen_time = human_time_diff( $user_last_seen, current_time( 'timestamp' ) );
											}
										}
							        	?>
								        <?php 
								        	$receiver_content .= '<a href="javascript:void(0);" class="pc-closechat"><i class="lnr lnr-arrow-left"></i></a>
											<figure class="pc-inboxname-img ' . esc_attr( $online_class ) .'">
												<img src="'.esc_attr( $user_thumb ).'" alt="img">
											</figure>
											<div class="pc-inboxname-content">
												<h5>'.esc_attr( $user_full_name ).'</h5>';
												if( $user_last_seen_time != '' ){
													$receiver_content .= '<span>'.esc_html__("Last seen", "workintry").'&nbsp;'.esc_attr( $user_last_seen_time ).'&nbsp;'.esc_html__("ago", "workintry").'</span>';
												} elseif( empty( $user_last_seen_time ) ) {
													
												} else{
													$receiver_content .= '<span>'.esc_html__("Online", "workintry").'</span>';
												}
											$receiver_content .= '</div>';			
							            ob_start();
							            ?>           
							                <?php 
								                if( !empty( $messages ) ){
								                	
								                    foreach ( $messages as $value ) { 
								                    	$message_now  = $value->chat_message;
								                    		//Find link and prepare it as well
															$regex = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
												            preg_match_all(
												              $regex,
												               $value->chat_message,
												              $macth

												            );
												            if( is_array( $macth ) ){
														        $new_string = $macth[0];								        
													            $strings = array();
													            $strings_savable = array();
													            $old_message = '';
													            if( is_array( $new_string ) ){
													              foreach ( $new_string as $key => $values ) {
													                //Creating links html array
													                $strings[] = '<a href="'.esc_url($values).'">'.esc_url($values).'</a> ';
													              }
													            }						           
													            //Replacing found URL strings with their proper html markup
													            $my_values = str_replace($new_string, $strings, $message_now );
													            //Now make savable string of links
													            if( is_array( $new_string ) ){
													              foreach ( $new_string as $key => $values ) {
													                //Creating links html array
													                $strings_savable[] = '[link src="'.esc_url($values).'"]';
													              }
													            }
													            $my_savable_values = str_replace($strings_savable, $strings,  $message_now );
												        } else {
												        	$my_savable_values = $value->chat_message;
															//Link testing                                        
												        }

														$chat_files = $value->chat_files;

														$chat_files = !empty( $chat_files ) ? json_decode( $chat_files ) : '';
														$chat_files = (array) $chat_files;			
														//Chat files (images )						
														if( is_array( $chat_files ) && !empty( $chat_files['images'] ) ){
															$chat_files = $chat_files['images'];
														} 
														$nyimg = '';
														if( is_array( $chat_files ) ){        
												              foreach ( $chat_files as $image_urls) {
												              	$image = wp_get_attachment_image_src( $image_urls, 'thumbnail' );
												              	$image = $image[0];
												              	$full_image = wp_get_attachment_url( $image_urls );
												                $nyimg .= '<figure class="item" data-src="'.esc_url($full_image).'"><img src="'. esc_url($image) .'" alt=""></figure>';
												              }					              					            
														}
														
								                        if( intval( $value->from_user_id ) == intval( $sender_id ) ){
															$timestamp 		= strtotime( $value->message_time );	
								                        	?>
								                        	<div class="pc-messagessend  pc-reservedmsg">	
																<div class="pc-messagessend-content">
																	<span clas="time" data-livestamp="<?php echo esc_attr( $value->message_time ); ?>"><?php echo esc_html( $value->message_time  ); ?>&nbsp;<?php esc_html_e('ago', 'workintry'); ?></span>
																	<p><span class="message-right-arrow"></span><?php echo wp_kses( $my_savable_values, array(
																	    'a' => array(
																	        'href' => array(),
																	        'title' => array()
																	    ),    
																	) ); ?></p>
																	<?php if( !empty( $nyimg ) ){ echo wp_kses_post($nyimg); }; ?>
																</div>
															</div>                                   
								                        <?php } else { 
								                        		$timestamp 		= strtotime( $value->message_time );
								                        	?>
								                        	<div class="pc-messagessend">
																<div class="pc-messagessend-content">	
																	<span class="time" data-livestamp="<?php echo esc_attr($value->message_time); ?>"><?php echo esc_html($value->message_time ); ?>&nbsp;<?php esc_html_e('ago', 'workintry'); ?></span>
																	<p><span class="message-left-arrow"></span><?php echo wp_kses( $my_savable_values, array(
																		    'a' => array(
																		        'href' => array(),
																		        'title' => array()
																		    ),   
																		) ); ?></p><?php if( !empty( $nyimg ) ){ echo wp_kses_post($nyimg); }; ?>
																											</div>
																										</div>                            
																			                            <?php 
																			                        }
																			                    } 
																			                }
																		                ?>
							                <input type="hidden" name="to-user" class="to-user" value="<?php echo esc_attr( $to_user_id ); ?>">           
							            <?php 
							            $wpdb->query($wpdb->prepare("UPDATE $chat_table_name 
							            SET status = '0' 
							            WHERE to_user_id = %s
							            AND from_user_id = %s
							            AND status = '1'            
							            ", $from_user_id, $to_user_id
							        	));                  
							            $count = codesquare_workintry_get_total_unseen_count( $from_user_id );
							            if( !$count > 0 ){
							            	$count = 'none';
							            }							            
							            $data = ob_get_clean();
							        ?>


<input type="hidden" class="from-user" value="<?php echo esc_attr( $current_user->ID ); ?>">
<div class="pc-sidebar-chat-box">
<div class="pc-chatarea">								
								<div class="pc-chatuser pc-update-online-user">
									<?php echo wp_kses_post($receiver_content); ?>
									<a class="close"><i class="lnr lnr-cross-circle"></i></a>
								</div>	
								<div class="pc-messages-section pc-messages-section-<?php echo esc_attr( $current_user->ID ); ?> pc-load-chat">
									<?php echo wp_kses_post($data); ?>
									<input type="hidden" name="to-user" class="to-user" value="<?php echo esc_attr( $to_user_id ); ?>">
								</div>
								<div class="clearfix"></div>
								<form class="pc-formtheme pc-replaybox">
									<fieldset >
										<div class="form-group">
											<div class="pc-user-is-typing">
												<span></span>
											</div>
										</div>
                                        <?php if( is_user_logged_in() ){ ?>
										<div class="form-group pc-btn-form-box">
											<a href="javascript:;" id="cl-upload-chat-gallery" class="pc-btn cl-fileinput">	
														<i class="fa fa-image"></i>
													</a> 
											<input type="text" class="msg form-control" placeholder="<?php esc_html_e('Type message...', 'workintry'); ?>">
											<a href="javascript:void(0);" class="pc-btn send"><?php esc_html_e('Send', 'workintry'); ?><i class="lnr lnr-location"></i></a>
										</div>	
                                        <?php } else{ ?>
                                        <div class="form-group pc-btn-form-box">
                                           <p><?php esc_html_e('Login to start chat', 'workintry'); ?></p>
                                        </div>  
                                        <?php } ?>									
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
	<?php 		
		$script	= "
		jQuery('document').ready(function ($) {
			jQuery('.close').on('click', function(){
				jQuery('.pc-sidebar-chat-box').toggleClass('show-chat');
			});
			jQuery('.cp-start-chat').on('click', function(){
				jQuery('.pc-sidebar-chat-box').toggleClass('show-chat');
			});
		});";
		wp_add_inline_script('workintry-script', $script,'after');
    }
?>

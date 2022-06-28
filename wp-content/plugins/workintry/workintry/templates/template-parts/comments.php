<?php 
 /* Detail Page Comment
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquareco.com/
 * @since 1.0
 */
global $post;
$post_id = $post->ID;
$args = array(
    'post_id' => $post_id,    
);
$comments = get_comments( $args );
$count 	  = count( $comments );
$count 	  = !empty( $count ) ? $count : 0;
$comment_title = esc_html__('Reviews', 'workintry');
?>
<div class="wi-sinlewrap">
    <div class="wi-sinletitle">
        <h2>
        	<?php echo esc_html( $count ); ?>&nbsp;<?php esc_html_e('Reviews', 'workintry'); ?>
        </h2>
    </div>
    <div class="wi-sinlecontent wi-commentwrap">
    	<?php if( !empty( $comments ) ){ ?>
			<ul>
				<?php 
				foreach ( $comments as $key => $value ) {
					$author 		= $value->comment_author;
					$comment_time 	= $value->comment_date;
					$timestamp 		= strtotime( $comment_time );
					$difference 	= human_time_diff( $timestamp, current_time( 'timestamp' ) );	
					$user_id 		= $value->user_id;
					$profile_images = get_user_meta( $user_id, 'profile_image', true);
					$images 		= !empty( $profile_images['image_data'] ) ? $profile_images['image_data'] : array();
					$profile_id  	= !empty( $profile_images['default_image'] ) ? $profile_images['default_image'] : '';
					$profile_image 	= !empty( $profile_id ) ? wp_get_attachment_image_url( $profile_id, array(70, 70), true, true ) : CSC_WORKINTRY_PLUGIN_URL .'assets/images/70X70.jpg';

					//Gallery and Rating
					$gallery = get_comment_meta( $value->comment_ID, 'gallery', true );
					$rating  = get_comment_meta( $value->comment_ID, 'rating', true );
					$rating  = !empty( $rating ) ? $rating : 0;

					//Subject
					$subject = get_comment_meta( $value->comment_ID, 'subject', true );
				?>
				<li>
	                <div class="wi-comment">	                	
	                    <figure class="wi-commentimg">
	                        <img src="<?php echo esc_url( $profile_image ); ?>" alt="<?php echo esc_attr( $author ); ?>">
	                    </figure>
	                    <div class="wi-commentcontent">
	                        <h3><?php echo esc_html( $author ); ?></h3>
	                        <div class="wi-userrating">
	                            <span class="wi-stars">
	                            	<span class="hp-stars cf-stars" id="cf-stars-<?php echo esc_attr( $value->comment_ID ); ?>"></span>	 
	                            	<?php
										$id = "'#cf-stars-".esc_attr($value->comment_ID)."'";
										$script	= "jQuery(document).ready(function ($) {codesquareworkintryPrintStars(".$rating.", ".$id."); });";
											wp_add_inline_script('workintry-script', $script,'after');
									?>                           	
	                            </span>
	                            <em>(<?php esc_html_e('Published', 'workintry'); ?>&nbsp;<?php echo esc_html( $difference ); ?>&nbsp;<?php esc_html_e('ago', 'workintry'); ?>)</em>
	                        </div>
	                        <p><?php echo esc_html( $value->comment_content ); ?></p>
	                    </div>
	                </div>
	            </li>				
				<?php } ?>
			</ul>
		<?php } else{ ?>		
			<b><?php esc_html_e('Nothing here: ', 'workintry'); ?></b><?php esc_html_e('No review yet', 'workintry'); ?>
		<?php } ?>      
    </div>
</div>
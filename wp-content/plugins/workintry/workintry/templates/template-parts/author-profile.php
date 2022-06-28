<?php 
 /* Detail Page Video
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
global $post, $current_user;
$post_author_id	= !empty( $_GET['author-id'] ) ? $_GET['author-id'] : 0;
$show_address 	= codesquare_workintry_get_settings_option('show_address');
$show_phone 	= codesquare_workintry_get_settings_option('show_phone');
$show_email 	= codesquare_workintry_get_settings_option('show_email');
$show_website 	= codesquare_workintry_get_settings_option('show_website');
$show_social 	= codesquare_workintry_get_settings_option('show_social');
$author_id 	  	= $post_author_id;
$user_data 		= get_userdata( $author_id );
$username 		= codesquare_workintry_get_full_username( $author_id );
$website 		= $user_data->data->user_url;
$email 			= $user_data->data->user_email;
$registered_date= $user_data->user_registered;
$ago		    = date("M j, Y", strtotime($registered_date) ); 

//User Meta Data
$facebook 		= get_user_meta( $author_id, 'facebook', true );
$twitter 		= get_user_meta( $author_id, 'twitter', true );
$google 		= get_user_meta( $author_id, 'google', true );
$pinterest 		= get_user_meta( $author_id, 'pinterest', true );
$linkedin 		= get_user_meta( $author_id, 'linkedin', true );
$address 		= get_user_meta( $author_id, 'address', true );
$phone 			= get_user_meta( $author_id, 'phone', true );
$description 	= get_the_author_meta( 'user_description', $author_id );

$profile_images = get_user_meta( $author_id, 'profile_image', true);
$images 		= !empty( $profile_images['image_data'] ) ? $profile_images['image_data'] : array();
$profile_id  	= !empty( $profile_images['default_image'] ) ? $profile_images['default_image'] : '';
$profile_image 	= !empty( $profile_id ) ? wp_get_attachment_image_url( $profile_id, array(70, 70), true, true ) : CSC_WORKINTRY_PLUGIN_URL .'assets/images/70X70.jpg';

$author_url     = codesquare_workintry_get_settings_option('author_page');
//Author Page URL
$author_url 	= !empty( $author_url ) ? get_the_permalink( $author_url ) : '';
$author_url 	= add_query_arg( 'author-id', $post_author_id, $author_url );

//Author ratings
$ratings = codesquare_workintry_get_comment_average_ratings_of_user( $post_author_id );

//Default currency sign
$cl_default_currency = codesquare_workintry_default_system_currency_sign();

//Rating
$total_ratings = codesquare_workintry_get_comment_total__ratings_of_user( $post_author_id );

if( $total_ratings ){
    //
} else {
    $total_ratings = 0;
}

//User details
$wishlist = array();
$commenter_id = '';
if( is_user_logged_in() ){
    global $current_user;
    $wishlist = get_user_meta( $current_user->ID, 'cl_wishlist', true );
    $wishlist = !empty( $wishlist ) ? $wishlist : array();
    $commenter_id = $current_user->ID;
}

//Set class
$class      = 'cf-ad-to-fav cf-detail-wishlist cp-liked';
$save_text = esc_html__('Save this Gig now for future', 'workintry');
if( in_array($post->ID, $wishlist ) ){ 
    $class      = 'cf-detail-wishlist';
    $save_text  = esc_html__('Added to favourites', 'workintry');
}

$country 	= get_user_meta( $post_author_id, 'w_country', true );
$city 		= get_user_meta( $post_author_id, 'w_city', true );
$final_location = '';
if( !empty( $country ) && !empty( $city ) ){
	$final_location = $city .', ' . $country;
} elseif( empty( $country ) && !empty( $city ) ) {
	$final_location = $city;
} elseif( !empty( $country ) && empty( $city ) ){
	$final_location = $country;
}

//Get jobs counter
$args = array(
	'post_type' => 'gig-order',
	'posts_per_page' => -1,	
	'post_status' 	 => 'publish',
);
$meta_query_args = array();
$meta_query_args[] = array(
	'key' 		=> 'seller_id',
	'value'		=> $author_id,
	'compare'	=> '=',
);

//Check as per need
$meta_query_args[] = array(
	'key' 		=> 'status',
	'value'		=> array('un-paid','paid'),
	'compare'	=> 'IN',
);

//Meta Query Mixing
$query_relation = array('relation' => 'AND',);
$meta_query_args = array_merge($query_relation, $meta_query_args);
$args['meta_query'] = $meta_query_args;
$gigs 				= new WP_Query( $args );
$total_completed	= $gigs->found_posts;
?>
<aside>
	<div class="wi-profilehead">
	    <div class="wi-profile">
	        <figure>
	        	<img src="<?php echo esc_url( $profile_image ); ?>" alt="<?php echo esc_attr( $username ); ?>">
	        </figure>
	        <div class="wi-profileinfo">
	           	<?php do_action( 'codesquare_workintry_print_user_level', $post_author_id ); ?>
	            <div class="wi-ftitle">
	                <a href="#"><?php esc_html_e('Freelancer', 'workintry'); ?></a>
	                <h3><?php echo esc_html( $username ); ?></h3>
	                <span><em> <i class="fa fa-star"></i> <?php echo esc_html( $ratings ); ?></em> (<?php echo esc_html( $total_ratings ); ?>&nbsp;<?php esc_html_e('Feedback', 'workintry'); ?>)</span>
	            </div>
	        </div>
	    </div>
	    <div class="wi-profile-content">
	        <div class="wi-protitle">
	            <h4><?php esc_html_e('Brief Introduction', 'workintry'); ?></h4>
	            <p><?php echo esc_html( $description ); ?></p>
	        </div>
	        <ul class="wi-profilelist">
	            <li><span><em><?php esc_html_e('Since:', 'workintry'); ?></em><?php echo esc_html( $ago ); ?></span></li>
	            <li><span><em><?php esc_html_e('Response Time:', 'workintry'); ?></em><?php echo esc_html( $ago ); ?></span></li>
	            <li><span><em><?php esc_html_e('Jobs Completed:', 'workintry'); ?></em><?php echo esc_html( $total_completed ); ?><?php esc_html_e(' Jobs', 'workintry'); ?></span></li>
	            <?php if( !empty( $final_location ) ){ ?>
		            <li>
		            	<span>
		            		<em>
		            			<?php esc_html_e('From:', 'workintry'); ?>
		            		</em>	
		            		<?php echo esc_html( $final_location ); ?>
		            	</span>
		            </li>
	        	<?php } ?>
	        </ul>	       
	    </div>
	</div>
</aside>
<?php 
require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/author-contact');

	
	
					


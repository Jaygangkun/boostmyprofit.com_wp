<?php 
 /* Detail Page Video
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
global $post, $current_user;
$post_author_id	= $post->post_author;
$current_user_id = '';
if( is_user_logged_in() ){
    $current_user_id = $current_user->ID;
}
$post_type 		= get_post_type( $post->ID );
$post_type_args = '';
$post_id 		= $post->ID;
$show_address 	= codesquare_workintry_get_settings_option('show_address');
$show_phone 	= codesquare_workintry_get_settings_option('show_phone');
$show_email 	= codesquare_workintry_get_settings_option('show_email');
$show_website 	= codesquare_workintry_get_settings_option('show_website');
$show_social 	= codesquare_workintry_get_settings_option('show_social');
$author_id 	  	= get_post_field( 'post_author', $post_id );
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
//Tags
$tags 				= wp_get_post_terms( $post->ID, 'gig_tags' );

//Get Gig details
//Gigs
$basic_gigs 	= get_post_meta( $post_id, 'cl_gig_basic', true );
$gold_gigs 		= get_post_meta( $post_id, 'cl_gig_gold', true );
$diamond_gigs 	= get_post_meta( $post_id, 'cl_gig_diamond', true );

$cl_fast 		= get_post_meta( $post_id, 'cl_fast', true );
if( $cl_fast == '1' ){
	$cl_fast = 'on';
}
$display_class 		= $cl_fast == 'on' ? 'wi-display' : '';
$basic_fast 		= get_post_meta( $post_id, 'cl_basic_fast_delivery', true );
$basic_fast_price 	= get_post_meta( $post_id, 'cl_basic_fast_price', true );
$gold_fast 			= get_post_meta( $post_id, 'cl_gold_fast_delivery', true );
$gold_fast_price 	= get_post_meta( $post_id, 'cl_gold_fast_price', true );
$diamond_fast 		= get_post_meta( $post_id, 'cl_diamond_fast_delivery', true );
$diamond_fast_price = get_post_meta( $post_id, 'cl_diamond_fast_price', true );	

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

//Author Page URL
$author_url     = codesquare_workintry_get_settings_option('author_page');
//Author Page URL
$author_url     = !empty( $author_url ) ? get_the_permalink( $author_url ) : '';
$author_url     = add_query_arg( 'author-id', $post_author_id, $author_url );
?>
<aside>
	<div class="wi-sidepackage">
	    <ul class="nav nav-tabs" id="myTab" role="tablist">
	        <li class="nav-item">
	            <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true"><?php esc_html_e('Basic', 'workintry' ); ?></a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" id="bronze-tab" data-toggle="tab" href="#bronze" role="tab" aria-controls="bronze" aria-selected="false"><?php esc_html_e('Gold', 'workintry'); ?></a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" id="gold-tab" data-toggle="tab" href="#gold" role="tab" aria-controls="gold" aria-selected="false"><?php esc_html_e('Diamond', 'workintry'); ?> <i class="fa fa-star wi-packageicon"></i></a>
	        </li>
	    </ul>
	    <div class="tab-content" id="myTabContent">
	        <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
	            <div class="wi-spackagetitle">
	                <h4><?php if( !empty( $basic_gigs['title'] ) ){ echo esc_html( $basic_gigs['title'] ); } ?></h4>
	                <h3><sup><?php echo esc_html( $cl_default_currency ); ?></sup><?php if( !empty( $basic_gigs['price'] ) ){ echo esc_html( $basic_gigs['price'] ); } ?></h3>
	                <span><?php if( !empty( $basic_gigs['description'] ) ){ echo esc_html( $basic_gigs['description'] ); } ?></span>
	            </div>
	            <ul class="wi-includeslist">
	                <li><h4><?php esc_html_e('Basic Pacakge Includes:', 'workintry'); ?></h4></li>
	                <?php if( !empty( $basic_gigs['delivery'] ) ){ ?>
		                <li>
	    					<span>
	    					<?php 
	    						esc_html_e('Delivery in', 'workintry'); 
	    					?>
	    					<?php echo esc_html( $basic_gigs['delivery'] ); ?>
	    					<?php 
	    						esc_html_e('Days', 'workintry'); 
	    					?>
	    					</span>
	    				</li>  
    				<?php } ?> 
    				<?php if( !empty( $basic_gigs['revisions' ] ) ){ ?>
		                <li>
	    					<span>
	    					<?php 
	    						esc_html_e('Revisions', 'workintry'); 
	    					?>
	    					<?php echo esc_html( $basic_gigs['revisions' ] ); ?>    					
	    					</span>
	    				</li>  
    				<?php } ?>
	                <?php 
	                	if( !empty( $basic_gigs ) ){
	                		unset($basic_gigs['title']);
	                		unset($basic_gigs['description']);
	                		unset($basic_gigs['delivery']);
	                		unset($basic_gigs['revisions']);
	                		$basic_price = $basic_gigs['price'];
	                		unset($basic_gigs['price']);
	                		foreach ( $basic_gigs as $key => $value ) {
	                			if( !empty( $value ) ){
		                			?>
		                				<li>
		                					<span>
		                					<?php echo esc_attr( $key ) . ' ' . esc_attr( $value ); ?>
		                					</span>
		                				</li>
		                			<?php 
		                		}
	                			
	                		}
	                	}
	                ?>             
	            </ul>
	            <div class="wi-spackagebtns">
	            	<?php if( $current_user_id == $post_author_id ){ ?>
	                <a href="javascript:void(0);" class="wi-btn" data-pkg="basic" data-id="<?php echo esc_attr( $post_id ); ?>" data-price="<?php echo esc_attr( $basic_price ); ?>">
	                	<?php esc_html_e('Your own Gig', 'workintry'); ?>
	                </a>
	            	<?php } else { ?>
	            		<a href="#" class="wi-btn wi-buy-gig-from-side" data-pkg="basic" data-id="<?php echo esc_attr( $post_id ); ?>" data-price="<?php echo esc_attr( $basic_price ); ?>">
	                	<?php esc_html_e('Get Started Now', 'workintry'); ?>
	                	</a>
	            	<?php } ?>
	                <span><?php esc_html_e('Chose your desired gig and click Purchase now and get it done', 'workintry'); ?><i class="ti-info-alt"></i></span>
	            </div>
	        </div>
	        <div class="tab-pane fade" id="bronze" role="tabpanel" aria-labelledby="bronze-tab">
	            <div class="wi-spackagetitle">
	                <h4><?php if( !empty( $gold_gigs['title'] ) ){ echo esc_html( $gold_gigs['title'] ); } ?></h4>
	                <h3><sup><?php echo esc_html( $cl_default_currency ); ?></sup><?php if( !empty( $gold_gigs['price'] ) ){ echo esc_html( $gold_gigs['price'] ); } ?></h3>
	                <span><?php if( !empty( $gold_gigs['description'] ) ){ echo esc_html( $gold_gigs['description'] ); } ?></span>
	            </div>
	            <ul class="wi-includeslist">
	                <li><h4><?php esc_html_e('Gold Pacakge Includes:', 'workintry'); ?></h4></li>
	                <?php if( !empty( $gold_gigs['delivery' ] ) ){ ?>
	                <li>
    					<span>
    					<?php 
    						esc_html_e('Delivery in', 'workintry'); 
    					?>
    					<?php echo esc_html( $gold_gigs['delivery' ] ); ?>
    					<?php 
    						esc_html_e('Days', 'workintry'); 
    					?>
    					</span>
    				</li>  
    				<?php } ?> 
    				<?php if( !empty( $gold_gigs['revisions' ] ) ){ ?>
	                <li>
    					<span>
    					<?php 
    						esc_html_e('Revisions', 'workintry'); 
    					?>
    					<?php echo esc_html( $gold_gigs['revisions' ] ); ?>    					
    					</span>
    				</li>  
    				<?php } ?> 
	                <?php 
	                	if( !empty( $gold_gigs ) ){
	                		unset($gold_gigs['title']);
	                		unset($gold_gigs['description']);
	                		unset($gold_gigs['delivery']);
	                		unset($gold_gigs['revisions']);
	                		$gold_price = $gold_gigs['price'];
	                		unset($gold_gigs['price']);
	                		foreach ( $gold_gigs as $key => $value ) {
	                			if( !empty( $value ) ){
		                			?>
		                				<li>
		                					<span>
		                					<?php echo esc_attr( $key ) . ' ' . esc_attr( $value ); ?>
		                					</span>
		                				</li>
		                			<?php 
		                		}
	                			
	                		}
	                	}
	                ?>       
	            </ul>
	            <div class="wi-spackagebtns">
	            	<?php if( $current_user_id == $post_author_id ){ ?>
	                <a href="javascript:void(0);" class="wi-btn" data-pkg="basic" data-id="<?php echo esc_attr( $post_id ); ?>" data-price="<?php echo esc_attr( $basic_price ); ?>">
	                	<?php esc_html_e('Your own Gig', 'workintry'); ?>
	                </a>
	            	<?php } else { ?>
	                <a href="#" class="wi-btn wi-buy-gig-from-side" data-pkg="gold" data-id="<?php echo esc_attr( $post_id ); ?>" data-price="<?php echo esc_attr( $gold_price ); ?>">
	                	<?php esc_html_e('Get Started Now', 'workintry'); ?>
	                </a>
	            	<?php } ?>
	                <span><?php esc_html_e('Chose your desired gig and click Purchase now and get it done', 'workintry'); ?><i class="ti-info-alt"></i></span>
	            </div>
	        </div>
	        <div class="tab-pane fade" id="gold" role="tabpanel" aria-labelledby="gold-tab">
	            <div class="wi-spackagetitle">
	                <h4><?php if( !empty( $diamond_gigs['title'] ) ){ echo esc_html( $diamond_gigs['title'] ); } ?></h4>
	                <h3><sup><?php echo esc_html( $cl_default_currency ); ?></sup><?php if( !empty( $diamond_gigs['price'] ) ){ echo esc_html( $diamond_gigs['price'] ); } ?></h3>
	                <span><?php if( !empty( $diamond_gigs['description'] ) ){ echo esc_html( $diamond_gigs['description'] ); } ?></span>
	            </div>
	            <ul class="wi-includeslist">
	                <li><h4><?php esc_html_e('Diamond Pacakge Includes:', 'workintry'); ?></h4></li>
	                <?php if( !empty( $diamond_gigs['delivery' ] ) ){ ?>
		                <li>
	    					<span>
	    					<?php 
	    						esc_html_e('Delivery in', 'workintry'); 
	    					?>
	    					<?php echo esc_html( $diamond_gigs['delivery' ] ); ?>
	    					<?php 
	    						esc_html_e('Days', 'workintry'); 
	    					?>
	    					</span>
	    				</li>  
    				<?php } ?> 
    				<?php if( !empty( $diamond_gigs['revisions' ] ) ){ ?>
		                <li>
	    					<span>
	    					<?php 
	    						esc_html_e('Revisions', 'workintry'); 
	    					?>
	    					<?php echo esc_html( $diamond_gigs['revisions' ] ); ?>    					
	    					</span>
	    				</li>  
    				<?php } ?>
	                <?php 
	                	if( !empty( $diamond_gigs ) ){
	                		unset($diamond_gigs['title']);
	                		unset($diamond_gigs['description']);
	                		unset($diamond_gigs['delivery']);
	                		unset($diamond_gigs['revisions']);
	                		$diamond_price = $diamond_gigs['price'];
	                		unset($diamond_gigs['price']);
	                		foreach ( $diamond_gigs as $key => $value ) {
	                			if( !empty( $value ) ){
		                			?>
		                				<li>
		                					<span>
		                					<?php echo esc_attr( $key ) . ' ' . esc_attr( $value ); ?>
		                					</span>
		                				</li>
		                			<?php 
		                		}
	                			
	                		}
	                	}
	                ?>             
	            </ul>
	            <div class="wi-spackagebtns">
	            	<?php if( $current_user_id == $post_author_id ){ ?>
	                <a href="javascript:void(0);" class="wi-btn" data-pkg="basic" data-id="<?php echo esc_attr( $post_id ); ?>" data-price="<?php echo esc_attr( $basic_price ); ?>">
	                	<?php esc_html_e('Your own Gig', 'workintry'); ?>
	                </a>
	            	<?php } else { ?>
	                <a href="#" class="wi-btn wi-buy-gig-from-side" data-pkg="diamond" data-id="<?php echo esc_attr( $post_id ); ?>" data-price="<?php echo esc_attr( $diamond_price ); ?>">
	                	<?php esc_html_e('Get Started Now', 'workintry'); ?>
	                </a>
	            	<?php } ?>
	                <span><?php esc_html_e('Chose your desired gig and click Purchase now and get it done', 'workintry'); ?><i class="ti-info-alt"></i></span>
	            </div>
	        </div>
	    </div>
	</div>
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
	            <li><span><em><?php esc_html_e('Job Complete:', 'workintry'); ?></em><?php echo esc_html( $total_completed ); ?><?php esc_html_e(' Jobs', 'workintry'); ?></span></li>
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
	        <div class="wi-profilebtns">
	            <a href="<?php echo esc_url( $author_url ); ?>" class="wi-btntwo"><?php esc_html_e('Contact Seller', 'workintry'); ?></a>
	        </div>
	    </div>
	</div>
	
	<?php if( !empty( $tags ) ){ ?>
		<div class="wi-relatedtags">
			<h4><?php esc_html_e('Related Tags:', 'workintry'); ?></h4>
			<?php 
			$total = count( $tags );
			$counter = 0;
			foreach ( $tags as $key => $value ) {
			$counter = $counter + 1;
			$term_type = 'gig_tags';
		    $term_type = $value->taxonomy;	
		    $tag_name 	= $value->name;			
			$link = get_term_link( $value->term_id, $term_type);
			?>
				<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $tag_name ); ?><?php if( $counter !== $total ){ ?>,<?php } else { } ?></a>
			<?php } ?>
		</div>
	<?php } ?>
</aside>

	
	
					


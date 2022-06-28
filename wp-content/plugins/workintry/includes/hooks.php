<?php
/**
 * Ad Grid
 * Ad grid box
 */
if( !function_exists( 'codesquare_workintry_print_ad_grid' ) ){
	function codesquare_workintry_print_ad_grid($post_id = '', $width = '255', $height = '180'){
		global $post, $current_user;
		$post_author_id	= $post->post_author;
		$user_data 		= get_userdata( $post_author_id );
		$registered_date= $user_data->user_registered;
		$ago		    = date("M Y", strtotime($registered_date) );
		if( empty( $post_id ) ){
			return;
		}
		$width 			= !empty( $width ) ? intval($width) : 255;
		$height 		= !empty( $height ) ? intval($height) : 180;
		$thumbnail  	= codesquare_workintry_get_post_thumbnail($post_id, $width, $height);
		$gallery   		= get_post_meta( $post_id, 'cl_galleryc' );
		$post_status 	= get_post_meta($post_id, 'cl_type', true);
		
		//Thumbnail
		if( empty( $thumbnail ) ) {
        	$thumbnail = CSC_WORKINTRY_PLUGIN_URL .'assets/images/'.$width.'X'.$height.'.jpg';
    	}     	        	          		

		//Get author details
		$class = '';
		if( is_user_logged_in() ){
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
			$wishlist = get_user_meta( $user_id, 'cl_wishlist', true);
			$wishlist = !empty( $wishlist ) ? $wishlist : array();
			if( in_array( $post_id, $wishlist ) ){
				$class = 'hp-liked';
			} else {
				$class = 'cf-add-to-wish ';
			}
		} else {
			$class = 'cf-add-to-wish ';
		}		

		//Author ratings		
		$ratings = codesquare_workintry_get_comment_average_ratings( $post_id );
		
		//Rating
		$total_ratings = codesquare_workintry_get_comment_total_ratings( $post_id );
		if( $total_ratings ){
		    //
		} else {
		    $total_ratings = 0;
		}

		//Basic price
		$basic_price = get_post_meta( $post_id, 'cl_basic_price', true );
		$basic_price =  !empty( $basic_price ) ? sprintf("%02d", $basic_price ) : 0.00;	
		//Default currency sign
		$cl_default_currency = codesquare_workintry_default_system_currency_sign();

		ob_start();
		?>					
		<div class="wi-freelacner">
			<?php do_action( 'codesquare_workintry_print_gig_featured_tag', $post_id ); ?>
			<figure class="wi-freelacnerimg owl-carousel">
				<?php 
				if( !empty( $gallery ) ){
					foreach ( $gallery as $value ) {
						$full_image = wp_get_attachment_image_src( $value, 'ad-grid' );	
					?>					
					<img src="<?php echo esc_url( $full_image[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
				<?php } } else { ?>
					<img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
				<?php } ?>			
			</figure>
			<div class="wi-freelacnercontent">
				<figure class="wi-fuserimg">
					<?php do_action('codesquare_workintry_print_user_profile_image_all', $post_author_id );  ?>
				</figure>
				<?php do_action( 'codesquare_workintry_print_user_level', $post_author_id ); ?>
				<div class="wi-ftitle">
					<a href="javascript:void(0);"><?php esc_html_e('Freelancer', 'workintry'); ?></a>
					<h3><a href="<?php echo esc_url(get_the_permalink( $post_id )); ?>"><?php echo esc_attr( get_the_title( $post_id ) ); ?></a></h3>	
					<span><em><i class="fa fa-star"></i><?php echo esc_html( $ratings ); ?></em> (<?php echo esc_html( $total_ratings ); ?>&nbsp;<?php esc_html_e('Feedback', 'workintry'); ?>)</span>
					<strong><em><?php esc_html_e('From :', 'workintry'); ?></em><sup><?php echo esc_html( $cl_default_currency ); ?></sup><?php echo esc_html( $basic_price ); ?></strong>
				</div>
				<div class="wi-freelacnerfooter">
					<div class="wi-footersubfooter">
						<span><?php esc_html_e('Member Since:', 'workintry'); ?>&nbsp;<?php echo esc_html( $ago ); ?></span>	
						<a href="javascript:void(0);" class="wi-like <?php echo esc_attr( $class ); ?>" data-id="<?php echo esc_attr( $post_id ); ?>"><i class="fa fa-heart"></i></a>
					</div>
				</div>
			</div>
		</div>	
		<?php 
		echo ob_get_clean();
		//Post content
	}
	add_action('codesquare_workintry_print_ad_grid', 'codesquare_workintry_print_ad_grid', 10, 3);
}

/**
 * Ad Featured Tag
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_gig_featured_tag' ) ){
	function codesquare_workintry_print_gig_featured_tag( $post_id = '' ){
		if( empty( $post_id ) ){
			return;
		}
		$current_time 		= new DateTime();					
		$current_time_stamp = $current_time->getTimestamp();
		$featured_stamp 	= get_post_meta( $post_id, 'cl_timestamp', true);
		if( $featured_stamp > $current_time_stamp ){
			ob_start();
			?>
				<i class="fa fa-star wi-packageicon"></i>
			<?php 
			echo ob_get_clean();
		}
	}
	add_action('codesquare_workintry_print_gig_featured_tag', 'codesquare_workintry_print_gig_featured_tag', 10, 1);
}

/**
 * No Gig found
 * HTML
 */
if( !function_exists( 'codesquare_workintry_show_warning_message' ) ){
	function codesquare_workintry_show_warning_message($title = '', $warning = ''){
		if( !empty( $warning ) ) {
		ob_start();		
		?>
		<div class="alert alert-info">
			<strong><?php echo esc_html__($title, 'workintry'); ?></strong>&nbsp;<?php echo esc_html__($warning, 'workintry'); ?>
		</div>
		<?php 
		echo ob_get_clean();
		}
	}
	add_action('codesquare_workintry_show_warning_message', 'codesquare_workintry_show_warning_message', 10, 2);
}

/**
 * Taxonomy HTML
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_taxonomy_options' ) ){
	function codesquare_workintry_print_taxonomy_options($taxonomy = '', $placeholder = '', $name = 'category', $selected = '', $multiple = '', $parent = '' ){
		if( empty( $taxonomy ) ){
			return '';
		}
		//Name
		if( empty( $name ) ){
			$name = 'category';
		}
		//Placeholder
		if( empty( $placeholder ) ){
			$placeholder = esc_html__('Select Category', 'workintry');
		}

		//Multiple
		if( !empty( $multiple ) && $multiple = 'multiple' ){
			$multiple = 'multiple';
		}	
		
		//Get terms
		$args = array(
		    'taxonomy' 		=> $taxonomy,
		    'hide_empty' 	=> false,
		    'parent'       	=> 0,
			'number'        => 5000,
		);

		if( !empty( $parent ) ){
			$key = '';
			if( $taxonomy == 'gig_sub_category' ){
				$key = 'parent_category';
			} elseif( $taxonomy == 'gig_service' ){
				$key = 'gig_meta';
			}
			$args['meta_query'] = array(
                array(
                   'key'       => $key,
                   'value'     => $parent,
                   'compare'   => '='
                )
            );
		}		
		$terms = get_terms( $args );			
		ob_start();
		?>
		<select name="<?php echo esc_attr( $name ); ?>" <?php echo esc_attr( $multiple ); ?>>
      		<option value="" class="none"><?php echo esc_html( $placeholder ); ?></option>
      	<?php 
      	//Set class
      	$c_class = '';
      	if( $name == 'gig-category' ){
      		$c_class = 'gig-cat';
      	} elseif( $name == 'sub-category' ){
      		$c_class = 'service-';
      	} elseif( $name == 'gig-service' ){
      		$c_class = 'gig-service';
      	}
		//If terms object set
      	if( !empty( $terms ) ){ 
      		foreach ( $terms as $key => $value ) { ?>	
      			 <option value="<?php echo esc_attr( $value->slug ); ?>" class="<?php echo esc_attr( $c_class ); echo esc_attr( $value->term_id );?>" <?php selected( $value->slug, $selected, true); ?>><?php echo esc_html( $value->name ); ?></option>
      		<?php } ?>
      			
      		<?php 
      		
      	} 
      	?>
      	</select>
      	<?php	  
      	echo ob_get_clean();    			        	  
	}
	add_action('codesquare_workintry_print_taxonomy_options', 'codesquare_workintry_print_taxonomy_options', 10, 6);
}

/**
 * Search HTML
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_search_keyword') ){
	function codesquare_workintry_print_search_keyword(){
		$keyword = isset( $_GET['keyword'] ) && !empty( $_GET['keyword'] ) ? sanitize_text_field($_GET['keyword']) : '';
		ob_start();
		?>
			<input type="text" name="keyword" class="form-control" placeholder="<?php esc_attr_e('Keyword', 'workintry'); ?>" value="<?php echo esc_attr( $keyword ); ?>">		
		<?php
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_search_keyword', 'codesquare_workintry_print_search_keyword', 10);
}

/**
 * Search Categories
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_search_categories') ){
	function codesquare_workintry_print_search_categories(){
		$category = isset( $_GET['category'] ) && !empty( $_GET['category'] ) ? sanitize_text_field($_GET['category']) : '';
		ob_start();
		//Get terms
		$args = array(
		    'taxonomy' 		=> 'gig_category',
		    'hide_empty' 	=> false,
		    'parent'       	=> 0,
			'number'        => 5000,
		);
		$terms = get_terms( $args );
		$class = 'wi-category-filter';
		$cat_class = '';
		if( $category == '' ){
			$class = 'wi-category-filter selected';
			$cat_class = 'active';
		}
		?>
		<ul class="wi-widgetslist">
		<li class="<?php echo esc_attr( $cat_class ); ?>"><a class="<?php echo esc_attr( $class ); ?>" href="javascript:void(0)" data-id=""><?php esc_html_e('All Categories', 'workintry'); ?></a></li>
		<?php 
		if( !empty( $terms ) ){ 
      		foreach ( $terms as $key => $value ) {
      			$class = 'wi-category-filter';
      			$cat_class = '';
      			if( $category == $value->slug ){
      				$class = 'wi-category-filter selected';
      				$cat_class = 'active';
      			}
      		 ?>	
      			<li class="<?php echo esc_attr( $cat_class ); ?>"><a class="<?php echo esc_attr( $class ); ?>" href="javascript:void(0)" data-id="<?php echo esc_attr( $value->slug ); ?>"><?php echo esc_html( $value->name ); ?></a></li>
      		<?php } ?>
      	<?php } ?>
      	</ul>
      	<input type="hidden" class="wi-selected-cat" name="category" value="<?php echo esc_attr( $category ); ?>">				
		<?php
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_search_categories', 'codesquare_workintry_print_search_categories', 10);
}

/**
 * Search Categories
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_search_item_stype') ){
	function codesquare_workintry_print_search_item_stype(){
		$type = isset( $_GET['type'] ) && !empty( $_GET['type'] ) ? sanitize_text_field($_GET['type']) : '';
		ob_start();
		
		$class = 'wi-item-filter';
		$cat_class = '';
		if( $type == '' ){
			$class = 'wi-item-filter selected';
			$cat_class = 'active';
		}
		?>
		<ul class="wi-widgetslist">
		<li class="<?php echo esc_attr( $cat_class ); ?>">
			<a class="<?php echo esc_attr( $class ); ?>" href="javascript:void(0)" data-id=""><?php esc_html_e('Both', 'workintry'); ?>
			</a>
		</li>
		<?php 
		$class_1 = 'wi-item-filter';
		$cat_class_1 = '';
		if( $type == 'yes' ){
			$class_1 = 'wi-item-filter selected';
			$cat_class_1 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_1 ); ?>">
      		<a class="<?php echo esc_attr( $class_1 ); ?>" href="javascript:void(0)" data-id="yes"><?php esc_html_e('With revisions', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_2 = 'wi-item-filter';
		$cat_class_2 = '';
		if( $type == 'no' ){
			$class_2 = 'wi-item-filter selected';
			$cat_class_2 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_2 ); ?>">
      		<a class="<?php echo esc_attr( $class_2 ); ?>" href="javascript:void(0)" data-id="no"><?php esc_html_e('Without revisions', 'workintry'); ?>
      		</a>
      	</li>
      	</ul>
      	<input type="hidden" class="wi-selected-type" name="type" value="<?php echo esc_attr( $type ); ?>">				
		<?php
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_search_item_stype', 'codesquare_workintry_print_search_item_stype', 10);
}

/**
 * Search Categories
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_search_item_delivery') ){
	function codesquare_workintry_print_search_item_delivery(){
		$delivery = isset( $_GET['delivery'] ) && !empty( $_GET['delivery'] ) ? sanitize_text_field($_GET['delivery']) : '';
		ob_start();		
		$class = 'wi-delivery-filter';
		$cat_class = '';
		if( $delivery == '' ){
			$class = 'wi-delivery-filter selected';
			$cat_class = 'active';
		}
		?>
		<ul class="wi-widgetslist">
		<li class="<?php echo esc_attr( $cat_class ); ?>">
			<a class="<?php echo esc_attr( $class ); ?>" href="javascript:void(0)" data-id=""><?php esc_html_e('Both', 'workintry'); ?>
			</a>
		</li>
		<?php 
		$class_1 = 'wi-delivery-filter';
		$cat_class_1 = '';
		if( $delivery == 'yes' ){
			$class_1 = 'wi-delivery-filter selected';
			$cat_class_1 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_1 ); ?>">
      		<a class="<?php echo esc_attr( $class_1 ); ?>" href="javascript:void(0)" data-id="yes"><?php esc_html_e('With fast delivery', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_2 = 'wi-delivery-filter';
		$cat_class_2 = '';
		if( $delivery == 'no' ){
			$class_2 = 'wi-delivery-filter selected';
			$cat_class_2 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_2 ); ?>">
      		<a class="<?php echo esc_attr( $class_2 ); ?>" href="javascript:void(0)" data-id="no"><?php esc_html_e('Without fast delivery', 'workintry'); ?>
      		</a>
      	</li>
      	</ul>
      	<input type="hidden" class="wi-selected-delivery" name="delivery" value="<?php echo esc_attr( $delivery ); ?>">				
		<?php
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_search_item_delivery', 'codesquare_workintry_print_search_item_delivery', 10);
}

/**
 * Search Categories
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_search_item_price_limit') ){
	function codesquare_workintry_print_search_item_price_limit(){
		$currency = codesquare_workintry_default_system_currency_sign();
		$limit = isset( $_GET['limit'] ) && !empty( $_GET['limit'] ) ? sanitize_text_field($_GET['limit']) : '';		
		ob_start();		
		$class = 'wi-limit-filter';
		$cat_class = '';
		if( $limit == '' ){
			$class = 'wi-limit-filter selected';
			$cat_class = 'active';
		}
		?>
		<ul class="wi-widgetslist">
		<li class="<?php echo esc_attr( $cat_class ); ?>">
			<a class="<?php echo esc_attr( $class ); ?>" href="javascript:void(0)" data-min="" data-max=""><?php esc_html_e('Any price', 'workintry'); ?>
			</a>
		</li>
		<?php 
		$class_1 = 'wi-limit-filter';
		$cat_class_1 = '';
		if( $limit == '1' ){
			$class_1 = 'wi-limit-filter selected';
			$cat_class_1 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_1 ); ?>">
      		<a class="<?php echo esc_attr( $class_1 ); ?>" href="javascript:void(0)" data-id="1"><?php echo esc_html( $currency ); ?><?php esc_html_e('10 and below', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_2 = 'wi-limit-filter';
		$cat_class_2 = '';
		if( $limit == '2' ){
			$class_2 = 'wi-limit-filter selected';
			$cat_class_2 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_2 ); ?>">
      		<a class="<?php echo esc_attr( $class_2 ); ?>" href="javascript:void(0)" data-id="2"><?php echo esc_html( $currency ); ?><?php esc_html_e('10', 'workintry'); ?> - <?php echo esc_html( $currency ); ?><?php esc_html_e('30', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_3 = 'wi-limit-filter';
		$cat_class_3 = '';
		if( $limit == '3' ){
			$class_3 = 'wi-limit-filter selected';
			$cat_class_3 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_3 ); ?>">
      		<a class="<?php echo esc_attr( $class_3 ); ?>" href="javascript:void(0)" data-id="3" data-max="60"><?php echo esc_html( $currency ); ?><?php esc_html_e('30', 'workintry'); ?> - <?php echo esc_html( $currency ); ?><?php esc_html_e('60', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_4 = 'wi-limit-filter';
		$cat_class_4 = '';
		if( $limit == '4' ){
			$class_4 = 'wi-limit-filter selected';
			$cat_class_4 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_4 ); ?>">
      		<a class="<?php echo esc_attr( $class_4 ); ?>" href="javascript:void(0)" data-id="4"><?php echo esc_html( $currency ); ?><?php esc_html_e('60', 'workintry'); ?><?php esc_html_e(' - & above', 'workintry'); ?>
      		</a>
      	</li>
      	</ul>
      	<input type="hidden" class="wi-selected-limit" name="limit" value="<?php echo esc_attr( $limit ); ?>">				
		<?php
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_search_item_price_limit', 'codesquare_workintry_print_search_item_price_limit', 10);
}

/**
 * Search Categories
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_search_seller_level') ){
	function codesquare_workintry_print_search_seller_level(){
		$currency = codesquare_workintry_default_system_currency_sign();
		$level = isset( $_GET['level'] ) && !empty( $_GET['level'] ) ? sanitize_text_field($_GET['level']) : '';		
		ob_start();		
		$class = 'wi-level-filter';
		$cat_class = '';
		if( $level == '' ){
			$class = 'wi-level-filter selected';
			$cat_class = 'active';
		}
		?>
		<ul class="wi-widgetslist">
		<li class="<?php echo esc_attr( $cat_class ); ?>">
			<a class="<?php echo esc_attr( $class ); ?>" href="javascript:void(0)" data-id=""><?php esc_html_e('Any Level', 'workintry'); ?>
			</a>
		</li>
		<?php 
		$class_1 = 'wi-level-filter';
		$cat_class_1 = '';
		if( $level == 'top' ){
			$class_1 = 'wi-level-filter selected';
			$cat_class_1 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_1 ); ?>">
      		<a class="<?php echo esc_attr( $class_1 ); ?>" href="javascript:void(0)" data-id="top"><?php esc_html_e('Seller Top Level', 'workintry'); ?>
      		</a>
      	</li>
		<?php 
		$class_1 = 'wi-level-filter';
		$cat_class_1 = '';
		if( $level == '5' ){
			$class_1 = 'wi-level-filter selected';
			$cat_class_1 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_1 ); ?>">
      		<a class="<?php echo esc_attr( $class_1 ); ?>" href="javascript:void(0)" data-id="5"><?php esc_html_e('Seller Level # 05', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_2 = 'wi-level-filter';
		$cat_class_2 = '';
		if( $level == '4' ){
			$class_2 = 'wi-level-filter selected';
			$cat_class_2 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_2 ); ?>">
      		<a class="<?php echo esc_attr( $class_2 ); ?>" href="javascript:void(0)" data-id="4"><?php esc_html_e('Seller Level # 04', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_3 = 'wi-level-filter';
		$cat_class_3 = '';
		if( $level == '3' ){
			$class_3 = 'wi-level-filter selected';
			$cat_class_3 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_3 ); ?>">
      		<a class="<?php echo esc_attr( $class_3 ); ?>" href="javascript:void(0)" data-id="3"><?php esc_html_e('Seller Level # 03', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_4 = 'wi-level-filter';
		$cat_class_4 = '';
		if( $level == '2' ){
			$class_4 = 'wi-level-filter selected';
			$cat_class_4 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_4 ); ?>">
      		<a class="<?php echo esc_attr( $class_4 ); ?>" href="javascript:void(0)" data-id="2"><?php esc_html_e('Seller Level # 02', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_5 = 'wi-level-filter';
		$cat_class_5 = '';
		if( $level == '1' ){
			$class_5 = 'wi-level-filter selected';
			$cat_class_5 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_5 ); ?>">
      		<a class="<?php echo esc_attr( $class_5 ); ?>" href="javascript:void(0)" data-id="1"><?php esc_html_e('Seller Level # 01', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_6 = 'wi-level-filter';
		$cat_class_6 = '';
		if( $level == 'fresh' ){
			$class_6 = 'wi-level-filter selected';
			$cat_class_6 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_6 ); ?>">
      		<a class="<?php echo esc_attr( $class_6 ); ?>" href="javascript:void(0)" data-id="fresh"><?php esc_html_e('Fresh / Newbie', 'workintry'); ?>
      		</a>
      	</li>
      	</ul>
      	<input type="hidden" class="wi-selected-level" name="level" value="<?php echo esc_attr( $level ); ?>">				
		<?php
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_search_seller_level', 'codesquare_workintry_print_search_seller_level', 10);
}

/**
 * Search Ratings
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_search_ratings') ){
	function codesquare_workintry_print_search_ratings(){
		$currency = codesquare_workintry_default_system_currency_sign();
		$rating = isset( $_GET['rating'] ) && !empty( $_GET['rating'] ) ? sanitize_text_field($_GET['rating']) : '';		
		ob_start();		
		$class = 'wi-rating-filter';
		$cat_class = '';
		if( $rating == '' ){
			$class = 'wi-rating-filter selected';
			$cat_class = 'active';
		}
		?>
		<ul class="wi-widgetslist">
		<li class="<?php echo esc_attr( $cat_class ); ?>">
			<a class="<?php echo esc_attr( $class ); ?>" href="javascript:void(0)" data-id=""><?php esc_html_e('Any Ratings', 'workintry'); ?>
			</a>
		</li>
		<?php 
		$class_1 = 'wi-rating-filter';
		$cat_class_1 = '';
		if( $rating == '5' ){
			$class_1 = 'wi-rating-filter selected';
			$cat_class_1 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_1 ); ?>">
      		<a class="<?php echo esc_attr( $class_1 ); ?>" href="javascript:void(0)" data-id="5"><em class="wi-rating"><i class="fa fa-star"></i> <?php esc_html_e('5.0', 'workintry'); ?></em><?php esc_html_e('Five star ratings', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_2 = 'wi-rating-filter';
		$cat_class_2 = '';
		if( $rating == '4' ){
			$class_2 = 'wi-rating-filter selected';
			$cat_class_2 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_2 ); ?>">
      		<a class="<?php echo esc_attr( $class_2 ); ?>" href="javascript:void(0)" data-id="4"><em class="wi-rating"><i class="fa fa-star"></i> <?php esc_html_e('4.0', 'workintry'); ?></em><?php esc_html_e('Four star ratings', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_3 = 'wi-rating-filter';
		$cat_class_3 = '';
		if( $rating == '3' ){
			$class_3 = 'wi-rating-filter selected';
			$cat_class_3 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_3 ); ?>">
      		<a class="<?php echo esc_attr( $class_3 ); ?>" href="javascript:void(0)" data-id="3"><em class="wi-rating"><i class="fa fa-star"></i> <?php esc_html_e('3.0', 'workintry'); ?></em><?php esc_html_e('Three star ratings', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_4 = 'wi-rating-filter';
		$cat_class_4 = '';
		if( $rating == '2' ){
			$class_4 = 'wi-rating-filter selected';
			$cat_class_4 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_4 ); ?>">
      		<a class="<?php echo esc_attr( $class_4 ); ?>" href="javascript:void(0)" data-id="2"><em class="wi-rating"><i class="fa fa-star"></i> <?php esc_html_e('2.0', 'workintry'); ?></em><?php esc_html_e('Two star ratings', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_5 = 'wi-rating-filter';
		$cat_class_5 = '';
		if( $rating == '1' ){
			$class_5 = 'wi-rating-filter selected';
			$cat_class_5 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_5 ); ?>">
      		<a class="<?php echo esc_attr( $class_5 ); ?>" href="javascript:void(0)" data-id="1"><em class="wi-rating"><i class="fa fa-star"></i> <?php esc_html_e('1.0', 'workintry'); ?></em><?php esc_html_e('One star ratings', 'workintry'); ?>
      		</a>
      	</li>
      	<?php 
		$class_6 = 'wi-rating-filter';
		$cat_class_6 = '';
		if( $rating == 'fresh' ){
			$class_6 = 'wi-rating-filter selected';
			$cat_class_6 = 'active';
		}	
      	?>	
      	<li class="<?php echo esc_attr( $cat_class_6 ); ?>">
      		<a class="<?php echo esc_attr( $class_6 ); ?>" href="javascript:void(0)" data-id="fresh"><?php esc_html_e('Fresh / Newbie', 'workintry'); ?>
      		</a>
      	</li>
      	</ul>
      	<input type="hidden" class="wi-selected-rating" name="rating" value="<?php echo esc_attr( $rating ); ?>">				
		<?php
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_search_ratings', 'codesquare_workintry_print_search_ratings', 10);
}

/**
 * Insight HTML
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_dash_count') ){
	function codesquare_workintry_print_dash_count($user_id = '', $type = 'total'){
		global $current_user;
		//time stamp for current time
		$current_time = new DateTime();					
		$current_time_stamp = $current_time->getTimestamp();
		$img_url 	= '';
		//Profile page
		$profile_page = codesquare_workintry_get_profile_url();
		if( empty( $user_id ) ){
			return '';
		}

		$type = !empty( $type ) ? $type : 'total';
		$args = array(
			'post_type' => array('workintry','workintry_home', 'workintry_vehicle', 'workintry_mobile'),
			'author'	=> $user_id,
		);

		//Count as per post status/nature
		if( $type == 'total' ){
			$img_url = codesquare_workintry_get_settings_option('dashboard_total');
			$args['post_status'] = 'publish';
			$text = esc_html__('Total Gigs Posted', 'workintry');
			$link_type = 'all';
		} elseif( $type == 'featured' ){
			$img_url = codesquare_workintry_get_settings_option('dashboard_featured');
			$link_type = 'featured';
			$args['post_status'] = 'publish';
			$args['meta_query'] = array(
		        array(
		           'key' => 'cl_timestamp',
		           'value' => $current_time_stamp,
		           'compare' => '>',
		        )				
			);
			$text = esc_html__('Featured Gigs Posted', 'workintry');
		} elseif( $type == 'inactive' ){
			$img_url = codesquare_workintry_get_settings_option('dashboard_inactive');
			$link_type = 'inactive';
			$args['post_status'] = 'draft';
			$text = esc_html__('Inactive Gigs', 'workintry');
		} else {
			$img_url = codesquare_workintry_get_settings_option('dashboard_active');
			$link_type = 'active';
			$args['post_status'] = 'publish';
			$text = esc_html__('Active Gigs', 'workintry');
		}
		$query = new WP_Query( $args );		
		$total = $query->found_posts;
		$total = !empty( $total ) ? $total : 0;
		$img_url = !empty( $img_url ) ? $img_url : $img_url 	= CSC_WORKINTRY_PLUGIN_URL.'assets/images/56X56.png';
		$permalink = codesquare_workintry_profile_menu_link($profile_page, 'listings', $current_user->ID, true, $link_type); 
		ob_start();
		?>
		<div class="pc-dashboardinfo pc-dashboardinfo-color1">
			<div class="pc-dashboardinfocontent">
				<h4><?php echo esc_html( $total ); ?></h4>
				<span><?php echo esc_html( $text ); ?></span>
			</div>
			<div class="pc-dashboardinfo-icon">
				<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php esc_attr_e('image', 'workintry'); ?>">
			</div>
		</div>	
		<?php 
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_dash_count', 'codesquare_workintry_print_dash_count', 10, 2);
}

/**
 * User Profile Part
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_user_profile_image' ) ){
	function codesquare_workintry_print_user_profile_image(){		
		global $current_user; 
		$email 		= $current_user->data->user_email;
		$username 	= codesquare_workintry_get_full_username( $current_user->ID );
		$profile_images = get_user_meta( $current_user->ID, 'profile_image', true);
		$social_picture = get_user_meta( $current_user->ID, 'picture', true );
		$images 		= !empty( $profile_images['image_data'] ) ? $profile_images['image_data'] : array();
		$profile_id  	= !empty( $profile_images['default_image'] ) ? $profile_images['default_image'] : '';
		$profile_image 	= !empty( $profile_id ) ? wp_get_attachment_image_url( $profile_id, 'thumbnail', true, true ) : $social_picture; 
		$profile_image = !empty( $profile_image ) ? $profile_image : CSC_WORKINTRY_PLUGIN_URL .'assets/images/150X150.jpg';
		ob_start();
		?>		
        <div class="pc-user-box">
        	<figure>
	        	<strong>
	        		<img src="<?php echo esc_url( $profile_image ); ?>" alt="<?php echo esc_attr( $username ); ?>">
				</strong>
			</figure>
			<h2><?php echo esc_html( $username ); ?></h2>
		</div>
		<?php 
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_user_profile_image', 'codesquare_workintry_print_user_profile_image', 10);
}

/**
 * User Profile Part
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_user_profile_top_image' ) ){
	function codesquare_workintry_print_user_profile_top_image(){		
		global $current_user; 
		$email 		= $current_user->data->user_email;
		$username 	= codesquare_workintry_get_full_username( $current_user->ID );
		$profile_images = get_user_meta( $current_user->ID, 'profile_image', true);
		$images 		= !empty( $profile_images['image_data'] ) ? $profile_images['image_data'] : array();
		$profile_id  	= !empty( $profile_images['default_image'] ) ? $profile_images['default_image'] : '';
		$profile_image 	= !empty( $profile_id ) ? wp_get_attachment_image_url( $profile_id, 'thumbnail', true, true ) : CSC_WORKINTRY_PLUGIN_URL .'assets/images/56X56.jpg';
		ob_start();
		?>	
		<figure>
			<img src="<?php echo esc_url( $profile_image ); ?>" alt="<?php echo esc_attr( $username ); ?>">
			<figcaption>
				<a href="javascript:void(0);"><?php echo esc_html( $username ); ?></a>
				<i class="lnr lnr-chevron-down"></i>
			</figcaption>
		</figure>	        
		<?php 
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_user_profile_top_image', 'codesquare_workintry_print_user_profile_top_image', 10);
}

/**
 * User Profile Part
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_user_profile_image_all' ) ){
	function codesquare_workintry_print_user_profile_image_all( $user_id = '' ){
		$current_user = get_userdata( $user_id ) ;
		$username 	= codesquare_workintry_get_full_username( $current_user->ID );
		$profile_images = get_user_meta( $current_user->ID, 'profile_image', true);
		$images 		= !empty( $profile_images['image_data'] ) ? $profile_images['image_data'] : array();
		$profile_id  	= !empty( $profile_images['default_image'] ) ? $profile_images['default_image'] : '';
		$profile_image 	= !empty( $profile_id ) ? wp_get_attachment_image_url( $profile_id, 'thumbnail', true, true ) : CSC_WORKINTRY_PLUGIN_URL .'assets/images/56X56.jpg';
		ob_start();
		?>			
			<img src="<?php echo esc_url( $profile_image ); ?>" alt="<?php echo esc_attr( $username ); ?>">
		<?php 
		echo ob_get_clean();
	}
	add_action('codesquare_workintry_print_user_profile_image_all', 'codesquare_workintry_print_user_profile_image_all', 10, 1);
}

/**
 * Get term id by slug
 * HTML
 */
if( !function_exists( 'codesquare_workintry_get_term_id_by_slug' ) ){
	function codesquare_workintry_get_term_id_by_slug( $slug = '', $taxonomy = 'ad_category' ){
		if( !empty( $slug ) ){			
			$term = get_term_by( 'slug', $slug, $taxonomy, 'id' );		   
		    if( !empty( $term ) ){
		        $term_id = $term->term_id;		        
		        return $term_id;
		    }
		}
	    return '';
	}
}

/**
 * Check user Featured
 * HTML
 */
if( !function_exists( 'codesquare_workintry_can_user_add_featured' ) ){
	function codesquare_workintry_can_user_add_featured( $type = 'featured_ads' ){
		global $current_user;
		if( empty( $type ) ){
			return '';
		}
		$feature_exists = get_user_meta( $current_user->ID, $type, true );
		if( !empty( $feature_exists ) && $feature_exists > 0 ){
			return 'allowed';
		}
		return 'not_allowed';
	}
}

/**
 * Featured Form
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_featured_ad_form' ) ){
	function codesquare_workintry_print_featured_ad_form( $post_id = '' ){
		global $current_user;
		$can_user = codesquare_workintry_can_user_add_featured( 'featured_ads' );
		ob_start();
		if( !empty( $can_user ) && $can_user == 'allowed' ){
			if( !empty( $post_id ) ){
				$current_time = new DateTime();		
				$current_time_stamp = $current_time->getTimestamp();
				$featured_stamp = get_post_meta( $post_id, 'cl_timestamp', true );	
				$featured_stamp = !empty( $featured_stamp ) ? $featured_stamp : 0;
				if( $featured_stamp < $current_time_stamp ){
				?>
					<div class="form-group half-form-group-3 pc-tariff-price">
						<div class="pc-checkbox">
							<input type="checkbox" name="featured" id="backgroundimage" value="yes">
							<label for="backgroundimage"><span><?php esc_html_e('Set as Featured ?', 'workintry'); ?></span></label>
						</div>								
					</div>	
				<?php 
				} else { ?>
					<div class="form-group half-form-group-3 pc-tariff-price">
						<input type="hidden" name="featured" value="yes">
						<h2><?php esc_html_e('Featured', 'workintry'); ?></h2>	
					</div>
				<?php }				
			} else {
				?>
				<div class="form-group half-form-group-3 pc-tariff-price">
					<div class="pc-checkbox">
						<input type="checkbox" name="featured" id="backgroundimage" value="yes">
						<label for="backgroundimage"><span><?php esc_html_e('Set as Featured ?', 'workintry'); ?></span></label>
					</div>								
				</div>				
				<?php 
			}		
 		?>
 			
 		<?php 
		echo ob_get_clean();
		} else { ?>
			<div class="form-group half-form-group-3 pc-tariff-price">
				<div class="pc-checkbox">
					<label for="backgroundimage"><span><?php esc_html_e('Update your package to set featured Gigs', 'workintry'); ?></span></label>
				</div>								
			</div>
		<?php }
		return '';
	}
	add_action('codesquare_workintry_print_featured_ad_form', 'codesquare_workintry_print_featured_ad_form', 10, 1);
}

/**
 * Bump Up Form
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_bump_ad_form' ) ){
	function codesquare_workintry_print_bump_ad_form( $post_id = '' ){
		global $current_user;
		$can_user = codesquare_workintry_can_user_add_featured( 'bump_ads' );
		ob_start();
		if( !empty( $can_user ) && $can_user == 'allowed' ){
			if( !empty( $post_id ) ){
				$highlight = get_post_meta( $post_id, 'cl_bump', true );
				if( !empty( $highlight ) && $highlight == 'no' ){
					?>
						<div class="form-group half-form-group-3 pc-tariff-price">
							<div class="pc-checkbox">
								<input type="checkbox" name="bump" id="backgroundimages" value="yes">
								<label for="backgroundimages"><span><?php esc_html_e('Bump UP Gig?', 'workintry'); ?></span></label>
							</div>								
						</div>					
					<?php 
				} else { ?>
					<div class="form-group half-form-group-3 pc-tariff-price">
						<h2><?php esc_html_e('Bump up Gig', 'workintry'); ?></h2>			
					</div>
				<?php }
			} else {		
 		?>	 		
			<div class="form-group half-form-group-3 pc-tariff-price">
				<div class="pc-checkbox">
					<input type="checkbox" name="bump" id="carouselss" value="yes">
					<label for="carouselss"><span><?php esc_html_e('Set as Bump up Gig?', 'workintry'); ?></span></label>
				</div>								
			</div>	
 		<?php 
		echo ob_get_clean();
		}
		} else { ?>
			<div class="form-group half-form-group-3 pc-tariff-price">
				<div class="pc-checkbox">
				<label for="carousels"><span><?php esc_html_e('Update your package to set Bump up Gigs', 'workintry'); ?></span></label>
				</div>			
			</div>
		<?php }
	}
	add_action('codesquare_workintry_print_bump_ad_form', 'codesquare_workintry_print_bump_ad_form', 10, 1);
}

/**
 * @Set Post Views
 * @return
 */
if (!function_exists('codesquare_workintry_add_ad_view')) {
    function codesquare_workintry_add_ad_view($post_id = '', $key = '',$cookie_name='views_count') {
		
        if (!isset($_COOKIE[$key . $post_id])) {
            setcookie($key . $post_id, $cookie_name, time() + 3600);
            $count = get_post_meta($post_id, $key, true);
			
            if ($count == '') {
                $count = 1;
                update_post_meta($post_id, $key, $count);
            } else {
                $count++;
                update_post_meta($post_id, $key, $count);
            }
   			$post_author_id = get_post_field( 'post_author', $post_id );
            //Get current day
			$current_day = date('l', time());			
			$current_day = strtolower( $current_day );
			//Get today's timestamp
			$today_stamp = strtotime( date('Y-m-d') );
			//Now get today's views count and also check if its not passed yet
			$get_current_day_stamp = '';
			$workintry_user_views = get_user_meta( $post_author_id, 'workintry_ad_views', true );
				
			//Verify if data is there
			$get_current_day = !empty( $workintry_user_views[strtolower( $current_day ) ] ) ? $workintry_user_views[strtolower( $current_day ) ] : array();			
			//As we have got data for current day if there now its time to verify it
			if( is_array( $get_current_day ) ){
				//Get saved stamp in db for the user
				$get_current_day_stamp = !empty( $get_current_day['timestamp'] ) ? $get_current_day['timestamp'] : '';	
			}
			//If today's stam is equal to current stamp its OK else date is passed			
			if( $get_current_day_stamp == $today_stamp ){				
				//it means both are same so its today no need to update any thing but count value
				$todays_counts = !empty( $get_current_day['view'] ) ? $get_current_day['view'] : 0;		
				$todays_counts = $todays_counts + 1;				
				
				$workintry_user_views[strtolower( $current_day )]['timestamp'] = $today_stamp;
				$workintry_user_views[strtolower( $current_day )]['view'] = $todays_counts;
				update_user_meta( $post_author_id, 'workintry_ad_views', $workintry_user_views );
			} elseif( $today_stamp > $get_current_day_stamp ) {		
				$workintry_user_views = array();
				//It means day has passed we have to set it to 0
				$workintry_user_views[strtolower( $current_day ) ]['timestamp'] = $today_stamp;
				$workintry_user_views[strtolower( $current_day ) ]['view'] = 1;
				update_user_meta( $post_author_id, 'workintry_ad_views', $workintry_user_views );
			}			
            //Update user count view ends
        }
    }
    add_action('codesquare_workintry_add_ad_view', 'codesquare_workintry_add_ad_view', 10,3);
}

/*
* Chat Template File
* Create Custom Chat Tables
*/   
global $wpdb, $current_user;
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
add_action('init', 'codesquare_workintry_get_current_user_id');
function codesquare_workintry_get_current_user_id(){
    global $current_user_id;
    $current_user_id = get_current_user_id();   
    return $current_user_id;
}
$current_user_id = codesquare_workintry_get_current_user_id();
$charset_collate = $wpdb->get_charset_collate();
$chat_table_name = $wpdb->prefix . 'chat_message';
$chat_details = "CREATE TABLE IF NOT EXISTS $chat_table_name (
    chat_message_id mediumint(20) NOT NULL AUTO_INCREMENT,
    to_user_id mediumint(11) NOT NULL,     
    from_user_id mediumint(11) NOT NULL,
    chat_message text,
    message_time text NULL,
    status mediumint(11) NOT NULL, 
    chat_files text NULL,        
    PRIMARY KEY (chat_message_id)
) $charset_collate;";
dbDelta($chat_details);
        
//Earnings and widthdrawals tables
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
$withdrawal_table = $wpdb->prefix . 'withdrawal_earnings';
$earnings_table = $wpdb->prefix . 'workintry_earnings';
$withdrawal_query = "CREATE TABLE IF NOT EXISTS $withdrawal_table (
    id mediumint(11) NOT NULL AUTO_INCREMENT,
    user_id mediumint(11) NOT NULL,
    amount FLOAT NOT NULL DEFAULT '0.0',    
	currency_symbol varchar(50) NOT NULL,
    payment_method varchar(255) NOT NULL,
    processed_date DATETIME NULL,
    timestamp BIGINT NOT NULL,
    year YEAR NULL DEFAULT NULL,
	month varchar(5) NOT NULL,
	status ENUM('paid','un-paid', 'pending') NOT NULL DEFAULT 'paid',
    PRIMARY KEY (id)
    ) $charset_collate;";

$earnings_query = "CREATE TABLE IF NOT EXISTS $earnings_table (
    id mediumint(11) NOT NULL AUTO_INCREMENT,
    user_id mediumint(11) NOT NULL,
    amount FLOAT NOT NULL DEFAULT '0.0',
    user_amount FLOAT NOT NULL DEFAULT '0.0',
    admin_amount FLOAT NOT NULL DEFAULT '0.0',
	order_id varchar(50) NOT NULL,
	gig_id varchar(50) NOT NULL,
    process_date DATETIME NULL,
    timestamp BIGINT NOT NULL,
	order_date DATETIME NULL,
    year YEAR NULL DEFAULT NULL,
	month varchar(5) NOT NULL,
	status ENUM('paid','un-paid','pending') NOT NULL DEFAULT 'un-paid',
	type ENUM('sell','buy') NOT NULL DEFAULT 'sell',
    PRIMARY KEY (id)
    ) $charset_collate;";
dbDelta($withdrawal_query);
dbDelta($earnings_query);

//Gig chat
$gig_chat_table_name = $wpdb->prefix . 'gig_chat_message';
$gig_chat_details = "CREATE TABLE IF NOT EXISTS $gig_chat_table_name (
    gig_message_id mediumint(20) NOT NULL AUTO_INCREMENT,
    user_id mediumint(11) NOT NULL,        
    chat_message text,
    message_time text NULL,
    status mediumint(11) NOT NULL, 
    chat_files text NULL, 
    post_id mediumint(20) NULL,       
    PRIMARY KEY (gig_message_id)
) $charset_collate;";
dbDelta($gig_chat_details);
/*
* Get User Earnings
*/
if( !function_exists( 'codesquare_codesquare_workintry_get_user_earnings_unpaid_and_paid') ){
	function codesquare_codesquare_workintry_get_user_earnings_unpaid_and_paid( $user_id = '', $month = '', $year = ''){
		global $wpdb;
		if( empty( $user_id ) ){
			return 0;
		}
		$table = $wpdb->prefix . 'workintry_earnings';
		$total_earning 	= 0;
		$total_spent 	= 0;
		//Get earnings for the year only
		$earning_year = $year;
		//Get earnings for the month only
		$earning_month = $month;
		if( !empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_year,$earning_month
			), ARRAY_A);
		} else if( !empty( $earning_year ) && empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s ORDER BY $table.id DESC", $user_id,$earning_year
			), ARRAY_A);
		} else if( empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_month
			), ARRAY_A);
		} else{
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d ORDER BY $table.id DESC", $user_id
			), ARRAY_A);
		}
		if( !empty( $earnings_query ) ){	
			foreach ($earnings_query as $key => $value) {
				$id 				 	= $value['gig_id'];
				$gig_title 				= get_the_title( $id );
				$earning 				= $value['user_amount'];
				$date 					= $value['order_date'];
				$status 				= $value['status'];
				$type 					= $value['type'];
				if( $status == 'un-paid' && $type == 'sell'){
					$total_earning = $total_earning + $earning;
				} else if( $status == 'paid' && $type == 'sell'){
					$total_earning = $total_earning + $earning;
				} 
				if( $type == 'buy' && $status == 'un-paid' ){
					$total_spent = $total_spent + $earning;
				}
			}
		}

		$total_earning = $total_earning - $total_spent;
		return $total_earning;
	}
}

/*
* Get User Earnings
*/
if( !function_exists( 'codesquare_workintry_get_user_earnings') ){
	function codesquare_workintry_get_user_earnings( $user_id = '', $month = '', $year = ''){
		global $wpdb;
		if( empty( $user_id ) ){
			return 0;
		}
		$table = $wpdb->prefix . 'workintry_earnings';
		$total_earning 	= 0;
		$total_spent 	= 0;
		//Get earnings for the year only
		$earning_year = $year;
		//Get earnings for the month only
		$earning_month = $month;
		if( !empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_year,$earning_month
			), ARRAY_A);
		} else if( !empty( $earning_year ) && empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s ORDER BY $table.id DESC", $user_id,$earning_year
			), ARRAY_A);
		} else if( empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_month
			), ARRAY_A);
		} else{
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d ORDER BY $table.id DESC", $user_id
			), ARRAY_A);
		}
		if( !empty( $earnings_query ) ){	
			foreach ($earnings_query as $key => $value) {
				$id 				 	= $value['gig_id'];
				$gig_title 				= get_the_title( $id );
				$earning 				= $value['user_amount'];
				$date 					= $value['order_date'];
				$status 				= $value['status'];
				$type 					= $value['type'];
				if( $status == 'un-paid' && $type == 'sell'){
					$status = 'In Account';
					$total_earning = $total_earning + $earning;
				} 
				if( $type == 'buy' && $status == 'un-paid' ){
					$total_spent = $total_spent + $earning;
				}
			}
		}

		$total_earning = $total_earning - $total_spent;
		return $total_earning;
	}
}

/*
* Get User Pending Earnings
*/
if( !function_exists( 'codesquare_workintry_get_user_pending_earnings') ){
	function codesquare_workintry_get_user_pending_earnings( $user_id = '', $month = '', $year = ''){
		global $wpdb;
		if( empty( $user_id ) ){
			return 0;
		}
		$table = $wpdb->prefix . 'workintry_earnings';
		$total_pending 	= 0;
		//Get earnings for the year only
		$earning_year = $year;
		//Get earnings for the month only
		$earning_month = $month;
		if( !empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_year,$earning_month
			), ARRAY_A);
		} else if( !empty( $earning_year ) && empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s ORDER BY $table.id DESC", $user_id,$earning_year
			), ARRAY_A);
		} else if( empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_month
			), ARRAY_A);
		} else{
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d ORDER BY $table.id DESC", $user_id
			), ARRAY_A);
		}
		if( !empty( $earnings_query ) ){	
			foreach ($earnings_query as $key => $value) {
				$id 				 	= $value['gig_id'];
				$gig_title 				= get_the_title( $id );
				$earning 				= $value['user_amount'];
				$date 					= $value['order_date'];
				$status 				= $value['status'];
				$type 					= $value['type'];
				if( $status == 'pending' ){					
					$total_pending = $total_pending + $earning;
				} 				
			}
		}		
		return $total_pending;
	}
}

/*
* Get User Earnings Obtained 
*/
if( !function_exists( 'codesquare_workintry_get_user_got_earnings') ){
	function codesquare_workintry_get_user_got_earnings( $user_id = '', $month = '', $year = ''){
		global $wpdb;
		if( empty( $user_id ) ){
			return 0;
		}
		$table = $wpdb->prefix . 'workintry_earnings';
		$total_income 	= 0;
		//Get earnings for the year only
		$earning_year = $year;
		//Get earnings for the month only
		$earning_month = $month;
		if( !empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_year,$earning_month
			), ARRAY_A);
		} else if( !empty( $earning_year ) && empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s ORDER BY $table.id DESC", $user_id,$earning_year
			), ARRAY_A);
		} else if( empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_month
			), ARRAY_A);
		} else{
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d ORDER BY $table.id DESC", $user_id
			), ARRAY_A);
		}
		if( !empty( $earnings_query ) ){	
			foreach ($earnings_query as $key => $value) {
				$id 				 	= $value['gig_id'];
				$gig_title 				= get_the_title( $id );
				$earning 				= $value['user_amount'];
				$date 					= $value['order_date'];
				$status 				= $value['status'];
				$type 					= $value['type'];
				if( $status == 'paid' && $type == 'sell' ){	
					$total_income = $total_income + $earning;
				} 				
			}
		}		
		return $total_income;
	}
}

/*
* Get Jobs in progress
*/
if( !function_exists( 'codesquare_workintry_get_earnings_in_progress') ){
	function codesquare_workintry_get_earnings_in_progress($user_id = '',  $month = '', $year = ''){
		global $wpdb;		
		$table = $wpdb->prefix . 'workintry_earnings';
		$total_earning 	= 0;
		$total_spent 	= 0;
		//Get earnings for the year only
		$earning_year = $year;
		//Get earnings for the month only
		$earning_month = $month;
		if( !empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.year = %s AND $table.month = %s ORDER BY $table.id DESC", $earning_year,$earning_month
			), ARRAY_A);
		} else if( !empty( $earning_year ) && empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.year = %s ORDER BY $table.id DESC", $earning_year
			), ARRAY_A);
		} else if( empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.month = %s ORDER BY $table.id DESC", $earning_month
			), ARRAY_A);
		} else{			
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.status = %s ORDER BY $table.id DESC", 'pending'
			), ARRAY_A);
		}
		if( !empty( $earnings_query ) ){	
			foreach ($earnings_query as $key => $value) {
				$id 				 	= $value['gig_id'];
				$earning 				= $value['amount'];
				$date 					= $value['order_date'];
				$status 				= $value['status'];
				$type 					= $value['type'];
				if( $status == 'pending' && $type == 'sell'){
					$total_earning = $total_earning + $earning;
				} 
				if( $type == 'buy' ){
					$total_spent = $total_spent + $earning;
				}
			}
		}

		$total_earning = $total_earning - $total_spent;
		return $total_earning;
	}
}

/*
* Get User Earnings
*/
if( !function_exists( 'codesquare_workintry_get_all_in_progress_earnings') ){
	function codesquare_workintry_get_all_in_progress_earnings( $user_id = '', $month = '', $year = ''){
		global $wpdb;		
		$table = $wpdb->prefix . 'workintry_earnings';
		$total_earning 	= 0;
		$total_spent 	= 0;
		//Get earnings for the year only
		$earning_year = $year;
		//Get earnings for the month only
		$earning_month = $month;
		if( !empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_year,$earning_month
			), ARRAY_A);
		} else if( !empty( $earning_year ) && empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s ORDER BY $table.id DESC", $user_id,$earning_year
			), ARRAY_A);
		} else if( empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_month
			), ARRAY_A);
		} else{
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT SUM(amount) AS amount FROM $table WHERE $table.status = %s AND $table.type = 'sell' ORDER BY $table.id DESC", 'pending'
			), ARRAY_A);
		}

		//Calculate
		if( !empty( $earnings_query ) ){	
			foreach ($earnings_query as $key => $value) {
				$earning = $value['amount'];
				$earning = !empty( $earning ) ? $earning : 0;
				$total_earning = $earning;
			}
		}		
		return $total_earning;
	}
}

/*
* Get User Earnings
*/
if( !function_exists( 'codesquare_workintry_get_all_comission_in_progress_earnings') ){
	function codesquare_workintry_get_all_comission_in_progress_earnings( $user_id = '', $month = '', $year = ''){
		global $wpdb;		
		$table = $wpdb->prefix . 'workintry_earnings';
		$total_earning 	= 0;
		$total_spent 	= 0;
		//Get earnings for the year only
		$earning_year = $year;
		//Get earnings for the month only
		$earning_month = $month;
		if( !empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_year,$earning_month
			), ARRAY_A);
		} else if( !empty( $earning_year ) && empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s ORDER BY $table.id DESC", $user_id,$earning_year
			), ARRAY_A);
		} else if( empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_month
			), ARRAY_A);
		} else{
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT SUM(admin_amount) AS amount FROM $table WHERE $table.status = %s AND $table.type = 'sell' ORDER BY $table.id DESC", 'pending'
			), ARRAY_A);
		}

		//Calculate
		if( !empty( $earnings_query ) ){	
			foreach ($earnings_query as $key => $value) {
				$earning = $value['amount'];
				$earning = !empty( $earning ) ? $earning : 0;
				$total_earning = $earning;
			}
		}		
		return $total_earning;
	}
}

/*
* Get User Earnings
*/
if( !function_exists( 'codesquare_workintry_get_all_comission_earnings_available') ){
	function codesquare_workintry_get_all_comission_earnings_available( $user_id = '', $month = '', $year = ''){
		global $wpdb;		
		$table = $wpdb->prefix . 'workintry_earnings';
		$total_earning 	= 0;
		$total_spent 	= 0;
		//Get earnings for the year only
		$earning_year = $year;
		//Get earnings for the month only
		$earning_month = $month;
		if( !empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_year,$earning_month
			), ARRAY_A);
		} else if( !empty( $earning_year ) && empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s ORDER BY $table.id DESC", $user_id,$earning_year
			), ARRAY_A);
		} else if( empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_month
			), ARRAY_A);
		} else{
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT SUM(admin_amount) AS amount FROM $table WHERE $table.status = %s AND $table.type = 'sell' ORDER BY $table.id DESC", 'un-paid'
			), ARRAY_A);
		}

		//Calculate
		if( !empty( $earnings_query ) ){	
			foreach ($earnings_query as $key => $value) {
				$earning = $value['amount'];
				$earning = !empty( $earning ) ? $earning : 0;
				$total_earning = $earning;
			}
		}		
		return $total_earning;
	}
}

/*
* Get User Earnings
*/
if( !function_exists( 'codesquare_workintry_get_all_comission_earnings_lifetime') ){
	function codesquare_workintry_get_all_comission_earnings_lifetime( $user_id = '', $month = '', $year = ''){
		global $wpdb;		
		$table = $wpdb->prefix . 'workintry_earnings';
		$total_earning 	= 0;
		$total_spent 	= 0;
		//Get earnings for the year only
		$earning_year = $year;
		//Get earnings for the month only
		$earning_month = $month;
		if( !empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_year,$earning_month
			), ARRAY_A);
		} else if( !empty( $earning_year ) && empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s ORDER BY $table.id DESC", $user_id,$earning_year
			), ARRAY_A);
		} else if( empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_month
			), ARRAY_A);
		} else{
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT SUM(admin_amount) AS amount FROM $table WHERE ( $table.status = %s AND $table.type = 'sell' ) OR ( $table.status = 'paid' AND $table.type = 'sell' ) ORDER BY $table.id DESC", 'un-paid'
			), ARRAY_A);
		}		

		//Calculate
		if( !empty( $earnings_query ) ){	
			foreach ($earnings_query as $key => $value) {
				$earning = $value['amount'];
				$earning = !empty( $earning ) ? $earning : 0;
				$total_earning = $earning;
			}
		}		
		return $total_earning;
	}
}

/*
* Get User Earnings
*/
if( !function_exists( 'codesquare_workintry_get_all_user_owed_earnings') ){
	function codesquare_workintry_get_all_user_owed_earnings( $user_id = '', $month = '', $year = ''){
		global $wpdb;		
		$table = $wpdb->prefix . 'workintry_earnings';
		$total_earning 	= 0;
		$total_spent 	= 0;
		//Get earnings for the year only
		$earning_year = $year;
		//Get earnings for the month only
		$earning_month = $month;
		if( !empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_year,$earning_month
			), ARRAY_A);
		} else if( !empty( $earning_year ) && empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.year = %s ORDER BY $table.id DESC", $user_id,$earning_year
			), ARRAY_A);
		} else if( empty( $earning_year ) && !empty( $earning_month ) ){
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM $table WHERE $table.user_id = %d AND $table.month = %s ORDER BY $table.id DESC", $user_id,$earning_month
			), ARRAY_A);
		} else{
			$earnings_query = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT SUM(user_amount) AS amount FROM $table WHERE $table.status = %s AND $table.type = 'sell' ORDER BY $table.id DESC", 'un-paid'
			), ARRAY_A);
		}		

		//Calculate
		if( !empty( $earnings_query ) ){	
			foreach ($earnings_query as $key => $value) {
				$earning = $value['amount'];
				$earning = !empty( $earning ) ? $earning : 0;
				$total_earning = $earning;
			}
		}		
		return $total_earning;
	}
}

/*
* Get full username
*/
add_action('init', 'codesquare_workintry_get_full_users_name');
function codesquare_workintry_get_full_users_name( $user_id = '' ){
	return codesquare_workintry_get_full_username( $user_id );
}

/**
 * Search HOME HTML
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_homes_search_form' ) ){
	function codesquare_workintry_print_homes_search_form(){		
		$currency = codesquare_workintry_default_system_currency_sign();
		ob_start();
		?>				
			<div class="wi-widgets">
				<div class="wi-widgets-title wi-widgets-title-top">
					<h3><?php esc_html_e('Apply Filter', 'workintry'); ?></h3>					
				</div>
				<div class="wi-widgets-content">
					<div class="wi-searchfilter">
						<?php do_action('codesquare_workintry_print_search_keyword'); ?>						
					</div>
				</div>
			</div>
			<div class="wi-widgets">
				<div class="wi-widgets-title">
					<h2><?php esc_html_e('Categories', 'workintry'); ?> </h2>					
				</div>
				<div class="wi-widgets-content">
					<?php do_action('codesquare_workintry_print_search_categories'); ?>
				</div>
			</div>
			<div class="wi-widgets">
				<div class="wi-widgets-title">
					<h2><?php esc_html_e('Filter by Price', 'workintry'); ?> </h2>					
				</div>
				<div class="wi-widgets-content">
					<div class="wi-currencynum">
						<span><?php esc_html_e('Base currency is set to ', 'workintry'); ?>(<?php echo esc_html( $currency ); ?>)</span>
						<?php 
						$minprice = !empty( $_GET['minprice'] ) ? $_GET['minprice'] : '';
						$maxprice = !empty( $_GET['maxprice'] ) ? $_GET['maxprice'] : '';
						?>
						<div class="wi-currencygroup">
							<input type="number" class="form-control" name="minprice" placeholder="<?php esc_attr_e('Min Price', 'workintry'); ?>" value="<?php echo esc_attr( $minprice ); ?>">
							<input type="number" class="form-control" name="maxprice" placeholder="<?php esc_attr_e('Max Price', 'workintry'); ?>" value="<?php echo esc_attr( $maxprice ); ?>">
						</div>
					</div>
					<?php do_action( 'codesquare_workintry_print_search_item_price_limit' ); ?>
				</div>
			</div>
			<div class="wi-widgets">
				<div class="wi-widgets-title">
					<h2><?php esc_html_e('Seller Level', 'workintry'); ?></h2>					
				</div>
				<div class="wi-widgets-content">
					<?php do_action( 'codesquare_workintry_print_search_seller_level' ); ?>
				</div>
			</div>
			<div class="wi-widgets">
				<div class="wi-widgets-title">
					<h2><?php esc_html_e('Gig Ratings', 'workintry'); ?></h2>					
				</div>
				<div class="wi-widgets-content">
					<?php do_action( 'codesquare_workintry_print_search_ratings' ); ?>
				</div>
			</div>
			<div class="wi-widgets">
				<div class="wi-widgets-title">
					<h2><?php esc_html_e('Item Type', 'workintry'); ?> </h2>					
				</div>
				<div class="wi-widgets-content">
					<?php do_action( 'codesquare_workintry_print_search_item_stype' ); ?>
				</div>			
			</div>
			<div class="wi-widgets">
				<div class="wi-widgets-title">
					<h2><?php esc_html_e('Delivery Type', 'workintry'); ?> </h2>					
				</div>
				<div class="wi-widgets-content">
					<?php do_action( 'codesquare_workintry_print_search_item_delivery' ); ?>
				</div>
			</div>							
			<div class="wi-profilebtns">		
				<input type="submit" name="submit" class="wi-btntwo" value="<?php esc_attr_e('APPLY FILTER', 'workintry'); ?>">
			</div>
		<?php 
		echo ob_get_clean();
	}
	add_action( 'codesquare_workintry_print_homes_search_form', 'codesquare_workintry_print_homes_search_form', 10 );
}

/**
* Breadcrumb HTML
* 
*/
if( !function_exists('codesquare_workintry_print_custom_bread_crumb') ){
	function codesquare_workintry_print_custom_bread_crumb(){
		?>
		<div class="hp-breadcrumb-section">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="hp-breadcrumb-wrap">
							<div class="hp-breadcrumb-title">
								<ol class="hp-breadcrumb">
									<li><a href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e('Home', 'workintry'); ?></a></li>
									<li><?php esc_html_e('Search Results', 'workintry'); ?></li>
								</ol>
								<h3><?php echo get_the_title( get_the_ID() ); ?></h3>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php 
	}
	add_action('codesquare_workintry_print_custom_bread_crumb', 'codesquare_workintry_print_custom_bread_crumb', 10);
}

/*websocket based chat*/
if( !function_exists( 'codesquare_workintry_get_all_chat_with_user' ) ){
	function codesquare_workintry_get_all_chat_with_user(){
		$response = array();
		global $wpdb, $current_user;
		$from_user_id = $current_user->ID;
		$sender_id = $from_user_id;
		$to_user_id = sanitize_text_field($_POST['to_user_id']);		
        $chat_table_name = $wpdb->prefix . 'chat_message';
        $messages = $wpdb->get_results($wpdb->prepare( "SELECT * from $chat_table_name WHERE ( from_user_id = %s AND to_user_id = %s ) OR ( from_user_id = %s AND to_user_id = %s) ORDER BY chat_message_id ASC ", $from_user_id, $to_user_id, $to_user_id, $from_user_id ) );
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
					<img src="'.esc_url( $user_thumb ).'" alt="img">
				</figure>
				<div class="pc-inboxname-content">
					<h5>'.esc_html( $user_full_name ).'</h5>';
					if( $user_last_seen_time != '' ){
						$receiver_content .= '<span>'.esc_html__("Last seen", "workintry").'&nbsp;'.esc_html( $user_last_seen_time ).'&nbsp;'.esc_html__("ago", "workintry").'</span>';
					} else {
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
						                $strings_savable[] = '[link src="'.$values.'"]';
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
            ", $from_user_id, $to_user_id) );                                  
            $data = ob_get_clean();
            $count = codesquare_workintry_get_total_unseen_count( $from_user_id );
            if( !$count > 0 ){
            	$count = 'none';
            }
            $response['data'] = $data;
            $response['count'] = $count;
            $response['user'] = $receiver_content;
            wp_send_json( $response );
	}
	add_action('wp_ajax_codesquare_workintry_get_all_chat_with_user', 'codesquare_workintry_get_all_chat_with_user');
    add_action('wp_ajax_nopriv_codesquare_workintry_get_all_chat_with_user', 'codesquare_workintry_get_all_chat_with_user');
}

/*
* Get Unseen Messgaes Count
*/
if( !function_exists( 'codesquare_workintry_get_total_unseen_count' ) ){
	function codesquare_workintry_get_total_unseen_count( $user_id = '' ){
		global $wpdb, $current_user;	
		$current_user_id = '';	
		if( !empty( $user_id ) ){
			$current_user_id = $user_id;
		} else {
			$current_user_id = $current_user->ID;
		}

		$chat_table_name = $wpdb->prefix . 'chat_message';
		$messages = $wpdb->get_results($wpdb->prepare( "SELECT * from $chat_table_name WHERE 
			to_user_id = %s
			AND status = '1'
		", $current_user_id ) ); 
		$count = '';
		if( !empty( $messages ) ){
			$count = count( $messages );
		}
		if( $count > 0 ){
			return $count;
		}
	}	
}

/*
* Get Unseen Messgaes Count by other ID
*/
if( !function_exists( 'codesquare_workintry_get_total_unseen_count_by_id' ) ){
	function codesquare_workintry_get_total_unseen_count_by_id( $user_id = '' ){
		global $wpdb, $current_user;	
		$current_user_id = '';	
		$other_user_id = '';
		if( !empty( $user_id ) ){
			$other_user_id = $user_id;
		} else {
			return '0';
		}

		$current_user_id = $current_user->ID;		
		$chat_table_name = $wpdb->prefix . 'chat_message';
		$messages = $wpdb->get_results($wpdb->prepare( "SELECT * from $chat_table_name WHERE from_user_id = %s AND
 			to_user_id = %s
			AND status = '1'
		", $other_user_id, $current_user_id ) ); 		
		$count = '';
		if( !empty( $messages ) ){
			$count = count( $messages );
		}
		if( $count > 0 ){
			return $count;
		}
		return $count;
	}	
}

/*
* Add user last seen
*/
if( !function_exists( 'codesquare_workintry_add_user_last_seen' ) ){
	function codesquare_workintry_add_user_last_seen(){
		global $current_user;
		$user_id = get_current_user_id();
		if( empty( $user_id ) ){
			$user_id = $current_user->ID;
		}
		update_user_meta( $user_id, 'cl_last_seen', time() );
	}
	add_action('clear_auth_cookie', 'codesquare_workintry_add_user_last_seen', 10);
}
/*
* remove user last seen
*/
if( !function_exists( 'codesquare_workintry_remove_user_last_seen' ) ){
	function codesquare_workintry_remove_user_last_seen( $user_login, $user ) {
	    //Get user_id
	    $user_id = $user->ID;
	    update_user_meta( $user_id, 'cl_last_seen', 'online' );
	}
	add_action('wp_login', 'codesquare_workintry_remove_user_last_seen', 10, 2);
}

/*
* Print User Level
*/
if( !function_exists( 'codesquare_workintry_print_user_level' ) ){
	function codesquare_workintry_print_user_level( $user_id = '' ){
		if( !empty( $user_id ) ){
			$get_user_earnings = codesquare_codesquare_workintry_get_user_earnings_unpaid_and_paid( $user_id );
            //Get Levels            
            $level_1  = codesquare_workintry_get_settings_option('seller_one');
            $level_2  = codesquare_workintry_get_settings_option('seller_two');
            $level_3  = codesquare_workintry_get_settings_option('seller_three');
            $level_4  = codesquare_workintry_get_settings_option('seller_four');
            $level_5  = codesquare_workintry_get_settings_option('seller_five');
            $level_top = codesquare_workintry_get_settings_option('seller_top');
            $level_1 = !empty( $level_1 ) ? $level_1 : '';
            $level_2 = !empty( $level_1 ) ? $level_2 : '';
            $level_3 = !empty( $level_3 ) ? $level_3 : '';
            $level_4 = !empty( $level_4 ) ? $level_4 : '';
            $level_5 = !empty( $level_5 ) ? $level_5 : '';
            $level_top = !empty( $level_top ) ? $level_top : '';
            
            $user_level = '';
            if( !empty( $get_user_earnings ) && $get_user_earnings > $level_top ){
                $user_level = esc_html__('Top Level Seller', 'workintry');
            } elseif( !empty( $get_user_earnings ) && $get_user_earnings > $level_5 ) {
                $user_level = esc_html__('Seller Level 5', 'workintry');
            } elseif( !empty( $get_user_earnings ) && $get_user_earnings > $level_4 ) {
                $user_level = esc_html__('Seller Level 4', 'workintry');
            } elseif( !empty( $get_user_earnings ) && $get_user_earnings > $level_3 ) {
                $user_level = esc_html__('Seller Level 3', 'workintry');
            } elseif( !empty( $get_user_earnings ) && $get_user_earnings > $level_2 ) {
                $user_level = esc_html__('Seller Level 2', 'workintry');
            } elseif( !empty( $get_user_earnings ) && $get_user_earnings > $level_1 ) {
                $user_level = esc_html__('Seller Level 1', 'workintry');
            } else {               
                $user_level = esc_html__('Fresh / Newbie', 'workintry');
            } 
            ob_start();
            ?>
            <div class="wi-usertag">
				<a href="javascript:void(0);" class="wi-toplevel"><i class="ti-crown"></i> <?php echo esc_html( $user_level ); ?></a>
			</div>
            <?php 
            echo ob_get_clean();
		}
	}
	add_action( 'codesquare_workintry_print_user_level', 'codesquare_workintry_print_user_level', 10, 1 );
}
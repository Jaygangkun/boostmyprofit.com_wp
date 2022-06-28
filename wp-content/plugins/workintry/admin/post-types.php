<?php
/*
* Register Required Post Types
*/
if (!class_exists('Code_Square_Co_Workintry')) {

		class Code_Square_Co_Workintry {
			/**
			 * @access  public
			 * @Init Hooks in Constructor
			 */
			public function __construct() {				
				add_action('init', array(&$this, 'prepare_workintry_gig_post'));
				add_action('init', array(&$this, 'prepare_workintry_gig_order_post'));
			}
			
			/**
			 * @Prepare Post Type Workintry Gig
			 * @return post type
			 */	
			public function prepare_workintry_gig_post(){	
				$enable_property 	= codesquare_workintry_get_settings_option('enable_workintry_property');						
				$home_post_slug	= get_option('home_post_slug');
				$home_post_slug	=  !empty( $home_post_slug ) ? $home_post_slug : 'workintry';
				
					$home_labels = array(
						'name' => esc_html__('Gig', 'workintry'),
						'all_items' => esc_html__('Gigs', 'workintry'),
						'singular_name' => esc_html__('Gig', 'workintry'),
						'add_new' => esc_html__('Add Gig', 'workintry'),
						'add_new_item' => esc_html__('Add New Gig', 'workintry'),
						'edit' => esc_html__('Edit', 'workintry'),
						'edit_item' => esc_html__('Edit Gig', 'workintry'),
						'new_item' => esc_html__('New Gig', 'workintry'),
						'view' => esc_html__('View Gig', 'workintry'),
						'view_item' => esc_html__('View Gig', 'workintry'),
						'search_items' => esc_html__('Search Gig', 'workintry'),
						'not_found' => esc_html__('No Gig found', 'workintry'),
						'not_found_in_trash' => esc_html__('No Gig found in trash', 'workintry'),
						'parent' => esc_html__('Parent Gig', 'workintry'),
					);
					$home_args = array(
						'labels' => $home_labels,
						'description' => esc_html__('This is where you can add new Gigs.', 'workintry'),
						'public' => true,
						'supports' => array('title', 'editor', 'thumbnail', 'author'),
						'show_ui' => true,
						'capability_type' => 'post',
						'show_in_menu' => true,
						'map_meta_cap' => true,
						'publicly_queryable' => true,
						'exclude_from_search' => false,
						'hierarchical' => true,
						'menu_position' => 10,
						'rewrite' => array('slug' => $home_post_slug, 'with_front' => true),
						'query_var' => true,
						'has_archive' => 'true',
					);
					
					register_post_type('workintry', $home_args);
				
				//Banner Post
				$banner_labels = array(
					'name' => esc_html__('Shortcode', 'workintry'),
					'all_items' => esc_html__('Shortcodes', 'workintry'),
					'singular_name' => esc_html__('Shortcode', 'workintry'),
					'add_new' => esc_html__('Add Shortcode', 'workintry'),
					'add_new_item' => esc_html__('Add New Shortcode', 'workintry'),
					'edit' => esc_html__('Edit', 'workintry'),
					'edit_item' => esc_html__('Edit Shortcode', 'workintry'),
					'new_item' => esc_html__('New Shortcode', 'workintry'),
					'view' => esc_html__('View Shortcode', 'workintry'),
					'view_item' => esc_html__('View Shortcode', 'workintry'),
					'search_items' => esc_html__('Search Shortcode', 'workintry'),
					'not_found' => esc_html__('No Shortcode found', 'workintry'),
					'not_found_in_trash' => esc_html__('No Shortcode found in trash', 'workintry'),
					'parent' => esc_html__('Parent Shortcode', 'workintry'),
				);
				$banner_args = array(
					'labels' => $banner_labels,
					'description' => esc_html__('This is where you can add new shortcodes.', 'workintry'),
					'public' => true,
					'supports' => array('title', 'author'),
					'show_ui' => true,
					'capability_type' => 'post',
					'show_in_menu' => true,
					'map_meta_cap' => true,
					'publicly_queryable' => true,
					'exclude_from_search' => true,
					'hierarchical' => false,
					'menu_position' => 10,
					'rewrite' => array('slug' => 'shortcodes', 'with_front' => true),
					'query_var' => true,
					'has_archive' => 'true',
				);
					
				register_post_type('workintry_shortcodes', $banner_args);
				//Gig Category Taxonomy
			    $gig_category_labels = array(
			        'name' => esc_html__('Categories', 'workintry'),
			        'singular_name' => esc_html__('Category','workintry'),
			        'search_items' => esc_html__('Search Category', 'workintry'),
			        'all_items' => esc_html__('All Category', 'workintry'),
			        'parent_item' => esc_html__('Parent Category', 'workintry'),
			        'parent_item_colon' => esc_html__('Parent Category:', 'workintry'),
			        'edit_item' => esc_html__('Edit Category', 'workintry'),
			        'update_item' => esc_html__('Update Category', 'workintry'),
			        'add_new_item' => esc_html__('Add New Category', 'workintry'),
			        'new_item_name' => esc_html__('New Category Name', 'workintry'),
			        'menu_name' => esc_html__('Categories', 'workintry'),
			    );
			    //Category Taxonomy
			    $gig_category_args = array(
			        'hierarchical' => true,
			        'labels' => $gig_category_labels,
			        'show_ui' => true,
			        'show_admin_column' => false,
			        'query_var' => true,
			        'rewrite' => array('slug' => 'gig_category'),
			        'meta_box_cb'                => false,
			    );

			    //Register
			    register_taxonomy('gig_category', array('workintry'), $gig_category_args);

			    //Gig sub Category Taxonomy
			    $gig_sub_category_labels = array(
			        'name' => esc_html__('Sub Categories', 'workintry'),
			        'singular_name' => esc_html__('Sub Category','workintry'),
			        'search_items' => esc_html__('Search Sub Category', 'workintry'),
			        'all_items' => esc_html__('All Sub Categories', 'workintry'),
			        'parent_item' => esc_html__('Parent Sub  Category', 'workintry'),
			        'parent_item_colon' => esc_html__('Parent Category:', 'workintry'),
			        'edit_item' => esc_html__('Edit Sub Category', 'workintry'),
			        'update_item' => esc_html__('Update Sub Category', 'workintry'),
			        'add_new_item' => esc_html__('Add New Sub Category', 'workintry'),
			        'new_item_name' => esc_html__('New Sub Category Name', 'workintry'),
			        'menu_name' => esc_html__('Sub Categories', 'workintry'),
			    );
			    //Category Taxonomy
			    $gig_sub_category_args = array(
			        'hierarchical' => true,
			        'labels' => $gig_sub_category_labels,
			        'show_ui' => true,
			        'show_admin_column' => false,
			        'query_var' => true,
			        'rewrite' => array('slug' => 'sub_category'),
			        'meta_box_cb'                => false,
			    );

			    //Register
			    register_taxonomy('gig_sub_category', array('workintry'), $gig_sub_category_args);

			    $gig_service_labels = array(
			        'name' => esc_html__('Services', 'workintry'),
			        'singular_name' => esc_html__('Service','workintry'),
			        'search_items' => esc_html__('Search Service', 'workintry'),
			        'all_items' => esc_html__('All Services', 'workintry'),
			        'parent_item' => esc_html__('Parent Service', 'workintry'),
			        'parent_item_colon' => esc_html__('Parent Service', 'workintry'),
			        'edit_item' => esc_html__('Edit Service', 'workintry'),
			        'update_item' => esc_html__('Update Service', 'workintry'),
			        'add_new_item' => esc_html__('Add New Service', 'workintry'),
			        'new_item_name' => esc_html__('New Service Name', 'workintry'),
			        'menu_name' => esc_html__('Services', 'workintry'),
			    );
			    //Category Taxonomy
			    $gig_service_args = array(
			        'hierarchical' => true,
			        'labels' => $gig_service_labels,
			        'show_ui' => true,
			        'show_admin_column' => false,
			        'query_var' => true,
			        'rewrite' => array('slug' => 'gig-service'),
			        'meta_box_cb'                => false,
			    );

			    //Register
			    register_taxonomy('gig_service', array('workintry'), $gig_service_args);

			    //Country Taxonomy
			    $location_labels = array(
			        'name' => esc_html__('Countries', 'workintry'),
			        'singular_name' => esc_html__('Country','workintry'),
			        'search_items' => esc_html__('Search Country', 'workintry'),
			        'all_items' => esc_html__('All Country', 'workintry'),
			        'parent_item' => esc_html__('Parent Country', 'workintry'),
			        'parent_item_colon' => esc_html__('Parent Country:', 'workintry'),
			        'edit_item' => esc_html__('Edit Country', 'workintry'),
			        'update_item' => esc_html__('Update Country', 'workintry'),
			        'add_new_item' => esc_html__('Add New Country', 'workintry'),
			        'new_item_name' => esc_html__('New Country Name', 'workintry'),
			        'menu_name' => esc_html__('Countries', 'workintry'),
			    );
			    //Country Taxonomy
			    $property_location_args = array(
			        'hierarchical' => true,
			        'labels' => $location_labels,
			        'show_ui' => true,
			        'show_admin_column' => false,
			        'query_var' => true,
			        'rewrite' => array('slug' => 'gig_country'),
			        'meta_box_cb'                => false,
			    );

			    //Register
			    register_taxonomy('gig_country', array('workintry'), $property_location_args);

			    //City Taxonomy
			    $gig_city_labels = array(
			        'name' => esc_html__('Cities', 'workintry'),
			        'singular_name' => esc_html__('City','workintry'),
			        'search_items' => esc_html__('Search City', 'workintry'),
			        'all_items' => esc_html__('All Cities', 'workintry'),
			        'parent_item' => esc_html__('Parent City', 'workintry'),
			        'parent_item_colon' => esc_html__('Parent City:', 'workintry'),
			        'edit_item' => esc_html__('Edit City', 'workintry'),
			        'update_item' => esc_html__('Update City', 'workintry'),
			        'add_new_item' => esc_html__('Add New City', 'workintry'),
			        'new_item_name' => esc_html__('New City Name', 'workintry'),
			        'menu_name' => esc_html__('Cities', 'workintry'),
			    );
			    //Country Taxonomy
			    $gig_city_args = array(
			        'hierarchical' => true,
			        'labels' => $gig_city_labels,
			        'show_ui' => true,
			        'show_admin_column' => false,
			        'query_var' => true,
			        'rewrite' => array('slug' => 'gig_city'),
			        'meta_box_cb'                => false,
			    );

			    //Register
			    register_taxonomy('gig_city', array('workintry'), $gig_city_args);				

			    //Gig Tags Taxonomy
			    $gig_tags_labels = array(
			        'name' => esc_html__('Tags', 'workintry'),
			        'singular_name' => esc_html__('Tag','workintry'),
			        'search_items' => esc_html__('Search Tag', 'workintry'),
			        'all_items' => esc_html__('All Tag', 'workintry'),
			        'parent_item' => esc_html__('Parent Tag', 'workintry'),
			        'parent_item_colon' => esc_html__('Parent Tag:', 'workintry'),
			        'edit_item' => esc_html__('Edit Tag', 'workintry'),
			        'update_item' => esc_html__('Update Tag', 'workintry'),
			        'add_new_item' => esc_html__('Add New Tag', 'workintry'),
			        'new_item_name' => esc_html__('New Tag Name', 'workintry'),
			        'menu_name' => esc_html__('Tags', 'workintry'),
			    );
			    //Tags Taxonomy
			    $gig_tags_args = array(
			        'hierarchical' => false,
			        'labels' => $gig_tags_labels,
			        'show_ui' => true,
			        'show_admin_column' => false,
			        'query_var' => true,
			        'rewrite' => array('slug' => 'gig-tag'),
			    );

			    //Register
			    register_taxonomy('gig_tags', array('workintry'), $gig_tags_args);

			} //Gig Ends			

			//Gig order
			/**
			 * @Prepare Post Type Workintry Home
			 * @return post type
			 */	
			public function prepare_workintry_gig_order_post(){
				$gig_labels = array(
						'name' => esc_html__('Gig Orders', 'workintry'),
						'all_items' => esc_html__('Gig Orders', 'workintry'),
						'singular_name' => esc_html__('Gig Order', 'workintry'),
						'add_new' => esc_html__('Add Gig Orders', 'workintry'),
						'add_new_item' => esc_html__('Add New Gig Order', 'workintry'),
						'edit' => esc_html__('Edit', 'workintry'),
						'edit_item' => esc_html__('Edit Gig Order', 'workintry'),
						'new_item' => esc_html__('New Gig Order', 'workintry'),
						'view' => esc_html__('View Gig Order', 'workintry'),
						'view_item' => esc_html__('View Gig Order', 'workintry'),
						'search_items' => esc_html__('Search Gig Order', 'workintry'),
						'not_found' => esc_html__('No Gig Order found', 'workintry'),
						'not_found_in_trash' => esc_html__('No Gig Order found in trash', 'workintry'),
						'parent' => esc_html__('Parent Gig Order', 'workintry'),
					);
					$gig_order_args = array(
						'labels' => $gig_labels,
						'description' => esc_html__('This is where you can see new gig orders.', 'workintry'),
						'public' => true,
						'supports' => array('title', 'editor', 'author'),
						'show_ui' => true,
						'capability_type' => 'post',
						'show_in_menu' => true,
						'map_meta_cap' => true,
						'publicly_queryable' => true,
						'exclude_from_search' => false,
						'hierarchical' => true,
						'menu_position' => 10,
						'rewrite' => array('slug' => 'gig-order', 'with_front' => true),
						'query_var' => true,
						'has_archive' => 'true',
					);
					
					register_post_type('gig-order', $gig_order_args);
			}

		}

		new Code_Square_Co_Workintry();
	}

	//Save Ad	
	add_action( 'save_post', 'codesquare_workintry_ave_post_upon_creation', 10,3);			
	function codesquare_workintry_ave_post_upon_creation( $post_id, $post, $update ) {
		// Only set for post_type = workintry!
	    if ( 'workintry' === $post->post_type ) {
	    	//Get Details
		    //Gigs
	        $basic          = !empty( $_POST['basic'] ) ? $_POST['basic'] : '';
	        $gold           = !empty( $_POST['gold'] ) ? $_POST['gold'] : '';
	        $diamond        = !empty( $_POST['diamond'] ) ? $_POST['diamond'] : '';	      
	        //Basic
	        $basic_title         = !empty( $basic['title'] ) ? $basic['title'] :'';
	        $basic_description   = !empty( $basic['description'] ) ? $basic['description'] : '';
	        $basic_delivery      = !empty( $basic['delivery'] ) ? $basic['delivery'] : '';
	        $basic_revision      = !empty( $basic['revisions'] ) ? $basic['revisions'] : '';
	        $basic_price         = !empty( $basic['price'] ) ? $basic['price'] : '';
	        //Gold
	        $gold_title         = !empty( $gold['title'] ) ? $gold['title'] : '';
	        $gold_description   = !empty( $gold['description'] ) ? $gold['description'] : '';
	        $gold_delivery      = !empty( $gold['delivery'] ) ? $gold['delivery'] : '';
	        $gold_revision      = !empty( $gold['revisions'] ) ? $gold['revisions'] : '';
	        $gold_price         = !empty( $gold['price'] ) ? $gold['price'] : '';
	        //Diamond
	        $diamond_title         = !empty( $diamond['title'] ) ? $diamond['title'] : '';
	        $diamond_description   = !empty( $diamond['description'] ) ? $diamond['description'] : '';
	        $diamond_delivery      = !empty( $diamond['delivery'] ) ? $diamond['delivery'] : '';
	        $diamond_revision      = !empty( $diamond['revisions'] ) ? $diamond['revisions'] : '';
	        $diamond_price         = !empty( $diamond['price'] ) ? $diamond['price'] : '';

	        //Terms
	        $category       = !empty( $_POST['gig-category'] ) ? sanitize_text_field($_POST['gig-category']) : '';
	        $subcategory    = !empty( $_POST['sub-category'] ) ? sanitize_text_field($_POST['sub-category']) : '';
	        $service        =  !empty( $_POST['gig-service'] ) ? sanitize_text_field($_POST['gig-service']) : '';
        
			//Set terms
			//Get term id from slug               
	        $category_id        = codesquare_workintry_get_term_id_by_slug( $category, 'gig_category' );
	        $sub_category_id    = codesquare_workintry_get_term_id_by_slug( $subcategory, 'gig_sub_category' );
	        $service_id         = codesquare_workintry_get_term_id_by_slug( $service, 'gig_service' );        
        
	        //Set taxonomies             
	        wp_set_post_terms( $post_id, $category_id, 'gig_category');
	        wp_set_post_terms( $post_id, $sub_category_id, 'gig_sub_category');
	        wp_set_post_terms( $post_id, $service_id, 'gig_service'); 

	        //Set Revisions 
	        $cl_revisions = 'no';
	        if( !empty( $basic_revision ) ){
	        	$cl_revisions = 'yes';
	        }
	        //Set Delivery 
	        $cl_delivery = 'no';
	        if( !empty( $basic_delivery ) ){
	        	$cl_delivery = 'yes';
	        }
	        //Set meta
	        //update meta data           
	        $ad_meta = array(
	            'cl_gig_basic'      => $basic,
	            'cl_gig_gold'       => $gold,
	            'cl_gig_diamond'    => $diamond,
	            //Basic
	            'cl_basic_title'    => $basic_title,
	            'cl_basic_desc'     => $basic_description,
	            'cl_basic_delivery' => $basic_delivery,
	            'cl_basic_revision' => $basic_revision,
	            'cl_basic_price'    => $basic_price,
	            //Gold
	            'cl_gold_title'    => $gold_title,
	            'cl_gold_desc'     => $gold_description,
	            'cl_gold_delivery' => $gold_delivery,
	            'cl_gold_revision' => $gold_revision,
	            'cl_gold_price'    => $gold_price,
	            //Diamond
	            'cl_diamond_title'    => $diamond_title,
	            'cl_diamond_desc'     => $diamond_description,
	            'cl_diamond_delivery' => $diamond_delivery,
	            'cl_diamond_revision' => $diamond_revision,
	            'cl_diamond_price'    => $diamond_price, 
	            'cl_revisions' 		  => $cl_revisions,
	            'cl_delivery' 		  => $cl_delivery
	        );

	        //Update ad post meta 
	        foreach ( $ad_meta as $key => $value ) {
	            update_post_meta( $post_id, $key, $value );
	        }    
       
	    	//Current Time Stamp
	       	$current_time = new DateTime();					
			$current_time_stamp = $current_time->getTimestamp();
			//Featured Stamp
			$featured_days = intval( 30 );
			$time_stamp = time() + ( 60 * 60 * 24 * $featured_days );	    	 
		    //Update rating
		    $ratings = get_post_meta( $post_id, 'cl_rating', true );
		    if( empty( $ratings ) ){
		    	update_post_meta($post_id, 'cl_rating', 0);	
		    }    

		    //Set featured		   
		    if( isset( $_POST['cl_featured'] ) ){
			    if( !empty( $_POST['cl_featured'] ) && $_POST['cl_featured'] == 'yes' ){
			    	$featured_stamp 		= get_post_meta( $post_id, 'cl_timestamp', true );
			    	if( $featured_stamp > $current_time_stamp ){
			    		//Already featured no action needed
			    	} else {
			    		//Set it as featured now
			    		update_post_meta( $post_id, 'cl_timestamp', $time_stamp );
			    		update_post_meta( $post_id, 'cl_featured', 'yes');
			    	}

			    } elseif( !empty( $_POST['cl_featured'] ) && $_POST['cl_featured'] == 'no' ) {
			    	update_post_meta( $post_id, 'cl_timestamp', 0);
			    	update_post_meta( $post_id, 'cl_featured', 'no');
			    }
			}		   		    
    	
	    }	   			    

	}

	add_filter( 'manage_workintry_posts_columns', 'codesquare_workintry_columns' );
	function codesquare_workintry_columns( $columns ) {
	    $columns = array(
	      'cb' 	  => $columns['cb'],
	      'image' => __( 'Image', 'workintry' ),
	      'title' => __( 'Title', 'workintry' ),
	      'author' => __( 'Author/Company', 'workintry' ),
	      'featured' => __( 'Featured', 'workintry' ),
	      'price' => __( 'Price', 'workintry' ),
	      'type'  => __( 'Ad Type', 'workintry' ),
	    );
	  
	  return $columns;
	}

add_action( 'manage_workintry_posts_custom_column', 'codesquare_workintry_custom_column', 10, 2);
function codesquare_workintry_custom_column( $column, $post_id ) {
  // Image column
  if ( $column == 'image' ) {
    echo get_the_post_thumbnail( $post_id, array( 80, 80 ) );
  }
  
  // Price column
  if ( 'price' === $column ) {
    $price = get_post_meta( $post_id, 'cl_basic_price', true );

    if ( $price ) {
        echo esc_attr( $price );
    } 

  }

  //Featured 
  if( 'featured' === $column ){
  		$current_time 		= new DateTime();					
		$current_time_stamp = $current_time->getTimestamp();
		$featured_stamp 	= get_post_meta( $post_id, 'cl_timestamp', true);
		if( $featured_stamp > $current_time_stamp ){
			echo esc_html__('Featured', 'workintry');
		} else {
			echo esc_html__('No', 'workintry');
		}
  }
  //Type Column
  if( 'type' === $column ){
    $type = get_post_meta( $post_id, 'cl_type', true );
    if( !empty( $type ) ){        
        echo esc_attr( ucfirst( $type ) );
    } else {
    	$type = esc_html__('Sale', 'workintry');
    	echo esc_attr( ucfirst( $type ) );
    }
  }  
}

//Banner Form Shortcode
add_filter( 'manage_workintry_shortcodes_posts_columns', 'codesquare_workintry_shortcodes_columns' );
function codesquare_workintry_shortcodes_columns( $columns ) {
    $columns = array(
      'cb' 	  => $columns['cb'],	
      'title' => __( 'Title', 'workintry' ),     
      'shortcode' => __( 'Shortcode', 'workintry' ),	      
    );
  
  return $columns;
}
add_action( 'manage_workintry_shortcodes_posts_custom_column', 'codesquare_workintry_shortcodes_custom_column', 10, 2);
function codesquare_workintry_shortcodes_custom_column( $column, $post_id ) {
  // Shortcode column
  if ( 'shortcode' === $column ) {  
  	$type = get_post_meta( $post_id, 'cl_type', true );
  	if( $type == 'banner' ){
    	echo '[banner_shortcode id="'.esc_attr( $post_id ).'"]';
	} elseif( $type == 'featured' ){
		echo '[workintry_featured_ads id="'.esc_attr( $post_id ).'"]';
	} elseif( $type == 'normal' ){
		echo '[workintry_normal_ads id="'.esc_attr( $post_id ).'"]';
	} elseif( $type == 'locations' ){
		echo '[workintry_location id="'.esc_attr( $post_id ).'"]';
	} elseif( $type == 'stat' ){
		echo '[workintry_stats id="'.esc_attr( $post_id ).'"]';
	} elseif( $type == 'testimonials' ){
		echo '[workintry_testimonials id="'.esc_attr( $post_id ).'"]';
	} elseif( $type == 'clients' ){
		echo '[workintry_clients id="'.esc_attr( $post_id ).'"]';
	} elseif( $type == 'categories' ){
		echo '[workintry_categories id="'.esc_attr( $post_id ).'"]';
	} elseif( $type == 'about' ){
		echo '[workintry_about id="'.esc_attr( $post_id ).'"]';
	}
  }  
}

//Include file for categories/services
require_once codesquare_workintry_addon_template_exsits('admin/workintry-data');
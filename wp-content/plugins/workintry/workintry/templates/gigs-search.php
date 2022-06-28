<?php
/**
 * Template Name: Gigs Search
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/* Define Global Variables */
global $paged, $query_args, $showposts, $wp_query;
$page_layout    = codesquare_workintry_get_settings_option('search_layout');
$view           = isset( $_GET['view'] ) ? sanitize_text_field($_GET['view']) : $page_layout;
$view           = !empty( $view ) ? $view : 'grid';
$per_page       = 9;
$pg_page        = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged for single while, page - works on homepage
$paged = max($pg_page, $pg_paged);
$meta_query_args	        = array();
$tax_query_args		        = array();
$tax_category_query_args	= array();
$tax_tag_query_args			= array();

//search filters
$search			= !empty($_GET['keyword']) ? sanitize_text_field( $_GET['keyword'] ) : '';
$category 		= !empty($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
$sort_by 		= !empty($_GET['sortby']) ? sanitize_text_field($_GET['sortby']) : '';
$minprice      = !empty($_GET['minprice']) ? sanitize_text_field($_GET['minprice']) : 1;
$maxprice      = !empty($_GET['maxprice']) ? sanitize_text_field($_GET['maxprice']) : '';
$limit          = !empty($_GET['limit']) ? sanitize_text_field($_GET['limit']) : '';
$level          = !empty($_GET['level']) ? sanitize_text_field($_GET['level']) : '';
$rating          = !empty($_GET['rating']) ? sanitize_text_field($_GET['rating']) : '';
$type          = !empty($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
$delivery          = !empty($_GET['delivery']) ? sanitize_text_field($_GET['delivery']) : '';
$showposts 		= !empty($_GET['showposts']) ? sanitize_text_field($_GET['showposts']) : $per_page;
$per_page       = $showposts;
$pg_page        = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged for single while, page - works on homepage
$paged = max($pg_page, $pg_paged);

//Category search
if (is_tax('gig_category') && empty( $category )) {    
    $cat = $wp_query->get_queried_object();
    if (!empty($cat->slug)) {
        $category = $cat->slug;         
    }
} 
if( !empty( $category ) ){
    $tax_category_query_args[] = array(
        'taxonomy'  => 'gig_category',
        'field'     => 'slug',
        'terms'     => $category,
    );
}

//Tags search
if ( is_tax('gig_tags') ) {    
    $tag = $wp_query->get_queried_object();
    if (!empty($tag->slug)) {
        $tag_name = $tag->slug;         
    }
    $tax_category_query_args[] = array(
        'taxonomy'  => 'gig_tags',
        'field'     => 'slug',
        'terms'     => $tag_name,
    );
} 

//Price Range
if( !empty( $minprice ) && !empty( $maxprice ) ){
    $meta_query_args[] = array(
        'key'     => 'cl_basic_price',
        'value'   => array( $minprice, $maxprice ),
        'type'    => 'numeric',
        'compare' => 'BETWEEN',        
    );
}

//Price Limits
if( !empty( $limit ) && $limit == 1 ){
    $meta_query_args[] = array(
        'key'     => 'cl_basic_price',
        'value'   => 10,
        'type'    => 'numeric',
        'compare' => '<=',        
    );
} elseif( !empty( $limit ) && $limit == 2 ){
    $meta_query_args[] = array(
        'key'     => 'cl_basic_price',
        'value'   => array( 10, 30 ),
        'type'    => 'numeric',
        'compare' => 'BETWEEN',        
    );
} elseif( !empty( $limit ) && $limit == 3 ){
    $meta_query_args[] = array(
        'key'     => 'cl_basic_price',
        'value'   => array( 30, 60 ),
        'type'    => 'numeric',
        'compare' => 'BETWEEN',        
    );
} elseif( !empty( $limit ) && $limit == 4 ){
    $meta_query_args[] = array(
        'key'     => 'cl_basic_price',
        'value'   => 60,
        'type'    => 'numeric',
        'compare' => '=>',        
    );
}

//Seller Level
if( !empty( $level ) && $level == 'top' ){
    $meta_query_args[] = array(
        'key'     => 'cl_level',
        'value'   => $level,        
        'compare' => '=',        
    );
} elseif( !empty( $level ) && $level == 5 ){
    $meta_query_args[] = array(
        'key'     => 'cl_level',
        'value'   => $level,
        'compare' => '=',        
    );
} elseif( !empty( $level ) && $level == 4 ){
    $meta_query_args[] = array(
        'key'     => 'cl_level',
        'value'   => $level,        
        'compare' => '=',        
    );
} elseif( !empty( $level ) && $level == 3 ){
    $meta_query_args[] = array(
        'key'     => 'cl_level',
        'value'   => $level,       
        'compare' => '=',        
    );
} elseif( !empty( $level ) && $level == 2 ){
    $meta_query_args[] = array(
        'key'     => 'cl_level',
        'value'   => $level,       
        'compare' => '=',        
    );
} elseif( !empty( $level ) && $level == 1 ){
    $meta_query_args[] = array(
        'key'     => 'cl_level',
        'value'   => $level,       
        'compare' => '=',        
    );
} elseif( !empty( $level ) && $level == 'fresh' ){
    $meta_query_args[] = array(
        'key'     => 'cl_level',
        'value'   => $level,       
        'compare' => '=',        
    );
}

//Ratings
if( !empty( $rating ) && $rating == 1 ){
    $meta_query_args[] = array(
        'key'     => 'cl_rating',
        'value'   => '1',        
        'compare' => '=',        
    );
} elseif( !empty( $rating ) && $rating == 2 ){
    $meta_query_args[] = array(
        'key'     => 'cl_rating',
        'value'   => array( 1, 2 ),
        'type'    => 'numeric',
        'compare' => 'BETWEEN',        
    );
} elseif( !empty( $rating ) && $rating == 3 ){
    $meta_query_args[] = array(
        'key'     => 'cl_rating',
        'value'   => array( 3, 4 ),
        'type'    => 'numeric',
        'compare' => 'BETWEEN',        
    );
} elseif( !empty( $rating ) && $rating == 4 ){
    $meta_query_args[] = array(
        'key'     => 'cl_rating',
        'value'   => array( 4, 5 ),
        'type'    => 'numeric',
        'compare' => 'BETWEEN',        
    );
} elseif( !empty( $rating ) && $rating == 5 ){
    $meta_query_args[] = array(
        'key'     => 'cl_rating',
        'value'   => '5',       
        'compare' => '=',        
    );
} elseif( !empty( $rating ) && $rating == 'fresh' ){
    $meta_query_args[] = array(
        'key'     => 'cl_rating',
        'value'   => '0',       
        'compare' => '=',        
    );
} 

//Gig with or without revisions
if( !empty( $type ) ){
    $meta_query_args[] = array(
        'key'     => 'cl_revisions',
        'value'   => $type,
        'compare'   => '='            
    );
}

//Prepare Arugments Array
$query_args = array(
    'posts_per_page'        => $showposts,
    'post_type'             => 'workintry',
    'paged'                 => $paged,
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1
);

//Order By (put featured always on top no matter what)
$orderby = isset( $_GET['orderby'] ) && !empty( $_GET['orderby'] ) ? sanitize_text_field($_GET['orderby']) : 'featured';
$meta_query_args[] = array(
    'featured' => array(
        'key'     => 'cl_timestamp',
        'type'    => 'NUMERIC',
        'compare' => 'EXISTS',
    ),
    'rating'    => array(
        'key'     => 'cl_rating',
        'type'    => 'NUMERIC',
        'compare' => 'EXISTS',
    ),
    'recent' => array(
        'key'     => 'cl_timestamp',
        'type'    => 'NUMERIC',
        'compare' => 'EXISTS',
    ),
    'price' => array(
        'key'     => 'cl_basic_price',
        'type'    => 'NUMERIC',
        'compare' => 'EXISTS',
    ),    
);

//Inject orderby based on user selection
switch( $orderby ) {
    case 'recent':
        $query_args['orderby']  = array(             
            'post_date'     => 'DESC',
        );
    break;
    case 'featured':
        $query_args['orderby']  = array( 
            'featured'      => 'DESC',            
        );
    break;                        
    case 'rating':
        $query_args['orderby']  = array( 
            'rating'      => 'DESC',
        );
    break; 
    case 'price':
        $query_args['orderby']  = array( 
            'price'      => 'DESC',
        );
    break; 
}

//Price High to Low
if( $orderby == 'price-low' ){
    $query_args['orderby']  = array( 
            'price' => 'ASC',
        );
}

//meta query
if (!empty($meta_query_args)) {
    $query_relation = array('relation' => 'AND',);
    $meta_query_args = array_merge($query_relation, $meta_query_args);
    $query_args['meta_query'] = $meta_query_args;
}


//Prepare Taxonomy Query
if (!empty($tax_category_query_args)) {
    $query_relation = array('relation' => 'AND',);
    $tax_query_args = array_merge($query_relation, $tax_category_query_args);
    $query_args['tax_query'] = $tax_query_args;
}

//Prepare Search Based Query
if (!empty($search)) {
    $query_args['s'] = $search;
}

//Icnlude template
require_once codesquare_workintry_addon_template_exsits('workintry/templates/gig-grid');
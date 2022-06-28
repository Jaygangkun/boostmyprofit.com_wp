<?php
/*
* Meta Boxes inclusion
*/

/*
* Package Meta
*/
add_filter( 'rwmb_meta_boxes', 'codesquare_workintry_register_package_meta_boxes' );
function codesquare_workintry_register_package_meta_boxes( $meta_boxes ){   
    $meta_boxes[] = array(
        'id'         => 'featured',
        'title'      => esc_html__('Package Settings', 'workintry'),
        'post_types' => 'product',
        'context'    => 'normal',
        'priority'   => 'high',
        'fields' => array(         
            array(
                'name'  => esc_html__('Package Duration?', 'workintry'),
                'desc'  => esc_html__('Package duration in days [only number value]', 'workintry'),
                'id'    => 'package_duration',
                'type'  => 'text',                
            ),
            array(
                'name'  => esc_html__('Featured Duration?', 'workintry'),
                'desc'  => esc_html__('Featured duration in days [only number value]', 'workintry'),
                'id'    => 'featured_duration',
                'type'  => 'text',                
            ),          
            array(
                'name'  => esc_html__('Featured Gigs?', 'workintry'),
                'desc'  => esc_html__('Featured gigs allowed in pacakge [only number value]', 'workintry'),
                'id'    => 'featured_ads',
                'type'  => 'text',                
            ),
            array(
                'name'  => esc_html__('Bump up gigs ?', 'workintry'),
                'desc'  => esc_html__('Bump up gigs [only number value]', 'workintry'),
                'id'    => 'bump_ads',
                'type'  => 'text',                
            ),       
        ),
    );
    return $meta_boxes;
}

/*
* Gig Meta
*/
add_filter( 'rwmb_meta_boxes', 'codesquare_workintry_register_property_meta_boxes' );
function codesquare_workintry_register_property_meta_boxes( $meta_boxes ) {
    $terms = get_term_meta( 24, 'feature', true );
    $prefix = 'cl_';
    $meta_boxes[] = array(
        'title'  => 'Property Details',
        'post_types' => 'workintry',
        'fields' => array(         
            //Default Settings                                       
            array(
                'name'  => esc_html__('Fast Delivery', 'workintry'),
                'id'    => $prefix . 'fast',
                'type'  => 'checkbox',  
                'std'   => 1,
            ), 
            array(
                'name' => esc_html__('Basic Fast Delivery Days', 'workintry'),
                'id'   => $prefix . 'basic_fast_delivery',
                'type'  => 'select',
                'options' => array( 
                    ''      => esc_html__('Select', 'workintry'),  
                    '1'     => esc_html__('1 Day', 'workintry'),
                    '2'     => esc_html__('1 Days', 'workintry'), 
                    '3'     => esc_html__('3 Days', 'workintry'),
                    '4'     => esc_html__('4 Days', 'workintry'),
                    '5'     => esc_html__('5 Days', 'workintry'),
                    '6'     => esc_html__('6 Days', 'workintry'),
                    '7'     => esc_html__('7 Days', 'workintry'),
                    '8'     => esc_html__('8 Days', 'workintry'),
                    '9'     => esc_html__('9 Days', 'workintry'),
                    '10'    => esc_html__('10 Days', 'workintry'),
                    '14'     => esc_html__('14 Days', 'workintry'),
                    '21'     => esc_html__('21 Days', 'workintry'),
                    '28'     => esc_html__('28 Days', 'workintry'),
                    '35'     => esc_html__('35 Days', 'workintry'),
                    '45'     => esc_html__('45 Days', 'workintry'),
                    '60'     => esc_html__('60 Days', 'workintry'),
                    '75'     => esc_html__('75 Days', 'workintry'),
                    '90'     => esc_html__('90 Days', 'workintry'),
                ),  
                'hidden' => array( 'cl_fast', '!=', '1' )     
            ),  
            array(
                'name' => esc_html__('Basic Fast Price', 'workintry'),
                'id'   => $prefix . 'basic_fast_price',
                'type'  => 'text',  
                'hidden' => array( 'cl_fast', '!=', '1' )              
            ),
            array(
                'name' => esc_html__('Gold Fast Delivery Days', 'workintry'),
                'id'   => $prefix . 'gold_fast_delivery',
                'type'  => 'select',
                'options' => array( 
                    ''      => esc_html__('Select', 'workintry'),  
                    '1'     => esc_html__('1 Day', 'workintry'),
                    '2'     => esc_html__('1 Days', 'workintry'), 
                    '3'     => esc_html__('3 Days', 'workintry'),
                    '4'     => esc_html__('4 Days', 'workintry'),
                    '5'     => esc_html__('5 Days', 'workintry'),
                    '6'     => esc_html__('6 Days', 'workintry'),
                    '7'     => esc_html__('7 Days', 'workintry'),
                    '8'     => esc_html__('8 Days', 'workintry'),
                    '9'     => esc_html__('9 Days', 'workintry'),
                    '10'    => esc_html__('10 Days', 'workintry'),
                    '14'     => esc_html__('14 Days', 'workintry'),
                    '21'     => esc_html__('21 Days', 'workintry'),
                    '28'     => esc_html__('28 Days', 'workintry'),
                    '35'     => esc_html__('35 Days', 'workintry'),
                    '45'     => esc_html__('45 Days', 'workintry'),
                    '60'     => esc_html__('60 Days', 'workintry'),
                    '75'     => esc_html__('75 Days', 'workintry'),
                    '90'     => esc_html__('90 Days', 'workintry'),
                ), 
                'hidden' => array( 'cl_fast', '!=', '1' )               
            ),  
            array(
                'name' => esc_html__('Gold Fast Price', 'workintry'),
                'id'   => $prefix . 'gold_fast_price',
                'type'  => 'text',  
                'hidden' => array( 'cl_fast', '!=', '1' )              
            ),
            array(
                'name' => esc_html__('Diamond Fast Delivery Days', 'workintry'),
                'id'   => $prefix . 'diamond_fast_delivery',
                'type'  => 'select',
                'options' => array( 
                    ''      => esc_html__('Select', 'workintry'),  
                    '1'     => esc_html__('1 Day', 'workintry'),
                    '2'     => esc_html__('1 Days', 'workintry'), 
                    '3'     => esc_html__('3 Days', 'workintry'),
                    '4'     => esc_html__('4 Days', 'workintry'),
                    '5'     => esc_html__('5 Days', 'workintry'),
                    '6'     => esc_html__('6 Days', 'workintry'),
                    '7'     => esc_html__('7 Days', 'workintry'),
                    '8'     => esc_html__('8 Days', 'workintry'),
                    '9'     => esc_html__('9 Days', 'workintry'),
                    '10'    => esc_html__('10 Days', 'workintry'),
                    '14'     => esc_html__('14 Days', 'workintry'),
                    '21'     => esc_html__('21 Days', 'workintry'),
                    '28'     => esc_html__('28 Days', 'workintry'),
                    '35'     => esc_html__('35 Days', 'workintry'),
                    '45'     => esc_html__('45 Days', 'workintry'),
                    '60'     => esc_html__('60 Days', 'workintry'),
                    '75'     => esc_html__('75 Days', 'workintry'),
                    '90'     => esc_html__('90 Days', 'workintry'),
                ),     
                'hidden' => array( 'cl_fast', '!=', '1' )           
            ),  
            array(
                'name' => esc_html__('Diamond Fast Price', 'workintry'),
                'id'   => $prefix . 'diamond_fast_price',
                'type'  => 'text',  
                'hidden' => array( 'cl_fast', '!=', '1' )              
            ),
            array(
                'name'   => esc_html__('FAQ\'s', 'workintry'), // Optional
                'id'     => $prefix.'faq',
                'type'   => 'group',
                'clone'  => true,               
                'fields' => array(
                    array(
                        'name'  => esc_html__('Question', 'workintry'),
                        'desc'  => esc_html__('Provide question which will become as question', 'workintry'),
                        'id'    => 'title',
                        'type'  => 'text',                
                    ),
                    array(
                        'name'  => esc_html__('Answer', 'workintry'),
                        'desc'  => esc_html__('Provide answer which will become as answer', 'workintry'),
                        'id'    => 'description',
                        'type'  => 'textarea',                
                    ),          
                ),
            ),     
            array(
                'id'               => $prefix . 'galleryc',
                'name'             => esc_html__('Gallery', 'workintry'),
                'type'             => 'image_advanced',               
                'force_delete'     => false,               
                'image_size'       => 'thumbnail',
            ), 
            array(
                'name'  => esc_html__('Featured Ad?', 'workintry'),
                'desc'  => esc_html__('Make Ad Featured', 'workintry'),
                'id'    => $prefix . 'featured',
                'type'  => 'select',
                'options' => array(                 
                    'no' => esc_html__('No', 'workintry'),
                    'yes' => esc_html__('Yes', 'workintry')
                ),
            ),                    
            array(
                'name'  => esc_html__('Bump Up?', 'workintry'),
                'desc'  => esc_html__('Bump up ad by changing date to latest', 'workintry'),
                'id'    => $prefix . 'bump',
                'type'  => 'select',
                'options' => array(                 
                    'no' => esc_html__('No', 'workintry'),
                    'yes' => esc_html__('Yes', 'workintry'),
                ),
            ),                                   
        ),    
    );
    return $meta_boxes;
}

/*
* Shortcode builder
*/
add_filter( 'rwmb_meta_boxes', 'codesquare_workintry_register_banner_meta_boxes' );
function codesquare_workintry_register_banner_meta_boxes( $meta_boxes ){   
    $meta_boxes[] = array(
        'id'         => 'featured',
        'title'      => esc_html__('Shortcode Settings', 'workintry'),
        'post_types' => 'workintry_shortcodes',
        'context'    => 'normal',
        'priority'   => 'high',
        'fields' => array(  
            array(
                'name'  => esc_html__('Shortcode For?', 'workintry'),
                'desc'  => esc_html__('Choose shortcode type', 'workintry'),
                'id'    => 'cl_type',
                'type'  => 'select',
                'options' => array(    
                    ''       => esc_html__('Select', 'workintry'),
                    'banner' => esc_html__('Banner', 'workintry'),
                    'clients' => esc_html__('Clients', 'workintry'),
                    'categories' => esc_html__('Categories', 'workintry'),
                    'featured' => esc_html__('Featured Posts', 'workintry'),
                    'normal' => esc_html__('Recent Posts', 'workintry'),
                    'stat' => esc_html__('Stats', 'workintry'),
                    'testimonials' => esc_html__('Testimonial', 'workintry'),
                     'about' => esc_html__('About', 'workintry'),
                ),
            ),       
            array(
                'name'  => esc_html__('Sub title?', 'workintry'),
                'desc'  => esc_html__('Sub title', 'workintry'),
                'id'    => 'sub_title',
                'type'  => 'text',   
                'hidden' => array( 'cl_type', '!=', 'banner' )
            ),
            array(
                'name'  => esc_html__('Title?', 'workintry'),
                'desc'  => esc_html__('Title', 'workintry'),
                'id'    => 'title',
                'type'  => 'text',     
                'hidden' => array( 'cl_type', '!=', 'banner' )
            ),
            array(
                'name'  => esc_html__('Description?', 'workintry'),
                'desc'  => esc_html__('description', 'workintry'),
                'id'    => 'description',
                'type'  => 'textarea', 
                'hidden' => array( 'cl_type', '!=', 'banner' )
            ),            
            array(
                'id'               => 'gallery',
                'name'             => esc_html__('Background Image', 'workintry'),
                'type'             => 'image_advanced',               
                'force_delete'     => false,               
                'image_size'       => 'thumbnail',
                'max_file_uploads' => 1,
                'hidden' => array( 'cl_type', '!=', 'banner' )
            ),
            array(
                'id'               => 'images',
                'name'             => esc_html__('Right Image', 'workintry'),
                'type'             => 'image_advanced',               
                'force_delete'     => false,               
                'image_size'       => 'thumbnail',
                'max_file_uploads' => 1,
                'hidden' => array( 'cl_type', '!=', 'banner' )
            ),
            array(
                'name'  => esc_html__('Show Form?', 'workintry'),
                'desc'  => esc_html__('Make form visible or hidden', 'workintry'),
                'id'    => 'show_form',
                'type'  => 'select',
                'options' => array(                 
                    'no' => esc_html__('No', 'workintry'),
                    'yes' => esc_html__('Yes', 'workintry')
                ),                        
                'hidden' => array( 'cl_type', '!=', 'banner' )
            ),          
            array(
                'name'       => esc_html__('Categories','workintry'),
                'id'         => 'categories',
                'type'       => 'taxonomy_advanced',                
                'taxonomy'   => 'gig_category',
                'field_type' => 'checkbox_list',
                'hidden' => array( 'cl_type', '!=', 'banner' )
            ),
            //Clients
            array(
                'id'               => 'clients',
                'name'             => esc_html__('Background Image', 'workintry'),
                'type'             => 'image_advanced',               
                'force_delete'     => false,               
                'image_size'       => 'thumbnail',
                'max_file_uploads' => 6,
                'hidden' => array( 'cl_type', '!=', 'clients' )
            ),
            //Categories
            array(
                'name'  => esc_html__('Categories title?', 'workintry'),
                'desc'  => esc_html__('Title', 'workintry'),
                'id'    => 'cat_title',
                'type'  => 'text',   
                'hidden' => array( 'cl_type', '!=', 'categories' )
            ),
            array(
                'name'  => esc_html__('Categories description?', 'workintry'),
                'desc'  => esc_html__('Description', 'workintry'),
                'id'    => 'cat_desc',
                'type'  => 'textarea',   
                'hidden' => array( 'cl_type', '!=', 'categories' )
            ),
            array(
                'name'  => esc_html__('Categories button title?', 'workintry'),
                'desc'  => esc_html__('Title', 'workintry'),
                'id'    => 'cat_btn_title',
                'type'  => 'text',   
                'hidden' => array( 'cl_type', '!=', 'categories' )
            ),
            array(
                'name'  => esc_html__('Categories button link?', 'workintry'),
                'desc'  => esc_html__('Link', 'workintry'),
                'id'    => 'cat_btn_link',
                'type'  => 'text',   
                'hidden' => array( 'cl_type', '!=', 'categories' )
            ),
            array(
                'name'       => esc_html__('Select Categories','workintry'),
                'id'         => 'cat_categories',
                'type'       => 'taxonomy_advanced',                
                'taxonomy'   => 'gig_category',
                'field_type' => 'checkbox_list',
                'hidden' => array( 'cl_type', '!=', 'categories' )
            ),
            //Featured Ads Slider
            array(
                'name'  => esc_html__('Title?', 'workintry'),
                'desc'  => esc_html__('Title', 'workintry'),
                'id'    => 'ftitle',
                'type'  => 'text',   
                'default' => 'Our Featured Properties',                
                'hidden' => array( 'cl_type', '!=', 'featured' )
            ),
            array(
                'name'  => esc_html__('Sub Title?', 'workintry'),
                'desc'  => esc_html__('Sub Title', 'workintry'),
                'id'    => 'fsub_title',
                'default' => 'Latest & Trending',
                'type'  => 'text',   
                'hidden' => array( 'cl_type', '!=', 'featured' )
            ),
            array(
                'name'  => esc_html__('Description?', 'workintry'),
                'desc'  => esc_html__('Description', 'workintry'),
                'id'    => 'fdescription',
                'type'  => 'textarea',   
                'hidden' => array( 'cl_type', '!=', 'featured' )
            ),            
            array(
                'name'  => esc_html__('Count?', 'workintry'),
                'desc'  => esc_html__('Number of ads to show', 'workintry'),
                'id'    => 'fcount',
                'type'  => 'text',   
                'hidden' => array( 'cl_type', '!=', 'featured' )
            ),
            //Normal Ads Slider            
            array(
                'name'  => esc_html__('Title?', 'workintry'),
                'desc'  => esc_html__('Title', 'workintry'),
                'id'    => 'ntitle',
                'type'  => 'text',   
                'default' => 'Our Featured Properties',                
                'hidden' => array( 'cl_type', '!=', 'normal' )
            ),
            array(
                'name'  => esc_html__('Sub Title?', 'workintry'),
                'desc'  => esc_html__('Sub Title', 'workintry'),
                'id'    => 'nsub_title',
                'default' => 'Latest & Trending',
                'type'  => 'text',   
                'hidden' => array( 'cl_type', '!=', 'normal' )
            ),
            array(
                'name'  => esc_html__('Description?', 'workintry'),
                'desc'  => esc_html__('Description', 'workintry'),
                'id'    => 'ndescription',
                'type'  => 'textarea',   
                'hidden' => array( 'cl_type', '!=', 'normal' )
            ),            
            array(
                'name'  => esc_html__('Count?', 'workintry'),
                'desc'  => esc_html__('Number of ads to show', 'workintry'),
                'id'    => 'ncount',
                'type'  => 'text',   
                'hidden' => array( 'cl_type', '!=', 'normal' )
            ),        
            //Stats   
             array(             
                'name' => 'Background image',
                'id'   => 'bg_images',
                'type' => 'image_upload',
                'max_file_uploads' => 1,     
                'hidden' => array( 'cl_type', '!=', 'stat' )
            ),         
            array(
                'id'     => 'stats',
                'type'   => 'group',
                'clone'  => true,
                'sort_clone' => true,
                'fields' => array(                   
                    array(
                        'name' => 'Stat value',
                        'id'   => 'value',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Stat figure',
                        'id'   => 'figure',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Stat text',
                        'id'   => 'stat',
                        'type' => 'text',
                    ),                    
                    array(                
                        'name' => 'Image',
                        'id'   => 'stat_image',
                        'type' => 'image_upload',
                        'max_file_uploads' => 1,                        
                    ), 
                ),
                'hidden' => array( 'cl_type', '!=', 'stat' )
            ),
            //Testimonials  
            array(
                'name' => 'Testimonial section title',
                'id'   => 'ttitle',
                'type' => 'text',
                'hidden' => array( 'cl_type', '!=', 'testimonials' )
            ),
            array(
                'name' => 'Testimonial section sub title',
                'id'   => 'tsub_title',
                'type' => 'text',
                'hidden' => array( 'cl_type', '!=', 'testimonials' )
            ),
            array(
                'name' => 'Testimonial section description',
                'id'   => 'tdesc',
                'type' => 'textarea',
                'hidden' => array( 'cl_type', '!=', 'testimonials' )
            ),   
            array(                
                'name' => 'Testimonial Section Background Image',
                'id'   => 'tbgimage',
                'type' => 'image_upload',
                'max_file_uploads' => 1,
                'hidden' => array( 'cl_type', '!=', 'testimonials' )
            ),                          
            array(
                'id'     => 'testimonials',
                'type'   => 'group',
                'clone'  => true,
                'sort_clone' => true,
                'fields' => array(
                    array(                
                        'name' => 'Testimonial Image',
                        'id'   => 'timage',
                        'type' => 'image_upload',
                        'max_file_uploads' => 1,
                        'hidden' => array( 'cl_type', '!=', 'testimonials' )
                    ), 
                    array(                
                        'name' => 'Testimonial Logo',
                        'id'   => 'tlogo',
                        'type' => 'image_upload',
                        'max_file_uploads' => 1,
                        'hidden' => array( 'cl_type', '!=', 'testimonials' )
                    ),  
                    array(
                        'name' => 'Testimonial name',
                        'id'   => 'btn_title',
                        'type' => 'text',
                        'hidden' => array( 'cl_type', '!=', 'testimonials' )
                    ),
                    array(
                        'name' => 'Testimonial content',
                        'id'   => 'btn_link',
                        'type' => 'textarea',
                        'hidden' => array( 'cl_type', '!=', 'testimonials' )
                    ),                   
                ),
                'hidden' => array( 'cl_type', '!=', 'testimonials' )
            ),
            //About us            
            array(
                'name' => 'About section title',
                'id'   => 'atitle',
                'type' => 'text',
                'hidden' => array( 'cl_type', '!=', 'about' )
            ),
            array(
                'name' => 'About section sub title',
                'id'   => 'asub_title',
                'type' => 'text',
                'hidden' => array( 'cl_type', '!=', 'about' )
            ),
            array(
                'name' => 'About section description',
                'id'   => 'adesc',
                'type' => 'textarea',
                'hidden' => array( 'cl_type', '!=', 'about' )
            ),                                       
            array(
                'id'     => 'abouts',
                'type'   => 'group',
                'clone'  => true,
                'sort_clone' => true,
                'fields' => array(
                    array(                
                        'name' => 'About Image',
                        'id'   => 'aimage',
                        'type' => 'image_upload',
                        'max_file_uploads' => 1,
                        'hidden' => array( 'cl_type', '!=', 'about' )
                    ),                   
                    array(
                        'name' => 'About title',
                        'id'   => 'a_title',
                        'type' => 'text',
                        'hidden' => array( 'cl_type', '!=', 'about' )
                    ),
                    array(
                        'name' => 'About content',
                        'id'   => 'adesc',
                        'type' => 'textarea',
                        'hidden' => array( 'cl_type', '!=', 'about' )
                    ),                   
                ),
                'hidden' => array( 'cl_type', '!=', 'about' )
            ),
        ),
    );
    return $meta_boxes;
}

/*
* Gig Order
*/
add_filter( 'rwmb_meta_boxes', 'codesquare_workintry_register_gig_meta_boxes' );
function codesquare_workintry_register_gig_meta_boxes( $meta_boxes ){   
    $meta_boxes[] = array(
        'id'         => 'featured',
        'title'      => esc_html__('Gig Order Details', 'workintry'),
        'post_types' => 'gig-order',
        'context'    => 'normal',
        'priority'   => 'high',
        'fields' => array(                     
            array(
                'name'        => esc_html__('Seller User','workintry'),
                'id'          => 'seller_id',
                'type'        => 'user',
                'field_type'  => 'select_advanced',
                'placeholder' => esc_html__('Select a seller', 'workintry'),
                'query_args'  => array(),
            ),
            array(
                'name'        => esc_html__('Buyer User','workintry'),
                'id'          => 'buyer_id',
                'type'        => 'user',
                'field_type'  => 'select_advanced',
                'placeholder' => esc_html__('Select a buyer', 'workintry'),
                'query_args'  => array(),
            ), 
            array(
                'name'    => esc_html__('Actual Price','workintry'),
                'id'      => 'price',
                'type'    => 'text', 
            ),
            array(
                'name'    => esc_html__('Admin Amount','workintry'),
                'id'      => 'admin_price',
                'type'    => 'text', 
            ),
            array(
                'name'    => esc_html__('Seller Amount','workintry'),
                'id'      => 'seller_amount',
                'type'    => 'text', 
            ),    
            array(
                'name'        => esc_html__('Select Gig', 'workintry'),
                'id'          => 'gig_id',
                'type'        => 'post',
                'post_type'   => 'workintry',
                'field_type'  => 'select_advanced',                
                'query_args'  => array(
                    'post_status'    => 'publish',
                    'posts_per_page' => - 1,
                ),
            ),    
            array(
                'name'    => esc_html__('Status','workintry'),
                'id'      => 'status',
                'type'    => 'select',
                'options' => array(
                    ''    => esc_html__('Select', 'workintry'),
                    'pending'    => esc_html__('In Progress', 'workintry'),
                    'un-paid'    => esc_html__('Awaiting Response', 'workintry'),
                    'paid'    => esc_html__('Completed', 'workintry'),
                ),
            ),
            array(
                'name'    => esc_html__('Delivery Result/Status','workintry'),
                'id'      => 'result',
                'type'    => 'select',
                'options' => array(
                    ''    => esc_html__('Select', 'workintry'),
                    'awaiting'      => esc_html__('Awaiting', 'workintry'),
                    'done'          => esc_html__('Completed and accepted', 'workintry'),                    
                ),
            ),             
            array(
                'name'    => esc_html__('Payment Type','workintry'),
                'id'      => 'type',
                'type'    => 'select',
                'options' => array(
                    ''    => esc_html__('Select', 'workintry'),
                    'sell'   => esc_html__('Sale', 'workintry'),
                ),
            ),  
            array(
                'name'       => esc_html__('Time', 'workintry'),
                'id'         => 'timestamp',
                'type'       => 'datetime',
                'js_options' => array(
                    'stepMinute'      => 1,
                    'showTimepicker'  => true,
                    'controlType'     => 'select',
                    'showButtonPanel' => false,
                    'oneLine'         => true,
                ),
                // Display inline?
                'inline'     => false,
                // Save value as timestamp?
                'timestamp'  => true,
            ),
            array(
                'name'       => esc_html__('Year', 'workintry'),
                'id'         => 'year',
                'type'       => 'datetime',
                'js_options' => array(
                    'dateFormat'      => 'yy',
                    'showTimepicker'  => false,
                ),
                // Display inline?
                'inline'     => false,
                // Save value as timestamp?
                'timestamp'  => false,
            ),
            array(
                'name'       => esc_html__('Month', 'workintry'),
                'id'         => 'month',
                'type'       => 'datetime',
                'js_options' => array(
                    'dateFormat'      => 'mm',
                    'showTimepicker'  => false,
                ),
                // Display inline?
                'inline'     => false,
                // Save value as timestamp?
                'timestamp'  => false,
            ),
            array(
                'name'    => esc_html__('Gig Type','workintry'),
                'id'      => 'gig_type',
                'type'    => 'text', 
            ),
            array(
                'name'    => esc_html__('Revisions','workintry'),
                'id'      => 'gig_revisions',
                'type'    => 'select',
                'options' => array(
                    ''    => esc_html__('Select', 'workintry'),
                    '1'   => esc_html__('1 Revision', 'workintry'),
                    '2'   => esc_html__('2 Revisions', 'workintry'),
                    '3'   => esc_html__('3 Revisions', 'workintry'),
                ),
            ),
            array(
                'name'    => esc_html__('Used Revisions','workintry'),
                'id'      => 'used_gig_revisions',
                'type'    => 'select',
                'options' => array(
                    ''    => esc_html__('Select', 'workintry'),
                    '1'   => esc_html__('1 Revision', 'workintry'),
                    '2'   => esc_html__('2 Revisions', 'workintry'),
                    '3'   => esc_html__('3 Revisions', 'workintry'),
                ),
            ),
            array(
                'name'    => esc_html__('Gig Delivery','workintry'),
                'id'      => 'gig_delivery',
                'type'    => 'text', 
            ),           
        ),
    );
    return $meta_boxes;
}


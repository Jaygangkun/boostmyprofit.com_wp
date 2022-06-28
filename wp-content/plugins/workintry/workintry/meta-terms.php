<?php 
add_filter( 'rwmb_meta_boxes', 'codesquare_workintry_register_custom_category_terms' );
function codesquare_workintry_register_custom_category_terms( $meta_boxes ){
    $meta_boxes[] = array(
        'title'      => esc_html__('Workintry Fields','workintry'),
        'taxonomies' => array('gig_category', 'property_country', 'property_city'), // List of taxonomies. Array or string
        'fields' => array(          
            array(
                'name' => esc_html__('Featured Image','workintry'),
                'id'   => 'image',
                'type' => 'image_advanced',
                'max_file_uploads' => 1,
            ),
            array(
                'name' => esc_html__('Color', 'workintry'),
                'id'   => 'color',
                'type' => 'color',
            ),           
        ),
    );
    return $meta_boxes;
}

//City
add_filter( 'rwmb_meta_boxes', 'codesquare_workintry_register_custom_category_terms_city' );
function codesquare_workintry_register_custom_category_terms_city( $meta_boxes ){
    $meta_boxes[] = array(
        'title'      => '',
        'taxonomies' => array('property_city'), // List of taxonomies. Array or string
        'fields' => array(          
           array(
            'name'       => 'Taxonomy',
            'id'         => 'city_meta',
            'type'       => 'taxonomy_advanced',

            // Taxonomy slug.
            'taxonomy'   => 'property_country',

            // How to show taxonomy.
            'field_type' => 'select_advanced',
        ),          
        ),
    );
    return $meta_boxes;
}

//Category
add_filter( 'rwmb_meta_boxes', 'codesquare_workintry_register_custom_category_icon' );
function codesquare_workintry_register_custom_category_icon( $meta_boxes ){
    $meta_boxes[] = array(
        'title'      => '',
        'taxonomies' => array('gig_category'), // List of taxonomies. Array or string
        'fields' => array(          
            array(
                'name' => esc_html__('Icon', 'workintry'),
                'id'   => 'icon',
                'type' => 'text',
            ),          
        ),
    );
    return $meta_boxes;
}


//Sub Category
add_filter( 'rwmb_meta_boxes', 'codesquare_workintry_custom_sub_category_terms_meta' );
function codesquare_workintry_custom_sub_category_terms_meta( $meta_boxes ){
    $meta_boxes[] = array(
        'title'      => 'Parent Category',
        'taxonomies' => array('gig_sub_category'), // List of taxonomies. Array or string
        'fields' => array(          
           array(
            'name'       => 'Taxonomy',
            'id'         => 'parent_category',
            'type'       => 'taxonomy_advanced',

            // Taxonomy slug.
            'taxonomy'   => 'gig_category',

            // How to show taxonomy.
            'field_type' => 'select_advanced',
        ),          
        ),
    );
    return $meta_boxes;
}

//Services
add_filter( 'rwmb_meta_boxes', 'codesquare_workintry_custom_services_terms_meta' );
function codesquare_workintry_custom_services_terms_meta( $meta_boxes ){
    $prefix = 'wi';
    $meta_boxes[] = array(
        'title'      => 'Parent Sub Category',
        'taxonomies' => array('gig_service'), // List of taxonomies. Array or string
        'fields' => array(          
           array(
                'name'       => 'Taxonomy',
                'id'         => 'gig_meta',
                'type'       => 'taxonomy_advanced',

                // Taxonomy slug.
                'taxonomy'   => 'gig_sub_category',

                // How to show taxonomy.
                'field_type' => 'select_advanced',
            ),            
            array(
                'name'   => esc_html__('Gig Meta', 'workintry'), // Optional
                'id'     => $prefix.'meta',
                'type'   => 'group',
                'clone'  => true,               
                'fields' => array(
                    array(
                        'name'  => esc_html__('Title', 'workintry'),
                        'desc'  => esc_html__('Provide title which will become as meta title', 'workintry'),
                        'id'    => $prefix . 'title',
                        'type'  => 'text',                
                    ),
                    array(
                        'name'  => esc_html__('Type', 'workintry'),
                        'desc'  => esc_html__('Select type like checkbox or select', 'workintry'),
                        'id'    => $prefix . 'type',
                        'type'  => 'select', 
                        'options' => array(                 
                            'check' => esc_html__('Checkbox', 'workintry'),
                            'selecte' => esc_html__('Select', 'workintry'),
                        ),               
                    ),   
                    array(
                        'name'  => esc_html__('Select Field Values', 'workintry'),
                        'desc'  => esc_html__('Provide select field values in each line', 'workintry'),
                        'id'    => $prefix . 'value',
                        'type'  => 'text',
                        'clone'       => true,
                        'hidden' => array( 'witype', '!=', 'selecte' )          
                    ),         
                    // Other sub-fields here
                ),
            ),            
        ),
    );
    return $meta_boxes;
}

//City Property
add_filter( 'rwmb_meta_boxes', 'codesquare_workintry_register_custom_gig_terms_city' );
function codesquare_workintry_register_custom_gig_terms_city( $meta_boxes ){
    $meta_boxes[] = array(
        'title'      => '',
        'taxonomies' => array('gig_city'), // List of taxonomies. Array or string
        'fields' => array(          
           array(
            'name'       => 'Taxonomy',
            'id'         => 'city_meta',
            'type'       => 'taxonomy_advanced',

            // Taxonomy slug.
            'taxonomy'   => 'gig_country',

            // How to show taxonomy.
            'field_type' => 'select_advanced',
        ),          
        ),
    );
    return $meta_boxes;
}
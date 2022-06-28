<?php 
// Register settings page. In this case, it's a theme options page
add_filter( 'mb_settings_pages', 'codesquare_workintry_plugin_options_page' );
function codesquare_workintry_plugin_options_page( $settings_pages ) {
    $settings_pages[] = array(
        'id'          => 'workintry',
        'option_name' => 'workintry',
        'menu_title'  => esc_html__('Workintry Settings','workintry'),
        'icon_url'    => 'dashicons-edit',
        'style'       => 'boxes',
        'columns'     => 1,
        'tab_style' => 'left',
        'tabs'        => array(
            'general' => esc_html__('General Settings','workintry'),
            'search'  => esc_html__('Search Settings', 'workintry'),
            'register'  => esc_html__('Registration Settings', 'workintry'),
            'promotion' => esc_html__('Promotion Settings', 'workintry'),
                
            'home'    => esc_html__('Gig Field Settings', 'workintry'),          
            'detail'    => esc_html__('Detail Page Settings', 'workintry'),
            'payment'    => esc_html__('Payment Settings', 'workintry'),
            'faq'     => esc_html__('FAQ & Help', 'workintry'),
        ),
        'tab_style' => 'left', 
        'position'   => 5,
    );
    return $settings_pages;
}

// Register meta boxes and fields for settings page
add_filter( 'rwmb_meta_boxes', 'prefix_options_meta_boxes' );
function prefix_options_meta_boxes( $meta_boxes ) {
    //Get Theme registered menus list
    $cl_registered_menus = get_registered_nav_menus();
    array_unshift($cl_registered_menus, 'No Menu' );
    $meta_boxes[] = array(
        'id'             => 'general',
        'title'          => 'General',
        'settings_pages' => 'workintry',
        'tab'            => 'general',
        'fields' => array(  
            array(
                'name'            => esc_html__('Directory Type', 'workintry'),
                'id'                => 'directory_type',
                'type'              => 'select_advanced',            
                'options'           => array(
                    'paid'          => 'Paid',
                    'free'          => 'Free',                 
                ),
            ),              
            array(
                'name' => esc_html__('Maximum Gigs per account', 'workintry'),
                'id'   => 'max_gigs',
                'type' => 'select',
                'options' => array(
                    '1'      => esc_html__('1 Gig','workintry'),
                    '2'      => esc_html__('2 Gigs','workintry'),
                    '3'      => esc_html__('3 Gigs','workintry'),
                    '4'      => esc_html__('4 Gigs','workintry'),
                    '5'      => esc_html__('5 Gigs','workintry'),
                    '6'      => esc_html__('6 Gigs','workintry'),
                    '7'      => esc_html__('7 Gigs','workintry'),
                    '8'      => esc_html__('8 Gigs','workintry'),
                    '9'      => esc_html__('9 Gigs','workintry'),
                    '10'      => esc_html__('10 Gigs','workintry'),
                ),
                'std'       => '5',
            ),          
            array(
                'name' => esc_html__('Logo Image', 'workintry'),
                'id'   => 'logo',
                'type' => 'file_input',
            ),     
         	array(
			    'name' => esc_html__('Dashboard Page', 'workintry'),
			    'id'   => 'profile_page',
			    'type' => 'post',			    
			    'post_type'   => 'page',			   
			    'field_type'  => 'select_advanced',			    
			    'placeholder' => esc_html__('Select dashboard page', 'workintry'),			   
			    'query_args'  => array(
			        'post_status'    => 'publish',
			        'posts_per_page' => - 1,
			    ),
			),
			array(
                'name' => esc_html__('Author Page', 'workintry'),
                'id'   => 'author_page',
                'type' => 'post',               
                'post_type'   => 'page',               
                'field_type'  => 'select_advanced',             
                'placeholder' => esc_html__('Select author gigs page', 'workintry'),             
                'query_args'  => array(
                    'post_status'    => 'publish',
                    'posts_per_page' => - 1,
                ),
            ),
            //Dashboard Menu
            array(
                'name' => esc_html__('Dashboard Menu', 'workintry'),
                'id'   => 'dash-menu',
                'type' => 'select',
                'options' => $cl_registered_menus,
            ),   
            //Dashboard lOgos
            array(
                'name' => esc_html__('Dashboard Total Gigs Image', 'workintry'),
                'id'   => 'dashboard_total',
                'type' => 'file_input',
            ),
            array(
                'name' => esc_html__('Dashboard Featured Image', 'workintry'),
                'id'   => 'dashboard_featured',
                'type' => 'file_input',
            ),
            array(
                'name' => esc_html__('Dashboard Active Image', 'workintry'),
                'id'   => 'dashboard_active',
                'type' => 'file_input',
            ),
            array(
                'name' => esc_html__('Dashboard Inactive Image', 'workintry'),
                'id'   => 'dashboard_inactive',
                'type' => 'file_input',
            ),        
            //Color
            array(
                'name'          => esc_html__('Default Color', 'workintry'),
                'id'            => 'cl_color',
                'type'          => 'color',
                // Add alpha channel?
                'alpha_channel' => true,
                // Color picker options. See here: https://automattic.github.io/Iris/.
                'js_options'    => array(
                    'palettes' => array( '#125', '#459', '#78b', '#ab0', '#de3', '#f0f' )
                ),
            ),          
            array(
                'name' => esc_html__('System Currency', 'workintry'),
                'id'   => 'cl_default_currency',
                'type' => 'text',
            ),    
            array(
                'name' => esc_html__('Copyright Text', 'workintry'),
                'id'   => 'cl_dashboard_copy',
                'type' => 'text',
            ),          
        ),
    );
    $meta_boxes[] = array(
        'id'             => 'map',
        'title'          => esc_html__('Search Settings', 'workintry'),
        'settings_pages' => 'workintry',
        'tab'            => 'search',
        'fields' => array(
            array(
                'name' => esc_html__('Search Page', 'workintry'),
                'id'   => 'homes_search_page',
                'type' => 'post',               
                'post_type'   => 'page',               
                'field_type'  => 'select_advanced',             
                'placeholder' => esc_html__('Select search page', 'workintry'),            
                'query_args'  => array(
                    'post_status'    => 'publish',
                    'posts_per_page' => - 1,
                ),
            ), 
            array(
                'name'        => esc_html__('Choose Search Layout', 'workintry'),
                'id'          => 'search_layout',
                'type'        => 'select_advanced',            
                'options'     => array(
                    'grid'         => esc_html__('Grid view','workintry'),                    
                ),              
                'multiple'        => false,            
                'placeholder'     => esc_html__('Chose layout', 'workintry'),            
                'select_all_none' => false,                                 
            ),
        ),
    );
    //Register
    $meta_boxes[] = array(
        'id'             => 'register',
        'title'          => esc_html__('Registration Settings', 'workintry'),
        'settings_pages' => 'workintry',
        'tab'            => 'register',
        'fields' => array(
            array(
                'name'            => esc_html__('Enable Login/Registration', 'workintry'),
                'id'              => 'login_registration',
                'type'            => 'select_advanced',            
                'options'         => array(
                    'enable'       => 'Enable',
                    'disable' => 'Disable',                 
                ),              
                'multiple'        => false,            
                'placeholder'     => esc_html__('Enable Login/Register', 'workintry'),            
                'select_all_none' => false,             
                'js_options'      => array(
                    //empty              
                ),
            ),
            array(
                'name' => esc_html__('Login/Register Page', 'workintry'),
                'id'   => 'register_page',
                'type' => 'post',               
                'post_type'   => 'page',               
                'field_type'  => 'select_advanced',             
                'placeholder' => esc_html__('Select registration/login page', 'workintry'),               
                'query_args'  => array(
                    'post_status'    => 'publish',
                    'posts_per_page' => - 1,
                ),
            ),
            array(
                'name' => esc_html__('Terms & conditions page', 'workintry'),
                'id'   => 'terms_page',
                'type' => 'post',               
                'post_type'   => 'page',               
                'field_type'  => 'select_advanced',             
                'placeholder' => esc_html__('Select terms page', 'workintry'),            
                'query_args'  => array(
                    'post_status'    => 'publish',
                    'posts_per_page' => - 1,
                ),
            ),   
            array(
                'name' => esc_html__('Main Banner Image', 'workintry'),
                'id'   => 'main_banner',
                'type' => 'file_input',
            ),
            array(
                'name' => esc_html__('Lower Banner Image', 'workintry'),
                'id'   => 'lower_banner',
                'type' => 'file_input',
            ),
            array(
                'type' => 'custom_html',
                'std'  => esc_html__('Below settings will show/hide fields on Registration page, choose as per your own needs', 'workintry'),
            ),
            array(
                'id'        => 'show_first_name',
                'name'      => esc_html__('Show First Name','workintry'),
                'type'      => 'switch',                               
                'style'     => 'rounded',  
                'std'       => 1,      
                'on_label'  => esc_html__('Show First Name','workintry'),
                'off_label' => esc_html__('Hide First Name','workintry'),
            ),
            array(
                'id'        => 'show_last_name',
                'name'      => esc_html__('Show Last Name','workintry'),
                'type'      => 'switch',                               
                'style'     => 'rounded',  
                'std'       => 1,      
                'on_label'  => esc_html__('Show Last Name','workintry'),
                'off_label' => esc_html__('Hide Last Name','workintry'),
            ),
            array(
                'id'        => 'show_gender',
                'name'      => esc_html__('Show Gender','workintry'),
                'type'      => 'switch',                               
                'style'     => 'rounded',  
                'std'       => 1,      
                'on_label'  => esc_html__('Show Gender','workintry'),
                'off_label' => esc_html__('Hide Gender','workintry'),
            ),
            array(
                'id'        => 'show_register_phone',
                'name'      => esc_html__('Show Phone','workintry'),
                'type'      => 'switch',                               
                'style'     => 'rounded',  
                'std'       => 1,      
                'on_label'  => esc_html__('Show Phone','workintry'),
                'off_label' => esc_html__('Hide Phone','workintry'),
            ),  
            array(
                'name' => esc_html__('Google Auth Cliend ID', 'workintry'),
                'id'   => 'google_client_id',
                'type' => 'text',
                'clone'=> false,
            ), 
            array(
                'name' => esc_html__('Google Auth Cliend Secret', 'workintry'),
                'id'   => 'google_client_secret',
                'type' => 'text',
                'clone'=> false,
            ),     
            array(
                'name' => esc_html__('Facebook Login Redirect Page', 'workintry'),
                'id'   => 'facebook_register_page',
                'type' => 'post',               
                'post_type'   => 'page',               
                'field_type'  => 'select_advanced',             
                'placeholder' => esc_html__('Select facebook registration/login redirect page', 'workintry'),               
                'query_args'  => array(
                    'post_status'    => 'publish',
                    'posts_per_page' => - 1,
                ),
            ), 
            array(
                'name' => esc_html__('Facebook Auth App ID', 'workintry'),
                'id'   => 'facebook_client_id',
                'type' => 'text',
                'clone'=> false,
            ), 
            array(
                'name' => esc_html__('Facebook Auth App Secret', 'workintry'),
                'id'   => 'facebook_client_secret',
                'type' => 'text',
                'clone'=> false,
            ),                                               
        ),
    );
    //Register
    $meta_boxes[] = array(
        'id'             => 'promotion',
        'title'          => esc_html__('Promotional Settings','workintry'),
        'settings_pages' => 'workintry',
        'tab'            => 'promotion',
        'fields'         => array(
            array(
                'type' => 'custom_html',
                'std'  => 'Add your desired ad related data which users can get upon registration, for example give users 1 featured ad upon registration etc (you can allow users to use your promotional data to convince more users to your website)',
            ),
            array(
                'type' => 'custom_html',
                'std'  => 'If you chose unlimited then user can create unlimited gigs (it means you can start a free direcory where users can create unlmited free gigs and you can earn from featured, highlighted and bumped gigs',
            ),
            array(
                'name' => esc_html__('Free Total Gigs', 'workintry'),
                'id'   => 'total_ads',
                'type' => 'select',
                'options' => array(
                    '0'    => esc_html__('No Gig', 'workintry'),
                    '-1'   => esc_html__('Unlimited','workintry'),                    
                    '1'    => esc_html__('1 Gig', 'workintry'),
                    '2'    => esc_html__('2 Gig', 'workintry'),
                    '3'    => esc_html__('3 Gig', 'workintry'),
                    '4'    => esc_html__('4 Gig', 'workintry'),
                    '5'    => esc_html__('5 Gig', 'workintry'),
                    '6'    => esc_html__('6 Gig', 'workintry'),
                    '7'    => esc_html__('7 Gig', 'workintry'),
                    '8'    => esc_html__('8 Gig', 'workintry'),
                    '9'    => esc_html__('9 Gigs', 'workintry'),
                    '10'   => esc_html__('10 Gigs', 'workintry'),
                ),
            ),
            array(
                'name' => esc_html__('Free Featued Gigs', 'workintry'),
                'id'   => 'featured_ads',
                'type' => 'select',
                'options' => array(                   
                    '0'    => esc_html__('No Gig', 'workintry'),
                    '1'    => esc_html__('1 Gig', 'workintry'),                    
                    '2'    => esc_html__('2 Gig', 'workintry'),
                    '3'    => esc_html__('3 Gig', 'workintry'),
                    '4'    => esc_html__('4 Gig', 'workintry'),
                    '5'    => esc_html__('5 Gig', 'workintry'),
                    '6'    => esc_html__('6 Gig', 'workintry'),
                    '7'    => esc_html__('7 Gig', 'workintry'),
                    '8'    => esc_html__('8 Gig', 'workintry'),
                    '9'    => esc_html__('9 Gigs', 'workintry'),
                    '10'   => esc_html__('10 Gigs', 'workintry'),
                ),
            ),          
            array(
                'name' => esc_html__('Free Bumped Gigs', 'workintry'),
                'id'   => 'bump_ads',
                'type' => 'select',
                'options' => array(                   
                    '0'    => esc_html__('No Gig', 'workintry'),
                    '1'    => esc_html__('1 Gig', 'workintry'),                    
                    '2'    => esc_html__('2 Gig', 'workintry'),
                    '3'    => esc_html__('3 Gig', 'workintry'),
                    '4'    => esc_html__('4 Gig', 'workintry'),
                    '5'    => esc_html__('5 Gig', 'workintry'),
                    '6'    => esc_html__('6 Gig', 'workintry'),
                    '7'    => esc_html__('7 Gig', 'workintry'),
                    '8'    => esc_html__('8 Gig', 'workintry'),
                    '9'    => esc_html__('9 Gigs', 'workintry'),
                    '10'   => esc_html__('10 Gigs', 'workintry'),
                ),
            ),
            array(
                'name' => esc_html__('Free featued Gigs duration', 'workintry'),
                'id'   => 'feature_duration',
                'type' => 'select',
                'options' => array(                   
                    '0'    => esc_html__('No day', 'workintry'),
                    '1'    => esc_html__('1 day', 'workintry'),                    
                    '2'    => esc_html__('2 days', 'workintry'),
                    '3'    => esc_html__('3 days', 'workintry'),
                    '4'    => esc_html__('4 days', 'workintry'),
                    '5'    => esc_html__('5 days', 'workintry'),
                    '6'    => esc_html__('6 days', 'workintry'),
                    '7'    => esc_html__('7 days', 'workintry'),
                    '8'    => esc_html__('8 days', 'workintry'),
                    '9'    => esc_html__('9 days', 'workintry'),
                    '10'   => esc_html__('10 days', 'workintry'),
                    '21'   => esc_html__('21 days', 'workintry'),
                    '28'   => esc_html__('28 days', 'workintry'),
                    '30'   => esc_html__('30 days', 'workintry'),
                ),
            ),
            array(
                'type' => 'custom_html',
                'std'  => '<b>Total Gigs</b> are those which users can create (unlimited means user can create unlimited gigs lifetime basis',
            ),
            array(
                'type' => 'custom_html',
                'std'  => '<b>Featured gigs</b> are those which users can set as featured (featured gigs always stay on top in search and get special tiny badge as featured) nice way to earn. Allow your users to pay for featured gigs',
            ),           
            array(
                'type' => 'custom_html',
                'std'  => '<b>Bumped gigs</b> are those which gets new day (latest) to show on top of the table again. If user ad not went good he/she can pay you to make his/her ad on top again.',
            ),
            array(
                'type' => 'custom_html',
                'std'  => '<b>Feature Duration</b> is the number of days you want user ad as featured (maximum one month time is set rest you can set as per your needs)',
            ),
        ),
    );
    $meta_boxes[] = array(
        'id'             => 'home',
        'title'          => esc_html__('Gig Field Settings','workintry'),
        'settings_pages' => 'workintry',
        'tab'            => 'home',
        'fields'         => array(    
            array(
                'name' => esc_html__('Approve Gig', 'workintry'),
                'id'   => 'approve_post',
                'type' => 'select',
                'options' => array(                    
                    'auto'          => esc_html__('Auto Approval','workintry'),
                    'admin'          => esc_html__('Admin will approve gig','workintry'),
                ),
                'std'       => 'admin',
            ),
        ),
    );
    $meta_boxes[] = array(
        'id'             => 'detail',
        'title'          => esc_html__('Detail Page','workintry'),
        'settings_pages' => 'workintry',
        'tab'            => 'detail',
        'fields'         => array(
            array(
                'name' => esc_html__('Packages Title', 'workintry'),
                'id'   => 'p_title',
                'type' => 'text',
            ),
            array(
                'name' => esc_html__('Packages Description', 'workintry'),
                'id'   => 'p_desc',
                'type' => 'textarea',
            ),
            array(
                'type' => 'custom_html',
                'std'  => esc_html__('Set requirements for ad detail page', 'workintry'),
            ),
            array(
                'name' => esc_html__('How it works', 'workintry'),
                'id'   => 'tips',
                'type' => 'text',
                'clone'=> true,
            ),
            array(
                'name' => esc_html__('Ad Report Options', 'workintry'),
                'id'   => 'reasons',
                'type' => 'text',
                'clone'=> true,
            ),     
            array(
                'type' => 'custom_html',
                'std'  => esc_html__('Show/Hide field settings to show or hide Property content on detail page', 'workintry'),
            ),
            // Show Hide Fields                                    
            array(
                'id'        => 'show_report',
                'name'      => esc_html__('Show Report Gig Form','workintry'),
                'type'      => 'switch',                               
                'style'     => 'rounded',        
                'on_label'  => esc_html__('Show Report Gig','workintry'),
                'off_label' => esc_html__('Hide Report Gig','workintry'),
                'std'       => 1,
            ), 
            array(
                'id'        => 'show_tags',
                'name'      => esc_html__('Show Tags','workintry'),
                'type'      => 'switch',                               
                'style'     => 'rounded',        
                'on_label'  => esc_html__('Show Tags','workintry'),
                'off_label' => esc_html__('Hide Tags','workintry'),
                'std'       => 1,
            ),       
            array(
                'id'        => 'show_related',
                'name'      => esc_html__('Show Realted Gigs','workintry'),
                'type'      => 'switch',                               
                'style'     => 'rounded',        
                'on_label'  => esc_html__('Show Related Gigs','workintry'),
                'off_label' => esc_html__('Hide Related Gigs','workintry'),
                'std'       => 1,
            ),  
            array(
                'name' => esc_html__('Author similar gigs Description', 'workintry'),
                'id'   => 'a_desc',
                'type' => 'textarea',
            ),
            //Fields show/hide ends
        ),
    );
    //payment
    $meta_boxes[] = array(
        'id'             => 'payment',
        'title'          => esc_html__('Payment Settings','workintry'),
        'settings_pages' => 'workintry',
        'tab'            => 'payment',
        'fields'         => array(
            array(
                'type' => 'custom_html',
                'std'  => esc_html__('Make sure you set all fields as per your own needs', 'workintry'),
            ),
            array(
                'name' => esc_html__('Set sale comission', 'workintry'),
                'id'   => 'percent',
                'type' => 'select',
                'options' => array(                   
                    'no'    => esc_html__('No commission', 'workintry'),
                    '1'    => esc_html__('1 %', 'workintry'),                    
                    '2'    => esc_html__('2 %', 'workintry'),
                    '3'    => esc_html__('3 %', 'workintry'),
                    '4'    => esc_html__('4 %', 'workintry'),
                    '5'    => esc_html__('5 %', 'workintry'),
                    '6'    => esc_html__('6 %', 'workintry'),
                    '7'    => esc_html__('7 %', 'workintry'),
                    '8'    => esc_html__('8 %', 'workintry'),
                    '9'    => esc_html__('9 %', 'workintry'),
                    '10'   => esc_html__('10 %', 'workintry'),
                    '11'   => esc_html__('11 %', 'workintry'),
                    '12'   => esc_html__('12 %', 'workintry'),
                    '13'   => esc_html__('13 %', 'workintry'),
                    '14'   => esc_html__('14 %', 'workintry'),
                    '15'   => esc_html__('15 %', 'workintry'),
                    '16'   => esc_html__('16 %', 'workintry'),
                    '17'   => esc_html__('17 %', 'workintry'),
                    '18'   => esc_html__('18 %', 'workintry'),
                    '19'   => esc_html__('19 %', 'workintry'),
                    '20'   => esc_html__('20 %', 'workintry'),
                    '21'   => esc_html__('21 %', 'workintry'),
                    '22'   => esc_html__('22 %', 'workintry'),
                    '23'   => esc_html__('23 %', 'workintry'),
                    '24'   => esc_html__('24 %', 'workintry'),
                    '25'   => esc_html__('25 %', 'workintry'),
                    '30'   => esc_html__('30 %', 'workintry'),
                    '40'   => esc_html__('40 %', 'workintry'),
                    '50'   => esc_html__('50 %', 'workintry'),
                ),
                ),
                array(
                    'name' => esc_html__('Set API to sandbox or live', 'workintry'),
                    'id'   => 'api_status',
                    'type' => 'select',
                    'options' => array(                   
                        'sandbox'    => esc_html__('Sandbox', 'workintry'),
                        'live'    => esc_html__('Live', 'workintry'),         
                    )
                ),
                array(
                    'name' => esc_html__('Paypal API username', 'workintry'),
                    'id'   => 'paypal_user',
                    'type' => 'text',
                ),
                array(
                    'name' => esc_html__('Paypal API Password', 'workintry'),
                    'id'   => 'paypal_password',
                    'type' => 'text',
                ),
                array(
                    'name' => esc_html__('Paypal API Signature', 'workintry'),
                    'id'   => 'paypal_signature',
                    'type' => 'text',
                ), 
                array(
                    'name' => esc_html__('Minimum Amount to get paid', 'workintry'),
                    'id'   => 'minimum',
                    'type' => 'text',
                ),
                array(
                    'name' => esc_html__('Payment Service fee', 'workintry'),
                    'id'   => 'fee',
                    'type' => 'text',
                    'description' => esc_html__('Most systems charge transaction amount like 2$ or 3$ etc, you can paste yours here too', 'workintry'),
                ),   
                array(
                    'name' => esc_html__('Seller level 1 amount', 'workintry'),
                    'id'   => 'seller_one',
                    'type' => 'text',
                    'description' => esc_html__('Put amount after which user will become seller level 1', 'workintry'),
                ),
                array(
                    'name' => esc_html__('Seller level 2 amount', 'workintry'),
                    'id'   => 'seller_two',
                    'type' => 'text',
                    'description' => esc_html__('Put amount after which user will become seller level 2', 'workintry'),
                ),
                array(
                    'name' => esc_html__('Seller level 3 amount', 'workintry'),
                    'id'   => 'seller_three',
                    'type' => 'text',
                    'description' => esc_html__('Put amount after which user will become seller level 3', 'workintry'),
                ),
                array(
                    'name' => esc_html__('Seller level 4 amount', 'workintry'),
                    'id'   => 'seller_four',
                    'type' => 'text',
                    'description' => esc_html__('Put amount after which user will become seller level 4', 'workintry'),
                ),
                array(
                    'name' => esc_html__('Seller level 5 amount', 'workintry'),
                    'id'   => 'seller_five',
                    'type' => 'text',
                    'description' => esc_html__('Put amount after which user will become seller level 5', 'workintry'),
                ),
                array(
                    'name' => esc_html__('Seller level Top Rated amount', 'workintry'),
                    'id'   => 'seller_top',
                    'type' => 'text',
                    'description' => esc_html__('Put amount after which user will become seller level top rated', 'workintry'),
                ),                
        ),
    );
    //Payment
    $meta_boxes[] = array(
        'id'             => 'info',
        'title'          => 'Plugin Info',
        'settings_pages' => 'workintry',
        'tab'            => 'faq',
        'fields'         => array(
            array(
                'type' => 'custom_html',
                'std'  => 'Having questions? Check out our documentation or send an email at codesquare.co@gmail.com',
            ),
        ),
    );
    return $meta_boxes;
}

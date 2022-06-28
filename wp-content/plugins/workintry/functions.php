<?php 
/*
* Functions
*/

//Getting Functions File
require_once codesquare_workintry_addon_template_exsits('/admin/post-types');
require_once codesquare_workintry_addon_template_exsits('includes/functions');
require_once codesquare_workintry_addon_template_exsits('workintry/settings-page');
require_once codesquare_workintry_addon_template_exsits('workintry/templates');
require_once codesquare_workintry_addon_template_exsits('workintry/functions');
require_once codesquare_workintry_addon_template_exsits('workintry/meta-terms');
require_once codesquare_workintry_addon_template_exsits('workintry/meta-boxes');
require_once codesquare_workintry_addon_template_exsits('workintry/email-hooks');
require_once codesquare_workintry_addon_template_exsits('includes/hooks');
require_once codesquare_workintry_addon_template_exsits('workintry/front-end/functions');
require_once codesquare_workintry_addon_template_exsits('includes/custom-woocommerce');
require_once codesquare_workintry_addon_template_exsits('workintry/front-end/shortcodes');
require_once codesquare_workintry_addon_template_exsits('includes/class-tgm-plugin-activation');
require_once codesquare_workintry_addon_template_exsits('includes/install-plugins');
require_once codesquare_workintry_addon_template_exsits('includes/dynamic-css');

//Image cropping
add_action( 'init', 'codesquare_workintry_addon_image_cropping' );
function codesquare_workintry_addon_image_cropping() {
    add_image_size('home-banner-slider', 880, 724, true);
    add_image_size('home-ad-slider', 730, 450, true);
    add_image_size('ad-ad-grid', 500, 400, true);    
    add_image_size('cat-img', 255, 260, true);
 	add_image_size('ad-grid', 255, 180, true);     
    add_image_size('ad-dash', 100, 100, true);  
    add_image_size('slide-thumb', 82, 60, true);  
    add_image_size('author-icon', 70, 70, true);
 }

//Registering styles and scripts for Later use in Front End
function codesquare_workintry_scripts_handler(){
    //Lowest and Heighest Price Values    
    global $wpdb;
    $table_prefix = $wpdb->prefix.'postmeta';
    $cl_price_value = 'cl_price';
    $min_results = $wpdb->get_results($wpdb->prepare("SELECT MIN(meta_value) AS minimum FROM $table_prefix WHERE meta_key = %s", $cl_price_value ), OBJECT );
    $max_results = $wpdb->get_results($wpdb->prepare( "SELECT MAX(meta_value) AS maximum FROM $table_prefix WHERE meta_key = %s", $cl_price_value ), OBJECT );
    $min_price = 0;
    $max_price = 0;
    if( !empty( $max_results[0]->maximum ) ){
        $max_price = $max_results[0]->maximum;
    }

    if( !empty( $min_results[0]->minimum ) ){
        $min_price = $min_results[0]->minimum;
    }
    
    //Localize js
    $is_loggedin_user = 'false';
    $currentUser = '';
    if (is_user_logged_in()) {
        global $current_user;
        $is_loggedin_user = 'true';
        $currentUser = $current_user->ID;
    }

	wp_enqueue_style( 'bootstrap', CSC_WORKINTRY_PLUGIN_URL .'assets/css/bootstrap.min.css', array(), '');
    wp_register_style( 'jquery-ui', CSC_WORKINTRY_PLUGIN_URL .'assets/css/jquery-ui.css', array(), '');
	wp_enqueue_style( 'normalize', CSC_WORKINTRY_PLUGIN_URL .'assets/css/normalize.css', array(), '');
	wp_enqueue_style( 'fontawesome-all', CSC_WORKINTRY_PLUGIN_URL .'assets/css/fontawesome/fontawesome-all.min.css', array(), '');
    wp_register_script('moments', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/moments.min.js', array(), '', true);
    wp_register_script('timeago', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/timeago.min.js', array(), '', true);
	wp_enqueue_style( 'linearicons', CSC_WORKINTRY_PLUGIN_URL .'assets/css/linearicons.css', array(), '');	
	wp_register_style( 'owl-carousel', CSC_WORKINTRY_PLUGIN_URL .'assets/css/owl.carousel.min.css', array(), '');
    wp_register_style( 'cl-dashboard', CSC_WORKINTRY_PLUGIN_URL .'assets/css/dashboard.css', array(), '');
    wp_register_style( 'scrollbar', CSC_WORKINTRY_PLUGIN_URL .'assets/css/scrollbar.css', array(), '');    
    wp_register_style( 'lightgallery', CSC_WORKINTRY_PLUGIN_URL .'assets/css/lightgallery.css', array(), '');    
    wp_register_style( 'chosen', CSC_WORKINTRY_PLUGIN_URL .'assets/css/chosen.min.css', array(), '');
	wp_register_script('order-page', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/order.js', array(), '', true);
	wp_enqueue_style( 'cl-transitions', CSC_WORKINTRY_PLUGIN_URL .'assets/css/transitions.css', array(), '');    
    wp_register_script('chart', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/chart.js', array(), '', true);	
    wp_register_style( 'cl-single-chat', CSC_WORKINTRY_PLUGIN_URL .'assets/css/single-chat.css', array(), ''); 
    
    wp_register_style( 'cl-dash-responsive', CSC_WORKINTRY_PLUGIN_URL .'assets/css/dash-responsive.css', array(), ''); 
    wp_register_style( 'gig-edit', CSC_WORKINTRY_PLUGIN_URL .'assets/css/gig-edit.css', array(), '');		
	//Workintry Scripts and Styles
	wp_enqueue_script('bootstrap', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/bootstrap.min.js', array( 'jquery' ), '', true);
    wp_register_script('jquery-ui', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/jquery-ui.js', array( 'jquery' ), '', true); 
	wp_enqueue_script('owl-carousel', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/owl.carousel.min.js', array( 'jquery' ), '', true);
	wp_enqueue_script('cl-cookie', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/jquery.cookie.js', array( 'jquery' ), '', true);    
    wp_register_script('cl-dashboard', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/dashboard.js', array( 'jquery' ), '', true);
    wp_register_script('scrollbar', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/scrollbar.min.js', array( 'jquery' ), '', true);    
    wp_register_script('lightgallery', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/lightgallery-all.min.js', array( 'jquery' ), '', true);  
    wp_register_script('jRate', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/jRate.js', array( 'jquery' ), '', true);  
    wp_register_script('chosen', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/chosen.jquery.min.js', array( 'jquery' ), '', true); 
    wp_register_script('user-chat', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/custom-chat.js', array( 'jquery' ), '', true);
	wp_register_script('workintry-script', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/workintry-script.js', array( 'jquery' ), '', true);	
    wp_register_script('chat-single', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/chat-single.js', array(), '', true);
	wp_enqueue_script('workintry-script');        
    //Dynamic css
    $cl_dynamic_css = codesquare_workintry_print_default_color_css();       
	$sm_success = 'top-right';
	wp_localize_script('workintry-script', 'custom_vars', array(
        'ajaxurl' 		=> admin_url('admin-ajax.php'),
        'cache_title' 	=> esc_html__('Confirm?','workintry'),
        'cache_message' => esc_html__('Never show this message again','workintry'),
        'sm_success' 	=> $sm_success,
        'logged_in'     => $is_loggedin_user,
        'logout_message'=> esc_html__('Login to perform this action', 'workintry'),
        'saved'         => esc_html__('Added to Save', 'workintry'),
        'title'         => esc_html__('Are you sure ?', 'workintry'),
        'message'       => esc_html__('Do you really want to delete it ?', 'workintry'),
        'yes'           => esc_html__('Yes', 'workintry'),
        'no'            => esc_html__('No', 'workintry'),
        'min_price'     => $min_price,
        'max_price'     => $max_price,
    ));

    wp_localize_script('custom-chat', 'custom_vars', array(
        'ajaxurl'       => admin_url('admin-ajax.php'),
        'currentUser'   => $currentUser,
    ));
    wp_localize_script( 'order-page', 'order_details', array(
        'ajaxurl'       => admin_url('admin-ajax.php'),
        'userId' => $currentUser,
        'complete' => esc_html__('Accept as completed', 'workintry'),
        'revision' => esc_html__('Ask for revision', 'workintry'),
        'complete_text' => esc_html__('Your seller delivered order, take action', 'workintry'),
        'done'   => esc_html__('Deliverd', 'workintry'),
        //Upon Revision
        'asked_revision' => esc_html__('Mark as delivered', 'workintry'),
        //Set for the waiting response
        'waiting'      => esc_html__('Waiting Response', 'workintry'),
    ));
    //Scripts at search result page
    if ( is_page_template('gigs-search.php') || is_tax('gig_category') || is_tax( 'gig_country' ) || is_tax( 'gig_city' ) ){
        wp_enqueue_script( 'chosen' );
        wp_enqueue_style( 'chosen' );
        wp_enqueue_script( 'scrollbar' );
        wp_enqueue_style( 'scrollbar' );
        wp_enqueue_style('jquery-ui');
        wp_enqueue_script('jquery-ui');

        //Front END css
        wp_enqueue_style( 'homi-search', CSC_WORKINTRY_PLUGIN_URL .'assets/css/search.css', array(), ''); 
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), '');     
        wp_add_inline_style('workintry', $cl_dynamic_css);   
        wp_enqueue_style( 'themify-icons', CSC_WORKINTRY_PLUGIN_URL .'assets/css/themify-icons.css', array(), '');      

        //Slider
        wp_enqueue_script('owl-carousel');
        wp_enqueue_style( 'owl-carousel' );        
    }

    //Enqueue Dashboard Files
    if( is_page_template( 'dashboard.php' ) ){
        //Dynamic css
        $cl_dynamic_css = codesquare_workintry_print_default_color_css();       
        wp_enqueue_script('wp-util');
        wp_enqueue_script('plupload');
        wp_enqueue_style( 'cl-dashboard' );
        wp_enqueue_script( 'cl-dashboard' );
        wp_enqueue_script( 'scrollbar' );
        wp_enqueue_style( 'scrollbar' );       
        wp_enqueue_style( 'cl-dash-responsive' );
    }

    //Enqueue Detail Page File
    if( is_singular( 'workintry' ) ){
        wp_enqueue_script('wp-util');
        wp_enqueue_script('plupload');
        wp_enqueue_script('owl-carousel');
        wp_enqueue_style( 'owl-carousel' );
        wp_enqueue_style( 'lightgallery' );
        wp_dequeue_script('workintry-script');
        wp_enqueue_script( 'lightgallery' );
        wp_enqueue_script( 'jRate' );
        wp_enqueue_script('workintry-script');
        //Front end Style
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), '');      
        //Single page styling
        wp_enqueue_style( 'gig-single', CSC_WORKINTRY_PLUGIN_URL .'assets/css/gig-single.css', array(), '');              
        wp_add_inline_style('workintry', $cl_dynamic_css);  
        wp_enqueue_style( 'themify-icons', CSC_WORKINTRY_PLUGIN_URL .'assets/css/themify-icons.css', array(), '');              
    }

    if( is_page_template( 'user-ads.php' ) ){
        wp_enqueue_script('wp-util');
        wp_enqueue_script('plupload');
        wp_enqueue_script('owl-carousel');
        wp_enqueue_style( 'owl-carousel' );
        wp_enqueue_style( 'lightgallery' );
        wp_dequeue_script('workintry-script');
        wp_enqueue_script( 'lightgallery' );
        wp_enqueue_script( 'jRate' );
        wp_enqueue_script('workintry-script');
        //Front end Style
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), '');      
        //Single page styling
        wp_enqueue_style( 'gig-single', CSC_WORKINTRY_PLUGIN_URL .'assets/css/gig-single.css', array(), '');              
        wp_add_inline_style('workintry', $cl_dynamic_css);  
        wp_enqueue_style( 'themify-icons', CSC_WORKINTRY_PLUGIN_URL .'assets/css/themify-icons.css', array(), '');  
    }
    wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), '');      
    wp_add_inline_style('workintry', $cl_dynamic_css); 
    wp_register_style( 'cl-responsive', CSC_WORKINTRY_PLUGIN_URL .'assets/css/responsive.css', array(), ''); 
    wp_enqueue_style( 'cl-responsive' );
}
add_action( 'wp_enqueue_scripts', 'codesquare_workintry_scripts_handler' );
 
/**
 * @Enque Google Fonts
 * @return
 */
if (!function_exists('codesquare_workintry_enqueue_google_fonts')) {
    function codesquare_workintry_enqueue_google_fonts() {       
        //Check protocol
        $protocol = is_ssl() ? 'https' : 'http';
        
        //Default plugin font families
        $font_families  = array();
        //We are only having needed fonts to save loading time
        if( !is_page_template( 'dashboard.php' ) ){
            $font_families[] = 'Open+Sans:300,400,600,700,800';
            $font_families[] = 'Work+Sans:300,400,500,600,700';
            $font_families[] = 'Open+Sans|Merriweather+Sans:ital,wght@0,400;0,700;1,700';
        }
        //We need different font for dashboard 
        if( is_page_template( 'dashboard.php' ) ){
            $font_families[] = 'Nunito:400,700';
            $font_families[] = 'Source+Sans+Pro:400,700';       
        }        

        $query_args = array (
             'family' => implode('%7C' , $font_families) ,
             'subset' => 'latin,latin-ext' ,
        );

        $plugin_fonts = add_query_arg($query_args , $protocol.'://fonts.googleapis.com/css');

        wp_enqueue_style('workintry_default_google_fonts' , esc_url_raw($plugin_fonts), array () , null);
    }
    add_action('wp_enqueue_scripts' , 'codesquare_workintry_enqueue_google_fonts');
}

//Load Admin CSS
function codesquare_workintry_load_custom_wp_admin_style($hook) {
        // Load only on admin side       
        wp_enqueue_style( 'cl-admin', CSC_WORKINTRY_PLUGIN_URL .'admin/css/cl-admin-css.css', array(), '');
        wp_enqueue_script( 'admin-scripts', CSC_WORKINTRY_PLUGIN_URL .'assets/js/admin.js', 'post', '', true);
        wp_localize_script('admin-scripts', 'custom_vars', array(
            'ajaxurl'       => admin_url('admin-ajax.php'),        
        ));    
        //Earnings Scripts
        if( $hook != 'toplevel_page_earnings-page' ) {
            return;
        }
        //Add earnings scripts/styles
        
}
add_action( 'admin_enqueue_scripts', 'codesquare_workintry_load_custom_wp_admin_style' );

//Load plugin textdomain.
add_action( 'init', 'codesquare_workintry_addon_load_textdomain' );
function codesquare_workintry_addon_load_textdomain() {
	load_plugin_textdomain( 'workintry', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

/*
* Add new Role
*/
if (!function_exists('codesquare_workintry_add_user_roles')) {

    function codesquare_workintry_add_user_roles() {

        $workintry = add_role('workintry', esc_html__('workintry', 'workintry') );
    }

    add_action('admin_init', 'codesquare_workintry_add_user_roles');
}

//Allow uploads
if (!function_exists('codesquare_workintry_allow_uploads')) {
	add_action('admin_init', 'codesquare_workintry_allow_uploads');

	function codesquare_workintry_allow_uploads() {

		//redirect if admin side and role is workintry
		if (is_admin() 
			&& ( current_user_can('workintry') ) 
			&& !( defined('DOING_AJAX') && DOING_AJAX )
		) {
			wp_redirect(home_url('/'));
		}

		//Workintry users
		$workintry = get_role('workintry');
		$workintry->add_cap('upload_files');
		$workintry->add_cap('publish_posts');
		$workintry->add_cap('edit_posts');
		$workintry->add_cap('edit_published_posts');
		$workintry->add_cap('edit_others_posts');
		$workintry->add_cap('delete_posts');
		$workintry->add_cap('delete_others_posts');
		$workintry->add_cap('delete_published_posts');
		$workintry->add_cap('publish_pages');
		$workintry->add_cap('edit_pages');
		$workintry->add_cap('edit_published_pages');
		$workintry->add_cap('edit_others_pages');	
	}
}

//Get Full Name
if (!function_exists('codesquare_workintry_get_full_username')) {

    function codesquare_workintry_get_full_username($user_id = '') {
        if (empty($user_id)) {
            return esc_html__('unnamed', 'workintry');
        }

        //Get User Data
        $userdata = get_userdata($user_id);
        $user_role = '';
        if (!empty($userdata->roles[0])) {
            $user_role = $userdata->roles[0];
        }
		
		//Extract from Userdata        
        if (!empty($userdata->first_name) && !empty($userdata->last_name)) {
            return $userdata->first_name . ' ' . $userdata->last_name;
        } else if (!empty($userdata->first_name) && empty($userdata->last_name)) {
            return $userdata->first_name;
        } else if (empty($userdata->first_name) && !empty($userdata->last_name)) {
            return $userdata->last_name;
        } else {
            return $userdata->user_login;
        }
        
    }
}

//Get option
if( !function_exists( 'codesquare_workintry_get_settings_option' ) ){
	function codesquare_workintry_get_settings_option( $option_name = '' ){
		if( empty( $option_name ) ){
			return;
		}
		$workintry = get_option('workintry');
		$option = !empty( $workintry[$option_name] ) ? $workintry[$option_name] : '';
		return $option;
	}
}

/**
 * @Pagination
 * @return 
 */
if (!function_exists('codesquare_workintry_print_pagination_hmtl')) {

    function codesquare_workintry_print_pagination_hmtl($total = '', $step = 4) {

        if ( is_tax('ad_category') || is_tax('ad_country') || is_tax('ad_city') || is_tax('gig_category') ) {

            //Query
            global $paged;
            $pg_page = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
            $pg_paged = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var


            if (!empty($_GET['current_page'])) {
                $pg_paged = $_GET['current_page'];
            }

            //paged works on single pages, page - works on homepage
            $paged = max($pg_page, $pg_paged);

            $current_page = $paged;
            if ($total == '') {
                global $wp_query;
                $total = $wp_query->max_num_pages;
                if (!$total) {
                    $total = 1;
                }
            } else {
                $total = ceil($total / $step);
            }

            if (1 != $total) {
                echo "<nav class='hp-pagination'><ul>";

                if ($current_page > 1) {
                    echo "<li class='hp-prevpage'><a href='?current_page=" . ($current_page - 1) . "'>".esc_html__('Previous', 'workintry')."<i class=\"ti-arrow-left\"></i></a></li>";
                }
                for ($i = 1; $i <= $total; $i++) {
                    if (1 != $total && (!( $i >= $current_page + $step + 1 || $i <= $current_page - $step - 1 ) )) {
                        echo ( $paged == $i ) ? "<li class=\"hp-nextpage\"><a href='javascript:;'>" . $i . "</a></li>" : "<li><a href='?current_page=" . ($i) . "' class=\"inactive\">" . $i . "</a></li>";
                    }
                }
                if ($current_page < $total) {
                    echo "<li class='hp-nextpage'><a href=\"?current_page=" . ($current_page + 1) . "\"><i class=\"ti-arrow-right\"></i></a></li>";
                }

                echo "</ul></nav>";
            }
        } else {

            //Query
            global $paged;
            $pg_page = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
            $pg_paged = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
            //paged works on single pages, page - works on homepage
            $paged = max($pg_page, $pg_paged);

            $current_page = $paged;
            if ($total == '') {
                global $wp_query;
                $total = $wp_query->max_num_pages;
                if (!$total) {
                    $total = 1;
                }
            } else {
                $total = ceil($total / $step);
            }


            if (1 != $total) {
                 echo "<nav class='hp-pagination'><ul>";

                if ($current_page > 1) {
                    echo "<li class='hp-prevpage'><a href='" . get_pagenum_link($current_page - 1) . "'><i class=\"ti-arrow-left\"></i></a></li>";
                }

                for ($i = 1; $i <= $total; $i++) {
                    if (1 != $total && (!( $i >= $current_page + $step + 1 || $i <= $current_page - $step - 1 ) )) {
                        echo ( $paged == $i ) ? "<li class=\"cl-page-active\"><a href='javascript:;'>" . $i . "</a></li>" : "<li><a href='" . get_pagenum_link($i) . "' class=\"inactive\">" . $i . "</a></li>";
                    }
                }

                if ($current_page < $total) {
                    echo "<li class='hp-nextpage'><a href=\"" . get_pagenum_link($current_page + 1) . "\"><i class=\"ti-arrow-right\"></i></a></li>";
                }

                echo "</ul></nav>";
            }
        }
    }

}

/**
 * @Print class
 * @return {}
 */
if( !function_exists( 'codesquare_workintry_print_active_class' ) ){
    function codesquare_workintry_print_active_class( $class = '', $active = '' ){
        if( $class == $active ){
            return 'select';
        }
        return '';
    }
}

/**     
 * @Dashboard Menu
 * @Returns Dashoboard Menu
 */
if( !function_exists( 'codesquare_workintry_Print_Profile_Menu' ) ){
    function codesquare_workintry_Print_Profile_Menu($menu_type = "pc-sidebarnav") {
        global $current_user;            
        $user_identity   = $current_user->ID;

        $url_identity = $user_identity;
        if (isset($_GET['identity']) && !empty($_GET['identity'])) {
            $url_identity = $_GET['identity'];
        }
        
        $menu_list  = apply_filters('codesquare_workintry_get_dashboard_menu_list','default');            
        ob_start();
        ?>
        <ul class="<?php echo esc_attr($menu_type); ?>">
            <?php 
                if ($url_identity == $user_identity) {
                    if( !empty( $menu_list ) ){
                        foreach($menu_list as $key => $value){
                            if( file_exists( codesquare_workintry_addon_template_exsits('workintry/front-end/user-menu-templates/user-menu-'.$key) ) ){
                                include codesquare_workintry_addon_template_exsits('workintry/front-end/user-menu-templates/user-menu-'.$key );
                            }
                        }
                    }
                } 
            ?>
        </ul>
        <?php
        echo ob_get_clean();
    }
}

/**
 * @profile Menu
 * @Returns Menu Navigation
 */
if( !function_exists( 'codesquare_workintry_Print_Profile_Menu_Html' ) ){
    function codesquare_workintry_Print_Profile_Menu_Html() {
        ob_start();
        ?>                    
            <div class="pc-sidebarnavholder">
                <?php codesquare_workintry_Print_Profile_Menu('pc-sidebarnav'); ?>
            </div> 
        <?php
        echo ob_get_clean();
    }
}

/**
 * @Generates Link
 * @Link 
 */
if( !function_exists( 'codesquare_workintry_profile_menu_link' ) ){
    function codesquare_workintry_profile_menu_link($profile_page = '', $slug = '', $user_identity = '', $return = false, $source = '', $id = '') {
        if ( empty( $profile_page ) ) {
            $permalink = home_url('/') . '?author=' . $user_identity;
        } else {                
            $query_arg['rule'] = urlencode($slug);

            //source append
            if (!empty($source)) {
                $query_arg['source'] = urlencode($source);
            }
            
            //to append id of the post
            if (!empty($id)) {
                $query_arg['id'] = urlencode($id);
            }

            $query_arg['identity'] = urlencode($user_identity);

            //Append args to query
            $permalink = add_query_arg(
                $query_arg, $profile_page
            );            
        }       
        if ($return) {
            return esc_url( $permalink );
        } else {
            echo esc_url( $permalink );
        }
    }
}

//Adding custom templates for slider and portfolio single page
add_filter('template_include', 'codesquare_workintry_single_page_templates');
function codesquare_workintry_single_page_templates( $template ){
  // if workintry signgle page
  if( is_singular('workintry')) {
    $template = codesquare_workintry_addon_template_exsits('workintry/templates/single-workintry');
  } 
  return $template;
}

//Add extra profile fields
function codesquare_workintry_extra_user_fields($profile_fields) {

    // Add new fields
    $profile_fields['twitter']      = esc_html__('Twitter', 'workintry');
    $profile_fields['facebook']     = esc_html__('Facebook', 'workintry');
    $profile_fields['google']        = esc_html__('Google+', 'workintry');
    $profile_fields['pinterest']    = esc_html__('Pinterest', 'workintry');
    $profile_fields['linkedin']     = esc_html__('Linkedin', 'workintry');
    $profile_fields['phone']        = esc_html__('Phone', 'workintry');
    $profile_fields['gender']       = esc_html__('Gender', 'workintry');
    $profile_fields['address']      = esc_html__('Address', 'workintry');

    return $profile_fields;
}
add_filter('user_contactmethods', 'codesquare_workintry_extra_user_fields');

function codesquare_workintry_body_search_class( $classes ) {
    if( is_page_template('ads-search.php') )
        $classes[] = 'cl-search';

    return $classes;
}
add_filter( 'body_class', 'codesquare_workintry_body_search_class' );

//Ad total comments
if( !function_exists('codesquare_workintry_get_comment_average_ratings') ){
    function codesquare_workintry_get_comment_average_ratings( $id ) {
        $comments = get_approved_comments( $id );
        if ( $comments ) {
            $i = 0;
            $total = 0;
            foreach( $comments as $comment ){
                $rate = get_comment_meta( $comment->comment_ID, 'rating', true );
                if( isset( $rate ) && '' !== $rate ) {
                    $i++;
                    $total += $rate;
                }
            }

            if ( 0 === $i ) {
                return false;
            } else {
                return round( $total / $i, 1 );                 
            }
        } else {
            return false;
        }
    }
}

//Get average rating of a user [it will give average rating of a user]
if( !function_exists('codesquare_workintry_get_comment_average_ratings_of_user') ){
    function codesquare_workintry_get_comment_average_ratings_of_user( $id ) {
        $args = array(
            'post_type' => 'workintry',
            'post_status' => 'publish',
            'author' => $id,
            'posts_per_page' => -1
        );
        $posts = new WP_Query( $args );
        $post_ids = array();
        if( $posts->have_posts() ){
            while ( $posts->have_posts() ) {               
                $posts->the_post();                
                $post_id = get_the_ID();
                $post_ids[] = $post_id;
            } wp_reset_postdata();
        }       
        $comments_count = 0;
        $ratings = 0;
        if( !empty( $post_ids ) ){
            foreach ( $post_ids as $key => $value ) {
                $comments = get_approved_comments( $value );
                if ( $comments ) {                   
                    $comments_count = $comments_count + count( $comments );
                    $i = 0;
                    $total = 0;
                    foreach( $comments as $comment ){
                        $rate = get_comment_meta( $comment->comment_ID, 'rating', true );
                        if( isset( $rate ) && '' !== $rate ) {
                            $i++;
                            $total += $rate;
                            $ratings = $ratings + $rate;
                        }
                    }                    
                }
                
            }           
                 
            //Now we have total comments and ratings
            if( !empty( $comments_count ) && !empty( $ratings ) ){                
                return round( $ratings / $comments_count, 1 );   
            } else {
                return false;
            }
        } else {
            return false;
        }        
    }
}

//Get average rating of a user [it will give average rating of a user]
if( !function_exists('codesquare_workintry_get_comment_total__ratings_of_user') ){
    function codesquare_workintry_get_comment_total__ratings_of_user( $id ) {
        $args = array(
            'post_type' => 'workintry',
            'post_status' => 'publish',
            'author' => $id,
            'posts_per_page' => -1
        );
        $posts = new WP_Query( $args );
        $post_ids = array();
        if( $posts->have_posts() ){
            while ( $posts->have_posts() ) {               
                $posts->the_post();                
                $post_id = get_the_ID();
                $post_ids[] = $post_id;
            } wp_reset_postdata();
        }       
        $comments_count = 0;
        $ratings = 0;
        if( !empty( $post_ids ) ){
            foreach ( $post_ids as $key => $value ) {
                $comments = get_approved_comments( $value );
                if ( $comments ) {                   
                    $comments_count = $comments_count + count( $comments );
                    $i = 0;
                    $total = 0;
                    foreach( $comments as $comment ){
                        $rate = get_comment_meta( $comment->comment_ID, 'rating', true );
                        if( isset( $rate ) && '' !== $rate ) {
                            $i++;
                            $total += $rate;
                            $ratings = $ratings + $rate;
                        }
                    }                    
                }
                
            }           
                 
            //Now we have total comments and ratings
            if( !empty( $comments_count ) && !empty( $ratings ) ){              
                return $comments_count;   
            } else {
                return false;
            }
        } else {
            return false;
        }        
    }
}

//Get total ratings count [it will give all ratings on given post]
if( !function_exists('codesquare_workintry_get_comment_total_ratings') ){
    function codesquare_workintry_get_comment_total_ratings( $id ) {
        $comments = get_approved_comments( $id );
        if ( $comments ) {
            $i = 0;           
            foreach( $comments as $comment ){
                $rate = get_comment_meta( $comment->comment_ID, 'rating', true );
                if( isset( $rate ) && '' !== $rate ) {
                    $i++;                    
                }
            }

            if ( 0 === $i ) {
                return false;
            } else {
                return $i;                 
            }
        } else {
            return false;
        }
    }
}
//Author/Company Image
if( !function_exists( 'codesquare_workintry_provide_author_thumbnail') ){
    function codesquare_workintry_provide_author_thumbnail($author_id = '', $width = '', $height = '' ){
        if( empty( $author_id ) ){
            return '';
        }
        $width  = !empty( $width ) ? $width : 100;
        $height = !empty( $height ) ? $height : 100;
        $profile_images = get_user_meta( $author_id, 'profile_image', true);
        $images         = !empty( $profile_images['image_data'] ) ? $profile_images['image_data'] : array();
        $profile_id     = !empty( $profile_images['default_image'] ) ? $profile_images['default_image'] : '';
        $profile_image  = !empty( $profile_id ) ? wp_get_attachment_image_url( $profile_id, array($width, $height), true, true ) : CSC_WORKINTRY_PLUGIN_URL .'assets/images/'.$width.'X'.$height.'.jpg';
        return $profile_image;
    }
}

//System default currency
if( !function_exists( 'codesquare_workintry_default_system_currency_sign' ) ){
    function codesquare_workintry_default_system_currency_sign(){
        $currency_sign = codesquare_workintry_get_settings_option('cl_default_currency');
        $currency_sign = !empty( $currency_sign ) ? $currency_sign : '$';
        return $currency_sign;
    }
}

// Register Custom Post Status
if( !function_exists( 'codesquare_workintry_register_custom_ad_post_status' ) ){
    function codesquare_workintry_register_custom_ad_post_status(){
        register_post_status( 'Sold', array(
            'label'                     => _x( 'Sold', array('workintry') ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Sold <span class="count">(%s)</span>', 'Sold <span class="count">(%s)</span>' ),
        ) );
    }
    add_action( 'init', 'codesquare_workintry_register_custom_ad_post_status' );
}

// Register Custom Product Post Status
if( !function_exists( 'codesquare_workintry_register_custom_product_post_status' ) ){
    function codesquare_workintry_register_custom_product_post_status(){
        register_post_status( 'Package', array(
            'label'                     => _x( 'Package', 'product' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Package <span class="count">(%s)</span>', 'Package <span class="count">(%s)</span>' ),
        ) );
    }
    add_action( 'init', 'codesquare_workintry_register_custom_product_post_status' );
}

//Show custom post status for ads posts
add_action( 'post_submitbox_misc_actions', 'codesquare_workintry_show_ad_post_custom_status' );
    function codesquare_workintry_show_ad_post_custom_status(){
    global $post;
    //only when editing a post
    if( $post->post_type == 'workintry' ){

        // custom post status: approved
        $complete = '';
        $label = '';   

        if( $post->post_status == 'sold' ){
            $complete = 'selected=\"selected\"';
            $label = '<span id=\"post-status-display\"> Sold</span>';
        }

        $script = 
        'jQuery(document).ready(function($){'.
            '$("select#post_status").append('.
                 '"<option value=\"sold\" '.$complete.'>'.
                     'Sold'.
                 '</option>"'.
            ');'.
            '$("#post-status-display").append("'.$label.'");'.
        '});';
        wp_add_inline_script('post', $script, 'after');
    }

    //Product
    if( $post->post_type == 'product' ){

        // custom post status: approved
        $complete = '';
        $label = '';   

        if( $post->post_status == 'package' ){
            $complete = 'selected=\"selected\"';
            $label = '<span id=\"post-status-display\"> Package</span>';
        }

        $script = 
        'jQuery(document).ready(function($){'.
            '$("select#post_status").append('.
                 '"<option value=\"package\" '.$complete.'>'.
                     'Package'.
                 '</option>"'.
            ');'.
            '$("#post-status-display").append("'.$label.'");'.
        '});';
        wp_add_inline_script('post', $script, 'after');
    }
}
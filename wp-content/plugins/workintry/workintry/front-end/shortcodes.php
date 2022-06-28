<?php 
/*SHORTCODES*/
/**
 * Banner
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_banner_shortcode' ) ){
    function codesquare_workintry_print_banner_shortcode( $atts ) {
        $workintry = shortcode_atts( array(                
            'id'                    => '',                    
        ), $atts );  
        ob_start();       
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'themify-icons', CSC_WORKINTRY_PLUGIN_URL .'assets/css/themify-icons.css', array(), ''); 
        extract( $workintry );                            
        if( empty( $id ) ){
            esc_html_e('Banner ID missing', 'workintry');            
        } else{
        $post_type  = 'workintry_banner';        
            codesquare_workintry_load_default_banner_shortcode( $post_type, $id );
        }     
        return ob_get_clean();
    }
    add_shortcode( 'banner_shortcode', 'codesquare_workintry_print_banner_shortcode' );
}
/*Default Banner Form*/
if( !function_exists( 'codesquare_workintry_load_default_banner_shortcode' ) ){
    function codesquare_workintry_load_default_banner_shortcode( $post_type, $post_id ){
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'home-styles', CSC_WORKINTRY_PLUGIN_URL .'assets/css/home.css', array(), '');
        $class_name = 'cp-haslayout';    
        $width = '';    
        if( $width == 'full'){
            $class_name = 'cp-full-with-with-container';
        } elseif( $width == 'box' ){
            $class_name = 'cp-haslayout';
        }
        ob_start();
        $rand_id = rand(5, 8);    
        $sub_title = get_post_meta( $post_id, 'sub_title', true );
        $title      = get_post_meta( $post_id, 'title', true );
        $description = get_post_meta( $post_id, 'description', true );
        $show_form = get_post_meta( $post_id, 'show_form', true );
        $gallery = get_post_meta( $post_id, 'gallery' );
        $image = get_post_meta( $post_id, 'images' );
        $full_image = CSC_WORKINTRY_PLUGIN_URL .'assets/images/banner.png';
        if( !empty( $gallery ) ){
            foreach ( $gallery as $key => $value ) {
                $full_image = wp_get_attachment_image_src( $value, 'full' );
                $thum_image = wp_get_attachment_image_src( $value, 'full' );  
                if( !empty( $full_image[0] ) ){
                    $full_image = $full_image[0];
                } else {
                    $full_image = CSC_WORKINTRY_PLUGIN_URL .'assets/images/banner.png';
                }
            }
        }      
        //Image
        $right_image = '';
        if( !empty( $image ) ){
            foreach ( $image as $key => $value ) {
                $right_image = wp_get_attachment_image_src( $value, 'full' );
                $right_image = wp_get_attachment_image_src( $value, 'full' );  
                if( !empty( $right_image[0] ) ){
                    $right_image = $right_image[0];
                } else {
                    
                }
            }
        }        

        //Categories 
        $categories = get_post_meta( $post_id, 'categories', true );
        //Search URl
        $search_url = codesquare_workintry_get_settings_option('homes_search_page');
        $search_url = !empty( $search_url ) ? get_the_permalink( $search_url ) : '';
        $selected_category = isset( $_GET['category'] ) && !empty( $_GET['category'] ) ? sanitize_text_field($_GET['category']) : '';
       
        ?>
        <!-- Workintry banner -->
        <div class="cp-full-with-with-container">        
        <div class="wi-banner-wrap" style="background: url(<?php echo esc_url( $full_image ); ?>)">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 wi-bannerhomecontent">
                        <div class="wi-bannercontent">
                            <?php if( !empty( $sub_title ) || !empty( $title ) || !empty( $description ) ){ ?>
                            <div class="wi-bannertitle">
                                <?php if( !empty( $sub_title ) || !empty( $title ) ){ ?>
                                <h1> <?php if( !empty( $title )){ echo esc_html( $title ); } ?> 
                                <span><?php if( !empty( $sub_title )){ ?><em><?php echo esc_html( $sub_title ); ?></em> <?php } ?></span> </h1>
                                <?php } ?>
                                <?php if( !empty( $description ) ) {?><p><?php echo esc_html( $description ); ?></p><?php } ?>
                            </div>
                            <?php } ?>
                             <?php if( $show_form == 'yes' ){ ?>           
                            <form class="wi-form wi-bannerform" method="get" action="<?php echo esc_url( $search_url ); ?>">
                                <fieldset>
                                    <div class="form-wrap">
                                        <div class="form-group">
                                            <?php 
                                            $keyword = isset( $_GET['keyword'] ) && !empty( $_GET['keyword'] ) ? sanitize_text_field($_GET['keyword']) : '';
                                            ?>
                                                <input type="text" name="keyword" class="form-control" placeholder="<?php esc_attr_e('Search with keyword you like', 'workintry'); ?>" value="<?php echo esc_attr( $keyword ); ?>">      
                                            <?php
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <span class="wi-select">
                                                <?php do_action('codesquare_workintry_print_taxonomy_options', 'gig_category', esc_html__('Select Category', 'workintry'),'category', $selected_category); ?>
                                            </span>
                                        </div>        
                                        <div class="form-group wi-btns">
                                            <a href="javascript:void(0);" class="wi-btn"><?php esc_html_e('Search Now', 'workintry'); ?></a>
                                        </div>     
                                    </div>
                                </fieldset>
                            </form>   
                            <?php 
                                $script = "jQuery(document).ready(function(){
                                jQuery(document).on('click', '.wi-banner-wrap .wi-btn', function(e) {
                                        e.preventDefault();
                                        jQuery(this).closest('form').submit();
                                    });
                                });";
                                wp_add_inline_script('workintry-script', $script,'after');
                            ?>         
                            <?php } ?>    
                            <?php if( !empty( $categories ) ){ ?>       
                                <div class="wi-bannertags">
                                    <ul>
                                        <li>
                                            <span><?php esc_html_e('Top Searches:', 'workintry'); ?></span>
                                        </li>
                                        <?php 
                                        $categories = explode(',', $categories );          
                                        foreach ( $categories as $value) {            
                                            $term_data = get_term( $value );
                                            $link = get_term_link( $term_data->term_id, 'gig_category' );
                                           ?>
                                           <li><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $term_data->name ); ?></a></li>
                                           <?php 
                                        }
                                        ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if( !empty( $right_image ) ){ ?>
                    <div class="col-lg-6 wi-bannerhomeimg">
                        <figure class="wi-banner-img">
                            <img src="<?php echo esc_url( $right_image ); ?>" alt="img">
                        </figure>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>        
        </div>
        <!-- ==== Banner End ==== -->
        <!-- Workintry Banner Ends -->
        <div class="clearfix"></div>
        <?php 
        echo ob_get_clean();        
    }
}
/**
 * Clients
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_clients_shortcode' ) ){
    function codesquare_workintry_print_clients_shortcode( $atts ){
        $workintry = shortcode_atts( array(                
            'id'   => '',                    
        ), $atts );  
        ob_start();       
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'themify-icons', CSC_WORKINTRY_PLUGIN_URL .'assets/css/themify-icons.css', array(), ''); 
        extract( $workintry );                            
        if( empty( $id ) ){
            esc_html_e('ID missing', 'workintry');            
        } else{
        $post_type  = 'workintry_banner';        
            codesquare_workintry_load_default_clients_shortcode( $post_type, $id );
        }     
        return ob_get_clean();
    }
    add_shortcode( 'workintry_clients', 'codesquare_workintry_print_clients_shortcode' );
}
/**
 * Clients
 * HTML
 */
if( !function_exists( 'codesquare_workintry_load_default_clients_shortcode' ) ){
    function codesquare_workintry_load_default_clients_shortcode( $post_type = '', $post_id = '' ){
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'home-styles', CSC_WORKINTRY_PLUGIN_URL .'assets/css/home.css', array(), '');
        $class_name = 'cp-haslayout';    
        $width = '';    
        if( $width == 'full'){
            $class_name = 'cp-full-with-with-container';
        } elseif( $width == 'box' ){
            $class_name = 'cp-haslayout';
        }
        ob_start(); 
        $clients = get_post_meta( $post_id, 'clients' );        
        if( !empty( $clients ) ){
            ?>
            <!-- ==== Sponsors Start ==== -->
            <div class="wi-sponsors">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <ul class="wi-sponsorslist">
                            <?php 
                                foreach ($clients as $key => $value) {
                                    $right_image = wp_get_attachment_image_src( $value, 'full' );
                                    $right_image = wp_get_attachment_image_src( $value, 'full' );  
                                    if( !empty( $right_image[0] ) ){
                                        $right_image = $right_image[0];
                                    } else {
                                        
                                    }
                                    
                               ?> 
                                <li><a href="javascript:void(0);"><img src="<?php echo esc_url( $right_image ); ?>" alt="img"></a></li>
                            <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ==== Sponsors End ==== -->
            <?php 
        }
        echo ob_get_clean();
    }
}
/**
 * Categories
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_categories_shortcode' ) ){
    function codesquare_workintry_print_categories_shortcode( $atts ){
        $workintry = shortcode_atts( array(                
            'id'   => '',                    
        ), $atts );  
        ob_start();       
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'themify-icons', CSC_WORKINTRY_PLUGIN_URL .'assets/css/themify-icons.css', array(), ''); 
        extract( $workintry );                            
        if( empty( $id ) ){
            esc_html_e('ID is missing', 'workintry');            
        } else{
        $post_type  = 'workintry_banner';        
            codesquare_workintry_load_default_categories_shortcode( $post_type, $id );
        }     
        return ob_get_clean();
    }
    add_shortcode( 'workintry_categories', 'codesquare_workintry_print_categories_shortcode' );
}
/**
 * Categoris
 * HTML
 */
if( !function_exists( 'codesquare_workintry_load_default_categories_shortcode' ) ){
    function codesquare_workintry_load_default_categories_shortcode( $post_type = '', $post_id = '' ){
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'home-styles', CSC_WORKINTRY_PLUGIN_URL .'assets/css/home.css', array(), '');
        wp_enqueue_style( 'owl-carousel', CSC_WORKINTRY_PLUGIN_URL .'assets/css/owl.carousel.min.css', array(), '');
        wp_enqueue_script('owl-carousel', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/owl.carousel.min.js', array( 'jquery' ), '', true);
        $class_name = 'cp-haslayout';    
        $width = '';    
        if( $width == 'full'){
            $class_name = 'cp-full-with-with-container';
        } elseif( $width == 'box' ){
            $class_name = 'cp-haslayout';
        }
        ob_start(); 
        $title = get_post_meta( $post_id, 'cat_title', true );
        $description = get_post_meta( $post_id, 'cat_desc', true );
        $btn_title = get_post_meta( $post_id, 'cat_btn_title', true );
        $btn_link = get_post_meta( $post_id, 'cat_btn_link', true );
        $categories = get_post_meta( $post_id, 'cat_categories', true );
        ?>
        <section class="wi-servicewrap wi-section-wrap">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="wi-serviceholder">
                                    <?php 
                                    if( !empty( $title ) ||
                                    !empty( $description ) ||
                                    !empty( $btn_title ) || 
                                    !empty( $btn_link ) ){ ?>
                                    <div class="wi-sectiontitle">
                                        <?php if( !empty( $title ) ){ ?>
                                        <h2><?php echo esc_html( $title ); ?></h2>
                                        <?php } ?>
                                        <?php if( !empty( $description ) ){ ?>
                                        <p><?php echo esc_html( $description ); ?></p>
                                        <?php } ?>
                                        <?php if( !empty( $btn_title ) && !empty( $btn_link ) ){ ?>
                                        <a href="<?php echo esc_url( $btn_link ); ?>" class="wi-btntwo"><?php echo esc_html( $btn_title ); ?></a>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                    <?php if( !empty( $categories ) ){
                                    $categories = explode(',', $categories ); ?>
                                        <div class="wi-serviceslider owl-carousel"> 
                                        <?php foreach ( $categories as $key => $value) {
                                            $term_data = get_term( $value );
                                            $image = get_term_meta( $term_data->term_id, 'image', 'gig_category' );
                                            $image = !empty( $image ) ? wp_get_attachment_image_src( $image, 'cat-img' ) : CSC_WORKINTRY_PLUGIN_URL .'assets/images/category.png';
                                            if( is_array( $image ) ){
                                                $image = $image[0];
                                            }
                                            $link = get_term_link( $term_data->term_id, 'gig_category' );
                                            ?>
                                            <div class="wi-serviceitem">
                                                <figure class="wi-serviceimg">
                                                    <img src="<?php echo esc_url( $image ); ?>" alt="img">
                                                    <figcaption>
                                                        <a href="<?php echo esc_url( $link ); ?>"><i class="ti-arrow-right"></i></a>
                                                    </figcaption>
                                                </figure>
                                                <div class="wi-servicecontent">
                                                    <h3><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $term_data->name ); ?></a></h3>
                                                    <?php if( !empty( $term_data->description ) ){ ?>
                                                    <span><?php echo esc_html( $term_data->description  ); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php 
        echo ob_get_clean();
    }
}
/**
 * Featured Ads 
 * HTML
 */
if( !function_exists( 'codesquare_workintry_featured_ads_shortcode' ) ){
    function codesquare_workintry_featured_ads_shortcode( $atts ) {
        $workintry = shortcode_atts( array(                
            'id'          => '',
        ), $atts );         
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'home-styles', CSC_WORKINTRY_PLUGIN_URL .'assets/css/home.css', array(), ''); 
        extract( $workintry );                                            
        ob_start();
        if( !empty( $id ) ){
            $count = get_post_meta( $id, 'fcount', true );
            $count = !empty( $count ) ? $count : 8;  
            $post_type  = 'workintry';
            codesquare_workintry_print_featured_ads_shortcode( $post_type, $count, $workintry );      
        } else {
            esc_html_e('Select proper shortcode', 'workintry');
        }
        return ob_get_clean();
    }
    add_shortcode( 'workintry_featured_ads', 'codesquare_workintry_featured_ads_shortcode' );
}

/**
 * Normal Ads 
 * HTML
 */
if( !function_exists( 'codesquare_workintry_normal_ads_shortcode' ) ){
    function codesquare_workintry_normal_ads_shortcode( $atts ) {
        $workintry = shortcode_atts( array(                
            'id'                 => '',                    
        ), $atts );         
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'home-styles', CSC_WORKINTRY_PLUGIN_URL .'assets/css/home.css', array(), ''); 
        extract( $workintry );                                   
        ob_start();
        if( !empty( $id ) ){
            $count = get_post_meta( $id, 'ncount', true );
            $count = !empty( $count ) ? $count : 4;  
            $post_type  = 'workintry';
            codesquare_workintry_print_nomral_ads_shortcode( $post_type, $count, $workintry );        
        } else {
            esc_html_e('Select proper shortcode', 'workintry');
        }
        return ob_get_clean();
    }
    add_shortcode( 'workintry_normal_ads', 'codesquare_workintry_normal_ads_shortcode' );
}

/*
* Featured Ads
*/
if( !function_exists( 'codesquare_workintry_print_featured_ads_shortcode' ) ){
    function codesquare_workintry_print_featured_ads_shortcode( $post_type, $count, $workintry ){        
        wp_enqueue_script('owl-carousel');
        wp_enqueue_style( 'owl-carousel' );
        extract( $workintry );
        $current_time = new DateTime();                 
        $current_time_stamp = $current_time->getTimestamp();
        $args['post_type'] = $post_type;
        $args['posts_per_page'] = $count;
        $args['post_status'] = 'publish';
        $args['meta_query'] = array(
            array(
               'key' => 'cl_timestamp',
               'value' => $current_time_stamp,
               'compare' => '>',
            )               
        );
        $ads = new WP_Query( $args );
        $title = get_post_meta( $id, 'ftitle', true );
        $sub_title = get_post_meta( $id, 'fsub_title', true );
        $description = get_post_meta( $id, 'fdescription', true );
        //Search URl
        $search_url = codesquare_workintry_get_settings_option('homes_search_page');
        $search_url = !empty( $search_url ) ? get_the_permalink( $search_url ) : '';
        ob_start(); ?>
        <!-- ==== Top Freelacners Start ==== -->
        <section class="wi-section-wrap">
            <div class="container">
                <div class="row justify-content-center">
                    <?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $description ) ){ ?>
                        <div class="col-12 col-lg-10 push-lg-1 col-xl-8 push-xl-2">
                            <div class="wi-sectiontitle text-center">
                                <?php if( !empty( $sub_title ) ){ ?>
                                <span>
                                    <?php echo esc_html( $sub_title ); ?>
                                </span>
                                <?php } ?>
                                <?php if( !empty( $title ) ){ ?>
                                    <h2><?php echo esc_html( $title ); ?></h2>
                                <?php } ?>
                                <?php if( !empty( $description ) ){ ?>
                                    <p>
                                        <?php echo esc_html( $description ); ?>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if( $ads->have_posts() ){ ?>
                    <div class="wi-freelacners">
                        <?php while( $ads->have_posts() ){
                                $ads->the_post();
                                global $post;
                            ?>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <?php do_action('codesquare_workintry_print_ad_grid', $post->ID, 255, 180); ?>
                            </div>
                            <?php } wp_reset_postdata(); ?>
                            <div class="col-12 wi-sectionbtns">
                                <a href="<?php echo esc_url( $search_url ); ?>" class="wi-btntwo"><?php esc_html_e('View All', 'workintry'); ?></a>
                            </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <!-- ==== Top Freelacners End ==== -->        
        <?php             
        echo ob_get_clean();
    }
}

/**
 * Stats
 * HTML
 */
if( !function_exists( 'codesquare_workintry_stats_shortcode' ) ){
    function codesquare_workintry_stats_shortcode( $atts ) {
        $workintry = shortcode_atts( array(                
            'id'                 => '',                    
        ), $atts );         
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'home-styles', CSC_WORKINTRY_PLUGIN_URL .'assets/css/home.css', array(), ''); 
        wp_enqueue_script('jquery-appear', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/jquery.appear.min.js', array( 'workintry-script' ), '', true);
        
        extract( $workintry );                                   
        ob_start();
        if( !empty( $id ) ){                       
            codesquare_workintry_print_stats_ads_shortcode( $workintry );        
        } else {
            esc_html_e('Select proper shortcode', 'workintry');
        }
        return ob_get_clean();
    }
    add_shortcode( 'workintry_stats', 'codesquare_workintry_stats_shortcode' );
}

/**
 * Stats
 * HTML
 */
if( !function_exists( 'codesquare_workintry_testimonials_shortcode' ) ){
    function codesquare_workintry_testimonials_shortcode( $atts ) {
        $workintry = shortcode_atts( array(                
            'id' => '',                    
        ), $atts );         
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'home-styles', CSC_WORKINTRY_PLUGIN_URL .'assets/css/home.css', array(), '');     
        extract( $workintry );                                   
        ob_start();
        if( !empty( $id ) ){                       
            codesquare_workintry_print_testimonials_shortcode( $workintry );        
        } else {
            esc_html_e('Select proper shortcode', 'workintry');
        }
        return ob_get_clean();
    }
    add_shortcode( 'workintry_testimonials', 'codesquare_workintry_testimonials_shortcode' );
}

/*
* Testimonials 
*/
if( !function_exists( 'codesquare_workintry_print_testimonials_shortcode' ) ){
    function codesquare_workintry_print_testimonials_shortcode( $workintry ){
        extract( $workintry );       
        $t_title        = get_post_meta( $id, 'ttitle', true );
        $tsub_title     = get_post_meta( $id, 'tsub_title', true );
        $tdesc          = get_post_meta( $id, 'tdesc', true );       
        $testimonials   = get_post_meta( $id, 'testimonials', true );
        $bg             = get_post_meta( $id, 'tbgimage', true );
        $bg = !empty( $bg ) ? wp_get_attachment_image_src( $bg , 'full' ) : array();
        $bg = !empty( $bg[0] ) ? $bg[0] : '';
        ob_start(); ?>
        <!-- ==== Our Community Start ==== -->
        <div class="clearfix"></div>
        <section class="wi-section-wrap wi-community-section" <?php if( !empty( $bg ) ){?> style="background: url( <?php echo esc_url( $bg ); ?>)" <?php } ?>>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <?php if( !empty( $t_title ) || !empty( $tsub_title ) || !empty( $tdesc ) ){ ?>
                    <div class="col-12 col-lg-10 push-lg-1 col-xl-6 push-xl-3">
                        <div class="wi-sectiontitle text-center">
                            <?php if( !empty( $tsub_title ) ){ ?>
                                <span><?php echo esc_html( $tsub_title ); ?></span>
                            <?php } ?>
                            <?php if( !empty( $t_title ) ){ ?>
                                <h2><?php echo esc_html( $t_title ); ?></h2>
                            <?php } ?>
                            <?php if( !empty( $tdesc ) ){ ?>
                            <p><?php echo esc_html( $tdesc ); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if( !empty( $testimonials ) ){ ?>
                        <div class="col-12">
                            <div id="wi-communityslider" class="wi-communityslider owl-carousel">
                                 <?php 
                                    foreach ( $testimonials as $key => $value) {                        
                                    $timage = !empty( $value['timage'] ) ? $value['timage'] : array();
                                    $timage = !empty( $timage[0] ) ? wp_get_attachment_image_src( $timage[0] , 'full' ) : array();
                                    $timage = !empty( $timage[0] ) ? $timage[0] : '';
                                    $tlogo = !empty( $value['tlogo'] ) ? $value['tlogo'] : array();
                                    $tlogo = !empty( $tlogo[0] ) ? wp_get_attachment_image_src( $tlogo[0] , 'full' ) : array();
                                    $tlogo = !empty( $tlogo[0] ) ? $tlogo[0] : '';
                                    $name  = !empty( $value['btn_title'] ) ? $value['btn_title'] : '';
                                    $content = !empty( $value['btn_link'] ) ? $value['btn_link'] : '';
                                   
                                ?>
                                <div class="wi-communityitem">
                                    <i class="fa fa-quote-right"></i>
                                    <figure class="wi-communityimg">
                                        <?php if( !empty( $timage ) ){ ?>
                                        <img src="<?php echo esc_url( $timage ); ?>" alt="img">
                                        <?php } ?>
                                        <?php if( !empty( $tlogo ) ){ ?>
                                        <a href="javascript:void(0);" class="wi-socialicon"><img src="<?php echo esc_url( $tlogo ); ?>" alt="img"></a>
                                        <?php } ?>
                                    </figure>
                                    <div class="wi-communityinfo">
                                        <?php if( !empty( $name ) ){ ?>
                                        <h3><a href="javascript:void(0);"><?php echo esc_html( $name ); ?></a></h3>
                                        <?php } ?>
                                        <?php if( !empty( $content ) ){ ?>
                                        <p><?php echo esc_html( $content ); ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <!-- ==== Our Community End ==== -->     
        <?php                    
        echo ob_get_clean();
    }
}

/*
* Stats 
*/
if( !function_exists( 'codesquare_workintry_print_stats_ads_shortcode' ) ){
    function codesquare_workintry_print_stats_ads_shortcode( $workintry ){
        extract( $workintry );
        $image = get_post_meta( $id, 'bg_images', true );
        $image = !empty( $image ) ? wp_get_attachment_image_src( $image , 'full' ) : array();
        $image = !empty( $image[0] ) ? $image[0] : '';
        $stats = get_post_meta( $id, 'stats', true );
        if( !empty( $stats ) ){        
        ob_start(); ?>
            <!-- ==== Counter Start ==== -->
            <section class="wi-counter-wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div id="wi-counter" class="wi-counter" <?php if( !empty( $image ) ){ ?> style="background: url(<?php echo esc_url( $image ); ?>)" <?php } ?>>      
                                <?php 
                                foreach ( $stats as $stat ) {
                                $stat_image = !empty( $stat['stat_image'][0] ) ? $stat['stat_image'][0] : '';                               
                                $stat_image = !empty( $stat_image ) ? wp_get_attachment_image_src( $stat_image , 'full' ) : array();
                                $stat_image = !empty( $stat_image[0] ) ? $stat_image[0] : '';
                                $value = !empty( $stat['value'] ) ? $stat['value'] : '';
                                $figure = !empty( $stat['figure'] ) ? $stat['figure'] : '';
                                $stat = !empty( $stat['stat'] ) ? $stat['stat'] : '';
                               
                                ?>
                                <div class="wi-counteritem">
                                    <?php if( !empty( $stat_image ) ){ ?>
                                    <img src="<?php echo esc_url( $stat_image ); ?>" alt="img">
                                    <?php } ?>
                                    <div class="wi-countercontent">
                                        <?php if( !empty( $value ) ){ ?>
                                        <h3>
                                            <em  data-from="0" data-to="<?php echo esc_attr( $value ); ?>" data-speed="8000" data-refresh-interval="50"><?php echo esc_html( $value ); ?></em><?php if( !empty( $figure ) ){ echo esc_html( $figure ); } ?>
                                        </h3>
                                        <?php } ?>
                                        <?php if( !empty( $stat ) ) { ?>
                                        <span><?php echo esc_html( $stat ); ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ==== Counter End ==== -->          
        <?php             
        }
        echo ob_get_clean();
    }
}

/*
* Nomral Ads
*/
if( !function_exists( 'codesquare_workintry_print_nomral_ads_shortcode' ) ){
    function codesquare_workintry_print_nomral_ads_shortcode( $post_type, $count, $workintry ){        
        wp_enqueue_script('owl-carousel');
        wp_enqueue_style( 'owl-carousel' );
        extract( $workintry );
        $current_time = new DateTime();                 
        $current_time_stamp = $current_time->getTimestamp();
        $args['post_type'] = $post_type;
        $args['posts_per_page'] = $count;
        $args['post_status'] = 'publish';        
        $ads = new WP_Query( $args );
        $title = get_post_meta( $id, 'ntitle', true );
        $sub_title = get_post_meta( $id, 'nsub_title', true );
        $description = get_post_meta( $id, 'ndescription', true );
        //Search URl
        $search_url = codesquare_workintry_get_settings_option('homes_search_page');
        $search_url = !empty( $search_url ) ? get_the_permalink( $search_url ) : '';
        ob_start(); ?>
        <!-- ==== Featued Properties Start ==== -->
         <section class="wi-section-wrap">
            <div class="container">
                <div class="row justify-content-center">
                    <?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $description ) ){ ?>
                        <div class="col-12 col-lg-10 push-lg-1 col-xl-8 push-xl-2">
                            <div class="wi-sectiontitle text-center">
                                <?php if( !empty( $sub_title ) ){ ?>
                                <span>
                                    <?php echo esc_html( $sub_title ); ?>
                                </span>
                                <?php } ?>
                                <?php if( !empty( $title ) ){ ?>
                                    <h2><?php echo esc_html( $title ); ?></h2>
                                <?php } ?>
                                <?php if( !empty( $description ) ){ ?>
                                    <p>
                                        <?php echo esc_html( $description ); ?>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if( $ads->have_posts() ){ ?>
                    <div class="wi-freelacners">
                        <?php while( $ads->have_posts() ){
                                $ads->the_post();
                                global $post;
                            ?>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <?php do_action('codesquare_workintry_print_ad_grid', $post->ID, 255, 180); ?>
                            </div>
                            <?php } wp_reset_postdata(); ?>
                            <div class="col-12 wi-sectionbtns">
                                <a href="<?php echo esc_url( $search_url ); ?>" class="wi-btntwo"><?php esc_html_e('View All', 'workintry'); ?></a>
                            </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <div class="clearfix"></div>
        <!-- ==== Featued Properties End ==== -->      
        <?php             
        echo ob_get_clean();
    }
}

/*
* ABOUT US
*/
/**
 * Clients
 * HTML
 */
if( !function_exists( 'codesquare_workintry_print_about_us_shortcode' ) ){
    function codesquare_workintry_print_about_us_shortcode( $atts ){
        $workintry = shortcode_atts( array(                
            'id'   => '',                    
        ), $atts );  
        ob_start();       
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'themify-icons', CSC_WORKINTRY_PLUGIN_URL .'assets/css/themify-icons.css', array(), ''); 
        extract( $workintry );                            
        if( empty( $id ) ){
            esc_html_e('ID missing', 'workintry');            
        } else{
        $post_type  = 'workintry_banner';        
            codesquare_workintry_load_default_about_us_shortcode( $post_type, $id );
        }     
        return ob_get_clean();
    }
    add_shortcode( 'workintry_about', 'codesquare_workintry_print_about_us_shortcode' );
}
/**
 * ABOUT US
 * HTML
 */
if( !function_exists( 'codesquare_workintry_load_default_about_us_shortcode' ) ){
    function codesquare_workintry_load_default_about_us_shortcode( $post_type = '', $post_id = '' ){
        wp_enqueue_style( 'workintry', CSC_WORKINTRY_PLUGIN_URL .'assets/css/workintry.css', array(), ''); 
        wp_enqueue_style( 'home-styles', CSC_WORKINTRY_PLUGIN_URL .'assets/css/home.css', array(), '');
        $class_name = 'cp-haslayout';    
        $width = '';    
        if( $width == 'full'){
            $class_name = 'cp-full-with-with-container';
        } elseif( $width == 'box' ){
            $class_name = 'cp-haslayout';
        }
        ob_start(); 
        $sub_title      = get_post_meta( $post_id, 'asub_title', true );
        $title          = get_post_meta( $post_id, 'atitle', true );
        $description    = get_post_meta( $post_id, 'adesc', true );
        $abouts         = get_post_meta( $post_id, 'abouts' ); 

        ?>
        <!-- ==== About Us Start ==== -->   
        <section class="wi-section-wrap">
            <div class="container">
                <div class="row justify-content-center">
                    <?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $description ) ){ ?>
                    <div class="col-12 col-lg-10 push-lg-1 col-xl-8 push-xl-2">
                        <div class="wi-sectiontitle text-center">
                            <?php if( !empty( $sub_title ) ){ ?>
                                <span>
                                    <?php echo esc_html( $sub_title ); ?>
                                </span>
                            <?php } ?>
                            <?php if( !empty( $title ) ){ ?>
                                <h2><?php echo esc_html( $title ); ?></h2>
                            <?php } ?>
                            <?php if( !empty( $description ) ){ ?>
                            <p><?php echo esc_html( $description ); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if( !empty( $abouts[0] ) ){ ?>
                    <div class="wi-aboutus-wrap">
                        <?php foreach ( $abouts[0] as $key => $about ) {
                            $atitle = !empty( $about['a_title'] ) ? $about['a_title'] : '';
                            $adesc  = !empty( $about['adesc'] ) ? $about['adesc'] : '';
                            $aimage = !empty( $about['aimage'][0] ) ? $about['aimage'][0] : array();
                            $aimage = !empty( $aimage ) ? wp_get_attachment_image_src( $aimage , 'full' ) : array();
                            $aimage = !empty( $aimage[0] ) ? $aimage[0] : '';
                        ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="wi-aboutus">
                                <?php if( !empty( $aimage ) ){ ?>
                                    <img src="<?php echo esc_url( $aimage ); ?>" alt="">
                                <?php } ?>
                                <?php if( !empty( $atitle ) ){ ?>
                                <h4>
                                    <a href="javascript:void(0);"><?php echo esc_html( $atitle ); ?></a>
                                </h4>
                                <?php } ?>
                                <?php if( !empty( $adesc ) ){ ?>
                                <p><?php echo esc_html( $adesc ); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <!-- ==== About Us End ==== -->       
        <?php         
        echo ob_get_clean();
    }
}
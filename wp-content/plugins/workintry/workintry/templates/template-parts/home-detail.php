<?php
/**
 *
 * Detail Page
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
global $post;
$post_id = $post->ID; 
$cl_type        = get_post_meta( $post->ID, 'cl_type', true );
$cl_type        = !empty( $cl_type ) && $cl_type == 'sell' ? 'sale' : $cl_type;
$date           = get_the_date( 'M j, Y ', $post->ID );
$ad_views       = get_post_meta( $post->ID, 'ad_views', true );
$ad_views       = !empty( $ad_views ) ? $ad_views : 0; 

//Categories
$categories = wp_get_post_terms( $post->ID, array( 'gig_category' ) );

//Timestamp 
$current_time = new DateTime();                 
$current_time_stamp = $current_time->getTimestamp();

//Featured
$featured_stamp = get_post_meta($post->ID, 'cl_timestamp', true);   
$featured_stamp = !empty( $featured_stamp ) ? $featured_stamp : 0;

//Package Settings
$show_p_title   = codesquare_workintry_get_settings_option('p_title');
$show_p_desc    = codesquare_workintry_get_settings_option('p_desc');
//FAQs
$faqs           = get_post_meta( $post_id, 'cl_faq', true ); 
?>
<div class="clearfix"></div>
<!-- Wrapper End --> 
<!-- Workintry -->
<!--*** Main Start ***-->
<main id="wi-main" class="wi-main">
<!-- ==== Search Result Start ==== -->
<section class="wi-freelancersection wi-section-wrap">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-5 col-xl-4">
                <?php require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/author'); ?>
                <?php require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/report'); ?>
            </div>
            <div class="col-12 col-lg-7 col-xl-8 order-first">
                <?php require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/car-slider'); ?>
                <div class="wi-sinlewrap wi-logopassion">
                    <div class="wi-sinletitle">
                        <h2><?php the_title(); ?></h2>
                    </div>
                    <div class="wi-sinlecontent">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="wi-sinlewrap">
                    <?php if( !empty( $show_p_title ) ){ ?>
                        <div class="wi-sinletitle">
                            <h2><?php echo esc_html( $show_p_title ); ?></h2>
                        </div>
                    <?php } ?>
                    <div class="wi-sinlecontent">
                        <?php if( !empty( $show_p_desc ) ){ ?>
                            <p>
                                <?php echo esc_html( $show_p_desc ); ?>
                            </p>
                        <?php } ?>
                        <?php require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/gig-box'); ?>
                    </div>
                </div>
                <?php if( !empty( $faqs ) ){ ?>
                    <div class="wi-sinlewrap">
                        <div class="wi-sinletitle">
                            <h2><?php esc_html_e('Common FAQ\'s', 'workintry' ); ?></h2>
                        </div>
                        <div class="wi-sinlecontent">
                            <div id="accordion">
                                <?php 
                                $counter = 0;
                                foreach ($faqs as $key => $value) {
                                $counter++;        
                                if( $counter == 1 ){
                                    $class = 'show';
                                } else {
                                    $class = 'hide';
                                }
                                if( !empty( $value['title'] ) && !empty( $value['title'] ) ){
                                ?>
                                <div class="wi-faqaccordion">
                                    <div class="wi-titlefaqaccordion" id="headingOne<?php echo esc_attr( $counter ); ?>">
                                        <h5 data-toggle="collapse" data-target="#collapseOne<?php echo esc_attr( $counter ); ?>" aria-expanded="true" aria-controls="collapseOne<?php echo esc_attr( $counter ); ?>"><?php echo esc_html( $value['title'] ); ?> <i class="ti-plus"></i> </h5>
                                    </div>
                                    <div id="collapseOne<?php echo esc_attr( $counter ); ?>" class="wi-collapsewrap collapse <?php echo esc_attr( $class ); ?>" aria-labelledby="headingOne<?php echo esc_attr( $counter ); ?>" data-parent="#accordion">
                                        <div class="wi-faqaccordioninfo">
                                            <p>
                                            <?php echo esc_html( $value['description'] ); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div> 
                                <?php } } ?>                         
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/comments'); ?>
            </div>
        </div>
    </div>
</section>
<!-- ==== Search Result End ==== -->
<?php require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/similar-author'); ?>  
<?php require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/related'); ?>  
</main>
<!--*** Main End ***-->
<!-- Workintry Ends -->   
<?php 
require_once codesquare_workintry_addon_template_exsits('workintry/templates/template-parts/chat-box'); ?>
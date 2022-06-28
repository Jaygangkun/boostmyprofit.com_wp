<?php 
 /* Detail Page Related Ads
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
global $post;
$post_type      = get_post_type( $post->ID );
$show_related   = codesquare_workintry_get_settings_option('show_related');
$search_url     = codesquare_workintry_get_settings_option('search_page');
if( $show_related ){
$related = 'gig_category';
$search_url = codesquare_workintry_get_settings_option('homes_search_page');
$related_terms = wp_get_object_terms($post->ID, $related, array('fields' => 'ids'));
if( is_array( $related_terms ) ){
$args = array(
    'post_type' => 'workintry',
    'post_status' => 'publish',
    'posts_per_page' => 4,
    'order' => 'DESC',
    'orderby' => 'ID',
    'tax_query' => array(
        array(
            'taxonomy' => $related,
            'field' => 'term_id',
            'terms' => $related_terms
        )
    ),
    'post__not_in' => array( $post->ID ),
);
//Search Page URL
$search_url = !empty( $search_url ) ? get_the_permalink( $search_url ) : '';
$related_query = new wp_query($args);
if( $related_query->have_posts() ){ ?>    
    <section class="wi-section-wrap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="wi-offeringtitle">
                        <div class="wi-title">
                            <h2><?php esc_html_e('Similar Gigs', 'workintry'); ?></h2>
                        </div>
                         <a href="<?php echo esc_url( $search_url ); ?>" class="wi-btn"><?php esc_html_e('Show All', 'workintry'); ?></a>
                    </div>
                </div>                
                <div class="wi-freelacners">
                    <?php 
                        while( $related_query->have_posts() ){ 
                        $related_query->the_post();
                        global $post;
                        $post_id    = $post->ID;          
                    ?>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                       <?php do_action('codesquare_workintry_print_ad_grid', $post->ID, 255, 180); ?>
                    </div>
                    <?php } wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </section>  
<?php } } } ?>
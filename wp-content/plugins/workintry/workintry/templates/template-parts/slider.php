<?php 
 /* Detail Page Video
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
global $post;
$post_id = $post->ID;
$gallery = get_post_meta( $post_id, 'cl_galleryc' );
$cl_overlay = codesquare_workintry_get_settings_option('cl_img_overlay');
$show_slider = codesquare_workintry_get_settings_option('show_slider');
$cl_overlay = !empty( $cl_overlay ) ? $cl_overlay : '';
if( $show_slider ){
if( !empty( $gallery ) ){ 
?>
<div class="cp-haslayout cf-gallery-holder">
	<div id="cp-galleryslider1" class="cp-galleryslider owl-carousel">
		<?php 
		foreach ( $gallery as $key => $value ) {
			$full_image = wp_get_attachment_image_src( $value, 'full' );
			$thum_image = wp_get_attachment_image_src( $value, array(500, 400) );
		?>
			<figure class="item" data-src="<?php echo esc_url( $full_image[0] ); ?>">
				<img src="<?php echo esc_url( $thum_image[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
				<?php if( !empty( $cl_overlay ) ){ ?>
					<span class="cp-img-info">
						<?php echo esc_html( $cl_overlay ); ?>	
					</span>
				<?php } ?>
				<a href="javascript:void(0);"><i class="ti-plus"></i></a>
			</figure>
		<?php } ?>
	</div>
</div>
<?php } } //slider
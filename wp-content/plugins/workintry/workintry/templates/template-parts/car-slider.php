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
$cl_overlay = !empty( $cl_overlay ) ? $cl_overlay : '';
if( !empty( $gallery ) ){ 	
?>
<div class="wi-singleslider-wrap">
    <div id="wi-singleslider" class="wi-singleslider owl-carousel">
    	<?php 
			foreach ( $gallery as $value ) {				
				$full_image = wp_get_attachment_image_src( $value, 'home-ad-slider' );
				$thum_image = wp_get_attachment_image_src( $value, 'slide-thumb' );	
			?>					
			<figure class="item" data-src="<?php echo esc_url( $full_image[0] ); ?>">
	            <img src="<?php echo esc_url( $full_image[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
	        </figure>				
		<?php } ?>
    </div>
    <div id="wi-sliderthumbnail" class="wi-sliderthumbnail owl-carousel">
    	<?php 
			foreach ( $gallery as $key => $value ) {
				$full_image = wp_get_attachment_image_src( $value, 'home-ad-slider' );
				$thum_image = wp_get_attachment_image_src( $value, 'slide-thumb' );
		?>
		<div class="item">
			<figure>
				<img src="<?php echo esc_url( $thum_image[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">	
			</figure>				
		</div>
		<?php } ?>
    </div>
</div>			
<?php 
	$script = "jQuery(document).ready(function(){
		function detailSlider(){
	var sync1 = jQuery('#wi-singleslider');
	var sync2 = jQuery('#wi-sliderthumbnail');
	var slidesPerPage = 3;
	var syncedSecondary = true;
	sync1.owlCarousel({
		items : 1,
		loop: true,
		nav: false,
		dots: false,
		autoplay: false,
		video:true,
		videoHeight: 370,
		lazyLoad: true,
		videoWidth: 670,
		slideSpeed : 2000,
		navClass: ['wi-prev', 'wi-next'],
		navContainerClass: 'hp-slider-nav',
		navText: ['<span class=\"ti-arrow-left\"></span>', '<span class=\"ti-arrow-right\"></span>'],
		responsiveRefreshRate : 200,
		autoHeight: true,
	}).on('changed.owl.carousel', syncPosition);
	sync2.on('initialized.owl.carousel', function () {
		sync2.find(\".owl-item\").eq(0).addClass(\"current\");
	})
	.owlCarousel({
		items:10,
		dots: false,
		nav: false,
		margin:10,
		smartSpeed: 200,
		slideSpeed : 500,
		slideBy: slidesPerPage,
		responsiveRefreshRate : 100,
		responsiveClass:true,
	    responsive:{
	        0:{items:4, },
	        640:{items:8},
	        992:{items:10},
	    },
	}).on('changed.owl.carousel', syncPosition2);
	function syncPosition(el) {
		var count = el.item.count-1;
		var current = Math.round(el.item.index - (el.item.count/2) - .5);
		if(current < 0) {
			current = count;
		}
		if(current > count) {
			current = 0;
		}
		sync2
		.find(\".owl-item\")
		.removeClass(\"current\")
		.eq(current)
		.addClass(\"current\")
		var onscreen = sync2.find('.owl-item.active').length - 1;
		var start = sync2.find('.owl-item.active').first().index();
		var end = sync2.find('.owl-item.active').last().index();
		if (current > end) {
			sync2.data('owl.carousel').to(current, 100, true);
		}
		if (current < start) {
			sync2.data('owl.carousel').to(current - onscreen, 100, true);
		}
	}
	function syncPosition2(el) {
		if(syncedSecondary) {
			var number = el.item.index;
			sync1.data('owl.carousel').to(number, 100, true);
		}
	}
	sync2.on(\"click\", \".owl-item\", function(e){
		e.preventDefault();
		var number = jQuery(this).index();
		sync1.data('owl.carousel').to(number, 300, true);
	});
}
detailSlider();

	//Lightgallery now
    var slider_Content = jQuery('.wi-singleslider.owl-carousel');  
        slider_Content.lightGallery({
            selector: '.item'
        });
	});";


	wp_add_inline_script('workintry-script', $script,'after');
?>								
<?php }
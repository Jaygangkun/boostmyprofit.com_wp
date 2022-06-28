<?php 
/**
 *
 * Detail Page Soaicl Share
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
global $post;
?>
<!-- Social Sharing -->
<a href="javascript:void(0)" class="hp-share"><i class="ti-share"></i><?php esc_html_e('Share', 'workintry'); ?>
	<ul class="hp-socialiconsborder">		
		<li class="hp-facebook">
			<a href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>">
				<i class="fab fa-facebook-f"></i>
			</a>
		</li>
		<li class="hp-twitter">
			<a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>&via=[via]&hashtags=[hashtags]">
				<i class="fab fa-twitter"></i>
			</a>
		</li>
		<li class="hp-googleplus">
			<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>">
				<i class="fab fa-google"></i>
			</a>
		</li>
		<li class="hp-pinterestp">
			<a href="https://pinterest.com/pin/create/bookmarklet/?media=&url=<?php the_permalink(); ?>&is_video=&description=<?php the_title(); ?>
		">
				<i class="fab fa-pinterest-p"></i>
			</a>
		</li>
	</ul>
</a>
<!-- Social Sharing Ends -->

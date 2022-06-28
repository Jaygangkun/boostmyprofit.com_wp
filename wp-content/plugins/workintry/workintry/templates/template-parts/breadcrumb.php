<?php 
/*
* Bread Crumb
*/
$search_url     = codesquare_workintry_get_settings_option('search_page');
$search_url 	= codesquare_workintry_get_settings_option('homes_search_page');
$search_url 	= !empty( $search_url ) ? get_the_permalink( $search_url ) : '';
?>
<div class="wi-breadcrumb-section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php if( is_singular( 'workintry' ) ){ ?>
					<div class="wi-breadcrumb-wrap">
						<ol class="wi-breadcrumb">
							<li>
								<a href="<?php echo home_url(); ?>"><i class="ti-home"></i>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( $search_url ); ?>"><?php esc_html_e('Search', 'workintry'); ?></a>
							</li>
							<li><?php esc_html_e('Gig Detail', 'workintry'); ?></li>
						</ol>
						<div class="wi-likeserch">
							<?php if( in_array($post_id, $wishlist ) ){ ?>
								<span>
									<em>
										<?php esc_html_e('Like This Gig?', 'workintry'); ?>
									</em>
									<?php echo esc_html( $save_text ); ?>
								</span>
								<a href="#" class="<?php echo esc_attr( $class ); ?>"><i class="fa fa-heart"></i></a>
								<?php } else { ?>
									<span>
										<em><?php esc_html_e('Like This Gig?', 'workintry'); ?>
										</em>
										<span class="saved-text"><?php echo esc_html( $save_text ); ?></span>
									</span>
									<a href="#" class="<?php echo esc_attr( $class ); ?>" data-id="<?php echo esc_attr( $post->ID ); ?>"><i class="fa fa-heart"></i></a>
							<?php } ?>
						</div>
					</div>
				<?php } elseif( is_page_template( 'user-ads.php' ) ){  ?>
					<div class="wi-breadcrumb-wrap">
						<ol class="wi-breadcrumb">
							<li>
								<a href="<?php echo home_url(); ?>"><i class="ti-home"></i>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( $search_url ); ?>"><?php esc_html_e('Search', 'workintry'); ?></a>
							</li>
							<li><?php esc_html_e('Seller Detail', 'workintry'); ?></li>
						</ol>						
					</div>
				<?php } elseif( is_page_template('gigs-search.php') ){ ?>
					<div class="wi-breadcrumb-wrap">
						<ol class="wi-breadcrumb">
							<li>
								<a href="<?php echo home_url(); ?>"><i class="ti-home"></i>
								</a>
							</li>							
							<li><?php esc_html_e('Search Page', 'workintry'); ?></li>
						</ol>						
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
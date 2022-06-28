<?php 
/**
 * Packages page template
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/*Global variables*/
global $current_user; 
?>
<!-- dashboard Info Start -->
<div class="pc-haslayout">
	<div class="row">		
		<div class="pc-dashboardinfo-holder d-flex">
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<?php do_action('codesquare_workintry_print_dash_count', $current_user->ID, 'total'); ?>
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<?php do_action('codesquare_workintry_print_dash_count', $current_user->ID, 'featured'); ?>									
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<?php do_action('codesquare_workintry_print_dash_count', $current_user->ID, 'inactive'); ?>					
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">				
				<?php do_action('codesquare_workintry_print_dash_count', $current_user->ID, 'active'); ?>
			</div>
		</div>
	</div>
</div>
<!-- dashboard Info Start -->
<div class="pc-divhaslayout">
	<div class="row">		
		<div class="col-12">
			<div class="pc-packages-holder">
				<div class="pc-dashboardbox-title">
					<h3><?php esc_html_e('Packages', 'workintry'); ?></h3>
				</div>
				<?php 
					$args = array(
						'post_type' 		=> 'product',
						'post_status' 		=> 'package',
						'posts_per_page' 	=> 12,
					);

					$products = new WP_Query( $args );
					if( $products->have_posts() ){
				?>
				<div class="pc-packages">
					<div class="pc-package">
						<div class="pc-packagehead"></div>
						<div class="pc-packagecontent">
							<ul>
								<li><span><?php esc_html_e('Price', 'workintry'); ?></span></li>	
								<li><span><?php esc_html_e('Number of Featured Ads', 'workintry'); ?></span></li>
								<li><span><?php esc_html_e('Number of Bump Up Ads', 'workintry'); ?></span></li>								
								<li><span><?php esc_html_e('Featured Ad duration (days)', 'workintry'); ?></span></li>						
								<li class="pc-packagebtn pc-last-one"><a href="javascript:void(0);"><?php esc_html_e('Buy Package', 'workintry'); ?></a></li>
							</ul>
						</div>
					</div>
					<?php 
						while( $products->have_posts() ){
						$products->the_post();
						global $post;					
						$featured_ads 		= get_post_meta( $post->ID, 'featured_ads', true );
						$bump_ads 			= get_post_meta( $post->ID, 'bump_ads', true );					
						$featured_duration 	= get_post_meta( $post->ID, 'featured_duration', true );
						$regular_price 		= get_post_meta( $post->ID, '_regular_price', true );
						$sale_price 				= get_post_meta( $post->ID, '_sale_price', true );
						$price = '';
						if( !empty( $regular_price ) && !empty( $sale_price ) ){
							$price = $sale_price;
						} elseif( !empty( $regular_price ) && empty( $sale_price ) ){
							$price = $regular_price;
						} elseif( empty( $regular_price ) && !empty( $sale_price ) ){
							$price = $sale_price;
						} else{
							$price = $sale_price;
						}
						//Preparing Data					
						$featured_ads 		= !empty( $featured_ads ) ? $featured_ads : 0;
						$bump_ads 			= !empty( $bump_ads ) ? $bump_ads : 0;						
						$featured_duration 	= !empty( $featured_duration ) ? $featured_duration : 0;
						$price 				= !empty( $price ) ? $price : 0;
						$currency_symbol 	= get_woocommerce_currency_symbol();
					?>
					<div class="pc-package">
						<div class="pc-packagehead">
							<h3><?php the_title(); ?></h3>
						</div>
						<div class="pc-packagecontent">
							<ul>
								<li class="pc-price">
									<span>
										<sup>
											<?php echo esc_html( $currency_symbol ); ?>
										</sup> 
										<?php echo esc_html( $price ); ?>
									</span>
								</li>								
								<li>
									<span>
										<?php echo esc_html( $featured_ads ); ?>&nbsp;
										<?php esc_html_e('Ads', 'workintry'); ?>
									</span>
								</li>
								<li>
									<span>
										<?php echo esc_html( $bump_ads ); ?>&nbsp;
										<?php esc_html_e('Ads', 'workintry'); ?>	
									</span>
								</li>							
								<li>
									<span>
										<?php echo esc_html( $featured_duration ); ?>&nbsp;
										<?php esc_html_e('Days', 'workintry'); ?>	
									</span>
								</li>						
								<li class="pc-packagebtn">
									<a href="#" class="pc-btn cl-buy-package" data-id="<?php echo esc_attr( $post->ID ); ?>">
										<?php esc_html_e('buy package', 'workintry'); ?>	
									</a>
								</li>						
							</ul>
						</div>
					</div>
					<?php } wp_reset_postdata(); ?>			
				</div>
				<?php } else{ ?>
					<div class="pc-packages">				
						<div class="pc-ads-holder">
							<div class="alert alert-danger" role="alert"><?php esc_html_e('No package found', 'workintry'). esc_html_e('Ask your administrator to create one', 'workintry'); ?></div>
						</div>				
					</div>	
				<?php } ?>
			</div>
		</div>
	</div>
</div>
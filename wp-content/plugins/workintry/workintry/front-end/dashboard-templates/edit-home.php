<?php
/**
 * Edit ad page template
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/*Global variables*/
wp_enqueue_style( 'hp-chosen', CSC_WORKINTRY_PLUGIN_URL .'assets/css/chosen.min.css', array(), '');				
wp_enqueue_script('hp-chosen', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/chosen.jquery.min.js', array(), '', true);
wp_enqueue_style( 'hp-tags', CSC_WORKINTRY_PLUGIN_URL .'assets/css/bootstrap-tagsinput.css', array(), '');				
wp_enqueue_script('hp-tags', CSC_WORKINTRY_PLUGIN_URL . 'assets/js/bootstrap-tagsinput.js', array(), '', true);
wp_enqueue_style( 'gig-edit' );
global $current_user;
$first_name 			= get_user_meta( $current_user->ID, 'first_name', true );
$last_name  			= get_user_meta( $current_user->ID, 'last_name', true );
$country 				= get_post_meta( $current_user->ID, 'country', true );
$city 					= get_post_meta( $current_user->ID, 'city', true );
$post_id 				= !empty( $_GET['id'] ) ? intval( sanitize_text_field( $_GET['id'] ) ) : 0;
$user_id    			= !empty( $_GET['identity'] ) ? intval( sanitize_text_field( $_GET['identity'] ) ) : '';
//Default currency sign
$cl_default_currency = codesquare_workintry_default_system_currency_sign();
?>
<!-- dashboard Info Start -->
<div class="pc-divhaslayout">
	<div class="row">
		<?php if( $current_user->ID === $user_id ){ ?>
			<!-- Get Post Contents First -->
			<?php 
			$args = array(
				'posts_per_page' => '-1',
                'post_type' => array('workintry'),
                'post_status' => 'any',
                'post__in' => array( $post_id ),
                'orderby' => 'ID',                
                'suppress_filters' => false
            );

            $ad = new WP_Query( $args );
            if( $ad->have_posts() ){
            	while( $ad->have_posts() ){
            	$ad->the_post();
            	global $post;
            	$post_id = $post->ID; 	  
            	//Get post data            	
            	$cl_sign 		= get_post_meta( $post_id, 'cl_sign', true );            
            	$gallery    	= get_post_meta( $post_id, 'cl_galleryc' );
            	$content 		= strip_tags(get_the_content(), '<p>');   
            	//Post Terms
            	$category 		= wp_get_post_terms( $post_id, 'gig_category' );
            	$subcategory 		= wp_get_post_terms( $post_id, 'gig_sub_category' );
            	$service 		= wp_get_post_terms( $post_id, 'gig_service' );
            	$selected_category = !empty( $category ) ? $category[0]->slug : '';
            	$selected_category_id = !empty( $category ) ? $category[0]->term_id : '';
            	$selected_sub_category = !empty( $subcategory ) ? $subcategory[0]->slug : '';
            	$selected_sub_category_id = !empty( $subcategory ) ? $subcategory[0]->term_id : '';            	
            	$selected_service = !empty( $service ) ? $service[0]->slug : '';
            	$service_id = !empty( $service ) ? $service[0]->term_id : '';            	
            	//Tags
            	$tags 				= wp_get_post_terms( $post->ID, 'gig_tags' );	
            	$selected_tags = array();
            	if( !empty( $tags ) ){
            		foreach ($tags as $key => $value) {
            			$selected_tags[] = $value->slug;
            		}
            	}            	
            	$selected_tags = !empty( $selected_tags ) ? implode(",", $selected_tags ) : '';            	
            	
            	//Gigs
            	$basic_gigs 	= get_post_meta( $post_id, 'cl_gig_basic', true );
            	$gold_gigs 		= get_post_meta( $post_id, 'cl_gig_gold', true );
            	$diamond_gigs 	= get_post_meta( $post_id, 'cl_gig_diamond', true );
				$cl_fast = get_post_meta( $post_id, 'cl_fast', true );
				if( $cl_fast == '1' ){
					$cl_fast = 'on';
				}
				$display_class 		= $cl_fast == 'on' ? 'wi-display' : '';
				$basic_fast 		= get_post_meta( $post_id, 'cl_basic_fast_delivery', true );
				$basic_fast_price 	= get_post_meta( $post_id, 'cl_basic_fast_price', true );
				$gold_fast 			= get_post_meta( $post_id, 'cl_gold_fast_delivery', true );
				$gold_fast_price 	= get_post_meta( $post_id, 'cl_gold_fast_price', true );
				$diamond_fast 		= get_post_meta( $post_id, 'cl_diamond_fast_delivery', true );
				$diamond_fast_price = get_post_meta( $post_id, 'cl_diamond_fast_price', true );	
				//FAQ
				$faq = get_post_meta( $post_id, 'cl_faq', true );		
		?>
		
		<!-- gig edit -->
		<!--*** Main Start ***-->
<main id="wi-main" class="wi-main">
   <!-- ==== Search Result Start ==== -->
   <section class="wi-section-wrap">
      <div class="container-fluid">
      		<div class="row">
	         	<div class="col-12">
	         		<div class="alert alert-warning alert-dismissible fade show" role="alert">
					  <strong><?php esc_html_e('Please note!', 'workintry'); ?></strong>&nbsp;<?php esc_html_e('All * fields are required. We do take spam seriously so avoid spam otherwise your account will be blocked.', 'workintry'); ?>
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
					  </button>
					</div>	         		
	         	</div>
         	</div>
         <div class="row">         	
            <div class="col-12 col-xl-3">
               <aside>
                  <div class="wi-dbboxsteps">
					<div class="wi-stepstitle">						
						<h3><?php esc_html_e('Summary:', 'workintry'); ?> 
						<span class="wrap">
						<span class="gig-score"><?php esc_html_e('0', 'workintry'); ?>%</span>
						<span><?php esc_html_e('Completed', 'workintry'); ?></span>
						</span>
						</h3>
					</div>
                    <ul class="wi-stepslist">
                        <li class="gig-title-check"><span><?php esc_html_e('Add gig title (required)', 'workintry'); ?></span></li>
                        <li class="gig-desc-check"><span><?php esc_html_e('Gig description (required)', 'workintry'); ?></span></li>
                        <li class="gig-gallery-check"><span><?php esc_html_e('Gig gallery (required)', 'workintry'); ?></span></li>
                        <li class="gig-category-check"><span><?php esc_html_e('Select category (required)', 'workintry'); ?></span></li>
                        <li class="gig-subcategory-check"><span><?php esc_html_e('Select Sub sategory (required)', 'workintry'); ?></span></li>
                        <li class="gig-service-check"><span><?php esc_html_e('Select service (required)', 'workintry'); ?></span></li>   
                        <li class="gig-package-check"><span><?php esc_html_e('Offering packages (required)', 'workintry'); ?></span></li>
                        <li class="gig-tag-check"><span><?php esc_html_e('Gig tags (required)', 'workintry'); ?></span></li>
                        <li class="gig-faq-check"><span><?php esc_html_e('Add F.A.Q (optional)', 'workintry'); ?></span></li>
                    </ul>
                    <div class="wi-stepsfooter">
                        <a href="#" class="wi-btn wi-submit-gig-form"><?php esc_html_e('Post Now', 'workintry'); ?></a>
                        <em><?php esc_html_e('Click "Post Now"  button to post your new gig into list', 'workintry'); ?></em>
                    </div>
                  </div>
               </aside>
            </div>
            <div class="col-12 col-xl-9 order-first">
               <form class="cf-insert-ad-form">
                  <div class="wi-dbbox">
                     <div class="wi-dbbox-title">
                        <h3><?php esc_html_e('Edit Gig Details', 'workintry'); ?></h3>
                     </div>
                     <div class="wi-dbbox-content">
                        <div class="wi-form">
                           <div class="form-group">
                              <label class="form-title">
                              	<?php esc_html_e('Title*:', 'workintry'); ?>
                              	<i class="ti-info-alt"></i>
                              </label>
                              <input type="text" class="form-control gig-title-val" name="title" placeholder="<?php esc_attr_e('Gig Title', 'workintry') ?>" value="<?php the_title(); ?>">
                           </div>
                           <div class="form-group">
                              <label class="form-title">
                              	<?php esc_html_e('Description*:', 'workintry'); ?> 
                              	<i class="ti-info-alt"></i>
                              </label>
                              <?php do_action( 'codesquare_workintry_print_post_editor', $content ); ?>                              
                           </div>
                           <div class="form-group">
                              <label class="form-title">
                              	<?php esc_html_e('Upload Gallery of Your Gig*:', 'workintry'); ?> 
                              	<i class="ti-info-alt"></i>
                              </label>
                              <div class="wi-uploadimgs">
                                 <div class="wi-uploadimgsinfo">
                                    <i class="ti-image"></i>
                                    <h3>
                                    <?php esc_html_e('Upload Photos', 'workintry'); ?>
                                    </h3>
                                    <span>
                                	<?php esc_html_e('Drop files here or', 'workintry'); ?> 
                                	</span> 
                            		<a href="#" id="cl-upload-ad-gallery" class="pc-btn cl-fileinput">
									<?php esc_html_e('click here', 'workintry'); ?>
									</a>                          	
                                	<span>
										<?php esc_html_e('to upload', 'workintry'); ?>
									</span>                         
                                    <div id="plupload-gallery-container"></div>
                                 </div>
                                 
                                 <div class="wi-uploadimgshow cp-upload-imgs">
                                    <ul class="cf-hscrollbar cf-gallery-images">
                                       <?php
											if( !empty( $gallery ) ){
												$counter = 0;
												foreach ( $gallery as $key => $value ) {
													$counter++;
													$image = wp_get_attachment_url( $value );
													?>
													<li class="cf-check cf-cross">
														<a href="#" class="cf-cross-sign cf-delete-gallery-image"><i class="fa fa-times"></i></a><img src="<?php echo esc_url( $image ); ?>" alt="img">
														<input type="hidden" name="gallery[<?php echo esc_attr( $counter ); ?>][id]" value="<?php echo esc_attr( $value ); ?>" class="get-gig-gallery">
													</li>  
													<?php
												}
											}
										?>	
                                    </ul>
                                    <div id="myProgress">
									    <div id="myBar"></div>
									</div> 
                                 </div>
                              </div>
                           </div>
                           <div class="form-group form-group-half">
                              <label class="form-title"><?php esc_html_e('Select Category*:', 'workintry'); ?><i class="ti-info-alt"></i> </label>
                              <span class="wi-select main-cats"> 
                              	<?php do_action('codesquare_workintry_print_taxonomy_options', 'gig_category', esc_html__('Select Category', 'workintry'),'gig-category', $selected_category); ?>  	
                              </span>
                           </div>
                           <div class="form-group form-group-half">
                              <label class="form-title"><?php esc_html_e('Select Subcategory*:', 'workintry'); ?><i class="ti-info-alt"></i> </label>
                              <span class="wi-select sub-cats">
                                <?php                                 
                                do_action('codesquare_workintry_print_taxonomy_options', 'gig_sub_category', esc_html__('Select Subcategory', 'workintry'),'sub-category', $selected_sub_category, '', $selected_category_id); ?>
                              </span>
                           </div>
                           <div class="form-group form-group-half">
                              <label class="form-title">
                              	<?php esc_html_e('Select Service*:', 'workintry'); ?>
                              	<i class="ti-info-alt"></i> 
                              </label>
                              <span class="wi-select gig-services">
                                 <?php do_action('codesquare_workintry_print_taxonomy_options', 'gig_service', esc_html__('Select Service', 'workintry'),'gig-service', $selected_service, '', $selected_sub_category_id); ?>
                              </span>
                           </div>                           	
                        </div>
                     </div>
                  </div>
                  <div class="wi-dbbox">
                     	<div class="wi-dbbox-title">
	                        <h3>
	                        	<?php esc_html_e('Add Offering Packages', 'workintry'); ?>
	                        </h3>
                     	</div>
                     <div class="wi-dbbox-content">
                        <div class="wi-packagesedit">
                        	<!-- Basic -->
                           <ul class="wi-packagesform">
                              <li class="wi-packageform">
                                 <div class="wi-form">
                                    <div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Package 01 Title*:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
                                          <input type="text" class="form-control gig-basic-title" name="basic[title]" placeholder="<?php esc_html_e('Add Title Here', 'workintry'); ?>" value="<?php if( !empty( $basic_gigs['title'] ) ){ echo esc_attr( $basic_gigs['title'] ); } ?>">
                                       </div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Gig Description*:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
                                          <textarea type="text" class="form-control gig-basic-desc" name="basic[description]" placeholder="<?php esc_html_e('Description', 'workintry'); ?>"><?php if( !empty( $basic_gigs['description'] ) ){ echo esc_attr( $basic_gigs['description'] ); } ?></textarea>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li class="wi-pselectoption">
                              	<?php 
                              	$basic_gigsdelivery_data = !empty( $basic_gigs['delivery'] ) ? $basic_gigs['delivery'] : '';
                              	?>
                                 <span><?php esc_html_e('Delivery Time:', 'workintry'); ?></span>
                                 <span class="wi-select gig-basic-delivery">
                                    <?php do_action('codesquare_workintry_print_gig_delivery', 'basic', $basic_gigsdelivery_data ); ?>
                                 </span>
                              </li>
                            	<li class="wi-packageform embed-gig-basic">
                            		<ul>
	                            	<?php 
									$gig_meta = get_term_meta( $service_id, 'wimeta', true );
						        	if( !empty( $gig_meta ) ){ 
						        		$counter = 0;
										foreach ( $gig_meta as $value ) {
											$counter++;					
											if( $value['witype'] == 'check' ){
												$got_saved = !empty( $basic_gigs[$value['wititle']] ) ? $basic_gigs[$value['wititle']] : '';
												?>
												<li>
													<input type="checkbox" name="basic[<?php echo esc_attr( $value['wititle'] ); ?>]" id="basic-Logo<?php echo esc_attr( $counter ); ?>" <?php checked($got_saved, 'on' ); ?>>
													<label for="basic-Logo<?php echo esc_attr( $counter ); ?>"><?php echo esc_html( $value['wititle'] ); ?>: <i class="far fa-square"></i></label>
												</li>
												<?php 
											} elseif( $value['witype'] == 'selecte' ){
												$got_saved = !empty( $basic_gigs[$value['wititle']] ) ? $basic_gigs[$value['wititle']] : '';
												?>
												<li class="wi-pselectoption">
													<span><?php echo esc_html( $value['wititle'] ); ?>:	</span>
													<span class="wi-select">
														<select name="basic[<?php echo esc_attr( $value['wititle'] ); ?>]">
															<?php 
																$values = $value['wivalue'];
																if( !empty( $values ) ){
																	foreach ( $values as $key => $inner_value) {
																		?>
																		<option value="<?php echo esc_attr( $inner_value ); ?>" <?php selected($got_saved, $inner_value ); ?>><?php echo esc_html( $inner_value ); ?></option>
																		<?php 
																	}
																}
																?>
														</select>
													</span>
												</li>							
												<?php 
											}
										}
						        	}
										?>
									</ul>
                            	</li>
                            	<?php 
                            	$basic_gigsrevisions = !empty( $basic_gigs['revisions'] ) ? $basic_gigs['revisions'] : '';
                            	?>
                              <li class="wi-pselectoption">
                                 <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
                                 <span class="wi-select gig-basic-revision">
                                    <?php do_action('codesquare_workintry_print_gig_revisions', 'basic', $basic_gigsrevisions) ?>
                                 </span>
                              </li>
                              <?php 
                              $basic_gigsprice = !empty( $basic_gigs['price'] ) ? $basic_gigs['price'] : 0;
                              ?>
                              <li class="wi-totalprice basic-gig-price">
                                 <span><?php esc_html_e('Price: ('. codesquare_workintry_default_system_currency_sign().')', 'workintry'); ?></span>
                                 <em><?php do_action('codesquare_workintry_print_gig_price', 'basic', '10', $basic_gigsprice ); ?></em>
                              </li>
                           </ul>
                           <!-- Basic -->
                           <!-- Gold -->
                           <ul class="wi-packagesform">
                              <li class="wi-packageform">
                                 <div class="wi-form">
                                    <div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Gig 02 Title*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
                                          <input type="text" class="form-control gig-gold-title" name="gold[title]" placeholder="<?php esc_attr_e('Add Title Here', 'workintry'); ?>" value="<?php if( !empty( $gold_gigs['title'] ) ) { echo esc_attr( $gold_gigs['title'] ); } ?>">
                                       </div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Gig Description*:', 'workintry' ); ?><i class="ti-info-alt"></i></label>
                                          <textarea type="text" class="form-control gig-gold-desc" name="gold[description]" placeholder="<?php esc_attr_e('Description', 'workintry'); ?>"><?php if( !empty( $gold_gigs['description'] ) ){ echo esc_html( $gold_gigs['description'] ); } ?></textarea>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <?php 
                              	$gold_gigsdelivery = !empty( $gold_gigs['delivery'] ) ? $gold_gigs['delivery'] : '';
                              ?>
                              <li class="wi-pselectoption gig-gold-delivery">
                                 <span><?php esc_html_e('Delivery Time:', 'workintry'); ?></span>
                                 <span class="wi-select">
                                    <?php do_action('codesquare_workintry_print_gig_delivery', 'gold',$gold_gigsdelivery) ?>
                                 </span>
                              </li>
                              	<li class="wi-packageform embed-gig-gold">
                              		<ul>
	                            	<?php 
									$gig_meta = get_term_meta( $service_id, 'wimeta', true );
						        	if( !empty( $gig_meta ) ){ 
						        		$counter = 0;
										foreach ( $gig_meta as $value ) {
											$counter++;					
											if( $value['witype'] == 'check' ){
												$got_saved = !empty( $gold_gigs[$value['wititle']] ) ? $gold_gigs[$value['wititle']] : '';
												?>
												<li>
													<input type="checkbox" name="gold[<?php echo esc_attr( $value['wititle'] ); ?>]" id="gold-Logo<?php echo esc_attr( $counter ); ?>" <?php checked($got_saved, 'on' ); ?>>
													<label for="gold-Logo<?php echo esc_attr( $counter ); ?>"><?php echo esc_html( $value['wititle'] ); ?>: <i class="far fa-square"></i></label>
												</li>
												<?php 
											} elseif( $value['witype'] == 'selecte' ){
												$got_saved = !empty( $gold_gigs[$value['wititle']] ) ? $gold_gigs[$value['wititle']] : '';
												?>
												<li class="wi-pselectoption">
													<span><?php echo esc_html( $value['wititle'] ); ?>:	</span>
													<span class="wi-select">
														<select name="gold[<?php echo esc_attr( $value['wititle'] ); ?>]">
															<?php 
																$values = $value['wivalue'];
																if( !empty( $values ) ){
																	foreach ( $values as $key => $inner_value) {
																		?>
																		<option value="<?php echo esc_attr( $inner_value ); ?>" <?php selected($got_saved, $inner_value ); ?>><?php echo esc_html( $inner_value ); ?></option>
																		<?php 
																	}
																}
																?>
														</select>
													</span>
												</li>							
												<?php 
											}
										}
						        	}
										?>
									</ul>
                              	</li>
                              	<?php 
                              	$gold_gigsrevisions = !empty( $gold_gigs['revisions'] ) ? $gold_gigs['revisions'] : '';
                              	?>
                              <li class="wi-pselectoption">
                                 <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
                                 <span class="wi-select gig-gold-revision">
                                    <?php do_action('codesquare_workintry_print_gig_revisions', 'gold', $gold_gigsrevisions ); ?>
                                 </span>
                              </li>
                              <?php 
                              $gold_gigsprice = !empty( $gold_gigs['price'] ) ? $gold_gigs['price'] : 0;
                              ?>
                              <li class="wi-totalprice gold-gig-price">
                                 <span><?php esc_html_e('Price: ('. codesquare_workintry_default_system_currency_sign().')', 'workintry'); ?></span>
                                 <em><?php do_action('codesquare_workintry_print_gig_price', 'gold', '20', $gold_gigsprice ); ?></em>
                              </li>
                           </ul>
                           <!-- Gold -->
                           <!-- Diamond -->
                           <ul class="wi-packagesform">
                              <li class="wi-packageform">
                                 <div class="wi-form">
                                    <div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Gig 03 Title*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
                                          <input type="text" class="form-control gig-diamond-title" name="diamond[title]" placeholder="<?php esc_html_e('Add Title Here', 'workintry'); ?>" value="<?php if( !empty( $diamond_gigs['title'] ) ){ echo esc_attr( $diamond_gigs['title'] ); } ?>">
                                       </div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Gig Description*:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
                                          <textarea type="text" class="form-control gig-diamond-desc" name="diamond[description]" placeholder="<?php esc_attr_e('Description', 'workintry'); ?>"><?php if( !empty( $diamond_gigs['title'] ) ){ echo esc_html( $diamond_gigs['title'] ); } ?></textarea>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <?php 
                              $diamond_gigsdelivery = !empty( $diamond_gigs['delivery'] ) ? $diamond_gigs['delivery'] : '';
                              ?>
                              <li class="wi-pselectoption">
                                 <span><?php esc_html_e('Delivery Time:', 'workintry'); ?></span>
                                 <span class="wi-select gig-diamond-delivery">
                                    <?php do_action('codesquare_workintry_print_gig_delivery', 'diamond', $diamond_gigsdelivery ) ?>
                                 </span>
                              </li>
                              <li class="wi-packageform embed-gig-diamond">
                              	<ul>
	                            	<?php 
									$gig_meta = get_term_meta( $service_id, 'wimeta', true );
						        	if( !empty( $gig_meta ) ){ 
						        		$counter = 0;
										foreach ( $gig_meta as $value ) {
											$counter++;					
											if( $value['witype'] == 'check' ){
												$got_saved = !empty( $diamond_gigs[$value['wititle']] ) ? $diamond_gigs[$value['wititle']] : '';
												?>
												<li>
													<input type="checkbox" name="diamond[<?php echo esc_attr( $value['wititle'] ); ?>]" id="diamond-Logo<?php echo esc_attr( $counter ); ?>" <?php checked($got_saved, 'on' ); ?>>
													<label for="diamond-Logo<?php echo esc_attr( $counter ); ?>"><?php echo esc_html( $value['wititle'] ); ?>: <i class="far fa-square"></i></label>
												</li>
												<?php 
											} elseif( $value['witype'] == 'selecte' ){
												$got_saved = !empty( $diamond_gigs[$value['wititle']] ) ? $diamond_gigs[$value['wititle']] : '';
												?>
												<li class="wi-pselectoption">
													<span><?php echo esc_html( $value['wititle'] ); ?>:	</span>
													<span class="wi-select">
														<select name="diamond[<?php echo esc_attr( $value['wititle'] ); ?>]">
															<?php 
																$values = $value['wivalue'];
																if( !empty( $values ) ){
																	foreach ( $values as $key => $inner_value) {
																		?>
																		<option value="<?php echo esc_attr( $inner_value ); ?>" <?php selected($got_saved, $inner_value ); ?>><?php echo esc_html( $inner_value ); ?></option>
																		<?php 
																	}
																}
																?>
														</select>
													</span>
												</li>							
												<?php 
											}
										}
						        	}
										?>
									</ul>
                              </li>
                              <?php 
                              $diamond_gigsrevisions = !empty( $diamond_gigs['revisions'] ) ? $diamond_gigs['revisions'] : '';
                              ?>
                              <li class="wi-pselectoption">
                                 <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
                                 <span class="wi-select gig-diamond-revision">
                                    <?php do_action('codesquare_workintry_print_gig_revisions', 'diamond', $diamond_gigsrevisions ); ?>
                                 </span>
                              </li>
                              <?php 
                              $diamond_gigsprice = !empty( $diamond_gigs['price'] ) ? $diamond_gigs['price'] : 0;
                              ?>
                              <li class="wi-totalprice diamond-gig-price">
                                 <span><?php esc_html_e('Price: ('. codesquare_workintry_default_system_currency_sign().')', 'workintry'); ?></span>
                                 <em><?php do_action('codesquare_workintry_print_gig_price', 'diamond', '30', $diamond_gigsprice ); ?></em>
                              </li>
                           </ul>
                           <!-- Diamond -->
                        </div>
                        <div class="wi-addservices">
                           <div class="wi-addservicestitle">
                              <h3><?php esc_html_e('Add Gig Extra Services', 'workintry'); ?></h3> 
                           </div>
                           <div class="wi-addservicesinfo">
                              <div class="wi-addservicesinput">
                                 <div class="form-group wi-adddeliverycheckwrap">
                                    <div class="wi-adddeliverycheck">
                                       <input type="checkbox" name="fast" id="Delivery" <?php checked( $cl_fast, 'on' ); ?> value="<?php echo esc_attr( $cl_fast ); ?>">
                                       <label for="Delivery"><i class="far fa-square"></i> <?php esc_html_e('Fast Gig Delivery', 'workintry'); ?></label>
                                    </div>                             
                                 </div>
                                 <div class="wi-addservicescollapse <?php echo esc_attr( $display_class ); ?>">
                                    <div class="accordion">
                                       <div class="wi-formaccordion">
                                          <div class="wi-titleformac" id="headingOne">
                                             <h5 data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><?php esc_html_e( 'Price For Gig 01:', 'workintry' ); ?> <i class="ti-plus"></i> </h5>
                                          </div>
                                          <div id="collapseOne" class="wi-collapsewrap collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                             <div class="wi-faqaccordioninfo">
                                                <div class="wi-form">
                                                   <div>
                                                      <div class="form-group form-group-half">
                                                         <label class="form-title"><?php esc_html_e('I will deliver in only*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
                                                         <span class="wi-select">
                                                            <?php do_action('codesquare_workintry_print_gig_delivery', 'basicfast', $basic_fast ); ?>
                                                         </span>
                                                      </div>
                                                      <div class="form-group form-group-half">
                                                         <label class="form-title"><?php esc_html_e('For an Extra Price*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
                                                         <span class="wi-select">
                                                            <?php do_action('codesquare_workintry_print_gig_price', 'basicfast', '10', $basic_fast_price ); ?>
                                                         </span>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="wi-formaccordion">
                                          <div class="wi-titleformac" id="AheadingOne">
                                             <h5 data-toggle="collapse" data-target="#AcollapseOne" aria-expanded="false" aria-controls="AcollapseOne"><?php esc_html_e('Price For Gig 02:', 'workintry'); ?> <i class="ti-plus"></i> </h5>
                                          </div>
                                          <div id="AcollapseOne" class="wi-collapsewrap collapse" aria-labelledby="AheadingOne" data-parent="#accordion">
                                             <div class="wi-faqaccordioninfo">
                                                <div class="wi-form">
                                                   <div>
                                                      <div class="form-group form-group-half">
                                                         <label class="form-title"><?php esc_html_e( 'I will deliver in only*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
                                                         <span class="wi-select">
                                                            <span class="wi-select">
                                                            <?php do_action('codesquare_workintry_print_gig_delivery', 'goldfast', $gold_fast ) ?>
                                                         </span>
                                                         </span>
                                                      </div>
                                                      <div class="form-group form-group-half">
                                                         <label class="form-title"><?php esc_html_e('For an extra price*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
                                                         <span class="wi-select">
                                                            <?php do_action('codesquare_workintry_print_gig_price', 'goldfast', '10', $gold_fast_price ); ?>
                                                         </span>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="wi-formaccordion">
                                          <div class="wi-titleformac" id="cheadingOne">
                                             <h5 data-toggle="collapse" data-target="#ccollapseOne" aria-expanded="false" aria-controls="ccollapseOne"><?php esc_html_e( 'Price for Gig 03:', 'workintry' ); ?> <i class="ti-plus"></i> </h5>
                                          </div>
                                          <div id="ccollapseOne" class="wi-collapsewrap collapse" aria-labelledby="cheadingOne" data-parent="#accordion">
                                             <div class="wi-faqaccordioninfo">
                                                <div class="wi-form">
                                                   <div>
                                                      <div class="form-group form-group-half">
                                                         <label class="form-title"><?php esc_html_e('I will deliver in only*:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
                                                         <span class="wi-select">
                                                            <?php do_action('codesquare_workintry_print_gig_delivery', 'diamondfast', $diamond_fast) ?>
                                                         </span>
                                                      </div>
                                                      <div class="form-group form-group-half">
                                                         <label class="form-title"><?php esc_html_e('For an extra price*:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
                                                         <span class="wi-select">
                                                            <?php do_action('codesquare_workintry_print_gig_price', 'diamondfast', '30', $diamond_fast_price ); ?>
                                                         </span>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>                               
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Tags -->
                  <div class="wi-dbbox">
                     <div class="wi-dbbox-title">
                        <h3><?php esc_html_e('Add Gig Tag\'s (required)', 'workintry' ); ?></h3>                        
                     </div>
                     <div class="wi-dbbox-content">
                        <div class="wi-form">
                        	<div class="form-group hp-tags">
	                       		<label class="form-title"><?php esc_html_e('Tags*:', 'workintry'); ?><i class="ti-info-alt"></i> </label>
								<input type="text" name="tags" class="form-control" placeholder="<?php esc_attr_e('Tags', 'workintry'); ?>" data-role="tagsinput" value="<?php echo esc_attr( $selected_tags ); ?>">
							</div>
                        </div>
                    </div>                    
                  </div>
                  <!-- Tags -->
                  <div class="wi-dbbox wi-addfaq">
                     <div class="wi-dbbox-title">
                        <h3><?php esc_html_e('Add Common FAQ\'s', 'workintry' ); ?></h3>
                        <a class="wi-add-faq" href="javascript:void(0);"><?php esc_html_e('Add FAQ+', 'workintry'); ?></a>
                     </div>
                     <div class="wi-dbbox-content">
                        <div class="accordion">
                        	<?php if( !is_array( $faq ) ){ ?>
	                        	<span class="wi-remove-faq"><?php esc_html_e('Click Add FAQ+ button above in the right corner to add your FAQ', 'workintry'); ?>
	                        	</span> 
                        	<?php } else { 
                        		$counter = 0;
                        		foreach ( $faq as $key => $value ) {
                        			$counter = $counter + 1;
                        			?>
										<div class="wi-formaccordion">
											<div class="wi-titleformac" id="vheadingOne<?php echo esc_attr( $counter ); ?>">
											    <h5 data-toggle="collapse" data-target="#vcollapseOne<?php echo esc_attr( $counter ); ?>" aria-expanded="true" aria-controls="vcollapseOne<?php echo esc_attr( $counter ); ?>"><?php echo esc_html( $value['title'] ); ?><span class="remove-faq"><?php esc_html_e('Delete FAQ --', 'workintry'); ?></span></h5>
											</div>
											<div id="vcollapseOne<?php echo esc_attr( $counter ); ?>" class="wi-collapsewrap collapse show" aria-labelledby="vheadingOne<?php echo esc_attr( $counter ); ?>" data-parent="#accordion">
											 <div class="wi-faqaccordioninfo">
											    <div class="wi-form">
											       <div>
											          <div class="form-group">
											             <label class="form-title"><?php echo esc_html( $value['title'] ); ?> <i class="ti-info-alt"></i></label>
											             <input type="text" class="form-control" name="faq[<?php echo esc_attr( $counter ); ?>][title]" placeholder="<?php esc_attr_e('Question', 'workintry'); ?>" value="<?php echo esc_attr( $value['title'] ); ?>">
											          </div>
											          <div class="form-group">
											             <label class="form-title"><?php esc_html_e('Answer:', 'workintry'); ?><i class="ti-info-alt"></i></label>
											             <textarea type="text" class="form-control" name="faq[<?php echo esc_attr( $counter ); ?>][description]" placeholder="<?php esc_html_e('Answer', 'workintry'); ?>"><?php echo esc_html( $value['description'] ); ?></textarea>
											          </div>
											       </div>
											    </div>
											 </div>
											</div>
										</div>
                        			<?php 
                        		}
                        	} ?>
                     	</div>
                     </div>
                  </div>  
                  <!-- Tags -->                
                  	<!-- Featured --> 
                  	<div class="wi-dbbox">
                  		<div class="wi-dbbox-title">
	                        <h3><?php esc_html_e('Gig Promotions', 'workintry' ); ?></h3>                 
	                     </div>
	                     <div class="form-group">
	                     	<?php do_action('codesquare_workintry_print_featured_ad_form', $post_id ); ?>
	                     	<?php do_action( 'codesquare_workintry_print_bump_ad_form', $post_id ); ?>
	                     </div>
                  	</div>                                 
                  	<!-- Featured -->
                  <?php wp_nonce_field('add_new_ad_form', 'add_new_ad_form'); ?>	
                  <div class="wi-dbboxbtns">                  	
                     <a href="#" class="wi-btn cf-insert-gig" data-type="edit" data-id="<?php echo esc_attr( $post_id ); ?>"><?php esc_html_e('Update Now', 'workintry'); ?></a>
                     <em><?php esc_html_e('Click "Update Now"  button to update your gig into list', 'workintry'); ?></em>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
   <!-- ==== Search Result End ==== -->
</main>
<!--*** Main End ***-->
		<!-- gig edit ends -->
		<?php } wp_reset_postdata(); } ?>
		<?php } else { ?>			
			<div class="col-12">
				<div class="alert alert-danger" role="alert"><?php esc_html_e('Restricted Access! ', 'workintry'). esc_html_e('No kiddies please', 'workintry'); ?></div>
			</div>	
		<?php } ?>
	</div>
</div>
<!-- dashboard Info End -->
<script type="text/template" id="tmpl-append-gallery-photo">
	<li class="cf-check cf-cross">
		<a href="#" class="cf-cross-sign cf-delete-gallery-image"><i class="fa fa-times"></i></a><img src="{{data.response.thumbnail}}" alt="img">
		<input type="hidden" name="gallery[{{data.count}}][id]" value="{{data.response.attachment_id}}" class="get-gig-gallery">
	</li>  
</script>
<script type="text/template" id="tmpl-append-faq">
<div class="wi-formaccordion">
	<div class="wi-titleformac" id="vheadingOne{{data.count}}">
	    <h5 data-toggle="collapse" data-target="#vcollapseOne{{data.count}}" aria-expanded="true" aria-controls="vcollapseOne{{data.count}}"><?php esc_html_e('Add Question Here: ', 'workintry'); ?><span class="remove-faq"><?php esc_html_e('Delete FAQ --', 'workintry'); ?></span></h5>
	</div>
	<div id="vcollapseOne{{data.count}}" class="wi-collapsewrap collapse show" aria-labelledby="vheadingOne{{data.count}}" data-parent="#accordion">
	 <div class="wi-faqaccordioninfo">
	    <div class="wi-form">
	       <div>
	          <div class="form-group">
	             <label class="form-title"><?php esc_html_e('Add Question Here:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
	             <input type="text" class="form-control" name="faq[{{data.count}}][title]" placeholder="<?php esc_attr_e('Question', 'workintry'); ?>">
	          </div>
	          <div class="form-group">
	             <label class="form-title"><?php esc_html_e('Answer:', 'workintry'); ?><i class="ti-info-alt"></i></label>
	             <textarea type="text" class="form-control" name="faq[{{data.count}}][description]" placeholder="<?php esc_html_e('Answer', 'workintry'); ?>"></textarea>
	          </div>
	       </div>
	    </div>
	 </div>
	</div>
</div>
</script>
<!-- Get all categories/services -->
<?php 
require_once codesquare_workintry_addon_template_exsits('workintry/front-end/categories-data-edit');
?>

<?php
/*Workintry project data*/
add_action( 'wp_ajax_codesquare_workintry_gt_data_from_term', 'codesquare_workintry_gt_data_from_term' );
function codesquare_workintry_gt_data_from_term() {
    $term_id 	= $_POST['term_id'];
    $term_data 	= get_term_by('slug', $term_id, 'gig_category');
    $term_id 	= $term_data->term_id;    
    ob_start();
    ?>
    <div class="rwmb-field rwmb-taxonomy_advanced-wrapper cl-sub-cat-wrapper">
    	<div class="rwmb-label">
			<label for="cl_category"><?php esc_html_e('Sub category', 'workintry'); ?></label>	
		</div>
		<div class="rwmb-input">
			<select id="cl_sub_category" class="rwmb-taxonomy_advanced rwmb-select valid" name="cl_sub_category" aria-invalid="false">
				<option value=""><?php esc_html_e('Select', 'workintry'); ?></option>
				<?php 
				$args = array(
		        	'hide_empty' => false, 
		            'meta_query' => array(
		                array(
		                   'key'       => 'parent_category',
		                   'value'     => $term_id,
		                   'compare'   => '='
		                )
		            ),
		        	'taxonomy'  => 'gig_sub_category',
		    	);
		    	$sub_terms = get_terms( $args ); 
		    	if( !empty( $sub_terms ) ){		    		
		    		foreach ($sub_terms as $sub_key => $sub_value) {
		    			?>
		    				<option class="service-<?php echo esc_attr( $sub_value->term_id ); ?>" value="<?php echo esc_attr( $sub_value->slug ); ?>"><?php echo esc_html( $sub_value->name ); ?></option>
		    			<?php 
		    		}		    		
		    	}
				?>
			</select>
			<p id="cl_category-description" class="description">
				<?php esc_html_e('Select Category', 'workintry'); ?>	
			</p>
		</div>
	</div>	
<?php 
echo ob_get_clean();
wp_die();
}

//Get services function
add_action( 'wp_ajax_codesquare_workintry_get_services_form_cat', 'codesquare_workintry_get_services_form_cat' );
function codesquare_workintry_get_services_form_cat() {
    $term_id 	= $_POST['term_id'];
    $term_data 	= get_term_by('slug', $term_id, 'gig_sub_category');
    $term_id 	= $term_data->term_id;          
    ob_start();
    ?>
    <div class="rwmb-field rwmb-taxonomy_advanced-wrapper cl-services-wrapper">
    	<div class="rwmb-label">
			<label for="cl_category">
				<?php esc_html_e('Services', 'workintry'); ?>
			</label>	
		</div>
		<div class="rwmb-input">
			<select id="cl_services" class="rwmb-taxonomy_advanced rwmb-select valid" name="cl_service" aria-invalid="false">
				<option value=""><?php esc_html_e('Select', 'workintry'); ?></option>
				<?php 
				$args = array(
		        	'hide_empty' => false, 
		            'meta_query' => array(
		                array(
		                   'key'       => 'gig_meta',
		                   'value'     => $term_id,
		                   'compare'   => '='
		                )
		            ),
		        	'taxonomy'  => 'gig_service',
		    	);
		    	$sub_terms = get_terms( $args );		    	
		    	if( !empty( $sub_terms ) ){		    		
		    		foreach ($sub_terms as $sub_key => $sub_value) {
		    			?>
		    				<option class="gig-service<?php echo esc_attr( $sub_value->term_id ); ?>" value="<?php echo esc_attr( $sub_value->slug ); ?>"><?php echo esc_html( $sub_value->name ); ?></option>
		    			<?php 
		    		}		    		 
		    	}
				?>
			</select>
			<p id="cl_service-description" class="description">
				<?php esc_html_e('Select Service', 'workintry'); ?>		
			</p>
		</div>
	</div>		
<?php 
echo ob_get_clean();
wp_die();
}

//Basic
add_action( 'add_meta_boxes', 'codesquare_workintry_add_gig_metas' );
if ( ! function_exists( 'codesquare_workintry_add_gig_metas' ) ){
    function codesquare_workintry_add_gig_metas()
    {
        global $post;
        add_meta_box( 'codesquare_workintry_add_gig_metas', __('Gig Info','workintry'), 'codesquare_workintry_gig_meta_data_content', 'workintry', 'normal', 'core' );
    }
}

/*Print Content*/
function codesquare_workintry_gig_meta_data_content()
{
    global $post;
    $post_id = $post->ID;
    //Basic
	$basic_title = get_post_meta( $post->ID, 'cl_basic_title', true);
	$basic_description = get_post_meta( $post->ID, 'cl_basic_desc', true);
	$basic_delivery = get_post_meta( $post->ID,'cl_basic_delivery', true);
	$basic_revision =get_post_meta( $post->ID, 'cl_basic_revision', true );
	$basic_price = get_post_meta( $post->ID, 'cl_basic_price', true );
	//Gold
	$gold_title = get_post_meta( $post->ID, 'cl_gold_title', true);
	$gold_description = get_post_meta( $post->ID, 'cl_gold_desc', true);
	$gold_delivery = get_post_meta( $post->ID,'cl_gold_delivery', true);
	$gold_revision =get_post_meta( $post->ID, 'cl_gold_revision', true );
	$gold_price = get_post_meta( $post->ID, 'cl_gold_price', true );
	//Diamond
	$diamond_title = get_post_meta( $post->ID, 'cl_diamond_title', true);
	$diamond_description = get_post_meta( $post->ID, 'cl_diamond_desc', true);
	$diamond_delivery = get_post_meta( $post->ID,'cl_diamond_delivery', true);
	$diamond_revision =get_post_meta( $post->ID, 'cl_diamond_revision', true );
	$diamond_price = get_post_meta( $post->ID, 'cl_diamond_price', true );	

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

	//Services selected
	$service 			= wp_get_post_terms( $post_id, 'gig_service' );
    $selected_service 	= !empty( $service ) ? $service[0]->slug : '';
    $service_id 		= !empty( $service ) ? $service[0]->term_id : '';
    $category 		= wp_get_post_terms( $post_id, 'gig_category' );
    $subcategory 		= wp_get_post_terms( $post_id, 'gig_sub_category' );           
    $selected_category = !empty( $category ) ? $category[0]->slug : '';
    $selected_category_id = !empty( $category ) ? $category[0]->term_id : '';
    $selected_sub_category = !empty( $subcategory ) ? $subcategory[0]->slug : '';
    $selected_sub_category_id = !empty( $subcategory ) ? $subcategory[0]->term_id : '';     	 
    ?>
    <!-- Category Starts -->
    <div class="rwmb-meta-box" id="cl_category">
    	<div class="rwmb-field rwmb-taxonomy_advanced-wrapper">
    		<div class="rwmb-label">
				<label for="cl_category">
					<?php esc_html_e('Category', 'workintry'); ?>
				</label>
			</div>
			<div class="rwmb-input">
				<?php do_action('codesquare_workintry_print_taxonomy_options', 'gig_category', esc_html__('Select Category', 'workintry'),'gig-category', $selected_category); ?>
				<p id="cl_category-description" class="description">
					<?php  esc_html_e('Select Category', 'workintry'); ?>
				</p>
			</div>
		</div>
	</div>
    <!-- Category Ends -->
    <!-- Sub Category Starts -->
    <div class="rwmb-meta-box" id="cl_sub_category">
    	<div class="rwmb-field rwmb-taxonomy_advanced-wrapper">
    		<div class="rwmb-label">
				<label for="cl_sub_category">
					<?php esc_html_e('Subcategory', 'workintry'); ?>
				</label>
			</div>
			<div class="rwmb-input">
				<?php                                 
    			do_action('codesquare_workintry_print_taxonomy_options', 'gig_sub_category', esc_html__('Select Subcategory', 'workintry'),'sub-category', $selected_sub_category, '', $selected_category_id); ?>
				<p id="cl_subcategory-description" class="description">
					<?php  esc_html_e('Select Subcategory', 'workintry'); ?>
				</p>
			</div>
		</div>
	</div>
    <!-- Sub Category Ends -->
    <!-- Service Starts -->
    <div class="rwmb-meta-box" id="cl_service">
    	<div class="rwmb-field rwmb-taxonomy_advanced-wrapper">
    		<div class="rwmb-label">
				<label for="cl_service">
					<?php esc_html_e('Service', 'workintry'); ?>
				</label>
			</div>
			<div class="rwmb-input">
				<?php do_action('codesquare_workintry_print_taxonomy_options', 'gig_service', esc_html__('Select Service', 'workintry'),'gig-service', $selected_service, '', $selected_sub_category_id); ?>
				<p id="cl_subcategory-description" class="description">
					<?php  esc_html_e('Select Service', 'workintry'); ?>
				</p>
			</div>
		</div>
	</div>
    <!-- Service Ends -->   
    <!-- Gig Data -->
    <div class="wi-packagesedit">
	   <ul class="wi-packagesform">
	      <li class="wi-packageform">
	         <div class="wi-form">
	            <div>
	               <div class="form-group">
	                  <span class="form-title"><?php esc_html_e('Package 01 Title*:', 'workintry'); ?> <i class="ti-info-alt"></i></span>
	                  <input type="text" class="form-control gig-basic-title" name="basic[title]" placeholder="<?php esc_html_e('Add Title Here', 'workintry'); ?>" value="<?php echo esc_attr( $basic_title ); ?>">
	               </div>
	               <div class="form-group">
	                  <span class="form-title"><?php esc_html_e('Gig Description*:', 'workintry'); ?> <i class="ti-info-alt"></i></span>
	                  <textarea type="text" class="form-control gig-basic-desc" name="basic[description]" placeholder="<?php esc_html_e('Description', 'workintry'); ?>"><?php echo esc_html( $basic_description ); ?></textarea>
	               </div>
	            </div>
	         </div>
	      </li>
	      <li class="wi-pselectoption">
	         <span><?php esc_html_e('Delivery Time:', 'workintry'); ?></span>
	         <span class="wi-select gig-basic-delivery">
	            <?php do_action('codesquare_workintry_print_gig_delivery', 'basic', $basic_delivery) ?>
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
	      <li class="wi-pselectoption">
	         <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
	         <span class="wi-select gig-basic-revision">
	            <?php do_action('codesquare_workintry_print_gig_revisions', 'basic', $basic_revision) ?>
	         </span>
	      </li>
	      <li class="wi-totalprice basic-gig-price">
	         <span><?php esc_html_e('Price: ('. codesquare_workintry_default_system_currency_sign().')', 'workintry'); ?></span>
	         <em><?php do_action('codesquare_workintry_print_gig_price', 'basic', '10', $basic_price ); ?></em>
	      </li>
	   </ul>
	   <ul class="wi-packagesform">
	      <li class="wi-packageform">
	         <div class="wi-form">
	            <div>
	               <div class="form-group">
	                  <label class="form-title"><?php esc_html_e('Gig 02 Title*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
	                  <input type="text" class="form-control gig-gold-title" name="gold[title]" placeholder="<?php esc_attr_e('Add Title Here', 'workintry'); ?>" value="<?php echo esc_attr( $gold_title ); ?>">
	               </div>
	               <div class="form-group">
	                  <label class="form-title"><?php esc_html_e('Gig Description*:', 'workintry' ); ?><i class="ti-info-alt"></i></label>
	                  <textarea type="text" class="form-control gig-gold-desc" name="gold[description]" placeholder="<?php esc_attr_e('Description', 'workintry'); ?>"><?php echo esc_html( $gold_description ); ?></textarea>
	               </div>
	            </div>
	         </div>
	      </li>
	      <li class="wi-pselectoption gig-gold-delivery">
	         <span><?php esc_html_e('Delivery Time:', 'workintry'); ?></span>
	         <span class="wi-select">
	            <?php do_action('codesquare_workintry_print_gig_delivery', 'gold', $gold_delivery) ?>
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
	      <li class="wi-pselectoption">
	         <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
	         <span class="wi-select gig-gold-revision">
	            <?php do_action('codesquare_workintry_print_gig_revisions', 'gold', $gold_revision) ?>
	         </span>
	      </li>
	      <li class="wi-totalprice gold-gig-price">
	         <span><?php esc_html_e('Price: ('. codesquare_workintry_default_system_currency_sign().')', 'workintry'); ?></span>
	         <em><?php do_action('codesquare_workintry_print_gig_price', 'gold', '20', $gold_price ); ?></em>
	      </li>
	   </ul>
	   <ul class="wi-packagesform">
	      <li class="wi-packageform">
	         <div class="wi-form">
	            <div>
	               <div class="form-group">
	                  <label class="form-title"><?php esc_html_e('Gig 03 Title*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
	                  <input type="text" class="form-control gig-diamond-title" name="diamond[title]" placeholder="<?php esc_html_e('Add Title Here', 'workintry'); ?>" value="<?php echo esc_attr( $diamond_title ); ?>">
	               </div>
	               <div class="form-group">
	                  <label class="form-title"><?php esc_html_e('Gig Description*:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
	                  <textarea type="text" class="form-control gig-diamond-desc" name="diamond[description]" placeholder="<?php esc_attr_e('Description', 'workintry'); ?>"><?php echo esc_html( $diamond_description ); ?></textarea>
	               </div>
	            </div>
	         </div>
	      </li>
	      <li class="wi-pselectoption">
	         <span><?php esc_html_e('Delivery Time:', 'workintry'); ?></span>
	         <span class="wi-select gig-diamond-delivery">
	            <?php do_action('codesquare_workintry_print_gig_delivery', 'diamond', $diamond_delivery) ?>
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
	      <li class="wi-pselectoption">
	         <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
	         <span class="wi-select gig-diamond-revision">
	            <?php do_action('codesquare_workintry_print_gig_revisions', 'diamond', $diamond_revision) ?>
	         </span>
	      </li>
	      <li class="wi-totalprice diamond-gig-price">
	         <span><?php esc_html_e('Price: ('. codesquare_workintry_default_system_currency_sign().')', 'workintry'); ?></span>
	         <em><?php do_action('codesquare_workintry_print_gig_price', 'diamond', '30', $diamond_price ); ?></em>
	      </li>
	   </ul>
	   <div class="clear-all"></div>
	</div>
    <!-- Gig Data -->    
    <?php    
}

/*Append services for workintry*/
add_action( 'admin_footer', function() {
	global $post;	
	$post_type = !empty( $post->post_type ) ? $post->post_type : '';
	if( $post_type == 'workintry' ){
    ?>
    <!-- Printing all services -->
    <div class="wo-hidden-item">
		<?php 
			$services_terms = get_terms( array(
			    'taxonomy' => 'gig_service',
			    'hide_empty' => false,	    
				'number'        => 500000,
			) );
			?>	
			<?php 
			if( !empty( $services_terms ) ){ ?>		
			<?php 
			foreach ( $services_terms as $key => $subs_value ) {
			?>
			<div id="gig-service<?php echo esc_attr( $subs_value->term_id ); ?>-basic" class="service-0 hidden-service">
				<ul>			
					<?php 
						$gig_meta = get_term_meta( $subs_value->term_id, 'wimeta', true );
		        	if( !empty( $gig_meta ) ){ 
		        		$counter = 0;
						foreach ( $gig_meta as $value ) {
							$counter++;					
							if( $value['witype'] == 'check' ){
								?>
								<li>
									<input type="checkbox" name="basic[<?php echo esc_attr( $value['wititle'] ); ?>]" id="basic-Logo<?php echo esc_attr( $counter ); ?>">
									<label for="basic-Logo<?php echo esc_attr( $counter ); ?>"><?php echo esc_html( $value['wititle'] ); ?>: <i class="far fa-square"></i></label>
								</li>
								<?php 
							} elseif( $value['witype'] == 'selecte' ){
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
														<option value="<?php echo esc_attr( $inner_value ); ?>"><?php echo esc_html( $inner_value ); ?></option>
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
			</div>		
				<?php 	
			}  
		}?>	
		<!-- gold -->	
		<?php 
		if( !empty( $services_terms ) ){ ?>		
			<?php 
			foreach ( $services_terms as $key => $subs_value ) {
			?>	
			<div id="gig-service<?php echo esc_attr( $subs_value->term_id ); ?>-gold" class="service-0 hidden-service">
				<ul>			
					<?php 
						$gig_meta = get_term_meta( $subs_value->term_id, 'wimeta', true );
		        	if( !empty( $gig_meta ) ){ 
		        		$counter = 0;
						foreach ( $gig_meta as $value ) {
							$counter++;					
							if( $value['witype'] == 'check' ){
								?>
								<li>
									<input type="checkbox" name="gold[<?php echo esc_attr( $value['wititle'] ); ?>]" id="gold-Logo<?php echo esc_attr( $counter ); ?>">
									<label for="gold-Logo<?php echo esc_attr( $counter ); ?>"><?php echo esc_html( $value['wititle'] ); ?>: <i class="far fa-square"></i></label>
								</li>
								<?php 
							} elseif( $value['witype'] == 'selecte' ){
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
														<option value="<?php echo esc_attr( $inner_value ); ?>"><?php echo esc_html( $inner_value ); ?></option>
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
			</div>	
				<?php 	
			}  
		}?>
		<!-- diamond -->
		<?php 
		if( !empty( $services_terms ) ){ ?>		
			<?php 
			foreach ( $services_terms as $key => $subs_value ) {
			?>	
			<div id="gig-service<?php echo esc_attr( $subs_value->term_id ); ?>-diamond" class="service-0 hidden-service">
				<ul>			
					<?php 
						$gig_meta = get_term_meta( $subs_value->term_id, 'wimeta', true );
		        	if( !empty( $gig_meta ) ){ 
		        		$counter = 0;
						foreach ( $gig_meta as $value ) {
							$counter++;					
							if( $value['witype'] == 'check' ){
								?>
								<li>
									<input type="checkbox" name="diamond[<?php echo esc_attr( $value['wititle'] ); ?>]" id="diamond-Logo<?php echo esc_attr( $counter ); ?>">
									<label for="diamond-Logo<?php echo esc_attr( $counter ); ?>"><?php echo esc_html( $value['wititle'] ); ?>: <i class="far fa-square"></i></label>
								</li>
								<?php 
							} elseif( $value['witype'] == 'selecte' ){
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
														<option value="<?php echo esc_attr( $inner_value ); ?>"><?php echo esc_html( $inner_value ); ?></option>
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
			</div>
				<?php 	
			}  
		}
		?>
	</div>
    <!-- Printing services ends -->
    <?php
	}
} );
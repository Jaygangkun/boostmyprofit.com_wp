<?php
	//Get terms
	$terms = get_terms( array(
	    'taxonomy' 		=> 'gig_category',
	    'hide_empty' 	=> false,
	    'parent'       	=> 0,
		'number'        => 5000,
	) );
	?>
	<div id="gig-cat0" class="gig-cat0 hidden-cat">
		<select class="gig-cat" name="gig-category">
			<option class="none" value=""><?php esc_attr_e('Select Category', 'workintry'); ?></option>
			<?php 
		if( !empty( $terms ) ){
			foreach ( $terms as $key => $value ) {
			?>
				<option class="gig-cat<?php echo esc_attr( $value->term_id );  ?>" value="<?php echo esc_attr( $value->slug ); ?>"><?php echo esc_html( $value->name ); ?></option>
			<?php 	
		} }?>
		</select>
	</div>
	<?php 
		if( !empty( $terms ) ){
			foreach ( $terms as $key => $value ) {
				?>
				<div id="gig-cat<?php echo esc_attr( $value->term_id ); ?>" class="gig-cat<?php echo esc_attr( $value->term_id ); ?> hidden-cat">
					<select class="gig-cat" name="sub-category">
            			<option class="none" value=""><?php esc_attr_e('Select Sub Category', 'workintry'); ?></option>
				<?php 
				$args = array(
                	'hide_empty' => false, 
	                'meta_query' => array(
	                    array(
	                       'key'       => 'parent_category',
	                       'value'     => $value->term_id,
	                       'compare'   => '='
	                    )
	                ),
                	'taxonomy'  => 'gig_sub_category',
            	);
            	$sub_terms = get_terms( $args ); 
            	if( !empty( $sub_terms ) ){
            		?>
            		
            		<?php 
            		foreach ($sub_terms as $sub_key => $sub_value) {
            			?>
            				<option class="service-<?php echo esc_attr( $sub_value->term_id ); ?>" value="<?php echo esc_attr( $sub_value->slug ); ?>"><?php echo esc_html( $sub_value->name ); ?></option>
            			<?php 
            		}
            		?>
            		
            		<?php 
            	}
				?>
				</select>
				</div>
				<?php 
			}
		}
	?>
<?php 



?>
<!-- Services -->
<?php
	//Get terms
	$subs_terms = get_terms( array(
	    'taxonomy' 		=> 'gig_sub_category',
	    'hide_empty' 	=> false,	    
		'number'        => 500000,
	) );
	?>
	<div id="service-0" class="service-0 hidden-service">
		<select class="gig-service" name="gig-service">
			<option class="none" value=""><?php esc_attr_e('Select Service', 'workintry'); ?></option>
		</select>
	</div>
	<?php 
		if( !empty( $subs_terms ) ){ ?>
			
			<?php 
			foreach ( $subs_terms as $key => $subs_value ) {
			?>
			<div id="service-<?php echo esc_attr( $subs_value->term_id ); ?>" class="service-0 hidden-service">
				<select class="gig-service" name="gig-service">
					<option class="none" value=""><?php esc_attr_e('Select Service', 'workintry'); ?></option>
			<?php 
				$innr_args = array(
            		'hide_empty' => false, 
	                'meta_query' => array(
	                    array(
	                       'key'       => 'gig_meta',
	                       'value'     => $subs_value->term_id,
	                       'compare'   => '='
	                    )
	                ),
            		'taxonomy'  => 'gig_service',
        		);
            	$services = get_terms( $innr_args ); 
            	if( !empty( $services ) ){ ?>
            	
				<?php foreach($services as $key=>$service) { ?>
					<option class="gig-service<?php echo esc_attr( $service->term_id );  ?>" value="<?php echo esc_attr( $service->slug ); ?>"><?php echo esc_html( $service->name ); ?></option>
				<?php } ?>
				
            	<?php 
            	}
				?>
				</select>
			</div>
			<?php 	

			} ?>
			
			<?php 
		}?>
		
	<?php 		
	?>

<!-- Get gig meta based on service -->

<!-- Services Ends -->
<!-- Services Gig Meta -->
<?php
$services_terms = get_terms( array(
    'taxonomy' => 'gig_service',
    'hide_empty' => false,	    
	'number'        => 500000,
) );
?>	
<!-- Basic -->
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
}?>
<!-- Services Gig Meta -->
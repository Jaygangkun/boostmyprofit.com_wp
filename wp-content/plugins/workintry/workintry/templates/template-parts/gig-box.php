<?php 
/* Detail Page Gig Box
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
global $post, $current_user;
$post_author_id	= $post->post_author;
$current_user_id = '';
if( is_user_logged_in() ){
    $current_user_id = $current_user->ID;
}
$post_id 		= $post->ID;
//Get Gig details
$basic_gigs 	= get_post_meta( $post_id, 'cl_gig_basic', true );
$gold_gigs 		= get_post_meta( $post_id, 'cl_gig_gold', true );
$diamond_gigs 	= get_post_meta( $post_id, 'cl_gig_diamond', true );

//Basic Details
$basic_price 		= !empty( $basic_gigs['price'] ) ? $basic_gigs['price'] : '';
$basic_delivery 		= !empty( $basic_gigs['delivery'] ) ? $basic_gigs['delivery'] : '';
$basic_revisions 		= !empty( $basic_gigs['revisions'] ) ? $basic_gigs['revisions'] : '';
if( !empty( $basic_revisions ) ){
	$basic_revisions = sprintf("%02d", $basic_revisions);
}
//Basic Delivery
$basic_delivery_padded = '';
if( !empty( $basic_delivery ) ){
	$basic_delivery_padded = sprintf("%02d", $basic_delivery);
}

$gold_price 		= !empty( $gold_gigs['price'] ) ? $gold_gigs['price'] : '';
$gold_delivery 		= !empty( $gold_gigs['delivery'] ) ? $gold_gigs['delivery'] : '';
$gold_revisions 		= !empty( $gold_gigs['revisions'] ) ? $gold_gigs['revisions'] : '';
if( !empty( $gold_revisions ) ){
	$gold_revisions = sprintf("%02d", $gold_revisions);
}
//Gold Delivery
$gold_delivery_padded = '';
if( !empty( $gold_delivery ) ){
	$gold_delivery_padded = sprintf("%02d", $gold_delivery);
}

$diamond_price 	= !empty( $diamond_gigs['price'] ) ? $diamond_gigs['price'] : '';
$diamond_delivery 	= !empty( $diamond_gigs['delivery'] ) ? $diamond_gigs['delivery'] : '';
$diamond_revisions 	= !empty( $diamond_gigs['revisions'] ) ? $diamond_gigs['revisions'] : '';
if( !empty( $diamond_revisions ) ){
	$diamond_revisions = sprintf("%02d", $diamond_revisions);
}
//Diamond Delivery
$diamond_delivery_padded = '';
if( !empty( $diamond_delivery ) ){
	$diamond_delivery_padded = sprintf("%02d", $diamond_delivery);
}
//Fast Delivery
$cl_fast 		= get_post_meta( $post_id, 'cl_fast', true );
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

//Default currency sign
$cl_default_currency = codesquare_workintry_default_system_currency_sign();

//Basic Padded
$basic_fast_padded_delivery = '';
if( !empty( $basic_fast ) && $basic_fast < 10 ){
	$basic_fast_padded_delivery = sprintf("%02d", $basic_fast);
}

//Gold Padded
$gold_fast_padded_delivery = '';
if( !empty( $gold_fast ) && $gold_fast < 10 ){
	$gold_fast_padded_delivery = sprintf("%02d", $gold_fast);
}

//Diamond Padded
$diamond_fast_padded_delivery = '';
if( !empty( $diamond_fast ) && $diamond_fast < 10 ){
	$diamond_fast_padded_delivery = sprintf("%02d", $diamond_fast);
}

//Final price
$basic_final 	= '';
$gold_final 	= '';
$diamond_final 	= '';
//Basic final
if( !empty( $basic_price ) && !empty( $basic_fast_price ) ){
	$basic_final = $basic_price + $basic_fast_price;
}

//Gold final
if( !empty( $gold_price ) && !empty( $gold_fast_price ) ){
	$gold_final = $gold_price + $gold_fast_price;
}

//Diamond final
if( !empty( $diamond_price ) && !empty( $diamond_fast_price ) ){
	$diamond_final = $diamond_price + $diamond_fast_price;
}

//Service
$service 		= wp_get_post_terms( $post_id, 'gig_service' );
$selected_service_id = !empty( $service ) ? $service[0]->term_id : '';
$gig_meta 		= get_term_meta( $selected_service_id, 'wimeta', true);
?>
<div class="wi-packages">
    <div class="wi-package">
        <div class="wi-spackagetitle">
            <h4><?php esc_html_e('Basic Plan', 'workintry'); ?></h4>
            <?php if( !empty( $basic_price ) ){ ?>
	            <h3>
	            	<sup>
	            		<?php echo esc_html( $cl_default_currency ); ?>
	            	</sup>
	            	<?php echo esc_html( $basic_price ); ?>
	            </h3>
            <?php } ?>
            <?php if( !empty( $basic_gigs['description'] ) ){ ?>
            	<span>
            		<?php echo esc_html( $basic_gigs['description'] );  ?>
            	</span>
            <?php } ?>
        </div>
        <ul class="wi-packagelist wi-basic">
        	<?php 
        	if( !empty( $gig_meta ) ){
        		foreach ($gig_meta as $key => $value) {
        			if( $value['witype'] == 'check' ){
    				if( !empty( $value['wititle'] ) ){
    					$class = '';	    				
	    				$got_value = !empty( $basic_gigs[$value['wititle'] ] ) ? $basic_gigs[$value['wititle'] ] : '';
	    				if( $got_value == 1 || $got_value == 'on' ){
	    					$class = 'wi-checkpackage';
	    				}
        	?>
	        	<li class="<?php echo esc_attr( $class ); ?>">
	                <span><?php echo esc_html( $value['wititle'] ); ?> <em><i class="fa fa-check"></i></em></span>
	            </li>
        	<?php } } else{ ?>
        		<?php 
        			if( !empty( $value['wititle'] ) ){ 
        			$got_value = !empty( $basic_gigs[$value['wititle'] ] ) ? $basic_gigs[$value['wititle'] ] : '';
        			?>
	        		<li>
	                	<span><?php echo esc_html( $value['wititle'] ); ?> <em><?php echo esc_html( $got_value ); ?></em></span>
	            	</li>
            	<?php } ?>
        	<?php } } }?>
            <?php if( !empty( $basic_revisions ) ){ ?>
	            <li>
	                <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
	                <em><?php echo esc_html( $basic_revisions ); ?></em>
	            </li>
        	<?php } ?>
            <li>
                <span>
                	<?php esc_html_e('Delivery Time:', 'workintry'); ?>
                </span>
                <div class="wi-packageradio">
                    <input type="radio" name="Days" id="normal" checked="" data-price="<?php echo esc_attr( $basic_price ); ?>" value="basic">
                    <label for="normal"><i class="far fa-circle"></i><?php echo esc_html( $basic_delivery_padded ); ?>&nbsp;<?php esc_html_e('Day(s)', 'workintry'); ?></label>
                    <?php if( !empty( $basic_fast_padded_delivery ) ){ ?>
                    <input type="radio" name="Days" id="fast" data-price="<?php echo esc_attr( $basic_final ); ?>" value="basicfast">
                    <label for="fast"><i class="far fa-circle"></i><?php echo esc_html( $basic_fast_padded_delivery ); echo ' '; echo esc_html_e('Day(s)', 'workintry'); ?>&nbsp; 
                    (+<?php echo esc_html( $cl_default_currency ); echo esc_html( $basic_fast_price ); ?>)
                	</label>
                	<?php } ?>
                </div>
            </li>
            <li class="wi-packagefooter">
                <span><?php esc_html_e('Total:', 'workintry'); ?><strong>
                	<sup><?php echo esc_html( $cl_default_currency ); ?></sup>
                	<span class="wi-final-price"><?php if( !empty( $basic_price ) ){ echo esc_html( $basic_price ); } ?></span>
	                </strong>
	            </span>
                <?php if( $current_user_id == $post_author_id ){ ?>
                    <a href="javascript:void(0)" class="wi-btn"><?php esc_html_e('Your own gig', 'workintry'); ?></a>
                <?php } else { ?>
                    <a href="javascript:void(0)" class="wi-btn wi-buy-gig" data-id="<?php echo esc_attr( $post_id ); ?>" data-pkg="basic"><?php esc_html_e('Get Started Now', 'workintry'); ?></a>
                <?php } ?>
            </li>
        </ul>
    </div>
    <div class="wi-package">
        <div class="wi-spackagetitle">
            <h4><?php esc_html_e('Gold Plan', 'workintry'); ?></h4>
            <?php if( !empty( $gold_price ) ){ ?>
	            <h3>
	            	<sup>
	            		<?php echo esc_html( $cl_default_currency ); ?>
	            	</sup>
	            	<?php echo esc_html( $gold_price ); ?>
	            </h3>
            <?php } ?>
            <?php if( !empty( $gold_gigs['description'] ) ){ ?>
            	<span>
            		<?php echo esc_html( $gold_gigs['description'] );  ?>
            	</span>
            <?php } ?>
        </div>
        <ul class="wi-packagelist wi-gold">
            <?php 
        	if( !empty( $gig_meta ) ){
        		foreach ($gig_meta as $key => $value) {
        			if( $value['witype'] == 'check' ){
    				if( !empty( $value['wititle'] ) ){
    					$class = '';	    				
	    				$got_value = !empty( $gold_gigs[$value['wititle'] ] ) ? $gold_gigs[$value['wititle'] ] : '';
	    				if( $got_value == 1 || $got_value == 'on' ){
	    					$class = 'wi-checkpackage';
	    				}
        	?>
	        	<li class="<?php echo esc_attr( $class ); ?>">
	                <span><?php echo esc_html( $value['wititle'] ); ?> <em><i class="fa fa-check"></i></em></span>
	            </li>
        	<?php } } else{ ?>
        		<?php 
        			if( !empty( $value['wititle'] ) ){ 
        			$got_value = !empty( $gold_gigs[$value['wititle'] ] ) ? $gold_gigs[$value['wititle'] ] : '';
        			?>
	        		<li>
	                	<span><?php echo esc_html( $value['wititle'] ); ?> <em><?php echo esc_html( $got_value ); ?></em></span>
	            	</li>
            	<?php } ?>
        	<?php } } }?>
            <?php if( !empty( $gold_revisions ) ){ ?>
	            <li>
	                <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
	                <em><?php echo esc_html( $gold_revisions ); ?></em>
	            </li>
        	<?php } ?>
            <li>
                <span>
                	<?php esc_html_e('Delivery Time:', 'workintry'); ?>
                </span>
                <div class="wi-packageradio">
                    <input type="radio" name="Days" id="pkg2" data-price="<?php echo esc_attr( $gold_price ); ?>" value="gold">
                    <label for="pkg2"><i class="far fa-circle"></i><?php echo esc_html( $gold_delivery_padded ); ?>&nbsp;<?php esc_html_e('Day(s)', 'workintry'); ?></label>
                    <?php if( !empty( $gold_fast_padded_delivery ) ){ ?>
                    <input type="radio" name="Days" id="goldfast" data-price="<?php echo esc_attr( $gold_final ); ?>" value="goldfast">
                    <label for="goldfast"><i class="far fa-circle"></i><?php echo esc_html( $gold_fast_padded_delivery ); echo ' '; echo esc_html_e('Day(s)', 'workintry'); ?>&nbsp; 
                    (+<?php echo esc_html( $cl_default_currency ); echo esc_html( $gold_fast_price ); ?>)
                	</label>
                	<?php } ?>
                </div>
            </li>
            <li class="wi-packagefooter">
                <span><?php esc_html_e('Total:', 'workintry'); ?><strong>
                	<sup><?php echo esc_html( $cl_default_currency ); ?></sup>
                	<span class="wi-final-price"><?php if( !empty( $gold_price ) ){ echo esc_html( $gold_price ); } ?></span>
	                </strong>
	            </span>
                <?php if( $current_user_id == $post_author_id ){ ?>
                    <a href="javascript:void(0)" class="wi-btn"><?php esc_html_e('Your own gig', 'workintry'); ?></a>
                <?php } else { ?>
                    <a href="javascript:void(0)" class="wi-btn wi-buy-gig" data-id="<?php echo esc_attr( $post_id ); ?>" data-pkg="gold"><?php esc_html_e('Get Started Now', 'workintry'); ?></a>
                <?php } ?>
            </li>
        </ul>
    </div>
    <div class="wi-package">
         <div class="wi-spackagetitle">
            <h4><?php esc_html_e('Diamond Plan', 'workintry'); ?></h4>
            <?php if( !empty( $diamond_price ) ){ ?>
	            <h3>
	            	<sup>
	            		<?php echo esc_html( $cl_default_currency ); ?>
	            	</sup>
	            	<?php echo esc_html( $diamond_price ); ?>
	            </h3>
            <?php } ?>
            <?php if( !empty( $diamond_gigs['description'] ) ){ ?>
            	<span>
            		<?php echo esc_html( $diamond_gigs['description'] );  ?>
            	</span>
            <?php } ?>
        </div>
        <ul class="wi-packagelist wi-diamond">
            <?php 
        	if( !empty( $gig_meta ) ){
        		foreach ($gig_meta as $key => $value) {
        			if( $value['witype'] == 'check' ){
    				if( !empty( $value['wititle'] ) ){
    					$class = '';	    				
	    				$got_value = !empty( $diamond_gigs[$value['wititle'] ] ) ? $diamond_gigs[$value['wititle'] ] : '';
	    				if( $got_value == 1 || $got_value == 'on' ){
	    					$class = 'wi-checkpackage';
	    				}
        	?>
	        	<li class="<?php echo esc_attr( $class ); ?>">
	                <span><?php echo esc_html( $value['wititle'] ); ?> <em><i class="fa fa-check"></i></em></span>
	            </li>
        	<?php } } else{ ?>
        		<?php 
        			if( !empty( $value['wititle'] ) ){ 
        			$got_value = !empty( $diamond_gigs[$value['wititle'] ] ) ? $diamond_gigs[$value['wititle'] ] : '';
        			?>
	        		<li>
	                	<span><?php echo esc_html( $value['wititle'] ); ?> <em><?php echo esc_html( $got_value ); ?></em></span>
	            	</li>
            	<?php } ?>
        	<?php } } }?>           
            <?php if( !empty( $diamond_revisions ) ){ ?>
	            <li>
	                <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
	                <em><?php echo esc_html( $diamond_revisions ); ?></em>
	            </li>
        	<?php } ?>
            <li>
                <span>
                	<?php esc_html_e('Delivery Time:', 'workintry'); ?>
                </span>
                <div class="wi-packageradio">
                    <input type="radio" name="Days" id="pkg3" data-price="<?php echo esc_attr( $diamond_price ); ?>" value="diamond">
                    <label for="pkg3"><i class="far fa-circle"></i><?php echo esc_html( $diamond_delivery_padded ); ?>&nbsp;<?php esc_html_e('Day(s)', 'workintry'); ?></label>
                    <?php if( !empty( $diamond_fast_padded_delivery ) ){ ?>
                    <input type="radio" name="Days" id="diamondfast" data-price="<?php echo esc_attr( $diamond_final ); ?>" value="diamondfast">
                    <label for="diamondfast"><i class="far fa-circle"></i><?php echo esc_html( $diamond_fast_padded_delivery ); echo ' '; echo esc_html_e('Day(s)', 'workintry'); ?>&nbsp; 
                    (+<?php echo esc_html( $cl_default_currency ); echo esc_html( $diamond_fast_price ); ?>)
                	</label>
                	<?php } ?>
                </div>
            </li>
            <li class="wi-packagefooter">
                <span><?php esc_html_e('Total:', 'workintry'); ?><strong>
                	<sup><?php echo esc_html( $cl_default_currency ); ?></sup>
                	<span class="wi-final-price"><?php if( !empty( $diamond_price ) ){ echo esc_html( $diamond_price ); } ?></span>
	                </strong>
	            </span>
                <?php if( $current_user_id == $post_author_id ){ ?>
                    <a href="javascript:void(0)" class="wi-btn"><?php esc_html_e('Your own gig', 'workintry'); ?></a>
                <?php } else { ?>
                    <a href="javascript:void(0)" class="wi-btn wi-buy-gig" data-id="<?php echo esc_attr( $post_id ); ?>" data-pkg="diamond"><?php esc_html_e('Get Started Now', 'workintry'); ?></a>
                <?php } ?>
            </li>
        </ul>
    </div>
</div>

<!-- Payments -->
<?php 
if( is_user_logged_in() ){
global $current_user;
$user_id = $current_user->ID;
$total = codesquare_workintry_get_user_earnings( $user_id );
if( $total > $basic_price ){
?>
<!-- Payments -->
<!-- Modal -->
<div id="wpEarningsModal" class="modal fade cf-registerpopup" data-id="<?php echo esc_attr( $total ); ?>" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">        
                <h4 class="modal-title">
                    <?php esc_html_e('Available Earnings', 'workintry'); ?>
                </h4>
                <button type="button" class="close cp-btn" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="top">
                    <?php esc_html_e('Currently you have available earnings in your account', 'workintry'); ?> <br>
                    <?php esc_html_e('Which can be used to buy gig.', 'workintry'); ?>
                    <strong class="earnings"><?php echo esc_html( $cl_default_currency ); ?><?php echo esc_html( $total ); ?></strong>
                </p>
                <div class="bottom">
                    <a type="button" class="cp-btn hp-btn btn btn-default hp-buy-gig" data-id="<?php echo esc_attr( $post->ID ); ?>" data-type="earnings"><?php esc_html_e('Pay from earnings', 'workintry'); ?></a>
                    <a type="button" class="cp-btn hp-btn btn btn-default hp-buy-gig hp-blue" data-id="<?php echo esc_attr( $post->ID ); ?>" data-type="default"><?php esc_html_e('Pay via payment method', 'workintry'); ?></a>
                </div>
            </div>
            <?php wp_nonce_field('lost_password_request', 'lost_password_request'); ?>  
            <div class="modal-footer">
                <button type="button" class="cp-btn btn btn-default" data-dismiss="modal"><?php esc_html_e('Close', 'workintry'); ?></button>
            </div>
        </div>
        <input type="hidden" id="hp-gig-val" value="">
    </div>
</div>
<?php } }
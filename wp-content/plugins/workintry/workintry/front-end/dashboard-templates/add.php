<?php 
/**
 * Add new ad page template
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
$first_name = get_user_meta( $current_user->ID, 'first_name', true );
$last_name  = get_user_meta( $current_user->ID, 'last_name', true );
$country 	= get_post_meta( $current_user->ID, 'country', true );
$city 		= get_post_meta( $current_user->ID, 'city', true );
$user_id    = !empty( $_GET['identity'] ) ? intval( sanitize_text_field(  $_GET['identity'] ) ) : '';
//Default currency sign
$cl_default_currency = codesquare_workintry_default_system_currency_sign();
//Enquqe scripts
wp_enqueue_script('jquery-ui');
?>
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
                        <h3><?php esc_html_e('Add Gig Details', 'workintry'); ?></h3>
                     </div>
                     <div class="wi-dbbox-content">
                        <div class="wi-form">
                           <div class="form-group">
                              <label class="form-title">
                              	<?php esc_html_e('Title*:', 'workintry'); ?>
                              	<i class="ti-info-alt"></i>
                              </label>
                              <input type="text" class="form-control gig-title-val" name="title" placeholder="<?php esc_attr_e('Gig Title', 'workintry') ?>">
                           </div>
                           <div class="form-group">
                              <label class="form-title">
                              	<?php esc_html_e('Description*:', 'workintry'); ?> 
                              	<i class="ti-info-alt"></i>
                              </label>
                              <?php do_action( 'codesquare_workintry_print_post_editor', '' ); ?>                              
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
                              <?php do_action('codesquare_workintry_print_taxonomy_options', 'gig_category', esc_html__('Select Category', 'workintry'),'gig-category', '', ''); ?>   	
                              </span>
                           </div>
                           <div class="form-group form-group-half">
                              <label class="form-title"><?php esc_html_e('Select Subcategory*:', 'workintry'); ?><i class="ti-info-alt"></i> </label>
                              <span class="wi-select sub-cats">
                                <select><option></option></select>
                              </span>
                           </div>
                           <div class="form-group form-group-half">
                              <label class="form-title">
                              	<?php esc_html_e('Select Service*:', 'workintry'); ?>
                              	<i class="ti-info-alt"></i> 
                              </label>
                              <span class="wi-select gig-services">
                                 <select><option></option>Select</select>
                              </span>
                           </div>                           	
                        </div>
                     </div>
                  </div>
                  <div class="wi-dbbox">
                     <div class="wi-dbbox-title">
                        <h3>
                        	<?php esc_html_e('Add Packages', 'workintry'); ?>
                        </h3>
                     </div>
                     <div class="wi-dbbox-content">
                        <div class="wi-packagesedit">
                           <ul class="wi-packagesform">
                              <li class="wi-packageform">
                                 <div class="wi-form">
                                    <div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Package 01 Title*:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
                                          <input type="text" class="form-control gig-basic-title" name="basic[title]" placeholder="<?php esc_html_e('Add Title Here', 'workintry'); ?>">
                                       </div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Gig Description*:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
                                          <textarea type="text" class="form-control gig-basic-desc" name="basic[description]" placeholder="<?php esc_html_e('Description', 'workintry'); ?>"></textarea>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li class="wi-pselectoption">
                                 <span><?php esc_html_e('Delivery Time:', 'workintry'); ?></span>
                                 <span class="wi-select gig-basic-delivery">
                                    <?php do_action('codesquare_workintry_print_gig_delivery', 'basic') ?>
                                 </span>
                              </li>
                              <li class="wi-packageform embed-gig-basic"></li>
                              <li class="wi-pselectoption">
                                 <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
                                 <span class="wi-select gig-basic-revision">
                                    <?php do_action('codesquare_workintry_print_gig_revisions', 'basic') ?>
                                 </span>
                              </li>
                              <li class="wi-totalprice basic-gig-price">
                                 <span><?php esc_html_e('Price: ('. codesquare_workintry_default_system_currency_sign().')', 'workintry'); ?></span>
                                 <em><?php do_action('codesquare_workintry_print_gig_price', 'basic', '10' ); ?></em>
                              </li>
                           </ul>
                           <ul class="wi-packagesform">
                              <li class="wi-packageform">
                                 <div class="wi-form">
                                    <div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Gig 02 Title*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
                                          <input type="text" class="form-control gig-gold-title" name="gold[title]" placeholder="<?php esc_attr_e('Add Title Here', 'workintry'); ?>">
                                       </div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Gig Description*:', 'workintry' ); ?><i class="ti-info-alt"></i></label>
                                          <textarea type="text" class="form-control gig-gold-desc" name="gold[description]" placeholder="<?php esc_attr_e('Description', 'workintry'); ?>"></textarea>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li class="wi-pselectoption gig-gold-delivery">
                                 <span><?php esc_html_e('Delivery Time:', 'workintry'); ?></span>
                                 <span class="wi-select">
                                    <?php do_action('codesquare_workintry_print_gig_delivery', 'gold') ?>
                                 </span>
                              </li>
                              <li class="wi-packageform embed-gig-gold"></li>
                              <li class="wi-pselectoption">
                                 <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
                                 <span class="wi-select gig-gold-revision">
                                    <?php do_action('codesquare_workintry_print_gig_revisions', 'gold') ?>
                                 </span>
                              </li>
                              <li class="wi-totalprice gold-gig-price">
                                 <span><?php esc_html_e('Price: ('. codesquare_workintry_default_system_currency_sign().')', 'workintry'); ?></span>
                                 <em><?php do_action('codesquare_workintry_print_gig_price', 'gold', '20' ); ?></em>
                              </li>
                           </ul>
                           <ul class="wi-packagesform">
                              <li class="wi-packageform">
                                 <div class="wi-form">
                                    <div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Gig 03 Title*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
                                          <input type="text" class="form-control gig-diamond-title" name="diamond[title]" placeholder="<?php esc_html_e('Add Title Here', 'workintry'); ?>">
                                       </div>
                                       <div class="form-group">
                                          <label class="form-title"><?php esc_html_e('Gig Description*:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
                                          <textarea type="text" class="form-control gig-diamond-desc" name="diamond[description]" placeholder="<?php esc_attr_e('Description', 'workintry'); ?>"></textarea>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li class="wi-pselectoption">
                                 <span><?php esc_html_e('Delivery Time:', 'workintry'); ?></span>
                                 <span class="wi-select gig-diamond-delivery">
                                    <?php do_action('codesquare_workintry_print_gig_delivery', 'diamond') ?>
                                 </span>
                              </li>
                              <li class="wi-packageform embed-gig-diamond"></li>
                              <li class="wi-pselectoption">
                                 <span><?php esc_html_e('Revisions:', 'workintry'); ?></span>
                                 <span class="wi-select gig-diamond-revision">
                                    <?php do_action('codesquare_workintry_print_gig_revisions', 'diamond') ?>
                                 </span>
                              </li>
                              <li class="wi-totalprice diamond-gig-price">
                                 <span><?php esc_html_e('Price: ('. codesquare_workintry_default_system_currency_sign().')', 'workintry'); ?></span>
                                 <em><?php do_action('codesquare_workintry_print_gig_price', 'diamond', '30' ); ?></em>
                              </li>
                           </ul>
                        </div>
                        <div class="wi-addservices">
                           <div class="wi-addservicestitle">
                              <h3><?php esc_html_e('Add Gig Extra Services', 'workintry'); ?></h3> 
                           </div>
                           <div class="wi-addservicesinfo">
                              <div class="wi-addservicesinput">
                                 <div class="form-group wi-adddeliverycheckwrap">
                                    <div class="wi-adddeliverycheck">
                                       <input type="checkbox" name="fast" id="Delivery">
                                       <label for="Delivery"><i class="far fa-square"></i> <?php esc_html_e('Fast Gig Delivery', 'workintry'); ?></label>
                                    </div>                             
                                 </div>
                                 <div class="wi-addservicescollapse">
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
                                                            <?php do_action('codesquare_workintry_print_gig_delivery', 'basicfast') ?>
                                                         </span>
                                                      </div>
                                                      <div class="form-group form-group-half">
                                                         <label class="form-title"><?php esc_html_e('For an Extra Price*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
                                                         <span class="wi-select">
                                                            <?php do_action('codesquare_workintry_print_gig_price', 'basicfast', '10' ); ?>
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
                                                            <?php do_action('codesquare_workintry_print_gig_delivery', 'goldfast') ?>
                                                         </span>
                                                         </span>
                                                      </div>
                                                      <div class="form-group form-group-half">
                                                         <label class="form-title"><?php esc_html_e('For an extra price*:', 'workintry' ); ?> <i class="ti-info-alt"></i></label>
                                                         <span class="wi-select">
                                                            <?php do_action('codesquare_workintry_print_gig_price', 'goldfast', '10' ); ?>
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
                                                            <?php do_action('codesquare_workintry_print_gig_delivery', 'diamondfast') ?>
                                                         </span>
                                                      </div>
                                                      <div class="form-group form-group-half">
                                                         <label class="form-title"><?php esc_html_e('For an extra price*:', 'workintry'); ?> <i class="ti-info-alt"></i></label>
                                                         <span class="wi-select">
                                                            <?php do_action('codesquare_workintry_print_gig_price', 'diamondfast', '30' ); ?>
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
							<input type="text" name="tags" class="form-control" placeholder="<?php esc_attr_e('Tags', 'workintry'); ?>" data-role="tagsinput">	
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
                        	<span class="wi-remove-faq"><?php esc_html_e('Click Add FAQ+ button above in the right corner to add your FAQ', 'workintry'); ?>
                        	</span> 
                     	</div>
                     </div>
                  </div>  
                  	<!-- Featured --> 
                  	<div class="wi-dbbox">
                  		<div class="wi-dbbox-title">
	                        <h3><?php esc_html_e('Gig Promotions', 'workintry' ); ?></h3>                 
	                     </div>
	                     <div class="form-group">
	                     	<?php do_action('codesquare_workintry_print_featured_ad_form'); ?>
	                     </div>
                  	</div>                                 
                  <!-- Featured -->
                  <?php wp_nonce_field('add_new_ad_form', 'add_new_ad_form'); ?>	
                  <div class="wi-dbboxbtns">                  	
                     <a href="#" class="wi-btn cf-insert-gig" data-type="add"><?php esc_html_e('Post Now', 'workintry'); ?></a>
                     <em><?php esc_html_e('Click "Post Now"  button to post your new service into list', 'workintry'); ?></em>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
   <!-- ==== Search Result End ==== -->
</main>
<!--*** Main End ***-->
<!-- custom code ends -->
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
require_once codesquare_workintry_addon_template_exsits('workintry/front-end/categories-data');
?>

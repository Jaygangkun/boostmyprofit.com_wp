"use strict";
jQuery(document).ready(function(){	
	var ajax = custom_vars.ajaxurl;		
	//Set custom post status
	jQuery("#post-status-select .save-post-status").on('click', function(){
   		//Get status value
	    var selectedValue = jQuery("#post_status").val();    
	    if( selectedValue == "sold" ){
	        jQuery("#save-action #save-post").val("Save as Sold");
	    } else if( selectedValue == "package" ){
	        jQuery("#save-action #save-post").val("Save as Package");
	    }
    });

	//Set to sold button if post status is sold
    var selectedValue = jQuery('#post_status').val();
    if( selectedValue == 'sold' ){
    	jQuery("#save-action #save-post").val("Save as Sold");
    } if( selectedValue == 'package' ){
    	jQuery("#save-action #save-post").val("Save as Package");
    }
	
	//Send payments    
	jQuery('.cl-make-payments').on('click', function(){				
		if (confirm('Are you sure you want to release payments to your users?')) {
		    var percent = jQuery('#percent').val();
		    var status = jQuery('#api_status').val();
		    var user = jQuery('#paypal_user').val();
		    var password = jQuery('#paypal_password').val();
		    var signature = jQuery('#paypal_signature').val();
		    if( user == '' || password == '' || signature == '' ){
		    	alert('Make sure you set your API ID, password and signature');
		    	return false;
		    }
		    jQuery('.release-payments').append('<p class="loading">Processing...</p>')
		    //Send call to make payment
		    jQuery.ajax({
	            type: "POST",
	            url: ajax,
	            data: 'action=codesquare_workintry_process_payment',
	            dataType: "json",
	            success: function (response) {
	            	jQuery('body').find('.loading').remove();
	                if (response.type == 'success') {
	                    alert(response.message);
	                } else {
						alert(response.message);
	                }
	            }
        	});
		} else {
		    //Do Nothing
		}
	});

	//Get sub categories
    jQuery('#cl_category select').on('change', function() {
        var that = jQuery(this);
        // Get the term ID on select
        var term_id = that.val();

        // Create a div to show the response
        jQuery('#gig-details').append('<div id="meta-taxonomy-response"></div>');

        var data = {
            'action': 'codesquare_workintry_gt_data_from_term',
            'term_id': term_id,
        };

        jQuery.post(ajaxurl, data, function(response) {
        	if( jQuery('.cl-sub-cat-wrapper').length ){
        		jQuery('.cl-sub-cat-wrapper').remove();
        	}
        	jQuery('#cl_sub_category').html('');
            jQuery('#cl_sub_category').html(response);
        });
    });
    //Get services from sub categories
    jQuery(document).on('change','.rwmb-meta-box #cl_sub_category', function() {           
        var that = jQuery(this);
        // Get the term ID on select
        var term_id = that.val();

        // Create a div to show the response
    jQuery('#gig-details').append('<div id="meta-taxonomy-response"></div>');
    jQuery('#gig-details').append('<div id="meta-basic-gig"></div>');
    jQuery('#gig-details').append('<div id="meta-gold-gig"></div>');
    jQuery('#gig-details').append('<div id="meta-diamond-gig"></div>');

        var data = {
            'action': 'codesquare_workintry_get_services_form_cat',
            'term_id': term_id,
        };

        jQuery.post(ajaxurl, data, function(response) {
        	jQuery('.cl-services-wrapper').remove();
        	jQuery('#cl_service').html('');
            jQuery('#cl_service').html(response);
        });
    });

    //Get Gigs from the list
    jQuery(document).on('change', '#cl_service', function(){
		var element = jQuery(this).find('option:selected'); 
        var itemClass = element.attr("class"); 
		if( itemClass == 'none' || itemClass == undefined ){	
			jQuery('.gig-services select').html('<option value="">Select Service</option>');
			return false;
		}		
		var services 		= jQuery('#'+itemClass+'-basic').html();		
		var servicesGold 	= jQuery('#'+itemClass+'-gold').html();
		var servicesDiamond = jQuery('#'+itemClass+'-diamond').html();
		jQuery('.embed-gig-basic').html( services );	
		jQuery('.embed-gig-gold').html( servicesGold );
		jQuery('.embed-gig-diamond').html( servicesDiamond );
	});
});
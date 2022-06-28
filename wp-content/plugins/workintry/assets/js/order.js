"use strict";
jQuery(document).ready(function(){
	var ajaxUrl = order_details.ajaxurl;
	var currentUser = order_details.userId;
	var orderId = jQuery('#post-id').data('id');
	var loader = '<div class="workintry-loader"><div class="loader">Loading...</div></div>';
	
	//Send Message
	jQuery(document).on('click', '.wi-send-gig-message', function(e){
		e.preventDefault();
		var _this = jQuery(this);
		var ajaxUrl = order_details.ajaxurl;	
		var currentUser = order_details.userId;
		var orderId 	= jQuery('#post-id').data('id');
		var message 	= jQuery('#message-box').val();
		const gallery 	= [];
		var counter = 0;
		if( jQuery('.get-gig-gallery').length > 0 ){			
			jQuery('.get-gig-gallery').each(function(){
			  gallery[counter] = this.value;
			  counter++;
			});
		}
		//Check Message
	    if( message == '' || message == 'undefined' ){
	    	jQuery.sticky('Message is required', {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
	        return false;
	    }    
	    _this.toggleClass('wi-loader');
	    //Prepare Ajax Call
	    jQuery.ajax({
	        type: "POST",
	        url: ajaxUrl,
	        data: 'gallery='+gallery+'&message='+message+'&userID='+currentUser+'&id='+orderId+'&action=codesquare_workintry_post_order_chat_message',
	        dataType: "json",
	        success: function (response) {         
	            if (response.type == 'success') {            	
	            	//Clear Box
	            	jQuery('#message-box').val('');            	
	            	//Append Message            	
	            	_this.toggleClass('wi-loader');  
	            	//Remove Files
	            	jQuery('.cf-chat-files').html('');
				}            
	        },
	        error: function(err) {
	        	//Remove loader     
	        	_this.toggleClass('wi-loader');            	   	
	        },                
	    });
	});

	//Submit Order
	jQuery(document).on('click', '.wi-submit-gig-order', function(e){	
		e.preventDefault();
		var _this = jQuery(this);
		var ajaxUrl 	 = order_details.ajaxurl;	
		var orderId 	= jQuery('#post-id').data('id');
		_this.removeClass('wi-submit-gig-order');
		jQuery('body').append(loader);
	    //Prepare Ajax Call
	    jQuery.ajax({
	        type: "POST",
	        url: ajaxUrl,
	        data: 'id='+orderId+'&action=codesquare_workintry_make_order_complete',
	        dataType: "json",
	        success: function (response) {  
	        	jQuery('body').find('.workintry-loader').remove();       
	            if (response.type == 'success') { 
	            	//  
				}            
	        },
	        error: function(err) {
	        	//Remove loader      
	        	jQuery('body').find('.workintry-loader').remove();           	   	
	        },                
	    });
	});

	//Ask for Order revision
	jQuery(document).on('click', '.wi-ask-gig-revision', function(e){	
		e.preventDefault();
		var _this = jQuery(this);
		var ajaxUrl 	 = order_details.ajaxurl;	
		var revision 	 = order_details.asked_revision;
		var orderId 	= jQuery('#post-id').data('id');
		_this.removeClass('wi-ask-gig-revision');
		_this.addClass('hidden-must');
		jQuery('body').append(loader);
	    //Prepare Ajax Call
	    jQuery.ajax({
	        type: "POST",
	        url: ajaxUrl,
	        data: 'id='+orderId+'&action=codesquare_workintry_ask_for_order_revision',
	        dataType: "json",
	        success: function (response) {  
	        	jQuery('body').find('.workintry-loader').remove();       
	            if (response.type == 'success') { 
	            	
				}            
	        },
	        error: function(err) {
	        	//Remove loader      
	        	jQuery('body').find('.workintry-loader').remove();           	   	
	        },                
	    });
	});

	//Make order as done
	jQuery(document).on('click', '.wi-make-order-done', function(e){	
		e.preventDefault();
		var _this = jQuery(this);
		var ajaxUrl 	 = order_details.ajaxurl;	
		var revision 	 = order_details.asked_revision;
		var orderId 	= jQuery('#post-id').data('id');
		_this.removeClass('wi-make-order-done');		
		jQuery('body').append(loader);
	    //Prepare Ajax Call
	    jQuery.ajax({
	        type: "POST",
	        url: ajaxUrl,
	        data: 'id='+orderId+'&action=codesquare_workintry_make_order_as_done',
	        dataType: "json",
	        success: function (response) {  
	        	jQuery('body').find('.workintry-loader').remove();       
	            if (response.type == 'success') { 
	            	
				}            
	        },
	        error: function(err) {
	        	//Remove loader      
	        	jQuery('body').find('.workintry-loader').remove();           	   	
	        },                
	    });
	});
});

//Function for getting chat
setInterval(
function orderChat(){
	var ajaxUrl 		= order_details.ajaxurl;
	var currentUser 	= order_details.userId;
	var complete 		= order_details.complete;
	var completeText 	= order_details.complete_text;
	var done 			= order_details.done;
	var revision 		= order_details.revision;
	var orderId 		= jQuery('#post-id').data('id');
	var messageId = jQuery(document).find('.chat-messages .pc-dashboardbox:last-child').attr('message-id');	
	if( !jQuery('#post-id').length > 0){		
		return false;
	}		
	jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        data: 'messageId='+messageId+'&userID='+currentUser+'&id='+orderId+'&action=codesquare_workintry_get_order_chat_message',
        dataType: "json",
        success: function (response) {        	
            if (response.type == 'success') {
            	//Append Message
            	jQuery(document).find('.chat-messages').append(response.data);                
            	if( response.status == 'completed' ){
        			jQuery('.wi-btn.wi-gig-order-btn').text(done);
            		jQuery('.wi-btn.wi-gig-order-btn').removeClass('wi-submit-gig-order');
            		jQuery('.wi-btn.wi-gig-wait-btn:nth-child(1)').addClass('wi-make-order-done');
            		jQuery('.wi-btn.wi-gig-wait-btn:nth-child(1)').text(complete);
            		jQuery('.wi-btn.wi-gig-wait-btn:nth-child(2)').addClass('wi-ask-gig-revision');
            		jQuery('.wi-btn.wi-gig-wait-btn:nth-child(2)').text(revision);
            		jQuery('.wi-btn.wi-gig-wait-btn:nth-child(2)').removeClass('hidden-must');
            		jQuery('.order-arrived').text(completeText);
            	} else if( response.status == 'revision' ){
            		var revision 		= order_details.asked_revision;
            		var waiting 		= order_details.waiting;
            		// Add Proper Classes now
	            	jQuery('.wi-btn.wi-gig-order-btn').addClass('wi-submit-gig-order');
	            	jQuery('.wi-btn.wi-gig-order-btn').text(revision);
	            	jQuery('.wi-btn.wi-gig-wait-btn:nth-child(1)').removeClass('wi-make-order-done');
	            	jQuery('.wi-btn.wi-gig-wait-btn:nth-child(1)').text(waiting);
            	} else if( response.status == 'done' ){
            		location.reload(true);
            	}
			}
            
        },
        error: function(err) {
            // we need nothing here
        },                
    });
}, 5000);
"use strict";
jQuery(document).on('ready', function($) {
    var loader = '<div class="workintry-loader"><div class="loader">Loading...</div></div>';
    /* MOBILE MENU   */
    function collapseMenu(){
        jQuery('.pc-navigation ul li.menu-item-has-children').prepend('<span class="pc-dropbtn"><i class="lnr lnr-chevron-down"></i></span>');
        jQuery('.pc-navigation ul li.menu-item-has-children span').on('click', function() {
            jQuery(this).parent('li').toggleClass('pc-open');
            jQuery(this).next().next().slideToggle(300);
        });
    }
    collapseMenu();
    /* DASHBOARD MENU */
    if(jQuery('.pc-menures').length > 0){
        jQuery(".pc-menures").on('click', function(event) {
            event.preventDefault();
            jQuery('.pc-main').toggleClass('pc-menuresopen');
        });
    }
    /* FIXED NAV SIDEBAR */
    function fixedNav($){            
        jQuery(window).scroll(function () {          
        var $pscroll = jQuery(window).scrollTop();                       
            if($pscroll > 50){
                jQuery('.pc-sidebarholder').addClass('pc-navfixed');
            }else{
                jQuery('.pc-sidebarholder').removeClass('pc-navfixed');
            }
        });
    }
    fixedNav();

	/* Profile Updation */
    jQuery(document).on('click', '.cf-update-profile', function (e) {
        e.preventDefault();             
        var _this = jQuery(this);
        jQuery('body').append(loader);
        var profileForm = _this.parents('form.cf-update-profile-form').serialize();
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: profileForm + '&action=codesquare_workintry_update_user_profile',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type == 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });										
                } else {			
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });

    /*Show/Hide Fields*/
    jQuery('.pc-select-special-fields input').on('change', function(){
        var selectedAd = jQuery(this).val();
        jQuery('.pc-car').removeClass('pc-show-special-fields');
        jQuery('.pc-mobile').removeClass('pc-show-special-fields');
        jQuery('.pc-home').removeClass('pc-show-special-fields');
        if( selectedAd == 'ad' ){
            jQuery('.pc-car').siblings().removeClass('pc-show-special-fields');
        } 
        if( selectedAd == 'car' ){            
            jQuery('.pc-car').addClass('pc-show-special-fields');
        }
        if( selectedAd == 'mobile' ){           
            jQuery('.pc-mobile').addClass('pc-show-special-fields');
        }
        if( selectedAd == 'home' ){            
            jQuery('.pc-home').addClass('pc-show-special-fields');
        }
    });
    /* Change Password */
    jQuery(document).on('click', '.cf-update-password', function (e) {
        e.preventDefault();            
        var _this = jQuery(this);
        jQuery('body').append(loader);
        var profileForm = _this.parents('form.cf-update-password-form').serialize();
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: profileForm + '&action=codesquare_workintry_change_user_password',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type == 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000 });										
                } else {			
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 500000});
                }
            }
        });
    });

    /*Profile Image Uploader*/
    var uploaderArguments = {
        browse_button: 'cl-upload-profile-photo', // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'cf_profile_uploader',
        container: 'plupload-profile-container',
        runtimes: 'html5',
        drop_element: 'cl-upload-profile-photo',
        multipart_params: {
            "type": "profile_photo",
        },
        url: custom_vars.ajaxurl + "?action=codesquare_workintry_profile_image_uploader",
        filters: {
            mime_types: [
                {title: 'Upload Profile Image', extensions: "gif,png,jpg,jpeg"}
            ],
            max_file_size: 99999999,
            prevent_duplicates: false
        }
    };

    var ProfileUploader = new plupload.Uploader(uploaderArguments);
    ProfileUploader.init();
	
	//Method bind 
    ProfileUploader.bind('FilesAdded', function (up, files) {
        var _thumb = "";
        plupload.each(files, function (file) {
            //add any thing as per your needs
        });        
        up.refresh();
        ProfileUploader.start();
    });

    //Method Progress
    ProfileUploader.bind('UploadProgress', function (up, file) {
    jQuery('#myBar').css('width', '1%'); 	
        //Add you code like for progress bar    
        jQuery('#myBar').css('width', file.percent + '%');     
        console.log(file.percent);    
    });

    //Method Error
    ProfileUploader.bind('Error', function (up, err) {
        //Show warning/error
        jQuery.sticky(err.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
    });

    //Sidebar class
    jQuery(document).on('click', '.cf-sidebar-res', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        _this.closest('.cf-sidebarnav').toggleClass('cf-toggle-btn');
        _this.closest('.cf-wrapper').toggleClass('cf-expand-wrap');
    });
   
    //display data
    ProfileUploader.bind('FileUploaded', function (up, file, ajax_response) {
        var response = jQuery.parseJSON(ajax_response.response);
        //console.log(response);
        if (response.type === 'success') {
            var append_profile_photo = wp.template('append-profile-photo');
            var _thumb = append_profile_photo(response);
            jQuery('.cf-userprofile').find('img').attr('src', response.thumbnail);
             jQuery('.pc-user-box').find('img').attr('src', response.thumbnail);        
            jQuery('.pc-loginarea').find('img').attr('src', response.thumbnail);
            jQuery('.cf-hscrollbar #mCSB_1_container').append(_thumb);           
            jQuery('#myBar').css('width', '1%');
        } else {			
            jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
        }			
    });

    /*Gallery Image Uploader*/
    var uploaderGalleryArguments = {
        browse_button: 'cl-upload-ad-gallery', // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'cf_gallery_uploader',
        container: 'plupload-gallery-container',
        runtimes: 'html5',
        drop_element: 'cl-upload-ad-gallery',
        multipart_params: {
            "type": "gallery",
        },
        url: custom_vars.ajaxurl + "?action=codesquare_workintry_ad_gallery_uploader",
        filters: {
            mime_types: [
                {title: 'Upload Profile Image', extensions: "gif,png,jpg,jpeg"}
            ],
            max_file_size: 99999999,
            prevent_duplicates: false
        }
    };

    var ProfileGalleryUploader = new plupload.Uploader(uploaderGalleryArguments);
    ProfileGalleryUploader.init();
    
    //Method bind 
    ProfileGalleryUploader.bind('FilesAdded', function (up, files) {
        var _thumb = "";
        plupload.each(files, function (file) {
            //add any thing as per your needs
        });        
        up.refresh();
        ProfileGalleryUploader.start();
    });

    //Method Progress
    ProfileGalleryUploader.bind('UploadProgress', function (up, file) {    
        jQuery('#myBar').css('width', '1%');    
        //Add you code like for progress bar        
        jQuery('#myBar').css('width', file.percent + '%');     
        console.log(file.percent);     
    });

    //Method Error
    ProfileGalleryUploader.bind('Error', function (up, err) {
        //Show warning/error
        jQuery.sticky(err.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
    });


    //display data
    ProfileGalleryUploader.bind('FileUploaded', function (up, file, ajax_response) {
        var response = jQuery.parseJSON(ajax_response.response);        
        if (response.type === 'success') {
            var append_profile_photo = wp.template('append-gallery-photo');
            var count = codesquaresworkintryRandomNumber();
            var data = {count: count, response: response};
            var _thumb = append_profile_photo(data);           
            jQuery('.cf-gallery-images #mCSB_1_container').append(_thumb);
            jQuery('#myBar').css('width', '1%');    
            jQuery('.wi-stepslist .gig-gallery-check').addClass('wi-stepcheck');  
        } else {            
            jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
        }           
    });

    /*Chat Image Uploader*/     
    var uploaderChatFileArguments = {
        browse_button: 'cl-upload-chat-file', // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'cf_gallery_uploader',
        container: 'plupload-chat-container',
        runtimes: 'html5',
        drop_element: 'cl-upload-chat-file',
        multipart_params: {
            "type": "gallery",
        },
        url: custom_vars.ajaxurl + "?action=codesquare_workintry_chat_gallery_uploader",
        filters: {
            mime_types: [
                {title: 'File', extensions: "gif,png,jpg,jpeg,pdf,zip"}                
            ],
            max_file_size: 99999999,
            prevent_duplicates: false
        }
    };

    var ChatFileGalleryUploader = new plupload.Uploader(uploaderChatFileArguments);
    ChatFileGalleryUploader.init();
    
    //Method bind 
    ChatFileGalleryUploader.bind('FilesAdded', function (up, files) {
        var _thumb = "";
        plupload.each(files, function (file) {
            //add any thing as per your needs
        });        
        up.refresh();
        ChatFileGalleryUploader.start();
    });

    //Method Progress
    ChatFileGalleryUploader.bind('UploadProgress', function (up, file) {    
        jQuery('#myBar1').css('display', 'block');  
        jQuery('#myBar1').css('width', '1%');    
        //Add you code like for progress bar        
        jQuery('#myBar1').css('width', file.percent + '%'); 
        jQuery('#myBar1').html(file.percent + '%');
        console.log(file.percent);     
    });

    //Method Error
    ChatFileGalleryUploader.bind('Error', function (up, err) {
        //Show warning/error
        jQuery.sticky(err.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
    });

    //display data
    ChatFileGalleryUploader.bind('FileUploaded', function (up, file, ajax_response) {
        var response = jQuery.parseJSON(ajax_response.response);        
        if (response.type === 'success') {
            var append_profile_photo = wp.template('append-chat-file');
            var count = codesquaresworkintryRandomNumber();
            var data = {count: count, response: response};
            var _thumb = append_profile_photo(data);           
            jQuery('.cf-chat-files').append(_thumb);
            jQuery('#myBar1').css('width', '1%');
            jQuery('#myBar1').css('display', 'none');  
            jQuery('#myBar1').html('');
        } else {            
            jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
        }           
    });
    
    /*Floor PLan*/
    var uploaderPlanArguments = {
        //Oxie.Mime.addMimeType("application/pdf,pdf");
        browse_button: 'cl-upload-ad-plan', // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'cf_gallery_uploader',
        container: 'plupload-plan-container',
        runtimes: 'html5',
        drop_element: 'cl-upload-ad-plan',
        multipart_params: {
            "type": "gallery",
        },
        url: custom_vars.ajaxurl + "?action=codesquare_workintry_ad_gallery_uploader",
        filters: {
            mime_types: [
                {title: 'Upload Profile Image', extensions: "gif,png,jpg,jpeg"}
            ],
            max_file_size: 99999999,
            prevent_duplicates: false
        }
    };

    var PlanGalleryUploader = new plupload.Uploader(uploaderPlanArguments);
    PlanGalleryUploader.init();
    
    //Method bind 
    PlanGalleryUploader.bind('FilesAdded', function (up, files) {
        var _thumb = "";
        plupload.each(files, function (file) {
            //add any thing as per your needs
        });        
        up.refresh();
        PlanGalleryUploader.start();
    });

    //Method Progress
    PlanGalleryUploader.bind('UploadProgress', function (up, file) {    
        jQuery('#myBar2').css('width', '1%');    
        //Add you code like for progress bar        
        jQuery('#myBar2').css('width', file.percent + '%');     
        console.log(file.percent);     
    });

    //Method Error
    PlanGalleryUploader.bind('Error', function (up, err) {
        //Show warning/error
        jQuery.sticky(err.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
    });

    //display data
    PlanGalleryUploader.bind('FileUploaded', function (up, file, ajax_response) {
        var response = jQuery.parseJSON(ajax_response.response);        
        if (response.type === 'success') {
            var append_profile_photo = wp.template('append-plan-photo');
            var count = codesquaresworkintryRandomNumber();
            var data = {count: count, response: response};
            var _thumb = append_profile_photo(data);           
            jQuery('.cf-plan-images #mCSB_3_container').append(_thumb);
            jQuery('#myBar2').css('width', '1%');           
        } else {            
            jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
        }           
    });

    //Set Profile Image
    jQuery(document).on('click', '.cf-add-profile-photo', function(e){
    	e.preventDefault();
    	var url = jQuery(this).data('url'); 
    	var id  = jQuery(this).data('id');   	
        jQuery('.pc-user-box').find('img').attr('src', url);    	
        jQuery('.pc-loginarea').find('img').attr('src', url);        
        //Send data to server
        var dataString = 'id=' + id + '&action=codesquare_workintry_update_profile_image';
        jQuery('body').append(loader);
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') {					
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
                } else {
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });

    //Delete Profile Image
    jQuery(document).on('click', '.cf-delete-profile-photo', function(e){
        e.preventDefault();        
        var _this = jQuery( this );        
        var id  = jQuery(this).data('id');            

        //Send data to server
        var dataString = 'id=' + id + '&action=codesquare_workintry_delete_profile_image';
        
        jQuery.confirm({
            'title': custom_vars.title,
            'message': custom_vars.message,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        jQuery('body').append(loader);
                        jQuery.ajax({
                        type: "POST",
                        url: custom_vars.ajaxurl,
                        data: dataString,
                        dataType: "json",
                        success: function (response) {
                            jQuery('body').find('.workintry-loader').remove();
                            if (response.type === 'success') { 
                            _this.closest('.cf-cross').remove();
                            _this.parent('.cf-cross').remove();
                            _this.parent('figure').parent('.cf-cross').remove();
                            jQuery('.cf-userprofile').find('img').attr('src', response.thumb);
                            jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
                            } else {
                                jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                            }
                        }
                    });
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }   // Nothing to do in this case. You can as well omit the action property.
                }
            }
        }); 
    });

    //Delete Gallery Image
    jQuery(document).on('click', '.cf-delete-gallery-image', function(e){
        e.preventDefault();        
        var _this = jQuery( this );        
        var id  = jQuery(this).data('id');            

        //Send data to server
        var dataString = 'id=' + id + '&action=codesquare_workintry_delete_gallery_image';        
        jQuery.confirm({
            'title': custom_vars.title,
            'message': custom_vars.message,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        _this.closest('.cf-cross').remove();
                        _this.parent('.cf-cross').remove();
                        _this.parent('figure').parent('.cf-cross').remove();     
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }   // Nothing to do in this case. You can as well omit the action property.
                }
            }
        });       
    });

    //Random Number Generator
    function codesquaresworkintryRandomNumber() {     
      var Number = Math.floor((Math.random() * 99999) + 1);
      return Number;
    }

    /* Create Gig Ad */
    jQuery(document).on('click', '.cf-insert-gig', function (e) {
        e.preventDefault();      
        tinyMCE.triggerSave();
        var _this = jQuery(this);
        var type = _this.data('type'); 
        var id   = _this.data('id');         
        jQuery('body').append(loader);
        var propertyForm = _this.parents('form.cf-insert-ad-form').serialize();
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: propertyForm + '&type='+ type +'&current='+ id +'&action=codesquare_workintry_insert_user_property_ad',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type == 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });                                        
                } else {            
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });   

    /*Tags Chosen */
    if( jQuery('.hp-amenities').length > 0 ){
        jQuery('.hp-chosen').chosen();
    }    

    /* Delete User Ad */
    jQuery(document).on('click', '.cf-delete-user-ad', function (e) {
        e.preventDefault();              
        var _this = jQuery(this);        
        var id   = _this.data('id'); 
        var userId = _this.data('user'); 
        jQuery.confirm({
            'title': custom_vars.title,
            'message': custom_vars.message,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                    jQuery('body').append(loader);
                    jQuery.ajax({
                        type: "POST",
                        url: custom_vars.ajaxurl,
                        data: 'post_id='+ id +'&user_id='+ userId +'&action=codesquare_workintry_delete_user_ad',
                        dataType: "json",
                        success: function (response) {
                            jQuery('body').find('.workintry-loader').remove();
                            if (response.type == 'success') {
                                _this.parent( 'tr' ).remove();
                                _this.closest('tr').remove();
                                jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000 });                                        
                            } else {            
                                jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 500000});
                            }
                        }
                    });
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }   // Nothing to do in this case. You can as well omit the action property.
                }
            }
        });              
    });

    /* Delete User Ad from Wishlist */
    jQuery(document).on('click', '.cf-delete-from-wish', function (e) {
        e.preventDefault();            
        var _this   = jQuery(this);        
        var id      = _this.data('id'); 
        var userId  = _this.data('user');          
        jQuery.confirm({
            'title': custom_vars.title,
            'message': custom_vars.message,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        jQuery('body').append(loader);
                            jQuery.ajax({
                            type: "POST",
                            url: custom_vars.ajaxurl,
                            data: 'post_id='+ id +'&user_id='+ userId +'&action=codesquare_workintry_delete_ad_from_wishlist',
                            dataType: "json",
                            success: function (response) {
                                jQuery('body').find('.workintry-loader').remove();
                                if (response.type == 'success') {
                                    _this.parent( 'li' ).remove();
                                    _this.closest('li').remove();
                                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000 });                                        
                                } else {            
                                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 500000});
                                }
                            }
                        });
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }   // Nothing to do in this case. You can as well omit the action property.
                }
            }
        });            
    });     

    /* Update User Social Settings */
    jQuery(document).on('click', '.cf-update-social-settings', function (e) {
        e.preventDefault();             
        var _this   = jQuery(this);        
        var facebookForm = _this.parents('form.cf-social-settings-form').serialize();
        jQuery('body').append(loader);        
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: facebookForm +'&action=codesquare_workintry_update_social_settings',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type == 'success') {                   
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000 });                                        
                } else {            
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 500000});
                }
            }
        });
    });

    /* Delete User Account */
    jQuery(document).on('click', '.cf-delete-account', function (e) {
        e.preventDefault();            
        var _this   = jQuery(this);        
        var facebookForm = _this.parents('form.cf-delete-account-form').serialize();
        jQuery('body').append(loader);        
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: facebookForm +'&action=codesquare_workintry_delete_user_account',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type == 'success') {                    
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000 });                                        
                } else {            
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 500000});
                }
            }
        });
    });

    /* Profile gallery scroller */
    if(jQuery('.cf-hscrollbar').length > 0){
        var _cf_hscrollbar = jQuery('.cf-hscrollbar');
        _cf_hscrollbar.mCustomScrollbar({
            axis:"x",
            advanced:{autoExpandHorizontalScroll:true},
        });
    }   

    /*Add to cart*/        
    jQuery(document).on('click', '.cl-buy-package', function (e) {           
        e.preventDefault();
        var _this = jQuery(this);
        var _id = _this.data('id');           
        var dataString = 'id=' + _id + '&action=codesquare_workintry_update_user_cart';
        jQuery('body').append(loader);   
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });
                    window.location.replace(response.checkout_url);
                } else {
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });   
    
    /*Add fast delivery data*/
    jQuery('#Delivery').on('change', function(){
        var _this = jQuery(this);
        if ( _this.is(':checked') ){
            jQuery('.wi-addservicescollapse').css('display', 'block' );
            return false;
        } else {
            jQuery('.wi-addservicescollapse').css('display', 'none' );
            return false;          
        }
        return false;
    });

    /*Add Faqs*/
    jQuery('.wi-add-faq').on('click', function(){
        var appendFaq = wp.template('append-faq');
        var count = codesquaresworkintryRandomNumber();
        var data = {count: count};
        var faq = appendFaq(data);           
        jQuery('.wi-remove-faq').remove();
        jQuery('.wi-dbbox.wi-addfaq .accordion').append(faq);        
    });

    //Run accordion effect
    jQuery(document).on('click', '.wi-dbbox.wi-addfaq .wi-formaccordion h5', function(){
        jQuery(this).closest('.wi-formaccordion').find('.wi-form').slideToggle();
    });

    //Delete FAQ
    jQuery(document).on('click', '.remove-faq', function(){
        jQuery(this).closest('.wi-formaccordion').remove();
    });    
    
    //Check Form Change
    jQuery(document).on('change', 'form.cf-insert-ad-form :input', function() {
        jQuery(this).closest('form').data('changed', true);
        if(jQuery(this).closest('form').data('changed')) {            
            //do scoring
            var score = 0;  
            //Title          
            if( jQuery('.gig-title-val').val() ){
                score = score + 15;
                jQuery('.wi-stepslist .gig-title-check').addClass('wi-stepcheck');
            } else {
                jQuery('.wi-stepslist .gig-title-check').removeClass('wi-stepcheck');
            }

            //Description
            if (jQuery("#wp-description-wrap").hasClass("tmce-active")){
                if(tinyMCE.activeEditor.getContent()){
                    //alert('there');
                    score = score + 15;
                    jQuery('.wi-stepslist .gig-desc-check').addClass('wi-stepcheck');
                } else{
                    jQuery('.wi-stepslist .gig-desc-check').removeClass('wi-stepcheck');
                }
            }else{                
                if( jQuery('#description').val() ){
                    score = score + 15;
                    jQuery('.wi-stepslist .gig-desc-check').addClass('wi-stepcheck');
                } else {
                    jQuery('.wi-stepslist .gig-desc-check').removeClass('wi-stepcheck');
                }
            }           

            //Gallery
            if( jQuery('.get-gig-gallery').val() ){
                score = score + 15;
                jQuery('.wi-stepslist .gig-gallery-check').addClass('wi-stepcheck');
            } else {
                jQuery('.wi-stepslist .gig-gallery-check').removeClass('wi-stepcheck');
            }

            //Category
            if( jQuery('.cf-insert-ad-form .gig-cat').val() ){
                score = score + 10;
                jQuery('.wi-stepslist .gig-category-check').addClass('wi-stepcheck');
            } else{
                jQuery('.wi-stepslist .gig-category-check').removeClass('wi-stepcheck');
            }

            //Subcategory
            if( jQuery('.cf-insert-ad-form .sub-cats .gig-cat').val() ){
                score = score + 10;
                jQuery('.wi-stepslist .gig-subcategory-check').addClass('wi-stepcheck');
            } else {
                jQuery('.wi-stepslist .gig-subcategory-check').removeClass('wi-stepcheck');
            }

            //Service
            if( jQuery('.cf-insert-ad-form .gig-service').val() ){
                score = score + 10;
                jQuery('.wi-stepslist .gig-service-check').addClass('wi-stepcheck');
            } else {
                jQuery('.wi-stepslist .gig-service-check').removeClass('wi-stepcheck');
            }

            //Gigs            
            if( 
                //Basic
                jQuery(".gig-basic-title").val() &&
                jQuery(".gig-basic-desc").val() && 
                jQuery(".gig-basic-delivery select").val() &&
                jQuery(".gig-basic-revision select").val() &&
                jQuery(".basic-gig-price input").val() &&
                //Gold
                jQuery(".gig-gold-title").val() &&
                jQuery(".gig-gold-desc").val() && 
                jQuery(".gig-gold-delivery select").val() &&
                jQuery(".gig-gold-revision select").val() &&
                jQuery(".gold-gig-price input").val() &&

                //Diamond
                jQuery(".gig-diamond-title").val() &&
                jQuery(".gig-diamond-desc").val() && 
                jQuery(".gig-diamond-delivery select").val() &&
                jQuery(".gig-diamond-revision select").val() &&
                jQuery(".diamond-gig-price input").val()
            ){
                score = score + 10;
                jQuery('.wi-stepslist .gig-package-check').addClass('wi-stepcheck');
            } else {
                jQuery('.wi-stepslist .gig-package-check').removeClass('wi-stepcheck');
            }

            //Tags
            if( jQuery('.cf-insert-ad-form .hp-tags .bootstrap-tagsinput span').length ){
                score = score + 15;
                jQuery('.wi-stepslist .gig-tag-check').addClass('wi-stepcheck');
            } else {
                jQuery('.wi-stepslist .gig-tag-check').removeClass('wi-stepcheck');
            }
            
            //FAQ (optional)            
            if( jQuery('.cf-insert-ad-form .wi-addfaq input').val() && jQuery('.cf-insert-ad-form .wi-addfaq textarea').val()){                
                jQuery('.wi-stepslist .gig-faq-check').addClass('wi-stepcheck');
            } else {
                jQuery('.wi-stepslist .gig-faq-check').removeClass('wi-stepcheck');
            }
            //Append Score
            score = score + '%';
            jQuery('.gig-score').html( score );    
        } 
    });

    //Submit Form
    jQuery('.wi-submit-gig-form').on('click', function(e){
        e.preventDefault();
        jQuery('.cf-insert-gig').click();
        return false;
    });

    /*Post Comment*/
    jQuery(document).on('click', '.wi-submit-gig-review', function(e){
        e.preventDefault();      
        var _this = jQuery(this);
        var id = _this.data('id');
        jQuery('body').append(loader);
        var _serialize = _this.parents('form.wi-gig-review-form').serialize();    
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: _serialize + '&post_id=' + id + '&action=codesquare_workintry_submit_user_comment',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') { 
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right'});                                                        
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    }); 

    /*Custom Filter*/
    jQuery(document).on('click', '.filter-earnings', function(e) {
        e.preventDefault();
        jQuery(this).closest('form').submit();
    });

    /*Paypal Email*/
    jQuery(document).on('click', '.wc-update-paypal-account', function(e) {
        e.preventDefault();
        var id = jQuery('#paypal-id').val();
        if( id == undefined || id == '' ){
            jQuery.sticky('Provide your email ID', {classList: 'important',position:'center-center', speed: 200, autoclose: 3000});
            return false;            
        }
        //Proceed        
        jQuery('body').append(loader);
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: 'paypal_id=' + id + '&action=codesquare_workintry_update_paypal_id',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') { 
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right'});                                                        
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });

    /*Set Categories*/
    if( jQuery('#gig-cat0').length > 0 ){
        var cats = jQuery('#gig-cat0').html();        
        jQuery('.main-cats').html( cats );
    }

    /*Set sub categories and services*/
    //Select and choose sub category
    if( jQuery('.main-cats' ).length > 0 ){
        jQuery(document).on('change', '.main-cats select', function(){      
            var element = jQuery(this).find('option:selected'); 
            var itemClass = element.attr("class"); 
            if( itemClass == 'none' || itemClass == 'undefined' ){  
                jQuery('.sub-cats select').html('<option value="">Select Sub Category</option>');
                jQuery('.gig-services select').html('<option value="">Select Service</option>');
                return false;
            }       
            var subCats = jQuery('#'+itemClass).html();
            jQuery('.sub-cats').html( subCats );
        });
        //Select and choose service
        jQuery(document).on('change', '.sub-cats select', function(){
            var element = jQuery(this).find('option:selected'); 
            var itemClass = element.attr("class"); 
            if( itemClass == 'none' || itemClass == 'undefined' ){  
                jQuery('.gig-services select').html('<option value="">Select Service</option>');
                return false;
            }               
            var services = jQuery('#'+itemClass).html();
            jQuery('.gig-services').html( services );
        });
        //Select and choose services gigs
        jQuery(document).on('change', '.gig-services select', function(){
            var vices = '';
            var element = jQuery(this).find('option:selected'); 
            var itemClass = element.attr("class"); 
            if( itemClass == 'none' || itemClass == 'undefined' ){  
                jQuery('.gig-services select').html('<option value="">Select Service</option>');
                return false;
            }               
            var services        = jQuery('#'+itemClass+'-basic').html();
            var servicesGold    = jQuery('#'+itemClass+'-gold').html();
            var servicesDiamond = jQuery('#'+itemClass+'-diamond').html();
            jQuery('.embed-gig-basic').html( services );
            vices = jQuery('#'+itemClass+'-gold').html();
            jQuery('.embed-gig-gold').html( servicesGold );
            vices = jQuery('#'+itemClass+'-diamond').html();
            jQuery('.embed-gig-diamond').html( servicesDiamond );
        });
    }

    /*Categories*/
    //Select and choose sub category
    if( jQuery('.main-cats' ).length > 0 ){
        jQuery(document).on('change', '.main-cats select', function(){      
            var element = jQuery(this).find('option:selected'); 
            var itemClass = element.attr("class"); 
            if( itemClass == 'none' || itemClass == 'undefined' ){  
                jQuery('.sub-cats select').html('<option value="">Select Sub Category</option>');
                jQuery('.gig-services select').html('<option value="">Select Service</option>');
                return false;
            }       
            var subCats = jQuery('#'+itemClass).html();
            jQuery('.sub-cats').html( subCats );
        });
    }
    //Select and choose service
    if( jQuery('.sub-cats' ).length > 0 ){
        jQuery(document).on('change', '.sub-cats select', function(){
            var element = jQuery(this).find('option:selected'); 
            var itemClass = element.attr("class"); 
            if( itemClass == 'none' || itemClass == 'undefined' ){  
                jQuery('.gig-services select').html('<option value="">Select Service</option>');
                return false;
            }               
            var services = jQuery('#'+itemClass).html();
            jQuery('.gig-services').html( services );
        });
    }
    //Select and choose services gigs
    if( jQuery('.gig-services' ).length > 0 ){
        jQuery(document).on('change', '.gig-services select', function(){
            var element = jQuery(this).find('option:selected'); 
            var itemClass = element.attr("class"); 
            if( itemClass == 'none' || itemClass == 'undefined' ){  
                jQuery('.gig-services select').html('<option value="">Select Service</option>');
                return false;
            }               
            var services        = jQuery('#'+itemClass+'-basic').html();
            var servicesGold    = jQuery('#'+itemClass+'-gold').html();
            var servicesDiamond = jQuery('#'+itemClass+'-diamond').html();
            jQuery('.embed-gig-basic').html( services );           
            jQuery('.embed-gig-gold').html( servicesGold );
            jQuery('.embed-gig-diamond').html( servicesDiamond );
        });
    }
});


/* ---------------------------------------
 Confirm Box
 --------------------------------------- */
(function ($) {

    $.confirm = function (params) {      
        if ($('#clConfirmWrap').length) {
            // A confirm is already shown on the page:
            return false;
        }

        var buttonHTML = '';
        $.each(params.buttons, function (name, obj) {

            // Generating the markup for the buttons:
            if( name == 'Yes' ){
                name    = custom_vars.yes;
            } else if( name == 'No' ){
                name    = custom_vars.no;
            } else{
                name    = name;
            }
            
            buttonHTML += '<a href="#" class="button ' + obj['class'] + '">' + name + '<span></span></a>';
            if (!obj.action) {
                obj.action = function () {
                };
            }
        });
        var markup = [
            '<div id="clConfirmWrap">',
            '<div id="confirmWrap">',
            '<h1>', params.title, '</h1>',
            '<p>', params.message, '</p>',
            '<div id="confirmButtons">',
            buttonHTML,
            '</div></div></div>'
        ].join('');
        $(markup).hide().appendTo('body').fadeIn();
        var buttons = $('#confirmWrap .button'),
                i = 0;
        $.each(params.buttons, function (name, obj) {
            buttons.eq(i++).on( 'click', function () {

                // Calling the action attribute when a
                // click occurs, and hiding the confirm.

                obj.action();
                $.confirm.hide();
                return false;
            });
        });
    }

    $.confirm.hide = function () {
        $('#clConfirmWrap').fadeOut(function () {
            $(this).remove();
        });
    }

})(jQuery);
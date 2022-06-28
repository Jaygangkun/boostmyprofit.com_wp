 "use strict";
jQuery(document).ready(function($) {       
    var logged_in       = custom_vars.logged_in;
    var logoutMessage   = custom_vars.logout_message;
    var saved           = custom_vars.saved;
    var minPrice        = custom_vars.min_price;
    var maxPrice        = custom_vars.max_price;    
    var loader          = '<div class="workintry-loader"><div class="loader">Loading...</div></div>';    	   
    /* Registration Ajax */
    jQuery(document).on('click', '.process-register', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        jQuery('body').append(loader);
        var _authenticationform = _this.parents('form.process-registration-form').serialize();
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: _authenticationform + '&action=codesquare_workintry_process_registration',
            dataType: "json",
            success: function (response) {
                if (response.type == 'success') {
                    jQuery('body').find('.workintry-loader').remove();
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000 });
					jQuery('.process-registration-form').get(0).reset();
					window.location.replace(response.redirect);
                } else {
					jQuery('body').find('.workintry-loader').remove();                  
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 500000});
                }
            }
        });
    });

    /* Ajax Login  */
    jQuery(document).on('click', '.process-user-login', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        jQuery('body').append(loader);
        var _serialize = _this.parents('form.cp-signin-form').serialize();        
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: _serialize + '&action=codesquare_workintry_process_user_login',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') {
                    console.log(response.redirect);
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000, position: 'top-right'});
                    window.location.replace(response.redirect);                    
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });

    /* Add to Favourites  */
    jQuery(document).on('click', '.cf-add-to-wish', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        var id = _this.data('id');

        if (logged_in == 'false') {
            jQuery.sticky(logoutMessage, {classList: 'important',position:'center-center', speed: 200, autoclose: 7000});
            return false;
        }
        jQuery('body').append(loader);
        var _serialize = _this.parents('form.user-login-form').serialize();
        
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: 'id=' + id + '&action=codesquare_workintry_add_wo_wishlist',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') { 
                    _this.removeClass( 'cf-add-to-wish' );
                    _this.addClass('hp-liked');
                    _this.html('<i class="fa fa-heart"></i>');
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right'});                                    
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });      

    /* Add to Favourites  */
    jQuery(document).on('click', '.cf-ad-to-fav', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        var id = _this.data('id');

        if (logged_in == 'false') {
            jQuery.sticky(logoutMessage, {classList: 'important',position:'center-center', speed: 200, autoclose: 7000});
            return false;
        }
        jQuery('body').append(loader);                
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: 'id=' + id + '&action=codesquare_workintry_add_wo_wishlist',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') { 
                    _this.removeClass( 'cf-ad-to-fav' );
                    _this.closest('.wi-likeserch').find('.saved-text').text(saved);
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000, position: 'top-right'});                                    
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    }); 

    /*Submit Form*/
    jQuery(document).on('click', '.hp-submit-form-btn', function(e){
        e.preventDefault();
        jQuery(this).closest('form').trigger('submit');
    });

    /* Ad report */
    jQuery(document).on('click', '.cf-send-ad-report', function (event) {
        event.preventDefault();        
        var _this = jQuery(this);
        var id = _this.data('id');
      
        jQuery('body').append(loader);    
        var _serialize = _this.parents('form.cf-report-form').serialize();              
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: _serialize + '&id=' + id + '&action=codesquare_workintry_send_ad_report',            
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') {                     
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000, position: 'top-right'});                                    
                    jQuery('.cf-report-form').get(0).reset();
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });    

    /*Show Social share*/
    if( jQuery('.hp-shared .hp-share').length ){
        jQuery('.hp-shared .hp-share').on('click', function(){
            jQuery('.hp-shared .hp-socialiconsborder').toggle();
        });
    }
    
    /*Get Cities*/  
    jQuery(document).on('change', '.cf-country-to-city', function(e){
        event.preventDefault();   
        var _this = jQuery(this);
        var item = _this.find('select').val();     
        var taxonomy = jQuery('.get-country-name').val();       
        jQuery('body').append(loader);        
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: 'taxonomy='+taxonomy+'&country='+item+'&action=codesquare_workintry_get_cities',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') { 
                    jQuery('.cf-add-cities select').html('');
                    jQuery('.cf-add-cities select').append(response.data);
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right'});                                    
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });  

    /*Submit Search Form*/
    jQuery('.cp-searchform.cp-cars-search-form .cp-btn').on('click', function(){
        jQuery(this).closest('form').trigger('submit');
    });

    /*Submit Search Form*/
    jQuery('.cp-submit-for-search.cp-btn').on('click', function(){
        jQuery(this).closest('form').trigger('submit');
    });    

    /*Lost Password*/  
    jQuery(document).on('click', '.cl-get-user-password', function(e){
        event.preventDefault();   
        var _this = jQuery(this);
        jQuery('body').append(loader);
        var _serialize = _this.parents('form.cl-get-password').serialize();    
               
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: _serialize + '&action=codesquare_workintry_lost_password',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') {                                       
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000, position: 'top-right'});                                    
                    jQuery('#clPwdModal').modal('toggle');
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });
    
    /*Get Password*/
    jQuery(document).on('click', '.cl-resest-password', function(e){
        event.preventDefault();   
        var _this = jQuery(this);
        jQuery('body').append(loader);
        var _serialize = _this.parents('form.cl-get-password-form').serialize();    
               
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: _serialize + '&action=codesquare_workintry_get_user_password',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') { 
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000, position: 'top-right'});                                    
                    window.location.replace(response.redirect);
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });

    /*Send Message to Author*/
    jQuery(document).on('click', '.cp-send-msg-to-user', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        var id          = _this.data('id');
        var currentUsr  = _this.data('current');
        var authorId    = _this.data('author');
        jQuery('body').append(loader);
        var _serialize = _this.parents('form.cp-send-author-msg').serialize(); 

        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: _serialize + '&id=' + id + '&current=' + currentUsr + '&author_id=' + authorId + '&action=codesquare_workintry_submit_user_message',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') { 
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right'});                                                        
                    _this.parents('form.cp-send-author-msg').get(0).reset();
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });

    });
    
    //Scroll bar    
    if(jQuery('.cp-hscrollbar').length > 0){
        var _cp_hscrollbar = jQuery('.cp-hscrollbar');
        _cp_hscrollbar.mCustomScrollbar({
            axis:"x",
            advanced:{autoExpandHorizontalScroll:true},
        });
    }

    /* THEME VERTICAL SCROLLBAR */
    if(jQuery('.cp-vscrollbar').length > 0){
        var _cp_vscrollbar = jQuery('.cp-vscrollbar');
        _cp_vscrollbar.mCustomScrollbar({
            axis:"y",
        });
    }

    //Submit search form
    $('.cp-submit-form-filter').on('click', function(){
        $(this).closest('form').trigger('submit');
    });  

    /*Submit Search*/
    jQuery(document).on('click', '.cf-submit-banner-search', function(){
        jQuery(this).closest('form').trigger('submit');
    });

    /*User Login*/
    jQuery(document).on('click', '.cl-to-login', function(e){
        e.preventDefault();
        jQuery('.cl-login-input').focus();
    });
    /*User Login*/
    jQuery(document).on('click', '.cl-to-register', function(e){
        e.preventDefault();
        jQuery('.cl-register-input').focus();
    });
    /*Lightbox*/
    if( jQuery('#cp-galleryslider1').length > 0 ){
        jQuery('#cp-galleryslider1').lightGallery({           
            selector: '.item'
        });
    }
    /*Lightbox2*/
    if( jQuery('#cp-galleryslider2').length > 0 ){
        jQuery('#cp-galleryslider2').lightGallery({           
            selector: '.item'
        });
    }
    if( jQuery('.ca-fullimgshow').length > 0 ){
        jQuery('.ca-fullimgshow').lightGallery({           
            selector: '.item .slide-item'
        });
    }

    /*Banner Slider*/
    if( jQuery(".hp-banner-slider").length > 0 ){
        var hp_banner_slider = jQuery('.hp-banner-slider')
        hp_banner_slider.owlCarousel({
            items: 1,
            loop:true,
            nav:false,
            margin: 0,
            dots: false,
            autoplay:true,
        });
    }
    /*Slider*/
    if( jQuery(".hp-featured-slider").length > 0 ){
        // Featured Slider 
        var hp_featured_slider = jQuery('.hp-featured-slider')
        hp_featured_slider.owlCarousel({
            items: 5,
            loop:true,
            nav:false,
            margin: 30,
            dots: true,
            autoplay:false,
            responsiveClass:true,
            responsive:{
                0:{items:1, },
                767:{items:2},
                992:{items:3},
                1281:{items:4},
                1680:{items:5,}
            }
        });       
    }

    /*Banner Video*/
    if(  jQuery('.hp-banner-video a').length > 0 ){
        jQuery('.hp-banner-video a').lightGallery({
            selector: 'this'
        });
    }

    // Articles Slider 
    if( jQuery(".hp-articles-slider").length > 0 ){
        var hp_articles_slider = jQuery('.hp-articles-slider')
        hp_articles_slider.owlCarousel({
            items: 3,
            loop:true,
            nav:false,
            margin: 30,
            dots: true,
            autoplay:false,
            responsiveClass:true,
            responsive:{
                0:{items:1, },
                640:{items:2},
                992:{items:3},
            }
        });
    }

    // Feedback Slider 
    if( jQuery('.hp-feedback-slider').length > 0 ){
        var hp_feedback_slider = jQuery('.hp-feedback-slider');
        hp_feedback_slider.owlCarousel({
            items: 4,
            loop:true,
            nav:false,
            autoWidth:true,
            margin: 30,
            dots: true,
            autoplay:false,
        });
    }

    /* COUNTER */
    if( jQuery('#hp-counter').length > 0 ){   
        var _hp_counter = jQuery('#hp-counter');
        _hp_counter.appear(function () {
            var _hp_counter = jQuery('.hp-ourfocus-content h2 em');            
            _hp_counter.countTo({
                formatter: function (value, options) {
                    return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
                }
            });
        });
    }

    /*Star Rating*/
    if( jQuery('.single-workintry .cf-stars.cf-rightarea' ).length > 0 ){
        jQuery(".cf-stars.cf-rightarea").jRate({
            rating: 5,
            shape: "STAR",
            count: 5,
            width: "20",
            height: "20",
            widthGrowth: 0,
            heightGrowth: 0,
            backgroundColor: "white",
            startColor: "#f2b01e",
            endColor: "#f2b01e",
            strokeColor: "black",
            shapeGap: "0px",
            opacity: 1,
            min: 1,
            max: 5,
            decimal: false,
            precision: 1,
            horizontal: true,        
            onChange: function(rating) {
                console.log("OnChange: Rating: "+rating);
                jQuery('.cf-star-rating').val(rating);
            },
            onSet: function(rating) {
                console.log("OnSet: Rating: "+rating);
                jQuery('.cf-star-rating').val(rating);
            }
        });
    }

    /*Gallery Image Uploader*/
    if( jQuery('.single-workintry' ).length > 0 ){
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
            //Add you code like for progress bar
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
                jQuery('.cf-gallery-images').append(_thumb);           
            } else {            
                jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
            }           
        });
        //Random Number Generator
        function codesquaresworkintryRandomNumber() {     
          var Number = Math.floor((Math.random() * 99999) + 1);
          return Number;
        }
    }
    /*Gallery images end*/

    if( jQuery('.single-workintry' ).length > 0 ){
        //Delete Comment Image
        jQuery(document).on('click', '.cf-delete-gallery-image', function(e){
            e.preventDefault();
            var _this = jQuery( this );        
            var id  = jQuery(this).data('id');                                              
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
    }

    /*Add fast delivery data*/
    jQuery('.wi-packagelist li input').on('change', function(){        
        var _this = jQuery(this);
        var basicPrice = jQuery('.wi-basic').find('#normal').data('price');       
        jQuery('.wi-basic').find('.wi-final-price').html(basicPrice);
        var goldPrice = jQuery('.wi-gold').find('#pkg2').data('price');       
        jQuery('.wi-gold').find('.wi-final-price').html(goldPrice);
        var diamondPrice = jQuery('.wi-diamond').find('#pkg3').data('price');       
        jQuery('.wi-diamond').find('.wi-final-price').html(diamondPrice);
        if ( _this.is(':checked') ){
            var price = jQuery(_this).data('price');
            jQuery(_this).closest('.wi-packagelist').find('.wi-final-price').html(price);
            return false;
        } 
        return false;
    });

    /*Buy Gig*/
    jQuery(document).on('click', '.wi-buy-gig', function(e){
        e.preventDefault();       
        if (logged_in == 'false') {
            jQuery.sticky(logoutMessage, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
            return false;
        }
        var _this = jQuery(this);
        var postId  = _this.data('id');
        var pkg     = '';
        var type    = 'default';
        if( jQuery(_this).closest('.wi-packagelist').find('input[type=radio]:checked').size() > 0) {
            pkg = jQuery(_this).closest('.wi-packagelist').find('input[type=radio]:checked').val();            
            //Check for the available earnings
            var availableEarnings = jQuery(document).find('#wpEarningsModal').data('id');           
            if( availableEarnings == '' || availableEarnings == 'undefined' ){
                availableEarnings = 0;
            }
            //Get selected amount
            var selectedAmount = jQuery(_this).closest('.wi-packagelist').find('input[type=radio]:checked').data('price');
            if( selectedAmount == '' || selectedAmount == 'undefined' ){
                selectedAmount = 0;
            }
            if( availableEarnings >= selectedAmount ){
                jQuery('#hp-gig-val').val(pkg);
                jQuery('#wpEarningsModal').modal('toggle');
                return false;
            }
            //So we have to just send ajax request to charge user now
            jQuery('body').append(loader);
            jQuery.ajax({
                type: "POST",
                url: custom_vars.ajaxurl,
                data: 'type='+type+'&post_id=' + postId + '&pkg=' + pkg + '&action=codesquare_workintry_buy_gig',
                dataType: "json",
                success: function (response) {
                    jQuery('body').find('.workintry-loader').remove();
                    if (response.type === 'success') { 
                        jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right'});                                                        
                        window.location.replace(response.checkout_url);
                    } else {                    
                        jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                    }
                }
            });
        } else{            
            jQuery.sticky('Select your gig delivery time', {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
            return false;
        }
    });

    /*Buy Gig through earnings*/
    jQuery(document).on('click', '.hp-buy-gig', function(e){
        e.preventDefault();
        var _this   = jQuery(this);
        var pkg     = jQuery('#hp-gig-val').val();
        var postId  = _this.data('id');
        var type    = _this.data('type');
        jQuery(document).find('#wpEarningsModal').modal('toggle');
        jQuery('body').append(loader);
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: 'type='+ type +'&post_id=' + postId + '&pkg=' + pkg + '&action=codesquare_workintry_buy_gig',
            dataType: "json",
            success: function (response) {                
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') { 
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right'});                                                        
                    window.location.replace(response.checkout_url);
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });
    });

    /*Buy Gig From Sidebar Button*/    
    jQuery(document).on('click', '.wi-buy-gig-from-side', function(e){
        e.preventDefault();       
        if (logged_in == 'false') {
            jQuery.sticky(logoutMessage, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
            return false;
        }
        var _this           = jQuery(this);
        var postId          = _this.data('id');
        var pkg             = _this.data('pkg');
        var selectedAmount  = _this.data('price');       
        var type            = 'default';
      
        //Check for the available earnings
        var availableEarnings = jQuery(document).find('#wpEarningsModal').data('id');                       
        if( availableEarnings == '' || availableEarnings == 'undefined' ){
            availableEarnings = 0;
        }
        //Get selected amount        
        if( selectedAmount == '' || selectedAmount == 'undefined' ){
            selectedAmount = 0;
        }
        //Calculatoinos
        if( availableEarnings >= selectedAmount ){
            jQuery('#hp-gig-val').val(pkg);
            jQuery('#wpEarningsModal').modal('toggle');
            return false;
        }
           
        //So we have to just send ajax request to charge user now
        jQuery('body').append(loader);
        jQuery.ajax({
            type: "POST",
            url: custom_vars.ajaxurl,
            data: 'type='+type+'&post_id=' + postId + '&pkg=' + pkg + '&action=codesquare_workintry_buy_gig',
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.workintry-loader').remove();
                if (response.type === 'success') { 
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right'});                                                        
                    window.location.replace(response.checkout_url);
                } else {                    
                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                }
            }
        });        
    });    

    /*Search Filter*/
    jQuery('.wi-category-filter').on('click', function(e){
        e.preventDefault();
        var val = jQuery(this).data('id');
        jQuery(this).closest('li').siblings('li').find('.wi-category-filter').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(this).closest('li').siblings('li').removeClass('active');
        jQuery(this).closest('li').addClass('active');
        jQuery('.wi-selected-cat').val(val);
    });

    /*Search Filter Type*/
    jQuery('.wi-item-filter').on('click', function(e){
        e.preventDefault();
        var val = jQuery(this).data('id');
        jQuery(this).closest('li').siblings('li').find('.wi-item-filter').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(this).closest('li').siblings('li').removeClass('active');
        jQuery(this).closest('li').addClass('active');
        jQuery('.wi-selected-type').val(val);
    });

    /*Search Filter Delivery*/
    jQuery('.wi-delivery-filter').on('click', function(e){
        e.preventDefault();
        var val = jQuery(this).data('id');
        jQuery(this).closest('li').siblings('li').find('.wi-delivery-filter').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(this).closest('li').siblings('li').removeClass('active');
        jQuery(this).closest('li').addClass('active');
        jQuery('.wi-selected-delivery').val(val);
    });

    /*Search Filter Limit*/
    jQuery('.wi-limit-filter').on('click', function(e){
        e.preventDefault();
        var val = jQuery(this).data('id');
        jQuery(this).closest('li').siblings('li').find('.wi-limit-filter').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(this).closest('li').siblings('li').removeClass('active');
        jQuery(this).closest('li').addClass('active');
        jQuery('.wi-selected-limit').val(val);
    });

    /*Search Filter Level*/
    jQuery('.wi-level-filter').on('click', function(e){
        e.preventDefault();
        var val = jQuery(this).data('id');
        jQuery(this).closest('li').siblings('li').find('.wi-level-filter').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(this).closest('li').siblings('li').removeClass('active');
        jQuery(this).closest('li').addClass('active');
        jQuery('.wi-selected-level').val(val);
    });

    /*Search Filter Rating*/
    jQuery('.wi-rating-filter').on('click', function(e){
        e.preventDefault();
        var val = jQuery(this).data('id');
        jQuery(this).closest('li').siblings('li').find('.wi-rating-filter').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(this).closest('li').siblings('li').removeClass('active');
        jQuery(this).closest('li').addClass('active');
        jQuery('.wi-selected-rating').val(val);
    });

    //Further
    var minPrice        = custom_vars.min_price;
    var maxPrice        = custom_vars.max_price;

    //Add body class based on mobile search
    if( jQuery('.tax-ad_mobile_make').length > 0 || jQuery('.tax-ad_mobile_model').length > 0 || jQuery('.tax-mobile_location').length > 0 || jQuery('.tax-mobile_category').length > 0 ){
        jQuery('body').addClass('page-template-mobiles-search');
    }

    //Add body class based on vehicle search
    if( jQuery('.tax-ad_car_make').length > 0 || jQuery('.tax-ad_car_model').length > 0 || jQuery('.tax-vehicle_category').length > 0 || jQuery('.tax-vehicle_country').length > 0 || jQuery('.tax-vehicle_city').length > 0 ){
        jQuery('body').addClass('page-template-cars-search');
    }

    //Add body class based on property search
    if( jQuery('.tax-gig_category ').length > 0 || jQuery('.tax-property_country').length > 0 || jQuery('.tax-property_city').length > 0 ){
        jQuery('body').addClass('page-template-homes-search');
    }  

    /*Add Padding*/
    if( jQuery('.single-workintry').length > 0 ){
        if( jQuery('.cp-details-header .cp-ad-slider-info').length > 0 ){
            jQuery('.cp-details-header').css('background', '#fffaeb');
        } else {
            jQuery('.cp-details-header .cp-featuredad-content').css('padding', '30px');
        }
    }

    /* Featured Ads Slider*/
    if( jQuery('.cp-featured-slider').length > 0 ){
    var _cp_featured_slider = jQuery('.cp-featured-slider');
        _cp_featured_slider.owlCarousel({
            items: 1,
            nav:false,
            loop:true,
            dots: true,
            autoplay: false,
            dotsClass: 'cp-featured-dots'
        });
    }     

    jQuery('.cp-filterbtn').on('click', function(event){
        event.preventDefault();
        jQuery('.cp-filter-dropdown').slideToggle();
    });

    jQuery('.cp-submit-search-form').on('click', function(){
        jQuery(this).closest('form').trigger('submit');
    });

   
    /* Price Range */
    var selectedMin = jQuery('#min-price').val();
    var selectedMax = jQuery('#max-price').val();
    if( selectedMin == 'undefined' || selectedMin == '' ){
        selectedMin = 0;
    }
    if( selectedMax == 'undefined' || selectedMax == '' ){
        selectedMax = 0;
    }

    if( selectedMax == 0 ){
        selectedMax = maxPrice;
    }
    
    //For Search Page Only
    if( $('.cp-pricerange').length > 0 ){
        $( function() {
            $( ".cp-pricerange" ).slider({
              range: true,
              max: maxPrice,
              values: [ selectedMin, selectedMax ],
              slide: function( event, ui ) {
               // $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                $( "#min-price" ).val( ui.values[ 0 ] );
                $( '#max-price' ).val( ui.values[ 1 ] );
                $( ".cp-price-range-filter .cp-min-price" ).text( ui.values[ 0 ] );
                $( ".cp-price-range-filter .cp-max-price" ).text( ui.values[ 1 ] );
              }
            });
        });
    }
    
    //Set Max Price
    if( $( ".cp-price-range-filter .cp-max-price" ).length > 0 ){
        var oldPrice = $( ".cp-price-range-filter .cp-max-price" ).text();
        if( oldPrice == '' || oldPrice == 0 ){
            oldPrice = maxPrice;
        }
        $( ".cp-price-range-filter .cp-max-price" ).text( oldPrice );
    }

    //List and Grid view
    jQuery('.cp-filter-gl').on('click', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        var view = _this.data('id');
        jQuery('#cp-filter-view').val(view);
        jQuery(this).closest('form').trigger('submit');
    });

    //Filter for Date And Price
    jQuery('.filteractive').on('click', function(){
        jQuery("#recent").prop("checked", true);
        jQuery(this).closest('form').trigger('submit');
    });

    //Filter for Date And Price
    jQuery('.filterpriceactive').on('click', function(){
        jQuery("#price").prop("checked", true);
        jQuery(this).closest('form').trigger('submit');
    });

    // Top Freelacner Slider
    if( jQuery('.wi-freelacnerimg').length > 0 ){
        var wi_freelacnerimg = jQuery('.wi-freelacnerimg')
        wi_freelacnerimg.owlCarousel({
            items: 1,
            loop:true,
            nav:false,
            margin: 0,
            dots: false,
            autoplay:false,
            navClass: ['wi-prev', 'wi-next'],
            navContainerClass: 'wi-slider-nav',
            navText: ['<span class="ti-angle-left"></span>', '<span class="ti-angle-right"></span>'],
        });
    }   

    /*Slider for categories*/
    if( jQuery('.wi-serviceslider').length > 0 ){
        var wi_serviceslider = jQuery('.wi-serviceslider');
        wi_serviceslider.owlCarousel({
            items: 4,
            loop:true,
            nav:false,
            margin: 30,
            dots: false,
            autoplay:false,
            autoWidth:true,
        });
    }

    /*Counter*/
    if( jQuery('#wi-counter' ).length > 0 ){
        try {
            var _wi_counter = jQuery('#wi-counter');
            _wi_counter.appear(function () {
                var _wi_counter = jQuery('.wi-countercontent h3 em');
                _wi_counter.countTo({
                    formatter: function (value, options) {
                        return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
                    }
                });
            });
        } catch (err) {}
    }

    // Community Slider
    if( jQuery('.wi-communityslider').length > 0 ){
        var wi_communityslider = jQuery('.wi-communityslider');
        wi_communityslider.owlCarousel({
            items: 5,
            loop:true,
            nav:false,
            margin: 30,
            dots: true,
            autoplay:false,
            responsiveClass:true,
            responsive:{
                0:{items:1, },
                767:{items:2, },
                991:{items:3},
                1281:{items:5},
            },
        });
    }
});

/*
 Sticky v2.1.2 by Andy Matthews
 http://twitter.com/commadelimited
 
 forked from Sticky by Daniel Raftery
 http://twitter.com/ThrivingKings
 */
(function ($) {

    $.sticky = $.fn.sticky = function (note, options, callback) {

        // allow options to be ignored, and callback to be second argument
        if (typeof options === 'function')
            callback = options;

        // generate unique ID based on the hash of the note.
        var hashCode = function (str) {
            var hash = 0,
                    i = 0,
                    c = '',
                    len = str.length;
            if (len === 0)
                return hash;
            for (i = 0; i < len; i++) {
                c = str.charCodeAt(i);
                hash = ((hash << 5) - hash) + c;
                hash &= hash;
            }
            return 's' + Math.abs(hash);
        },
                o = {
                    position: 'top-right', // top-left, top-right, bottom-left, or bottom-right
                    speed: 'fast', // animations: fast, slow, or integer
                    allowdupes: true, // true or false
                    autoclose: 5000, // delay in milliseconds. Set to 0 to remain open.
                    classList: '' // arbitrary list of classes. Suggestions: success, warning, important, or info. Defaults to ''.
                },
        uniqID = hashCode(note), // a relatively unique ID
                display = true,
                duplicate = false,
                //tmpl = '<div class="cl-alertmessage" id="ID"><div class="sticky border-POS"><em class="lnr lnr-bullhorn CLASSLIST"></em><span class="sticky-close"></span><p class="sticky-note">NOTE</p></div></div>',
                tmpl = '<div id="ID" class="jf-alert alert-dismissible border-POS CLASSLIST" role="alert"><button type="button" class="jf-close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lnr lnr-cross"></i></span></button><div class="jf-description"><p>NOTE</p></div></div>',
                positions = ['top-right', 'top-center', 'top-left', 'bottom-right', 'bottom-center', 'bottom-left','middle-left','middle-right','middle-center'];

        // merge default and incoming options
        if (options)
            $.extend(o, options);

        // Handling duplicate notes and IDs
        $('.sticky').each(function () {
            if ($(this).attr('id') === hashCode(note)) {
                duplicate = true;
                if (!o.allowdupes)
                    display = false;
            }
            if ($(this).attr('id') === uniqID)
                uniqID = hashCode(note);
        });
        
        if( custom_vars.sm_success ){
            var _position   = custom_vars.sm_success;
        } else{
            var _position   = o.position;
        }
        
        // Make sure the sticky queue exists
        if (!$('.sticky-queue').length) {
            $('body').append('<div class="sticky-queue ' + _position + '">');
        } else {
            // if it exists already, but the position param is different,
            // then allow it to be overridden
            $('.sticky-queue').removeClass(positions.join(' ')).addClass(_position);
        }

        // Can it be displayed?
        if (display) {
            // Building and inserting sticky note
            $('.sticky-queue').prepend(
                    tmpl
                    .replace('POS', _position)
                    .replace('ID', uniqID)
                    .replace('NOTE', note)
                    .replace('CLASSLIST', o.classList)
                    ).find('#' + uniqID)
                    .slideDown(o.speed, function () {
                        display = true;
                        // Callback function?
                        if (callback && typeof callback === 'function') {
                            callback({
                                'id': uniqID,
                                'duplicate': duplicate,
                                'displayed': display
                            });
                        }
                    });

        }

        // Listeners
        $('.sticky').ready(function () {
            // If 'autoclose' is enabled, set a timer to close the sticky
            if (o.autoclose) {
                $('#' + uniqID).delay(o.autoclose).fadeOut(o.speed, function () {
                    // remove element from DOM
                    $(this).remove();
                });
            }
        });

        // Closing a sticky
        $('.jf-close').on('click', function () {
            var _this   = $(this);
            
            if( _this.parents('.jf-alert').hasClass('sp-cacheit') ){
                $.confirm({
                    'title': custom_vars.cache_title,
                    'message': custom_vars.cache_message,
                    'buttons': {
                        'Yes': {
                            'class': 'blue',
                            'action': function () {

                                if( _this.parents('.jf-alert').hasClass('cache-verification') ){
                                    $.cookie('sp_cache_verification_'+custom_vars.current_user_id, 'true', { expires: 365 });
                                } else if( _this.parents('.jf-alert').hasClass('cache-deactivation') ){
                                    $.cookie('sp_cache_deactivation_'+custom_vars.current_user_id, 'true', { expires: 365 });
                                }

                                //Remove message
                                $('#' + _this.parents('.jf-alert').attr('id')).dequeue().fadeOut(o.speed, function () {
                                    // remove element from DOM
                                    _this.remove();
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
            } else{
                //Remove message
                _this.parents('.jf-alert').remove();
                $('#' + _this.parents('.jf-alert').attr('id')).dequeue().fadeOut(o.speed, function () {
                    // remove element from DOM
                    _this.remove();
                });
            }
        });

    };
})(jQuery);

//Print starts
function codesquareworkintryPrintStars(ratingValue, id ){
    var roundedValue = Math.trunc(ratingValue);        
    for (var j = 0; j < roundedValue; j++) {
      jQuery(id).append('<i class="fa fa-star" aria-hidden="true"></i>');
    }
   var k = 0;
    if (ratingValue -roundedValue  > 0.4 && ratingValue -roundedValue < 1) {
      k = 1;
      jQuery(id).append('<i class="fa fa-star-half" aria-hidden="true"></i>');
    }
    for (var i = Math.trunc(ratingValue)+k; i < 5; i++) {
      jQuery(id).append('<i class="fa fa-star-o" aria-hidden="true"></i>');
    }
}

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
            buttons.eq(i++).on('click', function () {

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

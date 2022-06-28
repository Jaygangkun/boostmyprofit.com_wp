"use strict";
jQuery(document).ready(function(){
	//Get Ajax URL
	var ajaxUrl = custom_vars.ajaxurl;
	var currentUser = jQuery('.from-user').val();	
	//Websocket based chat system
	jQuery(document).on('click', '.pc-inboxname-content a', function(){
		var to_user_id = jQuery(this).data('id');		
		var _this = jQuery(this);
		var noticeClass = '.pc-notice-chat-count-'+currentUser+' em';		
		jQuery.ajax({
            type: "POST",
            url: ajaxUrl,
            data: 'to_user_id='+to_user_id+'&action=codesquare_workintry_get_all_chat_with_user',            
            success: function (response) {            	            	
            	if( response.count != 'none' ){
            		//
            	}
                jQuery('.pc-load-chat').html(response.data);
                jQuery('.pc-chatuser').html(response.user);

                jQuery('.pc-chatuser').removeClass();
                jQuery('.pc-chatarea div:first').addClass('pc-chatuser');
                jQuery('.pc-chatuser').addClass('pc-user-logged-in-'+to_user_id);               
                if( response.count != 'none' ){
                	jQuery('.pc-notiarea em').html(response.count);
            	} else {
            		jQuery('.pc-notiarea em').html('0');
            	}
            	_this.closest('.pc-inboxname').find('em').remove();            	
            	jQuery("html, body .pc-load-chat").animate({ scrollTop: 999999999999999999});            	
            	jQuery('.pc-replaybox').css('display', 'block');
            	jQuery('.msg').focus();    
               
                //Lightgallery now
                var chat_Content = jQuery('.pc-chatarea .pc-messages-section');                
                chat_Content.lightGallery();
                chat_Content.data('lightGallery').destroy(true);
                chat_Content.lightGallery({
                    selector: '.item'
                });                
            }
        });	
	});

	/*Stop form submit of chat*/
	jQuery(".pc-formtheme.pc-replaybox").on('submit', function(e){
        e.preventDefault();
    });

    /*Stop chat search form*/
    jQuery(".pc-formtheme.pc-inboxsearch").on('submit', function(e){
        e.preventDefault();
    });
    

    /*Check for the enter key button press*/
    jQuery('.msg.form-control').keypress(function(event){
	  if(event.keyCode == 13){	   
	    jQuery('.send').trigger('click');
	  }
	});

	/*Filter users list*/
	jQuery(".pc-inboxsearch input").keyup(function(){
 
        // Retrieve the input field text and reset the count to zero
        var filter = jQuery(this).val();
 
        // Loop through the comment list
        jQuery(".pc-inboxname-holder .pc-inboxname h5").each(function(){
 
            // If the list item does not contain the text phrase fade it out
            if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
            	jQuery(this).closest('.pc-inboxname').fadeOut();               
 
            // Show the list item if the phrase matches and increase the count by 1
            } else {
                jQuery(this).closest('.pc-inboxname').show();               
            }
        });         
    });	

    /*Gallery Image Uploader*/
    var uploaderChatArguments = {
        browse_button: 'cl-upload-chat-gallery', // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'cf_gallery_uploader',
        container: 'plupload-gallery-container',
        runtimes: 'html5',
        drop_element: 'cl-upload-chat-gallery',
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

    var ProfileGalleryUploader
ProfileGalleryUploader
var ChateGalleryUploader;
ChateGalleryUploader = new plupload.Uploader(uploaderChatArguments);
    ProfileGalleryUploader
ProfileGalleryUploader
ChateGalleryUploader.init();
    
    //Method bind 
    ProfileGalleryUploader
ProfileGalleryUploader
ChateGalleryUploader.bind('FilesAdded', function (up, files) {
        var _thumb = "";
        plupload.each(files, function (file) {
            //add any thing as per your needs
        });        
        up.refresh();
        ProfileGalleryUploader
ProfileGalleryUploader
ChateGalleryUploader.start();
    });

    //Method Progress
    ProfileGalleryUploader
ProfileGalleryUploader
ChateGalleryUploader.bind('UploadProgress', function (up, file) { 
        jQuery('#custom-progress-bar').css('display', 'block');    
        jQuery('#custom-progress').css('width', '1%');    
        //Add you code like for progress bar        
        jQuery('#custom-progress').css('width', file.percent + '%');     
        console.log(file.percent);     
    });

    //Method Error
    ProfileGalleryUploader
ProfileGalleryUploader
ChateGalleryUploader.bind('Error', function (up, err) {
        //Show warning/error
        jQuery.sticky(err.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
    });


    //display data
    ProfileGalleryUploader
ProfileGalleryUploader
ChateGalleryUploader.bind('FileUploaded', function (up, file, ajax_response) {
        var response = jQuery.parseJSON(ajax_response.response);        
        if (response.type === 'success') {
            var append_profile_photo = wp.template('append-gallery-photo');
            var count = codesquareworkintryRandomChatNumber();
            var data = {count: count, response: response};
            var _thumb = append_profile_photo(data);           
            jQuery('.cf-gallery-images #mCSB_1_container').append(_thumb);
            //Custom progress bar            
            jQuery(document).find('#custom-progress').css('width', '1%'); 
            jQuery('#custom-progress-bar').css('display', 'none');              
        } else {            
            jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
        }           
    });
    
    //Random Number Generator
    function codesquareworkintryRandomChatNumber() {     
      var Number = Math.floor((Math.random() * 99999) + 1);
      return Number;
    }

    //Chat JS    
    var senderID = '';      
    var fromUserIdOld = jQuery('.from-user').val();    
    var userId = jQuery('.from-user').val();            
    var fromUser = userId;        
    var conn = new WebSocket('ws://localhost:8081/');    
    conn.onopen = function(e) {
        console.log("Connection established!");             
        var data = {
            type: 'login',
            user_id: userId,
            msg: 'text'             
        }
        conn.send(JSON.stringify(data));            
    };

    //Send Message
    jQuery('.send').on('click',function(){
        //Test Gallery
        var galleriesImages = [];
        var galleriesIds = [];
        jQuery(".galleryIds").each(function(){ galleriesIds.push(this.value); });              
        jQuery(".galleryImages").each(function(){ galleriesImages.push(this.value); });              
        if( galleriesImages.length > 0 ){

        } else {
            galleriesImages = '';
        }
        if( galleriesIds.length > 0 ){

        } else {
            galleriesIds = '';
        }               
        //To user
        if( jQuery('.to-user').length ){

        } else {
            return false;
        }
        var touser  = jQuery('.to-user').val();                  
        var msg     = jQuery('.msg').val();         
        if( touser == '' || touser == 'undefined' ){
            return false;
        }
        //Check Message
        if( msg == '' || msg == 'undefined' ){
            return false;
        }
        var chat = '';
        chat = 'chat';
        var data = {
            type: chat,             
            msg: msg,
            to: touser,
            from: userId,
            imagesIds: galleriesIds,
            imagesUrls:galleriesImages
        }
        conn.send(JSON.stringify(data));            
        jQuery('.msg').val('');
        jQuery('.cf-hscrollbar.cf-gallery-images li').remove();
        jQuery(".galleryIds").remove();              
        jQuery(".galleryImages").remove();      
    });

    //Send typing message
    jQuery(document).on('focus', '.msg.form-control', function(){           
        var chat    = '';
        var msg     = '';
        var touser  = jQuery('.to-user').val(); 
        chat = 'typing';        
        var data = {
            type: chat,             
            msg: msg,
            to: touser,
            from: userId
        }
        conn.send(JSON.stringify(data));
    });

    //Send stop typing message
    jQuery(document).on('blur', '.msg.form-control', function(){            
        var chat    = '';
        var msg     = '';
        var touser  = jQuery('.to-user').val(); 
        chat = 'typingstop';
        var data = {
            type: chat,             
            msg: msg,
            to: touser,
            from: userId
        }
        conn.send(JSON.stringify(data));
    });

    conn.onmessage = function(e) {    
        var touserClass = jQuery('.to-user').val();
        var data = JSON.parse(e.data);       
        if( data.type == 'login' ){             
            LoggedInUserId = data.userid;     
            console.log(data.mytoken);
            jQuery('.pc-user-logged-in-'+LoggedInUserId).find('.pc-inboxname-img').removeClass('pc-useroffline');
            jQuery('.pc-user-logged-in-'+LoggedInUserId).find('.pc-inboxname-img').addClass('pc-useronline');
            //Set last seen time to Online
            jQuery('.pc-user-logged-in-'+LoggedInUserId).find('.pc-inboxname-content span').remove();
            jQuery('.pc-user-logged-in-'+LoggedInUserId).find('.pc-inboxname-content').append('<span>Online</span>');
            //Filter Online Users to shuffle list if any bottom one is just online
            var onlineUsersHtml = '';
            jQuery(".pc-inboxname-holder .pc-inboxname").each(function(){
                // Retrieve online users and create an object        
                if( jQuery(this).find('.pc-useronline').length ){           
                    onlineUsersHtml +=  jQuery(this).wrap('<p/>').parent().html();          
                    jQuery(this).remove();
                }
            });
            //Append to list
            jQuery(".pc-inboxname-holder").prepend(onlineUsersHtml);
            jQuery(".pc-inboxname-holder > p").remove();
        } else if( data.type == 'typing' ){                
            var toUserProfile = data.to;    
            var fromUserTyping = data.from;
            //Check for the open window of receiver and post there
            if( jQuery( '.pc-messages-section-'+toUserProfile+' .to-user' ).val() == fromUserTyping ){  
                jQuery('.pc-chatarea .pc-user-is-typing span').html('User is yping...');                    
            } 
        }else if( data.type == 'typingstop' ){            
            var toUserProfile = data.to;    
            var fromUserTyping = data.from;
            //Check for the open window of receiver and post there
            if( jQuery( '.pc-messages-section-'+toUserProfile+' .to-user' ).val() == fromUserTyping ){  
                jQuery('.pc-chatarea .pc-user-is-typing span').html('');                    
            } 
        }else if( data.type == 'logout' ){
            var loggedOutId = data.userid;
            timeAgo = data.time;
            jQuery('.pc-user-logged-in-'+loggedOutId).find('.pc-inboxname-img').removeClass('pc-useronline');
            jQuery('.pc-user-logged-in-'+loggedOutId).find('.pc-inboxname-img').addClass('pc-useroffline');
            //Set last seen time
            jQuery('.pc-user-logged-in-'+loggedOutId).find('.pc-inboxname-content span').html('');
            jQuery('.pc-user-logged-in-'+loggedOutId).find('.pc-inboxname-content span').html('Last seen ');
            jQuery('.pc-user-logged-in-'+loggedOutId).find('.pc-inboxname-content').append('<span class="time" data-livestamp="'+timeAgo+'"> a few seconds ago</span>');
            //Filter Offline Users to shuffle list if any bottom one is just online
            var offlineUsersHtml = '';
            jQuery(".pc-inboxname-holder .pc-inboxname").each(function(){
                // Retrieve online users and create an object        
                if( jQuery(this).find('.pc-useroffline').length ){          
                    offlineUsersHtml +=  jQuery(this).wrap('<p/>').parent().html();         
                    jQuery(this).remove();
                }
            });
            //Append to list
            jQuery(".pc-inboxname-holder").append(offlineUsersHtml);
            jQuery(".pc-inboxname-holder > p").remove();
        } else {                                
            var name            = data.name;
            var msg             = data.msg; 
            var toUser          = data.to;  
            var toUserId        = toUser;
            var fromUserId      = data.from;
            var classnow        = '';
            var messageTime     =  data.time;

            //Apply proper class
            var senderUserId = jQuery('.to-user').val();
            if( fromUserId == fromUserIdOld ){
                classnow = 'pc-messagessend pc-reservedmsg';
            } else {
                classnow = 'pc-messagessend';
            }
        
            //Create Full Message
            var fullMessage = '<div class="'+classnow+'"><div class="pc-messagessend-content"><span class="time" data-livestamp="'+messageTime+'">a few seconds ago</span>'+msg+'</div></div>';     

            //Check for the sender here and post to his/her screen  
            if( jQuery( '.pc-messages-section-'+fromUserId+' .to-user' ).val() == toUserId ){               
                jQuery('.pc-messages-section-'+fromUserId+'').append(fullMessage);  
            }

            //Check for the open window of receiver and post there
            if( jQuery( '.pc-messages-section-'+toUserId+' .to-user' ).val() == fromUserId ){               
                jQuery('.pc-messages-section-'+toUserId+'').append(fullMessage);    
                //Append this to his/her onlnie list
                jQuery('.pc-user-logged-in-'+fromUserIdOld).find('span').html(data.msg);
            }
            //Scroll to sent message
            jQuery("html, body .pc-load-chat").animate({ scrollTop: 999999999999999999}); 
            //Lightgallery now
            var chat_Content = jQuery('.pc-chatarea .pc-messages-section');                
            if( chat_Content.data('lightGallery') ){                    
                chat_Content.data('lightGallery').destroy(true);
            } else {  

            }
            chat_Content.lightGallery({
                selector: '.item'
            });
            function playSound() {
                var audioUrl = jQuery('#audio-file').data('id');
                var audio = '<audio id="audio-"'+toUserId+' src="'+audioUrl+'" autoplay="false" ></audio>';
                jQuery('#audio-file').html(audio);                  
                var sound = document.getElementById("audio-"+toUserId); 
                if( sound != null || sound != 'undefined' ){             
                    sound.play();
                }                   
            }        
            //Update their count now
            jQuery(".pc-notice-chat-count-"+toUserId+" a em").text(data.count);
            playSound();            
        }
    };      

    conn.onclose = function(e) {
        console.log(e.data);
    };          
});
jQuery(document).ready(function($){

	"use strict";

	// Add the new WP media uploader
	jQuery('.custom_media_upload').click(function() {

	    var send_attachment_bkp = wp.media.editor.send.attachment;
	    // this id is added as an additional class name
	    var current_img_id = $(this).parent('.custom-img-container').find('.custom_media_url').attr('id');

	    wp.media.editor.send.attachment = function(props, attachment) {

	       $('.custom_media_image.'+current_img_id).attr('src', attachment.url);
	       $('.custom_media_url.'+current_img_id).val(attachment.url);

	    	wp.media.editor.send.attachment = send_attachment_bkp;
		};

		wp.media.editor.open();

		return false;
	});

	jQuery('.custom_clear_image_button').click(function() {

		// grab current image id to use with class name
		var imageId = jQuery(this).parent().siblings('.custom_media_url').attr("id");
		var defaultImage = jQuery(this).parent().siblings('.custom-preview-image').text();

		$('.custom_media_url.'+imageId).val('');
		$('.custom_media_image.'+imageId).attr("src", defaultImage);
		return false;
	});

});

// Add a new input element into our sortable list
jQuery('.repeatable-add').click(function() {

 	// clone the last "li" in our list
 	var field = jQuery(this).closest('td').find('.custom_repeatable li:last-child').clone(true);

        // find the end of our list
		var fieldLocation = jQuery(this).closest('td').find('.custom_repeatable li:last-child');

        // increment our name input value
        jQuery('input', field).val('').attr('name', function( index, name ) {

                return name.replace( /(\d+)/, function( fullMatch, n ) {
                        return Number(n) + 1;
                 });
        });

        // insert our clone "li" into the end of our list
        field.insertAfter(fieldLocation, jQuery(this).closest('td'));
        return false;

 });

 jQuery('.repeatable-remove').click(function() {

        // find how many list items we have
       var fieldNum = jQuery('.custom_repeatable').find('li').length;

        if ( fieldNum <= 1 ) {

            // disable remove button if we only have one list item
           	jQuery(this).prop( "disabled", false );
            return false;

        } else {

            // remove "li" item
            jQuery(this).parent().remove();
            return false;
        }

});


jQuery('.repeatable-remove').click(function() {

	// find how many list items we have
	var fieldNum = jQuery('.custom_repeatable').find('li').length;

	if ( fieldNum <= 1 ) {

		// disable remove button if we only have one list item
		jQuery(this).prop( "disabled", false );
		return false;

	} else {

		// remove "li" item
		jQuery(this).parent().remove();
		return false;
	}

});

// format our phone inputs correctly
jQuery(function($){
   $(".phone").mask("(999) 999-9999");
});




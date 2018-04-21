/* global jQuery:false */

jQuery(document).ready(function() {
	UNICAEVENTS_GLOBALS['media_frame'] = null;
	UNICAEVENTS_GLOBALS['media_link'] = '';
});

function unicaevents_show_media_manager(el) {
	"use strict";

	UNICAEVENTS_GLOBALS['media_link'] = jQuery(el);
	// If the media frame already exists, reopen it.
	if ( UNICAEVENTS_GLOBALS['media_frame'] ) {
		UNICAEVENTS_GLOBALS['media_frame'].open();
		return false;
	}

	// Create the media frame.
	UNICAEVENTS_GLOBALS['media_frame'] = wp.media({
		// Set the title of the modal.
		title: UNICAEVENTS_GLOBALS['media_link'].data('choose'),
		// Tell the modal to show only images.
		library: {
			type: 'image'
		},
		// Multiple choise
		multiple: UNICAEVENTS_GLOBALS['media_link'].data('multiple')===true ? 'add' : false,
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: UNICAEVENTS_GLOBALS['media_link'].data('update'),
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.
			close: true
		}
	});

	// When an image is selected, run a callback.
	UNICAEVENTS_GLOBALS['media_frame'].on( 'select', function(selection) {
		"use strict";
		// Grab the selected attachment.
		var field = jQuery("#"+UNICAEVENTS_GLOBALS['media_link'].data('linked-field')).eq(0);
		var attachment = '';
		if (UNICAEVENTS_GLOBALS['media_link'].data('multiple')===true) {
			UNICAEVENTS_GLOBALS['media_frame'].state().get('selection').map( function( att ) {
				attachment += (attachment ? "\n" : "") + att.toJSON().url;
			});
			var val = field.val();
			attachment = val + (val ? "\n" : '') + attachment;
		} else {
			attachment = UNICAEVENTS_GLOBALS['media_frame'].state().get('selection').first().toJSON().url;
		}
		field.val(attachment);
		field.trigger('change');
	});

	// Finally, open the modal.
	UNICAEVENTS_GLOBALS['media_frame'].open();
	return false;
}

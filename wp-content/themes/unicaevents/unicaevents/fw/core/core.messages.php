<?php
/**
 * UnicaEvents Framework: messages subsystem
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('unicaevents_messages_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_messages_theme_setup' );
	function unicaevents_messages_theme_setup() {
		// Core messages strings
		add_action('unicaevents_action_add_scripts_inline', 'unicaevents_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('unicaevents_get_error_msg')) {
	function unicaevents_get_error_msg() {
		global $UNICAEVENTS_GLOBALS;
		return !empty($UNICAEVENTS_GLOBALS['error_msg']) ? $UNICAEVENTS_GLOBALS['error_msg'] : '';
	}
}

if (!function_exists('unicaevents_set_error_msg')) {
	function unicaevents_set_error_msg($msg) {
		global $UNICAEVENTS_GLOBALS;
		$msg2 = unicaevents_get_error_msg();
		$UNICAEVENTS_GLOBALS['error_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('unicaevents_get_success_msg')) {
	function unicaevents_get_success_msg() {
		global $UNICAEVENTS_GLOBALS;
		return !empty($UNICAEVENTS_GLOBALS['success_msg']) ? $UNICAEVENTS_GLOBALS['success_msg'] : '';
	}
}

if (!function_exists('unicaevents_set_success_msg')) {
	function unicaevents_set_success_msg($msg) {
		global $UNICAEVENTS_GLOBALS;
		$msg2 = unicaevents_get_success_msg();
		$UNICAEVENTS_GLOBALS['success_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('unicaevents_get_notice_msg')) {
	function unicaevents_get_notice_msg() {
		global $UNICAEVENTS_GLOBALS;
		return !empty($UNICAEVENTS_GLOBALS['notice_msg']) ? $UNICAEVENTS_GLOBALS['notice_msg'] : '';
	}
}

if (!function_exists('unicaevents_set_notice_msg')) {
	function unicaevents_set_notice_msg($msg) {
		global $UNICAEVENTS_GLOBALS;
		$msg2 = unicaevents_get_notice_msg();
		$UNICAEVENTS_GLOBALS['notice_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('unicaevents_set_system_message')) {
	function unicaevents_set_system_message($msg, $status='info', $hdr='') {
		update_option('unicaevents_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('unicaevents_get_system_message')) {
	function unicaevents_get_system_message($del=false) {
		$msg = get_option('unicaevents_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			unicaevents_del_system_message();
		return $msg;
	}
}

if (!function_exists('unicaevents_del_system_message')) {
	function unicaevents_del_system_message() {
		delete_option('unicaevents_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('unicaevents_messages_add_scripts_inline')) {
	function unicaevents_messages_add_scripts_inline() {
		global $UNICAEVENTS_GLOBALS;
		echo '<script type="text/javascript">'
			
			. "if (typeof UNICAEVENTS_GLOBALS == 'undefined') var UNICAEVENTS_GLOBALS = {};"
			
			// Strings for translation
			. 'UNICAEVENTS_GLOBALS["strings"] = {'
				. 'ajax_error: 			"' . addslashes(esc_html__('Invalid server answer', 'unicaevents')) . '",'
				. 'bookmark_add: 		"' . addslashes(esc_html__('Add the bookmark', 'unicaevents')) . '",'
				. 'bookmark_added:		"' . addslashes(esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'unicaevents')) . '",'
				. 'bookmark_del: 		"' . addslashes(esc_html__('Delete this bookmark', 'unicaevents')) . '",'
				. 'bookmark_title:		"' . addslashes(esc_html__('Enter bookmark title', 'unicaevents')) . '",'
				. 'bookmark_exists:		"' . addslashes(esc_html__('Current page already exists in the bookmarks list', 'unicaevents')) . '",'
				. 'search_error:		"' . addslashes(esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'unicaevents')) . '",'
				. 'email_confirm:		"' . addslashes(esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'unicaevents')) . '",'
				. 'reviews_vote:		"' . addslashes(esc_html__('Thanks for your vote! New average rating is:', 'unicaevents')) . '",'
				. 'reviews_error:		"' . addslashes(esc_html__('Error saving your vote! Please, try again later.', 'unicaevents')) . '",'
				. 'error_like:			"' . addslashes(esc_html__('Error saving your like! Please, try again later.', 'unicaevents')) . '",'
				. 'error_global:		"' . addslashes(esc_html__('Global error text', 'unicaevents')) . '",'
				. 'name_empty:			"' . addslashes(esc_html__('The name can\'t be empty', 'unicaevents')) . '",'
				. 'name_long:			"' . addslashes(esc_html__('Too long name', 'unicaevents')) . '",'
				. 'email_empty:			"' . addslashes(esc_html__('Too short (or empty) email address', 'unicaevents')) . '",'
				. 'email_long:			"' . addslashes(esc_html__('Too long email address', 'unicaevents')) . '",'
				. 'email_not_valid:		"' . addslashes(esc_html__('Invalid email address', 'unicaevents')) . '",'
				. 'subject_empty:		"' . addslashes(esc_html__('The subject can\'t be empty', 'unicaevents')) . '",'
				. 'subject_long:		"' . addslashes(esc_html__('Too long subject', 'unicaevents')) . '",'
				. 'text_empty:			"' . addslashes(esc_html__('The message text can\'t be empty', 'unicaevents')) . '",'
				. 'text_long:			"' . addslashes(esc_html__('Too long message text', 'unicaevents')) . '",'
				. 'send_complete:		"' . addslashes(esc_html__("Send message complete!", 'unicaevents')) . '",'
				. 'send_error:			"' . addslashes(esc_html__('Transmit failed!', 'unicaevents')) . '",'
				. 'login_empty:			"' . addslashes(esc_html__('The Login field can\'t be empty', 'unicaevents')) . '",'
				. 'login_long:			"' . addslashes(esc_html__('Too long login field', 'unicaevents')) . '",'
				. 'login_success:		"' . addslashes(esc_html__('Login success! The page will be reloaded in 3 sec.', 'unicaevents')) . '",'
				. 'login_failed:		"' . addslashes(esc_html__('Login failed!', 'unicaevents')) . '",'
				. 'password_empty:		"' . addslashes(esc_html__('The password can\'t be empty and shorter then 4 characters', 'unicaevents')) . '",'
				. 'password_long:		"' . addslashes(esc_html__('Too long password', 'unicaevents')) . '",'
				. 'password_not_equal:	"' . addslashes(esc_html__('The passwords in both fields are not equal', 'unicaevents')) . '",'
				. 'registration_success:"' . addslashes(esc_html__('Registration success! Please log in!', 'unicaevents')) . '",'
				. 'registration_failed:	"' . addslashes(esc_html__('Registration failed!', 'unicaevents')) . '",'
				. 'geocode_error:		"' . addslashes(esc_html__('Geocode was not successful for the following reason:', 'wspace')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(esc_html__('Google map API not available!', 'unicaevents')) . '",'
				. 'editor_save_success:	"' . addslashes(esc_html__("Post content saved!", 'unicaevents')) . '",'
				. 'editor_save_error:	"' . addslashes(esc_html__("Error saving post data!", 'unicaevents')) . '",'
				. 'editor_delete_post:	"' . addslashes(esc_html__("You really want to delete the current post?", 'unicaevents')) . '",'
				. 'editor_delete_post_header:"' . addslashes(esc_html__("Delete post", 'unicaevents')) . '",'
				. 'editor_delete_success:	"' . addslashes(esc_html__("Post deleted!", 'unicaevents')) . '",'
				. 'editor_delete_error:		"' . addslashes(esc_html__("Error deleting post!", 'unicaevents')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(esc_html__('Cancel', 'unicaevents')) . '",'
				. 'editor_caption_close:	"' . addslashes(esc_html__('Close', 'unicaevents')) . '"'
				. '};'
			
			. '</script>';
	}
}
?>
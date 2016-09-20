<?php
/**
 * Invetex Framework: messages subsystem
 *
 * @package	invetex
 * @since	invetex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('invetex_messages_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_messages_theme_setup' );
	function invetex_messages_theme_setup() {
		// Core messages strings
		add_filter('invetex_filter_localize_script', 'invetex_messages_localize_script');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('invetex_get_error_msg')) {
	function invetex_get_error_msg() {
		return invetex_storage_get('error_msg');
	}
}

if (!function_exists('invetex_set_error_msg')) {
	function invetex_set_error_msg($msg) {
		$msg2 = invetex_get_error_msg();
		invetex_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('invetex_get_success_msg')) {
	function invetex_get_success_msg() {
		return invetex_storage_get('success_msg');
	}
}

if (!function_exists('invetex_set_success_msg')) {
	function invetex_set_success_msg($msg) {
		$msg2 = invetex_get_success_msg();
		invetex_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('invetex_get_notice_msg')) {
	function invetex_get_notice_msg() {
		return invetex_storage_get('notice_msg');
	}
}

if (!function_exists('invetex_set_notice_msg')) {
	function invetex_set_notice_msg($msg) {
		$msg2 = invetex_get_notice_msg();
		invetex_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('invetex_set_system_message')) {
	function invetex_set_system_message($msg, $status='info', $hdr='') {
		update_option(invetex_storage_get('options_prefix') . '_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('invetex_get_system_message')) {
	function invetex_get_system_message($del=false) {
		$msg = get_option(invetex_storage_get('options_prefix') . '_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			invetex_del_system_message();
		return $msg;
	}
}

if (!function_exists('invetex_del_system_message')) {
	function invetex_del_system_message() {
		delete_option(invetex_storage_get('options_prefix') . '_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('invetex_messages_localize_script')) {
	//add_filter('invetex_filter_localize_script', 'invetex_messages_localize_script');
	function invetex_messages_localize_script($vars) {
		$vars['strings'] = array(
			'ajax_error'		=> esc_html__('Invalid server answer', 'invetex'),
			'bookmark_add'		=> esc_html__('Add the bookmark', 'invetex'),
            'bookmark_added'	=> esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'invetex'),
            'bookmark_del'		=> esc_html__('Delete this bookmark', 'invetex'),
            'bookmark_title'	=> esc_html__('Enter bookmark title', 'invetex'),
            'bookmark_exists'	=> esc_html__('Current page already exists in the bookmarks list', 'invetex'),
			'search_error'		=> esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'invetex'),
			'email_confirm'		=> esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'invetex'),
			'reviews_vote'		=> esc_html__('Thanks for your vote! New average rating is:', 'invetex'),
			'reviews_error'		=> esc_html__('Error saving your vote! Please, try again later.', 'invetex'),
			'error_like'		=> esc_html__('Error saving your like! Please, try again later.', 'invetex'),
			'error_global'		=> esc_html__('Global error text', 'invetex'),
			'name_empty'		=> esc_html__('The name can\'t be empty', 'invetex'),
			'name_long'			=> esc_html__('Too long name', 'invetex'),
			'email_empty'		=> esc_html__('Too short (or empty) email address', 'invetex'),
			'email_long'		=> esc_html__('Too long email address', 'invetex'),
			'email_not_valid'	=> esc_html__('Invalid email address', 'invetex'),
			'subject_empty'		=> esc_html__('The subject can\'t be empty', 'invetex'),
			'subject_long'		=> esc_html__('Too long subject', 'invetex'),
			'phone_empty'		=> esc_html__('The phone can\'t be empty', 'invetex'),
			'phone_long'		=> esc_html__('Too long phone', 'invetex'),
			'text_empty'		=> esc_html__('The message text can\'t be empty', 'invetex'),
			'text_long'			=> esc_html__('Too long message text', 'invetex'),
			'send_complete'		=> esc_html__("Send message complete!", 'invetex'),
			'send_error'		=> esc_html__('Transmit failed!', 'invetex'),
			'not_agree'			=> esc_html__('Please, check \'I agree with Terms and Conditions\'', 'invetex'),
			'login_empty'		=> esc_html__('The Login field can\'t be empty', 'invetex'),
			'login_long'		=> esc_html__('Too long login field', 'invetex'),
			'login_success'		=> esc_html__('Login success! The page will be reloaded in 3 sec.', 'invetex'),
			'login_failed'		=> esc_html__('Login failed!', 'invetex'),
			'password_empty'	=> esc_html__('The password can\'t be empty and shorter then 4 characters', 'invetex'),
			'password_long'		=> esc_html__('Too long password', 'invetex'),
			'password_not_equal'	=> esc_html__('The passwords in both fields are not equal', 'invetex'),
			'registration_success'	=> esc_html__('Registration success! Please log in!', 'invetex'),
			'registration_failed'	=> esc_html__('Registration failed!', 'invetex'),
			'geocode_error'			=> esc_html__('Geocode was not successful for the following reason:', 'invetex'),
			'googlemap_not_avail'	=> esc_html__('Google map API not available!', 'invetex'),
			'editor_save_success'	=> esc_html__("Post content saved!", 'invetex'),
			'editor_save_error'		=> esc_html__("Error saving post data!", 'invetex'),
			'editor_delete_post'	=> esc_html__("You really want to delete the current post?", 'invetex'),
			'editor_delete_post_header'	=> esc_html__("Delete post", 'invetex'),
			'editor_delete_success'	=> esc_html__("Post deleted!", 'invetex'),
			'editor_delete_error'	=> esc_html__("Error deleting post!", 'invetex'),
			'editor_caption_cancel'	=> esc_html__('Cancel', 'invetex'),
			'editor_caption_close'	=> esc_html__('Close', 'invetex')
			);
		return $vars;
	}
}
?>
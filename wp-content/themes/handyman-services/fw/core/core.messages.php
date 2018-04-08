<?php
/**
 * Handyman Services Framework: messages subsystem
 *
 * @package	handyman_services
 * @since	handyman_services 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('handyman_services_messages_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_messages_theme_setup' );
	function handyman_services_messages_theme_setup() {
		// Core messages strings
		add_filter('handyman_services_filter_localize_script', 'handyman_services_messages_localize_script');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('handyman_services_get_error_msg')) {
	function handyman_services_get_error_msg() {
		return handyman_services_storage_get('error_msg');
	}
}

if (!function_exists('handyman_services_set_error_msg')) {
	function handyman_services_set_error_msg($msg) {
		$msg2 = handyman_services_get_error_msg();
		handyman_services_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('handyman_services_get_success_msg')) {
	function handyman_services_get_success_msg() {
		return handyman_services_storage_get('success_msg');
	}
}

if (!function_exists('handyman_services_set_success_msg')) {
	function handyman_services_set_success_msg($msg) {
		$msg2 = handyman_services_get_success_msg();
		handyman_services_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('handyman_services_get_notice_msg')) {
	function handyman_services_get_notice_msg() {
		return handyman_services_storage_get('notice_msg');
	}
}

if (!function_exists('handyman_services_set_notice_msg')) {
	function handyman_services_set_notice_msg($msg) {
		$msg2 = handyman_services_get_notice_msg();
		handyman_services_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('handyman_services_set_system_message')) {
	function handyman_services_set_system_message($msg, $status='info', $hdr='') {
		update_option(handyman_services_storage_get('options_prefix') . '_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('handyman_services_get_system_message')) {
	function handyman_services_get_system_message($del=false) {
		$msg = get_option(handyman_services_storage_get('options_prefix') . '_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			handyman_services_del_system_message();
		return $msg;
	}
}

if (!function_exists('handyman_services_del_system_message')) {
	function handyman_services_del_system_message() {
		delete_option(handyman_services_storage_get('options_prefix') . '_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('handyman_services_messages_localize_script')) {
	//add_filter('handyman_services_filter_localize_script', 'handyman_services_messages_localize_script');
	function handyman_services_messages_localize_script($vars) {
		$vars['strings'] = array(
			'ajax_error'		=> esc_html__('Invalid server answer', 'handyman-services'),
			'bookmark_add'		=> esc_html__('Add the bookmark', 'handyman-services'),
            'bookmark_added'	=> esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'handyman-services'),
            'bookmark_del'		=> esc_html__('Delete this bookmark', 'handyman-services'),
            'bookmark_title'	=> esc_html__('Enter bookmark title', 'handyman-services'),
            'bookmark_exists'	=> esc_html__('Current page already exists in the bookmarks list', 'handyman-services'),
			'search_error'		=> esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'handyman-services'),
			'email_confirm'		=> esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'handyman-services'),
			'reviews_vote'		=> esc_html__('Thanks for your vote! New average rating is:', 'handyman-services'),
			'reviews_error'		=> esc_html__('Error saving your vote! Please, try again later.', 'handyman-services'),
			'error_like'		=> esc_html__('Error saving your like! Please, try again later.', 'handyman-services'),
			'error_global'		=> esc_html__('Global error text', 'handyman-services'),
			'name_empty'		=> esc_html__('The name can\'t be empty', 'handyman-services'),
			'name_long'			=> esc_html__('Too long name', 'handyman-services'),
			'email_empty'		=> esc_html__('Too short (or empty) email address', 'handyman-services'),
			'email_long'		=> esc_html__('Too long email address', 'handyman-services'),
			'email_not_valid'	=> esc_html__('Invalid email address', 'handyman-services'),
			'subject_empty'		=> esc_html__('The subject can\'t be empty', 'handyman-services'),
			'subject_long'		=> esc_html__('Too long subject', 'handyman-services'),
			'text_empty'		=> esc_html__('The message text can\'t be empty', 'handyman-services'),
			'text_long'			=> esc_html__('Too long message text', 'handyman-services'),
			'send_complete'		=> esc_html__("Send message complete!", 'handyman-services'),
			'send_error'		=> esc_html__('Transmit failed!', 'handyman-services'),
			'not_agree'			=> esc_html__('Please, check \'I agree with Terms and Conditions\'', 'handyman-services'),
			'login_empty'		=> esc_html__('The Login field can\'t be empty', 'handyman-services'),
			'login_long'		=> esc_html__('Too long login field', 'handyman-services'),
			'login_success'		=> esc_html__('Login success! The page will be reloaded in 3 sec.', 'handyman-services'),
			'login_failed'		=> esc_html__('Login failed!', 'handyman-services'),
			'password_empty'	=> esc_html__('The password can\'t be empty and shorter then 4 characters', 'handyman-services'),
			'password_long'		=> esc_html__('Too long password', 'handyman-services'),
			'password_not_equal'	=> esc_html__('The passwords in both fields are not equal', 'handyman-services'),
			'registration_success'	=> esc_html__('Registration success! Please log in!', 'handyman-services'),
			'registration_failed'	=> esc_html__('Registration failed!', 'handyman-services'),
			'geocode_error'			=> esc_html__('Geocode was not successful for the following reason:', 'handyman-services'),
			'googlemap_not_avail'	=> esc_html__('Google map API not available!', 'handyman-services'),
			'editor_save_success'	=> esc_html__("Post content saved!", 'handyman-services'),
			'editor_save_error'		=> esc_html__("Error saving post data!", 'handyman-services'),
			'editor_delete_post'	=> esc_html__("You really want to delete the current post?", 'handyman-services'),
			'editor_delete_post_header'	=> esc_html__("Delete post", 'handyman-services'),
			'editor_delete_success'	=> esc_html__("Post deleted!", 'handyman-services'),
			'editor_delete_error'	=> esc_html__("Error deleting post!", 'handyman-services'),
			'editor_caption_cancel'	=> esc_html__('Cancel', 'handyman-services'),
			'editor_caption_close'	=> esc_html__('Close', 'handyman-services')
			);
		return $vars;
	}
}
?>
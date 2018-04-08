<?php
/**
 * Handyman Services Framework: Theme options custom fields
 *
 * @package	handyman_services
 * @since	handyman_services 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'handyman_services_options_custom_theme_setup' ) ) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_options_custom_theme_setup' );
	function handyman_services_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'handyman_services_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'handyman_services_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'handyman_services_options_custom_load_scripts');
	function handyman_services_options_custom_load_scripts() {
		handyman_services_enqueue_script( 'handyman_services-options-custom-script',	handyman_services_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'handyman_services_show_custom_field' ) ) {
	function handyman_services_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(handyman_services_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager handyman_services_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'handyman-services') : esc_html__( 'Choose Image', 'handyman-services')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'handyman-services') : esc_html__( 'Choose Image', 'handyman-services')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'handyman-services') : esc_html__( 'Choose Image', 'handyman-services')) . '</a>';
				break;
		}
		return apply_filters('handyman_services_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>
<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_hide_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_hide_theme_setup' );
	function handyman_services_sc_hide_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('handyman_services_sc_hide')) {	
	function handyman_services_sc_hide($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		if (!empty($selector)) {
			handyman_services_storage_concat('js_code', '
				'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
				'.($delay>0 ? '},'.($delay).');' : '').'
			');
		}
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	handyman_services_require_shortcode('trx_hide', 'handyman_services_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_hide_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_hide_reg_shortcodes');
	function handyman_services_sc_hide_reg_shortcodes() {
	
		handyman_services_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'handyman-services'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'handyman-services') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'handyman-services'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'handyman-services'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'handyman-services') ),
					"value" => "yes",
					"size" => "small",
					"options" => handyman_services_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>
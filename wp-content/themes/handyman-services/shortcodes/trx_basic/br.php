<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_br_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_br_theme_setup' );
	function handyman_services_sc_br_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_br_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('handyman_services_sc_br')) {	
	function handyman_services_sc_br($atts, $content = null) {
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	handyman_services_require_shortcode("trx_br", "handyman_services_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_br_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_br_reg_shortcodes');
	function handyman_services_sc_br_reg_shortcodes() {
	
		handyman_services_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'handyman-services'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'handyman-services') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'handyman-services'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'handyman-services') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'handyman-services'),
						'left' => esc_html__('Left', 'handyman-services'),
						'right' => esc_html__('Right', 'handyman-services'),
						'both' => esc_html__('Both', 'handyman-services')
					)
				)
			)
		));
	}
}
?>
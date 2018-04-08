<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_tooltip_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_tooltip_theme_setup' );
	function handyman_services_sc_tooltip_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('handyman_services_sc_tooltip')) {	
	function handyman_services_sc_tooltip($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	handyman_services_require_shortcode('trx_tooltip', 'handyman_services_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_tooltip_reg_shortcodes');
	function handyman_services_sc_tooltip_reg_shortcodes() {
	
		handyman_services_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'handyman-services'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'handyman-services') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'handyman-services'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'handyman-services'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'handyman-services') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => handyman_services_get_sc_param('id'),
				"class" => handyman_services_get_sc_param('class'),
				"css" => handyman_services_get_sc_param('css')
			)
		));
	}
}
?>
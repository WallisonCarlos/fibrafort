<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_highlight_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_highlight_theme_setup' );
	function handyman_services_sc_highlight_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_highlight_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('handyman_services_sc_highlight')) {	
	function handyman_services_sc_highlight($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(handyman_services_prepare_css_value($font_size)) . '; line-height: 1.65em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	handyman_services_require_shortcode('trx_highlight', 'handyman_services_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_highlight_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_highlight_reg_shortcodes');
	function handyman_services_sc_highlight_reg_shortcodes() {
	
		handyman_services_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'handyman-services'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'handyman-services') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'handyman-services'),
					"desc" => wp_kses_data( __("Highlight type", 'handyman-services') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'handyman-services'),
						1 => esc_html__('Type 1', 'handyman-services'),
						2 => esc_html__('Type 2', 'handyman-services'),
						3 => esc_html__('Type 3', 'handyman-services')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'handyman-services'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'handyman-services') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'handyman-services'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'handyman-services') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'handyman-services'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'handyman-services'),
					"desc" => wp_kses_data( __("Content for highlight", 'handyman-services') ),
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


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_highlight_reg_shortcodes_vc');
	function handyman_services_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'handyman-services'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'handyman-services'),
					"description" => wp_kses_data( __("Highlight type", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'handyman-services') => 0,
							esc_html__('Type 1', 'handyman-services') => 1,
							esc_html__('Type 2', 'handyman-services') => 2,
							esc_html__('Type 3', 'handyman-services') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'handyman-services'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'handyman-services'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'handyman-services'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'handyman-services'),
					"description" => wp_kses_data( __("Content for highlight", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				handyman_services_get_vc_param('id'),
				handyman_services_get_vc_param('class'),
				handyman_services_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}
	}
}
?>
<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_icon_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_icon_theme_setup' );
	function handyman_services_sc_icon_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_icon_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('handyman_services_sc_icon')) {	
	function handyman_services_sc_icon($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !handyman_services_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(handyman_services_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || handyman_services_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !handyman_services_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	handyman_services_require_shortcode('trx_icon', 'handyman_services_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_icon_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_icon_reg_shortcodes');
	function handyman_services_sc_icon_reg_shortcodes() {
	
		handyman_services_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'handyman-services'),
			"desc" => wp_kses_data( __("Insert icon", 'handyman-services') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'handyman-services'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'handyman-services') ),
					"value" => "",
					"type" => "icons",
					"options" => handyman_services_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'handyman-services'),
					"desc" => wp_kses_data( __("Icon's color", 'handyman-services') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'handyman-services'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'handyman-services') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'handyman-services'),
						'round' => esc_html__('Round', 'handyman-services'),
						'square' => esc_html__('Square', 'handyman-services')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'handyman-services'),
					"desc" => wp_kses_data( __("Icon's background color", 'handyman-services') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'handyman-services'),
					"desc" => wp_kses_data( __("Icon's font size", 'handyman-services') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'handyman-services'),
					"desc" => wp_kses_data( __("Icon font weight", 'handyman-services') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'handyman-services'),
						'300' => esc_html__('Light (300)', 'handyman-services'),
						'400' => esc_html__('Normal (400)', 'handyman-services'),
						'700' => esc_html__('Bold (700)', 'handyman-services')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'handyman-services'),
					"desc" => wp_kses_data( __("Icon text alignment", 'handyman-services') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => handyman_services_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'handyman-services'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"top" => handyman_services_get_sc_param('top'),
				"bottom" => handyman_services_get_sc_param('bottom'),
				"left" => handyman_services_get_sc_param('left'),
				"right" => handyman_services_get_sc_param('right'),
				"id" => handyman_services_get_sc_param('id'),
				"class" => handyman_services_get_sc_param('class'),
				"css" => handyman_services_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_icon_reg_shortcodes_vc');
	function handyman_services_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'handyman-services'),
			"description" => wp_kses_data( __("Insert the icon", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'handyman-services'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => handyman_services_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'handyman-services'),
					"description" => wp_kses_data( __("Icon's color", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'handyman-services'),
					"description" => wp_kses_data( __("Background color for the icon", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'handyman-services'),
					"description" => wp_kses_data( __("Shape of the icon background", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'handyman-services') => 'none',
						esc_html__('Round', 'handyman-services') => 'round',
						esc_html__('Square', 'handyman-services') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'handyman-services'),
					"description" => wp_kses_data( __("Icon's font size", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'handyman-services'),
					"description" => wp_kses_data( __("Icon's font weight", 'handyman-services') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'handyman-services') => 'inherit',
						esc_html__('Thin (100)', 'handyman-services') => '100',
						esc_html__('Light (300)', 'handyman-services') => '300',
						esc_html__('Normal (400)', 'handyman-services') => '400',
						esc_html__('Bold (700)', 'handyman-services') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'handyman-services'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'handyman-services'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				handyman_services_get_vc_param('id'),
				handyman_services_get_vc_param('class'),
				handyman_services_get_vc_param('css'),
				handyman_services_get_vc_param('margin_top'),
				handyman_services_get_vc_param('margin_bottom'),
				handyman_services_get_vc_param('margin_left'),
				handyman_services_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}
	}
}
?>
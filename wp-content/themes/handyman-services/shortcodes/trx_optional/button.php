<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_button_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_button_theme_setup' );
	function handyman_services_sc_button_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_button_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('handyman_services_sc_button')) {	
	function handyman_services_sc_button($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= handyman_services_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (handyman_services_param_is_on($popup)) handyman_services_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (handyman_services_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	handyman_services_require_shortcode('trx_button', 'handyman_services_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_button_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_button_reg_shortcodes');
	function handyman_services_sc_button_reg_shortcodes() {
	
		handyman_services_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'handyman-services'),
			"desc" => wp_kses_data( __("Button with link", 'handyman-services') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'handyman-services'),
					"desc" => wp_kses_data( __("Button caption", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'handyman-services'),
					"desc" => wp_kses_data( __("Select button's shape", 'handyman-services') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'handyman-services'),
						'round' => esc_html__('Round', 'handyman-services')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'handyman-services'),
					"desc" => wp_kses_data( __("Select button's style", 'handyman-services') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'handyman-services'),
						'border' => esc_html__('Border', 'handyman-services')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'handyman-services'),
					"desc" => wp_kses_data( __("Select button's size", 'handyman-services') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'handyman-services'),
						'medium' => esc_html__('Medium', 'handyman-services'),
						'large' => esc_html__('Large', 'handyman-services')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'handyman-services'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'handyman-services') ),
					"value" => "",
					"type" => "icons",
					"options" => handyman_services_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'handyman-services'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'handyman-services') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'handyman-services'),
					"desc" => wp_kses_data( __("Any color for button's background", 'handyman-services') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'handyman-services'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'handyman-services') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => handyman_services_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'handyman-services'),
					"desc" => wp_kses_data( __("URL for link on button click", 'handyman-services') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'handyman-services'),
					"desc" => wp_kses_data( __("Target for link on button click", 'handyman-services') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'handyman-services'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'handyman-services') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => handyman_services_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'handyman-services'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'handyman-services') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"width" => handyman_services_shortcodes_width(),
				"height" => handyman_services_shortcodes_height(),
				"top" => handyman_services_get_sc_param('top'),
				"bottom" => handyman_services_get_sc_param('bottom'),
				"left" => handyman_services_get_sc_param('left'),
				"right" => handyman_services_get_sc_param('right'),
				"id" => handyman_services_get_sc_param('id'),
				"class" => handyman_services_get_sc_param('class'),
				"animation" => handyman_services_get_sc_param('animation'),
				"css" => handyman_services_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_button_reg_shortcodes_vc');
	function handyman_services_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'handyman-services'),
			"description" => wp_kses_data( __("Button with link", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'handyman-services'),
					"description" => wp_kses_data( __("Button caption", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'handyman-services'),
					"description" => wp_kses_data( __("Select button's shape", 'handyman-services') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'handyman-services') => 'square',
						esc_html__('Round', 'handyman-services') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'handyman-services'),
					"description" => wp_kses_data( __("Select button's style", 'handyman-services') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'handyman-services') => 'filled',
						esc_html__('Border', 'handyman-services') => 'border'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'handyman-services'),
					"description" => wp_kses_data( __("Select button's size", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'handyman-services') => 'small',
						esc_html__('Medium', 'handyman-services') => 'medium',
						esc_html__('Large', 'handyman-services') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'handyman-services'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'handyman-services') ),
					"class" => "",
					"value" => handyman_services_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'handyman-services'),
					"description" => wp_kses_data( __("Any color for button's caption", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'handyman-services'),
					"description" => wp_kses_data( __("Any color for button's background", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'handyman-services'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'handyman-services') ),
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'handyman-services'),
					"description" => wp_kses_data( __("URL for the link on button click", 'handyman-services') ),
					"class" => "",
					"group" => esc_html__('Link', 'handyman-services'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'handyman-services'),
					"description" => wp_kses_data( __("Target for the link on button click", 'handyman-services') ),
					"class" => "",
					"group" => esc_html__('Link', 'handyman-services'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'handyman-services'),
					"description" => wp_kses_data( __("Open link target in popup window", 'handyman-services') ),
					"class" => "",
					"group" => esc_html__('Link', 'handyman-services'),
					"value" => array(esc_html__('Open in popup', 'handyman-services') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'handyman-services'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'handyman-services') ),
					"class" => "",
					"group" => esc_html__('Link', 'handyman-services'),
					"value" => "",
					"type" => "textfield"
				),
				handyman_services_get_vc_param('id'),
				handyman_services_get_vc_param('class'),
				handyman_services_get_vc_param('animation'),
				handyman_services_get_vc_param('css'),
				handyman_services_vc_width(),
				handyman_services_vc_height(),
				handyman_services_get_vc_param('margin_top'),
				handyman_services_get_vc_param('margin_bottom'),
				handyman_services_get_vc_param('margin_left'),
				handyman_services_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}
	}
}
?>
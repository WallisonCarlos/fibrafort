<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_list_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_list_theme_setup' );
	function handyman_services_sc_list_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_list_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('handyman_services_sc_list')) {	
	function handyman_services_sc_list($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		handyman_services_storage_set('sc_list_data', array(
			'counter' => 0,
            'icon' => empty($icon) || handyman_services_param_is_inherit($icon) ? "icon-right" : $icon,
            'icon_color' => $icon_color,
            'style' => $style
            )
        );
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	handyman_services_require_shortcode('trx_list', 'handyman_services_sc_list');
}


if (!function_exists('handyman_services_sc_list_item')) {	
	function handyman_services_sc_list_item($atts, $content=null) {
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		handyman_services_storage_inc_array('sc_list_data', 'counter');
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || handyman_services_param_is_inherit($icon)) $icon = handyman_services_storage_get_array('sc_list_data', 'icon');
		if (trim($color) == '' || handyman_services_param_is_inherit($icon_color)) $icon_color = handyman_services_storage_get_array('sc_list_data', 'icon_color');
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. (handyman_services_storage_get_array('sc_list_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
			. (handyman_services_storage_get_array('sc_list_data', 'counter') == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. (handyman_services_storage_get_array('sc_list_data', 'style')=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. trim($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	handyman_services_require_shortcode('trx_list_item', 'handyman_services_sc_list_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_list_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_list_reg_shortcodes');
	function handyman_services_sc_list_reg_shortcodes() {
	
		handyman_services_sc_map("trx_list", array(
			"title" => esc_html__("List", 'handyman-services'),
			"desc" => wp_kses_data( __("List items with specific bullets", 'handyman-services') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", 'handyman-services'),
					"desc" => wp_kses_data( __("Bullet's style for each list item", 'handyman-services') ),
					"value" => "ul",
					"type" => "checklist",
					"options" => handyman_services_get_sc_param('list_styles')
				), 
				"color" => array(
					"title" => esc_html__("Color", 'handyman-services'),
					"desc" => wp_kses_data( __("List items color", 'handyman-services') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('List icon',  'handyman-services'),
					"desc" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)",  'handyman-services') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => handyman_services_get_sc_param('icons')
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", 'handyman-services'),
					"desc" => wp_kses_data( __("List icons color", 'handyman-services') ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"top" => handyman_services_get_sc_param('top'),
				"bottom" => handyman_services_get_sc_param('bottom'),
				"left" => handyman_services_get_sc_param('left'),
				"right" => handyman_services_get_sc_param('right'),
				"id" => handyman_services_get_sc_param('id'),
				"class" => handyman_services_get_sc_param('class'),
				"animation" => handyman_services_get_sc_param('animation'),
				"css" => handyman_services_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_list_item",
				"title" => esc_html__("Item", 'handyman-services'),
				"desc" => wp_kses_data( __("List item with specific bullet", 'handyman-services') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", 'handyman-services'),
						"desc" => wp_kses_data( __("Current list item content", 'handyman-services') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", 'handyman-services'),
						"desc" => wp_kses_data( __("Current list item title (show it as tooltip)", 'handyman-services') ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", 'handyman-services'),
						"desc" => wp_kses_data( __("Text color for this item", 'handyman-services') ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'handyman-services'),
						"desc" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'handyman-services') ),
						"value" => "",
						"type" => "icons",
						"options" => handyman_services_get_sc_param('icons')
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", 'handyman-services'),
						"desc" => wp_kses_data( __("Icon color for this item", 'handyman-services') ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", 'handyman-services'),
						"desc" => wp_kses_data( __("Link URL for the current list item", 'handyman-services') ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", 'handyman-services'),
						"desc" => wp_kses_data( __("Link target for the current list item", 'handyman-services') ),
						"value" => "",
						"type" => "text"
					),
					"id" => handyman_services_get_sc_param('id'),
					"class" => handyman_services_get_sc_param('class'),
					"css" => handyman_services_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_list_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_list_reg_shortcodes_vc');
	function handyman_services_sc_list_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", 'handyman-services'),
			"description" => wp_kses_data( __("List items with specific bullets", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", 'handyman-services'),
					"description" => wp_kses_data( __("Bullet's style for each list item", 'handyman-services') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(handyman_services_get_sc_param('list_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'handyman-services'),
					"description" => wp_kses_data( __("List items color", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", 'handyman-services'),
					"description" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => handyman_services_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'handyman-services'),
					"description" => wp_kses_data( __("List icons color", 'handyman-services') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => "",
					"type" => "colorpicker"
				),
				handyman_services_get_vc_param('id'),
				handyman_services_get_vc_param('class'),
				handyman_services_get_vc_param('animation'),
				handyman_services_get_vc_param('css'),
				handyman_services_get_vc_param('margin_top'),
				handyman_services_get_vc_param('margin_bottom'),
				handyman_services_get_vc_param('margin_left'),
				handyman_services_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", 'handyman-services'),
			"description" => wp_kses_data( __("List item with specific bullet", 'handyman-services') ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", 'handyman-services'),
					"description" => wp_kses_data( __("Title for the current list item (show it as tooltip)", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'handyman-services'),
					"description" => wp_kses_data( __("Link URL for the current list item", 'handyman-services') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'handyman-services'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'handyman-services'),
					"description" => wp_kses_data( __("Link target for the current list item", 'handyman-services') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'handyman-services'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'handyman-services'),
					"description" => wp_kses_data( __("Text color for this item", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", 'handyman-services'),
					"description" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => handyman_services_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'handyman-services'),
					"description" => wp_kses_data( __("Icon color for this item", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				handyman_services_get_vc_param('id'),
				handyman_services_get_vc_param('class'),
				handyman_services_get_vc_param('css')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends HANDYMAN_SERVICES_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends HANDYMAN_SERVICES_VC_ShortCodeContainer {}
	}
}
?>
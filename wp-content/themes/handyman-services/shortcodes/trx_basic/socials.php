<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_socials_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_socials_theme_setup' );
	function handyman_services_sc_socials_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_socials_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('handyman_services_sc_socials')) {	
	function handyman_services_sc_socials($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => handyman_services_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		handyman_services_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? handyman_services_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) handyman_services_storage_set_array('sc_social_data', 'icons', $list);
		} else if (handyman_services_param_is_off($custom))
			$content = do_shortcode($content);
		if (handyman_services_storage_get_array('sc_social_data', 'icons')===false) handyman_services_storage_set_array('sc_social_data', 'icons', handyman_services_get_custom_option('social_icons'));
		$output = handyman_services_prepare_socials(handyman_services_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	handyman_services_require_shortcode('trx_socials', 'handyman_services_sc_socials');
}


if (!function_exists('handyman_services_sc_social_item')) {	
	function handyman_services_sc_social_item($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = handyman_services_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(handyman_services_get_socials_dir($name.'.png')))
					$icon = handyman_services_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (handyman_services_storage_get_array('sc_social_data', 'icons')===false) handyman_services_storage_set_array('sc_social_data', 'icons', array());
			handyman_services_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	handyman_services_require_shortcode('trx_social_item', 'handyman_services_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_socials_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_socials_reg_shortcodes');
	function handyman_services_sc_socials_reg_shortcodes() {
	
		handyman_services_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'handyman-services'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'handyman-services') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'handyman-services'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'handyman-services') ),
					"value" => handyman_services_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'handyman-services'),
						'images' => esc_html__('Images', 'handyman-services')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'handyman-services'),
					"desc" => wp_kses_data( __("Size of the icons", 'handyman-services') ),
					"value" => "small",
					"options" => handyman_services_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'handyman-services'),
					"desc" => wp_kses_data( __("Shape of the icons", 'handyman-services') ),
					"value" => "square",
					"options" => handyman_services_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'handyman-services'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'handyman-services') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'handyman-services'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'handyman-services') ),
					"divider" => true,
					"value" => "no",
					"options" => handyman_services_get_sc_param('yes_no'),
					"type" => "switch"
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'handyman-services'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'handyman-services') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'handyman-services'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'handyman-services') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'handyman-services'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'handyman-services') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'handyman-services'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'handyman-services') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_socials_reg_shortcodes_vc');
	function handyman_services_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'handyman-services'),
			"description" => wp_kses_data( __("Custom social icons", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'handyman-services'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'handyman-services') ),
					"class" => "",
					"std" => handyman_services_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'handyman-services') => 'icons',
						esc_html__('Images', 'handyman-services') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'handyman-services'),
					"description" => wp_kses_data( __("Size of the icons", 'handyman-services') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(handyman_services_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'handyman-services'),
					"description" => wp_kses_data( __("Shape of the icons", 'handyman-services') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(handyman_services_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'handyman-services'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'handyman-services'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'handyman-services') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'handyman-services') => 'yes'),
					"type" => "checkbox"
				),
				handyman_services_get_vc_param('id'),
				handyman_services_get_vc_param('class'),
				handyman_services_get_vc_param('animation'),
				handyman_services_get_vc_param('css'),
				handyman_services_get_vc_param('margin_top'),
				handyman_services_get_vc_param('margin_bottom'),
				handyman_services_get_vc_param('margin_left'),
				handyman_services_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'handyman-services'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'handyman-services') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'handyman-services'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'handyman-services'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'handyman-services'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends HANDYMAN_SERVICES_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}
	}
}
?>
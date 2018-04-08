<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_googlemap_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_googlemap_theme_setup' );
	function handyman_services_sc_googlemap_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_googlemap_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('handyman_services_sc_googlemap')) {	
	function handyman_services_sc_googlemap($atts, $content = null) {
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= handyman_services_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = handyman_services_get_custom_option('googlemap_style');
		$api_key = handyman_services_get_theme_option('api_google');
		handyman_services_enqueue_script( 'googlemap', handyman_services_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
		handyman_services_enqueue_script( 'handyman_services-googlemap-script', handyman_services_get_file_url('js/core.googlemap.js'), array(), null, true );
		handyman_services_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = handyman_services_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => handyman_services_get_custom_option('googlemap_title'),
				'description' => handyman_services_strmacros(handyman_services_get_custom_option('googlemap_description')),
				'latlng' => handyman_services_get_custom_option('googlemap_latlng'),
				'address' => handyman_services_get_custom_option('googlemap_address'),
				'point' => handyman_services_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !handyman_services_param_is_off($scheme) && !handyman_services_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(handyman_services_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	handyman_services_require_shortcode("trx_googlemap", "handyman_services_sc_googlemap");
}


if (!function_exists('handyman_services_sc_googlemap_marker')) {	
	function handyman_services_sc_googlemap_marker($atts, $content = null) {
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		handyman_services_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : handyman_services_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	handyman_services_require_shortcode("trx_googlemap_marker", "handyman_services_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_googlemap_reg_shortcodes');
	function handyman_services_sc_googlemap_reg_shortcodes() {
	
		handyman_services_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'handyman-services'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'handyman-services') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'handyman-services'),
					"desc" => wp_kses_data( __("Map zoom factor", 'handyman-services') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'handyman-services'),
					"desc" => wp_kses_data( __("Select map style", 'handyman-services') ),
					"value" => "default",
					"type" => "checklist",
					"options" => handyman_services_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'handyman-services'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'handyman-services') ),
					"value" => "",
					"type" => "checklist",
					"options" => handyman_services_get_sc_param('schemes')
				),
				"width" => handyman_services_shortcodes_width('100%'),
				"height" => handyman_services_shortcodes_height(240),
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
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'handyman-services'),
				"desc" => wp_kses_data( __("Google map marker", 'handyman-services') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'handyman-services'),
						"desc" => wp_kses_data( __("Address of this marker", 'handyman-services') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'handyman-services'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'handyman-services') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'handyman-services'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'handyman-services') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'handyman-services'),
						"desc" => wp_kses_data( __("Title for this marker", 'handyman-services') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'handyman-services'),
						"desc" => wp_kses_data( __("Description for this marker", 'handyman-services') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => handyman_services_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_googlemap_reg_shortcodes_vc');
	function handyman_services_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'handyman-services'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'handyman-services'),
					"description" => wp_kses_data( __("Map zoom factor", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'handyman-services'),
					"description" => wp_kses_data( __("Map custom style", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'handyman-services'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'handyman-services') ),
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				handyman_services_get_vc_param('id'),
				handyman_services_get_vc_param('class'),
				handyman_services_get_vc_param('animation'),
				handyman_services_get_vc_param('css'),
				handyman_services_vc_width('100%'),
				handyman_services_vc_height(240),
				handyman_services_get_vc_param('margin_top'),
				handyman_services_get_vc_param('margin_bottom'),
				handyman_services_get_vc_param('margin_left'),
				handyman_services_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'handyman-services'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'handyman-services') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'handyman-services'),
					"description" => wp_kses_data( __("Address of this marker", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'handyman-services'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'handyman-services'),
					"description" => wp_kses_data( __("Title for this marker", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'handyman-services'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				handyman_services_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends HANDYMAN_SERVICES_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends HANDYMAN_SERVICES_VC_ShortCodeCollection {}
	}
}
?>
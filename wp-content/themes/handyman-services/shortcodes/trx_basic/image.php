<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_image_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_image_theme_setup' );
	function handyman_services_sc_image_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_image_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_image_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('handyman_services_sc_image')) {	
	function handyman_services_sc_image($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"url" => "",
			"icon" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= handyman_services_get_css_dimensions_from_values($width, $height);
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = handyman_services_get_resized_image_url($src, $w, $h);
		}
		if (trim($link)) handyman_services_enqueue_popup();
		$output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (trim($link) ? '<a href="'.esc_url($link).'">' : '')
				. '<img src="'.esc_url($src).'" alt="" />'
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
			. '</figure>');
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	handyman_services_require_shortcode('trx_image', 'handyman_services_sc_image');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_image_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_image_reg_shortcodes');
	function handyman_services_sc_image_reg_shortcodes() {
	
		handyman_services_sc_map("trx_image", array(
			"title" => esc_html__("Image", 'handyman-services'),
			"desc" => wp_kses_data( __("Insert image into your post (page)", 'handyman-services') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for image file", 'handyman-services'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site", 'handyman-services') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"title" => array(
					"title" => esc_html__("Title", 'handyman-services'),
					"desc" => wp_kses_data( __("Image title (if need)", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon before title",  'handyman-services'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'handyman-services') ),
					"value" => "",
					"type" => "icons",
					"options" => handyman_services_get_sc_param('icons')
				),
				"align" => array(
					"title" => esc_html__("Float image", 'handyman-services'),
					"desc" => wp_kses_data( __("Float image to left or right side", 'handyman-services') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => handyman_services_get_sc_param('float')
				), 
				"shape" => array(
					"title" => esc_html__("Image Shape", 'handyman-services'),
					"desc" => wp_kses_data( __("Shape of the image: square (rectangle) or round", 'handyman-services') ),
					"value" => "square",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"square" => esc_html__('Square', 'handyman-services'),
						"round" => esc_html__('Round', 'handyman-services')
					)
				), 
				"link" => array(
					"title" => esc_html__("Link", 'handyman-services'),
					"desc" => wp_kses_data( __("The link URL from the image", 'handyman-services') ),
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
if ( !function_exists( 'handyman_services_sc_image_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_image_reg_shortcodes_vc');
	function handyman_services_sc_image_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_image",
			"name" => esc_html__("Image", 'handyman-services'),
			"description" => wp_kses_data( __("Insert image", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_image',
			"class" => "trx_sc_single trx_sc_image",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("Select image", 'handyman-services'),
					"description" => wp_kses_data( __("Select image from library", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Image alignment", 'handyman-services'),
					"description" => wp_kses_data( __("Align image to left or right side", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Image shape", 'handyman-services'),
					"description" => wp_kses_data( __("Shape of the image: square or round", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Square', 'handyman-services') => 'square',
						esc_html__('Round', 'handyman-services') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'handyman-services'),
					"description" => wp_kses_data( __("Image's title", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title's icon", 'handyman-services'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'handyman-services') ),
					"class" => "",
					"value" => handyman_services_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'handyman-services'),
					"description" => wp_kses_data( __("The link URL from the image", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Image extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}
	}
}
?>
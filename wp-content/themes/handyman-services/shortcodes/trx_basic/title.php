<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_title_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_title_theme_setup' );
	function handyman_services_sc_title_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_title_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('handyman_services_sc_title')) {	
	function handyman_services_sc_title($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= handyman_services_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !handyman_services_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !handyman_services_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(handyman_services_strpos($image, 'http')===0 ? $image : handyman_services_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !handyman_services_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	handyman_services_require_shortcode('trx_title', 'handyman_services_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_title_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_title_reg_shortcodes');
	function handyman_services_sc_title_reg_shortcodes() {
	
		handyman_services_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'handyman-services'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'handyman-services') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'handyman-services'),
					"desc" => wp_kses_data( __("Title content", 'handyman-services') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'handyman-services'),
					"desc" => wp_kses_data( __("Title type (header level)", 'handyman-services') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'handyman-services'),
						'2' => esc_html__('Header 2', 'handyman-services'),
						'3' => esc_html__('Header 3', 'handyman-services'),
						'4' => esc_html__('Header 4', 'handyman-services'),
						'5' => esc_html__('Header 5', 'handyman-services'),
						'6' => esc_html__('Header 6', 'handyman-services'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'handyman-services'),
					"desc" => wp_kses_data( __("Title style", 'handyman-services') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'handyman-services'),
						'underline' => esc_html__('Underline', 'handyman-services'),
						'divider' => esc_html__('Divider', 'handyman-services'),
						'iconed' => esc_html__('With icon (image)', 'handyman-services')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'handyman-services'),
					"desc" => wp_kses_data( __("Title text alignment", 'handyman-services') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => handyman_services_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'handyman-services'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'handyman-services'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'handyman-services') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'handyman-services'),
						'100' => esc_html__('Thin (100)', 'handyman-services'),
						'300' => esc_html__('Light (300)', 'handyman-services'),
						'400' => esc_html__('Normal (400)', 'handyman-services'),
						'600' => esc_html__('Semibold (600)', 'handyman-services'),
						'700' => esc_html__('Bold (700)', 'handyman-services'),
						'900' => esc_html__('Black (900)', 'handyman-services')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'handyman-services'),
					"desc" => wp_kses_data( __("Select color for the title", 'handyman-services') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'handyman-services'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'handyman-services') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => handyman_services_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'handyman-services'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'handyman-services') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => handyman_services_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'handyman-services'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'handyman-services') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'handyman-services'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'handyman-services') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'handyman-services'),
						'medium' => esc_html__('Medium', 'handyman-services'),
						'large' => esc_html__('Large', 'handyman-services')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'handyman-services'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'handyman-services') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'handyman-services'),
						'left' => esc_html__('Left', 'handyman-services')
					)
				),
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
if ( !function_exists( 'handyman_services_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_title_reg_shortcodes_vc');
	function handyman_services_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'handyman-services'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'handyman-services'),
					"description" => wp_kses_data( __("Title content", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'handyman-services'),
					"description" => wp_kses_data( __("Title type (header level)", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'handyman-services') => '1',
						esc_html__('Header 2', 'handyman-services') => '2',
						esc_html__('Header 3', 'handyman-services') => '3',
						esc_html__('Header 4', 'handyman-services') => '4',
						esc_html__('Header 5', 'handyman-services') => '5',
						esc_html__('Header 6', 'handyman-services') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'handyman-services'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'handyman-services') => 'regular',
						esc_html__('Underline', 'handyman-services') => 'underline',
						esc_html__('Divider', 'handyman-services') => 'divider',
						esc_html__('With icon (image)', 'handyman-services') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'handyman-services'),
					"description" => wp_kses_data( __("Title text alignment", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'handyman-services'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'handyman-services'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'handyman-services') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'handyman-services') => 'inherit',
						esc_html__('Thin (100)', 'handyman-services') => '100',
						esc_html__('Light (300)', 'handyman-services') => '300',
						esc_html__('Normal (400)', 'handyman-services') => '400',
						esc_html__('Semibold (600)', 'handyman-services') => '600',
						esc_html__('Bold (700)', 'handyman-services') => '700',
						esc_html__('Black (900)', 'handyman-services') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'handyman-services'),
					"description" => wp_kses_data( __("Select color for the title", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'handyman-services'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'handyman-services') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'handyman-services'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => handyman_services_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'handyman-services'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'handyman-services') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'handyman-services'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => handyman_services_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'handyman-services'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'handyman-services') ),
					"group" => esc_html__('Icon &amp; Image', 'handyman-services'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'handyman-services'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'handyman-services') ),
					"group" => esc_html__('Icon &amp; Image', 'handyman-services'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'handyman-services') => 'small',
						esc_html__('Medium', 'handyman-services') => 'medium',
						esc_html__('Large', 'handyman-services') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'handyman-services'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'handyman-services') ),
					"group" => esc_html__('Icon &amp; Image', 'handyman-services'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'handyman-services') => 'top',
						esc_html__('Left', 'handyman-services') => 'left'
					),
					"type" => "dropdown"
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}
	}
}
?>
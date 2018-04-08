<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_price_block_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_price_block_theme_setup' );
	function handyman_services_sc_price_block_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_price_block_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('handyman_services_sc_price_block')) {	
	function handyman_services_sc_price_block($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		$class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= handyman_services_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode(handyman_services_sc_clear_around($content));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !handyman_services_param_is_off($scheme) && !handyman_services_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($title) ? '<div class="sc_price_block_title"><span>'.($title).'</span></div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	handyman_services_require_shortcode('trx_price_block', 'handyman_services_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_price_block_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_price_block_reg_shortcodes');
	function handyman_services_sc_price_block_reg_shortcodes() {
	
		handyman_services_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", 'handyman-services'),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", 'handyman-services') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Block style", 'handyman-services'),
					"desc" => wp_kses_data( __("Select style for this price block", 'handyman-services') ),
					"value" => 1,
					"options" => handyman_services_get_list_styles(1, 3),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'handyman-services'),
					"desc" => wp_kses_data( __("Block title", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", 'handyman-services'),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", 'handyman-services'),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'handyman-services'),
					"desc" => wp_kses_data( __('Select icon from Fontello icons set (placed before/instead price)',  'handyman-services') ),
					"value" => "",
					"type" => "icons",
					"options" => handyman_services_get_sc_param('icons')
				),
				"money" => array(
					"title" => esc_html__("Money", 'handyman-services'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'handyman-services') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'handyman-services'),
					"desc" => wp_kses_data( __("Currency character", 'handyman-services') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'handyman-services'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'handyman-services'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'handyman-services') ),
					"value" => "",
					"type" => "checklist",
					"options" => handyman_services_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'handyman-services'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'handyman-services') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => handyman_services_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", 'handyman-services'),
					"desc" => wp_kses_data( __("Description for this price block", 'handyman-services') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'handyman_services_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_price_block_reg_shortcodes_vc');
	function handyman_services_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", 'handyman-services'),
			"description" => wp_kses_data( __("Insert price block with title, price and description", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", 'handyman-services'),
					"desc" => wp_kses_data( __("Select style of this price block", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip(handyman_services_get_list_styles(1, 3)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'handyman-services'),
					"description" => wp_kses_data( __("Block title", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'handyman-services'),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", 'handyman-services'),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'handyman-services'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set (placed before/instead price)", 'handyman-services') ),
					"class" => "",
					"value" => handyman_services_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'handyman-services'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'handyman-services') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'handyman-services'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'handyman-services'),
					"description" => wp_kses_data( __("Currency character", 'handyman-services') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'handyman-services'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'handyman-services'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'handyman-services') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'handyman-services'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'handyman-services'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'handyman-services') ),
					"group" => esc_html__('Colors and Images', 'handyman-services'),
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'handyman-services'),
					"description" => wp_kses_data( __("Align price to left or right side", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", 'handyman-services'),
					"description" => wp_kses_data( __("Description for this price block", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
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
		
		class WPBakeryShortCode_Trx_PriceBlock extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}
	}
}
?>
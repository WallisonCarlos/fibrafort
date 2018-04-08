<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_anchor_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_anchor_theme_setup' );
	function handyman_services_sc_anchor_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_anchor_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('handyman_services_sc_anchor')) {	
	function handyman_services_sc_anchor($atts, $content = null) {
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(handyman_services_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (handyman_services_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	handyman_services_require_shortcode("trx_anchor", "handyman_services_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_anchor_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_anchor_reg_shortcodes');
	function handyman_services_sc_anchor_reg_shortcodes() {
	
		handyman_services_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'handyman-services'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'handyman-services') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'handyman-services'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'handyman-services') ),
					"value" => "",
					"type" => "icons",
					"options" => handyman_services_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'handyman-services'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'handyman-services'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'handyman-services'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'handyman-services'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'handyman-services') ),
					"value" => "no",
					"type" => "switch",
					"options" => handyman_services_get_sc_param('yes_no')
				),
				"id" => handyman_services_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_anchor_reg_shortcodes_vc');
	function handyman_services_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'handyman-services'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'handyman-services'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'handyman-services') ),
					"class" => "",
					"value" => handyman_services_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'handyman-services'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'handyman-services'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'handyman-services'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'handyman-services'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'handyman-services') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				handyman_services_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}
	}
}
?>
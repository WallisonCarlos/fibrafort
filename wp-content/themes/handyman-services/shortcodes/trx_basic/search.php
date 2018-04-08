<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_search_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_search_theme_setup' );
	function handyman_services_sc_search_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_search_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('handyman_services_sc_search')) {	
	function handyman_services_sc_search($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"state" => "",
			"ajax" => "",
			"title" => esc_html__('Search', 'handyman-services'),
			"scheme" => "original",
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
		if ($style == 'fullscreen') {
			if (empty($ajax)) $ajax = "no";
			if (empty($state)) $state = "closed";
		} else if ($style == 'expand') {
			if (empty($ajax)) $ajax = handyman_services_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else if ($style == 'slide') {
			if (empty($ajax)) $ajax = handyman_services_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else {
			if (empty($ajax)) $ajax = handyman_services_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "fixed";
		}
		// Load core messages
		handyman_services_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (handyman_services_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_attr__('Open search', 'handyman-services') : esc_attr__('Start search', 'handyman-services')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />'
								. ($style == 'fullscreen' ? '<a class="search_close icon-cancel"></a>' : '')
							. '</form>
						</div>'
						. (handyman_services_param_is_on($ajax) ? '<div class="search_results widget_area' . ($scheme && !handyman_services_param_is_off($scheme) && !handyman_services_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>' : '')
					. '</div>';
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	handyman_services_require_shortcode('trx_search', 'handyman_services_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_search_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_search_reg_shortcodes');
	function handyman_services_sc_search_reg_shortcodes() {
	
		handyman_services_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'handyman-services'),
			"desc" => wp_kses_data( __("Show search form", 'handyman-services') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'handyman-services'),
					"desc" => wp_kses_data( __("Select style to display search field", 'handyman-services') ),
					"value" => "regular",
					"options" => handyman_services_get_list_search_styles(),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'handyman-services'),
					"desc" => wp_kses_data( __("Select search field initial state", 'handyman-services') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'handyman-services'),
						"opened" => esc_html__('Opened', 'handyman-services'),
						"closed" => esc_html__('Closed', 'handyman-services')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'handyman-services'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'handyman-services') ),
					"value" => esc_html__("Search &hellip;", 'handyman-services'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'handyman-services'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'handyman-services') ),
					"value" => "yes",
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_search_reg_shortcodes_vc');
	function handyman_services_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'handyman-services'),
			"description" => wp_kses_data( __("Insert search form", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'handyman-services'),
					"description" => wp_kses_data( __("Select style to display search field", 'handyman-services') ),
					"class" => "",
					"value" => handyman_services_get_list_search_styles(),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'handyman-services'),
					"description" => wp_kses_data( __("Select search field initial state", 'handyman-services') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'handyman-services')  => "fixed",
						esc_html__('Opened', 'handyman-services') => "opened",
						esc_html__('Closed', 'handyman-services') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'handyman-services'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'handyman-services'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'handyman-services'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'handyman-services') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'handyman-services') => 'yes'),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}
	}
}
?>
<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'handyman_services_shortcodes_is_used' ) ) {
	function handyman_services_shortcodes_is_used() {
        $tem = '';
        if(isset($_REQUEST['page'])) $tem = $_REQUEST['page'];
		return handyman_services_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && $tem =='vc-roles')										// VC Role Manager
			|| (function_exists('handyman_services_vc_is_frontend') && handyman_services_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'handyman_services_shortcodes_width' ) ) {
	function handyman_services_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'handyman-services'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'handyman_services_shortcodes_height' ) ) {
	function handyman_services_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'handyman-services'),
			"desc" => wp_kses_data( __("Width and height of the element", 'handyman-services') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'handyman_services_get_sc_param' ) ) {
	function handyman_services_get_sc_param($prm) {
		return handyman_services_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'handyman_services_set_sc_param' ) ) {
	function handyman_services_set_sc_param($prm, $val) {
		handyman_services_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'handyman_services_sc_map' ) ) {
	function handyman_services_sc_map($sc_name, $sc_settings) {
		handyman_services_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'handyman_services_sc_map_after' ) ) {
	function handyman_services_sc_map_after($after, $sc_name, $sc_settings='') {
		handyman_services_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'handyman_services_sc_map_before' ) ) {
	function handyman_services_sc_map_before($before, $sc_name, $sc_settings='') {
		handyman_services_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'handyman_services_compare_sc_title' ) ) {
	function handyman_services_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'handyman_services_shortcodes_settings_theme_setup' ) ) {
//	if ( handyman_services_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'handyman_services_action_before_init_theme', 'handyman_services_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'handyman_services_action_after_init_theme', 'handyman_services_shortcodes_settings_theme_setup' );
	function handyman_services_shortcodes_settings_theme_setup() {
		if (handyman_services_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = handyman_services_storage_get('registered_templates');
			ksort($tmp);
			handyman_services_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			handyman_services_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'handyman-services'),
					"desc" => wp_kses_data( __("ID for current element", 'handyman-services') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'handyman-services'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'handyman-services'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'handyman-services'),
					'ol'	=> esc_html__('Ordered', 'handyman-services'),
					'iconed'=> esc_html__('Iconed', 'handyman-services')
				),

				'yes_no'	=> handyman_services_get_list_yesno(),
				'on_off'	=> handyman_services_get_list_onoff(),
				'dir' 		=> handyman_services_get_list_directions(),
				'align'		=> handyman_services_get_list_alignments(),
				'float'		=> handyman_services_get_list_floats(),
				'hpos'		=> handyman_services_get_list_hpos(),
				'show_hide'	=> handyman_services_get_list_showhide(),
				'sorting' 	=> handyman_services_get_list_sortings(),
				'ordering' 	=> handyman_services_get_list_orderings(),
				'shapes'	=> handyman_services_get_list_shapes(),
				'sizes'		=> handyman_services_get_list_sizes(),
				'sliders'	=> handyman_services_get_list_sliders(),
				'controls'	=> handyman_services_get_list_controls(),
				'categories'=> handyman_services_get_list_categories(),
				'columns'	=> handyman_services_get_list_columns(),
                'images'	=> array_merge(array('none'=>"none"), handyman_services_get_list_images("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), handyman_services_get_list_icons()),
				'locations'	=> handyman_services_get_list_dedicated_locations(),
				'filters'	=> handyman_services_get_list_portfolio_filters(),
				'formats'	=> handyman_services_get_list_post_formats_filters(),
				'hovers'	=> handyman_services_get_list_hovers(true),
				'hovers_dir'=> handyman_services_get_list_hovers_directions(true),
				'schemes'	=> handyman_services_get_list_color_schemes(true),
				'animations'		=> handyman_services_get_list_animations_in(),
				'margins' 			=> handyman_services_get_list_margins(true),
				'blogger_styles'	=> handyman_services_get_list_templates_blogger(),
				'forms'				=> handyman_services_get_list_templates_forms(),
				'posts_types'		=> handyman_services_get_list_posts_types(),
				'googlemap_styles'	=> handyman_services_get_list_googlemap_styles(),
				'field_types'		=> handyman_services_get_list_field_types(),
				'label_positions'	=> handyman_services_get_list_label_positions()
				)
			);

			// Common params
			handyman_services_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'handyman-services'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'handyman-services') ),
				"value" => "none",
				"type" => "select",
				"options" => handyman_services_get_sc_param('animations')
				)
			);
			handyman_services_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'handyman-services'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => handyman_services_get_sc_param('margins')
				)
			);
			handyman_services_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'handyman-services'),
				"value" => "inherit",
				"type" => "select",
				"options" => handyman_services_get_sc_param('margins')
				)
			);
			handyman_services_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'handyman-services'),
				"value" => "inherit",
				"type" => "select",
				"options" => handyman_services_get_sc_param('margins')
				)
			);
			handyman_services_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'handyman-services'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'handyman-services') ),
				"value" => "inherit",
				"type" => "select",
				"options" => handyman_services_get_sc_param('margins')
				)
			);

			handyman_services_storage_set('sc_params', apply_filters('handyman_services_filter_shortcodes_params', handyman_services_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			handyman_services_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('handyman_services_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = handyman_services_storage_get('shortcodes');
			uasort($tmp, 'handyman_services_compare_sc_title');
			handyman_services_storage_set('shortcodes', $tmp);
		}
	}
}
?>
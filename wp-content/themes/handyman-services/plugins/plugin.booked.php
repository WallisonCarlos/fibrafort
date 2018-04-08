<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('handyman_services_booked_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_booked_theme_setup', 1 );
	function handyman_services_booked_theme_setup() {
		// Register shortcode in the shortcodes list
		if (handyman_services_exists_booked()) {
			add_action('handyman_services_action_add_styles', 					'handyman_services_booked_frontend_scripts');
			add_action('handyman_services_action_shortcodes_list',				'handyman_services_booked_reg_shortcodes');
			if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
				add_action('handyman_services_action_shortcodes_list_vc',		'handyman_services_booked_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'handyman_services_filter_importer_options',			'handyman_services_booked_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_booked_importer_required_plugins', 10, 2);
			add_filter( 'handyman_services_filter_required_plugins',				'handyman_services_booked_required_plugins' );
		}
	}
}


// Check if plugin installed and activated
if ( !function_exists( 'handyman_services_exists_booked' ) ) {
	function handyman_services_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'handyman_services_booked_required_plugins' ) ) {
	//add_filter('handyman_services_filter_required_plugins',	'handyman_services_booked_required_plugins');
	function handyman_services_booked_required_plugins($list=array()) {
		if (in_array('booked', handyman_services_storage_get('required_plugins'))) {
			$path = handyman_services_get_file_dir('plugins/install/booked.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Booked', 'handyman-services'),
					'slug' 		=> 'booked',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'handyman_services_booked_frontend_scripts' ) ) {
	//add_action( 'handyman_services_action_add_styles', 'handyman_services_booked_frontend_scripts' );
	function handyman_services_booked_frontend_scripts() {
		if (file_exists(handyman_services_get_file_dir('css/plugin.booked.css')))
			handyman_services_enqueue_style( 'handyman_services-plugin.booked-style',  handyman_services_get_file_url('css/plugin.booked.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'handyman_services_booked_importer_required_plugins' ) ) {
	//add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_booked_importer_required_plugins', 10, 2);
	function handyman_services_booked_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('booked', handyman_services_storage_get('required_plugins')) && !handyman_services_exists_booked() )
		if (handyman_services_strpos($list, 'booked')!==false && !handyman_services_exists_booked() )
			$not_installed .= '<br>' . esc_html__('Booked Appointments', 'handyman-services');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'handyman_services_booked_importer_set_options' ) ) {
	//add_filter( 'handyman_services_filter_importer_options',	'handyman_services_booked_importer_set_options', 10, 1 );
	function handyman_services_booked_importer_set_options($options=array()) {
		if (in_array('booked', handyman_services_storage_get('required_plugins')) && handyman_services_exists_booked()) {
			$options['additional_options'][] = 'booked_%';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}


// Lists
//------------------------------------------------------------------------

// Return booked calendars list, prepended inherit (if need)
if ( !function_exists( 'handyman_services_get_list_booked_calendars' ) ) {
	function handyman_services_get_list_booked_calendars($prepend_inherit=false) {
		return handyman_services_exists_booked() ? handyman_services_get_list_terms($prepend_inherit, 'booked_custom_calendars') : array();
	}
}



// Register plugin's shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('handyman_services_booked_reg_shortcodes')) {
	//add_filter('handyman_services_action_shortcodes_list',	'handyman_services_booked_reg_shortcodes');
	function handyman_services_booked_reg_shortcodes() {
		if (handyman_services_storage_isset('shortcodes')) {

			$booked_cals = handyman_services_get_list_booked_calendars();

			handyman_services_sc_map('booked-appointments', array(
				"title" => esc_html__("Booked Appointments", 'handyman-services'),
				"desc" => esc_html__("Display the currently logged in user's upcoming appointments", 'handyman-services'),
				"decorate" => true,
				"container" => false,
				"params" => array()
				)
			);

			handyman_services_sc_map('booked-calendar', array(
				"title" => esc_html__("Booked Calendar", 'handyman-services'),
				"desc" => esc_html__("Insert booked calendar", 'handyman-services'),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"calendar" => array(
						"title" => esc_html__("Calendar", 'handyman-services'),
						"desc" => esc_html__("Select booked calendar to display", 'handyman-services'),
						"value" => "0",
						"type" => "select",
						"options" => handyman_services_array_merge(array(0 => esc_html__('- Select calendar -', 'handyman-services')), $booked_cals)
					),
					"year" => array(
						"title" => esc_html__("Year", 'handyman-services'),
						"desc" => esc_html__("Year to display on calendar by default", 'handyman-services'),
						"value" => date("Y"),
						"min" => date("Y"),
						"max" => date("Y")+10,
						"type" => "spinner"
					),
					"month" => array(
						"title" => esc_html__("Month", 'handyman-services'),
						"desc" => esc_html__("Month to display on calendar by default", 'handyman-services'),
						"value" => date("m"),
						"min" => 1,
						"max" => 12,
						"type" => "spinner"
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('handyman_services_booked_reg_shortcodes_vc')) {
	//add_filter('handyman_services_action_shortcodes_list_vc',	'handyman_services_booked_reg_shortcodes_vc');
	function handyman_services_booked_reg_shortcodes_vc() {

		$booked_cals = handyman_services_get_list_booked_calendars();

		// Booked Appointments
		vc_map( array(
				"base" => "booked-appointments",
				"name" => esc_html__("Booked Appointments", 'handyman-services'),
				"description" => esc_html__("Display the currently logged in user's upcoming appointments", 'handyman-services'),
				"category" => esc_html__('Content', 'handyman-services'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_appointments",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array()
			) );
			
		class WPBakeryShortCode_Booked_Appointments extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}

		// Booked Calendar
		vc_map( array(
				"base" => "booked-calendar",
				"name" => esc_html__("Booked Calendar", 'handyman-services'),
				"description" => esc_html__("Insert booked calendar", 'handyman-services'),
				"category" => esc_html__('Content', 'handyman-services'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_calendar",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "calendar",
						"heading" => esc_html__("Calendar", 'handyman-services'),
						"description" => esc_html__("Select booked calendar to display", 'handyman-services'),
						"admin_label" => true,
						"class" => "",
						"std" => "0",
						"value" => array_flip(handyman_services_array_merge(array(0 => esc_html__('- Select calendar -', 'handyman-services')), $booked_cals)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "year",
						"heading" => esc_html__("Year", 'handyman-services'),
						"description" => esc_html__("Year to display on calendar by default", 'handyman-services'),
						"admin_label" => true,
						"class" => "",
						"std" => date("Y"),
						"value" => date("Y"),
						"type" => "textfield"
					),
					array(
						"param_name" => "month",
						"heading" => esc_html__("Month", 'handyman-services'),
						"description" => esc_html__("Month to display on calendar by default", 'handyman-services'),
						"admin_label" => true,
						"class" => "",
						"std" => date("m"),
						"value" => date("m"),
						"type" => "textfield"
					)
				)
			) );
			
		class WPBakeryShortCode_Booked_Calendar extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}

	}
}
?>
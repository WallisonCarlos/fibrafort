<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('handyman_services_instagram_widget_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_instagram_widget_theme_setup', 1 );
	function handyman_services_instagram_widget_theme_setup() {
		if (handyman_services_exists_instagram_widget()) {
			add_action( 'handyman_services_action_add_styles', 						'handyman_services_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'handyman_services_filter_importer_required_plugins',		'handyman_services_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'handyman_services_filter_required_plugins',					'handyman_services_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'handyman_services_exists_instagram_widget' ) ) {
	function handyman_services_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'handyman_services_instagram_widget_required_plugins' ) ) {
	//add_filter('handyman_services_filter_required_plugins',	'handyman_services_instagram_widget_required_plugins');
	function handyman_services_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', handyman_services_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Widget', 'handyman-services'),
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'handyman_services_instagram_widget_frontend_scripts' ) ) {
	//add_action( 'handyman_services_action_add_styles', 'handyman_services_instagram_widget_frontend_scripts' );
	function handyman_services_instagram_widget_frontend_scripts() {
		if (file_exists(handyman_services_get_file_dir('css/plugin.instagram-widget.css')))
			handyman_services_enqueue_style( 'handyman_services-plugin.instagram-widget-style',  handyman_services_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'handyman_services_instagram_widget_importer_required_plugins' ) ) {
	//add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_instagram_widget_importer_required_plugins', 10, 2 );
	function handyman_services_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		if (handyman_services_strpos($list, 'instagram_widget')!==false && !handyman_services_exists_instagram_widget() )
			$not_installed .= '<br>' . esc_html__('WP Instagram Widget', 'handyman-services');
		return $not_installed;
	}
}
?>
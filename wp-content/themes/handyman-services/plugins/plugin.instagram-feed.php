<?php
/* Instagram Feed support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('handyman_services_instagram_feed_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_instagram_feed_theme_setup', 1 );
	function handyman_services_instagram_feed_theme_setup() {
		if (handyman_services_exists_instagram_feed()) {
			if (is_admin()) {
				add_filter( 'handyman_services_filter_importer_options',				'handyman_services_instagram_feed_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'handyman_services_filter_importer_required_plugins',		'handyman_services_instagram_feed_importer_required_plugins', 10, 2 );
			add_filter( 'handyman_services_filter_required_plugins',					'handyman_services_instagram_feed_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'handyman_services_exists_instagram_feed' ) ) {
	function handyman_services_exists_instagram_feed() {
		return defined('SBIVER');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'handyman_services_instagram_feed_required_plugins' ) ) {
	//add_filter('handyman_services_filter_required_plugins',	'handyman_services_instagram_feed_required_plugins');
	function handyman_services_instagram_feed_required_plugins($list=array()) {
		if (in_array('instagram_feed', handyman_services_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Feed', 'handyman-services'),
					'slug' 		=> 'instagram-feed',
					'required' 	=> false
				);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Feed in the required plugins
if ( !function_exists( 'handyman_services_instagram_feed_importer_required_plugins' ) ) {
	//add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_instagram_feed_importer_required_plugins', 10, 2 );
	function handyman_services_instagram_feed_importer_required_plugins($not_installed='', $list='') {
		if (handyman_services_strpos($list, 'instagram_feed')!==false && !handyman_services_exists_instagram_feed() )
			$not_installed .= '<br>' . esc_html__('Instagram Feed', 'handyman-services');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'handyman_services_instagram_feed_importer_set_options' ) ) {
	//add_filter( 'handyman_services_filter_importer_options',	'handyman_services_instagram_feed_importer_set_options' );
	function handyman_services_instagram_feed_importer_set_options($options=array()) {
		if ( in_array('instagram_feed', handyman_services_storage_get('required_plugins')) && handyman_services_exists_instagram_feed() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'sb_instagram_settings';
		}
		return $options;
	}
}
?>
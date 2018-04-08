<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('handyman_services_mailchimp_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_mailchimp_theme_setup', 1 );
	function handyman_services_mailchimp_theme_setup() {
		if (handyman_services_exists_mailchimp()) {
			if (is_admin()) {
				add_filter( 'handyman_services_filter_importer_options',				'handyman_services_mailchimp_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'handyman_services_filter_importer_required_plugins',		'handyman_services_mailchimp_importer_required_plugins', 10, 2 );
			add_filter( 'handyman_services_filter_required_plugins',					'handyman_services_mailchimp_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'handyman_services_exists_mailchimp' ) ) {
	function handyman_services_exists_mailchimp() {
		return function_exists('mc4wp_load_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'handyman_services_mailchimp_required_plugins' ) ) {
	//add_filter('handyman_services_filter_required_plugins',	'handyman_services_mailchimp_required_plugins');
	function handyman_services_mailchimp_required_plugins($list=array()) {
		if (in_array('mailchimp', handyman_services_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> esc_html__('MailChimp for WP', 'handyman-services'),
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Mail Chimp in the required plugins
if ( !function_exists( 'handyman_services_mailchimp_importer_required_plugins' ) ) {
	//add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_mailchimp_importer_required_plugins', 10, 2 );
	function handyman_services_mailchimp_importer_required_plugins($not_installed='', $list='') {
		if (handyman_services_strpos($list, 'mailchimp')!==false && !handyman_services_exists_mailchimp() )
			$not_installed .= '<br>' . esc_html__('Mail Chimp', 'handyman-services');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'handyman_services_mailchimp_importer_set_options' ) ) {
	//add_filter( 'handyman_services_filter_importer_options',	'handyman_services_mailchimp_importer_set_options' );
	function handyman_services_mailchimp_importer_set_options($options=array()) {
		if ( in_array('mailchimp', handyman_services_storage_get('required_plugins')) && handyman_services_exists_mailchimp() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'mc4wp_lite_checkbox';
			$options['additional_options'][] = 'mc4wp_lite_form';
		}
		return $options;
	}
}
?>
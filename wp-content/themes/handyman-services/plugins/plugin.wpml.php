<?php
/* WPML support functions
------------------------------------------------------------------------------- */

// Check if WPML installed and activated
if ( !function_exists( 'handyman_services_exists_wpml' ) ) {
	function handyman_services_exists_wpml() {
		return defined('ICL_SITEPRESS_VERSION') && class_exists('sitepress');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'handyman_services_wpml_required_plugins' ) ) {
	//add_filter('handyman_services_filter_required_plugins',	'handyman_services_wpml_required_plugins');
	function handyman_services_wpml_required_plugins($list=array()) {
		if (in_array('wpml', handyman_services_storage_get('required_plugins'))) {
			$path = handyman_services_get_file_dir('plugins/install/wpml.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('WPML', 'handyman-services'),
					'slug' 		=> 'wpml',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}
?>
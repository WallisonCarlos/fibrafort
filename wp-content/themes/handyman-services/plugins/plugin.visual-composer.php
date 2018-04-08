<?php
/* Visual Composer support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('handyman_services_vc_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_vc_theme_setup', 1 );
	function handyman_services_vc_theme_setup() {
		if (handyman_services_exists_visual_composer()) {
			if (is_admin()) {
				add_filter( 'handyman_services_filter_importer_options',				'handyman_services_vc_importer_set_options' );
			}
			add_action('handyman_services_action_add_styles',		 				'handyman_services_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'handyman_services_filter_importer_required_plugins',		'handyman_services_vc_importer_required_plugins', 10, 2 );
			add_filter( 'handyman_services_filter_required_plugins',					'handyman_services_vc_required_plugins' );
		}
	}
}

// Check if Visual Composer installed and activated
if ( !function_exists( 'handyman_services_exists_visual_composer' ) ) {
	function handyman_services_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'handyman_services_vc_is_frontend' ) ) {
	function handyman_services_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'handyman_services_vc_required_plugins' ) ) {
	//add_filter('handyman_services_filter_required_plugins',	'handyman_services_vc_required_plugins');
	function handyman_services_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', handyman_services_storage_get('required_plugins'))) {
			$path = handyman_services_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Visual Composer', 'handyman-services'),
					'slug' 		=> 'js_composer',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Enqueue VC custom styles
if ( !function_exists( 'handyman_services_vc_frontend_scripts' ) ) {
	//add_action( 'handyman_services_action_add_styles', 'handyman_services_vc_frontend_scripts' );
	function handyman_services_vc_frontend_scripts() {
		if (file_exists(handyman_services_get_file_dir('css/plugin.visual-composer.css')))
			handyman_services_enqueue_style( 'handyman_services-plugin.visual-composer-style',  handyman_services_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'handyman_services_vc_importer_required_plugins' ) ) {
	//add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_vc_importer_required_plugins', 10, 2 );
	function handyman_services_vc_importer_required_plugins($not_installed='', $list='') {
		if (!handyman_services_exists_visual_composer() )		// && handyman_services_strpos($list, 'visual_composer')!==false
			$not_installed .= '<br>' . esc_html__('Visual Composer', 'handyman-services');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'handyman_services_vc_importer_set_options' ) ) {
	//add_filter( 'handyman_services_filter_importer_options',	'handyman_services_vc_importer_set_options' );
	function handyman_services_vc_importer_set_options($options=array()) {
		if ( in_array('visual_composer', handyman_services_storage_get('required_plugins')) && handyman_services_exists_visual_composer() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'wpb_js_templates';
		}
		return $options;
	}
}
?>
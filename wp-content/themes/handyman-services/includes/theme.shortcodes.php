<?php
if (!function_exists('handyman_services_theme_shortcodes_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_theme_shortcodes_setup', 1 );
	function handyman_services_theme_shortcodes_setup() {
		add_filter('handyman_services_filter_googlemap_styles', 'handyman_services_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'handyman_services_theme_shortcodes_googlemap_styles' ) ) {
	function handyman_services_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'handyman-services');
		$list['greyscale']	= esc_html__('Greyscale', 'handyman-services');
		$list['inverse']	= esc_html__('Inverse', 'handyman-services');
		$list['apple']		= esc_html__('Apple', 'handyman-services');
		return $list;
	}
}
?>
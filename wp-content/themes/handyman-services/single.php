<?php
/**
 * Single post
 */
get_header(); 

$single_style = handyman_services_storage_get('single_style');
if (empty($single_style)) $single_style = handyman_services_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	handyman_services_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !handyman_services_param_is_off(handyman_services_get_custom_option('show_sidebar_main')),
			'content' => handyman_services_get_template_property($single_style, 'need_content'),
			'terms_list' => handyman_services_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>
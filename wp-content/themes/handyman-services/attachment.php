<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move handyman_services_set_post_views to the javascript - counter will work under cache system
	if (handyman_services_get_custom_option('use_ajax_views_counter')=='no') {
		handyman_services_set_post_views(get_the_ID());
	}

	handyman_services_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !handyman_services_param_is_off(handyman_services_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>
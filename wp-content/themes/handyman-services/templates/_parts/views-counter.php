<?php 
if (is_singular()) {
	if (handyman_services_get_theme_option('use_ajax_views_counter')=='yes') {
		handyman_services_storage_set_array('js_vars', 'ajax_views_counter', array(
			'post_id' => get_the_ID(),
			'post_views' => handyman_services_get_post_views(get_the_ID())
		));
	} else
		handyman_services_set_post_views(get_the_ID());
}
?>
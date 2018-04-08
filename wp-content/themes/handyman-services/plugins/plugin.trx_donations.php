<?php
/* Donations support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('handyman_services_trx_donations_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_trx_donations_theme_setup', 1 );
	function handyman_services_trx_donations_theme_setup() {

		// Register shortcode in the shortcodes list
		if (handyman_services_exists_trx_donations()) {
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('handyman_services_filter_get_blog_type',			'handyman_services_trx_donations_get_blog_type', 9, 2);
			add_filter('handyman_services_filter_get_blog_title',		'handyman_services_trx_donations_get_blog_title', 9, 2);
			add_filter('handyman_services_filter_get_current_taxonomy',	'handyman_services_trx_donations_get_current_taxonomy', 9, 2);
			add_filter('handyman_services_filter_is_taxonomy',			'handyman_services_trx_donations_is_taxonomy', 9, 2);
			add_filter('handyman_services_filter_get_stream_page_title',	'handyman_services_trx_donations_get_stream_page_title', 9, 2);
			add_filter('handyman_services_filter_get_stream_page_link',	'handyman_services_trx_donations_get_stream_page_link', 9, 2);
			add_filter('handyman_services_filter_get_stream_page_id',	'handyman_services_trx_donations_get_stream_page_id', 9, 2);
			add_filter('handyman_services_filter_query_add_filters',		'handyman_services_trx_donations_query_add_filters', 9, 2);
			add_filter('handyman_services_filter_detect_inheritance_key','handyman_services_trx_donations_detect_inheritance_key', 9, 1);
			add_filter('handyman_services_filter_list_post_types',		'handyman_services_trx_donations_list_post_types');
			// Register shortcodes in the list
			add_action('handyman_services_action_shortcodes_list',		'handyman_services_trx_donations_reg_shortcodes');
			if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
				add_action('handyman_services_action_shortcodes_list_vc','handyman_services_trx_donations_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'handyman_services_filter_importer_options',				'handyman_services_trx_donations_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_trx_donations_importer_required_plugins', 10, 2 );
			add_filter( 'handyman_services_filter_required_plugins',				'handyman_services_trx_donations_required_plugins' );
		}
	}
}

if ( !function_exists( 'handyman_services_trx_donations_settings_theme_setup2' ) ) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_trx_donations_settings_theme_setup2', 3 );
	function handyman_services_trx_donations_settings_theme_setup2() {
		// Add Donations post type and taxonomy into theme inheritance list
		if (handyman_services_exists_trx_donations()) {
			handyman_services_add_theme_inheritance( array('donations' => array(
				'stream_template' => 'blog-donations',
				'single_template' => 'single-donation',
				'taxonomy' => array(TRX_DONATIONS::TAXONOMY),
				'taxonomy_tags' => array(),
				'post_type' => array(TRX_DONATIONS::POST_TYPE),
				'override' => 'page'
				) )
			);
		}
	}
}

// Check if Donations installed and activated
if ( !function_exists( 'handyman_services_exists_trx_donations' ) ) {
	function handyman_services_exists_trx_donations() {
		return class_exists('TRX_DONATIONS');
	}
}


// Return true, if current page is donations page
if ( !function_exists( 'handyman_services_is_trx_donations_page' ) ) {
	function handyman_services_is_trx_donations_page() {
		$is = false;
		if (handyman_services_exists_trx_donations()) {
			$is = in_array(handyman_services_storage_get('page_template'), array('blog-donations', 'single-donation'));
			if (!$is) {
				if (!handyman_services_storage_empty('pre_query'))
					$is = (handyman_services_storage_call_obj_method('pre_query', 'is_single') && handyman_services_storage_call_obj_method('pre_query', 'get', 'post_type') == TRX_DONATIONS::POST_TYPE) 
							|| handyman_services_storage_call_obj_method('pre_query', 'is_post_type_archive', TRX_DONATIONS::POST_TYPE) 
							|| handyman_services_storage_call_obj_method('pre_query', 'is_tax', TRX_DONATIONS::TAXONOMY);
				else
					$is = (is_single() && get_query_var('post_type') == TRX_DONATIONS::POST_TYPE) 
							|| is_post_type_archive(TRX_DONATIONS::POST_TYPE) 
							|| is_tax(TRX_DONATIONS::TAXONOMY);
			}
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'handyman_services_trx_donations_detect_inheritance_key' ) ) {
	//add_filter('handyman_services_filter_detect_inheritance_key',	'handyman_services_trx_donations_detect_inheritance_key', 9, 1);
	function handyman_services_trx_donations_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return handyman_services_is_trx_donations_page() ? 'donations' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'handyman_services_trx_donations_get_blog_type' ) ) {
	//add_filter('handyman_services_filter_get_blog_type',	'handyman_services_trx_donations_get_blog_type', 9, 2);
	function handyman_services_trx_donations_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax(TRX_DONATIONS::TAXONOMY) || is_tax(TRX_DONATIONS::TAXONOMY))
			$page = 'donations_category';
		else if ($query && $query->get('post_type')==TRX_DONATIONS::POST_TYPE || get_query_var('post_type')==TRX_DONATIONS::POST_TYPE)
			$page = $query && $query->is_single() || is_single() ? 'donations_item' : 'donations';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'handyman_services_trx_donations_get_blog_title' ) ) {
	//add_filter('handyman_services_filter_get_blog_title',	'handyman_services_trx_donations_get_blog_title', 9, 2);
	function handyman_services_trx_donations_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( handyman_services_strpos($page, 'donations')!==false ) {
			if ( $page == 'donations_category' ) {
				$term = get_term_by( 'slug', get_query_var( TRX_DONATIONS::TAXONOMY ), TRX_DONATIONS::TAXONOMY, OBJECT);
				$title = $term->name;
			} else if ( $page == 'donations_item' ) {
				$title = handyman_services_get_post_title();
			} else {
				$title = esc_html__('All donations', 'handyman-services');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'handyman_services_trx_donations_get_stream_page_title' ) ) {
	//add_filter('handyman_services_filter_get_stream_page_title',	'handyman_services_trx_donations_get_stream_page_title', 9, 2);
	function handyman_services_trx_donations_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (handyman_services_strpos($page, 'donations')!==false) {
			if (($page_id = handyman_services_trx_donations_get_stream_page_id(0, $page=='donations' ? 'blog-donations' : $page)) > 0)
				$title = handyman_services_get_post_title($page_id);
			else
				$title = esc_html__('All donations', 'handyman-services');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'handyman_services_trx_donations_get_stream_page_id' ) ) {
	//add_filter('handyman_services_filter_get_stream_page_id',	'handyman_services_trx_donations_get_stream_page_id', 9, 2);
	function handyman_services_trx_donations_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (handyman_services_strpos($page, 'donations')!==false) $id = handyman_services_get_template_page_id('blog-donations');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'handyman_services_trx_donations_get_stream_page_link' ) ) {
	//add_filter('handyman_services_filter_get_stream_page_link',	'handyman_services_trx_donations_get_stream_page_link', 9, 2);
	function handyman_services_trx_donations_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (handyman_services_strpos($page, 'donations')!==false) {
			$id = handyman_services_get_template_page_id('blog-donations');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'handyman_services_trx_donations_get_current_taxonomy' ) ) {
	//add_filter('handyman_services_filter_get_current_taxonomy',	'handyman_services_trx_donations_get_current_taxonomy', 9, 2);
	function handyman_services_trx_donations_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( handyman_services_strpos($page, 'donations')!==false ) {
			$tax = TRX_DONATIONS::TAXONOMY;
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'handyman_services_trx_donations_is_taxonomy' ) ) {
	//add_filter('handyman_services_filter_is_taxonomy',	'handyman_services_trx_donations_is_taxonomy', 9, 2);
	function handyman_services_trx_donations_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get(TRX_DONATIONS::TAXONOMY)!='' || is_tax(TRX_DONATIONS::TAXONOMY) ? TRX_DONATIONS::TAXONOMY : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'handyman_services_trx_donations_query_add_filters' ) ) {
	//add_filter('handyman_services_filter_query_add_filters',	'handyman_services_trx_donations_query_add_filters', 9, 2);
	function handyman_services_trx_donations_query_add_filters($args, $filter) {
		if ($filter == 'donations') {
			$args['post_type'] = TRX_DONATIONS::POST_TYPE;
		}
		return $args;
	}
}

// Add custom post type to the list
if ( !function_exists( 'handyman_services_trx_donations_list_post_types' ) ) {
	//add_filter('handyman_services_filter_list_post_types',		'handyman_services_trx_donations_list_post_types');
	function handyman_services_trx_donations_list_post_types($list) {
		$list[TRX_DONATIONS::POST_TYPE] = esc_html__('Donations', 'handyman-services');
		return $list;
	}
}


// Register shortcode in the shortcodes list
if (!function_exists('handyman_services_trx_donations_reg_shortcodes')) {
	//add_filter('handyman_services_action_shortcodes_list',	'handyman_services_trx_donations_reg_shortcodes');
	function handyman_services_trx_donations_reg_shortcodes() {
		if (handyman_services_storage_isset('shortcodes')) {

			$plugin = TRX_DONATIONS::get_instance();
			$donations_groups = handyman_services_get_list_terms(false, TRX_DONATIONS::TAXONOMY);

			handyman_services_sc_map_before('trx_dropcaps', array(

				// Donations form
				"trx_donations_form" => array(
					"title" => esc_html__("Donations form", 'handyman-services'),
					"desc" => esc_html__("Insert Donations form", 'handyman-services'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'handyman-services'),
							"desc" => esc_html__("Title for the donations form", 'handyman-services'),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'handyman-services'),
							"desc" => esc_html__("Subtitle for the donations form", 'handyman-services'),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'handyman-services'),
							"desc" => esc_html__("Short description for the donations form", 'handyman-services'),
							"value" => "",
							"type" => "textarea"
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'handyman-services'),
							"desc" => esc_html__("Alignment of the donations form", 'handyman-services'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => handyman_services_get_sc_param('align')
						),
						"account" => array(
							"title" => esc_html__("PayPal account", 'handyman-services'),
							"desc" => esc_html__("PayPal account's e-mail. If empty - used from Donations settings", 'handyman-services'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"sandbox" => array(
							"title" => esc_html__("Sandbox mode", 'handyman-services'),
							"desc" => esc_html__("Use PayPal sandbox to test payments", 'handyman-services'),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => handyman_services_get_sc_param('yes_no')
						),
						"amount" => array(
							"title" => esc_html__("Default amount", 'handyman-services'),
							"desc" => esc_html__("Specify amount, initially selected in the form", 'handyman-services'),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"value" => 5,
							"min" => 1,
							"step" => 5,
							"type" => "spinner"
						),
						"currency" => array(
							"title" => esc_html__("Currency", 'handyman-services'),
							"desc" => esc_html__("Select payment's currency", 'handyman-services'),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => handyman_services_array_merge(array(0 => esc_html__('- Select currency -', 'handyman-services')), $plugin->currency_codes)
						),
						"width" => handyman_services_shortcodes_width(),
						"top" => handyman_services_get_sc_param('top'),
						"bottom" => handyman_services_get_sc_param('bottom'),
						"left" => handyman_services_get_sc_param('left'),
						"right" => handyman_services_get_sc_param('right'),
						"id" => handyman_services_get_sc_param('id'),
						"class" => handyman_services_get_sc_param('class'),
						"css" => handyman_services_get_sc_param('css')
					)
				),
				
				
				// Donations form
				"trx_donations_list" => array(
					"title" => esc_html__("Donations list", 'handyman-services'),
					"desc" => esc_html__("Insert Doantions list", 'handyman-services'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'handyman-services'),
							"desc" => esc_html__("Title for the donations list", 'handyman-services'),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'handyman-services'),
							"desc" => esc_html__("Subtitle for the donations list", 'handyman-services'),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'handyman-services'),
							"desc" => esc_html__("Short description for the donations list", 'handyman-services'),
							"value" => "",
							"type" => "textarea"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'handyman-services'),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", 'handyman-services'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'handyman-services'),
							"desc" => esc_html__("Caption for the button at the bottom of the block", 'handyman-services'),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("List style", 'handyman-services'),
							"desc" => esc_html__("Select style to display donations", 'handyman-services'),
							"value" => "excerpt",
							"type" => "select",
							"options" => array(
								'excerpt' => esc_html__('Excerpt', 'handyman-services')
							)
						),
						"readmore" => array(
							"title" => esc_html__("Read more text", 'handyman-services'),
							"desc" => esc_html__("Text of the 'Read more' link", 'handyman-services'),
							"value" => esc_html__('Read more', 'handyman-services'),
							"type" => "text"
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'handyman-services'),
							"desc" => esc_html__("Select categories (groups) to show donations. If empty - select donations from any category (group) or from IDs list", 'handyman-services'),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => handyman_services_array_merge(array(0 => esc_html__('- Select category -', 'handyman-services')), $donations_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of donations", 'handyman-services'),
							"desc" => esc_html__("How many donations will be displayed? If used IDs - this parameter ignored.", 'handyman-services'),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'handyman-services'),
							"desc" => esc_html__("How many columns use to show donations list", 'handyman-services'),
							"value" => 3,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'handyman-services'),
							"desc" => esc_html__("Skip posts before select next part.", 'handyman-services'),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Donadions order by", 'handyman-services'),
							"desc" => esc_html__("Select desired sorting method", 'handyman-services'),
							"value" => "date",
							"type" => "select",
							"options" => handyman_services_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Donations order", 'handyman-services'),
							"desc" => esc_html__("Select donations order", 'handyman-services'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => handyman_services_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Donations IDs list", 'handyman-services'),
							"desc" => esc_html__("Comma separated list of donations ID. If set - parameters above are ignored!", 'handyman-services'),
							"value" => "",
							"type" => "text"
						),
						"top" => handyman_services_get_sc_param('top'),
						"bottom" => handyman_services_get_sc_param('bottom'),
						"id" => handyman_services_get_sc_param('id'),
						"class" => handyman_services_get_sc_param('class'),
						"css" => handyman_services_get_sc_param('css')
					)
				)

			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('handyman_services_trx_donations_reg_shortcodes_vc')) {
	//add_filter('handyman_services_action_shortcodes_list_vc',	'handyman_services_trx_donations_reg_shortcodes_vc');
	function handyman_services_trx_donations_reg_shortcodes_vc() {

		$plugin = TRX_DONATIONS::get_instance();
		$donations_groups = handyman_services_get_list_terms(false, TRX_DONATIONS::TAXONOMY);

		// Donations form
		vc_map( array(
				"base" => "trx_donations_form",
				"name" => esc_html__("Donations form", 'handyman-services'),
				"description" => esc_html__("Insert Donations form", 'handyman-services'),
				"category" => esc_html__('Content', 'handyman-services'),
				'icon' => 'icon_trx_donations_form',
				"class" => "trx_sc_single trx_sc_donations_form",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'handyman-services'),
						"description" => esc_html__("Title for the donations form", 'handyman-services'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'handyman-services'),
						"description" => esc_html__("Subtitle for the donations form", 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'handyman-services'),
						"description" => esc_html__("Description for the donations form", 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'handyman-services'),
						"description" => esc_html__("Alignment of the donations form", 'handyman-services'),
						"class" => "",
						"value" => array_flip(handyman_services_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "account",
						"heading" => esc_html__("PayPal account", 'handyman-services'),
						"description" => esc_html__("PayPal account's e-mail. If empty - used from Donations settings", 'handyman-services'),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "sandbox",
						"heading" => esc_html__("Sandbox mode", 'handyman-services'),
						"description" => esc_html__("Use PayPal sandbox to test payments", 'handyman-services'),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'handyman-services'),
						'dependency' => array(
							'element' => 'account',
							'not_empty' => true
						),
						"class" => "",
						"value" => array("Sandbox mode" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "amount",
						"heading" => esc_html__("Default amount", 'handyman-services'),
						"description" => esc_html__("Specify amount, initially selected in the form", 'handyman-services'),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'handyman-services'),
						"class" => "",
						"value" => "5",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency", 'handyman-services'),
						"description" => esc_html__("Select payment's currency", 'handyman-services'),
						"class" => "",
						"value" => array_flip(handyman_services_array_merge(array(0 => esc_html__('- Select currency -', 'handyman-services')), $plugin->currency_codes)),
						"type" => "dropdown"
					),
					handyman_services_get_vc_param('id'),
					handyman_services_get_vc_param('class'),
					handyman_services_get_vc_param('css'),
					handyman_services_vc_width(),
					handyman_services_get_vc_param('margin_top'),
					handyman_services_get_vc_param('margin_bottom'),
					handyman_services_get_vc_param('margin_left'),
					handyman_services_get_vc_param('margin_right')
				)
			) );
			
		class WPBakeryShortCode_Trx_Donations_Form extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}



		// Donations list
		vc_map( array(
				"base" => "trx_donations_list",
				"name" => esc_html__("Donations list", 'handyman-services'),
				"description" => esc_html__("Insert Donations list", 'handyman-services'),
				"category" => esc_html__('Content', 'handyman-services'),
				'icon' => 'icon_trx_donations_list',
				"class" => "trx_sc_single trx_sc_donations_list",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("List style", 'handyman-services'),
						"description" => esc_html__("Select style to display donations", 'handyman-services'),
						"class" => "",
						"value" => array(
							esc_html__('Excerpt', 'handyman-services') => 'excerpt'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'handyman-services'),
						"description" => esc_html__("Title for the donations form", 'handyman-services'),
						"group" => esc_html__('Captions', 'handyman-services'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'handyman-services'),
						"description" => esc_html__("Subtitle for the donations form", 'handyman-services'),
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'handyman-services'),
						"description" => esc_html__("Description for the donations form", 'handyman-services'),
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", 'handyman-services'),
						"description" => esc_html__("Link URL for the button at the bottom of the block", 'handyman-services'),
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", 'handyman-services'),
						"description" => esc_html__("Caption for the button at the bottom of the block", 'handyman-services'),
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more text", 'handyman-services'),
						"description" => esc_html__("Text of the 'Read more' link", 'handyman-services'),
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => esc_html__('Read more', 'handyman-services'),
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'handyman-services'),
						"description" => esc_html__("Select category to show donations. If empty - select donations from any category (group) or from IDs list", 'handyman-services'),
						"group" => esc_html__('Query', 'handyman-services'),
						"class" => "",
						"value" => array_flip(handyman_services_array_merge(array(0 => esc_html__('- Select category -', 'handyman-services')), $donations_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'handyman-services'),
						"description" => esc_html__("How many columns use to show donations", 'handyman-services'),
						"group" => esc_html__('Query', 'handyman-services'),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'handyman-services'),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'handyman-services'),
						"group" => esc_html__('Query', 'handyman-services'),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'handyman-services'),
						"description" => esc_html__("Skip posts before select next part.", 'handyman-services'),
						"group" => esc_html__('Query', 'handyman-services'),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'handyman-services'),
						"description" => esc_html__("Select desired posts sorting method", 'handyman-services'),
						"group" => esc_html__('Query', 'handyman-services'),
						"class" => "",
						"value" => array_flip(handyman_services_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'handyman-services'),
						"description" => esc_html__("Select desired posts order", 'handyman-services'),
						"group" => esc_html__('Query', 'handyman-services'),
						"class" => "",
						"value" => array_flip(handyman_services_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("client's IDs list", 'handyman-services'),
						"description" => esc_html__("Comma separated list of donation's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'handyman-services'),
						"group" => esc_html__('Query', 'handyman-services'),
						'dependency' => array(
							'element' => 'cats',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),

					handyman_services_get_vc_param('id'),
					handyman_services_get_vc_param('class'),
					handyman_services_get_vc_param('css'),
					handyman_services_get_vc_param('margin_top'),
					handyman_services_get_vc_param('margin_bottom')
				)
			) );
			
		class WPBakeryShortCode_Trx_Donations_List extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}

	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'handyman_services_trx_donations_required_plugins' ) ) {
	//add_filter('handyman_services_filter_required_plugins',	'handyman_services_trx_donations_required_plugins');
	function handyman_services_trx_donations_required_plugins($list=array()) {
		if (in_array('trx_donations', handyman_services_storage_get('required_plugins'))) {
			$path = handyman_services_get_file_dir('plugins/install/trx_donations.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Donations', 'handyman-services'),
					'slug' 		=> 'trx_donations',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'handyman_services_trx_donations_importer_required_plugins' ) ) {
	//add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_trx_donations_importer_required_plugins', 10, 2 );
	function handyman_services_trx_donations_importer_required_plugins($not_installed='', $list='') {
		if (handyman_services_strpos($list, 'trx_donations')!==false && !handyman_services_exists_trx_donations() )
			$not_installed .= '<br>' . esc_html__('Donations', 'handyman-services');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'handyman_services_trx_donations_importer_set_options' ) ) {
	//add_filter( 'handyman_services_filter_importer_options',	'handyman_services_trx_donations_importer_set_options' );
	function handyman_services_trx_donations_importer_set_options($options=array()) {
		if ( in_array('trx_donations', handyman_services_storage_get('required_plugins')) && handyman_services_exists_trx_donations() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'trx_donations_options';
		}
		return $options;
	}
}
?>
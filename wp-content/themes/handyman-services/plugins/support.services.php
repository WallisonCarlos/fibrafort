<?php
/**
 * Handyman Services Framework: Services support
 *
 * @package	handyman_services
 * @since	handyman_services 1.0
 */

// Theme init
if (!function_exists('handyman_services_services_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_services_theme_setup',1 );
	function handyman_services_services_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('handyman_services_filter_get_blog_type',			'handyman_services_services_get_blog_type', 9, 2);
		add_filter('handyman_services_filter_get_blog_title',		'handyman_services_services_get_blog_title', 9, 2);
		add_filter('handyman_services_filter_get_current_taxonomy',	'handyman_services_services_get_current_taxonomy', 9, 2);
		add_filter('handyman_services_filter_is_taxonomy',			'handyman_services_services_is_taxonomy', 9, 2);
		add_filter('handyman_services_filter_get_stream_page_title',	'handyman_services_services_get_stream_page_title', 9, 2);
		add_filter('handyman_services_filter_get_stream_page_link',	'handyman_services_services_get_stream_page_link', 9, 2);
		add_filter('handyman_services_filter_get_stream_page_id',	'handyman_services_services_get_stream_page_id', 9, 2);
		add_filter('handyman_services_filter_query_add_filters',		'handyman_services_services_query_add_filters', 9, 2);
		add_filter('handyman_services_filter_detect_inheritance_key','handyman_services_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (handyman_services_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'handyman_services_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'handyman_services_post_fill_options_column', 9, 2);
		}

		// Register shortcodes [trx_services] and [trx_services_item]
		add_action('handyman_services_action_shortcodes_list',		'handyman_services_services_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_services_reg_shortcodes_vc');
		
		// Add supported data types
		handyman_services_theme_support_pt('services');
		handyman_services_theme_support_tx('services_group');
	}
}

if ( !function_exists( 'handyman_services_services_settings_theme_setup2' ) ) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_services_settings_theme_setup2', 3 );
	function handyman_services_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		handyman_services_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-service',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'custom'
			) )
		);
	}
}



// Return true, if current page is services page
if ( !function_exists( 'handyman_services_is_services_page' ) ) {
	function handyman_services_is_services_page() {
		$is = in_array(handyman_services_storage_get('page_template'), array('blog-services', 'single-service'));
		if (!$is) {
			if (!handyman_services_storage_empty('pre_query'))
				$is = handyman_services_storage_call_obj_method('pre_query', 'get', 'post_type')=='services' 
						|| handyman_services_storage_call_obj_method('pre_query', 'is_tax', 'services_group') 
						|| (handyman_services_storage_call_obj_method('pre_query', 'is_page') 
								&& ($id=handyman_services_get_template_page_id('blog-services')) > 0 
								&& $id==handyman_services_storage_get_obj_property('pre_query', 'queried_object_id', 0) 
							);
			else
				$is = get_query_var('post_type')=='services' 
						|| is_tax('services_group') 
						|| (is_page() && ($id=handyman_services_get_template_page_id('blog-services')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'handyman_services_services_detect_inheritance_key' ) ) {
	//add_filter('handyman_services_filter_detect_inheritance_key',	'handyman_services_services_detect_inheritance_key', 9, 1);
	function handyman_services_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return handyman_services_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'handyman_services_services_get_blog_type' ) ) {
	//add_filter('handyman_services_filter_get_blog_type',	'handyman_services_services_get_blog_type', 9, 2);
	function handyman_services_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'handyman_services_services_get_blog_title' ) ) {
	//add_filter('handyman_services_filter_get_blog_title',	'handyman_services_services_get_blog_title', 9, 2);
	function handyman_services_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( handyman_services_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = handyman_services_get_post_title();
			} else {
				$title = esc_html__('All services', 'handyman-services');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'handyman_services_services_get_stream_page_title' ) ) {
	//add_filter('handyman_services_filter_get_stream_page_title',	'handyman_services_services_get_stream_page_title', 9, 2);
	function handyman_services_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (handyman_services_strpos($page, 'services')!==false) {
			if (($page_id = handyman_services_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = handyman_services_get_post_title($page_id);
			else
				$title = esc_html__('All services', 'handyman-services');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'handyman_services_services_get_stream_page_id' ) ) {
	//add_filter('handyman_services_filter_get_stream_page_id',	'handyman_services_services_get_stream_page_id', 9, 2);
	function handyman_services_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (handyman_services_strpos($page, 'services')!==false) $id = handyman_services_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'handyman_services_services_get_stream_page_link' ) ) {
	//add_filter('handyman_services_filter_get_stream_page_link',	'handyman_services_services_get_stream_page_link', 9, 2);
	function handyman_services_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (handyman_services_strpos($page, 'services')!==false) {
			$id = handyman_services_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'handyman_services_services_get_current_taxonomy' ) ) {
	//add_filter('handyman_services_filter_get_current_taxonomy',	'handyman_services_services_get_current_taxonomy', 9, 2);
	function handyman_services_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( handyman_services_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'handyman_services_services_is_taxonomy' ) ) {
	//add_filter('handyman_services_filter_is_taxonomy',	'handyman_services_services_is_taxonomy', 9, 2);
	function handyman_services_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'handyman_services_services_query_add_filters' ) ) {
	//add_filter('handyman_services_filter_query_add_filters',	'handyman_services_services_query_add_filters', 9, 2);
	function handyman_services_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}





// ---------------------------------- [trx_services] ---------------------------------------

/*
[trx_services id="unique_id" columns="4" count="4" style="services-1|services-2|..." title="Block title" subtitle="xxx" description="xxxxxx"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
[/trx_services]
*/
if ( !function_exists( 'handyman_services_sc_services' ) ) {
	function handyman_services_sc_services($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "services-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"equalheight" => "no",
			"align" => "",
			"custom" => "no",
			"type" => "icons",	// icons | images
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"readmore" => esc_html__('Learn more', 'handyman-services'),
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'handyman-services'),
			"link" => '',
			"scheme" => '',
			"image" => '',
			"image_align" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (handyman_services_param_is_off($slider) && $columns > 1 && $style == 'services-5' && !empty($image)) $columns = 2;
		if (!empty($image)) {
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
		}

		if (empty($id)) $id = "sc_services_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && handyman_services_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		
		$class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = handyman_services_get_css_dimensions_from_values($width);
		$hs = handyman_services_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$columns = max(1, min(12, (int) $columns));
		$count = max(1, (int) $count);
		if (handyman_services_param_is_off($custom) && $count < $columns) $columns = $count;

		if (handyman_services_param_is_on($slider)) handyman_services_enqueue_slider('swiper');

		handyman_services_storage_set('sc_services_data', array(
			'id' => $id,
            'style' => $style,
            'type' => $type,
            'columns' => $columns,
            'counter' => 0,
            'slider' => $slider,
            'css_wh' => $ws . $hs,
            'readmore' => $readmore
            )
        );
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_services_wrap'
						. ($scheme && !handyman_services_param_is_off($scheme) && !handyman_services_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_services'
							. ' sc_services_style_'.esc_attr($style)
							. ' sc_services_type_'.esc_attr($type)
							. ' ' . esc_attr(handyman_services_get_template_property($style, 'container_classes'))
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!handyman_services_param_is_off($equalheight) ? ' data-equal-height=".sc_services_item"' : '')
						. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_services_subtitle sc_item_subtitle">' . trim(handyman_services_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_services_title sc_item_title">' . trim(handyman_services_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_services_descr sc_item_descr">' . trim(handyman_services_strmacros($description)) . '</div>' : '')
					. (handyman_services_param_is_on($slider) 
						? ('<div class="sc_slider_swiper swiper-slider-container'
										. ' ' . esc_attr(handyman_services_get_slider_controls_classes($controls))
										. (handyman_services_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && handyman_services_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && handyman_services_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
									. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
									. ' data-slides-min-width="250"'
								. '>'
							. '<div class="slides swiper-wrapper">')
						: ($columns > 1 
							? ($style == 'services-5' && !empty($image) 
								? '<div class="sc_service_container sc_align_'.esc_attr($image_align).'">'
									. '<div class="sc_services_image"><img src="'.esc_url($image).'" alt=""></div>' 
								: '')
								. '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		if (handyman_services_param_is_on($custom) && $content) {
			$output .= do_shortcode($content);
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'services',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
				'readmore' => $readmore
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = handyman_services_query_add_sort_order($args, $orderby, $order);
			$args = handyman_services_query_add_posts_and_cats($args, $ids, 'services', $cat, 'services_group');
			
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => handyman_services_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'readmore' => $readmore,
					'tag_type' => $type,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$output .= handyman_services_show_post_layout($args);
			}
			wp_reset_postdata();
		}
	
		if (handyman_services_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {
			$output .= '</div>';
			if ($style == 'services-5' && !empty($image))
				$output .= '</div>';
		}

		$output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.handyman_services_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_services -->'
				. '</div><!-- /.sc_services_wrap -->';
	
		// Add template specific scripts and styles
		do_action('handyman_services_action_blog_scripts', $style);
	
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_services', $atts, $content);
	}
	handyman_services_require_shortcode('trx_services', 'handyman_services_sc_services');
}


if ( !function_exists( 'handyman_services_sc_services_item' ) ) {
	function handyman_services_sc_services_item($atts, $content=null) {
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts( array(
			// Individual params
			"icon" => "",
			"image" => "",
			"title" => "",
			"link" => "",
			"readmore" => "(none)",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		handyman_services_storage_inc_array('sc_services_data', 'counter');

		$id = $id ? $id : (handyman_services_storage_get_array('sc_services_data', 'id') ? handyman_services_storage_get_array('sc_services_data', 'id') . '_' . handyman_services_storage_get_array('sc_services_data', 'counter') : '');

		$descr = trim(chop(do_shortcode($content)));
		$readmore = $readmore=='(none)' ? handyman_services_storage_get_array('sc_services_data', 'readmore') : $readmore;

		$type = handyman_services_storage_get_array('sc_services_data', 'type');
		if (!empty($icon)) {
			$type = 'icons';
		} else if (!empty($image)) {
			$type = 'images';
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$thumb_sizes = handyman_services_get_thumb_sizes(array('layout' => handyman_services_storage_get_array('sc_services_data', 'style')));
			$image = handyman_services_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		$post_data = array(
			'post_title' => $title,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_icon' => $icon,
			'post_link' => $link,
			'post_protected' => false,
			'post_format' => 'standard'
		);
		$args = array(
			'layout' => handyman_services_storage_get_array('sc_services_data', 'style'),
			'number' => handyman_services_storage_get_array('sc_services_data', 'counter'),
			'columns_count' => handyman_services_storage_get_array('sc_services_data', 'columns'),
			'slider' => handyman_services_storage_get_array('sc_services_data', 'slider'),
			'show' => false,
			'descr'  => -1,		// -1 - don't strip tags, 0 - strip_tags, >0 - strip_tags and truncate string
			'readmore' => $readmore,
			'tag_type' => $type,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => handyman_services_storage_get_array('sc_services_data', 'css_wh')
		);
		$output = handyman_services_show_post_layout($args, $post_data);
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_services_item', $atts, $content);
	}
	handyman_services_require_shortcode('trx_services_item', 'handyman_services_sc_services_item');
}
// ---------------------------------- [/trx_services] ---------------------------------------



// Add [trx_services] and [trx_services_item] in the shortcodes list
if (!function_exists('handyman_services_services_reg_shortcodes')) {
	//add_filter('handyman_services_action_shortcodes_list',	'handyman_services_services_reg_shortcodes');
	function handyman_services_services_reg_shortcodes() {
		if (handyman_services_storage_isset('shortcodes')) {

			$services_groups = handyman_services_get_list_terms(false, 'services_group');
			$services_styles = handyman_services_get_list_templates('services');
			$controls 		 = handyman_services_get_list_slider_controls();

			handyman_services_sc_map_after('trx_section', array(

				// Services
				"trx_services" => array(
					"title" => esc_html__("Services", 'handyman-services'),
					"desc" => wp_kses_data( __("Insert services list in your page (post)", 'handyman-services') ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'handyman-services'),
							"desc" => wp_kses_data( __("Title for the block", 'handyman-services') ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'handyman-services'),
							"desc" => wp_kses_data( __("Subtitle for the block", 'handyman-services') ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'handyman-services'),
							"desc" => wp_kses_data( __("Short description for the block", 'handyman-services') ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Services style", 'handyman-services'),
							"desc" => wp_kses_data( __("Select style to display services list", 'handyman-services') ),
							"value" => "services-1",
							"type" => "select",
							"options" => $services_styles
						),
						"image" => array(
								"title" => esc_html__("Item's image", 'handyman-services'),
								"desc" => wp_kses_data( __("Item's image", 'handyman-services') ),
								"dependency" => array(
									'style' => 'services-5'
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
						),
						"image_align" => array(
							"title" => esc_html__("Image alignment", 'handyman-services'),
							"desc" => wp_kses_data( __("Alignment of the image", 'handyman-services') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => handyman_services_get_sc_param('align')
						),
						"type" => array(
							"title" => esc_html__("Icon's type", 'handyman-services'),
							"desc" => wp_kses_data( __("Select type of icons: font icon or image", 'handyman-services') ),
							"value" => "icons",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'icons'  => esc_html__('Icons', 'handyman-services'),
								'images' => esc_html__('Images', 'handyman-services')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'handyman-services'),
							"desc" => wp_kses_data( __("How many columns use to show services list", 'handyman-services') ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'handyman-services'),
							"desc" => wp_kses_data( __("Select color scheme for this block", 'handyman-services') ),
							"value" => "",
							"type" => "checklist",
							"options" => handyman_services_get_sc_param('schemes')
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'handyman-services'),
							"desc" => wp_kses_data( __("Use slider to show services", 'handyman-services') ),
							"value" => "no",
							"type" => "switch",
							"options" => handyman_services_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'handyman-services'),
							"desc" => wp_kses_data( __("Slider controls style and position", 'handyman-services') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'handyman-services'),
							"desc" => wp_kses_data( __("Size of space (in px) between slides", 'handyman-services') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'handyman-services'),
							"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'handyman-services') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'handyman-services'),
							"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'handyman-services') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => handyman_services_get_sc_param('yes_no')
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'handyman-services'),
							"desc" => wp_kses_data( __("Alignment of the services block", 'handyman-services') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => handyman_services_get_sc_param('align')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'handyman-services'),
							"desc" => wp_kses_data( __("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", 'handyman-services') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => handyman_services_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'handyman-services'),
							"desc" => wp_kses_data( __("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", 'handyman-services') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => handyman_services_array_merge(array(0 => esc_html__('- Select category -', 'handyman-services')), $services_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'handyman-services'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'handyman-services') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'handyman-services'),
							"desc" => wp_kses_data( __("Skip posts before select next part.", 'handyman-services') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'handyman-services'),
							"desc" => wp_kses_data( __("Select desired posts sorting method", 'handyman-services') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => handyman_services_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'handyman-services'),
							"desc" => wp_kses_data( __("Select desired posts order", 'handyman-services') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => handyman_services_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'handyman-services'),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'handyman-services') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"readmore" => array(
							"title" => esc_html__("Read more", 'handyman-services'),
							"desc" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'handyman-services') ),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'handyman-services'),
							"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'handyman-services') ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'handyman-services'),
							"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'handyman-services') ),
							"value" => "",
							"type" => "text"
						),
						"width" => handyman_services_shortcodes_width(),
						"height" => handyman_services_shortcodes_height(),
						"top" => handyman_services_get_sc_param('top'),
						"bottom" => handyman_services_get_sc_param('bottom'),
						"left" => handyman_services_get_sc_param('left'),
						"right" => handyman_services_get_sc_param('right'),
						"id" => handyman_services_get_sc_param('id'),
						"class" => handyman_services_get_sc_param('class'),
						"animation" => handyman_services_get_sc_param('animation'),
						"css" => handyman_services_get_sc_param('css')
					),
					"children" => array(
						"name" => "trx_services_item",
						"title" => esc_html__("Service item", 'handyman-services'),
						"desc" => wp_kses_data( __("Service item", 'handyman-services') ),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", 'handyman-services'),
								"desc" => wp_kses_data( __("Item's title", 'handyman-services') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("Item's icon",  'handyman-services'),
								"desc" => wp_kses_data( __('Select icon for the item from Fontello icons set',  'handyman-services') ),
								"value" => "",
								"type" => "icons",
								"options" => handyman_services_get_sc_param('icons')
							),
							"image" => array(
								"title" => esc_html__("Item's image", 'handyman-services'),
								"desc" => wp_kses_data( __("Item's image (if icon not selected)", 'handyman-services') ),
								"dependency" => array(
									'icon' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"link" => array(
								"title" => esc_html__("Link", 'handyman-services'),
								"desc" => wp_kses_data( __("Link on service's item page", 'handyman-services') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"readmore" => array(
								"title" => esc_html__("Read more", 'handyman-services'),
								"desc" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'handyman-services') ),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'handyman-services'),
								"desc" => wp_kses_data( __("Item's short description", 'handyman-services') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => handyman_services_get_sc_param('id'),
							"class" => handyman_services_get_sc_param('class'),
							"animation" => handyman_services_get_sc_param('animation'),
							"css" => handyman_services_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_services] and [trx_services_item] in the VC shortcodes list
if (!function_exists('handyman_services_services_reg_shortcodes_vc')) {
	//add_filter('handyman_services_action_shortcodes_list_vc',	'handyman_services_services_reg_shortcodes_vc');
	function handyman_services_services_reg_shortcodes_vc() {

		$services_groups = handyman_services_get_list_terms(false, 'services_group');
		$services_styles = handyman_services_get_list_templates('services');
		$controls		 = handyman_services_get_list_slider_controls();

		// Services
		vc_map( array(
				"base" => "trx_services",
				"name" => esc_html__("Services", 'handyman-services'),
				"description" => wp_kses_data( __("Insert services list", 'handyman-services') ),
				"category" => esc_html__('Content', 'handyman-services'),
				"icon" => 'icon_trx_services',
				"class" => "trx_sc_columns trx_sc_services",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_services_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Services style", 'handyman-services'),
						"description" => wp_kses_data( __("Select style to display services list", 'handyman-services') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($services_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Icon's type", 'handyman-services'),
						"description" => wp_kses_data( __("Select type of icons: font icon or image", 'handyman-services') ),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Icons', 'handyman-services') => 'icons',
							esc_html__('Images', 'handyman-services') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "equalheight",
						"heading" => esc_html__("Equal height", 'handyman-services'),
						"description" => wp_kses_data( __("Make equal height for all items in the row", 'handyman-services') ),
						"value" => array("Equal height" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'handyman-services'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'handyman-services') ),
						"class" => "",
						"value" => array_flip(handyman_services_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", 'handyman-services'),
						"description" => wp_kses_data( __("Item's image", 'handyman-services') ),
						'dependency' => array(
							'element' => 'style',
							'value' => 'services-5'
						),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_align",
						"heading" => esc_html__("Image alignment", 'handyman-services'),
						"description" => wp_kses_data( __("Alignment of the image", 'handyman-services') ),
						"class" => "",
						"value" => array_flip(handyman_services_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'handyman-services'),
						"description" => wp_kses_data( __("Use slider to show services", 'handyman-services') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'handyman-services'),
						"class" => "",
						"std" => "no",
						"value" => array_flip(handyman_services_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'handyman-services'),
						"description" => wp_kses_data( __("Slider controls style and position", 'handyman-services') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'handyman-services'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", 'handyman-services'),
						"description" => wp_kses_data( __("Size of space (in px) between slides", 'handyman-services') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'handyman-services'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'handyman-services'),
						"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'handyman-services') ),
						"group" => esc_html__('Slider', 'handyman-services'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'handyman-services'),
						"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'handyman-services') ),
						"group" => esc_html__('Slider', 'handyman-services'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'handyman-services'),
						"description" => wp_kses_data( __("Alignment of the services block", 'handyman-services') ),
						"class" => "",
						"value" => array_flip(handyman_services_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'handyman-services'),
						"description" => wp_kses_data( __("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", 'handyman-services') ),
						"class" => "",
						"value" => array("Custom services" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'handyman-services'),
						"description" => wp_kses_data( __("Title for the block", 'handyman-services') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'handyman-services'),
						"description" => wp_kses_data( __("Subtitle for the block", 'handyman-services') ),
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'handyman-services'),
						"description" => wp_kses_data( __("Description for the block", 'handyman-services') ),
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'handyman-services'),
						"description" => wp_kses_data( __("Select category to show services. If empty - select services from any category (group) or from IDs list", 'handyman-services') ),
						"group" => esc_html__('Query', 'handyman-services'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(handyman_services_array_merge(array(0 => esc_html__('- Select category -', 'handyman-services')), $services_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'handyman-services'),
						"description" => wp_kses_data( __("How many columns use to show services list", 'handyman-services') ),
						"group" => esc_html__('Query', 'handyman-services'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'handyman-services'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'handyman-services') ),
						"admin_label" => true,
						"group" => esc_html__('Query', 'handyman-services'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'handyman-services'),
						"description" => wp_kses_data( __("Skip posts before select next part.", 'handyman-services') ),
						"group" => esc_html__('Query', 'handyman-services'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'handyman-services'),
						"description" => wp_kses_data( __("Select desired posts sorting method", 'handyman-services') ),
						"group" => esc_html__('Query', 'handyman-services'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "date",
						"class" => "",
						"value" => array_flip(handyman_services_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'handyman-services'),
						"description" => wp_kses_data( __("Select desired posts order", 'handyman-services') ),
						"group" => esc_html__('Query', 'handyman-services'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "desc",
						"class" => "",
						"value" => array_flip(handyman_services_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Service's IDs list", 'handyman-services'),
						"description" => wp_kses_data( __("Comma separated list of service's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'handyman-services') ),
						"group" => esc_html__('Query', 'handyman-services'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", 'handyman-services'),
						"description" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'handyman-services') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", 'handyman-services'),
						"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'handyman-services') ),
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", 'handyman-services'),
						"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'handyman-services') ),
						"group" => esc_html__('Captions', 'handyman-services'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					handyman_services_vc_width(),
					handyman_services_vc_height(),
					handyman_services_get_vc_param('margin_top'),
					handyman_services_get_vc_param('margin_bottom'),
					handyman_services_get_vc_param('margin_left'),
					handyman_services_get_vc_param('margin_right'),
					handyman_services_get_vc_param('id'),
					handyman_services_get_vc_param('class'),
					handyman_services_get_vc_param('animation'),
					handyman_services_get_vc_param('css')
				),
				'default_content' => '
					[trx_services_item title="' . esc_html__( 'Service item 1', 'handyman-services' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 2', 'handyman-services' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 3', 'handyman-services' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 4', 'handyman-services' ) . '"][/trx_services_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_services_item",
				"name" => esc_html__("Services item", 'handyman-services'),
				"description" => wp_kses_data( __("Custom services item - all data pull out from shortcode parameters", 'handyman-services') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_services_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_services_item',
				"as_child" => array('only' => 'trx_services'),
				"as_parent" => array('except' => 'trx_services'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'handyman-services'),
						"description" => wp_kses_data( __("Item's title", 'handyman-services') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", 'handyman-services'),
						"description" => wp_kses_data( __("Select icon for the item from Fontello icons set", 'handyman-services') ),
						"admin_label" => true,
						"class" => "",
						"value" => handyman_services_get_sc_param('icons'),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", 'handyman-services'),
						"description" => wp_kses_data( __("Item's image (if icon is empty)", 'handyman-services') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'handyman-services'),
						"description" => wp_kses_data( __("Link on item's page", 'handyman-services') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", 'handyman-services'),
						"description" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'handyman-services') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					handyman_services_get_vc_param('id'),
					handyman_services_get_vc_param('class'),
					handyman_services_get_vc_param('animation'),
					handyman_services_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Services extends HANDYMAN_SERVICES_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Services_Item extends HANDYMAN_SERVICES_VC_ShortCodeCollection {}

	}
}
?>
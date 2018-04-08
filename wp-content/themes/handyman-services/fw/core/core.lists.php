<?php
/**
 * Handyman Services Framework: return lists
 *
 * @package handyman_services
 * @since handyman_services 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'handyman_services_get_list_styles' ) ) {
	function handyman_services_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'handyman-services'), $i);
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'handyman_services_get_list_margins' ) ) {
	function handyman_services_get_list_margins($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'handyman-services'),
				'tiny'		=> esc_html__('Tiny',		'handyman-services'),
				'small'		=> esc_html__('Small',		'handyman-services'),
				'medium'	=> esc_html__('Medium',		'handyman-services'),
				'large'		=> esc_html__('Large',		'handyman-services'),
				'huge'		=> esc_html__('Huge',		'handyman-services'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'handyman-services'),
				'small-'	=> esc_html__('Small (negative)',	'handyman-services'),
				'medium-'	=> esc_html__('Medium (negative)',	'handyman-services'),
				'large-'	=> esc_html__('Large (negative)',	'handyman-services'),
				'huge-'		=> esc_html__('Huge (negative)',	'handyman-services')
				);
			$list = apply_filters('handyman_services_filter_list_margins', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'handyman_services_get_list_line_styles' ) ) {
	function handyman_services_get_list_line_styles($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'handyman-services'),
				'dashed'=> esc_html__('Dashed', 'handyman-services'),
				'dotted'=> esc_html__('Dotted', 'handyman-services'),
				'double'=> esc_html__('Double', 'handyman-services'),
				'image'	=> esc_html__('Image', 'handyman-services')
				);
			$list = apply_filters('handyman_services_filter_list_line_styles', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'handyman_services_get_list_animations' ) ) {
	function handyman_services_get_list_animations($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'handyman-services'),
				'bounce'		=> esc_html__('Bounce',		'handyman-services'),
				'elastic'		=> esc_html__('Elastic',	'handyman-services'),
				'flash'			=> esc_html__('Flash',		'handyman-services'),
				'flip'			=> esc_html__('Flip',		'handyman-services'),
				'pulse'			=> esc_html__('Pulse',		'handyman-services'),
				'rubberBand'	=> esc_html__('Rubber Band','handyman-services'),
				'shake'			=> esc_html__('Shake',		'handyman-services'),
				'swing'			=> esc_html__('Swing',		'handyman-services'),
				'tada'			=> esc_html__('Tada',		'handyman-services'),
				'wobble'		=> esc_html__('Wobble',		'handyman-services')
				);
			$list = apply_filters('handyman_services_filter_list_animations', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'handyman_services_get_list_animations_in' ) ) {
	function handyman_services_get_list_animations_in($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'handyman-services'),
				'bounceIn'			=> esc_html__('Bounce In',			'handyman-services'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'handyman-services'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'handyman-services'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'handyman-services'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'handyman-services'),
				'elastic'			=> esc_html__('Elastic In',			'handyman-services'),
				'fadeIn'			=> esc_html__('Fade In',			'handyman-services'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'handyman-services'),
				'fadeInUpSmall'		=> esc_html__('Fade In Up Small',	'handyman-services'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'handyman-services'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'handyman-services'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'handyman-services'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'handyman-services'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'handyman-services'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'handyman-services'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'handyman-services'),
				'flipInX'			=> esc_html__('Flip In X',			'handyman-services'),
				'flipInY'			=> esc_html__('Flip In Y',			'handyman-services'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'handyman-services'),
				'rotateIn'			=> esc_html__('Rotate In',			'handyman-services'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','handyman-services'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'handyman-services'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'handyman-services'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','handyman-services'),
				'rollIn'			=> esc_html__('Roll In',			'handyman-services'),
				'slideInUp'			=> esc_html__('Slide In Up',		'handyman-services'),
				'slideInDown'		=> esc_html__('Slide In Down',		'handyman-services'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'handyman-services'),
				'slideInRight'		=> esc_html__('Slide In Right',		'handyman-services'),
				'wipeInLeftTop'		=> esc_html__('Wipe In Left Top',	'handyman-services'),
				'zoomIn'			=> esc_html__('Zoom In',			'handyman-services'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'handyman-services'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'handyman-services'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'handyman-services'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'handyman-services')
				);
			$list = apply_filters('handyman_services_filter_list_animations_in', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'handyman_services_get_list_animations_out' ) ) {
	function handyman_services_get_list_animations_out($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'handyman-services'),
				'bounceOut'			=> esc_html__('Bounce Out',			'handyman-services'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'handyman-services'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',	'handyman-services'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',	'handyman-services'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'handyman-services'),
				'fadeOut'			=> esc_html__('Fade Out',			'handyman-services'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',		'handyman-services'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',	'handyman-services'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'handyman-services'),
				'fadeOutDownSmall'	=> esc_html__('Fade Out Down Small','handyman-services'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'handyman-services'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'handyman-services'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'handyman-services'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'handyman-services'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'handyman-services'),
				'flipOutX'			=> esc_html__('Flip Out X',			'handyman-services'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'handyman-services'),
				'hinge'				=> esc_html__('Hinge Out',			'handyman-services'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',	'handyman-services'),
				'rotateOut'			=> esc_html__('Rotate Out',			'handyman-services'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left','handyman-services'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right','handyman-services'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',	'handyman-services'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right','handyman-services'),
				'rollOut'			=> esc_html__('Roll Out',			'handyman-services'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'handyman-services'),
				'slideOutDown'		=> esc_html__('Slide Out Down',		'handyman-services'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',		'handyman-services'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'handyman-services'),
				'zoomOut'			=> esc_html__('Zoom Out',			'handyman-services'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'handyman-services'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',		'handyman-services'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',		'handyman-services'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',		'handyman-services')
				);
			$list = apply_filters('handyman_services_filter_list_animations_out', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('handyman_services_get_animation_classes')) {
	function handyman_services_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return handyman_services_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!handyman_services_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of the main menu hover effects
if ( !function_exists( 'handyman_services_get_list_menu_hovers' ) ) {
	function handyman_services_get_list_menu_hovers($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_menu_hovers'))=='') {
			$list = array(
				'fade'			=> esc_html__('Fade',		'handyman-services'),
				'slide_line'	=> esc_html__('Slide Line',	'handyman-services'),
				'slide_box'		=> esc_html__('Slide Box',	'handyman-services'),
				'zoom_line'		=> esc_html__('Zoom Line',	'handyman-services'),
				'path_line'		=> esc_html__('Path Line',	'handyman-services'),
				'roll_down'		=> esc_html__('Roll Down',	'handyman-services'),
				'color_line'	=> esc_html__('Color Line',	'handyman-services'),
				);
			$list = apply_filters('handyman_services_filter_list_menu_hovers', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_menu_hovers', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the button's hover effects
if ( !function_exists( 'handyman_services_get_list_button_hovers' ) ) {
	function handyman_services_get_list_button_hovers($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_button_hovers'))=='') {
			$list = array(
				'default'		=> esc_html__('Default',			'handyman-services'),
				'fade'			=> esc_html__('Fade',				'handyman-services'),
				'slide_left'	=> esc_html__('Slide from Left',	'handyman-services'),
				'slide_top'		=> esc_html__('Slide from Top',		'handyman-services'),
				'arrow'			=> esc_html__('Arrow',				'handyman-services'),
				);
			$list = apply_filters('handyman_services_filter_list_button_hovers', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_button_hovers', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the input field's hover effects
if ( !function_exists( 'handyman_services_get_list_input_hovers' ) ) {
	function handyman_services_get_list_input_hovers($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_input_hovers'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'handyman-services'),
				'accent'	=> esc_html__('Accented',	'handyman-services'),
				'path'		=> esc_html__('Path',		'handyman-services'),
				'jump'		=> esc_html__('Jump',		'handyman-services'),
				'underline'	=> esc_html__('Underline',	'handyman-services'),
				'iconed'	=> esc_html__('Iconed',		'handyman-services'),
				);
			$list = apply_filters('handyman_services_filter_list_input_hovers', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_input_hovers', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the search field's styles
if ( !function_exists( 'handyman_services_get_list_search_styles' ) ) {
	function handyman_services_get_list_search_styles($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_search_styles'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'handyman-services'),
				'fullscreen'=> esc_html__('Fullscreen',	'handyman-services'),
				'slide'		=> esc_html__('Slide',		'handyman-services'),
				'expand'	=> esc_html__('Expand',		'handyman-services'),
				);
			$list = apply_filters('handyman_services_filter_list_search_styles', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_search_styles', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of categories
if ( !function_exists( 'handyman_services_get_list_categories' ) ) {
	function handyman_services_get_list_categories($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'handyman_services_get_list_terms' ) ) {
	function handyman_services_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = handyman_services_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = handyman_services_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'handyman_services_get_list_posts_types' ) ) {
	function handyman_services_get_list_posts_types($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_posts_types'))=='') {
			// Return only theme inheritance supported post types
			$list = apply_filters('handyman_services_filter_list_post_types', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'handyman_services_get_list_posts' ) ) {
	function handyman_services_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = handyman_services_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'handyman-services');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set($hash, $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'handyman_services_get_list_pages' ) ) {
	function handyman_services_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return handyman_services_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'handyman_services_get_list_users' ) ) {
	function handyman_services_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = handyman_services_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'handyman-services');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_users', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'handyman_services_get_list_sliders' ) ) {
	function handyman_services_get_list_sliders($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'handyman-services')
			);
			$list = apply_filters('handyman_services_filter_list_sliders', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'handyman_services_get_list_slider_controls' ) ) {
	function handyman_services_get_list_slider_controls($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'handyman-services'),
				'side'		=> esc_html__('Side', 'handyman-services'),
				'bottom'	=> esc_html__('Bottom', 'handyman-services'),
				'pagination'=> esc_html__('Pagination', 'handyman-services')
				);
			$list = apply_filters('handyman_services_filter_list_slider_controls', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'handyman_services_get_slider_controls_classes' ) ) {
	function handyman_services_get_slider_controls_classes($controls) {
		if (handyman_services_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'handyman_services_get_list_popup_engines' ) ) {
	function handyman_services_get_list_popup_engines($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'handyman-services'),
				"magnific"	=> esc_html__("Magnific popup", 'handyman-services')
				);
			$list = apply_filters('handyman_services_filter_list_popup_engines', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_menus' ) ) {
	function handyman_services_get_list_menus($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'handyman-services');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'handyman_services_get_list_sidebars' ) ) {
	function handyman_services_get_list_sidebars($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_sidebars'))=='') {
			if (($list = handyman_services_storage_get('registered_sidebars'))=='') $list = array();
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'handyman_services_get_list_sidebars_positions' ) ) {
	function handyman_services_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'handyman-services'),
				'left'  => esc_html__('Left',  'handyman-services'),
				'right' => esc_html__('Right', 'handyman-services')
				);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'handyman_services_get_sidebar_class' ) ) {
	function handyman_services_get_sidebar_class() {
		$sb_main = handyman_services_get_custom_option('show_sidebar_main');
		$sb_outer = handyman_services_get_custom_option('show_sidebar_outer');
		return (handyman_services_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (handyman_services_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_body_styles' ) ) {
	function handyman_services_get_list_body_styles($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'handyman-services'),
				'wide'	=> esc_html__('Wide',		'handyman-services')
				);
			if (handyman_services_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'handyman-services');
				$list['fullscreen']	= esc_html__('Fullscreen',	'handyman-services');
			}
			$list = apply_filters('handyman_services_filter_list_body_styles', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return templates list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_templates' ) ) {
	function handyman_services_get_list_templates($mode='') {
		if (($list = handyman_services_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = handyman_services_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: handyman_services_strtoproper($v['layout'])
										);
				}
			}
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_templates_blog' ) ) {
	function handyman_services_get_list_templates_blog($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_templates_blog'))=='') {
			$list = handyman_services_get_list_templates('blog');
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_templates_blogger' ) ) {
	function handyman_services_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_templates_blogger'))=='') {
			$list = handyman_services_array_merge(handyman_services_get_list_templates('blogger'), handyman_services_get_list_templates('blog'));
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_templates_single' ) ) {
	function handyman_services_get_list_templates_single($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_templates_single'))=='') {
			$list = handyman_services_get_list_templates('single');
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_templates_header' ) ) {
	function handyman_services_get_list_templates_header($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_templates_header'))=='') {
			$list = handyman_services_get_list_templates('header');
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_templates_forms' ) ) {
	function handyman_services_get_list_templates_forms($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_templates_forms'))=='') {
			$list = handyman_services_get_list_templates('forms');
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_article_styles' ) ) {
	function handyman_services_get_list_article_styles($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'handyman-services'),
				"stretch" => esc_html__('Stretch', 'handyman-services')
				);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_post_formats_filters' ) ) {
	function handyman_services_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'handyman-services'),
				"thumbs"  => esc_html__('With thumbs', 'handyman-services'),
				"reviews" => esc_html__('With reviews', 'handyman-services'),
				"video"   => esc_html__('With videos', 'handyman-services'),
				"audio"   => esc_html__('With audios', 'handyman-services'),
				"gallery" => esc_html__('With galleries', 'handyman-services')
				);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_portfolio_filters' ) ) {
	function handyman_services_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'handyman-services'),
				"tags"		=> esc_html__('Tags', 'handyman-services'),
				"categories"=> esc_html__('Categories', 'handyman-services')
				);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_hovers' ) ) {
	function handyman_services_get_list_hovers($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'handyman-services');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'handyman-services');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'handyman-services');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'handyman-services');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'handyman-services');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'handyman-services');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'handyman-services');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'handyman-services');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'handyman-services');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'handyman-services');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'handyman-services');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'handyman-services');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'handyman-services');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'handyman-services');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'handyman-services');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'handyman-services');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'handyman-services');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'handyman-services');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'handyman-services');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'handyman-services');
			$list['square effect1']  = esc_html__('Square Effect 1',  'handyman-services');
			$list['square effect2']  = esc_html__('Square Effect 2',  'handyman-services');
			$list['square effect3']  = esc_html__('Square Effect 3',  'handyman-services');
			$list['square effect5']  = esc_html__('Square Effect 5',  'handyman-services');
			$list['square effect6']  = esc_html__('Square Effect 6',  'handyman-services');
			$list['square effect7']  = esc_html__('Square Effect 7',  'handyman-services');
			$list['square effect8']  = esc_html__('Square Effect 8',  'handyman-services');
			$list['square effect9']  = esc_html__('Square Effect 9',  'handyman-services');
			$list['square effect10'] = esc_html__('Square Effect 10',  'handyman-services');
			$list['square effect11'] = esc_html__('Square Effect 11',  'handyman-services');
			$list['square effect12'] = esc_html__('Square Effect 12',  'handyman-services');
			$list['square effect13'] = esc_html__('Square Effect 13',  'handyman-services');
			$list['square effect14'] = esc_html__('Square Effect 14',  'handyman-services');
			$list['square effect15'] = esc_html__('Square Effect 15',  'handyman-services');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'handyman-services');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'handyman-services');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'handyman-services');
			$list['square effect_more']  = esc_html__('Square Effect More',  'handyman-services');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'handyman-services');
			$list['square effect_pull']  = esc_html__('Square Effect Pull',  'handyman-services');
			$list['square effect_slide'] = esc_html__('Square Effect Slide', 'handyman-services');
			$list['square effect_border'] = esc_html__('Square Effect Border', 'handyman-services');
			$list = apply_filters('handyman_services_filter_portfolio_hovers', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'handyman_services_get_list_blog_counters' ) ) {
	function handyman_services_get_list_blog_counters($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'handyman-services'),
				'likes'		=> esc_html__('Likes', 'handyman-services'),
				'rating'	=> esc_html__('Rating', 'handyman-services'),
				'comments'	=> esc_html__('Comments', 'handyman-services')
				);
			$list = apply_filters('handyman_services_filter_list_blog_counters', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'handyman_services_get_list_alter_sizes' ) ) {
	function handyman_services_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'handyman-services'),
					'1_2' => esc_html__('1x2', 'handyman-services'),
					'2_1' => esc_html__('2x1', 'handyman-services'),
					'2_2' => esc_html__('2x2', 'handyman-services'),
					'1_3' => esc_html__('1x3', 'handyman-services'),
					'2_3' => esc_html__('2x3', 'handyman-services'),
					'3_1' => esc_html__('3x1', 'handyman-services'),
					'3_2' => esc_html__('3x2', 'handyman-services'),
					'3_3' => esc_html__('3x3', 'handyman-services')
					);
			$list = apply_filters('handyman_services_filter_portfolio_alter_sizes', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_hovers_directions' ) ) {
	function handyman_services_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'handyman-services'),
				'right_to_left' => esc_html__('Right to Left',  'handyman-services'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'handyman-services'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'handyman-services'),
				'scale_up'      => esc_html__('Scale Up',  'handyman-services'),
				'scale_down'    => esc_html__('Scale Down',  'handyman-services'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'handyman-services'),
				'from_left_and_right' => esc_html__('From Left and Right',  'handyman-services'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'handyman-services')
			);
			$list = apply_filters('handyman_services_filter_portfolio_hovers_directions', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'handyman_services_get_list_label_positions' ) ) {
	function handyman_services_get_list_label_positions($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'handyman-services'),
				'bottom'	=> esc_html__('Bottom',		'handyman-services'),
				'left'		=> esc_html__('Left',		'handyman-services'),
				'over'		=> esc_html__('Over',		'handyman-services')
			);
			$list = apply_filters('handyman_services_filter_label_positions', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'handyman_services_get_list_bg_image_positions' ) ) {
	function handyman_services_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'handyman-services'),
				'center top'   => esc_html__("Center Top", 'handyman-services'),
				'right top'    => esc_html__("Right Top", 'handyman-services'),
				'left center'  => esc_html__("Left Center", 'handyman-services'),
				'center center'=> esc_html__("Center Center", 'handyman-services'),
				'right center' => esc_html__("Right Center", 'handyman-services'),
				'left bottom'  => esc_html__("Left Bottom", 'handyman-services'),
				'center bottom'=> esc_html__("Center Bottom", 'handyman-services'),
				'right bottom' => esc_html__("Right Bottom", 'handyman-services')
			);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'handyman_services_get_list_bg_image_repeats' ) ) {
	function handyman_services_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'handyman-services'),
				'repeat-x'	=> esc_html__('Repeat X', 'handyman-services'),
				'repeat-y'	=> esc_html__('Repeat Y', 'handyman-services'),
				'no-repeat'	=> esc_html__('No Repeat', 'handyman-services')
			);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'handyman_services_get_list_bg_image_attachments' ) ) {
	function handyman_services_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'handyman-services'),
				'fixed'		=> esc_html__('Fixed', 'handyman-services'),
				'local'		=> esc_html__('Local', 'handyman-services')
			);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'handyman_services_get_list_bg_tints' ) ) {
	function handyman_services_get_list_bg_tints($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'handyman-services'),
				'light'	=> esc_html__('Light', 'handyman-services'),
				'dark'	=> esc_html__('Dark', 'handyman-services')
			);
			$list = apply_filters('handyman_services_filter_bg_tints', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_field_types' ) ) {
	function handyman_services_get_list_field_types($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'handyman-services'),
				'textarea' => esc_html__('Text Area','handyman-services'),
				'password' => esc_html__('Password',  'handyman-services'),
				'radio'    => esc_html__('Radio',  'handyman-services'),
				'checkbox' => esc_html__('Checkbox',  'handyman-services'),
				'select'   => esc_html__('Select',  'handyman-services'),
				'date'     => esc_html__('Date','handyman-services'),
				'time'     => esc_html__('Time','handyman-services'),
				'button'   => esc_html__('Button','handyman-services')
			);
			$list = apply_filters('handyman_services_filter_field_types', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'handyman_services_get_list_googlemap_styles' ) ) {
	function handyman_services_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'handyman-services')
			);
			$list = apply_filters('handyman_services_filter_googlemap_styles', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'handyman_services_get_list_icons' ) ) {
	function handyman_services_get_list_icons($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_icons'))=='') {
			$list = handyman_services_parse_icons_classes(handyman_services_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? array_merge(array('inherit'), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'handyman_services_get_list_socials' ) ) {
	function handyman_services_get_list_socials($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_socials'))=='') {
			$list = handyman_services_get_list_files("images/socials", "png");
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'handyman_services_get_list_yesno' ) ) {
	function handyman_services_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'handyman-services'),
			'no'  => esc_html__("No", 'handyman-services')
		);
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'handyman_services_get_list_onoff' ) ) {
	function handyman_services_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'handyman-services'),
			"off" => esc_html__("Off", 'handyman-services')
		);
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'handyman_services_get_list_showhide' ) ) {
	function handyman_services_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'handyman-services'),
			"hide" => esc_html__("Hide", 'handyman-services')
		);
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'handyman_services_get_list_orderings' ) ) {
	function handyman_services_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'handyman-services'),
			"desc" => esc_html__("Descending", 'handyman-services')
		);
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'handyman_services_get_list_directions' ) ) {
	function handyman_services_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'handyman-services'),
			"vertical" => esc_html__("Vertical", 'handyman-services')
		);
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'handyman_services_get_list_shapes' ) ) {
	function handyman_services_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'handyman-services'),
			"square" => esc_html__("Square", 'handyman-services')
		);
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'handyman_services_get_list_sizes' ) ) {
	function handyman_services_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'handyman-services'),
			"small"  => esc_html__("Small", 'handyman-services'),
			"medium" => esc_html__("Medium", 'handyman-services'),
			"large"  => esc_html__("Large", 'handyman-services')
		);
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'handyman_services_get_list_controls' ) ) {
	function handyman_services_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'handyman-services'),
			"side" => esc_html__("Side", 'handyman-services'),
			"bottom" => esc_html__("Bottom", 'handyman-services')
		);
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'handyman_services_get_list_floats' ) ) {
	function handyman_services_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'handyman-services'),
			"left" => esc_html__("Float Left", 'handyman-services'),
			"right" => esc_html__("Float Right", 'handyman-services')
		);
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'handyman_services_get_list_alignments' ) ) {
	function handyman_services_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'handyman-services'),
			"left" => esc_html__("Left", 'handyman-services'),
			"center" => esc_html__("Center", 'handyman-services'),
			"right" => esc_html__("Right", 'handyman-services')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'handyman-services');
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'handyman_services_get_list_hpos' ) ) {
	function handyman_services_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'handyman-services');
		if ($center) $list['center'] = esc_html__("Center", 'handyman-services');
		$list['right'] = esc_html__("Right", 'handyman-services');
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'handyman_services_get_list_vpos' ) ) {
	function handyman_services_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'handyman-services');
		if ($center) $list['center'] = esc_html__("Center", 'handyman-services');
		$list['bottom'] = esc_html__("Bottom", 'handyman-services');
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'handyman_services_get_list_sortings' ) ) {
	function handyman_services_get_list_sortings($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'handyman-services'),
				"title" => esc_html__("Alphabetically", 'handyman-services'),
				"views" => esc_html__("Popular (views count)", 'handyman-services'),
				"comments" => esc_html__("Most commented (comments count)", 'handyman-services'),
				"author_rating" => esc_html__("Author rating", 'handyman-services'),
				"users_rating" => esc_html__("Visitors (users) rating", 'handyman-services'),
				"random" => esc_html__("Random", 'handyman-services')
			);
			$list = apply_filters('handyman_services_filter_list_sortings', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'handyman_services_get_list_columns' ) ) {
	function handyman_services_get_list_columns($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'handyman-services'),
				"1_1" => esc_html__("100%", 'handyman-services'),
				"1_2" => esc_html__("1/2", 'handyman-services'),
				"1_3" => esc_html__("1/3", 'handyman-services'),
				"2_3" => esc_html__("2/3", 'handyman-services'),
				"1_4" => esc_html__("1/4", 'handyman-services'),
				"3_4" => esc_html__("3/4", 'handyman-services'),
				"1_5" => esc_html__("1/5", 'handyman-services'),
				"2_5" => esc_html__("2/5", 'handyman-services'),
				"3_5" => esc_html__("3/5", 'handyman-services'),
				"4_5" => esc_html__("4/5", 'handyman-services'),
				"1_6" => esc_html__("1/6", 'handyman-services'),
				"5_6" => esc_html__("5/6", 'handyman-services'),
				"1_7" => esc_html__("1/7", 'handyman-services'),
				"2_7" => esc_html__("2/7", 'handyman-services'),
				"3_7" => esc_html__("3/7", 'handyman-services'),
				"4_7" => esc_html__("4/7", 'handyman-services'),
				"5_7" => esc_html__("5/7", 'handyman-services'),
				"6_7" => esc_html__("6/7", 'handyman-services'),
				"1_8" => esc_html__("1/8", 'handyman-services'),
				"3_8" => esc_html__("3/8", 'handyman-services'),
				"5_8" => esc_html__("5/8", 'handyman-services'),
				"7_8" => esc_html__("7/8", 'handyman-services'),
				"1_9" => esc_html__("1/9", 'handyman-services'),
				"2_9" => esc_html__("2/9", 'handyman-services'),
				"4_9" => esc_html__("4/9", 'handyman-services'),
				"5_9" => esc_html__("5/9", 'handyman-services'),
				"7_9" => esc_html__("7/9", 'handyman-services'),
				"8_9" => esc_html__("8/9", 'handyman-services'),
				"1_10"=> esc_html__("1/10", 'handyman-services'),
				"3_10"=> esc_html__("3/10", 'handyman-services'),
				"7_10"=> esc_html__("7/10", 'handyman-services'),
				"9_10"=> esc_html__("9/10", 'handyman-services'),
				"1_11"=> esc_html__("1/11", 'handyman-services'),
				"2_11"=> esc_html__("2/11", 'handyman-services'),
				"3_11"=> esc_html__("3/11", 'handyman-services'),
				"4_11"=> esc_html__("4/11", 'handyman-services'),
				"5_11"=> esc_html__("5/11", 'handyman-services'),
				"6_11"=> esc_html__("6/11", 'handyman-services'),
				"7_11"=> esc_html__("7/11", 'handyman-services'),
				"8_11"=> esc_html__("8/11", 'handyman-services'),
				"9_11"=> esc_html__("9/11", 'handyman-services'),
				"10_11"=> esc_html__("10/11", 'handyman-services'),
				"1_12"=> esc_html__("1/12", 'handyman-services'),
				"5_12"=> esc_html__("5/12", 'handyman-services'),
				"7_12"=> esc_html__("7/12", 'handyman-services'),
				"10_12"=> esc_html__("10/12", 'handyman-services'),
				"11_12"=> esc_html__("11/12", 'handyman-services')
			);
			$list = apply_filters('handyman_services_filter_list_columns', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'handyman_services_get_list_dedicated_locations' ) ) {
	function handyman_services_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'handyman-services'),
				"center"  => esc_html__('Above the text of the post', 'handyman-services'),
				"left"    => esc_html__('To the left the text of the post', 'handyman-services'),
				"right"   => esc_html__('To the right the text of the post', 'handyman-services'),
				"alter"   => esc_html__('Alternates for each post', 'handyman-services')
			);
			$list = apply_filters('handyman_services_filter_list_dedicated_locations', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'handyman_services_get_post_format_name' ) ) {
	function handyman_services_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'handyman-services') : esc_html__('galleries', 'handyman-services');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'handyman-services') : esc_html__('videos', 'handyman-services');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'handyman-services') : esc_html__('audios', 'handyman-services');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'handyman-services') : esc_html__('images', 'handyman-services');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'handyman-services') : esc_html__('quotes', 'handyman-services');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'handyman-services') : esc_html__('links', 'handyman-services');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'handyman-services') : esc_html__('statuses', 'handyman-services');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'handyman-services') : esc_html__('asides', 'handyman-services');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'handyman-services') : esc_html__('chats', 'handyman-services');
		else						$name = $single ? esc_html__('standard', 'handyman-services') : esc_html__('standards', 'handyman-services');
		return apply_filters('handyman_services_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'handyman_services_get_post_format_icon' ) ) {
	function handyman_services_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('handyman_services_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'handyman_services_get_list_fonts_styles' ) ) {
	function handyman_services_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','handyman-services'),
				'u' => esc_html__('U', 'handyman-services')
			);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'handyman_services_get_list_fonts' ) ) {
	function handyman_services_get_list_fonts($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_fonts'))=='') {
			$list = array();
			$list = handyman_services_array_merge($list, handyman_services_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>handyman_services_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = handyman_services_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('handyman_services_filter_list_fonts', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'handyman_services_get_list_font_faces' ) ) {
	function handyman_services_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
        $fonts = handyman_services_storage_get('required_custom_fonts');
		$list = array();
        if (is_array($fonts)) {
            		foreach ($fonts as $font) {
               				if (($url = handyman_services_get_file_url('css/font-face/'.trim($font).'/stylesheet.css'))!='') {
                    					$list[sprintf(esc_html__('%s (uploaded font)', 'handyman-services'), $font)] = array('css' => $url);
				}
			}
		}
		return $list;
	}
}
?>
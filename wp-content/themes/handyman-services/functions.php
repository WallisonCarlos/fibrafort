<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Prepare demo data
$handyman_services_demo_data_url = esc_url('http://handyman-services.ancorathemes.com/demo/');


// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'handyman_services_theme_setup' ) ) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_theme_setup', 1 );
	function handyman_services_theme_setup() {

		// Register theme menus
		add_filter( 'handyman_services_filter_add_theme_menus',		'handyman_services_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'handyman_services_filter_add_theme_sidebars',	'handyman_services_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'handyman_services_filter_importer_options',		'handyman_services_set_importer_options' );

		// Add theme required plugins
		add_filter( 'handyman_services_filter_required_plugins',		'handyman_services_add_required_plugins' );
		
		// Add preloader styles
		add_filter('handyman_services_filter_add_styles_inline',		'handyman_services_head_add_page_preloader_styles');

		// Init theme after WP is created
		add_action( 'wp',									'handyman_services_core_init_theme' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 							'handyman_services_body_classes' );

		// Add data to the head and to the beginning of the body
		add_action('wp_head',								'handyman_services_head_add_page_meta', 1);
		add_action('before',								'handyman_services_body_add_gtm');
		add_action('before',								'handyman_services_body_add_toc');
		add_action('before',								'handyman_services_body_add_page_preloader');

		// Add data to the footer (priority 1, because priority 2 used for localize scripts)
		add_action('wp_footer',								'handyman_services_footer_add_views_counter', 1);
		add_action('wp_footer',								'handyman_services_footer_add_login_register', 1);
		add_action('wp_footer',								'handyman_services_footer_add_theme_customizer', 1);
		add_action('wp_footer',								'handyman_services_footer_add_scroll_to_top', 1);
		add_action('wp_footer',								'handyman_services_footer_add_custom_html', 1);
		add_action('wp_footer',								'handyman_services_footer_add_gtm2', 1);

		// Set list of the theme required plugins
		handyman_services_storage_set('required_plugins', array(
			'booked',
			'essgrids',
			'revslider',
			'trx_utils',
			'visual_composer',
			'woocommerce',
			)
		);
        // Set list of the theme required custom fonts from folder /css/font-faces
        // Attention! Font's folder must have name equal to the font's name
        handyman_services_storage_set('required_custom_fonts', array(
            		'Amadeus'
            	)
        );
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'handyman_services_add_theme_menus' ) ) {
	function handyman_services_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'handyman_services_add_theme_sidebars' ) ) {
	function handyman_services_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'handyman-services' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'handyman-services' )
			);
			if (function_exists('handyman_services_exists_woocommerce') && handyman_services_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'handyman-services' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'handyman_services_add_required_plugins' ) ) {
	function handyman_services_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__( 'Handyman Services Utilities','handyman-services' ),
			'version'	=> '2.9',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> handyman_services_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		return $plugins;
	}
}


// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( !function_exists( 'handyman_services_set_importer_options' ) ) {
	function handyman_services_set_importer_options($options=array()) {
		if (is_array($options)) {
			$options['debug'] = handyman_services_get_theme_option('debug_mode')=='yes';

			// Main demo
			global $handyman_services_demo_data_url;
			$options['files']['default'] = array(
				'title'				=> esc_html__('Basekit demo', 'handyman-services'),
				'file_with_posts'	=> $handyman_services_demo_data_url . 'default/posts.txt',
				'file_with_users'	=> $handyman_services_demo_data_url . 'default/users.txt',
				'file_with_mods'	=> $handyman_services_demo_data_url . 'default/theme_mods.txt',
				'file_with_options'	=> $handyman_services_demo_data_url . 'default/theme_options.txt',
				'file_with_templates'=>$handyman_services_demo_data_url . 'default/templates_options.txt',
				'file_with_widgets'	=> $handyman_services_demo_data_url . 'default/widgets.txt',
				'file_with_revsliders' => array(
					$handyman_services_demo_data_url . 'default/revsliders/handyman-slider.zip'
				),
				'file_with_attachments' => array(),
				'attachments_by_parts'	=> true,
				'domain_dev'	=> esc_url('handyman-services.dv.ancorathemes.com'),
				'domain_demo'	=> esc_url('handyman-services.ancorathemes.com')
			);
			for ($i=1; $i<=8; $i++) {
				$options['files']['default']['file_with_attachments'][] = $handyman_services_demo_data_url . 'default/uploads/uploads.' . sprintf('%03u', $i);
			}
		}
		return $options;
	}
}


// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('handyman_services_body_classes') ) {
	function handyman_services_body_classes( $classes ) {

		$classes[] = 'handyman_services_body';
		$classes[] = 'body_style_' . trim(handyman_services_get_custom_option('body_style'));
		$classes[] = 'body_' . (handyman_services_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'article_style_' . trim(handyman_services_get_custom_option('article_style'));
		
		$blog_style = handyman_services_get_custom_option(is_singular() && !handyman_services_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(handyman_services_get_template_name($blog_style));
		
		$body_scheme = handyman_services_get_custom_option('body_scheme');
		if (empty($body_scheme)  || handyman_services_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = handyman_services_get_custom_option('top_panel_position');
		if (!handyman_services_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = handyman_services_get_sidebar_class();

		if (handyman_services_get_custom_option('show_video_bg')=='yes' && (handyman_services_get_custom_option('video_bg_youtube_code')!='' || handyman_services_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (!handyman_services_param_is_off(handyman_services_get_theme_option('page_preloader')))
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page meta to the head
if (!function_exists('handyman_services_head_add_page_meta')) {
	function handyman_services_head_add_page_meta() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1<?php if (handyman_services_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
		<meta name="format-detection" content="telephone=no">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}

// Add page preloader styles to the head
if (!function_exists('handyman_services_head_add_page_preloader_styles')) {
	function handyman_services_head_add_page_preloader_styles($css) {
		if (($preloader=handyman_services_get_theme_option('page_preloader'))!='none') {
			$image = handyman_services_get_theme_option('page_preloader_image');
			$bg_clr = handyman_services_get_scheme_color('bg_color');
			$link_clr = handyman_services_get_scheme_color('text_link');
			$css .= '
				#page_preloader {
					background-color: '. esc_attr($bg_clr) . ';'
					. ($preloader=='custom' && $image
						? 'background-image:url('.esc_url($image).');'
						: ''
						)
				    . '
				}
				.preloader_wrap > div {
					background-color: '.esc_attr($link_clr).';
				}';
		}
		return $css;
	}
}

// Add gtm code to the beginning of the body 
if (!function_exists('handyman_services_body_add_gtm')) {
	function handyman_services_body_add_gtm() {
		echo force_balance_tags(handyman_services_get_custom_option('gtm_code'));
	}
}

// Add TOC anchors to the beginning of the body 
if (!function_exists('handyman_services_body_add_toc')) {
	function handyman_services_body_add_toc() {
		// Add TOC items 'Home' and "To top"
		if (handyman_services_get_custom_option('menu_toc_home')=='yes')
			echo trim(handyman_services_sc_anchor(array(
				'id' => "toc_home",
				'title' => esc_html__('Home', 'handyman-services'),
				'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'handyman-services'),
				'icon' => "icon-home",
				'separator' => "yes",
				'url' => esc_url(home_url('/'))
				)
			)); 
		if (handyman_services_get_custom_option('menu_toc_top')=='yes')
			echo trim(handyman_services_sc_anchor(array(
				'id' => "toc_top",
				'title' => esc_html__('To Top', 'handyman-services'),
				'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'handyman-services'),
				'icon' => "icon-double-up",
				'separator' => "yes")
				)); 
	}
}

// Add page preloader to the beginning of the body
if (!function_exists('handyman_services_body_add_page_preloader')) {
	function handyman_services_body_add_page_preloader() {
		if ( ($preloader=handyman_services_get_theme_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=handyman_services_get_theme_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				}
			?></div><?php
		}
	}
}


// Add data to the footer
//------------------------------------------------------------------------

// Add post/page views counter
if (!function_exists('handyman_services_footer_add_views_counter')) {
	function handyman_services_footer_add_views_counter() {
		// Post/Page views counter
		get_template_part(handyman_services_get_file_slug('templates/_parts/views-counter.php'));
	}
}

// Add Login/Register popups
if (!function_exists('handyman_services_footer_add_login_register')) {
	function handyman_services_footer_add_login_register() {
		if (handyman_services_get_theme_option('show_login')=='yes') {
			handyman_services_enqueue_popup();
			// Anyone can register ?
			if ( (int) get_option('users_can_register') > 0) {
				get_template_part(handyman_services_get_file_slug('templates/_parts/popup-register.php'));
			}
			get_template_part(handyman_services_get_file_slug('templates/_parts/popup-login.php'));
		}
	}
}

// Add theme customizer
if (!function_exists('handyman_services_footer_add_theme_customizer')) {
	function handyman_services_footer_add_theme_customizer() {
		// Front customizer
		if (handyman_services_get_custom_option('show_theme_customizer')=='yes') {
            require_once HANDYMAN_SERVICES_FW_PATH . 'core/core.customizer/front.customizer.php';
		}
	}
}

// Add scroll to top button
if (!function_exists('handyman_services_footer_add_scroll_to_top')) {
	function handyman_services_footer_add_scroll_to_top() {
		?><a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'handyman-services'); ?>"></a><?php
	}
}

// Add custom html
if (!function_exists('handyman_services_footer_add_custom_html')) {
	function handyman_services_footer_add_custom_html() {
		?><div class="custom_html_section"><?php
			echo force_balance_tags(handyman_services_get_custom_option('custom_code'));
		?></div><?php
	}
}

// Add gtm code
if (!function_exists('handyman_services_footer_add_gtm2')) {
	function handyman_services_footer_add_gtm2() {
		echo force_balance_tags(handyman_services_get_custom_option('gtm_code2'));
	}
}

function wpb_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}

add_filter( 'comment_form_fields', 'wpb_move_comment_field_to_bottom' );

// Include framework core files
//-------------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>
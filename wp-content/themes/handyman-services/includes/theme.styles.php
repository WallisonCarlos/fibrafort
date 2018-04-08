<?php
/**
 * Theme custom styles
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('handyman_services_action_theme_styles_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_action_theme_styles_theme_setup', 1 );
	function handyman_services_action_theme_styles_theme_setup() {
	
		// Add theme fonts in the used fonts list
		add_filter('handyman_services_filter_used_fonts',			'handyman_services_filter_theme_styles_used_fonts');
		// Add theme fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('handyman_services_filter_list_fonts',			'handyman_services_filter_theme_styles_list_fonts');

		// Add theme stylesheets
		add_action('handyman_services_action_add_styles',			'handyman_services_action_theme_styles_add_styles');
		// Add theme inline styles
		add_filter('handyman_services_filter_add_styles_inline',		'handyman_services_filter_theme_styles_add_styles_inline');

		// Add theme scripts
		add_action('handyman_services_action_add_scripts',			'handyman_services_action_theme_styles_add_scripts');
		// Add theme scripts inline
		add_filter('handyman_services_filter_localize_script',		'handyman_services_filter_theme_styles_localize_script');

		// Add theme less files into list for compilation
		add_filter('handyman_services_filter_compile_less',			'handyman_services_filter_theme_styles_compile_less');


		/* Color schemes
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		// Next settings are deprecated
		//bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		//bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Additional accented colors (if need)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		text_link		- links
		text_hover		- hover links
		
		// Inverse blocks
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Input colors - form fields
		input_text		- inactive text
		input_light		- placeholder text
		input_dark		- focused text
		input_bd_color	- inactive border
		input_bd_hover	- focused borde
		input_bg_color	- inactive background
		input_bg_hover	- focused background
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		// Next settings are deprecated
		//alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		handyman_services_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'handyman-services'),
			
			// Whole block border and background
			'bd_color'				=> '#eae3c9',       //
			'bg_color'				=> '#fefbef',       //

			// Headers, text and links colors
			'text'					=> '#5e594c',       //
			'text_light'			=> '#7e7a70',       //
			'text_dark'				=> '#443f33',       //
			'text_link'				=> '#fe6b38',       //
			'text_hover'			=> '#e7b320',       //

			// Inverse colors
			'inverse_text'			=> '#ffffff',       //
			'inverse_light'			=> '#fbf8d9',       //
			'inverse_dark'			=> '#aba393',       //
			'inverse_link'			=> '#e7b320',       //
			'inverse_hover'			=> '#e1d9a9',       //

			// Input fields
			'input_text'			=> '#8a8a8a',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#232a34',
			'input_bd_color'		=> '#1a1710',       //
			'input_bd_hover'		=> '#383326',       //
			'input_bg_color'		=> '#fdf5d7',       //
			'input_bg_hover'		=> '#b9b8b4',       //
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#fdfbec',       //
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#504037',       //
			'alter_link'			=> '#423d2f',       //
			'alter_hover'			=> '#e1d9a9',       //
			'alter_bd_color'		=> '#414141',       //
			'alter_bd_hover'		=> '#e75421',       //
			'alter_bg_color'		=> '#fef9e5',       //
			'alter_bg_hover'		=> '#302c21',       //
			)
		);

        // Add color schemes
        handyman_services_add_color_scheme('dark', array(

                'title'					=> esc_html__('Dark', 'handyman-services'),

                // Whole block border and background
                'bd_color'				=> '#eae3c9',       //
                'bg_color'				=> '#fefbef',       //

                // Headers, text and links colors
                'text'					=> '#5e594c',       //
                'text_light'			=> '#7e7a70',       //
                'text_dark'				=> '#fefcf3',       //
                'text_link'				=> '#fe6b38',       //
                'text_hover'			=> '#e7b320',       //

                // Inverse colors
                'inverse_text'			=> '#ffffff',       //
                'inverse_light'			=> '#423d2f',       // //
                'inverse_dark'			=> '#aba393',       //
                'inverse_link'			=> '#e7b320',       //
                'inverse_hover'			=> '#e1d9a9',       //

                // Input fields
                'input_text'			=> '#8a8a8a',
                'input_light'			=> '#acb4b6',
                'input_dark'			=> '#232a34',
                'input_bd_color'		=> '#1a1710',       //
                'input_bd_hover'		=> '#fefcf3',       //
                'input_bg_color'		=> '#fdf5d7',       //
                'input_bg_hover'		=> '#b9b8b4',       //

                // Alternative blocks (submenu items, etc.)
                'alter_text'			=> '#fdfbec',       //
                'alter_light'			=> '#acb4b6',
                'alter_dark'			=> '#504037',       //
                'alter_link'			=> '#423d2f',       //
                'alter_hover'			=> '#e1d9a9',       //
                'alter_bd_color'		=> '#414141',       //
                'alter_bd_hover'		=> '#e75421',       //
                'alter_bg_color'		=> '#fef9e5',       //
                'alter_bg_hover'		=> '#302c21',       //
            )
        );

        // Add color schemes
        handyman_services_add_color_scheme('red', array(

                'title'					=> esc_html__('Red', 'handyman-services'),

                // Whole block border and background
                'bd_color'				=> '#eae3c9',       //
                'bg_color'				=> '#fefbef',       //

                // Headers, text and links colors
                'text'					=> '#5e594c',       //
                'text_light'			=> '#7e7a70',       //
                'text_dark'				=> '#443f33',       //
                'text_link'				=> '#79bd8f',       //      //
                'text_hover'			=> '#ff6138',       //      //

                // Inverse colors
                'inverse_text'			=> '#ffffff',       //
                'inverse_light'			=> '#fbf8d9',       //
                'inverse_dark'			=> '#aba393',       //
                'inverse_link'			=> '#ff6138',       //  //
                'inverse_hover'			=> '#e1d9a9',       //

                // Input fields
                'input_text'			=> '#8a8a8a',
                'input_light'			=> '#acb4b6',
                'input_dark'			=> '#232a34',
                'input_bd_color'		=> '#1a1710',       //
                'input_bd_hover'		=> '#383326',       //
                'input_bg_color'		=> '#fdf5d7',       //
                'input_bg_hover'		=> '#b9b8b4',       //

                // Alternative blocks (submenu items, etc.)
                'alter_text'			=> '#fdfbec',       //
                'alter_light'			=> '#acb4b6',
                'alter_dark'			=> '#504037',       //
                'alter_link'			=> '#423d2f',       //
                'alter_hover'			=> '#e1d9a9',       //
                'alter_bd_color'		=> '#414141',       //
                'alter_bd_hover'		=> '#e75421',       //
                'alter_bg_color'		=> '#fef9e5',       //
                'alter_bg_hover'		=> '#302c21',       //
            )
        );


        // Add color schemes
        handyman_services_add_color_scheme('green', array(

                'title'					=> esc_html__('Green', 'handyman-services'),

                // Whole block border and background
                'bd_color'				=> '#eae3c9',       //
                'bg_color'				=> '#fefbef',       //

                // Headers, text and links colors
                'text'					=> '#5e594c',       //
                'text_light'			=> '#7e7a70',       //
                'text_dark'				=> '#443f33',       //
                'text_link'				=> '#468966',       //      //
                'text_hover'			=> '#ffb03b',       //      //

                // Inverse colors
                'inverse_text'			=> '#ffffff',       //
                'inverse_light'			=> '#fbf8d9',       //
                'inverse_dark'			=> '#aba393',       //
                'inverse_link'			=> '#ffb03b',       //  //
                'inverse_hover'			=> '#e1d9a9',       //

                // Input fields
                'input_text'			=> '#8a8a8a',
                'input_light'			=> '#acb4b6',
                'input_dark'			=> '#232a34',
                'input_bd_color'		=> '#1a1710',       //
                'input_bd_hover'		=> '#383326',       //
                'input_bg_color'		=> '#fdf5d7',       //
                'input_bg_hover'		=> '#b9b8b4',       //

                // Alternative blocks (submenu items, etc.)
                'alter_text'			=> '#fdfbec',       //
                'alter_light'			=> '#acb4b6',
                'alter_dark'			=> '#504037',       //
                'alter_link'			=> '#423d2f',       //
                'alter_hover'			=> '#e1d9a9',       //
                'alter_bd_color'		=> '#414141',       //
                'alter_bd_hover'		=> '#e75421',       //
                'alter_bg_color'		=> '#fef9e5',       //
                'alter_bg_hover'		=> '#302c21',       //
            )
        );

        // Add color schemes
        handyman_services_add_color_scheme('blue', array(

                'title'					=> esc_html__('Blue', 'handyman-services'),

                // Whole block border and background
                'bd_color'				=> '#eae3c9',       //
                'bg_color'				=> '#fefbef',       //

                // Headers, text and links colors
                'text'					=> '#5e594c',       //
                'text_light'			=> '#7e7a70',       //
                'text_dark'				=> '#443f33',       //
                'text_link'				=> '#ff9800',       //
                'text_hover'			=> '#4196ce',       //

                // Inverse colors
                'inverse_text'			=> '#ffffff',       //
                'inverse_light'			=> '#fbf8d9',       //
                'inverse_dark'			=> '#aba393',       //
                'inverse_link'			=> '#4196ce',       //      //
                'inverse_hover'			=> '#e1d9a9',       //

                // Input fields
                'input_text'			=> '#8a8a8a',
                'input_light'			=> '#acb4b6',
                'input_dark'			=> '#232a34',
                'input_bd_color'		=> '#1a1710',       //
                'input_bd_hover'		=> '#383326',       //
                'input_bg_color'		=> '#fdf5d7',       //
                'input_bg_hover'		=> '#b9b8b4',       //

                // Alternative blocks (submenu items, etc.)
                'alter_text'			=> '#fdfbec',       //
                'alter_light'			=> '#acb4b6',
                'alter_dark'			=> '#504037',       //
                'alter_link'			=> '#423d2f',       //
                'alter_hover'			=> '#e1d9a9',       //
                'alter_bd_color'		=> '#414141',       //
                'alter_bd_hover'		=> '#e75421',       //
                'alter_bg_color'		=> '#fef9e5',       //
                'alter_bg_hover'		=> '#302c21',       //
            )
        );

		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		handyman_services_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '4.6922em',
			'font-weight'	=> '900',
			'font-style'	=> '',
			'line-height'	=> '1.32em',
			'margin-top'	=> '1.13em',
			'margin-bottom'	=> '0.23em'
			)
		);
		handyman_services_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.769em',
			'font-weight'	=> '900',
			'font-style'	=> '',
			'line-height'	=> '1.32em',
			'margin-top'	=> '2.04em',
			'margin-bottom'	=> '1.3em'
			)
		);
		handyman_services_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.6155em',
			'font-weight'	=> '900',
			'font-style'	=> '',
			'line-height'	=> '1.34em',
			'margin-top'	=> '3.8em',
			'margin-bottom'	=> '1.1em'
			)
		);
		handyman_services_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.3845em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.54em',
			'margin-top'	=> '4.44em',
			'margin-bottom'	=> '0.7em'
			)
		);
		handyman_services_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.3845em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.55em',
			'margin-top'	=> '3.7em',
			'margin-bottom'	=> '0.8em'
			)
		);
		handyman_services_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.1em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.61em',
			'margin-top'	=> '5.7em',
			'margin-bottom'	=> '0'
			)
		);
		handyman_services_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '13px',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.7699em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		handyman_services_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		handyman_services_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		handyman_services_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '12px',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '2em',
			'margin-top'	=> '2.2em',
			'margin-bottom'	=> '1.7em'
			)
		);
		handyman_services_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '12px',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		handyman_services_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.6923em',
			'font-weight'	=> '900',
			'font-style'	=> '',
			'line-height'	=> '',
			'margin-top'	=> '3em',
			'margin-bottom'	=> '2.5em'
			)
		);
		handyman_services_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		handyman_services_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'handyman-services'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Theme fonts
//------------------------------------------------------------------------------

// Add theme fonts in the used fonts list
if (!function_exists('handyman_services_filter_theme_styles_used_fonts')) {
	function handyman_services_filter_theme_styles_used_fonts($theme_fonts) {
        $theme_fonts['Raleway'] = 1;
        $theme_fonts['Roboto'] = 1;
		return $theme_fonts;
	}
}

// Add theme fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('handyman_services_filter_theme_styles_list_fonts')) {
	function handyman_services_filter_theme_styles_list_fonts($list) {
		if (!isset($list['Raleway']))	$list['Raleway'] = array('family'=>'sans-serif', 'link'=>'Raleway:100,200,300,400,500,600,700,800,900');
        if (!isset($list['Roboto']))	$list['Roboto'] = array('family'=>'sans-serif', 'link'=>'Roboto:400,700,900');
		return $list;
	}
}



//------------------------------------------------------------------------------
// Theme stylesheets
//------------------------------------------------------------------------------

// Add theme.less into list files for compilation
if (!function_exists('handyman_services_filter_theme_styles_compile_less')) {
	function handyman_services_filter_theme_styles_compile_less($files) {
		if (file_exists(handyman_services_get_file_dir('css/theme.less'))) {
		 	$files[] = handyman_services_get_file_dir('css/theme.less');
		}
		return $files;	
	}
}

// Add theme stylesheets
if (!function_exists('handyman_services_action_theme_styles_add_styles')) {
	function handyman_services_action_theme_styles_add_styles() {
		// Add stylesheet files only if LESS supported
		if ( handyman_services_get_theme_setting('less_compiler') != 'no' ) {
			handyman_services_enqueue_style( 'handyman_services-theme-style', handyman_services_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'handyman_services-theme-style', handyman_services_get_inline_css() );
		}
	}
}

// Add theme inline styles
if (!function_exists('handyman_services_filter_theme_styles_add_styles_inline')) {
	function handyman_services_filter_theme_styles_add_styles_inline($custom_style) {
		// Submenu width
		$menu_width = handyman_services_get_theme_option('menu_width');
		if (!empty($menu_width)) {
			$custom_style .= "
				/* Submenu width */
				.menu_side_nav > li ul,
				.menu_main_nav > li ul {
					width: ".intval($menu_width)."px;
				}
				.menu_side_nav > li > ul ul,
				.menu_main_nav > li > ul ul {
					left:".intval($menu_width+4)."px;
				}
				.menu_side_nav > li > ul ul.submenu_left,
				.menu_main_nav > li > ul ul.submenu_left {
					left:-".intval($menu_width+1)."px;
				}
			";
		}
	
		// Logo height
		$logo_height = handyman_services_get_custom_option('logo_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo header height */
				.sidebar_outer_logo .logo_main,
				.top_panel_wrap .logo_main,
				.top_panel_wrap .logo_fixed {
					height:".intval($logo_height)."px;
				}
			";
		}
	
		// Logo top offset
		$logo_offset = handyman_services_get_custom_option('logo_offset');
		if (!empty($logo_offset)) {
			$custom_style .= "
				/* Logo header top offset */
				.top_panel_wrap .logo {
					margin-top:".intval($logo_offset)."px;
				}
			";
		}

		// Logo footer height
		$logo_height = handyman_services_get_theme_option('logo_footer_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo footer height */
				.contacts_wrap .logo img {
					height:".intval($logo_height)."px;
				}
			";
		}

		// Custom css from theme options
		$custom_style .= handyman_services_get_custom_option('custom_css');

		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Theme scripts
//------------------------------------------------------------------------------

// Add theme scripts
if (!function_exists('handyman_services_action_theme_styles_add_scripts')) {
	function handyman_services_action_theme_styles_add_scripts() {
		if (handyman_services_get_theme_option('show_theme_customizer') == 'yes' && file_exists(handyman_services_get_file_dir('js/theme.customizer.js')))
			handyman_services_enqueue_script( 'handyman_services-theme_styles-customizer-script', handyman_services_get_file_url('js/theme.customizer.js'), array(), null );
	}
}

// Add theme scripts inline
if (!function_exists('handyman_services_filter_theme_styles_localize_script')) {
	function handyman_services_filter_theme_styles_localize_script($vars) {
		if (empty($vars['theme_font']))
			$vars['theme_font'] = handyman_services_get_custom_font_settings('p', 'font-family');
		$vars['theme_color'] = handyman_services_get_scheme_color('text_dark');
		$vars['theme_bg_color'] = handyman_services_get_scheme_color('bg_color');
		return $vars;
	}
}
?>
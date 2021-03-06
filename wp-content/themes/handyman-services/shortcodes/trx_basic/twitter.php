<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_twitter_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_twitter_theme_setup' );
	function handyman_services_sc_twitter_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_twitter_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_twitter_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_twitter id="unique_id" user="username" consumer_key="" consumer_secret="" token_key="" token_secret=""]
*/

if (!function_exists('handyman_services_sc_twitter')) {	
	function handyman_services_sc_twitter($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"user" => "",
			"consumer_key" => "",
			"consumer_secret" => "",
			"token_key" => "",
			"token_secret" => "",
			"count" => "3",
			"controls" => "yes",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
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
	
		$twitter_username = $user ? $user : handyman_services_get_theme_option('twitter_username');
		$twitter_consumer_key = $consumer_key ? $consumer_key : handyman_services_get_theme_option('twitter_consumer_key');
		$twitter_consumer_secret = $consumer_secret ? $consumer_secret : handyman_services_get_theme_option('twitter_consumer_secret');
		$twitter_token_key = $token_key ? $token_key : handyman_services_get_theme_option('twitter_token_key');
		$twitter_token_secret = $token_secret ? $token_secret : handyman_services_get_theme_option('twitter_token_secret');
		$twitter_count = max(1, $count ? $count : intval(handyman_services_get_theme_option('twitter_count')));
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && handyman_services_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = handyman_services_get_scheme_color('bg');
			$rgb = handyman_services_hex2rgb($bg_color);
		}
		
		$class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);
		$ws = handyman_services_get_css_dimensions_from_values($width);
		$hs = handyman_services_get_css_dimensions_from_values('', $height);
	
		$css .= ($hs) . ($ws);
	
		$output = '';
	
		if (!empty($twitter_consumer_key) && !empty($twitter_consumer_secret) && !empty($twitter_token_key) && !empty($twitter_token_secret)) {
			$data = handyman_services_get_twitter_data(array(
				'mode'            => 'user_timeline',
				'consumer_key'    => $twitter_consumer_key,
				'consumer_secret' => $twitter_consumer_secret,
				'token'           => $twitter_token_key,
				'secret'          => $twitter_token_secret
				)
			);
			if ($data && isset($data[0]['text'])) {
				handyman_services_enqueue_slider('swiper');
				$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || handyman_services_strlen($bg_texture)>2 || ($scheme && !handyman_services_param_is_off($scheme) && !handyman_services_param_is_inherit($scheme))
						? '<div class="sc_twitter_wrap sc_section'
								. ($scheme && !handyman_services_param_is_off($scheme) && !handyman_services_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
								. ($align && $align!='none' && !handyman_services_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							.' style="'
								. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
								. ($bg_image !== '' ? 'background-image:url('.esc_url($bg_image).');' : '')
								. '"'
							. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
							. '>'
							. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
									. ' style="' 
										. ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
										. (handyman_services_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
										. '"'
										. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
										. '>' 
						: '')
						. '<div class="sc_twitter'
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && $align && $align!='none' && !handyman_services_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
							. '>'
								. '<div class="sc_slider_swiper sc_slider_nopagination swiper-slider-container'
										. (handyman_services_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
										. (handyman_services_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && handyman_services_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && handyman_services_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. '>'
									. '<div class="slides swiper-wrapper">';
				$cnt = 0;
				if (is_array($data) && count($data) > 0) {
					foreach ($data as $tweet) {
						if (handyman_services_substr($tweet['text'], 0, 1)=='@') continue;
							$output .= '<div class="swiper-slide" data-style="'.esc_attr(($ws).($hs)).'" style="'.esc_attr(($ws).($hs)).'">'
										. '<div class="sc_twitter_item">'
											. '<span class="sc_twitter_icon icon-twitter"></span>'
											. '<div class="sc_twitter_content">'
												. '<a href="' . esc_url('https://twitter.com/'.($twitter_username)).'" class="sc_twitter_author" target="_blank">@' . esc_html($tweet['user']['screen_name']) . '</a> '
												. force_balance_tags(handyman_services_prepare_twitter_text($tweet))
											. '</div>'
										. '</div>'
									. '</div>';
						if (++$cnt >= $twitter_count) break;
					}
				}
				$output .= '</div>'
						. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
						. '</div>'
					. '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || handyman_services_strlen($bg_texture)>2
						?  '</div></div>'
						: '');
			}
		}
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_twitter', $atts, $content);
	}
	handyman_services_require_shortcode('trx_twitter', 'handyman_services_sc_twitter');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_twitter_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_twitter_reg_shortcodes');
	function handyman_services_sc_twitter_reg_shortcodes() {
	
		handyman_services_sc_map("trx_twitter", array(
			"title" => esc_html__("Twitter", 'handyman-services'),
			"desc" => wp_kses_data( __("Insert twitter feed into post (page)", 'handyman-services') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"user" => array(
					"title" => esc_html__("Twitter Username", 'handyman-services'),
					"desc" => wp_kses_data( __("Your username in the twitter account. If empty - get it from Theme Options.", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"consumer_key" => array(
					"title" => esc_html__("Consumer Key", 'handyman-services'),
					"desc" => wp_kses_data( __("Consumer Key from the twitter account", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"consumer_secret" => array(
					"title" => esc_html__("Consumer Secret", 'handyman-services'),
					"desc" => wp_kses_data( __("Consumer Secret from the twitter account", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"token_key" => array(
					"title" => esc_html__("Token Key", 'handyman-services'),
					"desc" => wp_kses_data( __("Token Key from the twitter account", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"token_secret" => array(
					"title" => esc_html__("Token Secret", 'handyman-services'),
					"desc" => wp_kses_data( __("Token Secret from the twitter account", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"count" => array(
					"title" => esc_html__("Tweets number", 'handyman-services'),
					"desc" => wp_kses_data( __("Tweets number to show", 'handyman-services') ),
					"divider" => true,
					"value" => 3,
					"max" => 20,
					"min" => 1,
					"type" => "spinner"
				),
				"controls" => array(
					"title" => esc_html__("Show arrows", 'handyman-services'),
					"desc" => wp_kses_data( __("Show control buttons", 'handyman-services') ),
					"value" => "yes",
					"type" => "switch",
					"options" => handyman_services_get_sc_param('yes_no')
				),
				"interval" => array(
					"title" => esc_html__("Tweets change interval", 'handyman-services'),
					"desc" => wp_kses_data( __("Tweets change interval (in milliseconds: 1000ms = 1s)", 'handyman-services') ),
					"value" => 7000,
					"step" => 500,
					"min" => 0,
					"type" => "spinner"
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'handyman-services'),
					"desc" => wp_kses_data( __("Alignment of the tweets block", 'handyman-services') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => handyman_services_get_sc_param('align')
				),
				"autoheight" => array(
					"title" => esc_html__("Autoheight", 'handyman-services'),
					"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'handyman-services') ),
					"value" => "yes",
					"type" => "switch",
					"options" => handyman_services_get_sc_param('yes_no')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'handyman-services'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'handyman-services') ),
					"value" => "",
					"type" => "checklist",
					"options" => handyman_services_get_sc_param('schemes')
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'handyman-services'),
					"desc" => wp_kses_data( __("Any background color for this section", 'handyman-services') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'handyman-services'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'handyman-services') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'handyman-services'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'handyman-services') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'handyman-services'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'handyman-services') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_twitter_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_twitter_reg_shortcodes_vc');
	function handyman_services_sc_twitter_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_twitter",
			"name" => esc_html__("Twitter", 'handyman-services'),
			"description" => wp_kses_data( __("Insert twitter feed into post (page)", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_twitter',
			"class" => "trx_sc_single trx_sc_twitter",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "user",
					"heading" => esc_html__("Twitter Username", 'handyman-services'),
					"description" => wp_kses_data( __("Your username in the twitter account. If empty - get it from Theme Options.", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "consumer_key",
					"heading" => esc_html__("Consumer Key", 'handyman-services'),
					"description" => wp_kses_data( __("Consumer Key from the twitter account", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "consumer_secret",
					"heading" => esc_html__("Consumer Secret", 'handyman-services'),
					"description" => wp_kses_data( __("Consumer Secret from the twitter account", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "token_key",
					"heading" => esc_html__("Token Key", 'handyman-services'),
					"description" => wp_kses_data( __("Token Key from the twitter account", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "token_secret",
					"heading" => esc_html__("Token Secret", 'handyman-services'),
					"description" => wp_kses_data( __("Token Secret from the twitter account", 'handyman-services') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Tweets number", 'handyman-services'),
					"description" => wp_kses_data( __("Number tweets to show", 'handyman-services') ),
					"class" => "",
					"divider" => true,
					"value" => 3,
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Show arrows", 'handyman-services'),
					"description" => wp_kses_data( __("Show control buttons", 'handyman-services') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('yes_no')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Tweets change interval", 'handyman-services'),
					"description" => wp_kses_data( __("Tweets change interval (in milliseconds: 1000ms = 1s)", 'handyman-services') ),
					"class" => "",
					"value" => "7000",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'handyman-services'),
					"description" => wp_kses_data( __("Alignment of the tweets block", 'handyman-services') ),
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Autoheight", 'handyman-services'),
					"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'handyman-services') ),
					"class" => "",
					"value" => array("Autoheight" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'handyman-services'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'handyman-services') ),
					"group" => esc_html__('Colors and Images', 'handyman-services'),
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'handyman-services'),
					"description" => wp_kses_data( __("Any background color for this section", 'handyman-services') ),
					"group" => esc_html__('Colors and Images', 'handyman-services'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'handyman-services'),
					"description" => wp_kses_data( __("Select background image from library for this section", 'handyman-services') ),
					"group" => esc_html__('Colors and Images', 'handyman-services'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'handyman-services'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'handyman-services') ),
					"group" => esc_html__('Colors and Images', 'handyman-services'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'handyman-services'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'handyman-services') ),
					"group" => esc_html__('Colors and Images', 'handyman-services'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				handyman_services_get_vc_param('id'),
				handyman_services_get_vc_param('class'),
				handyman_services_get_vc_param('animation'),
				handyman_services_get_vc_param('css'),
				handyman_services_vc_width(),
				handyman_services_vc_height(),
				handyman_services_get_vc_param('margin_top'),
				handyman_services_get_vc_param('margin_bottom'),
				handyman_services_get_vc_param('margin_left'),
				handyman_services_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Twitter extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}
	}
}
?>
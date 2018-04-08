<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_call_to_action_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_call_to_action_theme_setup' );
	function handyman_services_sc_call_to_action_theme_setup() {
		add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_call_to_action_reg_shortcodes');
		if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
			add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_call_to_action_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_call_to_action id="unique_id" style="1|2" align="left|center|right"]
	[inner shortcodes]
[/trx_call_to_action]
*/

if (!function_exists('handyman_services_sc_call_to_action')) {	
	function handyman_services_sc_call_to_action($atts, $content=null){	
		if (handyman_services_in_shortcode_blogger()) return '';
		extract(handyman_services_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"align" => "center",
			"custom" => "no",
            "style_color" => '',
			"accent" => "no",
			"image" => "",
			"video" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Learn more', 'handyman-services'),
			"link2" => '',
			"link2_caption" => '',
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
	
		if (empty($id)) $id = "sc_call_to_action_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if (!empty($image)) {
			$thumb_sizes = handyman_services_get_thumb_sizes(array('layout' => 'excerpt'));
			$image = !empty($video) 
				? handyman_services_get_resized_image_url($image, $thumb_sizes['w'], $thumb_sizes['h']) 
				: handyman_services_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		if (!empty($video)) {
			$video = '<video' . ($id ? ' id="' . esc_attr($id.'_video') . '"' : '') 
				. ' class="sc_video"'
				. ' src="' . esc_url(handyman_services_get_video_player_url($video)) . '"'
				. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
				. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
				. ' data-ratio="16:9"'
				. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
				. ' controls="controls" loop="loop"'
				. '>'
				. '</video>';
			if (handyman_services_get_custom_option('substitute_video')=='no') {
				$video = handyman_services_get_video_frame($video, $image, '', '');
			} else {
				if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
					$video = handyman_services_substitute_video($video, $width, $height, false);
				}
			}
			if (handyman_services_get_theme_option('use_mediaelement')=='yes')
				handyman_services_enqueue_script('wp-mediaelement');
		}
		
		$class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= handyman_services_get_css_dimensions_from_values($width, $height);
		
		$content = do_shortcode($content);
		
		$featured = ($style==1 && (!empty($content) || !empty($image) || !empty($video))
					? '<div class="sc_call_to_action_featured column-1_2">'
						. (!empty($content) 
							? $content 
							: (!empty($video) 
								? $video 
								: $image)
							)
						. '</div>'
					: '');
	
		$need_columns = ($featured || $style==2) && !in_array($align, array('center', 'none'))
							? ($style==2 ? 4 : 2)
							: 0;
		
		$buttons = (!empty($link) || !empty($link2) 
						? '<div class="sc_call_to_action_buttons sc_item_buttons'.($need_columns && $style==2 ? ' column-1_'.esc_attr($need_columns) : '').'">'
							. (!empty($link) 
								? '<div class="sc_call_to_action_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'"]'.esc_html($link_caption).'[/trx_button]').'</div>'
								: '')
							. (!empty($link2) 
								? '<div class="sc_call_to_action_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link2).'"]'.esc_html($link2_caption).'[/trx_button]').'</div>'
								: '')
							. '</div>'
						: '');
	
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_call_to_action'
					. (handyman_services_param_is_on($accent) ? ' sc_call_to_action_accented' : '')
					. ' sc_call_to_action_style_' . esc_attr($style) 
					. ' sc_call_to_action_align_'.esc_attr($align)
					. (!empty($class) ? ' '.esc_attr($class) : '')
                    . (!empty($style_color) ? ' '.esc_attr($style_color) : '')
					. '"'
				. (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. ($need_columns ? '<div class="columns_wrap">' : '')
				. ($align!='right' ? $featured : '')
				. ($style==2 && $align=='right' ? $buttons : '')
				. '<div class="sc_call_to_action_info'.($need_columns ? ' column-'.esc_attr($need_columns-1).'_'.esc_attr($need_columns) : '').'">'
					. (!empty($subtitle) ? '<h6 class="sc_call_to_action_subtitle sc_item_subtitle">' . trim(handyman_services_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_call_to_action_title sc_item_title">' . trim(handyman_services_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_call_to_action_descr sc_item_descr">' . trim(handyman_services_strmacros($description)) . '</div>' : '')
					. ($style==1 ? $buttons : '')
				. '</div>'
				. ($style==2 && $align!='right' ? $buttons : '')
				. ($align=='right' ? $featured : '')
				. ($need_columns ? '</div>' : '')
			. '</div>';
	
		return apply_filters('handyman_services_shortcode_output', $output, 'trx_call_to_action', $atts, $content);
	}
	handyman_services_require_shortcode('trx_call_to_action', 'handyman_services_sc_call_to_action');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_call_to_action_reg_shortcodes' ) ) {
	//add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_call_to_action_reg_shortcodes');
	function handyman_services_sc_call_to_action_reg_shortcodes() {
	
		handyman_services_sc_map("trx_call_to_action", array(
			"title" => esc_html__("Call to action", 'handyman-services'),
			"desc" => wp_kses_data( __("Insert call to action block in your page (post)", 'handyman-services') ),
			"decorate" => true,
			"container" => true,
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
					"title" => esc_html__("Style", 'handyman-services'),
					"desc" => wp_kses_data( __("Select style to display block", 'handyman-services') ),
					"value" => "1",
					"type" => "checklist",
					"options" => handyman_services_get_list_styles(1, 2)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'handyman-services'),
					"desc" => wp_kses_data( __("Alignment elements in the block", 'handyman-services') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => handyman_services_get_sc_param('align')
				),
				"accent" => array(
					"title" => esc_html__("Accented", 'handyman-services'),
					"desc" => wp_kses_data( __("Fill entire block with links color from current color scheme", 'handyman-services') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => handyman_services_get_sc_param('yes_no')
				),
				"custom" => array(
					"title" => esc_html__("Custom", 'handyman-services'),
					"desc" => wp_kses_data( __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", 'handyman-services') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => handyman_services_get_sc_param('yes_no')
				),
				"image" => array(
					"title" => esc_html__("Image", 'handyman-services'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site to include image into this block", 'handyman-services') ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"video" => array(
					"title" => esc_html__("URL for video file", 'handyman-services'),
					"desc" => wp_kses_data( __("Select video from media library or paste URL for video file from other site to include video into this block", 'handyman-services') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose video', 'handyman-services'),
						'action' => 'media_upload',
						'type' => 'video',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose video file', 'handyman-services'),
							'update' => esc_html__('Select video file', 'handyman-services')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'handyman-services'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'handyman-services') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'handyman-services'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'handyman-services') ),
					"value" => "",
					"type" => "text"
				),
				"link2" => array(
					"title" => esc_html__("Button 2 URL", 'handyman-services'),
					"desc" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'handyman-services') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link2_caption" => array(
					"title" => esc_html__("Button 2 caption", 'handyman-services'),
					"desc" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'handyman-services') ),
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_call_to_action_reg_shortcodes_vc' ) ) {
	//add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_call_to_action_reg_shortcodes_vc');
	function handyman_services_sc_call_to_action_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_call_to_action",
			"name" => esc_html__("Call to Action", 'handyman-services'),
			"description" => wp_kses_data( __("Insert call to action block in your page (post)", 'handyman-services') ),
			"category" => esc_html__('Content', 'handyman-services'),
			'icon' => 'icon_trx_call_to_action',
			"class" => "trx_sc_collection trx_sc_call_to_action",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block's style", 'handyman-services'),
					"description" => wp_kses_data( __("Select style to display this block", 'handyman-services') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(handyman_services_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'handyman-services'),
					"description" => wp_kses_data( __("Select block alignment", 'handyman-services') ),
					"class" => "",
					"value" => array_flip(handyman_services_get_sc_param('align')),
					"type" => "dropdown"
				),
                array(
                    "param_name" => "style_color",
                    "heading" => esc_html__("Block's color style", 'handyman-services'),
                    "description" => wp_kses_data( __("Select block's color style", 'handyman-services') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => array(
                        esc_html__('Original', 'handyman-services') => 'sc_call_to_action_original_color',
                        esc_html__('White title', 'handyman-services') => 'sc_call_to_action_white'
                    ),
                    "type" => "dropdown"
                ),
				array(
					"param_name" => "accent",
					"heading" => esc_html__("Accent", 'handyman-services'),
					"description" => wp_kses_data( __("Fill entire block with links color from current color scheme", 'handyman-services') ),
					"class" => "",
					"value" => array("Fill with links color" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom", 'handyman-services'),
					"description" => wp_kses_data( __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", 'handyman-services') ),
					"class" => "",
					"value" => array("Custom content" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image", 'handyman-services'),
					"description" => wp_kses_data( __("Image to display inside block", 'handyman-services') ),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "video",
					"heading" => esc_html__("URL for video file", 'handyman-services'),
					"description" => wp_kses_data( __("Paste URL for video file to display inside block", 'handyman-services') ),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
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
				array(
					"param_name" => "link2",
					"heading" => esc_html__("Button 2 URL", 'handyman-services'),
					"description" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'handyman-services') ),
					"group" => esc_html__('Captions', 'handyman-services'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2_caption",
					"heading" => esc_html__("Button 2 caption", 'handyman-services'),
					"description" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'handyman-services') ),
					"group" => esc_html__('Captions', 'handyman-services'),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Call_To_Action extends HANDYMAN_SERVICES_VC_ShortCodeCollection {}
	}
}
?>
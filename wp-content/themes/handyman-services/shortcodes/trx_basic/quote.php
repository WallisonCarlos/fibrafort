<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('handyman_services_sc_quote_theme_setup')) {
    add_action( 'handyman_services_action_before_init_theme', 'handyman_services_sc_quote_theme_setup' );
    function handyman_services_sc_quote_theme_setup() {
        add_action('handyman_services_action_shortcodes_list', 		'handyman_services_sc_quote_reg_shortcodes');
        if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
            add_action('handyman_services_action_shortcodes_list_vc','handyman_services_sc_quote_reg_shortcodes_vc');
    }
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('handyman_services_sc_quote')) {
    function handyman_services_sc_quote($atts, $content=null){
        if (handyman_services_in_shortcode_blogger()) return '';
        extract(handyman_services_html_decode(shortcode_atts(array(
            // Individual params
            "title" => "",
            "cite" => "",
            "bg_image" => "",
            // Common params
            "id" => "",
            "class" => "",
            "animation" => "",
            "css" => "",
            "width" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));

        if ($bg_image > 0) {
            $attach = wp_get_attachment_image_src( $bg_image, 'full' );
            if (isset($attach[0]) && $attach[0]!='')
                $bg_image = $attach[0];
        }

        $class .= ($class ? ' ' : '') . handyman_services_get_css_position_as_classes($top, $right, $bottom, $left);
        $css .= handyman_services_get_css_dimensions_from_values($width);
        $css .= ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '');
        $cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
        $title = $title=='' ? $cite : $title;
        $content = do_shortcode($content);
        if (handyman_services_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
        $output = '<blockquote'
            . ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param)
            . ' class="sc_quote'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
            . (!handyman_services_param_is_off($animation) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($animation)).'"' : '')
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . '>'
            . ($content)
            . ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
            .'</blockquote>';
        return apply_filters('handyman_services_shortcode_output', $output, 'trx_quote', $atts, $content);
    }
    handyman_services_require_shortcode('trx_quote', 'handyman_services_sc_quote');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'handyman_services_sc_quote_reg_shortcodes' ) ) {
    //add_action('handyman_services_action_shortcodes_list', 'handyman_services_sc_quote_reg_shortcodes');
    function handyman_services_sc_quote_reg_shortcodes() {

        handyman_services_sc_map("trx_quote", array(
            "title" => esc_html__("Quote", 'handyman-services'),
            "desc" => wp_kses_data( __("Quote text", 'handyman-services') ),
            "decorate" => false,
            "container" => true,
            "params" => array(
                "cite" => array(
                    "title" => esc_html__("Quote cite", 'handyman-services'),
                    "desc" => wp_kses_data( __("URL for quote cite", 'handyman-services') ),
                    "value" => "",
                    "type" => "text"
                ),
                "title" => array(
                    "title" => esc_html__("Title (author)", 'handyman-services'),
                    "desc" => wp_kses_data( __("Quote title (author name)", 'handyman-services') ),
                    "value" => "",
                    "type" => "text"
                ),
                "_content_" => array(
                    "title" => esc_html__("Quote content", 'handyman-services'),
                    "desc" => wp_kses_data( __("Quote content", 'handyman-services') ),
                    "rows" => 4,
                    "value" => "",
                    "type" => "textarea"
                ),
                "width" => handyman_services_shortcodes_width(),
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
if ( !function_exists( 'handyman_services_sc_quote_reg_shortcodes_vc' ) ) {
    //add_action('handyman_services_action_shortcodes_list_vc', 'handyman_services_sc_quote_reg_shortcodes_vc');
    function handyman_services_sc_quote_reg_shortcodes_vc() {

        vc_map( array(
            "base" => "trx_quote",
            "name" => esc_html__("Quote", 'handyman-services'),
            "description" => wp_kses_data( __("Quote text", 'handyman-services') ),
            "category" => esc_html__('Content', 'handyman-services'),
            'icon' => 'icon_trx_quote',
            "class" => "trx_sc_single trx_sc_quote",
            "content_element" => true,
            "is_container" => false,
            "show_settings_on_create" => true,
            "params" => array(
                array(
                    "param_name" => "cite",
                    "heading" => esc_html__("Quote cite", 'handyman-services'),
                    "description" => wp_kses_data( __("URL for the quote cite link", 'handyman-services') ),
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "title",
                    "heading" => esc_html__("Title (author)", 'handyman-services'),
                    "description" => wp_kses_data( __("Quote title (author name)", 'handyman-services') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "bg_image",
                    "heading" => esc_html__("Background image URL", 'handyman-services'),
                    "description" => wp_kses_data( __("Select background image from library for this section", 'handyman-services') ),
                    "class" => "",
                    "value" => "",
                    "type" => "attach_image"
                ),
                array(
                    "param_name" => "content",
                    "heading" => esc_html__("Quote content", 'handyman-services'),
                    "description" => wp_kses_data( __("Quote content", 'handyman-services') ),
                    "class" => "",
                    "value" => "",
                    "type" => "textarea_html"
                ),
                handyman_services_get_vc_param('id'),
                handyman_services_get_vc_param('class'),
                handyman_services_get_vc_param('animation'),
                handyman_services_get_vc_param('css'),
                handyman_services_vc_width(),
                handyman_services_get_vc_param('margin_top'),
                handyman_services_get_vc_param('margin_bottom'),
                handyman_services_get_vc_param('margin_left'),
                handyman_services_get_vc_param('margin_right')
            ),
            'js_view' => 'VcTrxTextView'
        ) );

        class WPBakeryShortCode_Trx_Quote extends handyman_services_VC_ShortCodeSingle {}
    }
}
?>
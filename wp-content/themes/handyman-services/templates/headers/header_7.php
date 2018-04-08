<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'handyman_services_template_header_7_theme_setup' ) ) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_template_header_7_theme_setup', 1 );
	function handyman_services_template_header_7_theme_setup() {
		handyman_services_add_template(array(
			'layout' => 'header_7',
			'mode'   => 'header',
			'title'  => esc_html__('Header 7', 'handyman-services'),
			'icon'   => handyman_services_get_file_url('templates/headers/images/7.jpg'),
			'thumb_title'  => esc_html__('Original image', 'handyman-services'),
			'w'		 => null,
			'h_crop' => null,
			'h'      => null
			));
	}
}

// Template output
if ( !function_exists( 'handyman_services_template_header_7_output' ) ) {
	function handyman_services_template_header_7_output($post_options, $post_data) {

		$header_css = '';

		if (empty($header_image))
			$header_image = handyman_services_get_custom_option('top_panel_image');
		if (empty($header_image))
			$header_image = get_header_image();
		if (!empty($header_image)) {
			$header_css = ' style="background-image: url('.esc_url($header_image).')"';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_7 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_7 top_panel_position_<?php echo esc_attr(handyman_services_get_custom_option('top_panel_position')); ?>">

			<div class="top_panel_middle">
				<div class="content_wrap">
					<div class="column-1_6 contact_logo">
						<?php handyman_services_show_logo(true, true); ?>
					</div>
					<div class="column-5_6 menu_main_wrap">
						<nav class="menu_main_nav_area menu_hover_<?php echo esc_attr(handyman_services_get_theme_option('menu_hover')); ?>">
							<?php
							$menu_main = handyman_services_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = handyman_services_get_nav_menu();
							echo trim($menu_main);
							?>
						</nav>
                        <?php
                        if (( $contact_phone=trim(handyman_services_get_custom_option('contact_phone')) ) !='') {
                            ?>
                            <div class="contact_phone">
                                <?php echo '<span>'.esc_html__('Call', 'handyman-services').'</span> '.force_balance_tags($contact_phone); ?>
                            </div>
                        <?php
                        }

                        if (trim(handyman_services_get_custom_option('show_appointment')) == 'yes') {
                            ?>
                            <div class="appointment_button">
                                <?php echo '<a class="sc_button sc_button_style_border" href="'.esc_url("/appointment/").'">'.esc_html__('Book online', 'handyman-services').'</a>'; ?>
                            </div>
                        <?php
                        }

                        ?>
					</div>
				</div>
			</div>
			

			</div>
		</header>

        <?php
            $show_title = handyman_services_get_custom_option('show_page_title')=='yes';
            $show_breadcrumbs = handyman_services_get_custom_option('show_breadcrumbs')=='yes';
            if ($show_title || $show_breadcrumbs) {
                ?>
                <section class="top_panel_image" <?php echo trim($header_css); ?>>
                    <div class="top_panel_image_hover"></div>
                    <div class="top_panel_image_header">
                        <?php if (!empty($post_icon)) { ?>
                            <div class="top_panel_image_icon <?php echo esc_attr($post_icon); ?>"></div>
                        <?php } ?>
                        <h1 itemprop="headline"
                            class="top_panel_image_title entry-title"><?php echo strip_tags(handyman_services_get_blog_title()); ?></h1>

                        <div class="breadcrumbs">
                            <?php if (!is_404()) handyman_services_show_breadcrumbs(); ?>
                        </div>
                    </div>
                </section>
            <?php
            }
		handyman_services_storage_set('header_mobile', array(
				 'open_hours' => false,
				 'login' => false,
				 'socials' => false,
				 'bookmarks' => false,
				 'contact_address' => false,
				 'contact_phone_email' => false,
				 'woo_cart' => false,
				 'search' => false
			)
		);
	}
}
?>
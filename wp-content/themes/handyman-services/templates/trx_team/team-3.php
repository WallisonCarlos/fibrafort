<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'handyman_services_template_team_3_theme_setup' ) ) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_template_team_3_theme_setup', 1 );
	function handyman_services_template_team_3_theme_setup() {
		handyman_services_add_template(array(
			'layout' => 'team-3',
			'template' => 'team-3',
			'mode'   => 'team',
			'title'  => esc_html__('Team /Style 3/', 'handyman-services'),
			'thumb_title'  => esc_html__('Medium square images (crop)', 'handyman-services'),
			'w' => 370,
			'h' => 233
		));
	}
}

// Template output
if ( !function_exists( 'handyman_services_template_team_3_output' ) ) {
	function handyman_services_template_team_3_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (handyman_services_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_team_item sc_team_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!handyman_services_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(handyman_services_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
				<div class="sc_team_item_avatar"><?php echo trim($post_options['photo']); ?>
					<div class="sc_team_item_hover">
						<div class="sc_team_item_socials"><?php echo trim($post_options['socials']); ?></div>
					</div>
                    <div class="sc_team_item_info">
                        <?php
                        if(!isset($post_data['post_id'])) $post_data['post_id']=1;
                        ?>
                        <h4 class="sc_team_item_title"><?php echo (!empty($post_options['link']) ? '<a href="'.esc_url($post_options['link']).'">' : '') . (handyman_services_get_post_title($post_data['post_id'])) . (!empty($post_options['link']) ? '</a>' : ''); ?></h4>
                        <div class="sc_team_item_position"><?php echo trim($post_options['position']);?></div>
                    </div>
				</div>

			</div>
		<?php
		if (handyman_services_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>
<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'handyman_services_template_single_players_theme_setup' ) ) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_template_single_players_theme_setup', 1 );
	function handyman_services_template_single_players_theme_setup() {
		handyman_services_add_template(array(
			'layout' => 'single-players',
			'mode'   => 'players',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single Player', 'handyman-services'),
			'thumb_title'  => esc_html__('Large image (crop)', 'handyman-services'),
			'w'		 => 770,
			'h'		 => 434
		));
	}
}

// Template output
if ( !function_exists( 'handyman_services_template_single_players_output' ) ) {
	function handyman_services_template_single_players_output($post_options, $post_data) {
		$post_data['post_views']++;
		$show_title = handyman_services_get_custom_option('show_post_title')=='yes';
		$title_tag = handyman_services_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		handyman_services_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single_players'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/Article'
				. '">');

		if ($show_title && $post_options['location'] == 'center' && handyman_services_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="headline" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php echo trim($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(handyman_services_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])	// && $post_data['post_format']!='gallery' && $post_data['post_format']!='image')
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
				echo trim($post_options['dedicated']);
			} else {
				handyman_services_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php echo trim($post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}
		

		handyman_services_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="articleBody">');

		if ($show_title && $post_options['location'] != 'center') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="name" class="post_title entry-title"><?php echo trim($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}
		
		if(!empty($post_data['post_excerpt']) && ($post_options['location'] == 'left' || $post_options['location'] == 'right')){
			echo trim($post_data['post_excerpt']);
		}
		
		// Player information
		$post_meta = get_post_meta($post_data['post_id'], handyman_services_storage_get('options_prefix') . '_post_options', true);

		echo '<div class="player_info">'
				.(!empty($post_meta['player_country']) && $post_meta['player_country'] != 'inherit' ? '<span class="player_country">'. esc_html__( 'Country: ', 'handyman-services' ) .''. handyman_services_get_list_countries(false, $post_meta['player_country']) .'</span>' : '')
				.(!empty($post_meta['player_club']) && $post_meta['player_club'] != 'inherit' ? '<span class="player_club">'. esc_html__( 'Club: ', 'handyman-services' ) .''. trim($post_meta['player_club']) .'</span>' : '')
				.(!empty($post_meta['player_age']) && $post_meta['player_age'] != 'inherit' && $post_meta['player_type'] == 'player' ? '<span class="player_age">'. esc_html__( 'Age: ', 'handyman-services' ) .''. trim($post_meta['player_age']) .'</span>' : '')
			.'</div>';
			
		// Player socials
		if(!empty($post_meta['player_socials'])){ 
			$socials = $post_meta['player_socials'];
			echo trim(handyman_services_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($socials).'"][/trx_socials]'));
		}
			
		// Post content
		echo '<div class="player_content">';
			if ($post_data['post_protected']) { 
				echo trim($post_data['post_excerpt']);
				echo get_the_password_form(); 
			} else {
				echo trim(handyman_services_gap_wrapper(handyman_services_reviews_wrapper($post_data['post_content'])));
				wp_link_pages( array( 
					'before' => '<nav class="pagination_single"><span class="pager_pages">' . esc_html__( 'Pages:', 'handyman-services' ) . '</span>', 
					'after' => '</nav>',
					'link_before' => '<span class="pager_numbers">',
					'link_after' => '</span>'
					)
				); 
			} 
		echo '</div>';

		// Prepare args for all rest template parts
		// This parts not pop args from storage!
		handyman_services_template_set_args('single-footer', array(
			'post_options' => $post_options,
			'post_data' => $post_data
		));
			
		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			get_template_part(handyman_services_get_file_slug('templates/_parts/editor-area.php'));
		}

		handyman_services_close_wrapper();	// .post_content
			
		if (!$post_data['post_protected']) {
			get_template_part(handyman_services_get_file_slug('templates/_parts/share.php'));
		}

		handyman_services_close_wrapper();	// .post_item

		if (!$post_data['post_protected']) {
			// Show related posts
			get_template_part(handyman_services_get_file_slug('templates/_parts/related-posts.php'));
			// Show comments
			if ( comments_open() || get_comments_number() != 0 ) {
				comments_template();
			}
		}

		// Manually pop args from storage
		// after all single footer templates
		handyman_services_template_get_args('single-footer');
	}
}
?>
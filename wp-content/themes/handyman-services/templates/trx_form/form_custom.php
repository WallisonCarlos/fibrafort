<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'handyman_services_template_form_custom_theme_setup' ) ) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_template_form_custom_theme_setup', 1 );
	function handyman_services_template_form_custom_theme_setup() {
		handyman_services_add_template(array(
			'layout' => 'form_custom',
			'mode'   => 'forms',
			'title'  => esc_html__('Custom Form', 'handyman-services')
			));
	}
}

// Template output
if ( !function_exists( 'handyman_services_template_form_custom_output' ) ) {
	function handyman_services_template_form_custom_output($post_options, $post_data) {
		$form_style = handyman_services_get_theme_option('input_hover');
		?>
		<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?>
			class="sc_input_hover_<?php echo esc_attr($form_style); ?>"
			data-formtype="<?php echo esc_attr($post_options['layout']); ?>" 
			method="post" 
			action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
			<?php
			handyman_services_sc_form_show_fields($post_options['fields']);
			echo trim($post_options['content']);
			?>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>
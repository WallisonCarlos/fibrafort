<?php
/* Calculated fields form support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('handyman_services_calcfields_form_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_calcfields_form_theme_setup', 1 );
	function handyman_services_calcfields_form_theme_setup() {
		// Register shortcode in the shortcodes list
		if (handyman_services_exists_calcfields_form()) {
			add_action('handyman_services_action_shortcodes_list',				'handyman_services_calcfields_form_reg_shortcodes');
			if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
				add_action('handyman_services_action_shortcodes_list_vc',		'handyman_services_calcfields_form_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'handyman_services_filter_importer_options',			'handyman_services_calcfields_form_importer_set_options', 10, 1 );
				add_action( 'handyman_services_action_importer_params',			'handyman_services_calcfields_form_importer_show_params', 10, 1 );
				add_action( 'handyman_services_action_importer_import',			'handyman_services_calcfields_form_importer_import', 10, 2 );
				add_action( 'handyman_services_action_importer_import_fields',	'handyman_services_calcfields_form_importer_import_fields', 10, 1 );
				add_action( 'handyman_services_action_importer_export',			'handyman_services_calcfields_form_importer_export', 10, 1 );
				add_action( 'handyman_services_action_importer_export_fields',	'handyman_services_calcfields_form_importer_export_fields', 10, 1 );
			}
			// Uncomment next row if you want to disable CF jquery ui styles
			//add_action('wp_enqueue_scripts', 							'handyman_services_calcfields_form_frontend_scripts');
		}
		if (is_admin()) {
			add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_calcfields_form_importer_required_plugins', 10, 2 );
			add_filter( 'handyman_services_filter_required_plugins',				'handyman_services_calcfields_form_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'handyman_services_exists_calcfields_form' ) ) {
	function handyman_services_exists_calcfields_form() {
		return defined('CP_SCHEME');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'handyman_services_calcfields_form_required_plugins' ) ) {
	//add_filter('handyman_services_filter_required_plugins',	'handyman_services_calcfields_form_required_plugins');
	function handyman_services_calcfields_form_required_plugins($list=array()) {
		if (in_array('calcfields', handyman_services_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Calculated Fields Form', 'handyman-services'),
					'slug' 		=> 'calculated-fields-form',
					'required' 	=> false
					);
		return $list;
	}
}

// Remove jquery_ui from frontend
if ( !function_exists( 'handyman_services_calcfields_form_frontend_scripts' ) ) {
	//add_action('wp_enqueue_scripts', 'handyman_services_calcfields_form_frontend_scripts');
	function handyman_services_calcfields_form_frontend_scripts() {
		global $wp_styles;
		$wp_styles->done[] = 'cpcff_jquery_ui';
	}
}


// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'handyman_services_calcfields_form_importer_required_plugins' ) ) {
	//add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_calcfields_form_importer_required_plugins', 10, 2 );
	function handyman_services_calcfields_form_importer_required_plugins($not_installed='', $list='') {
		if (handyman_services_strpos($list, 'calcfields')!==false && !handyman_services_exists_calcfields_form() )
			$not_installed .= '<br>'.esc_html__('Calculated Fields Form', 'handyman-services');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'handyman_services_calcfields_form_importer_set_options' ) ) {
	//add_filter( 'handyman_services_filter_importer_options',	'handyman_services_calcfields_form_importer_set_options', 10, 1 );
	function handyman_services_calcfields_form_importer_set_options($options=array()) {
		if ( in_array('calcfields', handyman_services_storage_get('required_plugins')) && handyman_services_exists_calcfields_form() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_calcfields_form'] = str_replace('posts', 'calcfields_form', $v['file_with_posts']);
				}
			}
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'handyman_services_calcfields_form_importer_show_params' ) ) {
	//add_action( 'handyman_services_action_importer_params',	'handyman_services_calcfields_form_importer_show_params', 10, 1 );
	function handyman_services_calcfields_form_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('calcfields', handyman_services_storage_get('required_plugins')) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_calcfields_form" id="import_calcfields_form" /> <label for="import_calcfields_form"><?php esc_html_e('Import Calculated Fields Form', 'handyman-services'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'handyman_services_calcfields_form_importer_import' ) ) {
	//add_action( 'handyman_services_action_importer_import',	'handyman_services_calcfields_form_importer_import', 10, 2 );
	function handyman_services_calcfields_form_importer_import($importer, $action) {
		if ( $action == 'import_calcfields_form' ) {
			$importer->response['result'] = $importer->import_dump('calcfields_form', esc_html__('Calculated Fields Form', 'handyman-services'));
		}
	}
}

// Display import progress
if ( !function_exists( 'handyman_services_calcfields_form_importer_import_fields' ) ) {
	//add_action( 'handyman_services_action_importer_import_fields',	'handyman_services_calcfields_form_importer_import_fields', 10, 1 );
	function handyman_services_calcfields_form_importer_import_fields($importer) {
		?>
		<tr class="import_calcfields_form">
			<td class="import_progress_item"><?php esc_html_e('Calculated Fields Form', 'handyman-services'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'handyman_services_calcfields_form_importer_export' ) ) {
	//add_action( 'handyman_services_action_importer_export',	'handyman_services_calcfields_form_importer_export', 10, 1 );
	function handyman_services_calcfields_form_importer_export($importer) {
		handyman_services_storage_set('export_calcfields_form', serialize( array(
			CP_CALCULATEDFIELDSF_FORMS_TABLE => $importer->export_dump(CP_CALCULATEDFIELDSF_FORMS_TABLE)
			) )
		);
	}
}

// Display exported data in the fields
if ( !function_exists( 'handyman_services_calcfields_form_importer_export_fields' ) ) {
	//add_action( 'handyman_services_action_importer_export_fields',	'handyman_services_calcfields_form_importer_export_fields', 10, 1 );
	function handyman_services_calcfields_form_importer_export_fields($importer) {
		?>
		<tr>
			<th align="left"><?php esc_html_e('Calculated Fields Form', 'handyman-services'); ?></th>
			<td><?php handyman_services_fpc(handyman_services_get_file_dir('core/core.importer/export/calcfields_form.txt'), handyman_services_storage_get('export_calcfields_form')); ?>
				<a download="calcfields_form.txt" href="<?php echo esc_url(handyman_services_get_file_url('core/core.importer/export/calcfields_form.txt')); ?>"><?php esc_html_e('Download', 'handyman-services'); ?></a>
			</td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Return Calculated forms list list, prepended inherit (if need)
if ( !function_exists( 'handyman_services_get_list_calcfields_form' ) ) {
	function handyman_services_get_list_calcfields_form($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_calcfields_form'))=='') {
			$list = array();
			if (handyman_services_exists_calcfields_form()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT id, form_name FROM " . esc_sql($wpdb->prefix . CP_CALCULATEDFIELDSF_FORMS_TABLE) );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->id] = $row->form_name;
					}
				}
			}
			$list = apply_filters('handyman_services_filter_list_calcfields_form', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_calcfields_form', $list); 
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('handyman_services_calcfields_form_reg_shortcodes')) {
	//add_filter('handyman_services_action_shortcodes_list',	'handyman_services_calcfields_form_reg_shortcodes');
	function handyman_services_calcfields_form_reg_shortcodes() {
		if (handyman_services_storage_isset('shortcodes')) {

			$forms_list = handyman_services_get_list_calcfields_form();

			handyman_services_sc_map_after( 'trx_button', 'CP_CALCULATED_FIELDS', array(
					"title" => esc_html__("Calculated fields form", 'handyman-services'),
					"desc" => esc_html__("Insert calculated fields form", 'handyman-services'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"id" => array(
							"title" => esc_html__("Form ID", 'handyman-services'),
							"desc" => esc_html__("Select Form to insert into current page", 'handyman-services'),
							"value" => "",
							"size" => "medium",
							"options" => $forms_list,
							"type" => "select"
							)
						)
					)
			);
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('handyman_services_calcfields_form_reg_shortcodes_vc')) {
	//add_filter('handyman_services_action_shortcodes_list_vc',	'handyman_services_calcfields_form_reg_shortcodes_vc');
	function handyman_services_calcfields_form_reg_shortcodes_vc() {

		$forms_list = handyman_services_get_list_calcfields_form();

		// Calculated fields form
		vc_map( array(
				"base" => "CP_CALCULATED_FIELDS",
				"name" => esc_html__("Calculated fields form", 'handyman-services'),
				"description" => esc_html__("Insert calculated fields form", 'handyman-services'),
				"category" => esc_html__('Content', 'handyman-services'),
				'icon' => 'icon_trx_calcfields',
				"class" => "trx_sc_single trx_sc_calcfields",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("Form ID", 'handyman-services'),
						"description" => esc_html__("Select Form to insert into current page", 'handyman-services'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($forms_list),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Cp_Calculated_Fields extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}

	}
}
?>
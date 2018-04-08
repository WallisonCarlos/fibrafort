<?php
/* Responsive Poll support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('handyman_services_responsive_poll_theme_setup')) {
	add_action( 'handyman_services_action_before_init_theme', 'handyman_services_responsive_poll_theme_setup', 1 );
	function handyman_services_responsive_poll_theme_setup() {
		// Register shortcode in the shortcodes list
		if (handyman_services_exists_responsive_poll()) {
			add_action('handyman_services_action_add_styles', 					'handyman_services_responsive_poll_frontend_scripts');
			add_action('handyman_services_action_shortcodes_list',				'handyman_services_responsive_poll_reg_shortcodes');
			if (function_exists('handyman_services_exists_visual_composer') && handyman_services_exists_visual_composer())
				add_action('handyman_services_action_shortcodes_list_vc',		'handyman_services_responsive_poll_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'handyman_services_filter_importer_options',			'handyman_services_responsive_poll_importer_set_options', 10, 1 );
				add_action( 'handyman_services_action_importer_params',			'handyman_services_responsive_poll_importer_show_params', 10, 1 );
				add_action( 'handyman_services_action_importer_import',			'handyman_services_responsive_poll_importer_import', 10, 2 );
				add_action( 'handyman_services_action_importer_import_fields',	'handyman_services_responsive_poll_importer_import_fields', 10, 1 );
				add_action( 'handyman_services_action_importer_export',			'handyman_services_responsive_poll_importer_export', 10, 1 );
				add_action( 'handyman_services_action_importer_export_fields',	'handyman_services_responsive_poll_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_responsive_poll_importer_required_plugins', 10, 2 );
			add_filter( 'handyman_services_filter_required_plugins',				'handyman_services_responsive_poll_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'handyman_services_exists_responsive_poll' ) ) {
	function handyman_services_exists_responsive_poll() {
		return class_exists('Weblator_Polling');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'handyman_services_responsive_poll_required_plugins' ) ) {
	//add_filter('handyman_services_filter_required_plugins',	'handyman_services_responsive_poll_required_plugins');
	function handyman_services_responsive_poll_required_plugins($list=array()) {
		if (in_array('responsive_poll', handyman_services_storage_get('required_plugins'))) {
			$path = handyman_services_get_file_dir('plugins/install/responsive-poll.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Responsive Poll', 'handyman-services'),
					'slug' 		=> 'responsive-poll',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'handyman_services_responsive_poll_frontend_scripts' ) ) {
	//add_action( 'handyman_services_action_add_styles', 'handyman_services_responsive_poll_frontend_scripts' );
	function handyman_services_responsive_poll_frontend_scripts() {
		if (file_exists(handyman_services_get_file_dir('css/plugin.responsive-poll.css')))
			handyman_services_enqueue_style( 'handyman_services-plugin.responsive-poll-style',  handyman_services_get_file_url('css/plugin.responsive-poll.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'handyman_services_responsive_poll_importer_required_plugins' ) ) {
	//add_filter( 'handyman_services_filter_importer_required_plugins',	'handyman_services_responsive_poll_importer_required_plugins', 10, 2 );
	function handyman_services_responsive_poll_importer_required_plugins($not_installed='', $list='') {
		if (handyman_services_strpos($list, 'responsive_poll')!==false && !handyman_services_exists_responsive_poll() )
			$not_installed .= '<br>' . esc_html__('Responsive Poll', 'handyman-services');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'handyman_services_responsive_poll_importer_set_options' ) ) {
	//add_filter( 'handyman_services_filter_importer_options',	'handyman_services_responsive_poll_importer_set_options', 10, 1 );
	function handyman_services_responsive_poll_importer_set_options($options=array()) {
		if ( in_array('responsive_poll', handyman_services_storage_get('required_plugins')) && handyman_services_exists_responsive_poll() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_responsive_poll'] = str_replace('posts', 'responsive_poll', $v['file_with_posts']);
				}
			}
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'handyman_services_responsive_poll_importer_show_params' ) ) {
	//add_action( 'handyman_services_action_importer_params',	'handyman_services_responsive_poll_importer_show_params', 10, 1 );
	function handyman_services_responsive_poll_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('responsive_poll', handyman_services_storage_get('required_plugins')) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_responsive_poll" id="import_responsive_poll" /> <label for="import_responsive_poll"><?php esc_html_e('Import Responsive Poll', 'handyman-services'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'handyman_services_responsive_poll_importer_import' ) ) {
	//add_action( 'handyman_services_action_importer_import',	'handyman_services_responsive_poll_importer_import', 10, 2 );
	function handyman_services_responsive_poll_importer_import($importer, $action) {
		if ( $action == 'import_responsive_poll' ) {
			$importer->response['result'] = $importer->import_dump('responsive_poll', esc_html__('Responsive Poll', 'handyman-services'));
		}
	}
}

// Display import progress
if ( !function_exists( 'handyman_services_responsive_poll_importer_import_fields' ) ) {
	//add_action( 'handyman_services_action_importer_import_fields',	'handyman_services_responsive_poll_importer_import_fields', 10, 1 );
	function handyman_services_responsive_poll_importer_import_fields($importer) {
		?>
		<tr class="import_responsive_poll">
			<td class="import_progress_item"><?php esc_html_e('Responsive Poll', 'handyman-services'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'handyman_services_responsive_poll_importer_export' ) ) {
	//add_action( 'handyman_services_action_importer_export',	'handyman_services_responsive_poll_importer_export', 10, 1 );
	function handyman_services_responsive_poll_importer_export($importer) {
		handyman_services_storage_set('export_responsive_poll', serialize( array(
			'weblator_polls'		=> $importer->export_dump('weblator_polls'),
			'weblator_poll_options'	=> $importer->export_dump('weblator_poll_options'),
			'weblator_poll_votes'	=> $importer->export_dump('weblator_poll_votes')
			) )
		);
	}
}

// Display exported data in the fields
if ( !function_exists( 'handyman_services_responsive_poll_importer_export_fields' ) ) {
	//add_action( 'handyman_services_action_importer_export_fields',	'handyman_services_responsive_poll_importer_export_fields', 10, 1 );
	function handyman_services_responsive_poll_importer_export_fields($importer) {
		?>
		<tr>
			<th align="left"><?php esc_html_e('Responsive Poll', 'handyman-services'); ?></th>
			<td><?php handyman_services_fpc(handyman_services_get_file_dir('core/core.importer/export/responsive_poll.txt'), handyman_services_storage_get('export_responsive_poll')); ?>
				<a download="responsive_poll.txt" href="<?php echo esc_url(handyman_services_get_file_url('core/core.importer/export/responsive_poll.txt')); ?>"><?php esc_html_e('Download', 'handyman-services'); ?></a>
			</td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Return Responsive Pollst list, prepended inherit (if need)
if ( !function_exists( 'handyman_services_get_list_responsive_polls' ) ) {
	function handyman_services_get_list_responsive_polls($prepend_inherit=false) {
		if (($list = handyman_services_storage_get('list_responsive_polls'))=='') {
			$list = array();
			if (handyman_services_exists_responsive_poll()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT id, poll_name FROM " . esc_sql($wpdb->prefix . "weblator_polls") );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->id] = $row->poll_name;
					}
				}
			}
			$list = apply_filters('handyman_services_filter_list_responsive_polls', $list);
			if (handyman_services_get_theme_setting('use_list_cache')) handyman_services_storage_set('list_responsive_polls', $list);
		}
		return $prepend_inherit ? handyman_services_array_merge(array('inherit' => esc_html__("Inherit", 'handyman-services')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('handyman_services_responsive_poll_reg_shortcodes')) {
	//add_filter('handyman_services_action_shortcodes_list',	'handyman_services_responsive_poll_reg_shortcodes');
	function handyman_services_responsive_poll_reg_shortcodes() {
		if (handyman_services_storage_isset('shortcodes')) {

			$polls_list = handyman_services_get_list_responsive_polls();

			handyman_services_sc_map_before('trx_popup', 'poll', array(
					"title" => esc_html__("Poll", 'handyman-services'),
					"desc" => esc_html__("Insert poll", 'handyman-services'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"id" => array(
							"title" => esc_html__("Poll ID", 'handyman-services'),
							"desc" => esc_html__("Select Poll to insert into current page", 'handyman-services'),
							"value" => "",
							"size" => "medium",
							"options" => $polls_list,
							"type" => "select"
							)
						)
					)
			);
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('handyman_services_responsive_poll_reg_shortcodes_vc')) {
	//add_filter('handyman_services_action_shortcodes_list_vc',	'handyman_services_responsive_poll_reg_shortcodes_vc');
	function handyman_services_responsive_poll_reg_shortcodes_vc() {

		$polls_list = handyman_services_get_list_responsive_polls();

		// Calculated fields form
		vc_map( array(
				"base" => "poll",
				"name" => esc_html__("Poll", 'handyman-services'),
				"description" => esc_html__("Insert poll", 'handyman-services'),
				"category" => esc_html__('Content', 'handyman-services'),
				'icon' => 'icon_trx_poll',
				"class" => "trx_sc_single trx_sc_poll",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("Poll ID", 'handyman-services'),
						"description" => esc_html__("Select Poll to insert into current page", 'handyman-services'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($polls_list),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Poll extends HANDYMAN_SERVICES_VC_ShortCodeSingle {}

	}
}
?>
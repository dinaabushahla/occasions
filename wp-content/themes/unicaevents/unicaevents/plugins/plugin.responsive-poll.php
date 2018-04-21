<?php
/* Responsive Poll support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('unicaevents_responsive_poll_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_responsive_poll_theme_setup', 1 );
	function unicaevents_responsive_poll_theme_setup() {
		// Add shortcode in the shortcodes list
		if (unicaevents_exists_responsive_poll()) {
			add_action('unicaevents_action_shortcodes_list',				'unicaevents_responsive_poll_reg_shortcodes');
			if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
				add_action('unicaevents_action_shortcodes_list_vc',		'unicaevents_responsive_poll_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'unicaevents_filter_importer_options',			'unicaevents_responsive_poll_importer_set_options', 10, 1 );
				add_action( 'unicaevents_action_importer_params',			'unicaevents_responsive_poll_importer_show_params', 10, 1 );
				add_action( 'unicaevents_action_importer_import',			'unicaevents_responsive_poll_importer_import', 10, 2 );
				add_action( 'unicaevents_action_importer_import_fields',	'unicaevents_responsive_poll_importer_import_fields', 10, 1 );
				add_action( 'unicaevents_action_importer_export',			'unicaevents_responsive_poll_importer_export', 10, 1 );
				add_action( 'unicaevents_action_importer_export_fields',	'unicaevents_responsive_poll_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_responsive_poll_importer_required_plugins' );
			add_filter( 'unicaevents_filter_required_plugins',				'unicaevents_responsive_poll_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'unicaevents_exists_responsive_poll' ) ) {
	function unicaevents_exists_responsive_poll() {
		return class_exists('Weblator_Polling');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'unicaevents_responsive_poll_required_plugins' ) ) {
	//add_filter('unicaevents_filter_required_plugins',	'unicaevents_responsive_poll_required_plugins');
	function unicaevents_responsive_poll_required_plugins($list=array()) {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('responsive_poll', $UNICAEVENTS_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Responsive Poll',
					'slug' 		=> 'responsive-poll',
					'source'	=> unicaevents_get_file_dir('plugins/install/responsive-poll.zip'),
					'required' 	=> false
					);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'unicaevents_responsive_poll_importer_required_plugins' ) ) {
	//add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_responsive_poll_importer_required_plugins' );
	function unicaevents_responsive_poll_importer_required_plugins($not_installed='') {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('responsive_poll', $UNICAEVENTS_GLOBALS['required_plugins']) && !unicaevents_exists_responsive_poll() )
			$not_installed .= '<br>Responsive Poll';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'unicaevents_responsive_poll_importer_set_options' ) ) {
	//add_filter( 'unicaevents_filter_importer_options',	'unicaevents_responsive_poll_importer_set_options', 10, 1 );
	function unicaevents_responsive_poll_importer_set_options($options=array()) {
		if (is_array($options)) {
			$options['file_with_responsive_poll'] = 'demo/responsive_poll.txt';			// Name of the file with Responsive Poll data
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'unicaevents_responsive_poll_importer_show_params' ) ) {
	//add_action( 'unicaevents_action_importer_params',	'unicaevents_responsive_poll_importer_show_params', 10, 1 );
	function unicaevents_responsive_poll_importer_show_params($importer) {
		global $UNICAEVENTS_GLOBALS;
		?>
		<input type="checkbox" <?php echo in_array('responsive_poll', $UNICAEVENTS_GLOBALS['required_plugins']) ? 'checked="checked"' : ''; ?> value="1" name="import_responsive_poll" id="import_responsive_poll" /> <label for="import_responsive_poll"><?php esc_html_e('Import Responsive Poll', 'unicaevents'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'unicaevents_responsive_poll_importer_import' ) ) {
	//add_action( 'unicaevents_action_importer_import',	'unicaevents_responsive_poll_importer_import', 10, 2 );
	function unicaevents_responsive_poll_importer_import($importer, $action) {
		if ( $action == 'import_responsive_poll' ) {
			$importer->import_dump('responsive_poll', esc_html__('Responsive Poll', 'unicaevents'));
		}
	}
}

// Display import progress
if ( !function_exists( 'unicaevents_responsive_poll_importer_import_fields' ) ) {
	//add_action( 'unicaevents_action_importer_import_fields',	'unicaevents_responsive_poll_importer_import_fields', 10, 1 );
	function unicaevents_responsive_poll_importer_import_fields($importer) {
		?>
		<tr class="import_responsive_poll">
			<td class="import_progress_item"><?php esc_html_e('Responsive Poll', 'unicaevents'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'unicaevents_responsive_poll_importer_export' ) ) {
	//add_action( 'unicaevents_action_importer_export',	'unicaevents_responsive_poll_importer_export', 10, 1 );
	function unicaevents_responsive_poll_importer_export($importer) {
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['export_responsive_poll'] = serialize( array(
			'weblator_polls'		=> $importer->export_dump('weblator_polls'),
			'weblator_poll_options'	=> $importer->export_dump('weblator_poll_options'),
			'weblator_poll_votes'	=> $importer->export_dump('weblator_poll_votes')
			)
		);
	}
}

// Display exported data in the fields
if ( !function_exists( 'unicaevents_responsive_poll_importer_export_fields' ) ) {
	//add_action( 'unicaevents_action_importer_export_fields',	'unicaevents_responsive_poll_importer_export_fields', 10, 1 );
	function unicaevents_responsive_poll_importer_export_fields($importer) {
		global $UNICAEVENTS_GLOBALS;
		?>
		<tr>
			<th align="left"><?php esc_html_e('Responsive Poll', 'unicaevents'); ?></th>
			<td><?php unicaevents_fpc(unicaevents_get_file_dir('core/core.importer/export/responsive_poll.txt'), $UNICAEVENTS_GLOBALS['export_responsive_poll']); ?>
				<a download="responsive_poll.txt" href="<?php echo esc_url(unicaevents_get_file_url('core/core.importer/export/responsive_poll.txt')); ?>"><?php esc_html_e('Download', 'unicaevents'); ?></a>
			</td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Return Responsive Pollst list, prepended inherit (if need)
if ( !function_exists( 'unicaevents_get_list_responsive_polls' ) ) {
	function unicaevents_get_list_responsive_polls($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_responsive_polls']))
			$list = $UNICAEVENTS_GLOBALS['list_responsive_polls'];
		else {
			$list = array();
			if (unicaevents_exists_responsive_poll()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT id, poll_name FROM " . esc_sql($wpdb->prefix . 'weblator_polls') );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->id] = $row->poll_name;
					}
				}
			}
			$UNICAEVENTS_GLOBALS['list_responsive_polls'] = $list = apply_filters('unicaevents_filter_list_responsive_polls', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Add shortcode in the shortcodes list
if (!function_exists('unicaevents_responsive_poll_reg_shortcodes')) {
	//add_filter('unicaevents_action_shortcodes_list',	'unicaevents_responsive_poll_reg_shortcodes');
	function unicaevents_responsive_poll_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['shortcodes'])) {

			$polls_list = unicaevents_get_list_responsive_polls();

			unicaevents_array_insert_before($UNICAEVENTS_GLOBALS['shortcodes'], 'trx_popup', array(

				// Calculated fields form
				'poll' => array(
					"title" => esc_html__("Poll", "unicaevents"),
					"desc" => esc_html__("Insert poll", "unicaevents"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"id" => array(
							"title" => esc_html__("Poll ID", "unicaevents"),
							"desc" => esc_html__("Select Poll to insert into current page", "unicaevents"),
							"value" => "",
							"size" => "medium",
							"options" => $polls_list,
							"type" => "select"
						)
					)
				)

			));
		}
	}
}


// Add shortcode in the VC shortcodes list
if (!function_exists('unicaevents_responsive_poll_reg_shortcodes_vc')) {
	//add_filter('unicaevents_action_shortcodes_list_vc',	'unicaevents_responsive_poll_reg_shortcodes_vc');
	function unicaevents_responsive_poll_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;

		$polls_list = unicaevents_get_list_responsive_polls();

		// Calculated fields form
		vc_map( array(
				"base" => "poll",
				"name" => esc_html__("Poll", "unicaevents"),
				"description" => esc_html__("Insert poll", "unicaevents"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_poll',
				"class" => "trx_sc_single trx_sc_poll",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("Poll ID", "unicaevents"),
						"description" => esc_html__("Select Poll to insert into current page", "unicaevents"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($polls_list),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Poll extends UNICAEVENTS_VC_ShortCodeSingle {}

	}
}
?>
<?php
/* Calculated fields form support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('unicaevents_calcfields_form_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_calcfields_form_theme_setup', 1 );
	function unicaevents_calcfields_form_theme_setup() {
		// Add shortcode in the shortcodes list
		if (unicaevents_exists_calcfields_form()) {
			add_action('unicaevents_action_shortcodes_list',				'unicaevents_calcfields_form_reg_shortcodes');
			if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
				add_action('unicaevents_action_shortcodes_list_vc',		'unicaevents_calcfields_form_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'unicaevents_filter_importer_options',			'unicaevents_calcfields_form_importer_set_options', 10, 1 );
				add_action( 'unicaevents_action_importer_params',			'unicaevents_calcfields_form_importer_show_params', 10, 1 );
				add_action( 'unicaevents_action_importer_import',			'unicaevents_calcfields_form_importer_import', 10, 2 );
				add_action( 'unicaevents_action_importer_import_fields',	'unicaevents_calcfields_form_importer_import_fields', 10, 1 );
				add_action( 'unicaevents_action_importer_export',			'unicaevents_calcfields_form_importer_export', 10, 1 );
				add_action( 'unicaevents_action_importer_export_fields',	'unicaevents_calcfields_form_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_calcfields_form_importer_required_plugins');
			add_filter( 'unicaevents_filter_required_plugins',				'unicaevents_calcfields_form_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'unicaevents_exists_calcfields_form' ) ) {
	function unicaevents_exists_calcfields_form() {
		return defined('CP_SCHEME');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'unicaevents_calcfields_form_required_plugins' ) ) {
	//add_filter('unicaevents_filter_required_plugins',	'unicaevents_calcfields_form_required_plugins');
	function unicaevents_calcfields_form_required_plugins($list=array()) {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('calcfields', $UNICAEVENTS_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Calculated Fields Form',
					'slug' 		=> 'calculated-fields-form',
					'required' 	=> false
					);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'unicaevents_calcfields_form_importer_required_plugins' ) ) {
	//add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_calcfields_form_importer_required_plugins' );
	function unicaevents_calcfields_form_importer_required_plugins($not_installed='') {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('calcfields', $UNICAEVENTS_GLOBALS['required_plugins']) && !unicaevents_exists_calcfields_form() )
			$not_installed .= '<br>Calculated Fields Form';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'unicaevents_calcfields_form_importer_set_options' ) ) {
	//add_filter( 'unicaevents_filter_importer_options',	'unicaevents_calcfields_form_importer_set_options', 10, 1 );
	function unicaevents_calcfields_form_importer_set_options($options=array()) {
		if (is_array($options)) {
			$options['file_with_calcfields_form'] = 'demo/calcfields_form.txt';			// Name of the file with Calculated Fields Form data
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'unicaevents_calcfields_form_importer_show_params' ) ) {
	//add_action( 'unicaevents_action_importer_params',	'unicaevents_calcfields_form_importer_show_params', 10, 1 );
	function unicaevents_calcfields_form_importer_show_params($importer) {
		global $UNICAEVENTS_GLOBALS;
		?>
		<input type="checkbox" <?php echo in_array('calcfields', $UNICAEVENTS_GLOBALS['required_plugins']) ? 'checked="checked"' : ''; ?> value="1" name="import_calcfields_form" id="import_calcfields_form" /> <label for="import_calcfields_form"><?php esc_html_e('Import Calculated Fields Form', 'unicaevents'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'unicaevents_calcfields_form_importer_import' ) ) {
	//add_action( 'unicaevents_action_importer_import',	'unicaevents_calcfields_form_importer_import', 10, 2 );
	function unicaevents_calcfields_form_importer_import($importer, $action) {
		if ( $action == 'import_calcfields_form' ) {
			$importer->import_dump('calcfields_form', esc_html__('Calculated Fields Form', 'unicaevents'));
		}
	}
}

// Display import progress
if ( !function_exists( 'unicaevents_calcfields_form_importer_import_fields' ) ) {
	//add_action( 'unicaevents_action_importer_import_fields',	'unicaevents_calcfields_form_importer_import_fields', 10, 1 );
	function unicaevents_calcfields_form_importer_import_fields($importer) {
		?>
		<tr class="import_calcfields_form">
			<td class="import_progress_item"><?php esc_html_e('Calculated Fields Form', 'unicaevents'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'unicaevents_calcfields_form_importer_export' ) ) {
	//add_action( 'unicaevents_action_importer_export',	'unicaevents_calcfields_form_importer_export', 10, 1 );
	function unicaevents_calcfields_form_importer_export($importer) {
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['export_calcfields_form'] = serialize( array(
			CP_CALCULATEDFIELDSF_FORMS_TABLE => $importer->export_dump(CP_CALCULATEDFIELDSF_FORMS_TABLE)
			)
		);
	}
}

// Display exported data in the fields
if ( !function_exists( 'unicaevents_calcfields_form_importer_export_fields' ) ) {
	//add_action( 'unicaevents_action_importer_export_fields',	'unicaevents_calcfields_form_importer_export_fields', 10, 1 );
	function unicaevents_calcfields_form_importer_export_fields($importer) {
		global $UNICAEVENTS_GLOBALS;
		?>
		<tr>
			<th align="left"><?php esc_html_e('Calculated Fields Form', 'unicaevents'); ?></th>
			<td><?php unicaevents_fpc(unicaevents_get_file_dir('core/core.importer/export/calcfields_form.txt'), $UNICAEVENTS_GLOBALS['export_calcfields_form']); ?>
				<a download="calcfields_form.txt" href="<?php echo esc_url(unicaevents_get_file_url('core/core.importer/export/calcfields_form.txt')); ?>"><?php esc_html_e('Download', 'unicaevents'); ?></a>
			</td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Return Calculated forms list list, prepended inherit (if need)
if ( !function_exists( 'unicaevents_get_list_calcfields_form' ) ) {
	function unicaevents_get_list_calcfields_form($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_calcfields_form']))
			$list = $UNICAEVENTS_GLOBALS['list_calcfields_form'];
		else {
			$list = array();
			if (unicaevents_exists_calcfields_form()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT id, form_name FROM " . esc_sql($wpdb->prefix . CP_CALCULATEDFIELDSF_FORMS_TABLE) );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->id] = $row->form_name;
					}
				}
			}
			$UNICAEVENTS_GLOBALS['list_calcfields_form'] = $list = apply_filters('unicaevents_filter_list_calcfields_form', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Add shortcode in the shortcodes list
if (!function_exists('unicaevents_calcfields_form_reg_shortcodes')) {
	//add_filter('unicaevents_action_shortcodes_list',	'unicaevents_calcfields_form_reg_shortcodes');
	function unicaevents_calcfields_form_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['shortcodes'])) {

			$forms_list = unicaevents_get_list_calcfields_form();

			unicaevents_array_insert_after($UNICAEVENTS_GLOBALS['shortcodes'], 'trx_button', array(

				// Calculated fields form
				'CP_CALCULATED_FIELDS' => array(
					"title" => esc_html__("Calculated fields form", "unicaevents"),
					"desc" => esc_html__("Insert calculated fields form", "unicaevents"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"id" => array(
							"title" => esc_html__("Form ID", "unicaevents"),
							"desc" => esc_html__("Select Form to insert into current page", "unicaevents"),
							"value" => "",
							"size" => "medium",
							"options" => $forms_list,
							"type" => "select"
						)
					)
				)

			));
		}
	}
}


// Add shortcode in the VC shortcodes list
if (!function_exists('unicaevents_calcfields_form_reg_shortcodes_vc')) {
	//add_filter('unicaevents_action_shortcodes_list_vc',	'unicaevents_calcfields_form_reg_shortcodes_vc');
	function unicaevents_calcfields_form_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;

		$forms_list = unicaevents_get_list_calcfields_form();

		// Calculated fields form
		vc_map( array(
				"base" => "CP_CALCULATED_FIELDS",
				"name" => esc_html__("Calculated fields form", "unicaevents"),
				"description" => esc_html__("Insert calculated fields form", "unicaevents"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_calcfields',
				"class" => "trx_sc_single trx_sc_calcfields",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("Form ID", "unicaevents"),
						"description" => esc_html__("Select Form to insert into current page", "unicaevents"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($forms_list),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Cp_Calculated_Fields extends UNICAEVENTS_VC_ShortCodeSingle {}

	}
}
?>
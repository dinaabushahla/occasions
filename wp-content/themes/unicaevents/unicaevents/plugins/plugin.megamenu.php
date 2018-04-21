<?php
/* Mega Main Menu support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('unicaevents_megamenu_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_megamenu_theme_setup', 1 );
	function unicaevents_megamenu_theme_setup() {
		if (unicaevents_exists_megamenu()) {
			if (is_admin()) {
				add_filter( 'unicaevents_filter_importer_options',				'unicaevents_megamenu_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'unicaevents_filter_importer_required_plugins',		'unicaevents_megamenu_importer_required_plugins' );
			add_filter( 'unicaevents_filter_required_plugins',					'unicaevents_megamenu_required_plugins' );
		}
	}
}

// Check if MegaMenu installed and activated
if ( !function_exists( 'unicaevents_exists_megamenu' ) ) {
	function unicaevents_exists_megamenu() {
		return class_exists('mega_main_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'unicaevents_megamenu_required_plugins' ) ) {
	//add_filter('unicaevents_filter_required_plugins',	'unicaevents_megamenu_required_plugins');
	function unicaevents_megamenu_required_plugins($list=array()) {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('mega_main_menu', $UNICAEVENTS_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Mega Main Menu',
					'slug' 		=> 'mega_main_menu',
					'source'	=> unicaevents_get_file_dir('plugins/install/mega_main_menu.zip'),
					'required' 	=> false
				);

		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Mega Menu in the required plugins
if ( !function_exists( 'unicaevents_megamenu_importer_required_plugins' ) ) {
	//add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_megamenu_importer_required_plugins' );
	function unicaevents_megamenu_importer_required_plugins($not_installed='') {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('mega_main_menu', $UNICAEVENTS_GLOBALS['required_plugins']) && !unicaevents_exists_megamenu())
			$not_installed .= '<br>Mega Main Menu';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'unicaevents_megamenu_importer_set_options' ) ) {
	//add_filter( 'unicaevents_filter_importer_options',	'unicaevents_megamenu_importer_set_options' );
	function unicaevents_megamenu_importer_set_options($options=array()) {
		if (is_array($options)) {
			$options['additional_options'][] = 'mega_main_menu_options';		// Add slugs to export options for this plugin

		}
		return $options;
	}
}
?>
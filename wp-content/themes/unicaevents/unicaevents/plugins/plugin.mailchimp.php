<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('unicaevents_mailchimp_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_mailchimp_theme_setup', 1 );
	function unicaevents_mailchimp_theme_setup() {
		if (unicaevents_exists_mailchimp()) {
			if (is_admin()) {
				add_filter( 'unicaevents_filter_importer_options',				'unicaevents_mailchimp_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'unicaevents_filter_importer_required_plugins',		'unicaevents_mailchimp_importer_required_plugins' );
			add_filter( 'unicaevents_filter_required_plugins',					'unicaevents_mailchimp_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'unicaevents_exists_mailchimp' ) ) {
	function unicaevents_exists_mailchimp() {
		return function_exists('mc4wp_load_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'unicaevents_mailchimp_required_plugins' ) ) {
	//add_filter('unicaevents_filter_required_plugins',	'unicaevents_mailchimp_required_plugins');
	function unicaevents_mailchimp_required_plugins($list=array()) {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('mailchimp', $UNICAEVENTS_GLOBALS['required_plugins']))
			$list[] = array(
				'name' 		=> 'MailChimp for WP',
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Mail Chimp in the required plugins
if ( !function_exists( 'unicaevents_mailchimp_importer_required_plugins' ) ) {
	//add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_mailchimp_importer_required_plugins' );
	function unicaevents_mailchimp_importer_required_plugins($not_installed='') {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('mailchimp', $UNICAEVENTS_GLOBALS['required_plugins']) && !unicaevents_exists_mailchimp() )
			$not_installed .= '<br>Mail Chimp';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'unicaevents_mailchimp_importer_set_options' ) ) {
	//add_filter( 'unicaevents_filter_importer_options',	'unicaevents_mailchimp_importer_set_options' );
	function unicaevents_mailchimp_importer_set_options($options=array()) {
		if (is_array($options)) {
			$options['additional_options'][] = 'mc4wp_lite_checkbox';		// Add slugs to export options for this plugin
			$options['additional_options'][] = 'mc4wp_lite_form';
		}
		return $options;
	}
}
?>
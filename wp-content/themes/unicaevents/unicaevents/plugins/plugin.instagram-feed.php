<?php
/* Instagram Feed support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('unicaevents_instagram_feed_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_instagram_feed_theme_setup', 1 );
	function unicaevents_instagram_feed_theme_setup() {
		if (unicaevents_exists_instagram_feed()) {
			if (is_admin()) {
				add_filter( 'unicaevents_filter_importer_options',				'unicaevents_instagram_feed_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'unicaevents_filter_importer_required_plugins',		'unicaevents_instagram_feed_importer_required_plugins' );
			add_filter( 'unicaevents_filter_required_plugins',					'unicaevents_instagram_feed_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'unicaevents_exists_instagram_feed' ) ) {
	function unicaevents_exists_instagram_feed() {
		return defined('SBIVER');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'unicaevents_instagram_feed_required_plugins' ) ) {
	//add_filter('unicaevents_filter_required_plugins',	'unicaevents_instagram_feed_required_plugins');
	function unicaevents_instagram_feed_required_plugins($list=array()) {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('instagram_feed', $UNICAEVENTS_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Instagram Feed',
					'slug' 		=> 'instagram-feed',
					'required' 	=> false
				);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Feed in the required plugins
if ( !function_exists( 'unicaevents_instagram_feed_importer_required_plugins' ) ) {
	//add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_instagram_feed_importer_required_plugins' );
	function unicaevents_instagram_feed_importer_required_plugins($not_installed='') {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('instagram_feed', $UNICAEVENTS_GLOBALS['required_plugins']) && !unicaevents_exists_instagram_feed() )
			$not_installed .= '<br>Instagram Feed';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'unicaevents_instagram_feed_importer_set_options' ) ) {
	//add_filter( 'unicaevents_filter_importer_options',	'unicaevents_instagram_feed_importer_set_options' );
	function unicaevents_instagram_feed_importer_set_options($options=array()) {
		if (is_array($options)) {
			$options['additional_options'][] = 'sb_instagram_settings';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}
?>
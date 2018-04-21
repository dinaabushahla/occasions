<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('unicaevents_instagram_widget_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_instagram_widget_theme_setup', 1 );
	function unicaevents_instagram_widget_theme_setup() {
		if (is_admin()) {
			add_filter( 'unicaevents_filter_importer_required_plugins',		'unicaevents_instagram_widget_importer_required_plugins' );
			add_filter( 'unicaevents_filter_required_plugins',					'unicaevents_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'unicaevents_exists_instagram_widget' ) ) {
	function unicaevents_exists_instagram_widget() {
		return unicaevents_widget_is_active('wp-instagram-widget/wp-instagram-widget');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'unicaevents_instagram_widget_required_plugins' ) ) {
	//add_filter('unicaevents_filter_required_plugins',	'unicaevents_instagram_widget_required_plugins');
	function unicaevents_instagram_widget_required_plugins($list=array()) {
			$list[] = array(
					'name' 		=> 'Instagram Widget',
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'unicaevents_instagram_widget_importer_required_plugins' ) ) {
	//add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_instagram_widget_importer_required_plugins' );
	function unicaevents_instagram_widget_importer_required_plugins($not_installed='') {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('instagram_widget', $UNICAEVENTS_GLOBALS['required_plugins']) && !unicaevents_exists_instagram_widget() )
			$not_installed .= '<br>WP Instagram Widget';
		return $not_installed;
	}
}
?>
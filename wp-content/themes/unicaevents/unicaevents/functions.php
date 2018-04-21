<?php
/**
 * Theme sprecific functions and definitions
 */


/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'unicaevents_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_theme_setup', 1 );
	function unicaevents_theme_setup() {

		// Register theme menus
		add_filter( 'unicaevents_filter_add_theme_menus',		'unicaevents_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'unicaevents_filter_add_theme_sidebars',	'unicaevents_add_theme_sidebars' );

		// Set Theme required plugins
		add_filter( 'unicaevents_filter_required_plugins',		'unicaevents_set_required_plugins', 9 );

		// Set options for importer
		add_filter( 'unicaevents_filter_importer_options',		'unicaevents_set_importer_options' );

	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'unicaevents_add_theme_menus' ) ) {
	//add_filter( 'unicaevents_filter_add_theme_menus', 'unicaevents_add_theme_menus' );
	function unicaevents_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = esc_html__('Footer Menu', 'unicaevents');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'unicaevents_add_theme_sidebars' ) ) {
	//add_filter( 'unicaevents_filter_add_theme_sidebars',	'unicaevents_add_theme_sidebars' );
	function unicaevents_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'unicaevents' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'unicaevents' )
			);
			if (function_exists('unicaevents_exists_woocommerce') && unicaevents_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'unicaevents' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Set theme required plugins
if ( !function_exists( 'unicaevents_set_required_plugins' ) ) {
	//add_filter( 'unicaevents_filter_required_plugins', 'unicaevents_set_required_plugins', 9, 1 );
	function unicaevents_set_required_plugins($list=array()) {
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['required_plugins'] = array(
// Attention!
// Plugin Booking Calendar not compatible with bbPress and BuddyPress!
// And it generate notice messages, that break AJAX installer!
// Uncomment it if your theme not use bbPress and BuddyPress
//			'booking',
//			'buddypress',		// Attention! This slug used to install both BuddyPress and bbPress
//			'calcfields',
			'essgrids',
			'instagram_feed',
			'instagram_widget',
//			'learndash',
//			'mailchimp',
//			'mega_main_menu',
//			'responsive_poll',
			'revslider',
//			'tribe_events',
//			'trx_donations',
			'trx_utils',
			'visual_composer',
//			'woocommerce'
		);
		return $list;
	}
}


// Set theme specific importer options
if ( !function_exists( 'unicaevents_set_importer_options' ) ) {
	//add_filter( 'unicaevents_filter_importer_options',	'unicaevents_set_importer_options' );
	function unicaevents_set_importer_options($options=array()) {
		if (is_array($options)) {
			// Please, note! The following text strings should not be translated, 
			// since these are article titles, menu locations, etc. used by us in the demo-website. 
			// They are required when setting some of the WP parameters during demo data installation 
			// and cannot be changed/translated into other languages.
			$options['debug'] = unicaevents_get_theme_option('debug_mode')=='yes';
			$options['domain_dev'] = esc_url('_trex2.themerex.dnw');
			$options['domain_demo'] = esc_url('trex2.dev.themerex.net');
			$options['menus'] = array(																	// Menus locations and names (NOT FOR TRANSLATION)
				'menu-main'	  => esc_html__('Main menu', 'unicaevents'),
				'menu-user'	  => esc_html__('User menu', 'unicaevents'),
				'menu-footer' => esc_html__('Footer menu', 'unicaevents'),
				'menu-outer'  => esc_html__('Main menu', 'unicaevents')
			);
		}
		return $options;
	}
}


/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files
if (!isset($_POST['action']) || $_POST['action']!="heartbeat") {
	require_once get_template_directory().'/fw/loader.php';
}
?>
<?php
/**
 * UnicaEvents Framework
 *
 * @package unicaevents
 * @since unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'UNICAEVENTS_FW_DIR' ) )		define( 'UNICAEVENTS_FW_DIR', '/fw/' );

// Theme timing
if ( ! defined( 'UNICAEVENTS_START_TIME' ) )	define( 'UNICAEVENTS_START_TIME', microtime());			// Framework start time
if ( ! defined( 'UNICAEVENTS_START_MEMORY' ) )	define( 'UNICAEVENTS_START_MEMORY', memory_get_usage());	// Memory usage before core loading

// Global variables storage
global $UNICAEVENTS_GLOBALS;
$UNICAEVENTS_GLOBALS = array(
	'theme_slug'	=> 'unicaevents',	// Theme slug (used as prefix for theme's functions, text domain, global variables, etc.)
	'page_template'	=> '',			// Storage for current page template name (used in the inheritance system)
    'allowed_tags'	=> array(		// Allowed tags list (with attributes) in translations
    	'b' => array(),
    	'strong' => array(),
    	'i' => array(),
    	'em' => array(),
    	'u' => array(),
    	'a' => array(
			'href' => array(),
			'title' => array(),
			'target' => array(),
			'id' => array(),
			'class' => array()
		),
    	'span' => array(
			'id' => array(),
			'class' => array()
		)
    )	
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'unicaevents_loader_theme_setup', 20 );
	function unicaevents_loader_theme_setup() {
		
		// Init admin url and nonce
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['admin_url']	= get_admin_url();
		$UNICAEVENTS_GLOBALS['admin_nonce']= wp_create_nonce($UNICAEVENTS_GLOBALS['admin_url']);
		$UNICAEVENTS_GLOBALS['ajax_url']	= admin_url('admin-ajax.php');
		$UNICAEVENTS_GLOBALS['ajax_nonce']	= wp_create_nonce($UNICAEVENTS_GLOBALS['ajax_url']);

		// Before init theme
		do_action('unicaevents_action_before_init_theme');

		// Load current values for main theme options
		unicaevents_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			unicaevents_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */

// Manual load important libraries before load all rest files
// core.strings must be first - we use unicaevents_str...() in the unicaevents_get_file_dir()
require_once (file_exists(get_stylesheet_directory().(UNICAEVENTS_FW_DIR).'core/core.strings.php') ? get_stylesheet_directory() : get_template_directory()).(UNICAEVENTS_FW_DIR).'core/core.strings.php';
// core.files must be first - we use unicaevents_get_file_dir() to include all rest parts
require_once (file_exists(get_stylesheet_directory().(UNICAEVENTS_FW_DIR).'core/core.files.php') ? get_stylesheet_directory() : get_template_directory()).(UNICAEVENTS_FW_DIR).'core/core.files.php';

// Include custom theme files
unicaevents_autoload_folder( 'includes' );

// Include core files
unicaevents_autoload_folder( 'core' );

// Include theme-specific plugins and post types
unicaevents_autoload_folder( 'plugins' );

// Include theme templates
unicaevents_autoload_folder( 'templates' );

// Include theme widgets
unicaevents_autoload_folder( 'widgets' );
?>
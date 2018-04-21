<?php
if (is_admin() 
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
	require_once unicaevents_get_file_dir('core/core.shortcodes/shortcodes_vc_classes.php');
}

// Width and height params
if ( !function_exists( 'unicaevents_vc_width' ) ) {
	function unicaevents_vc_width($w='') {
		global $UNICAEVENTS_GLOBALS;
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", "unicaevents"),
			"description" => wp_kses( __("Width (in pixels or percent) of the current element", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"group" => esc_html__('Size &amp; Margins', 'unicaevents'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'unicaevents_vc_height' ) ) {
	function unicaevents_vc_height($h='') {
		global $UNICAEVENTS_GLOBALS;
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", "unicaevents"),
			"description" => wp_kses( __("Height (only in pixels) of the current element", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"group" => esc_html__('Size &amp; Margins', 'unicaevents'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'unicaevents_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'unicaevents_shortcodes_vc_scripts_admin' );
	function unicaevents_shortcodes_vc_scripts_admin() {
		// Include CSS 
		unicaevents_enqueue_style ( 'shortcodes_vc_admin-style', unicaevents_get_file_url('shortcodes/theme.shortcodes_vc_admin.css'), array(), null );
		// Include JS
		unicaevents_enqueue_script( 'shortcodes_vc_admin-script', unicaevents_get_file_url('core/core.shortcodes/shortcodes_vc_admin.js'), array('jquery'), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'unicaevents_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'unicaevents_shortcodes_vc_scripts_front' );
	function unicaevents_shortcodes_vc_scripts_front() {
		if (unicaevents_vc_is_frontend()) {
			// Include CSS 
			unicaevents_enqueue_style ( 'shortcodes_vc_front-style', unicaevents_get_file_url('shortcodes/theme.shortcodes_vc_front.css'), array(), null );
			// Include JS
			unicaevents_enqueue_script( 'shortcodes_vc_front-script', unicaevents_get_file_url('core/core.shortcodes/shortcodes_vc_front.js'), array('jquery'), null, true );
			unicaevents_enqueue_script( 'shortcodes_vc_theme-script', unicaevents_get_file_url('shortcodes/theme.shortcodes_vc_front.js'), array('jquery'), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'unicaevents_shortcodes_vc_add_init_script' ) ) {
	//add_filter('unicaevents_shortcode_output', 'unicaevents_shortcodes_vc_add_init_script', 10, 4);
	function unicaevents_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (unicaevents_strpos($output, 'unicaevents_vc_init_shortcodes')===false) {
				$id = "unicaevents_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							unicaevents_init_post_formats();
							unicaevents_init_shortcodes(jQuery("body").eq(0));
							unicaevents_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_shortcodes_vc_theme_setup' ) ) {
	//if ( unicaevents_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'unicaevents_action_before_init_theme', 'unicaevents_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'unicaevents_action_after_init_theme', 'unicaevents_shortcodes_vc_theme_setup' );
	function unicaevents_shortcodes_vc_theme_setup() {
		global $UNICAEVENTS_GLOBALS;

		// Set dir with theme specific VC shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			vc_set_shortcodes_templates_dir( unicaevents_get_folder_dir('shortcodes/vc' ) );
		}
		
		// Add/Remove params in the standard VC shortcodes
		vc_add_param("vc_row", array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "unicaevents"),
					"description" => wp_kses( __("Select color scheme for this block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Color scheme', 'unicaevents'),
					"class" => "",
					"value" => array_flip(unicaevents_get_list_color_schemes(true)),
					"type" => "dropdown"
		));

		if (unicaevents_shortcodes_is_used()) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'unicaevents_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'unicaevents_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('unicaevents_shortcode_output', 'unicaevents_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
			vc_remove_element("vc_button");
			vc_remove_element("vc_posts_slider");
			vc_remove_element("vc_gmaps");
			vc_remove_element("vc_teaser_grid");
			vc_remove_element("vc_progress_bar");
//			vc_remove_element("vc_facebook");
//			vc_remove_element("vc_tweetmeme");
//			vc_remove_element("vc_googleplus");
//			vc_remove_element("vc_facebook");
//			vc_remove_element("vc_pinterest");
			vc_remove_element("vc_message");
			vc_remove_element("vc_posts_grid");
//			vc_remove_element("vc_carousel");
//			vc_remove_element("vc_flickr");
			vc_remove_element("vc_tour");
//			vc_remove_element("vc_separator");
//			vc_remove_element("vc_single_image");
			vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			vc_remove_element("vc_toggle");
			vc_remove_element("vc_tabs");
			vc_remove_element("vc_tab");
//			vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			global $UNICAEVENTS_GLOBALS;
			
			$UNICAEVENTS_GLOBALS['vc_params'] = array(
				
				// Common arrays and strings
				'category' => esc_html__("UnicaEvents shortcodes", "unicaevents"),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", "unicaevents"),
					"description" => wp_kses( __("ID for current element", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('ID &amp; Class', 'unicaevents'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", "unicaevents"),
					"description" => wp_kses( __("CSS class for current element", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('ID &amp; Class', 'unicaevents'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", "unicaevents"),
					"description" => wp_kses( __("Select animation while object enter in the visible area of page", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('ID &amp; Class', 'unicaevents'),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['animations']),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", "unicaevents"),
					"description" => wp_kses( __("Any additional CSS rules (if need)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('ID &amp; Class', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", "unicaevents"),
					"description" => wp_kses( __("Margin above this shortcode", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Size &amp; Margins', 'unicaevents'),
					"std" => "inherit",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['margins']),
					"type" => "dropdown"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", "unicaevents"),
					"description" => wp_kses( __("Margin below this shortcode", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Size &amp; Margins', 'unicaevents'),
					"std" => "inherit",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['margins']),
					"type" => "dropdown"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", "unicaevents"),
					"description" => wp_kses( __("Margin on the left side of this shortcode", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Size &amp; Margins', 'unicaevents'),
					"std" => "inherit",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['margins']),
					"type" => "dropdown"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", "unicaevents"),
					"description" => wp_kses( __("Margin on the right side of this shortcode", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Size &amp; Margins', 'unicaevents'),
					"std" => "inherit",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['margins']),
					"type" => "dropdown"
				)
			);
			
			// Add theme-specific shortcodes
			do_action('unicaevents_action_shortcodes_list_vc');

		}
	}
}
?>
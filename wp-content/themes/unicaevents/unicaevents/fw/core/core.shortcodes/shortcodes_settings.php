<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'unicaevents_shortcodes_is_used' ) ) {
	function unicaevents_shortcodes_is_used() {
		return unicaevents_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (function_exists('unicaevents_vc_is_frontend') && unicaevents_vc_is_frontend());		// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'unicaevents_shortcodes_width' ) ) {
	function unicaevents_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", "unicaevents"),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'unicaevents_shortcodes_height' ) ) {
	function unicaevents_shortcodes_height($h='') {
		global $UNICAEVENTS_GLOBALS;
		return array(
			"title" => esc_html__("Height", "unicaevents"),
			"desc" => wp_kses( __("Width (in pixels or percent) and height (only in pixels) of element", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"value" => $h,
			"type" => "text"
		);
	}
}

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_shortcodes_settings_theme_setup' ) ) {
//	if ( unicaevents_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'unicaevents_action_before_init_theme', 'unicaevents_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'unicaevents_action_after_init_theme', 'unicaevents_shortcodes_settings_theme_setup' );
	function unicaevents_shortcodes_settings_theme_setup() {
		if (unicaevents_shortcodes_is_used()) {
			global $UNICAEVENTS_GLOBALS;

			// Sort templates alphabetically
			ksort($UNICAEVENTS_GLOBALS['registered_templates']);

			// Prepare arrays 
			$UNICAEVENTS_GLOBALS['sc_params'] = array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", "unicaevents"),
					"desc" => wp_kses( __("ID for current element", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", "unicaevents"),
					"desc" => wp_kses( __("CSS class for current element (optional)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", "unicaevents"),
					"desc" => wp_kses( __("Any additional CSS rules (if need)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'unicaevents'),
					'ol'	=> esc_html__('Ordered', 'unicaevents'),
					'iconed'=> esc_html__('Iconed', 'unicaevents')
				),
				'yes_no'	=> unicaevents_get_list_yesno(),
				'on_off'	=> unicaevents_get_list_onoff(),
				'dir' 		=> unicaevents_get_list_directions(),
				'align'		=> unicaevents_get_list_alignments(),
				'float'		=> unicaevents_get_list_floats(),
				'show_hide'	=> unicaevents_get_list_showhide(),
				'sorting' 	=> unicaevents_get_list_sortings(),
				'ordering' 	=> unicaevents_get_list_orderings(),
				'shapes'	=> unicaevents_get_list_shapes(),
				'sizes'		=> unicaevents_get_list_sizes(),
				'sliders'	=> unicaevents_get_list_sliders(),
				'categories'=> unicaevents_get_list_categories(),
				'columns'	=> unicaevents_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), unicaevents_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), unicaevents_get_list_icons()),
				'locations'	=> unicaevents_get_list_dedicated_locations(),
				'filters'	=> unicaevents_get_list_portfolio_filters(),
				'formats'	=> unicaevents_get_list_post_formats_filters(),
				'hovers'	=> unicaevents_get_list_hovers(true),
				'hovers_dir'=> unicaevents_get_list_hovers_directions(true),
				'schemes'	=> unicaevents_get_list_color_schemes(true),
				'animations'		=> unicaevents_get_list_animations_in(),
				'margins' 			=> unicaevents_get_list_margins(true),
				'blogger_styles'	=> unicaevents_get_list_templates_blogger(),
				'forms'				=> unicaevents_get_list_templates_forms(),
				'posts_types'		=> unicaevents_get_list_posts_types(),
				'googlemap_styles'	=> unicaevents_get_list_googlemap_styles(),
				'field_types'		=> unicaevents_get_list_field_types(),
				'label_positions'	=> unicaevents_get_list_label_positions()
			);

			$UNICAEVENTS_GLOBALS['sc_params']['animation'] = array(
				"title" => esc_html__("Animation",  'unicaevents'),
				"desc" => wp_kses( __('Select animation while object enter in the visible area of page',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"value" => "none",
				"type" => "select",
				"options" => $UNICAEVENTS_GLOBALS['sc_params']['animations']
			);
			$UNICAEVENTS_GLOBALS['sc_params']['top'] = array(
				"title" => esc_html__("Top margin",  'unicaevents'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => $UNICAEVENTS_GLOBALS['sc_params']['margins']
			);
			$UNICAEVENTS_GLOBALS['sc_params']['bottom'] = array(
				"title" => esc_html__("Bottom margin",  'unicaevents'),
				"value" => "inherit",
				"type" => "select",
				"options" => $UNICAEVENTS_GLOBALS['sc_params']['margins']
			);
			$UNICAEVENTS_GLOBALS['sc_params']['left'] = array(
				"title" => esc_html__("Left margin",  'unicaevents'),
				"value" => "inherit",
				"type" => "select",
				"options" => $UNICAEVENTS_GLOBALS['sc_params']['margins']
			);
			$UNICAEVENTS_GLOBALS['sc_params']['right'] = array(
				"title" => esc_html__("Right margin",  'unicaevents'),
				"desc" => wp_kses( __("Margins around this shortcode", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"value" => "inherit",
				"type" => "select",
				"options" => $UNICAEVENTS_GLOBALS['sc_params']['margins']
			);

			$UNICAEVENTS_GLOBALS['sc_params'] = apply_filters('unicaevents_filter_shortcodes_params', $UNICAEVENTS_GLOBALS['sc_params']);

	
			// Shortcodes list
			//------------------------------------------------------------------
			$UNICAEVENTS_GLOBALS['shortcodes'] = array();
			
			// Add shortcodes
			do_action('unicaevents_action_shortcodes_list');

			// Sort shortcodes list
			ksort($UNICAEVENTS_GLOBALS['shortcodes']);
		}
	}
}
?>
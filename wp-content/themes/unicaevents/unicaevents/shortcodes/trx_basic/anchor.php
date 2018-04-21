<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_anchor_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_anchor_theme_setup' );
	function unicaevents_sc_anchor_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_anchor_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('unicaevents_sc_anchor')) {	
	function unicaevents_sc_anchor($atts, $content = null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(unicaevents_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (unicaevents_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	unicaevents_require_shortcode("trx_anchor", "unicaevents_sc_anchor");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_anchor_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_anchor_reg_shortcodes');
	function unicaevents_sc_anchor_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_anchor"] = array(
			"title" => esc_html__("Anchor", "unicaevents"),
			"desc" => wp_kses( __("Insert anchor for the TOC (table of content)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'unicaevents'),
					"desc" => wp_kses( __('Select icon for the anchor from Fontello icons set',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
				),
				"title" => array(
					"title" => esc_html__("Short title", "unicaevents"),
					"desc" => wp_kses( __("Short title of the anchor (for the table of content)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", "unicaevents"),
					"desc" => wp_kses( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", "unicaevents"),
					"desc" => wp_kses( __("External URL for this TOC item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", "unicaevents"),
					"desc" => wp_kses( __("Add separator under item in the TOC", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "no",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
				),
				"id" => $UNICAEVENTS_GLOBALS['sc_params']['id']
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_anchor_reg_shortcodes_vc');
	function unicaevents_sc_anchor_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", "unicaevents"),
			"description" => wp_kses( __("Insert anchor for the TOC (table of content)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", "unicaevents"),
					"description" => wp_kses( __("Select icon for the anchor from Fontello icons set", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", "unicaevents"),
					"description" => wp_kses( __("Short title of the anchor (for the table of content)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", "unicaevents"),
					"description" => wp_kses( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", "unicaevents"),
					"description" => wp_kses( __("External URL for this TOC item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", "unicaevents"),
					"description" => wp_kses( __("Add separator under item in the TOC", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id']
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends UNICAEVENTS_VC_ShortCodeSingle {}
	}
}
?>
<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_br_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_br_theme_setup' );
	function unicaevents_sc_br_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_br_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_br_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('unicaevents_sc_br')) {	
	function unicaevents_sc_br($atts, $content = null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	unicaevents_require_shortcode("trx_br", "unicaevents_sc_br");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_br_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_br_reg_shortcodes');
	function unicaevents_sc_br_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_br"] = array(
			"title" => esc_html__("Break", "unicaevents"),
			"desc" => wp_kses( __("Line break with clear floating (if need)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", "unicaevents"),
					"desc" => wp_kses( __("Clear floating (if need)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'unicaevents'),
						'left' => esc_html__('Left', 'unicaevents'),
						'right' => esc_html__('Right', 'unicaevents'),
						'both' => esc_html__('Both', 'unicaevents')
					)
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_br_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_br_reg_shortcodes_vc');
	function unicaevents_sc_br_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
/*
		vc_map( array(
			"base" => "trx_br",
			"name" => esc_html__("Line break", "unicaevents"),
			"description" => wp_kses( __("Line break or Clear Floating", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_br',
			"class" => "trx_sc_single trx_sc_br",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "clear",
					"heading" => esc_html__("Clear floating", "unicaevents"),
					"description" => wp_kses( __("Select clear side (if need)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"value" => array(
						esc_html__('None', 'unicaevents') => 'none',
						esc_html__('Left', 'unicaevents') => 'left',
						esc_html__('Right', 'unicaevents') => 'right',
						esc_html__('Both', 'unicaevents') => 'both'
					),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Br extends UNICAEVENTS_VC_ShortCodeSingle {}
*/
	}
}
?>
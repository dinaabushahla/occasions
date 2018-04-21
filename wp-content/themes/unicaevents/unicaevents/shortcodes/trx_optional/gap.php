<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_gap_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_gap_theme_setup' );
	function unicaevents_sc_gap_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_gap_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('unicaevents_sc_gap')) {	
	function unicaevents_sc_gap($atts, $content = null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		$output = unicaevents_gap_start() . do_shortcode($content) . unicaevents_gap_end();
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	unicaevents_require_shortcode("trx_gap", "unicaevents_sc_gap");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_gap_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_gap_reg_shortcodes');
	function unicaevents_sc_gap_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_gap"] = array(
			"title" => esc_html__("Gap", "unicaevents"),
			"desc" => wp_kses( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", "unicaevents"),
					"desc" => wp_kses( __("Gap inner content", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_gap_reg_shortcodes_vc');
	function unicaevents_sc_gap_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", "unicaevents"),
			"description" => wp_kses( __("Insert gap (fullwidth area) in the post content", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Structure', 'js_composer'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Gap content", "unicaevents"),
					"description" => wp_kses( __("Gap inner content", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				)
				*/
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends UNICAEVENTS_VC_ShortCodeCollection {}
	}
}
?>
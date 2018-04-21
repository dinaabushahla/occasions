<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_hide_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_hide_theme_setup' );
	function unicaevents_sc_hide_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('unicaevents_sc_hide')) {	
	function unicaevents_sc_hide($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	unicaevents_require_shortcode('trx_hide', 'unicaevents_sc_hide');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_hide_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_hide_reg_shortcodes');
	function unicaevents_sc_hide_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_hide"] = array(
			"title" => esc_html__("Hide/Show any block", "unicaevents"),
			"desc" => wp_kses( __("Hide or Show any block with desired CSS-selector", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", "unicaevents"),
					"desc" => wp_kses( __("Any block's CSS-selector", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", "unicaevents"),
					"desc" => wp_kses( __("New state for the block: hide or show", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "yes",
					"size" => "small",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no'],
					"type" => "switch"
				)
			)
		);
	}
}
?>
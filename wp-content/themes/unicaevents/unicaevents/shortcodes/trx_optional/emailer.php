<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_emailer_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_emailer_theme_setup' );
	function unicaevents_sc_emailer_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_emailer_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_emailer_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_emailer group=""]

if (!function_exists('unicaevents_sc_emailer')) {	
	function unicaevents_sc_emailer($atts, $content = null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"group" => "",
			"open" => "yes",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= unicaevents_get_css_dimensions_from_values($width, $height);
		// Load core messages
		unicaevents_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="sc_emailer' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (unicaevents_param_is_on($open) ? ' sc_emailer_opened' : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
					. ($css ? ' style="'.esc_attr($css).'"' : '') 
					. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
					. '>'
				. '<form class="sc_emailer_form">'
				. '<input type="text" class="sc_emailer_input" name="email" value="" placeholder="'.esc_attr__('Please, enter you email address.', 'unicaevents').'">'
				. '<a href="#" class="sc_emailer_button icon-mail" title="'.esc_attr__('Submit', 'unicaevents').'" data-group="'.esc_attr($group ? $group : esc_html__('E-mailer subscription', 'unicaevents')).'"></a>'
				. '</form>'
			. '</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_emailer', $atts, $content);
	}
	unicaevents_require_shortcode("trx_emailer", "unicaevents_sc_emailer");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_emailer_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_emailer_reg_shortcodes');
	function unicaevents_sc_emailer_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_emailer"] = array(
			"title" => esc_html__("E-mail collector", "unicaevents"),
			"desc" => wp_kses( __("Collect the e-mail address into specified group", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"group" => array(
					"title" => esc_html__("Group", "unicaevents"),
					"desc" => wp_kses( __("The name of group to collect e-mail address", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"open" => array(
					"title" => esc_html__("Open", "unicaevents"),
					"desc" => wp_kses( __("Initially open the input field on show object", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "yes",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
				),
				"align" => array(
					"title" => esc_html__("Alignment", "unicaevents"),
					"desc" => wp_kses( __("Align object to left, center or right", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['align']
				), 
				"width" => unicaevents_shortcodes_width(),
				"height" => unicaevents_shortcodes_height(),
				"top" => $UNICAEVENTS_GLOBALS['sc_params']['top'],
				"bottom" => $UNICAEVENTS_GLOBALS['sc_params']['bottom'],
				"left" => $UNICAEVENTS_GLOBALS['sc_params']['left'],
				"right" => $UNICAEVENTS_GLOBALS['sc_params']['right'],
				"id" => $UNICAEVENTS_GLOBALS['sc_params']['id'],
				"class" => $UNICAEVENTS_GLOBALS['sc_params']['class'],
				"animation" => $UNICAEVENTS_GLOBALS['sc_params']['animation'],
				"css" => $UNICAEVENTS_GLOBALS['sc_params']['css']
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_emailer_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_emailer_reg_shortcodes_vc');
	function unicaevents_sc_emailer_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_emailer",
			"name" => esc_html__("E-mail collector", "unicaevents"),
			"description" => wp_kses( __("Collect e-mails into specified group", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_emailer',
			"class" => "trx_sc_single trx_sc_emailer",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "group",
					"heading" => esc_html__("Group", "unicaevents"),
					"description" => wp_kses( __("The name of group to collect e-mail address", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Opened", "unicaevents"),
					"description" => wp_kses( __("Initially open the input field on show object", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(esc_html__('Initially opened', 'unicaevents') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "unicaevents"),
					"description" => wp_kses( __("Align field to left, center or right", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['animation'],
				$UNICAEVENTS_GLOBALS['vc_params']['css'],
				unicaevents_vc_width(),
				unicaevents_vc_height(),
				$UNICAEVENTS_GLOBALS['vc_params']['margin_top'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_bottom'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_left'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_right']
			)
		) );
		
		class WPBakeryShortCode_Trx_Emailer extends UNICAEVENTS_VC_ShortCodeSingle {}
	}
}
?>
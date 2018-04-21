<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_button_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_button_theme_setup' );
	function unicaevents_sc_button_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_button_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('unicaevents_sc_button')) {	
	function unicaevents_sc_button($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= unicaevents_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '')
			. ($bg_color !== '' ? 'box-shadow: inset 0px 0px 0 2px ' . esc_attr($bg_color) . ';' : '');
		if (unicaevents_param_is_on($popup)) unicaevents_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (unicaevents_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	unicaevents_require_shortcode('trx_button', 'unicaevents_sc_button');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_button_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_button_reg_shortcodes');
	function unicaevents_sc_button_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_button"] = array(
			"title" => esc_html__("Button", "unicaevents"),
			"desc" => wp_kses( __("Button with link", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", "unicaevents"),
					"desc" => wp_kses( __("Button caption", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", "unicaevents"),
					"desc" => wp_kses( __("Select button's shape", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'unicaevents'),
						'round' => esc_html__('Round', 'unicaevents')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", "unicaevents"),
					"desc" => wp_kses( __("Select button's style", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'unicaevents'),
						'border' => esc_html__('Border', 'unicaevents')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", "unicaevents"),
					"desc" => wp_kses( __("Select button's size", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'unicaevents'),
						'medium' => esc_html__('Medium', 'unicaevents'),
						'large' => esc_html__('Large', 'unicaevents')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'unicaevents'),
					"desc" => wp_kses( __('Select icon for the title from Fontello icons set',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
				),
				"color" => array(
					"title" => esc_html__("Button's text color", "unicaevents"),
					"desc" => wp_kses( __("Any color for button's caption", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", "unicaevents"),
					"desc" => wp_kses( __("Any color for button's background", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", "unicaevents"),
					"desc" => wp_kses( __("Align button to left, center or right", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['align']
				), 
				"link" => array(
					"title" => esc_html__("Link URL", "unicaevents"),
					"desc" => wp_kses( __("URL for link on button click", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", "unicaevents"),
					"desc" => wp_kses( __("Target for link on button click", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", "unicaevents"),
					"desc" => wp_kses( __("Open link target in popup window", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", "unicaevents"),
					"desc" => wp_kses( __("Rel attribute for button's link (if need)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'unicaevents_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_button_reg_shortcodes_vc');
	function unicaevents_sc_button_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", "unicaevents"),
			"description" => wp_kses( __("Button with link", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", "unicaevents"),
					"description" => wp_kses( __("Button caption", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", "unicaevents"),
					"description" => wp_kses( __("Select button's shape", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'unicaevents') => 'square',
						esc_html__('Round', 'unicaevents') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", "unicaevents"),
					"description" => wp_kses( __("Select button's style", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'unicaevents') => 'filled',
						esc_html__('Border', 'unicaevents') => 'border'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", "unicaevents"),
					"description" => wp_kses( __("Select button's size", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'unicaevents') => 'small',
						esc_html__('Medium', 'unicaevents') => 'medium',
						esc_html__('Large', 'unicaevents') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", "unicaevents"),
					"description" => wp_kses( __("Select icon for the title from Fontello icons set", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", "unicaevents"),
					"description" => wp_kses( __("Any color for button's caption", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", "unicaevents"),
					"description" => wp_kses( __("Any color for button's background", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", "unicaevents"),
					"description" => wp_kses( __("Align button to left, center or right", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "unicaevents"),
					"description" => wp_kses( __("URL for the link on button click", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Link', 'unicaevents'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", "unicaevents"),
					"description" => wp_kses( __("Target for the link on button click", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Link', 'unicaevents'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", "unicaevents"),
					"description" => wp_kses( __("Open link target in popup window", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Link', 'unicaevents'),
					"value" => array(esc_html__('Open in popup', 'unicaevents') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", "unicaevents"),
					"description" => wp_kses( __("Rel attribute for the button's link (if need", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Link', 'unicaevents'),
					"value" => "",
					"type" => "textfield"
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends UNICAEVENTS_VC_ShortCodeSingle {}
	}
}
?>
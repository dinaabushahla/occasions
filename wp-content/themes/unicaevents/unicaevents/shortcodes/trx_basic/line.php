<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_line_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_line_theme_setup' );
	function unicaevents_sc_line_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_line_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_line_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_line id="unique_id" style="none|solid|dashed|dotted|double|groove|ridge|inset|outset" top="margin_in_pixels" bottom="margin_in_pixels" width="width_in_pixels_or_percent" height="line_thickness_in_pixels" color="line_color's_name_or_#rrggbb"]
*/

if (!function_exists('unicaevents_sc_line')) {	
	function unicaevents_sc_line($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"color" => "",
			"title" => "",
			"position" => "",
			"image" => "",
			"repeat" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		if (empty($style)) $style = 'solid';
		if (empty($position)) $position = 'center center';
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$block_height = '';
		if ($style=='image' && !empty($image)) {
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$attr = getimagesize($image);
			if (is_array($attr) && $attr[1] > 0)
				$block_height = $attr[1];
		} else if (!empty($title) && empty($height) && !in_array($position, array('left center', 'center center', 'right center'))) {
			$block_height = '1.5em';
		}
		$border_pos = in_array($position, array('left top', 'center top', 'right top')) ? 'bottom' : 'top';

		$css .= unicaevents_get_css_dimensions_from_values($width, $block_height)
			. ($style=='image' && !empty($image)
				? ( 'background-image: url(' . esc_url($image) . ');'
					. (unicaevents_param_is_on($repeat) ? 'background-repeat: repeat-x;' : '')
					)
				: ( ($height !='' ? 'border-'.esc_attr($border_pos).'-width:' . esc_attr(unicaevents_prepare_css_value($height)) . ';' : '')
					. ($style != '' ? 'border-'.esc_attr($border_pos).'-style:' . esc_attr($style) . ';' : '')
					. ($color != '' ? 'border-'.esc_attr($border_pos).'-color:' . esc_attr($color) . ';' : '')
					)
				);
		$output = '<div' . ($id ? ' id="'.esc_attr($id) . '"' : '') 
				. ' class="sc_line sc_line_position_'.esc_attr(str_replace(' ', '_', $position)) . ' sc_line_style_'.esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. (!empty($title) ? '<span class="sc_line_title">' . trim($title) . '</span>' : '')
				. '</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_line', $atts, $content);
	}
	unicaevents_require_shortcode('trx_line', 'unicaevents_sc_line');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_line_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_line_reg_shortcodes');
	function unicaevents_sc_line_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_line"] = array(
			"title" => esc_html__("Line", "unicaevents"),
			"desc" => wp_kses( __("Insert Line into your post (page)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", "unicaevents"),
					"desc" => wp_kses( __("Line style", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "solid",
					"dir" => "horizontal",
					"options" => unicaevents_get_list_line_styles(),
					"type" => "checklist"
				),
				"image" => array(
					"title" => esc_html__("Image as separator", "unicaevents"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site to use it as separator", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"readonly" => false,
					"dependency" => array(
						'style' => array('image')
					),
					"value" => "",
					"type" => "media"
				),
				"repeat" => array(
					"title" => esc_html__("Repeat image", "unicaevents"),
					"desc" => wp_kses( __("To repeat an image or to show single picture", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('image')
					),
					"value" => "no",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
				),
				"color" => array(
					"title" => esc_html__("Color", "unicaevents"),
					"desc" => wp_kses( __("Line color", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('solid', 'dashed', 'dotted', 'double')
					),
					"value" => "",
					"type" => "color"
				),
				"title" => array(
					"title" => esc_html__("Title", "unicaevents"),
					"desc" => wp_kses( __("Title that is going to be placed in the center of the line (if not empty)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"position" => array(
					"title" => esc_html__("Title position", "unicaevents"),
					"desc" => wp_kses( __("Title position", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'title' => array('not_empty')
					),
					"value" => "center center",
					"options" => unicaevents_get_list_bg_image_positions(),
					"type" => "select"
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
if ( !function_exists( 'unicaevents_sc_line_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_line_reg_shortcodes_vc');
	function unicaevents_sc_line_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_line",
			"name" => esc_html__("Line", "unicaevents"),
			"description" => wp_kses( __("Insert line (delimiter)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			"class" => "trx_sc_single trx_sc_line",
			'icon' => 'icon_trx_line',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "unicaevents"),
					"description" => wp_kses( __("Line style", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"std" => "solid",
					"value" => array_flip(unicaevents_get_list_line_styles()),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image as separator", "unicaevents"),
					"description" => wp_kses( __("Select or upload image or write URL from other site to use it as separator", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('image')
					),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "repeat",
					"heading" => esc_html__("Repeat image", "unicaevents"),
					"description" => wp_kses( __("To repeat an image or to show single picture", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('image')
					),
					"class" => "",
					"value" => array("Repeat image" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Line color", "unicaevents"),
					"description" => wp_kses( __("Line color", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('solid','dotted','dashed','double')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "unicaevents"),
					"description" => wp_kses( __("Title that is going to be placed in the center of the line (if not empty)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Title position", "unicaevents"),
					"description" => wp_kses( __("Title position", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"std" => "center center",
					"value" => array_flip(unicaevents_get_list_bg_image_positions()),
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
		
		class WPBakeryShortCode_Trx_Line extends UNICAEVENTS_VC_ShortCodeSingle {}
	}
}
?>
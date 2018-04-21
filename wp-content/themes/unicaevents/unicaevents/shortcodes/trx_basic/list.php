<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_list_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_list_theme_setup' );
	function unicaevents_sc_list_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_list_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('unicaevents_sc_list')) {	
	function unicaevents_sc_list($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"underlined" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_list_counter'] = 0;
		$UNICAEVENTS_GLOBALS['sc_list_icon'] = empty($icon) || unicaevents_param_is_inherit($icon) ? "icon-right" : $icon;
		$UNICAEVENTS_GLOBALS['sc_list_icon_color'] = $icon_color;
		$UNICAEVENTS_GLOBALS['sc_list_style'] = $style;
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '')
				. ($underlined=='true' ? ' underlined' : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	unicaevents_require_shortcode('trx_list', 'unicaevents_sc_list');
}


if (!function_exists('unicaevents_sc_list_item')) {	
	function unicaevents_sc_list_item($atts, $content=null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"style"=>"",
			"class" => "",
			"css" => ""
		), $atts)));
		global $UNICAEVENTS_GLOBALS;

		$style = $UNICAEVENTS_GLOBALS['sc_list_style'];
		$UNICAEVENTS_GLOBALS['sc_list_counter']++;
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || unicaevents_param_is_inherit($icon)) $icon = $UNICAEVENTS_GLOBALS['sc_list_icon'];
		if (trim($color) == '' || unicaevents_param_is_inherit($icon_color)) $icon_color = $UNICAEVENTS_GLOBALS['sc_list_icon_color'];
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. ($UNICAEVENTS_GLOBALS['sc_list_counter'] % 2 == 1 ? ' odd' : ' even') 
			. ($UNICAEVENTS_GLOBALS['sc_list_counter'] == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. ($UNICAEVENTS_GLOBALS['sc_list_style']=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. ($style=='ol' ? '<span>' : '')
			. trim($content)
			. ($style=='ol' ? '</span>' : '')
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	unicaevents_require_shortcode('trx_list_item', 'unicaevents_sc_list_item');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_list_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_list_reg_shortcodes');
	function unicaevents_sc_list_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_list"] = array(
			"title" => esc_html__("List", "unicaevents"),
			"desc" => wp_kses( __("List items with specific bullets", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", "unicaevents"),
					"desc" => wp_kses( __("Bullet's style for each list item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "ul",
					"type" => "checklist",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['list_styles']
				),
				"underlined" => array(
					"title" => __("Line for li", "unicaevents"),
					"desc" => __("Add line for li", "unicaevents"),
					"type" => "checkbox"
					), 
				"color" => array(
					"title" => esc_html__("Color", "unicaevents"),
					"desc" => wp_kses( __("List items color", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('List icon',  'unicaevents'),
					"desc" => wp_kses( __("Select list icon from Fontello icons set (only for style=Iconed)",  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", "unicaevents"),
					"desc" => wp_kses( __("List icons color", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"top" => $UNICAEVENTS_GLOBALS['sc_params']['top'],
				"bottom" => $UNICAEVENTS_GLOBALS['sc_params']['bottom'],
				"left" => $UNICAEVENTS_GLOBALS['sc_params']['left'],
				"right" => $UNICAEVENTS_GLOBALS['sc_params']['right'],
				"id" => $UNICAEVENTS_GLOBALS['sc_params']['id'],
				"class" => $UNICAEVENTS_GLOBALS['sc_params']['class'],
				"animation" => $UNICAEVENTS_GLOBALS['sc_params']['animation'],
				"css" => $UNICAEVENTS_GLOBALS['sc_params']['css']
			),
			"children" => array(
				"name" => "trx_list_item",
				"title" => esc_html__("Item", "unicaevents"),
				"desc" => wp_kses( __("List item with specific bullet", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", "unicaevents"),
						"desc" => wp_kses( __("Current list item content", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", "unicaevents"),
						"desc" => wp_kses( __("Current list item title (show it as tooltip)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", "unicaevents"),
						"desc" => wp_kses( __("Text color for this item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'unicaevents'),
						"desc" => wp_kses( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "icons",
						"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", "unicaevents"),
						"desc" => wp_kses( __("Icon color for this item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", "unicaevents"),
						"desc" => wp_kses( __("Link URL for the current list item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", "unicaevents"),
						"desc" => wp_kses( __("Link target for the current list item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"id" => $UNICAEVENTS_GLOBALS['sc_params']['id'],
					"class" => $UNICAEVENTS_GLOBALS['sc_params']['class'],
					"css" => $UNICAEVENTS_GLOBALS['sc_params']['css']
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_list_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_list_reg_shortcodes_vc');
	function unicaevents_sc_list_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", "unicaevents"),
			"description" => wp_kses( __("List items with specific bullets", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", "unicaevents"),
					"description" => wp_kses( __("Bullet's style for each list item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['list_styles']),
					"type" => "dropdown"
					),
				array(
					"param_name" => "underlined",
					"heading" => __("Underline", "unicaevents"),
					"description" => __("Underline for li", "unicaevents"),
					"type" => "checkbox"
					),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "unicaevents"),
					"description" => wp_kses( __("List items color", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", "unicaevents"),
					"description" => wp_kses( __("Select list icon from Fontello icons set (only for style=Iconed)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", "unicaevents"),
					"description" => wp_kses( __("List icons color", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => "",
					"type" => "colorpicker"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['animation'],
				$UNICAEVENTS_GLOBALS['vc_params']['css'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_top'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_bottom'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_left'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_right']
			),
			'default_content' => '
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", "unicaevents"),
			"description" => wp_kses( __("List item with specific bullet", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", "unicaevents"),
					"description" => wp_kses( __("Title for the current list item (show it as tooltip)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "unicaevents"),
					"description" => wp_kses( __("Link URL for the current list item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", "unicaevents"),
					"description" => wp_kses( __("Link target for the current list item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "unicaevents"),
					"description" => wp_kses( __("Text color for this item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", "unicaevents"),
					"description" => wp_kses( __("Select list item icon from Fontello icons set (only for style=Iconed)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", "unicaevents"),
					"description" => wp_kses( __("Icon color for this item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("List item text", "unicaevents"),
					"description" => wp_kses( __("Current list item content", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['css']
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends UNICAEVENTS_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends UNICAEVENTS_VC_ShortCodeContainer {}
	}
}
?>
<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_accordion_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_accordion_theme_setup' );
	function unicaevents_sc_accordion_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_accordion_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_accordion_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_accordion counter="off" initial="1"]
	[trx_accordion_item title="Accordion Title 1"]Lorem ipsum dolor sit amet, consectetur adipisicing elit[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 2"]Proin dignissim commodo magna at luctus. Nam molestie justo augue, nec eleifend urna laoreet non.[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 3 with custom icons" icon_closed="icon-check" icon_opened="icon-delete"]Curabitur tristique tempus arcu a placerat.[/trx_accordion_item]
[/trx_accordion]
*/
if (!function_exists('unicaevents_sc_accordion')) {	
	function unicaevents_sc_accordion($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"initial" => "1",
			"counter" => "off",
			"icon_closed" => "icon-down-open-1",
			"icon_opened" => "icon-up-open-1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$initial = max(0, (int) $initial);
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_accordion_counter'] = 0;
		$UNICAEVENTS_GLOBALS['sc_accordion_show_counter'] = unicaevents_param_is_on($counter);
		$UNICAEVENTS_GLOBALS['sc_accordion_icon_closed'] = empty($icon_closed) || unicaevents_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed;
		$UNICAEVENTS_GLOBALS['sc_accordion_icon_opened'] = empty($icon_opened) || unicaevents_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened;
		unicaevents_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (unicaevents_param_is_on($counter) ? ' sc_show_counter' : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. ' data-active="' . ($initial-1) . '"'
				. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_accordion', $atts, $content);
	}
	unicaevents_require_shortcode('trx_accordion', 'unicaevents_sc_accordion');
}


if (!function_exists('unicaevents_sc_accordion_item')) {	
	function unicaevents_sc_accordion_item($atts, $content=null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts( array(
			// Individual params
			"icon_closed" => "",
			"icon_opened" => "",
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_accordion_counter']++;
		if (empty($icon_closed) || unicaevents_param_is_inherit($icon_closed)) $icon_closed = $UNICAEVENTS_GLOBALS['sc_accordion_icon_closed'] ? $UNICAEVENTS_GLOBALS['sc_accordion_icon_closed'] : "icon-plus";
		if (empty($icon_opened) || unicaevents_param_is_inherit($icon_opened)) $icon_opened = $UNICAEVENTS_GLOBALS['sc_accordion_icon_opened'] ? $UNICAEVENTS_GLOBALS['sc_accordion_icon_opened'] : "icon-minus";
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion_item' 
				. (!empty($class) ? ' '.esc_attr($class) : '')
				. ($UNICAEVENTS_GLOBALS['sc_accordion_counter'] % 2 == 1 ? ' odd' : ' even') 
				. ($UNICAEVENTS_GLOBALS['sc_accordion_counter'] == 1 ? ' first' : '') 
				. '">'
				. '<h5 class="sc_accordion_title">'
				. (!unicaevents_param_is_off($icon_closed) ? '<span class="sc_accordion_icon sc_accordion_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
				. (!unicaevents_param_is_off($icon_opened) ? '<span class="sc_accordion_icon sc_accordion_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
				. ($UNICAEVENTS_GLOBALS['sc_accordion_show_counter'] ? '<span class="sc_items_counter">'.($UNICAEVENTS_GLOBALS['sc_accordion_counter']).'</span>' : '')
				. ($title)
				. '</h5>'
				. '<div class="sc_accordion_content"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
					. do_shortcode($content) 
				. '</div>'
				. '</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_accordion_item', $atts, $content);
	}
	unicaevents_require_shortcode('trx_accordion_item', 'unicaevents_sc_accordion_item');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_accordion_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_accordion_reg_shortcodes');
	function unicaevents_sc_accordion_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_accordion"] = array(
			"title" => esc_html__("Accordion", "unicaevents"),
			"desc" => wp_kses( __("Accordion items", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"counter" => array(
					"title" => esc_html__("Counter", "unicaevents"),
					"desc" => wp_kses( __("Display counter before each accordion title", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "off",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['on_off']
				),
				"initial" => array(
					"title" => esc_html__("Initially opened item", "unicaevents"),
					"desc" => wp_kses( __("Number of initially opened item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => 1,
					"min" => 0,
					"type" => "spinner"
				),
				"icon_closed" => array(
					"title" => esc_html__("Icon while closed",  'unicaevents'),
					"desc" => wp_kses( __('Select icon for the closed accordion item from Fontello icons set',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
				),
				"icon_opened" => array(
					"title" => esc_html__("Icon while opened",  'unicaevents'),
					"desc" => wp_kses( __('Select icon for the opened accordion item from Fontello icons set',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
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
				"name" => "trx_accordion_item",
				"title" => esc_html__("Item", "unicaevents"),
				"desc" => wp_kses( __("Accordion item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Accordion item title", "unicaevents"),
						"desc" => wp_kses( __("Title for current accordion item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"icon_closed" => array(
						"title" => esc_html__("Icon while closed",  'unicaevents'),
						"desc" => wp_kses( __('Select icon for the closed accordion item from Fontello icons set',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "icons",
						"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
					),
					"icon_opened" => array(
						"title" => esc_html__("Icon while opened",  'unicaevents'),
						"desc" => wp_kses( __('Select icon for the opened accordion item from Fontello icons set',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "icons",
						"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
					),
					"_content_" => array(
						"title" => esc_html__("Accordion item content", "unicaevents"),
						"desc" => wp_kses( __("Current accordion item content", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
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
if ( !function_exists( 'unicaevents_sc_accordion_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_accordion_reg_shortcodes_vc');
	function unicaevents_sc_accordion_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_accordion",
			"name" => esc_html__("Accordion", "unicaevents"),
			"description" => wp_kses( __("Accordion items", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_accordion',
			"class" => "trx_sc_collection trx_sc_accordion",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "counter",
					"heading" => esc_html__("Counter", "unicaevents"),
					"description" => wp_kses( __("Display counter before each accordion title", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Add item numbers before each element" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "initial",
					"heading" => esc_html__("Initially opened item", "unicaevents"),
					"description" => wp_kses( __("Number of initially opened item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => 1,
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", "unicaevents"),
					"description" => wp_kses( __("Select icon for the closed accordion item from Fontello icons set", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", "unicaevents"),
					"description" => wp_kses( __("Select icon for the opened accordion item from Fontello icons set", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
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
				[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'unicaevents' ) . '"][/trx_accordion_item]
				[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'unicaevents' ) . '"][/trx_accordion_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", "unicaevents").'">'.esc_html__("Add item", "unicaevents").'</button>
				</div>
			',
			'js_view' => 'VcTrxAccordionView'
		) );
		
		
		vc_map( array(
			"base" => "trx_accordion_item",
			"name" => esc_html__("Accordion item", "unicaevents"),
			"description" => wp_kses( __("Inner accordion item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_accordion_item',
			"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_accordion'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "unicaevents"),
					"description" => wp_kses( __("Title for current accordion item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", "unicaevents"),
					"description" => wp_kses( __("Select icon for the closed accordion item from Fontello icons set", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", "unicaevents"),
					"description" => wp_kses( __("Select icon for the opened accordion item from Fontello icons set", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['css']
			),
		  'js_view' => 'VcTrxAccordionTabView'
		) );

		class WPBakeryShortCode_Trx_Accordion extends UNICAEVENTS_VC_ShortCodeAccordion {}
		class WPBakeryShortCode_Trx_Accordion_Item extends UNICAEVENTS_VC_ShortCodeAccordionItem {}
	}
}
?>
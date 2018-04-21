<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_toggles_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_toggles_theme_setup' );
	function unicaevents_sc_toggles_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_toggles_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_toggles_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('unicaevents_sc_toggles')) {	
	function unicaevents_sc_toggles($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_toggle_counter'] = 0;
		$UNICAEVENTS_GLOBALS['sc_toggle_show_counter'] = unicaevents_param_is_on($counter);
		$UNICAEVENTS_GLOBALS['sc_toggles_icon_closed'] = empty($icon_closed) || unicaevents_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed;
		$UNICAEVENTS_GLOBALS['sc_toggles_icon_opened'] = empty($icon_opened) || unicaevents_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened;
		unicaevents_enqueue_script('jquery-effects-slide', false, array('jquery','jquery-effects-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_toggles'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (unicaevents_param_is_on($counter) ? ' sc_show_counter' : '') 
					. '"'
				. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_toggles', $atts, $content);
	}
	unicaevents_require_shortcode('trx_toggles', 'unicaevents_sc_toggles');
}


if (!function_exists('unicaevents_sc_toggles_item')) {	
	function unicaevents_sc_toggles_item($atts, $content=null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"open" => "",
			"icon_closed" => "",
			"icon_opened" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_toggle_counter']++;
		if (empty($icon_closed) || unicaevents_param_is_inherit($icon_closed)) $icon_closed = $UNICAEVENTS_GLOBALS['sc_toggles_icon_closed'] ? $UNICAEVENTS_GLOBALS['sc_toggles_icon_closed'] : "icon-plus";
		if (empty($icon_opened) || unicaevents_param_is_inherit($icon_opened)) $icon_opened = $UNICAEVENTS_GLOBALS['sc_toggles_icon_opened'] ? $UNICAEVENTS_GLOBALS['sc_toggles_icon_opened'] : "icon-minus";
		$css .= unicaevents_param_is_on($open) ? 'display:block;' : '';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_toggles_item'.(unicaevents_param_is_on($open) ? ' sc_active' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($UNICAEVENTS_GLOBALS['sc_toggle_counter'] % 2 == 1 ? ' odd' : ' even') 
					. ($UNICAEVENTS_GLOBALS['sc_toggle_counter'] == 1 ? ' first' : '')
					. '">'
					. '<h5 class="sc_toggles_title'.(unicaevents_param_is_on($open) ? ' ui-state-active' : '').'">'
					. (!unicaevents_param_is_off($icon_closed) ? '<span class="sc_toggles_icon sc_toggles_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
					. (!unicaevents_param_is_off($icon_opened) ? '<span class="sc_toggles_icon sc_toggles_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
					. ($UNICAEVENTS_GLOBALS['sc_toggle_show_counter'] ? '<span class="sc_items_counter">'.($UNICAEVENTS_GLOBALS['sc_toggle_counter']).'</span>' : '')
					. ($title) 
					. '</h5>'
					. '<div class="sc_toggles_content"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						.'>' 
						. do_shortcode($content) 
					. '</div>'
				. '</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_toggles_item', $atts, $content);
	}
	unicaevents_require_shortcode('trx_toggles_item', 'unicaevents_sc_toggles_item');
}


/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_toggles_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_toggles_reg_shortcodes');
	function unicaevents_sc_toggles_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_toggles"] = array(
			"title" => esc_html__("Toggles", "unicaevents"),
			"desc" => wp_kses( __("Toggles items", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"counter" => array(
					"title" => esc_html__("Counter", "unicaevents"),
					"desc" => wp_kses( __("Display counter before each toggles title", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "off",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['on_off']
				),
				"icon_closed" => array(
					"title" => esc_html__("Icon while closed",  'unicaevents'),
					"desc" => wp_kses( __('Select icon for the closed toggles item from Fontello icons set',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
				),
				"icon_opened" => array(
					"title" => esc_html__("Icon while opened",  'unicaevents'),
					"desc" => wp_kses( __('Select icon for the opened toggles item from Fontello icons set',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
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
				"name" => "trx_toggles_item",
				"title" => esc_html__("Toggles item", "unicaevents"),
				"desc" => wp_kses( __("Toggles item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Toggles item title", "unicaevents"),
						"desc" => wp_kses( __("Title for current toggles item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"open" => array(
						"title" => esc_html__("Open on show", "unicaevents"),
						"desc" => wp_kses( __("Open current toggles item on show", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "no",
						"type" => "switch",
						"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
					),
					"icon_closed" => array(
						"title" => esc_html__("Icon while closed",  'unicaevents'),
						"desc" => wp_kses( __('Select icon for the closed toggles item from Fontello icons set',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "icons",
						"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
					),
					"icon_opened" => array(
						"title" => esc_html__("Icon while opened",  'unicaevents'),
						"desc" => wp_kses( __('Select icon for the opened toggles item from Fontello icons set',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "icons",
						"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
					),
					"_content_" => array(
						"title" => esc_html__("Toggles item content", "unicaevents"),
						"desc" => wp_kses( __("Current toggles item content", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
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
if ( !function_exists( 'unicaevents_sc_toggles_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_toggles_reg_shortcodes_vc');
	function unicaevents_sc_toggles_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_toggles",
			"name" => esc_html__("Toggles", "unicaevents"),
			"description" => wp_kses( __("Toggles items", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_toggles',
			"class" => "trx_sc_collection trx_sc_toggles",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_toggles_item'),
			"params" => array(
				array(
					"param_name" => "counter",
					"heading" => esc_html__("Counter", "unicaevents"),
					"description" => wp_kses( __("Display counter before each toggles title", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Add item numbers before each element" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", "unicaevents"),
					"description" => wp_kses( __("Select icon for the closed toggles item from Fontello icons set", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", "unicaevents"),
					"description" => wp_kses( __("Select icon for the opened toggles item from Fontello icons set", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_top'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_bottom'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_left'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_right']
			),
			'default_content' => '
				[trx_toggles_item title="' . esc_html__( 'Item 1 title', 'unicaevents' ) . '"][/trx_toggles_item]
				[trx_toggles_item title="' . esc_html__( 'Item 2 title', 'unicaevents' ) . '"][/trx_toggles_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", "unicaevents").'">'.esc_html__("Add item", "unicaevents").'</button>
				</div>
			',
			'js_view' => 'VcTrxTogglesView'
		) );
		
		
		vc_map( array(
			"base" => "trx_toggles_item",
			"name" => esc_html__("Toggles item", "unicaevents"),
			"description" => wp_kses( __("Single toggles item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_toggles_item',
			"as_child" => array('only' => 'trx_toggles'),
			"as_parent" => array('except' => 'trx_toggles'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "unicaevents"),
					"description" => wp_kses( __("Title for current toggles item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Open on show", "unicaevents"),
					"description" => wp_kses( __("Open current toggle item on show", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Opened" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", "unicaevents"),
					"description" => wp_kses( __("Select icon for the closed toggles item from Fontello icons set", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", "unicaevents"),
					"description" => wp_kses( __("Select icon for the opened toggles item from Fontello icons set", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['css']
			),
			'js_view' => 'VcTrxTogglesTabView'
		) );
		class WPBakeryShortCode_Trx_Toggles extends UNICAEVENTS_VC_ShortCodeToggles {}
		class WPBakeryShortCode_Trx_Toggles_Item extends UNICAEVENTS_VC_ShortCodeTogglesItem {}
	}
}
?>
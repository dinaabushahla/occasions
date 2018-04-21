<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_price_block_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_price_block_theme_setup' );
	function unicaevents_sc_price_block_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_price_block_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('unicaevents_sc_price_block')) {	
	function unicaevents_sc_price_block($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
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
			"right" => "",
			"purchase" => "",
			"purchase_link" => "",
			"trending" => ""
			), $atts)));
		$output = '';
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= unicaevents_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
		. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
		. (!empty($class) ? ' '.esc_attr($class) : '')
		. ($scheme && !unicaevents_param_is_off($scheme) && !unicaevents_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
		. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
		. '"'
		. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
		. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
		. '>'
		. ($trending == 'on' ? '<span class="trending_product">'.esc_html__("trending!", "unicaevents").'</span>' : '')
		. '<div class="sc_price_block_money">'
		. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
		. ($money)
		. '</div>'
		. (!empty($title) ? '<div class="sc_price_block_title">'.($title).'</div>' : '')
		. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
		. (!empty($link_text) ? '<div class="sc_price_block_link"><a class="more_button" href="'.($link ? esc_url($link) : '#').'">'.($link_text).'</a></div>' : '')
		. ($purchase == 'on' ? '<a class="purchase_button" href="'.esc_url($purchase_link).'">purchase</a>' : '')
		. '</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	unicaevents_require_shortcode('trx_price_block', 'unicaevents_sc_price_block');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_price_block_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_price_block_reg_shortcodes');
	function unicaevents_sc_price_block_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;

		$UNICAEVENTS_GLOBALS['shortcodes']["trx_price_block"] = array(
			"title" => esc_html__("Price block", "unicaevents"),
			"desc" => wp_kses( __("Insert price block with title, price and description", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Block style", "unicaevents"),
					"desc" => wp_kses( __("Select style for this price block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => 1,
					"options" => unicaevents_get_list_styles(1, 3),
					"type" => "checklist"
					),
				"trending" => array(
					"title" => esc_html__('Is it trending?',  'unicaevents'),
					"desc" => wp_kses( __("Choose it if that is trending", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "off",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['on_off']
					),
				"title" => array(
					"title" => esc_html__("Title", "unicaevents"),
					"desc" => wp_kses( __("Block title", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
					),
				"link" => array(
					"title" => esc_html__("Link URL", "unicaevents"),
					"desc" => wp_kses( __("URL for link from button (at bottom of the block)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
					),
				"link_text" => array(
					"title" => esc_html__("Link text", "unicaevents"),
					"desc" => wp_kses( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
					),
				"purchase" => array(
					"title" => esc_html__('Add purchase button',  'unicaevents'),
					"desc" => wp_kses( __("Add purchase button to block bottom", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "off",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['on_off']
					),
				"purchase_link" => array(
					"title" => esc_html__("Link URL for purchase button", "unicaevents"),
					"desc" => wp_kses( __("Add URL for purchase button", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'purchase' => array('on')
					),
					"value" => "",
					"type" => "text"
					),
				"icon" => array(
					"title" => esc_html__("Icon",  'unicaevents'),
					"desc" => wp_kses( __('Select icon from Fontello icons set (placed before/instead price)',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
					),
				"money" => array(
					"title" => esc_html__("Money", "unicaevents"),
					"desc" => wp_kses( __("Money value (dot or comma separated)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
					),
				"currency" => array(
					"title" => esc_html__("Currency", "unicaevents"),
					"desc" => wp_kses( __("Currency character", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "$",
					"type" => "text"
					),
				"period" => array(
					"title" => esc_html__("Period", "unicaevents"),
					"desc" => wp_kses( __("Period text (if need). For example: monthly, daily, etc.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
					),
				"scheme" => array(
					"title" => esc_html__("Color scheme", "unicaevents"),
					"desc" => wp_kses( __("Select color scheme for this block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['schemes']
					),
				"align" => array(
					"title" => esc_html__("Alignment", "unicaevents"),
					"desc" => wp_kses( __("Align price to left or right side", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['float']
					), 
				"_content_" => array(
					"title" => esc_html__("Description", "unicaevents"),
					"desc" => wp_kses( __("Description for this price block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'unicaevents_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_price_block_reg_shortcodes_vc');
	function unicaevents_sc_price_block_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;

		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", "unicaevents"),
			"description" => wp_kses( __("Insert price block with title, price and description", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", "unicaevents"),
					"desc" => wp_kses( __("Select style of this price block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip(unicaevents_get_list_styles(1, 3)),
					"type" => "dropdown"
					),
				array(
					"param_name" => "trending",
					"heading" => esc_html__("Is it trending?", "unicaevents"),
					"description" => wp_kses( __("Choose it if that is trending", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Show" => "on" ),
					"type" => "checkbox"
					),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "unicaevents"),
					"description" => wp_kses( __("Block title", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
					),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "unicaevents"),
					"description" => wp_kses( __("URL for link from button (at bottom of the block)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
					),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", "unicaevents"),
					"description" => wp_kses( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
					),
				array(
					"param_name" => "purchase",
					"heading" => esc_html__("Add purchacse", "unicaevents"),
					"description" => wp_kses( __("Add purchase button to block bottom", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Show" => "on" ),
					"type" => "checkbox"
					),
				array(
					"param_name" => "purchase_link",
					"heading" => esc_html__("Link URL for purchase button", "unicaevents"),
					"description" => wp_kses( __("Add URL for purchase button", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"dependency" => array(
						'purchase' => array('on')
						),
					"value" => "",
					"type" => "textfield"
					),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", "unicaevents"),
					"description" => wp_kses( __("Select icon from Fontello icons set (placed before/instead price)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
					),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", "unicaevents"),
					"description" => wp_kses( __("Money value (dot or comma separated)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
					),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", "unicaevents"),
					"description" => wp_kses( __("Currency character", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'unicaevents'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
					),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", "unicaevents"),
					"description" => wp_kses( __("Period text (if need). For example: monthly, daily, etc.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
					),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "unicaevents"),
					"description" => wp_kses( __("Select color scheme for this block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'unicaevents'),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['schemes']),
					"type" => "dropdown"
					),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "unicaevents"),
					"description" => wp_kses( __("Align price to left or right side", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['float']),
					"type" => "dropdown"
					),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", "unicaevents"),
					"description" => wp_kses( __("Description for this price block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
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

class WPBakeryShortCode_Trx_PriceBlock extends UNICAEVENTS_VC_ShortCodeSingle {}
}
}
?>
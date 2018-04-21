<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_googlemap_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_googlemap_theme_setup' );
	function unicaevents_sc_googlemap_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_googlemap_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('unicaevents_sc_googlemap')) {	
	function unicaevents_sc_googlemap($atts, $content = null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= unicaevents_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = unicaevents_get_custom_option('googlemap_style');
		unicaevents_enqueue_script( 'googlemap', unicaevents_get_protocol().'://maps.google.com/maps/api/js?sensor=false', array(), null, true );
		unicaevents_enqueue_script( 'unicaevents-googlemap-script', unicaevents_get_file_url('js/core.googlemap.js'), array(), null, true );
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_googlemap_markers'] = array();
		$content = do_shortcode($content);
		$output = '';
		if (count($UNICAEVENTS_GLOBALS['sc_googlemap_markers']) == 0) {
			$UNICAEVENTS_GLOBALS['sc_googlemap_markers'][] = array(
				'title' => unicaevents_get_custom_option('googlemap_title'),
				'description' => unicaevents_strmacros(unicaevents_get_custom_option('googlemap_description')),
				'latlng' => unicaevents_get_custom_option('googlemap_latlng'),
				'address' => unicaevents_get_custom_option('googlemap_address'),
				'point' => unicaevents_get_custom_option('googlemap_marker')
			);
		}
		$output .= '<div id="'.esc_attr($id).'"'
			. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
			. ' data-zoom="'.esc_attr($zoom).'"'
			. ' data-style="'.esc_attr($style).'"'
			. '>';
		$cnt = 0;
		foreach ($UNICAEVENTS_GLOBALS['sc_googlemap_markers'] as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.$cnt;
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(unicaevents_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	unicaevents_require_shortcode("trx_googlemap", "unicaevents_sc_googlemap");
}


if (!function_exists('unicaevents_sc_googlemap_marker')) {	
	function unicaevents_sc_googlemap_marker($atts, $content = null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_googlemap_markers'][] = array(
			'id' => $id,
			'title' => $title,
			'description' => do_shortcode($content),
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : unicaevents_get_custom_option('googlemap_marker')
		);
		return '';
	}
	unicaevents_require_shortcode("trx_googlemap_marker", "unicaevents_sc_googlemap_marker");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_googlemap_reg_shortcodes');
	function unicaevents_sc_googlemap_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_googlemap"] = array(
			"title" => esc_html__("Google map", "unicaevents"),
			"desc" => wp_kses( __("Insert Google map with specified markers", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", "unicaevents"),
					"desc" => wp_kses( __("Map zoom factor", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", "unicaevents"),
					"desc" => wp_kses( __("Select map style", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "default",
					"type" => "checklist",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['googlemap_styles']
				),
				"width" => unicaevents_shortcodes_width('100%'),
				"height" => unicaevents_shortcodes_height(240),
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
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", "unicaevents"),
				"desc" => wp_kses( __("Google map marker", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", "unicaevents"),
						"desc" => wp_kses( __("Address of this marker", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", "unicaevents"),
						"desc" => wp_kses( __("Comma separated marker's coorditanes (instead Address)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", "unicaevents"),
						"desc" => wp_kses( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", "unicaevents"),
						"desc" => wp_kses( __("Title for this marker", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", "unicaevents"),
						"desc" => wp_kses( __("Description for this marker", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => $UNICAEVENTS_GLOBALS['sc_params']['id']
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_googlemap_reg_shortcodes_vc');
	function unicaevents_sc_googlemap_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", "unicaevents"),
			"description" => wp_kses( __("Insert Google map with desired address or coordinates", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", "unicaevents"),
					"description" => wp_kses( __("Map zoom factor", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "unicaevents"),
					"description" => wp_kses( __("Map custom style", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['googlemap_styles']),
					"type" => "dropdown"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['animation'],
				$UNICAEVENTS_GLOBALS['vc_params']['css'],
				unicaevents_vc_width('100%'),
				unicaevents_vc_height(240),
				$UNICAEVENTS_GLOBALS['vc_params']['margin_top'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_bottom'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_left'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_right']
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", "unicaevents"),
			"description" => wp_kses( __("Insert new marker into Google map", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			//"allowed_container_element" => 'vc_row',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", "unicaevents"),
					"description" => wp_kses( __("Address of this marker", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", "unicaevents"),
					"description" => wp_kses( __("Comma separated marker's coorditanes (instead Address)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "unicaevents"),
					"description" => wp_kses( __("Title for this marker", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", "unicaevents"),
					"description" => wp_kses( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id']
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends UNICAEVENTS_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends UNICAEVENTS_VC_ShortCodeCollection {}
	}
}
?>
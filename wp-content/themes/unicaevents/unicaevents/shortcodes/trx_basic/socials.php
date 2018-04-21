<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_socials_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_socials_theme_setup' );
	function unicaevents_sc_socials_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_socials_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('unicaevents_sc_socials')) {	
	function unicaevents_sc_socials($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => unicaevents_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		$UNICAEVENTS_GLOBALS['sc_social_icons'] = false;
		$UNICAEVENTS_GLOBALS['sc_social_type'] = $type;
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? unicaevents_get_socials_url($s[0]) : 'icon-'.$s[0],
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) $UNICAEVENTS_GLOBALS['sc_social_icons'] = $list;
		} else if (unicaevents_param_is_off($custom))
			$content = do_shortcode($content);
		if ($UNICAEVENTS_GLOBALS['sc_social_icons']===false) $UNICAEVENTS_GLOBALS['sc_social_icons'] = unicaevents_get_custom_option('social_icons');
		$output = unicaevents_prepare_socials($UNICAEVENTS_GLOBALS['sc_social_icons']);
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	unicaevents_require_shortcode('trx_socials', 'unicaevents_sc_socials');
}


if (!function_exists('unicaevents_sc_social_item')) {	
	function unicaevents_sc_social_item($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		global $UNICAEVENTS_GLOBALS;
		if (!empty($name) && empty($icon)) {
			$type = $UNICAEVENTS_GLOBALS['sc_social_type'];
			if ($type=='images') {
				if (file_exists(unicaevents_get_socials_dir($name.'.png')))
					$icon = unicaevents_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if ($UNICAEVENTS_GLOBALS['sc_social_icons']===false) $UNICAEVENTS_GLOBALS['sc_social_icons'] = array();
			$UNICAEVENTS_GLOBALS['sc_social_icons'][] = array(
				'icon' => $icon,
				'url' => $url
			);
		}
		return '';
	}
	unicaevents_require_shortcode('trx_social_item', 'unicaevents_sc_social_item');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_socials_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_socials_reg_shortcodes');
	function unicaevents_sc_socials_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_socials"] = array(
			"title" => esc_html__("Social icons", "unicaevents"),
			"desc" => wp_kses( __("List of social icons (with hovers)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", "unicaevents"),
					"desc" => wp_kses( __("Type of the icons - images or font icons", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => unicaevents_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'unicaevents'),
						'images' => esc_html__('Images', 'unicaevents')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", "unicaevents"),
					"desc" => wp_kses( __("Size of the icons", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "small",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['sizes'],
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", "unicaevents"),
					"desc" => wp_kses( __("Shape of the icons", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "square",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['shapes'],
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", "unicaevents"),
					"desc" => wp_kses( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", "unicaevents"),
					"desc" => wp_kses( __("Make custom icons from inner shortcodes (prepare it on tabs)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "no",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no'],
					"type" => "switch"
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", "unicaevents"),
				"desc" => wp_kses( __("Custom social item: name, profile url and icon url", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", "unicaevents"),
						"desc" => wp_kses( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", "unicaevents"),
						"desc" => wp_kses( __("URL of your profile in specified social network", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", "unicaevents"),
						"desc" => wp_kses( __("Select or upload image or write URL from other site for the current social icon", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_socials_reg_shortcodes_vc');
	function unicaevents_sc_socials_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", "unicaevents"),
			"description" => wp_kses( __("Custom social icons", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", "unicaevents"),
					"description" => wp_kses( __("Type of the icons - images or font icons", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"std" => unicaevents_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'unicaevents') => 'icons',
						esc_html__('Images', 'unicaevents') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", "unicaevents"),
					"description" => wp_kses( __("Size of the icons", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"std" => "small",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['sizes']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", "unicaevents"),
					"description" => wp_kses( __("Shape of the icons", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"std" => "square",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['shapes']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", "unicaevents"),
					"description" => wp_kses( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", "unicaevents"),
					"description" => wp_kses( __("Make custom icons from inner shortcodes (prepare it on tabs)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'unicaevents') => 'yes'),
					"type" => "checkbox"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['animation'],
				$UNICAEVENTS_GLOBALS['vc_params']['css'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_top'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_bottom'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_left'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_right']
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", "unicaevents"),
			"description" => wp_kses( __("Custom social item: name, profile url and icon url", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", "unicaevents"),
					"description" => wp_kses( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", "unicaevents"),
					"description" => wp_kses( __("URL of your profile in specified social network", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", "unicaevents"),
					"description" => wp_kses( __("Select or upload image or write URL from other site for the current social icon", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends UNICAEVENTS_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends UNICAEVENTS_VC_ShortCodeSingle {}
	}
}
?>
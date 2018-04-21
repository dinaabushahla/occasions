<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_title_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_title_theme_setup' );
	function unicaevents_sc_title_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_title_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('unicaevents_sc_title')) {	
	function unicaevents_sc_title($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= unicaevents_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !unicaevents_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !unicaevents_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(unicaevents_strpos($image, 'http:')!==false ? $image : unicaevents_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !unicaevents_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='style1' ? '<span class="sc_title_style1_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='style1' ? '<span class="sc_title_style1_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	unicaevents_require_shortcode('trx_title', 'unicaevents_sc_title');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_title_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_title_reg_shortcodes');
	function unicaevents_sc_title_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_title"] = array(
			"title" => esc_html__("Title", "unicaevents"),
			"desc" => wp_kses( __("Create header tag (1-6 level) with many styles", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", "unicaevents"),
					"desc" => wp_kses( __("Title content", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", "unicaevents"),
					"desc" => wp_kses( __("Title type (header level)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"style1" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'unicaevents'),
						'2' => esc_html__('Header 2', 'unicaevents'),
						'3' => esc_html__('Header 3', 'unicaevents'),
						'4' => esc_html__('Header 4', 'unicaevents'),
						'5' => esc_html__('Header 5', 'unicaevents'),
						'6' => esc_html__('Header 6', 'unicaevents'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", "unicaevents"),
					"desc" => wp_kses( __("Title style", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'unicaevents'),
						'underline' => esc_html__('Underline', 'unicaevents'),
						'style1' => esc_html__('Style1', 'unicaevents'),
						'iconed' => esc_html__('With icon (image)', 'unicaevents')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", "unicaevents"),
					"desc" => wp_kses( __("Title text alignment", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['align']
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", "unicaevents"),
					"desc" => wp_kses( __("Custom font size. If empty - use theme default", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", "unicaevents"),
					"desc" => wp_kses( __("Custom font weight. If empty or inherit - use theme default", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'unicaevents'),
						'100' => esc_html__('Thin (100)', 'unicaevents'),
						'300' => esc_html__('Light (300)', 'unicaevents'),
						'400' => esc_html__('Normal (400)', 'unicaevents'),
						'600' => esc_html__('Semibold (600)', 'unicaevents'),
						'700' => esc_html__('Bold (700)', 'unicaevents'),
						'900' => esc_html__('Black (900)', 'unicaevents')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", "unicaevents"),
					"desc" => wp_kses( __("Select color for the title", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'unicaevents'),
					"desc" => wp_kses( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'unicaevents'),
					"desc" => wp_kses( __("Select image icon for the title instead icon above (if style=iconed)",  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['images']
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', "unicaevents"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site (if style=iconed)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', "unicaevents"),
					"desc" => wp_kses( __("Select image (picture) size (if style='iconed')", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'unicaevents'),
						'medium' => esc_html__('Medium', 'unicaevents'),
						'large' => esc_html__('Large', 'unicaevents')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', "unicaevents"),
					"desc" => wp_kses( __("Select icon (image) position (if style=iconed)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'unicaevents'),
						'left' => esc_html__('Left', 'unicaevents')
					)
				),
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
if ( !function_exists( 'unicaevents_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_title_reg_shortcodes_vc');
	function unicaevents_sc_title_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", "unicaevents"),
			"description" => wp_kses( __("Create header tag (1-6 level) with many styles", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", "unicaevents"),
					"description" => wp_kses( __("Title content", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", "unicaevents"),
					"description" => wp_kses( __("Title type (header level)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'unicaevents') => '1',
						esc_html__('Header 2', 'unicaevents') => '2',
						esc_html__('Header 3', 'unicaevents') => '3',
						esc_html__('Header 4', 'unicaevents') => '4',
						esc_html__('Header 5', 'unicaevents') => '5',
						esc_html__('Header 6', 'unicaevents') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", "unicaevents"),
					"description" => wp_kses( __("Title style: only text (regular) or with icon/image (iconed)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'unicaevents') => 'regular',
						esc_html__('Underline', 'unicaevents') => 'underline',
						esc_html__('Style1', 'unicaevents') => 'style1',
						esc_html__('With icon (image)', 'unicaevents') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "unicaevents"),
					"description" => wp_kses( __("Title text alignment", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", "unicaevents"),
					"description" => wp_kses( __("Custom font size. If empty - use theme default", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", "unicaevents"),
					"description" => wp_kses( __("Custom font weight. If empty or inherit - use theme default", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'unicaevents') => 'inherit',
						esc_html__('Thin (100)', 'unicaevents') => '100',
						esc_html__('Light (300)', 'unicaevents') => '300',
						esc_html__('Normal (400)', 'unicaevents') => '400',
						esc_html__('Semibold (600)', 'unicaevents') => '600',
						esc_html__('Bold (700)', 'unicaevents') => '700',
						esc_html__('Black (900)', 'unicaevents') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", "unicaevents"),
					"description" => wp_kses( __("Select color for the title", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", "unicaevents"),
					"description" => wp_kses( __("Select font icon for the title from Fontello icons set (if style=iconed)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'unicaevents'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", "unicaevents"),
					"description" => wp_kses( __("Select image icon for the title instead icon above (if style=iconed)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'unicaevents'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['images'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", "unicaevents"),
					"description" => wp_kses( __("Select or upload image or write URL from other site (if style=iconed)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Icon &amp; Image', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", "unicaevents"),
					"description" => wp_kses( __("Select image (picture) size (if style=iconed)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Icon &amp; Image', 'unicaevents'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'unicaevents') => 'small',
						esc_html__('Medium', 'unicaevents') => 'medium',
						esc_html__('Large', 'unicaevents') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", "unicaevents"),
					"description" => wp_kses( __("Select icon (image) position (if style=iconed)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Icon &amp; Image', 'unicaevents'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'unicaevents') => 'top',
						esc_html__('Left', 'unicaevents') => 'left'
					),
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends UNICAEVENTS_VC_ShortCodeSingle {}
	}
}
?>
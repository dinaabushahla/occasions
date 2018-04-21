<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_video_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_video_theme_setup' );
	function unicaevents_sc_video_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_video_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_video_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_video id="unique_id" url="http://player.vimeo.com/video/20245032?title=0&amp;byline=0&amp;portrait=0" width="" height=""]

if (!function_exists('unicaevents_sc_video')) {	
	function unicaevents_sc_video($atts, $content = null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"url" => '',
			"src" => '',
			"image" => '',
			"ratio" => '16:9',
			"autoplay" => 'off',
			"align" => '',
			"bg_image" => '',
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($autoplay)) $autoplay = 'off';
		
		$ratio = empty($ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $ratio);
		$ratio_parts = explode(':', $ratio);
		if (empty($height) && empty($width)) {
			$width='100%';
			if (unicaevents_param_is_off(unicaevents_get_custom_option('substitute_video'))) $height="400";
		}
		$ed = unicaevents_substr($width, -1);
		if (empty($height) && !empty($width) && $ed!='%') {
			$height = round($width / $ratio_parts[0] * $ratio_parts[1]);
		}
		if (!empty($height) && empty($width)) {
			$width = round($height * $ratio_parts[0] / $ratio_parts[1]);
		}
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$css_dim = unicaevents_get_css_dimensions_from_values($width, $height);
		$css_bg = unicaevents_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
	
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		$url = $src!='' ? $src : $url;
		if ($image!='' && unicaevents_param_is_off($image))
			$image = '';
		else {
			if (unicaevents_param_is_on($autoplay) && is_singular() && !unicaevents_get_global('blog_streampage'))
				$image = '';
			else {
				if ($image > 0) {
					$attach = wp_get_attachment_image_src( $image, 'full' );
					if (isset($attach[0]) && $attach[0]!='')
						$image = $attach[0];
				}
				if ($bg_image) {
					$thumb_sizes = unicaevents_get_thumb_sizes(array(
						'layout' => 'grid_3'
					));
					if (!is_single() || !empty($image)) $image = unicaevents_get_resized_image_url(empty($image) ? get_the_ID() : $image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, false);
				} else
					if (!is_single() || !empty($image)) $image = unicaevents_get_resized_image_url(empty($image) ? get_the_ID() : $image, $ed!='%' ? $width : null, $height);
				if (empty($image) && (!is_singular() || unicaevents_get_global('blog_streampage')))	// || unicaevents_param_is_off($autoplay)))
					$image = unicaevents_get_video_cover_image($url);
			}
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
	
		$url = unicaevents_get_video_player_url($src!='' ? $src : $url);
		
		$video = '<video' . ($id ? ' id="' . esc_attr($id) . '"' : '') 
			. ' class="sc_video'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
			. ' src="' . esc_url($url) . '"'
			. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
			. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
			. ' data-ratio="'.esc_attr($ratio).'"'
			. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
			. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
			. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
			. ($bg_image ? ' data-bg-image="'.esc_attr($bg_image).'"' : '') 
			. ($css_bg!='' ? ' data-style="'.esc_attr($css_bg).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. (($image && unicaevents_param_is_on(unicaevents_get_custom_option('substitute_video'))) || (unicaevents_param_is_on($autoplay) && is_singular() && !unicaevents_get_global('blog_streampage')) ? ' autoplay="autoplay"' : '') 
			. ' controls="controls" loop="loop"'
			. '>'
			. '</video>';
		if (unicaevents_param_is_off(unicaevents_get_custom_option('substitute_video'))) {
			if (unicaevents_param_is_on($frame)) $video = unicaevents_get_video_frame($video, $image, $css, $css_bg);
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$video = unicaevents_substitute_video($video, $width, $height, false);
			}
		}
		if (unicaevents_get_theme_option('use_mediaelement')=='yes')
			unicaevents_enqueue_script('wp-mediaelement');
		return apply_filters('unicaevents_shortcode_output', $video, 'trx_video', $atts, $content);
	}
	unicaevents_require_shortcode("trx_video", "unicaevents_sc_video");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_video_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_video_reg_shortcodes');
	function unicaevents_sc_video_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_video"] = array(
			"title" => esc_html__("Video", "unicaevents"),
			"desc" => wp_kses( __("Insert video player", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for video file", "unicaevents"),
					"desc" => wp_kses( __("Select video from media library or paste URL for video file from other site", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose video', 'unicaevents'),
						'action' => 'media_upload',
						'type' => 'video',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose video file', 'unicaevents'),
							'update' => esc_html__('Select video file', 'unicaevents')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"ratio" => array(
					"title" => esc_html__("Ratio", "unicaevents"),
					"desc" => wp_kses( __("Ratio of the video", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "16:9",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"16:9" => esc_html__("16:9", 'unicaevents'),
						"4:3" => esc_html__("4:3", 'unicaevents')
					)
				),
				"autoplay" => array(
					"title" => esc_html__("Autoplay video", "unicaevents"),
					"desc" => wp_kses( __("Autoplay video on page load", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "off",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['on_off']
				),
				"align" => array(
					"title" => esc_html__("Align", "unicaevents"),
					"desc" => wp_kses( __("Select block alignment", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['align']
				),
				"image" => array(
					"title" => esc_html__("Cover image", "unicaevents"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site for video preview", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image", "unicaevents"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_top" => array(
					"title" => esc_html__("Top offset", "unicaevents"),
					"desc" => wp_kses( __("Top offset (padding) inside background image to video block (in percent). For example: 3%", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_bottom" => array(
					"title" => esc_html__("Bottom offset", "unicaevents"),
					"desc" => wp_kses( __("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_left" => array(
					"title" => esc_html__("Left offset", "unicaevents"),
					"desc" => wp_kses( __("Left offset (padding) inside background image to video block (in percent). For example: 20%", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_right" => array(
					"title" => esc_html__("Right offset", "unicaevents"),
					"desc" => wp_kses( __("Right offset (padding) inside background image to video block (in percent). For example: 12%", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'bg_image' => array('not_empty')
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
if ( !function_exists( 'unicaevents_sc_video_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_video_reg_shortcodes_vc');
	function unicaevents_sc_video_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_video",
			"name" => esc_html__("Video", "unicaevents"),
			"description" => wp_kses( __("Insert video player", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_video',
			"class" => "trx_sc_single trx_sc_video",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("URL for video file", "unicaevents"),
					"description" => wp_kses( __("Paste URL for video file", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "ratio",
					"heading" => esc_html__("Ratio", "unicaevents"),
					"description" => wp_kses( __("Select ratio for display video", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('16:9', 'unicaevents') => "16:9",
						esc_html__('4:3', 'unicaevents') => "4:3"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "autoplay",
					"heading" => esc_html__("Autoplay video", "unicaevents"),
					"description" => wp_kses( __("Autoplay video on page load", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array("Autoplay" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "unicaevents"),
					"description" => wp_kses( __("Select block alignment", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Cover image", "unicaevents"),
					"description" => wp_kses( __("Select or upload image or write URL from other site for video preview", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", "unicaevents"),
					"description" => wp_kses( __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Background', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_top",
					"heading" => esc_html__("Top offset", "unicaevents"),
					"description" => wp_kses( __("Top offset (padding) from background image to video block (in percent). For example: 3%", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Background', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_bottom",
					"heading" => esc_html__("Bottom offset", "unicaevents"),
					"description" => wp_kses( __("Bottom offset (padding) from background image to video block (in percent). For example: 3%", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Background', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_left",
					"heading" => esc_html__("Left offset", "unicaevents"),
					"description" => wp_kses( __("Left offset (padding) from background image to video block (in percent). For example: 20%", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Background', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_right",
					"heading" => esc_html__("Right offset", "unicaevents"),
					"description" => wp_kses( __("Right offset (padding) from background image to video block (in percent). For example: 12%", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Background', 'unicaevents'),
					"class" => "",
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Video extends UNICAEVENTS_VC_ShortCodeSingle {}
	}
}
?>
<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_search_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_search_theme_setup' );
	function unicaevents_sc_search_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_search_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('unicaevents_sc_search')) {	
	function unicaevents_sc_search($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'unicaevents'),
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
		if (empty($ajax)) $ajax = unicaevents_get_theme_option('use_ajax_search');
		// Load core messages
		unicaevents_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (unicaevents_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url( home_url( '/' ) ) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_attr__('Open search', 'unicaevents') : esc_attr__('Start search', 'unicaevents')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !unicaevents_param_is_off($scheme) && !unicaevents_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	unicaevents_require_shortcode('trx_search', 'unicaevents_sc_search');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_search_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_search_reg_shortcodes');
	function unicaevents_sc_search_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_search"] = array(
			"title" => esc_html__("Search", "unicaevents"),
			"desc" => wp_kses( __("Show search form", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", "unicaevents"),
					"desc" => wp_kses( __("Select style to display search field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'unicaevents'),
						"rounded" => esc_html__('Rounded', 'unicaevents')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", "unicaevents"),
					"desc" => wp_kses( __("Select search field initial state", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'unicaevents'),
						"opened" => esc_html__('Opened', 'unicaevents'),
						"closed" => esc_html__('Closed', 'unicaevents')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", "unicaevents"),
					"desc" => wp_kses( __("Title (placeholder) for the search field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => esc_html__("Search &hellip;", 'unicaevents'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", "unicaevents"),
					"desc" => wp_kses( __("Search via AJAX or reload page", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "yes",
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
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_search_reg_shortcodes_vc');
	function unicaevents_sc_search_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", "unicaevents"),
			"description" => wp_kses( __("Insert search form", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "unicaevents"),
					"description" => wp_kses( __("Select style to display search field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'unicaevents') => "regular",
						esc_html__('Flat', 'unicaevents') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", "unicaevents"),
					"description" => wp_kses( __("Select search field initial state", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'unicaevents')  => "fixed",
						esc_html__('Opened', 'unicaevents') => "opened",
						esc_html__('Closed', 'unicaevents') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "unicaevents"),
					"description" => wp_kses( __("Title (placeholder) for the search field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'unicaevents'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", "unicaevents"),
					"description" => wp_kses( __("Search via AJAX or reload page", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'unicaevents') => 'yes'),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends UNICAEVENTS_VC_ShortCodeSingle {}
	}
}
?>
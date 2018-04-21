<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_skills_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_skills_theme_setup' );
	function unicaevents_sc_skills_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_skills_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_skills_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_skills id="unique_id" type="bar|pie|arc|counter" dir="horizontal|vertical" layout="rows|columns" count="" max_value="100" align="left|right"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
[/trx_skills]
*/

if (!function_exists('unicaevents_sc_skills')) {	
	function unicaevents_sc_skills($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"max_value" => "100",
			"type" => "bar",
			"layout" => "",
			"dir" => "",
			"style" => "1",
			"columns" => "",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"arc_caption" => esc_html__("Skills", "unicaevents"),
			"pie_compact" => "on",
			"pie_cutout" => 0,
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'unicaevents'),
			"link" => '',
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
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_skills_counter'] = 0;
		$UNICAEVENTS_GLOBALS['sc_skills_columns'] = 0;
		$UNICAEVENTS_GLOBALS['sc_skills_height']  = 0;
		$UNICAEVENTS_GLOBALS['sc_skills_type']    = $type;
		$UNICAEVENTS_GLOBALS['sc_skills_pie_compact'] = unicaevents_param_is_on($pie_compact) ? 'on' : 'off';
		$UNICAEVENTS_GLOBALS['sc_skills_pie_cutout']  = max(0, min(99, $pie_cutout));
		$UNICAEVENTS_GLOBALS['sc_skills_color']   = $color;
		$UNICAEVENTS_GLOBALS['sc_skills_bg_color']= $bg_color;
		$UNICAEVENTS_GLOBALS['sc_skills_border_color']= $border_color;
		$UNICAEVENTS_GLOBALS['sc_skills_legend']  = '';
		$UNICAEVENTS_GLOBALS['sc_skills_data']    = '';
		unicaevents_enqueue_diagram($type);
		if ($type!='arc') {
			if ($layout=='' || ($layout=='columns' && $columns<1)) $layout = 'rows';
			if ($layout=='columns') $UNICAEVENTS_GLOBALS['sc_skills_columns'] = $columns;
			if ($type=='bar') {
				if ($dir == '') $dir = 'horizontal';
				if ($dir == 'vertical' && $height < 1) $height = 300;
			}
		}
		if (empty($id)) $id = 'sc_skills_diagram_'.str_replace('.','',mt_rand());
		if ($max_value < 1) $max_value = 100;
		if ($style) {
			$style = max(1, min(4, $style));
			$UNICAEVENTS_GLOBALS['sc_skills_style'] = $style;
		}
		$UNICAEVENTS_GLOBALS['sc_skills_max'] = $max_value;
		$UNICAEVENTS_GLOBALS['sc_skills_dir'] = $dir;
		$UNICAEVENTS_GLOBALS['sc_skills_height'] = unicaevents_prepare_css_value($height);
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= unicaevents_get_css_dimensions_from_values($width);
		if (!empty($UNICAEVENTS_GLOBALS['sc_skills_height']) && ($UNICAEVENTS_GLOBALS['sc_skills_type'] == 'arc' || ($UNICAEVENTS_GLOBALS['sc_skills_type'] == 'pie' && unicaevents_param_is_on($UNICAEVENTS_GLOBALS['sc_skills_pie_compact']))))
			$css .= 'height: '.$UNICAEVENTS_GLOBALS['sc_skills_height'];
		$content = do_shortcode($content);
		$output = '<div id="'.esc_attr($id).'"' 
					. ' class="sc_skills sc_skills_' . esc_attr($type) 
						. ($type=='bar' ? ' sc_skills_'.esc_attr($dir) : '') 
						. ($type=='pie' ? ' sc_skills_compact_'.esc_attr($UNICAEVENTS_GLOBALS['sc_skills_pie_compact']) : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
					. ' data-type="'.esc_attr($type).'"'
					. ' data-caption="'.esc_attr($arc_caption).'"'
					. ($type=='bar' ? ' data-dir="'.esc_attr($dir).'"' : '')
				. '>'
					. (!empty($subtitle) ? '<h6 class="sc_skills_subtitle sc_item_subtitle">' . esc_html($subtitle) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_skills_title sc_item_title">' . esc_html($title) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_skills_descr sc_item_descr">' . trim($description) . '</div>' : '')
					. ($layout == 'columns' ? '<div class="columns_wrap sc_skills_'.esc_attr($layout).' sc_skills_columns_'.esc_attr($columns).'">' : '')
					. ($type=='arc' 
						? ('<div class="sc_skills_legend">'.($UNICAEVENTS_GLOBALS['sc_skills_legend']).'</div>'
							. '<div id="'.esc_attr($id).'_diagram" class="sc_skills_arc_canvas"></div>'
							. '<div class="sc_skills_data" style="display:none;">' . ($UNICAEVENTS_GLOBALS['sc_skills_data']) . '</div>'
						  )
						: '')
					. ($type=='pie' && unicaevents_param_is_on($UNICAEVENTS_GLOBALS['sc_skills_pie_compact'])
						? ('<div class="sc_skills_legend">'.($UNICAEVENTS_GLOBALS['sc_skills_legend']).'</div>'
							. '<div id="'.esc_attr($id).'_pie" class="sc_skills_item">'
								. '<canvas id="'.esc_attr($id).'_pie" class="sc_skills_pie_canvas"></canvas>'
								. '<div class="sc_skills_data" style="display:none;">' . ($UNICAEVENTS_GLOBALS['sc_skills_data']) . '</div>'
							. '</div>'
						  )
						: '')
					. ($content)
					. ($layout == 'columns' ? '</div>' : '')
					. (!empty($link) ? '<div class="sc_skills_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_skills', $atts, $content);
	}
	unicaevents_require_shortcode('trx_skills', 'unicaevents_sc_skills');
}


if (!function_exists('unicaevents_sc_skills_item')) {	
	function unicaevents_sc_skills_item($atts, $content=null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"value" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"style" => "",
			"icon" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $UNICAEVENTS_GLOBALS;
		$old_color = $color;
		if (empty($color)) $color = $UNICAEVENTS_GLOBALS['sc_skills_color'];
		if (empty($bg_color)) $bg_color = $UNICAEVENTS_GLOBALS['sc_skills_bg_color'];
		if (empty($border_color)) $border_color = $UNICAEVENTS_GLOBALS['sc_skills_border_color'];
		if (empty($border_color)) $border_color = unicaevents_get_scheme_color('bd_color', $border_color);
		if (empty($style)) $style = $UNICAEVENTS_GLOBALS['sc_skills_style'];
		$style = max(1, min(4, $style));
		$UNICAEVENTS_GLOBALS['sc_skills_counter']++;
		$ed = unicaevents_substr($value, -1)=='%' ? '%' : '';
		$value = str_replace('%', '', $value);
		if ($UNICAEVENTS_GLOBALS['sc_skills_max'] < $value) $UNICAEVENTS_GLOBALS['sc_skills_max'] = $value;
		$percent = round($value / $UNICAEVENTS_GLOBALS['sc_skills_max'] * 100);
		$start = 0;
		$stop = $value;
		$steps = 100;
		$step = max(1, round($UNICAEVENTS_GLOBALS['sc_skills_max']/$steps));
		$speed = mt_rand(10,40);
		$animation = round(($stop - $start) / $step * $speed);
		$title_block = '<div class="sc_skills_info"><div class="sc_skills_label"
		' . ($UNICAEVENTS_GLOBALS['sc_skills_type']=='counter' && $color || $bg_color || $border_color ? 'style="color:'.esc_attr($color).'; border-color:'.esc_attr($border_color).'"' : '') .'


		>' . ($title) . '</div></div>';
		
		$output = '';
		if ($UNICAEVENTS_GLOBALS['sc_skills_type'] == 'arc' || ($UNICAEVENTS_GLOBALS['sc_skills_type'] == 'pie' && unicaevents_param_is_on($UNICAEVENTS_GLOBALS['sc_skills_pie_compact']))) {
			if ($UNICAEVENTS_GLOBALS['sc_skills_type'] == 'arc' && empty($old_color)) {
				$rgb = unicaevents_hex2rgb($color);
				$color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.(1 - 0.1*($UNICAEVENTS_GLOBALS['sc_skills_counter']-1)).')';
			}
			$UNICAEVENTS_GLOBALS['sc_skills_legend'] .= '<div class="sc_skills_legend_item"><span class="sc_skills_legend_marker" style="background-color:'.esc_attr($color).'"></span><span class="sc_skills_legend_title">' . ($title) . '</span><span class="sc_skills_legend_value">' . ($value) ."%" . '</span></div>';
			$UNICAEVENTS_GLOBALS['sc_skills_data'] .= '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="'.esc_attr($UNICAEVENTS_GLOBALS['sc_skills_type']).'"'
				. ($UNICAEVENTS_GLOBALS['sc_skills_type']=='pie'
					? ( ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr($UNICAEVENTS_GLOBALS['sc_skills_max']).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr($UNICAEVENTS_GLOBALS['sc_skills_pie_cutout']).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'"'
						)
					: '')
				. '><input type="hidden" class="text" value="'.esc_attr($title).'" /><input type="hidden" class="percent" value="'.esc_attr($percent).'" /><input type="hidden" class="color" value="'.esc_attr($color).'" /></div>';
		} else {
			$output .= ($UNICAEVENTS_GLOBALS['sc_skills_columns'] > 0 ? '<div class="sc_skills_column column-1_'.esc_attr($UNICAEVENTS_GLOBALS['sc_skills_columns']).'">' : '')
					. ($UNICAEVENTS_GLOBALS['sc_skills_type']=='bar' && $UNICAEVENTS_GLOBALS['sc_skills_dir']=='horizontal' ? $title_block : '')
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_skills_item' . ($style ? ' sc_skills_style_'.esc_attr($style) : '') 
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($UNICAEVENTS_GLOBALS['sc_skills_counter'] % 2 == 1 ? ' odd' : ' even') 
							. ($UNICAEVENTS_GLOBALS['sc_skills_counter'] == 1 ? ' first' : '') 
							. '"'
						. ($UNICAEVENTS_GLOBALS['sc_skills_height'] !='' || $css 
							? ' style="' 
								. ($UNICAEVENTS_GLOBALS['sc_skills_height'] !='' ? 'height: '.esc_attr($UNICAEVENTS_GLOBALS['sc_skills_height']).';' : '') 
								. ($css) 
								. '"' 
							: '')
					. '>'
					. (!empty($icon) ? '<div class="sc_skills_icon '.esc_attr($icon).'"></div>' : '');
			if (in_array($UNICAEVENTS_GLOBALS['sc_skills_type'], array('bar', 'counter'))) {
				$output .= '<div class="sc_skills_count"' . ($UNICAEVENTS_GLOBALS['sc_skills_type']=='bar' && $color ? ' style="background-color:' . esc_attr($bg_color) . '; border-color:' . esc_attr($border_color) . '"' : '') . '>'
						. '</div>'
						. '<div class="sc_skills_total"'
								. ' data-start="'.esc_attr($start).'"'
								. ' data-stop="'.esc_attr($stop).'"'
								. ' data-step="'.esc_attr($step).'"'
								. ' data-max="'.esc_attr($UNICAEVENTS_GLOBALS['sc_skills_max']).'"'
								. ' data-speed="'.esc_attr($speed).'"'
								. ' data-duration="'.esc_attr($animation).'"'
								. ' data-ed="'.esc_attr($ed).'"'
								. ($UNICAEVENTS_GLOBALS['sc_skills_type']=='counter' && $color || $bg_color || $border_color ? 'style="color:'.esc_attr($color).'; border-color:'.esc_attr($border_color).'"' : '') .'>'
								. ($start) . ($ed)
							.'</div>';
			} else if ($UNICAEVENTS_GLOBALS['sc_skills_type']=='pie') {
				if (empty($id)) $id = 'sc_skills_canvas_'.str_replace('.','',mt_rand());
				$output .= '<canvas id="'.esc_attr($id).'"></canvas>'
					. '<div class="sc_skills_total"'
						. ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr($UNICAEVENTS_GLOBALS['sc_skills_max']).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr($UNICAEVENTS_GLOBALS['sc_skills_pie_cutout']).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'">'
						. ($start) . ($ed)
					.'</div>';
			}
			$output .= 
					  ($UNICAEVENTS_GLOBALS['sc_skills_type']=='counter' ? $title_block : '')
					. '</div>'
					. ($UNICAEVENTS_GLOBALS['sc_skills_type']=='bar' && $UNICAEVENTS_GLOBALS['sc_skills_dir']=='vertical' || $UNICAEVENTS_GLOBALS['sc_skills_type'] == 'pie' ? $title_block : '')
					. ($UNICAEVENTS_GLOBALS['sc_skills_columns'] > 0 ? '</div>' : '');
		}
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_skills_item', $atts, $content);
	}
	unicaevents_require_shortcode('trx_skills_item', 'unicaevents_sc_skills_item');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_skills_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_skills_reg_shortcodes');
	function unicaevents_sc_skills_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_skills"] = array(
			"title" => esc_html__("Skills", "unicaevents"),
			"desc" => wp_kses( __("Insert skills diagramm in your page (post)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"max_value" => array(
					"title" => esc_html__("Max value", "unicaevents"),
					"desc" => wp_kses( __("Max value for skills items", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => 100,
					"min" => 1,
					"type" => "spinner"
				),
				"type" => array(
					"title" => esc_html__("Skills type", "unicaevents"),
					"desc" => wp_kses( __("Select type of skills block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "bar",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'bar' => esc_html__('Bar', 'unicaevents'),
						'pie' => esc_html__('Pie chart', 'unicaevents'),
						'counter' => esc_html__('Counter', 'unicaevents'),
						'arc' => esc_html__('Arc', 'unicaevents')
					)
				), 
				"layout" => array(
					"title" => esc_html__("Skills layout", "unicaevents"),
					"desc" => wp_kses( __("Select layout of skills block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('counter','pie','bar')
					),
					"value" => "rows",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'rows' => esc_html__('Rows', 'unicaevents'),
						'columns' => esc_html__('Columns', 'unicaevents')
					)
				),
				"dir" => array(
					"title" => esc_html__("Direction", "unicaevents"),
					"desc" => wp_kses( __("Select direction of skills block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('counter','pie','bar')
					),
					"value" => "horizontal",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['dir']
				), 
				"style" => array(
					"title" => esc_html__("Counters style", "unicaevents"),
					"desc" => wp_kses( __("Select style of skills items (only for type=counter)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('counter')
					),
					"value" => 1,
					"options" => unicaevents_get_list_styles(1, 4),
					"type" => "checklist"
				), 
				// "columns" - autodetect, not set manual
				"color" => array(
					"title" => esc_html__("Skills items color", "unicaevents"),
					"desc" => wp_kses( __("Color for all skills items", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "unicaevents"),
					"desc" => wp_kses( __("Background color for all skills items (only for type=pie)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "",
					"type" => "color"
				),
				"border_color" => array(
					"title" => esc_html__("Border color", "unicaevents"),
					"desc" => wp_kses( __("Border color for all skills items (only for type=pie)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Align skills block", "unicaevents"),
					"desc" => wp_kses( __("Align skills block to left or right side", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['float']
				), 
				"arc_caption" => array(
					"title" => esc_html__("Arc Caption", "unicaevents"),
					"desc" => wp_kses( __("Arc caption - text in the center of the diagram", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('arc')
					),
					"value" => "",
					"type" => "text"
				),
				"pie_compact" => array(
					"title" => esc_html__("Pie compact", "unicaevents"),
					"desc" => wp_kses( __("Show all skills in one diagram or as separate diagrams", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
				),
				"pie_cutout" => array(
					"title" => esc_html__("Pie cutout", "unicaevents"),
					"desc" => wp_kses( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => 0,
					"min" => 0,
					"max" => 99,
					"type" => "spinner"
				),
				"title" => array(
					"title" => esc_html__("Title", "unicaevents"),
					"desc" => wp_kses( __("Title for the block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", "unicaevents"),
					"desc" => wp_kses( __("Subtitle for the block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", "unicaevents"),
					"desc" => wp_kses( __("Short description for the block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", "unicaevents"),
					"desc" => wp_kses( __("Link URL for the button at the bottom of the block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", "unicaevents"),
					"desc" => wp_kses( __("Caption for the button at the bottom of the block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
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
			),
			"children" => array(
				"name" => "trx_skills_item",
				"title" => esc_html__("Skill", "unicaevents"),
				"desc" => wp_kses( __("Skills item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"container" => false,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Title", "unicaevents"),
						"desc" => wp_kses( __("Current skills item title", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Value", "unicaevents"),
						"desc" => wp_kses( __("Current skills level", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => 50,
						"min" => 0,
						"step" => 1,
						"type" => "spinner"
					),
					"color" => array(
						"title" => esc_html__("Color", "unicaevents"),
						"desc" => wp_kses( __("Current skills item color", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", "unicaevents"),
						"desc" => wp_kses( __("Current skills item background color (only for type=pie)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"border_color" => array(
						"title" => esc_html__("Border color", "unicaevents"),
						"desc" => wp_kses( __("Current skills item border color (only for type=pie)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"style" => array(
						"title" => esc_html__("Counter style", "unicaevents"),
						"desc" => wp_kses( __("Select style for the current skills item (only for type=counter)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => 1,
						"options" => unicaevents_get_list_styles(1, 4),
						"type" => "checklist"
					), 
					"icon" => array(
						"title" => esc_html__("Counter icon",  'unicaevents'),
						"desc" => wp_kses( __('Select icon from Fontello icons set, placed above counter (only for type=counter)',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "icons",
						"options" => $UNICAEVENTS_GLOBALS['sc_params']['icons']
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
if ( !function_exists( 'unicaevents_sc_skills_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_skills_reg_shortcodes_vc');
	function unicaevents_sc_skills_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_skills",
			"name" => esc_html__("Skills", "unicaevents"),
			"description" => wp_kses( __("Insert skills diagramm", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_skills',
			"class" => "trx_sc_collection trx_sc_skills",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_skills_item'),
			"params" => array(
				array(
					"param_name" => "max_value",
					"heading" => esc_html__("Max value", "unicaevents"),
					"description" => wp_kses( __("Max value for skills items", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "100",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Skills type", "unicaevents"),
					"description" => wp_kses( __("Select type of skills block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Bar', 'unicaevents') => 'bar',
						esc_html__('Pie chart', 'unicaevents') => 'pie',
						esc_html__('Counter', 'unicaevents') => 'counter',
						esc_html__('Arc', 'unicaevents') => 'arc'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "layout",
					"heading" => esc_html__("Skills layout", "unicaevents"),
					"description" => wp_kses( __("Select layout of skills block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter','bar','pie')
					),
					"class" => "",
					"value" => array(
						esc_html__('Rows', 'unicaevents') => 'rows',
						esc_html__('Columns', 'unicaevents') => 'columns'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "dir",
					"heading" => esc_html__("Direction", "unicaevents"),
					"description" => wp_kses( __("Select direction of skills block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['dir']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counters style", "unicaevents"),
					"description" => wp_kses( __("Select style of skills items (only for type=counter)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(unicaevents_get_list_styles(1, 4)),
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns count", "unicaevents"),
					"description" => wp_kses( __("Skills columns count (required)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "unicaevents"),
					"description" => wp_kses( __("Color for all skills items", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "unicaevents"),
					"description" => wp_kses( __("Background color for all skills items (only for type=pie)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", "unicaevents"),
					"description" => wp_kses( __("Border color for all skills items (only for type=pie)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "unicaevents"),
					"description" => wp_kses( __("Align skills block to left or right side", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['float']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "arc_caption",
					"heading" => esc_html__("Arc caption", "unicaevents"),
					"description" => wp_kses( __("Arc caption - text in the center of the diagram", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('arc')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "pie_compact",
					"heading" => esc_html__("Pie compact", "unicaevents"),
					"description" => wp_kses( __("Show all skills in one diagram or as separate diagrams", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => array(esc_html__('Show separate skills', 'unicaevents') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pie_cutout",
					"heading" => esc_html__("Pie cutout", "unicaevents"),
					"description" => wp_kses( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "unicaevents"),
					"description" => wp_kses( __("Title for the block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", "unicaevents"),
					"description" => wp_kses( __("Subtitle for the block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", "unicaevents"),
					"description" => wp_kses( __("Description for the block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", "unicaevents"),
					"description" => wp_kses( __("Link URL for the button at the bottom of the block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'unicaevents'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", "unicaevents"),
					"description" => wp_kses( __("Caption for the button at the bottom of the block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'unicaevents'),
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
		
		
		vc_map( array(
			"base" => "trx_skills_item",
			"name" => esc_html__("Skill", "unicaevents"),
			"description" => wp_kses( __("Skills item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			'icon' => 'icon_trx_skills_item',
			"class" => "trx_sc_single trx_sc_skills_item",
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_skills'),
			"as_parent" => array('except' => 'trx_skills'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "unicaevents"),
					"description" => wp_kses( __("Title for the current skills item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", "unicaevents"),
					"description" => wp_kses( __("Value for the current skills item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "unicaevents"),
					"description" => wp_kses( __("Color for current skills item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "unicaevents"),
					"description" => wp_kses( __("Background color for current skills item (only for type=pie)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", "unicaevents"),
					"description" => wp_kses( __("Border color for current skills item (only for type=pie)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counter style", "unicaevents"),
					"description" => wp_kses( __("Select style for the current skills item (only for type=counter)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(unicaevents_get_list_styles(1, 4)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Counter icon", "unicaevents"),
					"description" => wp_kses( __("Select icon from Fontello icons set, placed before counter (only for type=counter)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $UNICAEVENTS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['css']
			)
		) );
		
		class WPBakeryShortCode_Trx_Skills extends UNICAEVENTS_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Skills_Item extends UNICAEVENTS_VC_ShortCodeSingle {}
	}
}
?>
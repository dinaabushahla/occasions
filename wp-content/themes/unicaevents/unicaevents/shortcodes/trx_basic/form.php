<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_form_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_form_theme_setup' );
	function unicaevents_sc_form_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_form_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_form_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_form id="unique_id" title="Contact Form" description="Mauris aliquam habitasse magna."]
*/

if (!function_exists('unicaevents_sc_form')) {	
	function unicaevents_sc_form($atts, $content = null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "form_custom",
			"action" => "",
			"align" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_form_".str_replace('.', '', mt_rand());
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= unicaevents_get_css_dimensions_from_values($width);
	
		unicaevents_enqueue_messages();	// Load core messages
	
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_form_id'] = $id;
		$UNICAEVENTS_GLOBALS['sc_form_counter'] = 0;
	
		if ($style == 'form_custom')
			$content = do_shortcode($content);
	
		$output = '<div ' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
					. ' class="sc_form_wrap'
					. ($scheme && !unicaevents_param_is_off($scheme) && !unicaevents_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">'
			.'<div ' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_form'
					. ' sc_form_style_'.($style) 
					. (!empty($align) && !unicaevents_param_is_off($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
				. '>'
					. (!empty($subtitle) 
						? '<h6 class="sc_form_subtitle sc_item_subtitle">' . trim(unicaevents_strmacros($subtitle)) . '</h6>' 
						: '')
					. (!empty($title) 
						? '<h2 class="sc_form_title sc_item_title">' . trim(unicaevents_strmacros($title)) . '</h2>' 
						: '')
					. (!empty($description) 
						? '<div class="sc_form_descr sc_item_descr">' . trim(unicaevents_strmacros($description)) . ($style == 1 ? do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]') : '') . '</div>' 
						: '');
		
		$output .= unicaevents_show_post_layout(array(
												'layout' => $style,
												'id' => $id,
												'action' => $action,
												'content' => $content,
												'show' => false
												), false);

		$output .= '</div>'
				. '</div>';
	
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_form', $atts, $content);
	}
	unicaevents_require_shortcode("trx_form", "unicaevents_sc_form");
}

if (!function_exists('unicaevents_sc_form_item')) {	
	function unicaevents_sc_form_item($atts, $content=null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts( array(
			// Individual params
			"type" => "text",
			"name" => "",
			"value" => "",
			"options" => "",
			"align" => "",
			"label" => "",
			"label_position" => "top",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"size" => "",
			"placeholder" => ""
			), $atts)));

		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_form_counter']++;
		(!empty($size) ? $css .= ' width: ' . esc_attr($size) . ';' : '');
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		if (empty($id)) $id = ($UNICAEVENTS_GLOBALS['sc_form_id']).'_'.($UNICAEVENTS_GLOBALS['sc_form_counter']);
	
		$label = $type!='button' && $type!='submit' && $label ? '<label for="' . esc_attr($id) . '">' . esc_attr($label) . '</label>' : $label;
	
		// Open field container
		$output = '<div class="sc_form_item sc_form_item_'.esc_attr($type)
						.' sc_form_'.($type == 'textarea' ? 'message' : ($type == 'button' || $type == 'submit' ? 'button' : 'field'))
						.' label_'.esc_attr($label_position)
						.($class ? ' '.esc_attr($class) : '')
						.($align && $align!='none' ? ' align'.esc_attr($align) : '')
					.'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
					. '>';
		
		// Label top or left
		if ($type!='button' && $type!='submit' && ($label_position=='top' || $label_position=='left'))
			$output .= $label;

		// Field output
		if ($type == 'textarea')

			$output .= '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">' . esc_attr($value) . '</textarea>';

		else if ($type=='button' || $type=='submit')

			$output .= '<button id="' . esc_attr($id) . '">'.($label ? $label : $value).'</button>';

		else if ($type=='radio' || $type=='checkbox') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$i = 0;
					foreach ($options as $v) {
						$i++;
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<div class="sc_form_element">'
										. '<input type="'.esc_attr($type) . '"'
											. ' id="' . esc_attr($id.($i>1 ? '_'.$i : '')) . '"'
											. ' name="' . esc_attr($name ? $name : $id) . (count($options) > 1 && $type=='checkbox' ? '[]' : '') . '"'
											. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
											. (in_array($parts[0], explode(',', $value)) ? ' checked="checked"' : '') 
										. '>'
										. '<label for="' . esc_attr($id.($i>1 ? '_'.$i : '')) . '">' . trim(chop($parts[1])) . '</label>'
									. '</div>';
					}
				}
			}

		} else if ($type=='select') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$output .= '<div class="sc_form_select_container">'
						. '<select id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">';
					foreach ($options as $v) {
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<option'
										. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
										. (in_array($parts[0], explode(',', $value)) ? ' selected="selected"' : '') 
									. '>'
									. trim(chop($parts[1]))
									. '</option>';
					}
					$output .= '</select>'
							. '</div>';
				}
			}

		} else if ($type=='date') {
			unicaevents_enqueue_script( 'jquery-picker', unicaevents_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			unicaevents_enqueue_script( 'jquery-picker-date', unicaevents_get_file_url('/js/picker/picker.date.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_date_wrap icon-calendar-light">'
						. '<input placeholder="' . esc_attr__('Date', 'unicaevents') . '" id="' . esc_attr($id) . '" class="js__datepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';

		} else if ($type=='time') {
			unicaevents_enqueue_script( 'jquery-picker', unicaevents_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			unicaevents_enqueue_script( 'jquery-picker-time', unicaevents_get_file_url('/js/picker/picker.time.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_time_wrap icon-clock-empty">'
						. '<input placeholder="' . esc_attr__('Time', 'unicaevents') . '" id="' . esc_attr($id) . '" class="js__timepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';
	
		} else

		$output .= '<input type="'.esc_attr($type ? $type : 'text').'" id="' . esc_attr($id) . '"'.(!empty($placeholder) ? 'placeholder="'.esc_attr($placeholder).'"' : '') .' name="' . esc_attr($name ? $name : $id) . '" value="' . esc_attr($value) . '">';

		// Label bottom
		if ($type!='button' && $type!='submit' && $label_position=='bottom')
			$output .= $label;
		
		// Close field container
		$output .= '</div>';
	
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_form_item', $atts, $content);
	}
	unicaevents_require_shortcode('trx_form_item', 'unicaevents_sc_form_item');
}

// AJAX Callback: Send contact form data
if ( !function_exists( 'unicaevents_sc_form_send' ) ) {
	function unicaevents_sc_form_send() {
		global $_REQUEST, $UNICAEVENTS_GLOBALS;
	
		if ( !wp_verify_nonce( $_REQUEST['nonce'], $UNICAEVENTS_GLOBALS['ajax_url'] ) )
			die();
	
		$response = array('error'=>'');
		if (!($contact_email = unicaevents_get_theme_option('contact_email')) && !($contact_email = unicaevents_get_theme_option('admin_email'))) 
			$response['error'] = esc_html__('Unknown admin email!', 'unicaevents');
		else {
			$type = unicaevents_substr($_REQUEST['type'], 0, 7);
			parse_str($_POST['data'], $post_data);

			if (in_array($type, array('form_1', 'form_2'))) {
				$user_name	= unicaevents_strshort($post_data['username'],	100);
				$user_phone	= unicaevents_strshort($post_data['phone'],	100);
				$user_msg	= unicaevents_strshort($post_data['message'],	unicaevents_get_theme_option('message_maxlength_contacts'));
		
				$subj = sprintf(esc_html__('Site %s - Contact form message from %s', 'unicaevents'), get_bloginfo('site_name'), $user_name);
				$msg = "\n".esc_html__('Name:', 'unicaevents')   .' '.esc_html($user_name)
					.  "\n".esc_html__('Phone:', 'unicaevents') .' '.esc_html($user_phone)
					.  "\n".esc_html__('Message:', 'unicaevents').' '.esc_html($user_msg);

			} else {

				$subj = sprintf(esc_html__('Site %s - Custom form data', 'unicaevents'), get_bloginfo('site_name'));
				$msg = '';
				if (is_array($post_data) && count($post_data) > 0) {
					foreach ($post_data as $k=>$v)
						$msg .= "\n{$k}: $v";
				}
			}

			$msg .= "\n\n............. " . get_bloginfo('site_name') . " (" . esc(home_url('/')) . ") ............";

			$mail = unicaevents_get_theme_option('mail_function');
			if (!@$mail($contact_email, $subj, apply_filters('unicaevents_filter_form_send_message', $msg))) {
				$response['error'] = esc_html__('Error send message!', 'unicaevents');
			}
		
			echo json_encode($response);
			die();
		}
	}
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_form_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_form_reg_shortcodes');
	function unicaevents_sc_form_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_form"] = array(
			"title" => esc_html__("Form", "unicaevents"),
			"desc" => wp_kses( __("Insert form with specified style or with set of custom fields", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array(
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
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Style", "unicaevents"),
					"desc" => wp_kses( __("Select style of the form (if 'style' is not equal 'custom' - all tabs 'Field NN' are ignored!", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => 'form_custom',
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['forms'],
					"type" => "checklist"
				), 
				"scheme" => array(
					"title" => esc_html__("Color scheme", "unicaevents"),
					"desc" => wp_kses( __("Select color scheme for this block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['schemes']
				),
				"action" => array(
					"title" => esc_html__("Action", "unicaevents"),
					"desc" => wp_kses( __("Contact form action (URL to handle form data). If empty - use internal action", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", "unicaevents"),
					"desc" => wp_kses( __("Select form alignment", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['align']
				),
				"width" => unicaevents_shortcodes_width(),
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
				"name" => "trx_form_item",
				"title" => esc_html__("Field", "unicaevents"),
				"desc" => wp_kses( __("Custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"container" => false,
				"params" => array(
					"type" => array(
						"title" => esc_html__("Type", "unicaevents"),
						"desc" => wp_kses( __("Type of the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "text",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => $UNICAEVENTS_GLOBALS['sc_params']['field_types']
					),
					"size" => array(
						"title" => esc_html__("Size", "unicaevents"),
						"desc" => wp_kses( __("Size of the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"name" => array(
						"title" => esc_html__("Name", "unicaevents"),
						"desc" => wp_kses( __("Name of the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"placeholder" => array(
						"title" => esc_html__("Placeholder", "unicaevents"),
						"desc" => wp_kses( __("Placeholder for the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Default value", "unicaevents"),
						"desc" => wp_kses( __("Default value of the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"options" => array(
						"title" => esc_html__("Options", "unicaevents"),
						"desc" => wp_kses( __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"dependency" => array(
							'type' => array('radio', 'checkbox', 'select')
						),
						"value" => "",
						"type" => "text"
					),
					"label" => array(
						"title" => esc_html__("Label", "unicaevents"),
						"desc" => wp_kses( __("Label for the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"label_position" => array(
						"title" => esc_html__("Label position", "unicaevents"),
						"desc" => wp_kses( __("Label position relative to the field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"value" => "top",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => $UNICAEVENTS_GLOBALS['sc_params']['label_positions']
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
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_form_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_form_reg_shortcodes_vc');
	function unicaevents_sc_form_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_form",
			"name" => esc_html__("Form", "unicaevents"),
			"description" => wp_kses( __("Insert form with specefied style of with set of custom fields", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_form',
			"class" => "trx_sc_collection trx_sc_form",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('except' => 'trx_form'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "unicaevents"),
					"description" => wp_kses( __("Select style of the form (if 'style' is not equal 'custom' - all tabs 'Field NN' are ignored!", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"std" => "form_custom",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['forms']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "unicaevents"),
					"description" => wp_kses( __("Select color scheme for this block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['schemes']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "action",
					"heading" => esc_html__("Action", "unicaevents"),
					"description" => wp_kses( __("Contact form action (URL to handle form data). If empty - use internal action", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "unicaevents"),
					"description" => wp_kses( __("Select form alignment", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
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
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['animation'],
				$UNICAEVENTS_GLOBALS['vc_params']['css'],
				unicaevents_vc_width(),
				$UNICAEVENTS_GLOBALS['vc_params']['margin_top'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_bottom'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_left'],
				$UNICAEVENTS_GLOBALS['vc_params']['margin_right']
			)
		) );
		
		
		vc_map( array(
			"base" => "trx_form_item",
			"name" => esc_html__("Form item (custom field)", "unicaevents"),
			"description" => wp_kses( __("Custom field for the contact form", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"class" => "trx_sc_item trx_sc_form_item",
			'icon' => 'icon_trx_form_item',
			//"allowed_container_element" => 'vc_row',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_form,trx_column_item'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", "unicaevents"),
					"description" => wp_kses( __("Select type of the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['field_types']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "name",
					"heading" => esc_html__("Name", "unicaevents"),
					"description" => wp_kses( __("Name of the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "placeholder",
					"heading" => esc_html__("Placeholder", "unicaevents"),
					"description" => wp_kses( __("Placeholder for the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Size", "unicaevents"),
					"description" => wp_kses( __("Size of the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Default value", "unicaevents"),
					"description" => wp_kses( __("Default value of the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "options",
					"heading" => esc_html__("Options", "unicaevents"),
					"description" => wp_kses( __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('radio','checkbox','select')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "label",
					"heading" => esc_html__("Label", "unicaevents"),
					"description" => wp_kses( __("Label for the custom field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "label_position",
					"heading" => esc_html__("Label position", "unicaevents"),
					"description" => wp_kses( __("Label position relative to the field", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['label_positions']),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Form extends UNICAEVENTS_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Form_Item extends UNICAEVENTS_VC_ShortCodeItem {}
	}
}
?>
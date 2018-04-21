<?php
/* UnicaEvents Donations support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('unicaevents_trx_donations_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_trx_donations_theme_setup', 1 );
	function unicaevents_trx_donations_theme_setup() {

		// Add shortcode in the shortcodes list
		if (unicaevents_exists_trx_donations()) {
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('unicaevents_filter_get_blog_type',			'unicaevents_trx_donations_get_blog_type', 9, 2);
			add_filter('unicaevents_filter_get_blog_title',		'unicaevents_trx_donations_get_blog_title', 9, 2);
			add_filter('unicaevents_filter_get_current_taxonomy',	'unicaevents_trx_donations_get_current_taxonomy', 9, 2);
			add_filter('unicaevents_filter_is_taxonomy',			'unicaevents_trx_donations_is_taxonomy', 9, 2);
			add_filter('unicaevents_filter_get_stream_page_title',	'unicaevents_trx_donations_get_stream_page_title', 9, 2);
			add_filter('unicaevents_filter_get_stream_page_link',	'unicaevents_trx_donations_get_stream_page_link', 9, 2);
			add_filter('unicaevents_filter_get_stream_page_id',	'unicaevents_trx_donations_get_stream_page_id', 9, 2);
			add_filter('unicaevents_filter_query_add_filters',		'unicaevents_trx_donations_query_add_filters', 9, 2);
			add_filter('unicaevents_filter_detect_inheritance_key','unicaevents_trx_donations_detect_inheritance_key', 9, 1);
			add_filter('unicaevents_filter_list_post_types',		'unicaevents_trx_donations_list_post_types');
			// Add shortcodes in the list
			add_action('unicaevents_action_shortcodes_list',		'unicaevents_trx_donations_reg_shortcodes');
			if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
				add_action('unicaevents_action_shortcodes_list_vc','unicaevents_trx_donations_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'unicaevents_filter_importer_options',				'unicaevents_trx_donations_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_trx_donations_importer_required_plugins' );
			add_filter( 'unicaevents_filter_required_plugins',				'unicaevents_trx_donations_required_plugins' );
		}
	}
}

if ( !function_exists( 'unicaevents_trx_donations_settings_theme_setup2' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_trx_donations_settings_theme_setup2', 3 );
	function unicaevents_trx_donations_settings_theme_setup2() {
		// Add Donations post type and taxonomy into theme inheritance list
		if (unicaevents_exists_trx_donations()) {
			unicaevents_add_theme_inheritance( array('donations' => array(
				'stream_template' => 'blog-donations',
				'single_template' => 'single-donation',
				'taxonomy' => array(UNICAEVENTS_Donations::TAXONOMY),
				'taxonomy_tags' => array(),
				'post_type' => array(UNICAEVENTS_Donations::POST_TYPE),
				'override' => 'page'
				) )
			);
		}
	}
}

// Check if UnicaEvents Donations installed and activated
if ( !function_exists( 'unicaevents_exists_trx_donations' ) ) {
	function unicaevents_exists_trx_donations() {
		return class_exists('UNICAEVENTS_Donations');
	}
}


// Return true, if current page is donations page
if ( !function_exists( 'unicaevents_is_trx_donations_page' ) ) {
	function unicaevents_is_trx_donations_page() {
		$is = false;
		if (unicaevents_exists_trx_donations()) {
			global $UNICAEVENTS_GLOBALS;
			$is = in_array($UNICAEVENTS_GLOBALS['page_template'], array('blog-donations', 'single-donation'));
			if (!$is) {
				if (!empty($UNICAEVENTS_GLOBALS['pre_query']))
					$is = ($UNICAEVENTS_GLOBALS['pre_query']->is_single() && $UNICAEVENTS_GLOBALS['pre_query']->get('post_type') == UNICAEVENTS_Donations::POST_TYPE) 
							|| $UNICAEVENTS_GLOBALS['pre_query']->is_post_type_archive(UNICAEVENTS_Donations::POST_TYPE) 
							|| $UNICAEVENTS_GLOBALS['pre_query']->is_tax(UNICAEVENTS_Donations::TAXONOMY);
				else
					$is = (is_single() && get_query_var('post_type') == UNICAEVENTS_Donations::POST_TYPE) 
							|| is_post_type_archive(UNICAEVENTS_Donations::POST_TYPE) 
							|| is_tax(UNICAEVENTS_Donations::TAXONOMY);
			}
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'unicaevents_trx_donations_detect_inheritance_key' ) ) {
	//add_filter('unicaevents_filter_detect_inheritance_key',	'unicaevents_trx_donations_detect_inheritance_key', 9, 1);
	function unicaevents_trx_donations_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return unicaevents_is_trx_donations_page() ? 'donations' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'unicaevents_trx_donations_get_blog_type' ) ) {
	//add_filter('unicaevents_filter_get_blog_type',	'unicaevents_trx_donations_get_blog_type', 9, 2);
	function unicaevents_trx_donations_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax(UNICAEVENTS_Donations::TAXONOMY) || is_tax(UNICAEVENTS_Donations::TAXONOMY))
			$page = 'donations_category';
		else if ($query && $query->get('post_type')==UNICAEVENTS_Donations::POST_TYPE || get_query_var('post_type')==UNICAEVENTS_Donations::POST_TYPE)
			$page = $query && $query->is_single() || is_single() ? 'donations_item' : 'donations';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'unicaevents_trx_donations_get_blog_title' ) ) {
	//add_filter('unicaevents_filter_get_blog_title',	'unicaevents_trx_donations_get_blog_title', 9, 2);
	function unicaevents_trx_donations_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( unicaevents_strpos($page, 'donations')!==false ) {
			if ( $page == 'donations_category' ) {
				$term = get_term_by( 'slug', get_query_var( UNICAEVENTS_Donations::TAXONOMY ), UNICAEVENTS_Donations::TAXONOMY, OBJECT);
				$title = $term->name;
			} else if ( $page == 'donations_item' ) {
				$title = unicaevents_get_post_title();
			} else {
				$title = esc_html__('All donations', 'unicaevents');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'unicaevents_trx_donations_get_stream_page_title' ) ) {
	//add_filter('unicaevents_filter_get_stream_page_title',	'unicaevents_trx_donations_get_stream_page_title', 9, 2);
	function unicaevents_trx_donations_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (unicaevents_strpos($page, 'donations')!==false) {
			if (($page_id = unicaevents_trx_donations_get_stream_page_id(0, $page=='donations' ? 'blog-donations' : $page)) > 0)
				$title = unicaevents_get_post_title($page_id);
			else
				$title = esc_html__('All donations', 'unicaevents');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'unicaevents_trx_donations_get_stream_page_id' ) ) {
	//add_filter('unicaevents_filter_get_stream_page_id',	'unicaevents_trx_donations_get_stream_page_id', 9, 2);
	function unicaevents_trx_donations_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (unicaevents_strpos($page, 'donations')!==false) $id = unicaevents_get_template_page_id('blog-donations');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'unicaevents_trx_donations_get_stream_page_link' ) ) {
	//add_filter('unicaevents_filter_get_stream_page_link',	'unicaevents_trx_donations_get_stream_page_link', 9, 2);
	function unicaevents_trx_donations_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (unicaevents_strpos($page, 'donations')!==false) {
			$id = unicaevents_get_template_page_id('blog-donations');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'unicaevents_trx_donations_get_current_taxonomy' ) ) {
	//add_filter('unicaevents_filter_get_current_taxonomy',	'unicaevents_trx_donations_get_current_taxonomy', 9, 2);
	function unicaevents_trx_donations_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( unicaevents_strpos($page, 'donations')!==false ) {
			$tax = UNICAEVENTS_Donations::TAXONOMY;
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'unicaevents_trx_donations_is_taxonomy' ) ) {
	//add_filter('unicaevents_filter_is_taxonomy',	'unicaevents_trx_donations_is_taxonomy', 9, 2);
	function unicaevents_trx_donations_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get(UNICAEVENTS_Donations::TAXONOMY)!='' || is_tax(UNICAEVENTS_Donations::TAXONOMY) ? UNICAEVENTS_Donations::TAXONOMY : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'unicaevents_trx_donations_query_add_filters' ) ) {
	//add_filter('unicaevents_filter_query_add_filters',	'unicaevents_trx_donations_query_add_filters', 9, 2);
	function unicaevents_trx_donations_query_add_filters($args, $filter) {
		if ($filter == 'donations') {
			$args['post_type'] = UNICAEVENTS_Donations::POST_TYPE;
		}
		return $args;
	}
}

// Add custom post type to the list
if ( !function_exists( 'unicaevents_trx_donations_list_post_types' ) ) {
	//add_filter('unicaevents_filter_list_post_types',		'unicaevents_trx_donations_list_post_types');
	function unicaevents_trx_donations_list_post_types($list) {
		$list[UNICAEVENTS_Donations::POST_TYPE] = esc_html__('Donations', 'unicaevents');
		return $list;
	}
}


// Add shortcode in the shortcodes list
if (!function_exists('unicaevents_trx_donations_reg_shortcodes')) {
	//add_filter('unicaevents_action_shortcodes_list',	'unicaevents_trx_donations_reg_shortcodes');
	function unicaevents_trx_donations_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['shortcodes'])) {

			$plugin = UNICAEVENTS_Donations::get_instance();
			$donations_groups = unicaevents_get_list_terms(false, UNICAEVENTS_Donations::TAXONOMY);

			unicaevents_array_insert_before($UNICAEVENTS_GLOBALS['shortcodes'], 'trx_dropcaps', array(

				// UnicaEvents Donations form
				"trx_donations_form" => array(
					"title" => esc_html__("Donations form", "unicaevents"),
					"desc" => esc_html__("Insert UnicaEvents Donations form", "unicaevents"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "unicaevents"),
							"desc" => esc_html__("Title for the donations form", "unicaevents"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "unicaevents"),
							"desc" => esc_html__("Subtitle for the donations form", "unicaevents"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "unicaevents"),
							"desc" => esc_html__("Short description for the donations form", "unicaevents"),
							"value" => "",
							"type" => "textarea"
						),
						"align" => array(
							"title" => esc_html__("Alignment", "unicaevents"),
							"desc" => esc_html__("Alignment of the donations form", "unicaevents"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['align']
						),
						"account" => array(
							"title" => esc_html__("PayPal account", "unicaevents"),
							"desc" => esc_html__("PayPal account's e-mail. If empty - used from UnicaEvents Donations settings", "unicaevents"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"sandbox" => array(
							"title" => esc_html__("Sandbox mode", "unicaevents"),
							"desc" => esc_html__("Use PayPal sandbox to test payments", "unicaevents"),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
						),
						"amount" => array(
							"title" => esc_html__("Default amount", "unicaevents"),
							"desc" => esc_html__("Specify amount, initially selected in the form", "unicaevents"),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"value" => 5,
							"min" => 1,
							"step" => 5,
							"type" => "spinner"
						),
						"currency" => array(
							"title" => esc_html__("Currency", "unicaevents"),
							"desc" => esc_html__("Select payment's currency", "unicaevents"),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => unicaevents_array_merge(array(0 => esc_html__('- Select currency -', 'unicaevents')), $plugin->currency_codes)
						),
						"width" => unicaevents_shortcodes_width(),
						"top" => $UNICAEVENTS_GLOBALS['sc_params']['top'],
						"bottom" => $UNICAEVENTS_GLOBALS['sc_params']['bottom'],
						"left" => $UNICAEVENTS_GLOBALS['sc_params']['left'],
						"right" => $UNICAEVENTS_GLOBALS['sc_params']['right'],
						"id" => $UNICAEVENTS_GLOBALS['sc_params']['id'],
						"class" => $UNICAEVENTS_GLOBALS['sc_params']['class'],
						"css" => $UNICAEVENTS_GLOBALS['sc_params']['css']
					)
				),
				
				
				// UnicaEvents Donations form
				"trx_donations_list" => array(
					"title" => esc_html__("Donations list", "unicaevents"),
					"desc" => esc_html__("Insert UnicaEvents Doantions list", "unicaevents"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "unicaevents"),
							"desc" => esc_html__("Title for the donations list", "unicaevents"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "unicaevents"),
							"desc" => esc_html__("Subtitle for the donations list", "unicaevents"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "unicaevents"),
							"desc" => esc_html__("Short description for the donations list", "unicaevents"),
							"value" => "",
							"type" => "textarea"
						),
						"link" => array(
							"title" => esc_html__("Button URL", "unicaevents"),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", "unicaevents"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "unicaevents"),
							"desc" => esc_html__("Caption for the button at the bottom of the block", "unicaevents"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("List style", "unicaevents"),
							"desc" => esc_html__("Select style to display donations", "unicaevents"),
							"value" => "excerpt",
							"type" => "select",
							"options" => array(
								'excerpt' => esc_html__('Excerpt', 'unicaevents')
							)
						),
						"readmore" => array(
							"title" => esc_html__("Read more text", "unicaevents"),
							"desc" => esc_html__("Text of the 'Read more' link", "unicaevents"),
							"value" => esc_html__('Read more', 'unicaevents'),
							"type" => "text"
						),
						"cat" => array(
							"title" => esc_html__("Categories", "unicaevents"),
							"desc" => esc_html__("Select categories (groups) to show donations. If empty - select donations from any category (group) or from IDs list", "unicaevents"),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => unicaevents_array_merge(array(0 => esc_html__('- Select category -', 'unicaevents')), $donations_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of donations", "unicaevents"),
							"desc" => esc_html__("How many donations will be displayed? If used IDs - this parameter ignored.", "unicaevents"),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "unicaevents"),
							"desc" => esc_html__("How many columns use to show donations list", "unicaevents"),
							"value" => 3,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "unicaevents"),
							"desc" => esc_html__("Skip posts before select next part.", "unicaevents"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Donadions order by", "unicaevents"),
							"desc" => esc_html__("Select desired sorting method", "unicaevents"),
							"value" => "date",
							"type" => "select",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Donations order", "unicaevents"),
							"desc" => esc_html__("Select donations order", "unicaevents"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Donations IDs list", "unicaevents"),
							"desc" => esc_html__("Comma separated list of donations ID. If set - parameters above are ignored!", "unicaevents"),
							"value" => "",
							"type" => "text"
						),
						"top" => $UNICAEVENTS_GLOBALS['sc_params']['top'],
						"bottom" => $UNICAEVENTS_GLOBALS['sc_params']['bottom'],
						"id" => $UNICAEVENTS_GLOBALS['sc_params']['id'],
						"class" => $UNICAEVENTS_GLOBALS['sc_params']['class'],
						"css" => $UNICAEVENTS_GLOBALS['sc_params']['css']
					)
				)

			));
		}
	}
}


// Add shortcode in the VC shortcodes list
if (!function_exists('unicaevents_trx_donations_reg_shortcodes_vc')) {
	//add_filter('unicaevents_action_shortcodes_list_vc',	'unicaevents_trx_donations_reg_shortcodes_vc');
	function unicaevents_trx_donations_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;

		$plugin = UNICAEVENTS_Donations::get_instance();
		$donations_groups = unicaevents_get_list_terms(false, UNICAEVENTS_Donations::TAXONOMY);

		// UnicaEvents Donations form
		vc_map( array(
				"base" => "trx_donations_form",
				"name" => esc_html__("Donations form", "unicaevents"),
				"description" => esc_html__("Insert UnicaEvents Donations form", "unicaevents"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_donations_form',
				"class" => "trx_sc_single trx_sc_donations_form",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "unicaevents"),
						"description" => esc_html__("Title for the donations form", "unicaevents"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "unicaevents"),
						"description" => esc_html__("Subtitle for the donations form", "unicaevents"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "unicaevents"),
						"description" => esc_html__("Description for the donations form", "unicaevents"),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "unicaevents"),
						"description" => esc_html__("Alignment of the donations form", "unicaevents"),
						"class" => "",
						"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "account",
						"heading" => esc_html__("PayPal account", "unicaevents"),
						"description" => esc_html__("PayPal account's e-mail. If empty - used from UnicaEvents Donations settings", "unicaevents"),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'unicaevents'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "sandbox",
						"heading" => esc_html__("Sandbox mode", "unicaevents"),
						"description" => esc_html__("Use PayPal sandbox to test payments", "unicaevents"),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'unicaevents'),
						'dependency' => array(
							'element' => 'account',
							'not_empty' => true
						),
						"class" => "",
						"value" => array("Sandbox mode" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "amount",
						"heading" => esc_html__("Default amount", "unicaevents"),
						"description" => esc_html__("Specify amount, initially selected in the form", "unicaevents"),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'unicaevents'),
						"class" => "",
						"value" => "5",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency", "unicaevents"),
						"description" => esc_html__("Select payment's currency", "unicaevents"),
						"class" => "",
						"value" => array_flip(unicaevents_array_merge(array(0 => esc_html__('- Select currency -', 'unicaevents')), $plugin->currency_codes)),
						"type" => "dropdown"
					),
					$UNICAEVENTS_GLOBALS['vc_params']['id'],
					$UNICAEVENTS_GLOBALS['vc_params']['class'],
					$UNICAEVENTS_GLOBALS['vc_params']['css'],
					unicaevents_vc_width(),
					$UNICAEVENTS_GLOBALS['vc_params']['margin_top'],
					$UNICAEVENTS_GLOBALS['vc_params']['margin_bottom'],
					$UNICAEVENTS_GLOBALS['vc_params']['margin_left'],
					$UNICAEVENTS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
		class WPBakeryShortCode_Trx_Donations_Form extends UNICAEVENTS_VC_ShortCodeSingle {}



		// UnicaEvents Donations list
		vc_map( array(
				"base" => "trx_donations_list",
				"name" => esc_html__("Donations list", "unicaevents"),
				"description" => esc_html__("Insert UnicaEvents Donations list", "unicaevents"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_donations_list',
				"class" => "trx_sc_single trx_sc_donations_list",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("List style", "unicaevents"),
						"description" => esc_html__("Select style to display donations", "unicaevents"),
						"class" => "",
						"value" => array(
							esc_html__('Excerpt', 'unicaevents') => 'excerpt'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "unicaevents"),
						"description" => esc_html__("Title for the donations form", "unicaevents"),
						"group" => esc_html__('Captions', 'unicaevents'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "unicaevents"),
						"description" => esc_html__("Subtitle for the donations form", "unicaevents"),
						"group" => esc_html__('Captions', 'unicaevents'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "unicaevents"),
						"description" => esc_html__("Description for the donations form", "unicaevents"),
						"group" => esc_html__('Captions', 'unicaevents'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "unicaevents"),
						"description" => esc_html__("Link URL for the button at the bottom of the block", "unicaevents"),
						"group" => esc_html__('Captions', 'unicaevents'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "unicaevents"),
						"description" => esc_html__("Caption for the button at the bottom of the block", "unicaevents"),
						"group" => esc_html__('Captions', 'unicaevents'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more text", "unicaevents"),
						"description" => esc_html__("Text of the 'Read more' link", "unicaevents"),
						"group" => esc_html__('Captions', 'unicaevents'),
						"class" => "",
						"value" => esc_html__('Read more', 'unicaevents'),
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "unicaevents"),
						"description" => esc_html__("Select category to show donations. If empty - select donations from any category (group) or from IDs list", "unicaevents"),
						"group" => esc_html__('Query', 'unicaevents'),
						"class" => "",
						"value" => array_flip(unicaevents_array_merge(array(0 => esc_html__('- Select category -', 'unicaevents')), $donations_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "unicaevents"),
						"description" => esc_html__("How many columns use to show donations", "unicaevents"),
						"group" => esc_html__('Query', 'unicaevents'),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "unicaevents"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "unicaevents"),
						"group" => esc_html__('Query', 'unicaevents'),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "unicaevents"),
						"description" => esc_html__("Skip posts before select next part.", "unicaevents"),
						"group" => esc_html__('Query', 'unicaevents'),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", "unicaevents"),
						"description" => esc_html__("Select desired posts sorting method", "unicaevents"),
						"group" => esc_html__('Query', 'unicaevents'),
						"class" => "",
						"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "unicaevents"),
						"description" => esc_html__("Select desired posts order", "unicaevents"),
						"group" => esc_html__('Query', 'unicaevents'),
						"class" => "",
						"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("client's IDs list", "unicaevents"),
						"description" => esc_html__("Comma separated list of donation's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "unicaevents"),
						"group" => esc_html__('Query', 'unicaevents'),
						'dependency' => array(
							'element' => 'cats',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),

					$UNICAEVENTS_GLOBALS['vc_params']['id'],
					$UNICAEVENTS_GLOBALS['vc_params']['class'],
					$UNICAEVENTS_GLOBALS['vc_params']['css'],
					$UNICAEVENTS_GLOBALS['vc_params']['margin_top'],
					$UNICAEVENTS_GLOBALS['vc_params']['margin_bottom']
				)
			) );
			
		class WPBakeryShortCode_Trx_Donations_List extends UNICAEVENTS_VC_ShortCodeSingle {}

	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'unicaevents_trx_donations_required_plugins' ) ) {
	//add_filter('unicaevents_filter_required_plugins',	'unicaevents_trx_donations_required_plugins');
	function unicaevents_trx_donations_required_plugins($list=array()) {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('trx_donations', $UNICAEVENTS_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'UnicaEvents Donations',
					'slug' 		=> 'trx_donations',
					'source'	=> unicaevents_get_file_dir('plugins/install/trx_donations.zip'),
					'required' 	=> false
					);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'unicaevents_trx_donations_importer_required_plugins' ) ) {
	//add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_trx_donations_importer_required_plugins' );
	function unicaevents_trx_donations_importer_required_plugins($not_installed='') {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('trx_donations', $UNICAEVENTS_GLOBALS['required_plugins']) && !unicaevents_exists_trx_donations() )
			$not_installed .= '<br>UnicaEvents Donations';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'unicaevents_trx_donations_importer_set_options' ) ) {
	//add_filter( 'unicaevents_filter_importer_options',	'unicaevents_trx_donations_importer_set_options' );
	function unicaevents_trx_donations_importer_set_options($options=array()) {
		if (is_array($options)) {
			$options['additional_options'][] = 'trx_donations_options';		// Add slugs to export options for this plugin

		}
		return $options;
	}
}
?>
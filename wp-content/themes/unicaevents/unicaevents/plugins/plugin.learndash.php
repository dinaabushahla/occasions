<?php
/* LearnDash LMS support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('unicaevents_learndash_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_learndash_theme_setup', 1 );
	function unicaevents_learndash_theme_setup() {

		// Add shortcode in the shortcodes list
		if (unicaevents_exists_learndash()) {
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('unicaevents_filter_get_blog_type',			'unicaevents_learndash_get_blog_type', 9, 2);
			add_filter('unicaevents_filter_get_blog_title',		'unicaevents_learndash_get_blog_title', 9, 2);
			add_filter('unicaevents_filter_get_current_taxonomy',	'unicaevents_learndash_get_current_taxonomy', 9, 2);
			add_filter('unicaevents_filter_is_taxonomy',			'unicaevents_learndash_is_taxonomy', 9, 2);
			add_filter('unicaevents_filter_get_stream_page_title',	'unicaevents_learndash_get_stream_page_title', 9, 2);
			add_filter('unicaevents_filter_get_stream_page_link',	'unicaevents_learndash_get_stream_page_link', 9, 2);
			add_filter('unicaevents_filter_get_stream_page_id',	'unicaevents_learndash_get_stream_page_id', 9, 2);
			add_filter('unicaevents_filter_query_add_filters',		'unicaevents_learndash_query_add_filters', 9, 2);
			add_filter('unicaevents_filter_detect_inheritance_key','unicaevents_learndash_detect_inheritance_key', 9, 1);

			// One-click importer support
			add_filter( 'unicaevents_filter_importer_options',		'unicaevents_learndash_importer_set_options' );

			// Add shortcodes in the list
			//add_action('unicaevents_action_shortcodes_list',		'unicaevents_learndash_reg_shortcodes');
			//if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			//	add_action('unicaevents_action_shortcodes_list_vc','unicaevents_learndash_reg_shortcodes_vc');

			// Get list post_types and taxonomies
			global $UNICAEVENTS_GLOBALS;
			$UNICAEVENTS_GLOBALS['learndash_post_types'] = array('sfwd-courses', 'sfwd-lessons', 'sfwd-quiz', 'sfwd-topic', 'sfwd-certificates', 'sfwd-transactions');
			$UNICAEVENTS_GLOBALS['learndash_taxonomies'] = array('category');
		}
		if (is_admin()) {
			add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_learndash_importer_required_plugins' );
			add_filter( 'unicaevents_filter_required_plugins',				'unicaevents_learndash_required_plugins' );
		}
	}
}

// Attention! Add action on 'init' instead 'before_init_theme' because LearnDash add post_types and taxonomies on this action
if ( !function_exists( 'unicaevents_learndash_settings_theme_setup2' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_learndash_settings_theme_setup2', 3 );
	//add_action( 'init', 'unicaevents_learndash_settings_theme_setup2', 20 );
	function unicaevents_learndash_settings_theme_setup2() {
		// Add LearnDash post type and taxonomy into theme inheritance list
		if (unicaevents_exists_learndash()) {
			global $UNICAEVENTS_GLOBALS;
			// Get list post_types and taxonomies
			if (!empty(SFWD_CPT_Instance::$instances) && count(SFWD_CPT_Instance::$instances) > 0) {
				$post_types = array();
				foreach (SFWD_CPT_Instance::$instances as $pt=>$data)
					$post_types[] = $pt;
				if (count($post_types) > 0)
					$UNICAEVENTS_GLOBALS['learndash_post_types'] = $post_types;
			}
			// Add in the inheritance list
			unicaevents_add_theme_inheritance( array('learndash' => array(
				'stream_template' => 'blog-learndash',
				'single_template' => 'single-learndash',
				'taxonomy' => $UNICAEVENTS_GLOBALS['learndash_taxonomies'],
				'taxonomy_tags' => array('post_tag'),
				'post_type' => $UNICAEVENTS_GLOBALS['learndash_post_types'],
				'override' => 'page'
				) )
			);
		}
	}
}



// Check if UnicaEvents Donations installed and activated
if ( !function_exists( 'unicaevents_exists_learndash' ) ) {
	function unicaevents_exists_learndash() {
		return class_exists('SFWD_LMS');
	}
}


// Return true, if current page is donations page
if ( !function_exists( 'unicaevents_is_learndash_page' ) ) {
	function unicaevents_is_learndash_page() {
		$is = false;
		if (unicaevents_exists_learndash()) {
			global $UNICAEVENTS_GLOBALS;
			$is = in_array($UNICAEVENTS_GLOBALS['page_template'], array('blog-learndash', 'single-learndash'));
			if (!$is) {
				$is = !empty($UNICAEVENTS_GLOBALS['pre_query'])
							? $UNICAEVENTS_GLOBALS['pre_query']->is_single() && in_array($UNICAEVENTS_GLOBALS['pre_query']->get('post_type'), $UNICAEVENTS_GLOBALS['learndash_post_types'])
							: is_single() && in_array(get_query_var('post_type'), $UNICAEVENTS_GLOBALS['learndash_post_types']);
			}
			if (!$is) {
				if (count($UNICAEVENTS_GLOBALS['learndash_post_types']) > 0) {
					foreach ($UNICAEVENTS_GLOBALS['learndash_post_types'] as $pt) {
						if (!empty($UNICAEVENTS_GLOBALS['pre_query']) ? $UNICAEVENTS_GLOBALS['pre_query']->is_post_type_archive($pt) : is_post_type_archive($pt)) {
							$is = true;
							break;
						}
					}
				}
			}
			if (!$is) {
				if (count($UNICAEVENTS_GLOBALS['learndash_taxonomies']) > 0) {
					foreach ($UNICAEVENTS_GLOBALS['learndash_taxonomies'] as $pt) {
						if (!empty($UNICAEVENTS_GLOBALS['pre_query']) ? $UNICAEVENTS_GLOBALS['pre_query']->is_tax($pt) : is_tax($pt)) {
							$is = true;
							break;
						}
					}
				}
			}
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'unicaevents_learndash_detect_inheritance_key' ) ) {
	//add_filter('unicaevents_filter_detect_inheritance_key',	'unicaevents_learndash_detect_inheritance_key', 9, 1);
	function unicaevents_learndash_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return unicaevents_is_learndash_page() ? 'learndash' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'unicaevents_learndash_get_blog_type' ) ) {
	//add_filter('unicaevents_filter_get_blog_type',	'unicaevents_learndash_get_blog_type', 9, 2);
	function unicaevents_learndash_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		global $UNICAEVENTS_GLOBALS;
		if (count($UNICAEVENTS_GLOBALS['learndash_taxonomies']) > 0) {
			foreach ($UNICAEVENTS_GLOBALS['learndash_taxonomies'] as $pt) {
				if ($query && $query->is_tax($pt) || is_tax($pt)) {
					$page = 'learndash_'.$pt;
					break;
				}
			}
		}
		if (empty($page)) {
			$pt = $query ? $query->get('post_type') : get_query_var('post_type');
			if (in_array($pt, $UNICAEVENTS_GLOBALS['learndash_post_types'])) {
				$page = $query && $query->is_single() || is_single() ? 'learndash_item' : 'learndash';
			}
		}
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'unicaevents_learndash_get_blog_title' ) ) {
	//add_filter('unicaevents_filter_get_blog_title',	'unicaevents_learndash_get_blog_title', 9, 2);
	function unicaevents_learndash_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( unicaevents_strpos($page, 'learndash')!==false ) {
			if ( $page == 'learndash_item' ) {
				$title = unicaevents_get_post_title();
			} else if ( unicaevents_strpos($page, 'learndash_')!==false ) {
				$parts = explode('_', $page);
				$term = get_term_by( 'slug', get_query_var( $parts[1] ), $parts[1], OBJECT);
				$title = $term->name;
			} else {
				$title = esc_html__('All courses', 'unicaevents');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'unicaevents_learndash_get_stream_page_title' ) ) {
	//add_filter('unicaevents_filter_get_stream_page_title',	'unicaevents_learndash_get_stream_page_title', 9, 2);
	function unicaevents_learndash_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (unicaevents_strpos($page, 'learndash')!==false) {
			if (($page_id = unicaevents_learndash_get_stream_page_id(0, $page=='learndash' ? 'blog-learndash' : $page)) > 0)
				$title = unicaevents_get_post_title($page_id);
			else
				$title = esc_html__('All courses', 'unicaevents');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'unicaevents_learndash_get_stream_page_id' ) ) {
	//add_filter('unicaevents_filter_get_stream_page_id',	'unicaevents_learndash_get_stream_page_id', 9, 2);
	function unicaevents_learndash_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (unicaevents_strpos($page, 'learndash')!==false) $id = unicaevents_get_template_page_id('blog-learndash');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'unicaevents_learndash_get_stream_page_link' ) ) {
	//add_filter('unicaevents_filter_get_stream_page_link',	'unicaevents_learndash_get_stream_page_link', 9, 2);
	function unicaevents_learndash_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (unicaevents_strpos($page, 'learndash')!==false) {
			$id = unicaevents_get_template_page_id('blog-learndash');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'unicaevents_learndash_get_current_taxonomy' ) ) {
	//add_filter('unicaevents_filter_get_current_taxonomy',	'unicaevents_learndash_get_current_taxonomy', 9, 2);
	function unicaevents_learndash_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( unicaevents_strpos($page, 'learndash')!==false ) {
			global $UNICAEVENTS_GLOBALS;
			if (count($UNICAEVENTS_GLOBALS['learndash_taxonomies']) > 0) {
				$tax = $UNICAEVENTS_GLOBALS['learndash_taxonomies'][0];
			}
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'unicaevents_learndash_is_taxonomy' ) ) {
	//add_filter('unicaevents_filter_is_taxonomy',	'unicaevents_learndash_is_taxonomy', 9, 2);
	function unicaevents_learndash_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else {
			global $UNICAEVENTS_GLOBALS;
			if (count($UNICAEVENTS_GLOBALS['learndash_taxonomies']) > 0) {
				foreach ($UNICAEVENTS_GLOBALS['learndash_taxonomies'] as $pt) {
					if ($query && ($query->get($pt)!='' || $query->is_tax($pt)) || is_tax($pt)) {
						$tax = $pt;
						break;
					}
				}
			}
			return $tax;
		}
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'unicaevents_learndash_query_add_filters' ) ) {
	//add_filter('unicaevents_filter_query_add_filters',	'unicaevents_learndash_query_add_filters', 9, 2);
	function unicaevents_learndash_query_add_filters($args, $filter) {
		if ($filter == 'learndash') {
			//global $UNICAEVENTS_GLOBALS;
			$args['post_type'] = 'sfwd-courses';	//$UNICAEVENTS_GLOBALS['learndash_post_types'];
		}
		return $args;
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'unicaevents_learndash_required_plugins' ) ) {
	//add_filter('unicaevents_filter_required_plugins',	'unicaevents_learndash_required_plugins');
	function unicaevents_learndash_required_plugins($list=array()) {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('learndash', $UNICAEVENTS_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'LearnDash LMS',
					'slug' 		=> 'sfwd-lms',
					'source'	=> unicaevents_get_file_dir('plugins/install/sfwd-lms.zip'),
					'required' 	=> false
					);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'unicaevents_learndash_importer_required_plugins' ) ) {
	//add_filter( 'unicaevents_filter_importer_required_plugins',	'unicaevents_learndash_importer_required_plugins' );
	function unicaevents_learndash_importer_required_plugins($not_installed='') {
	    global $UNICAEVENTS_GLOBALS;
		if (in_array('learndash', $UNICAEVENTS_GLOBALS['required_plugins']) && !unicaevents_exists_learndash() )
			$not_installed .= '<br>LearnDash LMS';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'unicaevents_learndash_importer_set_options' ) ) {
	//add_filter( 'unicaevents_filter_importer_options',	'unicaevents_learndash_importer_set_options' );
	function unicaevents_learndash_importer_set_options($options=array()) {
		if (is_array($options)) {
			$options['additional_options'][] = 'sfwd_cpt_options';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}
?>
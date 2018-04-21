<?php
/**
 * UnicaEvents Framework: return lists
 *
 * @package unicaevents
 * @since unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'unicaevents_get_list_styles' ) ) {
	function unicaevents_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'unicaevents'), $i);
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'unicaevents_get_list_margins' ) ) {
	function unicaevents_get_list_margins($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_margins']))
			$list = $UNICAEVENTS_GLOBALS['list_margins'];
		else {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'unicaevents'),
				'tiny'		=> esc_html__('Tiny',		'unicaevents'),
				'small'		=> esc_html__('Small',		'unicaevents'),
				'medium'	=> esc_html__('Medium',		'unicaevents'),
				'large'		=> esc_html__('Large',		'unicaevents'),
				'huge'		=> esc_html__('Huge',		'unicaevents'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'unicaevents'),
				'small-'	=> esc_html__('Small (negative)',	'unicaevents'),
				'medium-'	=> esc_html__('Medium (negative)',	'unicaevents'),
				'large-'	=> esc_html__('Large (negative)',	'unicaevents'),
				'huge-'		=> esc_html__('Huge (negative)',	'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_margins'] = $list = apply_filters('unicaevents_filter_list_margins', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'unicaevents_get_list_animations' ) ) {
	function unicaevents_get_list_animations($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_animations']))
			$list = $UNICAEVENTS_GLOBALS['list_animations'];
		else {
			$list = array(
				'none'			=> esc_html__('- None -',	'unicaevents'),
				'bounced'		=> esc_html__('Bounced',		'unicaevents'),
				'flash'			=> esc_html__('Flash',		'unicaevents'),
				'flip'			=> esc_html__('Flip',		'unicaevents'),
				'pulse'			=> esc_html__('Pulse',		'unicaevents'),
				'rubberBand'	=> esc_html__('Rubber Band',	'unicaevents'),
				'shake'			=> esc_html__('Shake',		'unicaevents'),
				'swing'			=> esc_html__('Swing',		'unicaevents'),
				'tada'			=> esc_html__('Tada',		'unicaevents'),
				'wobble'		=> esc_html__('Wobble',		'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_animations'] = $list = apply_filters('unicaevents_filter_list_animations', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'unicaevents_get_list_line_styles' ) ) {
	function unicaevents_get_list_line_styles($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_line_styles']))
			$list = $UNICAEVENTS_GLOBALS['list_line_styles'];
		else {
			$list = array(
				'solid'	=> esc_html__('Solid', 'unicaevents'),
				'dashed'=> esc_html__('Dashed', 'unicaevents'),
				'dotted'=> esc_html__('Dotted', 'unicaevents'),
				'double'=> esc_html__('Double', 'unicaevents'),
				'image'	=> esc_html__('Image', 'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_line_styles'] = $list = apply_filters('unicaevents_filter_list_line_styles', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'unicaevents_get_list_animations_in' ) ) {
	function unicaevents_get_list_animations_in($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_animations_in']))
			$list = $UNICAEVENTS_GLOBALS['list_animations_in'];
		else {
			$list = array(
				'none'				=> esc_html__('- None -',			'unicaevents'),
				'bounceIn'			=> esc_html__('Bounce In',			'unicaevents'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'unicaevents'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'unicaevents'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'unicaevents'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'unicaevents'),
				'fadeIn'			=> esc_html__('Fade In',			'unicaevents'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'unicaevents'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'unicaevents'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'unicaevents'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'unicaevents'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'unicaevents'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'unicaevents'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'unicaevents'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'unicaevents'),
				'flipInX'			=> esc_html__('Flip In X',			'unicaevents'),
				'flipInY'			=> esc_html__('Flip In Y',			'unicaevents'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'unicaevents'),
				'rotateIn'			=> esc_html__('Rotate In',			'unicaevents'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','unicaevents'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'unicaevents'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'unicaevents'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','unicaevents'),
				'rollIn'			=> esc_html__('Roll In',			'unicaevents'),
				'slideInUp'			=> esc_html__('Slide In Up',		'unicaevents'),
				'slideInDown'		=> esc_html__('Slide In Down',		'unicaevents'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'unicaevents'),
				'slideInRight'		=> esc_html__('Slide In Right',		'unicaevents'),
				'zoomIn'			=> esc_html__('Zoom In',			'unicaevents'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'unicaevents'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'unicaevents'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'unicaevents'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_animations_in'] = $list = apply_filters('unicaevents_filter_list_animations_in', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'unicaevents_get_list_animations_out' ) ) {
	function unicaevents_get_list_animations_out($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_animations_out']))
			$list = $UNICAEVENTS_GLOBALS['list_animations_out'];
		else {
			$list = array(
				'none'				=> esc_html__('- None -',	'unicaevents'),
				'bounceOut'			=> esc_html__('Bounce Out',			'unicaevents'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'unicaevents'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'unicaevents'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'unicaevents'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'unicaevents'),
				'fadeOut'			=> esc_html__('Fade Out',			'unicaevents'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'unicaevents'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'unicaevents'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'unicaevents'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'unicaevents'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'unicaevents'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'unicaevents'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'unicaevents'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'unicaevents'),
				'flipOutX'			=> esc_html__('Flip Out X',			'unicaevents'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'unicaevents'),
				'hinge'				=> esc_html__('Hinge Out',			'unicaevents'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'unicaevents'),
				'rotateOut'			=> esc_html__('Rotate Out',			'unicaevents'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'unicaevents'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'unicaevents'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'unicaevents'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'unicaevents'),
				'rollOut'			=> esc_html__('Roll Out',		'unicaevents'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'unicaevents'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'unicaevents'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'unicaevents'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'unicaevents'),
				'zoomOut'			=> esc_html__('Zoom Out',			'unicaevents'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'unicaevents'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'unicaevents'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'unicaevents'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_animations_out'] = $list = apply_filters('unicaevents_filter_list_animations_out', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('unicaevents_get_animation_classes')) {
	function unicaevents_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return unicaevents_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!unicaevents_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'unicaevents_get_list_categories' ) ) {
	function unicaevents_get_list_categories($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_categories']))
			$list = $UNICAEVENTS_GLOBALS['list_categories'];
		else {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			$UNICAEVENTS_GLOBALS['list_categories'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'unicaevents_get_list_terms' ) ) {
	function unicaevents_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_taxonomies_'.($taxonomy)]))
			$list = $UNICAEVENTS_GLOBALS['list_taxonomies_'.($taxonomy)];
		else {
			$list = array();
			$args = array(
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => $taxonomy,
				'pad_counts'               => false );
			$taxonomies = get_terms( $taxonomy, $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			$UNICAEVENTS_GLOBALS['list_taxonomies_'.($taxonomy)] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'unicaevents_get_list_posts_types' ) ) {
	function unicaevents_get_list_posts_types($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_posts_types']))
			$list = $UNICAEVENTS_GLOBALS['list_posts_types'];
		else {
			$list = array();
			/* 
			// This way to return all registered post types
			$types = get_post_types();
			if (in_array('post', $types)) $list['post'] = esc_html__('Post', 'unicaevents');
			if (is_array($types) && count($types) > 0) {
				foreach ($types as $t) {
					if ($t == 'post') continue;
					$list[$t] = unicaevents_strtoproper($t);
				}
			}
			*/
			// Return only theme inheritance supported post types
			$UNICAEVENTS_GLOBALS['list_posts_types'] = $list = apply_filters('unicaevents_filter_list_post_types', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'unicaevents_get_list_posts' ) ) {
	function unicaevents_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		global $UNICAEVENTS_GLOBALS;
		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (isset($UNICAEVENTS_GLOBALS[$hash]))
			$list = $UNICAEVENTS_GLOBALS[$hash];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'unicaevents');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			$UNICAEVENTS_GLOBALS[$hash] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of registered users
if ( !function_exists( 'unicaevents_get_list_users' ) ) {
	function unicaevents_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_users']))
			$list = $UNICAEVENTS_GLOBALS['list_users'];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'unicaevents');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			$UNICAEVENTS_GLOBALS['list_users'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'unicaevents_get_list_sliders' ) ) {
	function unicaevents_get_list_sliders($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_sliders']))
			$list = $UNICAEVENTS_GLOBALS['list_sliders'];
		else {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'unicaevents')
			);
			$UNICAEVENTS_GLOBALS['list_sliders'] = $list = apply_filters('unicaevents_filter_list_sliders', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'unicaevents_get_list_slider_controls' ) ) {
	function unicaevents_get_list_slider_controls($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_slider_controls']))
			$list = $UNICAEVENTS_GLOBALS['list_slider_controls'];
		else {
			$list = array(
				'no'		=> esc_html__('None', 'unicaevents'),
				'side'		=> esc_html__('Side', 'unicaevents'),
				'bottom'	=> esc_html__('Bottom', 'unicaevents'),
				'pagination'=> esc_html__('Pagination', 'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_slider_controls'] = $list = apply_filters('unicaevents_filter_list_slider_controls', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'unicaevents_get_slider_controls_classes' ) ) {
	function unicaevents_get_slider_controls_classes($controls) {
		if (unicaevents_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'unicaevents_get_list_popup_engines' ) ) {
	function unicaevents_get_list_popup_engines($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_popup_engines']))
			$list = $UNICAEVENTS_GLOBALS['list_popup_engines'];
		else {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'unicaevents'),
				"magnific"	=> esc_html__("Magnific popup", 'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_popup_engines'] = $list = apply_filters('unicaevents_filter_list_popup_engines', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_menus' ) ) {
	function unicaevents_get_list_menus($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_menus']))
			$list = $UNICAEVENTS_GLOBALS['list_menus'];
		else {
			$list = array();
			$list['default'] = esc_html__("Default", 'unicaevents');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			$UNICAEVENTS_GLOBALS['list_menus'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'unicaevents_get_list_sidebars' ) ) {
	function unicaevents_get_list_sidebars($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_sidebars'])) {
			$list = $UNICAEVENTS_GLOBALS['list_sidebars'];
		} else {
			$list = isset($UNICAEVENTS_GLOBALS['registered_sidebars']) ? $UNICAEVENTS_GLOBALS['registered_sidebars'] : array();
			$UNICAEVENTS_GLOBALS['list_sidebars'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'unicaevents_get_list_sidebars_positions' ) ) {
	function unicaevents_get_list_sidebars_positions($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_sidebars_positions']))
			$list = $UNICAEVENTS_GLOBALS['list_sidebars_positions'];
		else {
			$list = array(
				'none'  => esc_html__('Hide',  'unicaevents'),
				'left'  => esc_html__('Left',  'unicaevents'),
				'right' => esc_html__('Right', 'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_sidebars_positions'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'unicaevents_get_sidebar_class' ) ) {
	function unicaevents_get_sidebar_class() {
		$sb_main = unicaevents_get_custom_option('show_sidebar_main');
		$sb_outer = unicaevents_get_custom_option('show_sidebar_outer');
		return (unicaevents_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (unicaevents_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_body_styles' ) ) {
	function unicaevents_get_list_body_styles($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_body_styles']))
			$list = $UNICAEVENTS_GLOBALS['list_body_styles'];
		else {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'unicaevents'),
				'wide'	=> esc_html__('Wide',		'unicaevents')
				);
			if (unicaevents_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'unicaevents');
				$list['fullscreen']	= esc_html__('Fullscreen',	'unicaevents');
			}
			$UNICAEVENTS_GLOBALS['list_body_styles'] = $list = apply_filters('unicaevents_filter_list_body_styles', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_skins' ) ) {
	function unicaevents_get_list_skins($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_skins']))
			$list = $UNICAEVENTS_GLOBALS['list_skins'];
		else
			$UNICAEVENTS_GLOBALS['list_skins'] = $list = unicaevents_get_list_folders("skins");
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'unicaevents_get_list_themes' ) ) {
	function unicaevents_get_list_themes($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_themes']))
			$list = $UNICAEVENTS_GLOBALS['list_themes'];
		else
			$UNICAEVENTS_GLOBALS['list_themes'] = $list = unicaevents_get_list_files("css/themes");
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_templates' ) ) {
	function unicaevents_get_list_templates($mode='') {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_templates_'.($mode)]))
			$list = $UNICAEVENTS_GLOBALS['list_templates_'.($mode)];
		else {
			$list = array();
			if (is_array($UNICAEVENTS_GLOBALS['registered_templates']) && count($UNICAEVENTS_GLOBALS['registered_templates']) > 0) {
				foreach ($UNICAEVENTS_GLOBALS['registered_templates'] as $k=>$v) {
					if ($mode=='' || unicaevents_strpos($v['mode'], $mode)!==false)
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: unicaevents_strtoproper($v['layout'])
										);
				}
			}
			$UNICAEVENTS_GLOBALS['list_templates_'.($mode)] = $list;
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_templates_blog' ) ) {
	function unicaevents_get_list_templates_blog($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_templates_blog']))
			$list = $UNICAEVENTS_GLOBALS['list_templates_blog'];
		else {
			$list = unicaevents_get_list_templates('blog');
			$UNICAEVENTS_GLOBALS['list_templates_blog'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_templates_blogger' ) ) {
	function unicaevents_get_list_templates_blogger($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_templates_blogger']))
			$list = $UNICAEVENTS_GLOBALS['list_templates_blogger'];
		else {
			$list = unicaevents_array_merge(unicaevents_get_list_templates('blogger'), unicaevents_get_list_templates('blog'));
			$UNICAEVENTS_GLOBALS['list_templates_blogger'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_templates_single' ) ) {
	function unicaevents_get_list_templates_single($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_templates_single']))
			$list = $UNICAEVENTS_GLOBALS['list_templates_single'];
		else {
			$list = unicaevents_get_list_templates('single');
			$UNICAEVENTS_GLOBALS['list_templates_single'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_templates_header' ) ) {
	function unicaevents_get_list_templates_header($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_templates_header']))
			$list = $UNICAEVENTS_GLOBALS['list_templates_header'];
		else {
			$list = unicaevents_get_list_templates('header');
			$UNICAEVENTS_GLOBALS['list_templates_header'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_templates_forms' ) ) {
	function unicaevents_get_list_templates_forms($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_templates_forms']))
			$list = $UNICAEVENTS_GLOBALS['list_templates_forms'];
		else {
			$list = unicaevents_get_list_templates('forms');
			$UNICAEVENTS_GLOBALS['list_templates_forms'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_article_styles' ) ) {
	function unicaevents_get_list_article_styles($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_article_styles']))
			$list = $UNICAEVENTS_GLOBALS['list_article_styles'];
		else {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'unicaevents'),
				"stretch" => esc_html__('Stretch', 'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_article_styles'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_post_formats_filters' ) ) {
	function unicaevents_get_list_post_formats_filters($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_post_formats_filters']))
			$list = $UNICAEVENTS_GLOBALS['list_post_formats_filters'];
		else {
			$list = array(
				"no"      => esc_html__('All posts', 'unicaevents'),
				"thumbs"  => esc_html__('With thumbs', 'unicaevents'),
				"reviews" => esc_html__('With reviews', 'unicaevents'),
				"video"   => esc_html__('With videos', 'unicaevents'),
				"audio"   => esc_html__('With audios', 'unicaevents'),
				"gallery" => esc_html__('With galleries', 'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_post_formats_filters'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_portfolio_filters' ) ) {
	function unicaevents_get_list_portfolio_filters($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_portfolio_filters']))
			$list = $UNICAEVENTS_GLOBALS['list_portfolio_filters'];
		else {
			$list = array(
				"hide"		=> esc_html__('Hide', 'unicaevents'),
				"tags"		=> esc_html__('Tags', 'unicaevents'),
				"categories"=> esc_html__('Categories', 'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_portfolio_filters'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_hovers' ) ) {
	function unicaevents_get_list_hovers($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_hovers']))
			$list = $UNICAEVENTS_GLOBALS['list_hovers'];
		else {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'unicaevents');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'unicaevents');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'unicaevents');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'unicaevents');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'unicaevents');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'unicaevents');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'unicaevents');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'unicaevents');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'unicaevents');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'unicaevents');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'unicaevents');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'unicaevents');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'unicaevents');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'unicaevents');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'unicaevents');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'unicaevents');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'unicaevents');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'unicaevents');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'unicaevents');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'unicaevents');
			$list['square effect1']  = esc_html__('Square Effect 1',  'unicaevents');
			$list['square effect2']  = esc_html__('Square Effect 2',  'unicaevents');
			$list['square effect3']  = esc_html__('Square Effect 3',  'unicaevents');
	//		$list['square effect4']  = esc_html__('Square Effect 4',  'unicaevents');
			$list['square effect5']  = esc_html__('Square Effect 5',  'unicaevents');
			$list['square effect6']  = esc_html__('Square Effect 6',  'unicaevents');
			$list['square effect7']  = esc_html__('Square Effect 7',  'unicaevents');
			$list['square effect8']  = esc_html__('Square Effect 8',  'unicaevents');
			$list['square effect9']  = esc_html__('Square Effect 9',  'unicaevents');
			$list['square effect10'] = esc_html__('Square Effect 10',  'unicaevents');
			$list['square effect11'] = esc_html__('Square Effect 11',  'unicaevents');
			$list['square effect12'] = esc_html__('Square Effect 12',  'unicaevents');
			$list['square effect13'] = esc_html__('Square Effect 13',  'unicaevents');
			$list['square effect14'] = esc_html__('Square Effect 14',  'unicaevents');
			$list['square effect15'] = esc_html__('Square Effect 15',  'unicaevents');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'unicaevents');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'unicaevents');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'unicaevents');
			$list['square effect_more']  = esc_html__('Square Effect More',  'unicaevents');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'unicaevents');
			$UNICAEVENTS_GLOBALS['list_hovers'] = $list = apply_filters('unicaevents_filter_portfolio_hovers', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'unicaevents_get_list_blog_counters' ) ) {
	function unicaevents_get_list_blog_counters($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_blog_counters']))
			$list = $UNICAEVENTS_GLOBALS['list_blog_counters'];
		else {
			$list = array(
				'views'		=> esc_html__('Views', 'unicaevents'),
				'likes'		=> esc_html__('Likes', 'unicaevents'),
				'rating'	=> esc_html__('Rating', 'unicaevents'),
				'comments'	=> esc_html__('Comments', 'unicaevents')
				);
			$UNICAEVENTS_GLOBALS['list_blog_counters'] = $list = apply_filters('unicaevents_filter_list_blog_counters', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'unicaevents_get_list_alter_sizes' ) ) {
	function unicaevents_get_list_alter_sizes($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_alter_sizes']))
			$list = $UNICAEVENTS_GLOBALS['list_alter_sizes'];
		else {
			$list = array(
					'1_1' => esc_html__('1x1', 'unicaevents'),
					'1_2' => esc_html__('1x2', 'unicaevents'),
					'2_1' => esc_html__('2x1', 'unicaevents'),
					'2_2' => esc_html__('2x2', 'unicaevents'),
					'1_3' => esc_html__('1x3', 'unicaevents'),
					'2_3' => esc_html__('2x3', 'unicaevents'),
					'3_1' => esc_html__('3x1', 'unicaevents'),
					'3_2' => esc_html__('3x2', 'unicaevents'),
					'3_3' => esc_html__('3x3', 'unicaevents')
					);
			$UNICAEVENTS_GLOBALS['list_alter_sizes'] = $list = apply_filters('unicaevents_filter_portfolio_alter_sizes', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_hovers_directions' ) ) {
	function unicaevents_get_list_hovers_directions($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_hovers_directions']))
			$list = $UNICAEVENTS_GLOBALS['list_hovers_directions'];
		else {
			$list = array();
			$list['left_to_right'] = esc_html__('Left to Right',  'unicaevents');
			$list['right_to_left'] = esc_html__('Right to Left',  'unicaevents');
			$list['top_to_bottom'] = esc_html__('Top to Bottom',  'unicaevents');
			$list['bottom_to_top'] = esc_html__('Bottom to Top',  'unicaevents');
			$list['scale_up']      = esc_html__('Scale Up',  'unicaevents');
			$list['scale_down']    = esc_html__('Scale Down',  'unicaevents');
			$list['scale_down_up'] = esc_html__('Scale Down-Up',  'unicaevents');
			$list['from_left_and_right'] = esc_html__('From Left and Right',  'unicaevents');
			$list['from_top_and_bottom'] = esc_html__('From Top and Bottom',  'unicaevents');
			$UNICAEVENTS_GLOBALS['list_hovers_directions'] = $list = apply_filters('unicaevents_filter_portfolio_hovers_directions', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'unicaevents_get_list_label_positions' ) ) {
	function unicaevents_get_list_label_positions($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_label_positions']))
			$list = $UNICAEVENTS_GLOBALS['list_label_positions'];
		else {
			$list = array();
			$list['top']	= esc_html__('Top',		'unicaevents');
			$list['bottom']	= esc_html__('Bottom',		'unicaevents');
			$list['left']	= esc_html__('Left',		'unicaevents');
			$list['over']	= esc_html__('Over',		'unicaevents');
			$UNICAEVENTS_GLOBALS['list_label_positions'] = $list = apply_filters('unicaevents_filter_label_positions', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'unicaevents_get_list_bg_image_positions' ) ) {
	function unicaevents_get_list_bg_image_positions($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_bg_image_positions']))
			$list = $UNICAEVENTS_GLOBALS['list_bg_image_positions'];
		else {
			$list = array();
			$list['left top']	  = esc_html__('Left Top', 'unicaevents');
			$list['center top']   = esc_html__("Center Top", 'unicaevents');
			$list['right top']    = esc_html__("Right Top", 'unicaevents');
			$list['left center']  = esc_html__("Left Center", 'unicaevents');
			$list['center center']= esc_html__("Center Center", 'unicaevents');
			$list['right center'] = esc_html__("Right Center", 'unicaevents');
			$list['left bottom']  = esc_html__("Left Bottom", 'unicaevents');
			$list['center bottom']= esc_html__("Center Bottom", 'unicaevents');
			$list['right bottom'] = esc_html__("Right Bottom", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_bg_image_positions'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'unicaevents_get_list_bg_image_repeats' ) ) {
	function unicaevents_get_list_bg_image_repeats($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_bg_image_repeats']))
			$list = $UNICAEVENTS_GLOBALS['list_bg_image_repeats'];
		else {
			$list = array();
			$list['repeat']	  = esc_html__('Repeat', 'unicaevents');
			$list['repeat-x'] = esc_html__('Repeat X', 'unicaevents');
			$list['repeat-y'] = esc_html__('Repeat Y', 'unicaevents');
			$list['no-repeat']= esc_html__('No Repeat', 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_bg_image_repeats'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'unicaevents_get_list_bg_image_attachments' ) ) {
	function unicaevents_get_list_bg_image_attachments($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_bg_image_attachments']))
			$list = $UNICAEVENTS_GLOBALS['list_bg_image_attachments'];
		else {
			$list = array();
			$list['scroll']	= esc_html__('Scroll', 'unicaevents');
			$list['fixed']	= esc_html__('Fixed', 'unicaevents');
			$list['local']	= esc_html__('Local', 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_bg_image_attachments'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'unicaevents_get_list_bg_tints' ) ) {
	function unicaevents_get_list_bg_tints($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_bg_tints']))
			$list = $UNICAEVENTS_GLOBALS['list_bg_tints'];
		else {
			$list = array();
			$list['white']	= esc_html__('White', 'unicaevents');
			$list['light']	= esc_html__('Light', 'unicaevents');
			$list['dark']	= esc_html__('Dark', 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_bg_tints'] = $list = apply_filters('unicaevents_filter_bg_tints', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_field_types' ) ) {
	function unicaevents_get_list_field_types($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_field_types']))
			$list = $UNICAEVENTS_GLOBALS['list_field_types'];
		else {
			$list = array();
			$list['text']     = esc_html__('Text',  'unicaevents');
			$list['textarea'] = esc_html__('Text Area','unicaevents');
			$list['password'] = esc_html__('Password',  'unicaevents');
			$list['radio']    = esc_html__('Radio',  'unicaevents');
			$list['checkbox'] = esc_html__('Checkbox',  'unicaevents');
			$list['select']   = esc_html__('Select',  'unicaevents');
			$list['date']     = esc_html__('Date','unicaevents');
			$list['time']     = esc_html__('Time','unicaevents');
			$list['button']   = esc_html__('Button','unicaevents');
			$UNICAEVENTS_GLOBALS['list_field_types'] = $list = apply_filters('unicaevents_filter_field_types', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'unicaevents_get_list_googlemap_styles' ) ) {
	function unicaevents_get_list_googlemap_styles($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_googlemap_styles']))
			$list = $UNICAEVENTS_GLOBALS['list_googlemap_styles'];
		else {
			$list = array();
			$list['default'] = esc_html__('Default', 'unicaevents');
			$list['simple'] = esc_html__('Simple', 'unicaevents');
			$list['greyscale'] = esc_html__('Greyscale', 'unicaevents');
			$list['greyscale2'] = esc_html__('Greyscale 2', 'unicaevents');
			$list['invert'] = esc_html__('Invert', 'unicaevents');
			$list['dark'] = esc_html__('Dark', 'unicaevents');
			$list['style1'] = esc_html__('Custom style 1', 'unicaevents');
			$list['style2'] = esc_html__('Custom style 2', 'unicaevents');
			$list['style3'] = esc_html__('Custom style 3', 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_googlemap_styles'] = $list = apply_filters('unicaevents_filter_googlemap_styles', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'unicaevents_get_list_icons' ) ) {
	function unicaevents_get_list_icons($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_icons']))
			$list = $UNICAEVENTS_GLOBALS['list_icons'];
		else
			$UNICAEVENTS_GLOBALS['list_icons'] = $list = unicaevents_parse_icons_classes(unicaevents_get_file_dir("css/fontello/css/fontello-codes.css"));
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'unicaevents_get_list_socials' ) ) {
	function unicaevents_get_list_socials($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_socials']))
			$list = $UNICAEVENTS_GLOBALS['list_socials'];
		else
			$UNICAEVENTS_GLOBALS['list_socials'] = $list = unicaevents_get_list_files("images/socials", "png");
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'unicaevents_get_list_flags' ) ) {
	function unicaevents_get_list_flags($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_flags']))
			$list = $UNICAEVENTS_GLOBALS['list_flags'];
		else
			$UNICAEVENTS_GLOBALS['list_flags'] = $list = unicaevents_get_list_files("images/flags", "png");
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'unicaevents_get_list_yesno' ) ) {
	function unicaevents_get_list_yesno($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_yesno']))
			$list = $UNICAEVENTS_GLOBALS['list_yesno'];
		else {
			$list = array();
			$list["yes"] = esc_html__("Yes", 'unicaevents');
			$list["no"]  = esc_html__("No", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_yesno'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'unicaevents_get_list_onoff' ) ) {
	function unicaevents_get_list_onoff($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_onoff']))
			$list = $UNICAEVENTS_GLOBALS['list_onoff'];
		else {
			$list = array();
			$list["on"] = esc_html__("On", 'unicaevents');
			$list["off"] = esc_html__("Off", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_onoff'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'unicaevents_get_list_showhide' ) ) {
	function unicaevents_get_list_showhide($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_showhide']))
			$list = $UNICAEVENTS_GLOBALS['list_showhide'];
		else {
			$list = array();
			$list["show"] = esc_html__("Show", 'unicaevents');
			$list["hide"] = esc_html__("Hide", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_showhide'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'unicaevents_get_list_orderings' ) ) {
	function unicaevents_get_list_orderings($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_orderings']))
			$list = $UNICAEVENTS_GLOBALS['list_orderings'];
		else {
			$list = array();
			$list["asc"] = esc_html__("Ascending", 'unicaevents');
			$list["desc"] = esc_html__("Descending", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_orderings'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'unicaevents_get_list_directions' ) ) {
	function unicaevents_get_list_directions($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_directions']))
			$list = $UNICAEVENTS_GLOBALS['list_directions'];
		else {
			$list = array();
			$list["horizontal"] = esc_html__("Horizontal", 'unicaevents');
			$list["vertical"] = esc_html__("Vertical", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_directions'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'unicaevents_get_list_shapes' ) ) {
	function unicaevents_get_list_shapes($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_shapes']))
			$list = $UNICAEVENTS_GLOBALS['list_shapes'];
		else {
			$list = array();
			$list["round"]  = esc_html__("Round", 'unicaevents');
			$list["square"] = esc_html__("Square", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_shapes'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'unicaevents_get_list_sizes' ) ) {
	function unicaevents_get_list_sizes($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_sizes']))
			$list = $UNICAEVENTS_GLOBALS['list_sizes'];
		else {
			$list = array();
			$list["tiny"]   = esc_html__("Tiny", 'unicaevents');
			$list["small"]  = esc_html__("Small", 'unicaevents');
			$list["medium"] = esc_html__("Medium", 'unicaevents');
			$list["large"]  = esc_html__("Large", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_sizes'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'unicaevents_get_list_floats' ) ) {
	function unicaevents_get_list_floats($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_floats']))
			$list = $UNICAEVENTS_GLOBALS['list_floats'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'unicaevents');
			$list["left"] = esc_html__("Float Left", 'unicaevents');
			$list["right"] = esc_html__("Float Right", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_floats'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'unicaevents_get_list_alignments' ) ) {
	function unicaevents_get_list_alignments($justify=false, $prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_alignments']))
			$list = $UNICAEVENTS_GLOBALS['list_alignments'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'unicaevents');
			$list["left"] = esc_html__("Left", 'unicaevents');
			$list["center"] = esc_html__("Center", 'unicaevents');
			$list["right"] = esc_html__("Right", 'unicaevents');
			if ($justify) $list["justify"] = esc_html__("Justify", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_alignments'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'unicaevents_get_list_sortings' ) ) {
	function unicaevents_get_list_sortings($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_sortings']))
			$list = $UNICAEVENTS_GLOBALS['list_sortings'];
		else {
			$list = array();
			$list["date"] = esc_html__("Date", 'unicaevents');
			$list["title"] = esc_html__("Alphabetically", 'unicaevents');
			$list["views"] = esc_html__("Popular (views count)", 'unicaevents');
			$list["comments"] = esc_html__("Most commented (comments count)", 'unicaevents');
			$list["author_rating"] = esc_html__("Author rating", 'unicaevents');
			$list["users_rating"] = esc_html__("Visitors (users) rating", 'unicaevents');
			$list["random"] = esc_html__("Random", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_sortings'] = $list = apply_filters('unicaevents_filter_list_sortings', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'unicaevents_get_list_columns' ) ) {
	function unicaevents_get_list_columns($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_columns']))
			$list = $UNICAEVENTS_GLOBALS['list_columns'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'unicaevents');
			$list["1_1"] = esc_html__("100%", 'unicaevents');
			$list["1_2"] = esc_html__("1/2", 'unicaevents');
			$list["1_3"] = esc_html__("1/3", 'unicaevents');
			$list["2_3"] = esc_html__("2/3", 'unicaevents');
			$list["1_4"] = esc_html__("1/4", 'unicaevents');
			$list["3_4"] = esc_html__("3/4", 'unicaevents');
			$list["1_5"] = esc_html__("1/5", 'unicaevents');
			$list["2_5"] = esc_html__("2/5", 'unicaevents');
			$list["3_5"] = esc_html__("3/5", 'unicaevents');
			$list["4_5"] = esc_html__("4/5", 'unicaevents');
			$list["1_6"] = esc_html__("1/6", 'unicaevents');
			$list["5_6"] = esc_html__("5/6", 'unicaevents');
			$list["1_7"] = esc_html__("1/7", 'unicaevents');
			$list["2_7"] = esc_html__("2/7", 'unicaevents');
			$list["3_7"] = esc_html__("3/7", 'unicaevents');
			$list["4_7"] = esc_html__("4/7", 'unicaevents');
			$list["5_7"] = esc_html__("5/7", 'unicaevents');
			$list["6_7"] = esc_html__("6/7", 'unicaevents');
			$list["1_8"] = esc_html__("1/8", 'unicaevents');
			$list["3_8"] = esc_html__("3/8", 'unicaevents');
			$list["5_8"] = esc_html__("5/8", 'unicaevents');
			$list["7_8"] = esc_html__("7/8", 'unicaevents');
			$list["1_9"] = esc_html__("1/9", 'unicaevents');
			$list["2_9"] = esc_html__("2/9", 'unicaevents');
			$list["4_9"] = esc_html__("4/9", 'unicaevents');
			$list["5_9"] = esc_html__("5/9", 'unicaevents');
			$list["7_9"] = esc_html__("7/9", 'unicaevents');
			$list["8_9"] = esc_html__("8/9", 'unicaevents');
			$list["1_10"]= esc_html__("1/10", 'unicaevents');
			$list["3_10"]= esc_html__("3/10", 'unicaevents');
			$list["7_10"]= esc_html__("7/10", 'unicaevents');
			$list["9_10"]= esc_html__("9/10", 'unicaevents');
			$list["1_11"]= esc_html__("1/11", 'unicaevents');
			$list["2_11"]= esc_html__("2/11", 'unicaevents');
			$list["3_11"]= esc_html__("3/11", 'unicaevents');
			$list["4_11"]= esc_html__("4/11", 'unicaevents');
			$list["5_11"]= esc_html__("5/11", 'unicaevents');
			$list["6_11"]= esc_html__("6/11", 'unicaevents');
			$list["7_11"]= esc_html__("7/11", 'unicaevents');
			$list["8_11"]= esc_html__("8/11", 'unicaevents');
			$list["9_11"]= esc_html__("9/11", 'unicaevents');
			$list["10_11"]= esc_html__("10/11", 'unicaevents');
			$list["1_12"]= esc_html__("1/12", 'unicaevents');
			$list["5_12"]= esc_html__("5/12", 'unicaevents');
			$list["7_12"]= esc_html__("7/12", 'unicaevents');
			$list["10_12"]= esc_html__("10/12", 'unicaevents');
			$list["11_12"]= esc_html__("11/12", 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_columns'] = $list = apply_filters('unicaevents_filter_list_columns', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'unicaevents_get_list_dedicated_locations' ) ) {
	function unicaevents_get_list_dedicated_locations($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_dedicated_locations']))
			$list = $UNICAEVENTS_GLOBALS['list_dedicated_locations'];
		else {
			$list = array();
			$list["default"] = esc_html__('As in the post defined', 'unicaevents');
			$list["center"]  = esc_html__('Above the text of the post', 'unicaevents');
			$list["left"]    = esc_html__('To the left the text of the post', 'unicaevents');
			$list["right"]   = esc_html__('To the right the text of the post', 'unicaevents');
			$list["alter"]   = esc_html__('Alternates for each post', 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_dedicated_locations'] = $list = apply_filters('unicaevents_filter_list_dedicated_locations', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'unicaevents_get_post_format_name' ) ) {
	function unicaevents_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'unicaevents') : esc_html__('galleries', 'unicaevents');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'unicaevents') : esc_html__('videos', 'unicaevents');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'unicaevents') : esc_html__('audios', 'unicaevents');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'unicaevents') : esc_html__('images', 'unicaevents');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'unicaevents') : esc_html__('quotes', 'unicaevents');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'unicaevents') : esc_html__('links', 'unicaevents');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'unicaevents') : esc_html__('statuses', 'unicaevents');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'unicaevents') : esc_html__('asides', 'unicaevents');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'unicaevents') : esc_html__('chats', 'unicaevents');
		else						$name = $single ? esc_html__('standard', 'unicaevents') : esc_html__('standards', 'unicaevents');
		return apply_filters('unicaevents_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'unicaevents_get_post_format_icon' ) ) {
	function unicaevents_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('unicaevents_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'unicaevents_get_list_fonts_styles' ) ) {
	function unicaevents_get_list_fonts_styles($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_fonts_styles']))
			$list = $UNICAEVENTS_GLOBALS['list_fonts_styles'];
		else {
			$list = array();
			$list['i'] = esc_html__('I','unicaevents');
			$list['u'] = esc_html__('U', 'unicaevents');
			$UNICAEVENTS_GLOBALS['list_fonts_styles'] = $list;
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'unicaevents_get_list_fonts' ) ) {
	function unicaevents_get_list_fonts($prepend_inherit=false) {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['list_fonts']))
			$list = $UNICAEVENTS_GLOBALS['list_fonts'];
		else {
			$list = array();
			$list = unicaevents_array_merge($list, unicaevents_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>unicaevents_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list['Advent Pro'] = array('family'=>'sans-serif');
			$list['Alegreya Sans'] = array('family'=>'sans-serif');
			$list['Arimo'] = array('family'=>'sans-serif');
			$list['Asap'] = array('family'=>'sans-serif');
			$list['Averia Sans Libre'] = array('family'=>'cursive');
			$list['Averia Serif Libre'] = array('family'=>'cursive');
			$list['Bree Serif'] = array('family'=>'serif',);
			$list['Cabin'] = array('family'=>'sans-serif');
			$list['Cabin Condensed'] = array('family'=>'sans-serif');
			$list['Caudex'] = array('family'=>'serif');
			$list['Comfortaa'] = array('family'=>'cursive');
			$list['Cousine'] = array('family'=>'sans-serif');
			$list['Crimson Text'] = array('family'=>'serif');
			$list['Cuprum'] = array('family'=>'sans-serif');
			$list['Dosis'] = array('family'=>'sans-serif');
			$list['Economica'] = array('family'=>'sans-serif');
			$list['Exo'] = array('family'=>'sans-serif');
			$list['Expletus Sans'] = array('family'=>'cursive');
			$list['Karla'] = array('family'=>'sans-serif');
			$list['Lato'] = array('family'=>'sans-serif');
			$list['Lekton'] = array('family'=>'sans-serif');
			$list['Lobster Two'] = array('family'=>'cursive');
			$list['Maven Pro'] = array('family'=>'sans-serif');
			$list['Merriweather'] = array('family'=>'serif');
			$list['Montserrat'] = array('family'=>'sans-serif');
			$list['Neuton'] = array('family'=>'serif');
			$list['Noticia Text'] = array('family'=>'serif');
			$list['Old Standard TT'] = array('family'=>'serif');
			$list['Open Sans'] = array('family'=>'sans-serif');
			$list['Orbitron'] = array('family'=>'sans-serif');
			$list['Oswald'] = array('family'=>'sans-serif');
			$list['Overlock'] = array('family'=>'cursive');
			$list['Oxygen'] = array('family'=>'sans-serif');
			$list['Philosopher'] = array('family'=>'serif');
			$list['PT Serif'] = array('family'=>'serif');
			$list['Puritan'] = array('family'=>'sans-serif');
			$list['Raleway'] = array('family'=>'sans-serif');
			$list['Roboto'] = array('family'=>'sans-serif');
			$list['Roboto Slab'] = array('family'=>'sans-serif');
			$list['Roboto Condensed'] = array('family'=>'sans-serif');
			$list['Rosario'] = array('family'=>'sans-serif');
			$list['Share'] = array('family'=>'cursive');
			$list['Signika'] = array('family'=>'sans-serif');
			$list['Signika Negative'] = array('family'=>'sans-serif');
			$list['Source Sans Pro'] = array('family'=>'sans-serif');
			$list['Tinos'] = array('family'=>'serif');
			$list['Ubuntu'] = array('family'=>'sans-serif');
			$list['Vollkorn'] = array('family'=>'serif');
			$UNICAEVENTS_GLOBALS['list_fonts'] = $list = apply_filters('unicaevents_filter_list_fonts', $list);
		}
		return $prepend_inherit ? unicaevents_array_merge(array('inherit' => esc_html__("Inherit", 'unicaevents')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'unicaevents_get_list_font_faces' ) ) {
	function unicaevents_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = unicaevents_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? unicaevents_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? unicaevents_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.esc_html__('uploaded font', 'unicaevents').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>
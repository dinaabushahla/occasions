<?php
/**
 * UnicaEvents Framework: Team post type settings
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Theme init
if (!function_exists('unicaevents_team_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_team_theme_setup', 1 );
	function unicaevents_team_theme_setup() {

		// Add item in the admin menu
		add_action('add_meta_boxes',						'unicaevents_team_add_meta_box');

		// Save data from meta box
		add_action('save_post',								'unicaevents_team_save_data');
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('unicaevents_filter_get_blog_type',			'unicaevents_team_get_blog_type', 9, 2);
		add_filter('unicaevents_filter_get_blog_title',		'unicaevents_team_get_blog_title', 9, 2);
		add_filter('unicaevents_filter_get_current_taxonomy',	'unicaevents_team_get_current_taxonomy', 9, 2);
		add_filter('unicaevents_filter_is_taxonomy',			'unicaevents_team_is_taxonomy', 9, 2);
		add_filter('unicaevents_filter_get_stream_page_title',	'unicaevents_team_get_stream_page_title', 9, 2);
		add_filter('unicaevents_filter_get_stream_page_link',	'unicaevents_team_get_stream_page_link', 9, 2);
		add_filter('unicaevents_filter_get_stream_page_id',	'unicaevents_team_get_stream_page_id', 9, 2);
		add_filter('unicaevents_filter_query_add_filters',		'unicaevents_team_query_add_filters', 9, 2);
		add_filter('unicaevents_ filter_detect_inheritance_key','unicaevents_team_detect_inheritance_key', 9, 1);

		// Extra column for team members lists
		if (unicaevents_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-team_columns',			'unicaevents_post_add_options_column', 9);
			add_filter('manage_team_posts_custom_column',	'unicaevents_post_fill_options_column', 9, 2);
		}

		// Add shortcodes [trx_team] and [trx_team_item]
		add_action('unicaevents_action_shortcodes_list',		'unicaevents_team_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_team_reg_shortcodes_vc');

		// Meta box fields
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['team_meta_box'] = array(
			'id' => 'team-meta-box',
			'title' => esc_html__('Team Member Details', 'unicaevents'),
			'page' => 'team',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"team_member_position" => array(
					"title" => esc_html__('Position',  'unicaevents'),
					"desc" => wp_kses( __("Position of the team member", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "team_member_position",
					"std" => "",
					"type" => "text"),
				"team_member_email" => array(
					"title" => esc_html__("E-mail",  'unicaevents'),
					"desc" => wp_kses( __("E-mail of the team member - need to take Gravatar (if registered)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "team_member_email",
					"std" => "",
					"type" => "text"),
				"team_member_phone" => array(
					"title" => esc_html__("Phone",  'unicaevents'),
					"desc" => wp_kses( __("Phone of the team member", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "team_member_phone",
					"std" => "",
					"type" => "text"),
				"team_member_skype" => array(
					"title" => esc_html__("Skype",  'unicaevents'),
					"desc" => wp_kses( __("Skype of the team member", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "team_member_skype",
					"std" => "",
					"type" => "text"),
				"team_member_link" => array(
					"title" => esc_html__('Link to profile',  'unicaevents'),
					"desc" => wp_kses( __("URL of the team member profile page (if not this page)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "team_member_link",
					"std" => "",
					"type" => "text"),
				"team_member_socials" => array(
					"title" => esc_html__("Social links",  'unicaevents'),
					"desc" => wp_kses( __("Links to the social profiles of the team member", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "team_member_email",
					"std" => "",
					"type" => "social")
			)
		);
		
		// Prepare type "Team"
		unicaevents_require_data( 'post_type', 'team', array(
			'label'               => esc_html__( 'Team member', 'unicaevents' ),
			'description'         => esc_html__( 'Team Description', 'unicaevents' ),
			'labels'              => array(
				'name'                => esc_html__( 'Team', 'unicaevents' ),
				'singular_name'       => esc_html__( 'Team member', 'unicaevents' ),
				'menu_name'           => esc_html__( 'Team', 'unicaevents' ),
				'parent_item_colon'   => esc_html__( 'Parent Item:', 'unicaevents' ),
				'all_items'           => esc_html__( 'All Team', 'unicaevents' ),
				'view_item'           => esc_html__( 'View Item', 'unicaevents' ),
				'add_new_item'        => esc_html__( 'Add New Team member', 'unicaevents' ),
				'add_new'             => esc_html__( 'Add New', 'unicaevents' ),
				'edit_item'           => esc_html__( 'Edit Item', 'unicaevents' ),
				'update_item'         => esc_html__( 'Update Item', 'unicaevents' ),
				'search_items'        => esc_html__( 'Search Item', 'unicaevents' ),
				'not_found'           => esc_html__( 'Not found', 'unicaevents' ),
				'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'unicaevents' ),
			),
			'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'menu_icon'			  => 'dashicons-admin-users',
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => '52.3',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'query_var'           => true,
			'capability_type'     => 'page',
			'rewrite'             => true
			)
		);
			
		// Prepare taxonomy for team
		unicaevents_require_data( 'taxonomy', 'team_group', array(
			'post_type'			=> array( 'team' ),
			'hierarchical'      => true,
			'labels'            => array(
				'name'              => esc_html__( 'Team Group', 'unicaevents' ),
				'singular_name'     => esc_html__( 'Group', 'unicaevents' ),
				'search_items'      => esc_html__( 'Search Groups', 'unicaevents' ),
				'all_items'         => esc_html__( 'All Groups', 'unicaevents' ),
				'parent_item'       => esc_html__( 'Parent Group', 'unicaevents' ),
				'parent_item_colon' => esc_html__( 'Parent Group:', 'unicaevents' ),
				'edit_item'         => esc_html__( 'Edit Group', 'unicaevents' ),
				'update_item'       => esc_html__( 'Update Group', 'unicaevents' ),
				'add_new_item'      => esc_html__( 'Add New Group', 'unicaevents' ),
				'new_item_name'     => esc_html__( 'New Group Name', 'unicaevents' ),
				'menu_name'         => esc_html__( 'Team Group', 'unicaevents' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'team_group' ),
			)
		);
	}
}

if ( !function_exists( 'unicaevents_team_settings_theme_setup2' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_team_settings_theme_setup2', 3 );
	function unicaevents_team_settings_theme_setup2() {
		// Add post type 'team' and taxonomy 'team_group' into theme inheritance list
		unicaevents_add_theme_inheritance( array('team' => array(
			'stream_template' => 'blog-team',
			'single_template' => 'single-team',
			'taxonomy' => array('team_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('team'),
			'override' => 'page'
			) )
		);
	}
}


// Add meta box
if (!function_exists('unicaevents_team_add_meta_box')) {
	//add_action('add_meta_boxes', 'unicaevents_team_add_meta_box');
	function unicaevents_team_add_meta_box() {
		global $UNICAEVENTS_GLOBALS;
		$mb = $UNICAEVENTS_GLOBALS['team_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'unicaevents_team_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('unicaevents_team_show_meta_box')) {
	function unicaevents_team_show_meta_box() {
		global $post, $UNICAEVENTS_GLOBALS;

		// Use nonce for verification
		$data = get_post_meta($post->ID, 'team_data', true);
		$fields = $UNICAEVENTS_GLOBALS['team_meta_box']['fields'];
		?>
		<input type="hidden" name="meta_box_team_nonce" value="<?php echo esc_attr($UNICAEVENTS_GLOBALS['admin_nonce']); ?>" />
		<table class="team_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="team_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td>
						<?php
						if ($id == 'team_member_socials') {
							$socials_type = unicaevents_get_theme_setting('socials_type');
							$social_list = unicaevents_get_theme_option('social_icons');
							if (is_array($social_list) && count($social_list) > 0) {
								foreach ($social_list as $soc) {
									if ($socials_type == 'icons') {
										$parts = explode('-', $soc['icon'], 2);
										$sn = isset($parts[1]) ? $parts[1] : $sn;
									} else {
										$sn = basename($soc['icon']);
										$sn = unicaevents_substr($sn, 0, unicaevents_strrpos($sn, '.'));
										if (($pos=unicaevents_strrpos($sn, '_'))!==false)
											$sn = unicaevents_substr($sn, 0, $pos);
									}   
									$link = isset($meta[$sn]) ? $meta[$sn] : '';
									?>
									<label for="<?php echo esc_attr(($id).'_'.($sn)); ?>"><?php echo esc_attr(unicaevents_strtoproper($sn)); ?></label><br>
									<input type="text" name="<?php echo esc_attr($id); ?>[<?php echo esc_attr($sn); ?>]" id="<?php echo esc_attr(($id).'_'.($sn)); ?>" value="<?php echo esc_attr($link); ?>" size="30" /><br>
									<?php
								}
							}
						} else {
							?>
							<input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
							<?php
						}
						?>
						<br><small><?php echo esc_attr($field['desc']); ?></small>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from meta box
if (!function_exists('unicaevents_team_save_data')) {
	//add_action('save_post', 'unicaevents_team_save_data');
	function unicaevents_team_save_data($post_id) {
		global $UNICAEVENTS_GLOBALS;
		// verify nonce
		if (!isset($_POST['meta_box_team_nonce']) || !wp_verify_nonce($_POST['meta_box_team_nonce'], $UNICAEVENTS_GLOBALS['admin_url'])) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='team' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		global $UNICAEVENTS_GLOBALS;

		$data = array();

		$fields = $UNICAEVENTS_GLOBALS['team_meta_box']['fields'];

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) {
				if (isset($_POST[$id])) {
					if (is_array($_POST[$id]) && count($_POST[$id]) > 0) {
						foreach ($_POST[$id] as $sn=>$link) {
							$_POST[$id][$sn] = stripslashes($link);
						}
						$data[$id] = $_POST[$id];
					} else {
						$data[$id] = stripslashes($_POST[$id]);
					}
				}
			}
		}

		update_post_meta($post_id, 'team_data', $data);
	}
}



// Return true, if current page is team member page
if ( !function_exists( 'unicaevents_is_team_page' ) ) {
	function unicaevents_is_team_page() {
		global $UNICAEVENTS_GLOBALS;
		$is = in_array($UNICAEVENTS_GLOBALS['page_template'], array('blog-team', 'single-team'));
		if (!$is) {
			if (!empty($UNICAEVENTS_GLOBALS['pre_query']))
				$is = $UNICAEVENTS_GLOBALS['pre_query']->get('post_type')=='team' 
						|| $UNICAEVENTS_GLOBALS['pre_query']->is_tax('team_group') 
						|| ($UNICAEVENTS_GLOBALS['pre_query']->is_page() 
								&& ($id=unicaevents_get_template_page_id('blog-team')) > 0 
								&& $id==(isset($UNICAEVENTS_GLOBALS['pre_query']->queried_object_id) 
											? $UNICAEVENTS_GLOBALS['pre_query']->queried_object_id 
											: 0)
						);
			else
				$is = get_query_var('post_type')=='team' || is_tax('team_group') || (is_page() && ($id=unicaevents_get_template_page_id('blog-team')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'unicaevents_team_detect_inheritance_key' ) ) {
	//add_filter('unicaevents_filter_detect_inheritance_key',	'unicaevents_team_detect_inheritance_key', 9, 1);
	function unicaevents_team_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return unicaevents_is_team_page() ? 'team' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'unicaevents_team_get_blog_type' ) ) {
	//add_filter('unicaevents_filter_get_blog_type',	'unicaevents_team_get_blog_type', 9, 2);
	function unicaevents_team_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('team_group') || is_tax('team_group'))
			$page = 'team_category';
		else if ($query && $query->get('post_type')=='team' || get_query_var('post_type')=='team')
			$page = $query && $query->is_single() || is_single() ? 'team_item' : 'team';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'unicaevents_team_get_blog_title' ) ) {
	//add_filter('unicaevents_filter_get_blog_title',	'unicaevents_team_get_blog_title', 9, 2);
	function unicaevents_team_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( unicaevents_strpos($page, 'team')!==false ) {
			if ( $page == 'team_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'team_group' ), 'team_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'team_item' ) {
				$title = unicaevents_get_post_title();
			} else {
				$title = esc_html__('All team', 'unicaevents');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'unicaevents_team_get_stream_page_title' ) ) {
	//add_filter('unicaevents_filter_get_stream_page_title',	'unicaevents_team_get_stream_page_title', 9, 2);
	function unicaevents_team_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (unicaevents_strpos($page, 'team')!==false) {
			if (($page_id = unicaevents_team_get_stream_page_id(0, $page=='team' ? 'blog-team' : $page)) > 0)
				$title = unicaevents_get_post_title($page_id);
			else
				$title = esc_html__('All team', 'unicaevents');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'unicaevents_team_get_stream_page_id' ) ) {
	//add_filter('unicaevents_filter_get_stream_page_id',	'unicaevents_team_get_stream_page_id', 9, 2);
	function unicaevents_team_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (unicaevents_strpos($page, 'team')!==false) $id = unicaevents_get_template_page_id('blog-team');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'unicaevents_team_get_stream_page_link' ) ) {
	//add_filter('unicaevents_filter_get_stream_page_link',	'unicaevents_team_get_stream_page_link', 9, 2);
	function unicaevents_team_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (unicaevents_strpos($page, 'team')!==false) {
			$id = unicaevents_get_template_page_id('blog-team');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'unicaevents_team_get_current_taxonomy' ) ) {
	//add_filter('unicaevents_filter_get_current_taxonomy',	'unicaevents_team_get_current_taxonomy', 9, 2);
	function unicaevents_team_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( unicaevents_strpos($page, 'team')!==false ) {
			$tax = 'team_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'unicaevents_team_is_taxonomy' ) ) {
	//add_filter('unicaevents_filter_is_taxonomy',	'unicaevents_team_is_taxonomy', 9, 2);
	function unicaevents_team_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('team_group')!='' || is_tax('team_group') ? 'team_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'unicaevents_team_query_add_filters' ) ) {
	//add_filter('unicaevents_filter_query_add_filters',	'unicaevents_team_query_add_filters', 9, 2);
	function unicaevents_team_query_add_filters($args, $filter) {
		if ($filter == 'team') {
			$args['post_type'] = 'team';
		}
		return $args;
	}
}





// ---------------------------------- [trx_team] ---------------------------------------

/*
[trx_team id="unique_id" columns="3" style="team-1|team-2|..."]
	[trx_team_item user="user_login"]
	[trx_team_item member="member_id"]
	[trx_team_item name="team member name" photo="url" email="address" position="director"]
[/trx_team]
*/
if ( !function_exists( 'unicaevents_sc_team' ) ) {
	function unicaevents_sc_team($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "team-1",
			"slider" => "no",
			"controls" => "no",
			"slides_space" => 0,
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 3,
			"columns" => 3,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'unicaevents'),
			"link" => '',
			"scheme" => '',
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
			"right" => "",
			"bg_color" => ""
		), $atts)));

		if (empty($id)) $id = "sc_team_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && unicaevents_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = unicaevents_get_css_dimensions_from_values($width);
		$hs = unicaevents_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (unicaevents_param_is_off($custom) && $count < $columns) $columns = $count;

		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_team_id'] = $id;
		$UNICAEVENTS_GLOBALS['sc_team_style'] = $style;
		$UNICAEVENTS_GLOBALS['sc_team_columns'] = $columns;
		$UNICAEVENTS_GLOBALS['sc_team_counter'] = 0;
		$UNICAEVENTS_GLOBALS['sc_team_slider'] = $slider;
		$UNICAEVENTS_GLOBALS['sc_team_css_wh'] = $ws . $hs;
		$UNICAEVENTS_GLOBALS['sc_team_bg'] = $bg_color;

		if (unicaevents_param_is_on($slider)) unicaevents_enqueue_slider('swiper');
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_team_wrap '
						. ($scheme && !unicaevents_param_is_off($scheme) && !unicaevents_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_team sc_team_style_'.esc_attr($style)
							. ' ' . esc_attr(unicaevents_get_template_property($style, 'container_classes'))
							. ' ' . esc_attr(unicaevents_get_slider_controls_classes($controls))
							. (unicaevents_param_is_on($slider)
								? ' sc_slider_swiper swiper-slider-container'
									. (unicaevents_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
									. ($hs ? ' sc_slider_height_fixed' : '')
								: '')
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
						.'"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!empty($width) && unicaevents_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
						. (!empty($height) && unicaevents_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
						. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
						. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
						. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
						. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_team_subtitle sc_item_subtitle">' . trim(unicaevents_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_team_title sc_item_title">' . trim(unicaevents_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_team_descr sc_item_descr">' . trim(unicaevents_strmacros($description)) . '</div>' : '')
					. (unicaevents_param_is_on($slider) 
						? '<div class="slides swiper-wrapper">' 
						: ($columns > 1 // && unicaevents_get_template_property($style, 'need_columns')
							? '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		$content = do_shortcode($content);
	
		if (unicaevents_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'team',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = unicaevents_query_add_sort_order($args, $orderby, $order);
			$args = unicaevents_query_add_posts_and_cats($args, $ids, 'team', $cat, 'team_group');
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => unicaevents_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					"columns_count" => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => ''. (!empty($UNICAEVENTS_GLOBALS['sc_team_bg']) ? 'background: ' . $UNICAEVENTS_GLOBALS['sc_team_bg']. '; ' : ''),
					'tag_css_wh' => $ws . $hs
				);
				$post_data = unicaevents_get_post_data($args);
				$post_meta = get_post_meta($post_data['post_id'], 'team_data', true);
				$thumb_sizes = unicaevents_get_thumb_sizes(array('layout' => $style));
				$args['position'] = $post_meta['team_member_position'];
				$args['link'] = !empty($post_meta['team_member_link']) ? $post_meta['team_member_link'] : $post_data['post_link'];
				$args['email'] = $post_meta['team_member_email'];
				$args['photo'] = $post_data['post_thumb'];
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*min(2, max(1, unicaevents_get_theme_option("retina_ready"))));
				$args['socials'] = '';
				$soc_list = $post_meta['team_member_socials'];
				if (is_array($soc_list) && count($soc_list)>0) {
					$soc_str = '';
					foreach ($soc_list as $sn=>$sl) {
						if (!empty($sl))
							$soc_str .= (!empty($soc_str) ? '|' : '') . ($sn) . '=' . ($sl);
					}
					if (!empty($soc_str))
						$args['socials'] = unicaevents_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($soc_str).'"][/trx_socials]');
				}
	
				$output .= unicaevents_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}

		if (unicaevents_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {// && unicaevents_get_template_property($style, 'need_columns')) {
			$output .= '</div>';
		}

		$output .= (!empty($link) ? '<div class="sc_team_button sc_item_button">'.unicaevents_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_team -->'
				. '</div><!-- /.sc_team_wrap -->';
	
		// Add template specific scripts and styles
		do_action('unicaevents_action_blog_scripts', $style);
	
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_team', $atts, $content);
	}
	unicaevents_require_shortcode('trx_team', 'unicaevents_sc_team');
}


if ( !function_exists( 'unicaevents_sc_team_item' ) ) {
	function unicaevents_sc_team_item($atts, $content=null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts( array(
			// Individual params
			"user" => "",
			"member" => "",
			"name" => "",
			"position" => "",
			"photo" => "",
			"email" => "",
			"link" => "",
			"socials" => "",
			"skype"   => "",
			"phone"	  => "", 
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_team_counter']++;
	
		$id = $id ? $id : ($UNICAEVENTS_GLOBALS['sc_team_id'] ? $UNICAEVENTS_GLOBALS['sc_team_id'] . '_' . $UNICAEVENTS_GLOBALS['sc_team_counter'] : '');
	
		$descr = trim(chop(do_shortcode($content)));
	
		$thumb_sizes = unicaevents_get_thumb_sizes(array('layout' => $UNICAEVENTS_GLOBALS['sc_team_style']));
	
		if (!empty($socials)) $socials = unicaevents_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($socials).'"][/trx_socials]');
	
		if (!empty($user) && $user!='none' && ($user_obj = get_user_by('login', $user)) != false) {
			$meta = get_user_meta($user_obj->ID);
			if (empty($email))		$email = $user_obj->data->user_email;
			if (empty($name))		$name = $user_obj->data->display_name;
			if (empty($position))	$position = isset($meta['user_position'][0]) ? $meta['user_position'][0] : '';
			if (empty($descr))		$descr = isset($meta['description'][0]) ? $meta['description'][0] : '';
			if (empty($socials))	$socials = unicaevents_show_user_socials(array('author_id'=>$user_obj->ID, 'echo'=>false));
		}
	
		if (!empty($member) && $member!='none' && ($member_obj = (intval($member) > 0 ? get_post($member, OBJECT) : get_page_by_title($member, OBJECT, 'team'))) != null) {
			if (empty($name))		$name = $member_obj->post_title;
			if (empty($descr))		$descr = $member_obj->post_excerpt;
			$post_meta = get_post_meta($member_obj->ID, 'team_data', true);
			if (empty($position))	$position = $post_meta['team_member_position'];
			if (empty($phone))	$phone = $post_meta['team_member_phone'];
		if (empty($skype))	$skype = $post_meta['team_member_skype'];
			if (empty($link))		$link = !empty($post_meta['team_member_link']) ? $post_meta['team_member_link'] : get_permalink($member_obj->ID);
			if (empty($email))		$email = $post_meta['team_member_email'];
			if (empty($photo)) 		$photo = wp_get_attachment_url(get_post_thumbnail_id($member_obj->ID));
			if (empty($socials)) {
				$socials = '';
				$soc_list = $post_meta['team_member_socials'];
				if (is_array($soc_list) && count($soc_list)>0) {
					$soc_str = '';
					foreach ($soc_list as $sn=>$sl) {
						if (!empty($sl))
							$soc_str .= (!empty($soc_str) ? '|' : '') . ($sn) . '=' . ($sl);
					}
					if (!empty($soc_str))
						$socials = unicaevents_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($soc_str).'"][/trx_socials]');
				}
			}
		}
		if (empty($photo)) {
			if (!empty($email)) $photo = get_avatar($email, $thumb_sizes['w']*min(2, max(1, unicaevents_get_theme_option("retina_ready"))));
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = unicaevents_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}
		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr
		);
		$args = array(
			'layout' => $UNICAEVENTS_GLOBALS['sc_team_style'],
			'number' => $UNICAEVENTS_GLOBALS['sc_team_counter'],
			'columns_count' => $UNICAEVENTS_GLOBALS['sc_team_columns'],
			'slider' => $UNICAEVENTS_GLOBALS['sc_team_slider'],
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => $UNICAEVENTS_GLOBALS['sc_team_css_wh'],
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo,
			'socials' => $socials,
			'skype'		=>$skype,
			'phone'		=>$phone

		);
		$output = unicaevents_show_post_layout($args, $post_data);

		return apply_filters('unicaevents_shortcode_output', $output, 'trx_team_item', $atts, $content);
	}
	unicaevents_require_shortcode('trx_team_item', 'unicaevents_sc_team_item');
}
// ---------------------------------- [/trx_team] ---------------------------------------



// Add [trx_team] and [trx_team_item] in the shortcodes list
if (!function_exists('unicaevents_team_reg_shortcodes')) {
	//add_filter('unicaevents_action_shortcodes_list',	'unicaevents_team_reg_shortcodes');
	function unicaevents_team_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['shortcodes'])) {

			$users = unicaevents_get_list_users();
			$members = unicaevents_get_list_posts(false, array(
				'post_type'=>'team',
				'orderby'=>'title',
				'order'=>'asc',
				'return'=>'title'
				)
			);
			$team_groups = unicaevents_get_list_terms(false, 'team_group');
			$team_styles = unicaevents_get_list_templates('team');
			$controls	 = unicaevents_get_list_slider_controls();

			unicaevents_array_insert_after($UNICAEVENTS_GLOBALS['shortcodes'], 'trx_tabs', array(

				// Team
				"trx_team" => array(
					"title" => esc_html__("Team", "unicaevents"),
					"desc" => wp_kses( __("Insert team in your page (post)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
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
							"type" => "textarea"
							),
						"style" => array(
							"title" => esc_html__("Team style", "unicaevents"),
							"desc" => wp_kses( __("Select style to display team members", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"value" => "1",
							"type" => "select",
							"options" => $team_styles
							),
						"bg_color" => array(
							"title" => esc_html__("Background color", "unicaevents"),
							"desc" => wp_kses( __("Background color for all teams items", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "color"
							),
						"columns" => array(
							"title" => esc_html__("Columns", "unicaevents"),
							"desc" => wp_kses( __("How many columns use to show team members", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"value" => 3,
							"min" => 2,
							"max" => 5,
							"step" => 1,
							"type" => "spinner"
							),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "unicaevents"),
							"desc" => wp_kses( __("Select color scheme for this block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "checklist",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['schemes']
							),
						"slider" => array(
							"title" => esc_html__("Slider", "unicaevents"),
							"desc" => wp_kses( __("Use slider to show team members", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"value" => "no",
							"type" => "switch",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Controls", "unicaevents"),
							"desc" => wp_kses( __("Slider controls style and position", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", "unicaevents"),
							"desc" => wp_kses( __("Size of space (in px) between slides", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", "unicaevents"),
							"desc" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", "unicaevents"),
							"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", "unicaevents"),
							"desc" => wp_kses( __("Alignment of the team block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "unicaevents"),
							"desc" => wp_kses( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", "unicaevents"),
							"desc" => wp_kses( __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => unicaevents_array_merge(array(0 => esc_html__('- Select category -', 'unicaevents')), $team_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", "unicaevents"),
							"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "unicaevents"),
							"desc" => wp_kses( __("Skip posts before select next part.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "unicaevents"),
							"desc" => wp_kses( __("Select desired posts sorting method", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "unicaevents"),
							"desc" => wp_kses( __("Select desired posts order", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "unicaevents"),
							"desc" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
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
						"name" => "trx_team_item",
						"title" => esc_html__("Member", "unicaevents"),
						"desc" => wp_kses( __("Team member", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"container" => true,
						"params" => array(
							"user" => array(
								"title" => esc_html__("Registerd user", "unicaevents"),
								"desc" => wp_kses( __("Select one of registered users (if present) or put name, position, etc. in fields below", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "select",
								"options" => $users
							),
							"member" => array(
								"title" => esc_html__("Team member", "unicaevents"),
								"desc" => wp_kses( __("Select one of team members (if present) or put name, position, etc. in fields below", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "select",
								"options" => $members
							),
							"link" => array(
								"title" => esc_html__("Link", "unicaevents"),
								"desc" => wp_kses( __("Link on team member's personal page", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"name" => array(
								"title" => esc_html__("Name", "unicaevents"),
								"desc" => wp_kses( __("Team member's name", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", "unicaevents"),
								"desc" => wp_kses( __("Team member's position", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", "unicaevents"),
								"desc" => wp_kses( __("Team member's e-mail", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", "unicaevents"),
								"desc" => wp_kses( __("Team member's photo (avatar)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"socials" => array(
								"title" => esc_html__("Socials", "unicaevents"),
								"desc" => wp_kses( __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", "unicaevents"),
								"desc" => wp_kses( __("Team member's short description", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $UNICAEVENTS_GLOBALS['sc_params']['id'],
							"class" => $UNICAEVENTS_GLOBALS['sc_params']['class'],
							"animation" => $UNICAEVENTS_GLOBALS['sc_params']['animation'],
							"css" => $UNICAEVENTS_GLOBALS['sc_params']['css']
						)
					)
				)

			));
		}
	}
}


// Add [trx_team] and [trx_team_item] in the VC shortcodes list
if (!function_exists('unicaevents_team_reg_shortcodes_vc')) {
	//add_filter('unicaevents_action_shortcodes_list_vc',	'unicaevents_team_reg_shortcodes_vc');
	function unicaevents_team_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;

		$users = unicaevents_get_list_users();
		$members = unicaevents_get_list_posts(false, array(
			'post_type'=>'team',
			'orderby'=>'title',
			'order'=>'asc',
			'return'=>'title'
			)
		);
		$team_groups = unicaevents_get_list_terms(false, 'team_group');
		$team_styles = unicaevents_get_list_templates('team');
		$controls	 = unicaevents_get_list_slider_controls();

		// Team
		vc_map( array(
			"base" => "trx_team",
			"name" => esc_html__("Team", "unicaevents"),
			"description" => wp_kses( __("Insert team members", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_team',
			"class" => "trx_sc_columns trx_sc_team",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_team_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Team style", "unicaevents"),
					"description" => wp_kses( __("Select style to display team members", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip($team_styles),
					"type" => "dropdown"
					),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "unicaevents"),
					"description" => wp_kses( __("Background color for all teams items", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
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
					"param_name" => "slider",
					"heading" => esc_html__("Slider", "unicaevents"),
					"description" => wp_kses( __("Use slider to show team members", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'unicaevents'),
						"class" => "",
						"std" => "no",
						"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", "unicaevents"),
						"description" => wp_kses( __("Slider controls style and position", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'unicaevents'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", "unicaevents"),
						"description" => wp_kses( __("Size of space (in px) between slides", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'unicaevents'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", "unicaevents"),
						"description" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Slider', 'unicaevents'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", "unicaevents"),
						"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Slider', 'unicaevents'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "unicaevents"),
						"description" => wp_kses( __("Alignment of the team block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "unicaevents"),
						"description" => wp_kses( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array("Custom members" => "yes" ),
						"type" => "checkbox"
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
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "unicaevents"),
						"description" => wp_kses( __("Select category to show team members. If empty - select team members from any category (group) or from IDs list", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'unicaevents'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(unicaevents_array_merge(array(0 => esc_html__('- Select category -', 'unicaevents')), $team_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "unicaevents"),
						"description" => wp_kses( __("How many columns use to show team members", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'unicaevents'),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "unicaevents"),
						"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'unicaevents'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "unicaevents"),
						"description" => wp_kses( __("Skip posts before select next part.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'unicaevents'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", "unicaevents"),
						"description" => wp_kses( __("Select desired posts sorting method", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'unicaevents'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "unicaevents"),
						"description" => wp_kses( __("Select desired posts order", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'unicaevents'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Team member's IDs list", "unicaevents"),
						"description" => wp_kses( __("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'unicaevents'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
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
					unicaevents_vc_width(),
					unicaevents_vc_height(),
					$UNICAEVENTS_GLOBALS['vc_params']['margin_top'],
					$UNICAEVENTS_GLOBALS['vc_params']['margin_bottom'],
					$UNICAEVENTS_GLOBALS['vc_params']['margin_left'],
					$UNICAEVENTS_GLOBALS['vc_params']['margin_right'],
					$UNICAEVENTS_GLOBALS['vc_params']['id'],
					$UNICAEVENTS_GLOBALS['vc_params']['class'],
					$UNICAEVENTS_GLOBALS['vc_params']['animation'],
					$UNICAEVENTS_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_team_item user="' . esc_html__( 'Member 1', 'unicaevents' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 2', 'unicaevents' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 4', 'unicaevents' ) . '"][/trx_team_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_team_item",
				"name" => esc_html__("Team member", "unicaevents"),
				"description" => wp_kses( __("Team member - all data pull out from it account on your site", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_team_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_team_item',
				"as_child" => array('only' => 'trx_team'),
				"as_parent" => array('except' => 'trx_team'),
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => esc_html__("Registered user", "unicaevents"),
						"description" => wp_kses( __("Select one of registered users (if present) or put name, position, etc. in fields below", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($users),
						"type" => "dropdown"
					),
					array(
						"param_name" => "member",
						"heading" => esc_html__("Team member", "unicaevents"),
						"description" => wp_kses( __("Select one of team members (if present) or put name, position, etc. in fields below", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($members),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "unicaevents"),
						"description" => wp_kses( __("Link on team member's personal page", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", "unicaevents"),
						"description" => wp_kses( __("Team member's name", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Position", "unicaevents"),
						"description" => wp_kses( __("Team member's position", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", "unicaevents"),
						"description" => wp_kses( __("Team member's e-mail", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Member's Photo", "unicaevents"),
						"description" => wp_kses( __("Team member's photo (avatar)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "socials",
						"heading" => esc_html__("Socials", "unicaevents"),
						"description" => wp_kses( __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$UNICAEVENTS_GLOBALS['vc_params']['id'],
					$UNICAEVENTS_GLOBALS['vc_params']['class'],
					$UNICAEVENTS_GLOBALS['vc_params']['animation'],
					$UNICAEVENTS_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Team extends UNICAEVENTS_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Team_Item extends UNICAEVENTS_VC_ShortCodeCollection {}

	}
}
?>
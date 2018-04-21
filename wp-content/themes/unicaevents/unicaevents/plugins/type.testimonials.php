<?php
/**
 * UnicaEvents Framework: Testimonial post type settings
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Theme init
if (!function_exists('unicaevents_testimonial_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_testimonial_theme_setup', 1 );
	function unicaevents_testimonial_theme_setup() {
	
		// Add item in the admin menu
		add_action('add_meta_boxes',		'unicaevents_testimonial_add_meta_box');

		// Save data from meta box
		add_action('save_post',				'unicaevents_testimonial_save_data');

		// Add shortcodes [trx_testimonials] and [trx_testimonials_item]
		add_action('unicaevents_action_shortcodes_list',		'unicaevents_testimonials_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_testimonials_reg_shortcodes_vc');

		// Meta box fields
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['testimonial_meta_box'] = array(
			'id' => 'testimonial-meta-box',
			'title' => esc_html__('Testimonial Details', 'unicaevents'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => esc_html__('Testimonial author',  'unicaevents'),
					"desc" => wp_kses( __("Name of the testimonial's author", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_position" => array(
					"title" => esc_html__("Author's position",  'unicaevents'),
					"desc" => wp_kses( __("Position of the testimonial's author", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => esc_html__("Author's e-mail",  'unicaevents'),
					"desc" => wp_kses( __("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => esc_html__('Testimonial link',  'unicaevents'),
					"desc" => wp_kses( __("URL of the testimonial source or author profile page", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
			)
		);
		
		// Prepare type "Testimonial"
		unicaevents_require_data( 'post_type', 'testimonial', array(
			'label'               => esc_html__( 'Testimonial', 'unicaevents' ),
			'description'         => esc_html__( 'Testimonial Description', 'unicaevents' ),
			'labels'              => array(
				'name'                => esc_html__( 'Testimonials', 'unicaevents' ),
				'singular_name'       => esc_html__( 'Testimonial', 'unicaevents' ),
				'menu_name'           => esc_html__( 'Testimonials', 'unicaevents' ),
				'parent_item_colon'   => esc_html__( 'Parent Item:', 'unicaevents' ),
				'all_items'           => esc_html__( 'All Testimonials', 'unicaevents' ),
				'view_item'           => esc_html__( 'View Item', 'unicaevents' ),
				'add_new_item'        => esc_html__( 'Add New Testimonial', 'unicaevents' ),
				'add_new'             => esc_html__( 'Add New', 'unicaevents' ),
				'edit_item'           => esc_html__( 'Edit Item', 'unicaevents' ),
				'update_item'         => esc_html__( 'Update Item', 'unicaevents' ),
				'search_items'        => esc_html__( 'Search Item', 'unicaevents' ),
				'not_found'           => esc_html__( 'Not found', 'unicaevents' ),
				'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'unicaevents' ),
			),
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail'),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'menu_icon'			  => 'dashicons-cloud',
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => '52.4',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			)
		);
		
		// Prepare taxonomy for testimonial
		unicaevents_require_data( 'taxonomy', 'testimonial_group', array(
			'post_type'			=> array( 'testimonial' ),
			'hierarchical'      => true,
			'labels'            => array(
				'name'              => esc_html__( 'Testimonials Group', 'unicaevents' ),
				'singular_name'     => esc_html__( 'Group', 'unicaevents' ),
				'search_items'      => esc_html__( 'Search Groups', 'unicaevents' ),
				'all_items'         => esc_html__( 'All Groups', 'unicaevents' ),
				'parent_item'       => esc_html__( 'Parent Group', 'unicaevents' ),
				'parent_item_colon' => esc_html__( 'Parent Group:', 'unicaevents' ),
				'edit_item'         => esc_html__( 'Edit Group', 'unicaevents' ),
				'update_item'       => esc_html__( 'Update Group', 'unicaevents' ),
				'add_new_item'      => esc_html__( 'Add New Group', 'unicaevents' ),
				'new_item_name'     => esc_html__( 'New Group Name', 'unicaevents' ),
				'menu_name'         => esc_html__( 'Testimonial Group', 'unicaevents' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'testimonial_group' ),
			)
		);
	}
}


// Add meta box
if (!function_exists('unicaevents_testimonial_add_meta_box')) {
	//add_action('add_meta_boxes', 'unicaevents_testimonial_add_meta_box');
	function unicaevents_testimonial_add_meta_box() {
		global $UNICAEVENTS_GLOBALS;
		$mb = $UNICAEVENTS_GLOBALS['testimonial_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'unicaevents_testimonial_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('unicaevents_testimonial_show_meta_box')) {
	function unicaevents_testimonial_show_meta_box() {
		global $post, $UNICAEVENTS_GLOBALS;

		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_testimonial_nonce" value="', esc_attr($UNICAEVENTS_GLOBALS['admin_nonce']), '" />';
		
		$data = get_post_meta($post->ID, 'testimonial_data', true);
	
		$fields = $UNICAEVENTS_GLOBALS['testimonial_meta_box']['fields'];
		?>
		<table class="testimonial_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_attr($field['desc']); ?></small></td>
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
if (!function_exists('unicaevents_testimonial_save_data')) {
	//add_action('save_post', 'unicaevents_testimonial_save_data');
	function unicaevents_testimonial_save_data($post_id) {
		global $UNICAEVENTS_GLOBALS;
		// verify nonce
		if (!isset($_POST['meta_box_testimonial_nonce']) || !wp_verify_nonce($_POST['meta_box_testimonial_nonce'], $UNICAEVENTS_GLOBALS['admin_url'])) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		$data = array();

		$fields = $UNICAEVENTS_GLOBALS['testimonial_meta_box']['fields'];

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				if (isset($_POST[$id])) 
					$data[$id] = stripslashes($_POST[$id]);
			}
		}

		update_post_meta($post_id, 'testimonial_data', $data);
	}
}






// ---------------------------------- [trx_testimonials] ---------------------------------------

/*
[trx_testimonials id="unique_id" style="1|2|3"]
	[trx_testimonials_item user="user_login"]Testimonials text[/trx_testimonials_item]
	[trx_testimonials_item email="" name="" position="" photo="photo_url"]Testimonials text[/trx_testimonials]
[/trx_testimonials]
*/

if (!function_exists('unicaevents_sc_testimonials')) {
	function unicaevents_sc_testimonials($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "testimonials-1",
			"columns" => 1,
			"slider" => "yes",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
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
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && unicaevents_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = unicaevents_get_scheme_color('bg');
			$rgb = unicaevents_hex2rgb($bg_color);
		}
		
		$class .= ($class ? ' ' : '') . unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = unicaevents_get_css_dimensions_from_values($width);
		$hs = unicaevents_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (unicaevents_param_is_off($custom) && $count < $columns) $columns = $count;
		
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_testimonials_id'] = $id;
		$UNICAEVENTS_GLOBALS['sc_testimonials_style'] = $style;
		$UNICAEVENTS_GLOBALS['sc_testimonials_columns'] = $columns;
		$UNICAEVENTS_GLOBALS['sc_testimonials_counter'] = 0;
		$UNICAEVENTS_GLOBALS['sc_testimonials_slider'] = $slider;
		$UNICAEVENTS_GLOBALS['sc_testimonials_css_wh'] = $ws . $hs;

		if (unicaevents_param_is_on($slider)) unicaevents_enqueue_slider('swiper');
	
		$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || unicaevents_strlen($bg_texture)>2 || ($scheme && !unicaevents_param_is_off($scheme) && !unicaevents_param_is_inherit($scheme))
					? '<div class="sc_testimonials_wrap sc_section'
							. ($scheme && !unicaevents_param_is_off($scheme) && !unicaevents_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
							. '"'
						.' style="'
							. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
							. '"'
						. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
						. '>'
						. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
								. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
									. (unicaevents_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
									. '"'
									. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
									. '>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_testimonials sc_testimonials_style_'.esc_attr($style)
 					. ' ' . esc_attr(unicaevents_get_template_property($style, 'container_classes'))
 					. (unicaevents_param_is_on($slider)
						? ' sc_slider_swiper swiper-slider-container'
							. ' ' . esc_attr(unicaevents_get_slider_controls_classes($controls))
							. (unicaevents_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
							. ($hs ? ' sc_slider_height_fixed' : '')
						: '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
					. '"'
				. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
				. (!empty($width) && unicaevents_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && unicaevents_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_testimonials_subtitle sc_item_subtitle">' . trim(unicaevents_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_testimonials_title sc_item_title">' . trim(unicaevents_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_testimonials_descr sc_item_descr">' . trim(unicaevents_strmacros($description)) . '</div>' : '')
			. (unicaevents_param_is_on($slider) 
				? '<div class="slides swiper-wrapper">' 
				: ($columns > 1 
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
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = unicaevents_query_add_sort_order($args, $orderby, $order);
			$args = unicaevents_query_add_posts_and_cats($args, $ids, 'testimonial', $cat, 'testimonial_group');
	
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
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = unicaevents_get_post_data($args);
				$post_data['post_content'] = wpautop($post_data['post_content']);	// Add <p> around text and paragraphs. Need separate call because 'content'=>false (see above)
				$post_meta = get_post_meta($post_data['post_id'], 'testimonial_data', true);
				$thumb_sizes = unicaevents_get_thumb_sizes(array('layout' => $style));
				$args['author'] = $post_meta['testimonial_author'];
				$args['position'] = $post_meta['testimonial_position'];
				$args['link'] = !empty($post_meta['testimonial_link']) ? $post_meta['testimonial_link'] : '';	//$post_data['post_link'];
				$args['email'] = $post_meta['testimonial_email'];
				$args['photo'] = $post_data['post_thumb'];
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*min(2, max(1, unicaevents_get_theme_option("retina_ready"))));
				$output .= unicaevents_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}
	
		if (unicaevents_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || unicaevents_strlen($bg_texture)>2
						?  '</div></div>'
						: '');
	
		// Add template specific scripts and styles
		do_action('unicaevents_action_blog_scripts', $style);

		return apply_filters('unicaevents_shortcode_output', $output, 'trx_testimonials', $atts, $content);
	}
	unicaevents_require_shortcode('trx_testimonials', 'unicaevents_sc_testimonials');
}
	
	
if (!function_exists('unicaevents_sc_testimonials_item')) {
	function unicaevents_sc_testimonials_item($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"author" => "",
			"position" => "",
			"link" => "",
			"photo" => "",
			"email" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
		), $atts)));

		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_testimonials_counter']++;
	
		$id = $id ? $id : ($UNICAEVENTS_GLOBALS['sc_testimonials_id'] ? $UNICAEVENTS_GLOBALS['sc_testimonials_id'] . '_' . $UNICAEVENTS_GLOBALS['sc_testimonials_counter'] : '');
	
		$thumb_sizes = unicaevents_get_thumb_sizes(array('layout' => $UNICAEVENTS_GLOBALS['sc_testimonials_style']));

		if (empty($photo)) {
			if (!empty($email))
				$photo = get_avatar($email, $thumb_sizes['w']*min(2, max(1, unicaevents_get_theme_option("retina_ready"))));
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = unicaevents_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}

		$post_data = array(
			'post_content' => do_shortcode($content)
		);
		$args = array(
			'layout' => $UNICAEVENTS_GLOBALS['sc_testimonials_style'],
			'number' => $UNICAEVENTS_GLOBALS['sc_testimonials_counter'],
			'columns_count' => $UNICAEVENTS_GLOBALS['sc_testimonials_columns'],
			'slider' => $UNICAEVENTS_GLOBALS['sc_testimonials_slider'],
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => '',
			'tag_css' => $css,
			'tag_css_wh' => $UNICAEVENTS_GLOBALS['sc_testimonials_css_wh'],
			'author' => $author,
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo
		);
		$output = unicaevents_show_post_layout($args, $post_data);

		return apply_filters('unicaevents_shortcode_output', $output, 'trx_testimonials_item', $atts, $content);
	}
	unicaevents_require_shortcode('trx_testimonials_item', 'unicaevents_sc_testimonials_item');
}
// ---------------------------------- [/trx_testimonials] ---------------------------------------



// Add [trx_testimonials] and [trx_testimonials_item] in the shortcodes list
if (!function_exists('unicaevents_testimonials_reg_shortcodes')) {
	//add_filter('unicaevents_action_shortcodes_list',	'unicaevents_testimonials_reg_shortcodes');
	function unicaevents_testimonials_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
		if (isset($UNICAEVENTS_GLOBALS['shortcodes'])) {

			$testimonials_groups = unicaevents_get_list_terms(false, 'testimonial_group');
			$testimonials_styles = unicaevents_get_list_templates('testimonials');
			$controls = unicaevents_get_list_slider_controls();

			unicaevents_array_insert_before($UNICAEVENTS_GLOBALS['shortcodes'], 'trx_title', array(
			
				// Testimonials
				"trx_testimonials" => array(
					"title" => esc_html__("Testimonials", "unicaevents"),
					"desc" => wp_kses( __("Insert testimonials into post (page)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
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
							"title" => esc_html__("Testimonials style", "unicaevents"),
							"desc" => wp_kses( __("Select style to display testimonials", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"value" => "testimonials-1",
							"type" => "select",
							"options" => $testimonials_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", "unicaevents"),
							"desc" => wp_kses( __("How many columns use to show testimonials", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slider" => array(
							"title" => esc_html__("Slider", "unicaevents"),
							"desc" => wp_kses( __("Use slider to show testimonials", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"value" => "yes",
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
							"desc" => wp_kses( __("Alignment of the testimonials block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "unicaevents"),
							"desc" => wp_kses( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", "unicaevents"),
							"desc" => wp_kses( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => unicaevents_array_merge(array(0 => esc_html__('- Select category -', 'unicaevents')), $testimonials_groups)
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
							"value" => "date",
							"type" => "select",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "unicaevents"),
							"desc" => wp_kses( __("Select desired posts order", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
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
						"scheme" => array(
							"title" => esc_html__("Color scheme", "unicaevents"),
							"desc" => wp_kses( __("Select color scheme for this block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "checklist",
							"options" => $UNICAEVENTS_GLOBALS['sc_params']['schemes']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "unicaevents"),
							"desc" => wp_kses( __("Any background color for this section", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "unicaevents"),
							"desc" => wp_kses( __("Select or upload image or write URL from other site for the background", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "unicaevents"),
							"desc" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "unicaevents"),
							"desc" => wp_kses( __("Predefined texture style from 1 to 11. 0 - without texture.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
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
						"name" => "trx_testimonials_item",
						"title" => esc_html__("Item", "unicaevents"),
						"desc" => wp_kses( __("Testimonials item (custom parameters)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => esc_html__("Author", "unicaevents"),
								"desc" => wp_kses( __("Name of the testimonmials author", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", "unicaevents"),
								"desc" => wp_kses( __("Link URL to the testimonmials author page", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", "unicaevents"),
								"desc" => wp_kses( __("E-mail of the testimonmials author (to get gravatar)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", "unicaevents"),
								"desc" => wp_kses( __("Select or upload photo of testimonmials author or write URL of photo from other site", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Testimonials text", "unicaevents"),
								"desc" => wp_kses( __("Current testimonials text", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $UNICAEVENTS_GLOBALS['sc_params']['id'],
							"class" => $UNICAEVENTS_GLOBALS['sc_params']['class'],
							"css" => $UNICAEVENTS_GLOBALS['sc_params']['css']
						)
					)
				)

			));
		}
	}
}


// Add [trx_testimonials] and [trx_testimonials_item] in the VC shortcodes list
if (!function_exists('unicaevents_testimonials_reg_shortcodes_vc')) {
	//add_filter('unicaevents_action_shortcodes_list_vc',	'unicaevents_testimonials_reg_shortcodes_vc');
	function unicaevents_testimonials_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;

		$testimonials_groups = unicaevents_get_list_terms(false, 'testimonial_group');
		$testimonials_styles = unicaevents_get_list_templates('testimonials');
		$controls			 = unicaevents_get_list_slider_controls();
			
		// Testimonials			
		vc_map( array(
				"base" => "trx_testimonials",
				"name" => esc_html__("Testimonials", "unicaevents"),
				"description" => wp_kses( __("Insert testimonials slider", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_columns trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Testimonials style", "unicaevents"),
						"description" => wp_kses( __("Select style to display testimonials", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($testimonials_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", "unicaevents"),
						"description" => wp_kses( __("Use slider to show testimonials", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'unicaevents'),
						"class" => "",
						"std" => "yes",
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
						"description" => wp_kses( __("Alignment of the testimonials block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "unicaevents"),
						"description" => wp_kses( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
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
						"description" => wp_kses( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'unicaevents'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(unicaevents_array_merge(array(0 => esc_html__('- Select category -', 'unicaevents')), $testimonials_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "unicaevents"),
						"description" => wp_kses( __("How many columns use to show testimonials", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'unicaevents'),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
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
						"heading" => esc_html__("Post IDs list", "unicaevents"),
						"description" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
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
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "unicaevents"),
						"description" => wp_kses( __("Select color scheme for this block", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Colors and Images', 'unicaevents'),
						"class" => "",
						"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "unicaevents"),
						"description" => wp_kses( __("Any background color for this section", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Colors and Images', 'unicaevents'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "unicaevents"),
						"description" => wp_kses( __("Select background image from library for this section", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Colors and Images', 'unicaevents'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "unicaevents"),
						"description" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Colors and Images', 'unicaevents'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "unicaevents"),
						"description" => wp_kses( __("Texture style from 1 to 11. Empty or 0 - without texture.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Colors and Images', 'unicaevents'),
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
				'js_view' => 'VcTrxColumnsView'
		) );
			
			
		vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => esc_html__("Testimonial", "unicaevents"),
				"description" => wp_kses( __("Single testimonials item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", "unicaevents"),
						"description" => wp_kses( __("Name of the testimonmials author", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "unicaevents"),
						"description" => wp_kses( __("Link URL to the testimonmials author page", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", "unicaevents"),
						"description" => wp_kses( __("E-mail of the testimonmials author", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Photo", "unicaevents"),
						"description" => wp_kses( __("Select or upload photo of testimonmials author or write URL of photo from other site", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Testimonials text", "unicaevents"),
						"description" => wp_kses( __("Current testimonials text", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$UNICAEVENTS_GLOBALS['vc_params']['id'],
					$UNICAEVENTS_GLOBALS['vc_params']['class'],
					$UNICAEVENTS_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
		) );
			
		class WPBakeryShortCode_Trx_Testimonials extends UNICAEVENTS_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Testimonials_Item extends UNICAEVENTS_VC_ShortCodeCollection {}
		
	}
}
?>
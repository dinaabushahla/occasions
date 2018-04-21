<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('unicaevents_sc_slider_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_slider_theme_setup' );
	function unicaevents_sc_slider_theme_setup() {
		add_action('unicaevents_action_shortcodes_list', 		'unicaevents_sc_slider_reg_shortcodes');
		if (function_exists('unicaevents_exists_visual_composer') && unicaevents_exists_visual_composer())
			add_action('unicaevents_action_shortcodes_list_vc','unicaevents_sc_slider_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_slider id="unique_id" engine="revo|royal|flex|swiper|chop" alias="revolution_slider_alias|royal_slider_id" titles="no|slide|fixed" cat="id|slug" count="posts_number" ids="comma_separated_id_list" offset="" width="" height="" align="" top="" bottom=""]
[trx_slider_item src="image_url"]
[/trx_slider]
*/

if (!function_exists('unicaevents_sc_slider')) {	
	function unicaevents_sc_slider($atts, $content=null){	
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts(array(
			// Individual params
			"engine" => 'swiper',
			"custom" => "no",
			"alias" => "",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "0",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"controls" => "no",
			"pagination" => "no",
			"slides_space" => 0,
			"slides_per_view" => 1,
			"titles" => "no",
			"descriptions" => unicaevents_get_custom_option('slider_info_descriptions'),
			"links" => "no",
			"align" => "",
			"interval" => "",
			"date_format" => "",
			"crop" => "yes",
			"autoheight" => "no",
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

		if (empty($width) && $pagination!='full') $width = "100%";
		if (empty($height) && ($pagination=='full' || $pagination=='over')) $height = 250;
		if (!empty($height) && unicaevents_param_is_on($autoheight)) $autoheight = "off";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		if (empty($custom)) $custom = 'no';
		if (empty($controls)) $controls = 'no';
		if (empty($pagination)) $pagination = 'no';
		if (empty($titles)) $titles = 'no';
		if (empty($links)) $links = 'no';
		if (empty($autoheight)) $autoheight = 'no';
		if (empty($crop)) $crop = 'no';

		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['sc_slider_engine'] = $engine;
		$UNICAEVENTS_GLOBALS['sc_slider_width']  = unicaevents_prepare_css_value($width);
		$UNICAEVENTS_GLOBALS['sc_slider_height'] = unicaevents_prepare_css_value($height);
		$UNICAEVENTS_GLOBALS['sc_slider_links']  = unicaevents_param_is_on($links);
		$UNICAEVENTS_GLOBALS['sc_slider_bg_image'] = unicaevents_get_theme_setting('slides_type')=='bg';
		$UNICAEVENTS_GLOBALS['sc_slider_crop_image'] = $crop;
	
		if (empty($id)) $id = "sc_slider_".str_replace('.', '', mt_rand());
		
		$class2 = unicaevents_get_css_position_as_classes($top, $right, $bottom, $left);
		$ws = unicaevents_get_css_dimensions_from_values($width);
		$hs = unicaevents_get_css_dimensions_from_values('', $height);
	
		$css .= ($hs) . ($ws);
		
		if ($engine!='swiper' && in_array($pagination, array('full', 'over'))) $pagination = 'yes';
		
		$output = (in_array($pagination, array('full', 'over')) 
					? '<div class="sc_slider_pagination_area sc_slider_pagination_'.esc_attr($pagination)
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class2 ? ' '.esc_attr($class2) : '')
							. '"'
						. (!unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
						. ($hs ? ' style="'.esc_attr($hs).'"' : '') 
						.'>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_slider sc_slider_' . esc_attr($engine)
					. ($engine=='swiper' ? ' swiper-slider-container' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (unicaevents_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
					. ($hs ? ' sc_slider_height_fixed' : '')
					. (unicaevents_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
					. (unicaevents_param_is_on($pagination) ? ' sc_slider_pagination' : ' sc_slider_nopagination')
					. ($UNICAEVENTS_GLOBALS['sc_slider_bg_image'] ? ' sc_slider_bg' : ' sc_slider_images')
					. (!in_array($pagination, array('full', 'over')) 
							? ($class2 ? ' '.esc_attr($class2) : '') . ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							: '')
					. '"'
				. (!in_array($pagination, array('full', 'over')) && !unicaevents_param_is_off($animation) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($animation)).'"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
				. ($slides_per_view > 1 ? ' data-slides-per_view="' . esc_attr($slides_per_view) . '"' : '')
				. (!empty($width) && unicaevents_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && unicaevents_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>';
	
		unicaevents_enqueue_slider($engine);
	
		if ($engine=='revo') {
			if (!empty($alias))
				$output .= do_shortcode('[rev_slider '.esc_attr($alias).']');
			else
				$output = '';
		} else if ($engine=='swiper') {
			
			$caption = '';
	
			$output .= '<div class="slides'
				.($engine=='swiper' ? ' swiper-wrapper' : '').'"'
				.($engine=='swiper' && $UNICAEVENTS_GLOBALS['sc_slider_bg_image'] ? ' style="'.esc_attr($hs).'"' : '')
				.'>';
	
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
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => $count,
					'ignore_sticky_posts' => true,
					'order' => $order=='asc' ? 'asc' : 'desc',
				);
		
				if ($offset > 0 && empty($ids)) {
					$args['offset'] = $offset;
				}
		
				$args = unicaevents_query_add_sort_order($args, $orderby, $order);
				$args = unicaevents_query_add_filters($args, 'thumbs');
				$args = unicaevents_query_add_posts_and_cats($args, $ids, $post_type, $cat);
	
				$query = new WP_Query( $args );
	
				$post_number = 0;
				$pagination_items = '';
				$show_image 	= 1;
				$show_types 	= 0;
				$show_date 		= 1;
				$show_author 	= 0;
				$show_links 	= 0;
				$show_counters	= 'views';	//comments | rating
				
				while ( $query->have_posts() ) { 
					$query->the_post();
					$post_number++;
					$post_id = get_the_ID();
					$post_type = get_post_type();
					$post_title = get_the_title();
					$post_link = get_permalink();
					$post_date = get_the_date(!empty($date_format) ? $date_format : 'd.m.y');
					$post_attachment = wp_get_attachment_url(get_post_thumbnail_id($post_id));
					if (unicaevents_param_is_on($crop)) {
						$post_attachment = $UNICAEVENTS_GLOBALS['sc_slider_bg_image']
							? unicaevents_get_resized_image_url($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null)
							: unicaevents_get_resized_image_tag($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null);
					} else if (!$UNICAEVENTS_GLOBALS['sc_slider_bg_image']) {
						$post_attachment = '<img src="'.esc_url($post_attachment).'" alt="">';
					}
					$post_accent_color = '';
					$post_category = '';
					$post_category_link = '';
	
					if (in_array($pagination, array('full', 'over'))) {
						$old_output = $output;
						$output = '';
						if (file_exists(unicaevents_get_file_dir('templates/_parts/widgets-posts.php'))) {
							require unicaevents_get_file_dir('templates/_parts/widgets-posts.php');
						}
						$pagination_items .= $output;
						$output = $old_output;
					}
					$output .= '<div' 
						. ' class="'.esc_attr($engine).'-slide"'
						. ' data-style="'.esc_attr(($ws).($hs)).'"'
						. ' style="'
							. ($UNICAEVENTS_GLOBALS['sc_slider_bg_image'] ? 'background-image:url(' . esc_url($post_attachment) . ');' : '') . ($ws) . ($hs)
							. '"'
						. '>' 
						. (unicaevents_param_is_on($links) ? '<a href="'.esc_url($post_link).'" title="'.esc_attr($post_title).'">' : '')
						. (!$UNICAEVENTS_GLOBALS['sc_slider_bg_image'] ? $post_attachment : '')
						;
					$caption = $engine=='swiper' ? '' : $caption;
					if (!unicaevents_param_is_off($titles)) {
						$post_hover_bg  = unicaevents_get_scheme_color('accent1');
						$post_bg = '';
						if ($post_hover_bg!='' && !unicaevents_is_inherit_option($post_hover_bg)) {
							$rgb = unicaevents_hex2rgb($post_hover_bg);
							$post_hover_ie = str_replace('#', '', $post_hover_bg);
							$post_bg = "background-color: rgba({$rgb['r']},{$rgb['g']},{$rgb['b']},0.8);";
						}
						$caption .= '<div class="sc_slider_info' . ($titles=='fixed' ? ' sc_slider_info_fixed' : '') . ($engine=='swiper' ? ' content-slide' : '') . '"'.($post_bg!='' ? ' style="'.esc_attr($post_bg).'"' : '').'>';
						$post_descr = unicaevents_get_post_excerpt();
						if (unicaevents_get_custom_option("slider_info_category")=='yes') { // || empty($cat)) {
							// Get all post's categories
							$post_tax = unicaevents_get_taxonomy_categories_by_post_type($post_type);
							if (!empty($post_tax)) {
								$post_terms = unicaevents_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$post_tax));
								if (!empty($post_terms[$post_tax])) {
									if (!empty($post_terms[$post_tax]->closest_parent)) {
										$post_category = $post_terms[$post_tax]->closest_parent->name;
										$post_category_link = $post_terms[$post_tax]->closest_parent->link;
									}
									if ($post_category!='') {
										$caption .= '<div class="sc_slider_category"'.(unicaevents_substr($post_accent_color, 0, 1)=='#' ? ' style="background-color: '.esc_attr($post_accent_color).'"' : '').'><a href="'.esc_url($post_category_link).'">'.($post_category).'</a></div>';
									}
								}
							}
						}
						$output_reviews = '';
						if (unicaevents_get_custom_option('show_reviews')=='yes' && unicaevents_get_custom_option('slider_info_reviews')=='yes') {
							$avg_author = unicaevents_reviews_marks_to_display(get_post_meta($post_id, 'reviews_avg'.((unicaevents_get_theme_option('reviews_first')=='author' && $orderby != 'users_rating') || $orderby == 'author_rating' ? '' : '2'), true));
							if ($avg_author > 0) {
								$output_reviews .= '<div class="sc_slider_reviews post_rating reviews_summary blog_reviews' . (unicaevents_get_custom_option("slider_info_category")=='yes' ? ' after_category' : '') . '">'
									. '<div class="criteria_summary criteria_row">' . trim(unicaevents_reviews_get_summary_stars($avg_author, false, false, 5)) . '</div>'
									. '</div>';
							}
						}
						if (unicaevents_get_custom_option("slider_info_category")=='yes') $caption .= $output_reviews;
						$caption .= '<h3 class="sc_slider_subtitle"><a href="'.esc_url($post_link).'">'.($post_title).'</a></h3>';
						if (unicaevents_get_custom_option("slider_info_category")!='yes') $caption .= $output_reviews;
						if ($descriptions > 0) {
							$caption .= '<div class="sc_slider_descr">'.trim(unicaevents_strshort($post_descr, $descriptions)).'</div>';
						}
						$caption .= '</div>';
					}
					$output .= ($engine=='swiper' ? $caption : '') . (unicaevents_param_is_on($links) ? '</a>' : '' ) . '</div>';
				}
				wp_reset_postdata();
			}
	
			$output .= '</div>';
			if ($engine=='swiper') {
				if (unicaevents_param_is_on($controls))
					$output .= '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>';
				if (unicaevents_param_is_on($pagination))
					$output .= '<div class="sc_slider_pagination_wrap"></div>';
			}
		
		} else
			$output = '';
		
		if (!empty($output)) {
			$output .= '</div>';
			if (!empty($pagination_items)) {
				$output .= '
					<div class="sc_slider_pagination widget_area"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
						<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical swiper-slider-container scroll-container"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
							<div class="sc_scroll_wrapper swiper-wrapper">
								<div class="sc_scroll_slide swiper-slide">
									'.($pagination_items).'
								</div>
							</div>
							<div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical"></div>
						</div>
					</div>';
				$output .= '</div>';
			}
		}
	
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_slider', $atts, $content);
	}
	unicaevents_require_shortcode('trx_slider', 'unicaevents_sc_slider');
}


if (!function_exists('unicaevents_sc_slider_item')) {	
	function unicaevents_sc_slider_item($atts, $content=null) {
		if (unicaevents_in_shortcode_blogger()) return '';
		extract(unicaevents_html_decode(shortcode_atts( array(
			// Individual params
			"src" => "",
			"url" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $UNICAEVENTS_GLOBALS;
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
	
		if ($src && unicaevents_param_is_on($UNICAEVENTS_GLOBALS['sc_slider_crop_image'])) {
			$src = $UNICAEVENTS_GLOBALS['sc_slider_bg_image']
				? unicaevents_get_resized_image_url($src, !empty($UNICAEVENTS_GLOBALS['sc_slider_width']) && unicaevents_strpos($UNICAEVENTS_GLOBALS['sc_slider_width'], '%')===false ? $UNICAEVENTS_GLOBALS['sc_slider_width'] : null, !empty($UNICAEVENTS_GLOBALS['sc_slider_height']) && unicaevents_strpos($UNICAEVENTS_GLOBALS['sc_slider_height'], '%')===false ? $UNICAEVENTS_GLOBALS['sc_slider_height'] : null)
				: unicaevents_get_resized_image_tag($src, !empty($UNICAEVENTS_GLOBALS['sc_slider_width']) && unicaevents_strpos($UNICAEVENTS_GLOBALS['sc_slider_width'], '%')===false ? $UNICAEVENTS_GLOBALS['sc_slider_width'] : null, !empty($UNICAEVENTS_GLOBALS['sc_slider_height']) && unicaevents_strpos($UNICAEVENTS_GLOBALS['sc_slider_height'], '%')===false ? $UNICAEVENTS_GLOBALS['sc_slider_height'] : null);
		} else if ($src && !$UNICAEVENTS_GLOBALS['sc_slider_bg_image']) {
			$src = '<img src="'.esc_url($src).'" alt="">';
		}
	
		$css .= ($UNICAEVENTS_GLOBALS['sc_slider_bg_image'] ? 'background-image:url(' . esc_url($src) . ');' : '')
				. (!empty($UNICAEVENTS_GLOBALS['sc_slider_width'])  ? 'width:'  . esc_attr($UNICAEVENTS_GLOBALS['sc_slider_width'])  . ';' : '')
				. (!empty($UNICAEVENTS_GLOBALS['sc_slider_height']) ? 'height:' . esc_attr($UNICAEVENTS_GLOBALS['sc_slider_height']) . ';' : '');
	
		$content = do_shortcode($content);
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '').' class="'.esc_attr($UNICAEVENTS_GLOBALS['sc_slider_engine']).'-slide' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				.'>' 
				. ($src && unicaevents_param_is_on($UNICAEVENTS_GLOBALS['sc_slider_links']) ? '<a href="'.esc_url($src).'">' : '')
				. ($src && !$UNICAEVENTS_GLOBALS['sc_slider_bg_image'] ? $src : $content)
				. ($src && unicaevents_param_is_on($UNICAEVENTS_GLOBALS['sc_slider_links']) ? '</a>' : '')
			. '</div>';
		return apply_filters('unicaevents_shortcode_output', $output, 'trx_slider_item', $atts, $content);
	}
	unicaevents_require_shortcode('trx_slider_item', 'unicaevents_sc_slider_item');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'unicaevents_sc_slider_reg_shortcodes' ) ) {
	//add_action('unicaevents_action_shortcodes_list', 'unicaevents_sc_slider_reg_shortcodes');
	function unicaevents_sc_slider_reg_shortcodes() {
		global $UNICAEVENTS_GLOBALS;
	
		$UNICAEVENTS_GLOBALS['shortcodes']["trx_slider"] = array(
			"title" => esc_html__("Slider", "unicaevents"),
			"desc" => wp_kses( __("Insert slider into your post (page)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array_merge(array(
				"engine" => array(
					"title" => esc_html__("Slider engine", "unicaevents"),
					"desc" => wp_kses( __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => "swiper",
					"type" => "checklist",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['sliders']
				),
				"align" => array(
					"title" => esc_html__("Float slider", "unicaevents"),
					"desc" => wp_kses( __("Float slider to left or right side", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['float']
				),
				"custom" => array(
					"title" => esc_html__("Custom slides", "unicaevents"),
					"desc" => wp_kses( __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
				)
				),
				function_exists('unicaevents_exists_revslider') && unicaevents_exists_revslider() ? array(
				"alias" => array(
					"title" => esc_html__("Revolution slider alias", "unicaevents"),
					"desc" => wp_kses( __("Select Revolution slider to display", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('revo')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['revo_sliders']
				)) : array(), array(
				"cat" => array(
					"title" => esc_html__("Swiper: Category list", "unicaevents"),
					"desc" => wp_kses( __("Select category to show post's images. If empty - select posts from any category or from IDs list", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"style" => "list",
					"multiple" => true,
					"options" => unicaevents_array_merge(array(0 => esc_html__('- Select category -', 'unicaevents')), $UNICAEVENTS_GLOBALS['sc_params']['categories'])
				),
				"count" => array(
					"title" => esc_html__("Swiper: Number of posts", "unicaevents"),
					"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 3,
					"min" => 1,
					"max" => 100,
					"type" => "spinner"
				),
				"offset" => array(
					"title" => esc_html__("Swiper: Offset before select posts", "unicaevents"),
					"desc" => wp_kses( __("Skip posts before select next part.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 0,
					"min" => 0,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Swiper: Post order by", "unicaevents"),
					"desc" => wp_kses( __("Select desired posts sorting method", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "date",
					"type" => "select",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['sorting']
				),
				"order" => array(
					"title" => esc_html__("Swiper: Post order", "unicaevents"),
					"desc" => wp_kses( __("Select desired posts order", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['ordering']
				),
				"ids" => array(
					"title" => esc_html__("Swiper: Post IDs list", "unicaevents"),
					"desc" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Swiper: Show slider controls", "unicaevents"),
					"desc" => wp_kses( __("Show arrows inside slider", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
				),
				"pagination" => array(
					"title" => esc_html__("Swiper: Show slider pagination", "unicaevents"),
					"desc" => wp_kses( __("Show bullets for switch slides", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "no",
					"type" => "checklist",
					"options" => array(
						'no'   => esc_html__('None', 'unicaevents'),
						'yes'  => esc_html__('Dots', 'unicaevents'), 
						'full' => esc_html__('Side Titles', 'unicaevents'),
						'over' => esc_html__('Over Titles', 'unicaevents')
					)
				),
				"titles" => array(
					"title" => esc_html__("Swiper: Show titles section", "unicaevents"),
					"desc" => wp_kses( __("Show section with post's title and short post's description", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "no",
					"type" => "checklist",
					"options" => array(
						"no"    => esc_html__('Not show', 'unicaevents'),
						"slide" => esc_html__('Show/Hide info', 'unicaevents'),
						"fixed" => esc_html__('Fixed info', 'unicaevents')
					)
				),
				"descriptions" => array(
					"title" => esc_html__("Swiper: Post descriptions", "unicaevents"),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"desc" => wp_kses( __("Show post's excerpt max length (characters)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"value" => 0,
					"min" => 0,
					"max" => 1000,
					"step" => 10,
					"type" => "spinner"
				),
				"links" => array(
					"title" => esc_html__("Swiper: Post's title as link", "unicaevents"),
					"desc" => wp_kses( __("Make links from post's titles", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
				),
				"crop" => array(
					"title" => esc_html__("Swiper: Crop images", "unicaevents"),
					"desc" => wp_kses( __("Crop images in each slide or live it unchanged", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
				),
				"autoheight" => array(
					"title" => esc_html__("Swiper: Autoheight", "unicaevents"),
					"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['sc_params']['yes_no']
				),
				"slides_per_view" => array(
					"title" => esc_html__("Swiper: Slides per view", "unicaevents"),
					"desc" => wp_kses( __("Slides per view showed in this slider", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 1,
					"min" => 1,
					"max" => 6,
					"step" => 1,
					"type" => "spinner"
				),
				"slides_space" => array(
					"title" => esc_html__("Swiper: Space between slides", "unicaevents"),
					"desc" => wp_kses( __("Size of space (in px) between slides", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 0,
					"min" => 0,
					"max" => 100,
					"step" => 10,
					"type" => "spinner"
				),
				"interval" => array(
					"title" => esc_html__("Swiper: Slides change interval", "unicaevents"),
					"desc" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 5000,
					"step" => 500,
					"min" => 0,
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
			)),
			"children" => array(
				"name" => "trx_slider_item",
				"title" => esc_html__("Slide", "unicaevents"),
				"desc" => wp_kses( __("Slider item", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
				"container" => false,
				"params" => array(
					"src" => array(
						"title" => esc_html__("URL (source) for image file", "unicaevents"),
						"desc" => wp_kses( __("Select or upload image or write URL from other site for the current slide", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
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
if ( !function_exists( 'unicaevents_sc_slider_reg_shortcodes_vc' ) ) {
	//add_action('unicaevents_action_shortcodes_list_vc', 'unicaevents_sc_slider_reg_shortcodes_vc');
	function unicaevents_sc_slider_reg_shortcodes_vc() {
		global $UNICAEVENTS_GLOBALS;
	
		vc_map( array(
			"base" => "trx_slider",
			"name" => esc_html__("Slider", "unicaevents"),
			"description" => wp_kses( __("Insert slider", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_slider',
			"class" => "trx_sc_collection trx_sc_slider",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_slider_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "engine",
					"heading" => esc_html__("Engine", "unicaevents"),
					"description" => wp_kses( __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['sliders']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Float slider", "unicaevents"),
					"description" => wp_kses( __("Float slider to left or right side", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['float']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom slides", "unicaevents"),
					"description" => wp_kses( __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(esc_html__('Custom slides', 'unicaevents') => 'yes'),
					"type" => "checkbox"
				)
				),
				function_exists('unicaevents_exists_revslider') && unicaevents_exists_revslider() ? array(
				array(
					"param_name" => "alias",
					"heading" => esc_html__("Revolution slider alias", "unicaevents"),
					"description" => wp_kses( __("Select Revolution slider to display", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'engine',
						'value' => array('revo')
					),
					"value" => array_flip(unicaevents_array_merge(array('none' => esc_html__('- Select slider -', 'unicaevents')), $UNICAEVENTS_GLOBALS['sc_params']['revo_sliders'])),
					"type" => "dropdown"
				)) : array(), array(
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories list", "unicaevents"),
					"description" => wp_kses( __("Select category. If empty - show posts from any category or from IDs list", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(unicaevents_array_merge(array(0 => esc_html__('- Select category -', 'unicaevents')), $UNICAEVENTS_GLOBALS['sc_params']['categories'])),
					"type" => "dropdown"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Swiper: Number of posts", "unicaevents"),
					"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "3",
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Swiper: Offset before select posts", "unicaevents"),
					"description" => wp_kses( __("Skip posts before select next part.", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Swiper: Post sorting", "unicaevents"),
					"description" => wp_kses( __("Select desired posts sorting method", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['sorting']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Swiper: Post order", "unicaevents"),
					"description" => wp_kses( __("Select desired posts order", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip($UNICAEVENTS_GLOBALS['sc_params']['ordering']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("Swiper: Post IDs list", "unicaevents"),
					"description" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Swiper: Show slider controls", "unicaevents"),
					"description" => wp_kses( __("Show arrows inside slider", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Details', 'unicaevents'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Show controls', 'unicaevents') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pagination",
					"heading" => esc_html__("Swiper: Show slider pagination", "unicaevents"),
					"description" => wp_kses( __("Show bullets or titles to switch slides", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Details', 'unicaevents'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"std" => "no",
					"value" => array(
							esc_html__('None', 'unicaevents') => 'no',
							esc_html__('Dots', 'unicaevents') => 'yes', 
							esc_html__('Side Titles', 'unicaevents') => 'full',
							esc_html__('Over Titles', 'unicaevents') => 'over'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "titles",
					"heading" => esc_html__("Swiper: Show titles section", "unicaevents"),
					"description" => wp_kses( __("Show section with post's title and short post's description", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Details', 'unicaevents'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(
							esc_html__('Not show', 'unicaevents') => "no",
							esc_html__('Show/Hide info', 'unicaevents') => "slide",
							esc_html__('Fixed info', 'unicaevents') => "fixed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "descriptions",
					"heading" => esc_html__("Swiper: Post descriptions", "unicaevents"),
					"description" => wp_kses( __("Show post's excerpt max length (characters)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Details', 'unicaevents'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "links",
					"heading" => esc_html__("Swiper: Post's title as link", "unicaevents"),
					"description" => wp_kses( __("Make links from post's titles", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Details', 'unicaevents'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Titles as a links', 'unicaevents') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "crop",
					"heading" => esc_html__("Swiper: Crop images", "unicaevents"),
					"description" => wp_kses( __("Crop images in each slide or live it unchanged", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Details', 'unicaevents'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Crop images', 'unicaevents') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Swiper: Autoheight", "unicaevents"),
					"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Details', 'unicaevents'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Autoheight', 'unicaevents') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "slides_per_view",
					"heading" => esc_html__("Swiper: Slides per view", "unicaevents"),
					"description" => wp_kses( __("Slides per view showed in this slider", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Details', 'unicaevents'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "1",
					"type" => "textfield"
				),
				array(
					"param_name" => "slides_space",
					"heading" => esc_html__("Swiper: Space between slides", "unicaevents"),
					"description" => wp_kses( __("Size of space (in px) between slides", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Details', 'unicaevents'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Swiper: Slides change interval", "unicaevents"),
					"description" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Details', 'unicaevents'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "5000",
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
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_slider_item",
			"name" => esc_html__("Slide", "unicaevents"),
			"description" => wp_kses( __("Slider item - single slide", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_slider_item',
			"class" => "trx_sc_single trx_sc_slider_item",
			"as_child" => array('only' => 'trx_slider'),
			"as_parent" => array('except' => 'trx_slider'),
			"params" => array(
				array(
					"param_name" => "src",
					"heading" => esc_html__("URL (source) for image file", "unicaevents"),
					"description" => wp_kses( __("Select or upload image or write URL from other site for the current slide", "unicaevents"), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				$UNICAEVENTS_GLOBALS['vc_params']['id'],
				$UNICAEVENTS_GLOBALS['vc_params']['class'],
				$UNICAEVENTS_GLOBALS['vc_params']['css']
			)
		) );
		
		class WPBakeryShortCode_Trx_Slider extends UNICAEVENTS_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Slider_Item extends UNICAEVENTS_VC_ShortCodeSingle {}
	}
}
?>
<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_masonry_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_masonry_theme_setup', 1 );
	function unicaevents_template_masonry_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'masonry_2',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile (different height) /2 columns/', 'unicaevents'),
			'thumb_title'  => esc_html__('Large image', 'unicaevents'),
			'w'		 => 870,
			'h_crop' => 490,
			'h'      => null
		));
		unicaevents_add_template(array(
			'layout' => 'masonry_3',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile /3 columns/', 'unicaevents'),
			'thumb_title'  => esc_html__('Medium image', 'unicaevents'),
			'w'		 => 390,
			'h_crop' => 220,
			'h'      => null
		));
		unicaevents_add_template(array(
			'layout' => 'masonry_4',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile /4 columns/', 'unicaevents'),
			'thumb_title'  => esc_html__('Small image', 'unicaevents'),
			'w'		 => 300,
			'h_crop' => 170,
			'h'      => null
		));
		unicaevents_add_template(array(
			'layout' => 'classic_2',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Classic tile (equal height) /2 columns/', 'unicaevents'),
			'thumb_title'  => esc_html__('Large image (crop)', 'unicaevents'),
			'w'		 => 870,
			'h'		 => 490
		));
		unicaevents_add_template(array(
			'layout' => 'classic_3',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Classic tile /3 columns/', 'unicaevents'),
			'thumb_title'  => esc_html__('Medium image (crop)', 'unicaevents'),
			'w'		 => 390,
			'h'		 => 220
		));
		unicaevents_add_template(array(
			'layout' => 'classic_4',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Classic tile /4 columns/', 'unicaevents'),
			'thumb_title'  => esc_html__('Small image (crop)', 'unicaevents'),
			'w'		 => 300,
			'h'		 => 170
		));
		// Add template specific scripts
		add_action('unicaevents_action_blog_scripts', 'unicaevents_template_masonry_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('unicaevents_template_masonry_add_scripts')) {
	//add_action('unicaevents_action_blog_scripts', 'unicaevents_template_masonry_add_scripts');
	function unicaevents_template_masonry_add_scripts($style) {
		if (in_array(unicaevents_substr($style, 0, 8), array('classic_', 'masonry_'))) {
			unicaevents_enqueue_script( 'isotope', unicaevents_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
		}
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_masonry_output' ) ) {
	function unicaevents_template_masonry_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = unicaevents_in_shortcode_blogger(true) ? 'div' : 'article';
		?>
		<div class="isotope_item isotope_item_<?php echo esc_attr($style); ?> isotope_item_<?php echo esc_attr($post_options['layout']); ?> isotope_column_<?php echo esc_attr($columns); ?>
					<?php
					if ($post_options['filters'] != '') {
						if ($post_options['filters']=='categories' && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids);
						else if ($post_options['filters']=='tags' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids);
					}
					?>">
			<<?php echo trim($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['layout']); ?>
				 <?php echo ' post_format_'.esc_attr($post_data['post_format']) 
					. ($post_options['number']%2==0 ? ' even' : ' odd') 
					. ($post_options['number']==0 ? ' first' : '') 
					. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
				?>">
			

				<div class="post_content isotope_item_content">
					
					<?php
					if ($show_title) {
						if (!isset($post_options['links']) || $post_options['links']) {
							?>
							<h4 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h4>
							<?php
						} else {
							?>
							<h4 class="post_title"><?php echo trim($post_data['post_title']); ?></h4>
							<?php
						}
					}

					?>
					<?php if ($post_data['post_video'] || $post_data['post_audio'] || $post_data['post_thumb'] ||  $post_data['post_gallery']) { ?>
					<div class="post_featured">
						<?php require unicaevents_get_file_dir('templates/_parts/post-featured.php'); ?>
					</div>
				<?php } ?>

					<?php
					
					if (!$post_data['post_protected'] && $post_options['info']) {
						$info_parts = array('counters'=>true, 'terms'=>false, 'author'=>false);
						require unicaevents_get_file_dir('templates/_parts/post-info.php');
					}
					?>

					<div class="post_descr">
						<?php
						if ($post_data['post_protected']) {
							echo trim($post_data['post_excerpt']); 
						} else {
							if ($post_data['post_excerpt']) {
								echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p class="post_excerpt">'.trim(unicaevents_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : unicaevents_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
							}
							if (empty($post_options['readmore'])) $post_options['readmore'] = esc_html__('more', 'unicaevents');
							if (!unicaevents_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {
								//echo trim(unicaevents_sc_button(array('link'=>$post_data['post_link'], 'class'=>"post_readmore"), $post_options['readmore']));
								?><a href="<?php echo esc_url($post_data['post_link']);?>" class="post_item_button"><?php echo trim($post_options['readmore']);?></a><?php
							}
						}
						?>
					</div>

				</div>				<!-- /.post_content -->
			</<?php echo trim($tag); ?>>	<!-- /.post_item -->
		</div>						<!-- /.isotope_item -->
		<?php
	}
}
?>
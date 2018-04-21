<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_single_standard_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_single_standard_theme_setup', 1 );
	function unicaevents_template_single_standard_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'single-standard',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single standard', 'unicaevents'),
			'thumb_title'  => esc_html__('Fullwidth image', 'unicaevents'),
			'w'		 => 1170,
			'h'		 => 660
		));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_single_standard_output' ) ) {
	function unicaevents_template_single_standard_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && unicaevents_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = unicaevents_get_custom_option('show_post_title')=='yes' && (unicaevents_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));
		$title_tag = unicaevents_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		unicaevents_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		if ($show_title && $post_options['location'] == 'center' && unicaevents_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo ($avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'headline'); ?>" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php echo trim($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
		<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(unicaevents_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])	// && $post_data['post_format']!='gallery' && $post_data['post_format']!='image')
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
				echo trim($post_options['dedicated']);
			} else {
				unicaevents_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php echo trim($post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}
			
		
		if ($show_title && $post_options['location'] != 'center' && unicaevents_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo ($avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'headline'); ?>" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php echo trim($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}

		if (!$post_data['post_protected'] && unicaevents_get_custom_option('show_post_info')=='yes') {
			$info_parts = array('snippets'=>true);
			require unicaevents_get_file_dir('templates/_parts/post-info.php');
		}
		
		require unicaevents_get_file_dir('templates/_parts/reviews-block.php');
			
		unicaevents_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'">');
			
		// Post content
		if ($post_data['post_protected']) { 
			echo trim($post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
			global $UNICAEVENTS_GLOBALS;
			if (unicaevents_strpos($post_data['post_content'], unicaevents_get_reviews_placeholder())===false) $post_data['post_content'] = unicaevents_sc_reviews(array()) . ($post_data['post_content']);
			echo trim(unicaevents_gap_wrapper(unicaevents_reviews_wrapper($post_data['post_content'])));
			require unicaevents_get_file_dir('templates/_parts/single-pagination.php');
			if ( unicaevents_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info post_info_bottom">
					<span class="post_info_item post_info_tags"><?php esc_html_e('Tags:', 'unicaevents'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
				</div>
				<?php 
			}
		} 
		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			require unicaevents_get_file_dir('templates/_parts/editor-area.php');
		}
			
		unicaevents_close_wrapper();	// .post_content
			
		if (!$post_data['post_protected']) {
			require unicaevents_get_file_dir('templates/_parts/author-info.php');
			require unicaevents_get_file_dir('templates/_parts/share.php');
		}

		$sidebar_present = !unicaevents_param_is_off(unicaevents_get_custom_option('show_sidebar_main'));
		if (!$sidebar_present) unicaevents_close_wrapper();	// .post_item
		require unicaevents_get_file_dir('templates/_parts/related-posts.php');
		if ($sidebar_present) unicaevents_close_wrapper();		// .post_item

		if (!$post_data['post_protected']) {
			require unicaevents_get_file_dir('templates/_parts/comments.php');
		}
	}
}
?>
<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_services_2_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_services_2_theme_setup', 1 );
	function unicaevents_template_services_2_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'services-2',
			'template' => 'services-2',
			'mode'   => 'services',
			'need_columns' => true,
			'title'  => esc_html__('Services /Style 2/', 'unicaevents'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'unicaevents'),
			'w'		 => 390,
			'h'		 => 390
		));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_services_2_output' ) ) {
	function unicaevents_template_services_2_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (unicaevents_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><div class="sc_services_item_wrap"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo ($post_options['tag_id'] ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''); ?>
				class="sc_services_item sc_services_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo ($post_options['tag_css']!='' ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!unicaevents_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
				<div class="sc_services_item_content">
				<?php 
				if ($post_data['post_icon'] && $post_options['tag_type']=='icons') {
					$html = unicaevents_do_shortcode('[trx_icon icon="'.esc_attr($post_data['post_icon']).'" shape="round"]');
					if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
						?><?php echo trim($html); ?><?php
					} else
						echo trim($html);
				} else {
					?>
					<div class="sc_services_item_featured post_featured">
						<?php require unicaevents_get_file_dir('templates/_parts/post-featured.php'); ?>
					</div>
					<?php
				}
				?>
					<?php
					if ($show_title) {
						if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
							?><h4 class="sc_services_item_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h4><?php
						} else {
							?><h4 class="sc_services_item_title"><?php echo trim($post_data['post_title']); ?></h4><?php
						}
					}
					?>

					<div class="sc_services_item_description">
						<?php
						if ($post_data['post_protected']) {
							echo trim($post_data['post_excerpt']); 
						} else {
							if ($post_data['post_excerpt']) {
								echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(unicaevents_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : unicaevents_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
							}
							if (!empty($post_data['post_link']) && !unicaevents_param_is_off($post_options['readmore'])) {
								?></div><a href="<?php echo esc_url($post_data['post_link']); ?>" class="sc_services_item_readmore"><?php echo trim($post_options['readmore']); ?></a><?php
							}
						}
						?>
				</div>
			</div>
		<?php
		if (unicaevents_param_is_on($post_options['slider'])) {
			?></div></div><?php
		} else if ($columns > 1) {
			?></div><?php
		}
	}
}
?>
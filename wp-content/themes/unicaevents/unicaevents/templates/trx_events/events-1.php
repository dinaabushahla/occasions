<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_events_1_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_events_1_theme_setup', 1 );
	function unicaevents_template_events_1_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'events-1',
			'template' => 'events-1',
			'mode'   => 'events',
			'title'  => esc_html__('Events /Style 1/', 'unicaevents'),
			'thumb_title'  => esc_html__('Medium image (crop)', 'unicaevents'),
			'w'		 => 390,
			'h'		 => 220
		));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_events_1_output' ) ) {
	function unicaevents_template_events_1_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		$date = tribe_get_start_date(null, true, get_option('date_format').' '.get_option('time_format'));
		if (unicaevents_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><div class="sc_events_item_wrap"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo ($post_options['tag_id'] ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''); ?>
				class="sc_events_item sc_events_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo ($post_options['tag_css']!='' ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!unicaevents_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>
				>
				<div class="sc_events_item_featured post_featured">
					<?php require unicaevents_get_file_dir('templates/_parts/post-featured.php'); ?>
				</div>
				<div class="sc_events_item_content">
					<?php
					echo '<p class="sc_events_item_date">' . ($date) . '</p>';
					if ($show_title) {
						if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
							?><h5 class="sc_events_item_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h5><?php
						} else {
							?><h5 class="sc_events_item_title"><?php echo trim($post_data['post_title']); ?></h5><?php
						}
					}
					?>

					<div class="sc_events_item_description">
						<?php
						if ($post_data['post_protected']) {
							echo trim($post_data['post_excerpt']); 
						} else {
							if ($post_data['post_excerpt']) {
								echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) 
										? $post_data['post_excerpt'] 
										: '<p>'.trim(unicaevents_strshort($post_data['post_excerpt'], isset($post_options['descr']) 
																										? $post_options['descr'] 
																										: unicaevents_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
							}
							if (!empty($post_data['post_link']) && !unicaevents_param_is_off($post_options['readmore'])) {
								?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="sc_events_item_readmore"><?php echo trim($post_options['readmore']); ?><span class="icon-right"></span></a><?php
							}
						}
						?>
					</div>
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
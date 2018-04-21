<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_team_1_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_team_1_theme_setup', 1 );
	function unicaevents_template_team_1_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'team-1',
			'template' => 'team-1',
			'mode'   => 'team',
			/*'container_classes' => 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom',*/
			'title'  => esc_html__('Team /Style 1/', 'unicaevents'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'unicaevents'),
			'w' => 390,
			'h' => 390
		));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_team_1_output' ) ) {
	function unicaevents_template_team_1_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (unicaevents_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo ($post_options['tag_id'] ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''); ?>
				class="sc_team_item sc_team_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo ($post_options['tag_css']!='' ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!unicaevents_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
				<div class="sc_team_item_avatar"><?php echo trim($post_options['photo']); ?>
				<?php echo ($post_options['link'] ? '<a href="'.esc_url($post_options['link']).'">' : '') . ($post_options['link'] ? '</a>' : ''); ?>
				</div>
				<div class="sc_team_item_info">
					<h5 class="sc_team_item_title"><?php echo ($post_options['link'] ? '<a href="'.esc_url($post_options['link']).'">' : '') . ($post_data['post_title']) . ($post_options['link'] ? '</a>' : ''); ?></h5>
					<div class="sc_team_item_position"><?php echo trim($post_options['position']);?></div>
					<?php echo trim($post_options['socials']); ?>
				</div>
			</div>
		<?php
		if (unicaevents_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>
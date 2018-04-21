<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_testimonials_2_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_testimonials_2_theme_setup', 1 );
	function unicaevents_template_testimonials_2_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'testimonials-2',
			'template' => 'testimonials-2',
			'mode'   => 'testimonials',
			/*'container_classes' => 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols',*/
			'title'  => esc_html__('Testimonials /Style 2/', 'unicaevents')
		));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_testimonials_2_output' ) ) {
	function unicaevents_template_testimonials_2_output($post_options, $post_data) {
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
			<div<?php echo ($post_options['tag_id'] ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''); ?> class="sc_testimonial_item<?php echo (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"<?php echo ($post_options['tag_css'] ? ' style="'.esc_attr($post_options['tag_css']).'"' : '');?>>
				<div class="sc_testimonial_content"><?php echo trim($post_data['post_content']); ?></div>
				<?php if ($post_options['author']) { ?>
				<div class="sc_testimonial_author"><?php 
					echo ($post_options['link'] 
							? '<a href="'.esc_url($post_options['link']).'" class="sc_testimonial_author_name">'.($post_options['author']).'</a>' 
							: '<span class="sc_testimonial_author_name">'.($post_options['author']).'</span>')
						. ($post_options['position']
							? '<span class="sc_testimonial_author_position">, '.($post_options['position']).'</span>'
							: ''); ?></div>
				<?php } ?>
			</div>
		<?php
		if (unicaevents_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>
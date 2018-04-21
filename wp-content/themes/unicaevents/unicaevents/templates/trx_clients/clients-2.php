<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_clients_2_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_clients_2_theme_setup', 1 );
	function unicaevents_template_clients_2_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'clients-2',
			'template' => 'clients-2',
			'mode'   => 'clients',
			/*'container_classes' => 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom',*/
			'title'  => esc_html__('Clients /Style 2/', 'unicaevents')
		));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_clients_2_output' ) ) {
	function unicaevents_template_clients_2_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? $post_options['columns_count'] : (int) $parts[1]));
		if (unicaevents_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo ($post_options['tag_id'] ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''); ?>
				class="sc_clients_item sc_clients_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"<?php echo ($post_options['tag_css']!='' ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') . (!unicaevents_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(unicaevents_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
				<div class="sc_client_image"><?php echo trim($post_options['client_image']); ?>
					<div class="sc_client_hover">
						<div class="sc_client_info">
							<h5 class="sc_client_title"><?php echo ($post_options['post_title'] ? '<a href="'.esc_url($post_options['post_title']).'">' : '') . ($post_options['post_title'] ? $post_options['post_title'] : $post_data['post_title']) . ($post_options['client_link'] ? '</a>' : ''); ?></h5>
							<div class="sc_client_description"><?php echo trim(unicaevents_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : unicaevents_get_custom_option('post_excerpt_maxlength_masonry'))); ?></div>
						</div>
					</div>
				</div>
			</div>
		<?php
		if (unicaevents_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>
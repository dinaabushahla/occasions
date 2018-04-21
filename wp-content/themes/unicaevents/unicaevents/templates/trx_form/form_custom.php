<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_form_custom_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_form_custom_theme_setup', 1 );
	function unicaevents_template_form_custom_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'form_custom',
			'mode'   => 'forms',
			'title'  => esc_html__('Custom Form', 'unicaevents')
			));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_form_custom_output' ) ) {
	function unicaevents_template_form_custom_output($post_options, $post_data) {
		global $UNICAEVENTS_GLOBALS;
		?>
		<form <?php echo ($post_options['id'] ? ' id="'.esc_attr($post_options['id']).'"' : ''); ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : $UNICAEVENTS_GLOBALS['ajax_url']); ?>">
			<?php echo trim($post_options['content']); ?>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>
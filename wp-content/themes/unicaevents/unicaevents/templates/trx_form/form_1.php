<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_form_1_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_form_1_theme_setup', 1 );
	function unicaevents_template_form_1_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'form_1',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 1', 'unicaevents')
			));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_form_1_output' ) ) {
	function unicaevents_template_form_1_output($post_options, $post_data) {
		global $UNICAEVENTS_GLOBALS;
		?>
		<form <?php echo ($post_options['id'] ? ' id="'.esc_attr($post_options['id']).'"' : ''); ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : $UNICAEVENTS_GLOBALS['ajax_url']); ?>">
			<div class="sc_form_info">
			<div class="columns_wrap sc_columns columns_nofluid sc_columns_count_2">
				<div class="column-1_2 sc_column_item sc_column_item_1"><div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name:', 'unicaevents'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name:', 'unicaevents'); ?>"></div></div>
				<div class="column-1_2 sc_column_item sc_column_item_2"><div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_phone"><?php esc_html_e('Phone:', 'unicaevents'); ?></label><input id="sc_form_phone" type="text" name="phone" placeholder="<?php esc_attr_e('Phone:', 'unicaevents'); ?>"></div></div>
			</div>
			</div>
			<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'unicaevents'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message:', 'unicaevents'); ?>"></textarea></div>
			<div class="sc_form_item sc_form_button"><button><?php esc_html_e('send', 'unicaevents'); ?></button></div>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>
<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_form_2_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_form_2_theme_setup', 1 );
	function unicaevents_template_form_2_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'unicaevents')
			));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_form_2_output' ) ) {
	function unicaevents_template_form_2_output($post_options, $post_data) {
		global $UNICAEVENTS_GLOBALS;
		$address_1 = unicaevents_get_theme_option('contact_address_1');
		$address_2 = unicaevents_get_theme_option('contact_address_2');
		$phone = unicaevents_get_theme_option('contact_phone');
		$fax = unicaevents_get_theme_option('contact_fax');
		$email = unicaevents_get_theme_option('contact_email');
		$open_hours = unicaevents_get_theme_option('contact_open_hours');
		$session = unicaevents_get_theme_option('session_time');
		$link_email = 'mailto:' . $email;
		?>
		<div class="sc_columns columns_wrap">
			<div class="sc_form_address column-1_2">
				<div class="sc_form_address_field column-1_2 address">
					<span class="sc_form_address_label"><?php esc_html_e('Postal Address', 'unicaevents'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($address_1) . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2; ?></span>
				</div>
				<div class="sc_form_address_field column-1_2 open">
					<span class="sc_form_address_label"><?php esc_html_e('Open Hours', 'unicaevents'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($open_hours); ?></span>
				</div>
				<div class="sc_form_address_field column-1_2 phone">
					<span class="sc_form_address_label"><?php esc_html_e('Phone & E-mail', 'unicaevents'); ?></span>
					<span class="sc_form_address_data"><?php echo esc_html__('Phone: ', 'unicaevents') . trim($phone) . (!empty($phone) && !empty($fax) ? '<br>' : '') 
					. esc_html__('Fax: ', 'unicaevents') . trim($fax) . (!empty($fax) && !empty($email) ? '<br>' : '') . '<a href="'.esc_url($link_email).'">'. trim($email) . '</a>' ?></span>
				</div>
				<div class="sc_form_address_field column-1_2 session">
					<span class="sc_form_address_label"><?php esc_html_e('Sessions', 'unicaevents'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($session); ?></span>
				</div>
			</div><div class="sc_form_fields column-1_2">
				<form <?php echo ($post_options['id'] ? ' id="'.esc_attr($post_options['id']).'"' : ''); ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : $UNICAEVENTS_GLOBALS['ajax_url']); ?>">
					<div class="sc_form_info">
						<div class="sc_form_item sc_form_field label_over column-1_2"><label class="required" for="sc_form_username"><?php esc_html_e('Name:', 'unicaevents'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name:', 'unicaevents'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over column-1_2"><label class="required" for="sc_form_phone"><?php esc_html_e('Phone;', 'unicaevents'); ?></label><input id="sc_form_phone" type="text" name="phone" placeholder="<?php esc_attr_e('Phone:', 'unicaevents'); ?>"></div>
					</div>
					<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message:', 'unicaevents'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message:', 'unicaevents'); ?>"></textarea></div>
					<div class="sc_form_item sc_form_button"><button class="aligncenter"><?php esc_html_e('Send', 'unicaevents'); ?></button></div>
					<div class="result sc_infobox"></div>
				</form>
			</div>
		</div>
		<?php
	}
}
?>
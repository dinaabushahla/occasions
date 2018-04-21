<?php
/**
 * UnicaEvents Framework: Theme options custom fields
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_options_custom_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_options_custom_theme_setup' );
	function unicaevents_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'unicaevents_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'unicaevents_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'unicaevents_options_custom_load_scripts');
	function unicaevents_options_custom_load_scripts() {
		unicaevents_enqueue_script( 'unicaevents-options-custom-script',	unicaevents_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
function unicaevents_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviews_block">' . trim(unicaevents_reviews_get_markup($field, $value, true)) . '</div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager"
				data-param="' . esc_attr($id) . '"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'unicaevents') : esc_html__( 'Choose Image', 'unicaevents')).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'unicaevents') : esc_html__( 'Choose Image', 'unicaevents')).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.esc_attr($field['media_field_id']).'"
				onclick="unicaevents_show_media_manager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'unicaevents') : esc_html__( 'Choose Image', 'unicaevents')) . '</a>';
			break;
	}
	return apply_filters('unicaevents_filter_show_custom_field', $output, $id, $field, $value);
}
?>
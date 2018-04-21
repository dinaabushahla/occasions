<?php
/**
 * UnicaEvents Framework: Supported post types settings
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Theme init
if (!function_exists('unicaevents_post_type_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_post_type_theme_setup', 9 );
	function unicaevents_post_type_theme_setup() {
		if ( !unicaevents_options_is_used() ) return;
		$post_type = unicaevents_admin_get_current_post_type();
		if (empty($post_type)) $post_type = 'post';
		$override_key = unicaevents_get_override_key($post_type, 'post_type');
		if ($override_key) {
			// Set post type action
			add_action('save_post',				'unicaevents_post_type_save_options');
			add_action('add_meta_boxes',		'unicaevents_post_type_add_meta_box');
			add_action('admin_enqueue_scripts', 'unicaevents_post_type_admin_scripts');
			// Create meta box
			global $UNICAEVENTS_GLOBALS;
			$UNICAEVENTS_GLOBALS['post_meta_box'] = array(
				'id' => 'post-meta-box',
				'title' => esc_html__('Post Options', 'unicaevents'),
				'page' => $post_type,
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array()
			);
		}
	}
}


// Admin scripts
if (!function_exists('unicaevents_post_type_admin_scripts')) {
	//add_action('admin_enqueue_scripts', 'unicaevents_post_type_admin_scripts');
	function unicaevents_post_type_admin_scripts() {
	}
}


// Add meta box
if (!function_exists('unicaevents_post_type_add_meta_box')) {
	//add_action('add_meta_boxes', 'unicaevents_post_type_add_meta_box');
	function unicaevents_post_type_add_meta_box() {
		global $UNICAEVENTS_GLOBALS;
		$mb = $UNICAEVENTS_GLOBALS['post_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'unicaevents_post_type_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('unicaevents_post_type_show_meta_box')) {
	function unicaevents_post_type_show_meta_box() {
		global $post, $UNICAEVENTS_GLOBALS;
		
		$post_type = unicaevents_admin_get_current_post_type();
		$override_key = unicaevents_get_override_key($post_type, 'post_type');
		
		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_post_nonce" value="' .esc_attr($UNICAEVENTS_GLOBALS['admin_nonce']).'" />';
		echo '<input type="hidden" name="meta_box_post_type" value="'.esc_attr($post_type).'" />';
	
		$custom_options = apply_filters('unicaevents_filter_post_load_custom_options', get_post_meta($post->ID, 'post_custom_options', true), $post_type, $post->ID);

		$mb = $UNICAEVENTS_GLOBALS['post_meta_box'];
		$post_options = unicaevents_array_merge($UNICAEVENTS_GLOBALS['options'], $mb['fields']);

		do_action('unicaevents_action_post_before_show_meta_box', $post_type, $post->ID);
	
		unicaevents_options_page_start(array(
			'data' => $post_options,
			'add_inherit' => true,
			'create_form' => false,
			'buttons' => array('import', 'export'),
			'override' => $override_key
		));

		if (is_array($post_options) && count($post_options) > 0) {
			foreach ($post_options as $id=>$option) { 
				if (!isset($option['override']) || !in_array($override_key, explode(',', $option['override']))) continue;

				$option = apply_filters('unicaevents_filter_post_show_custom_field_option', $option, $id, $post_type, $post->ID);
				$meta = isset($custom_options[$id]) 
								? apply_filters('unicaevents_filter_post_show_custom_field_value', $custom_options[$id], $option, $id, $post_type, $post->ID) 
								: (isset($option['inherit']) && !$option['inherit'] ? $option['std'] : '');

				do_action('unicaevents_action_post_before_show_custom_field', $post_type, $post->ID, $option, $id, $meta);

				unicaevents_options_show_field($id, $option, $meta);

				do_action('unicaevents_action_post_after_show_custom_field', $post_type, $post->ID, $option, $id, $meta);
			}
		}
	
		unicaevents_options_page_stop();
		
		do_action('unicaevents_action_post_after_show_meta_box', $post_type, $post->ID);
		
	}
}


// Save data from meta box
if (!function_exists('unicaevents_post_type_save_options')) {
	//add_action('save_post', 'unicaevents_post_type_save_options');
	function unicaevents_post_type_save_options($post_id) {

		global $UNICAEVENTS_GLOBALS;
		
		// verify nonce
		if (!isset($_POST['meta_box_post_nonce']) || !wp_verify_nonce($_POST['meta_box_post_nonce'], $UNICAEVENTS_GLOBALS['admin_url'])) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		$post_type = isset($_POST['meta_box_post_type']) ? $_POST['meta_box_post_type'] : $_POST['post_type'];
		$override_key = unicaevents_get_override_key($post_type, 'post_type');

		// check permissions
		$capability = 'page';
		$post_types = get_post_types( array( 'name' => $post_type), 'objects' );
		if (!empty($post_types) && is_array($post_types)) {
			foreach ($post_types  as $type) {
				$capability = $type->capability_type;
				break;
			}
		}
		if (!current_user_can('edit_'.($capability), $post_id)) {
			return $post_id;
		}

		$custom_options = array();

		$post_options = array_merge($UNICAEVENTS_GLOBALS['options'], $UNICAEVENTS_GLOBALS['post_meta_box']['fields']);

		if (unicaevents_options_merge_new_values($post_options, $custom_options, $_POST, 'save', $override_key)) {
			update_post_meta($post_id, 'post_custom_options', apply_filters('unicaevents_filter_post_save_custom_options', $custom_options, $post_type, $post_id));
		}

		// Init post counters
		global $post;
		if ( !empty($post->ID) && $post_id==$post->ID ) {
			unicaevents_get_post_views($post_id);
			unicaevents_get_post_likes($post_id);
		}
	}
}
?>
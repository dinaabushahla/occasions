<?php
/**
 * UnicaEvents Framework: shortcodes manipulations
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('unicaevents_sc_theme_setup')) {
	add_action( 'unicaevents_action_init_theme', 'unicaevents_sc_theme_setup', 1 );
	function unicaevents_sc_theme_setup() {
		// Add sc stylesheets
		add_action('unicaevents_action_add_styles', 'unicaevents_sc_add_styles', 1);
	}
}

if (!function_exists('unicaevents_sc_theme_setup2')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_sc_theme_setup2' );
	function unicaevents_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'unicaevents_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('unicaevents_sc_prepare_content')) unicaevents_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('unicaevents_shortcode_output', 'unicaevents_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'unicaevents_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'unicaevents_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'unicaevents_sc_selector_add_in_toolbar', 11);

	}
}


// Add shortcodes styles
if ( !function_exists( 'unicaevents_sc_add_styles' ) ) {
	//add_action('unicaevents_action_add_styles', 'unicaevents_sc_add_styles', 1);
	function unicaevents_sc_add_styles() {
		// Shortcodes
		unicaevents_enqueue_style( 'unicaevents-shortcodes-style',	unicaevents_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
	}
}


// Add shortcodes init scripts
if ( !function_exists( 'unicaevents_sc_add_scripts' ) ) {
	//add_filter('unicaevents_shortcode_output', 'unicaevents_sc_add_scripts', 10, 4);
	function unicaevents_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		global $UNICAEVENTS_GLOBALS;
		
		if (empty($UNICAEVENTS_GLOBALS['shortcodes_scripts_added'])) {
			$UNICAEVENTS_GLOBALS['shortcodes_scripts_added'] = true;
			//unicaevents_enqueue_style( 'unicaevents-shortcodes-style', unicaevents_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
			unicaevents_enqueue_script( 'unicaevents-shortcodes-script', unicaevents_get_file_url('shortcodes/theme.shortcodes.js'), array('jquery'), null, true );	
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('unicaevents_sc_prepare_content')) {
	function unicaevents_sc_prepare_content() {
		if (function_exists('unicaevents_sc_clear_around')) {
			$filters = array(
				array('unicaevents', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (function_exists('unicaevents_exists_woocommerce') && unicaevents_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'unicaevents_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('unicaevents_sc_excerpt_shortcodes')) {
	function unicaevents_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
			//$content = strip_shortcodes($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('unicaevents_sc_clear_around')) {
	function unicaevents_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// UnicaEvents shortcodes load scripts
if (!function_exists('unicaevents_sc_load_scripts')) {
	function unicaevents_sc_load_scripts() {
		unicaevents_enqueue_script( 'unicaevents-shortcodes-script', unicaevents_get_file_url('core/core.shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		unicaevents_enqueue_script( 'unicaevents-selection-script',  unicaevents_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
	}
}

// UnicaEvents shortcodes prepare scripts
if (!function_exists('unicaevents_sc_prepare_scripts')) {
	function unicaevents_sc_prepare_scripts() {
		global $UNICAEVENTS_GLOBALS;
		if (!isset($UNICAEVENTS_GLOBALS['shortcodes_prepared'])) {
			$UNICAEVENTS_GLOBALS['shortcodes_prepared'] = true;
			$json_parse_func = 'eval';	// 'JSON.parse'
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					try {
						UNICAEVENTS_GLOBALS['shortcodes'] = <?php echo trim($json_parse_func); ?>(<?php echo json_encode( unicaevents_array_prepare_to_json($UNICAEVENTS_GLOBALS['shortcodes']) ); ?>);
					} catch (e) {}
					UNICAEVENTS_GLOBALS['shortcodes_cp'] = '<?php echo is_admin() ? (!empty($UNICAEVENTS_GLOBALS['to_colorpicker']) ? $UNICAEVENTS_GLOBALS['to_colorpicker'] : 'wp') : 'custom'; ?>';	// wp | tiny | custom
				});
			</script>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('unicaevents_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','unicaevents_sc_selector_add_in_toolbar', 11);
	function unicaevents_sc_selector_add_in_toolbar(){

		if ( !unicaevents_options_is_used() ) return;

		unicaevents_sc_load_scripts();
		unicaevents_sc_prepare_scripts();

		global $UNICAEVENTS_GLOBALS;

		$shortcodes = $UNICAEVENTS_GLOBALS['shortcodes'];
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'unicaevents').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		echo trim($shortcodes_list);
	}
}

// UnicaEvents shortcodes builder settings
require_once unicaevents_get_file_dir('core/core.shortcodes/shortcodes_settings.php');

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	require_once unicaevents_get_file_dir('core/core.shortcodes/shortcodes_vc.php');
}

// UnicaEvents shortcodes implementation
unicaevents_autoload_folder( 'shortcodes/trx_basic' );
unicaevents_autoload_folder( 'shortcodes/trx_optional' );
?>
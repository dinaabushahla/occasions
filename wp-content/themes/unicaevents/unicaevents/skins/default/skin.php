<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('unicaevents_action_skin_theme_setup')) {
	add_action( 'unicaevents_action_init_theme', 'unicaevents_action_skin_theme_setup', 1 );
	function unicaevents_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('unicaevents_filter_used_fonts',			'unicaevents_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('unicaevents_filter_list_fonts',			'unicaevents_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('unicaevents_action_add_styles',			'unicaevents_action_skin_add_styles');
		// Add skin inline styles
		add_filter('unicaevents_filter_add_styles_inline',		'unicaevents_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('unicaevents_action_add_responsive',		'unicaevents_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('unicaevents_filter_add_responsive_inline',	'unicaevents_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('unicaevents_action_add_scripts',			'unicaevents_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('unicaevents_action_add_scripts_inline',	'unicaevents_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('unicaevents_filter_compile_less',			'unicaevents_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		unicaevents_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'unicaevents'),

			// Accent colors
			'accent1'				=> '#ff635c',
			'accent1_hover'			=> '#ea4e47',
			'accent2'				=> '#fcb41e',
			'accent2_hover'			=> '#e9a312',
			'accent3'				=> '#76c1d1',
			'accent3_hover'			=> '#68acba',
			'accent4'				=> '#5d5167',
			'accent4_hover'			=> '#584866',
			
			// Headers, text and links colors
			'text'					=> '#676767',
			'text_light'			=> '#a3a3a6',
			'text_dark'				=> '#272530',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#d7d7d7',
			'bg_color'				=> '#ffffff',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
			'transparent' 			=> 'transparent',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#ff635c',
			'alter_hover'			=> '#ea4e47',
			'alter_bd_color'		=> '#272834',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f5f5f5',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
	/*	unicaevents_add_color_scheme('light', array(

			'title'					=> esc_html__('Light', 'unicaevents'),

			// Accent colors
			'accent1'				=> '#20c7ca',
			'accent1_hover'			=> '#189799',
//			'accent2'				=> '#ff0000',
//			'accent2_hover'			=> '#aa0000',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			
			// Headers, text and links colors
			'text'					=> '#8a8a8a',
			'text_light'			=> '#acb4b6',
			'text_dark'				=> '#232a34',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#dddddd',
			'bg_color'				=> '#f7f7f7',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#e7e7e7',
			'alter_bd_hover'		=> '#dddddd',
			'alter_bg_color'		=> '#ffffff',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		unicaevents_add_color_scheme('dark', array(

			'title'					=> esc_html__('Dark', 'unicaevents'),

			// Accent colors
			'accent1'				=> '#20c7ca',
			'accent1_hover'			=> '#189799',
//			'accent2'				=> '#ff0000',
//			'accent2_hover'			=> '#aa0000',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			
			// Headers, text and links colors
			'text'					=> '#909090',
			'text_light'			=> '#a0a0a0',
			'text_dark'				=> '#e0e0e0',
			'inverse_text'			=> '#f0f0f0',
			'inverse_light'			=> '#e0e0e0',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#e5e5e5',
			
			// Whole block border and background
			'bd_color'				=> '#000000',
			'bg_color'				=> '#333333',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#999999',
			'alter_light'			=> '#aaaaaa',
			'alter_dark'			=> '#d0d0d0',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#29fbff',
			'alter_bd_color'		=> '#909090',
			'alter_bd_hover'		=> '#888888',
			'alter_bg_color'		=> '#666666',
			'alter_bg_hover'		=> '#505050',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);*/

		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		unicaevents_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '3.929em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.4em',
			'margin-top'	=> '',
			'margin-bottom'	=> '0.26em'
			)
		);
		unicaevents_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '3.2143em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> '0.48em'
			)
		);
		unicaevents_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '1.786em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.2em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.27em'
			)
		);
		unicaevents_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '1.643em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.2em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.4em'
			)
		);
		unicaevents_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '1.429em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.1em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.85em'
			)
		);
		unicaevents_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '1.214em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.85em'
			)
		);
		unicaevents_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> 'Open Sans',
			'font-size' 	=> '14px',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '25px;',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		unicaevents_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		unicaevents_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '2.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> '2.5em'
			)
		);
		unicaevents_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> 'Montserrat',
			'font-size' 	=> '0.786em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.45em',
			'margin-bottom'	=> '0.35em'
			)
		);
		unicaevents_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		unicaevents_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '2.5em',
			'margin-bottom'	=> '1.65em'
			)
		);
		unicaevents_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.9em'
			)
		);
		unicaevents_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('unicaevents_filter_skin_used_fonts')) {
	//add_filter('unicaevents_filter_used_fonts', 'unicaevents_filter_skin_used_fonts');
	function unicaevents_filter_skin_used_fonts($theme_fonts) {
		$theme_fonts['Open Sans'] = 1;
		$theme_fonts['Vidaloka'] = 1;
		$theme_fonts['Montserrat'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('unicaevents_filter_skin_list_fonts')) {
	//add_filter('unicaevents_filter_list_fonts', 'unicaevents_filter_skin_list_fonts');
	function unicaevents_filter_skin_list_fonts($list) {
		if (!isset($list['Vidaloka']))	$list['Vidaloka'] = array('family'=>'serif', 'link'   => 'Vidaloka:400');
		return $list;
		if (!isset($list['Open Sans']))	$list['Open Sans'] = array('family'=>'sans-serif', 'link'   => 'Open+Sans:400,600,700');
		return $list;
		if (!isset($list['Montserrat']))	$list['Montserrat'] = array('family'=>'sans-serif', 'link'   => 'Montserrat:700');
		return $list;
	}
}

//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('unicaevents_action_skin_add_styles')) {
	//add_action('unicaevents_action_add_styles', 'unicaevents_action_skin_add_styles');
	function unicaevents_action_skin_add_styles() {
		// Add stylesheet files
		unicaevents_enqueue_style( 'unicaevents-skin-style', unicaevents_get_file_url('skin.css'), array(), null );
		if (file_exists(unicaevents_get_file_dir('skin.customizer.css')))
			unicaevents_enqueue_style( 'unicaevents-skin-customizer-style', unicaevents_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('unicaevents_filter_skin_add_styles_inline')) {
	//add_filter('unicaevents_filter_add_styles_inline', 'unicaevents_filter_skin_add_styles_inline');
	function unicaevents_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = unicaevents_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = unicaevents_get_scheme_color('accent1');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_regular .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('unicaevents_action_skin_add_responsive')) {
	//add_action('unicaevents_action_add_responsive', 'unicaevents_action_skin_add_responsive');
	function unicaevents_action_skin_add_responsive() {
		$suffix = unicaevents_param_is_off(unicaevents_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(unicaevents_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			unicaevents_enqueue_style( 'theme-skin-responsive-style', unicaevents_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('unicaevents_filter_skin_add_responsive_inline')) {
	//add_filter('unicaevents_filter_add_responsive_inline', 'unicaevents_filter_skin_add_responsive_inline');
	function unicaevents_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('unicaevents_filter_skin_compile_less')) {
	//add_filter('unicaevents_filter_compile_less', 'unicaevents_filter_skin_compile_less');
	function unicaevents_filter_skin_compile_less($files) {
		if (file_exists(unicaevents_get_file_dir('skin.less'))) {
		 	$files[] = unicaevents_get_file_dir('skin.less');
		}
		return $files;	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('unicaevents_action_skin_add_scripts')) {
	//add_action('unicaevents_action_add_scripts', 'unicaevents_action_skin_add_scripts');
	function unicaevents_action_skin_add_scripts() {
		if (file_exists(unicaevents_get_file_dir('skin.js')))
			unicaevents_enqueue_script( 'theme-skin-script', unicaevents_get_file_url('skin.js'), array(), null );
		if (unicaevents_get_theme_option('show_theme_customizer') == 'yes' && file_exists(unicaevents_get_file_dir('skin.customizer.js')))
			unicaevents_enqueue_script( 'theme-skin-customizer-script', unicaevents_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('unicaevents_action_skin_add_scripts_inline')) {
	//add_action('unicaevents_action_add_scripts_inline', 'unicaevents_action_skin_add_scripts_inline');
	function unicaevents_action_skin_add_scripts_inline() {
		// Todo: add skin specific scripts
		// Example:
		// echo '<script type="text/javascript">'
		//	. 'jQuery(document).ready(function() {'
		//	. "if (UNICAEVENTS_GLOBALS['theme_font']=='') UNICAEVENTS_GLOBALS['theme_font'] = '" . unicaevents_get_custom_font_settings('p', 'font-family') . "';"
		//	. "UNICAEVENTS_GLOBALS['theme_skin_color'] = '" . unicaevents_get_scheme_color('accent1') . "';"
		//	. "});"
		//	. "< /script>";
	}
}
?>
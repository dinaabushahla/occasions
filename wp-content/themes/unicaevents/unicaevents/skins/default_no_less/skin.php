<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if ( !function_exists( 'unicaevents_options_settings_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_options_settings_theme_setup3', 3 );	// Priority 1 for add unicaevents_filter handlers, 2 for create Theme Options
	function unicaevents_options_settings_theme_setup3() {
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS['options']['top_panel_scheme']['options']		= $UNICAEVENTS_GLOBALS['options_params']['list_bg_tints'];
		$UNICAEVENTS_GLOBALS['options']['sidebar_main_scheme']['options']	= $UNICAEVENTS_GLOBALS['options_params']['list_bg_tints'];
		$UNICAEVENTS_GLOBALS['options']['sidebar_outer_scheme']['options']	= $UNICAEVENTS_GLOBALS['options_params']['list_bg_tints'];
		$UNICAEVENTS_GLOBALS['options']['sidebar_footer_scheme']['options']= $UNICAEVENTS_GLOBALS['options_params']['list_bg_tints'];
		$UNICAEVENTS_GLOBALS['options']['testimonials_scheme']['options']	= $UNICAEVENTS_GLOBALS['options_params']['list_bg_tints'];
		$UNICAEVENTS_GLOBALS['options']['twitter_scheme']['options']		= $UNICAEVENTS_GLOBALS['options_params']['list_bg_tints'];
		$UNICAEVENTS_GLOBALS['options']['contacts_scheme']['options']		= $UNICAEVENTS_GLOBALS['options_params']['list_bg_tints'];
		$UNICAEVENTS_GLOBALS['options']['copyright_scheme']['options']		= $UNICAEVENTS_GLOBALS['options_params']['list_bg_tints'];
	}
}

if (!function_exists('unicaevents_action_skin_theme_setup')) {
	add_action( 'unicaevents_action_init_theme', 'unicaevents_action_skin_theme_setup', 1 );
	function unicaevents_action_skin_theme_setup() {

		// Disable less compilation
		unicaevents_set_theme_setting('less_compiler', 'no');
		// Disable customizer demo
		unicaevents_set_theme_setting('customizer_demo', false);

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
		
		*/

		// Add color schemes
		unicaevents_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'unicaevents'),

			// Accent colors
			'accent1'				=> '#20c7ca',
			'accent1_hover'			=> '#189799',
//			'accent2'				=> '',
//			'accent2_hover'			=> '',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			
			)
		);

		unicaevents_add_color_scheme('contrast', array(

			'title'		 =>	esc_html__('Contrast', 'unicaevents'),

			// Accent colors
			'accent1'				=> '#26c3d6',		// rgb(38,195,214)
			'accent1_hover'			=> '#24b6c8',		// rgb(36,182,200)
//			'accent2'				=> '',
//			'accent2_hover'			=> '',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			)
		);
		unicaevents_add_color_scheme('modern', array(

			'title'		 =>	esc_html__('Modern', 'unicaevents'),

			// Accent colors
			'accent1'				=> '#f9c82d',		// rgb(249,200,45)
			'accent1_hover'  		=> '#e6ba29',		// rgb(230,186,41)
//			'accent2'				=> '',
//			'accent2_hover'			=> '',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			)
		);
		unicaevents_add_color_scheme('pastel', array(

			'title'		 =>	esc_html__('Pastel', 'unicaevents'),

			// Accent colors
			'accent1' 				=> '#0dcdc0',		// rgb(13,205,192)
			'accent1_hover'  		=> '#0bbaae',		// rgb(11,186,174)
//			'accent2'				=> '',
//			'accent2_hover'			=> '',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			)
		);


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
			'font-family'	=> '',
			'font-size' 	=> '3.5714em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '0.4em'
			)
		);
		unicaevents_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '3.2143em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.6667em',
			'margin-bottom'	=> '0.4em'
			)
		);
		unicaevents_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '3.2143em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.6667em',
			'margin-bottom'	=> '0.4em'
			)
		);
		unicaevents_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.7857em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '0.6em'
			)
		);
		unicaevents_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.5em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '0.5em'
			)
		);
		unicaevents_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.2857em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.25em',
			'margin-bottom'	=> '0.65em'
			)
		);
		unicaevents_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> 'Lato',
			'font-size' 	=> '14px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.5em',
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
			'font-style'	=> 'i',
			'line-height'	=> '1.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		unicaevents_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.8em',
			'margin-bottom'	=> '1.8em'
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
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '0.5em'
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
			'margin-bottom'	=> '2em'
			)
		);
		unicaevents_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'unicaevents'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em'
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
		//$theme_fonts['Roboto'] = 1;
		//$theme_fonts['Love Ya Like A Sister'] = 1;
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
		// Example:
		// if (!isset($list['Advent Pro'])) {
		//		$list['Advent Pro'] = array(
		//			'family' => 'sans-serif',																						// (required) font family
		//			'link'   => 'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
		//			'css'    => unicaevents_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
		//			);
		// }
		if (!isset($list['Lato']))	$list['Lato'] = array('family'=>'sans-serif');
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
		$clr = unicaevents_get_scheme_colors();
		$clr['accent1_rgb'] = unicaevents_hex2rgb($clr['accent1']);
		$clr['accent1_hover_rgb'] = unicaevents_hex2rgb($clr['accent1_hover']);
		$fnt = unicaevents_get_custom_fonts_properties();
		$css = '

body {
	'.unicaevents_get_custom_font_css('p').';
}

h1 { '.unicaevents_get_custom_font_css('h1').'; '.unicaevents_get_custom_margins_css('h1').'; }
h2 { '.unicaevents_get_custom_font_css('h2').'; '.unicaevents_get_custom_margins_css('h2').'; }
h3 { '.unicaevents_get_custom_font_css('h3').'; '.unicaevents_get_custom_margins_css('h3').'; }
h4 { '.unicaevents_get_custom_font_css('h4').'; '.unicaevents_get_custom_margins_css('h4').'; }
h5 { '.unicaevents_get_custom_font_css('h5').'; '.unicaevents_get_custom_margins_css('h5').'; }
h6 { '.unicaevents_get_custom_font_css('h6').'; '.unicaevents_get_custom_margins_css('h6').'; }
a,
.scheme_dark a,
.scheme_light a {
	'.unicaevents_get_custom_font_css('link').';
	color: '.$clr['accent1'].';
}
a:hover,
.scheme_dark a:hover,
.scheme_light a:hover {
	color: '.$clr['accent1_hover'].';
}

/* Accent1 color - use it as background and border with next classes */
.accent1 {			color: '.$clr['accent1'].'; }
.accent1_bgc {		background-color: '.$clr['accent1'].'; }
.accent1_bg {		background: '.$clr['accent1'].'; }
.accent1_border {	border-color: '.$clr['accent1'].'; }

a.accent1:hover {	color: '.$clr['accent1_hover'].'; }


/* 2.1 Common colors
-------------------------------------------------------------- */

/* Portfolio hovers */
.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect3.colored .info,
.post_content.ih-item.circle.effect4.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect6.colored .info,
.post_content.ih-item.circle.effect7.colored .info,
.post_content.ih-item.circle.effect8.colored .info,
.post_content.ih-item.circle.effect9.colored .info,
.post_content.ih-item.circle.effect10.colored .info,
.post_content.ih-item.circle.effect11.colored .info,
.post_content.ih-item.circle.effect12.colored .info,
.post_content.ih-item.circle.effect13.colored .info,
.post_content.ih-item.circle.effect14.colored .info,
.post_content.ih-item.circle.effect15.colored .info,
.post_content.ih-item.circle.effect16.colored .info,
.post_content.ih-item.circle.effect18.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect1.colored .info,
.post_content.ih-item.square.effect2.colored .info,
.post_content.ih-item.square.effect3.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect5.colored .info,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect8.colored .info,
.post_content.ih-item.square.effect9.colored .info .info-back,
.post_content.ih-item.square.effect10.colored .info,
.post_content.ih-item.square.effect11.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect14.colored .info,
.post_content.ih-item.square.effect15.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect_book.colored .info {
	background: '.$clr['accent1'].';
}

.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect_more.colored .info,
.post_content.ih-item.square.effect_fade.colored:hover .info,
.post_content.ih-item.square.effect_dir.colored .info,
.post_content.ih-item.square.effect_shift.colored .info {
	background: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6);
}

.post_content.ih-item.square.effect_fade.colored .info {
	background: -moz-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(70%,rgba(255,255,255,0)), color-stop(100%,rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6)));
	background: -webkit-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: -o-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: -ms-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: linear-gradient(to bottom, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
}

.post_content.ih-item.circle.effect17.colored:hover .img:before {
	-webkit-box-shadow: inset 0 0 0 110px rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);
	-moz-box-shadow: inset 0 0 0 110px rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);
	box-shadow: inset 0 0 0 110px rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);
}

.post_content.ih-item.circle.effect1 .spinner {
	border-right-color: '.$clr['accent1'].';
	border-bottom-color: '.$clr['accent1'].';
}


/* Tables */
.sc_table table tr:first-child {
	background-color: '.$clr['accent1_hover'].';
}


/* Table of contents */
pre.code,
#toc .toc_item.current,
#toc .toc_item:hover {
	border-color: '.$clr['accent1'].';
}


::selection,
::-moz-selection { 
	background-color: '.$clr['accent1'].';
}




/* 3. Form fields settings
-------------------------------------------------------------- */

input[type="text"],
input[type="number"],
input[type="email"],
input[type="search"],
input[type="password"],
select,
textarea {
	'.unicaevents_get_custom_font_css('input').';
}


/* 7.1 Top panel
-------------------------------------------------------------- */

.top_panel_inner_style_3 .top_panel_cart_button,
.top_panel_inner_style_4 .top_panel_cart_button {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.2);
}
.top_panel_style_8 .top_panel_buttons .top_panel_cart_button:before {
	background-color: '.$clr['accent1'].';
}

.top_panel_inner_style_3 .top_panel_top,
.top_panel_inner_style_4 .top_panel_top,
.top_panel_inner_style_5 .top_panel_top,
.top_panel_inner_style_3 .top_panel_top .sidebar_cart,
.top_panel_inner_style_4 .top_panel_top .sidebar_cart {
	background-color: '.$clr['accent1'].';
}

.top_panel_top a:hover {
	color: '.$clr['accent1_hover'].';
}


.top_panel_inner_style_3 .search_results .post_info a > span,
.top_panel_inner_style_3 .search_results .post_info a[class*="icon-"] {
	color: '.$clr['inverse_text'].';
}
.top_panel_inner_style_3 .search_results .post_info a[class*="icon-"]:hover {
	color: '.$clr['inverse_hover'].';
}


/* User menu */
.menu_user_nav > li > a:hover {
	color: '.$clr['accent1_hover'].';
}

.top_panel_inner_style_3 .menu_user_nav > li > ul:after,
.top_panel_inner_style_4 .menu_user_nav > li > ul:after,
.top_panel_inner_style_5 .menu_user_nav > li > ul:after,
.top_panel_inner_style_3 .menu_user_nav > li ul,
.top_panel_inner_style_4 .menu_user_nav > li ul,
.top_panel_inner_style_5 .menu_user_nav > li ul {
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1_hover'].';
}
.top_panel_inner_style_3 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_3 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_3 .menu_user_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_4 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_4 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_4 .menu_user_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_5 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_5 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_5 .menu_user_nav > li ul li.current-menu-ancestor > a {
	background-color: '.$clr['accent1_hover'].';
}



/* Top panel - middle area */
.top_panel_middle .logo {
	'.unicaevents_get_custom_margins_css('logo').';
}
.logo .logo_text {
	'.unicaevents_get_custom_font_css('logo').';
}

.top_panel_middle .menu_main_wrap {
	margin-top: calc('.$fnt['logo_mt'].'*0.75);
}
.top_panel_style_5 .top_panel_middle .logo {
	margin-bottom: calc('.$fnt['logo_mb'].'*0.5);
}


/* Top panel (bottom area) */
.top_panel_bottom {
	background-color: '.$clr['accent1'].';
}



/* Top panel image in the header 7  */
.top_panel_image_hover {
	background-color: rgba('.$clr['accent1_hover_rgb']['r'].','.$clr['accent1_hover_rgb']['b'].','.$clr['accent1_hover_rgb']['b'].', 0.8);
}


/* Main menu */
.menu_main_nav > li > a {
	padding:'.$fnt['menu_mt'].' 1.5em '.$fnt['menu_mb'].';
	'.unicaevents_get_custom_font_css('menu').';
}
.menu_main_nav > li > a:hover,
.menu_main_nav > li.sfHover > a,
.menu_main_nav > li#blob,
.menu_main_nav > li.current-menu-item > a,
.menu_main_nav > li.current-menu-parent > a,
.menu_main_nav > li.current-menu-ancestor > a {
  color: '.$clr['accent1_hover'].';
}
.top_panel_inner_style_1 .menu_main_nav > li > a:hover,
.top_panel_inner_style_2 .menu_main_nav > li > a:hover {
	background-color: '.$clr['accent1_hover'].';
}
.top_panel_inner_style_1 .menu_main_nav > li ul,
.top_panel_inner_style_2 .menu_main_nav > li ul {
	border-color: '.$clr['accent1_hover'].';
	background-color: '.$clr['accent1'].';
}
.top_panel_inner_style_1 .menu_main_nav > a:hover,
.top_panel_inner_style_1 .menu_main_nav > li.sfHover > a,
.top_panel_inner_style_1 .menu_main_nav > li#blob,
.top_panel_inner_style_1 .menu_main_nav > li.current-menu-item > a,
.top_panel_inner_style_1 .menu_main_nav > li.current-menu-parent > a,
.top_panel_inner_style_1 .menu_main_nav > li.current-menu-ancestor > a,
.top_panel_inner_style_2 .menu_main_nav > a:hover,
.top_panel_inner_style_2 .menu_main_nav > li.sfHover > a,
.top_panel_inner_style_2 .menu_main_nav > li#blob,
.top_panel_inner_style_2 .menu_main_nav > li.current-menu-item > a,
.top_panel_inner_style_2 .menu_main_nav > li.current-menu-parent > a,
.top_panel_inner_style_2 .menu_main_nav > li.current-menu-ancestor > a {
	background-color: '.$clr['accent1_hover'].';
}
.menu_main_nav > li ul {
	'.unicaevents_get_custom_font_css('submenu').';
}
.menu_main_nav > li > ul {
	top: calc('.$fnt['menu_mt'].'+'.$fnt['menu_mb'].'+'.$fnt['menu_lh'].');
}
.menu_main_nav > li ul li a {
	padding: '.$fnt['submenu_mt'].' 1.5em '.$fnt['submenu_mb'].';
}
.menu_main_nav > li ul li a:hover,
.menu_main_nav > li ul li.current-menu-item > a,
.menu_main_nav > li ul li.current-menu-ancestor > a {
  color: '.$clr['accent1_hover'].';
}
.top_panel_inner_style_1 .menu_main_nav > li ul li a:hover,
.top_panel_inner_style_1 .menu_main_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_1 .menu_main_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_2 .menu_main_nav > li ul li a:hover,
.top_panel_inner_style_2 .menu_main_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_2 .menu_main_nav > li ul li.current-menu-ancestor > a {
	background-color: '.$clr['accent1_hover'].';
}


/* Responsive menu */
.menu_main_responsive_button {
	margin-top:'.$fnt['menu_mt'].';
	margin-bottom:'.$fnt['menu_mb'].';
}
.menu_main_responsive_button:hover {	
	color: '.$clr['accent1_hover'].'; 
}
.responsive_menu .top_panel_middle .menu_main_responsive_button {
	top: '.$fnt['logo_mt'].';
}
.responsive_menu .menu_main_responsive_button {
	margin-top:calc('.$fnt['menu_mt'].'*0.8);
	margin-bottom:calc('.$fnt['menu_mb'].'*0.6);
}

.top_panel_inner_style_1 .menu_main_responsive,
.top_panel_inner_style_2 .menu_main_responsive {
	background-color: '.$clr['accent1'].';
}
.top_panel_inner_style_1 .menu_main_responsive a:hover,
.top_panel_inner_style_2 .menu_main_responsive a:hover {
	background-color: '.$clr['accent1_hover'].'; 
}

/* Search field */
.top_panel_bottom .search_wrap,
.top_panel_inner_style_4 .search_wrap {
	padding-top:calc('.$fnt['menu_mt'].'*0.65);
	padding-bottom:calc('.$fnt['menu_mb'].'*0.5);
}
.top_panel_inner_style_1 .search_form_wrap,
.top_panel_inner_style_2 .search_form_wrap {
	background-color: rgba('.$clr['accent1_hover_rgb']['r'].','.$clr['accent1_hover_rgb']['g'].','.$clr['accent1_hover_rgb']['b'].', 0.2); 
}

.top_panel_icon {
	margin: calc('.$fnt['menu_mt'].'*0.7) 0 '.$fnt['menu_mb'].' 1em;
}
.top_panel_icon.search_wrap,
.top_panel_inner_style_5 .menu_main_responsive_button,
.top_panel_inner_style_6 .menu_main_responsive_button,
.top_panel_inner_style_7 .menu_main_responsive_button {
	color: '.$clr['accent1'].';
}
.top_panel_icon .contact_icon,
.top_panel_icon .search_submit {
	color: '.$clr['accent1'].';
}
.top_panel_middle a:hover .contact_icon,
.top_panel_icon.search_wrap:hover,
.top_panel_icon:hover .contact_icon,
.top_panel_icon:hover .search_submit,
.top_panel_inner_style_5 .menu_main_responsive_button:hover,
.top_panel_inner_style_6 .menu_main_responsive_button:hover,
.top_panel_inner_style_7 .menu_main_responsive_button:hover {
	background-color: '.$clr['accent1'].';
}

/* Search results */
.search_results .post_more,
.search_results .search_results_close {
	color: '.$clr['accent1'].';
}
.search_results .post_more:hover,
.search_results .search_results_close:hover {
	color: '.$clr['accent1_hover'].';
}
.top_panel_inner_style_1 .search_results,
.top_panel_inner_style_1 .search_results:after,
.top_panel_inner_style_2 .search_results,
.top_panel_inner_style_2 .search_results:after,
.top_panel_inner_style_3 .search_results,
.top_panel_inner_style_3 .search_results:after {
	background-color: '.$clr['accent1'].'; 
	border-color: '.$clr['accent1_hover'].'; 
}


/* Fixed menu */
.top_panel_fixed .menu_main_wrap {
	padding-top:calc('.$fnt['menu_mt'].'*0.3);
}
.top_panel_fixed .top_panel_wrap .logo {
	margin-top: calc('.$fnt['menu_mt'].'*0.6);
	margin-bottom: calc('.$fnt['menu_mb'].'*0.6);
}


/* Header style 8 */
.top_panel_inner_style_8 .top_panel_buttons,
.top_panel_inner_style_8 .menu_pushy_wrap .menu_pushy_button {
	padding-top: '.$fnt['menu_mt'].';
	padding-bottom: '.$fnt['menu_mb'].';
}
.pushy_inner a {
	color: '.$clr['accent1'].'; 
}
.pushy_inner a:hover {
	color: '.$clr['accent1_hover'].'; 
}

/* Register and login popups */
.top_panel_inner_style_3 .popup_wrap a,
.top_panel_inner_style_3 .popup_wrap .sc_socials.sc_socials_type_icons a:hover,
.top_panel_inner_style_4 .popup_wrap a,
.top_panel_inner_style_4 .popup_wrap .sc_socials.sc_socials_type_icons a:hover,
.top_panel_inner_style_5 .popup_wrap a,
.top_panel_inner_style_5 .popup_wrap .sc_socials.sc_socials_type_icons a:hover {
	color: '.$clr['accent1'].'; 
}
.top_panel_inner_style_3 .popup_wrap a:hover,
.top_panel_inner_style_4 .popup_wrap a:hover,
.top_panel_inner_style_5 .popup_wrap a:hover {
	color: '.$clr['accent1_hover'].'; 
}



/* 7.4 Main content wrapper
-------------------------------------------------------------- */

/* Layout Excerpt */
.post_title .post_icon {
	color: '.$clr['accent1'].';
}

/* Blog pagination */
.pagination > a {
	border-color: '.$clr['accent1'].';
}




/* 7.5 Post formats
-------------------------------------------------------------- */

/* Aside */
.post_format_aside.post_item_single .post_content p,
.post_format_aside .post_descr {
	border-color: '.$clr['accent1'].';
}




/* 7.6 Posts layouts
-------------------------------------------------------------- */

.post_info {
	'.unicaevents_get_custom_font_css('info').';
	'.unicaevents_get_custom_margins_css('info').';
}
.post_info a[class*="icon-"] {
	color: '.$clr['accent1'].';
}
.post_info a:hover {
	color: '.$clr['accent1_hover'].';
}

.post_item .post_readmore:hover .post_readmore_label {
	color: '.$clr['accent1_hover'].';
}

/* Related posts */
.post_item_related .post_info a:hover,
.post_item_related .post_title a:hover {
	color: '.$clr['accent1_hover'].';
}


/* Style "Colored" */
.isotope_item_colored .post_featured .post_mark_new,
.isotope_item_colored .post_featured .post_title,
.isotope_item_colored .post_content.ih-item.square.colored .info {
	background-color: '.$clr['accent1'].';
}

.isotope_item_colored .post_category a,
.isotope_item_colored .post_rating .reviews_stars_bg,
.isotope_item_colored .post_rating .reviews_stars_hover,
.isotope_item_colored .post_rating .reviews_value {
	color: '.$clr['accent1'].';
}

.isotope_item_colored .post_info_wrap .post_button .sc_button {
	color: '.$clr['accent1'].';
}

.isotope_item_colored_1 .post_item {
	background-color: '.$clr['alter_bg_color'].';
	color: '.$clr['alter_text'].';
}
.isotope_item_colored_1 a,
.isotope_item_colored_1 .post_title a {
	color: '.$clr['alter_link'].';
}
.isotope_item_colored_1 a:hover,
.isotope_item_colored_1 .post_title a:hover,
.isotope_item_colored_1 .post_category a:hover {
	color: '.$clr['alter_hover'].';
}


/* Masonry and Portfolio */
.isotope_wrap .isotope_item_colored_1 .post_featured {
	border-color: '.$clr['accent1'].';
}

/* Isotope filters */
.isotope_filters a {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.isotope_filters a.active,
.isotope_filters a:hover {
	border-color: '.$clr['accent1_hover'].';
	background-color: '.$clr['accent1_hover'].';
}




/* 7.7 Paginations
-------------------------------------------------------------- */

/* Style Pages and Slider */
.pagination_single > .pager_numbers,
.pagination_single a,
.pagination_slider .pager_cur,
.pagination_pages > a,
.pagination_pages > span {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.pagination_single > .pager_numbers,
.pagination_single a:hover,
.pagination_slider .pager_cur:hover,
.pagination_slider .pager_cur:focus,
.pagination_pages > .active,
.pagination_pages > a:hover {
	color: '.$clr['accent1'].';
}

.pagination_wrap .pager_next,
.pagination_wrap .pager_prev,
.pagination_wrap .pager_last,
.pagination_wrap .pager_first {
	color: '.$clr['accent1'].';
}
.pagination_wrap .pager_next:hover,
.pagination_wrap .pager_prev:hover,
.pagination_wrap .pager_last:hover,
.pagination_wrap .pager_first:hover {
	color: '.$clr['accent1_hover'].';
}



/* Style Load more */
.pagination_viewmore > a {
	background-color: '.$clr['accent1'].';
}
.pagination_viewmore > a:hover {
	background-color: '.$clr['accent1_hover'].';
}

/* Loader picture */
.viewmore_loader,
.mfp-preloader span,
.sc_video_frame.sc_video_active:before {
	background-color: '.$clr['accent1_hover'].';
}


/* 8 Single page parts
-------------------------------------------------------------- */


/* 8.1 Attachment and Portfolio post navigation
------------------------------------------------------------- */
.post_featured .post_nav_item:before {
	background-color: '.$clr['accent1'].';
}
.post_featured .post_nav_item .post_nav_info {
	background-color: '.$clr['accent1'].';
}


/* 8.2 Reviews block
-------------------------------------------------------------- */
.reviews_block .reviews_summary .reviews_item {
	background-color: '.$clr['accent1'].';
}
.reviews_block .reviews_summary,
.reviews_block .reviews_max_level_100 .reviews_stars_bg {
	background-color: '.$clr['alter_bg_hover'].';
}
.reviews_block .reviews_max_level_100 .reviews_stars_hover,
.reviews_block .reviews_item .reviews_slider {
	background-color: '.$clr['accent1'].';
}
.reviews_block .reviews_item .reviews_stars_hover {
	color: '.$clr['accent1'].';
}

/* Summary stars in the post item (under the title) */
.post_item .post_rating .reviews_stars_bg,
.post_item .post_rating .reviews_stars_hover,
.post_item .post_rating .reviews_value {
	color: '.$clr['accent1'].';
}


/* 8.3 Post author
-------------------------------------------------------------- */
.post_author .post_author_title a {
	color: '.$clr['accent1'].';
}
.post_author .post_author_title a:hover {
	color: '.$clr['accent1_hover'].';
}




/* 8.4 Comments
-------------------------------------------------------- */
.comments_list_wrap ul.children,
.comments_list_wrap ul > li + li {
	border-top-color: '.$clr['accent1'].';
}
.comments_list_wrap .comment-respond {
	border-bottom-color: '.$clr['accent1'].';
}
.comments_list_wrap > ul {
	border-bottom-color: '.$clr['accent1'].';
}

.comments_list_wrap .comment_info > span.comment_author,
.comments_list_wrap .comment_info > .comment_date > .comment_date_value {
	color: '.$clr['accent1'].';
}



/* 8.5 Page 404
-------------------------------------------------------------- */
.post_item_404 .page_title,
.post_item_404 .page_subtitle {
	font-family: '.$fnt['logo_ff'].';
	color: '.$clr['accent1'].';
}




/* 9. Sidebars
-------------------------------------------------------------- */

/* Side menu */
.sidebar_outer_menu .menu_side_nav > li > a,
.sidebar_outer_menu .menu_side_responsive > li > a {
	'.unicaevents_get_custom_font_css('menu').';
}
.sidebar_outer_menu .menu_side_nav > li ul,
.sidebar_outer_menu .menu_side_responsive > li ul {
	'.unicaevents_get_custom_font_css('submenu').';
}
.sidebar_outer_menu .menu_side_nav > li ul li a,
.sidebar_outer_menu .menu_side_responsive > li ul li a {
	padding: '.$fnt['submenu_mt'].' 1.5em '.$fnt['submenu_mb'].';
}
.sidebar_outer_menu .sidebar_outer_menu_buttons > a:hover,
.scheme_dark .sidebar_outer_menu .sidebar_outer_menu_buttons > a:hover,
.scheme_light .sidebar_outer_menu .sidebar_outer_menu_buttons > a:hover {
	color: '.$clr['accent1'].';
}

/* Common rules */
.widget_area_inner a,
.widget_area_inner ul li:before,
.widget_area_inner ul li a:hover,
.widget_area_inner button:before {
	color: '.$clr['accent1'].';
}
.widget_area_inner a:hover,
.widget_area_inner ul li a,
.widget_area_inner button:hover:before {
	color: '.$clr['accent1_hover'].';
}
.widget_area_inner .widget_text a,
.widget_area_inner .post_info a {
	color: '.$clr['accent1'].';
}
.widget_area_inner .widget_text a:hover,
.widget_area_inner .post_info a:hover {
	color: '.$clr['accent1_hover'].';
}


/* Widget: Calendar */
.widget_area_inner .widget_calendar td a:hover {
	background-color: '.$clr['accent1'].';
}
.widget_area_inner .widget_calendar .today .day_wrap {
	border-color: '.$clr['accent1'].';
}


/* Widget: Tag Cloud */
.widget_area_inner .widget_product_tag_cloud a,
.widget_area_inner .widget_tag_cloud a {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.widget_area_inner .widget_product_tag_cloud a:hover,
.widget_area_inner .widget_tag_cloud a:hover {
	color: '.$clr['accent1'].';
}


/* 10. Footer areas
-------------------------------------------------------------- */

/* Twitter and testimonials */
.testimonials_wrap_inner,
.twitter_wrap_inner {
  background-color: '.$clr['accent1'].';
}

/* Copyright */
.copyright_wrap_inner .menu_footer_nav li a:hover,
.scheme_dark .copyright_wrap_inner .menu_footer_nav li a:hover,
.scheme_light .copyright_wrap_inner .menu_footer_nav li a:hover {
    color: '.$clr['accent1'].';
}




/* 11. Utils
-------------------------------------------------------------- */

/* Scroll to top */
.scroll_to_top {
	background-color: '.$clr['accent1'].';
}
.scroll_to_top:hover {
	background-color: '.$clr['accent1_hover'].';
}
.custom_options #co_toggle {
	background-color: '.$clr['accent1_hover'].' !important;
}


/* 13.2 WooCommerce
------------------------------------------------------ */

/* Theme colors */
.woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-message:before,
.woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price,.woocommerce ul.products li.product .price,.woocommerce-page ul.products li.product .price,
.woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover,
.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover,
.woocommerce .quantity input[type="button"]:hover, .woocommerce #content input[type="button"]:hover, .woocommerce-page .quantity input[type="button"]:hover, .woocommerce-page #content .quantity input[type="button"]:hover,
.woocommerce ul.cart_list li > .amount, .woocommerce ul.product_list_widget li > .amount, .woocommerce-page ul.cart_list li > .amount, .woocommerce-page ul.product_list_widget li > .amount,
.woocommerce ul.cart_list li span .amount, .woocommerce ul.product_list_widget li span .amount, .woocommerce-page ul.cart_list li span .amount, .woocommerce-page ul.product_list_widget li span .amount,
.woocommerce ul.cart_list li ins .amount, .woocommerce ul.product_list_widget li ins .amount, .woocommerce-page ul.cart_list li ins .amount, .woocommerce-page ul.product_list_widget li ins .amount,
.woocommerce.widget_shopping_cart .total .amount, .woocommerce .widget_shopping_cart .total .amount, .woocommerce-page.widget_shopping_cart .total .amount, .woocommerce-page .widget_shopping_cart .total .amount,
.woocommerce a:hover h3, .woocommerce-page a:hover h3,
.woocommerce .cart-collaterals .order-total strong, .woocommerce-page .cart-collaterals .order-total strong,
.woocommerce .checkout #order_review .order-total .amount, .woocommerce-page .checkout #order_review .order-total .amount,
.woocommerce .star-rating, .woocommerce-page .star-rating, .woocommerce .star-rating:before, .woocommerce-page .star-rating:before,
.widget_area_inner .widgetWrap ul > li .star-rating span, .woocommerce #review_form #respond .stars a, .woocommerce-page #review_form #respond .stars a
{
	color: '.$clr['accent1'].';
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range
{ 
	background-color: '.$clr['accent1'].';
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle
{
	background: '.$clr['accent1'].';
}

.woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message,
.woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active,
.woocommerce a.button:active, .woocommerce button.button:active, .woocommerce input.button:active, .woocommerce #respond input#submit:active, .woocommerce #content input.button:active, .woocommerce-page a.button:active, .woocommerce-page button.button:active, .woocommerce-page input.button:active, .woocommerce-page #respond input#submit:active, .woocommerce-page #content input.button:active
{ 
	border-top-color: '.$clr['accent1'].';
}

/* Buttons */
.woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce-account .addresses .title .edit {
	background-color: '.$clr['accent1'].';
}
.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce-account .addresses .title .edit:hover {
	background-color: '.$clr['accent1_hover'].';
}

/* Products stream */
.woocommerce span.new, .woocommerce-page span.new,
.woocommerce span.onsale, .woocommerce-page span.onsale {
	background-color: '.$clr['accent1_hover'].';
}

.woocommerce ul.products li.product h3 a, .woocommerce-page ul.products li.product h3 a,
.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.woocommerce ul.products li.product .star-rating:before, .woocommerce ul.products li.product .star-rating span {
	color: '.$clr['accent1'].';
}
.woocommerce ul.products li.product h3 a:hover, .woocommerce-page ul.products li.product h3 a:hover {
	color: '.$clr['accent1_hover'].';
}

.woocommerce ul.products li.product .add_to_cart_button, .woocommerce-page ul.products li.product .add_to_cart_button {
	background-color: '.$clr['accent1'].';
}
.woocommerce ul.products li.product .add_to_cart_button:hover, .woocommerce-page ul.products li.product .add_to_cart_button:hover {
	background-color: '.$clr['accent1_hover'].';
}

/* Pagination */
.woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span.current {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current {
	color: '.$clr['accent1'].';
}

/* Cart */
.woocommerce table.cart thead th, .woocommerce #content table.cart thead th, .woocommerce-page table.cart thead th, .woocommerce-page #content table.cart thead th {
	background-color: '.$clr['accent1'].';
}


/* 13.3 Tribe Events
------------------------------------------------------- */
.tribe-events-calendar thead th {
	background-color: '.$clr['accent1'].';
}

/* Buttons */
a.tribe-events-read-more,
.tribe-events-button,
.tribe-events-nav-previous a,
.tribe-events-nav-next a,
.tribe-events-widget-link a,
.tribe-events-viewmore a {
	background-color: '.$clr['accent1'].';
}
a.tribe-events-read-more:hover,
.tribe-events-button:hover,
.tribe-events-nav-previous a:hover,
.tribe-events-nav-next a:hover,
.tribe-events-widget-link a:hover,
.tribe-events-viewmore a:hover {
	background-color: '.$clr['accent1_hover'].';
}




/* 13.4 BB Press and Buddy Press
------------------------------------------------------- */

/* Buttons */
#bbpress-forums div.bbp-topic-content a,
#buddypress button, #buddypress a.button, #buddypress input[type="submit"], #buddypress input[type="button"], #buddypress input[type="reset"], #buddypress ul.button-nav li a, #buddypress div.generic-button a, #buddypress .comment-reply-link, a.bp-title-button, #buddypress div.item-list-tabs ul li.selected a {
	background: '.$clr['accent1'].';
}
#bbpress-forums div.bbp-topic-content a:hover,
#buddypress button:hover, #buddypress a.button:hover, #buddypress input[type="submit"]:hover, #buddypress input[type="button"]:hover, #buddypress input[type="reset"]:hover, #buddypress ul.button-nav li a:hover, #buddypress div.generic-button a:hover, #buddypress .comment-reply-link:hover, a.bp-title-button:hover, #buddypress div.item-list-tabs ul li.selected a:hover {
	background: '.$clr['accent1_hover'].';
}

#buddypress #reply-title small a span, #buddypress a.bp-primary-action span {
	color: '.$clr['accent1'].';
}



/* 13.6 Booking Calendar
------------------------------------------------------- */
.booking_font_custom,
.booking_day_container,
.booking_calendar_container_all {
	font-family: '.$fnt['p_ff'].';
}
.booking_weekdays_custom {
	font-family: '.$fnt['h1_ff'].';
}
.booking_month_navigation_button_custom:hover {
	background-color: '.$clr['accent1_hover'].' !important;
}



/* 13.6 LearnDash LMS
------------------------------------------------------- */
#learndash_next_prev_link > a {
	background-color: '.$clr['accent1'].';
}
#learndash_next_prev_link > a:hover {
	background-color: '.$clr['accent1_hover'].';
}
.widget_area dd.course_progress div.course_progress_blue {
	background-color: '.$clr['accent1_hover'].';
}


/* 15. Shortcodes
-------------------------------------------------------------- */

/* Accordion */
.sc_accordion .sc_accordion_item .sc_accordion_title.ui-state-active {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_accordion .sc_accordion_item .sc_accordion_title.ui-state-active .sc_accordion_icon_opened {
	background-color: '.$clr['accent1'].';
}
.sc_accordion .sc_accordion_item .sc_accordion_title:hover {
	color: '.$clr['accent1_hover'].';
	border-color: '.$clr['accent1_hover'].';
}
.sc_accordion .sc_accordion_item .sc_accordion_title:hover .sc_accordion_icon_opened {
	background-color: '.$clr['accent1_hover'].';
}


/* Audio */
.sc_audio .sc_audio_author_name,
.sc_audio .sc_audio_title {
	color: '.$clr['accent1'].';
}
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, .mejs-controls .mejs-time-rail .mejs-time-current {
	background: '.$clr['accent1'].' !important;
}


/* Button */
input[type="submit"],
input[type="reset"],
input[type="button"],
button,
.sc_button {
	'.unicaevents_get_custom_font_css('button').';
}
input[type="submit"],
input[type="reset"],
input[type="button"],
button,
.sc_button.sc_button_style_filled {
	background-color: '.$clr['accent1'].';
}

input[type="submit"]:hover,
input[type="reset"]:hover,
input[type="button"]:hover,
button:hover,
.sc_button.sc_button_style_filled:hover {
	background-color: '.$clr['accent1_hover'].';
}
.sc_button.sc_button_style_border {
	border-color: '.$clr['accent1'].';
	color: '.$clr['accent1'].';
}
.sc_button.sc_button_style_border:hover {
	border-color: '.$clr['accent1_hover'].' !important;
}



/* Blogger */
.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date { 
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_blogger.layout_polaroid .photostack nav span.current {
	background-color: '.$clr['accent1'].';
}
.sc_blogger.layout_polaroid .photostack nav span.current.flip {
	background-color: '.$clr['accent1_hover'].';
}


/* Call to Action */
.sc_call_to_action_accented {
	background-color: '.$clr['accent1'].';
}
.sc_call_to_action_accented .sc_item_button > a {
	color: '.$clr['accent1'].';
}
.sc_call_to_action_accented .sc_item_button > a:before {
	background-color: '.$clr['accent1'].';
}

/* Chat */
.sc_chat_inner a {
  color: '.$clr['accent1'].';
}
.sc_chat_inner a:hover {
  color: '.$clr['accent1_hover'].';
}

/* Clients */
.sc_clients_style_clients-2 .sc_client_title a:hover {
	color: '.$clr['accent1'].';
}
.sc_clients_style_clients-2 .sc_client_description:before,
.sc_clients_style_clients-2 .sc_client_position {
	color: '.$clr['accent1'].';
}

/* Contact form */
.sc_form .sc_form_item.sc_form_button button { 
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_form .sc_form_item.sc_form_button button:hover { 
	background-color: '.$clr['accent1'].';
}

/* picker */
.sc_form table.picker__table th {
	background-color: '.$clr['accent1'].';
}
.sc_form .picker__day--today:before,
.sc_form .picker__button--today:before,
.sc_form .picker__button--clear:before,
.sc_form button:focus {
	border-color: '.$clr['accent1'].';
}
.sc_form .picker__button--close:before {
	color: '.$clr['accent1'].';
}
.sc_form .picker--time .picker__button--clear:hover,
.sc_form .picker--time .picker__button--clear:focus {
	background-color: '.$clr['accent1_hover'].';
}


/* Countdown Style 1 */
.sc_countdown.sc_countdown_style_1 .sc_countdown_digits,
.sc_countdown.sc_countdown_style_1 .sc_countdown_separator {
	color: '.$clr['accent1'].';
}
.sc_countdown.sc_countdown_style_1 .sc_countdown_label {
	color: '.$clr['accent1'].';
}

/* Countdown Style 2 */
.sc_countdown.sc_countdown_style_2 .sc_countdown_separator {
	color: '.$clr['accent1'].';
}
.sc_countdown.sc_countdown_style_2 .sc_countdown_digits span {
	background-color: '.$clr['accent1'].';
}
.sc_countdown.sc_countdown_style_2 .sc_countdown_label {
	color: '.$clr['accent1'].';
}

/* Dropcaps */
.sc_dropcaps.sc_dropcaps_style_1 .sc_dropcaps_item {
	color: '.$clr['accent1'].';
}
.sc_dropcaps.sc_dropcaps_style_2 .sc_dropcaps_item {
	background-color: '.$clr['accent1'].';
} 
.sc_dropcaps.sc_dropcaps_style_3 .sc_dropcaps_item {
	background-color: '.$clr['accent1_hover'].';
} 
.sc_dropcaps.sc_dropcaps_style_4 .sc_dropcaps_item {
	color: '.$clr['accent1'].';
} 

/* Events */
.sc_events_style_events-2 .sc_events_item_date {
	background-color: '.$clr['accent1'].';
}


/* Highlight */
.sc_highlight_style_1 {
	background-color: '.$clr['accent1'].';
}
.sc_highlight_style_2 {
	background-color: '.$clr['accent1_hover'].';
}


/* Icon */
.sc_icon_hover:hover,
a:hover .sc_icon_hover {
	background-color: '.$clr['accent1'].' !important; 
}

.sc_icon_shape_round.sc_icon,
.sc_icon_shape_square.sc_icon {	
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}

.sc_icon_shape_round.sc_icon:hover,
.sc_icon_shape_square.sc_icon:hover,
a:hover .sc_icon_shape_round.sc_icon,
a:hover .sc_icon_shape_square.sc_icon {
	color: '.$clr['accent1'].';
}


/* Image */
figure figcaption,
.sc_image figcaption {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6);
}


/* Infobox */
.sc_infobox.sc_infobox_style_regular {
	background-color: '.$clr['accent1'].';
}


/* List */
.sc_list_style_iconed li:before,
.sc_list_style_iconed .sc_list_icon {
	color: '.$clr['accent1'].';
}
.sc_list_style_iconed li a:hover .sc_list_title {
	color: '.$clr['accent1_hover'].';
}


/* Popup */
.sc_popup:before {
	background-color: '.$clr['accent1'].';
}


/* Price block */
.sc_price_block.sc_price_block_style_1 {
	background-color: '.$clr['accent1'].';
}
.sc_price_block.sc_price_block_style_2 {
	background-color: '.$clr['accent1_hover'].';
}

/* Section */
.sc_services_item .sc_services_item_readmore span {
	color: '.$clr['accent1'].';
}
.sc_services_item .sc_services_item_readmore:hover,
.sc_services_item .sc_services_item_readmore:hover span {
	color: '.$clr['accent1_hover'].';
}


/* Services */
.sc_services_item .sc_services_item_readmore span {
  color: '.$clr['accent1'].';
}
.sc_services_item .sc_services_item_readmore:hover,
.sc_services_item .sc_services_item_readmore:hover span {
  color: '.$clr['accent1_hover'].';
}
.sc_services_style_services-1 .sc_icon,
.sc_services_style_services-2 .sc_icon {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_services_style_services-1 .sc_icon:hover,
.sc_services_style_services-1 a:hover .sc_icon,
.sc_services_style_services-2 .sc_icon:hover,
.sc_services_style_services-2 a:hover .sc_icon {
	background-color: '.$clr['accent1'].';
}
.sc_services_style_services-3 a:hover .sc_icon,
.sc_services_style_services-3 .sc_icon:hover {
	color: '.$clr['accent1'].';
}
.sc_services_style_services-3 a:hover .sc_services_item_title {
	color: '.$clr['accent1'].';
}
.sc_services_style_services-4 .sc_icon {
	background-color: '.$clr['accent1'].';
}
.sc_services_style_services-4 a:hover .sc_icon,
.sc_services_style_services-4 .sc_icon:hover {
	background-color: '.$clr['accent1_hover'].';
}
.sc_services_style_services-4 a:hover .sc_services_item_title {
	color: '.$clr['accent1'].';
}


/* Scroll controls */
.sc_scroll_controls_wrap a {
	background-color: '.$clr['accent1'].';
}
.sc_scroll_controls_type_side .sc_scroll_controls_wrap a {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8);
}
.sc_scroll_controls_wrap a:hover {
	background-color: '.$clr['accent1_hover'].';
}
.sc_scroll_bar .swiper-scrollbar-drag:before {
	background-color: '.$clr['accent1'].';
}

/* Skills */
.sc_skills_counter .sc_skills_item .sc_skills_icon {
	color: '.$clr['accent1'].';
}
.sc_skills_counter .sc_skills_item:hover .sc_skills_icon {
	color: '.$clr['accent1_hover'].';
}
.sc_skills_bar .sc_skills_item .sc_skills_count {
	border-color: '.$clr['accent1'].';
}

.sc_skills_bar .sc_skills_item .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_3 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_info {
	background-color: '.$clr['accent1'].';
}

/* Slider */
.sc_slider_controls_wrap a:hover {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.sc_slider_swiper .sc_slider_pagination_wrap .swiper-pagination-bullet-active,
.sc_slider_swiper .sc_slider_pagination_wrap span:hover {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.sc_slider_swiper .sc_slider_info {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8) !important;
}
.sc_slider_pagination_over .sc_slider_pagination_wrap span:hover,
.sc_slider_pagination_over .sc_slider_pagination_wrap .swiper-pagination-bullet-active {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}


/* Socials */
.sc_socials.sc_socials_type_icons a:hover,
.scheme_dark .sc_socials.sc_socials_type_icons a:hover,
.scheme_light .sc_socials.sc_socials_type_icons a:hover {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}


/* Tabs */
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a,
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li a:hover {
	color: '.$clr['accent1'].';
}
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a:after {
	background-color: '.$clr['accent1'].';
}
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a:hover,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a {
	color: '.$clr['accent1'].';
}



/* Team */
.sc_team_item .sc_team_item_info .sc_team_item_title a:hover {
	color: '.$clr['accent1_hover'].';
}
.sc_team_item .sc_team_item_info .sc_team_item_position {
	color: '.$clr['accent1'].';
}
.sc_team_style_team-1 .sc_team_item_info,
.sc_team_style_team-3 .sc_team_item_info {
	border-color: '.$clr['accent1'].';
}
.sc_team.sc_team_style_team-3 .sc_team_item_avatar .sc_team_item_hover {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8);
}
.sc_team.sc_team_style_team-4 .sc_socials_item a:hover {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_team_style_team-4 .sc_team_item_info .sc_team_item_title a:hover {
	color: '.$clr['accent1'].';
}


/* Testimonials */
.sc_testimonials_style_testimonials-3 .sc_testimonial_content p:first-child:before,
.sc_testimonials_style_testimonials-3 .sc_testimonial_author_position {
	color: '.$clr['accent1'].';
}
.sc_testimonials_style_testimonials-4 .sc_testimonial_content p:first-child:before,
.sc_testimonials_style_testimonials-4 .sc_testimonial_author_position {
	color: '.$clr['accent1'].';
}

/* Title */
.sc_title_icon {
	color: '.$clr['accent1'].';
}

/* Toggles */
.sc_toggles .sc_toggles_item .sc_toggles_title.ui-state-active {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_toggles .sc_toggles_item .sc_toggles_title.ui-state-active .sc_toggles_icon_opened {
	background-color: '.$clr['accent1'].';
}
.sc_toggles .sc_toggles_item .sc_toggles_title:hover {
	color: '.$clr['accent1_hover'].';
	border-color: '.$clr['accent1_hover'].';
}
.sc_toggles .sc_toggles_item .sc_toggles_title:hover .sc_toggles_icon_opened {
	background-color: '.$clr['accent1_hover'].';
}


/* Tooltip */
.sc_tooltip_parent .sc_tooltip,
.sc_tooltip_parent .sc_tooltip:before {
	background-color: '.$clr['accent1'].';
}

/* Common styles (title, subtitle and description for some shortcodes) */
.sc_item_subtitle {
	color: '.$clr['accent1'].';
}
.sc_item_title:after {
	background-color: '.$clr['accent1'].';
}
.sc_item_button > a:before {
	color: '.$clr['accent1'].';
}
.sc_item_button > a:hover:before {
	color: '.$clr['accent1_hover'].';
}
';		
		return $custom_style.$css;	
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

// Remove list files for compilation
if (!function_exists('unicaevents_filter_skin_compile_less')) {
	//add_filter('unicaevents_filter_compile_less', 'unicaevents_filter_skin_compile_less');
	function unicaevents_filter_skin_compile_less($files) {
		return array();	
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
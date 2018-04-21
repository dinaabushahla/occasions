<?php
/**
 * UnicaEvents Framework: Theme specific actions
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_core_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_core_theme_setup', 11 );
	function unicaevents_core_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add user menu
		add_theme_support('nav-menus');
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Editor custom stylesheet - for user
		add_editor_style(unicaevents_get_file_url('css/editor-style.css'));	
		
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'unicaevents', unicaevents_get_folder_dir('languages') );


		/* Front and Admin actions and filters:
		------------------------------------------------------------------------ */

		if ( !is_admin() ) {
			
			/* Front actions and filters:
			------------------------------------------------------------------------ */
	
			// Filters wp_title to print a neat <title> tag based on what is being viewed
			if (floatval(get_bloginfo('version')) < "4.1") {
				add_filter('wp_title',						'unicaevents_wp_title', 10, 2);
			}

			// Add main menu classes
			//add_filter('wp_nav_menu_objects', 			'unicaevents_add_mainmenu_classes', 10, 2);
	
			// Prepare logo text
			add_filter('unicaevents_filter_prepare_logo_text',	'unicaevents_prepare_logo_text', 10, 1);
	
			// Add class "widget_number_#' for each widget
			add_filter('dynamic_sidebar_params', 			'unicaevents_add_widget_number', 10, 1);

			// Frontend editor: Save post data
			add_action('wp_ajax_frontend_editor_save',		'unicaevents_callback_frontend_editor_save');
			add_action('wp_ajax_nopriv_frontend_editor_save', 'unicaevents_callback_frontend_editor_save');

			// Frontend editor: Delete post
			add_action('wp_ajax_frontend_editor_delete', 	'unicaevents_callback_frontend_editor_delete');
			add_action('wp_ajax_nopriv_frontend_editor_delete', 'unicaevents_callback_frontend_editor_delete');
	
			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 				'unicaevents_core_frontend_scripts');
			add_action('wp_footer',		 					'unicaevents_core_frontend_scripts_inline');
			add_action('unicaevents_action_add_scripts_inline','unicaevents_core_add_scripts_inline');

			// Prepare theme core global variables
			add_action('unicaevents_action_prepare_globals',	'unicaevents_core_prepare_globals');

		}

		// Register theme specific nav menus
		unicaevents_register_theme_menus();

		// Register theme specific sidebars
		unicaevents_register_theme_sidebars();
	}
}




/* Theme init
------------------------------------------------------------------------ */

// Init theme template
function unicaevents_core_init_theme() {
	global $UNICAEVENTS_GLOBALS;
	if (!empty($UNICAEVENTS_GLOBALS['theme_inited'])) return;
	$UNICAEVENTS_GLOBALS['theme_inited'] = true;

	// Load custom options from GET and post/page/cat options
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (unicaevents_get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop / event
	unicaevents_load_custom_options();

	// Load skin
	$skin = unicaevents_esc(unicaevents_get_custom_option('theme_skin'));
	$UNICAEVENTS_GLOBALS['theme_skin'] = $skin;
	if ( file_exists(unicaevents_get_file_dir('skins/'.($skin).'/skin.php')) ) {
		require_once unicaevents_get_file_dir('skins/'.($skin).'/skin.php');
	}

	// Fire init theme actions (after skin and custom options are loaded)
	do_action('unicaevents_action_init_theme');

	// Prepare theme core global variables
	do_action('unicaevents_action_prepare_globals');

	// Fire after init theme actions
	do_action('unicaevents_action_after_init_theme');
}


// Prepare theme global variables
if ( !function_exists( 'unicaevents_core_prepare_globals' ) ) {
	function unicaevents_core_prepare_globals() {
		if (!is_admin()) {
			// AJAX Queries settings
			global $UNICAEVENTS_GLOBALS;
		
			// Logo text and slogan
			$UNICAEVENTS_GLOBALS['logo_text'] = apply_filters('unicaevents_filter_prepare_logo_text', unicaevents_get_custom_option('logo_text'));
			$slogan = unicaevents_get_custom_option('logo_slogan');
			if (!$slogan) $slogan = get_bloginfo ( 'description' );
			$UNICAEVENTS_GLOBALS['logo_slogan'] = $slogan;
			
			// Logo image and icons from skin
			$logo_side   = unicaevents_get_logo_icon('logo_side');
			$logo_fixed  = unicaevents_get_logo_icon('logo_fixed');
			$logo_footer = unicaevents_get_logo_icon('logo_footer');
			$UNICAEVENTS_GLOBALS['logo']        = unicaevents_get_logo_icon('logo');
			$UNICAEVENTS_GLOBALS['logo_icon']   = unicaevents_get_logo_icon('logo_icon');
			$UNICAEVENTS_GLOBALS['logo_side']   = $logo_side   ? $logo_side   : $UNICAEVENTS_GLOBALS['logo'];
			$UNICAEVENTS_GLOBALS['logo_fixed']  = $logo_fixed  ? $logo_fixed  : $UNICAEVENTS_GLOBALS['logo'];
			$UNICAEVENTS_GLOBALS['logo_footer'] = $logo_footer ? $logo_footer : $UNICAEVENTS_GLOBALS['logo'];
	
			$shop_mode = '';
			if (unicaevents_get_custom_option('show_mode_buttons')=='yes')
				$shop_mode = unicaevents_get_value_gpc('unicaevents_shop_mode');
			if (empty($shop_mode))
				$shop_mode = unicaevents_get_custom_option('shop_mode', '');
			if (empty($shop_mode) || !is_archive())
				$shop_mode = 'thumbs';
			$UNICAEVENTS_GLOBALS['shop_mode'] = $shop_mode;
		}
	}
}


// Return url for the uploaded logo image or (if not uploaded) - to image from skin folder
if ( !function_exists( 'unicaevents_get_logo_icon' ) ) {
	function unicaevents_get_logo_icon($slug) {
		$logo_icon = unicaevents_get_custom_option($slug);
		return $logo_icon;
	}
}


// Display logo image with text and slogan (if specified)
if ( !function_exists( 'unicaevents_show_logo' ) ) {
	function unicaevents_show_logo($logo_main=true, $logo_fixed=false, $logo_footer=false, $logo_side=false, $logo_text=true, $logo_slogan=true) {
		global $UNICAEVENTS_GLOBALS;
		if ($logo_main===true)		$logo_main = $UNICAEVENTS_GLOBALS['logo'];
		if ($logo_fixed===true)		$logo_fixed = $UNICAEVENTS_GLOBALS['logo_fixed'];
		if ($logo_footer===true)	$logo_footer = $UNICAEVENTS_GLOBALS['logo_footer'];
		if ($logo_side===true)		$logo_side = $UNICAEVENTS_GLOBALS['logo_side'];
		if ($logo_text===true)		$logo_text = $UNICAEVENTS_GLOBALS['logo_text'];
		if ($logo_slogan===true)	$logo_slogan = $UNICAEVENTS_GLOBALS['logo_slogan'];
		if ($logo_main || $logo_fixed || $logo_footer || $logo_side || $logo_text) {
		?>
		<div class="logo">
			<a href="<?php echo esc_url(home_url('/')); ?>"><?php
				if (!empty($logo_main)) {
					$attr = getimagesize($logo_main);
					echo '<img src="'.esc_url($logo_main).'" class="logo_main" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_fixed)) {
					$attr = getimagesize($logo_fixed);
					echo '<img src="'.esc_url($logo_fixed).'" class="logo_fixed" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_footer)) {
					$attr = getimagesize($logo_footer);
					echo '<img src="'.esc_url($logo_footer).'" class="logo_footer" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_side)) {
					$attr = getimagesize($logo_side);
					echo '<img src="'.esc_url($logo_side).'" class="logo_side" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				echo !empty($logo_text) ? '<div class="logo_text">'.trim($UNICAEVENTS_GLOBALS['logo_text']).'</div>' : '';
				echo !empty($logo_slogan) ? '<div class="logo_slogan">' . esc_html($UNICAEVENTS_GLOBALS['logo_slogan']) . '</div>' : '';
			?></a>
		</div>
		<?php 
		}
	} 
}


// Add menu locations
if ( !function_exists( 'unicaevents_register_theme_menus' ) ) {
	function unicaevents_register_theme_menus() {
		register_nav_menus(apply_filters('unicaevents_filter_add_theme_menus', array(
			'menu_main'		=> esc_html__('Main Menu', 'unicaevents'),
			'menu_user'		=> esc_html__('User Menu', 'unicaevents'),
			'menu_footer'	=> esc_html__('Footer Menu', 'unicaevents'),
			'menu_side'		=> esc_html__('Side Menu', 'unicaevents')
		)));
	}
}


// Register widgetized area
if ( !function_exists( 'unicaevents_register_theme_sidebars' ) ) {
	function unicaevents_register_theme_sidebars($sidebars=array()) {
		global $UNICAEVENTS_GLOBALS;
		if (!is_array($sidebars)) $sidebars = array();
		// Custom sidebars
		$custom = unicaevents_get_theme_option('custom_sidebars');
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$sidebars['sidebar_custom_'.($i)]  = $sb;
			}
		}
		$sidebars = apply_filters( 'unicaevents_filter_add_theme_sidebars', $sidebars );
		$UNICAEVENTS_GLOBALS['registered_sidebars'] = $sidebars;
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $id=>$name) {
				register_sidebar( array(
					'name'          => $name,
					'id'            => $id,
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h5 class="widget_title">',
					'after_title'   => '</h5>',
				) );
			}
		}
	}
}





/* Front actions and filters:
------------------------------------------------------------------------ */

//  Enqueue scripts and styles
if ( !function_exists( 'unicaevents_core_frontend_scripts' ) ) {
	function unicaevents_core_frontend_scripts() {
		global $UNICAEVENTS_GLOBALS;
		
		// Modernizr will load in head before other scripts and styles
		// Use older version (from photostack)
		unicaevents_enqueue_script( 'unicaevents-core-modernizr-script', unicaevents_get_file_url('js/photostack/modernizr.min.js'), array(), null, false );
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		
		// Prepare custom fonts
		$fonts = unicaevents_get_list_fonts(false);
		$theme_fonts = array();
		$custom_fonts = unicaevents_get_custom_fonts();
		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
			foreach ($custom_fonts as $s=>$f) {
				if (!empty($f['font-family']) && !unicaevents_is_inherit_option($f['font-family'])) $theme_fonts[$f['font-family']] = 1;
			}
		}
		// Prepare current skin fonts
		$theme_fonts = apply_filters('unicaevents_filter_used_fonts', $theme_fonts);
		// Link to selected fonts
		if (is_array($theme_fonts) && count($theme_fonts) > 0) {
			$google_fonts = '';
			foreach ($theme_fonts as $font=>$v) {
				if (isset($fonts[$font])) {
					$font_name = ($pos=unicaevents_strpos($font,' ('))!==false ? unicaevents_substr($font, 0, $pos) : $font;
					if (!empty($fonts[$font]['css'])) {
						$css = $fonts[$font]['css'];
						unicaevents_enqueue_style( 'unicaevents-font-'.str_replace(' ', '-', $font_name).'-style', $css, array(), null );
					} else {
						$google_fonts .= ($google_fonts ? '|' : '') 
							. (!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name).':300,300italic,400,400italic,700,700italic');
					}
				}
			}
			if ($google_fonts)
				unicaevents_enqueue_style( 'unicaevents-font-google_fonts-style', unicaevents_get_protocol() . '://fonts.googleapis.com/css?family=' . $google_fonts . '&subset=' . unicaevents_get_theme_option('fonts_subset'), array(), null );
		}
		
		// Fontello styles must be loaded before main stylesheet
		unicaevents_enqueue_style( 'unicaevents-fontello-style',  unicaevents_get_file_url('css/fontello/css/fontello.css'),  array(), null);
		//unicaevents_enqueue_style( 'unicaevents-fontello-animation-style', unicaevents_get_file_url('css/fontello/css/animation.css'), array(), null);

		// Main stylesheet
		unicaevents_enqueue_style( 'unicaevents-main-style', get_stylesheet_uri(), array(), null );
		
		// Animations
		if (unicaevents_get_theme_option('css_animation')=='yes')
			unicaevents_enqueue_style( 'unicaevents-animation-style',	unicaevents_get_file_url('css/core.animation.css'), array(), null );

		// Theme skin stylesheet
		do_action('unicaevents_action_add_styles');
		
		// Theme customizer stylesheet and inline styles
		unicaevents_enqueue_custom_styles();

		// Responsive
		if (unicaevents_get_theme_option('responsive_layouts') == 'yes') {
			$suffix = unicaevents_param_is_off(unicaevents_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
			unicaevents_enqueue_style( 'unicaevents-responsive-style', unicaevents_get_file_url('css/responsive'.($suffix).'.css'), array(), null );
			do_action('unicaevents_action_add_responsive');
			if (unicaevents_get_custom_option('theme_skin')!='') {
				$css = apply_filters('unicaevents_filter_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style( 'unicaevents-responsive-style', $css );
			}
		}

		// Disable loading JQuery UI CSS
		//global $wp_styles, $wp_scripts;
		//$wp_styles->done[]	= 'jquery-ui';
		//$wp_styles->done[]	= 'date-picker-css';
		wp_deregister_style('jquery_ui');
		wp_deregister_style('date-picker-css');


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		
		// Load separate theme scripts
		unicaevents_enqueue_script( 'superfish', unicaevents_get_file_url('js/superfish.min.js'), array('jquery'), null, true );
		if (unicaevents_get_theme_option('menu_slider')=='yes') {
			unicaevents_enqueue_script( 'unicaevents-slidemenu-script', unicaevents_get_file_url('js/jquery.slidemenu.js'), array('jquery'), null, true );
			//unicaevents_enqueue_script( 'unicaevents-jquery-easing-script', unicaevents_get_file_url('js/jquery.easing.js'), array('jquery'), null, true );
		}

		if ( is_single() && unicaevents_get_custom_option('show_reviews')=='yes' ) {
			unicaevents_enqueue_script( 'unicaevents-core-reviews-script', unicaevents_get_file_url('js/core.reviews.js'), array('jquery'), null, true );
		}

		unicaevents_enqueue_script( 'unicaevents-core-utils-script',	unicaevents_get_file_url('js/core.utils.js'), array('jquery'), null, true );
		unicaevents_enqueue_script( 'unicaevents-core-init-script',	unicaevents_get_file_url('js/core.init.js'), array('jquery'), null, true );	
		unicaevents_enqueue_script( 'unicaevents-theme-init-script',	unicaevents_get_file_url('js/theme.init.js'), array('jquery'), null, true );	

		// Media elements library	
		if (unicaevents_get_theme_option('use_mediaelement')=='yes') {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
//			$wp_styles->done[]	= 'mediaelement';
//			$wp_styles->done[]	= 'wp-mediaelement';
//			$wp_scripts->done[]	= 'mediaelement';
//			$wp_scripts->done[]	= 'wp-mediaelement';
			wp_deregister_style('mediaelement');
			wp_deregister_style('wp-mediaelement');
			wp_deregister_script('mediaelement');
			wp_deregister_script('wp-mediaelement');
		}
		
		// Video background
		if (unicaevents_get_custom_option('show_video_bg') == 'yes' && unicaevents_get_custom_option('video_bg_youtube_code') != '') {
			unicaevents_enqueue_script( 'unicaevents-video-bg-script', unicaevents_get_file_url('js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}

		// Google map
		if ( unicaevents_get_custom_option('show_googlemap')=='yes' ) { 
			unicaevents_enqueue_script( 'googlemap', unicaevents_get_protocol().'://maps.google.com/maps/api/js?sensor=false', array(), null, true );
			unicaevents_enqueue_script( 'unicaevents-googlemap-script', unicaevents_get_file_url('js/core.googlemap.js'), array(), null, true );
		}

			
		// Social share buttons
		if (is_singular() && !unicaevents_get_global('blog_streampage') && unicaevents_get_custom_option('show_share')!='hide') {
			unicaevents_enqueue_script( 'unicaevents-social-share-script', unicaevents_get_file_url('js/social/social-share.js'), array('jquery'), null, true );
		}

		// Comments
		if ( is_singular() && !unicaevents_get_global('blog_streampage') && comments_open() && get_option( 'thread_comments' ) ) {
			unicaevents_enqueue_script( 'comment-reply', false, array(), null, true );
		}

		// Custom panel
		if (unicaevents_get_theme_option('show_theme_customizer') == 'yes') {
			if (file_exists(unicaevents_get_file_dir('core/core.customizer/front.customizer.css')))
				unicaevents_enqueue_style(  'unicaevents-customizer-style',  unicaevents_get_file_url('core/core.customizer/front.customizer.css'), array(), null );
			if (file_exists(unicaevents_get_file_dir('core/core.customizer/front.customizer.js')))
				unicaevents_enqueue_script( 'unicaevents-customizer-script', unicaevents_get_file_url('core/core.customizer/front.customizer.js'), array(), null, true );	
		}
		
		//Debug utils
		if (unicaevents_get_theme_option('debug_mode')=='yes') {
			unicaevents_enqueue_script( 'unicaevents-core-debug-script', unicaevents_get_file_url('js/core.debug.js'), array(), null, true );
		}

		// Theme skin script
		do_action('unicaevents_action_add_scripts');
	}
}

//  Enqueue Swiper Slider scripts and styles
if ( !function_exists( 'unicaevents_enqueue_slider' ) ) {
	function unicaevents_enqueue_slider($engine='all') {
		if ($engine=='all' || $engine=='swiper') {
			unicaevents_enqueue_style(  'unicaevents-swiperslider-style', 			unicaevents_get_file_url('js/swiper/swiper.css'), array(), null );
			//unicaevents_enqueue_script( 'unicaevents-swiperslider-script', 			unicaevents_get_file_url('js/swiper/swiper.js'), array(), null, true );
			unicaevents_enqueue_script( 'unicaevents-swiperslider-script', 			unicaevents_get_file_url('js/swiper/swiper.jquery.js'), array(), null, true );
		}
	}
}

//  Enqueue Photostack gallery
if ( !function_exists( 'unicaevents_enqueue_polaroid' ) ) {
	function unicaevents_enqueue_polaroid() {
		unicaevents_enqueue_style(  'unicaevents-polaroid-style', 	unicaevents_get_file_url('js/photostack/component.css'), array(), null );
		unicaevents_enqueue_script( 'unicaevents-classie-script',		unicaevents_get_file_url('js/photostack/classie.js'), array(), null, true );
		unicaevents_enqueue_script( 'unicaevents-polaroid-script',	unicaevents_get_file_url('js/photostack/photostack.js'), array(), null, true );
	}
}

//  Enqueue Messages scripts and styles
if ( !function_exists( 'unicaevents_enqueue_messages' ) ) {
	function unicaevents_enqueue_messages() {
		unicaevents_enqueue_style(  'unicaevents-messages-style',		unicaevents_get_file_url('js/core.messages/core.messages.css'), array(), null );
		unicaevents_enqueue_script( 'unicaevents-messages-script',	unicaevents_get_file_url('js/core.messages/core.messages.js'),  array('jquery'), null, true );
	}
}

//  Enqueue Portfolio hover scripts and styles
if ( !function_exists( 'unicaevents_enqueue_portfolio' ) ) {
	function unicaevents_enqueue_portfolio($hover='') {
		unicaevents_enqueue_style( 'unicaevents-portfolio-style',  unicaevents_get_file_url('css/core.portfolio.css'), array(), null );
		if (unicaevents_strpos($hover, 'effect_dir')!==false)
			unicaevents_enqueue_script( 'hoverdir', unicaevents_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
	}
}

//  Enqueue Charts and Diagrams scripts and styles
if ( !function_exists( 'unicaevents_enqueue_diagram' ) ) {
	function unicaevents_enqueue_diagram($type='all') {
		if ($type=='all' || $type=='pie') unicaevents_enqueue_script( 'unicaevents-diagram-chart-script',	unicaevents_get_file_url('js/diagram/chart.min.js'), array(), null, true );
		if ($type=='all' || $type=='arc') unicaevents_enqueue_script( 'unicaevents-diagram-raphael-script',	unicaevents_get_file_url('js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );
	}
}

// Enqueue Theme Popup scripts and styles
// Link must have attribute: data-rel="popup" or data-rel="popup[gallery]"
if ( !function_exists( 'unicaevents_enqueue_popup' ) ) {
	function unicaevents_enqueue_popup($engine='') {
		if ($engine=='pretty' || (empty($engine) && unicaevents_get_theme_option('popup_engine')=='pretty')) {
			unicaevents_enqueue_style(  'unicaevents-prettyphoto-style',	unicaevents_get_file_url('js/prettyphoto/css/prettyPhoto.css'), array(), null );
			unicaevents_enqueue_script( 'unicaevents-prettyphoto-script',	unicaevents_get_file_url('js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		} else if ($engine=='magnific' || (empty($engine) && unicaevents_get_theme_option('popup_engine')=='magnific')) {
			unicaevents_enqueue_style(  'unicaevents-magnific-style',	unicaevents_get_file_url('js/magnific/magnific-popup.css'), array(), null );
			unicaevents_enqueue_script( 'unicaevents-magnific-script',unicaevents_get_file_url('js/magnific/jquery.magnific-popup.min.js'), array('jquery'), '', true );
		} else if ($engine=='internal' || (empty($engine) && unicaevents_get_theme_option('popup_engine')=='internal')) {
			unicaevents_enqueue_messages();
		}
	}
}

//  Add inline scripts in the footer hook
if ( !function_exists( 'unicaevents_core_frontend_scripts_inline' ) ) {
	function unicaevents_core_frontend_scripts_inline() {
		do_action('unicaevents_action_add_scripts_inline');
	}
}

//  Add inline scripts in the footer
if (!function_exists('unicaevents_core_add_scripts_inline')) {
	function unicaevents_core_add_scripts_inline() {
		global $UNICAEVENTS_GLOBALS;
		
		$msg = unicaevents_get_system_message(true); 
		if (!empty($msg['message'])) unicaevents_enqueue_messages();

		echo "<script type=\"text/javascript\">"
			
			. "if (typeof UNICAEVENTS_GLOBALS == 'undefined') var UNICAEVENTS_GLOBALS = {};"			
			
			// AJAX parameters
			. "UNICAEVENTS_GLOBALS['ajax_url']			 = '" . esc_url($UNICAEVENTS_GLOBALS['ajax_url']) . "';"
			. "UNICAEVENTS_GLOBALS['ajax_nonce']		 = '" . esc_attr($UNICAEVENTS_GLOBALS['ajax_nonce']) . "';"
			. "UNICAEVENTS_GLOBALS['ajax_nonce_editor'] = '" . esc_attr(wp_create_nonce('unicaevents_editor_nonce')) . "';"
			
			// Site base url
			. "UNICAEVENTS_GLOBALS['site_url']			= '" . get_site_url() . "';"
			
			// VC frontend edit mode
			. "UNICAEVENTS_GLOBALS['vc_edit_mode']		= " . (function_exists('unicaevents_vc_is_frontend') && unicaevents_vc_is_frontend() ? 'true' : 'false') . ";"
			
			// Theme base font
			. "UNICAEVENTS_GLOBALS['theme_font']		= '" . unicaevents_get_custom_font_settings('p', 'font-family') . "';"
			
			// Theme skin
			. "UNICAEVENTS_GLOBALS['theme_skin']			= '" . esc_attr(unicaevents_get_custom_option('theme_skin')) . "';"
			. "UNICAEVENTS_GLOBALS['theme_skin_color']		= '" . unicaevents_get_scheme_color('text_dark') . "';"
			. "UNICAEVENTS_GLOBALS['theme_skin_bg_color']	= '" . unicaevents_get_scheme_color('bg_color') . "';"
			
			// Slider height
			. "UNICAEVENTS_GLOBALS['slider_height']	= " . max(100, unicaevents_get_custom_option('slider_height')) . ";"
			
			// System message
			. "UNICAEVENTS_GLOBALS['system_message']	= {"
				. "message: '" . addslashes($msg['message']) . "',"
				. "status: '"  . addslashes($msg['status'])  . "',"
				. "header: '"  . addslashes($msg['header'])  . "'"
				. "};"
			
			// User logged in
			. "UNICAEVENTS_GLOBALS['user_logged_in']	= " . (is_user_logged_in() ? 'true' : 'false') . ";"
			
			// Show table of content for the current page
			. "UNICAEVENTS_GLOBALS['toc_menu']		= '" . esc_attr(unicaevents_get_custom_option('menu_toc')) . "';"
			. "UNICAEVENTS_GLOBALS['toc_menu_home']	= " . (unicaevents_get_custom_option('menu_toc')!='hide' && unicaevents_get_custom_option('menu_toc_home')=='yes' ? 'true' : 'false') . ";"
			. "UNICAEVENTS_GLOBALS['toc_menu_top']	= " . (unicaevents_get_custom_option('menu_toc')!='hide' && unicaevents_get_custom_option('menu_toc_top')=='yes' ? 'true' : 'false') . ";"
			
			// Fix main menu
			. "UNICAEVENTS_GLOBALS['menu_fixed']		= " . (unicaevents_get_theme_option('menu_attachment')=='fixed' ? 'true' : 'false') . ";"
			
			// Use responsive version for main menu
			. "UNICAEVENTS_GLOBALS['menu_relayout']	= " . max(0, (int) unicaevents_get_theme_option('menu_relayout')) . ";"
			. "UNICAEVENTS_GLOBALS['menu_responsive']	= " . (unicaevents_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) unicaevents_get_theme_option('menu_responsive')) : 0) . ";"
			. "UNICAEVENTS_GLOBALS['menu_slider']     = " . (unicaevents_get_theme_option('menu_slider')=='yes' ? 'true' : 'false') . ";"

			// Right panel demo timer
			. "UNICAEVENTS_GLOBALS['demo_time']		= " . (unicaevents_get_theme_option('show_theme_customizer')=='yes' ? max(0, (int) unicaevents_get_theme_option('customizer_demo')) : 0) . ";"

			// Video and Audio tag wrapper
			. "UNICAEVENTS_GLOBALS['media_elements_enabled'] = " . (unicaevents_get_theme_option('use_mediaelement')=='yes' ? 'true' : 'false') . ";"
			
			// Use AJAX search
			. "UNICAEVENTS_GLOBALS['ajax_search_enabled'] 	= " . (unicaevents_get_theme_option('use_ajax_search')=='yes' ? 'true' : 'false') . ";"
			. "UNICAEVENTS_GLOBALS['ajax_search_min_length']	= " . min(3, unicaevents_get_theme_option('ajax_search_min_length')) . ";"
			. "UNICAEVENTS_GLOBALS['ajax_search_delay']		= " . min(200, max(1000, unicaevents_get_theme_option('ajax_search_delay'))) . ";"

			// Use CSS animation
			. "UNICAEVENTS_GLOBALS['css_animation']      = " . (unicaevents_get_theme_option('css_animation')=='yes' ? 'true' : 'false') . ";"
			. "UNICAEVENTS_GLOBALS['menu_animation_in']  = '" . esc_attr(unicaevents_get_theme_option('menu_animation_in')) . "';"
			. "UNICAEVENTS_GLOBALS['menu_animation_out'] = '" . esc_attr(unicaevents_get_theme_option('menu_animation_out')) . "';"

			// Popup windows engine
			. "UNICAEVENTS_GLOBALS['popup_engine']	= '" . esc_attr(unicaevents_get_theme_option('popup_engine')) . "';"

			// E-mail mask
			. "UNICAEVENTS_GLOBALS['email_mask']		= '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';"
			
			// Messages max length
			. "UNICAEVENTS_GLOBALS['contacts_maxlength']	= " . intval(unicaevents_get_theme_option('message_maxlength_contacts')) . ";"
			. "UNICAEVENTS_GLOBALS['comments_maxlength']	= " . intval(unicaevents_get_theme_option('message_maxlength_comments')) . ";"

			// Remember visitors settings
			. "UNICAEVENTS_GLOBALS['remember_visitors_settings']	= " . (unicaevents_get_theme_option('remember_visitors_settings')=='yes' ? 'true' : 'false') . ";"

			// Internal vars - do not change it!
			// Flag for review mechanism
			. "UNICAEVENTS_GLOBALS['admin_mode']			= false;"
			// Max scale factor for the portfolio and other isotope elements before relayout
			. "UNICAEVENTS_GLOBALS['isotope_resize_delta']	= 0.3;"
			// jQuery object for the message box in the form
			. "UNICAEVENTS_GLOBALS['error_message_box']	= null;"
			// Waiting for the viewmore results
			. "UNICAEVENTS_GLOBALS['viewmore_busy']		= false;"
			. "UNICAEVENTS_GLOBALS['video_resize_inited']	= false;"
			. "UNICAEVENTS_GLOBALS['top_panel_height']		= 0;"
			
			. "</script>";
	}
}


//  Enqueue Custom styles (main Theme options settings)
if ( !function_exists( 'unicaevents_enqueue_custom_styles' ) ) {
	function unicaevents_enqueue_custom_styles() {
		// Custom stylesheet
		$custom_css = '';	//unicaevents_get_custom_option('custom_stylesheet_url');
		unicaevents_enqueue_style( 'unicaevents-custom-style', $custom_css ? $custom_css : unicaevents_get_file_url('css/custom-style.css'), array(), null );
		// Custom inline styles
		wp_add_inline_style( 'unicaevents-custom-style', unicaevents_prepare_custom_styles() );
	}
}

// Add class "widget_number_#' for each widget
if ( !function_exists( 'unicaevents_add_widget_number' ) ) {
	//add_filter('dynamic_sidebar_params', 'unicaevents_add_widget_number', 10, 1);
	function unicaevents_add_widget_number($prm) {
		global $UNICAEVENTS_GLOBALS;
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_id='', $last_sidebar_columns=0, $last_sidebar_count=0, $sidebars_widgets=array();
		$cur_sidebar = !empty($UNICAEVENTS_GLOBALS['current_sidebar']) ? $UNICAEVENTS_GLOBALS['current_sidebar'] : 'undefined';
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $cur_sidebar) {
			$num = 0;
			$last_sidebar = $cur_sidebar;
			$last_sidebar_id = $prm[0]['id'];
			$last_sidebar_columns = max(1, (int) unicaevents_get_custom_option('sidebar_'.($cur_sidebar).'_columns'));
			$last_sidebar_count = count($sidebars_widgets[$last_sidebar_id]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget_number_'.esc_attr($num).($last_sidebar_columns > 1 ? ' column-1_'.esc_attr($last_sidebar_columns) : '').' ', $prm[0]['before_widget']);
		return $prm;
	}
}


// Filters wp_title to print a neat <title> tag based on what is being viewed.
if ( !function_exists( 'unicaevents_wp_title' ) ) {
	// add_filter( 'wp_title', 'unicaevents_wp_title', 10, 2 );
	function unicaevents_wp_title( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		if ( is_home() || is_front_page() ) {
			$site_description = unicaevents_get_custom_option('logo_slogan');
			if (empty($site_description)) 
				$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description )
				$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'unicaevents' ), max( $paged, $page ) );
		return $title;
	}
}

// Add main menu classes
if ( !function_exists( 'unicaevents_add_mainmenu_classes' ) ) {
	// add_filter('wp_nav_menu_objects', 'unicaevents_add_mainmenu_classes', 10, 2);
	function unicaevents_add_mainmenu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && unicaevents_get_theme_option('menu_colored')=='yes' && is_array($items) && count($items) > 0) {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_tint = unicaevents_taxonomy_get_inherited_property('category', $item->object_id, 'bg_tint');
						if (!empty($cur_tint) && !unicaevents_is_inherit_option($cur_tint))
							$items[$k]->classes[] = 'bg_tint_'.esc_attr($cur_tint);
					}
				}
			}
		}
		return $items;
	}
}


// Save post data from frontend editor
if ( !function_exists( 'unicaevents_callback_frontend_editor_save' ) ) {
	function unicaevents_callback_frontend_editor_save() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'unicaevents_editor_nonce' ) )
			die();

		$response = array('error'=>'');

		parse_str($_REQUEST['data'], $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( unicaevents_get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0) 
					$response['error'] = esc_html__('Post update error!', 'unicaevents');
			} else {
				$response['error'] = esc_html__('Post update error!', 'unicaevents');
			}
		} else
			$response['error'] = esc_html__('Post update denied!', 'unicaevents');
		
		echo json_encode($response);
		die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'unicaevents_callback_frontend_editor_delete' ) ) {
	function unicaevents_callback_frontend_editor_delete() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'unicaevents_editor_nonce' ) )
			die();

		$response = array('error'=>'');
		
		$post_id = $_REQUEST['post_id'];

		if ( unicaevents_get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = esc_html__('Post delete error!', 'unicaevents');
			} else {
				$response['error'] = esc_html__('Post delete error!', 'unicaevents');
			}
		} else
			$response['error'] = esc_html__('Post delete denied!', 'unicaevents');

		echo json_encode($response);
		die();
	}
}


// Prepare logo text
if ( !function_exists( 'unicaevents_prepare_logo_text' ) ) {
	function unicaevents_prepare_logo_text($text) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}
?>
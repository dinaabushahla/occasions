<?php

/* Theme setup section
-------------------------------------------------------------------- */


// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings

global $UNICAEVENTS_GLOBALS;

$UNICAEVENTS_GLOBALS['settings'] = array(
	
	'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																// lessc - fast & low memory required, but .less-map, shadows & gradients not supprted
																// less  - slow, but support all features
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_separator'	=> '/*---LESS_SEPARATOR---*/',			// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'internal',							// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg',								// images|bg - Use image as slide's content or as slide's background

	'admin_dummy_style' => 2									// 1 | 2 - Progress bar style when import dummy data
);



// Default Theme Options
if ( !function_exists( 'unicaevents_options_settings_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_options_settings_theme_setup', 2 );	// Priority 1 for add unicaevents_filter handlers
	function unicaevents_options_settings_theme_setup() {
		global $UNICAEVENTS_GLOBALS;
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'unicaevents_options_reset');

		// Settings 
		$socials_type = unicaevents_get_theme_setting('socials_type');
				
		// Prepare arrays 
		$UNICAEVENTS_GLOBALS['options_params'] = apply_filters('unicaevents_filter_theme_options_params', array(
			'list_fonts'				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_fonts' => ''),
			'list_fonts_styles'			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_fonts_styles' => ''),
			'list_socials' 				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_socials' => ''),
			'list_icons' 				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_icons' => ''),
			'list_posts_types' 			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_posts_types' => ''),
			'list_categories' 			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_categories' => ''),
			'list_menus'				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_menus' => ''),
			'list_sidebars'				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_sidebars' => ''),
			'list_positions' 			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_sidebars_positions' => ''),
			'list_skins'				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_skins' => ''),
			'list_color_schemes'		=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_color_schemes' => ''),
			'list_bg_tints'				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_bg_tints' => ''),
			'list_body_styles'			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_body_styles' => ''),
			'list_header_styles'		=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_templates_header' => ''),
			'list_blog_styles'			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_templates_blog' => ''),
			'list_single_styles'		=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_templates_single' => ''),
			'list_article_styles'		=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_article_styles' => ''),
			'list_blog_counters' 		=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_blog_counters' => ''),
			'list_animations_in' 		=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_animations_in' => ''),
			'list_animations_out'		=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_animations_out' => ''),
			'list_filters'				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_portfolio_filters' => ''),
			'list_hovers'				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_hovers' => ''),
			'list_hovers_dir'			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_hovers_directions' => ''),
			'list_alter_sizes'			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_alter_sizes' => ''),
			'list_sliders' 				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_sliders' => ''),
			'list_bg_image_positions'	=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_bg_image_positions' => ''),
			'list_popups' 				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_popup_engines' => ''),
			'list_gmap_styles'		 	=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_googlemap_styles' => ''),
			'list_yes_no' 				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_yesno' => ''),
			'list_on_off' 				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_onoff' => ''),
			'list_show_hide' 			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_showhide' => ''),
			'list_sorting' 				=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_sortings' => ''),
			'list_ordering' 			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_orderings' => ''),
			'list_locations' 			=> array('$'.$UNICAEVENTS_GLOBALS['theme_slug'].'_get_list_dedicated_locations' => '')
			)
		);


		// Theme options array
		$UNICAEVENTS_GLOBALS['options'] = array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'unicaevents'),
					"start" => "partitions",
					"override" => "category,services_group,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'unicaevents'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'unicaevents'),
					"desc" => wp_kses( __('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'body_style' => array(
					"title" => esc_html__('Body style', 'unicaevents'),
					"desc" => wp_kses( __('Select body style:', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] )
								. ' <br>' 
								. wp_kses( __('<b>boxed</b> - if you want use background color and/or image', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] )
								. ',<br>'
								. wp_kses( __('<b>wide</b> - page fill whole window with centered content', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] )
								. (unicaevents_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses( __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] )
									: '')
								. (unicaevents_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses( __('<b>fullscreen</b> - page content fill whole window without any paddings', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] )
									: ''),
					"override" => "category,services_group,post,page",
					"std" => "wide",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_body_styles'],
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'unicaevents'),
					"desc" => wp_kses( __('Add paddings above and below the page content', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'unicaevents'),
					"desc" => wp_kses( __('Select skin for the theme decoration', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_skins'],
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'unicaevents'),
					"desc" => wp_kses( __('Select predefined color scheme for the entire page', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'unicaevents'),
					"desc" => wp_kses( __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'unicaevents'),
					"desc" => wp_kses( __('Color and image for the site background', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'unicaevents'),
					"desc" => wp_kses( __("Use custom color and/or image as the site background", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'unicaevents'),
					"desc" => wp_kses( __('Body background color',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'unicaevents'),
					"desc" => wp_kses( __('Select theme background pattern (first case - without pattern)',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => unicaevents_get_file_url('images/spacer.png'),
						1 => unicaevents_get_file_url('images/bg/pattern_1.jpg'),
						2 => unicaevents_get_file_url('images/bg/pattern_2.jpg'),
						3 => unicaevents_get_file_url('images/bg/pattern_3.jpg'),
						4 => unicaevents_get_file_url('images/bg/pattern_4.jpg'),
						5 => unicaevents_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'unicaevents'),
					"desc" => wp_kses( __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'unicaevents'),
					"desc" => wp_kses( __('Select theme background image (first case - without image)',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => unicaevents_get_file_url('images/spacer.png'),
						1 => unicaevents_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => unicaevents_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => unicaevents_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'unicaevents'),
					"desc" => wp_kses( __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'unicaevents'),
					"desc" => wp_kses( __('Select custom image position',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'unicaevents'),
					"desc" => wp_kses( __('Always load background images or only for boxed body style', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'unicaevents'),
						'always' => esc_html__('Always', 'unicaevents')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => esc_html__('Video background', 'unicaevents'),
					"desc" => wp_kses( __('Parameters of the video, used as site background', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'unicaevents'),
					"desc" => wp_kses( __("Show video as the site background", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'unicaevents'),
					"desc" => wp_kses( __("Youtube code of video", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'unicaevents'),
					"desc" => wp_kses( __("URL to video-file (uploaded on your site)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"readonly" =>false,
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => esc_html__('Choose video', 'unicaevents'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'unicaevents'),
															'update' => esc_html__( 'Select Video', 'unicaevents')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'unicaevents'),
					"desc" => wp_kses( __('Use overlay texture for the video background', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'unicaevents'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'unicaevents'),
					"desc" => wp_kses( __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'unicaevents'),
					"desc" => wp_kses( __('Select desired style of the page header', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "header_1",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_header_styles'],
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'unicaevents'),
					"desc" => wp_kses( __('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'unicaevents'),
					"desc" => wp_kses( __('Select position for the top panel with logo and main menu', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'unicaevents'),
						'above' => esc_html__('Above slider', 'unicaevents'),
						'below' => esc_html__('Below slider', 'unicaevents'),
						'over'  => esc_html__('Over slider', 'unicaevents')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'unicaevents'),
					"desc" => wp_kses( __('Select predefined color scheme for the top panel', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"pushy_panel_scheme" => array(
					"title" => esc_html__('Push panel color scheme', 'unicaevents'),
					"desc" => wp_kses( __('Select predefined color scheme for the push panel (with logo, menu and socials)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_8')
					),
					"std" => "dark",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'unicaevents'),
					"desc" => wp_kses( __('Show post/page/category title', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'unicaevents'),
					"desc" => wp_kses( __('Show path to current category (post, page)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'unicaevents'),
					"desc" => wp_kses( __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'unicaevents'),
					"desc" => wp_kses( __('Select the Main menu style and position', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'unicaevents'),
					"desc" => wp_kses( __('Select main menu for the current page',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'unicaevents'),
					"desc" => wp_kses( __('Attach main menu to top of window then page scroll down', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "fixed",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'unicaevents'), 
						"none"=>esc_html__("Don't fix menu position", 'unicaevents')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => esc_html__('Main menu slider', 'unicaevents'),
					"desc" => wp_kses( __('Use slider background for main menu items', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"type" => "switch",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no']),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'unicaevents'),
					"desc" => wp_kses( __('Select animation to show submenu ', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "bounceIn",
					"type" => "select",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_animations_in']),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'unicaevents'),
					"desc" => wp_kses( __('Select animation to hide submenu ', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "fadeOutDown",
					"type" => "select",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_animations_out']),
		
		"menu_relayout" => array( 
					"title" => esc_html__('Main menu relayout', 'unicaevents'),
					"desc" => wp_kses( __('Allow relayout main menu if window width less then this value', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => esc_html__('Main menu responsive', 'unicaevents'),
					"desc" => wp_kses( __('Allow responsive version for the main menu if window width less then this value', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => 640,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'unicaevents'),
					"desc" => wp_kses( __('Width for dropdown menus in main menu', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'unicaevents'),
					"desc" => wp_kses( __("Select parts for the user's menu area", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'unicaevents'),
					"desc" => wp_kses( __('Show user menu area on top of page', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => esc_html__('Select user menu',  'unicaevents'),
					"desc" => wp_kses( __('Select user menu for the current page',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "default",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'unicaevents'),
					"desc" => wp_kses( __('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => esc_html__('Show Login/Logout buttons', 'unicaevents'),
					"desc" => wp_kses( __('Show Login and Logout buttons in the user menu area', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => esc_html__('Show bookmarks', 'unicaevents'),
					"desc" => wp_kses( __('Show bookmarks selector in the user menu', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'unicaevents'),
					"desc" => wp_kses( __('Show Social icons in the user menu area', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		

		
		"info_header_4" => array( 
					"title" => esc_html__("Table of Contents (TOC)", 'unicaevents'),
					"desc" => wp_kses( __("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => esc_html__('TOC position', 'unicaevents'),
					"desc" => wp_kses( __('Show TOC for the current page', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "float",
					"options" => array(
						'hide'  => esc_html__('Hide', 'unicaevents'),
						'fixed' => esc_html__('Fixed', 'unicaevents'),
						'float' => esc_html__('Float', 'unicaevents')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => esc_html__('Add "Home" into TOC', 'unicaevents'),
					"desc" => wp_kses( __('Automatically add "Home" item into table of contents - return to home page of the site', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => esc_html__('Add "To Top" into TOC', 'unicaevents'),
					"desc" => wp_kses( __('Automatically add "To Top" item into table of contents - scroll to top of the page', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'unicaevents'),
					"desc" => wp_kses( __("Select or upload logos for the site's header and select it position", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'favicon' => array(
					"title" => esc_html__('Favicon', 'unicaevents'),
					"desc" => wp_kses( __("Upload a 16px x 16px image that will represent your website's favicon.<br /><em>To ensure cross-browser compatibility, we recommend converting the favicon into .ico format before uploading. (<a href='http://www.favicon.cc/'>www.favicon.cc</a>)</em>", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"type" => "media"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'unicaevents'),
					"desc" => wp_kses( __('Main logo image', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'unicaevents'),
					"desc" => wp_kses( __('Logo image for the header (if menu is fixed after the page is scrolled)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'unicaevents'),
					"desc" => wp_kses( __('Logo text - display it after logo image', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_slogan' => array(
					"title" => esc_html__('Logo slogan', 'unicaevents'),
					"desc" => wp_kses( __('Logo slogan - display it under logo image (instead the site tagline)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'unicaevents'),
					"desc" => wp_kses( __('Height for the logo in the header area', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'unicaevents'),
					"desc" => wp_kses( __('Top offset for the logo in the header area', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'unicaevents'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'unicaevents'),
					"desc" => wp_kses( __('Select parameters for main slider (you can override it in each category and page)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'unicaevents'),
					"desc" => wp_kses( __('Do you want to show slider on each page (post)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'unicaevents'),
					"desc" => wp_kses( __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'unicaevents'),
						"fullwide"=>esc_html__("Fullwide", 'unicaevents'),
						"fullscreen"=>esc_html__("Fullscreen", 'unicaevents')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'unicaevents'),
					"desc" => wp_kses( __("Slider height (in pixels) - only if slider display with fixed height.", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'unicaevents'),
					"desc" => wp_kses( __('What engine use to show slider?', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "swiper",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_sliders'],
					"type" => "radio"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'unicaevents'),
					"desc" => wp_kses( __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => unicaevents_array_merge(array(0 => esc_html__('- Select category -', 'unicaevents')), $UNICAEVENTS_GLOBALS['options_params']['list_categories']),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'unicaevents'),
					"desc" => wp_kses( __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'unicaevents'),
					"desc" => wp_kses( __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'unicaevents'),
					"desc" => wp_kses( __('Select the desired ordering method for posts', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'unicaevents'),
					"desc" => wp_kses( __("Interval (in ms) for slides change in slider", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'unicaevents'),
					"desc" => wp_kses( __("Choose pagination style for the slider", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'unicaevents'),
						'yes'  => esc_html__('Dots', 'unicaevents'), 
						'over' => esc_html__('Titles', 'unicaevents')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'unicaevents'),
					"desc" => wp_kses( __("Do you want to show post's title, reviews rating and description on slides in slider", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'unicaevents'),
						'slide' => esc_html__('Slide', 'unicaevents'), 
						'fixed' => esc_html__('Fixed', 'unicaevents')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'unicaevents'),
					"desc" => wp_kses( __("Do you want to show post's category on slides in slider", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'unicaevents'),
					"desc" => wp_kses( __("Do you want to show post's reviews rating on slides in slider", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'unicaevents'),
					"desc" => wp_kses( __("How many characters show in the post's description in slider. 0 - no descriptions", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'unicaevents'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'unicaevents'),
					"desc" => wp_kses( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'unicaevents'),
					"desc" => wp_kses( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'unicaevents'),
					"desc" => wp_kses( __('Show / Hide and select main sidebar', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'unicaevents'),
					"desc" => wp_kses( __('Select position for the main sidebar or hide it',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "right",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_positions'],
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'unicaevents'),
					"desc" => wp_kses( __('Select predefined color scheme for the main sidebar', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'unicaevents'),
					"desc" => wp_kses( __('Select main sidebar content',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
	
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'unicaevents'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'unicaevents'),
					"desc" => wp_kses( __("Select components of the footer, set style and put the content for the user's footer area", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'unicaevents'),
					"desc" => wp_kses( __('Select style for the footer sidebar or hide it', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'unicaevents'),
					"desc" => wp_kses( __('Select predefined color scheme for the footer', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'unicaevents'),
					"desc" => wp_kses( __('Select footer sidebar for the blog page',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'unicaevents'),
					"desc" => wp_kses( __('Select columns number for the footer sidebar',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		"info_footer_2" => array(
					"title" => esc_html__('Testimonials in Footer', 'unicaevents'),
					"desc" => wp_kses( __('Select parameters for Testimonials in the Footer', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => esc_html__('Show Testimonials in footer', 'unicaevents'),
					"desc" => wp_kses( __('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"testimonials_scheme" => array(
					"title" => esc_html__("Color scheme", 'unicaevents'),
					"desc" => wp_kses( __('Select predefined color scheme for the testimonials area', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => esc_html__('Testimonials count', 'unicaevents'),
					"desc" => wp_kses( __('Number testimonials to show', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		
		"info_footer_3" => array(
					"title" => esc_html__('Twitter in Footer', 'unicaevents'),
					"desc" => wp_kses( __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => esc_html__('Show Twitter in footer', 'unicaevents'),
					"desc" => wp_kses( __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"twitter_scheme" => array(
					"title" => esc_html__("Color scheme", 'unicaevents'),
					"desc" => wp_kses( __('Select predefined color scheme for the twitter area', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => esc_html__('Twitter count', 'unicaevents'),
					"desc" => wp_kses( __('Number twitter to show', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),


		"info_footer_4" => array(
					"title" => esc_html__('Google map parameters', 'unicaevents'),
					"desc" => wp_kses( __('Select parameters for Google map (you can override it in each category and page)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'unicaevents'),
					"desc" => wp_kses( __('Do you want to show Google map on each page (post)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'unicaevents'),
					"desc" => wp_kses( __("Map height (default - in pixels, allows any CSS units of measure)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'unicaevents'),
					"desc" => wp_kses( __("Enter address to show on map center", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longitude to show on map',  'unicaevents'),
					"desc" => wp_kses( __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => esc_html__('Title to show on map',  'unicaevents'),
					"desc" => wp_kses( __("Enter title to show on map center", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => esc_html__('Description to show on map',  'unicaevents'),
					"desc" => wp_kses( __("Enter description to show on map center", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'unicaevents'),
					"desc" => wp_kses( __("Enter desired initial zoom for Google map", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'unicaevents'),
					"desc" => wp_kses( __("Select style to show Google map", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'style1',
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_gmap_styles'],
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'unicaevents'),
					"desc" => wp_kses( __("Select or upload png-image with Google map marker", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),
		
		
		
		"info_footer_5" => array(
					"title" => esc_html__("Contacts area", 'unicaevents'),
					"desc" => wp_kses( __("Show/Hide contacts area in the footer", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'unicaevents'),
					"desc" => wp_kses( __('Show contact information area in footer: site logo, contact info and large social icons', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => esc_html__("Color scheme", 'unicaevents'),
					"desc" => wp_kses( __('Select predefined color scheme for the contacts area', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'unicaevents'),
					"desc" => wp_kses( __('Logo image in the footer (in the contacts area)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => esc_html__('Logo height', 'unicaevents'),
					"desc" => wp_kses( __('Height for the logo in the footer area (in the contacts area)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
		
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'unicaevents'),
					"desc" => wp_kses( __("Show/Hide copyright area in the footer", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'unicaevents'),
					"desc" => wp_kses( __('Show area with copyright information, footer menu and small social icons in footer', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "plain",
					"options" => array(
						'none' => esc_html__('Hide', 'unicaevents'),
						'text' => esc_html__('Text', 'unicaevents'),
						'menu' => esc_html__('Text and menu', 'unicaevents'),
						'socials' => esc_html__('Text and Social icons', 'unicaevents')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'unicaevents'),
					"desc" => wp_kses( __('Select predefined color scheme for the copyright area', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'unicaevents'),
					"desc" => wp_kses( __('Select footer menu for the current page',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_menus'],
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'unicaevents'),
					"desc" => wp_kses( __("Copyright text to show in footer area (bottom of site)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "UnicaEvents &copy; 2014 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'unicaevents'),
					"override" => "category,services_group,page,post",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'unicaevents'),
					"desc" => wp_kses( __('Animation parameters and responsive layouts for the small screens', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'unicaevents'),
					"desc" => wp_kses( __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'unicaevents'),
					"desc" => wp_kses( __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'unicaevents'),
					"desc" => wp_kses( __('Do you want use extended animations effects on your site?', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'unicaevents'),
					"desc" => wp_kses( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'unicaevents'),
					"desc" => wp_kses( __('Do you want use responsive layouts on small screen or still use main layout?', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),


		'info_other_2' => array(
					"title" => esc_html__('Google fonts parameters', 'unicaevents'),
					"desc" => wp_kses( __('Specify additional parameters, used to load Google fonts', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"
					),
		
		"fonts_subset" => array(
					"title" => esc_html__('Characters subset', 'unicaevents'),
					"desc" => wp_kses( __('Select subset, included into used Google fonts', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "latin,latin-ext",
					"options" => array(
						'latin' => esc_html__('Latin', 'unicaevents'),
						'latin-ext' => esc_html__('Latin Extended', 'unicaevents'),
						'greek' => esc_html__('Greek', 'unicaevents'),
						'greek-ext' => esc_html__('Greek Extended', 'unicaevents'),
						'cyrillic' => esc_html__('Cyrillic', 'unicaevents'),
						'cyrillic-ext' => esc_html__('Cyrillic Extended', 'unicaevents'),
						'vietnamese' => esc_html__('Vietnamese', 'unicaevents')
					),
					"size" => "medium",
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),


		'info_other_3' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'unicaevents'),
					"desc" => wp_kses( __('Put here your custom CSS and JS code', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => esc_html__('Use custom CSS/HTML/JS', 'unicaevents'),
					"desc" => wp_kses( __('Do you want use custom HTML/CSS/JS code in your site? For example: custom styles, Google Analitics code, etc.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'unicaevents'),
					"desc" => wp_kses( __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'unicaevents'),
					"desc" => wp_kses( __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),
		
		'custom_code' => array(
					"title" => esc_html__('Your custom HTML/JS code',  'unicaevents'),
					"desc" => wp_kses( __('Put here your invisible html/js code: Google analitics, counters, etc',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		'custom_css' => array(
					"title" => esc_html__('Your custom CSS code',  'unicaevents'),
					"desc" => wp_kses( __('Put here your css code to correct main theme styles',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'unicaevents'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'unicaevents'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'unicaevents'),
					"desc" => wp_kses( __('Select desired blog streampage parameters (you can override it in each category)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'unicaevents'),
					"desc" => wp_kses( __('Select desired blog style', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "excerpt",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_blog_styles'],
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'unicaevents'),
					"desc" => wp_kses( __('Select desired hover style (only for Blog style = Portfolio)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_hovers'],
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'unicaevents'),
					"desc" => wp_kses( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_hovers_dir'],
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'unicaevents'),
					"desc" => wp_kses( __('Select article display method: boxed or stretch', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "stretch",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_article_styles'],
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'unicaevents'),
					"desc" => wp_kses( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_locations'],
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'unicaevents'),
					"desc" => wp_kses( __('What taxonomy use for filter buttons', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_filters'],
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'unicaevents'),
					"desc" => wp_kses( __('Select the desired sorting method for posts', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "date",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_sorting'],
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'unicaevents'),
					"desc" => wp_kses( __('Select the desired ordering method for posts', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "desc",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'unicaevents'),
					"desc" => wp_kses( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'unicaevents'),
					"desc" => wp_kses( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'unicaevents'),
					"desc" => wp_kses( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'unicaevents'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'unicaevents'),
					"desc" => wp_kses( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'unicaevents'),
					"desc" => wp_kses( __('Select desired style for single page', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "single-standard",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_single_styles'],
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => esc_html__('Select post icon', 'unicaevents'),
					"desc" => wp_kses( __('Select icon for output before post/category name in some layouts', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "services_group,page,post",
					"std" => "",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_icons'],
					"style" => "select",
					"type" => "icons"
					),

		"alter_thumb_size" => array(
					"title" => esc_html__('Alter thumb size (WxH)',  'unicaevents'),
					"override" => "page,post",
					"desc" => wp_kses( __("Select thumb size for the alternative portfolio layout (number items horizontally x number items vertically)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"std" => "1_1",
					"type" => "radio",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_alter_sizes']
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'unicaevents'),
					"desc" => wp_kses( __("Show featured image (if selected) before post content on single pages", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'unicaevents'),
					"desc" => wp_kses( __('Show area with post title on single pages', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'unicaevents'),
					"desc" => wp_kses( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'unicaevents'),
					"desc" => wp_kses( __('Show area with post info on single pages', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'unicaevents'),
					"desc" => wp_kses( __('Show text before "Read more" tag on single pages', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'unicaevents'),
					"desc" => wp_kses( __("Show post author information block on single post page", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'unicaevents'),
					"desc" => wp_kses( __("Show tags block on single post page", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'unicaevents'),
					"desc" => wp_kses( __("Show related posts block on single post page", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'unicaevents'),
					"desc" => wp_kses( __("How many related posts showed on single post page", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'unicaevents'),
					"desc" => wp_kses( __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'unicaevents'),
					"desc" => wp_kses( __('Select the desired sorting method for related posts', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'unicaevents'),
					"desc" => wp_kses( __('Select the desired ordering method for related posts', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => esc_html__('Show comments',  'unicaevents'),
					"desc" => wp_kses( __("Show comments block on single post page", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'unicaevents'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'unicaevents'),
					"desc" => wp_kses( __('Select excluded categories, substitute parameters, etc.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'unicaevents'),
					"desc" => wp_kses( __('Select categories, which posts are exclude from blog page', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_categories'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'unicaevents'),
					"desc" => wp_kses( __('Select type of the pagination on blog streampages', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "pages",
					"override" => "category,services_group,page",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'unicaevents'),
						'slider'   => esc_html__('Slider with page numbers', 'unicaevents'),
						'viewmore' => esc_html__('"View more" button', 'unicaevents'),
						'infinite' => esc_html__('Infinite scroll', 'unicaevents')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'unicaevents'),
					"desc" => wp_kses( __('Select counters, displayed near the post title', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "views",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_blog_counters'],
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'unicaevents'),
					"desc" => wp_kses( __('What category display in announce block (over posts thumb) - original or nearest parental', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'unicaevents'),
						'original' => esc_html__("Original post's category", 'unicaevents')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'unicaevents'),
					"desc" => wp_kses( __('Show post date after N days (before - show post age)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'unicaevents'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'unicaevents'),
					"desc" => wp_kses( __('Set up list of reviews criterias. You can override it in any category.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'unicaevents'),
					"desc" => wp_kses( __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'unicaevents'),
					"desc" => wp_kses( __("Maximum level for reviews marks", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'unicaevents'), 
						'10'=>esc_html__('10 stars', 'unicaevents'), 
						'100'=>esc_html__('100%', 'unicaevents')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'unicaevents'),
					"desc" => wp_kses( __("Show rating marks as text or as stars/progress bars.", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'unicaevents'), 
						'stars' => esc_html__('As stars or bars', 'unicaevents')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'unicaevents'),
					"desc" => wp_kses( __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => esc_html__("bad,poor,normal,good,great", 'unicaevents'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'unicaevents'),
					"desc" => wp_kses( __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'unicaevents'),
						'users' => esc_html__('By visitors', 'unicaevents')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'unicaevents'),
					"desc" => wp_kses( __("Do you want hide second reviews tab in widgets and single posts?", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "show",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_show_hide'],
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'unicaevents'),
					"desc" => wp_kses( __("What visitors can vote: all or only registered", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'unicaevents'), 
						'registered'=>esc_html__('Only registered', 'unicaevents')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'unicaevents'),
					"desc" => wp_kses( __('Add default reviews criterias.',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'unicaevents'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'unicaevents'),
					"desc" => wp_kses( __('Set up parameters to show images, galleries, audio and video posts', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'unicaevents'),
					"desc" => wp_kses( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'unicaevents'), 
						"2" => esc_html__("Retina", 'unicaevents')
					),
					"type" => "switch"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'unicaevents'),
					"desc" => wp_kses( __('Substitute standard Wordpress gallery with our slider on the single pages', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'unicaevents'),
					"desc" => wp_kses( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'unicaevents'),
					"desc" => wp_kses( __('Maximum images number from gallery into slider', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'unicaevents'),
					"desc" => wp_kses( __('Select engine to show popup windows with images and galleries', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "magnific",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_popups'],
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'unicaevents'),
					"desc" => wp_kses( __('Substitute audio tag with source from soundcloud to embed player', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'unicaevents'),
					"desc" => wp_kses( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'unicaevents'),
					"desc" => wp_kses( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'unicaevents'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'unicaevents'),
					"desc" => wp_kses( __("Social networks list for site footer and Social widget", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'unicaevents'),
					"desc" => wp_kses( __('Select icon and write URL to your profile in desired social networks.',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $UNICAEVENTS_GLOBALS['options_params']['list_socials'] : $UNICAEVENTS_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'unicaevents'),
					"desc" => wp_kses( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'unicaevents'),
					"desc" => wp_kses( __("Show social share buttons block", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'unicaevents'),
						'vertical'	=> esc_html__('Vertical', 'unicaevents'),
						'horizontal'=> esc_html__('Horizontal', 'unicaevents')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'unicaevents'),
					"desc" => wp_kses( __("Show share counters after social buttons", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'unicaevents'),
					"desc" => wp_kses( __('Caption for the block with social share buttons',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'unicaevents'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'unicaevents'),
					"desc" => wp_kses( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $UNICAEVENTS_GLOBALS['options_params']['list_socials'] : $UNICAEVENTS_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'unicaevents'),
					"desc" => wp_kses( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'unicaevents'),
					"desc" => wp_kses( __('Your login (username) in Twitter',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'unicaevents'),
					"desc" => wp_kses( __('Twitter API Consumer key',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'unicaevents'),
					"desc" => wp_kses( __('Twitter API Consumer secret',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'unicaevents'),
					"desc" => wp_kses( __('Twitter API Token key',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'unicaevents'),
					"desc" => wp_kses( __('Twitter API Token secret',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'unicaevents'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'unicaevents'),
					"desc" => wp_kses( __('Company address, phones and e-mail', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'unicaevents'),
					"desc" => wp_kses( __('String with contact info in the left side of the site header', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => esc_html__('Open hours in the header', 'unicaevents'),
					"desc" => wp_kses( __('String with open hours in the site header', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'unicaevents'),
					"desc" => wp_kses( __('E-mail for send contact form and user registration data', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'unicaevents'),
					"desc" => wp_kses( __('Company country, post code and city', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'unicaevents'),
					"desc" => wp_kses( __('Street and house number', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		"session_time" => array(
					"title" => esc_html__('Session', 'unicaevents'),
					"desc" => wp_kses( __('Session time', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'unicaevents'),
					"desc" => wp_kses( __('Phone number', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'unicaevents'),
					"desc" => wp_kses( __('Fax number', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'unicaevents'),
					"desc" => wp_kses( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'unicaevents'),
					"desc" => wp_kses( __("Message's maxlength in the contact form shortcode", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'unicaevents'),
					"desc" => wp_kses( __("Message's maxlength in the comments form", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'unicaevents'),
					"desc" => wp_kses( __('What function you want to use for sending mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'unicaevents'),
					"desc" => wp_kses( __("What function you want to use for sending mail?", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'unicaevents'),
						'mail' => esc_html__('PHP mail', 'unicaevents')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'unicaevents'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'unicaevents'),
					"desc" => wp_kses( __('Enable/disable AJAX search and output settings for it', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'unicaevents'),
					"desc" => wp_kses( __('Show search field in the top area and side menus', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'unicaevents'),
					"desc" => wp_kses( __('Use incremental AJAX search for the search field in top of page', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'unicaevents'),
					"desc" => wp_kses( __('The minimum length of the search string',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'unicaevents'),
					"desc" => wp_kses( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'unicaevents'),
					"desc" => wp_kses( __('Select post types, what will be include in search results. If not selected - use all types.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_posts_types'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'unicaevents'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __('Number of the posts to show in search results',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'unicaevents'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's thumbnail in the search results", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'unicaevents'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's publish date in the search results", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'unicaevents'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's author in the search results", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'unicaevents'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's counters (views, comments, likes) in the search results", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'unicaevents'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'unicaevents'),
					"desc" => wp_kses( __('Basic theme functionality settings', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"notify_about_new_registration" => array(
					"title" => esc_html__('Notify about new registration', 'unicaevents'),
					"desc" => wp_kses( __('Send E-mail with new registration data to the contact email or to site admin e-mail (if contact email is empty)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "no",
					"options" => array(
						'no'    => esc_html__('No', 'unicaevents'),
						'both'  => esc_html__('Both', 'unicaevents'),
						'admin' => esc_html__('Admin', 'unicaevents'),
						'user'  => esc_html__('User', 'unicaevents')
					),
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'unicaevents'),
					"desc" => wp_kses( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'unicaevents'),
					"desc" => wp_kses( __("Allow authors to edit their posts in frontend area)", 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'unicaevents'),
					"desc" => wp_kses( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'unicaevents'),
					"desc" => wp_kses( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'unicaevents'),
					"desc" => wp_kses( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'unicaevents'),
					"desc" => wp_kses( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'unicaevents'),
					"desc" => wp_kses( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => 1200,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'unicaevents'),
					"desc" => wp_kses( __('Allow to use UnicaEvents Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => esc_html__('Enable PO Composer in the admin panel', 'unicaevents'),
					"desc" => wp_kses( __('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'unicaevents'),
					"desc" => wp_kses( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services (for example <a href="http://yui.2clics.net/" target="_blank">http://yui.2clics.net/</a>) or utility <b>yuicompressor-x.y.z.jar</b>', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $UNICAEVENTS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		"info_service_2" => array(
					"title" => esc_html__('Clear Wordpress cache', 'unicaevents'),
					"desc" => wp_kses( __('For example, it recommended after activating the WPML plugin - in the cache are incorrect data about the structure of categories and your site may display "white screen". After clearing the cache usually the performance of the site is restored.', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"clear_cache" => array(
					"title" => esc_html__('Clear cache', 'unicaevents'),
					"desc" => wp_kses( __('Clear Wordpress cache data', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"icon" => "iconadmin-trash",
					"action" => "clear_cache",
					"type" => "button")
		);



		
		
		
		//###############################################
		//#### Hidden fields (for internal use only) #### 
		//###############################################
		/*
		$UNICAEVENTS_GLOBALS['options']["custom_stylesheet_file"] = array(
			"title" => esc_html__('Custom stylesheet file', 'unicaevents'),
			"desc" => wp_kses( __('Path to the custom stylesheet (stored in the uploads folder)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"std" => "",
			"type" => "hidden");
		
		$UNICAEVENTS_GLOBALS['options']["custom_stylesheet_url"] = array(
			"title" => esc_html__('Custom stylesheet url', 'unicaevents'),
			"desc" => wp_kses( __('URL to the custom stylesheet (stored in the uploads folder)', 'unicaevents'), $UNICAEVENTS_GLOBALS['allowed_tags'] ),
			"std" => "",
			"type" => "hidden");
		*/

		
		
	}
}


// Update all temporary vars (start with $unicaevents_) in the Theme Options with actual lists
if ( !function_exists( 'unicaevents_options_settings_theme_setup2' ) ) {
	add_action( 'unicaevents_action_after_init_theme', 'unicaevents_options_settings_theme_setup2', 1 );
	function unicaevents_options_settings_theme_setup2() {
		if (unicaevents_options_is_used()) {
			global $UNICAEVENTS_GLOBALS;
			// Replace arrays with actual parameters
			$lists = array();
			if (count($UNICAEVENTS_GLOBALS['options']) > 0) {
				$prefix = '$' . $UNICAEVENTS_GLOBALS['theme_slug'] . '_';
				$prefix_len = unicaevents_strlen($prefix);
				foreach ($UNICAEVENTS_GLOBALS['options'] as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (unicaevents_substr($k1, 0, $prefix_len) == $prefix || unicaevents_substr($v1, 0, $prefix_len) == $prefix) {
								$list_func = unicaevents_substr(unicaevents_substr($k1, 0, $prefix_len) == $prefix ? $k1 : $v1, 1);
								unset($UNICAEVENTS_GLOBALS['options'][$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$UNICAEVENTS_GLOBALS['options'][$k]['options'] = unicaevents_array_merge($UNICAEVENTS_GLOBALS['options'][$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$UNICAEVENTS_GLOBALS['options'][$k]['options'] = $lists[$list_func] = unicaevents_array_merge($UNICAEVENTS_GLOBALS['options'][$k]['options'], $list_func == 'unicaevents_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		dfl(sprintf(esc_html__('Wrong function name %s in the theme options array', 'unicaevents'), $list_func));
								}
							}
						}
					}
				}
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'unicaevents_options_reset' ) ) {
	function unicaevents_options_reset($clear=true) {
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(unicaevents_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'unicaevents_'.strip_tags($slug).'_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "unicaevents_%"');
				// Add Templates Options
				if (file_exists(unicaevents_get_file_dir('demo/templates_options.txt'))) {
					$txt = unicaevents_fgc(unicaevents_get_file_dir('demo/templates_options.txt'));
					$data = unicaevents_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = unicaevents_replace_uploads_url(unicaevents_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>
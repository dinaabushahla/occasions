<?php
/**
 * The Header for our theme.
 */

global $UNICAEVENTS_GLOBALS;

// Theme init - don't remove next row! Load custom options
unicaevents_core_init_theme();

$theme_skin = unicaevents_esc(unicaevents_get_custom_option('theme_skin'));
$body_scheme = unicaevents_get_custom_option('body_scheme');
if (empty($body_scheme)  || unicaevents_is_inherit_option($body_scheme)) $body_scheme = 'original';
$blog_style = unicaevents_get_custom_option(is_singular() && !unicaevents_get_global('blog_streampage') ? 'single_style' : 'blog_style');
$body_style  = unicaevents_get_custom_option('body_style');
$article_style = unicaevents_get_custom_option('article_style');
$top_panel_style = unicaevents_get_custom_option('top_panel_style');
$top_panel_position = unicaevents_get_custom_option('top_panel_position');
$top_panel_scheme = unicaevents_get_custom_option('top_panel_scheme');
$video_bg_show  = unicaevents_get_custom_option('show_video_bg')=='yes' && (unicaevents_get_custom_option('video_bg_youtube_code')!='' || unicaevents_get_custom_option('video_bg_url')!='');
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr('scheme_'.$body_scheme); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1<?php echo (unicaevents_get_theme_option('responsive_layouts') == 'yes' ? ', maximum-scale=1' : ''); ?>">
	
	<?php
	if (floatval(get_bloginfo('version')) < 4.1) {
		?><title><?php wp_title( '|', true, 'right' ); ?></title><?php
	}
	?>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    
    <?php
	if ( !function_exists('has_site_icon') || !has_site_icon() ) {
		$favicon = unicaevents_get_custom_option('favicon');
		if (!$favicon) {
			if ( file_exists(unicaevents_get_file_dir('skins/'.($theme_skin).'/images/favicon.ico')) )
				$favicon = unicaevents_get_file_url('skins/'.($theme_skin).'/images/favicon.ico');
			if ( !$favicon && file_exists(unicaevents_get_file_dir('favicon.ico')) )
				$favicon = unicaevents_get_file_url('favicon.ico');
		}
		if ($favicon) {
			?><link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon); ?>" /><?php
		}
	}
	
	wp_head();
	?>
</head>

<body <?php 
	body_class('unicaevents_body body_style_' . esc_attr($body_style) 
		. ' body_' . (unicaevents_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent')
		. ' theme_skin_' . esc_attr($theme_skin)
		. ' article_style_' . esc_attr($article_style)
		. ' layout_' . esc_attr($blog_style)
		. ' template_' . esc_attr(unicaevents_get_template_name($blog_style))
		. (!unicaevents_param_is_off($top_panel_position) ? ' top_panel_show top_panel_' . esc_attr($top_panel_position) : 'top_panel_hide')
		. ' ' . esc_attr(unicaevents_get_sidebar_class())
		. ($video_bg_show ? ' video_bg_show' : '')
	);
	?>
>
	<?php echo force_balance_tags(unicaevents_get_custom_option('gtm_code')); ?>

	<?php do_action( 'before' ); ?>

	<?php
	// Add TOC items 'Home' and "To top"
	if (unicaevents_get_custom_option('menu_toc_home')=='yes')
		echo trim(unicaevents_sc_anchor(array(
			'id' => "toc_home",
			'title' => esc_html__('Home', 'unicaevents'),
			'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'unicaevents'),
			'icon' => "icon-home",
			'separator' => "yes",
			'url' => home_url())
			)); 
	if (unicaevents_get_custom_option('menu_toc_top')=='yes')
		echo trim(unicaevents_sc_anchor(array(
			'id' => "toc_top",
			'title' => esc_html__('To Top', 'unicaevents'),
			'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'unicaevents'),
			'icon' => "icon-double-up",
			'separator' => "yes")
			)); 
	?>

	<?php if ( !unicaevents_param_is_off(unicaevents_get_custom_option('show_sidebar_outer')) ) { ?>
	<div class="outer_wrap">
	<?php } ?>

	<?php require_once unicaevents_get_file_dir('sidebar_outer.php'); ?>

	<?php
		$class = $style = '';
		if ($body_style=='boxed' || unicaevents_get_custom_option('bg_image_load')=='always') {
			if (($img = (int) unicaevents_get_custom_option('bg_image', 0)) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = (int) unicaevents_get_custom_option('bg_pattern', 0)) > 0)
				$class = 'bg_pattern_'.($img);
			else if (($img = unicaevents_get_custom_option('bg_color', '')) != '')
				$style = 'background-color: '.($img).';';
			else if (unicaevents_get_custom_option('bg_custom')=='yes') {
				if (($img = unicaevents_get_custom_option('bg_image_custom')) != '')
					$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', unicaevents_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
				else if (($img = unicaevents_get_custom_option('bg_pattern_custom')) != '')
					$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
				else if (($img = unicaevents_get_custom_option('bg_image')) > 0)
					$class = 'bg_image_'.($img);
				else if (($img = unicaevents_get_custom_option('bg_pattern')) > 0)
					$class = 'bg_pattern_'.($img);
				if (($img = unicaevents_get_custom_option('bg_color')) != '')
					$style .= 'background-color: '.($img).';';
			}
		}
	?>

	<div class="body_wrap<?php echo ($class ? ' '.esc_attr($class) : ''); ?>"<?php echo ($style ? ' style="'.esc_attr($style).'"' : ''); ?>>

		<?php
		if ($video_bg_show) {
			$youtube = unicaevents_get_custom_option('video_bg_youtube_code');
			$video   = unicaevents_get_custom_option('video_bg_url');
			$overlay = unicaevents_get_custom_option('video_bg_overlay')=='yes';
			if (!empty($youtube)) {
				?>
				<div class="video_bg<?php echo ($overlay ? ' video_bg_overlay' : ''); ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
				<?php
			} else if (!empty($video)) {
				$info = pathinfo($video);
				$ext = !empty($info['extension']) ? $info['extension'] : 'src';
				?>
				<div class="video_bg<?php echo esc_attr($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
				<?php
			}
		}
		?>

		<div class="page_wrap">

			<?php
			// Top panel 'Above' or 'Over'
			if (in_array($top_panel_position, array('above', 'over'))) {
				unicaevents_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}
			// Slider
			require_once unicaevents_get_file_dir('templates/headers/_parts/slider.php');
			// Top panel 'Below'
			if ($top_panel_position == 'below') {
				unicaevents_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}

			// Top of page section: page title and breadcrumbs
			$show_title = unicaevents_get_custom_option('show_page_title')=='yes';
			$show_breadcrumbs = unicaevents_get_custom_option('show_breadcrumbs')=='yes';
			if ($show_title || $show_breadcrumbs) {
				?>
				<div class="top_panel_title top_panel_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo ($show_title ? ' title_present' : '') . ($show_breadcrumbs ? ' breadcrumbs_present' : ''); ?> scheme_<?php echo esc_attr($top_panel_scheme); ?>">
					<div class="top_panel_title_inner top_panel_inner_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo ($show_title ? ' title_present_inner' : '') . ($show_breadcrumbs ? ' breadcrumbs_present_inner' : ''); ?>">
						<div class="content_wrap">
							<?php if ($show_title) { ?>
								<h1 class="page_title"><?php echo strip_tags(unicaevents_get_blog_title()); ?></h1>
							<?php } ?>
							<?php if ($show_breadcrumbs) { ?>
								<div class="breadcrumbs">
									<?php if (!is_404()) unicaevents_show_breadcrumbs(); ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php
			}
			?>

			<div class="page_content_wrap page_paddings_<?php echo esc_attr(unicaevents_get_custom_option('body_paddings')); ?>">

				<?php
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') unicaevents_open_wrapper('<div class="content_wrap">');
				
				// Main content wrapper
				unicaevents_open_wrapper('<div class="content">');
				?>
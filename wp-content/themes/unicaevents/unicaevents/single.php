<?php
/**
Template Name: Single post
 */
get_header(); 

global $UNICAEVENTS_GLOBALS;
$single_style = !empty($UNICAEVENTS_GLOBALS['single_style']) ? $UNICAEVENTS_GLOBALS['single_style'] : unicaevents_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	unicaevents_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !unicaevents_param_is_off(unicaevents_get_custom_option('show_sidebar_main')),
			'content' => unicaevents_get_template_property($single_style, 'need_content'),
			'terms_list' => unicaevents_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>
<?php
/**
Template Name: Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move unicaevents_set_post_views to the javascript - counter will work under cache system
	if (unicaevents_get_custom_option('use_ajax_views_counter')=='no') {
		unicaevents_set_post_views(get_the_ID());
	}

	unicaevents_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !unicaevents_param_is_off(unicaevents_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>
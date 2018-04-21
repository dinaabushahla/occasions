<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_no_articles_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_no_articles_theme_setup', 1 );
	function unicaevents_template_no_articles_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'no-articles',
			'mode'   => 'internal',
			'title'  => esc_html__('No articles found', 'unicaevents'),
			'w'		 => null,
			'h'		 => null
		));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_no_articles_output' ) ) {
	function unicaevents_template_no_articles_output($post_options, $post_data) {
		global $UNICAEVENTS_GLOBALS;
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php esc_html_e('No posts found', 'unicaevents'); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria.', 'unicaevents' ); ?></p>
				<p><?php echo wp_kses( sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'unicaevents'), home_url(), get_bloginfo()), $UNICAEVENTS_GLOBALS['allowed_tags'] ); ?>
				<br><?php esc_html_e('Please report any broken links to our team.', 'unicaevents'); ?></p>
				<?php echo trim(unicaevents_sc_search(array('state'=>"fixed"))); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>
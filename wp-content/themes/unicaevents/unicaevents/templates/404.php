<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_404_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_404_theme_setup', 1 );
	function unicaevents_template_404_theme_setup() {
		unicaevents_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			),
			'w'		 => null,
			'h'		 => null
			));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_404_output' ) ) {
	function unicaevents_template_404_output() {
		global $UNICAEVENTS_GLOBALS;
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<h1 class="page_title"><?php esc_html_e( '404', 'unicaevents' ); ?></h1>
				<h2 class="page_subtitle"><?php esc_html_e('The requested page cannot be found', 'unicaevents'); ?></h2>
				<p class="page_description"><?php echo wp_kses( sprintf( __('Can\'t find what you need? Take a moment and do a search below or start from <a href="%s">our homepage</a>.', 'unicaevents'), esc(home_url('/')) ), $UNICAEVENTS_GLOBALS['allowed_tags'] ); ?></p>
				<div class="page_search"><?php echo trim(unicaevents_sc_search(array('state'=>'fixed', 'title'=>__('To search type and hit enter', 'unicaevents')))); ?></div>
			</div>
		</article>
		<?php
	}
}
?>
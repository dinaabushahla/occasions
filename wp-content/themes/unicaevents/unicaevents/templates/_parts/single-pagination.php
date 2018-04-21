<?php
wp_link_pages( array( 
	'before' => '<nav class="pagination_single"><span class="pager_pages">' . esc_html__( 'Pages:', 'unicaevents' ) . '</span>', 
	'after' => '</nav>',
	'link_before' => '<span class="pager_numbers">',
	'link_after' => '</span>'
	)
); 
?>
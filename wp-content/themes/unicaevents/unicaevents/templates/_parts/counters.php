<?php
$show_all_counters = !isset($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';
 
if ($show_all_counters || unicaevents_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php echo trim($counters_tag); ?> class="post_counters_item post_counters_views icon-eye" title="<?php echo esc_attr( sprintf(__('Views - %s', 'unicaevents'), $post_data['post_views']) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_views']); ?></<?php echo trim($counters_tag); ?>>
	<?php
}

if ($show_all_counters || unicaevents_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments icon-comment-1" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'unicaevents'), $post_data['post_comments']) ); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php echo trim($post_data['post_comments']); ?></span><span><?php echo ($post_data['post_comments'] == 1 ? ' Comment' : ' Comments')?></span></a>
	<?php 
}
 
$rating = $post_data['post_reviews_'.(unicaevents_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || unicaevents_strpos($post_options['counters'], 'rating')!==false)) { 
	?>
	<<?php echo trim($counters_tag); ?> class="post_counters_item post_counters_rating icon-star" title="<?php echo esc_attr( sprintf(__('Rating - %s', 'unicaevents'), $rating) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo trim($rating); ?></span></<?php echo trim($counters_tag); ?>>
	<?php
}

if ($show_all_counters || unicaevents_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	unicaevents_enqueue_messages();
	$likes = isset($_COOKIE['unicaevents_likes']) ? $_COOKIE['unicaevents_likes'] : '';
	$allow = unicaevents_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart <?php echo ($allow ? 'enabled' : 'disabled'); ?>" title="<?php echo ($allow ? esc_attr__('Like', 'unicaevents') : esc_attr__('Dislike', 'unicaevents')); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_attr_e('Like', 'unicaevents'); ?>"
		data-title-dislike="<?php esc_attr_e('Dislike', 'unicaevents'); ?>"><span class="post_counters_number"><?php echo trim($post_data['post_likes']); ?></span></a>
	<?php
}

if (is_single() && unicaevents_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(unicaevents_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(unicaevents_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>
<?php
/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'unicaevents_widget_twitter_load' );

/**
 * Register our widget.
 * 'Twitter_Widget' is the widget class used below.
 */
function unicaevents_widget_twitter_load() {
	register_widget( 'unicaevents_widget_twitter' );
}

/**
 * Twitter Widget class.
 */
class unicaevents_widget_twitter extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_twitter', 'description' => esc_html__('Last Twitter Updates. Version for new Twitter API 1.1', 'unicaevents') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'unicaevents_widget_twitter' );

		/* Create the widget. */
		parent::__construct( 'unicaevents_widget_twitter', esc_html__('UnicaEvents - Twitter', 'unicaevents'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$twitter_username = isset($instance['twitter_username']) ? $instance['twitter_username'] : '';
		$twitter_consumer_key = isset($instance['twitter_consumer_key']) ? $instance['twitter_consumer_key'] : '';
		$twitter_consumer_secret = isset($instance['twitter_consumer_secret']) ? $instance['twitter_consumer_secret'] : '';
		$twitter_token_key = isset($instance['twitter_token_key']) ? $instance['twitter_token_key'] : '';
		$twitter_token_secret = isset($instance['twitter_token_secret']) ? $instance['twitter_token_secret'] : '';
		$twitter_count = isset($instance['twitter_count']) ? $instance['twitter_count'] : '';	

		if (empty($twitter_consumer_key) || empty($twitter_consumer_secret) || empty($twitter_token_key) || empty($twitter_token_secret)) return;
		
		$data = unicaevents_get_twitter_data(array(
			'mode'            => 'user_timeline',
			'consumer_key'    => $twitter_consumer_key,
			'consumer_secret' => $twitter_consumer_secret,
			'token'           => $twitter_token_key,
			'secret'          => $twitter_token_secret
			)
		);
		
		if (!$data || !isset($data[0]['text'])) return;
		
		$output = '<ul>';
		$cnt = 0;
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $tweet) {
				if (unicaevents_substr($tweet['text'], 0, 1)=='@') continue;
				$output .= '<li class="theme_text' . ($cnt==$twitter_count-1 ? ' last' : '') . '"><a href="' . esc_url('https://twitter.com/'.($twitter_username)) . '" class="username" target="_blank">@' . ($tweet['user']['screen_name']) . '</a> ' . force_balance_tags(unicaevents_prepare_twitter_text($tweet)) . '</li>';
				if (++$cnt >= $twitter_count) break;
			}
		}
		$output .= '</ul>';
		
		if (!empty($output)) {
	
			/* Before widget (defined by themes). */			
			echo trim($before_widget);
			
			/* Display the widget title if one was input (before and after defined by themes). */
			if ($title) echo trim($before_title . $title . $after_title);
	
			echo trim($output);
			
			/* After widget (defined by themes). */
			echo trim($after_widget);
		}
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['twitter_username'] = strip_tags( $new_instance['twitter_username'] );
		$instance['twitter_consumer_key'] = strip_tags( $new_instance['twitter_consumer_key'] );
		$instance['twitter_consumer_secret'] = strip_tags( $new_instance['twitter_consumer_secret'] );
		$instance['twitter_token_key'] = strip_tags( $new_instance['twitter_token_key'] );
		$instance['twitter_token_secret'] = strip_tags( $new_instance['twitter_token_secret'] );
		$instance['twitter_count'] = strip_tags( $new_instance['twitter_count'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'description' => esc_html__('Last Twitter Updates', 'unicaevents') );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		$title = isset($instance['title']) ? $instance['title'] : '';
		$twitter_username = isset($instance['twitter_username']) ? $instance['twitter_username'] : unicaevents_get_theme_option('twitter_username');
		$twitter_consumer_key = isset($instance['twitter_consumer_key']) ? $instance['twitter_consumer_key'] : unicaevents_get_theme_option('twitter_consumer_key');
		$twitter_consumer_secret = isset($instance['twitter_consumer_secret']) ? $instance['twitter_consumer_secret'] : unicaevents_get_theme_option('twitter_consumer_secret');
		$twitter_token_key = isset($instance['twitter_token_key']) ? $instance['twitter_token_key'] : unicaevents_get_theme_option('twitter_token_key');
		$twitter_token_secret = isset($instance['twitter_token_secret']) ? $instance['twitter_token_secret'] : unicaevents_get_theme_option('twitter_token_secret');
		$twitter_count = max(1, intval(isset($instance['twitter_count']) ? $instance['twitter_count'] : unicaevents_get_theme_option('twitter_count')));
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'unicaevents'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($title); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_count' )); ?>"><?php esc_html_e('Tweets count:', 'unicaevents'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_count' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_count' )); ?>" value="<?php echo esc_attr($twitter_count); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_username' )); ?>"><?php esc_html_e('Twitter Username:', 'unicaevents'); ?><br />(<?php esc_html_e('leave empty if you paste widget code', 'unicaevents'); ?>)</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_username' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_username' )); ?>" value="<?php echo esc_attr($twitter_username); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_key' )); ?>"><?php esc_html_e('Twitter Consumer Key:', 'unicaevents'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_key' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_consumer_key' )); ?>" value="<?php echo esc_attr($twitter_consumer_key); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_secret' )); ?>"><?php esc_html_e('Twitter Consumer Secret:', 'unicaevents'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_secret' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_consumer_secret' )); ?>" value="<?php echo esc_attr($twitter_consumer_secret); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_token_key' )); ?>"><?php esc_html_e('Twitter Token Key:', 'unicaevents'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_token_key' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_token_key' )); ?>" value="<?php echo esc_attr($twitter_token_key); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_token_secret' )); ?>"><?php esc_html_e('Twitter Token Secret:', 'unicaevents'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_token_secret' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_token_secret' )); ?>" value="<?php echo esc_attr($twitter_token_secret); ?>" style="width:100%;" />
		</p>

	<?php
	}
}
?>
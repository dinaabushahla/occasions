<?php
/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'unicaevents_widget_socials_load' );

/**
 * Register our widget.
 */
function unicaevents_widget_socials_load() {
	register_widget( 'unicaevents_widget_socials' );
}

/**
 * flickr Widget class.
 */
class unicaevents_widget_socials extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_socials', 'description' => esc_html__('Show site logo and social links', 'unicaevents') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'unicaevents_widget_socials' );

		/* Create the widget. */
		parent::__construct( 'unicaevents_widget_socials', esc_html__('UnicaEvents - Show logo and social links', 'unicaevents'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$text = isset($instance['text']) ? unicaevents_do_shortcode($instance['text']) : '';
		$logo_image = isset($instance['logo_image']) ? $instance['logo_image'] : '';
		$logo_text = isset($instance['logo_text']) ? $instance['logo_text'] : '';
		$logo_slogan = isset($instance['logo_slogan']) ? $instance['logo_slogan'] : '';
		$show_logo = isset($instance['show_logo']) ? (int) $instance['show_logo'] : 1;
		$show_icons = isset($instance['show_icons']) ? (int) $instance['show_icons'] : 1;

		/* Before widget (defined by themes). */			
		echo trim($before_widget);

		/* Display the widget title if one was input (before and after defined by themes). */
		if ($title) echo trim($before_title . $title . $after_title);
		
		
		?>
		<div class="widget_inner">
            <?php
				if ($show_logo) {
					if ($logo_image=='')	$logo_image = true;
					if ($logo_text=='')		$logo_text = true;
					if ($logo_slogan=='')	$logo_slogan = true;
					if ($logo_image || $logo_text)
						unicaevents_show_logo($logo_image, false, false, false, $logo_text, $logo_slogan);
				}

				if (!empty($text)) {
					?>
					<div class="logo_descr"><?php echo nl2br(do_shortcode($text)); ?></div>
                    <?php
				}
				
				if ($show_icons) {
					echo trim(unicaevents_sc_socials(array('size' => "tiny")));
				}
			?>
		</div>

		<?php
		/* After widget (defined by themes). */
		echo trim($after_widget);
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text'] = $new_instance['text'];
		$instance['logo_image'] = strip_tags( $new_instance['logo_image'] );
		$instance['logo_text'] = strip_tags( $new_instance['logo_text'] );
		$instance['show_logo'] = (int) $new_instance['show_logo'];
		$instance['show_icons'] = (int) $new_instance['show_icons'];
	
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'logo_image'=>'', 'logo_text'=>'', 'show_logo' => '1', 'show_icons' => '1', 'text'=>'', 'description' => esc_html__('Show logo and social icons', 'unicaevents') );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		$title = isset($instance['title']) ? $instance['title'] : '';
		$text = isset($instance['text']) ? $instance['text'] : '';
		$show_logo = isset($instance['show_logo']) ? $instance['show_logo'] : 1;
		$show_icons = isset($instance['show_icons']) ? $instance['show_icons'] : 1;
		$logo_image = isset($instance['logo_image']) ? $instance['logo_image'] : '';
		$logo_text = isset($instance['logo_text']) ? $instance['logo_text'] : '';
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'unicaevents'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'text' )); ?>"><?php esc_html_e('Description:', 'unicaevents'); ?></label>
			<textarea id="<?php echo esc_attr($this->get_field_id( 'text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'text' )); ?>" style="width:100%;"><?php echo htmlspecialchars($instance['text']); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'logo_image' )); ?>"><?php esc_html_e('Logo image:<br />(if empty - use logo from Theme Options)', 'unicaevents'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'logo_image' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'logo_image' )); ?>" value="<?php echo esc_attr($logo_image); ?>" style="width:100%;" onchange="if (jQuery(this).siblings('img').length > 0) jQuery(this).siblings('img').get(0).src=this.value;" />
            <?php
			echo trim(unicaevents_show_custom_field($this->get_field_id( 'logo_media' ), array('type'=>'mediamanager', 'media_field_id'=>$this->get_field_id( 'logo_image' )), null));
			if ($logo_image) {
			?>
	            <br /><br /><img src="<?php echo esc_url($logo_image); ?>" style="max-width:220px;" alt="" />
			<?php
			}
			?>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'logo_text' )); ?>"><?php esc_html_e('Logo text:', 'unicaevents'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'logo_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'logo_text' )); ?>" value="<?php echo esc_attr($instance['logo_text']); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1"><?php esc_html_e('Show logo:', 'unicaevents'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_logo')); ?>" value="1" <?php echo ($show_logo==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1"><?php esc_html_e('Show', 'unicaevents'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_logo')); ?>" value="0" <?php echo ($show_logo==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_0"><?php esc_html_e('Hide', 'unicaevents'); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1"><?php esc_html_e('Show social icons:', 'unicaevents'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_icons')); ?>" value="1" <?php echo ($show_icons==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1"><?php esc_html_e('Show', 'unicaevents'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_icons')); ?>" value="0" <?php echo ($show_icons==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_0"><?php esc_html_e('Hide', 'unicaevents'); ?></label>
		</p>

	<?php
	}
}

if (is_admin()) {
	require_once unicaevents_get_file_dir('core/core.options/core.options-custom.php');
}
?>
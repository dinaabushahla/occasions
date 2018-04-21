<?php 
if (in_array('contact_info', $top_panel_top_components) && ($contact_info=trim(unicaevents_get_custom_option('contact_info')))!='') {
	?>
	<div class="top_panel_top_contact_area">
		<?php echo force_balance_tags($contact_info); ?>
	</div>
	<?php
}
?>

<?php
if (in_array('contact_phone', $top_panel_top_components) && ($contact_phone=trim(unicaevents_get_custom_option('contact_phone')))!='') {
	?>
	<div class="top_panel_top_contact_phone icon-phone"><?php echo force_balance_tags($contact_phone); ?></div>
	<?php
}
?>

<?php
if (in_array('open_hours', $top_panel_top_components) && ($open_hours=trim(unicaevents_get_custom_option('contact_open_hours')))!='') {
	?>
	<div class="top_panel_top_open_hours icon-clock"><?php echo force_balance_tags($open_hours); ?></div>
	<?php
}
?>

<?php
if (in_array('contact_email', $top_panel_top_components) && ($contact_email=trim(unicaevents_get_custom_option('contact_email')))!='') {
	?>
	<div class="top_panel_top_contact_email"><?php echo force_balance_tags($contact_email); ?></div>
	<?php
}
?>

<div class="top_panel_top_user_area">
	<?php
	if (in_array('socials', $top_panel_top_components) && unicaevents_get_custom_option('show_socials')=='yes') {
		?>
		<div class="top_panel_top_socials">
			<?php echo trim(unicaevents_sc_socials(array('size'=>'tiny'))); ?>
		</div>
		<?php
	}

	if (in_array('search', $top_panel_top_components) && unicaevents_get_custom_option('show_search')=='yes') {
		?>
		<div class="top_panel_top_search"><?php echo trim(unicaevents_sc_search(array('state'=>'fixed'))); ?></div>
		<?php
	}

	global $UNICAEVENTS_GLOBALS;
	if (empty($UNICAEVENTS_GLOBALS['menu_user'])) 
		$UNICAEVENTS_GLOBALS['menu_user'] = unicaevents_get_nav_menu('menu_user');
	if (empty($UNICAEVENTS_GLOBALS['menu_user'])) {
		?>
		<ul id="menu_user" class="menu_user_nav">
		<?php
	} else {
		$menu = unicaevents_substr($UNICAEVENTS_GLOBALS['menu_user'], 0, unicaevents_strlen($UNICAEVENTS_GLOBALS['menu_user'])-5);
		$pos = unicaevents_strpos($menu, '<ul');
		if ($pos!==false) $menu = unicaevents_substr($menu, 0, $pos+3) . ' class="menu_user_nav"' . unicaevents_substr($menu, $pos+3);
		echo str_replace('class=""', '', $menu);
	}
	

	if (in_array('currency', $top_panel_top_components) && function_exists('unicaevents_is_woocommerce_page') && unicaevents_is_woocommerce_page() && unicaevents_get_custom_option('show_currency')=='yes') {
		?>
		<li class="menu_user_currency">
			<a href="#">$</a>
			<ul>
				<li><a href="#"><b>&#36;</b> <?php esc_html_e('Dollar', 'unicaevents'); ?></a></li>
				<li><a href="#"><b>&euro;</b> <?php esc_html_e('Euro', 'unicaevents'); ?></a></li>
				<li><a href="#"><b>&pound;</b> <?php esc_html_e('Pounds', 'unicaevents'); ?></a></li>
			</ul>
		</li>
		<?php
	}

	if (in_array('language', $top_panel_top_components) && unicaevents_get_custom_option('show_languages')=='yes' && function_exists('icl_get_languages')) {
		$languages = icl_get_languages('skip_missing=1');
		if (!empty($languages) && is_array($languages)) {
			$lang_list = '';
			$lang_active = '';
			foreach ($languages as $lang) {
				$lang_title = esc_attr($lang['translated_name']);	//esc_attr($lang['native_name']);
				if ($lang['active']) {
					$lang_active = $lang_title;
				}
				$lang_list .= "\n"
					.'<li><a rel="alternate" hreflang="' . esc_attr($lang['language_code']) . '" href="' . esc_url(apply_filters('WPML_filter_link', $lang['url'], $lang)) . '">'
						.'<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang_title) . '" title="' . esc_attr($lang_title) . '" />'
						. ($lang_title)
					.'</a></li>';
			}
			?>
			<li class="menu_user_language">
				<a href="#"><span><?php echo trim($lang_active); ?></span></a>
				<ul><?php echo trim($lang_list); ?></ul>
			</li>
			<?php
		}
	}

	if (in_array('bookmarks', $top_panel_top_components) && unicaevents_get_custom_option('show_bookmarks')=='yes') {
		// Load core messages
		unicaevents_enqueue_messages();
		?>
		<li class="menu_user_bookmarks"><a href="#" class="bookmarks_show icon-star" title="<?php esc_attr_e('Show bookmarks', 'unicaevents'); ?>"><?php esc_html_e('Bookmarks', 'unicaevents'); ?></a>
		<?php 
			$list = unicaevents_get_value_gpc('unicaevents_bookmarks', '');
			if (!empty($list)) $list = json_decode($list, true);
			?>
			<ul class="bookmarks_list">
				<li><a href="#" class="bookmarks_add icon-star-empty" title="<?php esc_attr_e('Add the current page into bookmarks', 'unicaevents'); ?>"><?php esc_html_e('Add bookmark', 'unicaevents'); ?></a></li>
				<?php 
				if (!empty($list) && is_array($list)) {
					foreach ($list as $bm) {
						echo '<li><a href="'.esc_url($bm['url']).'" class="bookmarks_item">'.($bm['title']).'<span class="bookmarks_delete icon-cancel" title="'.esc_attr__('Delete this bookmark', 'unicaevents').'"></span></a></li>';
					}
				}
				?>
			</ul>
		</li>
		<?php 
	}

	if (in_array('login', $top_panel_top_components) && unicaevents_get_custom_option('show_login')=='yes') {
		if ( !is_user_logged_in() ) {
			// Load core messages
			unicaevents_enqueue_messages();
			// Load Popup engine
			unicaevents_enqueue_popup();
			// Anyone can register ?
			if ( (int) get_option('users_can_register') > 0) {
			?>
			<li class="menu_user_register"><a href="#popup_registration" class="popup_link popup_register_link icon-pencil"><?php esc_html_e('Register', 'unicaevents'); ?></a><?php
				if (unicaevents_get_theme_option('show_login')=='yes') {
					require_once unicaevents_get_file_dir('templates/headers/_parts/register.php');
				}?></li>
			<?php } ?>
			<li class="menu_user_login"><a href="#popup_login" class="popup_link popup_login_link icon-user"><?php esc_html_e('Login', 'unicaevents'); ?></a><?php
				if (unicaevents_get_theme_option('show_login')=='yes') {
					require_once unicaevents_get_file_dir('templates/headers/_parts/login.php');
				}?></li>
			<?php 
		} else {
			$current_user = wp_get_current_user();
			?>
			<li class="menu_user_controls">
				<a href="#"><?php
					$user_avatar = '';
					if ($current_user->user_email) $user_avatar = get_avatar($current_user->user_email, 16*min(2, max(1, unicaevents_get_theme_option("retina_ready"))));
					if ($user_avatar) {
						?><span class="user_avatar"><?php echo trim($user_avatar); ?></span><?php
					}?><span class="user_name"><?php echo trim($current_user->display_name); ?></span></a>
				<ul>
					<?php if (current_user_can('publish_posts')) { ?>
					<li><a href="<?php echo home_url('/'); ?>/wp-admin/post-new.php?post_type=post" class="icon icon-doc"><?php esc_html_e('New post', 'unicaevents'); ?></a></li>
					<?php } ?>
					<li><a href="<?php echo get_edit_user_link(); ?>" class="icon icon-cog"><?php esc_html_e('Settings', 'unicaevents'); ?></a></li>
				</ul>
			</li>
			<li class="menu_user_logout"><a href="<?php echo wp_logout_url(home_url('/')); ?>" class="icon icon-logout"><?php esc_html_e('Logout', 'unicaevents'); ?></a></li>
			<?php 
		}
	}

	if (in_array('cart', $top_panel_top_components) && function_exists('unicaevents_exists_woocommerce') && unicaevents_exists_woocommerce() && (unicaevents_is_woocommerce_page() && unicaevents_get_custom_option('show_cart')=='shop' || unicaevents_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
		?>
		<li class="menu_user_cart">
			<?php require_once unicaevents_get_file_dir('templates/headers/_parts/contact-info-cart.php'); ?>
		</li>
		<?php
	}
	?>

	</ul>

</div>
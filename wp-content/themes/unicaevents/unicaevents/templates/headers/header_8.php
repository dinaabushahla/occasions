<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'unicaevents_template_header_8_theme_setup' ) ) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_template_header_8_theme_setup', 1 );
	function unicaevents_template_header_8_theme_setup() {
		unicaevents_add_template(array(
			'layout' => 'header_8',
			'mode'   => 'header',
			'title'  => esc_html__('Header 8', 'unicaevents'),
			'icon'   => unicaevents_get_file_url('templates/headers/images/8.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'unicaevents_template_header_8_output' ) ) {
	function unicaevents_template_header_8_output($post_options, $post_data) {
		global $UNICAEVENTS_GLOBALS;

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background: url('.esc_url($header_image).') repeat center top"' 
				: '';
		}
		?>

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_8 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			
			<div class="top_panel_wrap_inner top_panel_inner_style_8 top_panel_position_<?php echo esc_attr(unicaevents_get_custom_option('top_panel_position')); ?>">
			
				<?php if (unicaevents_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						$top_panel_top_components = array('contact_info', 'login', 'currency', 'bookmarks', 'socials');
						require_once unicaevents_get_file_dir('templates/headers/_parts/top-panel-top.php');
						?>
					</div>
				</div>
				<?php } ?>

				<div class="top_panel_middle" <?php echo trim($header_css); ?>>
					<div class="content_wrap">
						<div class="contact_logo">
							<?php unicaevents_show_logo(true, true); ?>
						</div>
						<div class="top_panel_buttons">
							<?php
							if (unicaevents_get_custom_option('show_search')=='yes') 
								echo trim(unicaevents_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed")));
							if (function_exists('unicaevents_exists_woocommerce') && unicaevents_exists_woocommerce() && (unicaevents_is_woocommerce_page() && unicaevents_get_custom_option('show_cart')=='shop' || unicaevents_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
								?>
								<div class="menu_main_cart top_panel_icon">
									<?php require_once unicaevents_get_file_dir('templates/headers/_parts/contact-info-cart.php'); ?>
								</div>
								<?php
							}
							?>
						</div>
						<div class="menu_pushy_wrap clearfix">
							<a href="#" class="menu_pushy_button icon-menu"><?php esc_html_e('MENU', 'unicaevents'); ?></a>
						</div>
					</div>
				</div>

			</div>

		</header>

		<nav class="menu_pushy_nav_area pushy pushy-left scheme_<?php echo esc_attr(unicaevents_get_custom_option('pushy_panel_scheme')); ?>">
			<div class="pushy_inner">
	
				<a href="#" class="close-pushy"></a>
	
				<?php 
				unicaevents_show_logo(false, false, false, true);
	
				if (empty($UNICAEVENTS_GLOBALS['menu_main'])) $UNICAEVENTS_GLOBALS['menu_main'] = unicaevents_get_nav_menu('menu_main');
				if (empty($UNICAEVENTS_GLOBALS['menu_main'])) $UNICAEVENTS_GLOBALS['menu_main'] = unicaevents_get_nav_menu();
				echo str_replace('menu_main', 'menu_pushy', $UNICAEVENTS_GLOBALS['menu_main']);
	
				$address_1 = unicaevents_get_theme_option('contact_address_1');
				$address_2 = unicaevents_get_theme_option('contact_address_2');
				$phone = unicaevents_get_theme_option('contact_phone');
				$fax = unicaevents_get_theme_option('contact_fax');
				if (!empty($address_1) || !empty($address_2) || !empty($phone) || !empty($fax)) {
					?>
					<div class="contact_info">
						<?php if (!empty($address_1) || !empty($address_2)) { ?>
							<address class="contact_address">
								<?php echo trim($address_1) . (!empty($address_1) ? ', ' : '') . trim($address_2); ?>
							</address>
						<?php } ?>
						<?php if (!empty($phone) || !empty($fax)) { ?>
							<address class="contact_phones">
								<?php echo esc_html__('Call:', 'unicaevents') . ' ' . ($phone) . (!empty($phone) ? ', ' : '') . ($fax); ?>
							</address>
						<?php } ?>
					</div>
					<?php
				}
	
				if (unicaevents_get_custom_option('show_socials')=='yes') {
					?>
					<div class="contact_socials">
						<?php echo trim(unicaevents_sc_socials(array('size'=>'small'))); ?>
					</div>
					<?php
				}
				?>

			</div>
        </nav>

        <!-- Site Overlay -->
        <div class="site-overlay"></div>
		<?php
	}
}
?>
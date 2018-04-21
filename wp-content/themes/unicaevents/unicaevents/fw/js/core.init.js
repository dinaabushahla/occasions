/* global jQuery:false */
/* global UNICAEVENTS_GLOBALS:false */

jQuery(document).ready(function() {
	"use strict";
	UNICAEVENTS_GLOBALS['theme_init_counter'] = 0;
	unicaevents_init_actions();
});



// Theme init actions
function unicaevents_init_actions() {
	"use strict";

	if (UNICAEVENTS_GLOBALS['vc_edit_mode'] && jQuery('.vc_empty-placeholder').length==0 && UNICAEVENTS_GLOBALS['theme_init_counter']++ < 30) {
		setTimeout(unicaevents_init_actions, 200);
		return;
	}

	unicaevents_ready_actions();
	unicaevents_resize_actions();
	unicaevents_scroll_actions();

	// Resize handlers
	jQuery(window).resize(function() {
		"use strict";
		unicaevents_resize_actions();
		unicaevents_scroll_actions()
	});

	// Scroll handlers
	jQuery(window).scroll(function() {
		"use strict";
		unicaevents_scroll_actions();
	});
}



// Theme first load actions
//==============================================
function unicaevents_ready_actions() {
	"use strict";

	// Call skin specific action (if exists)
    //----------------------------------------------
    if (window.unicaevents_skin_ready_actions) unicaevents_skin_ready_actions();

	// Call theme specific action (if exists)
    //----------------------------------------------
    if (window.unicaevents_theme_ready_actions) unicaevents_theme_ready_actions();


	// Widgets decoration
    //----------------------------------------------

	// Decorate nested lists in widgets and side panels
	jQuery('.widget ul > li').each(function() {
		if (jQuery(this).find('ul').length > 0) {
			jQuery(this).addClass('has_children');
		}
	});


	// Archive widget decoration
	jQuery('.widget_archive a').each(function() {
		var val = jQuery(this).html().split(' ');
		if (val.length > 1) {
			val[val.length-1] = '<span>' + val[val.length-1] + '</span>';
			jQuery(this).html(val.join(' '))
		}
	});

jQuery('.content .sc_button').each(function() {
	var delay = 0.11;
	var letteCount = jQuery(this).text();
	jQuery(this).attr('data-text', letteCount);
	var button = '<span>' + letteCount.split('').join('</span><span>') +'</span>';
	jQuery(this).html(button);
	var lettermassive = letteCount.split('');
	for (var i = 0; i <= lettermassive.length; i++) {
		delay +=0.02;
		jQuery('.sc_button > span:nth-child(' + i + ')').css({'transition-delay':delay +'s', '-webkit-transition-delay':delay +'s'});
	};
});

	// Calendar handlers - change months
	jQuery('.widget_calendar').on('click', '.month_prev a, .month_next a', function(e) {
		"use strict";
		var calendar = jQuery(this).parents('.wp-calendar');
		var m = jQuery(this).data('month');
		var y = jQuery(this).data('year');
		var pt = jQuery(this).data('type');
		jQuery.post(UNICAEVENTS_GLOBALS['ajax_url'], {
			action: 'calendar_change_month',
			nonce: UNICAEVENTS_GLOBALS['ajax_nonce'],
			month: m,
			year: y,
			post_type: pt
		}).done(function(response) {
			var rez = {};
			try {
				rez = JSON.parse(response);
			} catch (e) {
				rez = { error: UNICAEVENTS_GLOBALS['ajax_error'] };
				console.log(response);
			}
			if (rez.error === '') {
				calendar.parent().fadeOut(200, function() {
					jQuery(this).find('.wp-calendar').remove();
					jQuery(this).append(rez.data).fadeIn(200);
				});
			}
		});
		e.preventDefault();
		return false;
	});



	// Media setup
    //----------------------------------------------

	// Video background init
	jQuery('.video_background').each(function() {
		var youtube = jQuery(this).data('youtube-code');
		if (youtube) {
			jQuery(this).tubular({videoId: youtube});
		}
	});



	// Menu
    //----------------------------------------------

	// Clone side menu for responsive
	if (jQuery('ul#menu_side').length > 0) {
		jQuery('ul#menu_side').clone().removeAttr('id').removeClass('menu_side_nav').addClass('menu_side_responsive').insertAfter('ul#menu_side');
		unicaevents_show_current_menu_item(jQuery('.menu_side_responsive'), jQuery('.sidebar_outer_menu_responsive_button'));
	}

	// Clone main menu for responsive
	if (jQuery('.menu_main_nav_area ul#menu_main').length > 0) {
		var menu_responsive = jQuery('.menu_main_nav_area ul#menu_main').clone().removeAttr('id').removeClass('menu_main_nav').addClass('menu_main_responsive');	//.insertAfter('ul#menu_main');
		jQuery('.menu_main_nav_area ul#menu_main').parent().parent().append(menu_responsive);
		unicaevents_show_current_menu_item(jQuery('.menu_main_responsive'), jQuery('.top_panel_style_1 .menu_main_responsive_button, .top_panel_style_2 .menu_main_responsive_button'));
	}
	
	// Responsive menu button
	jQuery('.menu_main_responsive_button, .sidebar_outer_menu_responsive_button').on('click', function(e){
		"use strict";
		if (jQuery(this).hasClass('menu_main_responsive_button'))
			jQuery('.menu_main_responsive').slideToggle();
		else {
			jQuery(this).toggleClass('icon-down').toggleClass('icon-up');
			jQuery('.menu_side_responsive').slideToggle();
		}
		e.preventDefault();
		return false;
	});
	
	// Push menu button
	jQuery('.menu_pushy_button').on('click', function(e){
		"use strict";
		jQuery('body').addClass('pushy-active').css('overflow', 'hidden');
		jQuery('.site-overlay').fadeIn('fast');
		e.preventDefault();
		return false;
	});
	jQuery('.pushy .close-pushy,.site-overlay').on('click', function(e){
		jQuery('body').removeClass('pushy-active').css('overflow', 'visible');
		jQuery('.site-overlay').fadeOut('fast');
		e.preventDefault();
		return false;
	});
	
	// Side menu widgets button
	jQuery('.sidebar_outer_widgets_button').on('click', function(e){
		"use strict";
		jQuery('.sidebar_outer_widgets').slideToggle();
		e.preventDefault();
		return false;
	});

	// Submenu click handler for the responsive menu
	jQuery('.menu_main_responsive, .menu_side_responsive, .menu_pushy_nav_area').on('click', 'li a', function(e) {
		"use strict";
		var is_menu_main = jQuery(this).parents('.menu_main_responsive').length > 0;
		if ((!is_menu_main || jQuery('body').hasClass('responsive_menu')) && jQuery(this).parent().hasClass('menu-item-has-children')) {
			if (jQuery(this).siblings('ul:visible').length > 0)
				jQuery(this).siblings('ul').slideUp().parent().removeClass('opened');
			else {
				jQuery(this).parents('li').siblings('li').find('ul:visible').slideUp().parent().removeClass('opened');
				jQuery(this).siblings('ul').slideDown().parent().addClass('opened');
			}
		}
		if (jQuery(this).attr('href')=='#' || ((!is_menu_main || jQuery('body').hasClass('responsive_menu')) && jQuery(this).parent().hasClass('menu-item-has-children'))) {
			e.preventDefault();
			return false;
		}
	});
	
	// Init superfish menus
	unicaevents_init_sfmenu('.menu_main_nav_area ul#menu_main, ul#menu_user, ul#menu_side');

	// Slide effect for main menu
	if (UNICAEVENTS_GLOBALS['menu_slider']) {
		jQuery('#menu_main').spasticNav({
			color: UNICAEVENTS_GLOBALS['accent2_color']
		});
	}

	// Show table of contents for the current page
	if (UNICAEVENTS_GLOBALS['toc_menu'] != 'no') {
		unicaevents_build_page_toc();
	}

	// One page mode for menu links (scroll to anchor)
	jQuery('#toc, ul#menu_main li, ul#menu_user li, ul#menu_side li, ul#menu_footer li, ul#menu_pushy li').on('click', 'a', function(e) {
		"use strict";
		var href = jQuery(this).attr('href');
		if (href===undefined) return;
		var pos = href.indexOf('#');
		if (pos < 0 || href.length == 1) return;
		if (jQuery(href.substr(pos)).length > 0) {
			var loc = window.location.href;
			var pos2 = loc.indexOf('#');
			if (pos2 > 0) loc = loc.substring(0, pos2);
			var now = pos==0;
			if (!now) now = loc == href.substring(0, pos);
			if (now) {
				unicaevents_document_animate_to(href.substr(pos));
				unicaevents_document_set_location(pos==0 ? loc + href : href);
				e.preventDefault();
				return false;
			}
		}
	});
	
	
	// Store height of the top and side panels
	UNICAEVENTS_GLOBALS['top_panel_height'] = 0;	//Math.max(0, jQuery('.top_panel_wrap').height());
	UNICAEVENTS_GLOBALS['side_panel_height'] = 0;

	// Pagination
    //----------------------------------------------

	// Page navigation (style slider)
	jQuery('.pager_cur').on('click', function(e) {
		"use strict";
		jQuery('.pager_slider').slideDown(300, function() {
			unicaevents_init_shortcodes(jQuery('.pager_slider').eq(0));
		});
		e.preventDefault();
		return false;
	});


	// View More button
	jQuery('#viewmore_link').on('click', function(e) {
		"use strict";
		if (!UNICAEVENTS_GLOBALS['viewmore_busy'] && !jQuery(this).hasClass('viewmore_empty')) {
			jQuery(this).parent().addClass('loading');
			UNICAEVENTS_GLOBALS['viewmore_busy'] = true;
			jQuery.post(UNICAEVENTS_GLOBALS['ajax_url'], {
				action: 'view_more_posts',
				nonce: UNICAEVENTS_GLOBALS['ajax_nonce'],
				page: UNICAEVENTS_GLOBALS['viewmore_page']+1,
				data: UNICAEVENTS_GLOBALS['viewmore_data'],
				vars: UNICAEVENTS_GLOBALS['viewmore_vars']
			}).done(function(response) {
				"use strict";
				var rez = {};
				try {
					rez = JSON.parse(response);
				} catch (e) {
					rez = { error: UNICAEVENTS_GLOBALS['ajax_error'] };
					console.log(response);
				}
				jQuery('#viewmore_link').parent().removeClass('loading');
				UNICAEVENTS_GLOBALS['viewmore_busy'] = false;
				if (rez.error === '') {
					var posts_container = jQuery('.content').eq(0);
					if (posts_container.find('.isotope_wrap').length > 0) posts_container = posts_container.find('.isotope_wrap').eq(0);
					if (posts_container.hasClass('isotope_wrap')) {
						posts_container.data('last-width', 0).append(rez.data);
						UNICAEVENTS_GLOBALS['isotope_init_counter'] = 0;
						unicaevents_init_appended_isotope(posts_container, rez.filters);
					} else
						jQuery('#viewmore').before(rez.data);

					UNICAEVENTS_GLOBALS['viewmore_page']++;
					if (rez.no_more_data==1) {
						jQuery('#viewmore_link').addClass('viewmore_empty').parent().hide();
					}

					unicaevents_init_post_formats();
					unicaevents_init_shortcodes(posts_container);
					unicaevents_scroll_actions();
				}
			});
		}
		e.preventDefault();
		return false;
	});


	// Woocommerce
    //----------------------------------------------

	// Change display mode
	jQuery('.woocommerce,.woocommerce-page').on('click', '.mode_buttons a', function(e) {
		"use strict";
		var mode = jQuery(this).hasClass('woocommerce_thumbs') ? 'thumbs' : 'list';
		jQuery.cookie('unicaevents_shop_mode', mode, {expires: 365, path: '/'});
		jQuery(this).siblings('input').val(mode).parents('form').get(0).submit();
		e.preventDefault();
		return false;
	});
	// Added to cart
	jQuery('body').bind('added_to_cart', function() {
		"use strict";
		// Update amount on the cart button
		var total = jQuery('.menu_user_cart .total .amount').text();
		if (total != undefined) {
			jQuery('.top_panel_cart_button .cart_summa').text(total);
		}
		// Update count items on the cart button
		var cnt = 0;
		jQuery('.menu_user_cart .cart_list li').each(function() {
			var q = jQuery(this).find('.quantity').html().split(' ', 2);
			if (!isNaN(q[0]))
				cnt += Number(q[0]);
		});
		var items = jQuery('.top_panel_cart_button .cart_items').text().split(' ');
		items[0] = cnt;
		jQuery('.top_panel_cart_button .cart_items').text(items[0]+' '+items[1]);
		// Update data-attr on button
		jQuery('.top_panel_cart_button').data({
			'items': cnt ? cnt : 0,
			'summa': total ? total : 0
		});
	});
	// Show cart 
	jQuery('.top_panel_middle .top_panel_cart_button').on('click', function(e) {
		"use strict";
		jQuery(this).siblings('.sidebar_cart').slideToggle();
		e.preventDefault();
		return false;
	});


	// Popup login and register windows
    //----------------------------------------------
	jQuery('.popup_link').addClass('inited').on('click', function(e){
		var popup = jQuery(jQuery(this).attr('href'));
		if (popup.length === 1) {
			unicaevents_hide_popup(jQuery(popup.hasClass('popup_login') ? '.popup_registration' : '.popup_login' ));
			unicaevents_toggle_popup(popup);
		}
		e.preventDefault();
		return false;
	});
	jQuery('.popup_wrap').on('click', '.popup_close', function(e){
		var popup = jQuery(this).parent();
		if (popup.length === 1) {
			unicaevents_hide_popup(popup);
		}
		e.preventDefault();
		return false;
	});


	// Forms validation
    //----------------------------------------------

	// Login form
	jQuery('.popup_form.login_form').submit(function(e){
		"use strict";
		var rez = unicaevents_login_validate(jQuery(this));
		if (!rez)
			e.preventDefault();
		return rez;
	});
	
	// Registration form
	jQuery('.popup_form.registration_form').submit(function(e){
		"use strict";
		var rez = unicaevents_registration_validate(jQuery(this));
		if (!rez)
			e.preventDefault();
		return rez;
	});

	// Comment form
	jQuery("form#commentform").submit(function(e) {
		"use strict";
		var rez = unicaevents_comments_validate(jQuery(this));
		if (!rez)
			e.preventDefault();
		return rez;
	});



	// Bookmarks
    //----------------------------------------------

	// Add bookmark
	jQuery('.bookmarks_add').on('click', function(e) {
		"use strict";
		var title = window.document.title.split('|')[0];
		var url = window.location.href;
		var list = jQuery.cookie('unicaevents_bookmarks');
		var exists = false;
		if (list) {
			try {
				list = JSON.parse(list);
			} catch (e) {}
			if (list.length) {
				for (var i=0; i<list.length; i++) {
					if (list[i].url == url) {
						exists = true;
						break;
					}
				}
			}
		} else
			list = new Array();
		if (!exists) {
			var message_popup = unicaevents_message_dialog('<label for="bookmark_title">'+UNICAEVENTS_GLOBALS['strings']['bookmark_title']+'</label><br><input type="text" id="bookmark_title" name="bookmark_title" value="'+title+'">', UNICAEVENTS_GLOBALS['strings']['bookmark_add'], null,
				function(btn, popup) {
					"use strict";
					if (btn != 1) return;
					title = message_popup.find('#bookmark_title').val();
					list.push({title: title, url: url});
					jQuery('.bookmarks_list').append('<li><a href="'+url+'" class="bookmarks_item">'+title+'<span class="bookmarks_delete icon-cancel" title="'+UNICAEVENTS_GLOBALS['strings']['bookmark_del']+'"></span></a></li>');
					jQuery.cookie('unicaevents_bookmarks', JSON.stringify(list), {expires: 365, path: '/'});
					setTimeout(function () {unicaevents_message_success(UNICAEVENTS_GLOBALS['strings']['bookmark_added'], UNICAEVENTS_GLOBALS['strings']['bookmark_add']);}, UNICAEVENTS_GLOBALS['message_timeout']/4);
				});
		} else
			unicaevents_message_warning(UNICAEVENTS_GLOBALS['strings']['bookmark_exists'], UNICAEVENTS_GLOBALS['strings']['bookmark_add']);
		e.preventDefault();
		return false;
	});

	// Delete bookmark
	jQuery('.bookmarks_list').on('click', '.bookmarks_delete', function(e) {
		"use strict";
		var idx = jQuery(this).parent().index();
		var list = jQuery.cookie('unicaevents_bookmarks');
		if (list) {
			try {
				list = JSON.parse(list);
			} catch (e) {}
			if (list.length) {
				list.splice(idx, 1);
				jQuery.cookie('unicaevents_bookmarks', JSON.stringify(list), {expires: 365, path: '/'});
			}
		}
		jQuery(this).parent().remove();
		e.preventDefault();
		return false;
	});



	// Other settings
    //------------------------------------

	// Scroll to top button
	jQuery('.scroll_to_top').on('click', function(e) {
		"use strict";
		jQuery('html,body').animate({
			scrollTop: 0
		}, 'slow');
		e.preventDefault();
		return false;
	});

    // Show system message
	unicaevents_show_system_message();

	// Init post format specific scripts
	unicaevents_init_post_formats();

	// Init shortcodes scripts
	unicaevents_init_shortcodes(jQuery('body').eq(0));

	// Init hidden elements (if exists)
	if (window.unicaevents_init_hidden_elements) unicaevents_init_hidden_elements(jQuery('body').eq(0));
	
} //end ready




// Scroll actions
//==============================================

// Do actions when page scrolled
function unicaevents_scroll_actions() {
	"use strict";

	// Call skin specific action (if exists)
    //----------------------------------------------
	if (window.unicaevents_skin_scroll_actions) unicaevents_skin_scroll_actions();

	// Call theme specific action (if exists)
    //----------------------------------------------
	if (window.unicaevents_theme_scroll_actions) unicaevents_theme_scroll_actions();

	var scroll_offset = jQuery(window).scrollTop();
	var scroll_to_top_button = jQuery('.scroll_to_top');
	var adminbar_height = Math.max(0, jQuery('#wpadminbar').height());

	if (UNICAEVENTS_GLOBALS['top_panel_height'] == 0)	UNICAEVENTS_GLOBALS['top_panel_height'] = jQuery('.top_panel_wrap').height();

	// Scroll to top button show/hide
	if (scroll_offset > UNICAEVENTS_GLOBALS['top_panel_height'])
		scroll_to_top_button.addClass('show');
	else
		scroll_to_top_button.removeClass('show');
	
	// Fix/unfix top panel
	if (!jQuery('body').hasClass('responsive_menu') && UNICAEVENTS_GLOBALS['menu_fixed']) {
		var slider_height = 0;
		if (jQuery('.top_panel_below .slider_wrap').length > 0) {
			slider_height = jQuery('.top_panel_below .slider_wrap').height();
			if (slider_height < 10) {
				slider_height = jQuery('.slider_wrap').hasClass('.slider_fullscreen') ? jQuery(window).height() : UNICAEVENTS_GLOBALS['slider_height'];
			}
		}
		if (scroll_offset <= slider_height + UNICAEVENTS_GLOBALS['top_panel_height']) {
			if (jQuery('body').hasClass('top_panel_fixed')) {
				jQuery('body').removeClass('top_panel_fixed');
			}
		} else if (scroll_offset > slider_height + UNICAEVENTS_GLOBALS['top_panel_height']) {
			if (!jQuery('body').hasClass('top_panel_fixed')) {
				jQuery('.top_panel_fixed_wrap').height(UNICAEVENTS_GLOBALS['top_panel_height']);
				jQuery('.top_panel_wrap').css('marginTop', '-150px').animate({'marginTop': 0}, 500);
				jQuery('body').addClass('top_panel_fixed');
			}
		}
	}
	
	// Fix/unfix side panel
	if (jQuery('.sidebar_outer').length > 0) {
		if (UNICAEVENTS_GLOBALS['side_panel_height'] == 0)	
			UNICAEVENTS_GLOBALS['side_panel_height'] = jQuery('.sidebar_outer_logo_wrap').outerHeight() + jQuery('.sidebar_outer_menu').outerHeight() + jQuery('.sidebar_outer_widgets').outerHeight();
		if (scroll_offset + jQuery(window).height() > UNICAEVENTS_GLOBALS['side_panel_height'] + 100) {
			if (jQuery('.sidebar_outer').css('position')!=='fixed') {
				jQuery('.sidebar_outer').css({
					//'top': (scroll_offset + jQuery(window).height() - UNICAEVENTS_GLOBALS['side_panel_height'] - 100) + 'px'
					'position': 'fixed',
					'top': Math.min(0, jQuery(window).height() - UNICAEVENTS_GLOBALS['side_panel_height'] - 100) + 'px',
				});
			}
		} else {
			if (jQuery('.sidebar_outer').css('position')=='fixed') {
				jQuery('.sidebar_outer').css({
					'position': 'absolute',
					'top': 0
				});
			}
		}
	}

	// TOC current items
	jQuery('#toc .toc_item').each(function() {
		"use strict";
		var id = jQuery(this).find('a').attr('href');
		var pos = id.indexOf('#');
		if (pos < 0 || id.length == 1) return;
		var loc = window.location.href;
		var pos2 = loc.indexOf('#');
		if (pos2 > 0) loc = loc.substring(0, pos2);
		var now = pos==0;
		if (!now) now = loc == href.substring(0, pos);
		if (!now) return;
		var off = jQuery(id).offset().top;
		var id_next  = jQuery(this).next().find('a').attr('href');
		var off_next = id_next ? jQuery(id_next).offset().top : 1000000;
		if (off < scroll_offset + jQuery(window).height()*0.8 && scroll_offset + UNICAEVENTS_GLOBALS['top_panel_height'] < off_next)
			jQuery(this).addClass('current');
		else
			jQuery(this).removeClass('current');
	});
	
	// Infinite pagination
	unicaevents_infinite_scroll()
	
	// Parallax scroll
	unicaevents_parallax_scroll();
	
	// Scroll actions for shortcodes
	unicaevents_animation_shortcodes();
}


// Infinite Scroll
function unicaevents_infinite_scroll() {
	"use strict";
	if (UNICAEVENTS_GLOBALS['viewmore_busy']) return;
	var infinite = jQuery('#viewmore.pagination_infinite');
	if (infinite.length > 0) {
		var viewmore = infinite.find('#viewmore_link:not(.viewmore_empty)');
		if (viewmore.length > 0) {
			if (jQuery(window).scrollTop() + jQuery(window).height() + 100 >= infinite.offset().top) {
				viewmore.eq(0).trigger('click');
			}
		}
	}
}

// Parallax scroll
function unicaevents_parallax_scroll(){
	jQuery('.sc_parallax').each(function(){
		var windowHeight = jQuery(window).height();
		var scrollTops = jQuery(window).scrollTop();
		var offsetPrx = Math.max(jQuery(this).offset().top, windowHeight);
		if ( offsetPrx <= scrollTops + windowHeight ) {
			var speed  = Number(jQuery(this).data('parallax-speed'));
			var xpos   = jQuery(this).data('parallax-x-pos');  
			var ypos   = Math.round((offsetPrx - scrollTops - windowHeight) * speed + (speed < 0 ? windowHeight*speed : 0));
			jQuery(this).find('.sc_parallax_content').css('backgroundPosition', xpos+' '+ypos+'px');
			// Uncomment next line if you want parallax video (else - video position is static)
			jQuery(this).find('div.sc_video_bg').css('top', ypos+'px');
		} 
	});
}





// Resize actions
//==============================================

// Do actions when page scrolled
function unicaevents_resize_actions() {
	"use strict";

	// Call skin specific action (if exists)
    //----------------------------------------------
	if (window.unicaevents_skin_resize_actions) unicaevents_skin_resize_actions();

	// Call theme specific action (if exists)
    //----------------------------------------------
	if (window.unicaevents_theme_resize_actions) unicaevents_theme_resize_actions();

	unicaevents_responsive_menu();
	unicaevents_video_dimensions();
	unicaevents_resize_video_background();
	unicaevents_resize_fullscreen_slider();
	unicaevents_resize_alter_portfolio();
}


// Check window size and do responsive menu
function unicaevents_responsive_menu() {
	if (unicaevents_is_responsive_need(UNICAEVENTS_GLOBALS['menu_responsive'])) {
		if (!jQuery('body').hasClass('responsive_menu')) {
			jQuery('body').removeClass('top_panel_fixed').addClass('responsive_menu');
			if (jQuery('body').hasClass('menu_relayout'))
				jQuery('body').removeClass('menu_relayout');
			if (jQuery('ul.menu_main_nav').hasClass('inited')) {
				jQuery('ul.menu_main_nav').removeClass('inited').superfish('destroy');
			}
			if (jQuery('ul.menu_side_nav').hasClass('inited')) {
				jQuery('ul.menu_side_nav').removeClass('inited').superfish('destroy');
			}
		}
	} else {
		if (jQuery('body').hasClass('responsive_menu')) {
			jQuery('body').removeClass('responsive_menu');
			jQuery('.menu_main_responsive').hide();
			jQuery('.menu_side_responsive').hide();
			unicaevents_init_sfmenu('ul.menu_main_nav,ul.menu_side_nav');
			jQuery('.menu_main_nav_area').show();
		}
		if (unicaevents_is_responsive_need(UNICAEVENTS_GLOBALS['menu_relayout']))
			jQuery('body').addClass('menu_relayout');
		else if (jQuery('body').hasClass('menu_relayout'))
			jQuery('body').removeClass('menu_relayout');
	}
	if (!jQuery('.top_panel_wrap').hasClass('menu_show')) jQuery('.top_panel_wrap').addClass('menu_show');
	// Show widgets block on the sidebar outer (if sidebar not responsive and widgets are hidden)
	if (jQuery('.sidebar_outer').length > 0 && jQuery('.sidebar_outer').css('position')=='absolute' && jQuery('.sidebar_outer_widgets:visible').length==0) 
		jQuery('.sidebar_outer_widgets').show();
}


// Check if responsive menu need
function unicaevents_is_responsive_need(max_width) {
	"use strict";
	var rez = false;
	if (max_width > 0) {
		var w = window.innerWidth;
		if (w == undefined) {
			w = jQuery(window).width()+(jQuery(window).height() < jQuery(document).height() || jQuery(window).scrollTop() > 0 ? 16 : 0);
		}
		rez = max_width > w;
	}
	return rez;
}


// Show current page title on the responsive menu button
function unicaevents_show_current_menu_item(menu, button) {
	"use strict";
	menu.find('a').each(function () {
		var menu_link = jQuery(this);
		if (menu_link.text() == "") {
			return;
		}
		if (menu_link.attr('href') == window.location.href)
			button.text(menu_link.text());
	});
}


// Fit video frames to document width
function unicaevents_video_dimensions() {
	jQuery('.sc_video_frame').each(function() {
		"use strict";
		var frame  = jQuery(this).eq(0);
		var player = frame.parent();
		var ratio = (frame.data('ratio') ? frame.data('ratio').split(':') : (frame.find('[data-ratio]').length>0 ? frame.find('[data-ratio]').data('ratio').split(':') : [16,9]));
		ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
		var w_attr = frame.data('width');
		var h_attr = frame.data('height');
		if (!w_attr || !h_attr) return;
		var percent = (''+w_attr).substr(-1)=='%';
		w_attr = parseInt(w_attr);
		h_attr = parseInt(h_attr);
		var w_real = Math.min(percent || frame.parents('.columns_wrap').length>0 ? 10000 : w_attr, frame.parents('div,article').width()), //player.width();
			h_real = Math.round(percent ? w_real/ratio : w_real/w_attr*h_attr);
		if (parseInt(frame.attr('data-last-width'))==w_real) return;
		if (percent) {
			frame.height(h_real);
		} else {
			frame.css({'width': w_real+'px', 'height': h_real+'px'});
		}
		frame.attr('data-last-width', w_real);
	});
	jQuery('video.sc_video,video.wp-video-shortcode').each(function() {
		"use strict";
		var video = jQuery(this).eq(0);
		var ratio = (video.data('ratio')!=undefined ? video.data('ratio').split(':') : [16,9]);
		ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
		var mejs_cont = video.parents('.mejs-video');
		var frame = video.parents('.sc_video_frame');
		var w_attr = frame.length>0 ? frame.data('width') : video.data('width');
		var h_attr = frame.length>0 ? frame.data('height') : video.data('height');
		if (!w_attr || !h_attr) {
			w_attr = video.attr('width');
			h_attr = video.attr('height');
			if (!w_attr || !h_attr) return;
			video.data({'width': w_attr, 'height': h_attr});
		}
		var percent = (''+w_attr).substr(-1)=='%';
		w_attr = parseInt(w_attr);
		h_attr = parseInt(h_attr);
		var w_real = Math.round(mejs_cont.length > 0 ? Math.min(percent ? 10000 : w_attr, mejs_cont.parents('div,article').width()) : video.width()),
			h_real = Math.round(percent ? w_real/ratio : w_real/w_attr*h_attr);
		if (parseInt(video.attr('data-last-width'))==w_real) return;
		if (mejs_cont.length > 0 && mejs) {
			unicaevents_set_mejs_player_dimensions(video, w_real, h_real);
		}
		if (percent) {
			video.height(h_real);
		} else {
			video.attr({'width': w_real, 'height': h_real}).css({'width': w_real+'px', 'height': h_real+'px'});
		}
		video.attr('data-last-width', w_real);
	});
	jQuery('video.sc_video_bg').each(function() {
		"use strict";
		var video = jQuery(this).eq(0);
		var ratio = (video.data('ratio')!=undefined ? video.data('ratio').split(':') : [16,9]);
		ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
		var mejs_cont = video.parents('.mejs-video');
		var container = mejs_cont.length>0 ? mejs_cont.parent() : video.parent();
		var w = container.width();
		var h = container.height();
		var w1 = Math.ceil(h*ratio);
		var h1 = Math.ceil(w/ratio);
		if (video.parents('.sc_parallax').length > 0) {
			var windowHeight = jQuery(window).height();
			var speed = Number(video.parents('.sc_parallax').data('parallax-speed'));
			var h_add = Math.ceil(Math.abs((windowHeight-h)*speed));
			if (h1 < h + h_add) {
				h1 = h + h_add;
				w1 = Math.ceil(h1 * ratio);
			}
		}
		if (h1 < h) {
			h1 = h;
			w1 = Math.ceil(h1 * ratio);
		}
		if (w1 < w) { 
			w1 = w;
			h1 = Math.ceil(w1 / ratio);
		}
		var l = Math.round((w1-w)/2);
		var t = Math.round((h1-h)/2);
		if (parseInt(video.attr('data-last-width'))==w1) return;
		if (mejs_cont.length > 0) {
			unicaevents_set_mejs_player_dimensions(video, w1, h1);
			mejs_cont.css({
				//'left': -l+'px',
				'top': -t+'px'
			});
		} else
			video.css({
				//'left': -l+'px',
				'top': -t+'px'
			});
		video.attr({'width': w1, 'height': h1, 'data-last-width':w1}).css({'width':w1+'px', 'height':h1+'px'});
		if (video.css('opacity')==0) video.animate({'opacity': 1}, 3000);
	});
	jQuery('iframe').each(function() {
		"use strict";
		var iframe = jQuery(this).eq(0);
		var ratio = (iframe.data('ratio')!=undefined ? iframe.data('ratio').split(':') : (iframe.find('[data-ratio]').length>0 ? iframe.find('[data-ratio]').data('ratio').split(':') : [16,9]));
		ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
		var w_attr = iframe.attr('width');
		var h_attr = iframe.attr('height');
		var frame = iframe.parents('.sc_video_frame');
		if (frame.length > 0) {
			w_attr = frame.data('width');
			h_attr = frame.data('height');
		}
		if (!w_attr || !h_attr) {
			return;
		}
		var percent = (''+w_attr).substr(-1)=='%';
		w_attr = parseInt(w_attr);
		h_attr = parseInt(h_attr);
		var w_real = frame.length > 0 ? frame.width() : iframe.width(),
			h_real = Math.round(percent ? w_real/ratio : w_real/w_attr*h_attr);
		if (parseInt(iframe.attr('data-last-width'))==w_real) return;
		iframe.css({'width': w_real+'px', 'height': h_real+'px'});
	});
}

// Resize fullscreen video background
function unicaevents_resize_video_background() {
	"use strict";
	var bg = jQuery('.video_bg');
	if (bg.length < 1) 
		return;
	if (UNICAEVENTS_GLOBALS['media_elements_enabled'] && bg.find('.mejs-video').length == 0)  {
		setTimeout(unicaevents_resize_video_background, 100);
		return;
	}
	var video = bg.find('video');
	var ratio = (video.data('ratio')!=undefined ? video.data('ratio').split(':') : [16,9]);
	ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
	var w = bg.width();
	var h = bg.height();
	var w1 = Math.ceil(h*ratio);
	var h1 = Math.ceil(w/ratio);
	if (h1 < h) {
		h1 = h;
		w1 = Math.ceil(h1 * ratio);
	}
	if (w1 < w) { 
		w1 = w;
		h1 = Math.ceil(w1 / ratio);
	}
	var l = Math.round((w1-w)/2);
	var t = Math.round((h1-h)/2);
	if (bg.find('.mejs-container').length > 0) {
		unicaevents_set_mejs_player_dimensions(bg.find('video'), w1, h1);
		bg.find('.mejs-container').css({'left': -l+'px', 'top': -t+'px'});
	} else
		bg.find('video').css({'left': -l+'px', 'top': -t+'px'});
	bg.find('video').attr({'width': w1, 'height': h1}).css({'width':w1+'px', 'height':h1+'px'});
}

// Set Media Elements player dimensions
function unicaevents_set_mejs_player_dimensions(video, w, h) {
	"use strict";
	if (mejs) {
		for (var pl in mejs.players) {
			if (mejs.players[pl].media.src == video.attr('src')) {
				if (mejs.players[pl].media.setVideoSize) {
					mejs.players[pl].media.setVideoSize(w, h);
				}
				mejs.players[pl].setPlayerSize(w, h);
				mejs.players[pl].setControlsSize();
				//var mejs_cont = video.parents('.mejs-video');
				//mejs_cont.css({'width': w+'px', 'height': h+'px'}).find('.mejs-layers > div, .mejs-overlay, .mejs-poster').css({'width': w, 'height': h});
			}
		}
	}
}

// Resize Fullscreen Slider
function unicaevents_resize_fullscreen_slider() {
	"use strict";
	var slider_wrap = jQuery('.slider_wrap.slider_fullscreen');
	if (slider_wrap.length < 1) 
		return;
	var slider = slider_wrap.find('.sc_slider_swiper');
	if (slider.length < 1) 
		return;
	var h = jQuery(window).height() - jQuery('#wpadminbar').height() - (jQuery('body').hasClass('top_panel_above') && !jQuery('body').hasClass('.top_panel_fixed') ? jQuery('.top_panel_wrap').height() : 0);
	slider.height(h);
}

// Resize Alter portfolio elements
function unicaevents_resize_alter_portfolio() {
	"use strict";
	var wrap = jQuery('.isotope_wrap.inited');
	if (wrap.length==0) return;
	wrap.each(function() {
		"use strict";
		var alter = jQuery(this).find('.post_item_alter');
		if (alter.length==0) return;
		var single = alter.find('.post_featured img[data-alter-items-w="1"]').eq(0);
		if (single.length != 1) return;
		var w_real = single.width();
		var h_real = single.height();
		var space = Number(single.data('alter-item-space'));
		var relayout = false;
		alter.find('.post_featured img').each(function() {
			"use strict";
			var items_w = Number(jQuery(this).data('alter-items-w'));
			var items_h = Number(jQuery(this).data('alter-items-h'));
			if (items_h > 1) {
				jQuery(this).height(Math.round(items_h*h_real+(items_h-1)*(space+1)));
				relayout = true;
			} else if (items_w > 1) {
				jQuery(this).height(h_real);
				relayout = true;
			}
		});
		if (relayout) {
			jQuery(this).isotope('layout');
		}
	});
}





// Navigation
//==============================================

// Init Superfish menu
function unicaevents_init_sfmenu(selector) {
	jQuery(selector).show().each(function() {
		if (unicaevents_is_responsive_need() && (jQuery(this).attr('id')=='menu_main' || jQuery(this).attr('id')=='menu_side')) return;
		jQuery(this).addClass('inited').superfish({
			delay: 500,
			animation: {
				opacity: 'show'
			},
			animationOut: {
				opacity: 'hide'
			},
			speed: 		UNICAEVENTS_GLOBALS['css_animation'] ? 500 : (UNICAEVENTS_GLOBALS['menu_slider'] ? 300 : 200),
			speedOut:	UNICAEVENTS_GLOBALS['css_animation'] ? 500 : (UNICAEVENTS_GLOBALS['menu_slider'] ? 300 : 200),
			autoArrows: false,
			dropShadows: false,
			onBeforeShow: function(ul) {
				if (jQuery(this).parents("ul").length > 1){
					var w = jQuery(window).width();  
					var par_offset = jQuery(this).parents("ul").offset().left;
					var par_width  = jQuery(this).parents("ul").outerWidth();
					var ul_width   = jQuery(this).outerWidth();
					if (par_offset+par_width+ul_width > w-20 && par_offset-ul_width > 0)
						jQuery(this).addClass('submenu_left');
					else
						jQuery(this).removeClass('submenu_left');
				}
				if (UNICAEVENTS_GLOBALS['css_animation']) {
					jQuery(this).removeClass('animated fast '+UNICAEVENTS_GLOBALS['menu_animation_out']);
					jQuery(this).addClass('animated fast '+UNICAEVENTS_GLOBALS['menu_animation_in']);
				}
			},
			onBeforeHide: function(ul) {
				if (UNICAEVENTS_GLOBALS['css_animation']) {
					jQuery(this).removeClass('animated fast '+UNICAEVENTS_GLOBALS['menu_animation_in']);
					jQuery(this).addClass('animated fast '+UNICAEVENTS_GLOBALS['menu_animation_out']);
				}
			}
		});
	});
}


// Build page TOC from the tag's id
function unicaevents_build_page_toc() {
	"use strict";
	var toc = '', toc_count = 0;
	jQuery('[id^="toc_"],.sc_anchor').each(function(idx) {
		"use strict";
		var obj = jQuery(this);
		var id = obj.attr('id');
		var url = obj.data('url');
		var icon = obj.data('icon');
		if (!icon) icon = 'icon-circle-dot';
		var title = obj.attr('title');
		var description = obj.data('description');
		var separator = obj.data('separator');
		toc_count++;
		toc += '<div class="toc_item'+(separator=='yes' ? ' toc_separator' : '')+'">'
			+(description ? '<div class="toc_description">'+description+'</div>' : '')
			+'<a href="'+(url ? url : '#'+id)+'" class="toc_icon'+(title ? ' with_title' : '')+' '+icon+'">'+(title ? '<span class="toc_title">'+title+'</span>' : '')+'</a>'
			+'</div>';
	});
	if (toc_count > (UNICAEVENTS_GLOBALS['toc_menu_home'] ? 1 : 0) + (UNICAEVENTS_GLOBALS['toc_menu_top'] ? 1 : 0)) {
		if (jQuery('#toc').length > 0)
			jQuery('#toc .toc_inner').html(toc);
		else
			jQuery('body').append('<div id="toc" class="toc_'+UNICAEVENTS_GLOBALS['toc_menu']+'"><div class="toc_inner">'+toc+'</div></div>');
	}
}




// Isotope
//=====================================================

// First init isotope containers
function unicaevents_init_isotope() {
	"use strict";

	var all_images_complete = true;

	// Check if all images in isotope wrapper are loaded
	jQuery('.isotope_wrap:not(.inited)').each(function () {
		"use strict";
		all_images_complete = all_images_complete && unicaevents_check_images_complete(jQuery(this));
	});
	// Wait for images loading
	if (!all_images_complete && UNICAEVENTS_GLOBALS['isotope_init_counter']++ < 30) {
		setTimeout(unicaevents_init_isotope, 200);
		return;
	}

	// Isotope filters handler
	jQuery('.isotope_filters:not(.inited)').addClass('inited').on('click', 'a', function(e) {
		"use strict";
		jQuery(this).parents('.isotope_filters').find('a').removeClass('active');
		jQuery(this).addClass('active');
	
		var selector = jQuery(this).data('filter');
		jQuery(this).parents('.isotope_filters').siblings('.isotope_wrap').eq(0).isotope({
			filter: selector
		});

		if (selector == '*')
			jQuery('#viewmore_link').fadeIn();
		else
			jQuery('#viewmore_link').fadeOut();

		e.preventDefault();
		return false;
	});

	// Init isotope script
	jQuery('.isotope_wrap:not(.inited)').each(function() {
		"use strict";

		var isotope_container = jQuery(this);

		// Init shortcodes
		unicaevents_init_shortcodes(isotope_container);

		// If in scroll container - no init isotope
		if (isotope_container.parents('.sc_scroll').length > 0) {
			isotope_container.addClass('inited').find('.isotope_item').animate({opacity: 1}, 200, function () { jQuery(this).addClass('isotope_item_show'); });
			return;
		}
		
		// Init isotope with timeout
		setTimeout(function() {
			isotope_container.addClass('inited').isotope({
				itemSelector: '.isotope_item',
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: false
				}
			});
	
			// Show elements
			isotope_container.find('.isotope_item').animate({opacity: 1}, 200, function () { 
				jQuery(this).addClass('isotope_item_show'); 
			});
			
			// Resize Alter portfolio elements
			unicaevents_resize_alter_portfolio();
			
		}, 500);

	});		
}

function unicaevents_init_appended_isotope(posts_container, filters) {
	"use strict";
	
	if (posts_container.parents('.sc_scroll_horizontal').length > 0) return;
	
	if (!unicaevents_check_images_complete(posts_container) && UNICAEVENTS_GLOBALS['isotope_init_counter']++ < 30) {
		setTimeout(function() { unicaevents_init_appended_isotope(posts_container, filters); }, 200);
		return;
	}
	// Add filters
	var flt = posts_container.siblings('.isotope_filter');
	for (var i in filters) {
		if (flt.find('a[data-filter=".flt_'+i+'"]').length == 0) {
			flt.append('<a href="#" class="isotope_filters_button" data-filter=".flt_'+i+'">'+filters[i]+'</a>');
		}
	}
	// Init shortcodes in added elements
	unicaevents_init_shortcodes(posts_container);
	// Get added elements
	var elems = posts_container.find('.isotope_item:not(.isotope_item_show)');
	// Notify isotope about added elements with timeout
	setTimeout(function() {
		posts_container.isotope('appended', elems);
		// Show appended elements
		elems.animate({opacity: 1}, 200, function () { jQuery(this).addClass('isotope_item_show'); });
	}, 500);
}



// Shortcodes init
//=====================================================

function unicaevents_init_shortcodes(cont) {
	"use strict";

	// Call theme specific action (if exists)
	if (window.unicaevents_theme_sc_init) unicaevents_theme_sc_init(cont);

	// Call core shortcodes action (if exists)
	if (window.unicaevents_sc_init) unicaevents_sc_init(cont);
}

function unicaevents_animation_shortcodes() {
	"use strict";
	if (window.unicaevents_sc_animation) unicaevents_sc_animation();
}



// Post formats init
//=====================================================

function unicaevents_init_post_formats() {
	"use strict";

	// Call theme specific action (if exists)
	if (window.unicaevents_theme_init_post_formats) unicaevents_theme_init_post_formats();

	// MediaElement init
	unicaevents_init_media_elements(jQuery('body'));
	
	// Isotope first init
	if (jQuery('.isotope_wrap:not(.inited)').length > 0) {
		UNICAEVENTS_GLOBALS['isotope_init_counter'] = 0;
		unicaevents_init_isotope();
	}

	// Hover Effect 'Dir'
	if (jQuery('.isotope_wrap .isotope_item_content.square.effect_dir:not(.inited)').length > 0) {
		jQuery('.isotope_wrap .isotope_item_content.square.effect_dir:not(.inited)').each(function() {
			jQuery(this).addClass('inited').hoverdir();
		});
	}

	// Popup init
	if (UNICAEVENTS_GLOBALS['popup_engine'] == 'pretty') {
		jQuery("a[href$='jpg'],a[href$='jpeg'],a[href$='png'],a[href$='gif']").attr('rel', 'prettyPhoto[slideshow]');
		var images = jQuery("a[rel*='prettyPhoto']:not(.inited):not([data-rel*='pretty']):not([rel*='magnific']):not([data-rel*='magnific'])").addClass('inited');
		try {
			images.prettyPhoto({
				social_tools: '',
				theme: 'facebook',
				deeplinking: false
			});
		} catch (e) {};
	} else if (UNICAEVENTS_GLOBALS['popup_engine']=='magnific') {
		jQuery("a[href$='jpg'],a[href$='jpeg'],a[href$='png'],a[href$='gif']").attr('rel', 'magnific');
		var images = jQuery("a[rel*='magnific']:not(.inited):not(.prettyphoto):not([rel*='pretty']):not([data-rel*='pretty'])").addClass('inited');
		try {
			images.magnificPopup({
				type: 'image',
				mainClass: 'mfp-img-mobile',
				closeOnContentClick: true,
				closeBtnInside: true,
				fixedContentPos: true,
				midClick: true,
				//removalDelay: 500, 
				preloader: true,
				tLoading: UNICAEVENTS_GLOBALS['strings']['magnific_loading'],
				gallery:{
					enabled: true
				},
				image: {
					tError: UNICAEVENTS_GLOBALS['strings']['magnific_error'],
					verticalFit: true
				}
			});
		} catch (e) {};
	}


	// Add hover icon to products thumbnails
	jQuery(".post_item_product .product .images a.woocommerce-main-image:not(.hover_icon)").addClass('hover_icon hover_icon_view');


	// Likes counter
	if (jQuery('.post_counters_likes:not(.inited)').length > 0) {
		jQuery('.post_counters_likes:not(.inited)')
			.addClass('inited')
			.on('click', function(e) {
				var button = jQuery(this);
				var inc = button.hasClass('enabled') ? 1 : -1;
				var post_id = button.data('postid');
				var likes = Number(button.data('likes'))+inc;
				var cookie_likes = unicaevents_get_cookie('unicaevents_likes');
				if (cookie_likes === undefined || cookie_likes===null) cookie_likes = '';
				jQuery.post(UNICAEVENTS_GLOBALS['ajax_url'], {
					action: 'post_counter',
					nonce: UNICAEVENTS_GLOBALS['ajax_nonce'],
					post_id: post_id,
					likes: likes
				}).done(function(response) {
					var rez = {};
					try {
						rez = JSON.parse(response);
					} catch (e) {
						rez = { error: UNICAEVENTS_GLOBALS['ajax_error'] };
						console.log(response);
					}
					if (rez.error === '') {
						if (inc == 1) {
							var title = button.data('title-dislike');
							button.removeClass('enabled').addClass('disabled');
							cookie_likes += (cookie_likes.substr(-1)!=',' ? ',' : '') + post_id + ',';
						} else {
							var title = button.data('title-like');
							button.removeClass('disabled').addClass('enabled');
							cookie_likes = cookie_likes.replace(','+post_id+',', ',');
						}
						button.data('likes', likes).attr('title', title).find('.post_counters_number').html(likes);
						unicaevents_set_cookie('unicaevents_likes', cookie_likes, 365);
					} else {
						unicaevents_message_warning(UNICAEVENTS_GLOBALS['strings']['error_like']);
					}
				});
				e.preventDefault();
				return false;
			});
	}

	// Add video on thumb click
	if (jQuery('.sc_video_play_button:not(.inited)').length > 0) {
		jQuery('.sc_video_play_button:not(.inited)').each(function() {
			"use strict";
			jQuery(this)
				.addClass('inited')
				.animate({opacity: 1}, 1000)
				.on('click', function (e) {
					"use strict";
					if (!jQuery(this).hasClass('sc_video_play_button')) return;
					var video = jQuery(this).removeClass('sc_video_play_button hover_icon_play').data('video');
					if (video!=='') {
						jQuery(this).empty().html(video);
						unicaevents_video_dimensions();
						var video_tag = jQuery(this).find('video');
						var w = video_tag.width();
						var h = video_tag.height();
						unicaevents_init_media_elements(jQuery(this));
						// Restore WxH attributes, because Chrome broke it!
						jQuery(this).find('video').css({'width':w, 'height': h}).attr({'width':w, 'height': h});
					}
					e.preventDefault();
					return false;
				});
		});
	}

	// Tribe Events buttons
	jQuery('a.tribe-events-read-more,.tribe-events-button,.tribe-events-nav-previous a,.tribe-events-nav-next a,.tribe-events-widget-link a,.tribe-events-viewmore a').addClass('sc_button sc_button_style_filled');
}


function unicaevents_init_media_elements(cont) {
	if (UNICAEVENTS_GLOBALS['media_elements_enabled'] && cont.find('audio,video').length > 0) {
		if (window.mejs) {
			window.mejs.MepDefaults.enableAutosize = false;
			window.mejs.MediaElementDefaults.enableAutosize = false;
			cont.find('audio:not(.wp-audio-shortcode),video:not(.wp-video-shortcode)').each(function() {
				if (jQuery(this).parents('.mejs-mediaelement').length == 0) {
					var media_tag = jQuery(this);
					var settings = {
						enableAutosize: true,
						videoWidth: -1,		// if set, overrides <video width>
						videoHeight: -1,	// if set, overrides <video height>
						audioWidth: '100%',	// width of audio player
						audioHeight: 30,	// height of audio player
						success: function(mejs) {
							var autoplay, loop;
							if ( 'flash' === mejs.pluginType ) {
								autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;
								loop = mejs.attributes.loop && 'false' !== mejs.attributes.loop;
								autoplay && mejs.addEventListener( 'canplay', function () {
									mejs.play();
								}, false );
								loop && mejs.addEventListener( 'ended', function () {
									mejs.play();
								}, false );
							}
							media_tag.parents('.sc_audio,.sc_video').addClass('inited sc_show');
						}
					};
					jQuery(this).mediaelementplayer(settings);
				}
			});
		} else
			setTimeout(function() { unicaevents_init_media_elements(cont); }, 400);
	}
}






// Popups and system messages
//==============================================

// Show system message (bubble from previous page)
function unicaevents_show_system_message() {
	if (UNICAEVENTS_GLOBALS['system_message'] && UNICAEVENTS_GLOBALS['system_message']['message']) {
		if (UNICAEVENTS_GLOBALS['system_message']['status'] == 'success')
			unicaevents_message_success(UNICAEVENTS_GLOBALS['system_message']['message'], UNICAEVENTS_GLOBALS['system_message']['header']);
		else if (UNICAEVENTS_GLOBALS['system_message']['status'] == 'info')
			unicaevents_message_info(UNICAEVENTS_GLOBALS['system_message']['message'], UNICAEVENTS_GLOBALS['system_message']['header']);
		else if (UNICAEVENTS_GLOBALS['system_message']['status'] == 'error' || UNICAEVENTS_GLOBALS['system_message']['status'] == 'warning')
			unicaevents_message_warning(UNICAEVENTS_GLOBALS['system_message']['message'], UNICAEVENTS_GLOBALS['system_message']['header']);
	}
}

// Toggle popups
function unicaevents_toggle_popup(popup) {
	if (popup.css('display')!='none')
		unicaevents_hide_popup(popup);
	else
		unicaevents_show_popup(popup);
}

// Show popups
function unicaevents_show_popup(popup) {
	if (popup.css('display')=='none') {
		if (UNICAEVENTS_GLOBALS['css_animation'])
			popup.show().removeClass('animated fast '+UNICAEVENTS_GLOBALS['menu_animation_out']).addClass('animated fast '+UNICAEVENTS_GLOBALS['menu_animation_in']);
		else
			popup.slideDown();
	}
}

// Hide popups
function unicaevents_hide_popup(popup) {
	if (popup.css('display')!='none') {
		if (UNICAEVENTS_GLOBALS['css_animation'])
			popup.removeClass('animated fast '+UNICAEVENTS_GLOBALS['menu_animation_in']).addClass('animated fast '+UNICAEVENTS_GLOBALS['menu_animation_out']).delay(500).hide();
		else
			popup.fadeOut();
	}
}




// Forms validation
//-------------------------------------------------------


// Comments form
function unicaevents_comments_validate(form) {
	"use strict";
	form.find('input').removeClass('error_fields_class');
	var error = unicaevents_form_validate(form, {
		error_message_text: UNICAEVENTS_GLOBALS['strings']['error_global'],	// Global error message text (if don't write in checked field)
		error_message_show: true,									// Display or not error message
		error_message_time: 4000,									// Error message display time
		error_message_class: 'sc_infobox sc_infobox_style_error',	// Class appended to error message block
		error_fields_class: 'error_fields_class',					// Class appended to error fields
		exit_after_first_error: false,								// Cancel validation and exit after first error
		rules: [
			{
				field: 'author',
				min_length: { value: 1, message: UNICAEVENTS_GLOBALS['strings']['name_empty']},
				max_length: { value: 60, message: UNICAEVENTS_GLOBALS['strings']['name_long']}
			},
			{
				field: 'email',
				min_length: { value: 7, message: UNICAEVENTS_GLOBALS['strings']['email_empty']},
				max_length: { value: 60, message: UNICAEVENTS_GLOBALS['strings']['email_long']},
				mask: { value: UNICAEVENTS_GLOBALS['email_mask'], message: UNICAEVENTS_GLOBALS['strings']['email_not_valid']}
			},
			{
				field: 'comment',
				min_length: { value: 1, message: UNICAEVENTS_GLOBALS['strings']['text_empty'] },
				max_length: { value: UNICAEVENTS_GLOBALS['comments_maxlength'], message: UNICAEVENTS_GLOBALS['strings']['text_long']}
			}
		]
	});
	return !error;
}


// Login form
function unicaevents_login_validate(form) {
	"use strict";
	form.find('input').removeClass('error_fields_class');
	var error = unicaevents_form_validate(form, {
		error_message_show: true,
		error_message_time: 4000,
		error_message_class: 'sc_infobox sc_infobox_style_error',
		error_fields_class: 'error_fields_class',
		exit_after_first_error: true,
		rules: [
			{
				field: "log",
				min_length: { value: 1, message: UNICAEVENTS_GLOBALS['strings']['login_empty'] },
				max_length: { value: 60, message: UNICAEVENTS_GLOBALS['strings']['login_long'] }
			},
			{
				field: "pwd",
				min_length: { value: 4, message: UNICAEVENTS_GLOBALS['strings']['password_empty'] },
				max_length: { value: 30, message: UNICAEVENTS_GLOBALS['strings']['password_long'] }
			}
		]
	});
	if (!error) {
		jQuery.post(UNICAEVENTS_GLOBALS['ajax_url'], {
			action: 'login_user',
			nonce: UNICAEVENTS_GLOBALS['ajax_nonce'],
			remember: form.find('#rememberme').val(),
			user_log: form.find('#log').val(),
			user_pwd: form.find('#password').val()
		}).done(function(response) {
			var rez = {};
			try {
				rez = JSON.parse(response);
			} catch (e) {
				rez = { error: UNICAEVENTS_GLOBALS['ajax_error'] };
				console.log(response);
			}
			var result_box = form.find('.result');
			if (result_box.length==0) result_box = form.siblings('.result');
			if (result_box.length==0) result_box = form.after('<div class="result"></div>').next('.result');
			result_box.toggleClass('sc_infobox_style_error', false).toggleClass('sc_infobox_style_success', false);
			if (rez.error === '') {
				result_box.addClass('sc_infobox sc_infobox_style_success').html(UNICAEVENTS_GLOBALS['strings']['login_success']);
				setTimeout(function() { 
					location.reload(); 
					}, 3000);
			} else {
				result_box.addClass('sc_infobox sc_infobox_style_error').html(UNICAEVENTS_GLOBALS['strings']['login_failed'] + '<br>' + rez.error);
			}
			result_box.fadeIn().delay(3000).fadeOut();
		});
	}
	return false;
}


// Registration form 
function unicaevents_registration_validate(form) {
	"use strict";
	form.find('input').removeClass('error_fields_class');
	var error = unicaevents_form_validate(form, {
		error_message_show: true,
		error_message_time: 4000,
		error_message_class: "sc_infobox sc_infobox_style_error",
		error_fields_class: "error_fields_class",
		exit_after_first_error: true,
		rules: [
			{
				field: "registration_username",
				min_length: { value: 1, message: UNICAEVENTS_GLOBALS['strings']['login_empty'] },
				max_length: { value: 60, message: UNICAEVENTS_GLOBALS['strings']['login_long'] }
			},
			{
				field: "registration_email",
				min_length: { value: 7, message: UNICAEVENTS_GLOBALS['strings']['email_empty'] },
				max_length: { value: 60, message: UNICAEVENTS_GLOBALS['strings']['email_long'] },
				mask: { value: UNICAEVENTS_GLOBALS['email_mask'], message: UNICAEVENTS_GLOBALS['strings']['email_not_valid'] }
			},
			{
				field: "registration_pwd",
				min_length: { value: 4, message: UNICAEVENTS_GLOBALS['strings']['password_empty'] },
				max_length: { value: 30, message: UNICAEVENTS_GLOBALS['strings']['password_long'] }
			},
			{
				field: "registration_pwd2",
				equal_to: { value: 'registration_pwd', message: UNICAEVENTS_GLOBALS['strings']['password_not_equal'] }
			}
		]
	});
	if (!error) {
		jQuery.post(UNICAEVENTS_GLOBALS['ajax_url'], {
			action: 'registration_user',
			nonce: UNICAEVENTS_GLOBALS['ajax_nonce'],
			user_name: 	form.find('#registration_username').val(),
			user_email: form.find('#registration_email').val(),
			user_pwd: 	form.find('#registration_pwd').val()
		}).done(function(response) {
			var rez = {};
			try {
				rez = JSON.parse(response);
			} catch (e) {
				rez = { error: UNICAEVENTS_GLOBALS['ajax_error'] };
				console.log(response);
			}
			var result_box = form.find('.result');
			if (result_box.length==0) result_box = form.siblings('.result');
			if (result_box.length==0) result_box = form.after('<div class="result"></div>').next('.result');
			result_box.toggleClass('sc_infobox_style_error', false).toggleClass('sc_infobox_style_success', false);
			if (rez.error === '') {
				result_box.addClass('sc_infobox sc_infobox_style_success').html(UNICAEVENTS_GLOBALS['strings']['registration_success']);
				setTimeout(function() { 
					jQuery('.popup_login_link').trigger('click'); 
					}, 3000);
			} else {
				result_box.addClass('sc_infobox sc_infobox_style_error').html(UNICAEVENTS_GLOBALS['strings']['registration_failed'] + ' ' + rez.error);
			}
			result_box.fadeIn().delay(3000).fadeOut();
		});
	}
	return false;
}




// Contact form handlers
function unicaevents_sc_form_validate(form){
	"use strict";
	var url = form.attr('action');
	if (url == '') return false;
	form.find('input').removeClass('error_fields_class');
	var error = false;
	var form_custom = form.data('formtype')=='form_custom';
	if (!form_custom) {
		error = unicaevents_form_validate(form, {
			error_message_show: true,
			error_message_time: 4000,
			error_message_class: "sc_infobox sc_infobox_style_error",
			error_fields_class: "error_fields_class",
			exit_after_first_error: false,
			rules: [
				{
					field: "username",
					min_length: { value: 1,	 message: UNICAEVENTS_GLOBALS['strings']['name_empty'] },
					max_length: { value: 60, message: UNICAEVENTS_GLOBALS['strings']['name_long'] }
				},
				{
					field: "email",
					min_length: { value: 7,	 message: UNICAEVENTS_GLOBALS['strings']['email_empty'] },
					max_length: { value: 60, message: UNICAEVENTS_GLOBALS['strings']['email_long'] },
					mask: { value: UNICAEVENTS_GLOBALS['email_mask'], message: UNICAEVENTS_GLOBALS['strings']['email_not_valid'] }
				},
				{
					field: "subject",
					min_length: { value: 1,	 message: UNICAEVENTS_GLOBALS['strings']['subject_empty'] },
					max_length: { value: 100, message: UNICAEVENTS_GLOBALS['strings']['subject_long'] }
				},
				{
					field: "message",
					min_length: { value: 1,  message: UNICAEVENTS_GLOBALS['strings']['text_empty'] },
					max_length: { value: UNICAEVENTS_GLOBALS['contacts_maxlength'], message: UNICAEVENTS_GLOBALS['strings']['text_long'] }
				}
			]
		});
	}
	if (!error && url!='#') {
		jQuery.post(url, {
			action: "send_form",
			nonce: UNICAEVENTS_GLOBALS['ajax_nonce'],
			type: form.data('formtype'),
			data: form.serialize()
		}).done(function(response) {
			"use strict";
			var rez = {};
			try {
				rez = JSON.parse(response);
			} catch (e) {
				rez = { error: UNICAEVENTS_GLOBALS['ajax_error'] };
				console.log(response);
			}
			var result = form.find(".result").toggleClass("sc_infobox_style_error", false).toggleClass("sc_infobox_style_success", false);
			if (rez.error === '') {
				form.get(0).reset();
				result.addClass("sc_infobox_style_success").html(UNICAEVENTS_GLOBALS['strings']['send_complete']);
			} else {
				result.addClass("sc_infobox_style_error").html(UNICAEVENTS_GLOBALS['strings']['send_error'] + ' ' + rez.error);
			}
			result.fadeIn().delay(3000).fadeOut();
		});
	}
	return !error;
}

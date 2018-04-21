/* global jQuery:false */
/* global UNICAEVENTS_GLOBALS:false */


// Theme-specific first load actions
//==============================================
function unicaevents_theme_ready_actions() {
	"use strict";
	// Put here your init code for the theme-specific actions
	// It will be called before core actions
}


// Theme-specific scroll actions
//==============================================
function unicaevents_theme_scroll_actions() {
	"use strict";
	// Put here your theme-specific code for scroll actions
	// It will be called when page is scrolled (before core actions)
}


// Theme-specific resize actions
//==============================================
function unicaevents_theme_resize_actions() {
	"use strict";
	// Put here your theme-specific code for resize actions
	// It will be called when window is resized (before core actions)
}


// Theme-specific shortcodes init
//=====================================================
function unicaevents_theme_sc_init(cont) {
	"use strict";
	// Put here your theme-specific code for init shortcodes
	// It will be called before core init shortcodes
	// @param cont - jQuery-container with shortcodes (init only inside this container)
}


// Theme-specific post-formats init
//=====================================================
function unicaevents_theme_init_post_formats() {
	"use strict";
	// Put here your theme-specific code for init post-formats
	// It will be called before core init post_formats when page is loaded or after 'Load more' or 'Infonite scroll' actions
}

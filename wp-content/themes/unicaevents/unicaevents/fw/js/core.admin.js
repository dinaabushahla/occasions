/**
 * UnicaEvents Framework: Admin scripts
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */


// Fill categories after change post type in widgets
function unicaevents_admin_change_post_type(fld) {
	"use strict";
	var cat_fld = jQuery(fld).parent().next().find('select');
	var cat_lbl = jQuery(fld).parent().next().find('label');
	unicaevents_admin_fill_categories(fld, cat_fld, cat_lbl);
	return false;
}


// Fill categories in specified field
function unicaevents_admin_fill_categories(fld, cat_fld, cat_lbl) {
	"use strict";
	var cat_value = unicaevents_get_listbox_selected_value(cat_fld.get(0));
	cat_lbl.append('<span class="sc_refresh iconadmin-spin3 animate-spin"></span>');
	var pt = jQuery(fld).val();
	// Prepare data
	var data = {
		action: 'unicaevents_admin_change_post_type',
		nonce: UNICAEVENTS_GLOBALS['ajax_nonce'],
		post_type: pt
	};
	jQuery.post(UNICAEVENTS_GLOBALS['ajax_url'], data, function(response) {
		"use strict";
		var rez = {};
		try {
			rez = JSON.parse(response);
		} catch (e) {
			rez = { error: UNICAEVENTS_GLOBALS['ajax_error'] };
			console.log(response);
		}
		if (rez.error === '') {
			var opt_list = '';
			for (var i in rez.data.ids) {
				opt_list += '<option class="'+rez.data.ids[i]+'" value="'+rez.data.ids[i]+'"'+(rez.data.ids[i]==cat_value ? ' selected="selected"' : '')+'>'+rez.data.titles[i]+'</option>';
			}
			cat_fld.html(opt_list);
			cat_lbl.find('span').remove();
		}
	});
	return false;
}

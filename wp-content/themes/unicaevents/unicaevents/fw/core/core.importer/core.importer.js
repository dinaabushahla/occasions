// UnicaEvents Importer script

jQuery(document).ready(function(){
	"use strict";
	
	// Show/hide fetch attachment
	jQuery('#trx_importer_form').on('change', '#import_posts', function() {
		if (jQuery(this).get(0).checked)
			jQuery(this).siblings('.import_posts_params').show();
		else
			jQuery(this).siblings('.import_posts_params').hide();
	});
	
	// Start import
	jQuery('.trx_importer_section').on('click', '.trx_buttons input[type="button"]', function() {
		"use strict";
		var last_id = jQuery(this).data('last_id');
		if (!last_id) last_id = 0;
		var last_slider = jQuery(this).data('last_slider');
		if (!last_slider) last_slider = 0;
		var steps = [];
		var clear_tables = '';
		jQuery(this).parents('form').find('input[type="checkbox"]').each(function() {
			"use strict";
			var name = jQuery(this).attr('name');
			if (jQuery(this).get(0).checked) {
				clear_tables += (clear_tables ? ',' : '') + name.substr(7); // Remove 'import_' from name - save only slug into var clear_tables
				var step = {
					action: name,
					data: {}
				};
				if (name=='import_posts') {
					step.data['data_type'] = jQuery('#data_type_vc').length>0 && jQuery('#data_type_vc').get(0).checked ? jQuery('#data_type_vc').val() : jQuery('#data_type_no_vc').val();
					step.data['fetch_attachments'] = jQuery('#fetch_attachments_1').length>0 && jQuery('#fetch_attachments_1').get(0).checked ? 1 : 0;
					step.data['last_id'] = last_id;
				}
				steps.push(step);
			} else
				jQuery('#trx_importer_progress .'+name).hide();
		});
		steps.unshift({
			action: 'import_start',
			data: { 
				clear_tables: clear_tables
			}
		});
		steps.push({
			action: 'import_end',
		});
		// Start import
		jQuery('#trx_importer_form').hide();
		jQuery('#trx_importer_progress').fadeIn();
		unicaevents_importer_do_action(steps, 0);
	});
});

// Call specified action (step)
function unicaevents_importer_do_action(steps, idx) {
	"use strict";
	if ( !jQuery('#trx_importer_progress .'+steps[idx].action+' .import_progress_status').hasClass('step_in_progress') )
		jQuery('#trx_importer_progress .'+steps[idx].action+' .import_progress_status').addClass('step_in_progress').html('0%');
	// AJAX query params
	var data = {
		ajax_nonce: UNICAEVENTS_GLOBALS['ajax_nonce'],
		action: 'unicaevents_importer_start_import',
		importer_action: steps[idx].action
	};
	// Additional params depend current step
	for (var i in steps[idx].data)
		data[i] = steps[idx].data[i];
	// Send request to server
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
			var action = rez.action;
			if (rez.result >= 100) {
				jQuery('#trx_importer_progress .'+action+' .import_progress_status').html('');
				jQuery('#trx_importer_progress .'+action+' .import_progress_status').removeClass('step_in_progress').addClass('step_complete');
				idx++;
			} else {
				jQuery('#trx_importer_progress .'+action+' .import_progress_status').html(rez.result + '%');
				if (typeof steps[idx].data['last_id'] != 'undefined') steps[idx].data['last_id']++;
			}
			// Do next action
			if (idx < steps.length) {
				unicaevents_importer_do_action(steps, idx);
			} else {
				jQuery('#trx_importer_progress').removeClass('notice-info').addClass('notice-success');
				jQuery('.trx_importer_progress_complete').show();
			}
		}
	});
}
jQuery(document).ready(function () {
		//var controls = jQuery(parent.document.getElementById('vc_controls-template-default'));
		var controls = jQuery('#vc_controls-template-default', window.parent.document);
		if (controls.length > 0) {
			// Add controls for collection items
			controls.before('\
				<script type="text/html" id="vc_controls-template-collection">\
					<div class="vc_controls-container">\
						<div class="vc_controls-out-tr">\
							<div class="vc_element element-{{ tag }}"><a class="vc_control-btn vc_element-name vc_element-move"\
																		 title="Drag to move {{ name }}"><span\
										class="vc_btn-content">{{ name }}</span></a><a class="vc_control-btn vc_control-btn-edit"\
																					   href="#"\
																					   title="Edit {{ name }}"><span\
										class="vc_btn-content"><span class="icon"></span></span></a><a\
									class="vc_control-btn vc_control-btn-prepend" href="#"\
									title="Prepend to {{ name }}"><span\
										class="vc_btn-content"><span class="icon"></span></span></a><a\
									class="vc_control-btn vc_control-btn-clone" href="#"\
									title="Clone {{ name }}"><span\
										class="vc_btn-content"><span class="icon"></span></span></a><a\
									class="vc_control-btn vc_control-btn-delete" href="#"\
									title="Delete {{ name }}"><span\
										class="vc_btn-content"><span class="icon"></span></span></a></div>\
						</div>\
						<div class="vc_controls-bc"><a class="vc_control-btn vc_control-btn-append" href="#"\
													   title="Append to {{ name }}"><span\
									class="vc_btn-content"><span class="icon"></span></span></a></div>\
					</div><!-- end vc_controls-container -->\
				</script>\
				<script type="text/html" id="vc_controls-template-collection-item">\
					<div class="vc_controls-column">\
						<div class="vc_controls-out-tr">\
							<div class="vc_parent parent-{{ parent_tag }}"><a\
									class="vc_control-btn vc_element-name vc_move-{{ parent_tag }} vc_element-move"\
									title="Drag to move {{ parent_name }}"><span\
										class="vc_btn-content">{{ parent_name }}</span></a><span class="vc_advanced"><a\
										class="vc_control-btn vc_control-btn-edit vc_edit" href="#"\
										title="Edit {{ parent_name }}"><span\
											class="vc_btn-content"><span class="icon"></span></span></a><a\
										class="vc_control-btn vc_control-btn-prepend vc_edit" href="#"\
										title="Prepend to {{ parent_name }}"><span\
											class="vc_btn-content"><span class="icon"></span></span></a><a\
										class="vc_control-btn vc_control-btn-clone" href="#"\
										title="Clone {{ parent_name }}"><span\
											class="vc_btn-content"><span class="icon"></span></span></a><a\
										class="vc_control-btn vc_control-btn-delete" href="#"\
										title="Delete {{ parent_name }}"><span\
											class="vc_btn-content"><span class="icon"></span></span></a></span><a\
									class="vc_control-btn vc_control-btn-switcher"\
									title="Show {{ parent_name }} controls"><span\
										class="vc_btn-content"><span class="icon"></span></span></a></div>\
							<div class="vc_element element-{{ tag }} vc_active"><a\
									class="vc_control-btn vc_element-name vc_move-{{ tag }} vc_element-move"\
									title="Drag to move {{ name }}"><span\
										class="vc_btn-content">{{ name }}</span></a><span class="vc_advanced"><a\
										class="vc_control-btn vc_control-btn-edit" href="#"\
										title="Edit {{ name }}"><span\
											class="vc_btn-content"><span class="icon"></span></span></a><a\
										class="vc_control-btn vc_control-btn-prepend" href="#"\
										title="Prepend to {{ name }}"><span\
											class="vc_btn-content"><span class="icon"></span></span></a><a\
										class="vc_control-btn vc_control-btn-clone" href="#"\
										title="Clone {{ name }}"><span\
											class="vc_btn-content"><span class="icon"></span></span></a><a\
										class="vc_control-btn vc_control-btn-delete" href="#"\
										title="Delete {{ name }}"><span\
											class="vc_btn-content"><span class="icon"></span></span></a></span><a\
									class="vc_control-btn vc_control-btn-switcher"\
									title="Show {{ name }} controls"><span\
										class="vc_btn-content"><span class="icon"></span></span></a></div>\
						</div>\
						<div class="vc_controls-bc"><a class="vc_control-btn vc_control-btn-append" href="#"\
													   title="Append to {{ name }}"><span\
									class="vc_btn-content"><span class="icon"></span></span></a></div>\
					</div><!-- end vc_controls-column -->\
				</script>\
				');
		}
});

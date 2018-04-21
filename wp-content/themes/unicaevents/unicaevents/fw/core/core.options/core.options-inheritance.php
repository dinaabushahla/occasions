<?php
//####################################################
//#### Inheritance system (for internal use only) #### 
//####################################################

// Add item to the inheritance settings
if ( !function_exists( 'unicaevents_add_theme_inheritance' ) ) {
	function unicaevents_add_theme_inheritance($options, $append=true) {
		global $UNICAEVENTS_GLOBALS;
		if (!isset($UNICAEVENTS_GLOBALS["inheritance"])) $UNICAEVENTS_GLOBALS["inheritance"] = array();
		$UNICAEVENTS_GLOBALS['inheritance'] = $append 
			? unicaevents_array_merge($UNICAEVENTS_GLOBALS['inheritance'], $options) 
			: unicaevents_array_merge($options, $UNICAEVENTS_GLOBALS['inheritance']);
	}
}



// Return inheritance settings
if ( !function_exists( 'unicaevents_get_theme_inheritance' ) ) {
	function unicaevents_get_theme_inheritance($key = '') {
		global $UNICAEVENTS_GLOBALS;
		return $key ? $UNICAEVENTS_GLOBALS['inheritance'][$key] : $UNICAEVENTS_GLOBALS['inheritance'];
	}
}



// Detect inheritance key for the current mode
if ( !function_exists( 'unicaevents_detect_inheritance_key' ) ) {
	function unicaevents_detect_inheritance_key() {
		static $inheritance_key = '';
		if (!empty($inheritance_key)) return $inheritance_key;
		$key = apply_filters('unicaevents_filter_detect_inheritance_key', '');
		global $UNICAEVENTS_GLOBALS;
		if (empty($UNICAEVENTS_GLOBALS['pre_query'])) $inheritance_key = $key;
		return $key;
	}
}


// Return key for override parameter
if ( !function_exists( 'unicaevents_get_override_key' ) ) {
	function unicaevents_get_override_key($value, $by) {
		$key = '';
		$inheritance = unicaevents_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach ($inheritance as $k=>$v) {
				if (!empty($v[$by]) && in_array($value, $v[$by])) {
					$key = $by=='taxonomy' 
						? $value
						: (!empty($v['override']) ? $v['override'] : $k);
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for categories) by post_type from inheritance array
if ( !function_exists( 'unicaevents_get_taxonomy_categories_by_post_type' ) ) {
	function unicaevents_get_taxonomy_categories_by_post_type($value) {
		$key = '';
		$inheritance = unicaevents_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach ($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy']) ? $v['taxonomy'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for tags) by post_type from inheritance array
if ( !function_exists( 'unicaevents_get_taxonomy_tags_by_post_type' ) ) {
	function unicaevents_get_taxonomy_tags_by_post_type($value) {
		$key = '';
		$inheritance = unicaevents_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy_tags']) ? $v['taxonomy_tags'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}
?>
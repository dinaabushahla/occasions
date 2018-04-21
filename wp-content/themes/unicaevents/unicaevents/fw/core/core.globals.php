<?php
/**
 * UnicaEvents Framework: global variables storage
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get global variable
if (!function_exists('unicaevents_get_global')) {
	function unicaevents_get_global($var_name) {
		global $UNICAEVENTS_GLOBALS;
		return isset($UNICAEVENTS_GLOBALS[$var_name]) ? $UNICAEVENTS_GLOBALS[$var_name] : '';
	}
}

// Set global variable
if (!function_exists('unicaevents_set_global')) {
	function unicaevents_set_global($var_name, $value) {
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS[$var_name] = $value;
	}
}

// Inc/Dec global variable with specified value
if (!function_exists('unicaevents_inc_global')) {
	function unicaevents_inc_global($var_name, $value=1) {
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS[$var_name] += $value;
	}
}

// Concatenate global variable with specified value
if (!function_exists('unicaevents_concat_global')) {
	function unicaevents_concat_global($var_name, $value) {
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS[$var_name] .= $value;
	}
}

// Get global array element
if (!function_exists('unicaevents_get_global_array')) {
	function unicaevents_get_global_array($var_name, $key) {
		global $UNICAEVENTS_GLOBALS;
		return isset($UNICAEVENTS_GLOBALS[$var_name][$key]) ? $UNICAEVENTS_GLOBALS[$var_name][$key] : '';
	}
}

// Set global array element
if (!function_exists('unicaevents_set_global_array')) {
	function unicaevents_set_global_array($var_name, $key, $value) {
		global $UNICAEVENTS_GLOBALS;
		if (!isset($UNICAEVENTS_GLOBALS[$var_name])) $UNICAEVENTS_GLOBALS[$var_name] = array();
		$UNICAEVENTS_GLOBALS[$var_name][$key] = $value;
	}
}

// Inc/Dec global array element with specified value
if (!function_exists('unicaevents_inc_global_array')) {
	function unicaevents_inc_global_array($var_name, $key, $value=1) {
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS[$var_name][$key] += $value;
	}
}

// Concatenate global array element with specified value
if (!function_exists('unicaevents_concat_global_array')) {
	function unicaevents_concat_global_array($var_name, $key, $value) {
		global $UNICAEVENTS_GLOBALS;
		$UNICAEVENTS_GLOBALS[$var_name][$key] .= $value;
	}
}
?>
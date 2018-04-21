<?php
/**
 * UnicaEvents Framework: arrays manipulations
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

//  Return list <option value='id'>name</option> as string from two-dim array
if (!function_exists('unicaevents_array_get_list_options')) {
	function unicaevents_array_get_list_options($arr, $cur) {
		$rezList = "";
		if (is_array($arr) && count($arr) > 0) {
			foreach ($arr as $k=>$v) {
				$rezList .= "\n".'<option value="'.esc_attr($k).'"'.($cur==$k ? ' selected="selected">' : '>').esc_html($v).'</option>';
			}
		}
		return $rezList;
	}
}

//  Return 'id' by key from two-dim array
if (!function_exists('unicaevents_array_get_id_by_key')) {
	function unicaevents_array_get_id_by_key($curKey, $arr) {
		return (isset($arr[$curKey]) ? $arr[$curKey]['id'] : 0);
	}
}

//  Return key 'name' by key 'id'
if (!function_exists('unicaevents_array_get_name_by_id')) {
	function unicaevents_array_get_name_by_id($curId, $arr) {
		$rez = '';
		if (is_array($arr) && count($arr) > 0) {
			foreach ($arr as $k=>$v) {
				if ($arr[$k]['id']==$curId) {
					$rez = $arr[$k]['name'];
					break;
				}
			}
		}
		return $rez;
	}
}

// Merge arrays and lists (preserve number indexes)
// $a = array("one", "k2"=>"two", "three");
// $b = array("four", "k1"=>"five", "k2"=>"six", "seven");
// $c = array_merge($a, $b);			["one", "k2"=>"six", "three", "four", "k1"=>"five", "seven");
// $d = unicaevents_array_merge($a, $b);	["four", "k2"=>"six", "seven", "k1"=>"five");
if (!function_exists('unicaevents_array_merge')) {
	function unicaevents_array_merge($a1, $a2) {
		for ($i = 1; $i < func_num_args(); $i++){
			$arg = func_get_arg($i);
			if (is_array($arg) && count($arg)>0) {
				foreach($arg as $k=>$v) {
					$a1[$k] = $v;
				}
			}
		}
		return $a1;
	}
}

// Inserts any number of scalars or arrays at the point
// in the haystack immediately after the search key ($needle) was found,
// or at the end if the needle is not found or not supplied.
// Modifies $haystack in place.
// @param array &$haystack the associative array to search. This will be modified by the function
// @param string $needle the key to search for
// @param mixed $stuff one or more arrays or scalars to be inserted into $haystack
// @return int the index at which $needle was found
if (!function_exists('unicaevents_array_insert')) {
	function unicaevents_array_insert_after(&$haystack, $needle, $stuff){
		if (! is_array($haystack) ) return -1;

		$new_array = array();
		for ($i = 2; $i < func_num_args(); ++$i){
			$arg = func_get_arg($i);
			if (is_array($arg)) {
				if ($i==2)
					$new_array = $arg;
				else
					$new_array = unicaevents_array_merge($new_array, $arg);
			} else 
				$new_array[] = $arg;
		}

		$i = 0;
		if (is_array($haystack) && count($haystack) > 0) {
			foreach($haystack as $key => $value){
				$i++;
				if ($key == $needle) break;
			}
		}

		$haystack = unicaevents_array_merge(array_slice($haystack, 0, $i, true), $new_array, array_slice($haystack, $i, null, true));

		return $i;
    }
}

// Inserts any number of scalars or arrays at the point
// in the haystack immediately before the search key ($needle) was found,
// or at the end if the needle is not found or not supplied.
// Modifies $haystack in place.
// @param array &$haystack the associative array to search. This will be modified by the function
// @param string $needle the key to search for
// @param mixed $stuff one or more arrays or scalars to be inserted into $haystack
// @return int the index at which $needle was found
if (!function_exists('unicaevents_array_before')) {
	function unicaevents_array_insert_before(&$haystack, $needle, $stuff){
		if (! is_array($haystack) ) return -1;

		$new_array = array();
		for ($i = 2; $i < func_num_args(); ++$i){
			$arg = func_get_arg($i);
			if (is_array($arg)) {
				if ($i==2)
					$new_array = $arg;
				else
					$new_array = unicaevents_array_merge($new_array, $arg);
			} else 
				$new_array[] = $arg;
		}

		$i = 0;
		if (is_array($haystack) && count($haystack) > 0) {
			foreach($haystack as $key => $value){
				if ($key == $needle) break;
				$i++;
			}
		}

		$haystack = unicaevents_array_merge(array_slice($haystack, 0, $i, true), $new_array, array_slice($haystack, $i, null, true));

		return $i;
    }
}

// Prepare all strings in the array (both - keys and values) to convert in JSON string
if (!function_exists('unicaevents_array_prepare_to_json')) {
	function unicaevents_array_prepare_to_json($arr) {
/*
		if (is_array($arr)) {
			$new = array();
			if (is_array($arr) && count($arr) > 0) {
				foreach ($arr as $k=>$v) {
					$k = unicaevents_array_prepare_to_json($k);
					$v = unicaevents_array_prepare_to_json($v);
					$new[$k] = $v;
				}
			}
		} else if (is_string($arr)) {
			$new = str_replace(array('"'), array('\\"'), $arr);
		} else
			$new = $arr;
		return $new;
*/
	return $arr;
	}
}
?>
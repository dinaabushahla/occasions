<?php
/**
 * UnicaEvents Framework: strings manipulations
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'UNICAEVENTS_MULTIBYTE' ) ) define( 'UNICAEVENTS_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('unicaevents_strlen')) {
	function unicaevents_strlen($text) {
		return UNICAEVENTS_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('unicaevents_strpos')) {
	function unicaevents_strpos($text, $char, $from=0) {
		return UNICAEVENTS_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('unicaevents_strrpos')) {
	function unicaevents_strrpos($text, $char, $from=0) {
		return UNICAEVENTS_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('unicaevents_substr')) {
	function unicaevents_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = unicaevents_strlen($text)-$from;
		}
		return UNICAEVENTS_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('unicaevents_strtolower')) {
	function unicaevents_strtolower($text) {
		return UNICAEVENTS_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('unicaevents_strtoupper')) {
	function unicaevents_strtoupper($text) {
		return UNICAEVENTS_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('unicaevents_strtoproper')) {
	function unicaevents_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<unicaevents_strlen($text); $i++) {
			$ch = unicaevents_substr($text, $i, 1);
			$rez .= unicaevents_strpos(' .,:;?!()[]{}+=', $last)!==false ? unicaevents_strtoupper($ch) : unicaevents_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('unicaevents_strrepeat')) {
	function unicaevents_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('unicaevents_strshort')) {
	function unicaevents_strshort($str, $maxlength, $add='...') {
	//	if ($add && unicaevents_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return $str;
		if ($maxlength < 1 || $maxlength >= unicaevents_strlen($str)) 
			return strip_tags($str);
		$str = unicaevents_substr(strip_tags($str), 0, $maxlength - unicaevents_strlen($add));
		$ch = unicaevents_substr($str, $maxlength - unicaevents_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = unicaevents_strlen($str) - 1; $i > 0; $i--)
				if (unicaevents_substr($str, $i, 1) == ' ') break;
			$str = trim(unicaevents_substr($str, 0, $i));
		}
		if (!empty($str) && unicaevents_strpos(',.:;-', unicaevents_substr($str, -1))!==false) $str = unicaevents_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('unicaevents_strclear')) {
	function unicaevents_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (unicaevents_substr($text, 0, unicaevents_strlen($open))==$open) {
					$pos = unicaevents_strpos($text, '>');
					if ($pos!==false) $text = unicaevents_substr($text, $pos+1);
				}
				if (unicaevents_substr($text, -unicaevents_strlen($close))==$close) $text = unicaevents_substr($text, 0, unicaevents_strlen($text) - unicaevents_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('unicaevents_get_slug')) {
	function unicaevents_get_slug($title) {
		return unicaevents_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('unicaevents_strmacros')) {
	function unicaevents_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('unicaevents_unserialize')) {
	function unicaevents_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			//if ($data===false) $data = @unserialize(str_replace(array("\n", "\r"), array('\\n','\\r'), $str));
			return $data;
		} else
			return $str;
	}
}
?>
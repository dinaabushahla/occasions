<?php
/**
 * UnicaEvents Framework: http queries and data manipulations
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Get GET, POST value
if (!function_exists('unicaevents_get_value_gp')) {
	function unicaevents_get_value_gp($name, $defa='') {
		global $_GET, $_POST;
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		}
		return $rez;
	}
}


// Get GET, POST, SESSION value and save it (if need)
if (!function_exists('unicaevents_get_value_gps')) {
	function unicaevents_get_value_gps($name, $defa='', $page='') {
		global $_GET, $_POST, $_SESSION;
		$putToSession = $page!='';
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_SESSION[$name.($page!='' ? '_'.($page) : '')])) {
			$rez = stripslashes(trim($_SESSION[$name.($page!='' ? '_'.($page) : '')]));
			$putToSession = false;
		}
		if ($putToSession)
			unicaevents_set_session_value($name, $rez, $page);
		return $rez;
	}
}

// Get GET, POST, COOKIE value and save it (if need)
if (!function_exists('unicaevents_get_value_gpc')) {
	function unicaevents_get_value_gpc($name, $defa='', $page='', $exp=0) {
		global $_GET, $_POST, $_COOKIE;
		$putToCookie = $page!='';
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_COOKIE[$name.($page!='' ? '_'.($page) : '')])) {
			$rez = stripslashes(trim($_COOKIE[$name.($page!='' ? '_'.($page) : '')]));
			$putToCookie = false;
		}
		if ($putToCookie)
			setcookie($name.($page!='' ? '_'.($page) : ''), $rez, $exp, '/');
		return $rez;
	}
}

// Save value into session
if (!function_exists('unicaevents_set_session_value')) {
	function unicaevents_set_session_value($name, $value, $page='') {
		global $_SESSION;
		if (!session_id()) session_start();
		$_SESSION[$name.($page!='' ? '_'.($page) : '')] = $value;
	}
}

// Save value into session
if (!function_exists('unicaevents_del_session_value')) {
	function unicaevents_del_session_value($name, $page='') {
		global $_SESSION;
		if (!session_id()) session_start();
		unset($_SESSION[$name.($page!='' ? '_'.($page) : '')]);
	}
}


/* Other functions
-------------------------------------------------------------------------------- */

// Return current site protocol
if (!function_exists('unicaevents_get_protocol')) {
	function unicaevents_get_protocol() {
		return is_ssl() ? 'https' : 'http';
	}
}
	
//  Cache disable
if (!function_exists('unicaevents_http_cache_disable')) {
	function unicaevents_http_cache_disable() {
		Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		Header("Cache-Control: no-cache, must-revalidate");
		Header("Pragma: no-cache");
		Header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	}
}
?>
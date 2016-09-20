<?php
/**
 * Invetex Framework: theme variables storage
 *
 * @package	invetex
 * @since	invetex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('invetex_storage_get')) {
	function invetex_storage_get($var_name, $default='') {
		global $INVETEX_STORAGE;
		return isset($INVETEX_STORAGE[$var_name]) ? $INVETEX_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('invetex_storage_set')) {
	function invetex_storage_set($var_name, $value) {
		global $INVETEX_STORAGE;
		$INVETEX_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('invetex_storage_empty')) {
	function invetex_storage_empty($var_name, $key='', $key2='') {
		global $INVETEX_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($INVETEX_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($INVETEX_STORAGE[$var_name][$key]);
		else
			return empty($INVETEX_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('invetex_storage_isset')) {
	function invetex_storage_isset($var_name, $key='', $key2='') {
		global $INVETEX_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($INVETEX_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($INVETEX_STORAGE[$var_name][$key]);
		else
			return isset($INVETEX_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('invetex_storage_inc')) {
	function invetex_storage_inc($var_name, $value=1) {
		global $INVETEX_STORAGE;
		if (empty($INVETEX_STORAGE[$var_name])) $INVETEX_STORAGE[$var_name] = 0;
		$INVETEX_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('invetex_storage_concat')) {
	function invetex_storage_concat($var_name, $value) {
		global $INVETEX_STORAGE;
		if (empty($INVETEX_STORAGE[$var_name])) $INVETEX_STORAGE[$var_name] = '';
		$INVETEX_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('invetex_storage_get_array')) {
	function invetex_storage_get_array($var_name, $key, $key2='', $default='') {
		global $INVETEX_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($INVETEX_STORAGE[$var_name][$key]) ? $INVETEX_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($INVETEX_STORAGE[$var_name][$key][$key2]) ? $INVETEX_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('invetex_storage_set_array')) {
	function invetex_storage_set_array($var_name, $key, $value) {
		global $INVETEX_STORAGE;
		if (!isset($INVETEX_STORAGE[$var_name])) $INVETEX_STORAGE[$var_name] = array();
		if ($key==='')
			$INVETEX_STORAGE[$var_name][] = $value;
		else
			$INVETEX_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('invetex_storage_set_array2')) {
	function invetex_storage_set_array2($var_name, $key, $key2, $value) {
		global $INVETEX_STORAGE;
		if (!isset($INVETEX_STORAGE[$var_name])) $INVETEX_STORAGE[$var_name] = array();
		if (!isset($INVETEX_STORAGE[$var_name][$key])) $INVETEX_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$INVETEX_STORAGE[$var_name][$key][] = $value;
		else
			$INVETEX_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('invetex_storage_set_array_after')) {
	function invetex_storage_set_array_after($var_name, $after, $key, $value='') {
		global $INVETEX_STORAGE;
		if (!isset($INVETEX_STORAGE[$var_name])) $INVETEX_STORAGE[$var_name] = array();
		if (is_array($key))
			invetex_array_insert_after($INVETEX_STORAGE[$var_name], $after, $key);
		else
			invetex_array_insert_after($INVETEX_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('invetex_storage_set_array_before')) {
	function invetex_storage_set_array_before($var_name, $before, $key, $value='') {
		global $INVETEX_STORAGE;
		if (!isset($INVETEX_STORAGE[$var_name])) $INVETEX_STORAGE[$var_name] = array();
		if (is_array($key))
			invetex_array_insert_before($INVETEX_STORAGE[$var_name], $before, $key);
		else
			invetex_array_insert_before($INVETEX_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('invetex_storage_push_array')) {
	function invetex_storage_push_array($var_name, $key, $value) {
		global $INVETEX_STORAGE;
		if (!isset($INVETEX_STORAGE[$var_name])) $INVETEX_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($INVETEX_STORAGE[$var_name], $value);
		else {
			if (!isset($INVETEX_STORAGE[$var_name][$key])) $INVETEX_STORAGE[$var_name][$key] = array();
			array_push($INVETEX_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('invetex_storage_pop_array')) {
	function invetex_storage_pop_array($var_name, $key='', $defa='') {
		global $INVETEX_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($INVETEX_STORAGE[$var_name]) && is_array($INVETEX_STORAGE[$var_name]) && count($INVETEX_STORAGE[$var_name]) > 0) 
				$rez = array_pop($INVETEX_STORAGE[$var_name]);
		} else {
			if (isset($INVETEX_STORAGE[$var_name][$key]) && is_array($INVETEX_STORAGE[$var_name][$key]) && count($INVETEX_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($INVETEX_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('invetex_storage_inc_array')) {
	function invetex_storage_inc_array($var_name, $key, $value=1) {
		global $INVETEX_STORAGE;
		if (!isset($INVETEX_STORAGE[$var_name])) $INVETEX_STORAGE[$var_name] = array();
		if (empty($INVETEX_STORAGE[$var_name][$key])) $INVETEX_STORAGE[$var_name][$key] = 0;
		$INVETEX_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('invetex_storage_concat_array')) {
	function invetex_storage_concat_array($var_name, $key, $value) {
		global $INVETEX_STORAGE;
		if (!isset($INVETEX_STORAGE[$var_name])) $INVETEX_STORAGE[$var_name] = array();
		if (empty($INVETEX_STORAGE[$var_name][$key])) $INVETEX_STORAGE[$var_name][$key] = '';
		$INVETEX_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('invetex_storage_call_obj_method')) {
	function invetex_storage_call_obj_method($var_name, $method, $param=null) {
		global $INVETEX_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($INVETEX_STORAGE[$var_name]) ? $INVETEX_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($INVETEX_STORAGE[$var_name]) ? $INVETEX_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('invetex_storage_get_obj_property')) {
	function invetex_storage_get_obj_property($var_name, $prop, $default='') {
		global $INVETEX_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($INVETEX_STORAGE[$var_name]->$prop) ? $INVETEX_STORAGE[$var_name]->$prop : $default;
	}
}
?>
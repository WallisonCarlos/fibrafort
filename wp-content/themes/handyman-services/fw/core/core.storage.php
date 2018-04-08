<?php
/**
 * Handyman Services Framework: theme variables storage
 *
 * @package	handyman_services
 * @since	handyman_services 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('handyman_services_storage_get')) {
	function handyman_services_storage_get($var_name, $default='') {
		global $HANDYMAN_SERVICES_STORAGE;
		return isset($HANDYMAN_SERVICES_STORAGE[$var_name]) ? $HANDYMAN_SERVICES_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('handyman_services_storage_set')) {
	function handyman_services_storage_set($var_name, $value) {
		global $HANDYMAN_SERVICES_STORAGE;
		$HANDYMAN_SERVICES_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('handyman_services_storage_empty')) {
	function handyman_services_storage_empty($var_name, $key='', $key2='') {
		global $HANDYMAN_SERVICES_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($HANDYMAN_SERVICES_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($HANDYMAN_SERVICES_STORAGE[$var_name][$key]);
		else
			return empty($HANDYMAN_SERVICES_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('handyman_services_storage_isset')) {
	function handyman_services_storage_isset($var_name, $key='', $key2='') {
		global $HANDYMAN_SERVICES_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($HANDYMAN_SERVICES_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($HANDYMAN_SERVICES_STORAGE[$var_name][$key]);
		else
			return isset($HANDYMAN_SERVICES_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('handyman_services_storage_inc')) {
	function handyman_services_storage_inc($var_name, $value=1) {
		global $HANDYMAN_SERVICES_STORAGE;
		if (empty($HANDYMAN_SERVICES_STORAGE[$var_name])) $HANDYMAN_SERVICES_STORAGE[$var_name] = 0;
		$HANDYMAN_SERVICES_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('handyman_services_storage_concat')) {
	function handyman_services_storage_concat($var_name, $value) {
		global $HANDYMAN_SERVICES_STORAGE;
		if (empty($HANDYMAN_SERVICES_STORAGE[$var_name])) $HANDYMAN_SERVICES_STORAGE[$var_name] = '';
		$HANDYMAN_SERVICES_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('handyman_services_storage_get_array')) {
	function handyman_services_storage_get_array($var_name, $key, $key2='', $default='') {
		global $HANDYMAN_SERVICES_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($HANDYMAN_SERVICES_STORAGE[$var_name][$key]) ? $HANDYMAN_SERVICES_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($HANDYMAN_SERVICES_STORAGE[$var_name][$key][$key2]) ? $HANDYMAN_SERVICES_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('handyman_services_storage_set_array')) {
	function handyman_services_storage_set_array($var_name, $key, $value) {
		global $HANDYMAN_SERVICES_STORAGE;
		if (!isset($HANDYMAN_SERVICES_STORAGE[$var_name])) $HANDYMAN_SERVICES_STORAGE[$var_name] = array();
		if ($key==='')
			$HANDYMAN_SERVICES_STORAGE[$var_name][] = $value;
		else
			$HANDYMAN_SERVICES_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('handyman_services_storage_set_array2')) {
	function handyman_services_storage_set_array2($var_name, $key, $key2, $value) {
		global $HANDYMAN_SERVICES_STORAGE;
		if (!isset($HANDYMAN_SERVICES_STORAGE[$var_name])) $HANDYMAN_SERVICES_STORAGE[$var_name] = array();
		if (!isset($HANDYMAN_SERVICES_STORAGE[$var_name][$key])) $HANDYMAN_SERVICES_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$HANDYMAN_SERVICES_STORAGE[$var_name][$key][] = $value;
		else
			$HANDYMAN_SERVICES_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('handyman_services_storage_set_array_after')) {
	function handyman_services_storage_set_array_after($var_name, $after, $key, $value='') {
		global $HANDYMAN_SERVICES_STORAGE;
		if (!isset($HANDYMAN_SERVICES_STORAGE[$var_name])) $HANDYMAN_SERVICES_STORAGE[$var_name] = array();
		if (is_array($key))
			handyman_services_array_insert_after($HANDYMAN_SERVICES_STORAGE[$var_name], $after, $key);
		else
			handyman_services_array_insert_after($HANDYMAN_SERVICES_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('handyman_services_storage_set_array_before')) {
	function handyman_services_storage_set_array_before($var_name, $before, $key, $value='') {
		global $HANDYMAN_SERVICES_STORAGE;
		if (!isset($HANDYMAN_SERVICES_STORAGE[$var_name])) $HANDYMAN_SERVICES_STORAGE[$var_name] = array();
		if (is_array($key))
			handyman_services_array_insert_before($HANDYMAN_SERVICES_STORAGE[$var_name], $before, $key);
		else
			handyman_services_array_insert_before($HANDYMAN_SERVICES_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('handyman_services_storage_push_array')) {
	function handyman_services_storage_push_array($var_name, $key, $value) {
		global $HANDYMAN_SERVICES_STORAGE;
		if (!isset($HANDYMAN_SERVICES_STORAGE[$var_name])) $HANDYMAN_SERVICES_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($HANDYMAN_SERVICES_STORAGE[$var_name], $value);
		else {
			if (!isset($HANDYMAN_SERVICES_STORAGE[$var_name][$key])) $HANDYMAN_SERVICES_STORAGE[$var_name][$key] = array();
			array_push($HANDYMAN_SERVICES_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('handyman_services_storage_pop_array')) {
	function handyman_services_storage_pop_array($var_name, $key='', $defa='') {
		global $HANDYMAN_SERVICES_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($HANDYMAN_SERVICES_STORAGE[$var_name]) && is_array($HANDYMAN_SERVICES_STORAGE[$var_name]) && count($HANDYMAN_SERVICES_STORAGE[$var_name]) > 0) 
				$rez = array_pop($HANDYMAN_SERVICES_STORAGE[$var_name]);
		} else {
			if (isset($HANDYMAN_SERVICES_STORAGE[$var_name][$key]) && is_array($HANDYMAN_SERVICES_STORAGE[$var_name][$key]) && count($HANDYMAN_SERVICES_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($HANDYMAN_SERVICES_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('handyman_services_storage_inc_array')) {
	function handyman_services_storage_inc_array($var_name, $key, $value=1) {
		global $HANDYMAN_SERVICES_STORAGE;
		if (!isset($HANDYMAN_SERVICES_STORAGE[$var_name])) $HANDYMAN_SERVICES_STORAGE[$var_name] = array();
		if (empty($HANDYMAN_SERVICES_STORAGE[$var_name][$key])) $HANDYMAN_SERVICES_STORAGE[$var_name][$key] = 0;
		$HANDYMAN_SERVICES_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('handyman_services_storage_concat_array')) {
	function handyman_services_storage_concat_array($var_name, $key, $value) {
		global $HANDYMAN_SERVICES_STORAGE;
		if (!isset($HANDYMAN_SERVICES_STORAGE[$var_name])) $HANDYMAN_SERVICES_STORAGE[$var_name] = array();
		if (empty($HANDYMAN_SERVICES_STORAGE[$var_name][$key])) $HANDYMAN_SERVICES_STORAGE[$var_name][$key] = '';
		$HANDYMAN_SERVICES_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('handyman_services_storage_call_obj_method')) {
	function handyman_services_storage_call_obj_method($var_name, $method, $param=null) {
		global $HANDYMAN_SERVICES_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($HANDYMAN_SERVICES_STORAGE[$var_name]) ? $HANDYMAN_SERVICES_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($HANDYMAN_SERVICES_STORAGE[$var_name]) ? $HANDYMAN_SERVICES_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('handyman_services_storage_get_obj_property')) {
	function handyman_services_storage_get_obj_property($var_name, $prop, $default='') {
		global $HANDYMAN_SERVICES_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($HANDYMAN_SERVICES_STORAGE[$var_name]->$prop) ? $HANDYMAN_SERVICES_STORAGE[$var_name]->$prop : $default;
	}
}
?>
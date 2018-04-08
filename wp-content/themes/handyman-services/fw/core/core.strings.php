<?php
/**
 * Handyman Services Framework: strings manipulations
 *
 * @package	handyman_services
 * @since	handyman_services 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'HANDYMAN_SERVICES_MULTIBYTE' ) ) define( 'HANDYMAN_SERVICES_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('handyman_services_strlen')) {
	function handyman_services_strlen($text) {
		return HANDYMAN_SERVICES_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('handyman_services_strpos')) {
	function handyman_services_strpos($text, $char, $from=0) {
		return HANDYMAN_SERVICES_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('handyman_services_strrpos')) {
	function handyman_services_strrpos($text, $char, $from=0) {
		return HANDYMAN_SERVICES_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('handyman_services_substr')) {
	function handyman_services_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = handyman_services_strlen($text)-$from;
		}
		return HANDYMAN_SERVICES_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('handyman_services_strtolower')) {
	function handyman_services_strtolower($text) {
		return HANDYMAN_SERVICES_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('handyman_services_strtoupper')) {
	function handyman_services_strtoupper($text) {
		return HANDYMAN_SERVICES_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('handyman_services_strtoproper')) {
	function handyman_services_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<handyman_services_strlen($text); $i++) {
			$ch = handyman_services_substr($text, $i, 1);
			$rez .= handyman_services_strpos(' .,:;?!()[]{}+=', $last)!==false ? handyman_services_strtoupper($ch) : handyman_services_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('handyman_services_strrepeat')) {
	function handyman_services_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('handyman_services_strshort')) {
	function handyman_services_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= handyman_services_strlen($str)) 
			return strip_tags($str);
		$str = handyman_services_substr(strip_tags($str), 0, $maxlength - handyman_services_strlen($add));
		$ch = handyman_services_substr($str, $maxlength - handyman_services_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = handyman_services_strlen($str) - 1; $i > 0; $i--)
				if (handyman_services_substr($str, $i, 1) == ' ') break;
			$str = trim(handyman_services_substr($str, 0, $i));
		}
		if (!empty($str) && handyman_services_strpos(',.:;-', handyman_services_substr($str, -1))!==false) $str = handyman_services_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('handyman_services_strclear')) {
	function handyman_services_strclear($text, $tags=array()) {
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
				if (handyman_services_substr($text, 0, handyman_services_strlen($open))==$open) {
					$pos = handyman_services_strpos($text, '>');
					if ($pos!==false) $text = handyman_services_substr($text, $pos+1);
				}
				if (handyman_services_substr($text, -handyman_services_strlen($close))==$close) $text = handyman_services_substr($text, 0, handyman_services_strlen($text) - handyman_services_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('handyman_services_get_slug')) {
	function handyman_services_get_slug($title) {
		return handyman_services_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('handyman_services_strmacros')) {
	function handyman_services_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('handyman_services_unserialize')) {
	function handyman_services_unserialize($str) {
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
			return $data;
		} else
			return $str;
	}
}
?>
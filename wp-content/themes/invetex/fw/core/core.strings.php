<?php
/**
 * Invetex Framework: strings manipulations
 *
 * @package	invetex
 * @since	invetex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'INVETEX_MULTIBYTE' ) ) define( 'INVETEX_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('invetex_strlen')) {
	function invetex_strlen($text) {
		return INVETEX_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('invetex_strpos')) {
	function invetex_strpos($text, $char, $from=0) {
		return INVETEX_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('invetex_strrpos')) {
	function invetex_strrpos($text, $char, $from=0) {
		return INVETEX_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('invetex_substr')) {
	function invetex_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = invetex_strlen($text)-$from;
		}
		return INVETEX_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('invetex_strtolower')) {
	function invetex_strtolower($text) {
		return INVETEX_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('invetex_strtoupper')) {
	function invetex_strtoupper($text) {
		return INVETEX_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('invetex_strtoproper')) {
	function invetex_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<invetex_strlen($text); $i++) {
			$ch = invetex_substr($text, $i, 1);
			$rez .= invetex_strpos(' .,:;?!()[]{}+=', $last)!==false ? invetex_strtoupper($ch) : invetex_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('invetex_strrepeat')) {
	function invetex_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('invetex_strshort')) {
	function invetex_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= invetex_strlen($str)) 
			return strip_tags($str);
		$str = invetex_substr(strip_tags($str), 0, $maxlength - invetex_strlen($add));
		$ch = invetex_substr($str, $maxlength - invetex_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = invetex_strlen($str) - 1; $i > 0; $i--)
				if (invetex_substr($str, $i, 1) == ' ') break;
			$str = trim(invetex_substr($str, 0, $i));
		}
		if (!empty($str) && invetex_strpos(',.:;-', invetex_substr($str, -1))!==false) $str = invetex_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('invetex_strclear')) {
	function invetex_strclear($text, $tags=array()) {
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
				if (invetex_substr($text, 0, invetex_strlen($open))==$open) {
					$pos = invetex_strpos($text, '>');
					if ($pos!==false) $text = invetex_substr($text, $pos+1);
				}
				if (invetex_substr($text, -invetex_strlen($close))==$close) $text = invetex_substr($text, 0, invetex_strlen($text) - invetex_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('invetex_get_slug')) {
	function invetex_get_slug($title) {
		return invetex_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('invetex_strmacros')) {
	function invetex_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('invetex_unserialize')) {
	function invetex_unserialize($str) {
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
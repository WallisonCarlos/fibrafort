<?php
/**
 * Handyman Services Framework: file system manipulations, styles and scripts usage, etc.
 *
 * @package	handyman_services
 * @since	handyman_services 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* File names manipulations
------------------------------------------------------------------------------------- */

// Return path to directory with uploaded images
if (!function_exists('handyman_services_get_uploads_dir_from_url')) {	
	function handyman_services_get_uploads_dir_from_url($url) {
		$upload_info = wp_upload_dir();
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];
		
		$http_prefix = "http://";
		$https_prefix = "https://";
		
		if (!strncmp($url, $https_prefix, handyman_services_strlen($https_prefix)))			//if url begins with https:// make $upload_url begin with https:// as well
			$upload_url = str_replace($http_prefix, $https_prefix, $upload_url);
		else if (!strncmp($url, $http_prefix, handyman_services_strlen($http_prefix)))		//if url begins with http:// make $upload_url begin with http:// as well
			$upload_url = str_replace($https_prefix, $http_prefix, $upload_url);		
	
		// Check if $img_url is local.
		if ( false === handyman_services_strpos( $url, $upload_url ) ) return false;
	
		// Define path of image.
		$rel_path = str_replace( $upload_url, '', $url );
		$img_path = ($upload_dir) . ($rel_path);
		
		return $img_path;
	}
}

// Replace uploads url to current site uploads url
if (!function_exists('handyman_services_replace_uploads_url')) {	
	function handyman_services_replace_uploads_url($str, $uploads_folder='uploads') {
		static $uploads_url = '', $uploads_len = 0;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = handyman_services_replace_uploads_url($v, $uploads_folder);
			}
		} else if (is_string($str)) {
			if (empty($uploads_url)) {
				$uploads_info = wp_upload_dir();
				$uploads_url = $uploads_info['baseurl'];
				$uploads_len = handyman_services_strlen($uploads_url);
			}
			$break = '\'" ';
			$pos = 0;
			while (($pos = handyman_services_strpos($str, "/{$uploads_folder}/", $pos))!==false) {
				$pos0 = $pos;
				$chg = true;
				while ($pos0) {
					if (handyman_services_strpos($break, handyman_services_substr($str, $pos0, 1))!==false) {
						$chg = false;
						break;
					}
					if (handyman_services_substr($str, $pos0, 5)=='http:' || handyman_services_substr($str, $pos0, 6)=='https:')
						break;
					$pos0--;
				}
				if ($chg) {
					$str = ($pos0 > 0 ? handyman_services_substr($str, 0, $pos0) : '') . ($uploads_url) . handyman_services_substr($str, $pos+handyman_services_strlen($uploads_folder)+1);
					$pos = $pos0 + $uploads_len;
				} else 
					$pos++;
			}
		}
		return $str;
	}
}

// Replace site url to current site url
if (!function_exists('handyman_services_replace_site_url')) {	
	function handyman_services_replace_site_url($str, $old_url) {
		static $site_url = '', $site_len = 0;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = handyman_services_replace_site_url($v, $old_url);
			}
		} else if (is_string($str)) {
			if (empty($site_url)) {
				$site_url = get_site_url();
				$site_len = handyman_services_strlen($site_url);
				if (handyman_services_substr($site_url, -1)=='/') {
					$site_len--;
					$site_url = handyman_services_substr($site_url, 0, $site_len);
				}
			}
			if (handyman_services_substr($old_url, -1)=='/') $old_url = handyman_services_substr($old_url, 0, handyman_services_strlen($old_url)-1);
			$break = '\'" ';
			$pos = 0;
			while (($pos = handyman_services_strpos($str, $old_url, $pos))!==false) {
				$str = handyman_services_unserialize($str);
				if (is_array($str) && count($str) > 0) {
					foreach ($str as $k=>$v) {
						$str[$k] = handyman_services_replace_site_url($v, $old_url);
					}
					$str = serialize($str);
					break;
				} else {
					$pos0 = $pos;
					$chg = true;
					while ($pos0 >= 0) {
						if (handyman_services_strpos($break, handyman_services_substr($str, $pos0, 1))!==false) {
							$chg = false;
							break;
						}
						if (handyman_services_substr($str, $pos0, 5)=='http:' || handyman_services_substr($str, $pos0, 6)=='https:')
							break;
						$pos0--;
					}
					if ($chg && $pos0>=0) {
						$str = ($pos0 > 0 ? handyman_services_substr($str, 0, $pos0) : '') . ($site_url) . handyman_services_substr($str, $pos+handyman_services_strlen($old_url));
						$pos = $pos0 + $site_len;
					} else 
						$pos++;
				}
			}
		}
		return $str;
	}
}



/* File system utils
------------------------------------------------------------------------------------- */

// Return images list
if (!function_exists('handyman_services_get_list_images')) {
    	function handyman_services_get_list_images($folder, $ext='', $only_names=false) {
    		return function_exists('trx_utils_get_folder_list') ? trx_utils_get_folder_list($folder, $ext, $only_names) : array();
	}
}

// Put text into specified file
if (!function_exists('handyman_services_fpc')) {	
	function handyman_services_fpc($file, $content, $flag=0) {
		$fn = join('_', array('file', 'put', 'contents'));
		return @$fn($file, $content, $flag);
	}
}

// Get text from specified file
if (!function_exists('handyman_services_fgc')) {	
	function handyman_services_fgc($file) {
		if (file_exists($file)) {
			$fn = join('_', array('file', 'get', 'contents'));
			return @$fn($file);
		} else
			return '';
	}
}

// Get array with rows from specified file
if (!function_exists('handyman_services_fga')) {	
	function handyman_services_fga($file) {
		if (file_exists($file))
			return @file($file);
		else
			return array();
	}
}

// Get text from specified file (local or remote)
if (!function_exists('handyman_services_get_local_or_remote_file')) {	
	function handyman_services_get_local_or_remote_file($file) {
		$rez = '';
		if (substr($file, 0, 5)=='http:' || substr($file, 0, 6)=='https:') {
			$tm = round( 0.9 * max(30, ini_get('max_execution_time')));
			$response = wp_remote_get($file, array(
									'timeout'     => $tm,
									'redirection' => $tm
									)
								);
			if (is_array($response) && isset($response['response']['code']) && $response['response']['code']==200)
				$rez = $response['body'];
		} else {
			if (($file = handyman_services_get_file_dir($file)) != '')
				$rez = handyman_services_fgc($file);
		}
		return $rez;
	}
}

// Remove unsafe characters from file/folder path
if (!function_exists('handyman_services_esc')) {	
	function handyman_services_esc($file) {
		// maybe str_replace(array('~', '>', '<', '|', '"', "'", '`', "\xFF", "\x0A", '#', '&', ';', '*', '?', '^', '(', ')', '[', ']', '{', '}', '$'), '', $file);
		return str_replace(array('\\'), array('/'), $file);
	}
}


/* Enqueue scripts and styles from child or main theme directory and use .min version
------------------------------------------------------------------------------------- */

// Enqueue .min.css (if exists and filetime .min.css > filetime .css) instead .css
if (!function_exists('handyman_services_enqueue_style')) {	
	function handyman_services_enqueue_style($handle, $src=false, $depts=array(), $ver=null, $media='all') {
		$load = true;
		if (!is_array($src) && $src !== false && $src !== '') {
			$debug_mode = handyman_services_get_theme_option('debug_mode');
			$theme_dir = get_template_directory();
			$theme_url = get_template_directory_uri();
			$child_dir = get_stylesheet_directory();
			$child_url = get_stylesheet_directory_uri();
			$dir = $url = '';
			if (handyman_services_strpos($src, $child_url)===0) {
				$dir = $child_dir;
				$url = $child_url;
			} else if (handyman_services_strpos($src, $theme_url)===0) {
				$dir = $theme_dir;
				$url = $theme_url;
			}
			if ($dir != '') {
				if ($debug_mode == 'no') {
					if (handyman_services_substr($src, -4)=='.css') {
						if (handyman_services_substr($src, -8)!='.min.css') {
							$src_min = handyman_services_substr($src, 0, handyman_services_strlen($src)-4).'.min.css';
							$file_src = $dir . handyman_services_substr($src, handyman_services_strlen($url));
							$file_min = $dir . handyman_services_substr($src_min, handyman_services_strlen($url));
							if (file_exists($file_min) && filemtime($file_src) <= filemtime($file_min)) $src = $src_min;
						}
					}
				}
				$file_src = $dir . handyman_services_substr($src, handyman_services_strlen($url));
				$load = file_exists($file_src) && filesize($file_src) > 0;
			}
		}
		if ($load) {
			if (is_array($src))
				wp_enqueue_style( $handle, $depts, $ver, $media );
			else if (!empty($src) || $src===false)
				wp_enqueue_style( $handle, esc_url($src).(handyman_services_param_is_on(handyman_services_get_theme_option('debug_mode')) ? (handyman_services_strpos($src, '?')!==false ? '&' : '?').'rnd='.mt_rand() : ''), $depts, $ver, $media );
		}
	}
}

// Enqueue .min.js (if exists and filetime .min.js > filetime .js) instead .js
if (!function_exists('handyman_services_enqueue_script')) {	
	function handyman_services_enqueue_script($handle, $src=false, $depts=array(), $ver=null, $in_footer=false) {
		$load = true;
		if (!is_array($src) && $src !== false && $src !== '') {
			$debug_mode = handyman_services_get_theme_option('debug_mode');
			$theme_dir = get_template_directory();
			$theme_url = get_template_directory_uri();
			$child_dir = get_stylesheet_directory();
			$child_url = get_stylesheet_directory_uri();
			$dir = $url = '';
			if (handyman_services_strpos($src, $child_url)===0) {
				$dir = $child_dir;
				$url = $child_url;
			} else if (handyman_services_strpos($src, $theme_url)===0) {
				$dir = $theme_dir;
				$url = $theme_url;
			}
			if ($dir != '') {
				if ($debug_mode == 'no') {
					if (handyman_services_substr($src, -3)=='.js') {
						if (handyman_services_substr($src, -7)!='.min.js') {
							$src_min  = handyman_services_substr($src, 0, handyman_services_strlen($src)-3).'.min.js';
							$file_src = $dir . handyman_services_substr($src, handyman_services_strlen($url));
							$file_min = $dir . handyman_services_substr($src_min, handyman_services_strlen($url));
							if (file_exists($file_min) && filemtime($file_src) <= filemtime($file_min)) $src = $src_min;
						}
					}
				}
				$file_src = $dir . handyman_services_substr($src, handyman_services_strlen($url));
				$load = file_exists($file_src) && filesize($file_src) > 0;
			}
		}
		if ($load) {
			if (is_array($src))
				wp_enqueue_script( $handle, $depts, $ver, $in_footer );
			else if (!empty($src) || $src===false)
				wp_enqueue_script( $handle, esc_url($src).(handyman_services_param_is_on(handyman_services_get_theme_option('debug_mode')) ? (handyman_services_strpos($src, '?')!==false ? '&' : '?').'rnd='.mt_rand() : ''), $depts, $ver, $in_footer );
		}
	}
}


/* Check if file/folder present in the child theme and return path (url) to it. 
   Else - path (url) to file in the main theme dir
------------------------------------------------------------------------------------- */

// Detect file location with next algorithm:
// 1) check in the child theme folder
// 2) check in the framework folder in the child theme folder
// 3) check in the main theme folder
// 4) check in the framework folder in the main theme folder
if (!function_exists('handyman_services_get_file_dir')) {	
	function handyman_services_get_file_dir($file, $return_url=false) {
		if ($file[0]=='/') $file = handyman_services_substr($file, 1);
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if (file_exists(($child_dir).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($file);
		else if (file_exists(($child_dir).'/'.(HANDYMAN_SERVICES_FW_DIR).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.(HANDYMAN_SERVICES_FW_DIR).'/'.($file);
		else if (file_exists(($theme_dir).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($file);
		else if (file_exists(($theme_dir).'/'.(HANDYMAN_SERVICES_FW_DIR).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.(HANDYMAN_SERVICES_FW_DIR).'/'.($file);
		return $dir;
	}
}

// Detect file location with next algorithm:
// 1) check in the main theme folder
// 2) check in the framework folder in the main theme folder
// and return file slug (relative path to the file without extension)
// to use it in the get_template_part()
if (!function_exists('handyman_services_get_file_slug')) {	
	function handyman_services_get_file_slug($file) {
		if ($file[0]=='/') $file = handyman_services_substr($file, 1);
		$theme_dir = get_template_directory();
		$dir = '';
		if (file_exists(($theme_dir).'/'.($file)))
			$dir = $file;
		else if (file_exists(($theme_dir).'/'.HANDYMAN_SERVICES_FW_DIR.'/'.($file)))
			$dir = HANDYMAN_SERVICES_FW_DIR.'/'.($file);
		if (handyman_services_substr($dir, -4)=='.php') $dir = handyman_services_substr($dir, 0, handyman_services_strlen($dir)-4);
		return $dir;
	}
}

if (!function_exists('handyman_services_get_file_url')) {	
	function handyman_services_get_file_url($file) {
		return handyman_services_get_file_dir($file, true);
	}
}

// Detect folder location with same algorithm as file (see above)
if (!function_exists('handyman_services_get_folder_dir')) {	
	function handyman_services_get_folder_dir($folder, $return_url=false) {
		if ($folder[0]=='/') $folder = handyman_services_substr($folder, 1);
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if (is_dir(($child_dir).'/'.($folder)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($folder);
		else if (is_dir(($child_dir).'/'.(HANDYMAN_SERVICES_FW_DIR).'/'.($folder)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.(HANDYMAN_SERVICES_FW_DIR).'/'.($folder);
		else if (file_exists(($theme_dir).'/'.($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($folder);
		else if (file_exists(($theme_dir).'/'.(HANDYMAN_SERVICES_FW_DIR).'/'.($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.(HANDYMAN_SERVICES_FW_DIR).'/'.($folder);
		return $dir;
	}
}

if (!function_exists('handyman_services_get_folder_url')) {	
	function handyman_services_get_folder_url($folder) {
		return handyman_services_get_folder_dir($folder, true);
	}
}

// Return path to social icon (if exists)
if (!function_exists('handyman_services_get_socials_dir')) {	
	function handyman_services_get_socials_dir($soc, $return_url=false) {
		return handyman_services_get_file_dir('images/socials/' . handyman_services_esc($soc) . (handyman_services_strpos($soc, '.')===false ? '.png' : ''), $return_url, true);
	}
}

if (!function_exists('handyman_services_get_socials_url')) {	
	function handyman_services_get_socials_url($soc) {
		return handyman_services_get_socials_dir($soc, true);
	}
}

// Detect theme version of the template (if exists), else return it from fw templates directory
if (!function_exists('handyman_services_get_template_dir')) {	
	function handyman_services_get_template_dir($tpl) {
		return handyman_services_get_file_dir('templates/' . handyman_services_esc($tpl) . (handyman_services_strpos($tpl, '.php')===false ? '.php' : ''));
	}
}
?>
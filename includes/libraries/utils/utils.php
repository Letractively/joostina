<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
 * Prepares results from search for display
 * @param string The source string
 * @param int Number of chars to trim
 * @param string The searchword to select around
 * @return string
 */
function mosPrepareSearchContent($text,$length = 200,$searchword='') {
	// strips tags won't remove the actual jscript
	$text = preg_replace("'<script[^>]*>.*?</script>'si","",$text);
	$text = preg_replace('/{.+?}/','',$text);
	//$text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2', $text );
	// replace line breaking tags with whitespace
	$text = preg_replace("'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si",' ',$text);
	$text = mosSmartSubstr(strip_tags($text),$length,$searchword);
	return $text;
}

/**
 * returns substring of characters around a searchword
 * @param string The source string
 * @param int Number of chars to return
 * @param string The searchword to select around
 * @return string
 */
function mosSmartSubstr($text,$length = 200,$searchword='') {
	$wordpos = Jstring::strpos( Jstring::strtolower($text), Jstring::strtolower($searchword));
	$halfside = intval($wordpos - $length / 2 - Jstring::strlen($searchword));
	if($wordpos && $halfside > 0) {
		return '...'.Jstring::substr($text,$halfside,$length).'...';
	} else {
		return Jstring::substr($text,0,$length);
	}
}

/**
 * Sorts an Array of objects
 * sort_direction [1 = Ascending] [-1 = Descending]
 */
function SortArrayObjects(&$a,$k,$sort_direction = 1) {
	global $csort_cmp;
	$csort_cmp = array('key' => $k,'direction' => $sort_direction);
	usort($a,'SortArrayObjects_cmp');
	unset($csort_cmp);
}

/**
 * Sorts an Array of objects
 */
function SortArrayObjects_cmp(&$a,&$b) {
	global $csort_cmp;
	if($a->$csort_cmp['key'] > $b->$csort_cmp['key']) {
		return $csort_cmp['direction'];
	}
	if($a->$csort_cmp['key'] < $b->$csort_cmp['key']) {
		return - 1* $csort_cmp['direction'];
	}
	return 0;
}

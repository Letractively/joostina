<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();
/**
 * utf8::substr
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _substr($str, $offset, $length = NULL){
	// Normalize params
	$str    = (string) $str;
	$strlen = Jstring::strlen($str);
	$offset = (int) ($offset < 0) ? max(0, $strlen + $offset) : $offset; // Normalize to positive offset
	$length = ($length === NULL) ? NULL : (int) $length;

	// Impossible
	if ($length === 0 OR $offset >= $strlen OR ($length < 0 AND $length <= $offset - $strlen)){
		return '';
	}
	// Whole string
	if ($offset == 0 AND ($length === NULL OR $length >= $strlen)){
		return $str;
	}
	// Build regex
	$regex = '^';

	// Create an offset expression
	if ($offset > 0){
		// PCRE repeating quantifiers must be less than 65536, so repeat when necessary
		$x = (int) ($offset / 65535);
		$y = (int) ($offset % 65535);
		$regex .= ($x == 0) ? '' : '(?:.{65535}){'.$x.'}';
		$regex .= ($y == 0) ? '' : '.{'.$y.'}';
	}

	// Create a length expression
	if ($length === NULL){
		$regex .= '(.*)'; // No length set, grab it all
	}elseif ($length > 0){
		// Reduce length so that it can't go beyond the end of the string
		$length = min($strlen - $offset, $length);

		$x = (int) ($length / 65535);
		$y = (int) ($length % 65535);
		$regex .= '(';
		$regex .= ($x == 0) ? '' : '(?:.{65535}){'.$x.'}';
		$regex .= '.{'.$y.'})';
	}else{
		$x = (int) (-$length / 65535);
		$y = (int) (-$length % 65535);
		$regex .= '(.*)';
		$regex .= ($x == 0) ? '' : '(?:.{65535}){'.$x.'}';
		$regex .= '.{'.$y.'}';
	}

	preg_match('/'.$regex.'/us', $str, $matches);
	return $matches[1];
}

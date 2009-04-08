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
 * utf8::strpos
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _strpos($str, $search, $offset = 0){
	$offset = (int) $offset;

	if ($offset == 0){
		$array = explode($search, $str, 2);
		return isset($array[1]) ? Jstring::strlen($array[0]) : FALSE;
	}

	$str = Jstring::substr($str, $offset);
	$pos = Jstring::strpos($str, $search);
	return ($pos === FALSE) ? FALSE : $pos + $offset;
}

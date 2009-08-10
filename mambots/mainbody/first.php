<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

// очистка кода работы компонентов
$_MAMBOTS->registerFunction('onMainbody','body_clear');
// очистка кода всего шаблона
$_MAMBOTS->registerFunction('onTemplate','body_clear');

/* функция производит очистку от спецсимволов*/
function body_clear(&$body) {
	require_once (Jconfig::getInstance()->config_absolute_path.DS.'includes'.DS.'libraries'.DS.'html_optimize'.DS.'html_optimize.php');
	$body = html_optimize($body);
	return true;
}
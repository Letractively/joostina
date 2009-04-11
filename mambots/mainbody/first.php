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

$_MAMBOTS->registerFunction('onMainbody','body_clear');
$_MAMBOTS->registerFunction('onTemplate','template_clear');

global $mosConfig_absolute_path;

require_once ($mosConfig_absolute_path.'/includes/libraries/html_optimize/html_optimize.php');


/* функция производит очистку содержимого главного стека компонента от спецсимволов*/
function body_clear() {
	global $_MOS_OPTION;
	$_MOS_OPTION['buffer'] = html_optimize($_MOS_OPTION['buffer']);
	return;
}

/* очистка всего тела страницы от спецтегов*/
function template_clear() {
	global $_MOS_OPTION;
	$_MOS_OPTION['mainbody'] = html_optimize($_MOS_OPTION['mainbody']);
	return;
}

?>

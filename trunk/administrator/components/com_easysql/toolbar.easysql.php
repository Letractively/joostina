<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или LICENSE.php
* Joostina! - свободное программное обеспечение распостраняемое по условиям лицензиии GNU/GPL
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();


switch($task) {
	case "edit":
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel');
		mosMenuBar::endTable();
		break;
	case "execsql":
	default:
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::apply('execsql','Выполнить SQL');
		mosMenuBar::endTable();
		break;
}

?>

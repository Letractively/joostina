<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, смотрите LICENSE.php
* Joostina! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );


switch ($task) {
	case "new" :
		mosMenuBar::startTable();
		mosMenuBar::save('create');
		mosMenuBar::spacer();
		mosMenuBar::cancel( 'cancel' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
		break;
	case "edit" :
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::cancel( 'cancel' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
		break;
	case "execsql" :
	default:
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::save('tocsv',_ES_TOCSV);
		mosMenuBar::spacer();
		mosMenuBar::addnew();
		mosMenuBar::spacer();
		mosMenuBar::apply('execsql', _ES_EXECSQL);
		mosMenuBar::spacer();
		mosMenuBar::endTable();
		break;
}

?>

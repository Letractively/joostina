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

/**
 * @package Custom QuickIcons
 */
class QI_Toolbar {

	function _edit() {
		mosMenuBar::startTable();
		mosMenuBar::save('save', _QI_SAVE);
		mosMenuBar::spacer();
		mosMenuBar::apply('apply', _QI_APPLY);
		mosMenuBar::spacer();
		mosMenuBar::cancel('', _QI_CANCEL);
		mosMenuBar::endTable();
	}

	function _show() {
		mosMenuBar::startTable();
		mosMenuBar::publishList('publish', _QI_PUBLISH);
		mosMenuBar::spacer();
		mosMenuBar::unpublishList('unpublish', _QI_UNPUBLISH);
		mosMenuBar::spacer();
		mosMenuBar::addNew('new',_QI_NEW);
		mosMenuBar::spacer();
		mosMenuBar::editList('edit', _QI_EDIT);
		mosMenuBar::spacer();
		mosMenuBar::deleteList('', 'delete', _QI_DELETE);
		mosMenuBar::endTable();
	}
	
	function _chooseIcon(){
		mosMenuBar::startTable();
		mosMenuBar::endTable();
	}
}
?>
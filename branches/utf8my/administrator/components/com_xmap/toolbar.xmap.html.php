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

/** Administrator Toolbar output */
class TOOLBAR_xmap {
	/**
	* Draws the toolbar
	*/
	function _DEFAULT() {
		mosMenuBar::startTable();
		/*
			//Testing
			mosMenuBar::custom('backup', 'archive.png', 'archive_f2.png', "Backup Settings", false);
			mosMenuBar::custom('restore', 'restore.png', 'restore_f2.png', "Restore Settings", false);
			mosMenuBar::spacer();
		*/
		if (_XMAP_JOOMLA15) {
			JToolBarHelper::title( 'Xmap', 'addedit.png' );
		}
		mosMenuBar::save('save', _XMAP_TOOLBAR_SAVE);
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel', _XMAP_TOOLBAR_CANCEL);
		mosMenuBar::endTable();
	}
}
?>

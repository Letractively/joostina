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
* @package Joostina
* @subpackage Installer
*/
class TOOLBAR_installer
{
	function _DEFAULT()	{
		mosMenuBar::startTable();
		mosMenuBar::help( 'screen.installer' );
		mosMenuBar::endTable();
	}

	function _DEFAULT2()	{
		mosMenuBar::startTable();
		mosMenuBar::deleteList( '', 'remove', 'Удалить' );
		mosMenuBar::spacer();
		mosMenuBar::help( 'screen.installer2' );
		mosMenuBar::endTable();
	}

	function _NEW()	{
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}
}
?>

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
* @subpackage Sections
*/
class TOOLBAR_sections {
	/**
	* Draws the menu for Editing an existing category
	*/
	function _EDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::media_manager();
		mosMenuBar::spacer();
		mosMenuBar::custom( 'save_and_new', '-save', '', 'Добавить', false );
		mosMenuBar::spacer();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::apply();
		mosMenuBar::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			mosMenuBar::cancel( 'cancel', 'Закрыть' );
		} else {
			mosMenuBar::cancel();
		}
		mosMenuBar::spacer();
		mosMenuBar::help( 'screen.sections.edit' );
		mosMenuBar::endTable();
	}
	/**
	* Draws the menu for Copying existing sections
	* @param int The published state (to display the inverse button)
	*/
	function _COPY() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'copysave' );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}
	/**
	* Draws the menu for Editing an existing category
	*/
	function _DEFAULT(){
		mosMenuBar::startTable();
		mosMenuBar::ext('Создать категорию', 'index2.php?option=com_categories&task=new','-new');
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
		mosMenuBar::spacer();
		mosMenuBar::customX( 'copyselect', '-copy', '', 'Копировать', true );
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::editListX();
		mosMenuBar::spacer();
		mosMenuBar::addNewX();
		mosMenuBar::spacer();
		mosMenuBar::help( 'screen.sections' );
		mosMenuBar::endTable();
	}
}
?>

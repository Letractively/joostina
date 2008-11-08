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

class TOOLBAR_JCE {
	function _CONFIG() {
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::custom('main','-back','','Главная',false);
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}
	function _PLUGINS() {
		mosMenuBar::startTable();
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
		mosMenuBar::spacer();
		mosMenuBar::custom('newplugin','-new','','Новый',false);
		mosMenuBar::spacer();
		mosMenuBar::custom('installplugin','-new','','Установка',false);
		mosMenuBar::spacer();
		mosMenuBar::custom('editlayout','-preview','','Предпросмотр',false);
		mosMenuBar::spacer();
		mosMenuBar::custom('cancel','-cancel','','Отмена',false);
		mosMenuBar::endTable();
	}
	function _EDIT_PLUGINS() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::custom('saveplugin','-save','','Сохранить',false);
		mosMenuBar::spacer();
		if($id) {
			mosMenuBar::custom('canceledit','-cancel','','Закрыть',false);
		} else {
			mosMenuBar::custom('canceledit','-cancel','','Отмена',false);
		}
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	function _INSTALL($element) {
		if($element == 'plugins') {
			mosMenuBar::startTable();
			mosMenuBar::custom('showplugins','-new','','Плагины',false);
			mosMenuBar::spacer();
			mosMenuBar::custom('removeplugin','-delete','','Удаление',false);
			mosMenuBar::spacer();
			mosMenuBar::custom('cancel','-cancel','','Отмена',false);
			mosMenuBar::endTable();
		}
	}
	function _LAYOUT() {
		mosMenuBar::startTable();
		mosMenuBar::custom('savelayout','-save','','Сохранить',false);
		mosMenuBar::spacer();
		mosMenuBar::custom('cancel','-cancel','','Отмена',false);
		mosMenuBar::endTable();
	}
	function _LANGS() {
		mosMenuBar::startTable();
		mosMenuBar::publishList('publishlang');
		mosMenuBar::spacer();
		mosMenuBar::custom('removelang','-delete','','Удалить',false);
		mosMenuBar::spacer();
		mosMenuBar::custom('newlang','-new','','Установить',false);
		mosMenuBar::spacer();
		mosMenuBar::custom('cancel','-cancel','','Отмена',false);
		mosMenuBar::endTable();
	}
}
?>

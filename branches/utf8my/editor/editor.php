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
require(dirname(__FILE__).'/../die.php');

if (!defined( '_JOS_EDITOR_INCLUDED' )) {
	global $mosConfig_editor;
	global $my;

	if ($mosConfig_editor == '') {
		$mosConfig_editor = 'none';
	}

	// проверка сессии на параметр отключения редактора, если такой имеется - то вместо выбранного или прописанного по умолчанию редактора используется параметр 'none' - отсутствующий визуальный редактор
	if (intval( mosGetParam( $_SESSION, 'user_editor_off', '' ) )) {
		$editor = 'none';
	} else {	// получение параметров редактора из настоек пользователя
		$params = new mosParameters( $my->params );
		$editor = $params->get( 'editor', '' );
		if (!$editor) {
			$editor = $mosConfig_editor;
		}
	}
	
	$_MAMBOTS->loadBot( 'editors', $editor, 1 );
	
	/**
	* Инициализация редактора
	* При вызове функции происходит загрузка мамботов группы редакторов и выводятся данные их настройки
	*/
	function initEditor() {
		global $mainframe, $_MAMBOTS;

		if ($mainframe->get( 'loadEditor' )) {

			$results = $_MAMBOTS->trigger( 'onInitEditor' );
			foreach ($results as $result) {
				if (trim($result)) {
					echo $result;
				}
			}
		}
	}
	/**
	* Получение содержимого редактора
	* Проверяется функция соответствующая триггеру onGetEditorContents
	*/
	function getEditorContents( $editorArea, $hiddenField ) {
		global $mainframe, $_MAMBOTS;

		$mainframe->set( 'loadEditor', true );

		$results = $_MAMBOTS->trigger( 'onGetEditorContents', array( $editorArea, $hiddenField ) );
		foreach ($results as $result) {
			if (trim($result)) {
				echo $result;
			}
		}
	}
	// just present a textarea
	function editorArea( $name, $content, $hiddenField, $width, $height, $col, $row ) {
		global $mainframe, $_MAMBOTS, $my;

		// загрузка ботов раздела editor-xtd, константа _JOS_EDITORXTD_INCLUDED сигнализирует что мамботы загружены
		if (!defined( '_JOS_EDITORXTD_INCLUDED' )) {
			define( '_JOS_EDITORXTD_INCLUDED', 1 );
			$_MAMBOTS->loadBotGroup( 'editors-xtd' );
		}

		$mainframe->set( 'loadEditor', true );

		$results = $_MAMBOTS->trigger( 'onEditorArea', array( $name, $content, $hiddenField, $width, $height, $col, $row ) );
		foreach ($results as $result) {
			if (trim($result)) {
				echo $result;
			}
		}
	}
	// установка константы - флага, что редактор подключен
	define( '_JOS_EDITOR_INCLUDED', 1 );
}
?>
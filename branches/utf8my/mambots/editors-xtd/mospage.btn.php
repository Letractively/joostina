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
require(dirname(__FILE__).'/../../die.php');

$_MAMBOTS->registerFunction( 'onCustomEditorButton', 'botMosPageButton' );

/**
* Кнопка страницы Mambo (mospage)
* @return array - возвращает массив из двух элементов: imageName и textToInsert
*/
function botMosPageButton() {
	global $option;

	// button is not active in specific content components
	switch ( $option ) {
		case 'com_sections':
		case 'com_categories':
		case 'com_modules':
			$button = array( '', '' );
			break;

		default:
			$button = array( 'mospage.gif', '{mospagebreak}' );
			break;
	}

	return $button;
}
?>
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

$_MAMBOTS->registerFunction( 'onPrepareContent', 'botLegacyBots' );

/**
* Обработка любых унаследованных ботов в каталоге /mambots
*
* ЭТОТ ФАЙЛ МОЖЕТ БЫТЬ **БЕЗОПАСНО УДАЛЕН ** ЕСЛИ ВЫ НЕТ НАСЛЕДОВАНИЯ МАМБОТОВ
* @param object - объект содержимого
* @param int - Побитовая маска параметров
* @param int - Номер страницы
*/
function botLegacyBots( $published, &$row, &$params, $page=0 ) {
	global $mosConfig_absolute_path;

	// проверка, опубликован ли мамбот
	if ( !$published ) {
		return true;
	}

	// Процесс наследования ботов
	$bots = mosReadDirectory( "$mosConfig_absolute_path/mambots", "\.php$" );
	sort( $bots );
	foreach ($bots as $bot) {
		require $mosConfig_absolute_path ."/mambots/$bot";
	}
}
?>

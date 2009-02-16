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

$_MAMBOTS->registerFunction( 'onMainbody', 'body_clear' );
$_MAMBOTS->registerFunction( 'onTemplate', 'template_clear' );


/* функция производит очистку содержимого главного стека компонента от спецсимволов */
function body_clear( ){
	global $_MOS_OPTION;
	$text = $_MOS_OPTION['buffer'];
	$text = str_replace("\n",'',$text);
	$text = str_replace("\r",'',$text);
	$text = str_replace("\t",'',$text);
	$_MOS_OPTION['buffer'] = $text;
	return;
}

/* очистка всего тела страницы от спецтегов */
function template_clear( ){
	global $_MOS_OPTION;
	$text = $_MOS_OPTION['mainbody'];
	$text = str_replace(array("\r\n", "\r", "\n", "\t", '  '), ' ', $text);// удаление табуляций, переводов строки, двойных пробелов или смещение каретки
	$text = str_replace('>  <', '><', $text);
	$text = str_replace('> <', '><', $text);
	$text = str_replace(' >', '>', $text);
	$text = str_replace('> ', '>', $text);
	$text = str_replace(' <', '<', $text);
	$text = str_replace('< ', '<', $text);
	$text = str_replace('  ', ' ', $text);
	$_MOS_OPTION['mainbody'] = $text;
	return;
}

?>

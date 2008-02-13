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

global $cur_template,$mosConfig_absolute_path;

if( !defined( '_QUICKICON_MODULE' )) {
	define( '_QUICKICON_MODULE', 1 );
	if (file_exists( $mosConfig_absolute_path .'/administrator/templates/'.$cur_template.'/quickicons.php' )) {
		require_once( $mosConfig_absolute_path .'/administrator/templates/'.$cur_template.'/quickicons.php' );
	} else {
		require_once( $mosConfig_absolute_path .'/administrator/components/com_customquickicons/quickicons.php' );
	}
}
?>

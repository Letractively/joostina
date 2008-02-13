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
 * Configuration settings for frontend file browsing
 */

// ALLOW FRONTEND BROWSING ? Change to
//$frontend_enabled = true; // If needed!
$frontend_enabled = false;

// THE SUBDIRECTORY USERS CAN BROWSE INCLUDING ALL SUBDIRECTORIES
// relative to your physical Joomla root path ($mosConfig_absolute_path)!
// Please note: You currently can't exclude directories or files within
// the specified directory. All files and directories will be visible and downloadable
$subdir = '/dmdocuments';

?>

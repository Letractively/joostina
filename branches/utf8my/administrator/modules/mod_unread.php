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

$query = "SELECT COUNT(*)"
. "\n FROM #__messages"
. "\n WHERE state = 0"
. "\n AND user_id_to = " . (int) $my->id
;
$database->setQuery( $query );
$unread = $database->loadResult();

if ($unread) {
	echo "<a href=\"index2.php?option=com_messages\" style=\"color: red; text-decoration: none;  font-weight: bold\">$unread <img src=\"images/mail.png\" align=\"middle\" border=\"0\" alt=\"Почта\" /></a>";
} else {
	echo "<a href=\"index2.php?option=com_messages\" style=\"color: black; text-decoration: none;\">$unread <img src=\"images/nomail.png\" align=\"middle\" border=\"0\" alt=\"Почта\" /></a>";
}
?>

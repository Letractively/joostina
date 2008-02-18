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
* My Check in
*/
class HTML_mycheckin {
	
	function showlist( $option, &$itemlist, $itemcnt ) {
		global $mosConfig_live_site;
		?>
		<table class="adminheading">
		<tr> 
			<th class="checkin">
			Заблокированные объекты
			</th>
		</tr>
		</table>
		<table class="adminlist">
		<tr>
			<th class="title">
			Объект
			</th>
			<th class="title">
			Заголовок
			</th>
			<th>
			Заблокировал
			</th>
			<th>
			Время блокировки
			</th>
			<th>
			Действие
			</th>
		</tr>
			<?php
		for ($i = 0; $i < $itemcnt; $i++)
		{
		     print "<tr><td>\n";
		     print $itemlist[$i]["component"];
		     print "</td>\n";
		     print "<td>\n";
		     print $itemlist[$i]["title"];
		     print "</td>\n";
		     print "<td>\n";
		     print $itemlist[$i]["name"];
		     print "</td>\n";
		     print "<td>\n";
		     print $itemlist[$i]["cotime"];
		     print "</td>\n";
		     print "<td>\n";
		     print "<a href=\"$mosConfig_live_site/administrator/index2.php?option=$option&task=checkin&component=".$itemlist[$i]["component"]."&pkey=".$itemlist[$i]["PKEY"]."&checkid=".$itemlist[$i]["id"]."&editor=".$itemlist[$i]["editor"]."\">Разблокировать</a>\n";
		     print "</td></tr>";
		}
			?>
		</table>
		<?php
	}
}
?>

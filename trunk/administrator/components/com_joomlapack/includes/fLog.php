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

global $JPLang, $JPConfiguration;
?>
<table class="adminheading">
	<tr>
		<th class="cpanel" nowrap rowspan="2">
			<?php echo $JPLang['common']['jptitle']; ?>
		</th>
	</tr>
	<tr>
		<td nowrap><h2><?php echo $JPLang['log']['logbanner']; ?></h2></td>
	</tr>
</table>
<div style="text-align: left; padding: 0.5em; background-color: #EEEEFE; border: thin solid black; margin: 0.5em;"
<?php
CJPLogger::VisualizeLogDirect();
?>
</div>
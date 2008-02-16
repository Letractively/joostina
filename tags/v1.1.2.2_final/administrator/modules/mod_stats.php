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

$query = "SELECT menutype, COUNT(id) AS numitems"
. "\n FROM #__menu"
. "\n WHERE published = 1"
. "\n GROUP BY menutype"
;
$database->setQuery( $query );
$rows = $database->loadObjectList();
?>
<table class="adminlist">
	<tr>
		<th class="title" width="80%">
			Меню
		</th>
		<th class="title">Пунктов</th>
	</tr>
<?php
foreach ($rows as $row) {
	$link = 'index2.php?option=com_menus&amp;menutype='. $row->menutype;
	?>
	<tr>
		<td>
			<a href="<?php echo $link; ?>">
				<?php echo $row->menutype;?></a>
		</td>
		<td style="text-align: center;">
			<?php echo $row->numitems;?>
		</td>
	</tr>
<?php
}
?>
<tr>
	<th colspan="2">
	</th>
</tr>
</table>

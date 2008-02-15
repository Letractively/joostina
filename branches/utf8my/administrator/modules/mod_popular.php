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

$query = "SELECT a.hits, a.id, a.sectionid, a.title, a.created, u.name"
	. "\n FROM #__content AS a"
. "\n LEFT JOIN #__users AS u ON u.id=a.created_by"
. "\n WHERE a.state != -2"
. "\n ORDER BY hits DESC"
;
$database->setQuery( $query, 0, 10 );
$rows = $database->loadObjectList();
?>

<table class="adminlist">
<tr>
	<th class="title">
		Часто просматриваемое
	</th>
	<th class="title">
		Создано
	</th>
	<th class="title">
		Просмотров
	</th>
</tr>
<?php
foreach ($rows as $row) {
	if ( $row->sectionid == 0 ) {
		$link = 'index2.php?option=com_typedcontent&amp;task=edit&amp;hidemainmenu=1&amp;id='. $row->id;
	} else {
		$link = 'index2.php?option=com_content&amp;task=edit&amp;hidemainmenu=1&amp;id='. $row->id;
	}
	?>
	<tr>
		<td>
			<a href="<?php echo $link; ?>">
				<?php echo htmlspecialchars($row->title, ENT_QUOTES);?></a>
		</td>
		<td>
			<?php echo $row->created;?>
		</td>
		<td style="text-align: center">
			<?php echo $row->hits;?>
		</td>
	</tr>
	<?php
}
?>
<tr>
	<th colspan="3">
	</th>
</tr>
</table>

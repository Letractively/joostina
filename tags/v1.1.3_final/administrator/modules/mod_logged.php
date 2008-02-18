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

global $mosConfig_list_limit;

require_once( $mosConfig_absolute_path .'/administrator/includes/pageNavigation.php' );

$limit 			= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
$limitstart 	= $mainframe->getUserStateFromRequest( "view{$option}", 'limitstart', 0 );

// hides Administrator or Super Administrator from list depending on usertype
$and = '';
// administrator check
if ( $my->gid == 24 ) {
	$and = "\n AND userid != '25'";
}
// manager check
if ( $my->gid == 23 ) {
	$and = "\n AND userid != '25'";
	$and .= "\n AND userid != '24'";
}

// get the total number of records
$query = "SELECT COUNT(*)"
. "\n FROM #__session"
. "\n WHERE userid != 0"
. $and
. "\n ORDER BY usertype, username"
;
$database->setQuery( $query );
$total = $database->loadResult();

// page navigation
$pageNav = new mosPageNav( $total, $limitstart, $limit );

$query = "SELECT *"
. "\n FROM #__session"
. "\n WHERE userid != 0"
. $and
. "\n ORDER BY usertype, username"
;
$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
$rows = $database->loadObjectList();
?>
<table class="adminlist">
<tr>
	<th colspan="4" class="title">
	Сейчас на сайте авторизованы
	</th>
</tr>
<?php
$i = 0;
foreach ( $rows as $row ) {
	if ( $acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' ) ) {
		$link 	= 'index2.php?option=com_users&task=editA&hidemainmenu=1&id='. $row->userid;
		$name 	= '<a href="'. $link .'" title="Изменить данные пользователя">'. $row->username .'</a>';
	} else {
		$name 	= $row->username;
	}
	?>
	<tr>
		<td width="5%">
		<?php echo $pageNav->rowNumber( $i ); ?>
		</td>
		<td>
		<?php echo $name;?>
		</td>
		<td>
		<?php echo $row->usertype;?>
		</td>
		<?php
		if ( $acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' ) ) {
			?>
			<td>
			<a href="index2.php?option=com_users&task=flogout&id=<?php echo $row->userid; ?>">
			<img src="images/publish_x.png" width="12" height="12" border="0" alt="Отключить" Title="Быстро отключить пользователя" />
			</a>
			</td>
			<?php
		}
		?>
	</tr>
	<?php
	$i++;
}
?>
</table>
<?php echo $pageNav->getListFooter(); ?>
<input type="hidden" name="option" value="" />

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

class HTML_linkeditor {

function viewall(&$rows, $pageNav) {
global $mosConfig_live_site;
mosCommonHTML::loadOverlib();
?>
<form action="index2.php" method="post" name="adminForm">
<table class="adminheading">
  <tr>
    <th class="install"><?php echo _LE_LINKLIST;?></th>
   </tr>
</table>
<table class="adminlist">
<tr>
<th width="10" align="left">
			#
			</th>
			<th class="title" width="20">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows );?>);" />
			</th>
<th class="title" width="20%"><?php echo _LE_LINKNAME;?></th>
<th class="title" width="20%"><?php echo _LE_LINKDESC;?></th>
<th class="title" width="20%"><?php echo _LE_LINKISCORE;?></th>
<th class="title" width="20%"><?php echo _LE_LINKICON;?></th>
<th width="2%"><?php echo _LE_LINKORDER;?></th>
<th width="1%"><a href="javascript: saveorder( <?php echo count( $rows )-1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="Save Order" /></a></th>
</tr>
<?php
$k = 0;
$i = 0;
$n = count($rows);
foreach ($rows as $row) {
	$checked = mosHTML::idBox( $i, $row->id, null );
	$link = 'index2.php?option=com_linkeditor&amp;task=edit&amp;hidemainmenu=1&amp;id='. $row->id;
?>
<tr class="<?php echo "row$k"; ?>">
<td>
<?php echo $pageNav->rowNumber( $i ); ?>
</td>
<td>
<?php echo $checked; ?>
</td>
<td>
<a href="<?php echo $link; ?>">
<?php echo stripslashes( $row->treename ); ?>
</a>
</td>
<td>
<?php echo $row->admin_menu_alt;?>
</td>
<td>
<img src="images/<?php echo ( $row->iscore ) ? 'tick.png' : 'publish_x.png';?>" width="12" height="12" border="0" alt="<?php echo ( $row->iscore ) ? 'Evet' : 'Hayэr';?>" />
</td>
<td>
<img src="<?php echo $mosConfig_live_site;?>/includes/<?php echo ($row->admin_menu_img!='js/ThemeOffice/') ? $row->admin_menu_img : 'js/ThemeOffice/spacer.png';?>" />
</td>
<td align="center" colspan="2">
<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
</td>
<?php
$k = 1 - $k;
?>
</tr>
<?php
$k = 1 - $k;
$i++;
}
?>
</table>
	<?php echo $pageNav->getListFooter(); ?>
	<input type="hidden" name="task" value="all" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_linkeditor" />
</form>
<?php
}

function edit($row, $lists) {
	global $mosConfig_live_site;
?>
    <script  type="text/javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
	<table class="adminheading">
		<tr>
			<th class="install"><?php echo $row->id ? _LE_EDITLINK : _LE_NEWLINK;?></th>
		</tr>
	</table>
<?php if($row->iscore==1) echo '<div style="background-color: red;color:white"><b>Внимание:</b> этот компонент является частью ядра, при некорректном управлении им возможны проблемы в работе системы.</div>';?>
		<form action="index2.php" method="post" name="adminForm" id="adminForm">
		<table class="adminform">
		<tr>
			<td width="20%" align="right" >
			<?php echo _LE_LINKNAME;?><font color="red">*</font>:
			</td>
			<td width="25%">
			<input class="inputbox" type="text" name="name" size="45" value="<?php echo $row->name;?>" />
			<?php
  				$tip = 'Название пункта меню. Обязательно для заполнения.';
               echo mosToolTip( $tip );
         ?>
			</td>
			<td colspan="1" rowspan="4">
			<img name="view_imagefiles" src="<?php echo $mosConfig_live_site;?>/includes/<?php echo ($row->admin_menu_img!='js/ThemeOffice/') ? $row->admin_menu_img : 'js/ThemeOffice/spacer.png';?>" width="16" />
		<?php
			echo _LE_LINKICON.':<br />';
			echo $lists['image'];?>
			<?php
  				$tip = 'Значок пункта меню.';
               echo mosToolTip( $tip );
         ?>
			</td>
		</tr>
		<tr>
			<td align="right">
				<?php echo _LE_LINKDESC;?>:
			</td>
			<td>
				<input class="inputbox" type="text" name="admin_menu_alt" size="45" value="<?php echo $row->admin_menu_alt;?>" />
			<?php
  				$tip = 'Описание пункта меню.';
				echo mosToolTip( $tip );
			?>
			</td>
		</tr>
		<tr>
			<td align="right">
			<?php echo _LE_LINKLINK;?><font color="red">*</font>:
			</td>
			<td>
			<input class="inputbox" type="text" name="admin_menu_link" size="45" value="<?php echo $row->admin_menu_link;?>" />
			<?php
  				$tip = 'Ссылка на компонент. Если пункт меню не содержит подменю то поле обязательно для заполнения.';
               echo mosToolTip( $tip );
         ?>
			</td>
		</tr>
			<tr>
			<td align="right">
			<?php echo _LE_PARENT; ?>:
			</td>
			<td>
			<?php echo $lists['parent'];?>
			<?php
  				$tip = 'Родительский пункт меню. Допускается всего 1 уровень вложенности.';
               echo mosToolTip( $tip );
         ?>
			</td>
		</tr>
	<tr>
			<td>
<font color="red">*</font> пункты обязательны для заполнения.
			</td>
			<td>&nbsp;</td>
		</tr>
		</table>
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="savelink" />
		<input type="hidden" name="hidemainmenu" value="1" />
		<input type="hidden" name="option" value="com_linkeditor" />
		<input type="hidden" name="cur_option" value="<?php echo $row->option; ?>" />
		</form>
<?php

	}
}
?>

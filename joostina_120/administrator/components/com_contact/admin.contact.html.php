<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
* @package Joostina
* @subpackage Contact
*/
class HTML_contact {

	function showContacts(&$rows,&$pageNav,$search,$option,&$lists) {
		global $my;

		mosCommonHTML::loadOverlib();
?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Управление контактами
			</th>
			<td>
			Фильтр:
			</td>
			<td>
			<input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="inputbox" onChange="document.adminForm.submit();" />
			</td>
			<td width="right">
			<?php echo $lists['catid']; ?>
			</td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="20" class="title">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo
count($rows); ?>);" />
			</th>
			<th class="title">
			Имя
			</th>
			<th width="5%" class="title">
			На сайте
			</th>
			<th colspan="2" class="jtd_nowrap" width="5%">
			Сортировка
			</th>
			<th width="15%" align="left">
			Категория
			</th>
			<th class="title" width="15%">
			Связано с пользователем
			</th>
		</tr>
		<?php
		$k = 0;
		for($i = 0,$n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			mosMakeHtmlSafe($row);
			$link = 'index2.php?option=com_contact&task=editA&hidemainmenu=1&id='.$row->id;

			$img = $row->published?'tick.png':'publish_x.png';
			$task = $row->published?'unpublish':'publish';
			$alt = $row->published?'Опубликовано':'Не опубликовано';

			$checked = mosCommonHTML::CheckedOutProcessing($row,$i);
			$row->cat_link = 'index2.php?option=com_categories&section=com_contact_details&task=editA&hidemainmenu=1&id='.
				$row->catid;
			$row->user_link = 'index2.php?option=com_users&task=editA&hidemainmenu=1&id='.$row->user_id;
?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $pageNav->rowNumber($i); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
			if($row->checked_out && ($row->checked_out != $my->id)) {
				echo $row->name;
			} else {
?>
					<a href="<?php echo $link; ?>" title="Изменить контакт">
					<?php echo $row->name; ?>
					</a>
					<?php
			}
?>
				</td>
				<td align="center">
				<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')">
				<img src="images/<?php echo $img; ?>" border="0" alt="<?php echo $alt; ?>" />
				</a>
				</td>
				<td>
				<?php echo $pageNav->orderUpIcon($i,($row->catid == @$rows[$i - 1]->catid)); ?>
				</td>
				<td>
				<?php echo $pageNav->orderDownIcon($i,$n,($row->catid == @$rows[$i + 1]->catid)); ?>
				</td>
				<td>
				<a href="<?php echo $row->cat_link; ?>" title="Изменить категорию">
				<?php echo $row->category; ?>
				</a>
				</td>
				<td>
				<a href="<?php echo $row->user_link; ?>" title="Изменить пользователя">
				<?php echo $row->user; ?>
				</a>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}


	function editContact(&$row,&$lists,$option,&$params) {
		global $mosConfig_live_site;
		mosCommonHTML::loadOverlib();
		if($row->image == '') {
			$row->image = 'blank.png';
		}

		$tabs = new mosTabs(0);

		mosMakeHtmlSafe($row,ENT_QUOTES,'misc');
?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if ( form.name.value == "" ) {
				alert( "Вы должны ввести имя." );
			} else if ( form.catid.value == 0 ) {
				alert( "Пожалуйста, выберите категорию." );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Контакт:
			<small>
			<?php echo $row->id?'Редактирование':'Новый'; ?>
			</small>
			</th>
		</tr>
		</table>

		<table width="100%">
		<tr>
			<td width="60%" valign="top">
				<table width="100%" class="adminform">
				<tr>
					<th colspan="2">
					Детали контакта
					</th>
				<tr>
				<tr>
					<td width="30%" align="right">
					Категория:
					</td>
					<td width="40%">
					<?php echo $lists['catid']; ?>
					</td>
				</tr>
				<tr>
					<td width="30%" align="right">
					Связано с пользователем:
					</td>
					<td >
					<?php echo $lists['user_id']; ?>
					</td>
				</tr>
				<tr>
					<td width="30%" align="right">
					Имя:
					</td>
					<td >
					<input class="inputbox" type="text" name="name" size="50" maxlength="100" value="<?php echo $row->name; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
					Положение (должность):
					</td>
					<td>
					<input class="inputbox" type="text" name="con_position" size="50" maxlength="50" value="<?php echo $row->con_position; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
					E-mail:
					</td>
					<td>
					<input class="inputbox" type="text" name="email_to" size="50" maxlength="100" value="<?php echo $row->email_to; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
					Адрес (улица, дом):
					</td>
					<td>
					<input class="inputbox" type="text" name="address" size="100" value="<?php echo $row->address; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
					Город (Населенный пункт):
					</td>
					<td>
					<input class="inputbox" type="text" name="suburb" size="50" maxlength="100" value="<?php echo $row->suburb; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
					Край/Область/Республика:
					</td>
					<td>
					<input class="inputbox" type="text" name="state" size="50" maxlength="100" value="<?php echo $row->state; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
					Страна:
					</td>
					<td>
					<input class="inputbox" type="text" name="country" size="50" maxlength="100" value="<?php echo $row->country; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
					Почтовый индекс:
					</td>
					<td>
					<input class="inputbox" type="text" name="postcode" size="25" maxlength="20" value="<?php echo $row->postcode; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
					Телефон:
					</td>
					<td>
					<input class="inputbox" type="text" name="telephone" size="25" maxlength="25" value="<?php echo $row->telephone; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
					Факс:
					</td>
					<td>
					<input class="inputbox" type="text" name="fax" size="25" maxlength="25" value="<?php echo $row->fax; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">
					Дополнительная информация:
					</td>
					<td>
					<textarea name="misc" rows="5" cols="50" class="inputbox"><?php echo $row->misc; ?></textarea>
					</td>
				</tr>
				</table>
			</td>
			<td width="40%" valign="top">
				<?php
		$tabs->startPane("content-pane");
		$tabs->startTab("Публикация","publish-page");
?>
				<table width="100%" class="adminform">
				<tr>
					<th colspan="2">
					Информация о публикации
					</th>
				<tr>
				<tr>
					<td valign="top" align="right">
					Опубликовано:
					</td>
					<td>
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					Расположение:
					</td>
					<td>
					<?php echo $lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					Уровень доступа:
					</td>
					<td>
					<?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;

					</td>
				</tr>
				</table>
				<?php
		$tabs->endTab();
		$tabs->startTab("Изображения","images-page");
?>
				<table width="100%" class="adminform">
				<tr>
					<th colspan="2">
					Информация об изображении
					</th>
				<tr>
				<tr>
					<td align="left" width="20%">
					Изображение:
					</td>
					<td align="left">
					<?php echo $lists['image']; ?>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
					<script language="javascript" type="text/javascript">
					if (document.forms[0].image.options.value!=''){
						jsimg='../images/stories/' + getSelectedValue( 'adminForm', 'image' );
					} else {
						jsimg='../images/M_images/blank.png';
					}
					document.write('<img src=' + jsimg + ' name="imagelib" width="100" height="100" border="2" alt="Просмотр" />');
					</script>
					</td>
				</tr>
				</table>
				<?php
		$tabs->endTab();
		$tabs->startTab("Параметры","params-page");
?>
				<table class="adminform">
				<tr>
					<th>
					Параметры
					</th>
				</tr>
				<tr>
					<td>
					* Эти параметры управляют отображением только при просмотре информации о контакте*
					<br /><br />
					</td>
				</tr>
				<tr>
					<td>
					<?php echo $params->render(); ?>
					</td>
				</tr>
				</table>
				<?php
		$tabs->endTab();
		$tabs->endPane();
?>
			</td>
		</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}
}
?>

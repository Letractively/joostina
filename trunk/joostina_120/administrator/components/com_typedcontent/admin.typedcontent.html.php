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
 * @subpackage Content
 */
class HTML_typedcontent {

	/**
	 * Writes a list of the content items
	 * @param array An array of content objects
	 */
	function showContent(&$rows,&$pageNav,$option,$search,&$lists) {
	global $my,$acl,$database,$mosConfig_live_site;
	mosCommonHTML::loadOverlib();
?>
<form action="index2.php" method="post" name="adminForm">
	<table class="adminheading">
	<tr>
		<th class="edit">Статичное содержимое</th>
		<td>Фильтр:&nbsp;</td>
		<td><input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="text_area" onChange="document.adminForm.submit();" /></td>
		<td>&nbsp;Сортировка:&nbsp;</td>
		<td><?php echo $lists['order']; ?></td>
		<td width="right"><?php echo $lists['authorid']; ?></td>
	</tr>
	</table>
	<table class="adminlist">
	<tr>
		<th width="5">#</th>
		<th width="5px"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" /></th>
		<th class="title">Заголовок</th>
		<th width="5%">Опубликовано</th>
		<th width="2%"> Сортировка</th>
		<th width="1%"><a href="javascript: saveorder( <?php echo count($rows) - 1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="Сохранить порядок" /></a></th>
		<th width="10%">Доступ</th>
		<th width="5%">ID</th>
		<th width="1%" align="left">Ссылок</th>
	</tr>
<?php
	$k = 0;
	$nullDate = $database->getNullDate();
	for($i = 0,$n = count($rows); $i < $n; $i++) {
		$row = &$rows[$i];
		mosMakeHtmlSafe($row);
		$now = _CURRENT_SERVER_TIME;
		if($now <= $row->publish_up && $row->state == 1) {
		// Published
		$img = 'publish_y.png';
		$alt = 'Опубликовано';
		} else
		if(($now <= $row->publish_down || $row->publish_down == $nullDate) && $row->state == 1) {
			// Pending
			$img = 'publish_g.png';
			$alt = 'Опубликовано';
		} else
			if($now > $row->publish_down && $row->state == 1) {
			// Expired
			$img = 'publish_r.png';
			$alt = 'Истек срок публикации';
			} elseif($row->state == 0) {
			// Unpublished
			$img = 'publish_x.png';
			$alt = 'Неопубликовано';
			}

		// correct times to include server offset info
		$row->publish_up = mosFormatDate($row->publish_up,_CURRENT_SERVER_TIME_FORMAT);
		if(trim($row->publish_down) == $nullDate || trim($row->publish_down) == '' || trim($row->publish_down) == '-') {
		$row->publish_down = 'Никогда';
		}
		$row->publish_down = mosFormatDate($row->publish_down,_CURRENT_SERVER_TIME_FORMAT);

		$times = '';
		if($row->publish_up == $nullDate) {
		$times .= "<tr><td>Начало: Всегда</td></tr>";
		} else {
		$times .= "<tr><td>Начало: $row->publish_up</td></tr>";
		}
		if($row->publish_down == $nullDate || $row->publish_down == 'Никогда') {
		$times .= "<tr><td>Окончание: Без срока</td></tr>";
		} else {
		$times .= "<tr><td>Окончание: $row->publish_down</td></tr>";
		}

		if(!$row->access) {
		$color_access = 'style="color: green;"';
		$task_access = 'accessregistered';
		} else
		if($row->access == 1) {
			$color_access = 'style="color: red;"';
			$task_access = 'accessspecial';
		} else {
			$color_access = 'style="color: black;"';
			$task_access = 'accesspublic';
		}
		$link = 'index2.php?option=com_typedcontent&task=edit&hidemainmenu=1&id='.$row->id;
		$checked = mosCommonHTML::CheckedOutProcessing($row,$i);
		$access = mosCommonHTML::AccessProcessing($row,$i,1);
		if($acl->acl_check('administration','manage','users',$my->usertype,'components','com_users')) {
			if($row->created_by_alias) {
				$author = $row->created_by_alias;
			} else {
				$linkA = 'index2.php?option=com_users&task=editA&hidemainmenu=1&id='.$row->created_by;
				$author = '<a href="'.$linkA.'" title="Изменить данные пользователя">'.$row->creator.'</a>';
			}
		} else {
			if($row->created_by_alias) {
				$author = $row->created_by_alias;
			} else {
				$author = $row->creator;
			}
		}

?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $pageNav->rowNumber($i); ?></td>
		<td><?php echo $checked; ?></td>
		<td align="left">
<?php
		if($row->checked_out && ($row->checked_out != $my->id)) {
			echo $row->title;
		if($row->title_alias) {
			echo ' (<i>'.$row->title_alias.'</i>)';
		}
		} else {
?>
		<a href="<?php echo $link; ?>" class="abig" title="Изменить статичное содержимое">
<?php
		echo $row->title;
		if($row->title_alias) {
			echo ' (<i>'.$row->title_alias.'</i>)';
		}
?></a>
<?php
		echo '<br />'.$row->created.' : '.$author;
		}
?>
		</td>
<?php
		if($times) {
?>
		<td align="center" <?php echo ($row->checked_out && ($row->checked_out != $my->id))?null:'onclick="ch_publ('.$row->id.',\'com_typedcontent\');" class="td-state"'; ?>>
			<img class="img-mini-state" src="images/<?php echo $img; ?>" id="img-pub-<?php echo $row->id; ?>" alt="Публикация" />
		</td>
<?php
		}
?>
		<td align="center" colspan="2">
			<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
		</td>
		<td align="center" id="acc-id-<?php echo $row->id; ?>"><?php echo $access; ?></td>
		<td align="center"><?php echo $row->id; ?></td>
		<td align="center"><?php echo $row->links; ?></td>
	</tr>
<?php
		$k = 1 - $k;
	}
?>
	</table>
	<?php echo $pageNav->getListFooter(); ?>
	<?php mosCommonHTML::ContentLegend(); ?>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
	</form>
<?php
	}

	function edit(&$row,&$images,&$lists,&$params,$option,&$menus) {
	global $database,$mosConfig_live_site;
	mosMakeHtmlSafe($row);
	$create_date = null;
	$mod_date = null;
	$nullDate = $database->getNullDate();
	if($row->created != $nullDate) {
		$create_date = mosFormatDate($row->created,'%A, %d %B %Y %H:%M','0');
	}
	if($row->modified != $nullDate) {
		$mod_date = mosFormatDate($row->modified,'%A, %d %B %Y %H:%M','0');
	}
	$tabs = new mosTabs(1);
	// used to hide "Reset Hits" when hits = 0
	if(!$row->hits) {
		$visibility = "style='display: none; visibility: hidden;'";
	} else {
		$visibility = "";
	}
	mosCommonHTML::loadOverlib();
	mosCommonHTML::loadCalendar();
?>
	<script language="javascript" type="text/javascript">
	var folderimages = new Array;
<?php
	$i = 0;
	foreach($images as $k => $items) {
		foreach($items as $v) {
		echo "folderimages[".$i++."] = new Array( '$k','".addslashes($v->value)."','".addslashes($v->text)."' );\t";
		}
	}
?>
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
		}
		if ( pressbutton ==' resethits' ) {
		if (confirm('Вы действительно хотите обнулить счетчик просмотров? \nЛюбые несохраненные изменения этого содержимого будут утеряны.')){
			submitform( pressbutton );
			return;
		} else {
			return;
		}
		}
		if ( pressbutton == 'menulink' ) {
		if ( form.menuselect.value == "" ) {
			alert( "Пожалуйста, выберите меню" );
			return;
		} else if ( form.link_name.value == "" ) {
			alert( "Пожалуйста, введите имя для этого пункта меню" );
			return;
		}
		}
		var temp = new Array;
		for (var i=0, n=form.imagelist.options.length; i < n; i++) {
		temp[i] = form.imagelist.options[i].value;
		}
		form.images.value = temp.join( '\n' );
		try {
		document.adminForm.onsubmit();
		}
		catch(e){}
		if (trim(form.title.value) == ""){
		alert( "Объект содержимого должен иметь заголовок" );
		} else if (trim(form.name.value) == ""){
		alert( "Объект содержимого должен иметь название" );
		} else {
		if ( form.reset_hits.checked ) {
			form.hits.value = 0;
		} else {
		}
		<?php getEditorContents('editor1','introtext'); ?>
		submitform( pressbutton );
		}
	}
	</script>
	<table class="adminheading">
	<tr>
		<th class="edit">Статичное содержимое: <small><?php echo $row->id?'Редактирование':'Создание'; ?></small></th>
	</tr>
	</table>
	<form action="index2.php" method="post" name="adminForm">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td width="100%" valign="top">
			<table class="adminform">
				<tr>
					<th colspan="3">Информация о содержимом</th>
				</tr>
				<tr>
					<td align="left">Заголовок:</td>
					<td width="90%">
					<input class="inputbox" type="text" name="title" size="30" maxlength="150" style="width:98%" value="<?php echo $row->title; ?>" />
					</td>
				</tr>
				<tr>
					<td align="left">Псевдоним:</td>
					<td width="90%"><input class="inputbox" type="text" name="title_alias" size="30" maxlength="150" style="width:98%" value="<?php echo $row->title_alias; ?>" /></td>
				</tr>
				<tr>
					<td valign="top" align="left" colspan="2">
					Текст: (обязательно)<br />
<?php
	// parameters : areaname, content, hidden field, width, height, rows, cols
	editorArea('editor1',$row->introtext,'introtext','100%;','500','75','50');
?>
					</td>
				</tr>
				</table>
			</td>
			<td valign="top">
			<div id="params" style="width:410px">
<?php
	$tabs->startPane("content-pane");
	$tabs->startTab("Публикация","publish-page");
?>
	<table class="adminform">
		<tr>
			<td valign="top" align="right" width="120">Состояние:</td>
			<td><?php echo $row->state > 0?'Опубликовано':'Черновик - Не опубликовано'; ?></td>
		</tr>
		<tr>
			<td valign="top" align="right">Опубликовано:</td>
			<td><input type="checkbox" name="published" value="1" <?php echo $row->state?'checked="checked"':''; ?> /></td>
		</tr>
		<tr>
			<td valign="top" align="right">Уровень доступа:</td>
			<td><?php echo $lists['access']; ?></td>
		</tr>
		<tr>
			<td valign="top" align="right">Псевдоним автора:</td>
			<td><input type="text" name="created_by_alias" size="30" maxlength="100" value="<?php echo $row->created_by_alias; ?>" class="inputbox" /></td>
		</tr>
		<tr>
			<td valign="top" align="right">Изменить автора:</td>
			<td><?php echo $lists['created_by']; ?></td>
		</tr>
		<tr>
			<td valign="top" align="right">Дата создания:</td>
			<td>
				<input class="inputbox" type="text" name="created" id="created" size="25" maxlength="19" value="<?php echo $row->created; ?>" />
				<input name="reset" type="reset" class="button" onClick="return showCalendar('created', 'y-mm-dd');" value="...">
			</td>
		</tr>
		<tr>
			<td align="right">Начало публикации:</td>
			<td>
				<input class="inputbox" type="text" name="publish_up" id="publish_up" size="25" maxlength="19" value="<?php echo $row->publish_up; ?>" />
				<input type="reset" class="button" value="..." onclick="return showCalendar('publish_up', 'y-mm-dd');">
			</td>
		</tr>
		<tr>
			<td align="right">Окончание публикации:</td>
			<td>
				<input class="inputbox" type="text" name="publish_down" id="publish_down" size="25" maxlength="19" value="<?php echo $row->publish_down; ?>" />
				<input type="reset" class="button" value="..." onclick="return showCalendar('publish_down', 'y-mm-dd');">
			</td>
		</tr>
	</table>
	<br />
	<table class="adminform" width="100%">
<?php
	if($row->id) {
?>
		<tr>
			<td>ID содержимого:</td>
			<td><?php echo $row->id; ?></td>
		</tr>
<?php
	}
?>
		<tr>
			<td width="120" valign="top" align="right">Состояние</td>
			<td><?php echo $row->state > 0?'Опубликовано':($row->state < 0?'В архиве':'Черновик - Не опубликовано'); ?></td>
		</tr>
		<tr>
			<td valign="top" align="right">Просмотров</td>
			<td>
				<?php echo $row->hits; ?>
				<div <?php echo $visibility; ?>>
					<input name="reset_hits" type="button" class="button" value="Сбросить счетчик просмотров" onClick="submitbutton('resethits');">
				</div>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right">Версия</td>
			<td><?php echo $row->version; ?></td>
		</tr>
		<tr>
			<td valign="top" align="right">Создано</td>
			<td><?php echo $create_date ? $create_date : 'Новый документ';?></td>
		</tr>
		<tr>
			<td valign="top" align="right">Последнее изменение</td>
			<td><?php echo $mod_date ? $mod_date.'<br />'.$row->modifier : 'Не изменялось';?></td>
		</tr>
		<tr>
			<td valign="top" align="right">Окончание публикации</td>
			<td><?php echo $row->publish_down; ?></td>
		</tr>
	</table>
<?php
	$tabs->endTab();
	$tabs->startTab("Изображения","images-page");
?>
	<table class="adminform">
		<tr>
		<td colspan="2">
				<table width="100%">
				<tr>
					<td width="48%" valign="top">
					<div align="center">
						Изображения галереи:<br />
						<?php echo $lists['imagefiles']; ?>
					</div>
					</td>
					<td width="2%">
						<input class="button" type="button" value=">>" onclick="addSelectedToList('adminForm','imagefiles','imagelist')" title="Добавить"/>
						<input class="button" type="button" value="<<" onclick="delSelectedFromList('adminForm','imagelist')" title="Удалить"/>
					</td>
					<td width="48%">
						<div align="center">
						Изображения содержимого:
						<br />
						<?php echo $lists['imagelist']; ?>
						<br />
						<input class="button" type="button" value="Вверх" onclick="moveInList('adminForm','imagelist',adminForm.imagelist.selectedIndex,-1)" />
						<input class="button" type="button" value="Вниз" onclick="moveInList('adminForm','imagelist',adminForm.imagelist.selectedIndex,+1)" />
						</div>
					</td>
				</tr>
				</table>
				Подкаталог: <?php echo $lists['folders']; ?>
			</td>
		</tr>
		<tr valign="top">
			<td>
				<div align="center">
					Образец изображения:<br />
					<img name="view_imagefiles" src="../images/M_images/blank.png" width="100" />
				</div>
			</td>
			<td valign="top">
				<div align="center">
					Активное изображение:<br />
					<img name="view_imagelist" src="../images/M_images/blank.png" width="100" />
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				Редактирование выбранного изображения:
				<table>
				<tr>
					<td align="right">Источник</td>
					<td><input class="text_area" type="text" name= "_source" value="" /></td>
				</tr>
				<tr>
					<td align="right">Выравнивание</td>
					<td><?php echo $lists['_align']; ?></td>
				</tr>
				<tr>
					<td align="right">Альтернативный текст</td>
					<td><input class="text_area" type="text" name="_alt" value="" /></td>
				</tr>
				<tr>
					<td align="right">Рамка</td>
					<td><input class="text_area" type="text" name="_border" value="" size="3" maxlength="1" /></td>
				</tr>
				<tr>
					<td align="right">Подпись:</td>
					<td><input class="text_area" type="text" name="_caption" value="" size="30" /></td>
				</tr>
				<tr>
					<td align="right">Положение подписи:</td>
					<td><?php echo $lists['_caption_position']; ?></td>
				</tr>
				<tr>
					<td align="right">Выравнивание подписи:</td>
					<td><?php echo $lists['_caption_align']; ?></td>
				</tr>
				<tr>
					<td align="right">Ширина:</td>
					<td><input class="text_area" type="text" name="_width" value="" size="5" maxlength="5" /></td>
				</tr>
				<tr>
					<td colspan="2"><input class="button" type="button" value="Применить" onClick="applyImageProps()" /></td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
<?php
	$tabs->endTab();
	$tabs->startTab("Параметры","params-page");
?>
	<table class="adminform">
		<tr>
			<td><?php echo $params->render(); ?></td>
		</tr>
	</table>
<?php
	$tabs->endTab();
	$tabs->startTab("Метаданные","metadata-page");
?>
	<table class="adminform">
		<tr>
			<td align="left">Описание (Description):<br />
				<textarea class="inputbox" cols="40" rows="5" name="metadesc" style="width:98%"><?php echo str_replace('&','&amp;',$row->metadesc); ?></textarea>
			</td>
		</tr>
		<tr>
			<td align="left">Ключевые слова (Keywords):<br />
				<textarea class="inputbox" cols="40" rows="5" name="metakey" style="width:98%"><?php echo str_replace('&','&amp;',$row->metakey); ?></textarea>
			</td>
		</tr>
	</table>
<?php
	$tabs->endTab();
	$tabs->startTab("Связь с меню","link-page");
?>
	<table class="adminform">
		<tr>
			<td colspan="2">Здесь создается пункт меню типа 'Ссылка - Статичное содержимое', который вставляется в выбранное из списка меню</td>
		</tr>
		<tr>
			<td valign="top">Выберите меню</td>
			<td><?php echo $lists['menuselect']; ?></td>
		</tr>
		<tr>
			<td valign="top">Название пункта меню</td>
			<td><input type="text" name="link_name" class="inputbox" value="" size="30" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input name="menu_link" type="button" class="button" value="Создать пункт меню" onClick="submitbutton('menulink');" /></td>
		</tr>
			<tr><th colspan="2">Существующие связи с меню</th>
		</tr>
<?php
	if($menus == null) {
?>
		<tr>
			<td colspan="2">Отсутствуют	</td>
		</tr>
<?php
	} else {
		mosCommonHTML::menuLinksContent($menus);
	}
?>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</table>
<?php
	$tabs->endTab();
	$tabs->endPane();
?>
				</div>
			</td>
		</tr>
		</table>
		<input type="hidden" name="images" value="" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="hits" value="<?php echo $row->hits; ?>" />
		<input type="hidden" name="task" value="" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />

		</form>
		<?php
	}
}
?>

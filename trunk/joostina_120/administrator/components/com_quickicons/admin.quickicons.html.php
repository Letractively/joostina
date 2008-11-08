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
* @package Custom QuickIcons
*/
class HTML_QuickIcons {

	function show(&$rows,$option,$search,&$pageNav) {
		global $mosConfig_live_site;
		mosCommonHTML::loadOverlib();
?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
			<tr>
				<th class="quickicons">Кнопки быстрого доступа</th>
				<td>Найти:</td>
				<td align="right">
					<input type="text" name="search" value="<?php echo $search; ?>" class="inputbox" onChange="document.adminForm.submit();" />
				</td>
			</tr>
		</table>
		<table class="adminlist">
			<tr>
				<th width="20">#</th>
				<th width="20" class="title">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
				</th>
				<th width="5%" class="jtd_nowrap">Значок</th>
				<th width="53%" class="title">Заголовок</th>
				<th width="10%" class="jtd_nowrap">Отображение</th>
				<th width="7%" class="jtd_nowrap">Доступ</th>
				<th width="7%" class="jtd_nowrap">Опубликовано</th>
				<th width="7%" colspan="2" class="jtd_nowrap">Сортировка</th>
				<th width="2%">Порядок</th>
				<th width="1%">
					<a href="javascript:saveorder(<?php echo count($rows) - 1; ?>)" title="Сохранить порядок"><img src="images/filesave.png" border="0" width="16" height="16" alt="Сохранить порядок" /></a>
				</th>
			</tr>
			<?php
		$k = 0;
		for($i = 0; $i < count($rows); $i++) {
			$row	= $rows[$i];
			$editLink = 'index2.php?option=com_quickicons&amp;task=edit&amp;id='.$row->id;
			$link	= 'index2.php?option=com_quickicons&amp;task=';

			$img	= $row->published ? 'tick.png':'publish_x.png';

			$checked =mosHTML::idBox($i,$row->id);

			// check display
			$display = '';
			switch($row->display) {
				case '1':
					$display = 'Только текст';
					break;

				case '2':
					$display = '<span style="color:red;">Только значок</span>';
					break;

				default:
					$display = '<span style="color:green;">Значок и текст</span>';
					break;
			}
?>
				<tr class="row<?php echo $k; ?>">
					<td><?php echo $row->id; ?></td>
					<td><?php echo $checked; ?></td>
					<td align="center"><img src="<?php echo $mosConfig_live_site.$row->icon ;?>" alt="" border="0" /></td>
					<td align="left">
						<a href="<?php echo $editLink; ?>" title="Нажмите для редактирования элемента"><?php echo $row->text; ?></a><br />
						<?php
							if($row->target == 'index2.php?option=' || !$row->target) {
								echo '<span style="color:red; font-weight:bold;">Ссылка не выбрана</span>';
							} else {
								echo htmlentities($row->target);
							}
						?>
					</td>
					<td align="center"><?php echo $display; ?></td>
					<td align="left"><?php echo $row->groupname; ?></td> 
					<td align="center" onclick="ch_publ(<?php echo $row->id; ?>,'com_quickicons');" class="td-state">
						<img class="img-mini-state" src="images/<?php echo $img;?>" id="img-pub-<?php echo $row->id;?>" alt="Публикация" />
					</td>
					<td align="center">
						<?php if($i!=0){?>
							<a href="<?php echo $link.'orderUp&amp;id='.$row->id; ?>" title="Выше"><img src="images/uparrow.png" border="0" alt="<?php echo _QI_ORDER_UP; ?>" /></a>
						<?php };?>
					</td>
					<td align="center">
						<?php if($i!=(count($rows)-1)){?>
							<a href="<?php echo $link.'orderDown&amp;id='.$row->id; ?>" title="Ниже"><img src="images/downarrow.png" border="0" alt="<?php echo _QI_ORDER_DOWN; ?>" /></a>
						<?php };?>
					</td>
					<td align="center" colspan="2">
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
					</td>
				</tr>
				<?php
			$k = 1 - $k;
		} ?>
			</table>
			<?php echo $pageNav->getListFooter(); ?>

			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	function edit(&$row,$lists,$option) {
		global $mosConfig_live_site;

		mosMakeHtmlSafe($row,ENT_QUOTES);
		mosCommonHTML::loadOverlib();
		$tabs = new mosTabs(0); ?>

		<script type="text/javascript">
			/* <![CDATA[*/
			function string_replace(string, search, replace) {
				var new_string = "";
				var i = 0;

				while(i < string.length) {
					if(string.substring(i, i + search.length) == search) {
						new_string = new_string + replace;
						i = i + search.length - 1;
					}else{
						new_string = new_string + string.substring(i, i + 1);
						i++;
					}
					return new_string;
				}
			}

			function applyTag(tag, obj) {
				var pre = document.adminForm.prefix;
				var post = document.adminForm.postfix;

				if (!obj.checked) {
					pre.value = string_replace(pre.value, '<'+tag+'>', '');
					post.value = string_replace(post.value, '</'+tag+'>', '');
				}else{
					pre.value = '<'+tag+'>' + pre.value;
					post.value = post.value + '</'+tag+'>';
				}
			};

			function changeIcon( icon ) {
				if (document.all) {
					document.all.iconImg.src = '<?php echo $GLOBALS['mosConfig_live_site']; ?>' + icon;
				}else{
					SRAX.get('iconImg').src = '<?php echo $GLOBALS['mosConfig_live_site']; ?>' + icon;
				}
			};

			function addTarget() {
				// taken from daniel grothe - thx!
				var exclude = document.adminForm.target.value.split(',');
				exclude.push( document.adminForm.tar_gets.value );

				//remove duplicates;
				var tmp = new Object();
				for(var i = 0; i < exclude.length; i++) {
					var id = exclude[i];
					if( !isNaN(id)) {
						continue;
					}

					tmp[ id ] = 'index2.php?' + id;
				}
				exclude = new Array();
				for(var k in tmp) {
					exclude.push( tmp[k] );
				}

				document.adminForm.target.value = exclude.pop('');
			};
			/* ]]>*/
		</script>
		<form action="index2.php" method="post" name="adminForm">
			<table class="adminheading">
			<tr>
				<th>
<?php
	if($row->id) {
?>
						Редактирование кнопки&nbsp;[&nbsp;<small><?php echo $row->text; ?></small>&nbsp;]
<?php
	} else {
		echo 'Создание';
	}
?>
				</th>
			</tr>
			</table>

			<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
				<tr>
					<td>
<?php
						$tabs->startPane('qicons');
						$tabs->startTab('Общее','general');
?>
					<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminform">
						<tr>
							<td align="right">Текст кнопки:</td>
							<td align="left">
								<input class="inputbox" type="text" name="text" size="50" maxlength="100" value="<?php echo $row->text; ?>" />
							</td>
						</tr>
						<tr>
							<td align="right">Подсказка:</td>
							<td align="left">
								<input class="inputbox" type="text" name="title" size="50" maxlength="64" value="<?php echo $row->title; ?>" />
<?php
								$tip = '<strong>Опционально</strong><br />Здесь вы можете определить текст для всплывающей подсказки.<br />Это свойство очень важно заполнить если вы выбрали отображение только картинки!';
								echo mosToolTip($tip);
?>
							</td>
						</tr>
						<tr>
							<td align="right" width="120">Ссылка:</td>
							<td align="left">
								<input class="inputbox" type="text" name="target" id="target" size="75" maxlength="255" value="<?php echo ($row->target ? $row->target:'index2.php?option='); ?>" />
								<button onclick="addTarget(); return false;">&larr;</button>&nbsp;
<?php
									echo $lists['targets'];
									$tip = 'Ссылка для вызова сайта или компонента.<br />Для компонентов внутри системы ссылка должна быть подобной: <br />index2.php?option=com_joomlastats&task=stats  [ joomlastats - компонент, &task=stats вызов определённой функции компонента ].<br />Внешние ссылки должны быть <strong>абсолютными ссылками</strong> (например: http://www....)!';
									echo mosToolTip($tip);
?>
							</td>
						</tr>
						<tr>
							<td align="right">
								<label for="new_window">В новом окне:</label>
							</td>
							<td align="left">
								<input type="checkbox" name="new_window" value="1" id="new_window"<?php echo $row->new_window ? ' checked="checked"':''; ?> />
<?php
									$tip = 'Ссылка будет открыта в новом окне';
									echo mosToolTip($tip);
?>
							</td>
						</tr>
						<tr>
							<td align="right">Расположить после:</td>
							<td align="left"><?php echo $lists['ordering']; ?></td>
						</tr>
						<tr>
							<td align="right" valign="top">Доступ:</td>
							<td align="left"><?php echo $lists['gid']; ?></td>
						</tr>
						<tr>
							<td align="right" width="130">Опубликовано:</td>
							<td align="left">
								<input type="radio" id="published1" name="published" value="1"<?php echo $row->published ? ' checked="checked"':''; ?> /><label for="published1">Да</label>
								<input type="radio" id="published2" name="published" value="0"<?php echo $row->published ? '':' checked="checked"'; ?> /><label for="published2">Нет</label>
							</td>
						</tr>
					</table>
<?php
						$tabs->endTab();
						$tabs->startTab('Отображение','display');
?>
					<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminform">
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td align="right">Отображать:</td>
							<td align="left"><?php echo $lists['display']; ?></td>
						</tr>
						<tr>
							<td align="right">Значок:</td>
							<td align="left">
								<input class="inputbox" type="text" name="icon" size="100" maxlength="100" value="<?php echo $mosConfig_live_site.$row->icon; ?>" onblur="changeIcon(this.value)" />
								<a href="index2.php?option=<?php echo $option; ?>&amp;task=chooseIcon" target="_blank" title="Нажмите для выбора картинки (откроется в новом окне)">Выбрать картинку</a>
<?php
									$tip = 'Пожалуйста, выберите картинку для этой кнопки. Если хотите загрузить собственную картинку для кнопки, то она должна быть загружена в ../administrator/images - ../images ../images/icons';
									echo mosToolTip($tip);
?>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td style="padding-top:10px">
<?php
									if(empty($row->icon)) {
										$iconLink = 'blank.png';
									} else {
										$iconLink = $row->icon;
									}
?>
								<img id="iconImg" src="<?php echo $mosConfig_live_site.$iconLink; ?>" alt="" />
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
		</form>
<?php
	}

	function quickiconButton($image) {
		global $mosConfig_absolute_path,$mosConfig_live_site;

		$image = str_replace($mosConfig_absolute_path,$mosConfig_live_site,$image);
		$js_action = "window.opener.document.adminForm.icon.value='$image'; window.opener.changeIcon('$image'); window.close()"; ?>
		<div style="float:left;">
			<div class="cpicons">
				<a href="javascript:void(0);" onclick="<?php echo $js_action; ?>;">
					<img src="<?php echo $image; ?>" alt="<?php echo $image; ?>" title="<?php echo $image; ?>" border="0" />
				</a>
			</div>
		</div>
<?php
	}

	function chooseIcon($imgs) { ?>

		<table class="adminheading">
			<tr>
				<th>Выбрать картинку:</th>
			</tr>
		</table>

		<table class="adminform">
			<tr>
				<th>
					<div>
						<a href="#" onclick="window.close()">Закрыть</a>
					</div>
				</th>
			</tr>
			<tr>
				<td style="padding:20px">
					<div id="cpanel"><?php
							for($i = 0; $i < count($imgs); $i++) {
								HTML_QuickIcons::quickiconButton($imgs[$i]);
							}
					?></div>
				</td>
			</tr>
		</table>
<?php
	}

}
?>

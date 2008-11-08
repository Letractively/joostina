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
* @subpackage Categories
*/
class categories_html {

	/**
	* Writes a list of the categories for a section
	* @param array An array of category objects
	* @param string The name of the category section
	*/
	function show(&$rows,$section,$section_name,&$pageNav,&$lists,$type) {
		global $my;
		mosCommonHTML::loadOverlib();
?>
	<script type="text/javascript">
	// получение списка разделов
	function ch_get_sec(elID,curSEC){
		log('Получение списка разделов для категории: '+elID+' текущий раздел: '+curSEC);
		SRAX.replaceHtml('cat-id-'+elID,'<div style="text-align:center;"><img src="images/aload.gif" /></div>');
		dax({
			url: 'ajax.index.php?option=com_categories&utf=1&task=get_sec&id='+elID+'&cur_sec='+curSEC,
			id:'publ-'+elID,
			callback:
				function(resp, idTread, status, ops){
					log('Получен ответ: ' + resp.responseText);
					SRAX.replaceHtml('cat-id-'+elID,resp.responseText);
		}});
	};
	// смена раздела категории
	function ch_save_sec(elID,newSEC){
		log('Смена раздела категории: '+elID+' на '+newSEC);
		SRAX.replaceHtml('cat-id-'+elID,'<div style="text-align:center;"><img src="images/aload.gif" /></div>');
		dax({
			url: 'ajax.index.php?option=com_categories&utf=1&task=save_sec&id='+elID+'&new_sec='+newSEC,
			id:'publ-'+elID,
			callback:
				function(resp, idTread, status, ops){
					log('Получен ответ: ' + resp.responseText);
					if(resp.responseText==2)
						SRAX.replaceHtml('cat-id-'+elID,'<div style="text-align:center;"><img src="images/error.png" /></div>');
					else
						SRAX.replaceHtml('cat-id-'+elID,resp.responseText);
		}});
	};
	</script>
	<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
<?php
		if($section == 'content') {
?>
			<th class="categories">
				Категории содержимого <small><small>[ Всё содержимое ]</small></small>
			</th>
			<td width="right"><?php echo $lists['sectionid']; ?></td>
<?php
		} else {
?>
			<th class="categories">Категории содержимого <small><small>[ <?php echo $section_name; ?> ]</small></small></th>
<?php
		}
?>
		</tr>
		</table>
		<table class="adminlist">
		<tr>
			<th width="10" align="left">#</th>
			<th width="20"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title">Название</th>
			<th width="8%">Опубликовано</th>
<?php
		if($section != 'content') {
?>
			<th colspan="2" width="5%">Сортировка</th>
<?php
		}
?>
			<th width="2%">Порядок</th>
			<th width="1%">
				<a href="javascript: saveorder( <?php echo count($rows) - 1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="Сохранить порядок" /></a>
			</th>
			<th width="8%">Доступ</th>
<?php
		if($section == 'content') {
?>
			<th width="12%" align="left">Раздел</th>
<?php
		}
?>
<?php
		if($type == 'content') {
?>
			<th width="6%">Активных</th>
			<th width="6%">В корзине</th>
<?php
		} else {
?>
			<th width="20%"></th>
<?php
		}
?>
			<th width="5%" class="jtd_nowrap">ID</th>
		</tr>
		<?php
		$k = 0;
		for($i = 0,$n = count($rows); $i < $n; $i++) {
			$row = &$rows[$i];
			mosMakeHtmlSafe($row);
			$row->sect_link = 'index2.php?option=com_sections&task=editA&hidemainmenu=1&id='.$row->section;

			$link = 'index2.php?option=com_categories&section='.$section.'&task=editA&hidemainmenu=1&id='.$row->id;
			$link_aktiv = 'index2.php?option=com_content&sectionid=0&catid='.$row->id;
			if($row->checked_out_contact_category) {
				$row->checked_out = $row->checked_out_contact_category;
			}
			$access		= mosCommonHTML::AccessProcessing($row,$i,1);
			$checked	= mosCommonHTML::CheckedOutProcessing($row,$i);
			$img		= $row->published ? 'publish_g.png' : 'publish_x.png';
?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $pageNav->rowNumber($i); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
<?php
			if($row->checked_out_contact_category && ($row->checked_out_contact_category !=$my->id)) {
				echo stripslashes($row->name).' ( '.stripslashes($row->title).' )';
			} else {
?>
					<a href="<?php echo $link; ?>"><?php echo stripslashes($row->name).' ( '.stripslashes($row->title).' )'; ?></a>
<?php
			}
?>
				</td>
				<td align="center" <?php echo ($row->checked_out && ($row->checked_out != $my->id)) ? null : 'onclick="ch_publ('.$row->id.',\'com_categories\');" class="td-state"';?>>
					<img class="img-mini-state" src="images/<?php echo $img;?>" id="img-pub-<?php echo $row->id;?>" alt="Публикация" />
				</td>
<?php
			if($section != 'content') {
?>
				<td><?php echo $pageNav->orderUpIcon($i); ?></td>
				<td><?php echo $pageNav->orderDownIcon($i,$n); ?></td>
<?php
			}
?>
				<td align="center" colspan="2">
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
				</td>
				<td align="center" id="acc-id-<?php echo $row->id;?>"><?php echo $access; ?></td>
<?php
			if($section == 'content') {
?>
				<td align="left" id="cat-id-<?php echo $row->id;?>">
					<a onclick="ch_get_sec(<?php echo $row->id;?>,<?php echo $row->section?>);" href="javascript: ch_get_sec(<?php echo $row->id;?>,<?php echo $row->section?>);"><?php echo $row->section_name; ?></a>
				</td>
<?php
			}
			if($type == 'content') {
?>
				<td align="center"><a href="<?php echo $link_aktiv;?>" title="Просмотр содержимого категории"><?php echo $row->active; ?></a></td>
				<td align="center"><?php echo $row->trash; ?></td>
<?php
			} else {
?>
				<td>&nbsp;</td>
<?php
			}
			$k = 1 - $k;
?>
				<td align="center"><?php echo $row->id; ?></td>
			</tr>
<?php
		}
?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		<input type="hidden" name="option" value="com_categories" />
		<input type="hidden" name="section" value="<?php echo $section; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="chosen" value="" />
		<input type="hidden" name="act" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="type" value="<?php echo $type; ?>" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}

	/**
	* Writes the edit form for new and existing categories
	* @param mosCategory The category object
	* @param string
	* @param array
	*/
	function edit(&$row,&$lists,$redirect,$menus) {
		if($row->image == "") {
			$row->image = 'blank.png';
		}

		if($redirect == 'content') {
			$component = 'Содержимое';
		} else {
			$component = ucfirst(substr($redirect,4));
			if($redirect == 'com_contact_details') {
				$component = 'Контакт';
			}
		}
		mosMakeHtmlSafe($row,ENT_QUOTES,'description');
?>
	<script language="javascript" type="text/javascript">
		function ch_apply(){
			SRAX.get('tb-apply').className='tb-load';
			<?php getEditorContents('editor1','description'); ?>
			dax({
				url: 'ajax.index.php?option=com_mambots&task=apply',
				id:'publ-1',
				method:'post',
				form: 'adminForm',
				callback:
					function(resp){
						log('Получен ответ: ' + resp.responseText);
						mess_cool(resp.responseText);
						SRAX.get('tb-apply').className='tb-apply';
			}});
		}
		function submitbutton(pressbutton, section) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			if ( pressbutton == 'menulink' ) {
				if ( form.menuselect.value == "" ) {
					alert( "Пожалуйста, выберите меню" );
					return;
				} else if ( form.link_type.value == "" ) {
					alert( "Пожалуйста, выберите тип меню" );
					return;
				} else if ( form.link_name.value == "" ) {
					alert( "Пожалуйста, введите название для этого пункта меню" );
					return;
				}
			}

			if ( form.name.value == "" ) {
				alert("Категория должна иметь название");
			} else if (form.title.value ==""){
				alert("Введите заголовок категории");
			} else {
				<?php getEditorContents('editor1','description'); ?>
				submitform(pressbutton);
			}
		}
	</script>
		<form action="index2.php" method="post" name="adminForm" id="adminForm">
		<table class="adminheading">
		<tr>
			<th class="categories">
			Категория:
			<small><?php echo $row->id?'Редактирование':'Новая'; ?></small>
			<small><small>
			[ <?php echo $component; ?>: <?php echo stripslashes($row->name); ?> ]
			</small></small>
			</th>
		</tr>
		</table>
		<table width="100%">
		<tr>
			<td valign="top" width="60%">
				<table class="adminform">
				<tr>
					<th colspan="3">Свойства категории</th>
				<tr>
				<tr>
					<td>Заголовок категории (Title):</td>
					<td colspan="2">
						<input class="text_area" type="text" name="title" value="<?php echo stripslashes($row->title); ?>" size="50" maxlength="50" title="Короткое имя для меню" />
					</td>
				</tr>
				<tr>
					<td>Название категории (Name):</td>
					<td colspan="2">
						<input class="text_area" type="text" name="name" value="<?php echo stripslashes($row->name); ?>" size="50" maxlength="255" title="Длинное название, отображаемое в заголовках" />
					</td>
				</tr>
				<tr>
					<td>Раздел:</td>
					<td colspan="2"><?php echo $lists['section']; ?></td>
				</tr>
				<tr>
					<td>Порядок расположения:</td>
					<td colspan="2"><?php echo $lists['ordering']; ?></td>
				</tr>
				<tr>
					<td>Изображение:</td>
					<td><?php echo $lists['image']; ?></td>
					<td rowspan="5" width="50%">
					<script language="javascript" type="text/javascript">
					if (document.forms[0].image.options.value!=''){
						jsimg='../images/stories/' + getSelectedValue( 'adminForm', 'image' );
					} else {
						jsimg='../images/M_images/blank.png';
					}
					document.write('<img src=' + jsimg + ' name="imagelib" width="100" height="100" border="2" alt="Предпросмотр" />');
					</script>
					</td>
				</tr>
				<tr>
					<td>Расположение изображения:</td>
					<td><?php echo $lists['image_position']; ?></td>
				</tr>
				<tr>
					<td>Уровень доступа:</td>
					<td><?php echo $lists['access']; ?></td>
				</tr>
				<tr>
					<td>Опубликовано:</td>
					<td><?php echo $lists['published']; ?></td>
				</tr>
				<tr>
					<td valign="top" colspan="2">Описание:</td>
				</tr>
				<tr>
					<td colspan="3">
<?php
	// parameters : areaname, content, hidden field, width, height, rows, cols
	editorArea('editor1',$row->description,'description','100%;','300','60','20');
?>
					</td>
				</tr>
				</table>
			</td>
			<td valign="top" width="40%">
			<?php
		if($row->id > 0) {
?>
				<table class="adminform">
				<tr>
					<th colspan="2">Пункт меню</th>
				<tr>
				<tr>
					<td colspan="2">Создание нового пункта в выбранном вами меню.
					<br /><br />
					</td>
				<tr>
				<tr>
					<td valign="top" width="120">Выберите меню:</td>
					<td><?php echo $lists['menuselect']; ?></td>
				<tr>
				<tr>
					<td valign="top" width="120">Выберите тип меню:</td>
					<td><?php echo $lists['link_type']; ?></td>
				<tr>
				<tr>
					<td valign="top" width="120">Название пункта меню:</td>
					<td>
						<input type="text" name="link_name" class="inputbox" value="" size="25" />
					</td>
				<tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input name="menu_link" type="button" class="button" value="Создать пункт меню" onClick="submitbutton('menulink');" />
					</td>
				<tr>
				<tr>
					<th colspan="2">Существующие ссылки меню</th>
				</tr>
<?php
			if($menus == null) {
?>
				<tr>
					<td colspan="2">Отсутствуют</td>
				</tr>
<?php
			} else {
				mosCommonHTML::menuLinksSecCat($menus);
			}
?>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
			</table>
<?php
		} else {
?>
			<table class="adminform" width="40%">
				<tr>
					<th>&nbsp;</th>
				</tr>
				<tr>
					<td>Связь с меню будет доступна после сохранения</td>
				</tr>
			</table>
<?php
		}
		// content
		if($row->section > 0 || $row->section == 'content') {
?>
			<br />
			<table class="adminform">
				<tr>
					<th colspan="2">Каталоги изображений (MOSImage)</th>
				</tr>
				<tr>
					<td colspan="2"><?php echo $lists['folders']; ?></td>
				<tr>
			</table>
			<?php
		}
?>
			</td>
		</tr>
		</table>
		<input type="hidden" name="option" value="com_categories" />
		<input type="hidden" name="oldtitle" value="<?php echo $row->title; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="sectionid" value="<?php echo $row->section; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}


	/**
	* Form to select Section to move Category to
	*/
	function moveCategorySelect($option,$cid,$SectionList,$items,$sectionOld,$contents,
		$redirect) {
?>
		<form action="index2.php" method="post" name="adminForm">
		<br />
		<table class="adminheading">
		<tr>
			<th class="categories">
			Перемещение категорий
			</th>
		</tr>
		</table>

		<br />
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if (!getSelectedValue( 'adminForm', 'sectionmove' )) {
				alert( "Пожалуйста, выберите раздел для перемещаемой категории" );
			} else {
				submitform( pressbutton );
			}
		}
		</script>
		<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
			<strong>Переместить в раздел:</strong>
			<br />
			<?php echo $SectionList ?>
			<br /><br />
			</td>
			<td align="left" valign="top" width="20%">
			<strong>Перемещаемые категории:</strong>
			<br />
			<?php
		echo "<ol>";
		foreach($items as $item) {
			echo "<li>".$item->name."</li>";
		}
		echo "</ol>";
?>
			</td>
			<td valign="top" width="20%">
			<strong>Перемещаемые объекты содержимого:</strong>
			<br />
			<?php
		echo "<ol>";
		foreach($contents as $content) {
			echo "<li>".$content->title."</li>";
		}
		echo "</ol>";
?>
			</td>
			<td valign="top">
			В выбранный раздел будут перемещены все
			<br />
			 перечисленные категории и всё 
			<br />
			перечисленное содержимое этих категорий.
			</td>.
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="section" value="<?php echo $sectionOld; ?>" />
		<input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		<input type="hidden" name="task" value="" />
		<?php
		foreach($cid as $id) {
			echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
?>
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}


	/**
	* Form to select Section to copy Category to
	*/
	function copyCategorySelect($option,$cid,$SectionList,$items,$sectionOld,$contents,
		$redirect) {
?>
		<form action="index2.php" method="post" name="adminForm">
		<br />
		<table class="adminheading">
		<tr>
			<th class="categories">
			Копирование категорий
			</th>
		</tr>
		</table>

		<br />
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if (!getSelectedValue( 'adminForm', 'sectionmove' )) {
				alert( "Пожалуйста, выберите раздел для копируемой категории"" );
			} else {
				submitform( pressbutton );
			}
		}
		</script>
		<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
			<strong>Копировать в раздел:</strong>
			<br />
			<?php echo $SectionList ?>
			<br /><br />
			</td>
			<td align="left" valign="top" width="20%">
			<strong>Копируемые категории:</strong>
			<br />
			<?php
		echo "<ol>";
		foreach($items as $item) {
			echo "<li>".$item->name."</li>";
		}
		echo "</ol>";
?>
			</td>
			<td valign="top" width="20%">
			<strong>Копируемое содержимое категории:</strong>
			<br />
			<?php
		echo "<ol>";
		foreach($contents as $content) {
			echo "<li>".$content->title."</li>";
			echo "\n <input type=\"hidden\" name=\"item[]\" value=\"$content->id\" />";
		}
		echo "</ol>";
?>
			</td>
			<td valign="top">
			В выбранный раздел будут скопированы все
			<br />
			перечисленные категории и всё 
			<br />
			перечисленное содержимое этих категорий.
			</td>.
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="section" value="<?php echo $sectionOld; ?>" />
		<input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		<input type="hidden" name="task" value="" />
		<?php
		foreach($cid as $id) {
			echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
?>
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}

}
?>

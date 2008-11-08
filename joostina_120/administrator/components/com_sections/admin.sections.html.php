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
* @subpackage Sections
*/
class sections_html {
	/**
	* Writes a list of the categories for a section
	* @param array An array of category objects
	* @param string The name of the category section
	*/
	function show(&$rows,$scope,$myid,&$pageNav,$option) {
		global $my;
		mosCommonHTML::loadOverlib();
?>
	<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
			<tr>
				<th class="sections">Разделы содержимого</th>
			</tr>
		</table>
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th class="title">Название раздела</th>
			<th width="10%">Опубликовано</th>
			<th colspan="2" width="5%">Сортировка</th>
			<th width="2%">Порядок</th>
			<th width="1%">
				<a href="javascript: saveorder( <?php echo count($rows) - 1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="Сохранить порядок" /></a>
			</th>
			<th width="10%">Доступ</th>
			<th width="10%" class="jtd_nowrap">Категорий</th>
			<th width="10%" class="jtd_nowrap">Активных</th>
			<th width="10%" class="jtd_nowrap">В корзине</th>
			<th width="5%" class="jtd_nowrap">ID</th>
		</tr>
		<?php
		$k = 0;
		for($i = 0,$n = count($rows); $i < $n; $i++) {
			$row = &$rows[$i];
			mosMakeHtmlSafe($row);
			$link		= 'index2.php?option=com_sections&scope=content&task=editA&hidemainmenu=1&id='.$row->id;
			$link_aktiv = 'index2.php?option=com_content&sectionid='.$row->id;
			$link_aktiv_cat = 'index2.php?option=com_categories&section='.$row->id;
			$access		= mosCommonHTML::AccessProcessing($row,$i,1);
			$checked	= mosCommonHTML::CheckedOutProcessing($row,$i);
			$img		= $row->published ? 'publish_g.png' : 'publish_x.png';
?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20" align="right"><?php echo $pageNav->rowNumber($i); ?></td>
				<td width="20"><?php echo $checked; ?></td>
				<td width="35%" align="left">
<?php
			if($row->checked_out && ($row->checked_out != $my->id)) {
				echo $row->name." ( ".$row->title." )";
			} else {
?>
					<a href="<?php echo $link; ?>"><?php echo $row->name." ( ".$row->title." )"; ?></a>
<?php
			}
?>
				</td>
				<td align="center" <?php echo ($row->checked_out && ($row->checked_out != $my->id)) ? null : 'onclick="ch_publ('.$row->id.',\'com_sections\');" class="td-state"';?>>
<?php
				if ( !$row->checked_out ){
				?>
					<img class="img-mini-state" src="images/<?php echo $img;?>" id="img-pub-<?php echo $row->id;?>" alt="Публикация" />
<?php
				}else{
?>
					<img class="img-mini-state" src="images/<?php echo $img;?>" id="img-pub-<?php echo $row->id;?>" alt="Смена статуса публикации недоступна"/>
<?php
				}
?>
				</td>
				<td align="center"><?php echo $pageNav->orderUpIcon($i); ?></td>
				<td align="center"><?php echo $pageNav->orderDownIcon($i,$n); ?></td>
				<td align="center" colspan="2">
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
				</td>
				<td align="center" id="acc-id-<?php echo $row->id;?>"><?php echo $access; ?></td>
				<td align="center"><a href="<?php echo $link_aktiv_cat;?>" title="Просмотр категорий раздела"><?php echo $row->categories; ?></a></td>
				<td align="center"><a href="<?php echo $link_aktiv;?>" title="Просмотр содержимого раздела"><?php echo $row->active; ?></a></td>
				<td align="center"><?php echo $row->trash; ?></td>
				<td align="center"><?php echo $row->id; ?></td>
<?php
			$k = 1 - $k;
?>
			</tr>
<?php
		}
?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="scope" value="<?php echo $scope; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="chosen" value="" />
		<input type="hidden" name="act" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}

	/**
	* Writes the edit form for new and existing categories
	*
	* A new record is defined when <var>$row</var> is passed with the <var>id</var>
	* property set to 0.  Note that the <var>section</var> property <b>must</b> be defined
	* even for a new record.
	* @param mosCategory The category object
	* @param string The html for the image list select list
	* @param string The html for the image position select list
	* @param string The html for the ordering list
	* @param string The html for the groups select list
	*/
	function edit(&$row,$option,&$lists,&$menus) {
		global $mosConfig_live_site;

		if($row->name != '') {
			$name = $row->name;
		} else {
			$name = "Новый раздел";
		}
		if($row->image == "") {
			$row->image = 'blank.png';
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
		function submitbutton(pressbutton) {
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
					alert( "Пожалуйста, введите имя для этого пункта меню" );
					return;
				}
			}
			if (form.name.value == ""){
				alert("Раздел должен иметь название");
			} else if (form.title.value ==""){
				alert("Раздел должен иметь заголовок");
			} else {
				<?php getEditorContents('editor1','description'); ?>
				submitform(pressbutton);
			}
		}
		</script>
		<form action="index2.php" method="post" name="adminForm" id="adminForm">
		<table class="adminheading">
		<tr>
			<th class="sections">
			Раздел:
			<small><?php echo $row->id?'Редактирование':'Новый'; ?></small>
			<small><small>
			[ <?php echo stripslashes($name); ?> ]
			</small></small>
			</th>
		</tr>
		</table>
		<table width="100%">
		<tr>
			<td valign="top" width="60%">
				<table class="adminform">
				<tr>
					<th colspan="3">Детали раздела</th>
				<tr>
				<tr>
					<td width="150">Используется в:</td>
					<td width="85%" colspan="2"><strong><?php echo $row->scope; ?></strong></td>
				</tr>
				<tr>
					<td>Заголовок:</td>
					<td colspan="2">
					<input class="text_area" type="text" name="title" value="<?php echo $row->title; ?>" size="50" maxlength="50" title="Короткое имя для меню" />
					</td>
				</tr>
				<tr>
					<td>Название <?php echo (isset($row->section)?"категории":"раздела"); ?>:</td>
					<td colspan="2">
						<input class="text_area" type="text" name="name" value="<?php echo $row->name; ?>" size="50" maxlength="255" title="Длинное название, отображаемое в заголовках" />
					</td>
				</tr>
				<tr>
					<td>Порядок отображения:</td>
					<td colspan="2"><?php echo $lists['ordering']; ?></td>
				</tr>
				<tr>
					<td>Изображение:</td>
					<td><?php echo $lists['image']; ?></td>
					<td rowspan="5" width="50%">
<?php
		$path = $mosConfig_live_site."/images/";
		if($row->image != "blank.png") {
			$path .= "stories/";
		}
?>
						<img src="<?php echo $path.$row->image; ?>" name="imagelib" width="80" height="80" border="2" alt="Предпросмотр" />
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
			<td valign="top">
<?php
		if($row->id > 0) {
?>
				<table class="adminform">
				<tr>
					<th colspan="2">Связь с меню</th>
				<tr>
				<tr>
					<td colspan="2">Эта функция создаст новый пункт в выбранном вами меню<br /><br /></td>
				<tr>
				<tr>
					<td valign="top" width="100px">Выберите меню</td>
					<td><?php echo $lists['menuselect']; ?></td>
				<tr>
				<tr>
					<td valign="top" width="100px">Выберите тип меню</td>
					<td><?php echo $lists['link_type']; ?></td>
				<tr>
				<tr>
					<td valign="top" width="100px">Название пункта меню</td>
					<td><input type="text" name="link_name" class="inputbox" value="" size="25" /></td>
				<tr>
				<tr>
					<td></td>
					<td>
						<input name="menu_link" type="button" class="button" value="Создать пункт меню" onClick="submitbutton('menulink');" />
					</td>
				<tr>
				<tr>
					<th colspan="2">Существующие связи с меню</th>
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
					<td colspan="2">
					</td>
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
					<td>Связи с меню будут доступны после сохранения</td>
				</tr>
			</table>
<?php
		}
?>
				<br />
				<table class="adminform">
				<tr>
					<th colspan="2">Каталоги изображений (MOSImage)</th>
				</tr>
				<tr>
					<td colspan="2"><?php echo $lists['folders']; ?></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="scope" value="<?php echo $row->scope; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="oldtitle" value="<?php echo $row->title; ?>" />
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
<?php
	}


	/**
	* Form to select Section to copy Category to
	*/
	function copySectionSelect($option,$cid,$categories,$contents,$section) {
?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="sections">Копирование раздела</th>
		</tr>
		</table>
		<br />
		<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
			<strong>Название копии раздела:</strong>
			<br />
			<input class="text_area" type="text" name="title" value="" size="35" maxlength="50" title="Название нового раздела" />
			<br /><br />
			</td>
			<td align="left" valign="top" width="20%">
			<strong>Копируемые категории:</strong>
			<br />
<?php
		echo "<ol>";
		foreach($categories as $category) {
			echo "<li>".$category->name."</li>";
			echo "\n <input type=\"hidden\" name=\"category[]\" value=\"$category->id\" />";
		}
		echo "</ol>";
?>
			</td>
			<td valign="top" width="20%">
			<strong>Копируемые объекты содержимого:</strong>
			<br />
<?php
		echo "<ol>";
		foreach($contents as $content) {
			echo "<li>".$content->title."</li>";
			echo "\n <input type=\"hidden\" name=\"content[]\" value=\"$content->id\" />";
		}
		echo "</ol>";
?>
			</td>
			<td valign="top">
			Во вновь созданный раздел будут 
			<br />
			скопированы перечисленные категории 
			<br />
			и все перечисленные объекты
			<br /> 
			содержимого категорий.
			</td>.
		</tr>
		</table>
		<br /><br />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="section" value="<?php echo $section; ?>" />
		<input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="scope" value="content" />
<?php
		foreach($cid as $id) {
			echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
?>
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
<?php
	}

	function mass_add($option,$sec,$cat){
?>
	<script language="javascript" type="text/javascript">
	function sectionclick(){
		SRAX.get('catlist').style.display = 'none';
		SRAX.get('seclist').style.display = 'none';
	}
	function catclick(){
		SRAX.get('seclist').style.display = 'block';
		SRAX.get('catlist').style.display = 'none';
	}
	function conclick(){
		SRAX.get('seclist').style.display = 'none';
		SRAX.get('catlist').style.display = 'block';
	}
	</script>
	<form action="index2.php" method="post" name="adminForm" id="adminForm">
	<table class="adminheading">
		<tr>
			<th class="massadd">Массовое добавление</th>
		</tr>
	</table>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tbody>
		<tr valign="top">
			<td width="60%">
				<table width="100%" class="adminform">
					<tbody>
						<tr>
							<th>Добавить</th>
						</tr>
						<tr>
							<td>
								<textarea style="width: 100%; height: 400px;" rows="40" cols="110" id="addcontent" name="addcontent"/></textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
			<td width="40%" valign="top" align="right">
				<table width="100%" class="adminform">
				<tbody>
					<tr>
						<th colspan="2">Детали</th>
					</tr>
					<tr>
						<td colspan="2" align="left">Каждая новая категория / раздел должны начинаться с новой строки</td>
					</tr>
					<tr>
						<td width="150" align="left" valign="top">Добавить как:</td>
						<td>
							<input onclick="return sectionclick();" type="radio" class="inputbox" checked="checked" value="0" id="secid" name="type"/>
							<label onclick="return sectionclick();" for="secid">Разделы</label>
							<br />
							<input onclick="return catclick();" type="radio" class="inputbox" value="1" id="catid" name="type"/>
							<label onclick="return catclick();" for="catid">Категории</label>
							<br />
							<input onclick="return conclick();" type="radio" class="inputbox" value="2" id="conid" name="type"/>
							<label onclick="return conclick();" for="conid">Содержимое</label>
						</td>
					</tr>
					<tr>
						<td>Опубликовано:</td>
						<td>
							<input type="radio" class="inputbox" value="0" id="published0" name="published"/>
							<label for="published0">Нет</label>
							<input type="radio" class="inputbox" checked="checked" value="1" id="published1" name="published"/>
							<label for="published1">Да</label>
						</td>
					</tr>
					<tr>
						<td valign="top" align="left">Уровень доступа:</td>
						<td>
							<select size="4" class="inputbox" name="access" style="width: 98%;">
								<option selected="selected" value="0">Общий</option>
								<option value="1">Участники</option>
								<option value="2">Специальный</option>
							</select>
							<br />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="seclist" style="display: none;">Категории буду принадлежать разделу:<br /><?php echo $sec;?></div>
							<div id="catlist" style="display: none;">Содержимое будет принадлежать категории:<br /><?php echo $cat;?></div>
						</td>
					</tr>
				</tbody>
				</table>
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="scope" value="content" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
	</form>
<?php
	}
	function mas_result($type,$results,$text){
?>
	<form action="index2.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="com_sections" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="scope" value="content" />
	<input type="hidden" name="boxchecked" value="1" />
	</form>
	<table class="adminheading">
		<tr>
			<th class="massadd"><?php echo $text; ?></th>
		</tr>
	</table>
	<table id="adminlist" class="adminlist">
		<tbody>
		<tr>
			<th align="left">Результаты</th>
		</tr>
<?php
		$k = 0;
		foreach($results as $result) {
			echo '<tr class="row'.$k.'"><td width="1%">'.$result.'</td>'."\n</tr>\n";
			$k = 1 - $k;
		}
?>
		</tbody>
	</table>
<?php
	}

}
?>

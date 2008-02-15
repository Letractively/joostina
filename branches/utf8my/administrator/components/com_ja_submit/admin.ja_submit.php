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
require(dirname(__FILE__).'/../../die.php');

switch ($task) {
	case "settings":
		showConfig( $option );
		break;
	case "savesettings":
		saveConfig ($option);
		break;
	default:
		showConfig( $option );
	break;
}

function showConfig( $option ) {
	global $mosConfig_absolute_path,$database;
	require($mosConfig_absolute_path."/administrator/components/com_ja_submit/settings.php");
	$langfolder=$mosConfig_absolute_path.'/components/com_ja_submit/language/';
	$d = dir($langfolder);
	while (false !== ($entry = $d->read())){
		if ($entry!="."&&$entry!=".."){
			$listfiles[]=$entry;
		}
	}
?>
	<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		submitform( pressbutton );
	}
	</script>
	<table class="adminheading"><tr><th class="config jtd_nowrap" class="config">Отправка новостей с фронта сайта</th></tr></table>
	<form action="index2.php" method="POST" name="adminForm">
<?php $tabs = new mosTabs(1);
$tabs->startPane("configPane");
$tabs->startTab("Основные настройки","sett-page");
 ?>
<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td width="19%" align="right">Разрешено:</td>
		<td align="left">
			<input type="radio" name="enabled" value="1" <?php if ($H_enabled) echo "checked"; ?>/>Включить
			<input type="radio" name="enabled" value="0" <?php if (!$H_enabled) echo "checked"; ?>/>Отключить
		</td>
		<td width="53%" align="left">Включение/Отключение системы отправки публикаций с фронта сайта</td>
	</tr>
	<tr>
		<td width="19%" align="right">Разрешено гостям:</td>
		<td align="left">
			<input type="radio" name="guest" value="1" <?php if ($H_guest) echo "checked"; ?>/>Включить
			<input type="radio" name="guest" value="0" <?php if (!$H_guest) echo "checked"; ?>/>Отключить
		</td>
		<td width="53%" align="left">Разрешить неавторизованным пользователям присылать новости с фронта сайта</td>
	</tr>
	<tr>
		<td width="19%" align="right">Визуальная проверка - антиспам:</td>
		<td align="left">
			<input type="radio" name="captcha" value="1" <?php if ($H_captcha) echo "checked"; ?>/>Включить
			<input type="radio" name="captcha" value="0" <?php if (!$H_captcha) echo "checked"; ?>/>Отключить
		</td>
		<td width="53%" align="left">Включение/Отключение использование captcha при отправке новостей.</td>
	</tr>
	<tr>
		<td width="19%" align="right">Заголовок:</td>
		<td align="left">
			<input type="radio" name="title" value="1" <?php if ($H_title) echo "checked"; ?>/>Включить
			<input type="radio" name="title" value="0" <?php if (!$H_title) echo "checked"; ?>/>Отключить</td>
			<td width="53%" align="left">Включение/отключение заголовка компонента</td>
	</tr>
	<tr>
		<td width="19%" align="right">Визуальный редактор:</td>
		<td align="left">
			<input type="radio" name="editor" value="1" <?php if ($H_editor) echo "checked"; ?>/>Включить
			<input type="radio" name="editor" value="0" <?php if (!$H_editor) echo "checked"; ?>/>Отключить</td>
			<td width="53%" align="left">Включение/отключение загрузки визуального редактора</td>
	</tr>
	<tr><td width="19%" align="right">Поле fulltext:</td>
		<td align="left">
			<input type="radio" name="fulltext" value="1" <?php if ($H_fulltext) echo "checked"; ?>/>Включить
			<input type="radio" name="fulltext" value="0" <?php if (!$H_fulltext) echo "checked"; ?>/>Отключить</td>
			<td align="left" width="53%">Включение/отключение поля fulltext (также отключается загрузка второго изображения)</td>
	</tr>
	<tr>
		<td width="19%" align="right">Тэг для перехода на следующую строчку:</td>
		<td align="left">
			<input type="radio" name="tag" value="1" <?php if ($H_tag) echo "checked"; ?>/>br
			<input type="radio" name="tag" value="0" <?php if (!$H_tag) echo "checked"; ?>/>p</td>

			<td align="left" width="53%">Выберите тэг, который будет автоматически подставляться в текст при переходе на следующую строчку.</td>
	</tr>
	<tr>
		<td width="19%" align="right">Скрыть поля "имя" и "e-mail" от зарегистрированных:</td>
		<td align="left">
			<input type="radio" name="hiddenfield" value="1" <?php if ($H_hiddenfield) echo "checked"; ?>/>Включить
			<input type="radio" name="hiddenfield" value="0" <?php if (!$H_hiddenfield) echo "checked"; ?>/>Отключить			</td>
			<td align="left" width="53%">Включение/отключение полей "имя" и "e-mail" от зарегистрированных пользователей. Если выключено, то пользователь не сможет изменять данные.</td>
	</tr>
	<tr>
		<td width="19%" align="right">Показывать &quot;Правила для авторов&quot;:</td>
		<td align="left">
			<input type="radio" name="rules" value="1" <?php if ($H_rules) echo "checked"; ?>/>Включить
			<input type="radio" name="rules" value="0" <?php if (!$H_rules) echo "checked"; ?>/>Отключить			</td>
			<td align="left" width="53%">Включение &quot;Правил для авторов&quot; в форму отправки публикации. Свои собственные правила Вы сможете создать, редактируя языковой файл по ссылке &quot;Языки&quot; из меню компонента.</td>
	</tr>
	<tr>
		<td align="right">Сообщать на e-mail:</td>
		<td align="left">
			<input class="inputbox" type="text" style="width: 200px;" name="notify_email" value="<?php echo $H_notify_email; ?>"/>	  </td>
		<td align="left">Оставьте поле пустым, чтобы не получать сообщений о новых публикациях. Используйте точку-с-запятой &quot;;&quot; между несколькими адресами e-mail, если хотите получать сообщения на несколько разных адресов.</td>
	</tr>
</table>
<?php
$tabs->endTab();
$tabs->startTab("Настройка изображений","image-page");
?>
<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td align="right">Разрешённые форматы изображений </td>
		<td align="left">
			<input class="inputbox" type="text" style="width: 200px;" name="allowable_images" value="<?php echo $H_allowable_images; ?>"/>		</td>
		<td align="left">Вводить разрешения файлов через запятую (,) </td>
	</tr>
	<tr>
		<td align="right">Изменять размеры изображений </td>
		<td align="left"><input type="radio" name="enableResize" value="1" <?php if ($H_enable_resize) echo "checked"; ?>/>
		Включить
		<input class="inputbox" type="radio" name="enableResize" value="0" <?php if (!$H_enable_resize) echo "checked"; ?>/>
		Отключить </td>
		<td align="left">Включение/отключение автоматического изменения размеров изображения в присылаемых публикациях</td>
	</tr>
	<tr>
		<td align="right" width="19%">Позиция картинки:</td>
		<td align="left">
		<select class="inputbox" type="list" name="image_position">
			<option><?php echo $H_image_position ?></option>
			<option value="left">left</option>
			<option value="center">center</option>
			<option value="right">right</option>
		</select>
		</td>
		<td align="left" width="53%">Выбор позиции отображения картинки после загрузки</td>
	</tr>
	<tr>
		<td align="right" valign="top">Каталог загрузки изображений относительно <b>"images/stories/"</b>:</td>
		<td align="left">
			<input class="inputbox" type="text" style="width: 200px;" name="image_upload" value="<?php echo $H_image_upload ?>"/> 	</td>
			<td align="left">Укажите каталог для загрузки изображений <b>без открывающего и закрывающего слеша (/). Внимание! Картинки будут правильно отображаться только, если ваши папки расположены в директории /stories/</b>.</td>
	</tr>
	<tr>
		<td align="right">Изменить ширину до*:</td>
		<td align="left">
			<input class="inputbox" type="text" style="width: 60px;" name="iwidth" value="<?php echo $H_width; ?>"/> пикселей			</td>
			<td align="left">установка ширины загруженного изображения.</td>
	</tr>
	<tr>
		<td align="right" valign="top">Изменить высоту до*:</td>
		<td align="left">
			<input class="inputbox" type="text" style="width: 60px;" name="iheight" value="<?php echo $H_height; ?>"/> пикселей			</td>
			<td align="left">установка высоты загруженного изображения.</td>
	</tr>
	<tr>
		<td align="right" valign="top">Максимальный размер файла изображения*:</td>
		<td align="left">
			<input class="inputbox" type="text" style="width: 60px;" name="isize" value="<?php echo $H_maxsize; ?>"/> байт			</td>
			<td align="left">Максимальный размер файла изображения, разрешённый для загрузки на сайт.</td>
	</tr>
</table>
<?php
$tabs->endTab();
$tabs->startTab("Настройка публикации","publ-page");
 ?>
<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td valign="top" align="right">Сохранять новости от имени:</td>
		<td align="left" style="width:40%;">
		<?php
			$database->setQuery( "SELECT id AS value, name AS text FROM #__users ORDER BY username" );
			$groups = $database->loadObjectList();
			echo mosHTML::selectList( $groups, 'id_users', ''.' class="inputbox" size="6" style="width: 100%;"', 'value', 'text', $H_id_users );

		?>
			</td>
			<td align="left">Выберите, от чьего имени сохранять новости присланные неавторизованными пользователями.</td>
	</tr>
	<tr>
		<td valign="top" align="right">Доступные категории:</td>
		<td align="left"><?php
			$query = "SELECT c.id AS `id`, c.section AS `section`, CONCAT_WS( ' / ', s.title, c.title) AS `name`"
			. "\n FROM #__sections AS s"
			. "\n INNER JOIN #__categories AS c ON c.section = s.id"
			. "\n WHERE s.scope = 'content'"
			. "\n ORDER BY s.name,c.name";
			$database->setQuery( $query );
			$categories = $database->loadObjectList() ;
			//Select selected categories
			$listId=array();
			$listId=explode(",",$H_avaiCategories) ;
			foreach ($listId As $key)
			{
				$keyObject=new stdClass();
				$keyObject->id=$key;
				$listKeys[]=$keyObject;
			}
			$lists['catid'] = mosHTML::selectList( $categories, 'listcatid[]', 'class="inputbox" size="10" multiple="multiple"  style="width: 100%;"', 'id', 'name',$listKeys );
			echo $lists['catid'];?></td>
		<td valign="top" align="left">Выберите категории, в которые пользователь сможет отправлять публикации</td>
	</tr>
	<tr>
		<td valign="top" align="right">Группы для автопубликации: </td>
		<td align="left">
		<?php
			$query="SELECT name FROM #__usertypes";
			$database->setQuery( $query );
			$usertypes1 = $database->loadResultArray() ;
			$query="SELECT name FROM #__groups";
			$database->setQuery( $query );
			$usertypes2 = $database->loadResultArray() ;
			$usertypes=array();
			$usertypes=array_merge($usertypes1,$usertypes2);
			$selectedTypes=array();
			$selectedTypes=explode(",",$H_auto_approve_groups);
			$userlist='<select name="listusertypes[]" id="listusertypes" class="inputbox" size="10" multiple="multiple" style="width: 100%;">';
			if ($H_auto_approve_groups==""){
				$userlist.="<option value='none' selected='selected'>Отсутствуют</option>";
			}else{
				$userlist.="<option value='none'>Отсутствуют</option>";
			}
			foreach ($usertypes AS $usertype){
			if (in_array($usertype,$selectedTypes)){
					$userlist.="<option value='$usertype' id='$usertype' selected='selected'>$usertype</option>";
				}else{
					$userlist.="<option value='$usertype' id='$usertype'>$usertype</option>";
				}
		}
		$userlist.="</select>";
		echo $userlist;
		?>
		</td>
		<td valign="top" align="left"><p>Выберите группы пользователей, для которых будет разрешена АВТОМАТИЧЕСКАЯ публикация отправляемых сообщений</p>
			<p>Выберите &quot;Отсутствуют&quot; если Вы НЕ хотите разрешать АВТОМАТИЧЕСКУЮ публикацию сообщений</p></td>
	</tr>
	<tr>
		<td valign="top" align="right">Категории для автопубликации:</td>
		<td align="left">
		<?php
			$categories=array();
			$query = "SELECT c.id AS `id`, c.section AS `section`, CONCAT_WS( ' / ', s.title, c.title) AS `name`"
			. "\n FROM #__sections AS s"
			. "\n INNER JOIN #__categories AS c ON c.section = s.id"
			. "\n WHERE s.scope = 'content'"
			. "\n ORDER BY s.name,c.name";
			$database->setQuery( $query );
			$categories = $database->loadObjectList() ;
			
			//Select selected categories 
			$listId=array();
			$listId=explode(",",$H_auto_approve_categories) ;
			
			$catlist='<select name="listautocat[]" id="listautocat" class="inputbox" size="8" multiple="multiple" style="width: 100%;">';
			if ($H_auto_approve_categories==""){
				$catlist.="<option value='none' selected='selected'>Не разрешать</option>";
			}else{
				$catlist.="<option value='none'>Отсутствуют</option>";
			}
			foreach ($categories As $category){
				if (in_array($category->id,$listId)){
					$catlist.="<option value='".$category->id."' selected='selected'>".$category->name."</option>";
				}else{
					$catlist.="<option value='".$category->id."'>".$category->name."</option>";
				}
			}
			echo $catlist;
			
		?>
		</td>
		<td align="left" valign="top"><p>Выберите категории, в которых будет разрешена АВТОМАТИЧЕСКАЯ публикация сообщений</p>
		<p>Выберите &quot;Не разрешать&quot; если Вы НЕ хотите разрешать АВТОМАТИЧЕСКУЮ публикацию сообщений</p></td>
	</tr>
</table>
<?php
$tabs->endTab();
$tabs->startTab("Другие настройки","our-page");
 ?>
<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td valign="top" align="right">Язык:</td>
		<td align="left" style="width:40%;">
			<select class="inputbox" name="filelanguage"  style="width: 100%;">
		<?php
			if (count($listfiles)>0){
				foreach ($listfiles As $filename){
					if ($filename!="default.php"){
						if ($H_language==$filename){
							echo "<option name=$filename selected>$filename</option>";
						}else{
							echo "<option name=$filename>$filename</option>";
						}
					}
				}
			}
		?>
			</select>
			</td>
		<td valign="top" align="left">Выберите язык компонента</td>
	</tr>
	<tr>
		<td valign="top" align="right">Визуальный редактор &quot;по умолчанию&quot;: </td>
		<td align="left">
	<?php
		global $mosConfig_editor;
		$query = "SELECT element AS value, name AS text"
		. "\n FROM #__mambots"
		. "\n WHERE folder = 'editors'"
		. "\n AND published = 1"
		. "\n ORDER BY ordering, name"
		;
		$database->setQuery( $query );
		$edits = $database->loadObjectList();
		$listseditor = mosHTML::selectList( $edits, 'config_editor', 'class="inputbox" size="1" style="width: 100%;"', 'value', 'text', $mosConfig_editor);
		echo $listseditor;
	?></td>
		<td valign="top" align="left">Изменение WYSIWYG-редактора &quot;по умолчанию&quot; в этой опции приведёт к изменению &quot;редактора по умолчанию&quot; в &quot;Общих настройках&quot; для всего сайта</td>
	</tr>
	<input type="hidden" name="option" value="<?php echo $option; ?>">
	<input type="hidden" name="task" value="savesettings">
	</form>
</table>
<?php
$tabs->endTab();
$tabs->endPane();
echo '<br /><p><b>ВНИМАНИЕ:</b> все отмеченные звёздочкой <b>*</b> пункты необходимы для работы компонента.</p>';
}



function saveConfig ($option) {
	$configfile = "components/com_ja_submit/settings.php";
	@chmod ($configfile, 0666);
	$permission = is_writable($configfile);
	if (!$permission) {
		$mosmsg = "Config file not writeable!";
		mosRedirect("index2.php?option=$option&task=settings",$mosmsg);
		break;
	}

	$sectionid=$_POST['sectionid']?$_POST['sectionid']:0;
	$catid=$_POST['catid']?$_POST['catid']:0;
	$config  = "<?php\n";
	$config .= '$H_captcha = ' . $_POST['captcha'] . ";\n";
	$config .= '$H_enabled = ' . $_POST['enabled'] . ";\n";
	$config .= '$H_guest = ' . $_POST['guest'] . ";\n";
	$config .= '$H_title = ' . $_POST['title'] . ";\n";
	$config .= '$H_copyright ='."0;\n";
	$config .= '$H_editor = ' . $_POST['editor'] . ";\n";
	$config .= '$H_fulltext = ' . $_POST['fulltext'] . ";\n";
	$config .= '$H_tag = "' . $_POST['tag'] . "\";\n";
	$config .= '$H_hiddenfield = ' . $_POST['hiddenfield'] . ";\n";
	$config .= '$H_enable_resize = ' . $_POST['enableResize'] . ";\n";
	$config .= '$H_rules = '		 . $_POST['rules'] . ";\n";
	$config .= '$H_sectionid = '     . $sectionid . ";\n";
	$config .= '$H_catid = '		   . $catid. ";\n";
	$config .= '$H_id_users = "' . $_POST['id_users'] . "\";\n";
	$config .= '$H_allowable_images="'.$_POST['allowable_images'].'"'.";\n";
	$config .= '$H_notify_email = "' . $_POST['notify_email'] . "\";\n";
	//Fixed save config error with CZwidth & Czheight
	$config .= '$H_image_position = "' . $_POST['image_position'] . "\";\n";
	$config .= '$H_image_upload = "' . $_POST['image_upload'] . "\";\n";
	$config .= '$H_width = "'		  . $_POST['iwidth'] . "\";\n";
	$config .= '$H_height = "'		 . $_POST['iheight'] . "\";\n";
	//Added img maxsize config
	$config .= '$H_maxsize = "'		. $_POST['isize'] . "\";\n";
	//Added Security Images
	$config .= '$H_securityimage = "'. $_POST['securityimage'] . "\";\n";
	//language
	$config .= '$H_language ="'	   .$_POST['filelanguage']."\";\n";
	//Added available categories
	$config .= '$H_avaiCategories ="'  . implode(",",$_REQUEST['listcatid'])."\";\n";
	if (in_array("none",$_REQUEST['listusertypes'])){
		$config .= '$H_auto_approve_groups ="";'."\n";
	}else{
		$config .= '$H_auto_approve_groups ="'  . implode(",",$_REQUEST['listusertypes'])."\";\n";
	}
	if (in_array("none",$_REQUEST['listautocat'])){
		$config .= '$H_auto_approve_categories ="";'."\n";
	}else{
		$config .= '$H_auto_approve_categories ="'  . implode(",",$_REQUEST['listautocat'])."\";\n";
	}
	$config .= "?>";
	if ($fp = fopen("$configfile", "w")) {
		fputs($fp, $config, strlen($config));
		fclose ($fp);
	}
	global $mosConfig_absolute_path;
	$file = $mosConfig_absolute_path . '/configuration.php';
	$configcontent=file_get_contents($file);
	$pos1=strpos($configcontent,"'",strpos($configcontent,"mosConfig_editor")+1);
	$pos2=strpos($configcontent,"'",$pos1+1);
	$editorname=substr($configcontent,$pos1+1,$pos2-$pos1-1);
	$configcontent=str_replace($editorname,$_POST['config_editor'],$configcontent);
	if ( $fp = fopen($file, 'w') ) {
		fputs($fp, $configcontent, strlen($configcontent));
		fclose($fp);
	}
	mosRedirect("index2.php?option=$option&task=settings", "Настройки сохранены");
}

?>

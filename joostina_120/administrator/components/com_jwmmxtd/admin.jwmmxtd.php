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

// корень Медиа - менеджера из глобальной конфигурации
global $mosConfig_media_dir,$mosConfig_cachepath,$mosConfig_live_site;
$jwmmxtd_browsepath = $mosConfig_media_dir;

define('JWMMXTD_STARTABSPATH',$mosConfig_absolute_path.DIRECTORY_SEPARATOR.$jwmmxtd_browsepath);
define('JWMMXTD_STARTURLPATH',$mosConfig_live_site.'/'.$jwmmxtd_browsepath);

require_once ($mainframe->getPath('admin_html'));


function makeSafe($file) {
	return str_replace('..','',urldecode($file));
}

$subtask		= mosGetParam($_REQUEST,'subtask','');
$curdirectory	= makeSafe(mosGetParam($_REQUEST,'curdirectory',''));
$img			= mosGetParam($_REQUEST,'img','');
$selectedfile	= mosGetParam($_REQUEST,'selectedfile','');
$curfile		= mosGetParam($_REQUEST,'curfile','');
$newfile		= mosGetParam($_REQUEST,'newfilename','');
$folder_name	= mosGetParam($_POST,'createfolder','');
$delFile		= makeSafe(mosGetParam($_REQUEST,'delFile',''));
$delFolder		= mosGetParam($_REQUEST,'delFolder','');
$dirtocopy		= makeSafe(mosGetParam($_REQUEST,'dirtocopy','/'));
$dirtomove		= makeSafe(mosGetParam($_REQUEST,'dirtomove','/'));

if(is_int(strpos($curdirectory,".."))) {
	mosRedirect('index2.php','Попытка взлома...');
}

// очистка каталога кэша
$tmpimage = mosGetParam($_REQUEST,'tmpimage','');
if($tmpimage != "") {
	@unlink($mosConfig_cachepath.DIRECTORY_SEPARATOR.$tmpimage);
}

$mainframe->addCSS($mosConfig_live_site.'/administrator/components/com_jwmmxtd/css/jw_mmxtd.css');
mosCommonHTML::loadMootools();

if($task == 'edit') {
	$mainframe->addJS($mosConfig_live_site.'/administrator/components/com_jwmmxtd/js/jw_mmxtd_edit.php');
} else {
	$mainframe->addJS($mosConfig_live_site.'/administrator/components/com_jwmmxtd/js/jw_mmxtd_browse.php');
	$jw_mmxtd_head = '
	<script type="text/javascript">
	<!--
	if (navigator.appName.indexOf("Microsoft") == 0) {
		function loadEvent(obj, evType, fn) {
			if (obj.addEventListener) {
				obj.addEventListener(evType, fn, false);
				return (true);
			} else if (obj.attachEvent) {
				var r = obj.attachEvent("on"+evType, fn);
				return (r);
			} else return (false);
		}
		loadEvent(window, "load", Slider.init);
		loadEvent(window, "load", Lightbox.init.bind(Lightbox));
		loadEvent(window, "load", Videobox.init.bind(Videobox));
	} else {
		window.addEvent("domready", Slider.init);
		window.addEvent("domready", Lightbox.init.bind(Lightbox));
		window.addEvent("domready", Videobox.init.bind(Videobox));
	};
		function updateDir(){
			var allPaths = window.top.document.forms[0].dirPath.options;
			for(i=0; i<allPaths.length; i++) {
				allPaths.item(i).selected = false;
				if((allPaths.item(i).value)== "';
	if(strlen($curdirectory) > 0) {
		$jw_mmxtd_head .= $curdirectory;
	} else {
		$jw_mmxtd_head .= '/';
	}
	$jw_mmxtd_head .= '") {
					allPaths.item(i).selected = true;
				}
			}
		}
		function deleteFolder(folder, numFiles) {
			if(numFiles > 0) {
				alert("Каталог не пустой.\nПожалуйста, удалите сначала содержимое внутри каталога!");
				return false;
			}
			if(confirm("Удалить каталог \""+folder+"\"?")) return true; return false;
		}
	-->
	</script>
';
	$mainframe->addCustomHeadTag($jw_mmxtd_head);
}


switch($task) {
	case 'edit':
		editImage($img,$curdirectory);
		break;

	case 'unzipfile':
		$mosmsg = unzipzipfile($curdirectory,$curfile,$dirtocopy);
		viewMediaManager($dirtocopy,$mosmsg);
		break;

	case 'createfolder':
		if(ini_get('safe_mode') == 'On') {
			mosRedirect("index2.php?option=com_jwmmxtd&curdirectory=".$curdirectory,"При активированном параметре SAFE MODE возможны проблемы с созданием каталогов.");
		} else {
			if(create_folder($curdirectory,$folder_name))
				$mosmsg = 'Создан каталог '.$folder_name;
			else
				$mosmsg = 'Каталог '.$folder_name.' не создан';
		}
		viewMediaManager($curdirectory,$mosmsg);
		break;

	case 'delete':
		if(delete_file($curdirectory,$delFile))
			$mosmsg = 'Файл '.$delFile.' успешно удалён';
		else
			$mosmsg = 'Файл '.$delFile.' не удалён';
		viewMediaManager($curdirectory,$mosmsg);
		break;

	case 'deletefolder':
		if(delete_folder($curdirectory,$delFolder))
			$mosmsg = 'Каталог '.$delFolder.' удалён!';
		else
			$mosmsg = 'Каталог '.$delFolder.' не удалён!';
		viewMediaManager($curdirectory,$mosmsg);
		break;

	case 'uploadimages':
		$mosmsg = uploadImages($curdirectory);
		viewMediaManager($curdirectory,$mosmsg);
		break;

	case 'alterfilename':
		if(newFileName($curdirectory,$curfile,$newfile))
			$mosmsg = 'Переименовано!';
		else
			$mosmsg = 'Не переименовано!';
		viewMediaManager($curdirectory,$mosmsg);
		break;

	case 'copyfile':
		if(copyFile($curdirectory,$curfile,$dirtocopy))
			$mosmsg = 'Скопировано!';
		else
			$mosmsg = 'Не скопировано!';
		viewMediaManager($dirtocopy,$mosmsg);
		break;

	case 'movefile':
		if(moveFile($curdirectory,$curfile,$dirtomove))
			$mosmsg = 'Файл '.$curfile.' успешно перемещён в каталог '.$dirtomove;
		else
			$mosmsg = 'Файл '.$curfile.' не перемещен';
		viewMediaManager($dirtomove,$mosmsg);
		break;

	case 'emptytmp':
		if(emptyTmp())
			$mosmsg = 'Временный каталог полностью очищен';
		else
			$mosmsg = 'Временный каталог не очищен';
		viewMediaManager($curdirectory,$mosmsg);
		break;

	case 'saveimage':
		$mosmsg = saveImage($curdirectory);
		viewMediaManager($curdirectory,$mosmsg);
		break;

	case 'returnfromedit':
		returnFromEdit($curdirectory);
		viewMediaManager($curdirectory);
		break;

	default:
		viewMediaManager($curdirectory,"",$selectedfile);
		break;
}

// распаковка ZIP архивов
function unzipzipfile($curdirpath,$curfile,$destindir) {
	global $mosConfig_absolute_path;
	include_once ($mosConfig_absolute_path.'/administrator/includes/pcl/pclzip.lib.php');

	$path = JWMMXTD_STARTABSPATH.$curdirpath.DIRECTORY_SEPARATOR.$curfile;// файл для распаковки
	$path2 = JWMMXTD_STARTABSPATH.$destindir.DIRECTORY_SEPARATOR; // каталог для распаковки

	if(is_file($path)) {
		if(eregi(".zip",$path)) {
			$zip = new PclZip($path);
			$list = $zip->extract($path2);
			if($list > 0) {
				$msg = count($list).' файл(ов) распакованы.';
				return $msg;
			} else $msg = 'Ошибка распаковки: '.$curfile;
		} else {
			$msg = 'Файл: '.$curfile.' не является корректным zip архивом!';
			return $msg;
		}
	} else $msg = 'Файл: '.$curfile.' не существует!';
	return $msg;
}

// загрузка изображения
function saveImage($cur) {
	require_once ('class.upload.php');
	global $mosConfig_absolute_path;

	$cur = JWMMXTD_STARTABSPATH.$cur.DIRECTORY_SEPARATOR;

	$primage = mosGetParam($_REQUEST,'primage','');
	$orimage = mosGetParam($_REQUEST,'originalimage','');

	$tmp = explode("/",$orimage);
	$ornamewithext = end($tmp);
	$orname = str_replace(substr($ornamewithext,-4),"",$ornamewithext);

	if($orname) {
		$pic = new upload($mosConfig_absolute_path.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$primage);
		if($pic->uploaded) {
			$pic->file_src_name_body = $orname."_edit".rand(100,999);
			$pic->Process($cur);
			@unlink($mosConfig_absolute_path.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$primage);
			$ok = true;
		} else $ok = false;
	} else $ok = false;
	if($ok)
		$msg = 'Отредактированное изображение сохранено как '.$pic->file_dst_name;
	else
		$msg = 'Изображение НЕ сохранено!';
	return $msg;
}

function returnFromEdit() {
	require_once ('class.upload.php');
	global $mosConfig_absolute_path;
	$primage = mosGetParam($_REQUEST,'primage','');
	@unlink($mosConfig_absolute_path.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$primage);
}

function emptyTmp() {
	global $mosConfig_absolute_path;
	$dir = $mosConfig_absolute_path.DIRECTORY_SEPARATOR.'media';
	if(is_dir($dir)) {
		$d = dir($dir);
		while(false !== ($entry = $d->read())) {
			if(substr($entry,-4) == ".jpg" || substr($entry,-4) == ".gif" || substr($entry,
				-4) == ".png") {
				@unlink($dir."/".$entry);
			}
		}
		$d->close();
	}
	$total_file = 0;
	if(is_dir($dir)) {
		$d = dir($dir);
		while(false !== ($entry = $d->read())) {
			if(substr($entry,-4) == ".jpg" || substr($entry,-4) == ".gif" || substr($entry,
				-4) == ".png") {
				$total_file++;
			}
		}
		$d->close();
	}
	if($total_file == 0) $ok = true;
	else $ok = false;
	return $ok;
}

function newFileName($curdirectory,$curfile,$newfile) {
	if($curfile == "" || $newfile == "") return false;
	$path = JWMMXTD_STARTABSPATH.$curdirectory.DIRECTORY_SEPARATOR.$curfile;
	$path2 = JWMMXTD_STARTABSPATH.$curdirectory.DIRECTORY_SEPARATOR.$newfile;
	if(file_exists($path2)) return false;
	if(rename($path,$path2))
		$ok = true;
	else
		$ok = false;
	return $ok;
}

function copyFile($curdirectory,$curfile,$dirtocopy) {
	if($curfile == "") return false;
	$path = JWMMXTD_STARTABSPATH.$curdirectory.DIRECTORY_SEPARATOR.$curfile;
	$path2 = JWMMXTD_STARTABSPATH.$dirtocopy.DIRECTORY_SEPARATOR.$curfile;
	if(file_exists($path2)) return false;
	if(!copy($path,$path2))
		$ok = false;
	else
		$ok = true;
	return $ok;
}

function moveFile($curdirectory,$curfile,$dirtomove) {
	if($curfile == "") return false;
	$path = JWMMXTD_STARTABSPATH.$curdirectory.DIRECTORY_SEPARATOR.$curfile;
	$path2 = JWMMXTD_STARTABSPATH.$dirtomove.DIRECTORY_SEPARATOR.$curfile;
	if(file_exists($path2)) return false;
	if(!rename($path,$path2))
		$ok = false;
	else
		$ok = true;
	return $ok;
}

function uploadImages($curdirectory) {
	include ('class.upload.php');
	$files = array();
	foreach($_FILES['upimage'] as $k => $l) {
		foreach($l as $i => $v) {
			if(!array_key_exists($i,$files)) $files[$i] = array();
			$files[$i][$k] = $v;
		}
	}
	$mosmsg = 'Файл(ы) НЕ загружены на сервер!';
	foreach($files as $file) {
		$handle = new Upload($file);
		if($handle->uploaded) {
			$updirectory = JWMMXTD_STARTABSPATH.$curdirectory.DIRECTORY_SEPARATOR;
			$handle->Process($updirectory);
			if($handle->processed) {
				$mosmsg = 'Файлы загружены!';
			} else {
				$mosmsg = 'Файлы НЕ загружены!';
			}
		} else {
			//$mosmsg = 'Файлы не загружены на сервер!';
		}
	}
	return $mosmsg;
}

function delete_folder($listdir,$delFolder) {
	$del_html = JWMMXTD_STARTABSPATH.$listdir.DIRECTORY_SEPARATOR.$delFolder.DIRECTORY_SEPARATOR.'index.html';
	$del_folder = JWMMXTD_STARTABSPATH.$listdir.DIRECTORY_SEPARATOR.$delFolder;
	$entry_count = 0;
	$dir = opendir($del_folder);
	while($entry = readdir($dir)) {
		if($entry != "." & $entry != ".." & strtolower($entry) != "index.html") $entry_count++;
	}
	closedir($dir);
	if($entry_count < 1) {
		@unlink($del_html);
		if(rmdir($del_folder))
			$ok = true;
		else
			$ok = false;
	} else {
		$ok = false;
	}
	return $ok;
}

function delete_file($listdir,$delFile) {
	$fullPath = JWMMXTD_STARTABSPATH.$listdir.DIRECTORY_SEPARATOR.stripslashes($delFile);
	if(file_exists($fullPath)) {
		if(unlink($fullPath)) return true;
	}
	return false;
}

function listofImages($listdir) {
	$listdir = JWMMXTD_STARTABSPATH.$listdir;
	$d = @dir($listdir);

	if($d) {
		$images = array();
		$folders = array();
		$docs = array();
		// к изображениям относятся только файлы перечисленного типа
		$allowable = 'xcf|odg|gif|jpg|png|bmp';
		while(false !== ($entry = $d->read())) {
			$img_file = $entry;
			if(is_file($listdir.'/'.$img_file) && substr($entry,0,1) != '.' && strtolower($entry)
				!== 'index.html') {
				if(eregi($allowable,$img_file)) {
					$image_info = @getimagesize($listdir.'/'.$img_file);
					$file_details['file'] = $listdir."/".$img_file;
					$file_details['img_info'] = $image_info;
					$file_details['size'] = filesize($listdir."/".$img_file);
					$images[$entry] = $file_details;
				} else {
					$file_details['size'] = filesize($listdir."/".$img_file);
					$file_details['file'] = $listdir."/".$img_file;
					$docs[$entry] = $file_details;
				}
			} else
				if(is_dir($listdir.'/'.$img_file) && substr($entry,0,1) != '.' && strtolower($entry)!== 'cvs') {
					$folders[$entry] = $img_file;
				}
		}
		$d->close();
		if(count($images) > 0 || count($folders) > 0 || count($docs) > 0) {
			// сортировка файлов и каталогов по имени
			ksort($images);
			ksort($folders);
			ksort($docs);

			// подкаталоги
			if(count($folders) > 0) {
				echo '<fieldset><legend>Каталоги</legend>';
				for($i = 0; $i < count($folders); $i++) {
					$folder_name = key($folders);
					HTML_mmxtd::show_dir($folders[$folder_name],$folder_name,str_replace(JWMMXTD_STARTABSPATH,"",$listdir));
					next($folders);
				}
				echo '</fieldset>';
			}

			// изображения
			if(count($images) > 0) {
				echo '<fieldset><legend>Изображения</legend>';
				for($i = 0; $i < count($images); $i++) {
					$image_name = key($images);
					HTML_mmxtd::show_image($images[$image_name]['file'],$image_name,$images[$image_name]['img_info'],$images[$image_name]['size'],str_replace(JWMMXTD_STARTABSPATH,"",$listdir));
					next($images);
				}
				echo "</fieldset>";
			}
			// разные файлы
			if(count($docs) > 0) {
				echo '<fieldset><legend>Файлы</legend>';
				for($i = 0; $i < count($docs); $i++) {
					$doc_name = key($docs);
					$iconfile = $GLOBALS['mosConfig_absolute_path'].'/images/icons/'.substr($doc_name,-3).'.png';
					if(file_exists($iconfile)) {
						$icon = '../images/icons/'.(substr($doc_name,-3)).'.png';
					} else {
						$icon = '../images/icons/document.png';
					}
					$icon = strtolower($icon);
					HTML_mmxtd::show_doc($doc_name,$docs[$doc_name]['size'],str_replace(JWMMXTD_STARTABSPATH,'',$listdir),$icon);
					next($docs);
				}
				echo '</fieldset>';
			}
		} else {
		}
	} else {
	}
}

function listImagesBak($dirname = '.') {
	return glob($dirname.'*.{jpg,png,jpeg,gif}',GLOB_BRACE);
}
// создание каталога
function create_folder($curdirectory,$folder_name) {
	$folder_name = str_replace(" ","_",$folder_name);
	if(strlen($folder_name) > 0) {
		if(eregi("[^0-9a-zA-Z_]",$folder_name)) {
			mosRedirect("index2.php?option=com_jwmmxtdcurdirectory=".$curdirectory,'Пожалуйста, не используйте в названиях пробелы и спецсимволы!');
		}
		$folder = JWMMXTD_STARTABSPATH.$curdirectory.DIRECTORY_SEPARATOR.$folder_name;
		if(!is_dir($folder) && !is_file($folder)) {
			$suc = mosMakePath($folder);
			$fp = fopen($folder."/index.html","w");
			fwrite($fp,"<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>");
			fclose($fp);
			mosChmod($folder."/index.html");
			return $suc;
		}
	}
}
// список подкаталогов
function listofdirectories($base) {
	static $filelist = array();
	static $dirlist = array();
	if(is_dir($base)) {
		$dh = opendir($base);
		while(false !== ($dir = readdir($dh))) {
			if(is_dir($base.'/'.$dir) && $dir !== '.' && $dir !== '..' && strtolower($dir)!== 'cvs' && strtolower($dir) !== '.svn') {
				$subbase = $base.'/'.$dir;
				$dirlist[] = $subbase;
				$subdirlist = listofdirectories($subbase);
			}
		}
		closedir($dh);
	}
	return $dirlist;
}

// отображение медиа-менеджера
function viewMediaManager($curdirectory = "",$mosmsg = "",$selectedfile = "") {
	global $my,$mosConfig_absolute_path,$subtask;
	$imgFiles = listofdirectories(JWMMXTD_STARTABSPATH);
	$folders = array();
	$folders[] = mosHTML::makeOption("","/");
	$len = strlen(JWMMXTD_STARTABSPATH);
	foreach($imgFiles as $file) {
		$folders[] = mosHTML::makeOption(substr($file,$len));
	}
	if(is_array($folders)) {
		sort($folders);
	}
	$dirPath = mosHTML::selectList($folders,'curdirectory',"class=\"inputbox\" size=\"1\" onchange=\"document.adminForm.task.value='';document.adminForm.submit( );\" ",'value','text',$curdirectory);
	if($curdirectory == "") $upcategory = "";
	else {
		$tmp = explode("/",$curdirectory);
		end($tmp);
		unset($tmp[key($tmp)]);
		$upcategory = implode("/",$tmp);
		if($upcategory == "") $upcategory = "";
	}
	// сообщения о ошибках, уведомления
	if($mosmsg) {
		echo '<div class="message">'.$mosmsg.'</div>';
	}
?>
<div id="jwmmxtd">
	<form action="index2.php" name="adminForm" method="POST" enctype="multipart/form-data">
	<table cellpadding="0" cellspacing="0" style="width:100%;" id="upper" class="adminheading">
		<tr>
		<th class="media">Медиа менеджер</th>
		<td id="browse"><table cellpadding="0" cellspacing="4" align="right">
			<tr>
				<td>Создать каталог</td>
				<td style="width:220px;"><input class="inputbox" type="text" name="createfolder" id="createfolder" /></td>
				<td>
					<input type="button" class="button" onclick="javascript:document.adminForm.task.value='createfolder';document.adminForm.submit( );" value="Создать" />
				</td>
			</tr>
			<tr>
				<td>Загрузить файл:<a id="toggle" name="toggle" href="#">(+)</a></td>
				<td><input type="file" class="inputbox" name="upimage[]" />
				<div class="wrap">
				<div id="upload_more">
					<input type="file" class="inputbox" name="upimage[]" /><br />
					<input type="file" class="inputbox" name="upimage[]" /><br />
					<input type="file" class="inputbox" name="upimage[]" /><br />
					<input type="file" class="inputbox" name="upimage[]" /><br />
					</div>
				</div>
				</td>
				<td>
					<input type="button" class="button" onclick="javascript:document.adminForm.task.value='uploadimages';document.adminForm.submit( );" value="Загрузить" />
				</td>
			</tr>
			<tr>
				<td>Местоположение:</td>
				<td><?php echo $dirPath; ?></td>
				<td>
					<a href="index2.php?option=com_jwmmxtd&amp;curdirectory=<?php echo $upcategory; ?>"><img src="images/uparrow.png" alt="Перейти на каталог выше" /></a>
				</td>
			</tr>
			</table></td>
		</tr>
	</table>
	<div id="actions">
<?php if($selectedfile != "" && $subtask == "renamefile") { ?>
		<fieldset class="block">
		<legend>Переименование: <span><?php echo $selectedfile; ?></span></legend>
		<input type="hidden" name="curfile" value="<?php echo $selectedfile; ?>">Новое имя (включая расширение!):
		<input type="text" name="newfilename" id="newfilename">
		<input type="button" onclick="javascript:document.adminForm.task.value='alterfilename';document.adminForm.submit( );" class="button" value="Переименовать" />
		</fieldset>
<?php } ?>

<?php if($selectedfile != "" && $subtask == "copyfile") { ?>
		<fieldset class="block">
		<legend>Выберите каталог для копирования:<span><?php $selectedfile; ?></span></legend>
		<input type="hidden" name="curfile" value="<?php echo $selectedfile; ?>">
			Копировать в: <?php echo mosHTML::selectList($folders,'dirtocopy',"class=\"inputbox\" size=\"1\" ",'value','text',$curdirectory); ?>
		<input type="button" onclick="javascript:document.adminForm.task.value='copyfile';document.adminForm.submit( );" class="button" value="Копировать" />
		</fieldset>
<?php }if($selectedfile != "" && $subtask == "movefile") { ?>
		<fieldset class="block">
		<legend>Выберите каталог для перемещения:<span><?php echo $selectedfile; ?></span></legend>
		<input type="hidden" name="curfile" value="<?php echo $selectedfile; ?>">
		Переместить в: <?php echo mosHTML::selectList($folders,'dirtomove','class="inputbox" size="1" ','value','text',$curdirectory); ?>
		<input type="button" onclick="javascript:document.adminForm.task.value='movefile';document.adminForm.submit( );" class="button" value="Переместить" />
		</fieldset>
<?php }if($selectedfile != "" && $subtask == "unzipfile") {?>
	<fieldset class="block">
	<legend>Выберите каталог для распаковки:<span><?php echo $selectedfile; ?></span></legend>
		<input type="hidden" name="curfile" value="<?php echo $selectedfile; ?>" />
		Каталог распаковки:<?php echo mosHTML::selectList($folders,'dirtocopy',"class=\"inputbox\" size=\"1\" ",'value','text',$curdirectory); ?>
		<input type="button" onclick="javascript:document.adminForm.task.value='unzipfile';document.adminForm.submit( );" class="button" value="Распаковать" />
	</fieldset>
<?php } ?>

	</div>
	<input type="hidden" name="selectedfile" value="">
	<input type="hidden" name="subtask" value="">
	<input type="hidden" name="task" value="">
	<input type="hidden" name="option" value="com_jwmmxtd">
	</form>
	<div class="jwmmxtd_clr"></div>
	<?php echo listofImages($curdirectory); ?>
	<div class="jwmmxtd_clr"></div>
	<div id="jwmmxtd_tmp">
	<?php if($my->gid == 25 || $my->gid == 24) {
		echo 'Число изображений во временном каталоге: ';
		$dir = $mosConfig_absolute_path.'/media/';
		$total_file = 0;
		if(is_dir($dir)) {
			$d = dir($dir);
			while(false !== ($entry = $d->read())) {
				if(substr($entry,-4) == ".jpg" || substr($entry,-4) == ".gif" || substr($entry,-4) == ".png") {
					$total_file++;
				}
			}
			$d->close();
		}
		echo $total_file;
?>
	<input type="button" class="button" onclick="javascript:document.adminForm.task.value='emptytmp';document.adminForm.submit( );" value="Очистить каталог" />
	<?php } ?>
	</div>
</div>
<?php
}
// отмена всех действий по редактированию изображения
function OriginalImage($aFormValues) {
	require_once ('class.upload.php');
	global $mosConfig_absolute_path;
	$primage		= $aFormValues['primage'];
	$orimage		= $aFormValues['originalimage'];
	$curdirectory	= $aFormValues['curdirectory'];
	@unlink($mosConfig_absolute_path.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$primage);
	$objResponse	= new xajaxResponse();
	$objResponse->addAssign("mmxtd","innerHTML","<img name=\"mainimage\" id=\"mainimage\" src='".JWMMXTD_STARTURLPATH.$curdirectory."/".$orimage."'>");
	$objResponse->addAssign("imagepath","value",JWMMXTD_STARTURLPATH.$curdirectory."/".$orimage);
	return $objResponse;
}

function UpdateImage($aFormValues) {
	require_once ('class.upload.php');
	global $mosConfig_absolute_path,$mosConfig_live_site;

	$imagepath	= $aFormValues['imagepath'];
	$imagepath	= str_replace(JWMMXTD_STARTURLPATH,JWMMXTD_STARTABSPATH,$imagepath);

	$width		= intval($aFormValues['width']);
	$height		= intval($aFormValues['height']);
	$convert	= trim($aFormValues['convert']);
	$crop		= trim($aFormValues['crop']);
	$cropv		= trim($aFormValues['cropv']);
	$cropo		= trim($aFormValues['cropo']);
	$cropt		= trim($aFormValues['cropt']);
	$cropr		= trim($aFormValues['cropr']);
	$cropb		= trim($aFormValues['cropb']);
	$cropl		= trim($aFormValues['cropl']);
	$rotation	= intval($aFormValues['rotation']);
	$flip		= trim($aFormValues['flip']);
	$bevelpx	= intval($aFormValues['bevelpx']);
	$beveltl	= trim($aFormValues['beveltl']);
	$bevelrb	= trim($aFormValues['bevelrb']);
	$borderw	= trim($aFormValues['borderw']);
	$borderc	= trim($aFormValues['borderc']);
	$bordert	= trim($aFormValues['bordert']);
	$borderr	= trim($aFormValues['borderr']);
	$borderb	= trim($aFormValues['borderb']);
	$borderl	= trim($aFormValues['borderl']);
	$borderc2	= trim($aFormValues['borderc2']);
	$tint		= trim($aFormValues['tint']);
	$overlayp	= trim($aFormValues['overlayp']);
	$overlayc	= trim($aFormValues['overlayc']);
	$brightness	= intval($aFormValues['brightness']);
	$contrast	= intval($aFormValues['contrast']);
	$threshold	= intval($aFormValues['threshold']);
	$bgcolor	= trim($aFormValues['bgcolor']);
	$bgpercent	= intval($aFormValues['bgpercent']);
	$text		= trim($aFormValues['text']);
	$textcolor	= trim($aFormValues['textcolor']);
	$textfont	= trim($aFormValues['textfont']);

	if(isset($aFormValues['primage']))
		$primage = $aFormValues['primage'];
	else
		$primage = 0;
	if(isset($aFormValues['greyscale']))
		$greyscale = $aFormValues['greyscale'];
	else
		$greyscale = 0;
	if(isset($aFormValues['negative']))
		$negative = $aFormValues['negative'];
	else
		$negative = 0;

	$textpercent	= intval($aFormValues['textpercent']);
	$textdirection	= trim($aFormValues['textdirection']);
	$textposition	= trim($aFormValues['textposition']);
	$textpaddingx	= intval($aFormValues['textpaddingx']);
	$textpaddingy	= intval($aFormValues['textpaddingy']);
	$textabsolutex	= intval($aFormValues['textabsolutex']);
	$textabsolutey	= intval($aFormValues['textabsolutey']);

	$pic = new upload($imagepath);
	if($pic->uploaded) {
		$pic->file_new_name_body = md5(uniqid("mmxtd"));
		if($width > 0 || $height > 0) {
			$pic->image_resize = true;
		}
		if($width > 0 && $height > 0) {
			$pic->image_x = $width;
			$pic->image_y = $height;
		}
		if($width > 0 && $height == 0) {
			$pic->image_x = $width;
			$pic->image_ratio_y = true;
		}
		if($height > 0 && $width == 0) {
			$pic->image_y = $height;
			$pic->image_ratio_x = true;
		}
		if($crop != "") {
			$pic->image_crop = $crop;
		} elseif($cropv != "" && $cropo != "") {
			$pic->image_crop = $cropv." ".$cropo;
		} elseif($cropt != "" && $cropr != "" && $cropb != "" && $cropl != "") {
			$pic->image_crop = $cropt." ".$cropr." ".$cropb." ".$cropl;
		}
		if($rotation > 0) {
			$pic->image_rotate = $rotation;
		}
		if($flip != "none") {
			$pic->image_flip = $flip;
		}
		if($convert != "none") {
			$pic->image_convert = $convert;
		}
		if($bevelpx > 0 && $beveltl != "" && $bevelrb != "") {
			$pic->image_bevel = $bevelpx;
			$pic->image_bevel_color1 = $beveltl;
			$pic->image_bevel_color2 = $bevelrb;
		}
		if($borderw != "" && $borderc != "") {
			$pic->image_border = $borderw;
			$pic->image_border_color = $borderc;
		} elseif($bordert != "" && $borderr != "" && $borderb != "" && $borderl != "" &&
		$borderc2 != "") {
			$pic->image_border = $bordert." ".$borderr." ".$borderb." ".$borderl;
			$pic->image_border_color = $borderc2;
		}
		if($tint != "") {
			$pic->image_tint_color = $tint;
		}
		if($overlayp != "" && $overlayc != "") {
			$pic->image_overlay_percent = $overlayp;
			$pic->image_overlay_color = $overlayc;
		}
		if($brightness != 0) {
			$pic->image_brightness = $brightness;
		}
		if($contrast != 0) {
			$pic->image_contrast = $contrast;
		}
		if($threshold != 0) {
			$pic->image_threshold = $threshold;
		}
		if($greyscale) {
			$pic->image_greyscale = true;
		}
		if($negative) {
			$pic->image_negative = true;
		}
		if($text != "") {
			$pic->image_text = $text;
			if($textcolor != "") {
				$pic->image_text_color = "$textcolor";
			}
			if($textfont != "") {
				$pic->image_text_font = $textfont;
			}
			if($textpercent != 0) {
				$pic->image_text_percent = $textpercent;
			}
			if($textdirection != "") {
				$pic->image_text_direction = $textdirection;
			}
			if($textposition != "") {
				$pic->image_text_position = $textposition;
			}
			if($bgcolor != "") {
				$pic->image_text_background = $bgcolor;
			}
			if($bgpercent != 0) {
				$pic->image_text_background_percent = $bgpercent;
			}
			if($textpaddingx != 0) {
				$pic->image_text_padding_x = $textpaddingx;
			}
			if($textpaddingy != 0) {
				$pic->image_text_padding_y = $textpaddingy;
			}
			if($textabsolutex != 0) {
				$pic->image_text_x = $textabsolutex;
			}
			if($textabsolutey != 0) {
				$pic->image_text_y = $textabsolutey;
			}
		}
		$pic->Process($mosConfig_absolute_path.'/media/');
		if($pic->processed) {
			$img2out = '<img name="mainimage" id="mainimage" src="'.$mosConfig_live_site.'/media/'.$pic->file_dst_name.'" />';
			@unlink($mosConfig_absolute_path.'/media/'.$primage);
			$primage = $pic->file_dst_name;
		}
	} else $img2out = "Ошибка при обработке файла ".$imagepath;

	$objResponse = new xajaxResponse();
	//$objResponse->addAssign("mymsg","innerHTML",$imagepath."--".$primage);
	$objResponse->addAssign("tb-apply","className",'tb-apply'); // скрываем слой с индикатором выполнения процесса
	$objResponse->addClear("mainimage","src");
	$objResponse->addAssign("loading_placeholder","innerHTML",'');
	$objResponse->addAssign("mmxtd","innerHTML",$img2out);
	$objResponse->addAssign("primage","innerHTML","<input type=\"hidden\" name=\"primage\" id=\"primage\" value=\"".$primage."\">");
	$objResponse->addAssign("imagepath","value",$mosConfig_absolute_path.'/media/'.$primage);
	$objResponse->addAssign("width","value","");
	$objResponse->addAssign("height","value","");
	$objResponse->addAssign("rotation","value","0");
	$objResponse->addAssign("flip","value","none");
	$objResponse->addAssign("convert","value","none");
	$objResponse->addAssign("bevelpx","value","");
	$objResponse->addAssign("beveltl","value","");
	$objResponse->addAssign("bevelrb","value","");
	$objResponse->addAssign("borderw","value","");
	$objResponse->addAssign("borderc","value","");
	$objResponse->addAssign("bordert","value","");
	$objResponse->addAssign("borderr","value","");
	$objResponse->addAssign("borderb","value","");
	$objResponse->addAssign("borderl","value","");
	$objResponse->addAssign("borderc2","value","");
	$objResponse->addAssign("tint","value","");
	$objResponse->addAssign("overlayp","value","");
	$objResponse->addAssign("overlayc","value","");
	$objResponse->addAssign("brightness","value","");
	$objResponse->addAssign("contrast","value","");
	$objResponse->addAssign("threshold","value","");
	$objResponse->addAssign("greyscale","checked",false);
	$objResponse->addAssign("negative","checked",false);
	$objResponse->addAssign("text","value","");
	$objResponse->addAssign("textcolor","value","");
	$objResponse->addAssign("textfont","value","");
	$objResponse->addAssign("textpercent","value","");
	$objResponse->addAssign("textdirection","value","none");
	$objResponse->addAssign("textposition","value","none");
	$objResponse->addAssign("bgcolor","value","");
	$objResponse->addAssign("bgpercent","value","");
	$objResponse->addAssign("textpaddingx","value","");
	$objResponse->addAssign("textpaddingy","value","");
	$objResponse->addAssign("textabsolutex","value","");
	$objResponse->addAssign("textabsolutey","value","");
	return $objResponse;
}

function editImage($img,$cur) {
	global $mosConfig_live_site,$option,$mosConfig_absolute_path;
	require_once ($mosConfig_absolute_path.'/includes/xajax/xajax.inc.php');
	$path = JWMMXTD_STARTURLPATH.$cur.'/'.$img;
	$xajax = new xajax();
	//$xajax->debugOn();
	$xajax->registerFunction("UpdateImage");
	$xajax->registerFunction("OriginalImage");
	$xajax->registerFunction("MoveImage");
	$xajax->processRequests();
	$xajax->printJavascript($mosConfig_live_site.'/includes/xajax');
?>
<script type="text/javascript">
	function UpdateImg(value){
		SRAX.get('tb-apply').className='tb-load';
		xajax_UpdateImage(value);
	}
</script>
<div id="loading_placeholder"></div>
<div id="jimgedit">
	<table class="adminheading">
		<tr><th class="media">Редактирование изображения</th></tr>
	</table>
	<div id="mymsg"></div>
	<div id="show_image_path">Файл:<b><?php echo $path; ?></b></div>
	<div id="jwmmxtd_editpage">
	<div id="jwmmxtd_image">
		<div id="mmxtd"><img name="mainimage" id="mainimage" src="<?php echo $path; ?>" /></div>
	</div>
	<div id="jwmmxtd_panel">
		<form method="POST" id="adminForm" name="adminForm" enctype="multipart/form-data" onSubmit="return false;">
		<fieldset><legend>Высота x Ширина</legend>
			ширина<input id="width" name="width" type="text" size="4" />
			x<input id="height" name="height" type="text" size="4" />
			высота
		</fieldset>
		<fieldset>
			<legend>Расширение изображения</legend>
			Расширение
			<select id="convert" name="convert">
				<option value="none">-- выбор --</option>
				<option value="jpg">jpg</option>
				<option value="gif">gif</option>
				<option value="png">png</option>
			</select>
		</fieldset>
		<fieldset>
		<legend>Обрезать</legend>
		<fieldset>
		<legend>Размеры</legend>
		Размеры
		<input id="crop" name="crop" type="text" size="4" />
		</fieldset>
		<fieldset>
		<legend>X и Y координаты</legend>
			вертикали:<input id="cropv" name="cropv" type="text" size="4" />
			горизонтали:<input id="cropo" name="cropo" type="text" size="4" />
		</fieldset>
		<fieldset>
		<legend>Обрезать</legend>
		<table cellpadding="0" cellspacing="0" style="text-align:center;">
			<tr>
				<td>Сверху<br />
					<input id="cropt" name="cropt" type="text" size="4" />
				</td>
			</tr>
			<tr>
				<td>
					Слева<input id="cropl" name="cropl" type="text" size="4" />
					&nbsp;
					<input id="cropr" name="cropr" type="text" size="4" />
					Справа
				</td>
			</tr>
			<tr>
				<td><input id="cropb" name="cropb" type="text" size="4" />
					<br />Снизу
				</td>
			</tr>
		</table>
		</fieldset>
		</fieldset>
		<fieldset>
		<legend>Поворот</legend>
		Повернуть на
		<select id="rotation" name="rotation">
			<option value="0">-- выбор --</option>
			<option value="90">90</option>
			<option value="180">180</option>
			<option value="270">270</option>
		</select>
		</fieldset>
		<fieldset>
		<legend>Отражение</legend>
		Отразить по
		<select id="flip" name="flip">
			<option value="none">-- выбор --</option>
			<option value="H">вертикали</option>
			<option value="V">горизонтали</option>
		</select>
		</fieldset>
		<fieldset>
		<legend>Градиентная рамка</legend>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td>Размер px</td>
				<td><input id="bevelpx" name="bevelpx" type="text" /></td>
			</tr>
			<tr>
				<td>Сверху-Слева</td>
				<td><input id="beveltl" name="beveltl" type="text" />
				<a style="cursor:pointer;" onClick="showColorPicker(this,document.adminForm.beveltl)">
					<img width="16" height="16" border="0" alt="Нажмите для выбора цвета" src="<?php echo $mosConfig_live_site.'/administrator/components/com_jwmmxtd/images/color_wheel.png'; ?>">
				</a>
				</td>
			</tr>
			<tr>
				<td>Справа-Снизу</td>
				<td><input id="bevelrb" name="bevelrb" type="text" />
				<a style="cursor:pointer;" onClick="showColorPicker(this,document.adminForm.bevelrb)"><img width="16" height="16" border="0" alt="Нажмите для выбора цвета" src="<?php echo $mosConfig_live_site.'/administrator/components/com_jwmmxtd/images/color_wheel.png'; ?>"></a></td>
			</tr>
		</table>
		</fieldset>
		<fieldset>
		<legend>Бордюр</legend>
		<fieldset>
		<legend>Бордюр</legend>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td>ширина</td>
				<td><input id="borderw" name="borderw" type="text" /></td>
			</tr>
			<tr>
				<td>Цвет</td>
				<td><input id="borderc" name="borderc" type="text" />
				<a style="cursor:pointer;" onClick="showColorPicker(this,document.adminForm.borderc)"><img width="16" height="16" border="0" alt="Нажмите для выбора цвета" src="<?php echo $mosConfig_live_site.'/administrator/components/com_jwmmxtd/images/color_wheel.png'; ?>"> </a></td>
			</tr>
		</table>
		</fieldset>
		<fieldset>
		<legend>Все бордюры</legend>
		<table cellpadding="0" cellspacing="0" style="text-align:center;">
			<tr>
				<td>Сверху<br /><input id="bordert" name="bordert" type="text" size="4" /></td>
			</tr>
			<tr>
			<td>Слева<input id="borderl" name="borderl" type="text" size="4" />&nbsp;
				<input id="borderr" name="borderr" type="text" size="4" />
				Справа</td>
			</tr>
			<tr>
			<td><input id="borderb" name="borderb" type="text" size="4" />
				<br />
				Снизу<br />
				Цвет
				<input id="borderc2" name="borderc2" type="text" />
				<a style="cursor:pointer;" onClick="showColorPicker(this,document.adminForm.borderc2)"><img width="16" height="16" alt="Нажмите для выбора цвета" src="<?php echo $mosConfig_live_site.'/administrator/components/com_jwmmxtd/images/color_wheel.png'; ?>"></a> </td>
			</tr>
		</table>
		</fieldset>
		</fieldset>
		<fieldset>
		<legend>Tint Color</legend>
		Цвет
		<input id="tint" name="tint" type="text" />
		<a style="cursor:pointer;" onClick="showColorPicker(this,document.adminForm.tint)"> <img width="16" height="16" border="0" alt="Нажмите для выбора цвета" src="<?php echo $mosConfig_live_site.'/administrator/components/com_jwmmxtd/images/color_wheel.png'; ?>"> </a>
		</fieldset>
		<fieldset>
		<legend>Overlay</legend>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td>Percent</td>
				<td><input id="overlayp" name="overlayp" type="text" size="4" /></td>
			</tr>
			<tr>
			<td>Цвет</td>
			<td><input id="overlayc" name="overlayc" type="text" />
				<a style="cursor:pointer;" onClick="showColorPicker(this,document.adminForm.overlayc)"> <img width="16" height="16" border="0" alt="Нажмите для выбора цвета" src="<?php echo $mosConfig_live_site.'/administrator/components/com_jwmmxtd/images/color_wheel.png'; ?>"> </a></td>
			</tr>
		</table>
		</fieldset>
		<fieldset>
		<legend>Яркость</legend>
			<input id="brightness" name="brightness" type="text" />
		</fieldset>
		<fieldset>
		<legend>Контраст</legend>
			<input id="contrast" name="contrast" type="text" />
		</fieldset>
		<fieldset>
		<legend>Threshold filter</legend>
			<input id="threshold" name="threshold" type="text" />
		</fieldset>
		<fieldset>
		<legend>Дополнительные действия</legend>
			Градиент серого<input type="checkbox" name="greyscale" id="greyscale">
			Негатив<input type="checkbox" name="negative" id="negative">
		</fieldset>
		<fieldset>
		<legend>Добавить текст</legend>
		<table cellpadding="0" cellspacing="2">
			<tr>
			<td>Текст</td>
			<td><input type="text" name="text" id="text">
			</td>
			</tr>
			<tr>
			<td>Цвет текста</td>
			<td><input type="text" name="textcolor" id="textcolor">
				<a style="cursor:pointer;" onClick="showColorPicker(this,document.adminForm.textcolor)"> <img width="16" height="16" border="0" alt="Нажмите для выбора цвета" src="<?php echo $mosConfig_live_site.'/administrator/components/com_jwmmxtd/images/color_wheel.png'; ?>"> </a> </td>
			</tr>
			<tr>
			<td>Шрифт текста</td>
			<td><input type="text" name="textfont" id="textfont">
			</td>
			</tr>
			<tr>
			<td>Размер текста</td>
			<td><input type="text" name="textpercent" id="textpercent"></td>
			</tr>
			<tr>
			<td>Ориентация</td>
			<td><select name="textdirection" id="textdirection">
				<option value="none">-- выбор --</option>
				<option value="h">горизонтали</option>
				<option value="v">вертикали</option>
				</select>
			</td>
			</tr>
			<tr>
			<td>Позиция</td>
			<td><select name="textposition" id="textposition">
				<option value="none">-- выбор --</option>
				<option value="TL">Top - Left</option>
				<option value="T">Top</option>
				<option value="TR">Top - Right</option>
				<option value="L">Left</option>
				<option value="R">Right</option>
				<option value="BL">Bottom - Left</option>
				<option value="B">Bottom</option>
				<option value="BR">Bottom - Right</option>
				</select>
			</td>
			</tr>
			<tr>
			<td>Bg Percent</td>
			<td><input type="text" name="bgpercent" id="bgpercent">
			</td>
			</tr>
			<tr>
			<td>Цвет фона</td>
			<td><input type="text" name="bgcolor" id="bgcolor">
				<a style="cursor:pointer;" onClick="showColorPicker(this,document.adminForm.bgcolor)">
			<img width="16" height="16" border="0" alt="Нажмите для выбора цвета" src="<?php echo $mosConfig_live_site.'/administrator/components/com_jwmmxtd/images/color_wheel.png'; ?>"> </a> </td>
			</tr>
			<tr>
				<td>Расположение по X и Y</td>
				<td>
					X:<input type="text" name="textpaddingx" id="textpaddingx" size="4">
					Y:<input type="text" name="textpaddingy" id="textpaddingy" size="4">
				</td>
			</tr>
			<tr>
				<td>Отступы по X и Y</td>
				<td>
					X:<input type="text" name="textabsolutex" id="textabsolutex" size="4">
					Y:<input type="text" name="textabsolutey" id="textabsolutey" size="4">
				</td>
			</tr>
		</table>
		</fieldset>
		<input type="hidden" name="imagepath" id="imagepath" value="<?php echo $path; ?>">
		<input type="hidden" name="originalimage" id="originalimage" value="<?php echo $img; ?>">
		<input type="hidden" name="curdirectory" id="curdirectory" value="<?php echo $cur; ?>">
		<input type="hidden" name="option" id="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" id="task" value="">
		<div id="primage"></div>
	</form>
	</div>
	<div class="jwmmxtd_clr"></div>
<script type="text/javascript">
	initFloatingWindowWithTabs('editor_panel',Array('Первая','Вторая','Третья...'),100,450,80,60,true,false,false,true);
</script>
	</div>
</div>
<?php } ?>

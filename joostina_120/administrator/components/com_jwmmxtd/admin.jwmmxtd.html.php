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

global $mosConfig_live_site,$mainframe,$task;

class HTML_mmxtd {
// отображения подкаталога текущего каталога
	function show_dir($path,$dir,$listdir) {
		if($listdir) {
			$link = 'index2.php?option=com_jwmmxtd&amp;curdirectory='.$listdir."/".$path;
			$count = HTML_mmxtd::num_files($listdir."/".$path);
		} else {
			$link = 'index2.php?option=com_jwmmxtd&amp;curdirectory='."/".$path;
			$count = HTML_mmxtd::num_files('/'.$path);
		}
		$num_files = $count[0];
		$num_dir = $count[1];
?>
<div class="folder_style">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="filename" colspan="2"><h2><?php echo substr($dir,0,20).(strlen($dir) > 20?'...':''); ?></h2></td>
	</tr>
	<tr>
		<td class="fileinfo">
			Каталогов: <?php echo $num_dir; ?><br />
			Файлов: <?php echo $num_files; ?>
		</td>
		<td class="fileactions">
			<a href="javascript:void(null)" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $path ?>';document.adminForm.subtask.value='renamefile';document.adminForm.submit( );" title="Переименовать">
			<img src="images/ico/rename.png" alt="Переименовать" title="Переименовать" /></a>
			<a href="javascript:void(null)" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $path ?>';document.adminForm.subtask.value='copyfile';document.adminForm.submit( );" title="Копировать">
			<img src="images/ico/copy.png" alt="Копировать" title="Копировать" /></a>
			<a href="javascript:void(null)" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $path ?>';document.adminForm.subtask.value='movefile';document.adminForm.submit( );" title="Переместить">
			<img src="images/ico/cut.png" alt="Переместить" title="Переместить" /></a>
			<a href="index2.php?option=com_jwmmxtd&amp;task=deletefolder&amp;delFolder=<?php echo $path; ?>&amp;curdirectory=<?php echo $listdir; ?>" onclick="return deleteFolder('<?php echo $dir; ?>', <?php echo $num_files; ?>);" title="Удалить">
			<img src="images/ico/delete.png" alt="Удалить" title="Удалить" /></a>
		</td>
	</tr>
</table>
<div style="text-align:center;margin:2px auto;"> <a href="<?php echo $link; ?>"><img src="components/com_jwmmxtd/images/folder.gif" /></a> </div>
</div>
<?php
	}
// подсчет размера
	function parse_size($size) {
		if($size < 1024) {
			return $size.' байт';
		} else
			if($size >= 1024 && $size < 1024* 1024) {
				return sprintf('%01.2f',$size / 1024.0).' кб';
			} else {
				return sprintf('%01.2f',$size / (1024.0* 1024)).' мб';
			}
	}
// вывод изображения
	function show_image($img,$file,$info,$size,$listdir) {
		$img_file = basename($img);
		$img_url_link = JWMMXTD_STARTURLPATH.$listdir."/".rawurlencode($img_file);
		$cur = $listdir;
		$filesize = HTML_mmxtd::parse_size($size);
		if(($info[0] > 200) || ($info[1] > 200)) {
			$img_dimensions = HTML_mmxtd::imageResize($info[0],$info[1],200);
		} else {
			$img_dimensions = 'style="width:'.$info[0].'px;height:'.$info[1].'px; margin:4px auto;display:block;"';
		}
?>
<div class="image_style">
<table cellpadding="0" cellspacing="0">
		<tr>
			<td class="filename" colspan="2">
				<h2><a href="<?php echo $img_url_link; ?>" title="<?php echo $file; ?>" rel="lightbox[jwmmxtd-title]">
				<?php echo htmlspecialchars(substr($file,0,20).(strlen($file) > 20?'...':''),ENT_QUOTES); ?></a></h2>
			</td>
		</tr>
		<tr>
			<td class="fileactions">
				<a href="index2.php?option=com_jwmmxtd&task=edit&curdirectory=<?php echo $cur; ?>&img=<?php echo $img_file; ?>" title="Редактировать">
				<img src="images/ico/picture_edit.png" alt="Редактировать" title="Редактировать" /></a>
				<a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $file ?>';document.adminForm.subtask.value='renamefile';document.adminForm.submit( );" title="Переименовать">
				<img src="images/ico/rename.png" alt="Переименовать" title="Переименовать" /></a>
				<a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $file ?>';document.adminForm.subtask.value='copyfile';document.adminForm.submit( );" title="Копировать">
				<img src="images/ico/copy.png" alt="Копировать" title="Копировать" /></a>
				<a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $file ?>';document.adminForm.subtask.value='movefile';document.adminForm.submit( );" title="Переместить">
				<img src="images/ico/cut.png" alt="Переместить" title="Переместить" /></a>
				<a href="index2.php?option=com_jwmmxtd&amp;task=delete&amp;delFile=<?php echo $file; ?>&amp;curdirectory=<?php echo $cur; ?>" onclick="javascript:if(confirm('Удалить файл:<?php echo $file; ?>')) return true; return false;" title="Удалить">
				<img src="images/ico/delete.png" alt="Удалить" title="Удалить" /></a>
			</td>
		</tr>
</table>
<div class="fileimage"> <a href="<?php echo $img_url_link; ?>" rel="lightbox[jwmmxtd]" title="Файл:<br /><?php echo $file; ?>" alt="Нажмите для просмотра">
	<img src="<?php echo $img_url_link; ?>?ok=ok" <?php echo $img_dimensions; ?> alt="Нажмите для просмотра" title="Нажмите для просмотра" /></a>
</div>
Размер: <?php echo $filesize; ?><br />
Ширина: <?php echo $info[0]; ?>px, Высота: <?php echo $info[1]; ?>px
</div>
<?php
	}
// подсчет числа файлов
	function num_files($dir) {
		$total_file = 0;
		$total_dir = 0;
		$dir = JWMMXTD_STARTABSPATH.$dir;
		if(is_dir($dir)) {
			$d = dir($dir);

			while(false !== ($entry = $d->read())) {
				if(substr($entry,0,1) != '.' && is_file($dir.DIRECTORY_SEPARATOR.$entry) &&
					strpos($entry,'.html') === false && strpos($entry,'.php') === false) {
					$total_file++;
				}
				if(substr($entry,0,1) != '.' && is_dir($dir.DIRECTORY_SEPARATOR.$entry)) {
					$total_dir++;
				}
			}

			$d->close();
		}

		return array($total_file,$total_dir);
	}
// отображение документов
	function show_doc($doc,$size,$listdir,$icon) {
		$size = HTML_mmxtd::parse_size($size);
		$doc_url_link = JWMMXTD_STARTURLPATH.$listdir."/".rawurlencode($doc);
		$cur = $listdir;
?>

<div class="file_style">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="filename" colspan="2"><h2><?php echo htmlspecialchars(substr($doc,0,14).(strlen($doc) > 14?'...':''),ENT_QUOTES); ?></h2></td>
	</tr>
	<tr>
		<td class="fileinfo"><?php echo $size; ?></td>
		<td class="fileactions">
<?php
// архив
if($icon == "../images/icons/zip.png") { ?>
		<a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $doc ?>';document.adminForm.subtask.value='unzipfile';document.adminForm.submit( );" title="Распаковать">
		<img src="components/com_jwmmxtd/images/compress.png" alt="Распаковать" title="Распаковать" /></a>
<?php } ?>
			<a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $doc ?>';document.adminForm.subtask.value='renamefile';document.adminForm.submit( );" title="Переименовать">
			<img src="images/ico/rename.png" alt="Переименовать" title="Переименовать" /></a>
			<a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $doc ?>';document.adminForm.subtask.value='copyfile';document.adminForm.submit( );" title="Копировать">
			<img src="images/ico/copy.png" alt="Копировать" title="Копировать" /></a>
			<a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $doc ?>';document.adminForm.subtask.value='movefile';document.adminForm.submit( );" title="Переместить">
			<img src="images/ico/cut.png" alt="Переместить" title="Переместить" /></a>
			<a href="index2.php?option=com_jwmmxtd&amp;task=delete&amp;delFile=<?php echo $doc; ?>&amp;curdirectory=<?php echo $cur; ?>" onclick="javascript:if(confirm('Удалить файл: <?php echo $doc; ?>')) return true; return false;" title="Удалить">
			<img src="images/ico/delete.png" alt="Удалить" title="Удалить" /></a>
		</td>
	</tr>
</table>
<div class="fileimage">
<?php
	// флеш - файл flv
	if($icon == "../images/icons/flv.png") {
?>
	<a href="components/com_jwmmxtd/js/flvplayer.swf?file=<?php echo $doc_url_link; ?>&amp;autostart=true&amp;allowfullscreen=true" rel="vidbox 800 600" title="Видео файл:<br /><?php echo $doc; ?>" alt="Нажмите на значок для просмотра">
	<img src="<?php echo $icon ?>" alt="<?php echo $doc; ?>" title="Нажмите на значок для просмотра" /></a>
<?php
	// флеш - файл swf
	} elseif($icon == "../images/icons/swf.png") {
		$swfinfo = @getimagesize($doc_url_link);
?>
	<a href="<?php echo $doc_url_link; ?>" rel="vidbox <?php echo $swfinfo[0]; ?> <?php echo $swfinfo[1]; ?>" title="Файл:</b><br /><?php echo $doc; ?>" alt="Нажмите на значок для просмотра">
	<img src="<?php echo $icon ?>" alt="<?php echo $doc; ?>" title="Нажмите на значок для просмотра" /></a>
<?php
	} else {
?>
	<img src="<?php echo $icon ?>" alt="<?php echo $doc; ?>" />
<?php
	}
?>
</div>
</div>
<?php
	}
// расчет и отображение размера изображения
	function imageResize($width,$height,$target) {
		if($width > $height) {
			$percentage = ($target / $width);
		} else {
			$percentage = ($target / $height);
		}
		$width = round($width* $percentage);
		$height = round($height* $percentage);
		return 'width="'.$width.'" height="'.$height.'"';

	}
}
?>

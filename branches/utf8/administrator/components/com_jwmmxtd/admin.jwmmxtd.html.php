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

global $mosConfig_live_site, $mainframe, $task;

class HTML_mmxtd {
	function show_dir( $path, $dir, $listdir ) {
		
		$cur = JWMMXTD_STARTABSPATH . $listdir;
		if($listdir) {
		   $link = 'index2.php?option='.JWMMXTD_COMP.'&amp;curdirectory='.$listdir."/".$path;
		   $count = HTML_mmxtd::num_files( $listdir."/".$path );
		} else {
		   $link = 'index2.php?option='.JWMMXTD_COMP.'&amp;curdirectory='."/".$path;
		   $count = HTML_mmxtd::num_files( "/".$path );
		}  
		
		$num_files 	= $count[0];
		$num_dir 	= $count[1];
?>

<!-- JoomlaWorks MEDIA MANAGER XTD - FOLDERS -->
<div class="folder_style">
  <table cellpadding="0" cellspacing="0">
    <tr>
      <td class="filename" colspan="2"><h2><?php echo substr( $dir, 0, 20 ) . ( strlen( $dir ) > 20 ? '...' : ''); ?></h2></td>
    </tr>
    <tr>
      <td class="fileinfo"><?php echo _JWMEDIAMAN_NUM_DIR; ?>: <?php echo $num_dir; ?><br />
        <?php echo _JWMEDIAMAN_NUM_FILES; ?>: <?php echo $num_files; ?> </td>
      <td class="fileactions"><!-- rename -->
        <a href="javascript:void(null)" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $path ?>';document.adminForm.subtask.value='renamefile';document.adminForm.submit( );" title="<?php echo _JWMEDIAMAN_ACT_RENAME; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/rename.png" alt="<?php echo _JWMEDIAMAN_ACT_RENAME; ?>" title="<?php echo _JWMEDIAMAN_ACT_RENAME; ?>" /></a>
        <!-- copy -->
        <a href="javascript:void(null)" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $path ?>';document.adminForm.subtask.value='copyfile';document.adminForm.submit( );" title="<?php echo _JWMEDIAMAN_ACT_COPY; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/copy.png" alt="<?php echo _JWMEDIAMAN_ACT_COPY; ?>" title="<?php echo _JWMEDIAMAN_ACT_COPY; ?>" /></a>
        <!-- move -->
        <a href="javascript:void(null)" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $path ?>';document.adminForm.subtask.value='movefile';document.adminForm.submit( );" title="<?php echo _JWMEDIAMAN_ACT_MOVE; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/cut.png" alt="<?php echo _JWMEDIAMAN_ACT_MOVE; ?>" title="<?php echo _JWMEDIAMAN_ACT_MOVE; ?>" /></a>
        <!-- delete -->
        <a href="index2.php?option=<?php echo JWMMXTD_COMP; ?>&amp;task=deletefolder&amp;delFolder=<?php echo $path; ?>&amp;curdirectory=<?php echo $listdir; ?>" onclick="return deleteFolder('<?php echo $dir; ?>', <?php echo $num_files; ?>);" title="<?php echo _JWMEDIAMAN_ACT_DELETE; ?>"> <img src="components/<?php echo JWMMXTD_COMP; ?>/images/delete.png" alt="<?php echo _JWMEDIAMAN_ACT_DELETE; ?>" title="<?php echo _JWMEDIAMAN_ACT_DELETE; ?>" /></a> </td>
    </tr>
  </table>
  <div style="text-align:center;margin:2px auto;"> <a href="<?php echo $link; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/folder.gif" /></a> </div>
</div>
<?php
	}

	function parse_size($size){
		if($size < 1024) {
			return $size.' байт';
		} else if($size >= 1024 && $size < 1024*1024) {
			return sprintf('%01.2f',$size/1024.0).' кб';
		} else {
			return sprintf('%01.2f',$size/(1024.0*1024)).' мб';
		}
	}
	
	function show_image($img, $file, $info, $size, $listdir) {
		$img_file 		= basename($img);
		$img_url_link 	= JWMMXTD_STARTURLPATH.$listdir ."/". rawurlencode( $img_file );
		$img_rel_path   = $listdir ."/". rawurlencode( $img_file );
		
		$cur = $listdir;
		
		$filesize = HTML_mmxtd::parse_size( $size );

		if ( ( $info[0] > 200 ) || ( $info[1] > 200 ) ) {
			$img_dimensions = HTML_mmxtd::imageResize($info[0], $info[1], 200);
		} else {
			$img_dimensions = 'style="width:'. $info[0] .'px;height:'. $info[1] .'px; margin:4px auto;display:block;"';
		}
?>

<!-- JoomlaWorks MEDIA MANAGER XTD - IMAGES -->
<div class="image_style">
  <table cellpadding="0" cellspacing="0">
    <tr>
      <td class="filename" colspan="2"><h2><a href="<?php echo $img_url_link; ?>" title="<?php echo $file; ?>" rel="lightbox[jwmmxtd-title]"> <?php echo htmlspecialchars(substr($file,0,20).(strlen($file) > 20 ? '...' : ''), ENT_QUOTES ); ?></a></h2></td>
    </tr>
    <tr>
      <td class="fileinfo"><?php echo _JWMEDIAMAN_FILESIZE; ?>: <?php echo $filesize; ?><br />
        <?php echo _JWMEDIAMAN_TEXT_WIDTH; ?>: <?php echo $info[0]; ?>px<br />
        <?php echo _JWMEDIAMAN_TEXT_HEIGHT; ?>: <?php echo $info[1]; ?>px</td>
      <td class="fileactions"><!-- edit -->
        <a href="index2.php?option=<?php echo JWMMXTD_COMP; ?>&task=edit&curdirectory=<?php echo $cur; ?>&img=<?php echo $img_file;?>" title="<?php echo _JWMEDIAMAN_ACT_EDIT; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/edit.png" alt="<?php echo _JWMEDIAMAN_ACT_EDIT; ?>" title="<?php echo _JWMEDIAMAN_ACT_EDIT; ?>" /></a>
        <!-- rename -->
        <a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $file ?>';document.adminForm.subtask.value='renamefile';document.adminForm.submit( );" title="<?php echo _JWMEDIAMAN_ACT_RENAME; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/rename.png" alt="<?php echo _JWMEDIAMAN_ACT_RENAME; ?>" title="<?php echo _JWMEDIAMAN_ACT_RENAME; ?>" /></a>
        <!-- copy -->
        <a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $file ?>';document.adminForm.subtask.value='copyfile';document.adminForm.submit( );" title="<?php echo _JWMEDIAMAN_ACT_COPY; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/copy.png" alt="<?php echo _JWMEDIAMAN_ACT_COPY; ?>" title="<?php echo _JWMEDIAMAN_ACT_COPY; ?>" /></a>
        <!-- move -->
        <a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $file ?>';document.adminForm.subtask.value='movefile';document.adminForm.submit( );" title="<?php echo _JWMEDIAMAN_ACT_MOVE; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/cut.png" alt="<?php echo _JWMEDIAMAN_ACT_MOVE; ?>" title="<?php echo _JWMEDIAMAN_ACT_MOVE; ?>" /></a>
        <!-- delete -->
        <a href="index2.php?option=<?php echo JWMMXTD_COMP; ?>&amp;task=delete&amp;delFile=<?php echo $file; ?>&amp;curdirectory=<?php echo $cur; ?>" onclick="javascript:if(confirm('<?php echo _JWMEDIAMAN_ALERT_DEL_FILE; ?><?php echo $file; ?>')) return true; return false;" title="<?php echo _JWMEDIAMAN_ACT_DELETE; ?>"> <img src="components/<?php echo JWMMXTD_COMP; ?>/images/delete.png" alt="<?php echo _JWMEDIAMAN_ACT_DELETE; ?>" title="<?php echo _JWMEDIAMAN_ACT_DELETE; ?>" /> </a></td>
    </tr>
  </table>
  <div class="fileimage"> <a href="<?php echo $img_url_link; ?>" rel="lightbox[jwmmxtd]" title="<b><?php echo _JWMEDIAMAN_ACT_SLIMBOX; ?></b><br /><?php echo $file; ?>" alt="<?php echo _JWMEDIAMAN_ACT_PREV; ?>"><img class="reflect" src="<?php echo $img_url_link; ?>?ok=ok" <?php echo $img_dimensions; ?> alt="<?php echo _JWMEDIAMAN_ACT_PREV; ?>" title="<?php echo _JWMEDIAMAN_ACT_PREV; ?>" /></a> </div>
</div>
<?php
	}

	function num_files($dir) {
		$total_file 	= 0;
		$total_dir 		= 0;
		$dir = JWMMXTD_STARTABSPATH . $dir;

		if(is_dir($dir)) {
			$d = dir($dir);
			
			while ( false !== ($entry = $d->read()) ) {
				if ( substr($entry,0,1) != '.' && is_file($dir . DIRECTORY_SEPARATOR . $entry) && strpos( $entry, '.html' ) === false && strpos( $entry, '.php' ) === false ) {
					$total_file++;
				}
				if ( substr($entry,0,1) != '.' && is_dir($dir . DIRECTORY_SEPARATOR . $entry) ) {
					$total_dir++;
				}
			}
			
			$d->close();
		}
		
		return array( $total_file, $total_dir );
	}
	
	function show_doc($doc, $size, $listdir, $icon) {
		$size 			= HTML_mmxtd::parse_size( $size );
		$doc_url_link 	= JWMMXTD_STARTURLPATH.$listdir."/".rawurlencode( $doc );
		
		$cur = $listdir;

?>

<!-- JoomlaWorks MEDIA MANAGER XTD - FILES -->
<div class="file_style">
  <table cellpadding="0" cellspacing="0">
    <tr>
      <td class="filename" colspan="2"><h2><?php echo htmlspecialchars( substr( $doc, 0, 14 ) . ( strlen($doc) > 14 ? '...' : ''), ENT_QUOTES ); ?></h2></td>
    </tr>
    <tr>
      <td class="fileinfo"><?php echo _JWMEDIAMAN_FILESIZE; ?>: <?php echo $size; ?></td>
      <td class="fileactions">
  		<?php if ($icon == "components/com_jwmmxtd/icons/zip.png") { ?>      
        <!-- unzip -->
        <a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $doc ?>';document.adminForm.subtask.value='unzipfile';document.adminForm.submit( );" title="<?php echo _JWMEDIAMAN_ACT_UNZIP; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/compress.png" alt="<?php echo _JWMEDIAMAN_ACT_UNZIP; ?>" title="<?php echo _JWMEDIAMAN_ACT_UNZIP; ?>" /></a>
		<?php } ?>        
        <!-- rename -->
        <a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $doc ?>';document.adminForm.subtask.value='renamefile';document.adminForm.submit( );" title="<?php echo _JWMEDIAMAN_ACT_RENAME; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/rename.png" alt="<?php echo _JWMEDIAMAN_ACT_RENAME; ?>" title="<?php echo _JWMEDIAMAN_ACT_RENAME; ?>" /></a>
        <!-- copy -->
        <a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $doc ?>';document.adminForm.subtask.value='copyfile';document.adminForm.submit( );" title="<?php echo _JWMEDIAMAN_ACT_COPY; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/copy.png" alt="<?php echo _JWMEDIAMAN_ACT_COPY; ?>" title="<?php echo _JWMEDIAMAN_ACT_COPY; ?>" /></a>
        <!-- move -->
        <a href="#" onclick="javascript:document.adminForm.selectedfile.value='<?php echo $doc ?>';document.adminForm.subtask.value='movefile';document.adminForm.submit( );" title="<?php echo _JWMEDIAMAN_ACT_MOVE; ?>"><img src="components/<?php echo JWMMXTD_COMP; ?>/images/cut.png" alt="<?php echo _JWMEDIAMAN_ACT_MOVE; ?>" title="<?php echo _JWMEDIAMAN_ACT_MOVE; ?>" /></a>
        <!-- delete -->
        <a href="index2.php?option=<?php echo JWMMXTD_COMP; ?>&amp;task=delete&amp;delFile=<?php echo $doc; ?>&amp;curdirectory=<?php echo $cur; ?>" onclick="javascript:if(confirm('<?php echo _JWMEDIAMAN_ALERT_DEL_FILE; ?><?php echo $doc; ?>')) return true; return false;" title="<?php echo _JWMEDIAMAN_ACT_DELETE; ?>"> <img src="components/<?php echo JWMMXTD_COMP; ?>/images/delete.png" alt="<?php echo _JWMEDIAMAN_ACT_DELETE; ?>" title="<?php echo _JWMEDIAMAN_ACT_DELETE; ?>" /></a> </td>
    </tr>
  </table>
  <div class="fileimage">
  <?php
  global $mosConfig_live_site,$mosConfig_absolute_path;
  if ($icon == "images/icons/flv.png") {
  ?>
    <a href="components/<?php echo JWMMXTD_COMP; ?>/js/flvplayer.swf?file=<?php echo $doc_url_link; ?>&amp;autostart=true&amp;allowfullscreen=true" rel="vidbox 800 600" title="<b><?php echo _JWMEDIAMAN_VB_FLV; ?></b><br /><?php echo $doc; ?>" alt="<?php echo _JWMEDIAMAN_VB_PREV; ?>"><img src="<?php echo $icon ?>" alt="<?php echo $doc; ?>" title="<?php echo _JWMEDIAMAN_VB_PREV; ?>" /></a>
    <?php
  } elseif ($icon == "images/icons/swf.png") {
  // get SWF dimensions
  $swfinfo = @getimagesize($doc_url_link);
  ?>
    <a href="<?php echo $doc_url_link; ?>" rel="vidbox <?php echo $swfinfo[0]; ?> <?php echo $swfinfo[1]; ?>" title="<b><?php echo _JWMEDIAMAN_VB_SWF; ?></b><br /><?php echo $doc; ?>" alt="<?php echo _JWMEDIAMAN_VB_PREV; ?>"><img src="<?php echo $icon ?>" alt="<?php echo $doc; ?>" title="<?php echo _JWMEDIAMAN_VB_PREV; ?>" /></a>
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
	
	function imageResize($width, $height, $target) {
		//takes the larger size of the width and height and applies the
		//formula accordingly...this is so this script will work
		//dynamically with any size image
		if ($width > $height) {
			$percentage = ($target / $width);
		} else {
			$percentage = ($target / $height);
		}

		//gets the new value and applies the percentage, then rounds the value
		$width = round($width * $percentage);
		$height = round($height * $percentage);

		//returns the new sizes in html image tag format...this is so you
		//can plug this function inside an image tag and just get the
		return "width=\"$width\" height=\"$height\"";

	}		
}
?>

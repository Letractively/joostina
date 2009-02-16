<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

global $mosConfig_absolute_path, $mosConfig_live_site;

$type			= $params->get( 'type', 'jpg' );
$folder		= $params->get( 'folder' );
$link			= $params->get( 'link' );
$width			= $params->get( 'width' );
$height		= $params->get( 'height' );
$the_array		= array();
$the_image		= array();

// if folder includes livesite info, remove
if ( strpos($folder, $mosConfig_live_site) === 0 ) {
	$folder = str_replace( $mosConfig_live_site, '', $folder );
}
// if folder includes absolute path, remove
if ( strpos($folder, $mosConfig_absolute_path) === 0 ) {
	$folder= str_replace( $mosConfig_absolute_path, '', $folder );
}
// if folder doesnt contain slash to start, add
if ( strpos($folder, '/') !== 0 ) {	
	$folder = '/'. $folder;
}
// construct absolute path to directory
$abspath_folder = $mosConfig_absolute_path . $folder;
// check if directory exists
if (is_dir($abspath_folder)) {
	if ($handle = opendir($abspath_folder)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..' && $file != 'CVS' && $file != 'index.html' ) {
				$the_array[] = $file;
			}
		}
	}
	closedir($handle);

	foreach ($the_array as $img) {
		if (!is_dir($abspath_folder .'/'. $img)) {
			if (eregi($type, $img)) {
				$the_image[] = $img;
			}
		}
	}

	if (!$the_image) {
		echo _NO_IMAGES;
	} else {

	$i = count($the_image);
	$random = mt_rand(0, $i - 1);
	$image_name = $the_image[$random];
		$abspath_image	= $abspath_folder . '/'. $image_name;
		$size			= getimagesize ($abspath_image);

	if ($width == '') {
			($size[0] > 100 ? $width = 100 : $width = $size[0]);
	}
	if ($height == '') {
		$coeff = $size[0]/$size[1];
		$height = (int) ($width/$coeff);
	}

	$image = $mosConfig_live_site . $folder .'/'. $image_name;

?>
	<div align="center"><?php
	if ($link) {?>
		<a href="<?php echo $link; ?>" target="_self"><?php
	}
?>
		<img src="<?php echo $image; ?>" border="0" width="<?php echo $width; ?>" height="<?php echo $height; ?>" alt="<?php echo $image_name; ?>" /><br />
<?php
	if ($link) {
?>
		</a>
<?php
	}
?>
	</div>
<?php
	}
}
?>

<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

function mosChmodRecursive($path,$filemode = null,$dirmode = null) {
	$ret = true;
	if(is_dir($path)) {
		$dh = opendir($path);
		while($file = readdir($dh)) {
			if($file != '.' && $file != '..') {
				$fullpath = $path.'/'.$file;
				if(is_dir($fullpath)) {
					if(!mosChmodRecursive($fullpath,$filemode,$dirmode)) $ret = false;
				} else {
					if(isset($filemode))
						if(!@chmod($fullpath,$filemode)) $ret = false;
				}
			}
		}
		closedir($dh);
		if(isset($dirmode))
			if(!@chmod($path,$dirmode)) $ret = false;
	} else {
		if(isset($filemode)) $ret = @chmod($path,$filemode);
	}
	return $ret;
}

function mosChmod($path) {
	$config = Jconfig::getInstance();

	$config->config_fileperms = trim($config->config_fileperms);
	$config->config_dirperms = trim($config->config_fileperms);
	$filemode = null;
	if($config->config_fileperms != '') {
		$filemode = octdec($config->config_fileperms);
	}
	$dirmode = null;
	if($config->config_dirperms != '') {
		$dirmode = octdec($config->config_dirperms);
	}
	return (isset($filemode) || isset($dirmode)) ? mosChmodRecursive($path,$filemode,$dirmode) : true;
}
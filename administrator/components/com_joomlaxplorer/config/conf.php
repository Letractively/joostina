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

//------------------------------------------------------------------------------
// Configuration Variables
	global $mosConfig_absolute_path,$mosConfig_live_site,$mosConfig_joomlaxplorer_dir;
	// login to use joomlaXplorer: (true/false)
	$GLOBALS["require_login"] = false;
	
	$GLOBALS["language"] = $mosConfig_lang;
	
	// the filename of the QuiXplorer script: (you rarely need to change this)
	if($_SERVER['SERVER_PORT'] == 443 ) {
		$GLOBALS["script_name"] = "https://".$GLOBALS['__SERVER']['HTTP_HOST'].$GLOBALS['__SERVER']["PHP_SELF"];
	}
	else {
		$GLOBALS["script_name"] = "http://".$GLOBALS['__SERVER']['HTTP_HOST'].$GLOBALS['__SERVER']["PHP_SELF"];
	}
	
	// allow Zip, Tar, TGz -> Only (experimental) Zip-support
	if( function_exists("gzcompress")) {
	  	$GLOBALS["zip"] = $GLOBALS["tgz"] = true;
	}
	else {
	  	$GLOBALS["zip"] = $GLOBALS["tgz"] = false;
	}

//------------------------------------------------------------------------------
// Global User Variables (used when $require_login==false)
	
	if( strstr( $mosConfig_absolute_path, "/" )) {
		$GLOBALS["separator"] = "/";
	}
	else {
		$GLOBALS["separator"] = "\\";
	}
	  
	// the home directory for the filemanager: (use '/', not '\' or '\\', no trailing '/')
	
	// !Note! This has been changed since joomlaXplorer 1.3.0
	// and now grants access to all directories for one level ABOVE this Site
	$dir_above = substr( $mosConfig_absolute_path, 0, strrpos( $mosConfig_absolute_path, $GLOBALS["separator"] ));
	if( !@is_readable($dir_above)) {
		$GLOBALS["home_dir"] = $mosConfig_joomlaxplorer_dir;
		// the url corresponding with the home directory: (no trailing '/')
		$GLOBALS["home_url"] = $mosConfig_live_site;
	}
	else {
		$GLOBALS["home_dir"] = $mosConfig_joomlaxplorer_dir;
		// the url corresponding with the home directory: (no trailing '/')
		$GLOBALS["home_url"] = substr( $mosConfig_live_site, 0, strrpos($mosConfig_live_site, '/'));
	}
	
	// show hidden files in QuiXplorer: (hide files starting with '.', as in Linux/UNIX)
	$GLOBALS["show_hidden"] = true;
	
	// filenames not allowed to access: (uses PCRE regex syntax)
	$GLOBALS["no_access"] = "^\.ht";
	
	// user permissions bitfield: (1=modify, 2=password, 4=admin, add the numbers)
	$GLOBALS["permissions"] = 7;
//------------------------------------------------------------------------------
/* NOTE:
	Users can be defined by using the Admin-section,
	or in the file "config/.htusers.php".
	For more information about PCRE Regex Syntax,
	go to http://www.php.net/pcre.pattern.syntax
*/
//------------------------------------------------------------------------------
?>

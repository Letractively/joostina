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

$GLOBALS['jx_home'] = 'http://joomlacode.org/gf/project/joomlaxplorer';

define ( "_QUIXPLORER_PATH", $mosConfig_absolute_path."/administrator/components/com_joomlaxplorer" );
define ( "_QUIXPLORER_URL", $mosConfig_live_site."/administrator/components/com_joomlaxplorer" );

$GLOBALS['ERROR'] = '';

$GLOBALS['__GET']	=&$_GET;
$GLOBALS['__POST']	=&$_POST;
$GLOBALS['__SERVER']	=&$_SERVER;
$GLOBALS['__FILES']	=&$_FILES;

if( file_exists(_QUIXPLORER_PATH."/languages/$mosConfig_lang.php"))
  require _QUIXPLORER_PATH."/languages/$mosConfig_lang.php";
else
  require _QUIXPLORER_PATH."/languages/russian.php";
  
if( file_exists(_QUIXPLORER_PATH."/languages/".$mosConfig_lang."_mimes.php"))
  require _QUIXPLORER_PATH."/languages/".$mosConfig_lang."_mimes.php";
else
  require _QUIXPLORER_PATH."/languages/russian_mimes.php";
  
// the filename of the QuiXplorer script: (you rarely need to change this)
if($_SERVER['SERVER_PORT'] == 443 ) {
	$GLOBALS["script_name"] = "https://".$GLOBALS['__SERVER']['HTTP_HOST'].$GLOBALS['__SERVER']["PHP_SELF"];
}
else {
	$GLOBALS["script_name"] = "http://".$GLOBALS['__SERVER']['HTTP_HOST'].$GLOBALS['__SERVER']["PHP_SELF"];
}
@session_start();
if( !isset( $_REQUEST['dir'] )) {
	$dir = $GLOBALS['dir'] = mosGetParam( $_SESSION,'jx_dir', '' );
}
else {
	$dir = $GLOBALS['dir'] = $_SESSION['jx_dir'] = mosGetParam( $_REQUEST, "dir" );
}


if( strstr( $mosConfig_absolute_path, "/" )) {
	$GLOBALS["separator"] = "/";
}
else {
	$GLOBALS["separator"] = "\\";
}
// Get Sort
if(isset($GLOBALS['__GET']["order"])) {
	$GLOBALS["order"]=stripslashes($GLOBALS['__GET']["order"]);
}
else {
	$GLOBALS["order"]="name";
}
if($GLOBALS["order"]=="") {
	$GLOBALS["order"]=="name";
}

// Get Sortorder (yes==up)
if(isset($GLOBALS['__GET']["srt"])) {
	$GLOBALS["srt"]=stripslashes($GLOBALS['__GET']["srt"]);
}
else {
	$GLOBALS["srt"]="yes";
}
if($GLOBALS["srt"]=="") {
	$GLOBALS["srt"]=="yes";
}
  
// show hidden files in QuiXplorer: (hide files starting with '.', as in Linux/UNIX)
$GLOBALS["show_hidden"] = true;

// filenames not allowed to access: (uses PCRE regex syntax)
$GLOBALS["no_access"] = "^\.ht";

// user permissions bitfield: (1=modify, 2=password, 4=admin, add the numbers)
$GLOBALS["permissions"] = 1;

$GLOBALS['file_mode'] = 'file';

require _QUIXPLORER_PATH."/config/mimes.php";
require _QUIXPLORER_PATH."/libraries/File_Operations.php";
require _QUIXPLORER_PATH."/include/fun_extra.php";
require _QUIXPLORER_PATH."/include/header.php";
require _QUIXPLORER_PATH."/include/footer.php";
require _QUIXPLORER_PATH."/include/error.php";

//------------------------------------------------------------------------------
$GLOBALS['jx_File'] = new jx_File();

$abs_dir=get_abs_dir($GLOBALS["dir"]);
if(!file_exists($GLOBALS["home_dir"])) {
  if(!file_exists($GLOBALS["home_dir"].$GLOBALS["separator"])) {
	if(!empty($GLOBALS["require_login"])) {
		$extra="<a href=\"".make_link("logout",NULL,NULL)."\">".
			$GLOBALS["messages"]["btnlogout"]."</A>";
	} 
	else {
		$extra=NULL;
	}
	$GLOBALS['ERROR'] = $GLOBALS["error_msg"]["home"];
  }
}
if(!down_home($abs_dir)) show_error($GLOBALS["dir"]." : ".$GLOBALS["error_msg"]["abovehome"]);
if(!is_dir($abs_dir))
  if(!is_dir($abs_dir.$GLOBALS["separator"]))
	$GLOBALS['ERROR'] = $abs_dir." : ".$GLOBALS["error_msg"]["direxist"];
//------------------------------------------------------------------------------

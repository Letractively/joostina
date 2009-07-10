<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// Установка родительского флага
define('_VALID_MOS',1);

if(!file_exists('../configuration.php')) {
	header('Location: ../installation/index.php');
	exit();
}

require ('../includes/globals.php');
require_once ('../configuration.php');

// SSL check - $http_host returns <live site url>:<port number if it is 443>
$http_host = explode(':',$_SERVER['HTTP_HOST']);
if((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off' || isset($http_host[1]) && $http_host[1] == 443) && substr($mosConfig_live_site,0,8) !='https://') {
	$mosConfig_live_site = 'https://' . substr($mosConfig_live_site,7);
}

require_once ($mosConfig_absolute_path . '/includes/joomla.php');
//include_once ($mosConfig_absolute_path . '/language/' . $mosConfig_lang . '.php');


// must start the session before we create the mainframe object
session_name(md5($mosConfig_live_site));
session_start();

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = mosMainFrame::getInstance(true);
$mainframe->set('lang', $mosConfig_lang);
include_once($mainframe->getLangFile());

require_once ($mosConfig_absolute_path.'/'.ADMINISTRATOR_DIRECTORY.'/includes/admin.php');

$act		= strtolower(mosGetParam($_REQUEST,'act',''));
$section	= mosGetParam($_REQUEST,'section','');
$no_html	= intval(mosGetParam($_REQUEST,'no_html',''));
$id			= intval(mosGetParam($_REQUEST,'id',0));
$mosmsg		= strval(strip_tags(mosGetParam($_REQUEST,'mosmsg','')));
$option		= strval(strtolower(mosGetParam($_REQUEST,'option','')));
$task		= strval(mosGetParam($_REQUEST,'task',''));



// admin session handling
$my = $mainframe->initSessionAdmin($option,$task);

// start the html output
if($no_html) {
	if($path = $mainframe->getPath('admin')) {
		//Подключаем язык компонента
 		if($mainframe->getLangFile($option)){ 
 			include($mainframe->getLangFile($option));        	
		}
		require $path;
	}
	exit;
}

initGzip();

?>
<?php echo "<?xml version=\"1.0\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $mosConfig_sitename; ?> - Joostina</title>
<link rel="stylesheet" href="templates/<?php echo $mainframe->getTemplate(); ?>/css/template_css.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $mainframe->getTemplate(); ?>/css/theme.css" type="text/css" />
<script language="JavaScript" src="../includes/js/JSCookMenu.js" type="text/javascript"></script>
<script language="JavaScript" src="includes/js/ThemeOffice/theme.js" type="text/javascript"></script>
<script language="JavaScript" src="../includes/js/joomla.javascript.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<?php
$mainframe->set('loadEditor',true);
include_once ($mosConfig_absolute_path . '/includes/editor.php');
initEditor();
?>
</head>
<body>
<?php
if($mosmsg) {
	if(!get_magic_quotes_gpc()) {
		$mosmsg = addslashes($mosmsg);
	}
	echo "\n<script language=\"javascript\" type=\"text/javascript\">alert('$mosmsg');</script>";
}

// Show list of items to edit or delete or create new
if($path = $mainframe->getPath('admin')) {
	require $path;
} else {
?>
	<img src="images/error.jpg" border="0" alt="Joostina!" />
	<br />
	<?php
}
?>
</body>
</html>
<?php
doGzip();
?>

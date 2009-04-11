<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// устанавливаем родительский флаг
define('_VALID_MOS',1);
// проверка файла конфигурации
if(!file_exists('../configuration.php')) {
	die('error-config-file');
}

// подключаем файл регистрации глобальных переменных и конфигурацию
require ('../includes/globals.php');
require_once ('../configuration.php');

// обработка безопасного режима
$http_host = explode(':',$_SERVER['HTTP_HOST']);
if((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off' || isset($http_host[1]) && $http_host[1] == 443) && substr($mosConfig_live_site,0,8) !='https://') {
	$mosConfig_live_site = 'https://' . substr($mosConfig_live_site,7);
}

// подключаем ядро и язык
require_once ($mosConfig_absolute_path.'/includes/joomla.php');
include_once ($mosConfig_absolute_path.'/language/'.$mosConfig_lang.'.php');
require_once ($mosConfig_absolute_path.'/'.ADMINISTRATOR_DIRECTORY.'/includes/admin.php');

// создаём сессии
session_name(md5($mosConfig_live_site));
session_start();


$option		= strval(strtolower(mosGetParam($_REQUEST,'option','')));
$task		= strval(mosGetParam($_REQUEST,'task',''));

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = mosMainFrame::getInstance(true);

$my = $mainframe->initSessionAdmin($option,$task);

if(!$my->id){
	die('error-my');
}

$commponent = str_replace('com_','',$option);

header("Content-type: text/html; charset=utf-8");
header ("Cache-Control: no-cache, must-revalidate ");
ob_start();

// проверяем, какой файл необходимо подключить, данные берутся из пришедшего GET запроса
if(file_exists($mosConfig_absolute_path . "/".ADMINISTRATOR_DIRECTORY."/components/$option/admin.$commponent.ajax.php")) {
	include_once ($mosConfig_absolute_path . "/".ADMINISTRATOR_DIRECTORY."/components/$option/admin.$commponent.ajax.php");
} else {
	die('error-inc-component');
}

$_ajax_body = ob_get_contents();
ob_end_clean();

echo $_ajax_body;

flush();
exit();
?>

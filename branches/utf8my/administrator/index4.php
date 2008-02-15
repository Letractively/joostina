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

// устанавливаем родительский флаг
define( '_VALID_MOS', 1 );
// проверка файла конфигурации
if (!file_exists( '../configuration.php' )) {
	die('NON config file');
}
// подключаем файл регистрации глобальных переменных и конфигурацию
require( '../globals.php' );
require_once( '../configuration.php' );
// обработка безопасного режима
$http_host = explode(':', $_SERVER['HTTP_HOST'] );
if( (!empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) != 'off' || isset( $http_host[1] ) && $http_host[1] == 443) && substr( $mosConfig_live_site, 0, 8 ) != 'https://' ) {
	$mosConfig_live_site = 'https://'.substr( $mosConfig_live_site, 7 );
}
// подключаем ядро и язык
require_once( $mosConfig_absolute_path . '/includes/joomla.php' );
include_once( $mosConfig_absolute_path . '/language/'. $mosConfig_lang. '.php' );
require_once( $mosConfig_absolute_path . '/administrator/includes/admin.php' );
// создаём сессии
session_name( md5( $mosConfig_live_site ) );
session_start();

$option		= strval( strtolower( mosGetParam( $_GET, 'option', '' ) ) );
$task		= strval( mosGetParam( $_GET, 'task', '' ) );

$mainframe	= new mosMainFrame( $database, $option, '..', true );
$my			= $mainframe->initSessionAdmin( $option, $task );

$commponent	= str_replace('com_','',$option);

header("Content-type: text/html; charset=utf-8");
ob_start();	

// проверяем, какой файл необходимо подключить, данные берутся из пришедшего GET запроса
if (file_exists( $mosConfig_absolute_path . "/administrator/components/$option/admin.$commponent.ajax.php" )) {
	include_once($mosConfig_absolute_path . "/administrator/components/$option/admin.$commponent.ajax.php");
} else {
	die('NON include component');
}

$_ajax_body = ob_get_contents();
ob_end_clean();
echo joostina_api::convert($_ajax_body,1);

?>
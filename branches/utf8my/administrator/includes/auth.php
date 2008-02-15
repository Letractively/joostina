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
require(dirname(__FILE__).'/../die.php');

$basePath 	= dirname( __FILE__ );
require( $basePath . '/../../globals.php' );

// $basepath reintialization required as globals.php will kill initial when RGs Emulation `Off` 
$basePath 	= dirname( __FILE__ );
require( $basePath . '/../../configuration.php' );

// SSL check - $http_host returns <live site url>:<port number if it is 443>
$http_host = explode(':', $_SERVER['HTTP_HOST'] );
if( (!empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) != 'off' || isset( $http_host[1] ) && $http_host[1] == 443) && substr( $mosConfig_live_site, 0, 8 ) != 'https://' ) {
	$mosConfig_live_site = 'https://'.substr( $mosConfig_live_site, 7 );
}

if (!defined( '_MOS_MAMBO_INCLUDED' )) {
	$path = $basePath . '/../../includes/joomla.php';
	require( $path );
}

session_name( md5( $mosConfig_live_site ) );
session_start();
// restore some session variables
if (!isset( $my )) {
	$my = new mosUser( $database );
}

$my->id 		= intval( mosGetParam( $_SESSION, 'session_user_id', '' ) );
$my->username 	= strval( mosGetParam( $_SESSION, 'session_username', '' ) );
$my->usertype 	= strval( mosGetParam( $_SESSION, 'session_usertype', '' ) );
$my->gid 		= intval( mosGetParam( $_SESSION, 'session_gid', '' ) );
$session_id 	= strval( mosGetParam( $_SESSION, 'session_id', '' ) );
$logintime 		= strval( mosGetParam( $_SESSION, 'session_logintime', '' ) );

if ( $session_id != md5( $my->id.$my->username.$my->usertype.$logintime ) ) {
	mosRedirect( 'index.php' );
	die;
}
?>

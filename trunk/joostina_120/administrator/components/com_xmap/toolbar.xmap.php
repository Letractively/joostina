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

// load language file
if( file_exists($GLOBALS['mosConfig_absolute_path'].'/administrator/components/com_xmap/language/'.$GLOBALS['mosConfig_lang'].'.php') ) {
	require_once( $GLOBALS['mosConfig_absolute_path'].'/administrator/components/com_xmap/language/'.$GLOBALS['mosConfig_lang'].'.php' );
} else {
	require_once( $GLOBALS['mosConfig_absolute_path'].'/administrator/components/com_xmap/language/english.php' );
}
// load html output class
require_once( $mainframe->getPath( 'toolbar_html' ) );

$act = mosGetParam( $_REQUEST, 'act', '' );
if ($act) {
	$task = $act;
}

switch ($task) {
	default:
		TOOLBAR_xmap::_DEFAULT();
		break;
}
?>

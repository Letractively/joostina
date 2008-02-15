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

require_once( $mainframe->getPath( 'toolbar_html' ) );

// handle the task
$act = mosGetParam( $_REQUEST, 'act', '' );
$task = mosGetParam( $_REQUEST, 'task', '' );

switch ($act){
	case "config":
		switch( $task ) {
			case "save":
				break;
			case "apply":
				TOOLBAR_jpack::_CONFIG();
				break;
			case "":
				TOOLBAR_jpack::_CONFIG();
				break;
			default:
				break;
		}
		break;
	case "pack":
			TOOLBAR_jpack::_PACK();	
		break;
	case "ajax":
		break;
	default:
			TOOLBAR_jpack::_PACK();		
		break;
}

?>

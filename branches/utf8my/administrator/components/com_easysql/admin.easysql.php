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

// разрешим доступ только пользователям с правами супер-администратора
if (!$acl->acl_check( 'administration', 'config', 'users', $my->usertype )) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

$cms = 'm';
$mosConfig_alang = $mosConfig_lang;

// include language file
$lang_path = dirname(__FILE__)."/lang";

include_once("$lang_path/russian.php");

// include html body
require_once( $mainframe->getPath( 'admin_html' ) );

// read params
$task	= mosGetParam( $_GET, 'task', '' );
$task	= empty($task) ? mosGetParam( $_POST, 'task', 'execsql' ) : $task;
$id		= mosGetParam( $_GET, 'id', null );
$table	= base64_decode(mosGetParam( $_GET, 'prm1', null ));
$sql	= mosGetParam( $_POST, 'easysql_query', null );
if (empty($table)) $table = mosGetParam( $_POST, 'easysql_table', null );



switch ($task) {
	case "tocsv" :
		//ExportToCSV($table);
		$url = $mosConfig_live_site.'/administrator/components/com_easysql/export.easysql.php?prm1=csv&prm2='.$cms.'&prm3='.base64_encode($table).'&prm4='.base64_encode($sql);
		echo "<script>document.location.href='$url';</script>\n";
		break;

	case "new" :
	case "edit" :
		EditRecord($task, $table, $id);
		break;

	case "delete" :
		if (!is_null($id)&&!is_null($table))
			if (DeleteRecord($table, $id)) ExecSQL($task);
		break;

	case "save" :
		if (SaveRecord()) ExecSQL($task);
		break;

	case "create" :
		if (InsertRecord()) ExecSQL($task);
		break;

	default :
		ExecSQL($task);
		break;
}


echo _ES_COPYRIGHT;

?>

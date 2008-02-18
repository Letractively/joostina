<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или LICENSE.php
* Joostina! - свободное программное обеспечение распостраняемое по условиям лицензиии GNU/GPL
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

// разрешим доступ только пользователям с правами супер-администратора
if(!$acl->acl_check('administration','config','users',$my->usertype)) {
	mosRedirect('index2.php',_NOT_AUTH);
}

// include html body
require_once ($mainframe->getPath('admin_html'));

// read params
$task	= mosGetParam($_REQUEST,'task','execsql');
$id		= mosGetParam($_GET,'id',null);
$table	= base64_decode(mosGetParam($_GET,'prm1',null));
$sql	= mosGetParam($_POST,'easysql_query',null);

if(empty($table)) $table = mosGetParam($_POST,'easysql_table',null);

switch($task) {
	case 'new':
	case 'edit':
		EditRecord($task,$table,$id);
		break;

	case 'delete':
		if(!is_null($id) && !is_null($table))
			if(DeleteRecord($table,$id)) ExecSQL($task);
		break;

	case 'save':
		if(SaveRecord()) ExecSQL($task);
		break;

	case 'create':
		if(InsertRecord()) ExecSQL($task);
		break;

	default:
		ExecSQL($task);
		break;
}

?>

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

// разрешим доступ только пользователям с правами супер-администратора
if (!$acl->acl_check( 'administration', 'config', 'users', $my->usertype )) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

$cms = 'm';
$mosConfig_alang = $mosConfig_lang;

// include language file
$lang_path = dirname(__FILE__).'/lang';

include_once($lang_path.'/russian.php');

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
	case 'tocsv' :
		ExportCSV($table, $sql);
		break;

	case 'new' :
	case 'edit' :
		EditRecord($task, $table, $id);
		break;

	case 'delete' :
		if (!is_null($id)&&!is_null($table))
			if (DeleteRecord($table, $id)) ExecSQL($task);
		break;

	case 'save' :
		if (SaveRecord()) ExecSQL($task);
		break;

	case 'create' :
		if (InsertRecord()) ExecSQL($task);
		break;

	default :
		ExecSQL($task);
		break;
}


echo _ES_COPYRIGHT;

function ExportCSV($table, $sql)
{
	ob_end_clean();
	$file_name = 'export_'.$table.'.csv';
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Accept-Ranges: bytes');
	header('Content-Disposition: attachment; filename='.basename($file_name).';');
	header('Content-Type: text/plain; '._ISO);
	header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Pragma: no-cache');

	echo ExportToCSV($sql);
	die();	// no need to send anything else
}

////////////////////////////////////////////////////////////////
// Export table to CSV format
////////////////////////////////////////////////////////////////
function ExportToCSV($sql)
{
	global $database;
	$csv_save = '';
	$database->setQuery( $sql );
	$rows = @$database->loadAssocList();
	if (!empty($rows)) {
		$comma = _ES_CSV_DELEMITER;
		$CR = "\r";
		// Make csv rows for field name
		$i=0;
		$fields = $rows[0];
		$cnt_fields = count($fields);
		$csv_fields = '';
		foreach($fields as $name=>$val) {
			$i++;
			if ($cnt_fields<=$i) $comma = '';
			$csv_fields .= $name.$comma;
		}
		// Make csv rows for data
		$csv_values = '';
		foreach($rows as $row) {
			$i=0;
			$comma = _ES_CSV_DELEMITER;
			foreach($row as $name=>$val) {
				$i++;
				if ($cnt_fields<=$i) $comma = '';
				$csv_values .= $val.$comma;
			}
			$csv_values .= $CR;
		}
		$csv_save = $csv_fields.$CR.$csv_values;
	}
	return $csv_save;
}
?>
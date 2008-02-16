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

define('_FILEPATH', dirname(__FILE__));
$parts = explode( "/", str_replace( '\\', "/", _FILEPATH ) );
array_pop( $parts ); array_pop( $parts ); array_pop( $parts );
define( '_SITEPATH', implode( "/", $parts )  );

define( "_VALID_MOS", 1 );
include_once( _SITEPATH.'/globals.php' );
require_once( _SITEPATH.'/configuration.php' );
require_once( _SITEPATH.'/includes/joomla.php' );

// include language file
$lang_path = dirname(__FILE__)."/lang";
if (!isset($mosConfig_alang)) {
	include_once("$lang_path/russian.php");
} else {
	if (file_exists ("$lang_path/$mosConfig_alang.php")) {		
		include_once ("$lang_path/$mosConfig_alang.php");
	}
}
$table = base64_decode($_GET['prm3']);
$file_name = 'export_'.$table.'.csv';
$xtype = "text/plain charset='us-ascii'";


header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Accept-Ranges: bytes");				
header("Content-Disposition: attachment; filename=".basename($file_name).";");
header('Content-Type: ' . $xtype);
header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Pragma: no-cache');

echo ExportToCSV($_GET['prm3']);

////////////////////////////////////////////////////////////////
// Export table to CSV format
////////////////////////////////////////////////////////////////
function ExportToCSV($table) {
global $database;
	$csv_save = '';
	$sql	= base64_decode($_GET['prm4']);
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
			$csv_fields .= "$name".$comma;
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

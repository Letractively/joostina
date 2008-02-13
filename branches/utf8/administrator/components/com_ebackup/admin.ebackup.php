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

// ensure user has access to this function
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_ebackup' ))){
   mosRedirect( 'index2.php', _NOT_AUTH );
}

require_once($mainframe->getPath('admin_html'));
require_once ($mosConfig_absolute_path."/administrator/components/com_ebackup/class.ebackup.php");
//require_once ($mosConfig_absolute_path."/administrator/components/com_ebackup/database.ebackup.php");

//Get right Language file
if (file_exists($mosConfig_absolute_path.'/administrator/components/com_ebackup/language/'.$mosConfig_lang.'.php')) {
  include($mosConfig_absolute_path.'/administrator/components/com_ebackup/language/'.$mosConfig_lang.'.php');
} else {
  include($mosConfig_absolute_path.'/administrator/components/com_ebackup/language/russian.php');
}

//include configuration file
if (file_exists($mosConfig_absolute_path.'/administrator/components/com_ebackup/config.ebackup.php')) {
  include($mosConfig_absolute_path.'/administrator/components/com_ebackup/config.ebackup.php');
} else {
  die ("Файл настроек не обнаружен (".$mosConfig_absolute_path."/administrator/components/com_ebackup/config.ebackup.php)");
}

if ($task<>'') {
    $func = $task;
} elseif ($act<>'') {
    $func = $act;
} else {
  $act = mosGetParam( $_REQUEST, 'act', "" );
  if ($act<>'') {
    $func = $act;
  } else {
    $func = '';
  }
}

switch ($func) {
       case 'doBackup':
           createBackup($option);
           break;
       case 'doCheck':
           checkDatabase($option, $func);
           break;
       case 'doAnalyze':
           checkDatabase($option, $func);
           break;
       case 'doOptimize':
           checkDatabase($option, $func);
           break;
       case 'doRepair':
           checkDatabase($option, $func);
           break;
       case 'doRestore':
           doRestore($option, $func);
           break;
       case 'viewTables':
           viewTables($option);
           break;
       case 'viewRestore':
           viewRestore($option);
           break;
       case 'viewSetup':
           viewSetup($option);
           break;
       case 'saveSettings':
           saveSettings($option);
           break;
       case 'viewInfo':
           viewInfo($option);
           break;
       case 'delete':
           del_file($option);
           break;
       default:
           viewTables($option);
           break;
}

  function doRestore(){
    global $mosConfig_absolute_path;


  }

  function viewInfo($option){
    global $mosConfig_absolute_path;

    $data_path = $mosConfig_absolute_path."/administrator/backups/";
    define ("MAX_LINE_LENGTH",65536);
    $SQLDump = new JFiler();
    $filename = $data_path."/".$_REQUEST['file'];

    $buffer = "";
    $info = "<div style=\"border: 1px solid #cccccc; padding: 5px; margin: 5px; font-family: COURIER NEW;\">";
    $info.= $SQLDump->getFileInfo($data_path."/".$_REQUEST['file']);
    $info.= "</div>";

    HTML_eBackup::showInfo($option, $info);
  }

  function del_file($option){
    global $mosConfig_absolute_path;

    $data_path = $mosConfig_absolute_path."/administrator/backups/";
    $file = mosGetParam( $_REQUEST, 'file', "" );
    unlink($data_path.$file);
    mosRedirect("index2.php?option=$option&amp;task=viewRestore", "");
  }

  function viewTables($option){
    global $database, $mosConfig_db, $mosConfig_dbprefix, $tab_filter;

    unset($_SESSION['tables']);
    unset($_SESSION['table']);
    unset($_SESSION['sql_file']);
    unset($_SESSION['rec_no']);
    unset($_SESSION['start_time']);

    if ($tab_filter){
       $sql = "SHOW TABLE STATUS FROM `".$mosConfig_db."` LIKE '".$mosConfig_dbprefix."%'";
    }else{
       $sql = "SHOW TABLE STATUS FROM `".$mosConfig_db."`";
    }
    $database->setQuery($sql);
    $table_lists = $database->loadObjectList();
    $i = 0;
    $lists='';
	$stats_list['rows'] = '';
	$stats_list['data'] = '';
	$stats_list['over'] = '';
    foreach($table_lists as $table){
       if ($table->Check_time != ""){
          $check_time = strftime(_BBKP_DATE_FORMAT_LC2, strtotime($table->Check_time));
       }else{
          $check_time = "";
       }
       $lists.= "<tr>\n"
               ."<td width=\"1%\"><input type=\"checkbox\" id=\"cb".$i++."\" name=\"tables[]\" value=\"".$table->Name."\" onclick=\"isChecked(this.checked);\" /></td>\n"
               ."<td>".$table->Name."</td>\n"
               ."<td align=\"right\">".number_format($table->Rows, 0, ',', '.')."</td>\n"
               ."<td align=\"right\">".mosGetSizes($table->Data_length)."</td>\n"
               ."<td align=\"right\">".mosGetSizes($table->Data_free)."</td>\n"
               ."<td align=\"right\">".number_format($table->Auto_increment, 0, ',', '.')."</td>\n"

               ."<td align=\"right\" style=\"white-space: nowrap;\">".strftime(_BBKP_DATE_FORMAT_LC2, strtotime($table->Create_time))."</td>\n"
               ."<td align=\"right\" style=\"white-space: nowrap;\">".$check_time."</td>\n"
               ."</tr>\n";
               $stats_list['rows'] = $stats_list['rows'] + $table->Rows;
               $stats_list['data'] = $stats_list['data'] + $table->Data_length;
               $stats_list['over'] = $stats_list['over'] + $table-> Data_free;
    }
    $stats_list['rows'] = number_format($stats_list['rows'], 0, ',', '.');
    $stats_list['data'] = mosGetSizes($stats_list['data']);
    $stats_list['over'] = mosGetSizes($stats_list['over']);
    HTML_eBackup::showTables($option, $lists, &$table_lists, $stats_list);
  }

  function readBackupDir($dirname, $sort = false){
	global $mosConfig_absolute_path;    
    $data_path = $mosConfig_absolute_path."/administrator/backups";
    $filelist=array();
    if ($dirhandle = opendir($dirname)){
       while (false !== ($dirfile = readdir($dirhandle))){
             if ($dirfile != "." && $dirfile != ".."){
                $path_parts = pathinfo($data_path."/".$dirfile);
                $file_ext = strtolower($path_parts["extension"]);
                if (($file_ext == "sql") || ($file_ext == "gz")) $filelist[] = $dirfile;
             }
       }
       if ((count($filelist) > 0) && ($sort)) rsort($filelist);
       return $filelist;
    }
  }

  function viewRestore($option){
    global $mosConfig_absolute_path;

    $data_path = $mosConfig_absolute_path."/administrator/backups";
    $list = "";

    $files = readBackupDir($data_path, true);
    if (is_array($files)){
       foreach ($files as $dirfile){
                $info_link = "<a href=\"index2.php?option=com_ebackup&amp;task=viewInfo&amp;file=$dirfile\">"
                            ."<img src=\"images/tick.png\" width=\"12\" height=\"12\" border=\"0\" alt=\""._BBKP_SQL_INFO."\" title=\""._BBKP_SQL_INFO."\" /></a>";
                $del_link = "<a href=\"index2.php?option=com_ebackup&amp;task=delete&amp;file=$dirfile\">"
                           ."<img src=\"images/publish_x.png\" width=\"12\" height=\"12\" border=\"0\" alt=\""._BBKP_DEL."\" title=\""._BBKP_DEL."\" /></a>";
                $download = "<a href=\"components/com_ebackup/download.ebackup.php?file=$dirfile\"><img src=\"images/filesave.png\" width=\"16\" height=\"16\" border=\"0\" alt=\""._BBKP_DOWNLOAD."\" title=\""._BBKP_DOWNLOAD."\" /></a>";
                $list.= "<tr>\n"
                       ."  <td><input type=\"radio\" name=\"rb\" value=\"".$dirfile."\" /></td>\n"
                       ."  <td>".$dirfile."</td>\n"
                       ."  <td align=\"right\" style=\"white-space: nowrap;\">".mosGetSizes(filesize($data_path."/".$dirfile))."</td>\n"
                       ."  <td align=\"right\" style=\"white-space: nowrap;\">".strftime(_BBKP_DATE_FORMAT_LC2, filemtime($data_path."/".$dirfile))."</td>\n"
                       ."  <td align=\"center\" style=\"white-space: nowrap;\">".$info_link."</td>\n"
                       ."  <td align=\"center\" style=\"white-space: nowrap;\">".$del_link."</td>\n"
                       ."  <td align=\"center\" style=\"white-space: nowrap;\">".$download."</td>\n"
                       ."</tr>\n";
       }
    }
    HTML_eBackup::showRestore($option, $list);
  }

  function viewSetup($option){
    global $autoinc, $drop, $exists, $sql_compat, $gzip, $full_inserts, $tab_filter, $run_time, $delaypersession, $email;

    $selections = array ('NONE', 'ANSI', 'DB2', 'MAXDB', 'MSSQL', 'MYSQL323', 'MYSQL40', 'ORACLE', 'POSTGRESQL', 'TRADITIONAL');

    $sql_comp_list = "<select name=\"sql_compat\" class=\"inputbox\" size=\"1\">\n";
    foreach ($selections as $selection){
            if ($sql_compat == $selection){
               $sql_comp_list.= "<option value=\"$selection\" selected=\"selected\">$selection</option>\n";
            }else{
               $sql_comp_list.= "<option value=\"$selection\">$selection</option>\n";
            }
    }
    $sql_comp_list.= "</select>\n";
    $forms['sql_comp_list'] = $sql_comp_list;

    if (!$drop){
       $forms['drop'] = "<input type=\"checkbox\" id=\"frm_drop\" name=\"frm_drop\" value=\"frm_drop\" />";
    }else{
       $forms['drop'] = "<input type=\"checkbox\" id=\"frm_drop\" name=\"frm_drop\" value=\"frm_drop\" checked />";
    }
    if (!$exists){
       $forms['exists'] = "<input type=\"checkbox\" id=\"frm_exists\" name=\"frm_exists\" value=\"frm_exists\" />";
    }else{
       $forms['exists'] = "<input type=\"checkbox\" id=\"frm_exists\" name=\"frm_exists\" value=\"frm_exists\" checked />";
    }
    if (!$autoinc){
       $forms['autoinc'] = "<input type=\"checkbox\" id=\"frm_autoinc\" name=\"frm_autoinc\" value=\"frm_autoinc\" />";
    }else{
       $forms['autoinc'] = "<input type=\"checkbox\" id=\"frm_autoinc\" name=\"frm_autoinc\" value=\"frm_autoinc\" checked />";
    }
    if (!$full_inserts){
       $forms['fullinserts'] = "<input type=\"checkbox\" id=\"frm_fullinserts\" name=\"frm_fullinserts\" value=\"frm_fullinserts\" />";
    }else{
       $forms['fullinserts'] = "<input type=\"checkbox\" id=\"frm_fullinserts\" name=\"frm_fullinserts\" value=\"frm_fullinserts\" checked />";
    }
    if (!$gzip){
       $forms['gzip'] = "<input type=\"checkbox\" id=\"frm_gzip\" name=\"frm_gzip\" value=\"frm_gzip\" onclick=\"javascript: if(document.adminForm.frm_gzip.checked){document.adminForm.frm_email.readOnly = false}else{document.adminForm.frm_email.readOnly = true} \" />";
    }else{
       $forms['gzip'] = "<input type=\"checkbox\" id=\"frm_gzip\" name=\"frm_gzip\" value=\"frm_gzip\" onclick=\"javascript: if(document.adminForm.frm_gzip.checked){document.adminForm.frm_email.readOnly = false}else{document.adminForm.frm_email.readOnly = true} \" checked />";
    }
    if (!$tab_filter){
       $forms['tab_filter'] = "<input type=\"checkbox\" id=\"frm_tab_filter\" name=\"frm_tab_filter\" value=\"frm_tab_filter\" />";
    }else{
       $forms['tab_filter'] = "<input type=\"checkbox\" id=\"frm_tab_filter\" name=\"frm_tab_filter\" value=\"frm_tab_filter\" checked />";
    }
    $forms['run_time']   = "<input type=\"text\" size=\"5\" id=\"frm_run_time\" name=\"frm_run_time\" value=\"$run_time\" maxlength=\"2\" />";
    $forms['delay_time'] = "<input type=\"text\" size=\"5\" id=\"frm_delay_time\" name=\"frm_delay_time\" value=\"$delaypersession\" maxlength=\"4\" />";
    if (!$gzip){
       $forms['email']      = "<input type=\"text\" size=\"35\" id=\"frm_email\" name=\"frm_email\" value=\"$email\" readonly />";
    }else{
       $forms['email']      = "<input type=\"text\" size=\"35\" id=\"frm_email\" name=\"frm_email\" value=\"$email\" />";
    }

    HTML_eBackup::showSetup($option, $forms);

  }

  function saveSettings($option){

    $conf_frm_drop        = mosGetParam($_POST, 'frm_drop', '');
    $conf_frm_exists      = mosGetParam($_POST, 'frm_exists', '');
    $conf_frm_autoinc     = mosGetParam($_POST, 'frm_autoinc', '');
    $conf_frm_fullinserts = mosGetParam($_POST, 'frm_fullinserts', '');
    $conf_frm_gzip        = mosGetParam($_POST, 'frm_gzip', '');
    $conf_frm_sql_compat  = mosGetParam($_POST, 'sql_compat', '');
    $conf_frm_tab_filter  = mosGetParam($_POST, 'frm_tab_filter', '');
    $conf_frm_run_time    = mosGetParam($_POST, 'frm_run_time', '');
    $conf_frm_delay_time  = mosGetParam($_POST, 'frm_delay_time', '');
    $conf_frm_email       = mosGetParam($_POST, 'frm_email', '');

    if (!$conf_frm_drop) $conf_frm_drop = 0; else $conf_frm_drop = 1;
    if (!$conf_frm_exists) $conf_frm_exists = 0; else $conf_frm_exists = 1;
    if (!$conf_frm_autoinc) $conf_frm_autoinc = 0; else $conf_frm_autoinc = 1;
    if (!$conf_frm_fullinserts) $conf_frm_fullinserts = 0; else $conf_frm_fullinserts = 1;
    if (!$conf_frm_tab_filter) $conf_frm_tab_filter = 0; else $conf_frm_tab_filter = 1;
    if (!$conf_frm_gzip) $conf_frm_gzip = 0; else $conf_frm_gzip = 1;
    if (!$conf_frm_run_time) $conf_frm_run_time = 0;
    $configtxt = "<?php\r\n"
                ."  \$drop            = ".$conf_frm_drop.";\r\n"
                ."  \$exists          = ".$conf_frm_exists.";\r\n"
                ."  \$autoinc         = ".$conf_frm_autoinc.";\r\n"
                ."  \$full_inserts    = ".$conf_frm_fullinserts.";\r\n"
                ."  \$gzip            = ".$conf_frm_gzip.";\r\n"
                ."  \$sql_compat      = \"".$conf_frm_sql_compat."\";\r\n"
                ."  \$tab_filter      = ".$conf_frm_tab_filter.";\r\n"
                ."  \$run_time        = ".$conf_frm_run_time.";\r\n"
                ."  \$delaypersession = ".$conf_frm_delay_time.";\r\n"
                ."  \$email           = \"".$conf_frm_email."\";\r\n"
                ."?>\r\n";

    $configfile = "components/".$option."/config.ebackup.php";
    clearstatcache();
    @chmod ($configfile, 0777);
    if ($fp = fopen("$configfile", "w+")) {
       fputs($fp, $configtxt, strlen($configtxt));
       fclose ($fp);
    }
    $mosmsg = "Настройки сохранены!";
    mosRedirect("index2.php?option=$option&task=viewSetup",$mosmsg);
  }

  function checkDatabase($option, $func){
    global $tables, $database;
		$i=0;
    $tables = mosGetParam($_POST, 'tables', '');
    if (is_array($tables)){
       switch ($func) {
             case 'doCheck':
                  $sql   = "CHECK TABLE ";
                  $title = "Результаты проверки";
                  break;
             case 'doAnalyze':
                  $sql   = "ANALYZE TABLE ";
                  $title = "Результаты анализа";
                  break;
             case 'doOptimize':
                  $sql   = "OPTIMIZE TABLE ";
                  $title = "Результаты оптимизации";
                  break;
             case 'doRepair':
                  $sql   = "REPAIR TABLE ";
                  $title = "Результаты исправления";
                  break;
       }
       foreach ($tables as $table){
               $i++;
               if ($i != count($tables)){
                  $sql.= "`".$table."`, ";
               }else{
                  $sql.= "`".$table."`";
               }
       }
       $database->setQuery($sql);
       $result_msgs = $database->loadObjectList();
			$list='';
		$results = false;
       if (!$results){
          foreach ($result_msgs as $result_msg){
                  $list.= "<tr>\n"
                         ."  <td align=\"left\" style=\"white-space: nowrap;\">".$result_msg->Table."</td>\n"
                         ."  <td align=\"left\" style=\"white-space: nowrap;\">".$result_msg->Op."</td>\n"
                         ."  <td align=\"left\" style=\"white-space: nowrap;\">".$result_msg->Msg_type."</td>\n"
                         ."  <td align=\"left\" style=\"white-space: nowrap;\">".$result_msg->Msg_text."</td>\n"
                         ."</tr>\n";
      	$results = true;    
          }
          HTML_eBackup::showCheckResults($list, $title);
       }
    }
  }

  function createBackup($option){
    global $tables, $mosConfig_absolute_path, $mosConfig_db, $database, $email,
           $autoinc, $drop, $exists, $sql_compat, $gzip, $fullinserts, $run_time,
           $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_sitename;

    $tstart      = mosProfiler::getmicrotime();
    $SQLDump     = new JFiler($gzip);

    if (!isset($_POST['tables'])){
       $tables = mosGetParam($_SESSION, 'tables', '');
    }else{
       $tables             = mosGetParam($_POST, 'tables', '');
       $_SESSION['tables'] = $tables;
    }

    if (!isset($_SESSION['start_time'])){
       $_SESSION['start_time'] = $tstart;
    }else{
       $tstart = $_SESSION['start_time'];
    }

    if (isset($_SESSION['table'])){
       $table = $_SESSION['table'];
       $key   = array_search($table, $tables);
    }else{
       $key = 0;
    }

    if (isset($_SESSION['rec_no'])){
       $rec_no = mosGetParam($_SESSION, 'rec_no', '');
    }else{
       $rec_no = 0;
    }

    if (!isset($_SESSION['sql_file'])){
       $sql_time = time();
       $sql_file = $mosConfig_db."_".strftime("%Y%m%d_%H%M%S", $sql_time).".sql";
       $_SESSION['sql_file'] = $sql_file;
       $SQLDump->createFile($mosConfig_absolute_path."/administrator/backups/".$sql_file);
       makeHeaderTableDef($mosConfig_db, $sql_time, &$SQLDump, count($tables));
    }else{
       $sql_file = mosGetParam($_SESSION, 'sql_file');
       $SQLDump->openFile($mosConfig_absolute_path."/administrator/backups/".$sql_file);
    }

    $startTime = mosProfiler::getmicrotime();
    while ($key < count($tables)){
          $checkTime = mosProfiler::getmicrotime();
          if (($checkTime - $startTime) >= $run_time){
             $_SESSION['table']  = $tables[$key-1];
             $_SESSION['rec_no'] = 0;
             HTML_eBackup::showProcess($tables[$key], $rec_no, $sql_file, $key, count($tables));
             $link = "index2.php?option=com_ebackup&task=doBackup";
             echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"".$link."\";',500+$delaypersession);</script>\n";
             flush();
             return;
          }else{
             if ($rec_no == 0) makeTableDef($mosConfig_db, $tables[$key], &$SQLDump);
             if (!makeTableContent($tables[$key], &$rec_no, &$SQLDump, $startTime)) break;
             $key++;
          }
    }

    if ($key < count($tables)){
       HTML_eBackup::showProcess($tables[$key], $rec_no, $sql_file, $key, count($tables));
    }else{
       $SQLDump->closeFile();
       $bkp_filesize = mosGetSizes($SQLDump->getFileSize());
       if ($SQLDump->zipped){
          $bkp_file = $bkp_file.".gz";
          $attachement = $SQLDump->filename.".gz";
       }else{
          $attachement = $SQLDump->filename;
       }
       $body = makeBody($mosConfig_db, time(), count($tables));
       unset($_SESSION['tables']);
       unset($_SESSION['table']);
       unset($_SESSION['sql_file']);
       unset($_SESSION['rec_no']);
       unset($_SESSION['start_time']);
       $tend         = mosProfiler::getmicrotime();
       $backup_stat  = number_format(($tend - $tstart), 5, ',', '.')." "._BBKP_SECONDS;
       //function mosMail( $from, $fromname, $recipient, $subject, $body, $mode=0, $cc=NULL, $bcc=NULL, $attachment=NULL, $replyto=NULL, $replytoname=NULL ) {
       if (($email != "") && ($gzip)) {
           if (!$can_send = mosMail( mosConfig_mailfrom, $mosConfig_fromname, $email, "Бэкап ".$mosConfig_sitename, $body, true, NULL, NULL, $attachement, "", "")) echo "Отправлено";
       }
       HTML_eBackup::showResults($bkp_filesize, $sql_file, $backup_stat, count($tables));
    }

  }

  function makeBody($item, $bkp_time, $tab_count){
    global $autoinc, $drop, $exists, $sql_compat, $gzip, $full_inserts;
    $crlf = "<br />";
	$header = '';
    if ($drop) $xdrop       = "x";
    if ($exists) $xexists   = "x";
    if ($autoinc) $xautoinc = "x";
    $header.= "# ===============================================================$crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_HEAD_1."$crlf";
    $header.= "# "._BBKP_HEAD_4."$crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_HEAD_2." : $item $crlf";
    $header.= "# "._BBKP_HEAD_3." : ".strftime(_DATE_FORMAT_LC3, $bkp_time)."$crlf";
    $header.= "# "._BBKP_HEAD_7."           : $tab_count $crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_ENVIRONMENT."$crlf";
    $header.= "#   "._BBKP_SQL_SERVER."                : ".@mysql_get_server_info()."$crlf";
    $header.= "#   "._BBKP_SQL_CLIENT."                : ".@mysql_get_client_info()."$crlf";
    $header.= "#   "._BBKP_PHP_VERSION."                 : ".phpversion()."$crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_SETTINGS."$crlf";
    $header.= "#   "._BBKP_DROP.": [".$xdrop."]$crlf";
    $header.= "#   "._BBKP_EXISTS.": [".$xexists."]$crlf";
    $header.= "#   "._BBKP_DB_AUTO_INC.": [".$xautoinc."]$crlf";
    $header.= "#   "._BBKP_DB_COMP.": ".$sql_compat."$crlf";
    $header.= "# $crlf";
    $header.= "# ===============================================================$crlf$crlf";
    return $header;
  }

  function makeHeaderTableDef($item, $bkp_time, &$SQLDump, $tab_count){
    global $autoinc, $drop, $exists, $sql_compat, $gzip, $full_inserts;
    $crlf = "\r\n";
	$header='';
    if ($drop) $xdrop       = "x";
    if ($exists) $xexists   = "x";
    if ($autoinc) $xautoinc = "x";
    $header.= "# ===============================================================$crlf";
    $header.= "# "._BBKP_HEAD_1."$crlf";
    $header.= "# "._BBKP_HEAD_4."$crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_HEAD_2." : $item $crlf";
    $header.= "# "._BBKP_HEAD_3." : ".strftime(_DATE_FORMAT_LC3, $bkp_time)."$crlf";
    $header.= "# "._BBKP_HEAD_7."           : $tab_count $crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_ENVIRONMENT."$crlf";
    $header.= "#   "._BBKP_SQL_SERVER."                : ".@mysql_get_server_info()."$crlf";
    $header.= "#   "._BBKP_SQL_CLIENT."                : ".@mysql_get_client_info()."$crlf";
    $header.= "#   "._BBKP_PHP_VERSION."                 : ".phpversion()."$crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_SETTINGS."$crlf";
    $header.= "#   "._BBKP_DROP.": [".$xdrop."]$crlf";
    $header.= "#   "._BBKP_EXISTS.": [".$xexists."]$crlf";
    $header.= "#   "._BBKP_DB_AUTO_INC.": [".$xautoinc."]$crlf";
    $header.= "#   "._BBKP_DB_COMP.": ".$sql_compat."$crlf";
    $header.= "# $crlf";
    $header.= "# ===============================================================$crlf$crlf";
    $SQLDump->writeFile($header);
  }

  function makeTableDef($base, $table, &$SQLDump){
    global $database, $autoinc, $drop, $exists, $sql_compat;
    $crlf="\r\n";

    $create = "";
    if ((mosGetMySQLVersionShort() >= 40100) && ($sql_compat != 'NONE')) {
        $database->setQuery('SET @@SESSION.SQL_MODE="'.$sql_compat.'"');
        $database->Query();
    }

    $result = $database->setQuery("SHOW CREATE TABLE `".$table."`");
    $rows   = $database->loadrow();
    $create_query = $rows[1];

    if (strpos($create_query, "(\r\n ")) {
       $create_query = str_replace("\r\n", $crlf, $create_query);
    }elseif (strpos($create_query, "(\n ")) {
       $create_query = str_replace("\n", $crlf, $create_query);
    }elseif (strpos($create_query, "(\r ")) {
       $create_query = str_replace("\r", $crlf, $create_query);
    }


    if ($drop != "") $create = "DROP TABLE ";
    if (($exists != "") && ($drop != "")) $create.= "IF EXISTS ";
    if (($drop != "") || ($exists != "")) $create.= "`".$table."`;".$crlf;
    $create.= $create_query;

    //$result = $database->setQuery("SHOW TABLE STATUS FROM `".$base."` LIKE '".$table."'");
    //$stats  = $database->loadObjectList();
    //$stat   = $stats[0];
    //$auto_inc = $stat->Auto_increment;
	$create .= '  DEFAULT CHARSET=cp1251 ';
    //if (($stat->Auto_increment != "") && ($autoinc)){
     //  $create.= " AUTO_INCREMENT=".$stat->Auto_increment.";".$crlf.$crlf;
    //}else{
       $create.=";".$crlf.$crlf;
    //}

    $header = "# ===============================================================$crlf"
             ."# "._BBKP_HEAD_5." `$table` $crlf"
             ."# ===============================================================$crlf$crlf";
    $SQLDump->writeFile($header.$create);
  }

  function makeTableContent($table, &$rec_no, &$SQLDump, $startTime){
    global $database, $run_time, $full_inserts, $delaypersession;

    $crlf="\r\n";
    if ($rec_no == 0){
       $header = "# ===============================================================$crlf"
                ."# "._BBKP_HEAD_6." `$table $crlf"
                ."# ===============================================================$crlf$crlf";
       $SQLDump->writeFile($header);
    }

    $sql    = "SELECT * FROM ".$table;
    $database->setQuery($sql);
    $result = $database->query();

    if ($rec_no <> 0){
        $rec_i = 1;
        while ($row = mysql_fetch_assoc($result)){
              if ($rec_i == $rec_no) break;
              $rec_i++;
        }
    }

    while ($row = mysql_fetch_assoc($result)){
          $rec_no++;

          if ($full_inserts) {
             $item_list = "(";
             foreach ($row as $col => $value) {
                     $item_list.= "`".mysql_escape_string($col)."`, ";
             }
             $item_list = substr($item_list, 0, -2);
             $item_list.= ")";
             $insert = "INSERT INTO `$table` $item_list VALUES (";
          } else{
             $insert = "INSERT INTO `$table` VALUES (";
          }
          $data = "";
          foreach ($row as $value) {
                if (!isset($value)) {
                   $data.= " NULL,";
                } elseif ($value != "") {
                   $data.= " '".mysql_escape_string($value)."',";
                } else {
                   $data.= " '',";
                }
          }
          $insert.= ereg_replace(",$", "", $data);
          $insert.= ");".$crlf;
          $SQLDump->writeFile($insert);
          $checkTime = mosProfiler::getmicrotime();
          if (($checkTime - $startTime) >= $run_time){
             $_SESSION['table']  = $table;
             $_SESSION['rec_no'] = $rec_no;
             $link = "index2.php?option=com_ebackup&task=doBackup";
             echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"".$link."\";',500+$delaypersession);</script>\n";
             flush();
             return false;
          }
    }
    $SQLDump->writeFile($crlf);
    $_SESSION['rec_no'] = 0;
    $rec_no = 0;
    return true;
  }

  function mosGetSizes($size) {
    if ($size<1024)
       $size=number_format(Round($size,3), 0, ',', '.')." B";
    elseif ($size < 1048576)
       $size=number_format(Round($size/1024,3), 2, ',', '.')." KB";
    elseif ($size < 1073741824)
       $size=number_format(Round($size/1048576,3), 2, ',', '.')." MB";
    elseif (1073741824 < $size)
       $size=number_format(Round($size/1073741824,3), 2, ',', '.')." GB";
    elseif (1099511627776 < $size)
       $size=number_format(Round($size/1099511627776,3), 2, ',', '.')." TB";
    return $size;
  }

  function mosGetMySQLVersionShort(){
    if (!function_exists("mysql_get_server_info")){
       $mysql_s=PMBP_I_NO_RES;
    }else{
       $mysql_s=@mysql_get_server_info();
    }
    $mysql_s = substr(str_replace(".", "", $mysql_s)."00000", 0, 5);
    return $mysql_s;
  }

?>

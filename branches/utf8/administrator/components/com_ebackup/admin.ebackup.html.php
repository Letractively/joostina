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

class HTML_eBackup {

     function showTables($option, $list, &$table_lists, $stats_list){
     global $mosConfig_live_site;
       $content = "<form action=\"index2.php?option=com_ebackup\" method=\"post\" name=\"adminForm\">\n"
                 ."<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
                 ."  <tr>\n"
                 ."    <td width=\"100%\" align=\"left\">\n"
                 ."      <img src=\"".$mosConfig_live_site."/administrator/components/com_ebackup/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
                 ."      <font style=\"font-size : 18px;font-weight: bold;text-align: left;\">Управление базой данных</font>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."</table>\n"
                 ."<table class=\"adminlist\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">\n"
                 ."  <tr>\n"
                 ."    <th width=\"1%\"><input type=\"checkbox\" name=\"toggle\" value=\"\" onclick=\"checkAll(".count($table_lists).");\" /></th>\n"
                 ."    <th align=\"left\">Таблицы</th>\n"
                 ."    <th width=\"5%\">"._BBKP_LINES."</th>\n"
                 ."    <th width=\"5%\">"._BBKP_SIZES."</th>\n"
                 ."    <th width=\"5%\">"._BBKP_OVERHEAD."</th>\n"
                 ."    <th width=\"5%\">"._BBKP_AUTO_INC."</th>\n"
                 ."    <th width=\"5%\">"._BBKP_CREATE_TIME."</th>\n"
                 ."    <th width=\"5%\">"._BBKP_CHECK_TIME."</th>\n"
                 ."  </tr>\n"
                 ."  ".$list
                 ."  <tr>\n"
                 ."    <th colspan=\"2\">&nbsp;</th>\n"
                 ."    <th align=\"right\">".$stats_list['rows']."</th>\n"
                 ."    <th align=\"right\">".$stats_list['data']."</th>\n"
                 ."    <th align=\"right\">".$stats_list['over']."</th>\n"
                 ."    <th colspan=\"3\">&nbsp;</th>\n"
                 ."  </tr>\n"
                 ."</table>\n"
                 ."  <input type=\"hidden\" name=\"option\" value=\"$option\" />\n"
                 ."  <input type=\"hidden\" name=\"task\" value=\"\" />\n"
                 ."  <input type=\"hidden\" name=\"boxchecked\" value=\"0\" />\n"
                 ."</form>\n";
       echo $content;
     }

     function showProcess($table, $rec_no, $sql_file, $key, $act_tab){
       global $delaypersession,$mosConfig_live_site;

       $content = "<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
                 ."  <tr>\n"
                 ."    <td width=\"100%\">\n"
                 ."      <img src=\"".$mosConfig_live_site."/administrator/components/com_ebackup/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
                 ."      <font style=\"font-size : 18px;font-weight: bold;text-align: left;\">eBackup - Backup...</font>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."  <tr>\n"
                 ."    <td>\n"
                 ."      <table class=\"adminlist\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n"
                 ."        <tr>\n"
                 ."          <th colspan=\"2\">"._BBKP_BACKUP_WORKING."</th>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <td>"._BBKP_BACKUP_TABLE.":</td>\n"
                 ."          <td>".$table."</td>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <td>"._BBKP_BACKUP_RECORD.":</td>\n"
                 ."          <td>".number_format($rec_no, 0, ',', '.')."</td>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <td>"._BBKP_FILENAME.":</td>\n"
                 ."          <td>".$sql_file."</td>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <td>"._BBKP_TABLES.":</td>\n"
                 ."          <td>".$key." / ".$act_tab."</td>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <td>"._BBKP_DELAY_TIME.":</td>\n"
                 ."          <td>".$delaypersession." ms</td>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <th colspan=\"2\">&nbsp;</th>\n"
                 ."        </tr>\n"
                 ."      </table>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."</table>\n";
       echo $content;
     }

     function showResults($bkp_filesize, $bkp_file, $backup_stat, $backup_count){
     	global $mosConfig_live_site;
       $content = "<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
                 ."  <tr>\n"
                 ."    <td width=\"100%\" align=\"left\">\n"
                 ."      <img src=\"".$mosConfig_live_site."/administrator/components/com_ebackup/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
                 ."      <font style=\"font-size : 18px;font-weight: bold;text-align: left;\">База данных - результаты бэкапа</font>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."  <tr>\n"
                 ."    <td>\n"
                 ."      <table class=\"adminlist\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n"
                 ."        <tr>\n"
                 ."          <th colspan=\"2\">&nbsp;</th>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <td>"._BBKP_FILESIZE."</td>\n"
                 ."          <td>".$bkp_filesize."</td>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <td>"._BBKP_FILENAME."</td>\n"
                 ."          <td>".$bkp_file."</td>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <td>"._BBKP_TABLES."</td>\n"
                 ."          <td>".$backup_count."</td>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <td>"._BBKP_TIME."</td>\n"
                 ."          <td>".$backup_stat."</td>\n"
                 ."        </tr>\n"
                 ."        <tr>\n"
                 ."          <th colspan=\"2\">&nbsp;</th>\n"
                 ."        </tr>\n"
                 ."      </table>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."</table>\n";
       echo $content;
     }

     function showRestore($option, $list){
		global $mosConfig_live_site;
       $content = "<form action=\"index2.php?option=com_ebackup\" method=\"post\" name=\"adminForm\">\n"
                 ."<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
                 ."  <tr>\n"
                 ."    <td width=\"100%\" align=\"left\">\n"
                 ."      <img src=\"".$mosConfig_live_site."/administrator/components/com_ebackup/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
                 ."      <font style=\"font-size : 18px;font-weight: bold;text-align: left;\">База данных - управление сохранёнными файлами базы</font>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."</table>\n"
                 ."<table class=\"adminlist\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">\n"
                 ."  <tr>\n"
                 ."    <th width=\"1%\">&nbsp;</th>\n"
                 ."    <th align=\"left\">"._BBKP_FILENAME."</th>\n"
                 ."    <th width=\"10%\" align=\"right\">"._BBKP_SIZES."</th>\n"
                 ."    <th width=\"10%\">"._BBKP_DATE."</th>\n"
                 ."    <th width=\"2%\">&nbsp;</th>\n"
                 ."    <th width=\"2%\">&nbsp;</th>\n"
                 ."    <th width=\"2%\">&nbsp;</th>\n"
                 ."  </tr>\n"
                 ."  ".$list
                 ."  <tr>\n"
                 ."    <th colspan=\"8\">&nbsp;</th>\n"
                 ."  </tr>\n"
                 ."</table>\n"
                 ."  <input type=\"hidden\" name=\"option\" value=\"$option\" />\n"
                 ."  <input type=\"hidden\" name=\"task\" value=\"\" />\n"
                 ."  <input type=\"hidden\" name=\"boxchecked\" value=\"0\" />\n"
                 ."</form>\n";
       echo $content;
     }

     function showSetup($option, $forms){
       global $drop, $exists, $mosConfig_dbprefix,$mosConfig_live_site;;

       $content = "<form action=\"index2.php?option=$option\" method=\"post\" name=\"adminForm\">\n"
                 ."<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
                 ."  <tr>\n"
                 ."    <td style=\"border-bottom: 1px solid #CCCCCC;\" width=\"100%\" align=\"left\">\n"
                 ."      <img src=\"".$mosConfig_live_site."/administrator/components/com_ebackup/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
                 ."      <font style=\"font-size : 18px;font-weight: bold;text-align: left;\">База данных - Настройки</font>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."</table>\n"
                 ."<table border=\"0\" class=\"contentpaneopen\" width=\"100%\" align=\"center\">\n"
                 ."  <tr>\n"
                 ."    <td><br />\n"
                 ."      <table border=\"0\" width=\"100%\" align=\"center\">\n"
                 ."        <tr>\n"
                 ."          <td>\n"
                 ."            <table border=\"0\" class=\"adminlist\">\n"
                 ."              <tr>\n"
                 ."                <th colspan=\"2\" align=\"left\">"._BBKP_SETUP_TITLE."</th>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <td width=\"40%\">"._BBKP_DROP."</td>\n"
                 ."                <td>".$forms['drop']."</td>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <td>"._BBKP_EXISTS."</td>\n"
                 ."                <td>".$forms['exists']."</td>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <td>"._BBKP_AUTOINCREMENT."</td>\n"
                 ."                <td>".$forms['autoinc']."</td>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <td>"._BBKP_FULL_INSERTS."</td>\n"
                 ."                <td>".$forms['fullinserts']."</td>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <td>"._BBKP_GZIP."</td>\n"
                 ."                <td>".$forms['gzip']."</td>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <td>"._BBKP_DB_COMP."</td>\n"
                 ."                <td>".$forms['sql_comp_list']."</td>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <td>"._BBKP_TABLE_FILTER." (".$mosConfig_dbprefix.")</td>\n"
                 ."                <td>".$forms['tab_filter']."</td>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <td>"._BBKP_RUN_TIME."</td>\n"
                 ."                <td>".$forms['run_time']."</td>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <td>"._BBKP_DELAY_TIME." (ms)</td>\n"
                 ."                <td>".$forms['delay_time']."</td>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <th colspan=\"2\">&nbsp;</th>\n"
                 ."              </tr>\n"
                 ."            </table>\n"
                 ."          </td>\n"
                 ."        </tr>\n"
                 ."      </table>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."</table>\n"
                 ."  <input type=\"hidden\" name=\"option\" value=\"$option\" />\n"
                 ."  <input type=\"hidden\" name=\"task\" value=\"\" />\n"
                 ."  <input type=\"hidden\" name=\"boxchecked\" value=\"0\" />\n"
                 ."</form>\n";
       echo $content;
     }

     function showInfo($option, $info){
		global $mosConfig_live_site;
       $content = "<form action=\"index2.php?option=com_ebackup\" method=\"post\" name=\"adminForm\">\n"
                 ."<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
                 ."  <tr>\n"
                 ."    <td style=\"border-bottom: 1px solid #CCCCCC;\" width=\"100%\">\n"
                 ."      <img src=\"".$mosConfig_live_site."/administrator/components/com_ebackup/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
                 ."      <font style=\"font-size : 18px;font-weight: bold;text-align: left;\">eBackup - Backupfile Info</font>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."</table>\n"
                 ."<table border=\"0\" class=\"contentpaneopen\" width=\"50%\">\n"
                 ."  <tr>\n"
                 ."    <td><br>\n"
                 ."      <table border=\"0\" width=\"80%\" align=\"center\">\n"
                 ."        <tr>\n"
                 ."          <td>\n"
                 ."            <table border=\"0\" class=\"adminlist\" width=\"60%\">\n"
                 ."              <tr>\n"
                 ."                <th align=\"center\">"._BBKP_SQL_INFO."</th>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <td>".$info."</td>\n"
                 ."              </tr>\n"
                 ."              <tr>\n"
                 ."                <th>&nbsp;</th>\n"
                 ."              </tr>\n"
                 ."            </table>\n"
                 ."          </td>\n"
                 ."        </tr>\n"
                 ."      </table>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."</table>\n"
                 ."  <input type=\"hidden\" name=\"option\" value=\"$option\" />\n"
                 ."  <input type=\"hidden\" name=\"task\" value=\"\" />\n"
                 ."  <input type=\"hidden\" name=\"boxchecked\" value=\"0\" />\n"
                 ."</form>\n";
       echo $content;
     }

     function showCheckResults($list, $title){
		global $mosConfig_live_site;
       $content = "<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
                 ."  <tr>\n"
                 ."    <td width=\"100%\" align=\"left\">\n"
                 ."      <img src=\"".$mosConfig_live_site."/administrator/components/com_ebackup/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
                 ."      <font style=\"font-size : 18px;font-weight: bold;text-align: left;\">База данных - ".$title."</font>\n"
                 ."    </td>\n"
                 ."  </tr>\n"
                 ."</table>\n"
                 ."<table class=\"adminlist\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">\n"
                 ."  <tr>\n"
                 ."    <th align=\"left\">"._BBKP_TABLES."</th>\n"
                 ."    <th align=\"left\" width=\"5%\">"._BBKP_CHECK_OP."</th>\n"
                 ."    <th align=\"left\" width=\"5%\">"._BBKP_CHECK_TYPE."</th>\n"
                 ."    <th align=\"left\" width=\"5%\">"._BBKP_CHECK_MESSAGE."</th>\n"
                 ."  </tr>\n"
                 ."  ".$list
                 ."  <tr>\n"
                 ."    <th colspan=\"4\">&nbsp;</th>\n"
                 ."  </tr>\n"
                 ."</table>\n";
       echo $content;
     }
}

?>

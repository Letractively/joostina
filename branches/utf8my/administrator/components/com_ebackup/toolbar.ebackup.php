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

require_once($mainframe->getPath('toolbar_html'));
require_once($mainframe->getPath('toolbar_default'));
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
            TOOLBAR_eBackup::BACK_MENU($option);
            break;
       case 'doCheck':
            TOOLBAR_eBackup::BACK_MENU($option);
            break;
       case 'doAnalyze':
            TOOLBAR_eBackup::BACK_MENU($option);
            break;
       case 'doOptimize':
            TOOLBAR_eBackup::BACK_MENU($option);
            break;
       case 'doRepair':
            TOOLBAR_eBackup::BACK_MENU($option);
            break;
       case 'viewInfo':
            TOOLBAR_eBackup::INFO_BACK_MENU($option);
            break;
       case 'viewTables':
            TOOLBAR_eBackup::_DEFAULT();
            break;
       case 'viewSetup':
            TOOLBAR_eBackup::SETUP_MENU();
            break;
       case 'viewRestore':
            TOOLBAR_eBackup::RESTORE_MENU();
            break;
       default:
            TOOLBAR_eBackup::_DEFAULT();
            break;
}

?>

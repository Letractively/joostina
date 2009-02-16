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

class TOOLBAR_eBackup {
      function BACK_MENU($option) {
               global $option;
               mosMenuBar::startTable();
                  mosMenuBar::back("Панель управления", "index2.php?option=com_joomlapack");
               mosMenuBar::endTable();
      }
      function INFO_BACK_MENU($option) {
               //global $option;
               mosMenuBar::startTable();
               mosMenuBar::back("Назад", "index2.php?option=$option&task=viewRestore");
               mosMenuBar::endTable();
      }
      function RESTORE_MENU() {
               mosMenuBar::startTable();
               mosMenuBar::back("Панель управления", "index2.php?option=com_joomlapack");
               mosMenuBar::endTable();
      }
      function SETUP_MENU(){
               mosMenuBar::startTable();
               mosMenuBar::save('saveSettings', 'Сохранить');
               mosMenuBar::spacer();
               mosMenuBar::back("Панель управления", "index2.php?option=com_joomlapack");
               mosMenuBar::endTable();
      }
      function _DEFAULT() {
               mosMenuBar::startTable();
               mosMenuBar::custom('doCheck','-check','','Проверить');
               mosMenuBar::spacer();
               mosMenuBar::custom('doAnalyze','-info','','Анализировать');
               mosMenuBar::spacer();
               mosMenuBar::custom('doOptimize','-optimize','','Оптимизировать');
               mosMenuBar::spacer();
               mosMenuBar::custom('doRepair','-help','','Исправить');
               mosMenuBar::divider();
               mosMenuBar::back("Панель управления", "index2.php?option=com_joomlapack");
               mosMenuBar::endTable();
      }
}
?>

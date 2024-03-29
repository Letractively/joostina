<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

class modules_html {

    public $_mainframe;

    function modules_html($mainframe) {
        $this->_mainframe = $mainframe;
    }

    public function module(&$module,&$params,$Itemid,$style = 0) {
        $config = $this->_mainframe->config;

        $path = JPATH_BASE.DS.'language'.DS.$config->config_lang.DS.'frontend'.DS.$module->module.'.php';
        $path_def = JPATH_BASE.DS.'language/russian/frontend'.DS.$module->module.'.php';

        file_exists($path) ? include_once ($path) : (file_exists($path_def) ? include_once ($path_def):null);

        if( !is_file(JPATH_BASE.DS.'modules'.DS.$module->module.DS.$module->module.'.php') ) {
            $d = debug_backtrace();
            jd_log( 'mosMainFrame::getInstance  '.$d[0]['file'].'::'.$d[0]['line'] );
        }else {
            include (JPATH_BASE.DS.'modules'.DS.$module->module.DS.$module->module.'.php');
        }
    }
}
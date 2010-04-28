<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
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


    function module(&$module,&$params,$Itemid,$style = 0) {
        global $_MAMBOTS;

        $database = $this->_mainframe->getDBO();

        $moduleclass_sfx = $params->get('moduleclass_sfx');
        $rssurl		= $params->get('rssurl');
        $firebots	= $params->get('firebots',0);

        if($rssurl) {
            modules_html::modoutput_feed($module,$params,$moduleclass_sfx);
        }

        if($module->content != '' && $firebots) {
            $_MAMBOTS->loadBotGroup('content');
            $row = $module;
            $row->text = $module->content;

            $results = $_MAMBOTS->trigger('onPrepareContent',array(&$row,&$params,0),true);
            $module->content = $row->text;
        }

        $module = mosModule::convert_to_object($module, $this->_mainframe);
        switch($style) {
            case - 3:
                $this->modoutput_rounded($module,$params,$Itemid,$moduleclass_sfx,1);
                break;

            case - 2:
                $this->modoutput_xhtml($module,$params,$Itemid,$moduleclass_sfx,1);
                break;

            case - 1:
                $this->modoutput_naked($module,$params,$Itemid,$moduleclass_sfx,1);
                break;

            default:
                $this->modoutput_table($module,$params,$Itemid,$moduleclass_sfx,1);
                break;
        }
    }

    public function module2(&$module,&$params,$Itemid,$style = 0) {
        $config = $this->_mainframe->config;

        $path = JPATH_BASE.DS.'language'.DS.$config->config_lang.DS.'frontend'.DS.$module->module.'.php';
        $path_def = JPATH_BASE.DS.'language/russian/frontend'.DS.$module->module.'.php';

        file_exists($path) ? include_once ($path) : (file_exists($path_def) ? include_once ($path_def):null);

        include (JPATH_BASE.DS.'modules'.DS.$module->module.'.php');
    }
}
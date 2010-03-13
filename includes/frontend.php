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

/**
 * Displays the capture output of the main element
 */
function mosMainBody() {
    $page = &PageModel::getInstance();
    echo $page->MainBody();
}
/**
 * Utility functions and classes
 * not used?
 */
function mosLoadComponent($name) {
    global $my,$task,$Itemid,$id,$option,$gid;

    $mainframe = &mosMainFrame::getInstance();
    $database = &$mainframe->_db;
    include (JPATH_BASE.DS."components/com_$name/$name.php");
}
/**
 * Cache some modules information
 * @return array
 */

//Добавлено в класс mosModules
function &initModules() {

}
/**
 * @param string the template position
 */
function mosCountModules($position = 'left') {
    $modules =& mosModule::getInstance();
    return $modules->mosCountModules($position);
}
/**
 * @param string The position
 * @param int The style.  0=normal, 1=horiz, -1=no wrapper
 */
//Скопировано в класс
function mosLoadModules($position = 'left',$style = 0,$noindex = 0) {
    $modules =& mosModule::getInstance();
    return $modules->mosLoadModules($position,$style,$noindex);
}

/**
 * @param string The position
 * @param int The style.  0=normal, 1=horiz, -1=no wrapper
 */
function mosLoadModule($name = '', $title = '', $style = 0, $noindex = 0, $inc_params = null) {
    $modules =& mosModule::getInstance();
    return $modules->mosLoadModule($name,$title,$style,$noindex,$inc_params);
}

/**
 * Шапка страницы
 */
function mosShowHead($params=array('js'=>1,'css'=>1)) {
    $page = &PageModel::getInstance();
    // загружаем верхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
    $page->ShowHead($params);
}

function mosShowFooter($params=array('fromheader'=>1,'js'=>1)) {
    $page = &PageModel::getInstance();
    // загружаем верхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
    $page->ShowFooter($params);
}

// установка мета-тэгов для поисковика
function set_robot_metatag($robots) {

}

// выводк лент RSS
function syndicate_header() {

}


/**
 * @package Joostina
 */

class PageModel {

    var $_mainframe = null;
    var $_view = null;

    function PageModel($mainframe) {
        $this->_mainframe = $mainframe;
    }

    public static function &getInstance() {
        static $page_model;
        if(!is_object($page_model) ) {
            $mainframe = &mosMainFrame::getInstance();
            unset($mainframe->menu,$mainframe->_session);
            $page_model = new PageModel($mainframe);
        }

        return $page_model;
    }

    function _body() {
        $this->MainBody();
    }

    function _header($params) {
        $this->ShowHead($params);
    }

    function _footer($params) {
        $this->ShowFooter($params);
    }

    function MainBody() {
        $mainframe = $this->_mainframe;

        $mosmsg = $mainframe->get_mosmsg();
        if($mosmsg && !$popMessages && $browserCheck && $sessionCheck) {
            echo '<div class="message info">'.$mosmsg.'</div>';
        }
        
        $_body = $GLOBALS['_MOS_OPTION']['buffer'];

        echo $_body;

        unset($GLOBALS['_MOS_OPTION']['buffer']);
    }

    function ShowHead($params=array('js'=>1,'css'=>1)) {
        global $option,$my,$task,$id;

        $mainframe = $this->_mainframe;

        $description = '';
        $keywords = '';

        $_meta_keys_index = -1;
        $_meta_desc_index = -1;

        $n = count($mainframe->_head['meta']);
        for($i = 0; $i < $n; $i++) {
            if($mainframe->_head['meta'][$i][0] == 'keywords') {
                $_meta_keys_index = $i;
                $keywords = $mainframe->_head['meta'][$i][1];
            } else {
                if($mainframe->_head['meta'][$i][0] == 'description') {
                    $_meta_desc_index = $i;
                    $description = $mainframe->_head['meta'][$i][1];
                }
            }
        }

        if(!$description) {
            $mainframe->appendMetaTag('description',$mainframe->getCfg('MetaDesc'));
        }

        if(!$keywords) {
            $mainframe->appendMetaTag('keywords',$mainframe->getCfg('MetaKeys'));
        }

        echo $mainframe->getHead($params);

        // favourites icon
        if(!$mainframe->getCfg('disable_favicon')) {
            if(!$mainframe->getCfg('favicon')) {
                $favicon = 'favicon.ico';
            }else {
                $favicon = $mainframe->getCfg('favicon');
            }
            $icon = JPATH_BASE.'/images/'.$favicon;
            if(!file_exists($icon)) {
                $icon = JPATH_SITE.'/images/favicon.ico';
            } else {
                $icon = JPATH_SITE.'/images/'.$favicon;
            }
            echo '<link rel="shortcut icon" href="'.$icon.'" />';
        }
    }


    function ShowFooter($params=array('fromheader'=>1,'js'=>1)) {
        echo $this->_mainframe->getFooter($params);
    }
}
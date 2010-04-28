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

function mosMainBody() {
    echo PageModel::getInstance()->MainBody();
}

function mosLoadComponent($name) {
    global $my,$task,$Itemid,$id,$option,$gid;

    $mainframe = mosMainFrame::getInstance();
    $database = $mainframe->getDBO();
    include (JPATH_BASE.DS."components/com_$name/$name.php");
}


//Добавлено в класс mosModules
function initModules() {

}

function mosCountModules($position = 'left') {
    return mosModule::getInstance()->mosCountModules($position);
}

function mosLoadModules($position = 'left',$style = 0,$noindex = 0) {
    return mosModule::getInstance()->mosLoadModules($position,$style,$noindex);
}

function mosLoadModule($name = '', $title = '', $style = 0, $noindex = 0, $inc_params = null) {
    return mosModule::getInstance()->mosLoadModule($name,$title,$style,$noindex,$inc_params);
}

function mosShowHead($params=array('js'=>1,'css'=>1)) {
    // загружаем верхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
    PageModel::getInstance()->ShowHead($params);
}

function mosShowFooter($params=array('fromheader'=>1,'js'=>1)) {
    // загружаем верхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
    PageModel::getInstance()->ShowFooter($params);
}

// установка мета-тэгов для поисковика
function set_robot_metatag($robots) {
    mosMainFrame::getInstance()->set_robot_metatag($robots);
}

class PageModel {
    private static $_instance;

    private $_mainframe;
    private $_view;

    private function __clone() {

    }

    function PageModel() {
        $this->_mainframe = mosMainFrame::getInstance();
    }

    public static function getInstance() {

        if( self::$_instance === null ) {
            self::$_instance = new self();
        }

        return self::$_instance;
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

        // Session Check
        $sessionCheck = 0;
        // Session Cookie `name`
        $sessionCookieName = $mainframe->sessionCookieName();
        // Get Session Cookie `value`
        $sessioncookie = mosGetParam($_COOKIE,$sessionCookieName,null);
        if((strlen($sessioncookie) == 32 || $sessioncookie == '-')) {
            $sessionCheck = 1;
        }

        $mosmsg = $mainframe->get_mosmsg();
        if($mosmsg && $sessionCheck) {
            echo '<div class="message info">'.$mosmsg.'</div>';
        }

        $_body = $GLOBALS['_MOS_OPTION']['buffer'];

        // активация мамботов группы mainbody
        if($mainframe->getCfg('mmb_mainbody_off') == 0) {
            global $_MAMBOTS;
            $_MAMBOTS->loadBotGroup('mainbody');
            $_MAMBOTS->trigger('onMainbody',array(&$_body));
        }

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

        $meta = $mainframe->getHeadData('meta');
        $n = count($meta);
        for($i = 0; $i < $n; $i++) {
            if($meta[$i][0] == 'keywords') {
                $_meta_keys_index = $i;
                $keywords = $meta[$i][1];
            } else {
                if($meta[$i][0] == 'description') {
                    $_meta_desc_index = $i;
                    $description = $meta[$i][1];
                }
            }
        }

        $description ? null : $mainframe->appendMetaTag('description',$mainframe->getCfg('MetaDesc'));
        $keywords  ? null : $mainframe->appendMetaTag('keywords',$mainframe->getCfg('MetaKeys'));
        ($mainframe->getCfg('generator_off') == 0) ? $mainframe->addMetaTag('Generator',coreVersion::$CMS.' - '.coreVersion::$COPYRIGHT) : null;

        if($mainframe->getCfg('index_tag') == 1) {
            $mainframe->addMetaTag('distribution','global');
            $mainframe->addMetaTag('rating','General');
            $mainframe->addMetaTag('document-state','Dynamic');
            $mainframe->addMetaTag('documentType','WebDocument');
            $mainframe->addMetaTag('audience','all');
            $mainframe->addMetaTag('revisit',$mainframe->getCfg('mtage_revisit').' days');
            $mainframe->addMetaTag('revisit-after',$mainframe->getCfg('mtage_revisit').' days');
            $mainframe->addMetaTag('allow-search','yes');
            $mainframe->addMetaTag('language',$mainframe->getCfg('lang'));
        }

        echo $mainframe->getHead($params);


        // favourites icon
        if(!$mainframe->getCfg('disable_favicon')) {
            $icon = JPATH_SITE.'/images/favicon.ico';
            echo '<link rel="shortcut icon" href="'.$icon.'" />';
        }
    }
    
    function ShowFooter($params=array('fromheader'=>1,'js'=>1)) {
        echo $this->_mainframe->getFooter($params);
    }
}
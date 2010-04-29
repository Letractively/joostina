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

require_once ($mainframe->getPath('front_html'));
require_once ($mainframe->getPath('class'));

// управлятор
mosMainFrame::addLib('joiadmin');
JoiAdmin::dispatch();

class actionsPages {

    public static function index( ) {

        $menu = mosMainFrame::getInstance()->get('menu');
        $params = new mosParameters($menu->params);

        $page = new Pages();
        $page->load( $params->get('page_id',0) );

        mosMainFrame::getInstance()->addMetaTag('description',  $page->meta_description );
        mosMainFrame::getInstance()->addMetaTag('keywords',  $page->meta_keywords );
        mosMainFrame::getInstance()->setPageTitle( $page->title_page );

        pagesHTML::index($page);
    }
}
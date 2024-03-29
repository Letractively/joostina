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

/*
 * Класс формирования представлений
*/
class tagsHTML {

    /**
     * Страница с результатами поиска по тэгу
     */
    public static function tag_search($tag, $search_results_nodes, $pager) {
        mosMainFrame::addLib( 'text' );
        mosMainFrame::addLib( 'images' );
        require_once 'views/results/default.php';
    }
    
    /**
     * Страница с полным облаком
     */
    public static function full_cloud($tags_cloud) {
        require_once 'views/full_cloud/default.php';
    }
}
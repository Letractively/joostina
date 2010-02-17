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

require_once ($mainframe->getPath('admin_html'));
require_once ($mainframe->getPath('class'));

$id = (int) mosGetParam($_REQUEST, 'id', 0);

switch($task) {

    case 'test':
        test(0);
        break;

    case 'new':
        editArticle(0);
        break;

    case 'edit':
        editArticle($id);
        break;

    case 'save':
        saveArticle($id);
        break;

    case 'save_and_new':
        saveArticle($id,true);
        break;

    default:
        index();

        break;

}

function index() {

    $articles = new Articles;
    $articles_count = $articles->count();

    $pagenav = admin_pagenav( $articles_count , 'com_articles');

    $param = array(
            'offset'=>$pagenav->limitstart,
            'limit'=>$pagenav->limit
    );
    $articles_list = $articles->get_list($param);

    articlesHTML::index( $articles, $articles_list, $pagenav );
}

function editArticle( $id ) {
    $articles_data = new Articles;
    $articles_data->load( $id );

    articlesHTML::edit_article( $articles_data, $articles_data);
}

function saveArticle( $id, $create_new = false) {
    $article = new Articles;
    $article->save($_POST);

    $create_new ? mosRedirect( 'index2.php?option=com_articles&task=new', array( 'Сохранено успешно!','Создаём новое') ) : mosRedirect( 'index2.php?option=com_articles' , 'Сохранено успешно!');

}

function test() {
    $mf = mosMainFrame::getInstance(true);

    mosCommonHTML::loadJquery();
    mosCommonHTML::loadJqueryPlugins('elrte/js/elrte.min');
    mosCommonHTML::loadJqueryUI();
    $mf->addCSS( JPATH_SITE.'/includes/js/jquery/plugins/elrte/css/elrte.full.css');
    ?>
<div id="our-element" >111</div>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#our-element').elrte();
    });
</script>
    
    <?php

}
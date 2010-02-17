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

    case 'new':
        editGame(0);
        break;

    case 'edit':
        editGame($id);
        break;

    case 'save':
        saveGame($id);
        break;

    case 'save_and_new':
        saveGame($id,true);
        break;

    default:
        index();

        break;

}

function index() {

    $articles = new Games;
    $articles_count = $articles->count();

    $pagenav = admin_pagenav( $articles_count , 'com_articles');

    $param = array(
            'offset'=>$pagenav->limitstart,
            'limit'=>$pagenav->limit
    );
    $articles_list = $articles->get_list($param);

    gamesHTML::index( $articles, $articles_list, $pagenav );
}

function editGame( $id ) {
    $articles_data = new Games;
    $articles_data->load( $id );

    gamesHTML::edit_article( $articles_data, $articles_data);
}

function saveGame( $id, $create_new = false) {
    $article = new Games;
    $article->save($_POST);

    $create_new ? mosRedirect( 'index2.php?option=com_games&task=new', array( 'Сохранено успешно!','Создаём еще угруху.') ) : mosRedirect( 'index2.php?option=com_games' , 'Сохранено успешно!');

}
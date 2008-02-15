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

$_MAMBOTS->registerFunction( 'onSearch', 'botSearchCategories' );

/**
* Метод поиска категорий
*
* запрос sql должен возвратить поля, используются в обычной операции 
* отображения: href, title, section, created, text, browsernav
* @param определяет цель поиска
* @param сопоставляет параметры: exact|any|all
* @param определяет порядок сортировки: newest|oldest|popular|alpha|category
*/
function botSearchCategories( $text, $phrase='', $ordering='' ) {
	global $database, $my, $_MAMBOTS;

	// check if param query has previously been processed
	if ( !isset($_MAMBOTS->_search_mambot_params['categories']) ) {
	// load mambot params info
	$query = "SELECT params"
	. "\n FROM #__mambots"
	. "\n WHERE element = 'categories.searchbot'"
	. "\n AND folder = 'search'"
	;
	$database->setQuery( $query );
	$database->loadObject($mambot);
		
		// save query to class variable
		$_MAMBOTS->_search_mambot_params['categories'] = $mambot;
	}
	
	// pull query data from class variable
	$mambot = $_MAMBOTS->_search_mambot_params['categories'];	
	
	$botParams = new mosParameters( $mambot->params );
	
	$limit = $botParams->def( 'search_limit', 50 );
	
	$text = trim( $text );
	if ( $text == '' ) {
		return array();
	}

	switch ( $ordering ) {
		case 'alpha':
			$order = 'a.name ASC';
			break;

		case 'category':
		case 'popular':
		case 'newest':
		case 'oldest':
		default:
			$order = 'a.name DESC';
	}

	$query = "SELECT a.name AS title,"
	. "\n a.description AS text,"
	. "\n '' AS created,"
	. "\n '2' AS browsernav,"
	. "\n '' AS section,"
	. "\n '' AS href,"
	. "\n s.id AS secid, a.id AS catid,"
	. "\n m.id AS menuid, m.type AS menutype"
	. "\n FROM #__categories AS a"
	. "\n INNER JOIN #__sections AS s ON s.id = a.section"
	. "\n LEFT JOIN #__menu AS m ON m.componentid = a.id"
	. "\n WHERE ( a.name LIKE '%$text%'"
	. "\n OR a.title LIKE '%$text%'"
	. "\n OR a.description LIKE '%$text%' )"
	. "\n AND a.published = 1"
	. "\n AND s.published = 1"
	. "\n AND a.access <= " . (int) $my->gid
	. "\n AND s.access <= " . (int) $my->gid
	. "\n AND ( m.type = 'content_section' OR m.type = 'content_blog_section'"
	. "\n OR m.type = 'content_category' OR m.type = 'content_blog_category')"
	. "\n GROUP BY a.id" 
	. "\n ORDER BY $order"
	;
	$database->setQuery( $query, 0, $limit );
	$rows = $database->loadObjectList();

	$count = count( $rows );
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $rows[$i]->menutype == 'content_blog_category' ) {
			$rows[$i]->href = 'index.php?option=com_content&task=blogcategory&id='. $rows[$i]->catid .'&Itemid='. $rows[$i]->menuid;
			$rows[$i]->section 	= _SEARCH_CATBLOG;
		} else {
			$rows[$i]->href = 'index.php?option=com_content&task=category&sectionid='. $rows[$i]->secid .'&id='. $rows[$i]->catid .'&Itemid='. $rows[$i]->menuid;
			$rows[$i]->section 	= _SEARCH_CATLIST;
		}
	}

	return $rows;
}
?>

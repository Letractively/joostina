<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die(); 
require_once ($mainframe->getPath('front_html','com_content'));

global $gid,$task,$Itemid,$option,$my;

$id			= intval(mosGetParam($_REQUEST,'id',0));
$sectionid	= intval(mosGetParam($_REQUEST,'sectionid',0));
$pop		= intval(mosGetParam($_REQUEST,'pop',0));
$limit		= intval(mosGetParam($_REQUEST,'limit',0));
$limitstart	= intval(mosGetParam($_REQUEST,'limitstart',0));
$year		= intval(mosGetParam($_REQUEST,'year',date('Y')));
$month		= intval(mosGetParam($_REQUEST,'month',date('m')));
$module		= intval(mosGetParam($_REQUEST,'module',0));
$filter		= strval(mosGetParam($_REQUEST,'filter',''));
$order		= strval(mosGetParam($_REQUEST,'order',''));

// Editor usertype check
$access = new stdClass();
$access->canEdit	= $acl->acl_check('action','edit','users',$my->usertype,'content','all');
$access->canEditOwn	= $acl->acl_check('action','edit','users',$my->usertype,'content','own');
$access->canPublish	= $acl->acl_check('action','publish','users',$my->usertype,'content','all');

// cache activation
$cache = &mosCache::getCache('com_content');

// loads function for frontpage component
if($option == 'com_frontpage') {
	$cache->call('frontpage',$gid,$access,$pop,0,$limit,$limitstart);
	return;
}

switch($task) {

	case 'ucontent':
		showUserItems();
		break;

	case 'findkey':
		findKeyItem($gid,$access,$pop,$option,0);
		break;

	case 'view':
    case 'preview':
		if($mosConfig_enable_stats) {
			showItem($id,$gid,$access,$pop,$option,0);
		} else {
			$cache->call('showItem',$id,$gid,$access,$pop,$option,0,$limit,$limitstart);
		}
		break;

	case 'section':
		$cache->call('showSection',$id,$gid,$access,0);
		break;

	case 'category':
		$selected = strval(mosGetParam($_REQUEST,'order',''));
		$selected = preg_replace('/[^a-z]/i','',$selected);
		$filter = stripslashes(strval(mosGetParam($_REQUEST,'filter','')));

		$cache->call('showCategory',$id,$gid,$access,$sectionid,$limit,null,$limitstart,0,$selected,$filter);
		break;

	case 'blogsection':
		// Itemid is a dummy value to cater for caching
		$cache->call('showBlogSection',$id,$gid,$access,$pop,$Itemid,$limit,$limitstart);
		break;

	case 'blogcategorymulti':
	case 'blogcategory':
		// Itemid is a dummy value to cater for caching
		$cache->call('showBlogCategory',$id,$gid,$access,$pop,$Itemid,$limit,$limitstart);
		break;

	case 'archivesection':
		// Itemid is a dummy value to cater for caching
		$cache->call('showArchiveSection',$id,$gid,$access,$pop,$option,$year,$month,$limit,$limitstart,$Itemid);
		break;

	case 'archivecategory':
		// Itemid is a dummy value to cater for caching
		$cache->call('showArchiveCategory',$id,$gid,$access,$pop,$option,$year,$month,$module,$limit,$limitstart,$Itemid);
		break;

	case 'edit':
		editItem($task);
		break;

	case 'new':
		editItem($task);
		break;

	case 'save':
	case 'apply':
	case 'apply_new':
		mosCache::cleanCache('com_content');
		saveContent($access,$task);
		break;

	case 'cancel':
		cancelContent($access);
		break;

	case 'emailform':
		emailContentForm($id,$gid);
		break;

	case 'emailsend':
		emailContentSend($id,$gid);
		break;

	case 'vote':
		recordVote();
		break;

	default:
		header("HTTP/1.0 404 Not Found");
		echo _NOT_EXIST;
		break;
}


/**
* @param int The access level of the user
* @param int The section id
* @param int The number of items to dislpay
* @param int The offset for pagination
*/
function showUserItems() {
	global $database, $mainframe, $Itemid, $my, $acl;

    $limit		= intval(mosGetParam($_REQUEST,'limit',0));
    $limitstart	= intval(mosGetParam($_REQUEST,'limitstart',0));
    $orderby	= strval(mosGetParam($_REQUEST,'order',''));
    $user_id	= intval(mosGetParam($_REQUEST,'user',0));

    //права доступа
    $access = new jstContentAccess();
    // Paramters
    $params = new jstContentUserpageConfig($database);

    $limit = $limit ? $limit : $params->get('display_num');

	if (!$orderby) {
		$orderby = $params->get( 'orderby', 'rdate' );

	}
    $selected = $orderby;
	// Ordering control
	$orderby = _orderby_sec( $orderby );

	// get the total number of published items in the category
	// filter functionality
	$and = '';
	if ( $params->get( 'filter' ) ) {
		$filter = mosGetParam( $_POST, 'filter', '' );

		if ( $filter ) {
			// clean filter variable
			$filter = strtolower( $filter );

			switch ( $params->get( 'filter_type' ) ) {
				case 'title':
					$and = "\n AND LOWER( a.title ) LIKE '%$filter%'";
					break;

				case 'hits':
					$and = "\n AND a.hits LIKE '%$filter%'";
					break;
			}
		}
	}

    $user_items = new mosContent($database);
    //Получаем количество записей пользователя
    $total = $user_items->load_count_user_items($user_id, $and);
    //Получаем все записи пользователя
    $items = $user_items->load_user_items($user_id, $limitstart, $limit, $orderby, $and);

    //Постраничная навигация
	if ($total <= $limit) $limitstart = 0;
	require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	$check = 0;
	if ( $params->get( 'date' ) ) {
		$order[] = mosHTML::makeOption( 'date', _ORDER_DROPDOWN_DA );
		$order[] = mosHTML::makeOption( 'rdate', _ORDER_DROPDOWN_DD );
		$check .= 1;
	}
	if ( $params->get( 'title' ) ) {
		$order[] = mosHTML::makeOption( 'alpha', _ORDER_DROPDOWN_TA );
		$order[] = mosHTML::makeOption( 'ralpha', _ORDER_DROPDOWN_TD );
		$check .= 1;
	}
	if ( $params->get( 'hits' ) ) {
		$order[] = mosHTML::makeOption( 'hits', _ORDER_DROPDOWN_HA );
		$order[] = mosHTML::makeOption( 'rhits', _ORDER_DROPDOWN_HD );
		$check .= 1;
	}
	if ( $params->get( 'section' ) ) {
		$order[] = mosHTML::makeOption( 'section', 'Раздел / Категория по возрастанию' );
		$order[] = mosHTML::makeOption( 'rsection',  'Раздел / Категория по убыванию' );
		$check .= 1;
	}
	$order[] = mosHTML::makeOption( 'order', _ORDER_DROPDOWN_O );
	$lists['order'] = mosHTML::selectList( $order, 'order', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $selected );
	if ( $check < 1 ) {
		$lists['order'] = '';
		$params->set( 'order_select', 0 );
	}

	$lists['task'] = 'category';
	$lists['filter'] = $filter;
	$lists['limit'] = $limit;
	$lists['limitstart'] = $limitstart;


    $pagetitle = '';
	if ( $Itemid ) {
		$menu = new mosMenu( $database );
		$menu->load( $Itemid );
		$pagetitle = $menu->name;
	}
	// Dynamic Page Title
	$mainframe->SetPageTitle( $pagetitle );


	HTML_content::showUserContent( $items, $access, $params, $pageNav, $lists, $selected );
} // showCategory

/**
* Searches for an item by a key parameter
* @param int The user access level
* @param object Actions this user can perform
* @param int
* @param string The url option
* @param string A timestamp
*/
function findKeyItem($gid,$access,$pop,$option) {
	global $database;

	$keyref = stripslashes(strval(mosGetParam($_REQUEST,'keyref','')));

	$query = "SELECT id"
		. "\n FROM #__content"
		. "\n WHERE attribs LIKE '%keyref=" . $database->getEscaped( $keyref, true ) . "\n%'";
	$database->setQuery( $query );
	$id = $database->loadResult();

	if($id > 0) {
		showItem($id,$gid,$access,$pop,$option,0);
	} else {
		echo _KEY_NOT_FOUND;
	}
}

function frontpage($gid,&$access,$pop,$now,$limit,$limitstart) {
	global $database,$mainframe,$mosConfig_MetaDesc,$mosConfig_MetaKeys;

	$now = _CURRENT_SERVER_TIME;

	$noauth = !$mainframe->getCfg('shownoauth');

	// Parameters
	$menu = $mainframe->get('menu');
	$params = new mosParameters($menu->params);
	// Ordering control
	$orderby_sec = $params->def('orderby_sec','');
	$orderby_pri = $params->def('orderby_pri','');
	$order_sec = _orderby_sec($orderby_sec);
	$order_pri = _orderby_pri($orderby_pri);

	// voting control
	$voting = $params->def('rating','');
	$voting = votingQuery($voting);

	$where = _where(1,$access,$noauth,$gid,0,$now,null,null,$params);
	$where = (count($where)?"\n WHERE ".implode("\n AND ",$where):'');

	// Limit & limitstart
	$intro = $params->def('intro',4);
	$leading = $params->def('leading',1);
	$links = $params->def('link',4);

	$limit = $intro + $leading + $links;

	// query to determine total number of records
	$query = "SELECT COUNT(a.id) FROM #__content AS a INNER JOIN #__content_frontpage AS f ON f.content_id = a.id"
			."\n INNER JOIN #__categories AS cc ON cc.id = a.catid INNER JOIN #__sections AS s ON s.id = a.sectionid"
			."\n LEFT JOIN #__users AS u ON u.id = a.created_by LEFT JOIN #__groups AS g ON a.access = g.id".
		$where;
	$database->setQuery($query);
	$total = $database->loadResult();

	if($total <= $limit) {
		$limitstart = 0;
	}

	// query records
	$query = "SELECT a.attribs, a.notetext, a.id, a.title, a.title_alias, a.introtext, a.sectionid, a.state, a.catid, a.created, a.created_by, a.created_by_alias, a.modified, a.modified_by,".
		"\n a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, a.images, a.urls, a.ordering, a.metakey, a.metadesc, a.access, a.hits,".
		"\n CHAR_LENGTH( a.fulltext ) AS readmore, u.name AS author, u.usertype, u.username, s.name AS section, cc.name AS category, g.name AS groups".
		"\n, s.id AS sec_id, cc.id as cat_id".$voting['select']."\n FROM #__content AS a".
		"\n INNER JOIN #__content_frontpage AS f ON f.content_id = a.id"."\n INNER JOIN #__categories AS cc ON cc.id = a.catid".
		"\n INNER JOIN #__sections AS s ON s.id = a.sectionid"."\n LEFT JOIN #__users AS u ON u.id = a.created_by".
		"\n LEFT JOIN #__groups AS g ON a.access = g.id".$voting['join'].$where."\n ORDER BY $order_pri $order_sec";
	$database->setQuery($query,$limitstart,$limit);
	$rows = $database->loadObjectList();

	// Dynamic Page Title
	//$mainframe->SetPageTitle( $menu->name );
	// Makes the page title more dynamic, uses the pagetitle parameter instead of the menu name;
	if(trim($params->get('page_name')) != '') {
		$mainframe->SetPageTitle($menu->name,$params);
	} else
		if($params->get('header') != '') {
			$mainframe->SetPageTitle($params->get('header',1),$params);
		} else {
			$mainframe->SetPageTitle($menu->name,$params);
		}
		set_robot_metatag($params->get('robots'));
	if($params->get('meta_description') != "") {
		$mainframe->addMetaTag('description',$params->get('meta_description'));
	} else {
		$mainframe->addMetaTag('description',$mosConfig_MetaDesc);
	}
	if($params->get('meta_keywords') != "") {
		$mainframe->addMetaTag('keywords',$params->get('meta_keywords'));
	} else {
		$mainframe->addMetaTag('keywords',$mosConfig_MetaKeys);
	}
	if($params->get('meta_author') != "") {
		$mainframe->addMetaTag('author',$params->get('meta_author'));
	}

	BlogOutput($rows,$params,$gid,$access,$pop,$menu,$limitstart,$limit,$total);
}


function showSection($id,$gid,&$access,$now) {
	global $database,$mainframe,$Itemid,$mosConfig_MetaDesc,$mosConfig_MetaKeys;

	$section = new mosSection($database);
	$section->load((int)$id);

	/*
	* Check if section is published
	*/
	if(!$section->published) {
		mosNotAuth();
		return;
	}
	/*
	* check whether section access level allows access
	*/
	if($section->access > $gid) {
		mosNotAuth();
		return;
	}

	$now = _CURRENT_SERVER_TIME;
	$nullDate = $database->getNullDate();
	$noauth = !$mainframe->getCfg('shownoauth');

	// Paramters
	$params = new stdClass();
	if($Itemid) {
		$menu = $mainframe->get('menu');
		$params = new mosParameters($menu->params);
	} else {
		$menu = '';
		$params = new mosEmpty();

	}
	$orderby = $params->get('orderby','');

	$params->set('type','section');

	$params->def('page_title',1);
	$params->def('pageclass_sfx','');
	$params->def('description_sec',1);
	$params->def('description_sec_image',1);
	$params->def('other_cat_section',1);
	$params->def('empty_cat_section',0);
	$params->def('other_cat',1);
	$params->def('empty_cat',0);
	$params->def('cat_items',1);
	$params->def('cat_description',1);
	$params->def('back_button',$mainframe->getCfg('back_button'));
	$params->def('pageclass_sfx','');
	// param controls whether unpublished items visible to publishers and above
	$params->def('unpublished',1);

	// Ordering control
	$orderby = _orderby_sec($orderby);

	// Description & Description Image control
	$params->def('description',$params->get('description_sec'));
	$params->def('description_image',$params->get('description_sec_image'));

	if($access->canEdit) {
		$xwhere = '';
		if($params->get('unpublished')) {
			// shows unpublished items for publishers and above
			$xwhere2 = "\n AND (b.state >= 0 or b.state is null)";
		} else {
			// unpublished items NOT shown for publishers and above
			$xwhere2 = "\n AND (b.state = 1 or b.state is null)";
		}
	} else {
		$xwhere = "\n AND a.published = 1";
		$xwhere2 = "\n AND b.state = 1"."\n AND ( b.publish_up = ".$database->Quote($nullDate).
			" OR b.publish_up <= ".$database->Quote($now)." )"."\n AND ( b.publish_down = ".
			$database->Quote($nullDate)." OR b.publish_down >= ".$database->Quote($now).
			" )";
	}

	$empty = '';
	$empty_sec = '';
	if($params->get('type') == 'category') {
		// show/hide empty categories
		if(!$params->get('empty_cat')) {
			$empty = "\n HAVING numitems > 0";
		}
	}
	if($params->get('type') == 'section') {
		// show/hide empty categories in section
		if(!$params->get('empty_cat_section')) {
			$empty_sec = "\n HAVING numitems > 0";
		}
	}

	$access_check = '';
	$access_check_content = '';
	if($noauth) {
		$access_check = "\n AND a.access <= ".(int)$gid;
		$access_check_content = "\n AND ( b.access <= ".(int)$gid.
			" OR b.access is null)";
	}

	// Query of categories within section
	$query = "SELECT a.*, COUNT( b.id ) AS numitems"."\n FROM #__categories AS a"."\n LEFT JOIN #__content AS b ON b.catid = a.id".
		$xwhere2."\n WHERE a.section = '".(int)$section->id."'".$xwhere.$access_check.$access_check_content.
		"\n GROUP BY a.id".$empty.$empty_sec."\n ORDER BY $orderby";
	$database->setQuery($query);
	$categories = $database->loadObjectList();

	// If categories exist, the "new content" icon may be displayed
	$categories_exist = false;
	if($access->canEdit) {
		$query = "SELECT count(*) as numCategories"."\n FROM #__categories as a"."\n WHERE a.section = '".(int)
			$section->id."'".$access_check;
		$database->setQuery($query);
		$categories_exist = ($database->loadResult()) > 0;
	}

	// remove slashes
	$section->name = stripslashes($section->name);


	$mainframe->SetPageTitle($menu->name,$params);
	set_robot_metatag($params->get('robots'));
	if($params->get('meta_description') != "") {
		$mainframe->addMetaTag('description',$params->get('meta_description'));
	} else {
		$mainframe->addMetaTag('description',$mosConfig_MetaDesc);
	}
	if($params->get('meta_keywords') != "") {
		$mainframe->addMetaTag('keywords',$params->get('meta_keywords'));
	} else {
		$mainframe->addMetaTag('keywords',$mosConfig_MetaKeys);
	}
	if($params->get('meta_author') != "") {
		$mainframe->addMetaTag('author',$params->get('meta_author'));
	}

	$null = null;


    $params->section_data = $section;

	HTML_content::showContentList($section,$null,$access,$id,$null,$gid,$params,$null,$categories,$null,$null,$categories_exist);
}


/**
* @param int The category id
* @param int The group id of the user
* @param int The access level of the user
* @param int The section id
* @param int The number of items to dislpay
* @param int The offset for pagination
*/
function showCategory($id,$gid,&$access,$sectionid,$limit,$selected,$limitstart,$now,$selected,$filter) {
	global $database,$mainframe,$Itemid,$mosConfig_list_limit,$mosConfig_MetaDesc,$mosConfig_MetaKeys;

	$category = new mosCategory($database);
	$category->load((int)$id);

	/*
	* Check if category is published
	*/
	if(!$category->published) {
		mosNotAuth();
		return;
	}
	/*
	* check whether category access level allows access
	*/
	if($category->access > $gid) {
		mosNotAuth();
		return;
	}

	$section = new mosSection($database);
	$section->load($category->section);

	/*
	* Check if category is published
	*/
	if(!$section->published) {
		mosNotAuth();
		return;
	}
	/*
	* check whether section access level allows access
	*/
	if($section->access > $gid) {
		mosNotAuth();
		return;
	}

	$now = _CURRENT_SERVER_TIME;
	$nullDate = $database->getNullDate();
	$noauth = !$mainframe->getCfg('shownoauth');

	// Paramters
	$params = new stdClass();
	if($Itemid) {
		$menu = $mainframe->get('menu');
		$params = new mosParameters($menu->params);
	} else {
		$menu = '';
		$params = new mosParameters('');
	}

	$lists['order_value'] = '';
	if($selected) {
		$orderby = $selected;
		$lists['order_value'] = $selected;
	} else {
		$orderby = $params->get('orderby','rdate');
		$selected = $orderby;
	}

	$params->set('type','category');

	$params->def('description_cat',1);
	$params->def('description_cat_image',1);
	$params->def('page_title',1);
	$params->def('title',1);
	$params->def('hits',$mainframe->getCfg('hits'));
	$params->def('author',!$mainframe->getCfg('hideAuthor'));
	$params->def('date',!$mainframe->getCfg('hideCreateDate'));
	$params->def('date_format',_DATE_FORMAT_LC);
	$params->def('navigation',2);
	$params->def('display',1);
	$params->def('display_num',$mosConfig_list_limit);
	$params->def('other_cat',1);
	$params->def('empty_cat',0);
	$params->def('cat_items',1);
	$params->def('cat_description',0);
	$params->def('back_button',$mainframe->getCfg('back_button'));
	$params->def('pageclass_sfx','');
	$params->def('headings',1);
	$params->def('order_select',1);
	$params->def('filter',1);
	$params->def('filter_type','title');
	// param controls whether unpublished items visible to publishers and above
	$params->def('unpublished',1);

	// Ordering control
	$orderby = _orderby_sec($orderby);

	// Description & Description Image control
	$params->def('description',$params->get('description_cat'));
	$params->def('description_image',$params->get('description_cat_image'));

	if($sectionid == 0) {
		$sectionid = $category->section;
	}

	if($access->canEdit) {
		$xwhere = '';
		if($params->get('unpublished')) {
			// shows unpublished items for publishers and above
			$xwhere2 = "\n AND b.state >= 0";
		} else {
			// unpublished items NOT shown for publishers and above
			$xwhere2 = "\n AND b.state = 1";
		}
	} else {
		$xwhere = "\n AND c.published = 1";
		$xwhere2 = "\n AND b.state = 1"."\n AND ( b.publish_up = ".$database->Quote($nullDate).
			" OR b.publish_up <= ".$database->Quote($now)." )"."\n AND ( b.publish_down = ".
			$database->Quote($nullDate)." OR b.publish_down >= ".$database->Quote($now).
			" )";
	}



	// show/hide empty categories
	$empty = '';
	if(!$params->get('empty_cat')) $empty = "\n HAVING COUNT( b.id ) > 0";

	// get the list of other categories
	$query = "SELECT c.*, COUNT( b.id ) AS numitems"."\n FROM #__categories AS c"."\n LEFT JOIN #__content AS b ON b.catid = c.id ".
		$xwhere2.($noauth?"\n AND b.access <= ".(int)$gid:'')."\n WHERE c.section = '".(int)
		$category->section."'".$xwhere.($noauth?"\n AND c.access <= ".(int)$gid:'')."\n GROUP BY c.id".
		$empty."\n ORDER BY c.ordering";
	$database->setQuery($query);
	$other_categories = $database->loadObjectList();

	// get the total number of published items in the category
	// filter functionality
	$and = null;
	if($params->get('filter')) {
		if($filter) {
			// clean filter variable
			$filter = strtolower($filter);

			switch($params->get('filter_type')) {
				case 'title':
					$and = "\n AND LOWER( a.title ) LIKE '%".$database->getEscaped($filter, true )."%'";
					break;

				case 'author':
					$and = "\n AND ( ( LOWER( u.name ) LIKE '%".$database->getEscaped($filter, true ).
						"%' ) OR ( LOWER( a.created_by_alias ) LIKE '%".$database->getEscaped($filter, true ).
						"%' ) )";
					break;

				case 'hits':
					$and = "\n AND a.hits LIKE '%".$database->getEscaped($filter, true )."%'";
					break;
			}
		}
	}

	if($access->canEdit) {
		if($params->get('unpublished')) {
			// shows unpublished items for publishers and above
			$xwhere = "\n AND a.state >= 0";
		} else {
			// unpublished items NOT shown for publishers and above
			$xwhere = "\n AND a.state = 1";
		}
	} else {
		$xwhere = "\n AND a.state = 1"."\n AND ( publish_up = ".$database->Quote($nullDate).
			" OR publish_up <= ".$database->Quote($now)." )"."\n AND ( publish_down = ".$database->Quote($nullDate).
			" OR publish_down >= ".$database->Quote($now)." )";
	}

	// query to determine total number of records
	$query = "SELECT COUNT(a.id) as numitems"."\n FROM #__content AS a"."\n LEFT JOIN #__users AS u ON u.id = a.created_by".
		"\n LEFT JOIN #__groups AS g ON a.access = g.id"."\n WHERE a.catid = ".(int)$category->id.
		$xwhere.($noauth?"\n AND a.access <= ".(int)$gid:'')."\n AND ".(int)$category->access.
		" <= ".(int)$gid.$and."\n ORDER BY $orderby";
	$database->setQuery($query);
	$counter = $database->loadObjectList();
	$total = $counter[0]->numitems;

	$limit = $limit?$limit:$params->get('display_num');
	if($total <= $limit) {
		$limitstart = 0;
	}

	require_once ($GLOBALS['mosConfig_absolute_path'].'/includes/pageNavigation.php');
	$pageNav = new mosPageNav($total,$limitstart,$limit);

	// get the list of items for this category
	$query = "  SELECT  a.id, a.title, a.hits, a.created_by, a.created_by_alias,
                        a.created AS created, a.access, a.state,
                        u.name AS author, u.username,
                        g.name AS groups
                FROM #__content AS a
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__groups AS g ON a.access = g.id
                WHERE a.catid = ".(int)$category->id.$xwhere.($noauth?"\n AND a.access <= ".(int)$gid:'')."\n AND ".(int)$category->access." <= ".(int)$gid.$and."\n ORDER BY $orderby";
	$database->setQuery($query,$limitstart,$limit);
	$items = $database->loadObjectList();

	$check = 0;
	if($params->get('date')) {
		$order[] = mosHTML::makeOption('date',_ORDER_DROPDOWN_DA);
		$order[] = mosHTML::makeOption('rdate',_ORDER_DROPDOWN_DD);
		$check .= 1;
	}
	if($params->get('title')) {
		$order[] = mosHTML::makeOption('alpha',_ORDER_DROPDOWN_TA);
		$order[] = mosHTML::makeOption('ralpha',_ORDER_DROPDOWN_TD);
		$check .= 1;
	}
	if($params->get('hits')) {
		$order[] = mosHTML::makeOption('hits',_ORDER_DROPDOWN_HA);
		$order[] = mosHTML::makeOption('rhits',_ORDER_DROPDOWN_HD);
		$check .= 1;
	}
	if($params->get('author')) {
		$order[] = mosHTML::makeOption('author',_ORDER_DROPDOWN_AUA);
		$order[] = mosHTML::makeOption('rauthor',_ORDER_DROPDOWN_AUD);
		$check .= 1;
	}

	$order[] = mosHTML::makeOption('order',_ORDER_DROPDOWN_O);
	$lists['order'] = mosHTML::selectList($order,'order','class="inputbox" size="1"  onchange="document.adminForm.submit();"','value','text',$selected);
	if($check < 1) {
		$lists['order'] = '';
		$params->set('order_select',0);
	}

	$lists['task'] = 'category';
	$lists['filter'] = $filter;

	// remove slashes
	$category->name = stripslashes($category->name);

	// Makes the page title more dynamic, uses the pagetitle parameter instead of the menu name;
	$mainframe->SetPageTitle($menu->name,$params);
	set_robot_metatag($params->get('robots'));
	if($params->get('meta_description') != "") {
		$mainframe->addMetaTag('description',$params->get('meta_description'));
	} else {
		$mainframe->addMetaTag('description',$mosConfig_MetaDesc);
	}
	if($params->get('meta_keywords') != "") {
		$mainframe->addMetaTag('keywords',$params->get('meta_keywords'));
	} else {
		$mainframe->addMetaTag('keywords',$mosConfig_MetaKeys);
	}
	if($params->get('meta_author') != "") {
		$mainframe->addMetaTag('author',$params->get('meta_author'));
	}

    $params->category_data = $category;
	HTML_content::showContentList($category,$items,$access,$id,$sectionid,$gid,$params, $pageNav,$other_categories,$lists,$selected,true);
} // showCategory


function showBlogSection($id = 0,$gid,&$access,$pop,$now = null,$limit,$limitstart) {
	global $database,$mainframe,$Itemid,$mosConfig_MetaDesc,$mosConfig_MetaKeys;

	// needed for check whether section is published
	$check = ($id?$id:0);

	$now = _CURRENT_SERVER_TIME;
	$noauth = !$mainframe->getCfg('shownoauth');

	// Parameters
	$params = new stdClass();
	if($Itemid) {
		$menu = $mainframe->get('menu');
		$params = new mosParameters($menu->params);
	} else {
		$menu = '';
		$params = new mosParameters('');
	}

	// new blog multiple section handling
	if(!$id) {
		$id = $params->def('sectionid',0);
	}

	$where = _where(1,$access,$noauth,$gid,$id,$now,null,null,$params);
	$where = (count($where)?"\n WHERE ".implode("\n AND ",$where):'');

	// Ordering control
	$orderby_sec = $params->def('orderby_sec','rdate');
	$orderby_pri = $params->def('orderby_pri','');
	$order_sec = _orderby_sec($orderby_sec);
	$order_pri = _orderby_pri($orderby_pri);

	// voting control
	$voting = $params->def('rating','');
	$voting = votingQuery($voting);

	// Limit & limitstart
	$intro = $params->def('intro',4);
	$leading = $params->def('leading',1);
	$links = $params->def('link',4);

	$limit = $limit?$limit:($intro + $leading + $links);

	// query to determine total number of records
	$query = "  SELECT COUNT(a.id)
                FROM #__content AS a
                INNER JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                ".$where;
	$database->setQuery($query);
	$total = $database->loadResult();

	if($total <= $limit) {
		$limitstart = 0;
	}

	// Main data query
	$query = "  SELECT  a.id, a.attribs , a.title, a.title_alias, a.introtext, a.sectionid,
                        a.state, a.catid, a.created, a.created_by, a.created_by_alias, a.modified, a.modified_by,
                        a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, a.images, a.urls, a.ordering,
                        a.metakey, a.metadesc, a.access, CHAR_LENGTH( a.fulltext ) AS readmore,
                        u.name AS author, u.usertype, u.username,
                        s.name AS section,
                        cc.name AS category,
                        g.name AS groups
                        ".$voting['select']."
                FROM #__content AS a
                INNER JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                ".$voting['join']
                .$where."
                ORDER BY $order_pri $order_sec";
	$database->setQuery($query,$limitstart,$limit);
	$rows = $database->loadObjectList();

	// Section data query
    $section = new mosSection($database);
    $params->section_data = $section->get_section($id);


	// Dynamic Page Title
	if($menu) {
		if(trim($params->get('page_name'))) {
			$mainframe->SetPageTitle($menu->name,$params);
		} else
			if($params->get('header') != '') {
				$mainframe->SetPageTitle($params->get('header',1),$params);
			} else {
				$mainframe->SetPageTitle($menu->name,$params);
			}
	}

	set_robot_metatag($params->get('robots'));
	if($params->get('meta_description') != "") {
		$mainframe->addMetaTag('description',$params->get('meta_description'));
	} else {
		$mainframe->addMetaTag('description',$mosConfig_MetaDesc);
	}
	if($params->get('meta_keywords') != "") {
		$mainframe->addMetaTag('keywords',$params->get('meta_keywords'));
	} else {
		$mainframe->addMetaTag('keywords',$mosConfig_MetaKeys);
	}
	if($params->get('meta_author') != "") {
		$mainframe->addMetaTag('author',$params->get('meta_author'));
	}

	// check whether section is published
	if(!count($rows) && $check) {
		$secCheck = new mosSection($database);
		$secCheck->load((int)$check);

		/*
		* check whether section is published
		*/
		if(!$secCheck->published) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section access level allows access
		*/
		if($secCheck->access > $gid) {
			mosNotAuth();
			return;
		}
	}


	BlogOutput($rows,$params,$gid,$access,$pop,$menu,$limitstart,$limit,$total);
}

function showBlogCategory($id = 0,$gid,&$access,$pop,$now,$limit,$limitstart) {
	global $database,$mainframe,$Itemid,$mosConfig_MetaDesc,$mosConfig_MetaKeys;

	$now = _CURRENT_SERVER_TIME;
	$noauth = !$mainframe->getCfg('shownoauth');

	// needed for check whether section & category is published
	$check = ($id?$id:0);

	// Paramters
	$params = new stdClass();
	if($Itemid) {
		$menu = $mainframe->get('menu');
		$params = new mosParameters($menu->params);
	} else {
		$menu = '';
		$params = new mosParameters('');
	}

	// new blog multiple section handling
	if(!$id) {
		$id = $params->def('categoryid',0);
	}

    $where = '';
	$where = _where(2,$access,$noauth,$gid,$id,$now,null,null,$params);
    if(count($where)){
        $where = "\n WHERE ".implode("\n AND ",$where);
    }

	// Ordering control
	$orderby_sec = $params->def('orderby_sec','rdate');
	$orderby_pri = $params->def('orderby_pri','');
	$order_sec = _orderby_sec($orderby_sec);
	$order_pri = _orderby_pri($orderby_pri);

	// voting control
	$voting = $params->def('rating','');
	$voting = votingQuery($voting);

	// Limit & limitstart
	$intro = $params->def('intro',4);
	$leading = $params->def('leading',1);
	$links = $params->def('link',4);

	$limit = $limit?$limit:($intro + $leading + $links);

	// query to determine total number of records
	$query = "  SELECT COUNT(a.id)
                FROM #__content AS a
                LEFT JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                ".$where;
	$database->setQuery($query);
	$total = $database->loadResult();

	if($total <= $limit) {
		$limitstart = 0;
	}

	// Main data query
	$query = "  SELECT a.id, a.notetext,a.attribs, a.title, a.title_alias, a.introtext,
                    a.sectionid, a.state, a.catid, a.created, a.created_by, a.created_by_alias,
                    a.modified, a.modified_by, a.checked_out, a.checked_out_time,
                    a.publish_up, a.publish_down, a.images, a.urls, a.ordering, a.metakey, a.metadesc, a.access,
                    CHAR_LENGTH( a.fulltext ) AS readmore,
                    s.published AS sec_pub, s.name AS section, s.templates AS s_templates,
                    cc.published AS sec_pub, cc.name AS category, cc.templates AS c_templates,
                    u.name AS author, u.usertype, u.username,
                    g.name AS groups
                    ".$voting['select']."
                FROM #__content AS a
                LEFT JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                ".$voting['join']
                .$where."
                ORDER BY $order_pri $order_sec";
	$database->setQuery($query,$limitstart,$limit);
	$rows = $database->loadObjectList();


    if($rows){
        $category = new mosCategory($database);
        $category->templates = $rows[0]->c_templates;
        $params->category_data = $category;

        $section = new mosSection($database);
        $section->templates = $rows[0]->s_templates;
        $params->section_data = $section;
    }


	// check whether section & category is published
	if(!count($rows) && $check) {
		$catCheck = new mosCategory($database);
		$catCheck->load((int)$check);
        $params->category_data = $catCheck;

		/*
		* check whether category is published
		*/
		if(!$catCheck->published) {
			mosNotAuth();
			return;
		}
		/*
		* check whether category access level allows access
		*/
		if($catCheck->access > $gid) {
			mosNotAuth();
			return;
		}

		$secCheck = new mosSection($database);
		$secCheck->load($catCheck->section);
        $params->section_data = $secCheck;
		/*
		* check whether section is published
		*/
		if(!$secCheck->published) {
			mosNotAuth();
			return;
		}
		/*
		* check whether category access level allows access
		*/
		if($secCheck->access > $gid) {
			mosNotAuth();
			return;
		}
	}


	if($params->get('header') == "") {
		$mainframe->SetPageTitle($menu->name,$params);
	} else {
		$mainframe->SetPageTitle($params->get('header'));
	}

	set_robot_metatag($params->get('robots'));

	if($params->get('meta_description') != "") {
		$mainframe->addMetaTag('description',$params->get('meta_description'));
	} else {
		$mainframe->addMetaTag('description',$mosConfig_MetaDesc);
	}
	if($params->get('meta_keywords') != "") {
		$mainframe->addMetaTag('keywords',$params->get('meta_keywords'));
	} else {
		$mainframe->addMetaTag('keywords',$mosConfig_MetaKeys);
	}
	if($params->get('meta_author') != "") {
		$mainframe->addMetaTag('author',$params->get('meta_author'));
	}

	BlogOutput($rows,$params,$gid,$access,$pop,$menu,$limitstart,$limit,$total);
}

function showArchiveSection($id = null,$gid,&$access,$pop,$option,$year,$month,$limit,$limitstart) {
	global $database,$mainframe,$mosConfig_MetaDesc,$mosConfig_MetaKeys;
	global $Itemid;

	$secID = ($id?$id:0);

	$noauth = !$mainframe->getCfg('shownoauth');

	$params = new stdClass();
	if($Itemid) {
		$menu = $mainframe->get('menu');
		$params = new mosParameters($menu->params);
	} else {
		$menu = "";
		$params = new mosParameters('');
	}

	$params->set('intro_only',1);
	$params->set('year',$year);
	$params->set('month',$month);

	// Ordering control
	$orderby_sec = $params->def('orderby_sec','rdate');
	$orderby_pri = $params->def('orderby_pri','');
	$order_sec = _orderby_sec($orderby_sec);
	$order_pri = _orderby_pri($orderby_pri);

	// used in query
	$where = _where(-1,$access,$noauth,$gid,$id,null,$year,$month);
	$where = (count($where)?"\n WHERE ".implode("\n AND ",$where):'');

	// checks to see if 'All Sections' options used
	if($id == 0) {
		$check = '';
	} else {
		$check = "\n AND a.sectionid = ".(int)$id;
	}
	// query to determine if there are any archived entries for the section
	$query = "SELECT a.id"."\n FROM #__content as a"."\n WHERE a.state = -1".$check;
	$database->setQuery($query);
	$items = $database->loadObjectList();
	$archives = count($items);

	// voting control
	$voting = $params->def('rating','');
	$voting = votingQuery($voting);

	// Limit & limitstart
	$intro = $params->def('intro',4);
	$leading = $params->def('leading',1);
	$links = $params->def('link',4);

	$limit = $limit?$limit:($intro + $leading + $links);

	// query to determine total number of records
	$query = "SELECT COUNT(a.id)"."\n FROM #__content AS a"."\n INNER JOIN #__categories AS cc ON cc.id = a.catid".
		"\n LEFT JOIN #__users AS u ON u.id = a.created_by"."\n LEFT JOIN #__sections AS s ON a.sectionid = s.id".
		"\n LEFT JOIN #__groups AS g ON a.access = g.id".$where;
	$database->setQuery($query);
	$total = $database->loadResult();

	if($total <= $limit) {
		$limitstart = 0;
	}

	// Main Query
	$query = "SELECT a.id, a.title, a.title_alias, a.introtext, a.sectionid, a.state, a.catid, a.created, a.created_by, a.created_by_alias, a.modified, a.modified_by,".
		"\n a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, a.images, a.urls, a.ordering, a.metakey, a.metadesc, a.access,".
		"\n CHAR_LENGTH( a.fulltext ) AS readmore, u.name AS author, u.usertype, s.name AS section, cc.name AS category, g.name AS groups".
		$voting['select']."\n FROM #__content AS a"."\n INNER JOIN #__categories AS cc ON cc.id = a.catid".
		"\n LEFT JOIN #__users AS u ON u.id = a.created_by"."\n LEFT JOIN #__sections AS s ON a.sectionid = s.id".
		"\n LEFT JOIN #__groups AS g ON a.access = g.id".$voting['join'].$where."\n ORDER BY $order_pri $order_sec";
	$database->setQuery($query,$limitstart,$limit);
	$rows = $database->loadObjectList();

	// check whether section is published
	if(!count($rows) && $secID != 0) {
		$secCheck = new mosSection($database);
		$secCheck->load((int)$secID);

		/*
		* check whether section is published
		*/
		if(!$secCheck->published) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section access level allows access
		*/
		if($secCheck->access > $gid) {
			mosNotAuth();
			return;
		}
	}

	// Dynamic Page Title
	if($params->get('header') == "") {
		$mainframe->SetPageTitle($menu->name,$params);
	} else {
		$mainframe->SetPageTitle($params->get('header'));
	}
	# Joomlatwork: change into the dynamic robots metatag
	# Remark: Primairly the settings of blogsection, second the global settings ..
	#
	set_robot_metatag($params->get('robots'));

	if($params->get('meta_description') != "") {
		$mainframe->addMetaTag('description',$params->get('meta_description'));
	} else {
		$mainframe->addMetaTag('description',$mosConfig_MetaDesc);
	}
	if($params->get('meta_keywords') != "") {
		$mainframe->addMetaTag('keywords',$params->get('meta_keywords'));
	} else {
		$mainframe->addMetaTag('keywords',$mosConfig_MetaKeys);
	}
	if($params->get('meta_author') != "") {
		$mainframe->addMetaTag('author',$params->get('meta_author'));
	}


	BlogOutput($rows,$params,$gid,$access,$pop,$menu,$limitstart,$limit,$total,1,1);

}


function showArchiveCategory($id = 0,$gid,&$access,$pop,$option,$year,$month,$module,$limit,$limitstart) {
	global $database,$mainframe,$mosConfig_MetaDesc,$mosConfig_MetaKeys;
	global $Itemid;

	$noauth = !$mainframe->getCfg('shownoauth');

	// needed for check whether section & category is published
	$catID = ($id?$id:0);

	// used by archive module
	if($module) {
		$check = '';
	} else {
		$check = "\n AND a.catid = ".(int)$id;
	}

	if($Itemid) {
		$menu = $mainframe->get('menu');
		$params = new mosParameters($menu->params);
	} else {
		$menu = '';
		$params = new mosParameters('');
	}

	$params->set('year',$year);
	$params->set('month',$month);

	// Ordering control
	$orderby_sec = $params->def('orderby','rdate');
	$order_sec = _orderby_sec($orderby_sec);

	// used in query
	$where = _where(-2,$access,$noauth,$gid,$id,null,$year,$month);
	$where = (count($where)?"\n WHERE ".implode("\n AND ",$where):'');

	// получение числа архивных объектов. оптимизировано по совету: smart ( http://joomlaforum.ru/index.php/topic,20369.msg119792.html#msg119792 )
	$query = "SELECT COUNT(a.id)"."\n FROM #__content as a"."\n WHERE a.state = -1".$check;
	$database->setQuery($query);
	$archives = $database->loadResult();

	// voting control
	$voting = $params->def('rating','');
	$voting = votingQuery($voting);

	// Limit & limitstart
	$intro = $params->def('intro',4);
	$leading = $params->def('leading',1);
	$links = $params->def('link',4);

	$limit = $limit?$limit:($intro + $leading + $links);

	// query to determine total number of records
	$query = "  SELECT COUNT(a.id)
                FROM #__content AS a
                INNER JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                ".$where;
	$database->setQuery($query);
	$total = $database->loadResult();

	if($total <= $limit) {
		$limitstart = 0;
	}

	// main query
	$query = " SELECT   a.id, a.title, a.title_alias, a.introtext, a.sectionid, a.state, a.catid,
                        a.created, a.created_by, a.created_by_alias, a.modified, a.modified_by,
                        a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, a.images,
                        a.urls, a.ordering, a.metakey, a.metadesc, a.access,
                        CHAR_LENGTH( a.fulltext ) AS readmore,
                        u.name AS author, u.usertype, u.username,
                        s.name AS section,
                        cc.name AS category,
                        g.name AS groups
                        ".$voting['select']."
                FROM #__content AS a
                INNER JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                ".$voting['join']
                .$where."
                ORDER BY $order_sec";
	$database->setQuery($query,$limitstart,$limit);
	$rows = $database->loadObjectList();

	// check whether section & category is published
	if(!count($rows) && $catID != 0) {
		$catCheck = new mosCategory($database);
		$catCheck->load((int)$catID);

		/*
		* check whether category is published
		*/
		if(!$catCheck->published) {
			mosNotAuth();
			return;
		}
		/*
		* check whether category access level allows access
		*/
		if($catCheck->access > $gid) {
			mosNotAuth();
			return;
		}

		$secCheck = new mosSection($database);
		$secCheck->load($catCheck->section);

		/*
		* check whether section is published
		*/
		if(!$secCheck->published) {
			mosNotAuth();
			return;
		}
		/*
		* check whether category access level allows access
		*/
		if($secCheck->access > $gid) {
			mosNotAuth();
			return;
		}
	}



	// Dynamic Page Title

	if($params->get('header') == "") {
		$mainframe->SetPageTitle($menu->name,$params);
	} else {
		$mainframe->SetPageTitle($params->get('header'),$params);
	}
	# Joomlatwork: change into the dynamic robots metatag
	# Remark: Primairly the settings of blogsection, second the global settings ..
	#
	set_robot_metatag($params->get('robots'));

	if($params->get('meta_description') != "") {
		$mainframe->addMetaTag('description',$params->get('meta_description'));
	} else {
		$mainframe->addMetaTag('description',$mosConfig_MetaDesc);
	}
	if($params->get('meta_keywords') != "") {
		$mainframe->addMetaTag('keywords',$params->get('meta_keywords'));
	} else {
		$mainframe->addMetaTag('keywords',$mosConfig_MetaKeys);
	}
	if($params->get('meta_author') != "") {
		$mainframe->addMetaTag('author',$params->get('meta_author'));
	}

		// if coming from the Archive Module, the Archive Dropdown selector is not shown
		if($id) {
			BlogOutput($rows,$params,$gid,$access,$pop,$menu,$limitstart,$limit,$total,1,1);
		} else {
			BlogOutput($rows,$params,$gid,$access,$pop,$menu,$limitstart,$limit,$total,0,1);
		}

}


function BlogOutput(&$rows,&$params,$gid,&$access,$pop,&$menu,$limitstart,$limit,$total,$archive = null,$archive_page = null) {
	global $mainframe,$Itemid,$task,$id,$option,$database,$mosConfig_live_site, $mosConfig_absolute_path;

    $i = 0;
    $header = '';
    $display_desc = 0;
    $display_desc_img = 0;
    $display_desc_text = 0;
    $display_pagination = 0;
    $display_pagination_results = 0;
    $display_blog_more = 0;
    $tpl = '';
    $group_cat=$params->get('group_cat',0);

	if($params->get('page_title',1) && $menu) {
		$header = $params->def('header',$menu->name);
	}

	$columns = $params->def('columns',2);
	if($columns == 0) {
		$columns = 1;
	}
	$intro = $params->def('intro',4);
	$leading = $params->def('leading',1);
	$links = $params->def('link',4);
	$pagination = $params->def('pagination',2);
	$pagination_results = $params->def('pagination_results',1);
	$pagination_results = $params->def('pagination_results',1);
	$descrip = $params->def('description',0);
	$descrip_image = $params->def('description_image',0);
	$back = $params->get('back_button',$mainframe->getCfg('back_button'));
	$params->set('back_button',0);
	$params->def('pageclass_sfx','');
	$params->set('intro_only',1);
	
	 //группировка по категориям
	 $group_cat=$params->get('group_cat',0);
	 $groupcat_limit=$params->get('groupcat_limit',0);
	 $cats_arr=array(); $k=0;
	 
	 $sfx = $params->get('pageclass_sfx');

	// used to display section/catagory description text and images
	// currently not supported in Archives
	if($menu && $menu->componentid && ($descrip || $descrip_image)) {
	    $display_desc = 1;

		switch($menu->type) {
			case 'content_blog_section':
				$description = new mosSection($database);
				$description->load((int)$menu->componentid);

				break;

			case 'content_blog_category':
				$description = new mosCategory($database);
				$description->load((int)$menu->componentid);

				break;

			default:
				$menu->componentid = 0;
				break;
		}
	    if($descrip_image && $description->image) {
	    	$display_desc_img = 1;
	    }
	            	if($descrip && $description->description) {
            		$display_desc_text = 1;
		}
	}

    // checks to see if there are there any items to display
	if($total) {
		$col_with = 100 / $columns; // width of each column
		$width = 'width="'.intval($col_with).'%"';

		if($archive) {
			// Search Success message
			$msg = sprintf(_ARCHIVE_SEARCH_SUCCESS,$params->get('month'),$params->get('year'));
		}

        // Links output
		if($links && ($i < $total - $limitstart)) {
		    $display_blog_more = 1;
			$showmore = $leading + $intro;
		}

		// Pagination output
		if($pagination) {
			if(($pagination == 2) && ($total <= $limit)) {
				// not visible when they is no 'other' pages to display
			} else {
			    $display_pagination = 1;
				require_once ($GLOBALS['mosConfig_absolute_path'].'/includes/pageNavigation.php');
				$limitstart = $limitstart?$limitstart:0;
				$pageNav = new mosPageNav($total,$limitstart,$limit);

				if($Itemid && $Itemid != 99999999) {
					// where Itemid value is returned, do not add Itemid to url
					$Itemid_link = '&amp;Itemid='.$Itemid;
				} else {
					// where Itemid value is NOT returned, do not add Itemid to url
					$Itemid_link = '';
				}

				if($option == 'com_frontpage') {
					$link = 'index.php?option=com_frontpage'.$Itemid_link;
				} else
					if($archive_page) {
						$year = $params->get('year');
						$month = $params->get('month');

						if(!$archive) {
							// used when access via archive module
							$pid = '&amp;id=0';
							$module = '&amp;module=1';
						} else {
							// used when access via menu item
							$pid = '&amp;id='.$id;
							$module = '';
						}

						$link = 'index.php?option=com_content&amp;task='.$task.$pid.$Itemid_link.'&amp;year='.$year.'&amp;month='.$month.$module;
					} else {
						$link = 'index.php?option=com_content&amp;task='.$task.'&amp;id='.$id.$Itemid_link;
					}

				if($pagination_results) {
				    $display_pagination_results = 1;
				}
			}
		}
	}

    else if($archive && !$total) {
	    $msg = sprintf(_ARCHIVE_SEARCH_FAILURE,$params->get('month'),$params->get('year'));
    }

	// Back Button
	$params->set('back_button',$back);

    $template = new jstContentTemplate();
     $templates = null;
    //Определяем шаблон вывода страницы

     //Если это архив
    if($archive) {
        switch ($task){
            //Архив раздела
            case 'archivesection':
            default:
                $page_type = 'section_archive';
                $templates = $params->section_data->templates;
            break;

            //Архив категории
            case 'archivecategory':
                $page_type = 'category_archive';

                if($template->isset_settings($page_type, $params->category_data->templates)){
                    $templates = $params->category_data->templates;
                }
                elseif($template->isset_settings($page_type, $params->section_data->templates)){
                    $templates = $params->section_data->templates;
                }
            break;
        }
    }

    //Если это главная страница - компонент 'com_frontpage'
   else if($_REQUEST['option']=='com_frontpage'){
        include_once($mosConfig_absolute_path.'/components/com_content/view/frontpage/default.php');
        return;
    }

    //Не главная страница и не архив - обычный блог раздела или категории
    else{
        switch ($task){
            case 'blogcategory':
            default:
                $page_type = 'category_blog';
                //проверяем настройки категории на предмет  заданного шаблона
                if($template->isset_settings($page_type, $params->category_data->templates)){
                    $templates = $params->category_data->templates;
                }
                //иначе - проверяем настройки раздела
                elseif($template->isset_settings($page_type, $params->section_data->templates)){
                    $templates = $params->section_data->templates;
                }

            break;

            case 'blogsection':
                //Если группировка по категориям отключена - оставляем вывод как и был прежде
                if(!$group_cat){
                    $page_type = 'section_blog';
                }
                //Если включена группировка по категориям
                else{
                    $page_type = 'groupcats';
                }
                $templates = $params->section_data->templates;
                break;
        }
    }

    $template->set_template($page_type, $templates);
    include_once($template->template_file);
}


function showItem($uid,$gid,&$access,$pop) {
	global $database,$mainframe,$mosConfig_disable_date_state,$mosConfig_disable_access_control;
	global $mosConfig_MetaTitle,$mosConfig_MetaAuthor;
    global $task, $my;

	$now = _CURRENT_SERVER_TIME;
	$nullDate = $database->getNullDate();

	if($access->canEdit || $task =='preview') {
		$xwhere = '';
	} else {
		$xwhere = " AND ( a.state = 1 OR a.state = -1 )";
		if(!$mosConfig_disable_date_state) {
			$xwhere .= " AND ( a.publish_up = ".$database->Quote($nullDate)	. " OR a.publish_up <= ".$database->Quote($now)." )";
			$xwhere .= " AND ( a.publish_down = ".$database->Quote($nullDate)	. " OR a.publish_down >= ".$database->Quote($now)." )";
		}
		;
	}
	if(!$mosConfig_disable_access_control) $where_ac = "\n AND a.access <= ".(int)$gid;
	else $where_ac = '';
	// main query
	$query = "SELECT a.*,
                    cc.name AS category, cc.templates as c_templates, cc.access AS cat_access, cc.id as cat_id, cc.published AS cat_pub,
                    s.name AS section, s.published AS sec_pub, s.id AS sec_id, s.templates as s_templates, s.access AS sec_access,
                    u.name AS author, u.usertype, u.username,
                    g.name AS groups
            FROM #__content AS a
            LEFT JOIN #__categories AS cc ON cc.id = a.catid
            LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = 'content'
            LEFT JOIN #__users AS u ON u.id = a.created_by
            LEFT JOIN #__groups AS g ON a.access = g.id
            WHERE a.id = ".(int)$uid.$xwhere.$where_ac;
	$database->setQuery($query);
	$row = null;

	if($database->loadObject($row)) {

        if($task=='preview' && $my->id!=$row->created_by){
            mosNotAuth();
			return;
        }

		/*
		* check whether category is published
		*/
		if(!$row->cat_pub && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section is published
		*/
		if(!$row->sec_pub && $row->sectionid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether category access level allows access
		*/
		if(($row->cat_access > $gid) && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section access level allows access
		*/
		if(($row->sec_access > $gid) && $row->sectionid) {
			mosNotAuth();
			return;
		}

		$params = new mosParameters($row->attribs);
		$params->set('intro_only',0);
		$params->def('back_button',$mainframe->getCfg('back_button'));
		if($row->sectionid == 0) {
			$params->set('item_navigation',0);
		} else {
			$params->set('item_navigation',$mainframe->getCfg('item_navigation'));
		}

		// loads the links for Next & Previous Button
		if($params->get('item_navigation')) {
			// Paramters for menu item as determined by controlling Itemid
			$menu = $mainframe->get('menu');
			$mparams = new mosParameters($menu->params);

			// the following is needed as different menu items types utilise a different param to control ordering
			// for Blogs the `orderby_sec` param is the order controlling param
			// for Table and List views it is the `orderby` param
			$mparams_list = $mparams->toArray();
			if(array_key_exists('orderby_sec',$mparams_list)) {
				$order_method = $mparams->get('orderby_sec','');
			} else {
				$order_method = $mparams->get('orderby','');
			}

			// additional check for invalid sort ordering
			if($order_method == 'front') {
				$order_method = '';
			}
			$orderby = _orderby_sec($order_method);

			$where_ac = (!$mosConfig_disable_access_control?'':"\n AND a.access <= ".(int)$gid);

			$uname = '';
			$ufrom = '';
			if($order_method=='author' OR $order_method=='rauthor'){
				$uname = ', u.name ';
				$ufrom = ', #__users AS u ';
			}

			// array of content items in same category correctly ordered
			$query = "SELECT a.id, a.title $uname FROM #__content AS a $ufrom WHERE a.catid = "
					.(int)$row->catid
					."\n AND a.state = ".(int)$row->state.($access->canEdit? '' : $where_ac)
					.$xwhere."\n ORDER BY $orderby";
			$database->setQuery($query);
			$list = $database->loadObjectList();

			// this check needed if incorrect Itemid is given resulting in an incorrect result
			//  это кажется не надо, ведь чуть выше загружали объект - loadObjectList
			//if(!is_array($list)) {
			//	$list = array();
			//}
			// location of current content item in array list
			$prev = null;
			$current = array_shift($list);
			$next = array_shift($list);
			while($current->id != $uid) {
				$prev = $current;
				$current = $next;
				$next = array_shift($list);
			}
			$row->prev = '';
			$row->next = '';
			if(!empty($prev)) {
				$row->prev = $prev->id;
				$row->prev_title = $prev->title;
			}
			if(!empty($next)) {
				$row->next = $next->id;
				$row->next_title = $next->title;
			}
			unset($list);
		}




        $params->section_data = null;
        $params->category_data = null;

        if(!$row->sectionid){
            //$template='static_content/default.php';
             $params->page_type ='item_static';
        }
        else{
            //$template='full_view/default.php';
            $section = new mosSection($database);
            $category = new mosCategory($database);

            $params->page_type ='item_full';
            $params->section_data = $section;
            $params->category_data = $category;
        }


        //$row->rating=$row->total_rate;
		show($row,$params,$gid,$access,$pop);

		// page title
		$mainframe->setPageTitle($row->title,$params);

		if($mosConfig_MetaTitle == '1') {
			$mainframe->addMetaTag('title',$row->title);
		}
		if($mosConfig_MetaAuthor == '1') {
			if($row->created_by_alias != "") {
				$mainframe->addMetaTag('author',$row->created_by_alias);
			} else {
				$mainframe->addMetaTag('author',$row->author);
			}

		}
		if($params->get('robots') == 0) {
			$mainframe->addMetaTag('robots','index, follow');
		}
		if($params->get('robots') == 1) {
			$mainframe->addMetaTag('robots','index, nofollow');
		}
		if($params->get('robots') == 2) {
			$mainframe->addMetaTag('robots','noindex, follow');
		}
		if($params->get('robots') == 3) {
			$mainframe->addMetaTag('robots','noindex, nofollow');
		}

	} else {
		mosNotAuth();
		return;
	}
}


function show($row,$params,$gid,&$access,$pop, $template='') {
	global $database,$mainframe,$mosConfig_content_hits;
	global $cache;

	$noauth = !$mainframe->getCfg('shownoauth');

	if($access->canEdit) {
		if($row->id === null || $row->access > $gid) {
			mosNotAuth();
			return;
		}
	} else {
		if($row->id === null || $row->state == 0) {
			mosNotAuth();
			return;
		}
		if($row->access > $gid) {
			if($noauth) {
				mosNotAuth();
				return;
			} else {
				if(!($params->get('intro_only'))) {
					mosNotAuth();
					return;
				}
			}
		}
	}

	// GC Parameters
	$params->def('link_titles',$mainframe->getCfg('link_titles'));
	$params->def('author',!$mainframe->getCfg('hideAuthor'));
	$params->def('createdate',!$mainframe->getCfg('hideCreateDate'));
	$params->def('modifydate',!$mainframe->getCfg('hideModifyDate'));
	$params->def('print',!$mainframe->getCfg('hidePrint'));
	$params->def('email',!$mainframe->getCfg('hideEmail'));
	$params->def('rating',$mainframe->getCfg('vote'));
	$params->def('icons',$mainframe->getCfg('icons'));
	$params->def('readmore',$mainframe->getCfg('readmore'));
	// Other Params
	$params->def('image',1);
	$params->def('section',0);
	$params->def('section_link',0);
	$params->def('category',0);
	$params->def('category_link',0);
	$params->def('introtext',$params->get('introtext',1));
    $params->def('view_introtext',1);
	//$params->def('introtext',1);
	$params->def('pageclass_sfx','');
	$params->def('item_title',1);
	$params->def('url',1);

    $limit_introtext=$params->get('introtext_limit', 0);



	// if a popup item (e.g. print page) set popup param to correct value
	if($pop) {
		$params->set('popup',1);
	}

	// check if voting/rating enabled
	if($params->get('rating')) {
		$row->rating = null;
		$row->rating_count = null;
		global $voteLoad,$task;
		if(!isset($voteLoad)) {
			$query = "SELECT ROUND( rating_sum / rating_count ) AS rating, rating_count, content_id FROM #__content_rating";
			$task == 'view' ? $query .= "\n WHERE content_id=$row->id":'';
			$database->setQuery($query);
			$Allvote = $database->loadObjectList();
			$voteLoad = array();
			foreach($Allvote as $vote) {
				$voteLoad[$vote->content_id]['rating'] = $vote->rating;
				$voteLoad[$vote->content_id]['rating_count'] = $vote->rating_count;
			}
		}
		;

		if(isset($voteLoad[$row->id])) {
			$row->rating = $voteLoad[$row->id]['rating'];
			$row->rating_count = $voteLoad[$row->id]['rating_count'];
		}
	}

	$row->category = htmlspecialchars(stripslashes($row->category),ENT_QUOTES);
	if($params->get('section_link') || $params->get('category_link')) {
		// loads the link for Section name
		if($params->get('section_link') && $row->sectionid) {
			// pull values from mainframe
			$secLinkID = $mainframe->get('secID_'.$row->sectionid,-1);
			$secLinkURL = $mainframe->get('secURL_'.$row->sectionid);

			// check if values have already been placed into mainframe memory
			if($secLinkID == -1) {
				$query = "SELECT id, link FROM #__menu WHERE published = 1 AND type IN ( 'content_section', 'content_blog_section' ) AND componentid = ".(int)$row->sectionid."\n ORDER BY type DESC, ordering";
				$database->setQuery($query);
				//$secLinkID = $database->loadResult();
				$result = $database->loadRow();

				$secLinkID = $result[0];
				$secLinkURL = $result[1];

				if($secLinkID == null) {
					$secLinkID = 0;
					// save 0 query result to mainframe
					$mainframe->set('secID_'.$row->sectionid,0);
				} else {
					// save query result to mainframe
					$mainframe->set('secID_'.$row->sectionid,$secLinkID);
					$mainframe->set('secURL_'.$row->sectionid,$secLinkURL);
				}
			}

			$_Itemid = '';
			// use Itemid for section found in query
			if($secLinkID != -1 && $secLinkID) {
				$_Itemid = '&amp;Itemid='.$secLinkID;
			}
			if($secLinkURL) {
				$secLinkURL = ampReplace($secLinkURL);
				$link = sefRelToAbs($secLinkURL.$_Itemid);
			} else {
				$link = sefRelToAbs('index.php?option=com_content&amp;task=section&amp;id='.$row->sectionid.$_Itemid);
			}
			$row->section = '<a href="'.$link.'">'.$row->section.'</a>';
		}

		// loads the link for Category name
		if($params->get('category_link') && $row->catid) {
			// pull values from mainframe
			$catLinkID = $mainframe->get('catID_'.$row->catid,-1);
			$catLinkURL = $mainframe->get('catURL_'.$row->catid);

			// check if values have already been placed into mainframe memory
			if($catLinkID == -1) {
				$query = "SELECT id, link FROM #__menu WHERE published = 1 AND type IN ( 'content_category', 'content_blog_category' ) AND componentid = ".(int)$row->catid."\n ORDER BY type DESC, ordering";
				$database->setQuery($query);
				//$catLinkID = $database->loadResult();
				$result = $database->loadRow();

				$catLinkID = $result[0];
				$catLinkURL = $result[1];

				if($catLinkID == null) {
					$catLinkID = 0;
					// save 0 query result to mainframe
					$mainframe->set('catID_'.$row->catid,0);
				} else {
					// save query result to mainframe
					$mainframe->set('catID_'.$row->catid,$catLinkID);
					$mainframe->set('catURL_'.$row->catid,$catLinkURL);
				}
			}

			$_Itemid = '';
			// use Itemid for category found in query
			if($catLinkID != -1 && $catLinkID) {
				$_Itemid = '&amp;Itemid='.$catLinkID;
			} else
				if(isset($secLinkID) && $secLinkID != -1 && $secLinkID) {
					// use Itemid for section found in query
					$_Itemid = '&amp;Itemid='.$secLinkID;
				}
			if($catLinkURL) {
				$link = sefRelToAbs($catLinkURL.$_Itemid);
			} else {
				$link = sefRelToAbs('index.php?option=com_content&amp;task=category&amp;sectionid='.
					$row->sectionid.'&amp;id='.$row->catid.$_Itemid);
			}
			$row->category = '<a href="'.$link.'">'.$row->category.'</a>';
		}
	}

	// show/hides the intro text
	if($params->get('introtext')) {
		if($params->get('jeditable'))
			$row->text = $row->introtext.($params->get('intro_only')?'':chr(13).'</div><div id="jneditf-'.$row->id.'">'.chr(13).$row->fulltext.chr(13).chr(13).$row->notetext);
		else
			$row->text = $row->introtext.($params->get('intro_only')?'':chr(13).chr(13).$row->fulltext.chr(13).chr(13).$row->notetext);
	} else {
		$row->text = $row->fulltext;
	}

    if($limit_introtext){
         $row->text=mosHTML::cleanText($row->text);
         $row->text = implode(" ", array_slice(preg_split("/\s+/", $row->text), 0, $limit_introtext)).'...';
    }

	// deal with the {mospagebreak} mambots
	// only permitted in the full text area
	$page = intval(mosGetParam($_REQUEST,'limitstart',0));

	// запись счетчика прочтения
	if(!$params->get('intro_only') && ($page == 0) && ($mosConfig_content_hits)) {
		$obj = new mosContent($database);
		$obj->hit($row->id);
	}

	// needed for caching purposes to stop different cachefiles being created for same item
	// does not affect anything else as hits data not outputted
	$row->hits = 0;

	$cache->call('HTML_content::show',$row,$params,$access,$page, $template);
}


function editItem($task) {
	global $database,$my, $gid, $mainframe, $acl;
	global $mosConfig_absolute_path,$mosConfig_live_site,$mosConfig_offset;
	// boston, при редактировании материала с фронта отключаем показ всех модулей - пользователю будет не повадно переходить по ссылкам без сохранения, и место освободим
	global $mosConfig_module_on_edit_off;

    if($mosConfig_module_on_edit_off == 1) {
        $GLOBALS['_MOS_MODULES'] = '';
    }

    $nullDate = $database->getNullDate();
    $lists = array();

    //$id обнаруживается в адресной строке только в том случае, если пользователь редактирует материал
    $id	= intval(mosGetParam($_REQUEST,'id',0));
    //$section может присутствовать в ссылке, если форма настроена для какого-то конкретного раздела  и это добавление записи
    $section = intval(mosGetParam($_REQUEST,'section',0));

    //По-умолчанию в '__menus' содержится запись о пункте меню,
    //с помощью которого настраивается добавление/редактирование всех записей (независимо от раздела)
    $link = 'index.php?option=com_content&task=new';
    $special_params=0;

    // Editor usertype check
    $access = new stdClass();
    $access->canEdit	= $acl->acl_check('action','edit','users',$my->usertype,'content','all');
    $access->canEditOwn	= $acl->acl_check('action','edit','users',$my->usertype,'content','own');
    $access->canPublish	= $acl->acl_check('action','publish','users',$my->usertype,'content','all');

    //Создаем объект
    $content = new mosContent($database);


    //Если это добавление новой записи
    if($task=='new'){
         $row = $content;
        //запрещаем доступ тем, кому низя - у кого нет прав ни на редактирование вообще, ни на редактирование своего контента
		if(!($access->canEdit || $access->canEditOwn)) {
			HTML_content::_no_access();
			return;
        }

        //если в ссылке, по которой пользователь пришел добавлять контент, обнаруживается 'section' -
        //ищем в базе пункт меню, с помощью которого настраивается форма именно для текущего раздела
        if($section){
            $link = 'index.php?option=com_content&task=new&section='.(int)$section;
            $special_params=1;
        }

        //запрос на данные о пункте меню
        $query = "SELECT id, params FROM #__menu WHERE (link LIKE '%$link') AND published = 1";
        $r=null;
	    $database->setQuery($query);
	    $database->loadObject($r);
        $exists = $r;
	}

    //Если это редактирование записи
    else if($task=='edit'){
       $row = $content->get_item((int)$id);

       $section = $row->sectionid;
		// запрещаем доступ
		if(!($access->canEdit || ($access->canEditOwn && $row->created_by == $my->id))) {
			mosNotAuth();
			return;
		}

	    // выводим сообщение, если данная запись сейчас редактируется кем-то другим
	    if($content->isCheckedOut($my->id)) {
		    mosErrorAlert("[ ".$row->title." ] "._CONTENT_IS_BEING_EDITED_BY_OTHER_PEOPLE);
	    }

        //два варианта, в которых могут существовать ссылки на добавление/редактирование
        $link1 = 'index.php?option=com_content&task=new&section='.(int)$section;
        $link2 = 'index.php?option=com_content&task=new';

        //запрос на данные о пункте меню
        $query = "  SELECT a.id AS menu_id2, a.params AS menu_params2 , b.id AS menu_id1, b.params AS menu_params1
                    FROM #__menu AS a
                    LEFT JOIN  #__menu AS b  ON  b.link LIKE '%$link1' AND b.published = 1
                    WHERE  a.link LIKE '%$link2' AND a.published = 1
                  ";
	    $database->setQuery($query);
	    $exists = $database->loadObjectList();
    }

    if(!$exists) {
	    mosNotAuth();
		return;
	}

    //если это добавление новой записи - все просто, передаем параметры для парсинга
    if($task=='new'){
        $params = new mosParameters($exists->params);
    }
    //если же это редактирование - то нужно определить, какие именно параметры будем передавать
    //- из настроек ссылки по-умолчанию, или же есть настройки для текущего раздела
    else{
        //проверим, есть ли специальные настройки
        if(isset($exists[0]->menu_id1)){
            $params = new mosParameters($exists[0]->menu_params1);
            $special_params=1;
        }
        //иначе проверим, существуют ли настройки по-умолчанию
        else if(isset($exists[0]->menu_id2)){
            $params = new mosParameters($exists[0]->menu_params2);
        }
        //ну и так, на всякий случай. А вдруг!
        else{
            $menu = $mainframe->get('menu');
	        $params = new mosParameters($menu->params);
        }
    }


    // параметры полученные из настроек ссылки в меню
	$ids_user = $params->get('ids_user',0);// введенные значения ID
    $ids_action = $params->get('ids_action',0);// тип обработки введенных ID

    $where_c = "";
    $where_s = "";
    if($ids_action && $ids_user){
        switch($ids_action){
            case '1':  //разрешить публикацию только в указанных РАЗДЕЛАХ
            default:
                $where_s = " AND ( s.id IN (". $ids_user .") )";
                //если есть специальные настройки для раздела - сбрасываем перечень ID разделов, которе задал пользователь
                //поскольку если есть специальные настройки -  запись может быть добавлена только в определенный раздел
                if($special_params){
                    $where_s = " ";
                }
            break;

            case '2': //разрешить публикацию только в указанных КАТЕГОРИЯХ
                $where_c = " AND ( c.id IN (". $ids_user .") )";
            break;

            case '3':  //запретить публикацию в указанных РАЗДЕЛАХ
                $where_s = " AND ( s.id NOT IN (". $ids_user .") )";
                if($special_params){
                    $where_s = " ";
                }
            break;

            case '4':  //запретить публикацию в указанных КАТЕГОРИЯХ
                $where_c = " AND ( c.id NOT IN (". $ids_user .") )";
            break;
        }
    }


	if($task=='edit') {
		$content->checkout($my->id);

		$row->created = mosFormatDate($row->created,_CURRENT_SERVER_TIME_FORMAT);
		$row->modified = $row->modified == $nullDate?'':mosFormatDate($row->modified,_CURRENT_SERVER_TIME_FORMAT);
		$row->publish_up = mosFormatDate($row->publish_up,_CURRENT_SERVER_TIME_FORMAT);

		if(trim($row->publish_down) == $nullDate || trim($row->publish_down) == '' ||
			trim($row->publish_down) == '-') {
			$row->publish_down = 'Никогда';
		}
		$row->publish_down = mosFormatDate($row->publish_down,_CURRENT_SERVER_TIME_FORMAT);


		$row->creator = $row->author_nickname;
        $row->modifier = $row->modifier_nickname;
		if($row->created_by == $row->modified_by) {
			$row->modifier = $row->creator;
		}

		$query = "SELECT content_id FROM #__content_frontpage WHERE content_id = ".(int)$row->id;
		$database->setQuery($query);
		$row->frontpage = $database->loadResult();

        if($row->sectionid){
            $sec = new mosSection($database);
            $sec->templates = $row->s_templates;
            $params->section_data = $sec;
        }


	} else {
		$row->sectionid = $section;
		$row->version = 0;
		$row->ordering = 0;
		$row->images = array();
		$row->publish_up = date('Y-m-d H:i:s',time() + ($mosConfig_offset* 60* 60));
		$row->publish_down = 'Никогда';
		$row->creator = 0;
		$row->modifier = 0;
		$row->frontpage = 0;
        $params->section_data = null;
        //публикация контента
        // Publishing state hardening for Authors
        $auto_publish = $params->get('auto_publish',0);

        if(!$auto_publish){ //Если выбран первый параметр - права по группам
        	if(!$access->canPublish) {
        	    $row->state = 0;
            }
            else{
                 $row->state = 1;
            }
       }
        else{
            $row->state = 1;
        }

        if($section){
            $sec = new mosSection($database);
            $sec->load((int)$section);
            $params->section_data = $sec;
        }

	}



	// make the select list for the states
	$states[] = mosHTML::makeOption(0,_CMN_UNPUBLISHED);
	$states[] = mosHTML::makeOption(1,_CMN_PUBLISHED);
	$lists['state'] = mosHTML::selectList($states,'state','class="inputbox" size="1"','value','text',intval($row->state));

	// build the html select list for ordering
	$query = "SELECT ordering AS value, title AS text FROM #__content WHERE catid = ".(int)$row->catid."\n ORDER BY ordering";
	$lists['ordering'] = mosAdminMenus::SpecificOrdering($row,$id,$query,1);

	//$database->setQuery("SELECT CONCAT(s.id,'/',c.id) as value, CONCAT(s.name,'/',c.name) as text  FROM #__categories AS c ,#__sections AS s where s.id=c.section  ");
	//$z_cats_main = $database->loadObjectList();
	//$lists['catid'] = mosHTML::selectList($rows,'catid','class="inputbox" size="1"','value','text',intval($row->catid));


    //--->>>Строим selectlist для выбора категории, к которой будет принадлежать материал: BEGIN---<<<

    //Если есть специальные настройки для добавления материала в раздел
    //выбираем только категории, принадлежашие данному разделу
    if($special_params){
        $database->setQuery(" SELECT  CONCAT(c.section,'*',c.id) AS cid , c.title AS c_title, c.section  FROM   #__categories AS c WHERE c.section=$section AND c.published=1 $where_c ORDER BY c.title ASC ");
	    $cids = $database->loadObjectList();
        //$cats[] = mosHTML::makeOption('-1','Выберите категорию','id','c_title');
		//$cats = array_merge($cats,$cids);
		$lists['catid'] = mosHTML::selectList($cids,'catid','class="inputbox" size="1"','cid','c_title',intval($row->catid).'*'.($row->sectionid));
    }
    //если же это настройки по умолчанию - нужно построить селект, в котором присутствуют и разделы, и категории
    else{
    	$database->setQuery(" SELECT  c.id AS cid , c.title AS c_name, c.section  FROM   #__categories AS c WHERE c.published=1 $where_c ORDER BY title ASC ");
	    $cids = $database->loadObjectList();

        $database->setQuery(" SELECT   s.id, s.title  FROM  #__sections AS s WHERE s.published=1 $where_s ORDER BY title ASC");
	    $sids = $database->loadObjectList();

        //Здесь страшно, надо бы переписать ((
        $return="<select name=\"catid\" class=\"inputbox\" size=\"1\">";
        $cids_arr=array();
        $i2=0;  $i3=0;
    	foreach($cids as $row2) {
            $cids_arr[$i2]['cat_name']=$row2->c_name;
            $cids_arr[$i2]['parent']=$row2->section;
            $cids_arr[$i2]['cid']=$row2->cid;
            $i2++;
    	}

        foreach($sids as $row3) {
            $return .= "<option value=\"\" disabled=\"disabled\" style=\"color:#EF3527;\">" . $row3->title . "</option>";
            foreach($cids_arr as $v){
                if($v['parent']==$row3->id){
                    if($v['cid']==$row->catid){
                        $extra = " selected=\"selected\"";
                    } else {
                        $extra ="";
                    }
                  $return .= "<option value=\"".$row3->id."*".$v['cid']."\"$extra>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- " . $v['cat_name'] . "</option>";
                }
            }
            $i3++;
    	}

        $return .="</select>";
        $lists['catid']=$return;
    }
    //--->>>Строим selectlist для выбора категории, к которой будет принадлежать материал: END---<<<


	// build the html select list for the group access
	$lists['access'] = mosAdminMenus::Access($row);

    $page = new stdClass();
    $page->params = $params;
    $page->access = $access;
    $page->params->page_type = 'item_edit';

    $row->lists = $lists;

	HTML_content::editContent($row,$page,$task);
}


/**
* Saves the content item an edit form submit
*/
function saveContent(&$access,$task) {
	global $database,$mainframe,$my;
	global $mosConfig_absolute_path,$mosConfig_offset,$Itemid;

	// simple spoof check security
	josSpoofCheck();

	$nullDate = $database->getNullDate();
    $row = new mosContent($database);

	if(!$row->bind($_POST)) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// sanitise id field
	$row->id = (int)$row->id;

	$isNew = $row->id < 1;
	if($isNew) {
		// new record
		if(!($access->canEdit || $access->canEditOwn)) {
			mosNotAuth();
			return;
		}

		$row->created = date('Y-m-d H:i:s');
		$row->created_by = $my->id;
	} else {
		// existing record
		if(!($access->canEdit || ($access->canEditOwn && $row->created_by == $my->id))) {
			mosNotAuth();
			return;
		}

		$row->modified = date('Y-m-d H:i:s');
		$row->modified_by = $my->id;
	}



	if(strlen(trim($row->publish_up)) <= 10) {
		$row->publish_up .= ' 00:00:00';
	}
	$row->publish_up = mosFormatDate($row->publish_up,_CURRENT_SERVER_TIME_FORMAT,-
		$mosConfig_offset);

	if(trim($row->publish_down) == 'Никогда' || trim($row->publish_down) == '') {
		$row->publish_down = $nullDate;
	} else {
		if(strlen(trim($row->publish_down)) <= 10) {
			$row->publish_down .= ' 00:00:00';
		}
		$row->publish_down = mosFormatDate($row->publish_down,
			_CURRENT_SERVER_TIME_FORMAT,-$mosConfig_offset);
	}

	// code cleaner for xhtml transitional compliance
	$row->introtext = str_replace('<br>','<br />',$row->introtext);
	$row->fulltext = str_replace('<br>','<br />',$row->fulltext);

	// remove <br /> take being automatically added to empty fulltext
	$length = strlen($row->fulltext) < 9;
	$search = strstr($row->fulltext,'<br />');
	if($length && $search) {
		$row->fulltext = null;
	}

	$row->title = ampReplace($row->title);

	// Publishing state hardening for Authors
    //Участок перенесен в функцию редактирования

    if(isset($_POST['catid'])){
      $catid0=explode('*', $_POST['catid']);
      $row->catid=$catid0[1];
      $row->sectionid=$catid0[0];
    }



	if(!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->version++;
	if(!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// manage frontpage items
	require_once ($mainframe->getPath('class','com_frontpage'));
	$fp = new mosFrontPage($database);

	if(intval(mosGetParam($_REQUEST,'frontpage',0))) {
		// toggles go to first place
		if(!$fp->load((int)$row->id)) {
			// new entry
			$query = "INSERT INTO #__content_frontpage VALUES ( ".(int)$row->id.", 1 )";
			$database->setQuery($query);
			if(!$database->query()) {
				echo "<script> alert('".$database->stderr()."');</script>\n";
				exit();
			}
			$fp->ordering = 1;
		}
	} else {
		// no frontpage mask
		if(!$fp->delete((int)$row->id)) {
			$msg .= $fp->stderr();
		}
		$fp->ordering = 0;
	}
	$fp->updateOrder();

	$row->checkin();
	$row->updateOrder("catid = ".(int)$row->catid);



	$msg = $isNew?_THANK_SUB:_E_ITEM_SAVED;

    if($my->usertype == 'Publisher' || $row->state==1){
        $msg = _THANK_SUB_PUB;
    }

    $page = new stdClass();
    $page->access = $access;
    $page->task = $task;

    if($isNew){
        _after_create_content($row, $page);
    }
    else{
        _after_update_content($row, $page);
    }
}

function _after_create_content($row, $page){
    global $my, $database, $mosConfig_absolute_path;

	// gets section name of item
	$query = "SELECT s.title"
			."\n FROM #__sections AS s"
			."\n WHERE s.scope = 'content'"
			."\n AND s.id = ".(int)$row->sectionid;
	$database->setQuery($query);
	// gets category name of item
	$section = $database->loadResult();

	$query = "SELECT c.title"
			."\n FROM #__categories AS c"
			."\n WHERE c.id = ".(int)$row->catid;
	$database->setQuery($query);
	$category = $database->loadResult();
	$category = stripslashes($category);


		// Отправка сообщения админам о новой записе
		require_once ($mosConfig_absolute_path.'/components/com_messages/messages.class.php');
		$query = "SELECT id"
				."\n FROM #__users"
				."\n WHERE sendEmail = 1";
		$database->setQuery($query);
		$users = $database->loadResultArray();
		foreach($users as $user_id) {
			$msg = new mosMessage($database);
			$msg->send($my->id,$user_id,"Новый объект",sprintf(_ON_NEW_CONTENT,$my->username,$row->title,$section,$category));
		}


    	switch($page->task) {

        //если "Применить"
		case 'apply':
            //возвращаемся на страницу редактирования
            $msg = 'Изменения сохранены. Здесь (ссылка) страница для предпросмотра';
			$link = $_SERVER['HTTP_REFERER'];
			break;

       //если "Сохранить"
		case 'save':
		default:
            //если запись опубликована, даем ссылку на просмотр в обычном режиме
            if($row->state==1){
                $msg = 'Спасибо и все такое';
                $link = 'index.php?option=com_content&task=view&id='.$row->id;
            }
            //иначе - формируем ссылку на предварительный просмотр статьи
            else{
                $msg = 'Материал был успешно добавлен и будет доступен для общего просмотра после проверки модератором. А пока повпечатляйтесь версией для предпросмотра.';
                $link = 'index.php?option=com_content&task=preview&id='.$row->id;
            }

		    break;
	}

    mosRedirect($link,$msg);

}

function _after_update_content($row, $page){
    global $my;

    	switch($page->task) {
		case 'apply':
            $msg = 'Изменения сохранены.';
			$link = $_SERVER['HTTP_REFERER'];
			break;

		case 'save':
		default:
            //если запись опубликована, даем ссылку на просмотр в обычном режиме
            if($row->state==1){
                $msg = 'Все изменения были успешно сохранены';
                $link = 'index.php?option=com_content&task=view&id='.$row->id;
            }
            //иначе - формируем ссылку на предварительный просмотр статьи
            else{
                $msg = 'Внимание! Это версия для предпросмотра. Материал еще не был опубликован на сайте, вероятно, ожидается проверка модератором.';
                $link = 'index.php?option=com_content&task=preview&id='.$row->id;
            }

	}

    mosRedirect($link,$msg);

}


/**
* Cancels an edit operation
* @param database A database connector object
*/
function cancelContent(&$access) {
	global $database,$my,$task;

	$row = new mosContent($database);
	$row->bind($_POST);

	if($access->canEdit || ($access->canEditOwn && $row->created_by == $my->id)) {
		$row->checkin();
	}

	$Itemid = intval(mosGetParam($_POST,'Returnid','0'));

	$referer = strval(mosGetParam($_POST,'referer',''));
	$parts = parse_url($referer);
	parse_str($parts['query'],$query);

	if($task == 'edit' || $task == 'cancel') {
		$Itemid = mosGetParam($_POST,'Returnid','');
		$referer = 'index.php?option=com_content&task=view&id='.$row->id.'&Itemid='.$Itemid;
	}

	if($referer && $row->id) {
		mosRedirect($referer);
	} else {
		mosRedirect('index.php');
	}
}

/**
* Shows the email form for a given content item.
* @param int The content item id
*/
function emailContentForm($uid,$gid) {
	global $database,$mosConfig_hideEmail;

	$id = intval(mosGetParam($_REQUEST,'id',0));
	if($id) {
		$query = 'SELECT attribs FROM #__content WHERE `id`='.$id;
		$database->setQuery($query);
		$params = new mosParameters($database->loadResult());
	} else {
		$params = new mosParameters('');
	}
	$email = intval($params->get('email',0));
	if($mosConfig_hideEmail && !$email) {
		echo _NOT_AUTH;
		return;
	}

	$itemid = intval(mosGetParam($_GET,'itemid',0));

	$now = _CURRENT_SERVER_TIME;
	$nullDate = $database->getNullDate();

	// query to check for state and access levels
	$query = "SELECT a.*, cc.name AS category, s.name AS section, s.published AS sec_pub, cc.published AS cat_pub,".
		"\n  s.access AS sec_access, cc.access AS cat_access, s.id AS sec_id, cc.id as cat_id".
		"\n FROM #__content AS a"."\n LEFT JOIN #__categories AS cc ON cc.id = a.catid".
		"\n LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = 'content'"."\n WHERE a.id = ".(int)
		$uid."\n AND a.state = 1"."\n AND a.access <= ".(int)$gid."\n AND ( a.publish_up = ".
		$database->Quote($nullDate)." OR a.publish_up <= ".$database->Quote($now)." )".
		"\n AND ( a.publish_down = ".$database->Quote($nullDate).
		" OR a.publish_down >= ".$database->Quote($now)." )";
	$database->setQuery($query);
	$row = null;

	if($database->loadObject($row)) {
		/*
		* check whether category is published
		*/
		if(!$row->cat_pub && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section is published
		*/
		if(!$row->sec_pub && $row->sectionid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether category access level allows access
		*/
		if(($row->cat_access > $gid) && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section access level allows access
		*/
		if(($row->sec_access > $gid) && $row->sectionid) {
			mosNotAuth();
			return;
		}

		$query = "SELECT template"."\n FROM #__templates_menu"."\n WHERE client_id = 0".
			"\n AND menuid = 0";
		$database->setQuery($query);
		$template = $database->loadResult();

		HTML_content::emailForm($row->id,$row->title,$template,$itemid);
	} else {
		mosNotAuth();
		return;
	}
}

/**
* Shows the email form for a given content item.
* @param int The content item id
*/
function emailContentSend($uid,$gid) {
	global $database,$mainframe;
	global $mosConfig_sitename,$mosConfig_hideEmail;

	$id = intval(mosGetParam($_REQUEST,'id',0));
	if($id) {
		$query = 'SELECT attribs FROM #__content WHERE `id`='.$id;
		$database->setQuery($query);
		$params = new mosParameters($database->loadResult());
	} else {
		$params = new mosParameters('');
	}
	$paramEmail = intval($params->get('email',0));
	if($mosConfig_hideEmail && !$paramEmail) {
		echo _NOT_AUTH;
		return;
	}

	// simple spoof check security
	josSpoofCheck(1);

	// check for session cookie
	// Session Cookie `name`
	$sessionCookieName = mosMainFrame::sessionCookieName();
	// Get Session Cookie `value`
	$sessioncookie = mosGetParam($_COOKIE,$sessionCookieName,null);

	if(!(strlen($sessioncookie) == 32 || $sessioncookie == '-')) {
		mosErrorAlert(_NOT_AUTH);
	}

	$itemid = intval(mosGetParam($_POST,'itemid',0));
	$now = _CURRENT_SERVER_TIME;
	$nullDate = $database->getNullDate();

	// query to check for state and access levels
	$query = "SELECT a.*, cc.name AS category, s.name AS section, s.published AS sec_pub, cc.published AS cat_pub,".
		"\n  s.access AS sec_access, cc.access AS cat_access, s.id AS sec_id, cc.id as cat_id".
		"\n FROM #__content AS a"."\n LEFT JOIN #__categories AS cc ON cc.id = a.catid".
		"\n LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = 'content'"."\n WHERE a.id = ".(int)
		$uid."\n AND a.state = 1"."\n AND a.access <= ".(int)$gid."\n AND ( a.publish_up = ".
		$database->Quote($nullDate)." OR a.publish_up <= ".$database->Quote($now)." )".
		"\n AND ( a.publish_down = ".$database->Quote($nullDate).
		" OR a.publish_down >= ".$database->Quote($now)." )";
	$database->setQuery($query);
	$row = null;

	if($database->loadObject($row)) {
		/*
		* check whether category is published
		*/
		if(!$row->cat_pub && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section is published
		*/
		if(!$row->sec_pub && $row->sectionid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether category access level allows access
		*/
		if(($row->cat_access > $gid) && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section access level allows access
		*/
		if(($row->sec_access > $gid) && $row->sectionid) {
			mosNotAuth();
			return;
		}

		$email = strval(mosGetParam($_POST,'email',''));
		$yourname = strval(mosGetParam($_POST,'yourname',''));
		$youremail = strval(mosGetParam($_POST,'youremail',''));
		$subject = strval(mosGetParam($_POST,'subject',''));
		if(empty($subject)) {
			$subject = _EMAIL_INFO.' '.$yourname;
		}

		if($uid < 1 || !$email || !$youremail || (JosIsValidEmail($email) == false) || (JosIsValidEmail
			($youremail) == false)) {
			mosErrorAlert(_EMAIL_ERR_NOINFO);
		}

		$query = "SELECT template"."\n FROM #__templates_menu"."\n WHERE client_id = 0".
			"\n AND menuid = 0";
		$database->setQuery($query);
		$template = $database->loadResult();

		// determine Itemid for Item
		if($itemid) {
			$_itemid = '&Itemid='.$itemid;
		} else {
			$itemid = $mainframe->getItemid($uid,0,0);
			$_itemid = '&Itemid='.$itemid;
		}

		// link sent in email
		$link = sefRelToAbs('index.php?option=com_content&task=view&id='.$uid.$_itemid);

		// message text
		$msg = sprintf(_EMAIL_MSG,html_entity_decode($mosConfig_sitename,ENT_QUOTES),$yourname,
			$youremail,$link);

		// mail function
		$success = mosMail($youremail,$yourname,$email,$subject,$msg);
		if(!$success) {
			mosErrorAlert(_EMAIL_ERR_NOINFO);
		}

		HTML_content::emailSent($email,$template);
	} else {
		mosNotAuth();
		return;
	}
}

function recordVote() {
	global $database;

	$user_rating = intval(mosGetParam($_REQUEST,'user_rating',0));
	$url = mosGetParam($_REQUEST,'url','');
	$cid = intval(mosGetParam($_REQUEST,'cid',0));

	if(($user_rating >= 1) and ($user_rating <= 5)) {
		$currip = (phpversion() <= '4.2.1'?@getenv('REMOTE_ADDR'):$_SERVER['REMOTE_ADDR']);

		$query = "SELECT*"."\n FROM #__content_rating"."\n WHERE content_id = ".(int)$cid;
		$database->setQuery($query);
		$votesdb = null;
		if(!($database->loadObject($votesdb))) {
			$query = "INSERT INTO #__content_rating ( content_id, lastip, rating_sum, rating_count )".
				"\n VALUES ( ".(int)$cid.", ".$database->Quote($currip).", ".(int)$user_rating.
				", 1 )";
			$database->setQuery($query);
			$database->query() or die($database->stderr());
			;
		} else {
			if($currip != ($votesdb->lastip)) {
				$query = "UPDATE #__content_rating"."\n SET rating_count = rating_count + 1, rating_sum = rating_sum + ".(int)
					$user_rating.", lastip = ".$database->Quote($currip)."\n WHERE content_id = ".(int)
					$cid;
				$database->setQuery($query);
				$database->query() or die($database->stderr());
			} else {
				mosRedirect($url,_ALREADY_VOTE);
			}
		}
		mosRedirect($url,_THANKS);
	}
}


function _orderby_pri($orderby) {
	switch($orderby) {
		case 'alpha':
			$orderby = 'cc.title, ';
			break;

		case 'ralpha':
			$orderby = 'cc.title DESC, ';
			break;

		case 'order':
			$orderby = 'cc.ordering, ';
			break;

		default:
			$orderby = '';
			break;
	}

	return $orderby;
}


function _orderby_sec($orderby) {
	switch($orderby) {
		case 'date':
			$orderby = 'a.created';
			break;

		case 'rdate':
			$orderby = 'a.created DESC';
			break;

		case 'alpha':
			$orderby = 'a.title';
			break;

		case 'ralpha':
			$orderby = 'a.title DESC';
			break;

		case 'hits':
			$orderby = 'a.hits';
			break;

		case 'rhits':
			$orderby = 'a.hits DESC';
			break;

		case 'order':
			$orderby = 'a.ordering';
			break;

		case 'author':
			$orderby = 'a.created_by_alias, u.name';
			break;

		case 'rauthor':
			$orderby = 'a.created_by_alias DESC, u.name DESC';
			break;

		case 'section':
			$orderby = 's.name, c.name, a.created DESC';
			break;

		case 'rsection':
			$orderby = 's.name DESC, c.name DESC, a.created DESC';
			break;

		case 'front':
			$orderby = 'f.ordering';
			break;

		default:
			$orderby = 'a.ordering';
			break;
	}

	return $orderby;
}

/*
* @param int 0 = Archives, 1 = Section, 2 = Category
*/
function _where($type = 1,&$access,&$noauth,$gid,$id,$now = null,$year = null,$month = null,
	$params = null) {
	global $database,$mainframe,$mosConfig_disable_date_state,$mosConfig_disable_access_control;

	$noauth = !$mainframe->getCfg('shownoauth');
	$nullDate = $database->getNullDate();
	$now = _CURRENT_SERVER_TIME;
	$where = array();
	$unpublished = 0;

	if(isset($params)) {
		// param controls whether unpublished items visible to publishers and above
		$unpublished = $params->def('unpublished',0);
	}

	// normal
	if($type > 0) {
		if(isset($params) && $unpublished) {
			// shows unpublished items for publishers and above
			if($access->canEdit) {
				$where[] = "a.state >= 0";
			} else {
				$where[] = "a.state = 1";
				if(!$mosConfig_disable_date_state) {
					$where[] = "( a.publish_up = ".$database->Quote($nullDate)." OR a.publish_up <= ".$database->Quote($now)." )";
					$where[] = "( a.publish_down = ".$database->Quote($nullDate)." OR a.publish_down >= ".$database->Quote($now)." )";
				}
			}
		} else {
			// unpublished items NOT shown for publishers and above
			$where[] = "a.state = 1";
			if(!$mosConfig_disable_date_state) {
				$where[] = "( a.publish_up = ".$database->Quote($nullDate)." OR a.publish_up <= ".$database->Quote($now)." )";
				$where[] = "( a.publish_down = ".$database->Quote($nullDate)." OR a.publish_down >= ".$database->Quote($now)." )";
			}
		}

		// add query checks for category or section ids
		if($id > 0) {
			$ids = explode(',',$id);
			mosArrayToInts($ids);
			if($type == 1) {
				$where[] = '( a.sectionid='.implode(' OR a.sectionid=',$ids).' )';
			} else
				if($type == 2) {
					$where[] = '( a.catid='.implode(' OR a.catid=',$ids).' )';
				}
		}
	}

	// archive
	if($type < 0) {
		$where[] = "a.state = -1";
		if($year) {
			$where[] = "YEAR( a.created ) = ".$database->Quote($year);
		}
		if($month) {
			$where[] = "MONTH( a.created ) = ".$database->Quote($month);
		}
		if($id > 0) {
			if($type == -1) {
				$where[] = "a.sectionid = ".(int)$id;
			} else
				if($type == -2) {
					$where[] = "a.catid = ".(int)$id;
				}
		}
	}

	$where[] = "s.published = 1";
	$where[] = "cc.published = 1";
	/* если сессии на фронте отключены - то значит авторизация не возможна, и проверять доступ по авторизации бесполезно*/
	if($noauth and !$mosConfig_disable_access_control) {
		$where[] = "a.access <= ".(int)$gid;
		$where[] = "s.access <= ".(int)$gid;
		$where[] = "cc.access <= ".(int)$gid;
	}

	return $where;
}

function votingQuery($active = null) {
	global $mainframe;

	$voting = ($active?$active:$mainframe->getCfg('vote'));

	if($voting) {
		// calculate voting count
		$select = "\n , ROUND( v.rating_sum / v.rating_count ) AS rating, v.rating_count";
		$join = "\n LEFT JOIN #__content_rating AS v ON a.id = v.content_id";
	} else {
		$select = '';
		$join = '';
	}

	$results = array('select' => $select,'join' => $join);

	return $results;
}
?>
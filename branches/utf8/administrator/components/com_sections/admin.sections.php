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

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );

require_once( $mainframe->getPath( 'admin_html' ) );

define( 'COM_IMAGE_BASE', $mosConfig_absolute_path . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'stories' );

// get parameters from the URL or submitted form
$scope 		= stripslashes( mosGetParam( $_REQUEST, 'scope', '' ) );
$section 	= stripslashes( mosGetParam( $_REQUEST, 'scope', '' ) );

$cid 		= josGetArrayInts( 'cid' );


switch ($task) {
	case 'new':
		editSection( 0, $scope, $option );
		break;

	case 'edit':
		editSection( intval( $cid[0] ), '', $option );
		break;

	case 'editA':
		editSection( $id, '', $option );
		break;

	case 'go2menu':
	case 'go2menuitem':
	case 'menulink':
	case 'save':
	case 'apply':
	/* boston, добавил параметр 'save_and_new' - признак возврата в редактирование разделов для добавления нового */
	case 'save_and_new':
		//boston, чистим кэш меню панели управления
		js_menu_cache_clear();
		saveSection( $option, $scope, $task );
		break;

	case 'remove':
		//boston, чистим кэш меню панели управления
		js_menu_cache_clear();
		removeSections( $cid, $scope, $option );
		break;

	case 'copyselect':
		//boston, чистим кэш меню панели управления
		js_menu_cache_clear();
		copySectionSelect( $option, $cid, $section );
		break;

	case 'copysave':
		//boston, чистим кэш меню панели управления
		js_menu_cache_clear();
		copySectionSave( $cid );
		break;

	case 'publish':
		//boston, чистим кэш меню панели управления
		js_menu_cache_clear();
		publishSections( $scope, $cid, 1, $option );
		break;

	case 'unpublish':
		//boston, чистим кэш меню панели управления
		js_menu_cache_clear();
		publishSections( $scope, $cid, 0, $option );
		break;

	case 'cancel':
		cancelSection( $option, $scope );
		break;

	case 'orderup':
		orderSection( intval( $cid[0] ), -1, $option, $scope );
		break;

	case 'orderdown':
		orderSection( intval( $cid[0] ), 1, $option, $scope );
		break;

	case 'accesspublic':
		accessMenu( intval( $cid[0] ), 0, $option );
		break;

	case 'accessregistered':
		accessMenu( intval( $cid[0] ), 1, $option );
		break;

	case 'accessspecial':
		accessMenu( intval( $cid[0] ), 2, $option );
		break;

	case 'saveorder':
		saveOrder( $cid );
		break;

	default:
		showSections( $scope, $option );
		break;
}

/**
* Compiles a list of categories for a section
* @param database A database connector object
* @param string The name of the category section
* @param string The name of the current user
*/
function showSections( $scope, $option ) {
	global $database, $my, $mainframe, $mosConfig_list_limit;

	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );

	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__sections"
	. "\n WHERE scope = " . $database->Quote( $scope )
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	$query = "SELECT c.*, g.name AS groupname, u.name AS editor"
	. "\n FROM #__sections AS c"
	. "\n LEFT JOIN #__content AS cc ON c.id = cc.sectionid"
	. "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
	. "\n LEFT JOIN #__groups AS g ON g.id = c.access"
	. "\n WHERE scope = " . $database->Quote( $scope )
	. "\n GROUP BY c.id"
	. "\n ORDER BY c.ordering, c.name"
	;
	$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	$count = count( $rows );
	// number of Active Items
	for ( $i = 0; $i < $count; $i++ ) {
		$query = "SELECT COUNT( a.id )"
		. "\n FROM #__categories AS a"
		. "\n WHERE a.section = '" . (int) $rows[$i]->id . "'"
		. "\n AND a.published != -2"
		;
		$database->setQuery( $query );
		$active = $database->loadResult();
		$rows[$i]->categories = $active;
	}
	// number of Active Items
	for ( $i = 0; $i < $count; $i++ ) {
		$query = "SELECT COUNT( a.id )"
		. "\n FROM #__content AS a"
		. "\n WHERE a.sectionid = " . (int) $rows[$i]->id
		. "\n AND a.state != -2"
		;
		$database->setQuery( $query );
		$active = $database->loadResult();
		$rows[$i]->active = $active;
	}
	// number of Trashed Items
	for ( $i = 0; $i < $count; $i++ ) {
		$query = "SELECT COUNT( a.id )"
		. "\n FROM #__content AS a"
		. "\n WHERE a.sectionid = " . (int) $rows[$i]->id
		. "\n AND a.state = -2"
		;
		$database->setQuery( $query );
		$trash = $database->loadResult();
		$rows[$i]->trash = $trash;
	}

	sections_html::show( $rows, $scope, $my->id, $pageNav, $option );
}

/**
* Compiles information to add or edit a section
* @param database A database connector object
* @param string The name of the category section
* @param integer The unique id of the category to edit (0 if new)
* @param string The name of the current user
*/
function editSection( $uid=0, $scope='', $option ) {
	global $database, $my, $mainframe;

	$row = new mosSection( $database );
	// load the row from the db table
	$row->load( (int)$uid );

	// fail if checked out not by 'me'
	if ($row->isCheckedOut( $my->id )) {
		$msg = 'Раздел -'. $row->title .'- в настоящее время редактируется другим администратором';
		mosRedirect( 'index2.php?option='. $option .'&scope='. $row->scope .'&mosmsg='. $msg );
	}

	$selected_folders = NULL;
	if ( $uid ) {
		$row->checkout( $my->id );
		if ( $row->id > 0 ) {
			$query = "SELECT *"
			. "\n FROM #__menu"
			. "\n WHERE componentid = " . (int) $row->id
			. "\n AND ( type = 'content_archive_section' OR type = 'content_blog_section' OR type = 'content_section' )"
			;
			$database->setQuery( $query );
			$menus = $database->loadObjectList();
			$count = count( $menus );
			for( $i = 0; $i < $count; $i++ ) {
				switch ( $menus[$i]->type ) {
					case 'content_section':
						$menus[$i]->type = 'Таблица раздела';
						break;

					case 'content_blog_section':
						$menus[$i]->type = 'Блог раздела';
						break;

					case 'content_archive_section':
						$menus[$i]->type = 'Блог архива раздела';
						break;
				}
			}
		} else {
			$menus = array();
		}
		
		// handling for MOSImage directories
		if ( trim( $row->params ) ) {
			// get params definitions
			$params = new mosParameters( $row->params, $mainframe->getPath( 'com_xml', 'com_sections' ), 'component' );
			$temps 	= $params->get( 'imagefolders', '' );
			
			$temps 	= explode( ',', $temps );
			foreach( $temps as $temp ) {
				$selected_folders[] = mosHTML::makeOption( $temp, $temp );
			}
		} else {
			$selected_folders[] = mosHTML::makeOption( '*1*' );
		}
	} else {
		$row->scope 		= $scope;
		$row->published 	= 1;
		$menus 			= array();
		
		// handling for MOSImage directories
		$selected_folders[]	= mosHTML::makeOption( '*1*' );
	}

	// build the html select list for section types
	$types[] = mosHTML::makeOption( '', 'Тип раздела' );
	$types[] = mosHTML::makeOption( 'content_section', 'Список раздела' );
	$types[] = mosHTML::makeOption( 'content_blog_section', 'Блог раздела' );
	$types[] = mosHTML::makeOption( 'content_archive_section', 'Блог архива раздела' );
	$lists['link_type'] 		= mosHTML::selectList( $types, 'link_type', 'class="inputbox" size="1"', 'value', 'text' );;

	// build the html select list for ordering
	$query = "SELECT ordering AS value, title AS text"
	. "\n FROM #__sections"
	. "\n WHERE scope=" . $database->Quote( $row->scope ) . " ORDER BY ordering"
	;
	$lists['ordering'] 			= mosAdminMenus::SpecificOrdering( $row, $uid, $query );

	// build the select list for the image positions
	$active =  ( $row->image_position ? $row->image_position : 'left' );
	$lists['image_position'] 	= mosAdminMenus::Positions( 'image_position', $active, NULL, 0 );
	// build the html select list for images
	$lists['image'] 			= mosAdminMenus::Images( 'image', $row->image );
	// build the html select list for the group access
	$lists['access'] 			= mosAdminMenus::Access( $row );
	// build the html radio buttons for published
	$lists['published'] 		= mosHTML::yesnoRadioList( 'published', 'class="inputbox"', $row->published );
	// build the html select list for menu selection
	$lists['menuselect']		= mosAdminMenus::MenuSelect( );

	// list of folders in images/stories/
	$imgFiles 	= recursive_listdir( COM_IMAGE_BASE );
	$len 		= strlen( COM_IMAGE_BASE );
	
	// handling for MOSImage directories
	$folders[] 	= mosHTML::makeOption( '*1*', 'Все'  );
	$folders[] 	= mosHTML::makeOption( '*0*', 'Отсутствуют' );
	$folders[] 	= mosHTML::makeOption( '*#*', '---------------------' );
	$folders[] 	= mosHTML::makeOption( '/' );
	foreach ($imgFiles as $file) {
		$folders[] = mosHTML::makeOption( substr( $file, $len ) );
	}
	
	$lists['folders'] = mosHTML::selectList( $folders, 'folders[]', 'class="inputbox" size="17" multiple="multiple"', 'value', 'text', $selected_folders );
	
	sections_html::edit( $row, $option, $lists, $menus );
}

/**
* Saves the catefory after an edit form submit
* @param database A database connector object
* @param string The name of the category section
*/
function saveSection( $option, $scope, $task ) {
	global $database;

	$menu 		= stripslashes( strval( mosGetParam( $_POST, 'menu', 'mainmenu' ) ) );
	$menuid		= intval( mosGetParam( $_POST, 'menuid', 0 ) );
	$oldtitle 	= stripslashes( strval( mosGetParam( $_POST, 'oldtitle', null ) ) );

	$row = new mosSection( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); document.location.href='index2.php?option=$option&scope=$scope&task=new'; </script>\n";
		exit();
	}
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); document.location.href='index2.php?option=$option&scope=$scope&task=new'; </script>\n";
		exit();
	}
	if ( $oldtitle ) {
		if ( $oldtitle != $row->title ) {
			$query = "UPDATE #__menu"
			. "\n SET name = " . $database->Quote( $row->title )
			. "\n WHERE name = " . $database->Quote( $oldtitle )
			. "\n AND type = 'content_section'"
			;
			$database->setQuery( $query );
			$database->query();
		}
	}

	// handling for MOSImage directories
	$folders 		= mosGetParam( $_POST, 'folders', array() );
	$folders 		= implode( ',', $folders );	
	if ( strpos( $folders, '*1*' ) !== false  ) {
		$folders 	= '*1*';
	} else if ( strpos( $folders, '*0*' ) !== false ) {
		$folders	= '*0*';
	} else if ( strpos( $folders, ',*#*' ) !== false ) {
		$folders 	= str_replace( ',*#*', '', $folders );
	} else if ( strpos( $folders, '*#*,' ) !== false ) {
		$folders 	= str_replace( '*#*,', '', $folders );
	} else if ( strpos( $folders, '*#*' ) !== false ) {
		$folders 	= str_replace( '*#*', '', $folders );
	} 	
	$row->params	= 'imagefolders='. $folders;
	
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	$row->updateOrder( 'scope=' . $database->Quote( $row->scope ) );

	// clean any existing cache files
	mosCache::cleanCache( 'com_content' );

	switch ( $task ) {
		case 'go2menu':
			mosRedirect( 'index2.php?option=com_menus&menutype='. $menu );
			break;

		case 'go2menuitem':
			mosRedirect( 'index2.php?option=com_menus&menutype='. $menu .'&task=edit&hidemainmenu=1&id='. $menuid );
			break;

		case 'menulink':
			menuLink( $row->id );
			break;

		case 'apply':
			$msg = 'Изменения раздела сохранены';
			mosRedirect( 'index2.php?option='. $option .'&scope='. $scope .'&task=editA&hidemainmenu=1&id='. $row->id, $msg );
			break;

		/* boston, после сохранения возвращаемся в окно добавления нового раздела */
		case 'save_and_new':
			$msg = $row->title.' - сохранено.';
			mosRedirect( 'index2.php?option='. $option .'&scope='. $scope.'&task=new', $msg );
			break;

		case 'save':
		default:
			$msg = 'Раздел сохранен';
			mosRedirect( 'index2.php?option='. $option .'&scope='. $scope, $msg );
			break;
	}
}
/**
* Deletes one or more categories from the categories table
* @param database A database connector object
* @param string The name of the category section
* @param array An array of unique category id numbers
*/
function removeSections( $cid, $scope, $option ) {
	global $database;

	if (count( $cid ) < 1) {
		echo "<script> alert('Выберите раздел для удаления'); window.history.go(-1);</script>\n";
		exit;
	}

	mosArrayToInts( $cid );
	$cids = 's.id=' . implode( ' OR s.id=', $cid );

	$query = "SELECT s.id, s.name, COUNT(c.id) AS numcat"
	. "\n FROM #__sections AS s"
	. "\n LEFT JOIN #__categories AS c ON c.section=s.id"
	. "\n WHERE ( $cids )"
	. "\n GROUP BY s.id"
	;
	$database->setQuery( $query );
	if (!($rows = $database->loadObjectList())) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}

	$err = array();
	$cid = array();
	foreach ($rows as $row) {
		if ($row->numcat == 0) {
			$cid[] = $row->id;
			$name[] = $row->name;
		} else {
			$err[] = $row->name;
		}
	}

	if (count( $cid )) {
		mosArrayToInts( $cid );
		$cids = 'id=' . implode( ' OR id=', $cid );
		$query = "DELETE FROM #__sections"
		. "\n WHERE ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}

	if (count( $err )) {
		$cids = implode( ', ', $err );
		$msg = 'Разделы: '. $cids .' не могут быть удалены, т.к. содержат категории';
		mosRedirect( 'index2.php?option='. $option .'&scope='. $scope, $msg );
	}
	
	// clean any existing cache files
	mosCache::cleanCache( 'com_content' );

	$names = implode( ', ', $name );
	$msg = 'Раздел(ы): '. $names .' успешно удален(ы)';
	mosRedirect( 'index2.php?option='. $option .'&scope='. $scope, $msg );
}

/**
* Publishes or Unpublishes one or more categories
* @param database A database connector object
* @param string The name of the category section
* @param integer A unique category id (passed from an edit form)
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The name of the current user
*/
function publishSections( $scope, $cid=null, $publish=1, $option ) {
	global $database, $my;

	if ( !is_array( $cid ) || count( $cid ) < 1 ) {
		$action = $publish ? 'публикации' : 'отмены публикации';
		echo "<script> alert('Выберите раздел для $action'); window.history.go(-1);</script>\n";
		exit;
	}

	$count = count( $cid );
	if ( $publish ) {
		if ( !$count ){
			echo "<script> alert('Невозможно опубликовать пустой раздел $count'); window.history.go(-1);</script>\n";
			return;
		}
	}

	mosArrayToInts( $cid );
	$cids = 'id=' . implode( ' OR id=', $cid );

	$query = "UPDATE #__sections"
	. "\n SET published = " . (int) $publish
	. "\n WHERE ( $cids )"
	. "\n AND ( checked_out = 0 OR ( checked_out = " . (int) $my->id  . " ) )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if ( $count == 1 ) {
		$row = new mosSection( $database );
		$row->checkin( $cid[0] );
	}

	// check if section linked to menu items if unpublishing
	if ( $publish == 0 ) {
		mosArrayToInts( $cid );
		$cids = 'componentid=' . implode( ' OR componentid=', $cid );
		$query = "SELECT id"
		. "\n FROM #__menu"
		. "\n WHERE type = 'content_section'"
		. "\n AND ( $cids )"
		;
		$database->setQuery( $query );
		$menus = $database->loadObjectList();

		if ($menus) {
			foreach ($menus as $menu) {
				$query = "UPDATE #__menu"
				. "\n SET published = " . (int) $publish
				. "\n WHERE id = " . (int) $menu->id
				;
				$database->setQuery( $query );
				$database->query();
			}
		}
	}

	// clean any existing cache files
	mosCache::cleanCache( 'com_content' );

	mosRedirect( 'index2.php?option='. $option .'&scope='. $scope );
}

/**
* Cancels an edit operation
* @param database A database connector object
* @param string The name of the category section
* @param integer A unique category id
*/
function cancelSection( $option, $scope ) {
	global $database;
	$row = new mosSection( $database );
	$row->bind( $_POST );
	$row->checkin();

	mosRedirect( 'index2.php?option='. $option .'&scope='. $scope );
}

/**
* Moves the order of a record
* @param integer The increment to reorder by
*/
function orderSection( $uid, $inc, $option, $scope ) {
	global $database;

	$row = new mosSection( $database );
	$row->load( (int)$uid );
	$row->move( $inc, "scope = " . $database->Quote( $row->scope ) );

	// clean any existing cache files
	mosCache::cleanCache( 'com_content' );

	mosRedirect( 'index2.php?option='. $option .'&scope='. $scope );
}


/**
* Form for copying item(s) to a specific menu
*/
function copySectionSelect( $option, $cid, $section ) {
	global $database;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		echo "<script> alert('Выберите объект для перемещения'); window.history.go(-1);</script>\n";
		exit;
	}

	## query to list selected categories
	mosArrayToInts( $cid );
	$cids = 'a.section=' . implode( ' OR a.section=', $cid );
	$query = "SELECT a.name, a.id"
	. "\n FROM #__categories AS a"
	. "\n WHERE ( $cids )"
	;
	$database->setQuery( $query );
	$categories = $database->loadObjectList();

	## query to list items from categories
	//mosArrayToInts( $cid ); // Just done a few lines earlier
	$cids = 'a.sectionid=' . implode( ' OR a.sectionid=', $cid );
	$query = "SELECT a.title, a.id"
	. "\n FROM #__content AS a"
	. "\n WHERE ( $cids )"
	. "\n ORDER BY a.sectionid, a.catid, a.title"
	;
	$database->setQuery( $query );
	$contents = $database->loadObjectList();

	sections_html::copySectionSelect( $option, $cid, $categories, $contents, $section );
}


/**
* Save the item(s) to the menu selected
*/
function copySectionSave( $sectionid ) {
	global $database;

	$title 		= stripslashes( strval( mosGetParam( $_REQUEST, 'title', '' ) ) );
	$categories = josGetArrayInts( 'category', $_REQUEST, array(0) );
	$items 		= josGetArrayInts( 'content', $_REQUEST, array(0) );

	// create new section

	$section = new mosSection ( $database );
	$section->id = null;
		$section->title = $title;
		$section->name 	= $title;
	$section->scope = 'content';
	$section->published = 1;
		if ( !$section->check() ) {
			echo "<script> alert('".$section->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		if ( !$section->store() ) {
			echo "<script> alert('".$section->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$section->checkin();
	$newSectionId = $section->id;


	// new section created, now copy categories

	// old/new category lookup array
	$newOldCatLookup = array();

	foreach( $categories as $categoryId ) {
	$category = new mosCategory ( $database );
		$category->load( $categoryId );
		$category->id = null;
		$category->section = $newSectionId;

		if (!$category->check()) {
			echo "<script> alert('".$category->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		if (!$category->store()) {
			echo "<script> alert('".$category->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$category->checkin();
		$newOldCatLookup[$categoryId] = $category->id;
	}

	// categories copied, now copy content items

	foreach( $items as $itemId ) {
		$item = new mosContent( $database );
		$item->load( $itemId );

		$item->id = null;
		$item->catid = $newOldCatLookup[$item->catid];
		$item->sectionid = $newSectionId;
		if (!$item->check()) {
			echo "<script> alert('".$item->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		if (!$item->store()) {
			echo "<script> alert('".$item->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$item->checkin();
	}

	$msg = 'Выбранное содержимое раздела было скопировано в раздел '. $title;
	mosRedirect( 'index2.php?option=com_sections&scope=content&mosmsg='. $msg );
}

/**
* changes the access level of a record
* @param integer The increment to reorder by
*/
function accessMenu( $uid, $access, $option ) {
	global $database;

	$row = new mosSection( $database );
	$row->load( (int)$uid );
	$row->access = $access;

	if ( !$row->check() ) {
		return $row->getError();
	}
	if ( !$row->store() ) {
		return $row->getError();
	}

	// clean any existing cache files
	mosCache::cleanCache( 'com_content' );

	mosRedirect( 'index2.php?option='. $option .'&scope='. $row->scope );
}

function menuLink( $id ) {
	global $database;

	$section = new mosSection( $database );
	$section->bind( $_POST );
	$section->checkin();

	$menu 	= strval( mosGetParam( $_POST, 'menuselect', '' ) );
	$name 	= strval( mosGetParam( $_POST, 'link_name', '' ) );
	$type 	= strval( mosGetParam( $_POST, 'link_type', '' ) );

	$name	= stripslashes( ampReplace($name) );

	switch ( $type ) {
		case 'content_section':
			$link 		= 'index.php?option=com_content&task=section&id='. $id;
			$menutype	= 'Таблица раздела';
			break;

		case 'content_blog_section':
			$link 		= 'index.php?option=com_content&task=blogsection&id='. $id;
			$menutype	= 'Блог раздела';
			break;

		case 'content_archive_section':
			$link 		= 'index.php?option=com_content&task=archivesection&id='. $id;
			$menutype	= 'Блог архива раздела';
			break;
	}

	$row 				= new mosMenu( $database );
	$row->menutype 		= $menu;
	$row->name 			= $name;
	$row->type 			= $type;
	$row->published		= 1;
	$row->componentid	= $id;
	$row->link			= $link;
	$row->ordering		= 9999;

	if ( $type == 'content_blog_section' ) {
		$row->params = 'sectionid='. $id;
	}
	
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	$row->updateOrder( "menutype = " . $database->Quote( $menu ) );
	
	// clean any existing cache files
	mosCache::cleanCache( 'com_content' );

	$msg = $name .' ( '. $menutype .' ) в меню: '. $menu .' успешно создано';
	mosRedirect( 'index2.php?option=com_sections&scope=content&task=editA&hidemainmenu=1&id='. $id,  $msg );
}

function saveOrder( &$cid ) {
	global $database;

	$total		= count( $cid );
	$order 		= josGetArrayInts( 'order' );
	
	$row 		= new mosSection( $database );
	$conditions = array();

	// update ordering values
	for( $i=0; $i < $total; $i++ ) {
		$row->load( (int) $cid[$i] );
		if ($row->ordering != $order[$i]) {
			$row->ordering = $order[$i];
			if (!$row->store()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			} // if
			// remember to updateOrder this group
			$condition = "scope = " . $database->Quote( $row->scope );
			$found = false;
			foreach ( $conditions as $cond )
				if ($cond[1]==$condition) {
					$found = true;
					break;
				} // if
			if (!$found) $conditions[] = array($row->id, $condition);
		} // if
	} // for

	// execute updateOrder for each group
	foreach ( $conditions as $cond ) {
		$row->load( $cond[0] );
		$row->updateOrder( $cond[1] );
	} // foreach
		 
 	// clean any existing cache files
	mosCache::cleanCache( 'com_content' );

	$msg 	= 'Новый порядок сохранен';
	mosRedirect( 'index2.php?option=com_sections&scope=content', $msg );
} // saveOrder

function recursive_listdir( $base ) {
	static $filelist = array();
	static $dirlist = array();
	
	if(is_dir($base)) {
		$dh = opendir($base);
		while (false !== ($dir = readdir($dh))) {
			if ($dir !== '.' && $dir !== '..' && is_dir($base .'/'. $dir) && strtolower($dir) !== 'cvs' && strtolower($dir) !== '.svn') {
				$subbase = $base .'/'. $dir;
				$dirlist[] = $subbase;
				$subdirlist = recursive_listdir($subbase);
			}
		}
		closedir($dh);
	}
	return $dirlist;
}
?>

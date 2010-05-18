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

// ensure user has access to this function
Jacl::isDeny('mambots','view') ? mosRedirect('index2.php?', _NOT_AUTH) : null;

require_once ($mainframe->getPath('admin_html'));

$client = strval(mosGetParam($_REQUEST,'client',''));

$cid = josGetArrayInts('cid');

switch($task) {

	case 'new':
	case 'edit':
		Jacl::isDeny('mambots','edit') ? mosRedirect('index2.php?', _NOT_AUTH) : null;
		editMambot($option,intval($cid[0]),$client);
		break;

	case 'editA':
		Jacl::isDeny('mambots','edit') ? mosRedirect('index2.php?', _NOT_AUTH) : null;
		editMambot($option,$id,$client);
		break;

	case 'save':
	case 'apply':
		Jacl::isDeny('mambots','edit') ? mosRedirect('index2.php?', _NOT_AUTH) : null;
		saveMambot($option,$client,$task);
		break;

	case 'remove':
		Jacl::isDeny('mambots','edit') ? mosRedirect('index2.php?', _NOT_AUTH) : null;
		removeMambot($cid,$option,$client);
		break;

	case 'cancel':
		cancelMambot($option,$client);
		break;

	case 'orderup':
	case 'orderdown':
		Jacl::isDeny('mambots','edit') ? mosRedirect('index2.php?', _NOT_AUTH) : null;
		orderMambot(intval($cid[0]),($task == 'orderup'?-1:1),$option,$client);
		break;

	case 'accesspublic':
	case 'accessregistered':
	case 'accessspecial':
		Jacl::isDeny('mambots','edit') ? mosRedirect('index2.php?', _NOT_AUTH) : null;
		accessMenu(intval($cid[0]),$task,$option,$client);
		break;

	case 'saveorder':
		Jacl::isDeny('mambots','edit') ? mosRedirect('index2.php?', _NOT_AUTH) : null;
		saveOrder($cid);
		break;

	default:
		viewMambots($option,$client);
		break;
}

function viewMambots($option,$client) {

	$mainframe = mosMainFrame::getInstance();
	$database = $mainframe->getDBO();

	$limit = intval($mainframe->getUserStateFromRequest("viewlistlimit",'limit',$mainframe->getCfg('config_list_limit')));
	$limitstart = intval($mainframe->getUserStateFromRequest("view{$option}limitstart",'limitstart',0));
	$filter_type = $mainframe->getUserStateFromRequest("filter_type{$option}{$client}",'filter_type',1);
	$search = $mainframe->getUserStateFromRequest("search{$option}{$client}",'search','');

	if(get_magic_quotes_gpc()) {
		$search = stripslashes($search);
	}

	if($client == 'admin') {
		$where[] = "m.client_id = '1'";
		$client_id = 1;
	} else {
		$where[] = "m.client_id = '0'";
		$client_id = 0;
	}

	// used by filter
	if($filter_type != 1) {
		$where[] = "m.folder = ".$database->Quote($filter_type);
	}
	if($search) {
		$where[] = "LOWER( m.name ) LIKE '%".$database->getEscaped(Jstring::trim(Jstring::strtolower($search)))."%'";
	}

	// get the total number of records
	$query = "SELECT COUNT(*) FROM #__mambots AS m".(count($where)?"\n WHERE ".implode(' AND ',$where):'');
	$total = $database->setQuery($query)->loadResult();

	require_once (JPATH_BASE.DS.JADMIN_BASE.'/includes/pageNavigation.php');
	$pageNav = new mosPageNav($total,$limitstart,$limit);

	$query = "SELECT m.*, u.username AS editor, g.name AS groupname"
			."\n FROM #__mambots AS m"
			."\n LEFT JOIN #__users AS u ON u.id = m.checked_out"
			."\n LEFT JOIN #__groups AS g ON g.id = m.access"
			.(count($where)?"\n WHERE ".implode(' AND ',$where):'')
			."\n GROUP BY m.id"
			."\n ORDER BY m.folder ASC, m.ordering ASC, m.name ASC";
	$rows = $database->setQuery($query,$pageNav->limitstart,$pageNav->limit)->loadObjectList();

	if($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	$query = "SELECT folder AS value, folder AS text"
			."\n FROM #__mambots"
			."\n WHERE client_id = ".(int)$client_id
			."\n GROUP BY folder"
			."\n ORDER BY folder";
	$types[] = mosHTML::makeOption(1,_SEL_TYPE);
	$types = array_merge($types,$database->setQuery($query)->loadObjectList());

	$lists['type'] = mosHTML::selectList($types,'filter_type','class="inputbox" size="1" onchange="document.adminForm.submit( );"','value','text',$filter_type);

	HTML_modules::showMambots($rows,$client,$pageNav,$option,$lists,$search);
}

function saveMambot($option,$client,$task) {
	josSpoofCheck();

	$params = mosGetParam($_POST,'params','');
	// TODO тут бедас русским языком...
	mosMainFrame::addLib('json');
	$_POST['params'] = php2js($params);

	$database = database::getInstance();
	$row = new mosMambot($database);
	if(!$row->bind($_POST)) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if(!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if(!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if($client == 'admin') {
		$where = "client_id='1'";
	} else {
		$where = "client_id='0'";
	}
	$row->updateOrder("folder = ".$database->Quote($row->folder)." AND ordering > -10000 AND ordering < 10000 AND ( $where )");

	switch($task) {
		case 'apply':
			$msg = $row->name.'-- '._E_ITEM_SAVED;
			mosRedirect('index2.php?option='.$option.'&client='.$client.'&task=editA&hidemainmenu=1&id='.$row->id,$msg);

		case 'save':
		default:
			$msg = $row->name.'-- '._E_ITEM_SAVED;
			mosRedirect('index2.php?option='.$option.'&client='.$client,$msg);
			break;
	}
}

function editMambot($option,$uid,$client) {
	global $my;

	$database = database::getInstance();

	$lists = array();
	$row = new mosMambot($database);

	// load the row from the db table
	$row->load((int)$uid);

	// fail if checked out not by 'me'
	if($row->isCheckedOut($my->id)) {
		mosErrorAlert($row->title.' '._COM_MAMBOTS_NON_EDIT);
	}

	if($client == 'admin') {
		$where = "client_id='1'";
	} else {
		$where = "client_id='0'";
	}

	// get list of groups
	if($row->access == 99 || $row->client_id == 1) {
		$lists['access'] = 'Administrator<input type="hidden" name="access" value="99" />';
	} else {
		// build the html select list for the group access
		$lists['access'] = mosAdminMenus::Access($row);
	}

	if($uid) {

		if($row->ordering > -10000 && $row->ordering < 10000) {
			// build the html select list for ordering
			$query = "SELECT ordering AS value, name AS text"
					."\n FROM #__mambots"
					."\n WHERE folder = "
					.$database->Quote($row->folder)
					."\n AND published > 0"
					."\n AND $where"
					."\n AND ordering > -10000"
					."\n AND ordering < 10000"
					."\n ORDER BY ordering";
			$order = mosCommonHTML::mosGetOrderingList($query);
			$lists['ordering'] = mosHTML::selectList($order,'ordering','class="inputbox" size="1"','value','text',intval($row->ordering));
		} else {
			$lists['ordering'] = '<input type="hidden" name="ordering" value="'.$row->ordering.'" />'._COM_MAMBOTS_NON_REORDER;
		}
		$lists['folder'] = '<input type="hidden" name="folder" value="'.$row->folder.'" />'.$row->folder;

		// XML library
		require_once (JPATH_BASE.'/includes/domit/xml_domit_lite_include.php');
		// xml file for module
		$xmlfile = JPATH_BASE.DS.'mambots'.DS.$row->folder.DS.$row->element.'.xml';
		$xmlDoc = new DOMIT_Lite_Document();
		$xmlDoc->resolveErrors(true);
		if($xmlDoc->loadXML($xmlfile,false,true)) {
			$root = &$xmlDoc->documentElement;
			if($root->getTagName() == 'mosinstall' && $root->getAttribute('type') =='mambot') {
				$element = &$root->getElementsByPath('description',1);
				$row->description = $element ? trim($element->getText()):'';
			}
		}
	} else {
		$row->folder = '';
		$row->ordering = 999;
		$row->published = 1;
		$row->description = '';

		$folders = mosReadDirectory(JPATH_BASE.DS.'mambots'.DS);
		$folders2 = array();
		foreach($folders as $folder) {
			if(is_dir(JPATH_BASE.DS.'mambots'.DS.$folder) && ($folder != 'CVS')) {
				$folders2[] = mosHTML::makeOption($folder);
			}
		}
		$lists['folder'] = mosHTML::selectList($folders2,'folder','class="inputbox" size="1"','value','text',null);
		$lists['ordering'] = '<input type="hidden" name="ordering" value="'.$row->ordering.'" />'._NEW_MAMBOTS_IN_THE_END;
	}

	$lists['published'] = mosHTML::yesnoRadioList('published','class="inputbox"',$row->published);

	$path = JPATH_BASE.DS."mambots/$row->folder/$row->element.xml";
	if(!file_exists($path)) {
		$path = '';
	}

	// get params definitions
	$params = new mosParameters($row->params,$path,'mambot');
	HTML_modules::editMambot($row,$lists,$params,$option);
}

function removeMambot(&$cid,$option,$client) {
	global $database,$my;
	josSpoofCheck();
	if(count($cid) < 1) {
		echo "<script> alert('"._CHOOSE_OBJ_DELETE."'); window.history.go(-1);</script>\n";
		exit;
	}

	mosRedirect( 'index2.php?option=com_installer&element=mambot&client='. $client .'&task=remove&cid[]='. $cid[0] . '&' . josSpoofValue() . '=1');
}

function cancelMambot($option,$client) {
	josSpoofCheck();
	mosRedirect('index2.php?option='.$option.'&client='.$client);
}

function orderMambot($uid,$inc,$option,$client) {
	josSpoofCheck();

	if($client == 'admin') {
		$where = "client_id = 1";
	} else {
		$where = "client_id = 0";
	}

	$database = database::getInstance();

	$row = new mosMambot($database);
	$row->load((int)$uid);
	$row->move($inc,"folder=".$database->Quote($row->folder)." AND ordering > -10000 AND ordering < 10000 AND ($where)");

	mosRedirect('index2.php?option='.$option);
}

function accessMenu($uid,$access,$option,$client) {
	josSpoofCheck();

	switch($access) {
		case 'accesspublic':
			$access = 0;
			break;

		case 'accessregistered':
			$access = 1;
			break;

		case 'accessspecial':
			$access = 2;
			break;
	}

	$database = database::getInstance();
	$row = new mosMambot($database);
	$row->load((int)$uid);
	$row->access = $access;

	if(!$row->check()) {
		return $row->getError();
	}
	if(!$row->store()) {
		return $row->getError();
	}

	mosRedirect('index2.php?option='.$option);
}

function saveOrder(&$cid) {
	josSpoofCheck();

	$total = count($cid);
	$order = josGetArrayInts('order');

	$database = database::getInstance();
	$row = new mosMambot($database);
	$conditions = array();

	// update ordering values
	for($i = 0; $i < $total; $i++) {
		$row->load((int)$cid[$i]);
		if($row->ordering != $order[$i]) {
			$row->ordering = $order[$i];
			if(!$row->store()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			} // if
			// remember to updateOrder this group
			$condition = "folder = ".$database->Quote($row->folder)." AND ordering > -10000 AND ordering < 10000 AND client_id = ".(int)$row->client_id;
			$found = false;
			foreach($conditions as $cond)
				if($cond[1] == $condition) {
					$found = true;
					break;
				} // if
			if(!$found) $conditions[] = array($row->id,$condition);
		} // if
	} // for

	// execute updateOrder for each group
	foreach($conditions as $cond) {
		$row->load($cond[0]);
		$row->updateOrder($cond[1]);
	} // foreach

	$msg = _NEW_ORDER_SAVED;
	mosRedirect('index2.php?option=com_mambots',$msg);
}
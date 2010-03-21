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

switch($task) {
	case 'searches':
		showSearches($option,$task);
		break;

	case 'searchesresults':
		showSearches($option,$task,1);
		break;

	case 'pageimp':
		showPageImpressions($option,$task);
		break;

	default:
		showPageImpressions($option,$task);
		break;
}

function showPageImpressions($option,$task) {
	$database = database::getInstance();
	$mainframe = mosMainFrame::getInstance(true);

	$query = "SELECT COUNT( id ) FROM #__content";
	$database->setQuery($query);
	$total = $database->loadResult();

	$limit = $mainframe->getUserStateFromRequest("viewlistlimit",'limit',$mainframe->getCfg('list_limit'));
	$limitstart = $mainframe->getUserStateFromRequest("view{$option}{$task}limitstart",'limitstart',0);

	require_once (JPATH_BASE.'/'.JADMIN_BASE.'/includes/pageNavigation.php');
	$pageNav = new mosPageNav($total,$limitstart,$limit);

	$query = "SELECT id, title, created, hits FROM #__content ORDER BY hits DESC";
	$database->setQuery($query,$pageNav->limitstart,$pageNav->limit);

	$rows = $database->loadObjectList();

	HTML_statistics::pageImpressions($rows,$pageNav,$option,$task);
}

function showSearches($option,$task,$showResults = null) {
	global $_MAMBOTS;

	$database = database::getInstance();
	$mainframe = mosMainFrame::getInstance(true);

	$limit = $mainframe->getUserStateFromRequest("viewlistlimit",'limit',$mainframe->getCfg('list_limit'));
	$limitstart = $mainframe->getUserStateFromRequest("view{$option}{$task}limitstart",'limitstart',0);

	// get the total number of records
	$query = "SELECT COUNT(*) FROM #__core_log_searches";
	$database->setQuery($query);
	$total = $database->loadResult();

	require_once (JPATH_BASE.'/'.JADMIN_BASE.'/includes/pageNavigation.php');
	$pageNav = new mosPageNav($total,$limitstart,$limit);

	$query = "SELECT* FROM #__core_log_searches ORDER BY hits DESC";
	$database->setQuery($query,$pageNav->limitstart,$pageNav->limit);

	$rows = $database->loadObjectList();
	if($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	$_MAMBOTS->loadBotGroup('search');

	$total = count($rows);
	for($i = 0,$n = $total; $i < $n; $i++) {
		// determine if number of results for search item should be calculated
		// by default it is `off` as it is highly query intensive
		if($showResults) {
			$results = $_MAMBOTS->trigger('onSearch',array($rows[$i]->search_term));

			$count = 0;
			$total = count($results);
			for($j = 0,$n2 = $total; $j < $n2; $j++) {
				$count += count($results[$j]);
			}

			$rows[$i]->returns = $count;
		} else {
			$rows[$i]->returns = null;
		}
	}

	HTML_statistics::showSearches($rows,$pageNav,$option,$task,$showResults);
}
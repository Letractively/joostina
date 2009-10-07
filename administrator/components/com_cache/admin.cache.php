<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

/*
 * Make sure the user is authorized to view this page
 */

// ensure user has access to this function
if(!($acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'all') | $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_cache'))) {
	mosRedirect('index2.php', _NOT_AUTH);
}

// Load the html output class and the model class
require_once ($mainframe->getPath('admin_html'));
require_once ($mainframe->getPath('class'));

$cid = mosGetParam($_REQUEST,'cid',0);

/*
 * This is our main control structure for the component
 *
 * Each view is determined by the $task variable
 */
switch($task) 
{
	case 'delete':
		CacheController::deleteCache($cid);
		CacheController::showCache();
		break;
	//case 'purgeadmin':
	//	CacheController::showPurgeCache();
	//	break;
	//case 'purge':
	//	CacheController::purgeCache();
	//	break;
	default :
		CacheController::showCache();
		break;
}

/**
 * Static class to hold controller functions for the Cache component
 *
 * @static
 * @package		Joostina
 * @subpackage	Cache
 * @since		1.3
 */
class CacheController
{
	/**
	 * Show the cache
	 *
	 * @since	1.3
	 */
	function showCache()
	{
		
		global $mainframe, $option;
		$client = intval(mosGetParam($_REQUEST,'client',0));
		//if ($client == 1) {
			//JSubMenuHelper::addEntry(JText::_('Site'), 'index.php?option=com_cache&client=0');
			//JSubMenuHelper::addEntry(JText::_('Administrator'), 'index.php?option=com_cache&client=1', true);
		//} else {
			//JSubMenuHelper::addEntry(JText::_('Site'), 'index.php?option=com_cache&client=0', true);
			//JSubMenuHelper::addEntry(JText::_('Administrator'), 'index.php?option=com_cache&client=1');
		//}

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'));
		$limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0 );

		
		$cmData = new CacheData(JPATH_BASE . '/cache');
		
		//jimport('joomla.html.pagination');
		//$pageNav = new JPagination( $cmData->getGroupCount(), $limitstart, $limit );

		require_once ($GLOBALS['mosConfig_absolute_path'] . '/'.ADMINISTRATOR_DIRECTORY.'/includes/pageNavigation.php');
		$pageNav = new mosPageNav($cmData->getGroupCount(), $limitstart, $limit);
		//echo "sdsd " . $cmData->getGroupCount();
		//exit;
		CacheView::displayCache( $cmData->getRows( $limitstart, $limit ), $client, $pageNav );
		//echo "65465";
	}

	function deleteCache($cid)
	{
		// Check for request forgeries
		//JRequest::checkToken() or jexit( 'Invalid Token' );
		$client = intval(mosGetParam($_REQUEST,'client',0));

		$cmData = new CacheData($GLOBALS['mosConfig_absolute_path'] . '/cache');
		$cmData->cleanCacheList( $cid );
	}
	//function showPurgeCache()
	//{	
		// Check for request forgeries
	//	CacheView::showPurgeExecute();
	//}
	//function purgeCache()
	//{	
		// Check for request forgeries
		//JRequest::checkToken() or jexit( 'Invalid Token' );
	//	$cache =& mosCache::getCache('');
	//	$cache->gc();
	//	CacheView::purgeSuccess();
	//}
}
?>
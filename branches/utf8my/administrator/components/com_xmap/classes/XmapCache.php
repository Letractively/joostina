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
require(dirname(__FILE__).'/../../../die.php');

/**
* Class to support function caching
* @package Xmap
*/
class XmapCache {
	/**
	* @return object A function cache object
	*/
	function &getCache( &$sitemap ) {
		global $mosConfig_absolute_path, $mosConfig_cachepath, $mosConfig_cachetime;

		if (class_exists('JFactory')) {
			$cache = &JFactory::getCache('com_xmap_'.$sitemap->id);
			$cache->setCaching($sitemap->usecache);
			$cache->setLifeTime($sitemap->cachelifetime);
		} else {
			require_once( $mosConfig_absolute_path . '/includes/joomla.cache.php' );
			$options = array (
				'cacheDir'		=> $mosConfig_cachepath . '/',
				'caching'		=> $sitemap->usecache,
				'defaultGroup'	=> 'com_xmap_'.$sitemap->id,
				'lifeTime'		=> $sitemap->cachelifetime
			);
			$cache = new JCache_Lite_Function( $options );
		}
		return $cache;
	}
	/**
	* Cleans the cache
	*/
	function cleanCache( &$sitemap ) {
		$cache =&XmapCache::getCache( $sitemap );
		if (class_exists('JFactory')) {
			return $cache->clean();
		} else {
			return $cache->clean( $cache->_cache->_defaultGroup );
		}
	}
}
?>

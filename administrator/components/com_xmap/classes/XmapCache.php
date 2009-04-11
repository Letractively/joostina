<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();


class XmapCache {
	/**
	* @return object A function cache object
	*/
	function &getCache( &$sitemap ) {
		global $mosConfig_absolute_path, $mosConfig_caching, $mosConfig_cachepath, $mosConfig_cachetime, $mosConfig_cache_handler, $mosConfig_db_cache_handler, $mosConfig_lang;

		$options = array(
			'defaultgroup' 	=> 'com_xmap_'.$sitemap->id,
			'cachebase' 	=> $mosConfig_cachepath.'/',
			'lifetime' 		=> $mosConfig_cachetime,	// minutes to seconds
			'language' 		=> $mosConfig_lang,
			'storage'		=> $storage
		);

		require_once ($mosConfig_absolute_path.'/includes/libraries/cache/cache.php');
		$cache =&JCache::getInstance( $handler, $options );
		$cache->setCaching($mosConfig_caching);
		return $cache;

	}
	/**
	* Cleans the cache
	*/
	function cleanCache( &$sitemap ) {
		$cache =&XmapCache::getCache( $sitemap );
		return $cache->clean( $cache->_group );
	}
}

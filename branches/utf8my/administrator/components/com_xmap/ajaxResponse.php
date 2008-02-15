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

$action = mosGetParam($_REQUEST ,'action','');

global $database,$mosConfig_absolute_path;

require_once( $mosConfig_absolute_path . '/administrator/components/com_xmap/classes/XmapCache.php' );

header ('Content-Type: text/xhtml; charset='. _XMAP_CHARSET);
header ("Cache-Control: no-cache, must-revalidate ");

switch ($action) {
	case 'add_sitemap':
		$sitemap = new XmapSitemap();
		$sitemap->save();
		XmapAdminHtml::showSitemapInfo($sitemap);
	break;
	case 'delete_sitemap':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		$config = new XmapConfig();
		$config->load();
		if (!$id || $id != mosGetParam($_REQUEST ,'sitemap','')) {
			die("Invalid Sitemap ID");
		}
		if ( $config->sitemap_default==$id) {
			echo _XMAP_ERROR_DELETE_DEFAULT;
			exit;
		}

		$sitemap = new XmapSitemap();
		$sitemap->load($id);
		if ($sitemap->remove()) {
			echo 1;
		} else {
			$database->getErrorMsg();
		}
	break;
	case 'copy_sitemap':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		if (!$id || $id != mosGetParam($_REQUEST ,'sitemap','')) {
			die("Неверный ID");
		}
		$sitemap = new XmapSitemap();
		if ( $sitemap->load($id) ) {
			$sitemap->id=NULL;
			$sitemap->name=sprintf(_XMAP_COPY_OF,$sitemap->name);
			$sitemap->save();
			XmapAdminHtml::showSitemapInfo($sitemap);
		}
	break;
	case 'save_property':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		$property = mosGetParam($_REQUEST ,'property','');
		$value = mosGetParam($_REQUEST ,'value','');
		$sitemap = new XmapSitemap();
		if ($sitemap->load($id) ) {
			if (isset($sitemap->$property)) {
				$sitemap->$property = $value;
				if ( $sitemap->save() ) {
					if ( $sitemap->save() ) {
						if ( $sitemap->usecache ) {
							XmapCache::cleanCache($sitemap);
						}
						echo 1;
					} else {
						$database->getErrorMsg();
					}
					exit;
				}
			}
		}
		echo _XMAP_MSG_ERROR_SAVE_PROPERTY;
		exit;
	break;
	case 'edit_sitemap_settings':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		if (!$id || $id != mosGetParam($_REQUEST ,'sitemap','')) {
			die("Неверный ID");
		}
		$sitemap = new XmapSitemap();
		if ($sitemap->load($id) ) {
			// images for 'external link' tagging
			$javascript = 'onchange="changeDisplayImage();"';
			$directory = '/components/com_xmap/images';
			$lists['imageurl'] = mosAdminMenus::Images( 'imageurl', $sitemap->ext_image, $javascript, $directory );

			// column count selection
			$columns = array (
				mosHTML::makeOption( 1, 1 ),
				mosHTML::makeOption( 2, 2 ),
				mosHTML::makeOption( 3, 3 ),
				mosHTML::makeOption( 4, 4 )
			);
			$lists['columns'] = mosHTML::selectList( $columns, 'columns', 'id="columns" class="inputbox" size="1"', 'value', 'text',  $sitemap->columns );

			// get list of menu entries in all menus
			$query = "SELECT id AS value, name AS text, CONCAT( id, ' - ', name ) AS menu"
			. "\n FROM #__menu"
			. "\n WHERE published != -2"
			. "\n ORDER BY menutype, parent, ordering"
;
			$database->setQuery( $query );
			$exclmenus = $database->loadObjectList();
			$lists['exclmenus'] = mosHTML::selectList( $exclmenus, 'excl_menus', 'class="inputbox" size="1"', 'value', 'menu', NULL );

			XmapAdminHtml::showSitemapSettings($sitemap,$lists);
		} else {
			echo _XMAP_MSG_ERROR_LOADING_SITEMAP;
		}
	break;
	case 'save_sitemap_settings':
		$id = intval (mosGetParam($_REQUEST ,'id',''));
		if (!$id || $id != mosGetParam($_REQUEST ,'id','')) {
			die("Неверный ID карты");
		}
		$sitemap = new XmapSitemap();
		if ( $sitemap->load($id) ) {
			$_POST['menus']=$sitemap->menus;
			$sitemap->bind($_POST);
			if ( $sitemap->save() ) {
				if ( $sitemap->usecache ) {
					XmapCache::cleanCache($sitemap);
				}
				echo 1;
			} else {
				echo $database->getErrorMsg();
			}
		} else {
			die("Неверный ID карты");
		}
	break;
	case 'set_default':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		if (!$id || $id != mosGetParam($_REQUEST ,'sitemap','')) {
			die("Неверный ID карты");
		}
		$config = new XmapConfig();
		# $config->load();
		$config->sitemap_default=$id;
		if ($config->save()) {
			echo '1';
		} else {
			echo $database->getErrorMsg();
		}
	break;
	case 'clean_cache_sitemap':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		if (!$id || $id != mosGetParam($_REQUEST ,'sitemap','')) {
			die("Неверный ID");
		}
		$sitemap = new XmapSitemap();
		if ($sitemap->load($id)) {
			if ( XmapCache::cleanCache($sitemap) )  {
				echo _XMAP_MSG_CACHE_CLEANED;
			} else {
				echo _XMAP_MSG_ERROR_CLEAN_CACHE;
			}
		
		} else {
			echo $database->getErrorMsg();
		}
	break;
	case 'add_menu_sitemap':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		if (!$id || $id != mosGetParam($_REQUEST ,'sitemap','')) {
			die("Неверный ID");
		}
		$sitemap = new XmapSitemap();
		if ( $sitemap->load($id) ) {
			$menus = $sitemap->getMenus();	
			$newMenus = mosGetParam($_REQUEST ,'menus',array());
			$ordering = count($menus);
			foreach ($newMenus as $aMenu) {
				if (empty($menus[$aMenu])) {
					$menu = new stdclass;
					$menu->show = 1;
					$menu->showXML = 1;
					$menu->ordering = $ordering++;
					$menu->priority = '0.5';
					$menu->changefreq = 'daily';
					$menus[$aMenu] = $menu;
				}
			}
			$sitemap->setMenus($menus);
			if ( $sitemap->save() && $sitemap->usecache) {
					XmapCache::cleanCache($sitemap);
			}
			XmapAdminHtml::printMenusList($sitemap);
		}
	break;
	case 'remove_menu_sitemap':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		if (!$id || $id != mosGetParam($_REQUEST ,'sitemap','')) {
			die("Неверный ID");
		}
		$sitemap = new XmapSitemap();
		if ( $sitemap->load($id) ) {
			$menus = $sitemap->getMenus();	
			$menu_delete = mosGetParam($_REQUEST ,'menu',array());
			$newMenus = array();
			foreach ($menus as $aMenu => $menu) {
				if ($aMenu != $menu_delete) {
					$newMenus[$aMenu] = $menu;
				}
			}
			$sitemap->setMenus($newMenus);
			if ( $sitemap->save() && $sitemap->usecache) {
					XmapCache::cleanCache($sitemap);
			}
			XmapAdminHtml::printMenusList($sitemap);
		}
	break;
	case 'move_menu_sitemap':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		if (!$id || $id != mosGetParam($_REQUEST ,'sitemap','')) {
			die("Неверный ID");
		}
		$sitemap = new XmapSitemap();
		if ( $sitemap->load($id) ) {
			$menu_move = mosGetParam($_REQUEST ,'menu',array());
			$move = intval(mosGetParam($_REQUEST ,'move',array()));
			$sitemap->orderMenu($menu_move,$move);
			if ( $sitemap->save() && $sitemap->usecache) {
					XmapCache::cleanCache($sitemap);
			}
			
			XmapAdminHtml::printMenusList($sitemap);
		}
	break;
	case 'get_menus_sitemap':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		if (!$id || $id != mosGetParam($_REQUEST ,'sitemap','')) {
			die("Неверный ID");
		}
		$sitemap = new XmapSitemap();
		if ( $sitemap->load($id) ) {
			XmapAdminHtml::printMenusList($sitemap);
		}
	break;
	case 'menu_options':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		$sitemap = new XmapSitemap();
		if ( !$sitemap->load($id) ) {
			die('Cannot load sitemap');
		}
		$menutype = mosGetParam($_REQUEST ,'menutype','');
		$menus = $sitemap->getMenus();
		$menu = $menus[$menutype];
		$changefreq = array();
		$changefreq[] = mosHTML::makeOption( 'always', _XMAP_CFG_CHANGEFREQ_ALWAYS, 'value', 'name' );
		$changefreq[] = mosHTML::makeOption( 'hourly', _XMAP_CFG_CHANGEFREQ_HOURLY, 'value', 'name' );
		$changefreq[] = mosHTML::makeOption( 'daily', _XMAP_CFG_CHANGEFREQ_DAILY, 'value', 'name' );
		$changefreq[] = mosHTML::makeOption( 'weekly', _XMAP_CFG_CHANGEFREQ_WEEKLY, 'value', 'name' );
		$changefreq[] = mosHTML::makeOption( 'monthly', _XMAP_CFG_CHANGEFREQ_MONTHLY, 'value', 'name' );
		$changefreq[] = mosHTML::makeOption( 'yearly', _XMAP_CFG_CHANGEFREQ_YEARLY, 'value', 'name' );
		$changefreq[] = mosHTML::makeOption( 'never', _XMAP_CFG_CHANGEFREQ_NEVER, 'value', 'name' );
		$lists['changefreq'] = mosHTML::selectList( $changefreq, 'changefreq', 'class="inputbox" size="1"', 'value', 'name', $menu->changefreq );
		$priority = array();
		for ($i=0;$i<=9;$i++) {
			$priority[] =  mosHTML::makeOption( '0.'.$i, '0.'.$i, 'value', 'name' );
		}
		$priority[] =  mosHTML::makeOption( '1', '1', 'value', 'name' );
		$lists['priority'] = mosHTML::selectList( $priority, 'priority', 'class="inputbox" size="1"', 'value', 'name', $menu->priority );
		XmapAdminHtml::showMenuOptions($sitemap,$menu,$lists);
	break;
	case 'save_menu_options':
		$id = intval (mosGetParam($_REQUEST ,'sitemap',''));
		$sitemap = new XmapSitemap();
		if ( !$sitemap->load($id) ) {
			die('Невозможно загрузить карту');
		}
		$menutype = mosGetParam($_REQUEST ,'menutype','');
		$menus = $sitemap->getMenus();
		if (!empty($menus[$menutype]) ) {
			$menu = &$menus[$menutype];
			$menu->show = mosGetParam($_POST,'show','');
			$menu->showXML = mosGetParam($_POST,'showXML','');
			$menu->priority = mosGetParam($_POST,'priority','');
			$menu->changefreq = mosGetParam($_POST,'changefreq','');

			# Clean the cache of the sitemap

			
			$sitemap->setMenus($menus);
			if ($sitemap->save()) {
				if ($sitemap->usecache) {
					XmapCache::cleanCache($sitemap);
				}
				echo 1;
			} else {
				echo $database->getErrorMsg();
			}
		}
	break;
}


?>

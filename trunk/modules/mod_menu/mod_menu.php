<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

$params = new mosParameters( $module->params );

$menutype = $params->get('menutype', 'mainmenu');

$all_menu_links = mosMenu::get_all( $menutype );

if( count($all_menu_links)>0 ) {
	foreach ($all_menu_links as $menu_link) {
		echo sprintf( '<a href="%s" title="%s">%s</a><br />', sefRelToAbs($menu_link->link),
				( isset($menu_link->link_title) ? $menu_link->link_title : $menu_link->name), $menu_link->name  );
	}
}
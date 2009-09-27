<?php #
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

$menutype = $params->get('menutype','mainmenu');

$menus = get_menu_links($menutype);

$config = &Jconfig::getInstance();

// собираем всех детей
$parent_array = array();
foreach ($menus as $menu){
	if($menu->parent>0){
		$parent_array[$menu->parent][] = $menu;
	}
}

ob_start();
?>d = new dTree('d','<?php echo $config->config_live_site ?>/modules/mod_menu/tree/images/');
d.add(0,-1,'');<?php
$n = 1;
foreach ( $menus as $menu ){
	if( !isset($parent_array[$menu->id])){
		$menu->id = '1010101'.$n;
	}
	?>d.add(<?php echo $menu->id ?>,<?php echo $menu->parent ?>,'<?php echo htmlspecialchars($menu->name) ?>','<?php echo sefRelToAbs($menu->link) ?>');<?php
	$n++;
}
?>
document.write(d);
<?php
$cur_menu = ob_get_contents();
ob_end_clean();


function get_menu_links($menutype){
	$database = &database::getInstance();
	$sql = 'SELECT id,name,link,parent FROM #__menu'
	. ' WHERE menutype = ' . $database->Quote( $menutype )
	. ' AND published = 1 ORDER BY parent, ordering' ;
	$database->setQuery( $sql );
	$menus = $database->loadObjectList();
	unset($menus[0]); // убираем первый пункт меню

	$children = array();
	foreach($menus as $v) {
		$pt = $v->parent;
		$list = @$children[$pt]?$children[$pt]:array();
		array_push($list,$v);
		$children[$pt] = $list;
	}
	return mosTreeRecurse(0,'',array(),$children,max(0,5 - 1));
}


?>
<div class="dtree"><script type="text/javascript" src="<?php echo $config->config_live_site ?>/modules/mod_menu/tree/js/tree.js"></script></div>
<script type="text/javascript"><?php echo $cur_menu; ?></script>
<link href="<?php echo $config->config_live_site ?>/modules/mod_menu/tree/css/tree.css" rel="stylesheet" type="text/css" />
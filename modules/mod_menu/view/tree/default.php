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

	//Подключаем CSS модуля (если указано в настройках)
	$module->helper->load_css($params);
	
	//Получаем пункты меню
	$menus = $module->helper->get_menu_tree($params);
	$menus =  mosTreeRecurse(0,'',array(),$menus, max(0,5 - 1));
	
	// собираем всех детей
	$parent_array = array();
	foreach ($menus as $menu){
		if($menu->parent>0){
			$parent_array[$menu->parent][] = $menu;
		}
	}
	
	ob_start(); ?>
	
	d = new dTree('d','<?php echo JPATH_SITE ?>/modules/mod_menu/view/tree/images/');
	d.add(0,-1,'');
	
	<?php $n = 1; foreach ($menus as $menu){
		if( !isset($parent_array[$menu->id])){
			$menu->id = '1010101'.$n;
		}?>
		
		d.add(<?php echo $menu->id ?>,<?php echo $menu->parent ?>,'<?php echo htmlspecialchars($menu->name) ?>','<?php echo sefRelToAbs($menu->link) ?>');
	<?php $n++; }?>
	
	document.write(d);
	<?php $cur_menu = ob_get_contents(); ob_end_clean();?>
	
	
	<div class="dtree">
		<script type="text/javascript" src="<?php echo JPATH_SITE ?>/modules/mod_menu/view/tree/js/tree.js"></script>
	</div>
	<script type="text/javascript"><?php echo $cur_menu; ?></script>
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
	?>
	
	<div class="menu_vert1">
		<ul class="dropdown toplevel">
			<?php menu_recurse( 0, 0, $menus ); ?>
		</ul>
	</div>
<?php

function menu_recurse( $id, $level, &$children) {
	
	if ( isset( $children[$id] ) ) {
		
		foreach ($children[$id] as $row) {
			
			if($row->type == 'separator'){
				$href = 'javascript:void(0)';
			}
			else{
				$href = sefRelToAbs($row->link.'&Itemid='.$row->id);	
			}
				
			if (isset($children[$row->id])) {

				echo '<li><a href="'.$href.'" class="fly">'.$row->name."<!--[if gte IE 7]><!--></a><!--<![endif]-->";
				if (isset($children[$row->id])) {
					
					echo '<!--[if lte IE 6]><table><tr><td><![endif]--><ul class="submenu">';
					menu_recurse( $row->id, $level+1, $children);
					echo "<!--[if lte IE 6]></td></tr></table></a><![endif]--></ul>";
				}

				echo "</li>";
			} else {

				echo '<li><a href="'.$href.'">'.$row->name."</a></li>";
			}
		}
	}
}

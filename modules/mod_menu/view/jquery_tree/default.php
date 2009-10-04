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
	
	//Подключаем JS
	$module->helper->load_js(array('jquery.cookie.js', 'jquery.treeview.js'), $params);
	
	//Получаем пункты меню
	$menus = $module->helper->get_menu_tree($params);
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$(".jquery_tree").treeview({
		animated: "fast",
		collapsed: true,
		unique: true,
		persist: "location",
		toggle: function() {
			window.console && console.log("%o was toggled", this);
		}
	});
	});
	</script>	
	<div class="menu_jquery_tree">
		<ul class="jquery_tree filetree">
			<?php menu_recurse_jquery_tree( 0, 0, $menus ); ?>
		</ul>
	</div>
<?php

function menu_recurse_jquery_tree( $id, $level, &$children) {
	
	if ( isset( $children[$id] ) ) {
		
		foreach ($children[$id] as $row) {
			if($row->type == 'separator'){
				$href = 'javascript:void(0)';
			}
			else{
				$href = sefRelToAbs($row->link.'&Itemid='.$row->id);	
			}
				
			if (isset($children[$row->id])) {
				
				echo '<li><span><a href="'.$href.'">'.$row->name."</span></a>";
				if (isset($children[$row->id])) {
					
					echo '<ul class="submenu">';
					menu_recurse_jquery_tree( $row->id, $level+1, $children);
					echo "</ul>";
				}

				echo "</li>";
			} else {

				echo '<li><span><a href="'.$href.'">'.$row->name."</a></span></li>";
			}
		}
	}
}

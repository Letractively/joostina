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
	$module->helper->load_js(array('jquery.dimensions.js', 'jquery.accordion.js'), $params);
	
	//Получаем пункты меню
	$menus = $module->helper->get_menu_tree($params);
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$(".acc").accordion({
		    active: false, 
		    header: '.acc_head', 
		    navigation: true,
		    autoheight: false 

		});
		$(".submenu").accordion({
		    active: false, 
		    header: '.acc_head', 
		    navigation: true,
		    autoheight: false 

		});
	});
	</script>	
	<div class="menu_accordion">
		<ul class="acc">
			<?php menu_recurse_accordion( 0, 0, $menus ); ?>
		</ul>
	</div>
<?php

function menu_recurse_accordion( $id, $level, &$children) {
	
	if ( isset( $children[$id] ) ) {
		
		foreach ($children[$id] as $row) {
			$class = '';
			if($row->type == 'separator'){
				$href = 'javascript:void(0)';
			}
			else{
				$href = sefRelToAbs($row->link.'&Itemid='.$row->id);	
			}
				
			if (isset($children[$row->id])) {
				
					$class = ' class="acc_head"';
				
				echo '<li><a href="'.$href.'" '.$class.'>'.$row->name."</a>";
				if (isset($children[$row->id])) {
					
					echo '<ul class="submenu">';
					menu_recurse_accordion( $row->id, $level+1, $children);
					echo "</ul>";
				}

				echo "</li>";
			} else {

				echo '<li><a href="'.$href.'">'.$row->name."</a></li>";
			}
		}
	}
}

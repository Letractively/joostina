<?php #
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
* 
* // <!-- phpDesigner :: Timestamp [12.10.2009 10:16:21] -->12.10.2009
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

	//Подключаем CSS модуля (если указано в настройках)
	$module->helper->load_css($params);
	
	//Получаем пункты меню
	$menus = $module->helper->get_menu_single($params);
	?>
		<ul class="hor_simple_tabs">
			<?php menu_recurse_simple_tabs($menus, $module); ?>
		</ul>

<?php

	function menu_recurse_simple_tabs(&$menus, $module) {
		global $Itemid;
		
		$i = 1;
		foreach ($menus as $row) {
			
			$module->helper->prepare_link($row);
			
			$wrap_class = ' class="l_'.$i;
			$wrap_id = ' id="hs_'.$row->id.'" ';
			
			if($Itemid == $row->id){
					$wrap_class .= ' current" ';
			}
			else{
				$wrap_class .= '" ';	
			}

			echo '<li'.$wrap_class.$wrap_id.'><a href="'.$row->href.'"><b>'.$row->name."</b></a></li>";
			
			$i++;
		}
	}

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
	$menus = $module->helper->get_menu_single($params);
	?>
		<ul class="hor_simple_tabs">
			<?php menu_recurse_simple_tabs($menus ); ?>
		</ul>

<?php

function menu_recurse_simple_tabs(&$menus) {
	global $Itemid;
		
		foreach ($menus as $row) {
			
			$li_class = '';
			if($Itemid == $row->id){
					$li_class = ' class="current"';
			}
			
			if($row->type == 'separator'){
				$href = 'javascript:void(0)';
			}
			else{
				$href = sefRelToAbs($row->link.'&Itemid='.$row->id);	
			}

			echo '<li'.$li_class.'><a href="'.$href.'"><b>'.$row->name."</b></a></li>";	

		}

}

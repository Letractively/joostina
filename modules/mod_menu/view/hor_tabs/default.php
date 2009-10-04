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
	
		<ul class="dropdown_hor_tabs">
			<?php menu_recurse_hor_tabs( 0, 0, $menus ); ?>
		</ul>
		
	<script type="text/javascript">
	$(document).ready(function() {	
		$('ul.dropdown_hor_tabs li').each(function(e){
			if($(this).hasClass('currentsub')){
				$(this).parents('li').addClass('current');
				return false;
			}
		})

	});
	</script>

<?php

function menu_recurse_hor_tabs( $id, $level, &$children) {
	global $Itemid;
	
	if ( isset( $children[$id] ) ) {
		
		foreach ($children[$id] as $row) {
			
			$a_class = ''; $li_class = ' lev_'.$level.' ';
			if($Itemid == $row->id){
				if($level == 0){
					$li_class .= ' current';
				}
				else{
					$li_class .= ' currentsub';	
				}
			}
			
			if($row->type == 'separator'){
				$href = 'javascript:void(0)';
			}
			else{
				$href = sefRelToAbs($row->link.'&Itemid='.$row->id);	
			}
				
			if (isset($children[$row->id])) {
				
				if($level == 0){
					$a_class = ' class="sub"';
				}
				else{
					$a_class = ' class="fly"';	
				}
				
				if($level == 0){
					echo '<li class="'.$li_class.'"><a href="'.$href.'" '.$a_class.'><b>'.$row->name."</b><!--[if gte IE 7]><!--></a><!--<![endif]-->";	
				}
				else{
					echo '<li class="'.$li_class.'"><a href="'.$href.'" '.$a_class.'>'.$row->name."<!--[if gte IE 7]><!--></a><!--<![endif]-->";
				}
				
				
				if (isset($children[$row->id])) {
					
					echo '<!--[if lte IE 6]><table><tr><td><![endif]--><ul class="submenu">';
					menu_recurse_hor_tabs( $row->id, $level+1, $children);
					echo "<!--[if lte IE 6]></td></tr></table></a><![endif]--></ul>";
				}

				echo "</li>";
			} else {
				if($level == 0){
					echo '<li class="'.$li_class.'"><a href="'.$href.'"><b>'.$row->name."</b></a></li>";	
				}
				else{
					echo '<li class="'.$li_class.'"><a href="'.$href.'">'.$row->name."</a></li>";
				}
				
			}
		}
	}
}

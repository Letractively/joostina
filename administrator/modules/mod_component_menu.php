<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

global $option;

?><ul id="component_menu"><?php get_components_menu(); ?></ul><?php

function get_components_menu(){
	global $database,$option,$_SERVER;
	$sql = 'SELECT name,admin_menu_link,admin_menu_alt,admin_menu_img FROM #__components WHERE `#__components`.parent<>0 AND `#__components`.option=\''.$option.'\'';
	$database->setQuery($sql);
	$components = $database->loadObjectList();
	$url = $_SERVER['QUERY_STRING'];

	foreach($components as $component){
		if($url==$component->admin_menu_link){
			$style = 'class="active" style="color:red;"';
		}else{
			$style = '';
		}
		?><li><img src="../includes/<?php echo $component->admin_menu_img ?>"><a <?php echo $style; ?> href="index2.php?<?php echo $component->admin_menu_link ?>" title="<?php echo $component->admin_menu_alt; ?>"><?php echo $component->name; ?></a></li><?php
	}
}

?>

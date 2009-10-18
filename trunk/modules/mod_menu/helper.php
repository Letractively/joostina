<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();


class mod_menu_Helper{
	
	var $_mainframe = null;
	
	function mod_menu_Helper($mainframe){
		$this->_mainframe = $mainframe;	
	}

	function load_css($params){
		$mainframe = $this->_mainframe;
		if($params->get('css',1)){
			$css = 	JPATH_SITE.'/modules/mod_menu/view/'.$params->get( 'type_menu', 'tree' ).'/css/menu_style.css';
			$css2 = '<link href="'.$css.'" rel="stylesheet" type="text/css" />';
			$mainframe->addCustomFooterTag($css2);
		}
	}

	function load_js($js = array(), $params){
		$mainframe = $this->_mainframe;
		foreach($js as $file){
			?>
			<script type="text/javascript" src="<?php echo JPATH_SITE; ?>/modules/mod_menu/view/<?php echo $params->get( 'type_menu', 'tree' ) ;?>/js/<?php echo $file;?>"></script>
			<?php
		}
	}

	function get_menu_tree($params){
		$menutype = $params->get('menutype','mainmenu');

		$all_menus = $this->_mainframe->all_menu;
		$menus = $all_menus[$menutype];

		if($params->get('first_item', 1)){
			unset($menus[0]);
		}

		$children = array();
		foreach($menus as $v) {
			$pt = $v->parent;
			$list = @$children[$pt]?$children[$pt]:array();
			array_push($list,$v);
			$children[$pt] = $list;
		}
		return $children;
	}

	function get_menu_single($params){
		$menutype = $params->get('menutype','mainmenu');

		$all_menus = $this->_mainframe->all_menu;
		$menus = $all_menus[$menutype];
		
		if($params->get('first_item', 1)){
			unset($menus[0]);
		}

		return $menus;
	}
	
	function prepare_link($row){
		
		if($row->type == 'separator'){
			$row->href = 'javascript:void(0)';
		}
		else if($row->type == 'url'){
			$row->href = $row->link;	
		}
		else{			
			$row->href = sefRelToAbs($row->link.'&Itemid='.$row->id);
			$row->href = ampReplace($row->href);	
		}
		
		return $row;		
		
	}
}
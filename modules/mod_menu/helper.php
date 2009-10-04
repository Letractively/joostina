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
	
	function load_css($params){
		$mainframe = &mosMainFrame::getInstance();
		if($params->get('css',1)){?>
			<link href="<?php echo $mainframe->getCfg('live_site'); ?>/modules/mod_menu/view/<?php echo $params->get( 'type_menu', 'tree' ) ;?>/css/menu_style.css" rel="stylesheet" type="text/css" />
		<?php		
		}
	}
	
	function load_js($js = array(), $params){
		$mainframe = &mosMainFrame::getInstance();
		
		foreach($js as $file){
			?>
			<script type="text/javascript" src="<?php echo $mainframe->getCfg('live_site'); ?>/modules/mod_menu/view/<?php echo $params->get( 'type_menu', 'tree' ) ;?>/js/<?php echo $file;?>"></script>
			<?php
		}
	}

    function get_menu_tree($params){
    	$menutype = $params->get('menutype','mainmenu');
    	
		$database = &database::getInstance();
		$sql = 'SELECT id,name,link,parent, type FROM #__menu'
		. ' WHERE menutype = ' . $database->Quote( $menutype )
		. ' AND published = 1 ORDER BY parent, ordering' ;
		$database->setQuery( $sql );
		$menus = $database->loadObjectList();
		
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
    	
		$database = &database::getInstance();
		$sql = 'SELECT id,name,link, type FROM #__menu'
		. ' WHERE menutype = ' . $database->Quote( $menutype )
		. ' AND published = 1 AND parent = 0 ORDER BY ordering' ;
		$database->setQuery( $sql );
		$menus = $database->loadObjectList();
		
		if($params->get('first_item', 1)){
			unset($menus[0]); 	
		}		

		return $menus;	
		
    }
}
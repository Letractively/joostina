<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, смотрите LICENSE.php
* Joostina! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/
require(dirname(__FILE__).'/../../die.php');
global $mosConfig_absolute_path,$mosConfig_live_site,$my;

if(!$my->id) exit;

$task	= mosGetParam( $_GET, 'task', 'publish');
$id		= intval(mosGetParam( $_GET, 'id', '0'));
$pos	= mosGetParam( $_GET, 'pos', '0');
$newpos	= mosGetParam( $_GET, 'pos', '0');

switch($task){
	case "publish":
		$img = x_publish($id);
		echo '<img src="'.$mosConfig_live_site.'/administrator/images/'.$img.'" width="12" height="12" border="0" alt="" />';
		return ;
	case "position":
		echo x_position($pos,$id);
		global $mosConfig_absolute_path;
		/* подключаем Pquery */
		mosCommonHTML::loadPquery();
		$pquery= new PQuery();
		$ret = $pquery->visual_effect('hide','#pos_'.$id);
		echo $pquery->tag($ret);
		$ret = $pquery->visual_effect('show','#sel_'.$id);
		echo $pquery->tag($ret);
		return ;
	case "save_position":
		$img = x_save_position($id,$newpos);
		echo $id.'_to_'.$newpos;
		return ;
}

function x_publish($id=null){
	global $database,$my;
	
	if(!$id) return 'error.';
	

	$query = "SELECT published"
	. "\n FROM #__modules "
	. "\n WHERE id = " . (int) $id
	;
	$database->setQuery( $query );
	$state = $database->loadResult();

	if($state=='1'){
		$ret_img = 'publish_x.png';
		$state = '0';
	}else{
		$ret_img = 'publish_g.png';
		$state = '1';
	}
	$query = "UPDATE #__modules"
	. "\n SET published = " . (int) $state
	. "\n WHERE id = ".$id." "
	. "\n AND ( checked_out = 0 OR ( checked_out = " . (int) $my->id . " ) )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		return 'error!';
	}else{
		return $ret_img;
	}
	mosCache::cleanCache( 'com_content' );
}

function x_position($active=null,$id=null){
	global $database,$mosConfig_live_site;
	$query = "SELECT position, description"
	. "\n FROM #__template_positions"
	. "\n WHERE position != ''"
	. "\n ORDER BY position";
	$database->setQuery( $query );
	$positions = $database->loadObjectList();

	$orders2 = array();
	$pos = array();
	foreach ($positions as $position) {
		$orders2[$position->position] = array();
		$pos[] = mosHTML::makeOption( $position->position, $position->description );
	}

	$l = 0;
	$r = 0;
	for ($i=0, $n=count( $orders ); $i < $n; $i++) {
		$ord = 0;
		if (array_key_exists( $orders[$i]->position, $orders2 )) {
			$ord =count( array_keys( $orders2[$orders[$i]->position] ) ) + 1;
		}
		$orders2[$orders[$i]->position][] = mosHTML::makeOption( $ord, $ord.'::'.addslashes( $orders[$i]->title ) );
	}
	//$pos_select = 'onchange="changeDynaList(\'ordering\',orders,document.adminForm.position.options[document.adminForm.position.selectedIndex].value, originalPos, originalOrder)"';

	//$url = $mosConfig_live_site.'/administrator/components/com_modules/admin.modules.ajax.php?task=save_position&id='.$id.'&newpos=';
	//$pquery= new PQuery();
	//return $pquery->link_to_remote($sel,array('url'=>$url,'update'=>'#ajax_status');
	return mosHTML::selectList( $pos, 'position', 'class="inputbox" size="1" onchange="alert(\'555\')" ', 'value', 'text', $active );

}

function x_save_position(){

};
?>

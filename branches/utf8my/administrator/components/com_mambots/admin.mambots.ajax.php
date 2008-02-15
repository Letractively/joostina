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

switch($task){
	case "publish":
		$img = x_publish($id);
		echo '<img src="'.$mosConfig_live_site.'/administrator/images/'.$img.'" width="12" height="12" border="0" alt="" />';
		return ;
}

function x_publish($id=null){
	global $database,$my;
	
	if(!$id) return 'error.';
	

	$query = "SELECT published"
	. "\n FROM #__mambots "
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
	$query = "UPDATE #__mambots"
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
?>

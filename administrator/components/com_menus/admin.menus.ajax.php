<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2007 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/copyleft/gpl.html GNU/GPL, �������� LICENSE.php
* Joostina! - ��������� ����������� �����������. ��� ������ ����� ���� ��������
* � ������������ � ����������� ������������ ��������� GNU, ������� ��������
* � ���������� ��������������� � ������� ���������� ������, ����������������
* �������� ����������� ������������ ��������� GNU ��� ������ �������� ���������
* �������� ��� �������� � �������� �������� �����.
* ��� ��������� ������������ � ��������� �� ��������� �����, �������� ���� COPYRIGHT.php.
*/

// ������ ������� �������
defined( '_VALID_MOS' ) or die( '������ ����� ����� ��������' );
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
	. "\n FROM #__menu "
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
	$query = "UPDATE #__menu"
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

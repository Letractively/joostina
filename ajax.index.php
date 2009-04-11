<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008-2009 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

// ��������� �����, ��� ��� - ������������ ����
define('_VALID_MOS',1);

$mosConfig_absolute_path = dirname( __FILE__ );
require ($mosConfig_absolute_path.'/includes/globals.php');
require_once ('./configuration.php');


// ��������� ����������� ������
$http_host = explode(':',$_SERVER['HTTP_HOST']);
if((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off' || isset($http_host[1]) && $http_host[1] == 443) && substr($mosConfig_live_site,0,8) !='https://') {
	$mosConfig_live_site = 'https://' . substr($mosConfig_live_site,7);
}

require_once ('includes/joomla.php');

// ����������� ��������� ������������ �����
if($mosConfig_offline == 1) {
	echo 'syte-offline';
	exit();
}

// �������������� ������������� � ������, �� ��������� �������
$utf_conv	= intval(mosGetParam($_REQUEST,'utf',1));
$option		= strval(strtolower(mosGetParam($_REQUEST,'option','')));
$task		= strval(mosGetParam($_REQUEST,'task',''));

$commponent = str_replace('com_','',$option);

// mainframe - �������� ������� ����� API, ������������ �������������� � '�����'
$mainframe = mosMainFrame::getInstance();
$mainframe->initSession();

// �������� ����� �������� ����� �� ���������
if($mosConfig_lang == '') {
	$mosConfig_lang = 'russian';
}
include_once ($mosConfig_absolute_path.'/language/'.$mosConfig_lang.'.php');

// get the information about the current user from the sessions table
$my = $mainframe->getUser();
$gid = intval($my->gid);

// � ����������� �� ������������� ����������������� � UTF-8
if($utf_conv){
	header("Content-type: text/html; charset=utf-8");
	header ("Cache-Control: no-cache, must-revalidate ");
	ob_start();
}else{
	header("Content-type: text/html; "._ISO);
	header ("Cache-Control: no-cache, must-revalidate ");
}

// ���������, ����� ���� ���������� ����������, ������ ������� �� ���������� GET �������
if(file_exists($mosConfig_absolute_path . "/components/$option/$commponent.ajax.php")) {
	include_once ($mosConfig_absolute_path . "/components/$option/$commponent.ajax.php");
} else {
	die('error-1');
}

if($utf_conv){
	$_ajax_body = ob_get_contents();
	ob_end_clean();
	// ���� ������������� �������������� ������������� � ������
	echo joostina_api::convert($_ajax_body,1); // ������� ���������������� �����
}
?>

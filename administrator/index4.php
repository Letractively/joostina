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

// ������������� ������������ ����
define( '_VALID_MOS', 1 );
// �������� ����� ������������
if (!file_exists( '../configuration.php' )) {
	die('NON config file');
}
// ���������� ���� ����������� ���������� ���������� � ������������
require( '../globals.php' );
require_once( '../configuration.php' );
// ��������� ����������� ������
$http_host = explode(':', $_SERVER['HTTP_HOST'] );
if( (!empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) != 'off' || isset( $http_host[1] ) && $http_host[1] == 443) && substr( $mosConfig_live_site, 0, 8 ) != 'https://' ) {
	$mosConfig_live_site = 'https://'.substr( $mosConfig_live_site, 7 );
}
// ���������� ���� � ����
require_once( $mosConfig_absolute_path . '/includes/joomla.php' );
include_once( $mosConfig_absolute_path . '/language/'. $mosConfig_lang. '.php' );
require_once( $mosConfig_absolute_path . '/administrator/includes/admin.php' );
// ������ ������
session_name( md5( $mosConfig_live_site ) );
session_start();

$option		= strval( strtolower( mosGetParam( $_GET, 'option', '' ) ) );
$task		= strval( mosGetParam( $_GET, 'task', '' ) );

$mainframe	= new mosMainFrame( $database, $option, '..', true );
$my			= $mainframe->initSessionAdmin( $option, $task );

$commponent	= str_replace('com_','',$option);

header("Content-type: text/html; charset=utf-8");
ob_start();	

// ���������, ����� ���� ���������� ����������, ������ ������� �� ���������� GET �������
if (file_exists( $mosConfig_absolute_path . "/administrator/components/$option/admin.$commponent.ajax.php" )) {
	include_once($mosConfig_absolute_path . "/administrator/components/$option/admin.$commponent.ajax.php");
} else {
	die('NON include component');
}

$_ajax_body = ob_get_contents();
ob_end_clean();
echo joostina_api::convert($_ajax_body,1);

?>

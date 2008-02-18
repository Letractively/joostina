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

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ($task) {
	case 'newclient':
	case 'editclient':
	case 'editclientA':
		TOOLBAR_bannerClient::_EDIT();
		break;

	case 'listclients':
		TOOLBAR_bannerClient::_DEFAULT();
		break;

	case 'new':
	case 'edit':
	case 'editA':
		TOOLBAR_banners::_EDIT();
		break;

	default:
		TOOLBAR_banners::_DEFAULT();
		break;
}
?>

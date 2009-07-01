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

mosAdminMenus::menuItem( $type );

switch ($task) {
	case 'components':
		// this is the new item, ie, the same name as the menu `type`
		components_menu::edit( 0, $menutype, $option );
		break;

	case 'edit':
		components_menu::edit( $cid[0], $menutype, $option );
		break;


	case 'save':
	case 'apply':
		saveMenu( $option, $task );
		break;
}
?>
<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008-2009 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

// ������ ������� �������
defined('_VALID_MOS') or die();

mosAdminMenus::menuItem($type);

switch($task) {
	case 'content_item_link':
		// this is the new item, ie, the same name as the menu `type`
		content_item_link_menu::edit(0,$menutype,$option);
		break;

	case 'edit':
		content_item_link_menu::edit($cid[0],$menutype,$option);
		break;

	case 'save':
	case 'apply':
	case 'save_and_new':
		saveMenu($option,$task);
		break;

	case 'redirect':
		content_item_link_menu::redirect($scid);
		break;
}
?>

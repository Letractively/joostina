<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008-2009 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*
* @version		3.1.0
* @package		patTemplate
* @author		Stephan Schmidt <schst@php.net>
* @license		LGPL
* @link		http://www.php-tools.net
*/
// ������ ������� �������
defined('_VALID_MOS') or die();
class patTemplate_Modifier_Translate extends patTemplate_Modifier {
function modify($value,$params = array()) {
if(class_exists('JText')) {
return JText::_($value);
} else {
if(defined($value)) {
$value = constant($value);
}
return $value;
}
}
}
?>

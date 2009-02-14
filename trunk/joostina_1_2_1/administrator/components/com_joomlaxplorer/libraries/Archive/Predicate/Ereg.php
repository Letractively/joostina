<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

defined('_VALID_MOS') or die();
require_once dirname(__file__)."/../Predicate.php";
class File_Archive_Predicate_Ereg extends File_Archive_Predicate {
	var $ereg;
	function File_Archive_Predicate_Ereg($ereg) {
		$this->ereg = $ereg;
	}
	function isTrue(&$source) {
		return ereg($this->ereg,$source->getFilename());
	}
}

?>
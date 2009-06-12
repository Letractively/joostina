<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008-2009 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

defined('_VALID_MOS') or die();
require_once dirname(__file__)."/../Writer.php";
class File_Archive_Writer_AddBaseName {
	var $writer;
	var $baseName;
	function File_Archive_Writer_AddBaseName($baseName,&$writer) {
		if(substr($baseName,-1) == '/') {
			$this->baseName = $baseName;
		} else {
			$this->baseName = $baseName.'/';
		}
		$this->writer = &$writer;
	}
	function newFile($filename,$stat = array(),$mime = "application/octet-stream") {
		$this->writer->newFile($this->baseName.$filename,$stat,$mime);
	}
	function newFromTempFile($tmpfile,$filename,$stat = array(),$mime ="application/octet-stream") {
		$this->writer->newFromTempFile($tmpfile.$this->baseName.$filename,$stat,$mime);
	}
	function newFileNeedsMIME() {
		return $this->writer->newFileNeedsMIME();
	}
	function writeData($data) {
		$this->writer->writeData($data);
	}
	function writeFile($filename) {
		$this->writer->writeFile($filename);
	}
	function close() {
		$this->writer->close();
	}
}

?>
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
require(dirname(__FILE__).'/../../../die.php');

class CDirExclusionFilter {

	/** @var array Array of the database filters */
	var $_filterArray;

	/**
	* Class initializer, loads existing filters
	*/
	function CDirExclusionFilter(){
		global $database;

		// Initialize by loading any exisiting filters
		$sql = "SELECT * FROM #__jp_def";
		$database->setQuery( $sql );
		$database->query();

		$this->_filterArray = $database->loadAssocList();
	}

	function ReplaceSlashes($string){
		return str_replace("\\", "/", $string);
	}

	/**
	* Returns the array of the filters
	* @return array The exclusion filters
	*/
	function getFilters(){
		global $JPConfiguration, $mosConfig_absolute_path;

		// Initialize with existing filters
		if (is_null($this->_filterArray)) {
			$myArray = array();
		} else {
			$myArray = array();

			foreach($this->_filterArray as $filter){
				$myArray[] = $filter['directory'];
			}
		}

		// Add output, temporary and installation directory to exclusion filters
		$myArray[] = $this->ReplaceSlashes($JPConfiguration->OutputDirectory);
		$myArray[] = $this->ReplaceSlashes($JPConfiguration->TempDirectory);
		$myArray[] = $this->ReplaceSlashes($mosConfig_absolute_path . DIRECTORY_SEPARATOR . "installation");
		return $myArray;
	}

	/**
	* Returns the contents of a directory and their exclusion status
	* @param $root string Start from this folder
	* @return array Directories and their status
	*/
	function getDirectory( $root ){
		global $mosConfig_absolute_path;

		// If there's no root directory specified, use the site's root
		$root = is_null($root) ? $mosConfig_absolute_path : $root ;

		// Initialize filter list
		$tempFilterArray = $this->getFilters();

		$FilterArray = array();
		foreach($tempFilterArray as $filter){
			$FilterArray[] = $this->ReplaceSlashes($filter);
		}

		// Initialize directories array
		$arDirs = array();

		// Get subfolders
		require_once("CFSAbstraction.php");
		$FS = new CFSAbstraction();

		$allFilesAndDirs = $FS->getDirContents( $root );

		if (!($allFilesAndDirs === false)) {
			foreach($allFilesAndDirs as $fileDef) {
				$fileName = $fileDef['name'];
				if ($fileDef['type'] == "dir") {
					$fileName = basename( $fileName );
					if (($this->ReplaceSlashes($root) == $this->ReplaceSlashes($mosConfig_absolute_path)) && ( ($fileName == ".") || ($fileName == "..") )) {
					} else {
						if ($this->_filterArray == "") {
							$arDirs[$fileName] = false;
						} else {
							$arDirs[$fileName] = in_array($this->ReplaceSlashes($root . DIRECTORY_SEPARATOR . $fileName), $FilterArray);
						}
					}
				} // if
			} // foreach
		} // if

		ksort($arDirs);
		return $arDirs;
	}

	function modifyFilter($root, $dir, $checked){
		global $database;

		$activate = ($checked == "on") || ($checked == "yes") || ($checked == "checked") ? true : false;

		$sql = "SELECT `def_id` FROM #__jp_def WHERE `directory`=\"" . mysql_escape_string( $this->ReplaceSlashes($root . "/" . $dir) ) . "\"";
		$database->setQuery( $sql );
		$database->query();
		$def_id = $database->loadResult();

		if ($activate) {
			// Add the filter, if it doesn't exist
			if (is_null($def_id)) {
				$sql = "INSERT INTO #__jp_def (`directory`) VALUES (\"" . mysql_escape_string($this->ReplaceSlashes($root . "/" . $dir) ) . "\")";
				$database->setQuery( $sql );
				$database->query();
			}
		} else {
			// Remove the filter, if it exists
			$sql = "DELETE FROM #__jp_def WHERE `directory` = \"" . mysql_escape_string($this->ReplaceSlashes($root . "/" . $dir) ) . "\"";
			$database->setQuery( $sql );
			$database->query();
		}
	}

}
?>
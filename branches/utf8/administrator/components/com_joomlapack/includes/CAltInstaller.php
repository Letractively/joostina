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

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );

global $mosConfig_absolute_path;

require_once( $mosConfig_absolute_path . '/includes/domit/xml_domit_lite_include.php' );


class CAltInstaller {
	/** @var string Short name of the installer */
	var $Name;

	/** @var string Package file, wihout path */
	var $Package;

	/** @var string List of installer files */
	var $fileList;

	/** @var string Dump mode for the SQL data (split, one) */
	var $SQLDumpMode;

	/** @var string Filename of the unified or table definition dump, relative to installer root */
	var $BaseDump;

	/** @var string Filename of the data dump, relative to installer root */
	var $SampleDump;

	/**
	* Loads a definition file.
	* @param string The name of the file you want to load. Relative to 'installers' directory.
	* @return boolean True if loaded successful the file
	*/
	function loadDefinition( $file ){
		global $mosConfig_absolute_path, $option;

		// Instanciate new parser object
		$xmlDoc = new DOMIT_Lite_Document();
		$xmlDoc->resolveErrors( true );
		// Load XML file
		if (!defined('_JEXEC')) {
			if (!$xmlDoc->loadXML( $mosConfig_absolute_path."/administrator/components/$option/installers/" . $file , false, true )) {
				return false;
			}
		} else {
			if (!$xmlDoc->loadXML( JPATH_ADMINISTRATOR."/administrator/components/$option/installers/" . $file , false, true )) {
				return false;
			}
		}
		$root = &$xmlDoc->documentElement;

		// Check if it is a valid description file
		if ($root->getTagName() != 'jpconfig') {
			return false;
		} elseif ($root->getAttribute( 'type' ) != 'installpack' ) {
			return false;
		}

		// Set basic elements
		$e = &$root->getElementsByPath('name', 1);
		$this->Name = $e->getText();
		$e = &$root->getElementsByPath('package', 1);
		$this->Package = $e->getText();
		$sqlDumpRoot = &$root->getElementsByPath('sqldump', 1);
		$this->SQLDumpMode = &$sqlDumpRoot->getAttribute( "mode" );

		// Get SQL filenames
		if ($sqlDumpRoot->hasChildNodes()) {
			$e = $sqlDumpRoot->getElementsByPath('basedump', 1);
			if ( !is_null($e) ) {
				$this->BaseDump = $e->getText();
			} else {
				$this->BaseDump = "";
			}

			$e = $sqlDumpRoot->getElementsByPath('sampledump', 1);
			if ( !is_null($e) ) {
				$this->SampleDump = $e->getText();
			} else {
				$this->SampleDump = "";
			}
		}

		// Get file list
		$this->fileList = array();
		$flRoot = &$root->getElementsByPath('filelist',1);
		if (!is_null($flRoot)) {
			if ($flRoot->hasChildNodes()) {
				$files = $flRoot->childNodes;
				foreach($files as $file){
					$this->fileList[] = $file->getText();
				}
			}
		}

		return true;
	}

	/**
	* Loads all installer definition files
	* @return array An array of the installer names and packages
	*/
	function loadAllDefinitions() {
		global $mosConfig_absolute_path, $option;

		require_once "CFSAbstraction.php";
		$FS = new CFSAbstraction;

		$defs = array();

		if (!defined('_JEXEC')) {
			$fileList = $FS->getDirContents($mosConfig_absolute_path . "/administrator/components/$option/installers/", "*.xml");
		} else {
			$fileList = $FS->getDirContents(JPATH_ADMINISTRATOR . "/components/$option/installers/", "*.xml");
		}
		foreach($fileList as $fileDef){
			$file = $fileDef['name'];
			$baseName = basename( $file );
			if ($this->loadDefinition( $baseName )) {
				$newDef['name'] = $this->Name;
				$newDef['package'] = $this->Package;
				$newDef['meta'] = $baseName;
				$defs[] = $newDef;
			}
		}

		return $defs;
	}
}
?>
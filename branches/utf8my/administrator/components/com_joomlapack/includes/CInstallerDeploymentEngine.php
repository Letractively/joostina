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

class CInstallerDeploymentEngine {

	/**
     * Have we finished processing our task?
     * @access private
     * @var boolean
     */
	var $_isFinished;

	/**
     * Where we should put our copy of the installer
     * @access private
     * @var string
     */
	var $_targetDirectory;

	/**
     * The path to the compressed installer image
     * @access private
     * @var string
     */
	var $_tarName;

	/**
     * The list of installer files
     * @access private
     * @var array
     */
	var $_fileList;

	/**
	* Constructor for the CInstallerDeploymentEngine class
	*/
	function CInstallerDeploymentEngine(){
		global $JPConfiguration;

		// Indicate we've not finished
		$this->_isFinished = false;

		// Put default values from configuration
		$this->_targetDirectory = $JPConfiguration->TempDirectory;
		$this->_tarName = $JPConfiguration->_InstallationRoot . "/components/com_joomlapack/installers/" . $JPConfiguration->AltInstaller->Package;

		// Create the file list of the installer files
		$filePath = $this->_targetDirectory . "/installation/";
		$filePath = str_replace("\\\\", "/", $filePath);
		$filePath = str_replace("\\", "/", $filePath);

		$this->_fileList = array();
		$AltInstaller = $JPConfiguration->AltInstaller;
		foreach( $AltInstaller->fileList as $fileName ) {
			$this->_fileList[] = $filePath . $fileName;
		}

		CJPLogger::WriteLog(_JP_LOG_DEBUG, "CInstallerDeploymentEngine :: new instance");
	}

	/**
	* Try to execute the business logic of this step
	*/
	function tick(){
		if ($this->_isFinished) {
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "CInstallerDeploymentEngine :: Already finished");
			$returnArray = array();
			$returnArray['HasRun'] = false;
			$returnArray['Domain'] = "InstallerDeployment";
			$returnArray['Step'] = "";
			$returnArray['Substep'] = "";
			return $returnArray; // Indicate we have finished
		} else {
			global $mosConfig_absolute_path, $database;

			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Extracting installer");

			// Include TAR file suport
			require_once( $mosConfig_absolute_path . "/includes/PEAR/PEAR.php" );
			require_once( $mosConfig_absolute_path . "/includes/Archive/Tar.php" );

			// Remove any leftover files
			global $CUBE;
			$CUBE->_unlinkRecursive( $this->_targetDirectory . "/installation" );

			// Extract the installer image
			$tar = new Archive_Tar( $this->_tarName, 'gz' );
			$tar->extract( $this->_targetDirectory );

			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Done extracting installer");
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Creating fragment for installer");

			// Get the current node number in file list db table and start adding
			// to the next fragment
			$sql = "SELECT COUNT(*) FROM #__jp_packvars WHERE `key` like 'fragment%'";
			$database->setQuery( $sql );
			$database->query();
			$currentNode = $database->loadResult() + 1;

			// Add the installer files to the file list stored in db; all files
			// are added as 'Branch 2' entries, indicating a different subpath
			// from the rest of the filelist has to be removed from them.
			$fileList = array();
			$fragmentSize = 0;
			foreach($this->_fileList as $fileName){
				$filesize = filesize( $fileName );
				$fragmentSize += $filesize;
				$fileList[] = $fileName;
			}
			$fragmentDescriptor = array();
			$fragmentDescriptor['type'] = "installation"; // Other possible values are 'site', 'sql', 'external'
			$fragmentDescriptor['size'] = $fragmentSize;
			$fragmentDescriptor['files'] = $fileList;

			$serializedDescriptor = serialize($fragmentDescriptor);
			unset($fragmentDescriptor);
			unset($fileList);
			$sql = "INSERT INTO #__jp_packvars (`key`, value2) VALUES ('fragment" . $currentNode . "', \"" . mysql_escape_string($serializedDescriptor) . "\")";
			$database->setQuery( $sql );
			$database->query();
			unset($serializedDescriptor);

			// Indicate we have finished
			$this->_isFinished = true;

			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Done creating fragment for installer");

			// return information
			$returnArray = array();
			$returnArray['HasRun'] = true;
			$returnArray['Domain'] = "InstallerDeployment";
			$returnArray['Step'] = "";
			$returnArray['Substep'] = "";
			return $returnArray; // Indicate we have finished
		}
	}

}

?>
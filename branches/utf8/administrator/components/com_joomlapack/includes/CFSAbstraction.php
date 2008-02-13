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

class CFSAbstraction {

	/** Should we use glob() ?
	 * @var boolean
	*/
	var $_globEnable;

	/**
	 * Public constructor for CFSAbstraction class. Does some heuristics to figure out the
	 * server capabilities and setup internal variables
	 */
	function CFSAbstraction()
	{
		// Don't use glob if it's disabled or if opendir is available
		$this->_globEnable = function_exists('glob');
		if( function_exists('opendir') && function_exists('readdir') && function_exists('closedir') )
			$this->_globEnable = false;
	}

	/**
	 * Searches the given directory $dirName for files and folders and returns a multidimensional array.
	 * If the directory is not accessible, returns FALSE
	 * @return array See function description for details
	 */
	function getDirContents( $dirName, $shellFilter = null )
	{
		if ($this->_globEnable) {
			return $this->_getDirContents_glob( $dirName, $shellFilter );
		} else {
			return $this->_getDirContents_opendir( $dirName, $shellFilter );
		}
	}

	// ============================================================================
	// PRIVATE SECTION
	// ============================================================================

	/**
	 * Searches the given directory $dirName for files and folders and returns a multidimensional array.
	 * If the directory is not accessible, returns FALSE. This function uses the PHP glob() function.
	 * @return array See function description for details
	 */
	function _getDirContents_glob( $dirName, $shellFilter = null )
	{
		global $JPConfiguration; // Needed for TranslateWinPath function

		if (is_null($shellFilter)) {
			// Get folder contents
			$allFilesAndDirs1 = @glob($dirName . "/*"); // regular files
			$allFilesAndDirs2 = @glob($dirName . "/.*"); // *nix hidden files

			// Try to merge the arrays
			if ($allFilesAndDirs1 === false) {
				if ($allFilesAndDirs2 === false) {
					$allFilesAndDirs = false;
				} else {
					$allFilesAndDirs = $allFilesAndDirs2;
				}
			} elseif ($allFilesAndDirs2 === false) {
				$allFilesAndDirs = $allFilesAndDirs1;
			} else {
				$allFilesAndDirs = @array_merge($allFilesAndDirs1, $allFilesAndDirs2);
			}

			// Free unused arrays
			unset($allFilesAndDirs1);
			unset($allFilesAndDirs2);

		} else {
			$allFilesAndDirs = @glob($dirName . "/$shellFilter"); // filtered files
		}

		// Check for unreadable directories
		if ( $allFilesAndDirs === FALSE ) {
			return FALSE;
		}

		// Populate return array
		$retArray = array();

		foreach($allFilesAndDirs as $filename) {
			$filename = $JPConfiguration->TranslateWinPath( $filename );
			$newEntry['name'] = $filename;
			$newEntry['type'] = filetype( $filename );
			if ($newEntry['type'] == "file") {
				$newEntry['size'] = filesize( $filename );
			} else {
				$newEntry['size'] = 0;
			}
			$retArray[] = $newEntry;
		}

		return $retArray;
	}

	function _getDirContents_opendir( $dirName, $shellFilter = null )
	{
		global $JPConfiguration;

		$handle = @opendir( $dirName );

		// If directory is not accessible, just return FALSE
		if ($handle === FALSE) {
			return FALSE;
		}

		// Initialize return array
		$retArray = array();

		while( !( ( $filename = readdir($handle) ) === false) ) {
			$match = is_null( $shellFilter );
			$match = (!$match) ? fnmatch($shellFilter, $filename) : true;
			if ($match) {
				$filename = $JPConfiguration->TranslateWinPath( $dirName . "/" . $filename );
				$newEntry['name'] = $filename;
				$newEntry['type'] = @filetype( $filename );
				if ($newEntry['type'] !== FALSE) {
					// FIX 1.1.0 Stable - When open_basedir restrictions are in effect, an attempt to read <root>/.. could result into failure of the backup. This fix is a simplistic workaround.
					if ($newEntry['type'] == 'file') {
						$newEntry['size'] = @filesize( $filename );
					} else {
						$newEntry['size'] = 0;
					}
					$retArray[] = $newEntry;
				}
			}
		}

		closedir($handle);
		return $retArray;
	}
}

// FIX 1.1.0 -- fnmatch not available on non-POSIX systems
// Thanks to soywiz@php.net for this usefull alternative function [http://gr2.php.net/fnmatch]
if (!function_exists('fnmatch')) {
	function fnmatch($pattern, $string) {
		return @preg_match(
			'/^' . strtr(addcslashes($pattern, '/\\.+^$(){}=!<>|'),
			array('*' => '.*', '?' => '.?')) . '$/i', $string
		);
	}
}
?>
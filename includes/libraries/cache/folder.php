<?php
/**
* @package Joostina
 * @subpackage Cache 
* @copyright Авторские права (C) 2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// Check to ensure this file is within the rest of the framework
defined('_VALID_MOS') or die();

/**
 * A Folder handling class
 *
 * @static
 * @author		
 * @package 	Joostina
 * @subpackage	FileSystem
 * @since		1.3
 */
class JFolder
{
	function clean($path, $ds=DS)
	{
		$path = trim($path);

		if (empty($path)) {
			$path = JFolder::clean( $mosConfig_absolute_path );
		} else {
			// Remove double slashes and backslahses and convert all slashes and backslashes to DS
			$path = preg_replace('#[/\\\\]+#', $ds, $path);
		}

		return $path;
	}
	function delete_files($file)
	{
		if (is_array($file)) {
			$files = $file;
		} else {
			$files[] = $file;
		}

		foreach ($files as $file)
		{
			$file = JFolder::clean($file);

			// Try making the file writeable first. If it's read-only, it can't be deleted
			// on Windows, even if the parent folder is writeable
			@chmod($file, 0777);

			// In case of restricted permissions we zap it one way or the other
			// as long as the owner is either the webserver or the ftp
			if (@unlink($file)) {
				
			} else {
				$filename	= basename($file);
				JError::raiseWarning('SOME_ERROR_CODE', 'Delete failed' . ": '$filename'");
				return false;
			}
		}

		return true;
	}
	
	/**
	 * Delete a folder
	 *
	 * @param string $path The path to the folder to delete
	 * @return boolean True on success
	 * @since 1.3
	 */
	function delete($path)
	{
		// Sanity check
		if ( ! $path ) {
			// Bad programmer! Bad Bad programmer!
			JError::raiseWarning(500, 'JFolder::delete: '.'Attempt to delete base directory' );
			return false;
		}
		
		// Check to make sure the path valid and clean
		$path = JFolder::clean($path);

		// Is this really a folder?
		if (!is_dir($path)) {
			JError::raiseWarning(21, 'JFolder::delete: '.'Path is not a folder'.' '.$path);
			return false;
		}

		// Remove all the files in folder if they exist
		$files = JFolder::files($path, '.', false, true, array());
		if (count($files)) {
			//require_once(dirname(__FILE__).DS.'file.php');
			
			if (JFolder::delete_files($files) !== true) {
				// delete_files throws an error
				return false;
			}
		}

		// Remove sub-folders of folder
		$folders = JFolder::folders($path, '.', false, true, array());
		foreach ($folders as $folder) {
			if (JFolder::delete($folder) !== true) {
				// JFolder::delete throws an error
				return false;
			}
		}

		// In case of restricted permissions we zap it one way or the other
		// as long as the owner is either the webserver or the ftp
		if (@rmdir($path)) {
			$ret = true;
		} else {
			JError::raiseWarning('SOME_ERROR_CODE', 'JFolder::delete: '.'Could not delete folder'.' '.$path);
			$ret = false;
		}

		return $ret;
	}
	
	/**
	 * Utility function to read the files in a folder
	 *
	 * @param	string	$path		The path of the folder to read
	 * @param	string	$filter		A filter for file names
	 * @param	mixed	$recurse	True to recursively search into sub-folders, or an integer to specify the maximum depth
	 * @param	boolean	$fullpath	True to return the full path to the file
	 * @param	array	$exclude	Array with names of files which should not be shown in the result
	 * @return	array	Files in the given folder
	 * @since 1.3
	 */
	function files($path, $filter = '.', $recurse = false, $fullpath = false, $exclude = array('.svn', 'CVS'))
	{
		// Initialize variables
		$arr = array ();

		// Check to make sure the path valid and clean
		$path = JFolder::clean($path);

		// Is the path a folder?
		if (!is_dir($path)) {
			JError::raiseWarning(21, 'JFolder::files: '.'Path is not a folder'.' '.$path);
			return false;
		}

		// read the source directory
		$handle = opendir($path);
		while (($file = readdir($handle)) !== false)
		{
			$dir = $path.DS.$file;
			$isDir = is_dir($dir);
			if (($file != '.') && ($file != '..') && (!in_array($file, $exclude))) {
				if ($isDir) {
					if ($recurse) {
						if (is_integer($recurse)) {
							$recurse--;
						}
						$arr2 = JFolder::files($dir, $filter, $recurse, $fullpath);
						$arr = array_merge($arr, $arr2);
					}
				} else {
					if (preg_match("/$filter/", $file)) {
						if ($fullpath) {
							$arr[] = $path.DS.$file;
						} else {
							$arr[] = $file;
						}
					}
				}
			}
		}
		closedir($handle);

		asort($arr);
		return $arr;
	}

	/**
	 * Utility function to read the folders in a folder
	 *
	 * @param	string	$path		The path of the folder to read
	 * @param	string	$filter		A filter for folder names
	 * @param	mixed	$recurse	True to recursively search into sub-folders, or an integer to specify the maximum depth
	 * @param	boolean	$fullpath	True to return the full path to the folders
	 * @param	array	$exclude	Array with names of folders which should not be shown in the result
	 * @return	array	Folders in the given folder
	 * @since 1.3
	 */
	function folders($path, $filter = '.', $recurse = false, $fullpath = false, $exclude = array('.svn', 'CVS'))
	{
		// Initialize variables
		$arr = array ();

		// Check to make sure the path valid and clean
		$path = JFolder::clean($path);

		// Is the path a folder?
		if (!is_dir($path)) {
			JError::raiseWarning(21, 'JFolder::folder: '.'Path is not a folder'.' '.$path);
			return false;
		}

		// read the source directory
		$handle = opendir($path);
		while (($file = readdir($handle)) !== false)
		{
			$dir = $path.DS.$file;
			$isDir = is_dir($dir);
			if (($file != '.') && ($file != '..') && (!in_array($file, $exclude)) && $isDir) {
				// removes SVN directores from list
				if (preg_match("/$filter/", $file)) {
					if ($fullpath) {
						$arr[] = $dir;
					} else {
						$arr[] = $file;
					}
				}
				if ($recurse) {
					if (is_integer($recurse)) {
						$recurse--;
					}
					$arr2 = JFolder::folders($dir, $filter, $recurse, $fullpath);
					$arr = array_merge($arr, $arr2);
				}
			}
		}
		closedir($handle);

		asort($arr);
		return $arr;
	}
}
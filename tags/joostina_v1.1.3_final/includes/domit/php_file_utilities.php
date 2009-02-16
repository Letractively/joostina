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

if (!defined('PHP_TEXT_CACHE_INCLUDE_PATH')) {
	define('PHP_TEXT_CACHE_INCLUDE_PATH', (dirname(__FILE__) . "/"));
}

class php_file_utilities {
	/**
	* Retrieves binary or text data from the specified file
	* @param string The file path
	* @param string The attributes for the read operation ('r' or 'rb' or 'rt')
	* @return mixed he text or binary data contained in the file
	*/
	function &getDataFromFile($filename, $readAttributes, $readSize = 8192) {
		$fileContents = null;
		$fileHandle = @fopen($filename, $readAttributes);

		if($fileHandle){
			do {
				$data = fread($fileHandle, $readSize);

				if (strlen($data) == 0) {
					break;
				}

				$fileContents .= $data;
			} while (true);

			fclose($fileHandle);
		}

		return $fileContents;
	} //getDataFromFile

	/**
	* Writes the specified binary or text data to a file
	* @param string The file path
	* @param mixed The data to be written
	* @param string The attributes for the write operation ('w' or 'wb')
	*/
	function putDataToFile($fileName, &$data, $writeAttributes) {
		$fileHandle = @fopen($fileName, $writeAttributes);

		if ($fileHandle) {
			fwrite($fileHandle, $data);
			fclose($fileHandle);
		}
	} //putDataToFile
} //php_file_utilities
?>

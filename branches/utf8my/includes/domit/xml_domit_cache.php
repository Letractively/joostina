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
require(dirname(__FILE__).'/../../die.php');
/**
* @package domit-xmlparser
* @copyright (C) 2004 John Heinstein. All rights reserved
* @license http://www.gnu.org/copyleft/lesser.html LGPL License
* @author John Heinstein <johnkarl@nbnet.nb.ca>
* @link http://www.engageinteractive.com/domit/ DOMIT! Home Page
* DOMIT! is Free Software
**/

/** Extension for cache files */
define ('DOMIT_FILE_EXTENSION_CACHE', 'dch');

/**
* A simple caching mechanism for a DOMIT_Document
*/
class DOMIT_cache {
	/**
	* Serializes and caches the specified DOMIT! document
	* @param string The name of the xml file to be saved
	* @param Object A reference to the document to be saved
	* @param string The write attributes for the saved document ('w' or 'wb')
	*/
	function toCache($xmlFileName, &$doc, $writeAttributes = 'w') {
		require_once(DOMIT_INCLUDE_PATH . 'xml_domit_utilities.php');
		require_once(DOMIT_INCLUDE_PATH . 'php_file_utilities.php');

		$name = DOMIT_Utilities::removeExtension($xmlFileName) . '.' . DOMIT_FILE_EXTENSION_CACHE;
		php_file_utilities::putDataToFile($name, serialize($doc), $writeAttributes);

		return (file_exists($name) && is_writable($name));
	} //toCache

	/**
	* Unserializes a cached DOMIT! document
	* @param string The name of the xml file to be retrieved
	* @return Object The retrieved document
	*/
	function &fromCache($xmlFileName) {
		require_once(DOMIT_INCLUDE_PATH . 'xml_domit_utilities.php');
		require_once(DOMIT_INCLUDE_PATH . 'php_file_utilities.php');

		$name = DOMIT_Utilities::removeExtension($xmlFileName) . '.' . DOMIT_FILE_EXTENSION_CACHE;
		$fileContents =& php_file_utilities::getDataFromFile($name, 'r');
		$newxmldoc =& unserialize($fileContents);

		return $newxmldoc;
	} //fromCache

	/**
	* Determines whether a cached version of the specified document exists
	* @param string The name of the xml file to be retrieved
	* @return boolean True if a cache of the specified document exists
	*/
	function cacheExists($xmlFileName) {
		require_once(DOMIT_INCLUDE_PATH . 'xml_domit_utilities.php');

		$name = DOMIT_Utilities::removeExtension($xmlFileName) . '.' . DOMIT_FILE_EXTENSION_CACHE;
		return file_exists($name);
	} //xmlFileName

	/**
	* Removes a cache of the specified document
	* @param string The name of the xml file to be retrieved
	* @return boolean True if a cache has been removed
	*/
	function removeFromCache($xmlFileName) {
		require_once(DOMIT_INCLUDE_PATH . 'xml_domit_utilities.php');

		$name = DOMIT_Utilities::removeExtension($xmlFileName) . '.' . DOMIT_FILE_EXTENSION_CACHE;
		return unlink($name);
	} //removeFromCache
} //DOMIT_cache
?>

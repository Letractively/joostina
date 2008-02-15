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
require(dirname(__FILE__).'/../die.php');
define( '_JOS_CACHE_INCLUDED', 1 );

require_once( $mosConfig_absolute_path . '/includes/Cache/Lite/Function.php' );

/**
 * Joomla! Cache Lite wrapper for adding special parameters
 * The class uses an aggregation for the reference to the Cache_Lite_Function 
 * in order to be able of calling the methods generically.
 * 
 * @package Joostina
 * @access public
 */
class JCache_Lite_Function {
	/** @var object internal aggregation to the Cache */
	var $_cache = null;

	/** Special constructor which is creating all required references
	* @param array $options options
	* @access public
	*/
	function JCache_Lite_Function($options = array(NULL)) {
		$this->_cache = new Cache_Lite_Function( $options );
	}

	/**
	* Calls a cacheable function or method (or not if there is already a cache for it)
	*
	* This overwritten method addes automatically special arguments to the call
	* Those arguments are e.g. the language if multilingual support is activated
	*
	* @return mixed result of the function/method
	* @access public
	*/
	function call() {
		$arguments = func_get_args();

		// Add language to all arguments, if not already added and multilingual support is activated
		if( array_key_exists( 'mosConfig_multilingual_support', $GLOBALS ) && $GLOBALS['mosConfig_multilingual_support'] == 1 ) {
			$arguments[] = $GLOBALS['mosConfig_lang'];
		}
		
		$ret = call_user_func_array(array($this->_cache, 'call'), $arguments);
		return $ret;
	}

	/**
	* Clean the cache
	*
	* if no group is specified all cache files will be destroyed
	* else only cache files of the specified group will be destroyed
	*
	* @param string $group name of the cache group
	* @return boolean true if no problem
	* @access public
	*/
	function clean($group = false) {
		return  $this->_cache->clean( $group );
	}
}
?>

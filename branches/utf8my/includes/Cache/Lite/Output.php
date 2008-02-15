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
/**
* This class extends Cache_Lite and uses output buffering to get the data to cache.
*
* There are some examples in the 'docs/examples' file
* Technical choices are described in the 'docs/technical' file
*
* @package Cache_Lite
* @version $Id: Output.php 47 2005-09-15 02:55:27Z rhuk $
* @author Fabien MARTY <fab@php.net>
*/

require_once('Cache/Lite.php');

class Cache_Lite_Output extends Cache_Lite
{

	// --- Public methods ---

	/**
	* Constructor
	*
	* $options is an assoc. To have a look at availables options,
	* see the constructor of the Cache_Lite class in 'Cache_Lite.php'
	*
	* @param array $options options
	* @access public
	*/
	function Cache_Lite_Output($options)
	{
		$this->Cache_Lite($options);
	}

	/**
	* Start the cache
	*
	* @param string $id cache id
	* @param string $group name of the cache group
	* @return boolean true if the cache is hit (false else)
	* @access public
	*/
	function start($id, $group = 'default')
	{
		$data = $this->get($id, $group);
		if ($data !== false) {
			echo($data);
			return true;
		} else {
			ob_start();
			ob_implicit_flush(false);
			return false;
		}
	}

	/**
	* Stop the cache
	*
	* @access public
	*/
	function end()
	{
		$data = ob_get_contents();
		ob_end_clean();
		$this->save($data, $this->_id, $this->_group);
		echo($data);
	}

}


?>
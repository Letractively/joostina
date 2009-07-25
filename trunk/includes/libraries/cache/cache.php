<?php
/**
* @package Joostina
 * @subpackage Cache handler
* @copyright ��������� ����� (C) 2007-2009 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

// Check to ensure this file is within the rest of the framework
defined('_VALID_MOS') or die();

//Register the session storage class with the loader

//require_once(dirname(__FILE__).DS.'storage.php');

/**
 * Joostina! Cache base object
 *
 * @abstract
 * @author		
 * @package		Joostina
 * @subpackage	Cache handler
 * @since		1.3
 */
class JCache{
	/**
	 * Storage Handler
	 * @access	private
	 * @var		object
	 */
	var $_handler;

	/**
	 * Cache Options
	 * @access	private
	 * @var		array
	 */
	var $_options;

	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	array	$options	options
	 */
	function __construct($options)
	{
		$this->_options =& $options;
		
		// Get the default group and caching
		if(isset($options['language'])) {
			$this->_options['language'] = $options['language'];
		} else {
			$options['language'] = 'en-GB';
		}

		if(isset($options['cachebase'])) {
			$this->_options['cachebase'] = $options['cachebase'];
		} else {
			$this->_options['cachebase'] = JPATH_ROOT.DS.'cache';
		}

		if(isset($options['defaultgroup'])) {
			$this->_options['defaultgroup'] = $options['defaultgroup'];
		} else {
			$this->_options['defaultgroup'] = 'default';
		}

		if(isset($options['caching'])) {
			$this->_options['caching'] =  $options['caching'];
		} else {
			$this->_options['caching'] = true;
		}

		if( isset($options['storage'])) {
			$this->_options['storage'] = $options['storage'];
		} else {
			$this->_options['storage'] = 'file';
		}
	}
	
	
	
	/**
	 * Returns a reference to a cache adapter object, always creating it
	 *
	 * @static
	 * @param	string	$type	The cache object type to instantiate
	 * @return	object	A JCache object
	 * @since	1.3
	 */
	 
	function &getInstance($type = 'output', $options = array())
	{
		$type = strtolower(preg_replace('/[^A-Z0-9_\.-]/i', '', $type));

		$class = 'JCache'.ucfirst($type);

		if(!class_exists($class))
		{
			$path = dirname(__FILE__).DS.'handler'.DS.$type.'.php';

			if (file_exists($path)) {
				require_once($path);
			} else {
				return NULL;
			}
		}
		$instance = new $class($options);
		return $instance;
	}

	/**
	 * Get the storage handlers
	 *
	 * @access public
	 * @return array An array of available storage handlers
	 */
	function getStores(){
		require_once(dirname(__FILE__).DS.'../filesystem/folder.php');
		$handlers = JFolder::files(dirname(__FILE__).DS.'storage', '.php');

		$names = array();
		foreach($handlers as $handler){
			$name = substr($handler, 0, strrpos($handler, '.'));
			$class = 'JCacheStorage'.$name;

			if(!class_exists($class)) {
				require_once(dirname(__FILE__).DS.'storage'.DS.$name.'.php');
			}

			if(call_user_func_array( array( trim($class), 'test' ), null)) {
				$names[] = $name;
			}
		}

		return $names;
	}

	/**
	 * Set caching enabled state
	 *
	 * @access	public
	 * @param	boolean	$enabled	True to enable caching
	 * @return	void
	 * @since	1.3
	 */
	function setCaching($enabled){
		$this->_options['caching'] = $enabled;
	}

	/**
	 * Set cache lifetime
	 *
	 * @access	public
	 * @param	int	$lt	Cache lifetime
	 * @return	void
	 * @since	1.3
	 */
	function setLifeTime($lt){
		$this->_options['lifetime'] = $lt;
	}

	/**
	 * Set cache validation
	 *
	 * @access	public
	 * @return	void
	 * @since	1.3
	 */
	function setCacheValidation(){
		// Deprecated
	}

	/**
	 * Get cached data by id and group
	 *
	 * @abstract
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	mixed	Boolean false on failure or a cached data string
	 * @since	1.3
	 */
	function get($id, $group=null){
		// Get the default group
		$group = ($group) ? $group : $this->_options['defaultgroup'];

		// Get the storage handler
		$handler =& $this->_getStorage();
		if ($handler != NULL && $this->_options['caching']) {
			return $handler->get($id, $group, (isset($this->_options['checkTime']))? $this->_options['checkTime'] : true);
		}
		return false;
	}

	/**
	 * Store the cached data by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @param	mixed	$data	The data to store
	 * @return	boolean	True if cache stored
	 * @since	1.3
	 */
	function store($data, $id, $group=null){
		// Get the default group
		$group = ($group) ? $group : $this->_options['defaultgroup'];

		// Get the storage handler and store the cached data
		$handler =& $this->_getStorage();
		if ($handler != NULL && $this->_options['caching']) {
			return $handler->store($id, $group, $data);
		}
		return false;
	}

	/**
	 * Remove a cached data entry by id and group
	 *
	 * @abstract
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	boolean	True on success, false otherwise
	 * @since	1.3
	 */
	function remove($id, $group=null){
		// Get the default group
		$group = ($group) ? $group : $this->_options['defaultgroup'];

		// Get the storage handler
		$handler =& $this->_getStorage();
		if ($handler != NULL) {
			return $handler->remove($id, $group);
		}
		return false;
	}

	/**
	 * Clean cache for a group given a mode.
	 *
	 * group mode		: cleans all cache in the group
	 * notgroup mode	: cleans all cache not in the group
	 *
	 * @access	public
	 * @param	string	$group	The cache data group
	 * @param	string	$mode	The mode for cleaning cache [group|notgroup]
	 * @return	boolean	True on success, false otherwise
	 * @since	1.3
	 */
	function clean($group=null, $mode='group'){
		// Get the default group
		$group = ($group) ? $group : $this->_options['defaultgroup'];
		
		// Get the storage handler
		$handler =& $this->_getStorage();
		if ($handler != NULL) {
			return $handler->clean($group, $mode);
			//return false;
		}
		return false;
	}

	/**
	 * Garbage collect expired cache data
	 *
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 * @since	1.3
	 */
	function gc(){
		// Get the storage handler
		$handler =& $this->_getStorage();
		if ($handler != NULL) {
			return $handler->gc();
		}
		return false;
	}

	/**
	 * Get the cache storage handler
	 *
	 * @access protected
	 * @return object A JCacheStorage object
	 * @since	1.3
	 */
	function &_getStorage(){
		if (is_a($this->_handler, 'JCacheStorage')) {
			return $this->_handler;
		}

		$this->_handler =& JCacheStorage::getInstance($this->_options['storage'], $this->_options);
		if($this->_handler != NULL)
		{				
			if($this->_handler->test())
			{
				return $this->_handler;
			}
			else
			{
				$this->_handler =& JCacheStorage::getInstance('file', $this->_options);
				return $this->_handler;
			}
		}
		else
		{
			return NULL;
		}
	}
}

/**
 * Abstract cache storage handler
 *
 * @abstract
 * @author
 * @package		Joostina
 * @subpackage	Cache
 * @since		1.3
 */
class JCacheStorage{
	/**
	* Constructor
	*
	* @access protected
	* @param array $options optional parameters
	*/
	function __construct( $options = array() ){
		$this->_application	= (isset($options['application'])) ? $options['application'] : null;
		$this->_language	= (isset($options['language'])) ? $options['language'] : 'en-GB';
		$this->_locking		= (isset($options['locking'])) ? $options['locking'] : true;
		$this->_lifetime	= (isset($options['lifetime'])) ? $options['lifetime'] : null;
		$this->_now		= (isset($options['now'])) ? $options['now'] : time();

		// Set time threshold value.  If the lifetime is not set, default to 60 (0 is BAD)
		// _threshold is now available ONLY as a legacy (it's deprecated).  It's no longer used in the core.
		if (empty($this->_lifetime)) {
			$this->_threshold = $this->_now - 60;
			$this->_lifetime = 60;
		} else {
			$this->_threshold = $this->_now - $this->_lifetime;
		}
	}

	/**
	 * Returns a reference to a cache storage hanlder object, only creating it
	 * if it doesn't already exist.
	 *
	 * @static
	 * @param	string	$handler	The cache storage handler to instantiate
	 * @return	object	A JCacheStorageHandler object
	 * @since	1.3
	 */
	function &getInstance($handler = 'file', $options = array()){
		static $now = null;
		if(is_null($now)) {
			$now = time();
		}
		$options['now'] = $now;
		//We can't cache this since options may change...
		$handler = strtolower(preg_replace('/[^A-Z0-9_\.-]/i', '', $handler));
		$class   = 'JCacheStorage'.ucfirst($handler);
		if(!class_exists($class)){
			$path = dirname(__FILE__).DS.'storage'.DS.$handler.'.php';
			if (file_exists($path) ) {
				require_once($path);
			} else {
				return NULL;
			}
		}
		$return = new $class($options);
		return $return;
	}

	/**
	 * Get cached data by id and group
	 *
	 * @abstract
	 * @access	public
	 * @param	string	$id			The cache data id
	 * @param	string	$group		The cache data group
	 * @param	boolean	$checkTime	True to verify cache time expiration threshold
	 * @return	mixed	Boolean false on failure or a cached data string
	 * @since	1.3
	 */
	function get($id, $group, $checkTime){
		return;
	}

	/**
	 * Store the data to cache by id and group
	 *
	 * @abstract
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @param	string	$data	The data to store in cache
	 * @return	boolean	True on success, false otherwise
	 * @since	1.3
	 */
	function store($id, $group, $data){
		return true;
	}

	/**
	 * Remove a cached data entry by id and group
	 *
	 * @abstract
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	boolean	True on success, false otherwise
	 * @since	1.3
	 */
	function remove($id, $group){
		return true;
	}

	/**
	 * Clean cache for a group given a mode.
	 *
	 * group mode		: cleans all cache in the group
	 * notgroup mode	: cleans all cache not in the group
	 *
	 * @abstract
	 * @access	public
	 * @param	string	$group	The cache data group
	 * @param	string	$mode	The mode for cleaning cache [group|notgroup]
	 * @return	boolean	True on success, false otherwise
	 * @since	1.3
	 */
	function clean($group, $mode){
		return true;
	}

	/**
	 * Garbage collect expired cache data
	 *
	 * @abstract
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	function gc(){
		return true;
	}

	/**
	 * Test to see if the storage handler is available.
	 *
	 * @abstract
	 * @static
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	function test(){
		return true;
	}
}

?>
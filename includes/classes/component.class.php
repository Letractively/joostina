<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();
/**
 * Component database table class
 * @package Joostina
 */
class mosComponent extends mosDBTable {
	/**
	 @var int Primary key*/
	public $id = null;
	/**
	 @var string*/
	public $name = null;
	/**
	 @var string*/
	public $link = null;
	/**
	 @var int*/
	public $menuid = null;
	/**
	 @var int*/
	public $parent = null;
	/**
	 @var string*/
	public $admin_menu_link = null;
	/**
	 @var string*/
	public $admin_menu_alt = null;
	/**
	 @var string*/
	public $option = null;
	/**
	 @var string*/
	public $ordering = null;
	/**
	 @var string*/
	public $admin_menu_img = null;
	/**
	 @var int*/
	public $iscore = null;
	/**
	 @var string*/
	public $params = null;
	/*@var int права доступа к компоненту */
	#var $access = null;
	public $_model = null;
	public $_controller = null;
	public $_view = null;
	public $_mainframe = null;

	/**
	 * @param database A database connector object
	 */
	function mosComponent(&$db=null) {
		$this->mosDBTable('#__components','id',$db);
	}

	function _init($option, $mainframe) {

		$this->option = $option;
		$this->_mainframe = $mainframe;

		$component = str_replace('com_', '', $this->option);

		$view = $component.'View';

		if(class_exists($view)) {
			$this->_view = 	new $view($this->_mainframe) ;
		}

	}
}
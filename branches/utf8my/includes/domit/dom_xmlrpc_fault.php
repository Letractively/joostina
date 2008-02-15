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
* dom_xmlrpc_fault is a representation of an XML-RPC fault
* @package dom-xmlrpc
* @copyright (C) 2004 John Heinstein. All rights reserved
* @license http://www.gnu.org/copyleft/lesser.html LGPL License
* @author John Heinstein <johnkarl@nbnet.nb.ca>
* @link http://www.engageinteractive.com/dom_xmlrpc/ DOM XML-RPC Home Page
* DOM XML-RPC is Free Software
**/

/**
* A representation of an XML-RPC fault
*
* @package dom-xmlrpc
* @author John Heinstein <johnkarl@nbnet.nb.ca>
*/
class dom_xmlrpc_fault {
	/** @var int The fault code */
	var $faultCode;
	/** @var string A string description of the fault code */
	var $faultString;

	/**
	* Constructor: instantiates the fault object
	* @param int The fault code
	* @param string The fault string
	*/
	function dom_xmlrpc_fault($faultCode, $faultString) {
		$this->faultCode = $faultCode;
		$this->faultString = $faultString;
	} //dom_xmlrpc_fault

	/**
	* Returns the fault code
	* @return int The fault code
	*/
	function getFaultCode() {
		return $this->faultCode;
	} //getFaultCode

	/**
	* Returns the fault string
	* @return int The fault string
	*/
	function getFaultString() {
		return $this->faultString;
	} //getFaultStringltCode
} //dom_xmlrpc_fault

?>

<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008-2009 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*
* dom_xmlrpc_array_document wraps a PHP array with the DOM XML-RPC API
* @package dom-xmlrpc
* @copyright (C) 2004 John Heinstein. All rights reserved
* @license http://www.gnu.org/copyleft/lesser.html LGPL License
* @author John Heinstein <johnkarl@nbnet.nb.ca>
* @link http://www.engageinteractive.com/dom_xmlrpc/ DOM XML-RPC Home Page
* DOM XML-RPC is Free Software
**/

defined('_VALID_MOS') or die();
if(!defined('DOM_XMLRPC_INCLUDE_PATH')) {
	define('DOM_XMLRPC_INCLUDE_PATH',(dirname(__file__)."/"));
}
require_once (DOM_XMLRPC_INCLUDE_PATH.'dom_xmlrpc_builder.php');
class dom_xmlrpc_methodresponse_fault extends dom_xmlrpc_builder {
	var $faultCode;
	var $faultString;
	function dom_xmlrpc_methodresponse_fault($fault = null) {
		require_once (DOM_XMLRPC_INCLUDE_PATH.'dom_xmlrpc_constants.php');
		require_once (DOM_XMLRPC_INCLUDE_PATH.'dom_xmlrpc_utilities.php');
		$this->methodType = DOM_XMLRPC_TYPE_METHODRESPONSE;
		if($fault != null) {
			$this->setFaultCode($fault->getFaultCode());
			$this->setFaultString($fault->getFaultString());
		}
	}

	function getFaultCode() {
		return $this->faultCode;
	}

	function setFaultCode($faultCode) {
		$this->faultCode = $faultCode;
	}

	function getFaultString() {
		return $this->faultString;
	}

	function setFaultString($faultString) {
		$this->faultString = $faultString;
	}

	function toString() {
		$data = <<< METHODRESPONSE_FAULT
<?xml version='1.0'?>
<methodResponse>
	<fault>
		<value>
			<struct>
				<member>
					<name>faultCode</name>
					<value><int>$this->faultCode</int></value>
				</member>
				<member>
					<name>faultString</name>
					<value><string>$this->faultString</string></value>
				</member>
			</struct>
		</value>
	</fault>
</methodResponse>
METHODRESPONSE_FAULT;
		return $data;
	}

	function toXML() {
		return $this->toString();
	}

}




?>

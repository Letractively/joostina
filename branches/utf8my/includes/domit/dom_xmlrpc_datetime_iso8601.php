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
//*******************************************************************
//iso8601 datetime class
//*******************************************************************
//by John Heinstein
//johnkarl@nbnet.nb.ca
//*******************************************************************
//Version 0.1
//copyright 2004 Engage Interactive
//http://www.engageinteractive.com/dom_xmlrpc/
//All rights reserved
//*******************************************************************
//Licensed under the GNU General Public License (GPL)
//http://www.gnu.org/copyleft/gpl.html
//*******************************************************************

class dom_xmlrpc_datetime_iso8601 {
	var $year;  var $month; var $day;
	var $hour; var $minute; var $second;

	function dom_xmlrpc_datetime_iso8601($datetime) {
		if (is_int($datetime)) {
			$this->fromDateTime_php($datetime);
		}
		else {
			$this->fromDateTime_iso($datetime);
		}
	} //dom_xmlrpc_datetime_iso8601

	function phpToISO(&$phpDate) {
		return (date('Y', $phpDate) . date('m', $phpDate) . date('d', $phpDate) .
					'T'. date('H', $phpDate). ':' . date('i', $phpDate) . ':' . date('s', $phpDate));
	} //phpToISO

	function fromDateTime_php($phpdatetime) {
		//input php date time
		$this->year = date('Y', $phpdatetime);
		$this->month = date('m', $phpdatetime);
		$this->day = date('d', $phpdatetime);
		$this->hour = date('H', $phpdatetime);
		$this->minute = date('i', $phpdatetime);
		$this->second = date('s', $phpdatetime);
	} //fromDateTime_php

	function fromDateTime_iso($isoFormattedString) {
		//input iso date time
		$this->year = substr($isoFormattedString, 0, 4);
		$this->month = substr($isoFormattedString, 4, 2);
		$this->day = substr($isoFormattedString, 6, 2);
		$this->hour = substr($isoFormattedString, 9, 2);
		$this->minute = substr($isoFormattedString, 12, 2);
		$this->second = substr($isoFormattedString, 15, 2);
	} //fromDateTime_iso

	function getDateTime_iso() {
		//return iso date time
		return ($this->year . $this->month . $this->day .
			'T'. $this->hour . ':' . $this->minute . ':' . $this->second);
	} //getDateTime_iso

	function getDateTime_php() {
		//return php date time
		return mktime($this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year);
	} //getDateTime_php
} //dom_xmlrpc_datetime_iso8601

?>

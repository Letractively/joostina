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
//*******************************************************************
//php_http_status_codes list the http static codes as constants
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
class php_http_status_codes {
	var $codes;

	function php_http_status_codes() {
		$this->codes = array(
			200 => 'OK',
			201 => 'CREATED',
			202 => 'Accepted',
			203 => 'Partial Information',
			204 => 'No Response',
			301 => 'Moved',
			302 => 'Found',
			303 => 'Method',
			304 => 'Not Modified',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'PaymentRequired',
			403 => 'Forbidden',
			404 => 'Not found',
			500 => 'Internal Error',
			501 => 'Not implemented',
			502 => 'Service temporarily overloaded',
			503 => 'Gateway timeout');
	} //php_http_status_codes

	function getCodes() {
		return $this->codes;
	} //getCodes

	function getCodeString($code) {
		return $this->codes[$code];
	} //getCodeString
} //class php_http_status_codes

?>

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

class Timer {
	var $startTime;
	var $stopTime;

	function start() {
		$this->startTime = microtime();
	} //start

	function stop() {
		$this->stopTime = microtime();
	} //stop

	function getTime() {
		return $this->elapsed($this->startTime, $this->stopTime);
	} //getTime

	function elapsed($a, $b) {
		list($a_micro, $a_int) = explode(' ',$a);
		list($b_micro, $b_int) = explode(' ',$b);

		if ($a_int > $b_int) {
			return ($a_int - $b_int) + ($a_micro - $b_micro);
		}
		else if ($a_int == $b_int) {
			if ($a_micro > $b_micro) {
				return ($a_int - $b_int) + ($a_micro - $b_micro);
			}
			else if ($a_micro<$b_micro) {
				return ($b_int - $a_int) + ($b_micro - $a_micro);
			}
			else {
				return 0;
			 }
		}
		else { // $a_int < $b_int
			return ($b_int - $a_int) + ($b_micro - $a_micro);
		}
	} //elapsed
} //Timer

?>

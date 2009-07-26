<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

class jdebug {
	/* стек сообщений лога*/
	var $_log = array();
	/* буфер сообщений лога*/
	var $text = null;
	/* счетчики */
	var $_insc = array();

	function getInstance(){
		static $instance;
		if (!is_object( $instance )) {
			$instance = new jdebug();
		}
		return $instance;
	}

	/* добавление сообщения в лог*/
	function add($text) {
		$this->_log[] = $text;
	}

	/* добавление сообщения в лог*/
	function insc($key) {
		if(!isset($this->_insc[$key])){
			$this->_insc[$key] = 0;
		}
		$this->_insc[$key] ++;
	}

	
	/* вывод сообщений из лога*/
	function get($db = 1) {
		$database = &database::getInstance();

		$this->add('<b>'._INCLUDED_FILES.':</b> '.count(get_included_files()));
		if($db){
			$this->_db();
		}else{
			$this->add(_SQL_QUERIES_COUNT.': '.count($database->_log));
		}

		/* счетчики */
		foreach($this->_insc as $key => $value) {
			$this->text .= 'INSC: <b>'.$key.'</b>: '.$value.'<br />';
		}
		/* лог */
		foreach($this->_log as $key => $value) {
			$this->text .= $value.'<br />';
		}

		echo '<noindex><div id="jdebug">'.$this->text.'</div></noindex>';
	}

	/* отладка sql запросов базы данных*/
	function _db() {
		$database = &database::getInstance();

		count($database->_log);
		$this->add('<b>SQL:</b> '.count($database->_log).'<pre>');
		foreach($database->_log as $k => $sql) {
			$this->add($k + 1 . ': '.$sql.'<hr />');
		}
		$this->add('</pre>');
		return;
	}
}
;
/* упрощенная процедура добавления сообщения в лог*/
function jd_log($text) {
	$debug = &jdebug::getInstance();
	$debug->add($text);
}
/* счетчики вызывов */
function jd_insc($name='counter'){
	$debug = &jdebug::getInstance();
	$debug->insc($name);
}

function jd_get(){
	$debug = &jdebug::getInstance();
	echo $debug->get();
}

?>

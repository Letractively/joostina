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

class jdebug {
	/* стек сообщений лога */
	var $_log = array();
	/* буфер сообщений лога */
	var $text = null;
	/* добавление сообщения в лог */
	function add($text){
		$this->_log[] = $text;
	}
	/* вывод сообщений из лога */
	function get($db=1){
		if($db) $this->_db();
		foreach( $this->_log as $key => $value ) {
			$this->text .= $value.'<br />';
		}
		echo '<noindex><div id="jdebug">'.$this->text.'</div></noindex>';
	}
	/* отладка sql запросов базы данных */
	function _db(){
		global $database;
		$this->add('<b>SQL запросов:</b> '.count($database->_log).'<pre>');
		foreach ($database->_log as $k=>$sql) {
			$this->add($k+1 .': '.$sql.'<hr />');
		}
		$this->add('</pre>');
		return;
	}

};
/* упрощенная процедура добавления сообщения в лог */
function jlog($text){
	global $debug;
	if(!isset($debug)) $debug = new jdebug();
	$debug->add($text);
}

?>

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
	var $_inc = array();

	function getInstance(){
		static $instance;
		if (!is_object( $instance )) {
			$instance = new jdebug();
		}
		return $instance;
	}

	/* добавление сообщения в лог*/
	function add($text,$top=0) {
		$top ? array_unshift($this->_log,$text) : $this->_log[] = $text;
	}

	/* добавление сообщения в лог*/
	function inc($key) {
		if(!isset($this->_inc[$key])){
			$this->_inc[$key] = 0;
		}
		$this->_inc[$key] ++;
	}

	
	/* вывод сообщений из лога*/
	function get() {
		echo '<del><![CDATA[<noindex>]]></del><pre>';
		/* счетчики */
		foreach($this->_inc as $key => $value) {
			$this->text .= '<small class="debug_counter">COUNTER:</small> <b>'.htmlentities($key).'</b>: '.$value.'<br />';
		}

		/* лог */
		$this->text .= '<ul class="debug_log">';
		foreach($this->_log as $key => $value) {
			$this->text .= '<li><small>LOG:</small> '.$value.'</li>';
		}
		$this->text .= '</ul>';

		$this->text .=$this->db_debug();

		/* подключенные файлы */
		$files = get_included_files();
		$f = array();
		$f[]='<div onclick="$(\'#_sql_debug_file\').toggle();" style="cursor: pointer;border-bottom:1px solid #CCCCCC;border-top:1px solid #CCCCCC;">'._INCLUDED_FILES.': '.count($files).'</div>';
		$f[]='<div id="_sql_debug_file" style="display:none">';
		foreach($files as $key => $value) {
			$f[]= '<small>'.$key.':</small> '.$value.'<br />';
		}
		$f[]='</div>';

		$this->text .=implode('',$f);
		unset($f);
		echo '<div id="jdebug">'.$this->text.'</div>';
		echo '</pre><del><![CDATA[</noindex>]]></del>';
	}

	function db_debug(){
		$database = &database::getInstance();
		$database->setQuery('show profiles;');
		$profs = $database->loadObjectList();
		$r = array();
		$r[]='<div onclick="$(\'#_sql_debug_log\').toggle();" style="cursor: pointer;border-bottom:1px solid #CCCCCC;border-top:1px solid #CCCCCC;">SQL: '.count($profs).'</div>';
		$r[]='<table id="_sql_debug_log" style="display:none"><tr><th colspan="3"></th></tr>';

		foreach($profs as $prof){
			$r[]='<tr valign="top"><td>#'.$prof->Query_ID.' </td><td> '.$prof->Duration.' </td><td> '.$prof->Query.' </td></tr>';
		}
		$r[]='</table>';
		return implode('',$r);

	}

}
/* упрощенная процедура добавления сообщения в лог */
function jd_log($text) {
	$debug = &jdebug::getInstance();
	$debug->add($text);
}
/* упрощенная процедура добавления сообщения в начало лога */
function jd_log_top($text) {
	$debug = &jdebug::getInstance();
	$debug->add($text,1);
}

/* счетчики вызывов */
function jd_inc($name='counter'){
	$debug = &jdebug::getInstance();
	$debug->inc($name);
}

function jd_get(){
	$debug = &jdebug::getInstance();
	echo $debug->get();
}
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
DEFINE('_MULTISITE', '1');

class MultiSite {

	var $flag = null;
	var $main_site = null;
	var $table_preffix = null;
	var $cookie_domen = null;
	var $remote_tables = array('users', 'session');
	var $remote_tables_admin = array('users');

	function MultiSite() {

	}

	function _init($flag, $main_site, $table_preffix, $cookie_domen, $remote_tables, $remote_tables_admin) {

		$this->flag = $flag;
		$this->main_site = $main_site;
		$this->table_preffix = $table_preffix;
		$this->cookie_domen = $cookie_domen;
		$this->remote_tables = $remote_tables;
		$this->remote_tables_admin = $remote_tables_admin;
	}
}
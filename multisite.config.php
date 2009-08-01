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

	$mainframe->set('_multisite', 1);
	$cookie_exist = 0;

	$m_s = new stdClass(); 
	$m_s->main_site = '';
	$m_s->db_host = 'localhost';
	$m_s->db_name = '';
	$m_s->db_user = 'root';
	$m_s->db_pass = '';
	$m_s->table_preffix = '';
	
	if(isset($_COOKIE[mosMainFrame::sessionCookieName($m_s->main_site)])){
		$cookie_exist = 1;	
	}
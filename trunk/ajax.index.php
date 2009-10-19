<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// Установка флага, что это - родительский файл
define('_VALID_MOS',1);
// корень файлов
define('JPATH_BASE', dirname(__FILE__) );
// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR );

require (JPATH_BASE.'/includes/globals.php');
require_once ('./configuration.php');
// live_site
define('JPATH_SITE', $mosConfig_live_site );
// для совместимости
$mosConfig_absolute_path = JPATH_BASE;

require_once ('includes/joomla.php');

// отображение состояния выключенного сайта
if($mosConfig_offline == 1) {
	echo 'syte-offline';
	exit();
}

if(file_exists(JPATH_BASE.'/components/com_sef/sef.php')) {
	require_once (JPATH_BASE.'/components/com_sef/sef.php');
} else {
	require_once (JPATH_BASE.'/includes/sef.php');
}

// автоматическая перекодировка в юникод, по умолчанию актвино
$utf_conv	= intval(mosGetParam($_REQUEST,'utf',1));
$option		= strval(strtolower(mosGetParam($_REQUEST,'option','')));
$task		= strval(mosGetParam($_REQUEST,'task',''));

$commponent = str_replace('com_','',$option);

if($mosConfig_mmb_system_off == 0) {
	$_MAMBOTS->loadBotGroup('system');
	$_MAMBOTS->trigger('onAjaxStart');
}

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = &mosMainFrame::getInstance();
//Межсайтовая интеграция
if(is_file(JPATH_BASE.DS.'multisite.config.php')){
	include_once(JPATH_BASE.DS.'multisite.config.php');
}

$mainframe->initSession();


// загрузка файла русского языка по умолчанию
if($mosConfig_lang == '') {
	$mosConfig_lang = 'russian';
}
$mainframe->set('lang', $mosConfig_lang);
include_once($mainframe->getLangFile());

// get the information about the current user from the sessions table
if($mainframe->get('_multisite')=='2' && $cookie_exist ){
	$mainframe->set('_multisite_params', $m_s);
	$my = $mainframe->getUser_from_sess($_COOKIE[mosMainFrame::sessionCookieName($m_s->main_site)]);
}
else{
	$my = $mainframe->getUser();
}
$gid = intval($my->gid);

if($mosConfig_mmb_system_off == 0) {
	$_MAMBOTS->trigger('onAfterAjaxStart');
}

header("Content-type: text/html; charset=utf-8");
header ("Cache-Control: no-cache, must-revalidate ");

// проверяем, какой файл необходимо подключить, данные берутся из пришедшего GET запроса
if(file_exists(JPATH_BASE . "/components/$option/$commponent.ajax.php")) {
	include_once (JPATH_BASE . "/components/$option/$commponent.ajax.php");
} else {
	die('error-1');
}
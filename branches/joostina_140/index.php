<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// Установка флага родительского файла
define('_VALID_MOS',1);

// корень файлов
define('JPATH_BASE', dirname(__FILE__) );

// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR );

// рассчет памяти
if(function_exists('memory_get_usage')) {
	define('_MEM_USAGE_START', memory_get_usage());
}

// подключение файла конфигурации
require_once (JPATH_BASE.DS.'configuration.php');

// отображение страницы выключенного сайта
$mosConfig_offline ? require (JPATH_BASE.DS.'templates'.DS.'system'.DS.'offline.php') : null;

// live_site
define('JPATH_SITE', $mosConfig_live_site );

// для совместимости
$mosConfig_absolute_path = JPATH_BASE;

// считаем время за которое сгенерирована страница
$mosConfig_time_generate ? $sysstart = microtime(true) : null;

// подключение главного файла - ядра системы
require_once ( JPATH_BASE.DS.'includes'.DS.'joomla.php' );
require_once ( JPATH_BASE.DS.'includes'.DS.'sef.php' );
require_once ( JPATH_BASE.DS.'includes'.DS.'frontend.php' );


// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = &mosMainFrame::getInstance();
$option = $mainframe->option;

//_xdump($mainframe);
//exit();

// отключение ведения сессий на фронте
($mosConfig_no_session_front == 0) ? $mainframe->initSession() : null;

// загрузка файла русского языка по умолчанию
$mosConfig_lang = ($mosConfig_lang == '') ? 'russian' : $mosConfig_lang;
$mainframe->set('lang', $mosConfig_lang);
include_once($mainframe->getLangFile('',$mosConfig_lang));

// контроль входа и выхода в фронт-энд
$return		   = strval(mosGetParam($_REQUEST,'return',null));
$message	= intval(mosGetParam($_POST,'message',0));

// текущий пользователь
$my = $mainframe->getUser();

/*** * @global - Места для хранения информации обработки компонента*/
$_MOS_OPTION = array();

// начало буферизации основного содержимого
ob_start();

$path = $mainframe->getPath('front');
if( $path ) {
	//Подключаем язык компонента
	if($mainframe->getLangFile($option)) {
		require_once($mainframe->getLangFile($option));
	}
	require_once ($path);
} else {
	header('HTTP/1.0 404 Not Found');
	// сюда добавить вызов 404 страницы
	echo _NOT_EXIST;
}
$_MOS_OPTION['buffer'] = ob_get_contents(); // главное содержимое - стек вывода компонента - mainbody
ob_end_clean();

initGzip();

header('Content-type: text/html; charset=UTF-8');
// при активном кэшировании отправим браузеру более "правильные" заголовки
/*
if(!$mosConfig_caching) { // не кэшируется
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0',false);
	header('Pragma: no-cache');
} elseif($option != 'logout' or $option != 'login') { // кэшируется
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Expires: '.gmdate('D, d M Y H:i:s',time() + 3600).' GMT');
	header('Cache-Control: max-age=3600');
}
*/


// загрузка файла шаблона
require_once (JPATH_BASE.'/templates/'.JTEMPLATE.'/index.php');

// подсчет времени генерации страницы
echo $mosConfig_time_generate ? '<div id="time_gen">'.round((microtime(true) - $sysstart),5).'</div>' : null;


// вывод лога отладки
if($mosConfig_debug) {
	if(defined('_MEM_USAGE_START')) {
		$mem_usage = (memory_get_usage() - _MEM_USAGE_START);
		jd_log_top('<b>'._SCRIPT_MEMORY_USING.':</b> '.sprintf('%0.2f',$mem_usage / 1048576).' MB');
	}
	jd_get();
}

doGzip();

// запускаем встроенный оптимизатор таблиц
($mosConfig_optimizetables == 1) ? joostina_api::optimizetables():null;
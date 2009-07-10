<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// Установка флага, что этот файл - родительский
define('_VALID_MOS',1);

if(!file_exists('../configuration.php')) {
	header('Location: ../installation/index.php');
	exit();
}

// корень админки
$root = dirname(dirname(__FILE__));
define('ADMIN_ROOT',$root);

require_once (ADMIN_ROOT.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'globals.php');
require_once (ADMIN_ROOT.DIRECTORY_SEPARATOR.'configuration.php');


// SSL проверка  - $http_host returns <live site url>:<port number if it is 443>
$http_host = explode(':',$_SERVER['HTTP_HOST']);
if((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off' || isset($http_host[1]) && $http_host[1] == 443) && substr($mosConfig_live_site,0,8) !='https://') {
	$mosConfig_live_site = 'https://' . substr($mosConfig_live_site,7);
}

require_once ($mosConfig_absolute_path .DIRECTORY_SEPARATOR. 'includes'.DIRECTORY_SEPARATOR.'joomla.php');

$database = database::getInstance();

// работа с сессиями начинается до создания главного объекта взаимодействия с ядром
session_name(md5($mosConfig_live_site));
session_start();

// получение основных параметров
$option		= strval(strtolower(mosGetParam($_REQUEST,'option','')));
$task		= strval(mosGetParam($_REQUEST,'task',''));
$act		= strtolower(mosGetParam($_REQUEST,'act',''));
$section	= mosGetParam($_REQUEST,'section','');
$no_html	= intval(mosGetParam($_REQUEST,'no_html',0));
$id			= intval(mosGetParam($_REQUEST,'id',0));

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = mosMainFrame::getInstance(true);
$mainframe->set('lang', $mosConfig_lang);
//include_once ($mosConfig_absolute_path .DS.'language' .DS. $mosConfig_lang . '.php');
include_once($mainframe->getLangFile());

require_once ($mosConfig_absolute_path.DS.ADMINISTRATOR_DIRECTORY.DS.'includes'.DS.'admin.php');


// запуск сессий панели управления
$my = $mainframe->initSessionAdmin($option,$task);

// получаем название шаблона для панели управления
$cur_template = $mainframe->getTemplate();
// установка параметра overlib
$mainframe->set('loadOverlib',false);

// страница панели управления по умолчанию
if($option == '') {
	$option = 'com_admin';
}


// инициализация редактора
$mainframe->set( 'allow_wysiwyg', 1 );  
require_once ($mosConfig_absolute_path . '/includes/editor.php');

ob_start();
if($path = $mainframe->getPath('admin')) {
	//Подключаем язык компонента
 	if($mainframe->getLangFile($option)){ 
 		include_once($mainframe->getLangFile($option));        	
	}
	require_once ($path);
} else {
?>
	<img src="images/error.png" border="0" alt="Joostina!" />
<?php
}

$_MOS_OPTION['buffer'] = ob_get_contents();
ob_end_clean();

initGzip();
header('Content-type: text/html; charset=UTF-8');
// начало вывода html
if($no_html == 0) {
	// загрузка файла шаблона
	if(!file_exists($mosConfig_absolute_path . '/'.ADMINISTRATOR_DIRECTORY.'/templates/' . $cur_template .'/index.php')) {
		echo _TEMPLATE_NOT_FOUND.': ',$cur_template;
	} else {
		//Подключаем язык шаблона
		if($mainframe->getLangFile('tmpl_'.$cur_template)){include_once($mainframe->getLangFile('tmpl_'.$cur_template));}
		require_once ($mosConfig_absolute_path . '/'.ADMINISTRATOR_DIRECTORY.'/templates/' . $cur_template .'/index.php');
	}
} else {
	mosMainBody_Admin();
}

// информация отладки, число запросов в БД
if($mosConfig_debug) {
	echo _SQL_QUERIES_COUNT.': '. $database->_ticker;
	echo '<pre>';
	foreach($database->_log as $k => $sql) {
		echo $k + 1 . ":&nbsp;" . $sql . '<br /><br />';
	}
	echo '</pre>';
}

// восстановление сессий
if($task == 'save' || $task == 'apply' || $task == 'save_and_new' ) {
	$mainframe->initSessionAdmin($option,'');
}

doGzip();

?>

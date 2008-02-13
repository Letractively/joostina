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

// Установка флага, что этот файл - родительский
define( '_VALID_MOS', 1 );

if (!file_exists( '../configuration.php' )) {
	header( 'Location: ../installation/index.php' );
	exit();
}

require( '../globals.php' );
require_once( '../configuration.php' );

// SSL проверка  - $http_host returns <live site url>:<port number if it is 443>
$http_host = explode(':', $_SERVER['HTTP_HOST'] );
if( (!empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) != 'off' || isset( $http_host[1] ) && $http_host[1] == 443) && substr( $mosConfig_live_site, 0, 8 ) != 'https://' ) {
	$mosConfig_live_site = 'https://'.substr( $mosConfig_live_site, 7 );
}

require_once( $mosConfig_absolute_path . '/includes/joomla.php' );
include_once( $mosConfig_absolute_path . '/language/'. $mosConfig_lang .'.php' );
require_once( $mosConfig_absolute_path . '/administrator/includes/admin.php' );

// работа с сессиями начинается до создания главного объекта взаимодействия с ядром
session_name( md5( $mosConfig_live_site ) );
session_start();

$option	= strval( strtolower( mosGetParam( $_REQUEST, 'option', '' ) ) );
$task	= strval( mosGetParam( $_REQUEST, 'task', '' ) );

// создание объекта взаимодействия с ядром системы
$mainframe	= new mosMainFrame( $database, $option, '..', true );

// запуск сессий панели управления
$my			= $mainframe->initSessionAdmin( $option, $task );

// проверка на наличие в POST данных параметра отключения редактора - если есть - то заводится новый параметр сессии user_editor_off=1 - означающий что визуальный редактор отключен
if(intval( mosGetParam( $_POST, 'user_editor_off', null ) )) $_SESSION['user_editor_off'] = 1;
// проверка на включение визуального редактора, при наличии соответствующего параметра в POST запросе параметр отключения редактора в сессии обнуляется и редактор снова активируется
if(intval( mosGetParam( $_POST, 'user_editor_on', null ) )) $_SESSION['user_editor_off'] = 0;


// получение основных параметров
$act 		= strtolower( mosGetParam( $_REQUEST, 'act', '' ) );
$section	= mosGetParam( $_REQUEST, 'section', '' );
$no_html	= intval( mosGetParam( $_REQUEST, 'no_html', 0 ) );
$id			= intval( mosGetParam( $_REQUEST, 'id', 0 ) );

$cur_template = $mainframe->getTemplate();

// страница панели управления по умолчанию
if ($option == '') {
	$option = 'com_admin';
}

// установка параметра overlib 
$mainframe->set( 'loadOverlib', false );

// инициализация редактора
require_once( $mosConfig_absolute_path . '/editor/editor.php' );

ob_start();
if ($path = $mainframe->getPath( 'admin' )) {
		require_once ( $path );
} else {
	?>
	<img src="images/joomla_logo_black.jpg" border="0" alt="Joostina!" />
	<br />
	<?php
}

$_MOS_OPTION['buffer'] = ob_get_contents();
ob_end_clean();

initGzip();

// начало вывода html
if ($no_html == 0) {
	// загрузка файла шаблона
	if ( !file_exists( $mosConfig_absolute_path .'/administrator/templates/'. $cur_template .'/index.php' ) ) {
		echo 'ШАБЛОН '. $cur_template .' НЕ ОБНАРУЖЕН' ;
	} else {
		require_once( $mosConfig_absolute_path .'/administrator/templates/'. $cur_template .'/index.php' );
	}
} else {
	mosMainBody_Admin();
}

// информация отладки, число запросов в БД
if ($mosConfig_debug) {
	echo 'Запросов:&nbsp;' . $database->_ticker;
	echo '<pre>';
	foreach ($database->_log as $k=>$sql) {
		echo $k+1 . ":&nbsp;" . $sql . '<br /><br />';
	}
	echo '</pre>';
}

// восстановление сессий
if ( $task == 'save' || $task == 'apply' ) {
	$mainframe->initSessionAdmin( $option, '' );
}

doGzip();

?>

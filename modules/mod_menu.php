<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

# получаем объект конфигурации системы
$config = Jconfig::getInstance();

// используемое меню
$use_menu = $params->get( 'menutype', 'mainmenu' );

// тип вывода меню
$type_menu = $params->get( 'type_menu', 'standart' );

// разделитель пунктов меню
$spacer = $params->get( 'spacer', ' ');

# файл вывода меню
$file = $config->config_absolute_path.'/modules/mod_menu/'.$type_menu.'.php';

if(file_exists($file)){
	require_once $file;
}
unset($config,$params,$type_menu,$file,$use_menu);
?>

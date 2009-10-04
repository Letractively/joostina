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
$mainframe = &mosMainFrame::getInstance();

// используемое меню
$use_menu = $params->get( 'menutype', 'mainmenu' );

// разделитель пунктов меню
$spacer = $params->get( 'spacer', ' ');

// тип вывода меню
$type_menu = $params->get( 'type_menu', 'tree' );

//путь к шаблонам
$params->def('template', 'default.php');
$params->def('template_dir', 0);

$params->set('template', $type_menu.DS.$params->get('template'));
$module->get_helper();

//Подключаем шаблон
if($module->set_template($params)){
	require_once($module->template);
}

unset($config,$params,$type_menu,$file,$use_menu);
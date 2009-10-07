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

global $cur_template;

$use_ext = $params->get('use_ext',0);

if(!defined('_QUICKICON_MODULE')) {
	define('_QUICKICON_MODULE',1);
	if($use_ext){
		// использование значков отображения шаблона
		if(file_exists(JPATH_BASE.DS.ADMINISTRATOR_DIRECTORY.'/templates/'.$cur_template.'/html/quickicons.php')) {
			require_once (JPATH_BASE.DS.ADMINISTRATOR_DIRECTORY.'/templates/'.$cur_template.'/html/quickicons.php');
		} else {
			// использование стандартных значков отображения
			require_once (JPATH_BASE.DS.ADMINISTRATOR_DIRECTORY.'/components/com_quickicons/quickicons.php');
		}
	}else{
		// использование стандартных значков отображения
		require_once (JPATH_BASE.DS.ADMINISTRATOR_DIRECTORY.'/components/com_quickicons/quickicons.php');
	}
}
<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

switch($task) {
	case 'edit';
		mosMenuBar::startTable();
		mosMenuBar::ext('Применить','#','-apply','id="tb-apply" onclick="UpdateImg(xajax.getFormValues(\'adminForm\'))"');
		mosMenuBar::ext('Отменить всё','#','-unpublis','onclick="xajax_OriginalImage(xajax.getFormValues(\'adminForm\'));"');
		mosMenuBar::ext('Сохранить','#','-save','onclick="submitform(\'saveimage\');"');
		mosMenuBar::ext('Закрыть','#','-cancel','onclick="submitform(\'returnfromedit\');"');
		mosMenuBar::endTable();
		break;
	default;
	break;

}
?>

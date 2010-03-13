<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

$mosmsg = $mainframe->get_mosmsg();

echo count($mosmsg)>0 ? '<script language="JavaScript" type="text/javascript">$.jGrowl("'.implode('");$.jGrowl("', $mosmsg).'");</script>' : null;
<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/LICENSE.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для просмотра подробностей и замечаний об авторском праве, смотрите файл help/COPYRIGHT.php.
 *
 */

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

global $my;

require_once $my->id ? 'views/logout/default.php' : 'views/login/default.php';
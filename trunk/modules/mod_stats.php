<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

global $mosConfig_offset, $mosConfig_caching;
global $mosConfig_gzip;

$serverinfo = $params->get( 'serverinfo' );
$siteinfo	= $params->get( 'siteinfo' );

$content = '';

if ($serverinfo) {
	echo "<strong>OS:</strong> "  . substr(php_uname(),0,7) . "<br />\n";
	echo "<strong>PHP:</strong> " .phpversion() . "<br />\n";
	echo "<strong>MySQL:</strong> " .$database->getVersion() . "<br />\n";
	echo "<strong>"._TIME_STAT.": </strong> " .date("H:i",time()+($mosConfig_offset*60*60)) . "<br />\n";
	$c = $mosConfig_caching ? _YES:_NO;
	echo '<strong>'._CACHE.':</strong> ' . $c . '<br />';
	$z = $mosConfig_gzip ? _YES:_NO;
	echo '<strong>GZIP:</strong> ' . $z . '<br />';
}

if ($siteinfo) {
	$query="SELECT COUNT( id ) AS count_users FROM #__users";
	$database->setQuery($query);
	echo "<strong>"._MEMBERS_STAT.":</strong> " .$database->loadResult() . "<br />\n";

	$query="SELECT COUNT( id ) AS count_items FROM #__content";
	$database->setQuery($query);
	echo "<strong>"._NEWS_STAT.":</strong> ".$database->loadResult() . "<br />\n";
}
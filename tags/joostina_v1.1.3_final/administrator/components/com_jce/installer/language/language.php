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

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );

global $mainframe, $database;
$database->setQuery( "SELECT lang FROM #__jce_langs WHERE published= '1'" );
$lang = $database->loadResult();

$database->setQuery( "SELECT id as id, plugin as plugin FROM #__jce_plugins WHERE type = 'plugin'" );
$plugins = $database->loadObjectList();

require_once( $mainframe->getCfg('absolute_path') . '/administrator/components/com_jce/language/' . $lang . '.php' );

$backlink = '<a href="index2.php?option=com_jce&task=lang">'._JCE_LANG_BACK.'</a>';
HTML_installer::showInstallForm( _JCE_LANG_HEADING_INSTALL, $option, 'language', '', dirname(__FILE__), $backlink );
?>
<table class="content">
<?php
writableCell( 'administrator/components/com_jce/language' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/themes/advanced/langs' );
foreach( $plugins as $plugin ){
	if( file_exists( $mainframe->getCfg('absolute_path') . '/mambots/editors/jce/jscripts/tiny_mce/plugins/' . $plugin->plugin . '/langs' ) ){
		writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/' . $plugin->plugin . '/langs' );
	}
}
?>
</table>

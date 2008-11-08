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
class TOOLBAR_jx {

	function _DEFAULT() {
		$dir = mosGetParam($_SESSION, 'jx_'.$GLOBALS['file_mode'].'dir', '');
		mosMenuBar::startTable();
		mosMenuBar::ext('Копировать','#','-copy','id="tb-copy" onclick="javascript:Copy();"');
		mosMenuBar::ext('Переместить','#','-move','id="tb-move" onclick="javascript:Move();"');
		mosMenuBar::ext('Удалить','#','-delete','id="tb-delete" onclick="javascript:Delete();"');
		mosMenuBar::ext('Смена прав','#','-chmod','id="tb-chmod" onclick="javascript:Chmod();"');
		if(ini_get("file_uploads")) {
			mosMenuBar::ext('Загрузить',make_link("upload", $dir, null),'-upload','id="tb-upload"');
		}
		if(($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]) && !jx_isFTPMode()) {
			mosMenuBar::ext('Упаковать','#','-zip','id="tb-upload" onclick="javascript:Archive();"');
		}
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	function _NULL(){
		return true;
	}
}

?>

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

// ensure user has access to this function
//if(!$acl->acl_check('administration','install','users',$my->usertype,$element.'s','all')) {
//	mosRedirect('index2.php',_NOT_AUTH);
//}
global $mainframe;
require_once ($mainframe->getPath('installer_html','mambot'));
require_once ($mainframe->getPath('installer_class','mambot'));

switch($task) {
case 'remove':
	{
		//echo "<script>alert('sdssd');</script>";
		removeElement($client);
		js_menu_cache_clear();
		break;
	}
default:
	{
		//echo "<script>alert('sdssd');</script>";
		showInstalledMambots($option);
		js_menu_cache_clear();
		break;
	}
}
//showInstalledModules($option);


/**
*
* @param
*/
function removeElement($client) {
	josSpoofCheck(null, null, 'request');
	$cid = mosGetParam($_REQUEST,'cid',array(0));
	if(!is_array($cid)) {
		$cid = array(0);
	}

	$installer = new mosInstallerMambot();
	$result = false;
	if($cid[0]) {
		$result = $installer->uninstall($cid[0],$option,$client);
	}

	$msg = $installer->getError();

	mosRedirect($installer->returnTo('com_installer','module',$client),$result?_DELETE_SUCCESS.' '.$msg : _UNSUCCESS.' '.$msg);
}

function showInstalledMambots($_option) {
	global $database,$mosConfig_absolute_path;

	$query = "SELECT id, name, folder, element, client_id FROM #__mambots WHERE iscore = 0 ORDER BY folder, name";
	$database->setQuery($query);
	$rows = $database->loadObjectList();

	// path to mambot directory
	$mambotBaseDir = mosPathName(mosPathName($mosConfig_absolute_path)."mambots");

	$id = 0;
	$n = count($rows);
	for($i = 0; $i < $n; $i++) {
		$row = &$rows[$i];
		// xml file for module
		$xmlfile = $mambotBaseDir."/".$row->folder.'/'.$row->element.".xml";

		if(file_exists($xmlfile)) {
			$xmlDoc = new DOMIT_Lite_Document();
			$xmlDoc->resolveErrors(true);
			if(!$xmlDoc->loadXML($xmlfile,false,true)) {
				continue;
			}

			$root = &$xmlDoc->documentElement;

			if($root->getTagName() != 'mosinstall') {
				continue;
			}
			if($root->getAttribute("type") != "mambot") {
				continue;
			}

			$element = &$root->getElementsByPath('creationDate',1);
			$row->creationdate = $element?$element->getText():'';

			$element = &$root->getElementsByPath('author',1);
			$row->author = $element?$element->getText():'';

			$element = &$root->getElementsByPath('copyright',1);
			$row->copyright = $element?$element->getText():'';

			$element = &$root->getElementsByPath('authorEmail',1);
			$row->authorEmail = $element?$element->getText():'';

			$element = &$root->getElementsByPath('authorUrl',1);
			$row->authorUrl = $element?$element->getText():'';

			$element = &$root->getElementsByPath('version',1);
			$row->version = $element?$element->getText():'';
		}
	}

	HTML_mambot::showInstalledMambots($rows,$_option,$id,$xmlfile);
}
?>
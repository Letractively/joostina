<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

// ������ ������� �������
defined('_VALID_MOS') or die();

global $mainframe;
require_once ($mainframe->getPath('installer_html','language'));
require_once ($mainframe->getPath('installer_class','language'));

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
		viewLanguages('com_installer','');
		js_menu_cache_clear();
		break;
	}
}

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

	$installer = new mosInstallerLanguage();
	$result = false;
	if($cid[0]) {
		$result = $installer->uninstall($cid[0],$option,$client);
	}

	$msg = $installer->getError();

	mosRedirect($installer->returnTo('com_installer','language',$client),$result?_DELETE_SUCCESS.' '.$msg : _UNSUCCESS.' '.$msg);
}
/**
* Compiles a list of installed languages
*/
function viewLanguages($option) {
	global $languages;
	global $mainframe;
	global $mosConfig_lang,$mosConfig_absolute_path,$mosConfig_list_limit;

	$limit = $mainframe->getUserStateFromRequest("viewlistlimit",'limit',$mosConfig_list_limit);
	$limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart",'limitstart',0);

	// get current languages
	$cur_language = $mosConfig_lang;

	$rows = array();
	// Read the template dir to find templates
	$languageBaseDir = mosPathName(mosPathName($mosConfig_absolute_path)."language");

	$rowid = 0;

	$xmlFilesInDir = mosReadDirectory($languageBaseDir,'.xml$');

	$dirName = $languageBaseDir;
	foreach($xmlFilesInDir as $xmlfile) {
		// Read the file to see if it's a valid template XML file
		$xmlDoc = new DOMIT_Lite_Document();
		$xmlDoc->resolveErrors(true);
		if(!$xmlDoc->loadXML($dirName.$xmlfile,false,true)) {
			continue;
		}

		$root = &$xmlDoc->documentElement;

		if($root->getTagName() != 'mosinstall') {
			continue;
		}
		if($root->getAttribute("type") != "language") {
			continue;
		}

		$row = new StdClass();
		$row->id = $rowid;
		$row->language = substr($xmlfile,0,-4);
		$element = &$root->getElementsByPath('name',1);
		$row->name = $element->getText();

		$element = &$root->getElementsByPath('creationDate',1);
		$row->creationdate = $element?$element->getText():'Unknown';

		$element = &$root->getElementsByPath('author',1);
		$row->author = $element?$element->getText():'Unknown';

		$element = &$root->getElementsByPath('copyright',1);
		$row->copyright = $element?$element->getText():'';

		$element = &$root->getElementsByPath('authorEmail',1);
		$row->authorEmail = $element?$element->getText():'';

		$element = &$root->getElementsByPath('authorUrl',1);
		$row->authorUrl = $element?$element->getText():'';

		$element = &$root->getElementsByPath('version',1);
		$row->version = $element?$element->getText():'';

		// if current than set published
		if($cur_language == $row->language) {
			$row->published = 1;
		} else {
			$row->published = 0;
		}

		$row->checked_out = 0;
		$row->mosname = strtolower(str_replace(" ","_",$row->name));
		$rows[] = $row;
		$rowid++;
	}

	require_once ($GLOBALS['mosConfig_absolute_path'].'/'.ADMINISTRATOR_DIRECTORY.'/includes/pageNavigation.php');
	$pageNav = new mosPageNav(count($rows),$limitstart,$limit);

	$rows = array_slice($rows,$pageNav->limitstart,$pageNav->limit);

	HTML_language::showLanguages($cur_language,$rows,$pageNav,$option);
}
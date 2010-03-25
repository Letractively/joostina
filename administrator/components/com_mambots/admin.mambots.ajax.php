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
global $my;

$acl = gacl::getInstance( true );

if(!($acl->acl_check('administration','edit','users',$my->usertype,'modules','all') | $acl->acl_check('administration','install','users',$my->usertype,'modules','all'))) {
	die('error-acl');
}

$task = mosGetParam($_GET,'task','publish');
$id = intval(mosGetParam($_GET,'id','0'));

$obj_id = intval(mosGetParam($_POST, 'obj_id', false));

switch($task) {

	case "publish":
		echo x_publish($obj_id);
		return;

	case "access":
		echo x_access($id);
		return;

	case "apply":
		echo x_apply();
		return;

	default:
		echo 'error-task';
		return;
}


/**
 * Saves the module after an edit form submit
 */
function x_apply() {
	global $database;
	josSpoofCheck();
	$params = mosGetParam($_POST,'params','');
	$client = strval(mosGetParam($_REQUEST,'client',''));

	if(is_array($params)) {
		$txt = array();
		foreach($params as $k => $v) {
			$txt[] = "$k=$v";
		}
		$_POST['params'] = mosParameters::textareaHandling($txt);
	}

	$row = new mosMambot($database);


	if(!$row->bind($_POST)) return 'error-bind';
	if(!$row->check()) return 'error-check';
	if(!$row->store()) return 'error-store';
	if(!$row->checkin()) return 'error-checkin';

	if($client == 'admin') {
		$where = "client_id='1'";
	} else {
		$where = "client_id='0'";
	}
	$row->updateOrder("folder = ".$database->Quote($row->folder)." AND ordering > -10000 AND ordering < 10000 AND ( $where )");
	$msg = sprintf(_COM_MAMBOTS_APPLY,$row->name);
	return $msg;
}


function x_access($id) {
	global $database;
	$access = mosGetParam($_GET,'chaccess','accessregistered');
	$option = strval(mosGetParam($_REQUEST,'option',''));
	switch($access) {
		case 'accesspublic':
			$access = 0;
			break;
		case 'accessregistered':
			$access = 1;
			break;
		case 'accessspecial':
			$access = 2;
			break;
		default:
			$access = 0;
			break;
	}
	$row = new mosMambot($database);
	$row->load((int)$id);
	$row->access = $access;

	if(!$row->check()) return 'error-check';
	if(!$row->store()) return 'error-store';

	if(!$row->access) {
		$color_access = 'style="color: green;"';
		$task_access = 'accessregistered';
		$text_href = _USER_GROUP_ALL;
	} elseif($row->access == 1) {
		$color_access = 'style="color: red;"';
		$task_access = 'accessspecial';
		$text_href = _USER_GROUP_REGISTERED;
	} else {
		$color_access = 'style="color: black;"';
		$task_access = 'accesspublic';
		$text_href = _USER_GROUP_SPECIAL;
	}
	return '<a href="#" onclick="ch_access('.$row->id.',\''.$task_access.'\',\''.$option.'\')" '.$color_access.'>'.$text_href.'</a>';
}

function x_publish($id = null) {
    $database = database::getInstance();

    if (!$id) return 'error-id';

    $mambot = new mosMambot($database);
    $mambot->load($id);

    // пустой объект для складирования результата
    $return_onj = new stdClass();

    // меняем состояние объекта на противоположное
    if ( $mambot->changeState('published')) {
        // формируем ответ из противоположных элементов текущему состоянию
        $return_onj->image = $mambot->published ?  'publish_x.png' : 'publish_g.png';
        $return_onj->mess = $mambot->published ?  _UNPUBLISHED : _PUBLISHED;
        mosCache::cleanCache('com_content');
    } else {
        // формируем резульлтат с ошибкой
        $return_onj->image = 'error.png';
        $return_onj->mess = 'error-class';
    }
    return json_encode($return_onj);
}
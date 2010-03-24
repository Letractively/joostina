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

$acl = gacl::getInstance(true);

if (!$acl->acl_check('administration', 'manage', 'users', $my->usertype, 'components', 'com_users')) {
    die('error-acl');
}

$task = mosGetParam($_REQUEST, 'task', '');
$id = intval(mosGetParam($_REQUEST, 'id', '0'));

$obj_id = intval(mosGetParam($_POST, 'obj_id', false));

switch ($task) {

    case 'publish':
        echo x_user_block($obj_id);
        return;

    case 'apply':
        echo x_apply();
        return;

    case 'upload_avatar':
        echo upload_avatar();
        return;

    case 'del_avatar':
        echo x_delavatar();
        return;


    default:
        echo 'error-task';
        return;
}

function upload_avatar() {
    global $my;
    $id = intval(mosGetParam($_REQUEST, 'id', 0));

    $database = database::getInstance();

    mosMainFrame::addLib('images');

    $return = array();

    $resize_options = array(
        'method' => '0',        //Приводит к заданной ширине, сохраняя пропорции.
        'output_file' => '',    //если 'thumb', то ресайзенная копия ляжет в подпапку "thumb'
        'width' => '150',
        'height' => '150'
    );

    $file = new Image();
    $file->field_name = 'avatar';
    $file->directory = 'images/avatars';
    $file->file_prefix = 'av_';
    $file->max_size = 0.5 * 1024 * 1024;

    $foto_name = $file->upload($resize_options);

    if ($foto_name) {
        if ($id) {
            $user = new mosUser($database);
            $user->load((int) $id);
            $user_id = $user->id;
            if ($user->avatar != '') {
                $foto = new Image();
                $foto->directory = 'images/avatars';
                $foto->name = $user->avatar;
                $foto->delFile($foto);
            }
            $user->update_avatar($id, $foto_name);
        }

        echo $foto_name;
    } else {
        return false;
    };
}

function x_delavatar() {
    $database = database::getInstance();

    $file_name = mosGetParam($_REQUEST, 'file_name', '');

    $user = new mosUser($database);
    $user->update_avatar(null, $file_name, 1);

    echo 'none.jpg';
}

// блокировка пользователя
function x_user_block($id) {
    global $my;

    $database = database::getInstance();

    $ret_img = 'error.png';

    if ($my->id != $id) {
        $user = new mosUser($database);
        $user->load($id);

         $ret_img = $user->block ?  'publish_g.png' : 'publish_x.png';
        
        $ret_img = $user->changeState('block') ? $ret_img : 'error.png';

        // всех кто не я и не суперадмин - блокируем
        if ($my->id != $id && $user->gid != 25) {
            // удаляем сессию авторизованного пользователя
            $query = "DELETE FROM #__session WHERE userid = $id";
            $database->setQuery($query)->query();
        }
    }
    $return_onj = new stdClass();
    $return_onj->image = $ret_img;
    $return_onj->mess = _USER_BLOCK;

    return json_encode($return_onj);
}

function img_resize($src, $dest, $width = 250, $height = 250, $quality = 100) {
    if (!file_exists($src)) return false;
    $size = getimagesize($src);
    list($width_orig, $height_orig) = $size;

    if ($size === false) return false;
    $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
    $icfunc = "imagecreatefrom" . $format;
    if (!function_exists($icfunc)) return false;
    $ratio_orig = $width_orig / $height_orig;

    if ($width / $height > $ratio_orig) {
        $width = $height * $ratio_orig;
    } else {
        $height = $width / $ratio_orig;
    }

    $isrc = $icfunc($src);
    $idest = imagecreatetruecolor($width, $height);
    imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
    imagejpeg($idest, $dest, $quality);
    imagedestroy($isrc);
    imagedestroy($idest);
    return true;
}

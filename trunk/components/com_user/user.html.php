<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
* @package Joostina
* @subpackage Users
*/
class HTML_user {
	function frontpage() {
?>
	<div class="componentheading"><?php echo _WELCOME; ?></div>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td><?php echo _WELCOME_DESC; ?></td>
		</tr>
	</table>
<?php
	}

function profile($user,$option, &$params){
      	global $mosConfig_absolute_path,$mosConfig_frontend_userparams,$mosConfig_live_site, $my, $database;
        	mosCommonHTML::loadFullajax();

        $owner=0;  $admin = 0;
        if($my->user_type = 'Super Administrator'){
             $admin = 1;
        }
        if($my->id && $user->id==$my->id){
          $owner=1;
          $editable=' editable';
          $edit_info_link=sefRelToAbs('index.php?option=com_user&task=UserDetails&Itemid=17');
          $avatar_pic='<img class="avatar" src="'.$mosConfig_live_site.mosUser::avatar($user->id,'big').'" />';
        }

        else{
          $editable='';
          $avatar_pic=' <img class="avatar" src="'.$mosConfig_live_site.mosUser::avatar($user->id, 'big').'" /> ';
          $avatar_edit='';
        }

        $user_id= $user->id;

        //Переменные для шаблона
        $user_real_name = $user->name;
        $user_nickname = $user->username;

        $user_status = $user->get_user_status($user->id);
        if($user_status){
            $user_status='<span class="online">на сайте</span>';
            }
        else {
             $user_status='<span class="offline">отсутствует</span>';
        }

        $registerDate = mosFormatDate($user->registerDate);
        $lastvisitDate = mosFormatDate($user->lastvisitDate);

        $user_content_href=sefRelToAbs('index.php?option=com_content&task=mycontent&user='.$user_id.'&Itemid=28');

        switch($user->usertype){
          case 'Registered':
          default:
            $template_file='registered_profile.php';
            break;

          case 'Editor':
            $template_file='editor_profile.php';
            break;

          case 'Author':
            $template_file='author_profile.php';
            break;

          case 'Super Administrator':
            $template_file='superadmin_profile.php';
            break;
        }

        if(!is_file($mosConfig_absolute_path.'/components/com_user/view/profile/'.$template_file)){
            $template_file='default.php';
        }

        //Определяем плагин
        $view=mosGetParam( $_REQUEST, 'view', '' );
        if($view){
            $plugin_file = $mosConfig_absolute_path.'/components/com_user/plugins/user_'.$view.'/user_'.$view.'.php';
            if (file_exists($plugin_file)) {
	            $plugin_page =  $plugin_file;
            } else {
                $view = 'info';
                $plugin_page =  $mosConfig_absolute_path.'/components/com_user/plugins/user_info.php';
            }
        } else{
            $view = 'info';
            $plugin_page =  $mosConfig_absolute_path.'/components/com_user/plugins/user_info.php';
        }

        include ($mosConfig_absolute_path.'/components/com_user/view/profile/'.$template_file);
    }

	function userEdit($user,$option,$submitvalue,&$params) {
		global $mosConfig_absolute_path,$mosConfig_frontend_userparams,$mosConfig_live_site;
		require_once ($mosConfig_absolute_path.'/includes/HTML_toolbar.php');
		// used for spoof hardening
		$validate = josSpoofValue();
		mosCommonHTML::loadFullajax();
        $tabs = new mosTabs(1);
		mosCommonHTML::loadOverlib();

        $user_extra = $user->user_extra;
        $bday_date = mosFormatDate($user_extra->birthdate, '%d', '0') ;
        $bday_month = mosFormatDate($user_extra->birthdate, '%m', '0') ;
        $bday_year = mosFormatDate($user_extra->birthdate, '%Y', '0') ;

        define('_MALE', 'Мужской');
        define('_FEMALE', 'Женский');

        include ($mosConfig_absolute_path.'/components/com_user/view/edit/default.php');

	}

	function confirmation() {
?>
	<div class="componentheading"><?php echo _SUBMIT_SUCCESS; ?></div>
	<table>
		<tr>
			<td><?php echo _SUBMIT_SUCCESS_DESC; ?></td>
		</tr>
	</table>
<?php
 }

}
?>

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

function profile($user,$option, &$params, $config){
      	global $mosConfig_absolute_path,$mosConfig_frontend_userparams,$mosConfig_live_site, $my, $database, $_MAMBOTS;

        $owner=0;  $admin = 0;
        if($my->user_type = 'Super Administrator'){
             $admin = 1;
        }
        if($my->id && $user->id==$my->id){
          $owner=1;
          $editable=' editable';
          $edit_info_link=sefRelToAbs('index.php?option=com_users&task=UserDetails&Itemid=17');
          
        }

        else{
          $editable='';
          $avatar_edit='';
        }
        
        //Переменные для шаблона
        $avatar_pic = '<img class="avatar" src="'.$mosConfig_live_site.'/'.$user->get_avatar($user).'" />';
        $user_id= $user->id;
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

		//Шаблон
		$template_file='default.php';
		if(!$config->get('template')){			
			if(is_file($mosConfig_absolute_path.'/components/com_users/view/profile/'.strtolower(str_replace(' ', '', $user->usertype )).'.php')){
            	$template_file=strtolower(str_replace(' ', '', $user->usertype )).'.php';
        	}	
		}
		
		
		//Находим плагины профиля пользователя
		$plugins = new userPlugins();
		$profile_plugins = $plugins->get_plugins('profile');
		
		$plugin_page = '';
		$cur_plugin = mosGetParam( $_REQUEST, 'view', '' );
		//Если плагины установлены 
		if($profile_plugins){
			
			//выцепляем первый плагин в группе как плагин по-умолчанию
			$plugin_page = $profile_plugins[0]->element;
			
			
   			//Обращение к странице плагина        	
        	if($cur_plugin){         	
        		//Проверяем запрашиваемый плагин на доступность
        		if($plugins->allow_plugin($cur_plugin)){
        			$plugin_page = $cur_plugin; 
        		}        	
   			}
   			else{
				$cur_plugin = $plugin_page; 	
   			}   			
   			//подключаем плагин
   			$_MAMBOTS->loadBot('profile',$plugin_page,1);		
		
		}
		//$user->profile_plugins = $profile_plugins;
		//$user->current_plugin = $plugin->get_current_plugin($profile_plugins);
		
        include ($mosConfig_absolute_path.'/components/com_users/view/profile/'.$template_file);
    }

	function userEdit($user,$option,$submitvalue,&$params, $config) {
		global $mosConfig_absolute_path,$mosConfig_frontend_userparams,$mosConfig_live_site;
		require_once ($mosConfig_absolute_path.'/includes/HTML_toolbar.php');
		// used for spoof hardening
		$validate = josSpoofValue();

        $tabs = new mosTabs(1);


        $user_extra = $user->user_extra;
        $bday_date = mosFormatDate($user_extra->birthdate, '%d', '0') ;
        $bday_month = mosFormatDate($user_extra->birthdate, '%m', '0') ;
        $bday_year = mosFormatDate($user_extra->birthdate, '%Y', '0') ;



        
		//Шаблон
		$template_file='default.php';
		if(!$config->get('template_edit')){			
			if(is_file($mosConfig_absolute_path.'/components/com_users/view/edit/'.strtolower(str_replace(' ', '', $user->usertype )).'.php')){
            	$template_file=strtolower(str_replace(' ', '', $user->usertype )).'.php';
        	}	
		}

        include ($mosConfig_absolute_path.'/components/com_users/view/edit/'.$template_file);

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

/**
* @package Joostina
* @subpackage Users
*/
class HTML_registration {
	function lostPassForm($option) {
		global $mosConfig_captcha_reg,$mosConfig_live_site;
		// used for spoof hardening
		$validate = josSpoofValue();
?>
		<form action="index.php" method="post" name="mosForm" id="mosForm">
		<div class="componentheading">
			<?php echo _PROMPT_PASSWORD; ?>
		</div>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr>
			<td colspan="2">
				<?php echo _NEW_PASS_DESC; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo _PROMPT_UNAME; ?>
			</td>
			<td>
				<input type="text" name="checkusername" class="inputbox" size="40" maxlength="25" />
			</td>
		</tr>
		<tr>
			<td>
				<?php echo _PROMPT_EMAIL; ?>
			</td>
			<td>
				<input type="text" name="confirmEmail" class="inputbox" size="40" />
			</td>
		</tr>
<?php if($mosConfig_captcha_reg) { ?>
		<tr>
			<td>&nbsp;</td>
			<td>
				<img id="captchaimg" alt="<?php echo _REG_CAPTCHA_REF; ?>" onclick="document.mosForm.captchaimg.src='<?php echo $mosConfig_live_site; ?>/includes/libraries/kcaptcha/index.php?' + new String(Math.random())" src="<?php echo $mosConfig_live_site; ?>/includes/libraries/kcaptcha/index.php?<?php echo session_id() ?>" />
			</td>
		</tr>
		<tr>
			<td><?php echo _REG_CAPTCHA; ?></td>
			<td>
				<input type="text" name="captcha" class="inputbox" size="40" value=""/>
			</td>
		</tr>
<?php } ?>
		<tr>
			<td colspan="2">
				<span class="button"><input type="submit" class="button" value="<?php echo _BUTTON_SEND_PASS; ?>" /></span>
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="sendNewPass" /> 
		<input type="hidden" name="<?php echo $validate; ?>" value="1" />
		</form>
<?php
	}

}
?>

<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/LICENSE.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл help/COPYRIGHT.php.
*
* Данный файл изменен Mitrich http://mitrichlab.ru
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

global $mosConfig_frontend_login,$my,$mosConfig_lang, $mainframe;

if ( $mosConfig_frontend_login != NULL && ($mosConfig_frontend_login === 0 || $mosConfig_frontend_login === '0')) {
	return;
}

// url of current page that user will be returned to after login
$query_string = mosGetParam( $_SERVER, 'QUERY_STRING', '' );
if (trim($query_string)!='') {
	$return = 'index.php?' . $query_string;
} else {
	$return = 'index.php';
}

// converts & to &amp; for xtml compliance
$return				= str_replace( '&', '&amp;', $return );

$params_aray=array(
'registration_enabled'=> $mainframe->getCfg( 'allowUserRegistration' ),

//-------------------------------Основные настройки
'moduleclass_sfx'=> $params->get( 'moduleclass_sfx'),  //Суффикс класса модуля
'ml_visibility'=> $params->get( 'ml_visibility'),      //Обычный вид или во всплывающем окне
'dr_login_text'=> $params->get( 'dr_login_text'),      //Текст на кнопке (если тип вывода - всплывающее окно)
'orientation'=> $params->get( 'orientation'),          //Ориентация формы: вертикально, горизонтально
'pretext'=> $params->get( 'pretext'),                  //Текст перед формой
'posttext'=> $params->get( 'posttext'),                //Текст после формы

//-------------------------------Авторизация
'login'=> $params->def( 'login', $return ),             //Адрес URL переадресации после входа
'message_login'=>$params->def( 'login_message',	0),    //Сообщение при авторизации
'greeting'=> $params->def( 'greeting',			1),    //Приветствие
'user_name'=> $params->def( 'user_name',1 ),                      //Псевдоним/Имя пользователя
'profile_link'=> $params->def( 'profile_link',0 ),     //Ссылка на профиль
'profile_link_text'=> $params->get('profile_link_text'),//Текст ссылки на профиль
//-------------------------------Выход из системы
'message_logout'=> $params->def( 'logout_message',	0), //Сообщение при выходе
'logout'=> $params->def( 'logout',$return ),            //Адрес URL переадресации пользователя при выходе

//-------------------------------Поля Логин/Пароль
'show_login_text'=> $params->get( 'show_login_text',1),         //Показать текст Пользователь
'ml_login_text'=> $params->get( 'ml_login_text', _USERNAME ),   //Текст Пользователь
'show_login_tooltip'=> $params->get( 'show_login_tooltip' ),
'login_tooltip_text'=> $params->get( 'login_tooltip_text' ),

'show_pass_text'=> $params->get( 'show_pass_text',	1),
'ml_pass_text'=> $params->get( 'ml_pass_text', _PASSWORD ),
'show_pass_tooltip'=> $params->get( 'show_pass_tooltip' ),
'pass_tooltip_text'=> $params->get( 'pass_tooltip_text' ),

//-------------------------------Другие элементы формы
'ml_avatar'   => $params->get ( 'ml_avatar',1 ),
'show_remember'=> $params->get( 'show_remember',	1),
'ml_rem_text'=> $params->get( 'ml_rem_text', _REMEMBER_ME ),
'show_lost_pass'=> $params->get( 'show_lost_pass',	1),
'ml_rem_pass_text'=> $params->get( 'ml_rem_pass_text', _LOST_PASSWORD ),
'show_register'=> $params->get( 'show_register',	1),
'ml_reg_text'=> $params->get( 'ml_reg_text', _CREATE_ACCOUNT ),
'submit_button_text'=> $params->get( 'submit_button_text', _BUTTON_LOGIN )
);
    if( $params_aray['show_login_tooltip']==1 OR $params_aray['show_pass_tooltip']){
        mosCommonHTML::loadOverlib(1);
    }
    if ( $my->id ) {
        logoutForm($params_aray);
    } else {
        loginForm($params_aray);
    }

function logoutForm($params_aray){
  global $mosConfig_frontend_login,$my,$mosConfig_lang, $mosConfig_live_site;
	if ($params_aray['user_name']) {
		$name = $my->name;
	} else {
		$name = $my->username;
	}

    $user_link = 'index.php?option=com_user&amp;task=profile&amp;user='.$my->id;
    $user_seflink = sefRelToAbs($user_link);
    $profile_link="";
	if ($params_aray['profile_link']==0) {
	    $profile_link0='<a href="'.$user_seflink.'">'.$name.'</a>';
        $name=$profile_link0;
        $profile_link="";
   	} else if($params_aray['profile_link']==1) {
        $profile_link='<a href="'.$user_seflink.'">'.$params_aray['profile_link_text'].'</a>';
   	}

    if($params_aray['ml_avatar']){
		$avatar='<div class="mod_avatar"><img id="mod_avatar_img" src="'.$mosConfig_live_site.'/'.mosUser::get_avatar($my).'" alt="'.$my->name.'"/></div>';     } else {$avatar='';}

    ?>
	<form action="<?php echo sefRelToAbs( 'index.php?option=logout' ); ?>" method="post" name="logout">
<?php echo $avatar; ?>
	<div class="ml_login_info">
<?php
	if ( $params_aray['greeting'] ) {
		echo _HI;
		echo $name;
	}
	echo $profile_link;
	?>
	</div>

	<span class="button">	
		<input type="submit" name="Submit" id="logout_button" class="button<?php echo $params_aray['moduleclass_sfx']; ?>" value="<?php echo _BUTTON_LOGOUT; ?>" />
	</span>

	<input type="hidden" name="option" value="logout" />
	<input type="hidden" name="op2" value="logout" />
	<input type="hidden" name="lang" value="<?php echo $mosConfig_lang; ?>" />
	<input type="hidden" name="return" value="<?php echo sefRelToAbs( $params_aray['logout'] ); ?>" />
	<input type="hidden" name="message" value="<?php echo $params_aray['message_logout']; ?>" />
	</form>
<?php

}

function loginForm($params_aray){
  global $mainframe, $mosConfig_live_site;

    if ($params_aray['ml_visibility']==0){
        BuildLoginForm($params_aray, $params_aray['orientation'] );
    } else {
		mosCommonHTML::loadJquery(1);
      ?>
       <script type="text/javascript">
          jQuery(document).ready(function(){
          	jQuery('.login_button').click (function() {
          	jQuery('.loginform_area').toggle(200);
           	jQuery('body').addClass("tb");
                return false;
            });
             jQuery('.closewin').click(function(){
              jQuery('.loginform_area').toggle(400);
              jQuery('.closewin').removeClass("tb");
             });
        });
      </script>
       <div class="login_button" id="log_in"><?php echo $params_aray['dr_login_text'];?></div>
       <div id="box1"> <div class="loginform_area">
            <div class="loginform_area_inside">
                <h3><?php echo _SITE_AUTH ?></h3>
                <?php  BuildLoginForm($params_aray, $params_aray['orientation']);?>
            </div>
            <div class="closewin">&nbsp;</div>
        </div></div>
    <?php }
}

function BuildLoginForm($params_aray, $orientation){
    global $mosConfig_frontend_login,$my,$mosConfig_lang;
    $validate = josSpoofValue(1);

    if($params_aray['show_login_tooltip']){
      $login_tooltip="onmouseover=\"return overlib('".$params_aray['login_tooltip_text']."');\" onmouseout=\"return nd();\"";
    }else{
        $login_tooltip='';
    }

    if($params_aray['show_pass_tooltip']){
        $pass_tooltip="onmouseover=\"return overlib('".$params_aray['pass_tooltip_text']."');\" onmouseout=\"return nd();\"";
    }else{
        $pass_tooltip='';
    }

    $login_label_def='<span class="login_label" id="login_lbl">'.$params_aray['ml_login_text'].'</span>';
    $login_input_def='<input type="text" name="username" id="mod_login_username" class="inputbox" alt="username" value="" '.$login_tooltip.' />';

    $pass_label_def='<span class="pass_label" id="pass_lbl">'.$params_aray['ml_pass_text'].'</span>';
    $pass_input_def='<input type="password" id="mod_login_password" name="passwd" class="inputbox" alt="password" value="" />';

    switch($params_aray['show_login_text']){
        case '0':
        $input_login=$login_input_def;

        case '1':
        default:
        $input_login=$login_label_def.'<br />'.$login_input_def;
        break;

        case '2':
        $input_login='<input type="text" name="username" id="mod_login_username"  class="inputbox" alt="username" value="'. $params_aray['ml_login_text'] .'" onblur="if(this.value==\'\') this.value=\''. $params_aray['ml_login_text'] .'\';" onfocus="if(this.value==\''. $params_aray['ml_login_text'] .'\') this.value=\'\';" />';
        break;

        case '3':
        default:
        $input_login=$login_label_def.$login_input_def;
        break;
    }

    switch($params_aray['show_pass_text']){
        case '0':
        $input_pass=$pass_input_def;

        case '1':
        default:
        $input_pass=$pass_label_def.'<br />'.$pass_input_def;
        break;

        case '2':
        $input_pass='<input type="password" id="mod_login_password" name="passwd" class="inputbox" alt="password" value="'. $params_aray['ml_pass_text'] .'" onblur="if(this.value==\'\') this.value=\''. $params_aray['ml_pass_text'] .'\';" onfocus="if(this.value==\''. $params_aray['ml_pass_text'] .'\') this.value=\'\';" />';
        break;

        case '3':
        default:
        $input_pass=$pass_label_def.$pass_input_def;
        break;
    }

      if ($params_aray['show_remember'] == 1) {
		  $remember_me='<input type="checkbox" name="remember" id="mod_login_remember"  value="yes" alt="Remember Me" /><label for="mod_login_remember">'.$params_aray['ml_rem_text'].'</label>';
     } else {
         $remember_me ='';
     }

      if ($params_aray['show_lost_pass'] == 1) {
		  $lost_pass='<a class="ml_login" href="'.sefRelToAbs( 'index.php?option=com_user&amp;task=lostPassword' ).'">'.$params_aray['ml_rem_pass_text'].'</a>';
     } else {
         $lost_pass ='';
     }

      if ($params_aray['show_register'] == 1) {
		  $register_me='<a class="ml_login" href="'.sefRelToAbs( 'index.php?option=com_user&amp;task=register' ).'">'.$params_aray['ml_reg_text'].'</a>';
     } else {
         $register_me ='';
     }

     $submit_button='<span class="button"><input type="submit" name="Submit" class="button" id="login_button" value="'.$params_aray['submit_button_text'].'" /></span>';

     //Выводим форму
     echo '<div class="form_pretext">'.$params_aray['pretext'].'</div>';
     ?>
        <form action="<?php echo sefRelToAbs( 'index.php' ); ?>" method="post" name="login">
     <?php if ($orientation=='1'){
     ?>
        <table cellpadding="0" cellspacing="0" class="login_form" border="0" width="95%">
            <tr>
                <td><?php echo $input_login; ?></td>
                <td width="38%"><?php echo $input_pass; ?></td>
                <td width="15%" align="right"><br /><?php echo $submit_button; ?></td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" class="login_form_misc" border="0" width="95%">
            <tr>
                <td><?php echo $remember_me; ?></td>
                <td><?php echo $lost_pass; ?></td>
                <td><?php echo $register_me; ?></td>
            </tr>
        </table>
     <?php
     } else{
     ?>
       <div class="login_form">
            <?php echo $input_login; ?><br />
            <?php echo $input_pass; ?><br />
            <?php echo $remember_me; ?><br />
            <?php echo $submit_button; ?><br />
            <?php echo $lost_pass; ?> <?php echo $register_me; ?>
       </div>
     <?php
     }
     echo '<div class="form_posttext">'.$params_aray['posttext'].'</div>';

     ?>
	<input type="hidden" name="option" value="login" />
	<input type="hidden" name="op2" value="login" />
	<input type="hidden" name="lang" value="<?php echo $mosConfig_lang; ?>" />
	<input type="hidden" name="return" value="<?php echo sefRelToAbs( $params_aray['login'] ); ?>" />
	<input type="hidden" name="message" value="<?php echo $params_aray['message_login']; ?>" />
	<input type="hidden" name="force_session" value="1" />
	<input type="hidden" name="<?php echo $validate; ?>" value="1" />
	</form>
<?php }?>

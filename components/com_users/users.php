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

global $my, $task, $option, $mosConfig_frontend_login, $mosConfig_useractivation;

userHelper::_load_core_js();
?>
<script type="text/javascript">
	var _comuser_url = '<?php echo $mosConfig_live_site;?>/components/com_users';
	var _comuser_ajax_handler = 'ajax.index.php?option=com_users';
	var _comuser_defines = new Array();
</script>
<?php

// Editor usertype check
$access = new stdClass();
$access->canEdit = $acl->acl_check('action','edit','users',$my->usertype,'content','all');
$access->canEditOwn = $acl->acl_check('action','edit','users',$my->usertype,'content','own');

require_once ($mainframe->getPath('front_html'));
require_once ($mainframe->getPath('config','com_users'));
require_once ($mainframe->getPath('class'));

switch($task) {
	case 'UserDetails':
		userEdit($option,$my->id,_UPDATE);
		break;

	case 'saveUserEdit':
		// check to see if functionality restricted for use as demo site
		if($_VERSION->RESTRICT == 1) {
			mosRedirect('index.php?mosmsg='._RESTRICT_FUNCTION);
		} else {
			userSave($option,$my->id);
		}
		break;

	case 'CheckIn':
		CheckIn($my->id,$access,$option);
		break;

	case 'cancel':
		mosRedirect('index.php?option=com_users&task=profile&user='.mosGetParam( $_REQUEST, 'id', 0 ));
		break;

	case 'profile':
		profile($option);
		break;

	case 'lostPassword':
		if($mosConfig_frontend_login != null && ($mosConfig_frontend_login === 0 || $mosConfig_frontend_login=== '0')) {
			echo _NOT_AUTH;
			return;
		}
		lostPassForm($option);
		break;

	case 'sendNewPass':
		if($mosConfig_frontend_login != null && ($mosConfig_frontend_login === 0 || $mosConfig_frontend_login=== '0')) {
			echo _NOT_AUTH;
			return;
		}
		sendNewPass($option);
		break;

	case 'register':
		if($mosConfig_frontend_login != null && ($mosConfig_frontend_login === 0 || $mosConfig_frontend_login=== '0')) {
			echo _NOT_AUTH;
			return;
		}
		registerForm($option,$mosConfig_useractivation);
		break;

	case 'saveRegistration':
		if($mosConfig_frontend_login != null && ($mosConfig_frontend_login === 0 || $mosConfig_frontend_login=== '0')) {
			echo _NOT_AUTH;
			return;
		}
		saveRegistration();
		break;

	case 'activate':
		if($mosConfig_frontend_login != null && ($mosConfig_frontend_login === 0 || $mosConfig_frontend_login=== '0')) {
			echo _NOT_AUTH;
			return;
		}
		activate($option);
		break;
		
	case 'userlist':
		userlist();
		break;

	default:
		HTML_user::frontpage();
		break;
}

function profile($option){
	global $database,$mainframe, $mosConfig_absolute_path;

	$uid = mosGetParam( $_REQUEST, 'user', 0 );

	//require_once ($mosConfig_absolute_path.'/administrator/components/com_users/users.class.php');
	$row = new mosUser($database);
	//$row->load((int)$uid);
	if($row->load((int)$uid)){
		//Дополнительная информация о пользователе
		$row->user_extra = $row->get_user_extra($uid);

		$file = $mainframe->getPath('com_xml','com_users');
		$params = &new mosUserParameters($row->params,$file,'component');

		$config = new configUser_profile($database);

		HTML_user::profile($row,$option, $params, $config);
	}else{
	echo 'Извините, пользователь не найден';
	}

}

function userEdit($option,$uid,$submitvalue) {
	global $database,$mainframe;
	global $mosConfig_absolute_path;

	//require_once ($mosConfig_absolute_path.'/'.ADMINISTRATOR_DIRECTORY.'/components/com_users/users.class.php');

	if($uid == 0) {
		mosNotAuth();
		return;
	}
	$user = new mosUser($database);
	$user->load((int)$uid);
	$user->orig_password = $user->password;

	$user->name = trim($user->name);
	$user->email = trim($user->email);
	$user->username = trim($user->username);

	$file = $mainframe->getPath('com_xml','com_users');
	$params = &new mosUserParameters($user->params,$file,'component');

	$user_extra = new userUsersExtra($database);
	$user_extra->load((int)$uid);
	$user->user_extra = $user_extra;

	$config = new configUser_profile($database);

	HTML_user::userEdit($user,$option,$submitvalue,$params, $config);
}

function userSave($option,$uid) {
	global $database,$my,$mosConfig_frontend_userparams;

	$user_id = intval(mosGetParam($_POST,'id',0));

	// do some security checks
	if($uid == 0 || $user_id == 0 || $user_id != $uid) {
		mosNotAuth();
		return;
	}

	// simple spoof check security
	josSpoofCheck();

	$row = new mosUser($database);
	$row->load((int)$user_id);

	$orig_password = $row->password;
	$orig_USER = $row->username;

	if(!$row->bind($_POST,'gid usertype')) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$row->name = trim($row->name);
	$row->email = trim($row->email);
	$row->username = trim($row->username);


	mosMakeHtmlSafe($row);

	if(isset($_POST['password']) && $_POST['password'] != '') {
		if(isset($_POST['verifyPass']) && ($_POST['verifyPass'] == $_POST['password'])) {
			$row->password = trim($row->password);
			$salt = mosMakePassword(16);
			$crypt = md5($row->password.$salt);
			$row->password = $crypt.':'.$salt;
		} else {
			echo "<script> alert(\"".addslashes(_PASS_MATCH)."\"); window.history.go(-1); </script>\n";
			exit();
		}
	} else {
		// Restore 'original password'
		$row->password = $orig_password;
	}

	if($mosConfig_frontend_userparams == '1' || $mosConfig_frontend_userparams == 1 ||
		$mosConfig_frontend_userparams == null) {
		// save params
		$params = mosGetParam($_POST,'params','');
		if(is_array($params)) {
			$txt = array();
			foreach($params as $k => $v) {
				$txt[] = "$k=$v";
			}
			$row->params = implode("\n",$txt);
		}
	}
	if(!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if(!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$user_extra = new userUsersExtra($database);
	$ret = $user_extra->load((int)$user_id);
	if(!$user_extra->bind($_POST)) {
		echo "<script> alert('".$user_extra->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}		
	$user_extra->birthdate  = $_POST['birthdate_year'].'-'.$_POST['birthdate_month'].'-'.$_POST['birthdate_day'].' 00:00:00';
  	if(!$ret){
  		$user_extra->insert($user_id);
  	}
	$user_extra->store();

	// check if username has been changed
	if($orig_USER != $row->username) {
		// change username value in session table
		$query = "UPDATE #__session"
				."\n SET username = ".$database->Quote($row->username)
				."\n WHERE username = ".$database->Quote($orig_USER)
				."\n AND userid = ".(int)$my->id
				."\n AND gid = ".(int)$my->gid
				."\n AND guest = 0";
		$database->setQuery($query);
		$database->query();
	}

	userEdit($option,$my->id,_UPDATE);
}

function userlist(){
	global $acl;
	
	$db = &database::getInstance();
	$mainframe = &mosMainFrame::getInstance();
	
	$gid = intval(mosGetParam($_REQUEST,'group',0));
	
	if(isset($mainframe->menu)){
		$menu = $mainframe->menu;		
	}
	else{
		$link = 'index.php?option=com_users&task=userlist';
		if($gid){
			$link = 'index.php?option=com_users&task=userlist&group='.$gid;	
		}
		$menu = new mosMenu($db);
		$menu = $menu->getMenu(false, 'userlist', $link);	
	}
		
	$params = new mosParameters($mainframe->menu->params);
	$usertype = $acl->get_group_name($params->get('group', 0));
	
	$limit		= intval(mosGetParam($_REQUEST,'limit',$params->get('limit',20)));
    $limitstart	= intval(mosGetParam($_REQUEST,'limitstart',0));
	
	$template = 'default.php';
	$template_dir = 'components/com_users/view/userlist';
	
	if($params->get('template')){
		$template = $params->get('template');	
	}
	else if($params->get('group', 0)){
		$template = strtolower(str_replace(' ', '', $usertype )).'.php';	
	}
	
	
	if($params->get('template_dir')){
		$template_dir = 'templates/' . $mainframe->getTemplate() . '/html/com_users/userlist';	
	}
	$template_file = $mainframe->getCfg('absolute_path').'/'.$template_dir.'/'.$template;
	
	//Получаем список пользователей
	$users = new mosUser($db);
	//Общее количество
	$users->total = $users->get_total($usertype);
	// список
	$users->user_list = $users->get_users($usertype, $limitstart, $limit);
	
	//Подключаем шаблон
	if(is_file($template_file)){
		include_once($template_file);
	}
		
	
		
}

function CheckIn($userid,$access) {
	global $database;
	global $mosConfig_db;

	$nullDate = $database->getNullDate();
	if(!($access->canEdit || $access->canEditOwn || $userid > 0)) {
		mosNotAuth();
		return;
	}

	// security check to see if link exists in a menu
	$link = 'index.php?option=com_users&task=CheckIn';
	$query = "SELECT id"
			."\n FROM #__menu"
			."\n WHERE link LIKE '%$link%'"
			."\n AND published = 1";
	$database->setQuery($query);
	$exists = $database->loadResult();
	if(!$exists) {
		mosNotAuth();
		return;
	}

	$lt = mysql_list_tables($mosConfig_db);
	$k = 0;
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
	while(list($tn) = mysql_fetch_array($lt)) {
		// only check in the jos_* tables
		if(strpos($tn,$database->_table_prefix) !== 0) {
			continue;
		}
		$lf = mysql_list_fields($mosConfig_db,"$tn");
		$nf = mysql_num_fields($lf);

		$checked_out = false;
		$editor = false;

		for($i = 0; $i < $nf; $i++) {
			$fname = mysql_field_name($lf,$i);
			if($fname == "checked_out") {
				$checked_out = true;
			} else
				if($fname == "editor") {
					$editor = true;
				}
		}

		if($checked_out) {
			if($editor) {
				$query = "SELECT checked_out, editor"
						."\n FROM `$tn`"
						."\n WHERE checked_out > 0"
						."\n AND checked_out = ".(int)$userid;
				$database->setQuery($query);
			} else {
				$query = "SELECT checked_out"
						."\n FROM `$tn`"
						."\n WHERE checked_out > 0"
						."\n AND checked_out = ".(int)$userid;
				$database->setQuery($query);
			}
			$res = $database->query();
			$num = $database->getNumRows($res);

			if($editor) {
				$query = "UPDATE `$tn`"
						."\n SET checked_out = 0, checked_out_time = ".$database->Quote($nullDate).", editor = NULL"
						."\n WHERE checked_out > 0"
						."\n AND checked_out = ".(int)$userid;
				$database->setQuery($query);
			} else {
				$query = "UPDATE `$tn`"
						."\n SET checked_out = 0, checked_out_time = ".$database->Quote($nullDate)
						."\n WHERE checked_out > 0"
						."\n AND checked_out = ".(int)$userid;
				$database->setQuery($query);
			}
			$res = $database->query();

			if($res == 1) {
				if($num > 0) {
					echo "\n<tr class=\"row$k\">";
					echo "\n\t<td width=\"250\">";
					echo _CHECK_TABLE;
					echo " - $tn</td>";
					echo "\n\t<td>";
					echo _CHECKED_IN;
					echo "<b>$num</b>";
					echo _CHECKED_IN_ITEMS;
					echo "</td>";
					echo "\n</tr>";
				}
				$k = 1 - $k;
			}
		}
	}
?>
	<tr>
		<td colspan="2">
			<b><?php echo _CONF_CHECKED_IN; ?></b>
		</td>
	</tr>
	</table>
	<?php
}

function lostPassForm($option) {
	global $mainframe;

	$mainframe->SetPageTitle(_PROMPT_PASSWORD);

	HTML_registration::lostPassForm($option);
}

function sendNewPass($option) {
	global $database,$mosConfig_mailfrom,$mosConfig_fromname,$mosConfig_captcha_reg;

	// simple spoof check security
	josSpoofCheck();

	$checkusername = stripslashes(mosGetParam($_POST,'checkusername',''));
	$confirmEmail = stripslashes(mosGetParam($_POST,'confirmEmail',''));

	if($mosConfig_captcha_reg) {
		session_start();
		$captcha = $_POST['captcha'];
		if(!isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !==$captcha) {
			mosErrorAlert('Введен неверный код проверки.');
			unset($_SESSION['captcha_keystring']);
			exit;
		}
		session_unset();
		session_write_close();
	}

	$query = "SELECT id FROM #__users WHERE username = ".$database->Quote($checkusername)."\n AND email = ".$database->Quote($confirmEmail);
	$database->setQuery($query);
	if(!($user_id = $database->loadResult()) || !$checkusername || !$confirmEmail) {
		mosRedirect("index.php?option=$option&task=lostPassword&mosmsg="._ERROR_PASS);
	}

	$newpass = mosMakePassword();
	$message = _NEWPASS_MSG;
	eval("\$message = \"$message\";");
	$subject = _NEWPASS_SUB;
	eval("\$subject = \"$subject\";");

	mosMail($mosConfig_mailfrom,$mosConfig_fromname,$confirmEmail,$subject,$message);

	$salt = mosMakePassword(16);
	$crypt = md5($newpass.$salt);
	$newpass = $crypt.':'.$salt;
	$sql = "UPDATE #__users SET password = ".$database->Quote($newpass)."\n WHERE id = ".(int)
		$user_id;
	$database->setQuery($sql);
	if(!$database->query()) {
		die("SQL error".$database->stderr(true));
	}

	mosRedirect('index.php?option=com_users&mosmsg='._NEWPASS_SENT);
}

function registerForm($option,$useractivation) {
	global $mainframe, $database, $mosConfig_absolute_path, $mosConfig_live_site,$mosConfig_captcha_reg;
	if(!$mainframe->getCfg('allowUserRegistration')) {
		mosNotAuth();
		return;
	}
	session_start();

	$params = new configUser_registration($database);

	//Определяем шаблон для вывода регистрационной формы
	$template = 'default.php';

	if(!$params->get('template')){
		$type = mosGetParam( $_REQUEST, 'type', '' );
		if($type){
			if(!is_file($mosConfig_absolute_path.'/components/com_users/view/registration/'.$type.'.php')){
				$template = $type.'.php';
			}
		}
	}

	// used for spoof hardening
	$validate = josSpoofValue();

	include ($mosConfig_absolute_path.'/components/com_users/view/registration/'.$template);
	//HTML_registration::registerForm($option,$useractivation, $params);
}

function saveRegistration() {
	global $database,$acl,$mosConfig_captcha_reg;
	global $mosConfig_sitename,$mosConfig_live_site,$mosConfig_useractivation,$mosConfig_allowUserRegistration;
	global $mosConfig_mailfrom,$mosConfig_fromname,$mosConfig_mailfrom,$mosConfig_fromname, $mosConfig_absolute_path;

	if($mosConfig_allowUserRegistration == 0) {
		mosNotAuth();
		return;
	}

	$params = new configUser_registration($database);
	// simple spoof check security
	josSpoofCheck();

	if($mosConfig_captcha_reg) {
		session_start();
		$captcha = $_POST['captcha'];
		if(!isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !==
			$captcha) {
			mosErrorAlert('Введен неверный код проверки.');
			unset($_SESSION['captcha_keystring']);
			exit;
		}
		session_unset();
		session_write_close();
	}

	$row = new mosUser($database);

	if(!$row->bind($_POST,'usertype')) {
		mosErrorAlert($row->getError());
	}

	$row->name = trim($row->name);
	$row->email = trim($row->email);
	$row->username = trim($row->username);
	$row->password = trim($row->password);

	mosMakeHtmlSafe($row);

	$row->id = 0;
	$row->usertype = '';
	//$row->gid = $acl->get_group_id('Registered','ARO');
	$row->gid = $params->get('gid');

	if($mosConfig_useractivation == 1) {
		$row->activation = md5(mosMakePassword());
		$row->block = '1';
	}

	if(!$row->check()) {
		echo "<script> alert('".html_entity_decode($row->getError())."'); window.history.go(-1); </script>\n";
		exit();
	}

	$pwd = $row->password;

	$salt = mosMakePassword(16);
	$crypt = md5($row->password.$salt);
	$row->password = $crypt.':'.$salt;
	$row->registerDate = date('Y-m-d H:i:s');

	if(!$row->store()) {
		echo "<script> alert('".html_entity_decode($row->getError())."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->id = $row->insertid();
	$row->checkin();

	$email_info = array();
	$email_info['name']		= trim($row->name);
	$email_info['email']	= trim($row->email);
	$email_info['username']	= trim($row->username);

	//Подготавливаем письмо пользователю
	$email_info['subject'] = sprintf(_SEND_SUB, $email_info['name'], $mosConfig_sitename);
	$email_info['subject'] = html_entity_decode($email_info['subject'], ENT_QUOTES);

	if($mosConfig_useractivation == 1  ) {
		$email_info['message'] = sprintf(_USEND_MSG_ACTIVATE, $email_info['name'],
							$mosConfig_sitename,
							$mosConfig_live_site."/index.php?option=com_users&task=activate&activation=".
							$row->activation, $mosConfig_live_site, $email_info['username'], $pwd);
	} else {
		$email_info['message'] = sprintf(_USEND_MSG,$email_info['name'],
							$mosConfig_sitename,$mosConfig_live_site);
	}
	$email_info['message'] = html_entity_decode($email_info['message'],ENT_QUOTES);

	if($mosConfig_mailfrom != '' && $mosConfig_fromname != '') {
		$email_info['adminName'] = $mosConfig_fromname;
		$email_info['adminEmail'] = $mosConfig_mailfrom;
	} else {
		// use email address and name of first superadmin for use in email sent to user
		$query = "SELECT name, email FROM #__users WHERE LOWER( usertype ) = 'superadministrator' OR LOWER( usertype ) = 'super administrator'";
		$database->setQuery($query);
		$rows = $database->loadObjectList();
		$row2 = $rows[0];
		$email_info['adminName'] = $row2->name;
		$email_info['adminEmail'] = $row2->email;
	}

	// Отсылаем пользователю письмо только в случае, если не включено "Активация администратором"
	if(!$params->get('admin_activation')){
		$row->send_mail_to_user($email_info);
	}


	// Подготавливаем письмо администраторам сайта
	$email_info['subject'] = sprintf(_SEND_SUB, $email_info['name'],$mosConfig_sitename);
	$email_info['message'] = sprintf(_ASEND_MSG, $email_info['adminName'],
							$mosConfig_sitename, $row->name, $email_info['email'],$email_info['username']);
	$email_info['subject'] = html_entity_decode($email_info['subject'],ENT_QUOTES);
	$email_info['message'] = html_entity_decode($email_info['message'],ENT_QUOTES);
	//отправляем письма
	$row->send_mail_to_admins($email_info);



	if($mosConfig_useractivation == 1) {

		$msg = _REG_COMPLETE_ACTIVATE;
		if($params->get('admin_activation')){
			$msg = 'Благодарим за регистрацию. Доступ к аккаунту будет предоставлен после проверки модератором.';
		}

		if($params->get('redirect_url')){
			mosRedirect($params->get('redirect_url'), $msg);
		}

		//Определяем шаблон
		$template = 'default.php';

		//Если в параметрах настройки регистрации задано использование
		//разных шаблонов для разных групп пользователей -
		//даём возможность выводить сообщения также с помощью разных шаблонов
		//Если шаблон для группы не найден - используем стандартный шаблон
		if(!$params->get('template')){
			$group_name = $acl->get_group_name($row->gid,'ARO');
			if($group_name){
				if(!is_file($mosConfig_absolute_path.'/components/com_users/view/after_registration/'.$group_name.'.php')){
					$template = $group_name.'.php';
				}
			}
		}

		include ($mosConfig_absolute_path.'/components/com_users/view/after_registration/'.$template);
		return;

	}
	else {
		$msg = _REG_COMPLETE;
		mosRedirect('index.php?option=com_users&task=profile&user='.$row->id, $msg);
	}
}

function activate() {
	global $database,$my,$mosConfig_auto_activ_login,$mainframe,$mosConfig_auto_activ_login;
	global $mosConfig_useractivation,$mosConfig_allowUserRegistration;
	if($my->id) {
		mosRedirect('index.php');
	}

	if($mosConfig_allowUserRegistration == '0' || $mosConfig_useractivation == '0') {
		mosNotAuth();
		return;
	}

	$activation = stripslashes(mosGetParam($_REQUEST,'activation',''));

	if(empty($activation)) {
		echo _REG_ACTIVATE_NOT_FOUND;
		return;
	}

	$query = "SELECT id FROM #__users WHERE activation = ".$database->Quote($activation)."\n AND block = 1";
	$database->setQuery($query);
	$result = $database->loadResult();

	if($result) {
		$query = "UPDATE #__users SET block = 0, activation = '' WHERE activation = ".$database->Quote($activation)."\n AND block = 1";
		$database->setQuery($query);
		if(!$database->query()) {
			if(!defined(_REG_ACTIVATE_FAILURE)) {
				DEFINE('_REG_ACTIVATE_FAILURE',_USER_ACTIVATION_FAILED);
			}
			echo _REG_ACTIVATE_FAILURE;
		} else {
			if($mosConfig_auto_activ_login == 1) {
				$user = new mosUser($database);
				if($user->load($result)) {
					$_POST['remember'] = 1;
					$mainframe->login($user->username,$user->password);
					mosRedirect( 'index.php', _REG_ACTIVATE_COMPLETE );
				}
			} else {
				echo _REG_ACTIVATE_COMPLETE;
			}
		}
	} else {
		echo _REG_ACTIVATE_NOT_FOUND;
	}
}
?>

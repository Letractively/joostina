<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// Установка флага родительского файла
define('_VALID_MOS',1);

if(!file_exists('../configuration.php')) {
	header('Location: ../installation/index.php');
	exit();
}

require ('../includes/globals.php');
require_once ('../configuration.php');

// SSL check - $http_host returns <live site url>:<port number if it is 443>
$http_host = explode(':',$_SERVER['HTTP_HOST']);
if((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off' || isset($http_host[1]) && $http_host[1] == 443) && substr($mosConfig_live_site,0,8) !='https://') {
	$mosConfig_live_site = 'https://' . substr($mosConfig_live_site,7);
}

require_once ($mosConfig_absolute_path.'/includes/joomla.php');

// Проверяем ip адрес: если он находится в стоп-листе и выбрана опция блокировки достутпа в админку, то блокируем доступ
if(file_exists('./components/com_security/block_access.php')) {
	require_once ('./components/com_security/block_access.php');
	block_access(1);
}
// Такого ip адреса нет в стоп-листе. Продолжаем загрузку.


// загрузка файла русского языка по умолчанию
if($mosConfig_lang == '') {
	$mosConfig_lang = 'russian';
}

include_once ($mosConfig_absolute_path . '/language/' . $mosConfig_lang . '.php');

//Installation sub folder check, removed for work with SVN
if(file_exists('../installation/index.php') && $_VERSION->SVN == 0) {
	define('_INSTALL_CHECK',1);
	include ($mosConfig_absolute_path . '/templates/system/offline.php');
	exit();
}

$option = strtolower(strval(mosGetParam($_REQUEST,'option',null)));

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = mosMainFrame::getInstance(true);

if(isset($_POST['submit'])) {
	$usrname = stripslashes(mosGetParam($_POST,'usrname',null));
	$pass = stripslashes(mosGetParam($_POST,'pass',null));

	if($pass == null) {
		echo "<script>alert('"._PLEASE_ENTER_PASSWORD."'); document.location.href='index.php?mosmsg="._PLEASE_ENTER_PASSWORD."'</script>\n";
		exit();
	}

	session_start();
	if($mosConfig_admin_bad_auth <= $_SESSION['bad_auth'] && (int)$mosConfig_admin_bad_auth >= 0) {
		$captcha = $_POST['captcha'];
		if(!isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !==
			$captcha) {
			$mosmsg = _BAD_CAPTCHA_STRING;
			echo "<script>alert('$mosmsg'); document.location.href='index.php?mosmsg=$mosmsg'</script>\n";
			unset($_SESSION['captcha_keystring']);
			exit;
		}
	}

	$query = "SELECT COUNT(*)"
			."\n FROM #__users"
			."\n WHERE (" // Администраторы
			."\n gid = 24" // СуперАдминистраторы
			."\n OR gid = 25 )";
	$database->setQuery($query);
	$count = intval($database->loadResult());
	if($count < 1) {
		mosErrorAlert(_LOGIN_NOADMINS,"document.location.href='index.php?$mosConfig_admin_secure_code'");
	}

	$my = null;
	$query = "SELECT u.*, m.*"
			."\n FROM #__users AS u"
			."\n LEFT JOIN #__messages_cfg AS m ON u.id = m.user_id AND m.cfg_name = 'auto_purge'"
			."\n WHERE u.username = " . $database->Quote($usrname)
			."\n AND u.block = 0";
	$database->setQuery($query);
	$database->loadObject($my);

	/** find the user group (or groups in the future)*/
	if(@$my->id) {
		$grp = $acl->getAroGroup($my->id);
		$my->gid = $grp->group_id;
		$my->usertype = $grp->name;

		// Conversion to new type
		if((strpos($my->password,':') === false) && $my->password == md5($pass)) {
			// Old password hash storage but authentic ... lets convert it
			$salt = mosMakePassword(16);
			$crypt = md5($pass . $salt);
			$my->password = $crypt . ':' . $salt;

			// Now lets store it in the database
			$query = 'UPDATE #__users SET password = ' . $database->Quote($my->password) . 'WHERE id = ' . (int)$my->id;
			$database->setQuery($query);
			if(!$database->query()) {
				// This is an error but not sure what to do with it ... we'll still work for now
			}
		}

		list($hash,$salt) = explode(':',$my->password);
		$cryptpass = md5($pass . $salt);

		if(strcmp($hash,$cryptpass) || !$acl->acl_check('administration','login','users',$my->usertype)) {
			// Admin authorization failure
			$query = 'UPDATE #__users SET bad_auth_count = bad_auth_count + 1 WHERE id = ' . (int)$my->id;
			$database->setQuery($query);
			$database->query();
			$_SESSION['bad_auth'] = ((int)$_SESSION['bad_auth']) + 1;
			mosErrorAlert(_BAD_USERNAME_OR_PASSWORD,"document.location.href='index.php?$mosConfig_admin_secure_code'");
		}
		session_destroy();
		session_unset();
		session_write_close();

		// construct Session ID
		$logintime = time();
		$session_id = md5($my->id . $my->username . $my->usertype . $logintime);

		session_name( md5( $mosConfig_live_site ) );
		session_id( $session_id );
		session_start();

		// add Session ID entry to DB
		$query = "INSERT INTO #__session SET time = " . $database->Quote($logintime) .", session_id = " . $database->Quote($session_id) . ", userid = " . (int)$my->id . ", usertype = " . $database->Quote($my->usertype) . ", username = " . $database->Quote($my->username);
		$database->setQuery($query);
		if(!$database->query()) {
			echo $database->stderr();
		}

		// check if site designated as a production site
		// for a demo site allow multiple logins with same user account
		if($_VERSION->SITE == 1) {
			// delete other open admin sessions for same account
			$query = "DELETE FROM #__session WHERE userid = " . (int)$my->id . "\n AND username = " .$database->Quote($my->username) . "\n AND usertype = " . $database->Quote($my->usertype) . "\n AND session_id != " . $database->Quote($session_id). "\n AND guest = 1" . "\n AND gid = 0";
			$database->setQuery($query);
			if(!$database->query()) {
				echo $database->stderr();
			}
		}

		$_SESSION['session_id'] = $session_id;
		$_SESSION['session_user_id'] = $my->id;
		$_SESSION['session_username'] = $my->username;
		$_SESSION['session_usertype'] = $my->usertype;
		$_SESSION['session_gid'] = $my->gid;
		$_SESSION['session_logintime'] = $logintime;
		$_SESSION['session_user_params'] = $my->params;
		$_SESSION['session_bad_auth_count'] = $my->bad_auth_count;
		$_SESSION['session_userstate'] = array();

		session_write_close();

		$expired = 'index2.php';

		// check if site designated as a production site
		// for a demo site disallow expired page functionality
		if($_VERSION->SITE == 1 && @$mosConfig_admin_expired === '1') {
			$file = $mainframe->getPath('com_xml','com_users');
			$params = &new mosParameters($my->params,$file,'component');

			$now = time();

			// expired page functionality handling
			$expired = $params->def('expired','');
			$expired_time = $params->def('expired_time','');

			// if now expired link set or expired time is more than half the admin session life set, simply load normal admin homepage
			$checktime = ($mosConfig_session_life_admin ? $mosConfig_session_life_admin : 1800) / 2;
			if(!$expired || (($now - $expired_time) > $checktime)) {
				$expired = 'index2.php';
			}
			// link must also be a Joomla link to stop malicious redirection
			if(strpos($expired,'index2.php?option=com_') !== 0) {
				$expired = 'index2.php';
			}

			// clear any existing expired page data
			$params->set('expired','');
			$params->set('expired_time','');

			// param handling
			if(is_array($params->toArray())) {
				$txt = array();
				foreach($params->toArray() as $k => $v) {
					$txt[] = "$k=$v";
				}
				$saveparams = implode("\n",$txt);
			}

			// save cleared expired page info to user data
			$query = "UPDATE #__users SET params = " . $database->Quote($saveparams) ." WHERE id = " . (int)$my->id . " AND username = " . $database->Quote($my->username) . " AND usertype = " . $database->Quote($my->usertype);
			$database->setQuery($query);
			$database->query();
		}

		// check if auto_purge value set
		if($my->cfg_name == 'auto_purge') {
			$purge = $my->cfg_value;
		} else {
			// if no value set, default is 7 days
			$purge = 7;
		}
		// calculation of past date
		$past = date('Y-m-d H:i:s',time() - $purge* 60* 60* 24);

		// if purge value is not 0, then allow purging of old messages
		if($purge != 0) {
			// purge old messages at day set in message configuration
			$query = "DELETE FROM #__messages"
					."\n WHERE date_time < " . $database->Quote($past)
					."\n AND user_id_to = " . (int)$my->id;
			$database->setQuery($query);
			if(!$database->query()) {
				echo $database->stderr();
			}
		}

		/** cannot using mosredirect as this stuffs up the cookie in IIS*/
		// redirects page to admin homepage by default or expired page
		echo "<script>document.location.href='$expired';</script>\n";
		exit();
	} else {
		mosErrorAlert(_BAD_USERNAME_OR_PASSWORD2,"document.location.href='index.php?$mosConfig_admin_secure_code&mosmsg="._BAD_USERNAME_OR_PASSWORD2."'");
	}
} else {
	initGzip();
	session_start();
	if(!isset($_SESSION['bad_auth'])){
		$_SESSION['bad_auth'] = 0;
	}

	if($mosConfig_admin_bad_auth <= $_SESSION['bad_auth'] && (int)$mosConfig_admin_bad_auth >= 0) {
		$mosConfig_captcha = 1;
	}
	$path = $mosConfig_absolute_path . '/'.ADMINISTRATOR_DIRECTORY.'/templates/' . $mainframe->getTemplate() . '/login.php';
	require_once ($path);
	doGzip();
}
?>

<?php
/**
* @JoostFREE
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/
// запрет прямого доступа
defined('_VALID_MOS') or die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>
<?php echo $mosConfig_sitename; ?> - <?php echo _JOOSTINA_CONTRIL_PANEL?></title>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<style type="text/css">@import url(templates/joostfree/css/admin_login.css);</style>
</head>
<body onload="setFocus();">
<div id="joo"><img src="templates/joostfree/images/logo.png" alt="Joostina!" /></div>
<?php
include_once ($mosConfig_absolute_path.'/'.ADMINISTRATOR_DIRECTORY.'/modules/mod_mosmsg.php');
?>
<div id="ctr1" align="center">
<div class="login">
<div class="login-form">
<form action="index.php" method="post" name="loginForm" id="loginForm">
<div class="form-block">
Логин  <input name="usrname" id="usrname" type="text" class="inputbox" size="15" />
Пароль <input name="pass" type="password" class="inputbox" size="15" />
<?php
if($mosConfig_captcha) {
session_start();
?>
<div>
<img id="loginCaptcha" alt="<?php echo _PRESS_HERE_TO_RELOAD_CAPTCHA?>" onclick="document.loginForm.loginCaptcha.src='<?php
echo $mosConfig_live_site; ?>/includes/kcaptcha/index.php?' + new String(Math.random())" src="<?php
echo $mosConfig_live_site; ?>/includes/kcaptcha/index.php?<?php echo session_id() ?>" />
</div>
<span class="captcha" onclick="document.loginForm.loginCaptcha.src='<?php
echo $mosConfig_live_site; ?>/includes/kcaptcha/index.php?' + new String(Math.random())"><?php echo _SHOW_CAPTCHA?></span>
<div><?php echo _PLEASE_ENTER_CAPTCHA?>:</div>
<div><input name="captcha" type="text" class="inputbox" size="15" /></div>
<?php
};
?>
<div align="center">
<input type="submit" name="submit" class="button" value="Войти" /><br />
<input type="button" name="submit" onClick="document.location.href='<?php echo $mosConfig_live_site; ?>'" class="button" value="Перейти на сайт" />
</div>
</div>
</form>
</div>
<div class="clr"></div>
</div>
</div>
<div id="break"></div>
<div align="center">
<noscript><div class="message"><?php echo _PLEASE_ENABLE_JAVASCRIPT?></div></noscript>
</div>
<div id="footer" align="center">
<div align="center"><?php echo $_VERSION->URL; ?></div>
</div>
<script language="javascript" type="text/javascript"><!--
function setFocus() {
document.loginForm.usrname.select();
document.loginForm.usrname.focus();
}
//--></script>
</body>
</html>
<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, смотрите LICENSE.php
* Joostina! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>
		<?php echo $mosConfig_sitename; ?> - Панель управления [ Joostina ]</title>
	<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
	<style type="text/css">
		@import url(templates/joostfree/css/admin_login.css);
	</style>
	<script language="javascript" type="text/javascript">
	function setFocus() {
		document.loginForm.usrname.select();
		document.loginForm.usrname.focus();
	}
	</script>
</head>
<body onload="setFocus();">
	<div id="wrapper">
		<div id="joo">
			<img src="templates/joostfree/images/logo.png" alt="Joostina!" />
		</div>
	</div>
	<div align="center">
		<?php
			include_once( $mosConfig_absolute_path .'/administrator/modules/mod_mosmsg.php' );
		?>
	</div>
	<div id="ctr" align="center">
		<div class="login">
			<div class="login-form">
				<form action="index.php" method="post" name="loginForm" id="loginForm">
					<div class="form-block">
						<div class="inputlabel">
							Логин
						</div>
						<div>
							<input name="usrname" id="usrname" type="text" class="inputbox" size="15" />
						</div>
						<div>
							Пароль
						</div>
						<div align="center">
							<input name="pass" type="password" class="inputbox" size="15" />
						</div>
<?php
	if($mosConfig_captcha){
	session_start();
?>
						<div>
							<img id="loginCaptcha" alt="Нажмите что бы обновить изображение" onclick="document.loginForm.loginCaptcha.src='<?php echo $mosConfig_live_site; ?>/includes/kcaptcha/index.php?' + new String(Math.random())" src="<?php echo $mosConfig_live_site; ?>/includes/kcaptcha/index.php?<?php echo session_id()?>" />
						</div>
						<span class="captcha" onclick="document.loginForm.loginCaptcha.src='<?php echo $mosConfig_live_site; ?>/includes/kcaptcha/index.php?' + new String(Math.random())">Обновить изображение</span>
						<div>Введите код проверки с картинки выше:</div>
						<div>
							<input name="captcha" type="text" class="inputbox" size="15" />
						</div>
<?php }; ?>
						<div align="center">
							<input type="submit" name="submit" class="button" value="Войти" />
							<br />
							<input type="button" name="submit" onClick="document.location.href='<?php echo $mosConfig_live_site;?>'" class="button" value="Перейти на сайт" />
						</div>
					</div>
				</form>
			</div>
			<div class="login-text">
				<div class="ctr">
					<img src="templates/joostfree/images/lock.gif" alt="Joostina!" />
				</div>
			</div>
			<div class="clr"></div>
		</div>
	</div>
	<div id="break"></div>
	<noscript>
		!Предупреждение! Javascript должен быть разрешены для правильной работы панели управления администратора
	</noscript>
		<div id="footer" align="center">
			<div align="center">
				<?php echo $_VERSION->URL; ?>
			</div>
		</div>
</body>
</html>

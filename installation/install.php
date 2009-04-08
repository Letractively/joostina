<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

define("_VALID_MOS",1);

if(file_exists('../configuration.php') && filesize('../configuration.php') > 10) {
	header('Location: ../index.php');
	exit();
}
/** Include common.php*/
include_once ('common.php');

$lang = 'russian';

function writableCell($folder) {
	echo "<tr>";
	echo "<td class=\"item\">".$folder."/</td>";
	echo "<td align=\"left\">";
	echo is_writable("../$folder")?'<b><font color="green">Доступен для записи </font></b>':'<b><font color="red">Недоступен для записи</font></b>'."</td>";
	echo "</tr>";
}
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Joostina - Web-установка. Принятие лицензии</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="../images/favicon.ico" />
		<link rel="stylesheet" href="install.css" type="text/css" />
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<div id="joomla">
					<img src="img/header_install.png" alt="Установка Joostina" />
				</div>
			</div>
		</div>
		<div id="ctr" align="center">
		<form action="install1.php" method="post" name="adminForm" id="adminForm">
			<div class="install">
				<div id="step"><span>Лицензия</span>
					<div class="step-right">
						<input class="button" type="submit" name="next" value="Далее &gt;&gt;"/>
					</div>
				</div>
				<div id="stepbar">
					<div class="step-off">Проверка системы</div>
					<div class="step-on">Лицензия</div>
					<div class="step-off">Шаг 1</div>
					<div class="step-off">Шаг 2</div>
					<div class="step-off">Шаг 3</div>
					<div class="step-off">Шаг 4</div>
					<div class="step-off">
						<img src="img/img-1.png" alt="Установка Joostina" />
					</div>
				</div>
				<div id="right">
					<div class="clr"></div>
					<div class="licensetext">
						Joostina- свободное программное обеспечение, распространяемое по лицензии GNU/GPL, для использования системы Вы должны полностью согласиться с предоставленной лицензией.
					</div>
					<div class="clr"></div>
					<div class="license-form">
						<div class="form-block" style="padding: 0px;">
							<iframe src="lang/<?php echo $lang;?>/license.html" class="license" frameborder="0" scrolling="auto"></iframe>
						</div>
					</div>
				</div>
			<div id="break"></div>
			<div class="clr"></div>
			<div class="clr"></div>
			</div>
		</form>
		</div>
	<div class="ctr" id="footer"><a href="http://www.Joostina.ru" target="_blank">Joostina</a> - свободное программное обеспечение, распространяемое по лицензии GNU/GPL.</div>
</body>
</html>

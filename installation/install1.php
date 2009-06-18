<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/


// Set flag that this is a parent file
define("_VALID_MOS",1);
/** Include common.php*/
require_once ('common.php');
echo $DBhostname	= mosGetParam($_POST,'DBhostname','');
$DBuserName	= mosGetParam($_POST,'DBuserName','');
$DBpassword	= mosGetParam($_POST,'DBpassword','');
$DBname		= mosGetParam($_POST,'DBname','');
$DBPrefix	= mosGetParam($_POST,'DBPrefix','jos_');
$DBDel		= intval(mosGetParam($_POST,'DBDel',0));
$DBBackup	= intval(mosGetParam($_POST,'DBBackup',0));
$DBSample	= intval(mosGetParam($_POST,'DBSample',1));
$DBexp		= intval(mosGetParam($_POST,'DBexp',0));
// заменить на 1 для возможности выбора экспериментального типа базы данных
$YA_UVEREN = 0;

echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Joostina - Web-установка. Шаг 1 - конфигурация базы данных.</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="../images/favicon.ico" />
		<link rel="stylesheet" href="install.css" type="text/css" />
<script  type="text/javascript">
<!--
function check() {
// форма основной конфигурации
var formValid=false;
var f = document.form;
if ( f.DBhostname.value == '' ) {
alert('Пожалуйста, введите имя Хоста MySQL');
f.DBhostname.focus();
formValid=false;
} else if ( f.DBuserName.value == '' ) {
alert('Пожалуйста, введите имя пользователя Базы Данных MySQL');
f.DBuserName.focus();
formValid=false;
} else if ( f.DBname.value == '' ) {
alert('Пожалуйста, введите Имя для своей новой БД');
f.DBname.focus();
formValid=false;
} else if ( f.DBPrefix.value == '' ) {
alert('Для правильной работы Joostina Вы должны ввести префикс таблиц БД MySQL.');
f.DBPrefix.focus();
formValid=false;
} else if ( f.DBPrefix.value == 'old_' ) {
alert('Вы не можете использовать префикс таблиц "old_", так как Joostina использует его для создания резервных таблиц.');
f.DBPrefix.focus();
formValid=false;
} else if ( confirm('Вы уверены, что правильно ввели данные? \Joostina будет заполнять таблицы в БД, параметры которой Вы указали.')) {
formValid=true;
}
return formValid;
}
//-->
</script>
	</head>
	<body onload="document.form.DBhostname.focus();">
		<div id="wrapper">
			<div id="header">
				<div id="joomla">
					<img src="img/header_install.png" alt="Установка Joostina" />
				</div>
			</div>
		</div>
		<div id="ctr" align="center">
			<form action="install2.php" method="post" name="form" id="form" onsubmit="return check();">
				<div class="install">
					<div id="step"><span>Конфигурации базы данных</span>
						<div class="step-right">
							<input class="button" type="submit" name="next" value="Далее > >"/>
						</div>
					</div>
					<div id="stepbar">
						<div class="step-off">Проверка системы
						</div>
						<div class="step-off">Лицензия
						</div>
						<div class="step-on">Шаг 1
						</div>
						<div class="step-off">Шаг 2
						</div>
						<div class="step-off">Шаг 3
						</div>
						<div class="step-off">Шаг 4
						</div>
					</div>
					<div id="right">
						<div class="install-form">
							<div class="form-block">
								<table class="content2" width="100%">
									<tr class="trongate-1">
										<td colspan="2"> Имя хоста MySQL<br />
											<input class="inputbox" type="text" name="DBhostname" value="<?php echo ($DBhostname=='') ? 'localhost':$DBhostname; ?>" />
										</td>
										<td>
											<br />Обычно это &nbsp;<b>localhost</b>
										</td>
									</tr>
									<tr class="trongate-2">
										<td colspan="2" valign="top"> Имя пользователя MySQL<br />
											<input class="inputbox" type="text" name="DBuserName" value="<?php echo $DBuserName; ?>" />
										</td>
										<td>
											Для установки на домашнем компьютере чаще всего используется имя&nbsp;
											<b><font color="green">root</font></b>
											, а для установки в Интернете, введите данные, полученные у Хостера.
										</td>
									</tr>
									<tr class="trongate-1">
										<td colspan="2" valign="top"> Пароль доступа к БД MySQL<br />
											<input class="inputbox" type="text" name="DBpassword" value="<?php echo $DBpassword; ?>" />
										</td>
										<td>
											Оставьте поле пустым для домашней установки или введите пароль доступа к Вашей БД, полученный у хостера.
										</td>
									</tr>
									<tr class="trongate-2">
										<td colspan="2" valign="top"> Имя БД MySQL<br />
											<input class="inputbox" type="text" name="DBname" value="<?php echo $DBname; ?>" />
										</td>
										<td>
											Имя существующей или новой БД, которая будет использоваться для сайта
										</td>
									</tr>
									<tr class="trongate-2">
										<td colspan="2" valign="top"> Префикс таблиц БД MySQL<br />
											<input class="inputbox" type="text" name="DBPrefix" value="<?php echo $DBPrefix; ?>" />
										</td>
										<td>
											Используйте префикс таблиц для установки в одну БД.
											Не используйте <font color="red">'old_'</font> - это зарезервированное значение.
										</td>
									</tr>
									<tr class="trongate-1">
										<td valign="top">
											<input type="checkbox" name="DBDel" id="DBDel" value="1" <?php if($DBDel) echo 'checked="checked"'; ?> />
										</td>
										<td valign="top">
											<label for="DBDel">Удалить существующие таблицы.</label>
										</td>
										<td valign="top">
											Все существующие таблицы от предыдущих установок Joostina будут удалены.
										</td>
									</tr>
									<tr class="trongate-2">
										<td valign="top">
											<input type="checkbox" name="DBBackup" id="DBBackup" value="1" <?php if($DBBackup) echo 'checked="checked"'; ?> />
										</td>
										<td valign="top">
											<label for="DBBackup">Создать резервные копии существующих таблиц</label>
										</td>
										<td>
											Все существующие резервные копии таблиц от предыдущих установок Joostina будут заменены.
										</td>
									</tr>
									<tr class="trongate-1">
										<td valign="top">
											<input type="checkbox" name="DBSample" id="DBSample" value="1" <?php if($DBSample) echo 'checked="checked"'; ?> />
										</td>
										<td valign="top" width="200px">
											<label for="DBSample">Установить демонстрационные данные
											</label></td>
										<td valign="top">Не выключайте это, если Вы ещё не знакомы с Joostina!</td>
									</tr>
									<tr class="trongate-1">
										<td colspan="2" valign="top">
											<input type="checkbox" name="create_db" id="create_db" value="1" checked="checked" /><label for="create_db">Создать базу если её нет</label>
										</td>
										<td>
										Внимание! Не на всех хостингах создание БД таким способом будет возможно. В случае возникновения ошибок - создайте пустую БД стандартным для вашего хостинга способом и выберите её.
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="clr">
					</div>
				</div>
			</form>
		</div>
		<div class="clr"></div>
		 <div class="ctr" id="footer"><a href="http://www.Joostina.ru" target="_blank">Joostina</a> - свободное программное обеспечение, распространяемое по лицензии GNU/GPL.</div>
	</body>
</html>

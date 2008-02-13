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


// Set flag that this is a parent file
define( "_VALID_MOS", 1 );
/** Include common.php */
require_once( 'common.php' );
$DBhostname = mosGetParam( $_POST, 'DBhostname', '' );
$DBuserName = mosGetParam( $_POST, 'DBuserName', '' );
$DBpassword = mosGetParam( $_POST, 'DBpassword', '' );
$DBname     = mosGetParam( $_POST, 'DBname', '' );
$DBPrefix   = mosGetParam( $_POST, 'DBPrefix', 'jos_' );
$DBDel      = intval( mosGetParam( $_POST, 'DBDel', 0 ) );
$DBBackup   = intval( mosGetParam( $_POST, 'DBBackup', 0 ) );
$DBSample   = intval( mosGetParam( $_POST, 'DBSample', 1 ) );
$DBold      = intval( mosGetParam( $_POST, 'DBold', 0 ) );
$DBexp      = intval( mosGetParam( $_POST, 'DBexp', 0 ) );
// заменить на 1 для возможности выбора экспериментального типа базы даных
$YA_UVEREN = 0;

?>
<?php echo "<?xml version=\"1.0\" encoding=\"windows-1251\"?".">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Joostina - Web-установка. Шаг 1 ...
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
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
					<img src="header_install.png" alt="Установка Joostina" />
				</div>
			</div>
		</div>
		<div id="ctr" align="center">
			<form action="install2.php" method="post" name="form" id="form" onsubmit="return check();">
				<div class="install">
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
						<div class="far-right">
							<input class="button" type="submit" name="next" value="Далее > >"/>
						</div>
						<div id="step">Шаг 1
						</div>
						<div class="clr">
						</div><h1>Конфигурация базы данных MySQL:</h1>
						<div class="install-form">
							<div class="form-block">
								<table class="content2" width="100%">
									<tr class="trongate-1">
										<td colspan="2"> Имя хоста MySQL<br />
											<input class="inputbox" type="text" name="DBhostname" value="<?php echo "$DBhostname"; ?>" /></td><td><br />&nbsp;&nbsp;
											Обычно это &nbsp;<b>localhost</b>
										</td>
									</tr>
									<tr class="trongate-2">
										<td colspan="2" valign="top"> Имя пользователя MySQL<br />
											<input class="inputbox" type="text" name="DBuserName" value="<?php echo "$DBuserName"; ?>" /></td><td>&nbsp;&nbsp;
											Для установки на домашнем компьютере чаще всего используется имя&nbsp;
											<b>
												<font color="green">root
												</font></b>
											, а для установки в Интернете, введите данные, полученные у Хостера.
											</td>
									</tr>
									<tr class="trongate-1">
										<td colspan="2" valign="top"> Пароль доступа к БД MySQL<br />
											<input class="inputbox" type="text" name="DBpassword" value="<?php echo "$DBpassword"; ?>" /></td><td>&nbsp;&nbsp;
											 Оставьте поле пустым для домашней установки или введите пароль доступа к Вашей БД, полученный у хостера.
											</td>
									</tr>
									<tr class="trongate-2">
										<td colspan="2" valign="top"> Имя БД MySQL<br />
											<input class="inputbox" type="text" name="DBname" value="<?php echo "$DBname"; ?>" /></td><td>&nbsp;&nbsp;
											Имя существующей или новой БД, которая будет использоваться для сайта
											</td>
									</tr>
									<tr class="trongate-1">
										<td colspan="2" valign="top"> Префикс таблиц БД MySQL<br />
											<input class="inputbox" type="text" name="DBPrefix" value="<?php echo "$DBPrefix"; ?>" /></td><td>&nbsp;&nbsp;
											Используйте префикс таблиц для установки в одну БД.
											Не используйте <font color="red">'old_'</font> - это зарезервированный префикс.
											</td>
									</tr>
									<tr class="trongate-2">
										<td valign="top">
											<input type="checkbox" name="DBDel" id="DBDel" value="1" <?php if ($DBDel) echo 'checked="checked"'; ?> /></td>
										<td valign="top">
											<label for="DBDel">Удалить существующие таблицы.
											</label></td>
										<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;
											 Все существующие таблицы от предыдущих установок Joostina будут удалены.
											</td>
									</tr>
									<tr class="trongate-1">
										<td valign="top">
											<input type="checkbox" name="DBBackup" id="DBBackup" value="1" <?php if ($DBBackup) echo 'checked="checked"'; ?> /></td>
										<td valign="top">
											<label for="DBBackup">Создать резервные копии существующих таблиц
											</label></td><td>&nbsp;&nbsp;&nbsp;&nbsp;
											 Все существующие резервные копии таблиц от предыдущих установок Joostina будут заменены.
											</td>
									</tr>
									<tr class="trongate-2">
										<td valign="top">
											<input type="checkbox" name="DBSample" id="DBSample" value="1" <?php if ($DBSample) echo 'checked="checked"'; ?> /></td>
										<td valign="top" width="200px">
											<label for="DBSample">Установить демонстрационные данные
											</label></td>
										<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;
											Не выключайте это, если Вы ещё не знакомы с Joostina!
											</td>
									</tr>
									<tr class="trongate-1">
										<td valign="top">
											<input type="checkbox" name="DBold" id="DBold" value="1" <?php if ($DBold) echo 'checked="checked"'; ?> /></td>
										<td valign="top">
											<label for="DBold">Поддержка MySQL младше 4.1
											</label></td><td>&nbsp;&nbsp;&nbsp;&nbsp;
											 Использовать работу в режиме совместимости с младшими версиями базы данных.
											</td>
									</tr>
									<?php if($YA_UVEREN){?>
									<tr class="trongate-2">
										<td valign="top">
											<input type="checkbox" name="DBexp" id="DBexp" value="1" <?php if ($DBexp) echo 'checked="checked"'; ?> /></td>
										<td valign="top">
											<label for="DBexp">Новый тип таблиц
											</label></td><td>&nbsp;&nbsp;&nbsp;&nbsp;
											<font color="red"><b>ВНИМАНИЕ! Экспериментальный пункт.</b><br />Использовать новый тип таблиц для работы системы.</font>
										</td>
									</tr>
									<?php };?>
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

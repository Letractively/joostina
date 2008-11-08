<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

global $JPConfiguration,$option;

$task	= mosGetParam($_REQUEST,'task','default');
$act	= mosGetParam($_REQUEST,'act','default');

?>
<table class="adminheading">
	<tr>
		<th class="config" nowrap rowspan="2">Конфигурация резервного копирования данных</th>
	</tr>
</table>
<div class="message">Сохранение настроек: <?php echo colorizeWriteStatus($JPConfiguration->isConfigurationWriteable());?></div>
<form action="index2.php" method="post" name="adminForm">
	<table class="adminform">
		<tr align="center" valign="middle">
			<th colspan="2">Основные настройки</th>
		</tr>
		<tr class="row0">
			<td width="30%">Каталог сохранения архивов:</td>
			<td><input class="inputbox" type="text" name="outdir" size="60" value="<?php echo $JPConfiguration->OutputDirectory; ?>" /></td>
		</tr>
		<tr class="row0">
			<td>Название архива:</td>
			<td><input class="inputbox" type="text" name="tarname" size="60" value="<?php echo $JPConfiguration->TarNameTemplate; ?>" /></td>
		</tr>
		<tr class="row1">
			<td>Уровень ведения лога:</td>
			<td><?php outputLogLevel($JPConfiguration->logLevel); ?></td>
		</tr>
		<tr>
			<th colspan="2">Дополнительные настройки</th>
		</tr>
		<tr class="row1">
			<td>Удалять преффикс таблиц:</td>
			<td><?php echo mosHTML::yesnoRadioList('sql_pref','class="inputbox"',$JPConfiguration->sql_pref); ?></td>
		</tr>
		<tr class="row0">
			<td>Тип экспорта базы данных:</td>
			<td><?php outputSQLCompat($JPConfiguration->MySQLCompat); ?></td>
		</tr>
		<tr class="row1">
			<td>Обработка файлов:</td>
			<td><?php AlgorithmChooser($JPConfiguration->fileListAlgorithm,"fileListAlgorithm"); ?></td>
		</tr>
		<tr class="row0">
			<td>Обработка базы:</td>
			<td><?php AlgorithmChooser($JPConfiguration->dbAlgorithm,"dbAlgorithm"); ?></td>
		</tr>
		<tr class="row1">
			<td>Сжатие файлов:</td>
			<td><?php AlgorithmChooser($JPConfiguration->packAlgorithm,"packAlgorithm"); ?></td>
		</tr>
		<tr class="row0">
			<td>Сжатие дампа базы данных:</td>
			<td><?php outputBoolChooser($JPConfiguration->sql_pack); ?></td>
		</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="act" value="config" />
</form>
<?php

// доступность сохранения настроек
function colorizeWriteStatus($status) {
	if($status) {
		return '<font color="green"><b>доступно</b></font>';
	} else {
		return '<font color="red"><b>не доступно</b></font>';
	}
}
// тип экспорта базы данных
function outputSQLCompat($sqlcompat) {
	$options = array(array(
		"value" => "compat","desc" =>"В режиме совместимости с MySQL 4"),
			array(
		"value" => "default","desc" =>"По умолчанию"));
	echo '<select class="inputbox" name="sqlcompat">';
	foreach($options as $choice) {
		$selected = ($sqlcompat == $choice['value'])?"selected":"";
		echo "<option value=\"".$choice['value']."\" $selected>".$choice['desc']."</option>";
	}
	echo '</select>';
}
// типы сжатия
function outputBoolChooser($boolOption) {
	echo '<select class="inputbox" name="sql_pack">';
		$selected = ($boolOption == "0")?"selected":"";
		echo "<option value=\"0\" $selected>Не архивировать (.sql)</option>";
		$selected = ($boolOption == "1")?"selected":"";
		echo "<option value=\"1\" $selected>Архивировать в TAR.GZ (.tar.gz)</option>";
		$selected = ($boolOption == "2")?"selected":"";
		echo "<option value=\"2\" $selected>Архивировать в ZIP (.zip)</option>";
	echo '</select>';
}
// резервирования
function AlgorithmChooser($strOption,$strName) {
	echo "<select class=\"inputbox\" name=\"$strName\">";
	$selected = ($strOption == "single")?"selected":"";
	echo "<option value=\"single\" $selected>Быстро - один проход</option>";
	$selected = ($strOption == "smart")?"selected":"";
	echo "<option value=\"smart\" $selected>Рекомендовано - Стандартно</option>";
	$selected = ($strOption == "multi")?"selected":"";
	echo "<option value=\"multi\" $selected>Медленно - мультипроходность</option>";
	echo '</select>';
}
// список уровней регистрации лога
function outputLogLevel($strOption) {
	echo '<select class="inputbox" name="logLevel">';
		$selected = ($strOption == "1")?"selected":"";
		echo "<option value=\"1\" $selected>Только ошибки</option>";
		$selected = ($strOption == "2")?"selected":"";
		echo "<option value=\"2\" $selected>Ошибки и предупреждения</option>";
		$selected = ($strOption == "3")?"selected":"";
		echo "<option value=\"3\" $selected>Вся информация</option>";
		$selected = ($strOption == "4")?"selected":"";
		echo "<option value=\"4\" $selected>Вся информация и отладка</option>";
	echo '</select>';
}
?>

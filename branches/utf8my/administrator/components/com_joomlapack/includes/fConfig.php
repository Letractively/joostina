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
require(dirname(__FILE__).'/../../../die.php');

global $JPConfiguration, $JPLang;

$task		= mosGetParam( $_REQUEST, 'task', 'default' );
$act		= mosGetParam( $_REQUEST, 'act', 'default' );

?>
<table class="adminheading">
	<tr>
		<th class="config" nowrap rowspan="2">
			<?php echo $JPLang['common']['jptitle']; ?>
		</th>
	</tr>
	<tr>
		<td nowrap><h2><?php echo $JPLang['cpanel']['config'];?></h2></td>
	</tr>
</table>
<?php

echo "<p align=\"center\">" . $JPLang['config']['filestatus'] . colorizeWriteStatus($JPConfiguration->isConfigurationWriteable()) . "</p>";

outputConfig();

function outputConfig() {
	global $JPConfiguration, $JPLang, $option;
?>
	<form action="index2.php" method="post" name="adminForm">
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="act" value="config" />
		<table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">
			<tr align="center" valign="middle">
				<th width="20%">&nbsp;</th>
				<th width="20%"><?php echo $JPLang['config']['option']; ?></th>
				<th width="60%"><?php echo $JPLang['config']['cursettings']; ?></th>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['common']['outdir']; ?></td>
				<td><input class="inputbox" type="text" name="outdir" size="40" value="<?php echo $JPConfiguration->OutputDirectory; ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['common']['tempdir']; ?></td>
				<td><input class="inputbox" type="text" name="tempdir" size="40" value="<?php echo $JPConfiguration->TempDirectory; ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['tarname']; ?></td>
				<td><input class="inputbox" type="text" name="tarname" size="40" value="<?php echo $JPConfiguration->TarNameTemplate;?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['loglevel']; ?></td>
				<td><?php outputLogLevel( $JPConfiguration->logLevel ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" align="center"><h3><?php echo $JPLang['config']['advanced_options']; ?></h3></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['sqlcompat']; ?></td>
				<td><?php outputSQLCompat( $JPConfiguration->MySQLCompat ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['fla_label']; ?></td>
				<td><?php AlgorithmChooser( $JPConfiguration->fileListAlgorithm, "fileListAlgorithm" ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['dba_label']; ?></td>
				<td><?php AlgorithmChooser( $JPConfiguration->dbAlgorithm, "dbAlgorithm" ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['pa_label']; ?></td>
				<td><?php AlgorithmChooser( $JPConfiguration->packAlgorithm, "packAlgorithm" ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['compress']; ?></td>
				<td><?php outputBoolChooser( $JPConfiguration->boolCompress ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['altinstaller']; ?></td>
				<td><?php echo AltInstallerChooser( $JPConfiguration->InstallerPackage ); ?></td>
			</tr>
		</table>
	</form>
<?php
}

function colorizeWriteStatus( $status ) {
	global $JPLang;

	if ( $status ) {
		return '<span class="statusok">' . $JPLang['common']['writable']  . '</span>';
	} else {
		return '<span class="statusnotok">' . $JPLang['common']['unwritable'] . '</span>';
	}
}

function outputSQLCompat( $sqlcompat ) {
	global $JPLang;

	$options = array(
		array("value" => "compat", "desc" => $JPLang['config']['compat']),
		array("value" => "default", "desc" => $JPLang['config']['default'])
	);

	echo '<select class="inputbox" name="sqlcompat">';
	foreach( $options as $choice ) {
		$selected = ( $sqlcompat == $choice['value'] ) ? "selected" : "";
		echo "<option value=\"". $choice['value'] ."\" $selected>". $choice['desc'] ."</option>";
	}
	echo '</select>';
}

function outputBoolChooser( $boolOption ) {
	global $JPLang;

	echo '<select class="inputbox" name="compress">';
	$selected = ($boolOption == "zip") ? "selected" : "";
	echo "<option value=\"zip\" $selected>". $JPLang['config']['zip'] ."</option>";
	$selected = ($boolOption == "jpa") ? "selected" : "";
	echo "<option value=\"jpa\" $selected>". $JPLang['config']['jpa'] ."</option>";
	echo '</select>';
}

function AlgorithmChooser( $strOption, $strName ) {
	global $JPLang;

	echo "<select class=\"inputbox\" name=\"$strName\">";
	$selected = ($strOption == "single") ? "selected" : "";
	echo "<option value=\"single\" $selected>". $JPLang['config']['single'] ."</option>";
	$selected = ($strOption == "smart") ? "selected" : "";
	echo "<option value=\"smart\" $selected>". $JPLang['config']['smart'] ."</option>";
	$selected = ($strOption == "multi") ? "selected" : "";
	echo "<option value=\"multi\" $selected>". $JPLang['config']['multi'] ."</option>";
	echo '</select>';
}

function AltInstallerChooser( $strOption ) {
	global $JPConfiguration;

	$altInstallers = $JPConfiguration->AltInstaller->loadAllDefinitions();
	echo '<select class="inputbox" name="altInstaller">';
	foreach ($altInstallers as $altInstaller) {
		$selected = ($strOption == $altInstaller['meta']) ? "selected" : "";
		echo "<option value=\"" . $altInstaller['meta'] . "\" $selected>". $altInstaller['name'] ."</option>";
	}
	echo '</select>';
}

function outputLogLevel( $strOption ) {
	global $JPConfiguration, $JPLang;

	echo '<select class="inputbox" name="logLevel">';
	$selected = ($strOption == "1") ? "selected" : "";
	echo "<option value=\"1\" $selected>". $JPLang['config']['llerror'] ."</option>";
	$selected = ($strOption == "2") ? "selected" : "";
	echo "<option value=\"2\" $selected>". $JPLang['config']['llwarning'] ."</option>";
	$selected = ($strOption == "3") ? "selected" : "";
	echo "<option value=\"3\" $selected>". $JPLang['config']['llinfo'] ."</option>";
	$selected = ($strOption == "4") ? "selected" : "";
	echo "<option value=\"4\" $selected>". $JPLang['config']['lldebug'] ."</option>";
	echo '</select>';
}
?>

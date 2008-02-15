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

global $JPConfiguration, $JPLang, $option, $mosConfig_live_site;

// Get location and writable status of output directory and temporary folder
$WSOutdir = $JPConfiguration->isOutputWriteable();
$WSTemp = $JPConfiguration->isTempWriteable();

$appStatusGood = true;
if (!($WSOutdir && $WSTemp)) {
	$appStatusGood = false;
}

?>
<table class="adminheading">
	<tr>
		<th class="cpanel" nowrap rowspan="2">
			<?php echo $JPLang['common']['jptitle']; ?>
		</th>
	</tr>
</table>
<table class="adminform">
	<tr>
		<td width="55%" valign="top">
			<div id="cpanel">
<?php
				$link = "index2.php?option=$option&act=pack";
				JP_quickiconButton( $link, 'backup.png', $JPLang['cpanel']['pack'] );
				$link = 'index2.php?option=com_ebackup';
				JP_quickiconButton( $link, 'ebackup.png', 'Управление базой данных' );
				$link = "index2.php?option=$option&act=backupadmin";
				JP_quickiconButton( $link, 'archive_f2.png', "" . $JPLang['cpanel']['buadmin'] );
				$link = "index2.php?option=$option&act=config";
				JP_quickiconButton( $link, 'config.png', $JPLang['cpanel']['config'] );
				$link = 'index2.php?option=com_ebackup&task=viewSetup';
				JP_quickiconButton( $link, 'config.png', 'Настройки управления базой');
				$link = "index2.php?option=$option&act=def";
				JP_quickiconButton( $link, 'config.png', $JPLang['cpanel']['def'] );
				$link = "index2.php?option=$option&act=log";
				JP_quickiconButton( $link, 'config.png', $JPLang['cpanel']['log'] );
?>
			</div>
			<div style="clear:both;"> </div>
		</td>
		<td width="45%" valign="top">
<?php
			$tabs = new mosTabs(1);
			$tabs->startPane(1);
			$tabs->startTab($JPLang['main']['overview'],'jpstatusov');
?>
				<p class="sanityCheck"><?php echo $JPLang['main']['status'] . ": " . colorizeAppStatus( $appStatusGood ); ?></p>
<?php
			$tabs->endTab();
			$tabs->startTab($JPLang['main']['details'],'jpstatusdet');
?>
				<table align="center" border="0" cellspacing="0" cellpadding="5" class="adminlist">
					<thead>
						<th class="title"><?php echo $JPLang['main']['item']; ?></th>
						<th><?php echo $JPLang['main']['status']; ?></th>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $JPLang['common']['tempdir']; ?></td>
							<td><?php echo colorizeWriteStatus($WSTemp, true); ?></td>
						</tr>
						<tr>
							<td><?php echo $JPLang['common']['outdir']; ?></td>
							<td><?php echo colorizeWriteStatus($WSOutdir, true); ?></td>
						</tr>
					</tbody>
				</table>
<?php
			$tabs->endTab();
			$tabs->endPane();
?>
		</td>
	</tr>
</table>

<?php

/**
	Colorizes (red/green) the writable status of various components
*/
function colorizeWriteStatus( $status, $okstatus ) {
	global $JPLang;

	$statusVerbal = $status ? $JPLang['common']['writable'] : $JPLang['common']['unwritable'];
	if ( $status == $okstatus ) {
		return '<span class="statusok">' . $statusVerbal . '</span>';
	} else {
		return '<span class="statusnotok">' . $statusVerbal . '</span>';
	}
}

/**
	Colorizes (red/green) the overall application status
*/
function colorizeAppStatus( $status ) {
	global $JPLang;

	$statusVerbal = $status ? $JPLang['main']['appgood'] : $JPLang['main']['appnotgood'];
	if ( $status ) {
		return '<span class="statusok">' . $statusVerbal . '</span>';
	} else {
		return '<span class="statusnotok">' . $statusVerbal . '</span>';
	}
}

/**
	Creates one of those cool cpanel kind of icons
*/
function JP_quickiconButton( $link, $image, $text ) {
	?>
	<div style="float:left;">
		<div class="icon">
			<a href="<?php echo $link; ?>">
				<?php echo mosAdminMenus::imageCheckAdmin( $image, '/administrator/images/', NULL, NULL, $text ); ?>
				<span><?php echo $text; ?></span>
			</a>
		</div>
	</div>
	<?php
}
?>

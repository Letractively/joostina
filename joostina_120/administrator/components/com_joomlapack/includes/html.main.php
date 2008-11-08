<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

global $JPConfiguration,$option,$mosConfig_live_site,$mosConfig_absolute_path;

$WSOutdir = $JPConfiguration->isOutputWriteable();

$appStatusGood = true;
if(!($WSOutdir)) {
	$appStatusGood = false;
}

// информация о состоянии пакера
echo colorizeAppStatus($appStatusGood);
?>

<table class="adminheading">
	<tr>
		<th class="cpanel">Резервное копирование</th>
	</tr>
</table>
<table>
	<tr>
		<td width="40%" valign="top">
			<div class="cpicons">
<?php
	$link = "index2.php?option=com_joomlapack&act=pack";
	quickiconButton($link,'pack.png', 'Создать архив данных');

	$link = 'index2.php?option=com_joomlapack&act=db';
	quickiconButton($link,'db.png','Управление базой данных');

	$link = "index2.php?option=com_joomlapack&act=def";
	quickiconButton($link,'stopfolder.png', 'Не сохранять каталоги');

	$link = "index2.php?option=com_joomlapack&act=config";
	quickiconButton($link,'config.png', 'Настройки сохранения');

	$link = "index2.php?option=com_joomlapack&act=log";
	quickiconButton($link,'log.png', 'Лог выполнения действий');
?>
				</div>
			<div style="clear:both;">&nbsp;</div>
		</td>
		<td valign="top">
		<?php
		require_once ($mosConfig_absolute_path.'/administrator/components/com_joomlapack/includes/html.files.php');
		?>
		</td>
	</tr>
</table>

<?php

/**
* вывод итогового состояния пакера
*/
function colorizeAppStatus($status) {
	global $JPConfiguration;
	$statusVerbal = 'Обнаружены ошибки, проверьте возможность записи в каталог хранения резервных копий ( <b>'.$JPConfiguration->OutputDirectory.'</b> )';
	if(!$status) {
		return '<div class="jwarning">'.$statusVerbal.'</div>';
	}
}
// прорисовка кнопок управления
function quickiconButton($link,$image,$text) {
?>
	<span>
		<a href="<?php echo $link; ?>" title="<?php echo $text; ?>">
<?php
			echo mosAdminMenus::imageCheckAdmin($image,'/administrator/images/',null,null,$text);
			echo $text;
?>
		</a>
	</span>
	<?php
}
?>

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

function writableCell($folder) {
	echo '<tr><td class="item">'.$folder.'/</td><td align="left">';
	echo is_writable($GLOBALS['mosConfig_absolute_path'].'/'.$folder)?'<b><font color="green">Доступен для записи</font></b>':'<b><font color="red">Недоступен для записи</font></b></td>';
	echo '</tr>';
}

/**
* @package Joostina
*/
class HTML_installer {

	function showInstallForm($title,$option,$element,$client = "",$p_startdir = "",
		$backLink = "") {
?>
	<script language="javascript" type="text/javascript">
		function submitbutton3(pressbutton) {
			var form = document.adminForm_dir;
			if (form.userfile.value == ""){
				alert( "Пожалуйста, выберите каталог" );
			} else {
				form.submit();
			}
		}
	</script>
		<table class="adminheading">
		<tr>
			<th class="install"><?php echo $title; ?></th>
			<td align="right" class="jtd_nowrap"><?php echo $backLink; ?></td>
		</tr>
		</table>
		<table width="100%">
			<tr>
				<td style="width:49%">
					<form enctype="multipart/form-data" action="index2.php" method="post" name="filename">
					<table class="adminform">
					<tr>
						<th>Загрузка архива расширения с последующей установкой</th>
					</tr>
					<tr>
						<td align="left">
							Файл пакета:
							<input class="text_area" name="userfile" type="file" size="50"/>
							<input class="button" type="submit" value="Загрузить и установить" />
						</td>
					</tr>
					</table>
					<input type="hidden" name="task" value="uploadfile"/>
					<input type="hidden" name="option" value="<?php echo $option; ?>"/>
					<input type="hidden" name="element" value="<?php echo $element; ?>"/>
					<input type="hidden" name="client" value="<?php echo $client; ?>"/>
					<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
					</form>
				</td>
				<td style="width:49%">
					<form enctype="multipart/form-data" action="index2.php" method="post" name="adminForm_dir">
					<table class="adminform">
					<tr>
						<th>Установка из каталога</th>
					</tr>
					<tr>
						<td align="left">
							Каталог установки:
							<input type="text" name="userfile" class="text_area" size="50" value="<?php echo $p_startdir; ?>"/>
							<input type="button" class="button" value="Установить" onclick="submitbutton3()" />
						</td>
					</tr>
					</table>
					<input type="hidden" name="task" value="installfromdir" />
					<input type="hidden" name="option" value="<?php echo $option; ?>"/>
					<input type="hidden" name="element" value="<?php echo $element; ?>"/>
					<input type="hidden" name="client" value="<?php echo $client; ?>"/>
					<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
					</form>
				</td>
			</tr>
		</table>

		<?php
	}

	/**
	* @param string
	* @param string
	* @param string
	* @param string
	*/
	function showInstallMessage($message,$title,$url) {
		global $PHP_SELF;
?>
	<table class="adminheading">
		<tr>
			<th class="install"><?php echo $title; ?></th>
		</tr>
	</table>
	<table class="adminform">
		<tr>
			<td align="left"><strong><?php echo $message; ?></strong></td>
		</tr>
		<tr>
			<td colspan="2" align="center">[&nbsp;<a href="<?php echo $url; ?>" style="font-size: 16px; font-weight: bold">Продолжить ...</a>&nbsp;]</td>
		</tr>
	</table>
		<?php
	}
}
?>

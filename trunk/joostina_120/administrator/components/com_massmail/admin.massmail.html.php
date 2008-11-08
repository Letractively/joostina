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

/**
* @package Joostina
* @subpackage Massmail
*/
class HTML_massmail {
	function messageForm(&$lists,$option) {
?>
		<script language="javascript" type="text/javascript">
			function submitbutton(pressbutton) {
				var form = document.adminForm;
				if (pressbutton == 'cancel') {
					submitform( pressbutton );
					return;
				}
				// do field validation
				if (form.mm_subject.value == ""){
					alert( "Пожалуйста, впишите тему" );
				} else if (getSelectedValue('adminForm','mm_group') < 0){
					alert( "Пожалуйста, выберите группу" );
				} else if (form.mm_message.value == ""){
					alert( "Пожалуйста, заполните сообщение" );
				} else {
					submitform( pressbutton );
				}
			}
		</script>

		<form action="index2.php" name="adminForm" method="post">
		<table class="adminheading">
		<tr>
			<th class="massemail">
			Рассылка почты
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th colspan="2">
			Детали
			</th>
		</tr>
		<tr>
			<td width="150" valign="top">
			Группа:
			</td>
			<td width="85%">
			<?php echo $lists['gid']; ?>
			</td>
		</tr>
		<tr>
			<td>
			Отправить подчиненным группам:
			</td>
			<td>
			<input type="checkbox" name="mm_recurse" value="RECURSE" />
			</td>
		</tr>
		<tr>
			<td>
			Отправить в HTML-формате:
			</td>
			<td>
			<input type="checkbox" name="mm_mode" value="1" />
			</td>
		</tr>
		<tr>
			<td>
			Тема:
			</td>
			<td>
			<input class="inputbox" type="text" name="mm_subject" value="" size="50"/>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Сообщение:
			</td>
			<td>
			<textarea cols="80" rows="25" name="mm_message" class="inputbox"></textarea>
			</td>
		</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>"/>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}
}
?>

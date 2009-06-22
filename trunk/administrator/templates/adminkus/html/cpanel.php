<?php
/**
* @JoostFREE
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

?>
<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td valign="top" width="65%" class="cicons">
        <?php
// загрузка модулей панели управления позиции icon без использования деления модулей по вкладкам
mosLoadAdminModules('icon',0);
?>
        </td>

        <td valign="top"  class="notepad">
            <textarea>Запишите сюда что-нибудь. Ну пожааалуйста...</textarea>
        </td>
    </tr>
</table>
<br />
<form action="index2.php" method="post" name="adminForm" id="adminForm">
<table width="100%">
	<tr>
		<td width="65%" valign="top">
<?php
	// загрузка модулей панели управления позиции advert1 c использованием деления модулей по вкладкам
	mosLoadAdminModules('advert1',0);
?>
		</td><td valign="top">
<?php
	// загрузка модулей панели управления позиции advert1 c использованием деления модулей по вкладкам
	mosLoadAdminModules('advert2',1);
?>
		</td>
	</tr>
</table>
</form>
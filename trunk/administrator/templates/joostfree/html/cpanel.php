<?php
/**
* @JoostFREE
* @package Joostina
* @copyright јвторские права (C) 2008-2009 Joostina team. ¬се права защищены.
* @license Ћицензи€ http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распростран€емое по услови€м лицензии GNU/GPL
* ƒл€ получени€ информации о используемых расширени€х и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет пр€мого доступа
defined('_VALID_MOS') or die();

?>
<?php mosLoadAdminModules('icon',0); ?>
<form action="index2.php" method="post" name="adminForm" id="adminForm">
<table width="100%">
	<tr>
		<td width="65%" valign="top">
<?php
	// загрузка модулей панели управлени€ позиции advert1 c использованием делени€ модулей по вкладкам
	mosLoadAdminModules('advert1',0);
?>
		</td>
		<td width="35%" valign="top">
<?php
	// загрузка модулей панели управлени€ позиции advert1 c использованием делени€ модулей по вкладкам
	mosLoadAdminModules('advert2',0);
?>
		</td>
	</tr>
</table>
</form>
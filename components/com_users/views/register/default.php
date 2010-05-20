<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();
?>
<div class="componentheading"><h1>Регистрация</h1></div>
<form action="<?php echo sefRelToAbs('index.php?option=com_users&task=register',true) ?>" method="post">
  <table width="100%">
    <tr>
      <td colspan="2"><?php echo $user->getError(); ?></td>
    </tr>
    <tr>
      <td width="30%" align="right"><?php echo _REGISTER_NAME; ?></td>
      <td><input type="text" name="username" size="40" value="<?php echo $user->username ?>" class="inputbox" maxlength="20" /></td>
    </tr>
    <tr>
      <td align="right"><?php echo _REGISTER_EMAIL; ?></td>
      <td><input type="text" name="email" size="40" value="<?php echo $user->email ?>" class="inputbox" maxlength="50" /></td>
    </tr>
    <tr>
      <td align="right"><?php echo _REGISTER_PASSWORD; ?></td>
      <td><input class="inputbox" type="password" name="password" size="40" value="" /></td>
    </tr>
    <tr>
      <td align="right"><?php echo _REGISTER_VPASS; ?></td>
      <td><input class="inputbox" type="password" name="password2" size="40" value="" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="<?php echo _BUTTON_SEND_REG; ?>" class="button" /></td>
    </tr>
  </table>
  <input type="hidden" name="<?php echo josSpoofValue() ?>" value="1" />
</form>
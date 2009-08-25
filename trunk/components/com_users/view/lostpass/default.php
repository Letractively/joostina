<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

// used for spoof hardening
$validate = josSpoofValue();

?>
		<form action="index.php" method="post" name="mosForm" id="mosForm">
		
			<div class="componentheading"><h1><?php echo $user_config->get('title');?></h1></div>
			
			<div class="info"><?php echo _NEW_PASS_DESC; ?></div>
			
			<label for="checkusername"><?php echo _PROMPT_UNAME; ?></label>
			<input type="text" name="checkusername" class="inputbox" size="40" maxlength="25" />

			<label for="confirmEmail"><?php echo _PROMPT_EMAIL; ?></label>
			<input type="text" name="confirmEmail" class="inputbox" size="40" />
			

			<?php if($config->config_captcha_reg) { session_start(); ?>
			<div class="captcha">
				<img id="captchaimg" alt="<?php echo _PRESS_HERE_TO_RELOAD_CAPTCHA?>" onclick="document.mosForm.captchaimg.src='<?php echo $config->config_live_site; ?>/includes/libraries/kcaptcha/index.php?<?php echo session_name()?>=<?php echo session_id() ?>&' + new String(Math.random())" src="<?php echo $config->config_live_site; ?>/includes/libraries/kcaptcha/index.php?<?php echo session_name()?>=<?php echo session_id() ?>" />
				
				<label for="captcha"><?php echo _REG_CAPTCHA; ?></label>
				<input type="text" name="captcha" class="inputbox" size="40" value=""/>				
			</div>			
			<?php } ?>
			
			<span class="button"><input type="submit" class="button" value="<?php echo _BUTTON_SEND_PASS; ?>" /></span>

			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="sendNewPass" /> 
			<input type="hidden" name="<?php echo $validate; ?>" value="1" />
		
		</form>
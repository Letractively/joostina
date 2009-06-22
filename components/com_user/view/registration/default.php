<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008-2009 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

// ������ ������� �������
defined('_VALID_MOS') or die();
$mainframe->SetPageTitle($params->get('title'));

?>
		<script language="javascript" type="text/javascript">
		function submitbutton_reg() {
			var form = document.mosForm;
			var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");

			// do field validation
			if (form.name.value == "") {
				alert( "<?php echo addslashes(html_entity_decode(_REGWARN_NAME)); ?>" );
			} else if (form.username.value == "") {
				alert( "<?php echo addslashes(html_entity_decode(_REGWARN_UNAME)); ?>" );
			} else if (r.exec(form.username.value) || form.username.value.length < 3) {
				alert( "<?php printf(addslashes(html_entity_decode(_VALID_AZ09_USER)),addslashes(html_entity_decode(_PROMPT_UNAME)),2); ?>" );
			} else if (form.email.value == "") {
				alert( "<?php echo addslashes(html_entity_decode(_REGWARN_MAIL)); ?>" );
			} else if (form.password.value.length < 6) {
				alert( "<?php echo addslashes(html_entity_decode(_REGWARN_PASS)); ?>" );
			} else if (form.password2.value == "") {
				alert( "<?php echo addslashes(html_entity_decode(_REGWARN_VPASS1)); ?>" );
			} else if ((form.password.value != "") && (form.password.value != form.password2.value)){
				alert( "<?php echo addslashes(html_entity_decode(_REGWARN_VPASS2)); ?>" );
			} else if (r.exec(form.password.value)) {
				alert( "<?php printf(addslashes(html_entity_decode(_VALID_AZ09)),addslashes(html_entity_decode(_REGISTER_PASS)),6); ?>" );
			}
			
			<?php if($mosConfig_captcha_reg){?>
				else if (form.captcha.value == "") {
					alert( "<?php echo addslashes(html_entity_decode(_REG_CAPTCHA_VAL)); ?>" );
				}
			<?php };?>
			
			
			else {
				form.submit();
			}
		}
		</script>
		<form action="index.php" method="post" name="mosForm" id="mosForm">
		<div class="componentheading"><h1><?php echo $params->get('title'); ?></h1></div>
		
		<?php if($params->get('pre_text')){
			?>
			<div class="info">
				<?php echo $params->get('pre_text'); ?>	
			</div>
			<?php
		}  ?>
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr>
			<td colspan="2"><?php echo _REGISTER_REQUIRED; ?></td>
		</tr>
		<tr>
			<td width="30%"><?php echo _REGISTER_NAME; ?>*</td>
			<td>
				<input type="text" name="name" size="40" value="" class="inputbox" maxlength="50" />
			</td>
		</tr>
		<tr>
			<td><?php echo _REGISTER_UNAME; ?>*</td>
			<td>
				<input type="text" name="username" size="40" value="" class="inputbox" maxlength="25" />
			</td>
		</tr>
		<tr>
			<td><?php echo _REGISTER_EMAIL; ?>*</td>
			<td>
				<input type="text" name="email" size="40" value="" class="inputbox" maxlength="100" />
			</td>
		</tr>
		<tr>
			<td><?php echo _REGISTER_PASS; ?>*</td>
			<td>
				<input class="inputbox" type="password" name="password" size="40" value="" />
			</td>
		</tr>
		<tr>
			<td>
				<?php echo _REGISTER_VPASS; ?>*
			</td>
			<td>
				<input class="inputbox" type="password" name="password2" size="40" value="" />
			</td>
		</tr>
		
		<?php if($mosConfig_captcha_reg) { ?>
		<tr>
			<td>&nbsp;</td>
			<td>
				<img id="captchaimg" alt="<?php echo _REG_CAPTCHA_REF; ?>" onclick="document.mosForm.captchaimg.src='<?php echo $mosConfig_live_site; ?>/includes/libraries/kcaptcha/index.php?' + new String(Math.random())" src="<?php echo $mosConfig_live_site; ?>/includes/libraries/kcaptcha/index.php?<?php echo session_id() ?>" />
			</td>
		</tr>
		<tr>
			<td><?php echo _REG_CAPTCHA; ?></td>
			<td>
				<input type="text" name="captcha" class="inputbox" size="40" value=""/>
			</td>
		</tr>
		<?php } ?>
		
		
		</table>
		
		<?php if($params->get('post_text')){
			?>
			<div class="info">
				<?php echo $params->get('post_text'); ?>	
			</div>
			<?php
		}  ?>


		<span class="button"><input type="button" value="<?php echo _BUTTON_SEND_REG; ?>" class="button" onclick="submitbutton_reg()" /></span>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="saveRegistration" />		
		<input type="hidden" name="<?php echo $validate; ?>" value="1" />
		</form>

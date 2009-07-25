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

//Подключение плагина валидации форм
mosCommonHTML::loadJqueryPlugins('jquery.validate', false, false, 'js');

?><div class="page_edit_profile">
	<div class="componentheading"><h1><?php echo $user->name; ?>&nbsp;(<?php echo $user->username; ?>)</h1></div>
	
	<?php $tabs->startPane("userInfo"); ?>

	<form action="index.php" method="post" name="mosUserForm" id="mosUserForm">
		<div style="float: right;height: 100%;">

		<span class="button"><button type="submit" class="button submit" name="submit" id="save">Сохранить</button></span>
		<span class="button"><button type="submit" class="button cancel" name="cancel" id="cancel">Отмена</button></span>

	</div>
	<?php $tabs->startTab(_GENERAL,"general"); ?>
			<h3>Данные аккаунта</h3>
			<table width="100%">
				<tr>
					<td><label for="username"><?php echo _UNAME; ?></label></td>
					<td><input class="inputbox required" type="text" name="username" id="username" value="<?php echo $user->username; ?>"/></td>

				</tr>
				<tr>
					<td><label for="name"><?php echo _YOUR_NAME; ?></label></td>
					<td><input class="inputbox required" type="text" name="name" id="name" value="<?php echo $user->name; ?>"/></td>

				</tr>
				<tr>
					<td><label for="email"><?php echo _EMAIL; ?></label></td>
					<td><input class="inputbox required" type="text" name="email" id="email" value="<?php echo $user->email; ?>"/></td>

				</tr>
				<tr>
					<td><label for="password"><?php echo _PASS; ?></label></td>
					<td><input class="inputbox" type="password" name="password" id="password" value=""/></td>

				</tr>
				<tr>
				<td><label for="verifyPass"><?php echo _VPASS; ?></label></td>
					<td><input class="inputbox" type="password" name="verifyPass" id="verifyPass"/></td>

				</tr>
			</table>
			
			<?php if($mosConfig_frontend_userparams == '1' || $mosConfig_frontend_userparams == 1 ||$mosConfig_frontend_userparams == null) {
			?>
			<h3>Настройки сайта</h3>
			<table cellpadding="5" cellspacing="0" border="0" width="100%">
				<tr>
					<td colspan="2"><?php echo $params->render('params'); ?></td>
				</tr>
			</table>
			<?php } ?>

			<br />
			<h3>Личные данные</h3>
			<table width="100%">
				<tr>
					<td><label for="gender">Пол</label></td>
					<td><?php echo mosHTML::genderSelectList('gender','class="inputbox"', $user_extra->gender);?> </td>
				</tr>
				<tr>
					<td><label>Дата рождения</label></td>
					<td>
						<?php echo mosHTML::daySelectList('birthdate_day','class="inputbox"', $bday_date);?>
						<?php echo mosHTML::monthSelectList('birthdate_month','class="inputbox"', $bday_month,1);?>
						<?php echo mosHTML::yearSelectList('birthdate_year','class="inputbox"', $bday_year);?>
					</td>
				</tr>
				<tr>
					<td><label>О себе</label></td>
					<td>
						<textarea class="inputbox" name="about" id="about"><?php echo $user->user_extra->about ?></textarea>
					</td>
				</tr>
				<tr>
					<td><label>Местоположение</label></td>
					<td>
						<input class="inputbox" type="text" name="location" id="location" value="<?php echo $user->user_extra->location ?>"/>
					</td>
				</tr>
			</table>
			
		<?php $tabs->endTab(); ?>
		 <?php $tabs->startTab('Контакты',"cantacts"); ?> 	

			<h3>Контактная информация</h3>
			<table width="100%">
				<tr>
					<td><label>Сайт</label></td>
					<td><input class="inputbox" type="text" name="url" id="url" value="<?php echo $user->user_extra->url ?>"/></td>
				</tr>
				<tr>
					<td><label>ICQ</label></td>
					<td><input class="inputbox" type="text" name="icq" id="icq" value="<?php echo $user->user_extra->icq ?>"/></td>
				</tr>
				<tr>
					<td><label>Skype</label></td>
					<td><input class="inputbox" type="text" name="skype" id="skype" value="<?php echo $user->user_extra->skype ?>"/></td>
				</tr>
				<tr>
					<td><label>Jabber </label></td>
					<td><input class="inputbox" type="text" name="jabber" id="jabber" value="<?php echo $user->user_extra->jabber ?>"/></td>
				</tr>
				<tr>
					<td><label>MSN</label></td>
					<td><input class="inputbox" type="text" name="msn" id="msn" value="<?php echo $user->user_extra->msn ?>"/></td>
				</tr>
				<tr>
					<td><label>Yahoo</label></td>
					<td><input class="inputbox" type="text" name="yahoo" id="yahoo" value="<?php echo $user->user_extra->yahoo ?>"/></td>
				</tr>
				<tr>
					<td><label>Телефон</label></td>
					<td><input class="inputbox" type="text" name="phone" id="phone" value="<?php echo $user->user_extra->phone ?>"/></td>
				</tr>
				<tr>
					<td><label>Факс</label></td>
					<td><input class="inputbox" type="text" name="fax" id="fax" value="<?php echo $user->user_extra->fax ?>"/></td>
				</tr>
				<tr>
					<td><label>Мобильный</label></td>
					<td><input class="inputbox" type="text" name="mobil" id="mobil" value="<?php echo $user->user_extra->mobil ?>"/></td>
				</tr>

			</table>

	<?php $tabs->endTab(); ?>
		<input type="hidden" name="id" value="<?php echo $user->id; ?>" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" id="task" value="saveUserEdit" />
	<input type="hidden" name="<?php echo $validate; ?>" value="1" />
	</form>
	
	<?php $tabs->startTab('Аватар',"avatar"); ?> 
	<h3>Аватар</h3>

			<?php
			$form_params = new stdClass();
			$form_params->id = 'avatar_uploadForm';
			$form_params->img_field = 'avatar';
			$form_params->img_path = 'images/avatars';
			$form_params->default_img = 'images/avatars/none.jpg';
			$form_params->img_class = 'user_avatar';
			$form_params->ajax_handler = 'ajax.index.php?option=com_users';

			if(!$user->avatar){
				userHelper::_build_img_upload_area($user, $form_params, 'upload');
			} else {
				userHelper::_build_img_upload_area($user, $form_params, 'reupload');
			} ?>

	<?php $tabs->endTab(); ?>
<?php $tabs->endPane(); ?>
</div> 
		

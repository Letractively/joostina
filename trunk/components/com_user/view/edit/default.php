<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
    //Подключение плагина валидации форм
    mosCommonHTML::loadJqueryPlugins('jquery.validate');

?>
    <script language="javascript" type="text/javascript">

    $(document).ready(function() {
        $("#save").click(function () {
            $("input#task").val('saveUserEdit');
            $("#mosUserForm").submit();
        });
        $("#cancel").click(function () {
            $("input#task").val('cancel');
            $("#mosUserForm").submit();
        });
   });


    $(document).ready(function(){
        jQuery.validator.messages.required = "";
        $("#mosUserForm").validate();
  });
  </script>

   	<script language="javascript" type="text/javascript">
		function submitbutton( pressbutton ) {
			var form = document.mosUserForm;
			var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");

			// do field validation
			if (form.name.value == "") {
				alert( "<?php echo addslashes(_REGWARN_NAME); ?>" );
			} else if (form.username.value == "") {
				alert( "<?php echo addslashes(_REGWARN_UNAME); ?>" );
			} else if (r.exec(form.username.value) || form.username.value.length < 3) {
				alert( "<?php printf(addslashes(_VALID_AZ09),addslashes(_PROMPT_UNAME),4); ?>" );
			} else if (form.email.value == "") {
				alert( "<?php echo addslashes(_REGWARN_MAIL); ?>" );
			} else if ((form.password.value != "") && (form.password.value != form.verifyPass.value)){
				alert( "<?php echo addslashes(_REGWARN_VPASS2); ?>" );
			} else if (r.exec(form.password.value)) {
				alert( "<?php printf(addslashes(_VALID_AZ09),addslashes(_REGISTER_PASS),4); ?>" );
			} else {
				form.submit();
			}
		}


		function startupload() {
			SRAX.get('userav').src = 'images/system/aload.gif';
			return true;
		};
		function funishupload(text) {
			log(text);
			if(text!='0'){
				log('Всё ок!');
				log(text);
				SRAX.get('userav').src = text;
			}
			SRAX.get('mosUserForm').action='index.php';
			SRAX.get('mosUserForm').target='';
			SRAX.get('task').value='saveUserEdit';
			SRAX.get('mosUserForm').reset();
			return true;
		};
		function addavatar(){
			SRAX.get('mosUserForm').action='ajax.index.php';
			log(SRAX.get('mosUserForm').action);
			SRAX.get('task').value='uploadavatar';
			SRAX.Uploader('mosUserForm', startupload, funishupload, true);
			return false;
		}
		function delavatar(){
			log('Удаление аватара: ');
			SRAX.get('userav').src = 'images/system/aload.gif';
			dax({
				url: 'ajax.index.php?option=com_user&utf=0&task=delavatar',
				callback:
					function(resp, idTread, status, ops){
						log('Получен ответ: ' + resp.responseText);
						SRAX.get('userav').src = resp.responseText;
			}});
		}
		</script>

	<form action="index.php" method="post" name="mosUserForm" id="mosUserForm" enctype="multipart/form-data">
	<div style="float: right;height: 100%;">

        <input type="submit" class="button submit" name="submit" id="save" value="Сохранить" />
        <input type="submit" class="button cancel" name="cancel" id="cancel" value="Отмена" />

	</div>
	<div class="componentheading"><h1><?php echo $user->name; ?>&nbsp;(<?php echo $user->username; ?>)</h1></div>
<?php
	$tabs->startPane("userInfo");
	$tabs->startTab(_GENERAL,"main-page");
?>



            <h3>Данные аккаунта</h3>
			<table width="100%">
				<tr>
                    <td><label for="username"><?php echo _UNAME; ?></label></td>
					<td><input class="inputbox" type="text" name="username" id="username" value="<?php echo $user->username; ?>"/></td>

				</tr>
				<tr>
                    <td><label for="name"><?php echo _YOUR_NAME; ?></label></td>
					<td><input class="inputbox" type="text" name="name" id="name" value="<?php echo $user->name; ?>"/></td>

				</tr>
				<tr>
                    <td><label for="email"><?php echo _EMAIL; ?></label></td>
					<td><input class="inputbox" type="text" name="email" id="email" value="<?php echo $user->email; ?>"/></td>

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
                        <?php echo mosHTML::daySelectList('birthdate[day]','class="inputbox"', $bday_month);?>
                        <?php echo mosHTML::monthSelectList('birthdate[month]','class="inputbox"', $bday_month);?>
                        <?php echo mosHTML::yearSelectList('birthdate[year]','class="inputbox"', $bday_year);?>
                    </td>
				</tr>
			</table>

            <br />
            <h3>Аватар</h3>

			<div><img id="userav" src="<?php echo $mosConfig_live_site.mosUser::avatar($user->id,'big');?>" /></div>
			<br />
			<input class="inputbox" type="file" name="avatar" id="fileavatar" /><br /><br />
			<button class="inputbox" onclick="addavatar(); return false;"><?php echo _TASK_UPLOAD?></button>
			<button class="inputbox" onclick="delavatar(); return false;"><?php echo _CMN_DELETE?></button>




<?php
	$tabs->endTab();
	if($mosConfig_frontend_userparams == '1' || $mosConfig_frontend_userparams == 1 ||$mosConfig_frontend_userparams == null) {
	$tabs->startTab(_ADVANCED,"ext-page");
?>
	<table cellpadding="5" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2"><?php echo $params->render('params'); ?></td>
		</tr>
	</table>
<?php
		}
	$tabs->endTab();
?>
	<input type="hidden" name="id" value="<?php echo $user->id; ?>" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" id="task" value="saveUserEdit" />
	<input type="hidden" name="<?php echo $validate; ?>" value="1" />
	</form>
<?php
	$tabs->endPane();


?>

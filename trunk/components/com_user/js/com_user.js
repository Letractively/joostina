

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

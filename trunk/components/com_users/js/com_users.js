$(document).ready(function() {
	$("#save").click(function () {
		$("input#task").val('saveUserEdit');
		$("#mosUserForm").submit();
	});
	$("#cancel").click(function () {
		$("input#task").val('cancel');
		$("#mosUserForm").submit();
	});
	if((jQuery.inArray("jquery.validate", _js_defines)>-1)){
		jQuery.validator.messages.required = "";
		$("#mosUserForm").validate();
	}
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
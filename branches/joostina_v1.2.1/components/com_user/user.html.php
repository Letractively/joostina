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
* @subpackage Users
*/
class HTML_user {
function frontpage() {
?>
<div class="componentheading"><?php echo _WELCOME; ?></div>
<div><?php echo _WELCOME_DESC; ?></div>
<?php
}
function userEdit($row,$option,$submitvalue,&$params) {
global $mosConfig_absolute_path,$mosConfig_frontend_userparams,$mosConfig_live_site;
require_once ($mosConfig_absolute_path.'/includes/HTML_toolbar.php');
// used for spoof hardening
$validate = josSpoofValue();
mosCommonHTML::loadFullajax();
$tabs = new mosTabs(1);
mosCommonHTML::loadOverlib();
?>
<script language="javascript" type="text/javascript">
//<![CDATA[
function submitbutton( pressbutton ) {
var form = document.mosUserForm;
var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
if (pressbutton == 'cancel') {
form.task.value = 'cancel';
form.submit();
return;
}
// do field validation
if (form.name.value == "") {
alert("<?php echo addslashes(_REGWARN_NAME); ?>");
} else if (form.username.value == "") {
alert("<?php echo addslashes(_REGWARN_UNAME); ?>");
} else if (r.exec(form.username.value) || form.username.value.length < 3) {
alert("<?php printf(addslashes(_VALID_AZ09),addslashes(_PROMPT_UNAME),4); ?>");
} else if (form.email.value == "") {
alert("<?php echo addslashes(_REGWARN_MAIL); ?>");
} else if ((form.password.value != "") && (form.password.value != form.verifyPass.value)){
alert( "<?php echo addslashes(_REGWARN_VPASS2); ?>" );
} else if (r.exec(form.password.value)) {
alert("<?php printf(addslashes(_VALID_AZ09),addslashes(_REGISTER_PASS),4); ?>");
} else {
form.submit();
}
}
function startupload() {
SRAX.get('userav').src = 'images/aload.gif';
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
SRAX.get('userav').src = 'images/aload.gif';
dax({
url: 'ajax.index.php?option=com_user&utf=0&task=delavatar',
callback:
function(resp, idTread, status, ops){
log('Получен ответ: ' + resp.responseText);
SRAX.get('userav').src = resp.responseText;
}});
}
//]]>
</script>
<form action="index.php" method="post" name="mosUserForm" id="mosUserForm" enctype="multipart/form-data">
<div style="float:right;height:100%;">
<?php
mosToolBar::spacer();
mosToolBar::save();
mosToolBar::cancel();
?>
</div>
<div class="componentheading"><?php echo _EDIT_TITLE; ?></div>
<?php
$tabs->startPane("userInfo");
$tabs->startTab("Общее","main-page");
?>
<table width="100%" id="table_userprofile">
<tr>
<td id="user_avatar">
<div><img id="userav" src="<?php echo $mosConfig_live_site.mosUser::avatar($row->id,'big');?>" alt="" /></div>
<br />
<input class="inputbox" type="file" name="avatar" id="fileavatar" /><br /><br />
<button class="inputbox" onclick="addavatar(); return false;"><?php echo _TASK_UPLOAD; ?></button>
<button class="inputbox" onclick="delavatar(); return false;"><?php echo _CMN_DELETE; ?></button>
</td>
<td valign="top">
<table cellpadding="5" width="100%">
<tr height="30px">
<td width="60%"><input class="inputbox" type="text" name="username" id="username" value="<?php echo $row->username; ?>" size="40" /></td>
<td><label for="username"><?php echo _UNAME; ?></label></td>
</tr>
<tr height="30px">
<td><input class="inputbox" type="text" name="name" id="name" value="<?php echo $row->name; ?>" size="40" /></td>
<td><label for="name"><?php echo _YOUR_NAME; ?></label></td>
</tr>
<tr height="30px">
<td><input class="inputbox" type="text" name="email" id="email" value="<?php echo $row->email; ?>" size="40" /></td>
<td><label for="email"><?php echo _EMAIL; ?></label></td>
</tr>
<tr height="30px">
<td><input class="inputbox" type="password" name="password" id="password" value="" size="40" /></td>
<td><label for="password"><?php echo _PASS; ?></label></td>
</tr>
<tr height="30px">
<td><input class="inputbox" type="password" name="verifyPass" id="verifyPass" size="40" /></td>
<td><label for="verifyPass"><?php echo _VPASS; ?></label></td>
</tr>
</table>
</td>
</tr>
</table>
<?php
$tabs->endTab();
if($mosConfig_frontend_userparams == '1' || $mosConfig_frontend_userparams == 1 ||$mosConfig_frontend_userparams == null) {
$tabs->startTab("Дополнительно","ext-page");
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
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="task" id="task" value="saveUserEdit" />
<input type="hidden" name="<?php echo $validate; ?>" value="1" />
</form>
<?php
$tabs->endPane();
}
function confirmation() {
?>
<div class="componentheading"><?php echo _SUBMIT_SUCCESS; ?></div>
<table>
<tr><td><?php echo _SUBMIT_SUCCESS_DESC; ?></td></tr>
</table>
<?php
}
}
?>
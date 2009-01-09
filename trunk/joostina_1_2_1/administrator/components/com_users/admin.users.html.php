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
class HTML_users {

	function showUsers(&$rows,$pageNav,$search,$option,$lists) {
		global $my,$mosConfig_live_site;
?>
		<form action="index2.php" method="post" name="adminForm" id="adminForm">
		<table class="adminheading">
		<tr>
			<th class="user"><?php echo _USERS?></th>
			<td>Фильтр:</td>
			<td>
				<input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="inputbox" onChange="document.adminForm.submit();" />
			</td>
			<td><?php echo $lists['type']; ?></td>
			<td><?php echo $lists['logged']; ?></td>
		</tr>
		</table>
		<table class="adminlist">
		<tr>
			<th width="1%" class="title">#</th>
			<th width="1%" class="title">
				<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th class="title" colspan="2"><?php echo _CMN_NAME?></th>
			<th width="22%"><?php echo _USER_LOGIN_TXT?></th>
			<th width="5%"><?php echo _LOGGED_IN?></th>
			<th width="5%"><?php echo _ALLOWED?></th>
			<th width="10%"><?php echo _O_GROUP?></th>
			<th width="10%">E-Mail</th>
			<th width="13%"><?php echo _LAST_LOGIN?></th>
			<th width="1%">ID</th>
		</tr>
		<?php
		$k = 0;
		for($i = 0,$n = count($rows); $i < $n; $i++) {
			$row = &$rows[$i];
			$img	= $row->block ? 'publish_x.png':'tick.png';
			$task	= $row->block ? 'unblock':'block';
			$alt	= $row->block ? _ALLOW:_DISALLOW;
			$link	= 'index2.php?option=com_users&amp;task=editA&amp;id='.$row->id.'&amp;hidemainmenu=1';
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i + 1 + $pageNav->limitstart; ?></td>
			<td><?php echo mosHTML::idBox($i,$row->id); ?></td>
			<td width="1%"><img class="miniavatar" id="userav" src="<?php echo $mosConfig_live_site.mosUser::miniavatar($row->id);?>" /></td>
			<td align="left"><a href="<?php echo $link; ?>">
			<?php echo $row->name; ?></a></td>
			<td align="left"><?php echo $row->username; ?></td>
			<td align="center"><?php echo $row->loggedin ? '<img src="images/tick.png" border="0" alt="" />':''; ?></td>
			<td width="5%" align="center" <?php if($row->id!=$my->id){ ?> class="td-state" onclick="ch_publ(<?php echo $row->id;?>,'com_users');" <?php };?>>
				<img id="img-pub-<?php echo $row->id;?>" class="img-mini-state" alt="<?php echo _USER_BLOCK?>" src="images/<?php echo $img;?>"/>
			</td>
			<td><?php echo $row->groupname; ?></td>
			<td><a href="mailto:<?php echo $row->email; ?>"><?php echo $row->email; ?></a></td>
			<td class="jtd_nowrap"><?php echo mosFormatDate($row->lastvisitDate,_CURRENT_SERVER_TIME_FORMAT); ?></td>
			<td><?php echo $row->id; ?></td>
		</tr>
		<?php
		$k = 1 - $k;
	}
?>
	</table>
	<?php echo $pageNav->getListFooter(); ?>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
	</form>
	<?php
	}
	function edituser(&$row,&$contact,&$lists,$option,$uid,&$params) {
		global $my,$acl;
		global $mosConfig_live_site;

		mosMakeHtmlSafe($row);

		$tabs = new mosTabs(0,1);

		mosCommonHTML::loadOverlib();
		$canBlockUser = $acl->acl_check('administration','edit','users',$my->usertype,'user properties','block_user');
		$canEmailEvents = $acl->acl_check('workflow','email_events','users',$acl->get_group_name($row->gid,'ARO'));
?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");

			// do field validation
			if (trim(form.name.value) == "") {
				alert( "<?php echo _ENTER_NAME_PLEASE?>" );
			} else if (form.username.value == "") {
				alert( "<?php echo _ENTER_LOGIN_PLEASE?>" );
			} else if (r.exec(form.username.value) || form.username.value.length < 3) {
				alert( "<?php echo _BAD_USER_LOGIN?>" );
			} else if (trim(form.email.value) == "") {
				alert( "<?php echo _ENTER_EMAIL_PLEASE?>" );
			} else if (form.gid.value == "") {
				alert( "<?php echo _ENTER_GROUP_PLEASE?>" );
			} else if (trim(form.password.value) != "" && form.password.value != form.password2.value){
				alert( "<?php echo _BAD_PASSWORD?>" );
			} else if (form.gid.value == "29") {
				alert( "<?php echo _BAD_GROUP_1?>" );
			} else if (form.gid.value == "30") {
				alert( "<?php echo _BAD_GROUP_2?>" );
			} else {
				submitform( pressbutton );
			}
		}
		function gotocontact( id ) {
			var form = document.adminForm;
			form.contact_id.value = id;
			submitform( 'contact' );
		}
		function startupload() {
			SRAX.get('userav').src = 'images/aload.gif';
		};
		function funishupload(text) {
			log(text);
			if(text!='0'){
				log('Всё ок!');
				log(text);
				SRAX.get('userav').src = text;
			}
			SRAX.get('adminForm').action='index2.php';
			SRAX.get('adminForm').target='';
			SRAX.get('adminForm').reset();
		};
		function addavatar(){
			SRAX.get('adminForm').action='ajax.index.php';
			log(SRAX.get('adminForm').action);
			SRAX.get('task').value='uploadavatar';
			SRAX.Uploader('adminForm', startupload, funishupload, true);
		}
		function delavatar(elid){
			log('Удаление аватара: ');
			SRAX.get('userav').src = 'images/aload.gif';
			dax({
				url: 'ajax.index.php?option=com_users&utf=0&task=delavatar&id='+elid,
				callback:
					function(resp, idTread, status, ops){
						log('Получен ответ: ' + resp.responseText);
						SRAX.get('userav').src = resp.responseText;
			}});
		}
		</script>
		<form action="index2.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<table class="adminheading">
		<tr>
			<th class="user"><small><?php echo $row->id ? 'Редактирование профиля пользователя: '.$row->name : 'Новый пользователь'; ?></small></th>
		</tr>
		</table>

		<table width="100%">
		<tr>
			<td width="60%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="3"><?php echo _USER_INFO?></th>
				</tr>
				<tr>
					<td width="50%" rowspan="6" id="user_avatar" align="center">
						<div ><img id="userav" src="<?php echo $mosConfig_live_site.mosUser::avatar($row->id,'big');?>" /></div>
						<br />
						<?php if($row->id){?>
						<input class="inputbox" type="file" size="1" id="fileavatar" name="avatar" class="inputbox"/>
						<button class="inputbox" onclick="addavatar();">Загрузить</button>
						<button class="inputbox" onclick="delavatar(<?php echo $row->id;?>); return false;"><?php echo _CMN_DELETE?></button>
						<?php };?>
					</td>
					<td width="200" class="key"><?php echo _YOUR_NAME?>:</td>
					<td><input type="text" name="name" class="inputbox" size="40" value="<?php echo $row->name; ?>" maxlength="50" /></td>
				</tr>
				<tr>
					<td class="key"><?php echo _USER_LOGIN_TXT?>:</td>
					<td><input type="text" name="username" class="inputbox" size="40" value="<?php echo $row->username; ?>" maxlength="25" /></td>
				<tr>
					<td class="key">E-mail:</td>
					<td><input class="inputbox" type="text" name="email" size="40" value="<?php echo $row->email; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><?php echo _NEW_PASSWORD?>:</td>
					<td><input class="inputbox" type="password" name="password" size="40" value="" /></td>
				</tr>
				<tr>
					<td class="key"><?php echo _REPEAT_PASSWORD?>:</td>
					<td><input class="inputbox" type="password" name="password2" size="40" value="" /></td>
				</tr>
				<tr>
					<td valign="top" class="key"><?php echo _O_GROUP?>:</td>
					<td><?php echo $lists['gid']; ?></td>
				</tr>
<?php
		if($canBlockUser) {
?>
				<tr>
					<td class="key"><?php echo _BLOCK_USER?>:</td>
					<td ><?php echo $lists['block']; ?></td>
				</tr>
<?php
		}
		if($canEmailEvents) {
?>
				<tr>
					<td class="key"><?php echo _RECEIVE_EMAILS?>:</td>
					<td colspan="2">
					<?php echo $lists['sendEmail']; ?>
					</td>
				</tr>
<?php
		}
		if($uid) {
?>
				<tr>
					<td class="key"><?php echo _REGISTRATION_DATE?>:</td>
					<td colspan="2"><?php echo $row->registerDate; ?></td>
				</tr>
				<tr>
					<td class="key"><?php echo _LAST_LOGIN?>:</td>
					<td colspan="2"><?php echo $row->lastvisitDate; ?></td>
				</tr>
<?php
		}
?>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="40%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="1"><?php echo _PARAMETERS?>:</th>
				</tr>
				<tr>
					<td><?php echo $params->render('params'); ?></td>
				</tr>
				</table>
<?php
		if(!$contact) {
?>
					<table class="adminform">
					<tr>
						<th><?php echo _CONTACT_INFO?>:</th>
					</tr>
					<tr>
						<td>
						<br />
						<php echo _NO_USER_CONTACTS?>
						<br />
						</td>
					</tr>
					</table>
<?php
		} else {
?>
					<table class="adminform">
					<tr>
						<th colspan="2"><?php echo _CONTACT_INFO?>:</th>
					</tr>
					<tr>
						<td width="15%"><?php echo _FULL_NAME?>:</td>
						<td>
						<strong><?php echo $contact[0]->name; ?></strong></td>
					</tr>
					<tr>
						<td><?php echo _USER_POSITION?>:</td>
						<td><strong><?php echo $contact[0]->con_position; ?></strong></td>
					</tr>
					<tr>
						<td><?php echo _CONTACT_HEADER_PHONE?>:</td>
						<td><strong><?php echo $contact[0]->telephone; ?></strong></td>
					</tr>
					<tr>
						<td><?php echo _CONTACT_HEADER_FAX?>:</td>
						<td><strong><?php echo $contact[0]->fax; ?></strong></td>
					</tr>
					<tr>
						<td></td>
						<td ><strong><?php echo $contact[0]->misc; ?></strong></td>
					</tr>
<?php
			if($contact[0]->image) {
?>
					<tr>
						<td></td>
						<td valign="top">
							<img src="<?php echo $mosConfig_live_site; ?>/images/stories/<?php echo $contact[0]->image; ?>" align="middle" alt="" />
						</td>
					</tr>
<?php
			}
?>
					<tr>
						<td colspan="2">
						<br /><br />
						<input class="button" type="button" value="<?php echo _CHANGE_CONTACT_INFO?>" onclick="javascript: gotocontact( '<?php echo $contact[0]->id; ?>' )">
						<i>
						<br />'<?php echo _CONTACT_INFO_PATH_URL?>'.
						</i>
						</td>
					</tr>
					</table>
<?php
		}
?>
			</td>
		</tr>
		</table>
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" id="task" value="" />
		<input type="hidden" name="contact_id" value="" />
<?php
		if(!$canEmailEvents) {
?>
		<input type="hidden" name="sendEmail" value="0" />
<?php
		}
?>
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
<?php
	}
}
?>
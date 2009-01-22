<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2007 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/copyleft/gpl.html GNU/GPL, �������� LICENSE.php
* Joostina! - ��������� ����������� �����������. ��� ������ ����� ���� ��������
* � ������������ � ����������� ������������ ��������� GNU, ������� ��������
* � ���������� ��������������� � ������� ���������� ������, ����������������
* �������� ����������� ������������ ��������� GNU ��� ������ �������� ���������
* �������� ��� �������� � �������� �������� �����.
* ��� ��������� ������������ � ��������� �� ��������� �����, �������� ���� COPYRIGHT.php.
*/

// ������ ������� �������
defined( '_VALID_MOS' ) or die( '������ ����� ����� ��������' );

/**
* @package Joostina
* @subpackage Users
*/
class HTML_users {

	function showUsers( &$rows, $pageNav, $search, $option, $lists ) {
		?>
		<form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="user">
			������������
			</th>
			<td>
			������:
			</td>
			<td>
			<input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="inputbox" onChange="document.adminForm.submit();" />
			</td>
			<td width="right">
			<?php echo $lists['type'];?>
			</td>
			<td width="right">
			<?php echo $lists['logged'];?>
			</td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="2%" class="title">
			#
			</th>
			<th width="3%" class="title">
				<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th class="title">
			���
			</th>
			<th width="15%" class="title">
			��� ������������
			</th>
			<th width="5%" class="title">
			�� �����
			</th>
			<th width="5%" class="title">
			��������
			</th>
			<th width="12%" class="title">
			������
			</th>
			<th width="13%" class="title">
			E-Mail
			</th>
			<th width="15%" class="title">
			��������� ���������
			</th>
			<th width="1%" class="title">
			ID
			</th>			
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row 	=& $rows[$i];

			$img 	= $row->block ? 'publish_x.png' : 'tick.png';
			$task 	= $row->block ? 'unblock' : 'block';
			$alt 	= $row->block ? '���������' : '�����������';
			$link 	= 'index2.php?option=com_users&amp;task=editA&amp;id='. $row->id. '&amp;hidemainmenu=1';
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $i+1+$pageNav->limitstart;?>
				</td>
				<td>
				<?php echo mosHTML::idBox( $i, $row->id ); ?>
				</td>
				<td>
				<a href="<?php echo $link; ?>">
				<?php echo $row->name; ?>
				</a>
				<td>
				<?php echo $row->username; ?>
				</td>
				</td>
				<td align="center">
				<?php echo $row->loggedin ? '<img src="images/tick.png" width="12" height="12" border="0" alt="" />': ''; ?>
				</td>
				<td>
				<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
				<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
				</a>
				</td>
				<td>
				<?php echo $row->groupname; ?>
				</td>
				<td>
				<a href="mailto:<?php echo $row->email; ?>">
				<?php echo $row->email; ?>
				</a>
				</td>
				<td class="jtd_nowrap">
				<?php echo mosFormatDate( $row->lastvisitDate, _CURRENT_SERVER_TIME_FORMAT ); ?>
				</td>
				<td>
				<?php echo $row->id; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	function edituser( &$row, &$contact, &$lists, $option, $uid, &$params ) {
		global $my, $acl;
		global $mosConfig_live_site;
		
		mosMakeHtmlSafe( $row );
		
		$tabs = new mosTabs( 0 );

		mosCommonHTML::loadOverlib();
		$canBlockUser 	= $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'user properties', 'block_user' );
		$canEmailEvents = $acl->acl_check( 'workflow', 'email_events', 'users', $acl->get_group_name( $row->gid, 'ARO' ) );
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
				alert( "�� ������ ������ ���." );
			} else if (form.username.value == "") {
				alert( "�� ������ ������ ��� ������������ ��� ����� �� ����." );
			} else if (r.exec(form.username.value) || form.username.value.length < 3) {
				alert( "���� ��� ��� ����� �������� ������������ ������� ��� ������� ��������." );
			} else if (trim(form.email.value) == "") {
				alert( "�� ������ ������ ����� email." );
			} else if (form.gid.value == "") {
				alert( "�� ������ ��������� ������������ ������ �������." );
			} else if (trim(form.password.value) != "" && form.password.value != form.password2.value){
				alert( "������ ������������." );
			} else if (form.gid.value == "29") {
				alert( "����������, �������� ������ ������. ������ ���� `Public Front-end` �������� ������" );
			} else if (form.gid.value == "30") {
				alert( "����������, �������� ������ ������. ������ ���� `Public Back-end` �������� ������" );
			} else {
				submitform( pressbutton );
			}
		}

		function gotocontact( id ) {
			var form = document.adminForm;
			form.contact_id.value = id;
			submitform( 'contact' );
		}
		</script>
		<form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="user">
			������������: <small><?php echo $row->id ? '���������' : '����������';?></small>
			</th>
		</tr>
		</table>

		<table width="100%">
		<tr>
			<td width="60%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					���������� � ������������
					</th>
				</tr>
				<tr>
					<td width="130">
					������ ���:
					</td>
					<td>
					<input type="text" name="name" class="inputbox" size="40" value="<?php echo $row->name; ?>" maxlength="50" />
					</td>
				</tr>
				<tr>
					<td>
					��� ������������:
					</td>
					<td>
					<input type="text" name="username" class="inputbox" size="40" value="<?php echo $row->username; ?>" maxlength="25" />
					</td>
				<tr>
					<td>
					E-mail:
					</td>
					<td>
					<input class="inputbox" type="text" name="email" size="40" value="<?php echo $row->email; ?>" />
					</td>
				</tr>
				<tr>
					<td>
					����� ������:
					</td>
					<td>
					<input class="inputbox" type="password" name="password" size="40" value="" />
					</td>
				</tr>
				<tr>
					<td>
					�������� ������:
					</td>
					<td>
					<input class="inputbox" type="password" name="password2" size="40" value="" />
					</td>
				</tr>
				<tr>
					<td valign="top">
					������:
					</td>
					<td>
					<?php echo $lists['gid']; ?>
					</td>
				</tr>
				<?php
				if ($canBlockUser) {
					?>
					<tr>
						<td>
						����������� ������������
						</td>
						<td>
						<?php echo $lists['block']; ?>
						</td>
					</tr>
					<?php
				}
				if ($canEmailEvents) {
					?>
					<tr>
						<td>
						�������� ��������� ��������� �� e-mail
						</td>
						<td>
						<?php echo $lists['sendEmail']; ?>
						</td>
					</tr>
					<?php
				}
				if( $uid ) {
					?>
					<tr>
						<td>
						���� �����������
						</td>
						<td>
						<?php echo $row->registerDate;?>
						</td>
					</tr>
				<tr>
					<td>
					���� ���������� ���������
					</td>
					<td>
					<?php echo $row->lastvisitDate;?>
					</td>
				</tr>
					<?php
				}
				?>
				<tr>
					<td colspan="2">&nbsp;

					</td>
				</tr>
				</table>
			</td>
			<td width="40%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="1">
					<?php echo '���������'; ?>
					</th>
				</tr>
				<tr>
					<td>
					<?php echo $params->render( 'params' );?>
					</td>
				</tr>
				</table>

				<?php
				if ( !$contact ) {
					?>
					<table class="adminform">
					<tr>
						<th>
						���������� ����������
						</th>
					</tr>
					<tr>
						<td>
						<br />
						� ����� ������������ ��� ���������� ����������:
						<br />
						��� ������������ �������� '���������� -> �������� -> ���������� ����������'.
						<br /><br />
						</td>
					</tr>
					</table>
					<?php
				} else {
					?>
					<table class="adminform">
					<tr>
						<th colspan="2">
						���������� ����������
						</th>
					</tr>
					<tr>
						<td width="15%">
						������ ���:
						</td>
						<td>
						<strong>
						<?php echo $contact[0]->name;?>
						</strong>
						</td>
					</tr>
					<tr>
						<td>
						��������� (���������):
						</td>
						<td >
						<strong>
						<?php echo $contact[0]->con_position;?>
						</strong>
						</td>
					</tr>
					<tr>
						<td>
						�������:
						</td>
						<td >
						<strong>
						<?php echo $contact[0]->telephone;?>
						</strong>
						</td>
					</tr>
					<tr>
						<td>
						����:
						</td>
						<td >
						<strong>
						<?php echo $contact[0]->fax;?>
						</strong>
						</td>
					</tr>
					<tr>
						<td></td>
						<td >
						<strong>
						<?php echo $contact[0]->misc;?>
						</strong>
						</td>
					</tr>
					<?php
					if ($contact[0]->image) {
						?>
						<tr>
							<td></td>
							<td valign="top">
							<img src="<?php echo $mosConfig_live_site;?>/images/stories/<?php echo $contact[0]->image; ?>" align="middle" alt="�������" />
							</td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td colspan="2">
						<br /><br />
						<input class="button" type="button" value="�������� ���������� ����������" onclick="javascript: gotocontact( '<?php echo $contact[0]->id; ?>' )">
						<i>
						<br />
						'���������� -> �������� -> ���������� ����������'.
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
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="contact_id" value="" />
		<?php
		if (!$canEmailEvents) {
			?>
			<input type="hidden" name="sendEmail" value="0" />
			<?php
		}
		?>
		</form>
		<?php
	}
}
?>
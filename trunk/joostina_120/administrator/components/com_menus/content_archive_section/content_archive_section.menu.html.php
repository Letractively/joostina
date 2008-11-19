<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

// ������ ������� �������
defined('_VALID_MOS') or die();

/**
* Writes the edit form for new and existing content item
*
* A new record is defined when <var>$row</var> is passed with the <var>id</var>
* property set to 0.
* @package Joostina
* @subpackage Menus
*/
class content_archive_section_menu_html {

	function editSection(&$menu,&$lists,&$params,$option) {
		global $mosConfig_live_site;
		mosCommonHTML::loadOverlib();
?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			var form = document.adminForm;
			<?php
		if(!$menu->id) {
?>
				if ( getSelectedValue( 'adminForm', 'componentid' ) < 0 ) {
					alert( '�� ������ ������� ������' );
					return;
				}

				if ( form.name.value == '' ) {
					if ( form.componentid.value == 0 ) {
						form.name.value = "��� �������";
					} else {
						form.name.value = form.componentid.options[form.componentid.selectedIndex].text;
					}
				}
				form.link.value = "index.php?option=com_content&task=archivesection&id=" + form.componentid.value;
				submitform( pressbutton );
				<?php
		} else {
?>
				if ( form.name.value == '' ) {
					alert( '���� ����� ���� ������ ����� ��������' );
				} else {
					submitform( pressbutton );
				}
				<?php
		}
?>
		}
		</script>
		<form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="menus">
			<?php echo $menu->id?'�������������� -':'�������� -'; ?> ����� ���� :: ���� - ���������� ������� � ������
			</th>
		</tr>
		</table>

		<table width="100%">
		<tr valign="top">
			<td width="60%">
				<table class="adminform">
				<tr>
					<th colspan="3">
					������
					</th>
				</tr>
				<tr>
					<td width="10%" align="right" valign="top">��������:</td>
					<td width="200px">
					<input type="text" name="name" size="30" maxlength="100" class="inputbox" value="<?php echo htmlspecialchars($menu->name,ENT_QUOTES); ?>"/>
					</td>
					<td>
					<?php
		if(!$menu->id) {
			echo mosToolTip('���� ���� ����� ��������� ������, �� ������������� ����� ������������ �������� �������');
		}
?>
					</td>
				</tr>
				<tr>
					<td width="10%" align="right" valign="top">
					title ������:
					</td>
					<td width="80%">
						<input class="inputbox" type="text" name="params[title]" size="50" maxlength="100" value="<?php echo htmlspecialchars($params->get('title',''),ENT_QUOTES); ?>" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">������:</td>
					<td colspan="2">
					<?php echo $lists['componentid']; ?>
					</td>
				</tr>
				<tr>
					<td align="right">URL:</td>
					<td colspan="2">
					<?php echo ampReplace($lists['link']); ?>
					</td>
				</tr>
				<tr>
					<td align="right">������������ ����� ����:</td>
					<td colspan="2">
					<?php echo $lists['parent']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">������� ������������:</td>
					<td colspan="2">
					<?php echo $lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">������� �������:</td>
					<td colspan="2">
					<?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">������������:</td>
					<td colspan="2">
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="40%">
				<table class="adminform">
				<tr>
					<th>
					���������
					</th>
				</tr>
				<tr>
					<td>
					<?php echo $params->render(); ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $menu->id; ?>" />
		<input type="hidden" name="menutype" value="<?php echo $menu->menutype; ?>" />
		<input type="hidden" name="type" value="<?php echo $menu->type; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}
}
?>
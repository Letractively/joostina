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
* Display Component item link
* @package Joostina
* @subpackage Menus
*/
class component_item_link_menu_html {

	function edit( &$menu, &$lists, &$params, $option ) {
		global $mosConfig_live_site;
		?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if ( pressbutton == 'cancel' ) {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if ( trim(form.name.value) == "" ){
				alert( "������ ������ ����� ���" );
			} else if ( trim( form.link.value ) == "" ){
				alert( "�� ������ ������� ��������� ��� �������� ������ �� ����" );
			} else {
				submitform( pressbutton );
			}
		}
		</script>

		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="menus">
			<?php echo $menu->id ? '��������� -' : '���������� -';?> ����� ���� :: ������ - ������ ����������
			</th>
		</tr>
		</table>

		<table width="100%">
		<tr valign="top">
			<td width="60%">
				<table class="adminform">
				<tr>
					<th colspan="2">
					������
					</th>
				</tr>
				<tr>
					<td width="10%" align="right">
					���:
					</td>
					<td width="80%">
					<input class="inputbox" type="text" name="name" size="50" maxlength="100" value="<?php echo htmlspecialchars( $menu->name, ENT_QUOTES ); ?>" />
					</td>
				</tr>
   				<tr>
					<td width="10%" align="right" valign="top">
					title ������:
					</td>
					<td width="80%">
						<input class="inputbox" type="text" name="params[title]" size="50" maxlength="100" value="<?php echo htmlspecialchars( $params->get('title',''), ENT_QUOTES ); ?>" />
					</td>
				</tr>
				<tr>
					<td width="10%" align="right" valign="top">
					��������� ��� ������:
					</td>
					<td width="80%">
					<?php echo $lists['components']; ?>
					</td>
				</tr>
				<tr>
					<td width="10%" align="right">URL:</td>
					<td width="80%">
					<?php echo ampReplace($lists['link']); ?>
					</td>
				</tr>
				<tr>
					<td width="10%" align="right" valign="top">
					��� ������� ������� � ...
					</td>
					<td width="80%">
					<?php echo $lists['target']; ?>
					</td>
				</tr>
				<tr>
					<td align="right">
					������������ ����� ����:
					</td>
					<td>
					<?php echo $lists['parent']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					�������:
					</td>
					<td>
					<?php echo $lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					������� �������:
					</td>
					<td>
					<?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">������������:</td>
					<td>
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
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
					<?php echo $params->render();?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="id" value="<?php echo $menu->id; ?>" />
		<input type="hidden" name="menutype" value="<?php echo $menu->menutype; ?>" />
		<input type="hidden" name="type" value="<?php echo $menu->type; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<script language="Javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
		<?php
	}
}
?>
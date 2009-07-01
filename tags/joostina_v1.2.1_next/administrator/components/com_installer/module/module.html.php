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
* @package Joostina
* @subpackage Installer
*/
class HTML_module {

	function showInstalledModules(&$rows,$option,&$xmlfile,&$lists) {
		if(count($rows)) {
			// ����������� ������� �������� ������
			mosCommonHTML::loadPrettyTable();
?>
		<form action="index2.php" method="post" name="adminForm">
			<table class="adminheading">
			<tr>
				<th class="install"><?=_INSTALL_MODULE?></th>
				<td>������:</td>
				<td width="right"><?php echo $lists['filter']; ?></td>
			</tr>
			<tr>
				<td colspan="3"><div class="jwarning"><?=_INSTALLED_COMPONENTS2?></div></td>
			</tr>
			</table>
			<table class="adminlist" id="adminlist">
			<tr>
				<th width="20%" class="title"><?=_MODULE?></th>
				<th width="5%" align="center"><?=_E_VERSION?></th>
				<th width="10%" align="left"><?=_USED_ON?></th>
				<th width="10%" align="left"><?=_AUTHOR_BY?></th>
				<th width="10%" align="center"><?=_DATE?></th>
				<th width="15%" align="left">E-mail</th>
				<th width="15%" align="left"><?=_COMPONENT_AUTHOR_URL?></th>
			</tr>
			<?php
			$rc = 0;
			for($i = 0,$n = count($rows); $i < $n; $i++) {
				$row = &$rows[$i];
?>
				<tr class="<?php echo "row$rc"; ?>">
					<td align="left">
					<input type="radio" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);"><span class="bold"><?php echo $row->module; ?></span></td>
					<td align="center"><?php echo @$row->version != ""?$row->version:"&nbsp;"; ?></td>
					<td align="left"><?php echo $row->client_id == "0"? _SITE : _CONTROL_PANEL; ?></td>
					<td><?php echo @$row->author != ""?$row->author:"&nbsp;"; ?></td>
					<td align="center"><?php echo @$row->creationdate != ""?$row->creationdate:"&nbsp;"; ?></td>
					<td align="center"><?php echo @$row->authorEmail != ""?$row->authorEmail:"&nbsp;"; ?></td>
					<td align="center"><?php echo @$row->authorUrl != ""?"<a href=\"".(substr($row->authorUrl,0,7) =='http://'?$row->authorUrl:'http://'.$row->authorUrl)."\" target=\"_blank\">$row->authorUrl</a>":"&nbsp;"; ?></td>
				</tr>
				<?php
				$rc = $rc == 0?1:0;
			}
		} else {
?>
			<td class="small"><?=_NO_OTHER_MODULES?></td>
			<?php
		}
?>
		</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_installer" />
		<input type="hidden" name="element" value="module" />
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}
}
?>
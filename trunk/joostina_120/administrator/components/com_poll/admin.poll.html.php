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
* @subpackage Polls
*/
class HTML_poll {

	function showPolls(&$rows,&$pageNav,$option) {
		global $my;

		mosCommonHTML::loadOverlib();
?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="menus">������</th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="5">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th align="left">
			��������� ������
			</th>
			<th width="10%" align="center">
			�����������
			</th>
			<th width="10%" align="center">
			���������
			</th>
			<th width="10%" align="center">
			��������
			</th>
		</tr>
		<?php
		$k = 0;
		for($i = 0,$n = count($rows); $i < $n; $i++) {
			$row = &$rows[$i];
			mosMakeHtmlSafe($row);
			$link = 'index2.php?option=com_poll&task=editA&hidemainmenu=1&id='.$row->id;

			$task = $row->published?'unpublish':'publish';
			$img = $row->published?'publish_g.png':'publish_x.png';
			$alt = $row->published?'�����������':'�������������';

			$checked = mosCommonHTML::CheckedOutProcessing($row,$i);
?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $pageNav->rowNumber($i); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<a href="<?php echo $link; ?>" title="�������� �����">
				<?php echo $row->title; ?>
				</a>
				</td>
				<td align="center">
				<a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')">
				<img src="images/<?php echo $img; ?>" border="0" alt="<?php echo $alt; ?>" />
				</a>
				</td>
				<td align="center">
				<?php echo $row->numoptions; ?>
				</td>
				<td align="center">
				<?php echo $row->lag; ?>
				</td>
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
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}


	function editPoll(&$row,&$options,&$lists) {
		mosMakeHtmlSafe($row,ENT_QUOTES);
?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.title.value == "") {
				alert( "����� ������ ����� ��������" );
			} else if( isNaN( parseInt( form.lag.value ) ) ) {
				alert( "�������� ����� �������� �� ������ ���� �������" );
			//} else if (form.menu.options.value == ""){
			//	alert( "����� ������ ����� ��������." );
			//} else if (form.adminForm.textfieldcheck.value == 0){
			//	alert( "����� ������ ����� ������." );
			} else {
				submitform( pressbutton );
			}
		}
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="menus">
			�����:
			<small>
			<?php echo $row->id?'��������������':'�����'; ?>
			</small>
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th colspan="4">
			������ ������
			</th>
		</tr>
		<tr>
			<td width="10%">
			���������:
			</td>
			<td>
			<input class="inputbox" type="text" name="title" size="60" value="<?php echo $row->title; ?>" />
			</td>
			<td width="20px">&nbsp;

			</td>
			<td width="100%" rowspan="20" valign="top">
			������ � �������� ����:
			<br />
			<?php echo $lists['select']; ?>
			</td>
		</tr>
		<tr>
			<td>
			�������� ����� ��������:
			</td>
			<td>
			<input class="inputbox" type="text" name="lag" size="10" value="<?php echo $row->lag; ?>" /> (������ ����� ��������� �������)
			</td>
		</tr>
		<tr>
			<td valign="top">
			�����������:
			</td>
			<td>
			<?php echo $lists['published']; ?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			<br /><br />
			�������� �������:
			</td>
		</tr>
		<?php
		for($i = 0,$n = count($options); $i < $n; $i++) {
?>
			<tr>
				<td>
				<?php echo ($i + 1); ?>
				</td>
				<td>
				<input class="inputbox" type="text" name="polloption[<?php echo $options[$i]->id; ?>]" value="<?php echo htmlspecialchars(stripslashes($options[$i]->text)); ?>" size="60" />
				</td>
			</tr>
			<?php
		}
		for(; $i < 12; $i++) {
?>
			<tr>
				<td>
				<?php echo ($i + 1); ?>
				</td>
				<td>
				<input class="inputbox" type="text" name="polloption[]" value="" size="60"/>
				</td>
			</tr>
			<?php
		}
?>
		</table>

		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="com_poll" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="textfieldcheck" value="<?php echo $n; ?>" />
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}

}
?>
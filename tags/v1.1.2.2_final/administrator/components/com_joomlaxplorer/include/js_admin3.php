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
?>
<script language="JavaScript1.2" type="text/javascript">
<!--
	function check_pwd() {
		if(document.edituser.user.value=="" || document.edituser.home_dir.value=="") {
			alert("<?php echo $GLOBALS["error_msg"]["miscfieldmissed"]; ?>");
			return false;
		}
		if(document.edituser.chpass.checked &&
			document.edituser.pass1.value!=document.edituser.pass2.value)
		{
			alert("<?php echo $GLOBALS["error_msg"]["miscnopassmatch"]; ?>");
			return false;
		}
		return true;
	}
// -->
</script>

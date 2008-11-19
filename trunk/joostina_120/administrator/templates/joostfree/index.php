<?php
/**
* @JoostFREE
* @package Joostina
* @copyright ��������� ����� (C) 2008 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

// ������ ������� �������
defined('_VALID_MOS') or die();

$iso = explode('=',_ISO);
echo '<?xml version="1.0" encoding="'.$iso[1].'"?'.'>'."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $mosConfig_sitename; ?>  - ������ ���������� [ Joostina ]</title>
		<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<?php
/* ���������� fullajax */
mosCommonHTML::loadFullajax();
/* ���������� ���������� �������� ���������� ���� */
//mosCommonHTML::loadJWin();
/**
* ����������� css � js ������ �������
* $mainframe->addCSS(������_����_�_�����) - ���������� css �����
* $mainframe->addJS(������_����_�_�����) - ���������� java-script �����
**/
if($mosConfig_gz_js_css) { // ������ �� ������� css � js �������
	$mainframe->addCSS($mosConfig_live_site.'/administrator/templates/joostfree/css/joostfree_css.php');
	$mainframe->addJS($mosConfig_live_site.'/includes/js/joostina.admin.php');
} else { // ������������ ����������� - �� ������ �����
	$mainframe->addCSS($mosConfig_live_site.'/administrator/templates/joostfree/css/template_css.css');
	$mainframe->addJS($mosConfig_live_site.'/includes/js/joomla.javascript.full.js');
};
include_once ($mosConfig_absolute_path.'/editor/editor.php');
initEditor();
/**
* ����� ����������� js � css
*/

if(isset($mainframe->_head['custom'])) {
	$head = array();
	foreach($mainframe->_head['custom'] as $html) {
		$head[] = $html;
	}
	echo implode("\n",$head)."\n";
};
// �������� ������������ ����� - ����� ������� �������� ���� ����� ������������� ���������� ��� ��������
flush();
?>
	</head>
	<body>
	<div class="page">
		<div id="topper">
			<div id="wrapper">
				<div id="joo">
					<a href="index2.php" title="������� �� ������� �������� ������ ����������">
						<img border="0" alt="������� �� ������� �������� ������ ����������" src="templates/joostfree/images/logo.png" />
					</a>
				</div>
			</div>
			<div id="ajax_status">�����...</div>
			<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
				<tr class="menubackgr">
					<td style="padding-left: 7px;width: 25px;">
						<a href="<?php echo $mosConfig_live_site; ?>/administrator/index2.php" title="�� �������"><img border="0" alt="J!" src="templates/joostfree/images/m-logo.png" /></a>
					</td>
					<td width="85%">
						<?php mosLoadAdminModule('fullmenu'); ?>
					</td>
					<td align="right">
						<?php mosLoadAdminModules('header',-2); ?>
					</td>
					<td align="right">
						<input type="image" name="jtoggle_editor" id="jtoggle_editor" title="������������� ����������� ���������" onclick="jtoggle_editor();" src="images/<?php echo (intval(mosGetParam($_SESSION,'user_editor_off',''))) ? 'editor_off.png':'editor_on.png'?>" alt="��������� ��������" />
					</td>
					<td align="right">
						<a href="<?php echo $mosConfig_live_site; ?>/" target="_blank" title="������������ ����� � ����� ����">
							<img src="../includes/js/ThemeOffice/preview.png" border="0" alt="������������ �����"/>
						</a>
					</td>
					<td align="right" class="jtd_nowrap">
						<a href="index2.php?option=logout" class="logoff">����� <?php echo $my->username; ?></a>&nbsp;
					</td>
				</tr>
			</table>
		</div>
		<div id="top-toolbar"><?php mosLoadAdminModule('toolbar'); ?></div>
		<?php mosLoadAdminModule('mosmsg'); ?>
		<div id="status-info" style="display: none;">&nbsp;</div>
		
		<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="center">
					<div id="main_body">
						<?php mosMainBody_Admin(); ?>
					</div>
				</td>
			</tr>
		</table>
		<div id="footer_cleaner">&nbsp;</div>
	</div>
		<div id="footer" align="center" class="smallgrey"><?php echo $jostina_ru; ?></div>
<?php if ( mosLoadAdminModule('debug',2) > 0 ) {?>
		<div id="debug"><?php mosLoadAdminModule('debug',2); ?></div>
<?php }?>
		<script type="text/javascript" language="JavaScript">function jf_hideLoading() {document.getElementById('ajax_status').style.display='none';};if (window.addEventListener) {window.addEventListener('load', jf_hideLoading, false);} else if (window.attachEvent) {var r=window.attachEvent("onload", jf_hideLoading);}else{jf_hideLoading();}</script>
	</body>
</html>
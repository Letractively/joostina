<?php
/**
* @JoostFREE
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

$iso = explode('=',_ISO);
echo '<?xml version="1.0" encoding="'.$iso[1].'"?'.'>'."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $mosConfig_sitename; ?> - <?php echo _JOOSTINA_CONTROL_PANEL?></title>
		<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
		
		<script type="text/javascript">
    		var _live_site = '<?php echo $mosConfig_live_site;?>';
    		var _option = '<?php echo mosGetParam( $_REQUEST, 'option', '' );?>';
    		var _js_defines = new Array();
		</script>
<?php
/* подключаем fullajax */
mosCommonHTML::loadFullajax();

if($mosConfig_gz_js_css) { // работа со сжатыми css и js файлами
	$mainframe->addCSS($mosConfig_live_site.'/'.ADMINISTRATOR_DIRECTORY.'/templates/joostfree/css/joostfree_css.php');
	$mainframe->addJS($mosConfig_live_site.'/includes/js/joostina.admin.php');
} else { // использовать стандартные - не сжатые файлы
	$mainframe->addCSS($mosConfig_live_site.'/'.ADMINISTRATOR_DIRECTORY.'/templates/joostfree/css/template_css.css');
	$mainframe->addJS($mosConfig_live_site.'/includes/js/joomla.javascript.full.js');
};
include_once ($mosConfig_absolute_path.DS.'editor/editor.php');
initEditor();
/**
* вывод подключения js и css
*/

if(isset($mainframe->_head['custom'])) {
	$head = array();
	foreach($mainframe->_head['custom'] as $html) {
		$head[] = $html;
	}
	echo implode("\n",$head)."\n";
};
if(isset($mainframe->_head['js'])) {
	$head = array();
	foreach($mainframe->_head['js'] as $html) {
		$head[] = $html;
	}
	echo implode("\n",$head)."\n";
};
if(isset($mainframe->_head['css'])) {
	$head = array();
	foreach($mainframe->_head['css'] as $html) {
		$head[] = $html;
	}
	echo implode("\n",$head)."\n";
};

// отправим пользователю шапку - пусть браузер работает пока будет формироваться дальнейший код страницы
flush();
?>
<link rel="shortcut icon" href="<?php echo $mosConfig_live_site; ?>/images/favicon.ico" />
	</head>
	<body>
	<div class="page">
		<div id="topper">
			<div id="wrapper">
				<div id="joo">
					<a href="index2.php" title="<?php echo _GO_TO_MAIN_ADMIN_PAGE?>">
						<img border="0" alt="<?php echo _GO_TO_MAIN_ADMIN_PAGE?>" src="templates/joostfree/images/logo.png" />
					</a>
				</div>
			</div>
			<div id="ajax_status"><?php echo _PLEASE_WAIT?></div>
			<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
				<tr class="menubackgr">
					<td style="padding-left: 7px;width: 25px;">
						<a href="<?php echo $mosConfig_live_site; ?>/<?php echo ADMINISTRATOR_DIRECTORY?>/index2.php" title="<?php echo _GO_TO_MAIN_ADMIN_PAGE?>"><img border="0" alt="J!" src="templates/joostfree/images/m-logo.png" /></a>
					</td>
					<td width="85%">
						<?php mosLoadAdminModule('fullmenu'); ?>
					</td>
					<td align="right" class="header_info">
						<?php mosLoadAdminModules('header',-2); ?>
					</td>
					<td align="right">
						<input type="image" name="jtoggle_editor" id="jtoggle_editor" title="<?php echo _TOGGLE_WYSIWYG_EDITOR?>" onclick="jtoggle_editor();" src="templates/joostfree/images/toolbar_ico/<?php echo (intval(mosGetParam($_SESSION,'user_editor_off',''))) ? 'editor_off.png':'editor_on.png'?>" alt="<?php echo _DISABLE_WYSIWYG_EDITOR?>" />
					</td>
					<td align="right">
						<a href="<?php echo $mosConfig_live_site; ?>/" target="_blank" title="<?php echo _PREVIEW_SITE?>">
							<img src="../includes/js/ThemeOffice/preview.png" border="0" alt="<?php echo _PREVIEW_SITE?>"/>
						</a>
					</td>
					<td align="right" class="jtd_nowrap">
						<a href="index2.php?option=logout" class="logoff"><?PHP echo _BUTTON_LOGOUT?> <?php echo $my->username; ?></a>&nbsp;
					</td>
				</tr>
			</table>
		</div>
		<div id="top-toolbar"><?php mosLoadAdminModule('toolbar'); ?></div>
		<?php mosLoadAdminModule('mosmsg'); ?>
		<?php mosLoadAdminModule('component_menu'); ?>
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
		<script type="text/javascript" language="JavaScript">function jf_hideLoading() {SRAX.get('ajax_status').style.display='none';};if (window.addEventListener) {window.addEventListener('load', jf_hideLoading, false);} else if (window.attachEvent) {var r=window.attachEvent("onload", jf_hideLoading);}else{jf_hideLoading();}</script>
	</body>
</html>

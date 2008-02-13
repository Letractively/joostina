<?php
/**
* @JoostFREE
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, смотрите LICENSE.php
* Joostina! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );

$tstart = mosProfiler::getmicrotime();
$iso = explode( '=', _ISO );
echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>'."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $mosConfig_sitename; ?>  - Панель управления [ Joostina ]</title>
		<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
		<?php if($mosConfig_gz_js_css){?>
		<link rel="stylesheet" href="<?php echo $mosConfig_live_site; ?>/administrator/templates/joostfree/css/joostfree_css.php" type="text/css" />
		<script language="JavaScript" src="<?php echo $mosConfig_live_site; ?>/includes/js/joostina.admin.php" type="text/javascript" /></script>
		<?php }else{?>
		<link rel="stylesheet" href="templates/joostfree/css/template_css.css" type="text/css" />
		<script language="JavaScript" src="<?php echo $mosConfig_live_site; ?>/includes/js/JSCookMenu.js" type="text/javascript"></script>
		<script language="JavaScript" src="<?php echo $mosConfig_live_site; ?>/administrator/includes/js/ThemeOffice/theme.js" type="text/javascript"></script>
		<script language="JavaScript" src="<?php echo $mosConfig_live_site; ?>/includes/js/joomla.javascript.js" type="text/javascript"></script>
		<?php };?>
	<?php
			include_once( $mosConfig_absolute_path . '/editor/editor.php' );
			initEditor();
			if (isset( $mainframe->_head['custom'] )){
				$head = array();
				foreach ($mainframe->_head['custom'] as $html) {
					$head[] = $html;
				}
				echo implode( "\n", $head ) . "\n";
			}
		?>
	</head>
	<body>
		<div id="topper">
			<div id="wrapper">
				<div id="joo">
					<a href="index2.php" title="Перейти на главную страницу Панели управления"><img border="0" alt="Перейти на главную страницу Панели управления" src="templates/joostfree/images/logo.png" /></a>
				</div>
			</div>
			<div id="ajax_status">Ждите...</div>
			<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
				<tr class="menubackgr">
					<td>
						<?php mosLoadAdminModule( 'fullmenu' );?>
					</td>
					<td align="right">
						<form action="<?php echo  $mosConfig_live_site. '/administrator/index2.php?' .$_SERVER['QUERY_STRING']; ?>" method="post" name="form_editor_off" id="form_editor_off">
						<?php if(!intval( mosGetParam( $_SESSION, 'user_editor_off', '' ) )){?>
								<input type="hidden" name="user_editor_off" value="1" />
								<input type="image" name="editor_off" title="Отключить визуальный редактор" src="../includes/js/ThemeOffice/editor_on.png" alt="Отключить редактор" />
						<?php }else{?>
								<input type="hidden" name="user_editor_on" value="1" />
								<input type="image" name="editor_on" title="Включить визуальный редактор" src="../includes/js/ThemeOffice/editor_off.png" alt="Включить редактор" />
							<?php };?>
					</form>
					</td>
					<td align="right">
							<?php mosLoadAdminModules( 'header', -2 );?>
					</td>
					<td align="right">
						<a href="<?php echo $mosConfig_live_site; ?>/" target="_blank" title="Предпросмотр сайта в новом окне"><img src="../includes/js/ThemeOffice/preview.png" border="0" alt="Предпросмотр сайта"/></a>
					</td>
					<td align="right">
						<a href="index2.php?option=logout" style="color: #333333; font-weight: bold">Выход <?php echo $my->username;?></a>&nbsp;
					</td>
				</tr>
			</table>
		</div>

		<div id="top-toolbar">
			<?php mosLoadAdminModule( 'toolbar');?>
		</div>
		<?php mosLoadAdminModule( 'mosmsg' );?>
		<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="center">
					<div id="main_body">
						<?php mosMainBody_Admin(); ?>
					</div>
				</td>
			</tr>
		</table>
		<div id="footer" align="center" class="smallgrey">
			<?php echo $jostina_ru; ?>
		</div>
		<div id="debug"><?php mosLoadAdminModule( 'debug',2 );?></div>
		<script type="text/javascript" language="JavaScript">function hideLoading() {document.getElementById('ajax_status').style.display='none';};if (window.addEventListener) {window.addEventListener('load', hideLoading, false);} else if (window.attachEvent) {var r=window.attachEvent("onload", hideLoading);}else{hideLoading();}</script>
	</body>
</html>

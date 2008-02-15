<?php
/**
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
require(dirname(__FILE__).'/../../die.php');

/**
 * @package Custom QuickIcons
 */
class HTML_QuickIcons {

	function show( &$rows, $option, $search, &$pageNav ){
		global $mosConfig_live_site;
		mosCommonHTML::loadOverlib(); ?>

		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
			<tr>
				<th>
					<?php echo _QI_QUICKICONS . ' :: ' . _QI_HDR_MGMNT; ?>
				</th>
				<td><?php echo _QI_SEARCH; ?>:</td>
				<td align="right">
					<input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
				</td>
			</tr>
		</table>

		<?php
		if( !file_exists( $GLOBALS['mosConfig_absolute_path'] . '/administrator/modules/mod_customquickicons.php' ) ) {
			echo '<table width="95%" style="text-align:center; color:red; font-weight:bold; font-size:14px; background-color:#FFDDDD; border:1px solid #999999; margin:2px;">' . "\n"
			. '<tr><td>'
			. _QI_ERR_NO_MOD_INSTALLED
			. '</td></tr>' . "\n"
			. '</table>' . "\n";
		} ?>

		<table class="adminlist">
			<tr>
				<th width="20">#</th>
				<th width="20" class="title">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
				</th>
				<th width="5%" class="jtd_nowrap"><?php echo _QI_ICO; ?></th>
				<th width="43%" class="title">
					<?php echo _QI_NAME; ?>
				</th>
				<th class="jtd_nowrap"><?php echo _QI_DISPLAY; ?></th>
				<th width="7%" class="jtd_nowrap"><?php echo _QI_CMN_ACCESS; ?></th>
				<th width="7%" class="jtd_nowrap"><?php echo _QI_PUBLISHED; ?></th>
				<th width="7%" colspan="2" class="jtd_nowrap"><?php echo _QI_REORDER; ?></th>
				<th width="2%"><?php echo _QI_ORDER; ?></th>
				<th width="1%">
					<a href="javascript:saveorder(<?php echo count( $rows ) - 1; ?>)" title="<?php echo _QI_SAVE_ORDER; ?>"><img src="images/filesave.png" border="0" width="16" height="16" alt="<?php echo _QI_SAVE_ORDER; ?>" /></a>
				</th>
				<th>
					<?php
					$tip = _QI_TIP_ACCESSKEY;
					echo mosToolTip( $tip ); ?>
				</th>
				<th width="40%" class="title"><?php echo _QI_TARGET; ?></th>
			</tr>
			<?php
			$k=0;
			for ($i=0; $i<count($rows); $i++ ){
				$row 		= $rows[$i];
				$editLink   = 'index2.php?option=com_customquickicons&amp;task=edit&amp;id='. $row->id;
				$link   	= 'index2.php?option=com_customquickicons&amp;task=';

				$img		= $row->published ? 'tick.png' : 'publish_x.png';
				$task   	= $row->published ? 'unpublish' : 'publish';
				$alt		= $row->published ? _QI_UNPUBLISH : _QI_PUBLISH;

				$checked	= mosCommonHTML::CheckedOutProcessing( $row, $i );

				// check display
				$display = '';
				switch( $row->display ) {
					case '1':
						$display = _QI_DISPLAY_TEXT;
						break;

					case '2':
						$display = '<span style="color:red;">' . _QI_DISPLAY_ICON . '</span>';
						break;

					default:
						$display = '<span style="color:green;">' . _QI_DISPLAY_ICON_TEXT . '</span>';
						break;
				}
				?>
				<tr class="row<?php echo $k; ?>">
					<td><?php echo $row->id; ?></td>
					<td><?php echo $checked; ?></td>
					<td><?php echo '<img src="' . $mosConfig_live_site . $row->icon . '" alt="" border="0" />';?></td>
					<td valign="top">
						<a href="<?php echo $editLink; ?>" title="<?php echo _QI_TIT_EDIT_ENTRY; ?>"><?php echo $row->text; ?></a>
					</td>
					<td><?php echo $display; ?></td>
					<td align="left"><?php echo $row->groupname; ?></td> 
					<td align="center">
						<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')" title="<?php echo $alt; ?>">
							<img src="images/<?php echo $img; ?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
						</a>
					</td>
					<td align="center">
						<a href="<?php echo $link . 'orderUp&amp;id='. $row->id; ?>" title="<?php echo _QI_ORDER_UP; ?>"><img src="images/uparrow.png" width="12" height="12" border="0" alt="<?php echo _QI_ORDER_UP; ?>" /></a>
					</td>
					<td align="center">
						<a href="<?php echo $link . 'orderDown&amp;id='. $row->id; ?>" title="<?php echo _QI_ORDER_DOWN; ?>"><img src="images/downarrow.png" width="12" height="12" border="0" alt="<?php echo _QI_ORDER_DOWN; ?>" /></a>
					</td>
					<td align="center" colspan="2">
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
					</td>
					<td align="center">
						<?php
						echo $row->akey ? $row->akey : '<span style="color:red; font-weight:bold;">!</span>'; ?>
					</td>
					<td>
						<?php
						if( $row->target == 'index2.php?option=' || !$row->target ){
							echo '<span style="color:red; font-weight:bold;">' . _QI_ERR_NO_TARGET . '</span>';
						}else{
							echo htmlentities( $row->target );
						} ?>
					</td>
				</tr>
				<?php
				$k = 1-$k;
			} ?>
			</table>
			<?php echo $pageNav->getListFooter(); ?>

			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
		HTML_QuickIcons::_qiFOOTER();
	}

	function edit( &$row, $lists, $option ){
		global $mosConfig_live_site;

		mosMakeHtmlSafe( $row, ENT_QUOTES );
		mosCommonHTML::loadOverlib();
		$tabs = new mosTabs( 0 ); ?>

		<script type="text/javascript">
			/* <![CDATA[ */
			function string_replace(string, search, replace) {
				var new_string = "";
				var i = 0;

				while(i < string.length) {
					if(string.substring(i, i + search.length) == search) {
						new_string = new_string + replace;
						i = i + search.length - 1;
					}else{
						new_string = new_string + string.substring(i, i + 1);
						i++;
					}
					return new_string;
				}
			}

			function applyTag(tag, obj) {
				var pre = document.adminForm.prefix;
				var post = document.adminForm.postfix;

				if (!obj.checked) {
					pre.value = string_replace(pre.value, '<'+tag+'>', '');
					post.value = string_replace(post.value, '</'+tag+'>', '');
				}else{
					pre.value = '<'+tag+'>' + pre.value;
					post.value = post.value + '</'+tag+'>';
				}
			};

			function changeIcon( icon ) {
				if (document.all) {
					document.all.iconImg.src = '<?php echo $GLOBALS['mosConfig_live_site']; ?>' + icon;
				}else{
					document.getElementById('iconImg').src = '<?php echo $GLOBALS['mosConfig_live_site']; ?>' + icon;
				}
			};

			function addTarget() {
				// taken from daniel grothe - thx!
				var exclude = document.adminForm.target.value.split(',');
				exclude.push( document.adminForm.tar_gets.value );

				//remove duplicates;
				var tmp = new Object();
				for(var i = 0; i < exclude.length; i++) {
					var id = exclude[i];
					if( !isNaN(id)) {
						continue;
					}

					tmp[ id ] = 'index2.php?' + id;
				}
				exclude = new Array();
				for(var k in tmp) {
					exclude.push( tmp[k] );
				}

				document.adminForm.target.value = exclude.pop('');
			};
			/* ]]> */
		</script>
		<form action="index2.php" method="post" name="adminForm">
			<table class="adminheading">
			<tr>
				<th>
					<?php
					if( $row->id ){
						echo _QI_DETAIL_EDIT; ?>
						&nbsp;[&nbsp;<small><?php echo $row->text; ?></small>&nbsp;]
						<?php
					}else{
						echo _QI_DETAIL_NEW;
					} ?>
				</th>
			</tr>
			</table>

			<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
				<tr>
					<td>
					<?php
					$tabs->startPane( 'qicons' );
					$tabs->startTab( _QI_TABS_GENERAL, 'general' ); ?>
					<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
						<tr>
							<td align="right" width="120"><?php echo _QI_TARGET; ?></td>
							<td align="left">
								<input class="inputbox" type="text" name="target" id="target" size="75" maxlength="255" value="<?php echo ( $row->target ? $row->target : 'index2.php?option=' ); ?>" />
								&nbsp;
								&nbsp;<button onclick="addTarget(); return false;">&larr;</button>&nbsp;
								&nbsp;<?php echo $lists['targets']; ?>
								&nbsp;
								<?php
								$tip = _QI_TIP_TARGET;
								echo mosToolTip( $tip ); ?>
							</td>
						</tr>
						<tr>
							<td align="right"><label for="new_window"><?php echo _QI_DETAIL_NEW_WINDOW; ?></label></td>
							<td align="left">
								<input type="checkbox" name="new_window" value="1" id="new_window"<?php echo $row->new_window ? ' checked="checked"' : ''; ?> />
								&nbsp;
								<?php
								$tip = _QI_TIP_DETAIL_NEW_WINDOW;
								echo mosToolTip( $tip ); ?>
							</td>
						</tr>
						<tr>
							<td  align="right" width="130">
								<?php echo _QI_PUBLISHED; ?>
							</td>
							<td align="left">
								<input type="radio" id="published1" name="published" value="1"<?php echo $row->published ? ' checked="checked"' : ''; ?> /><label for="published1"><?php echo _QI_DETAIL_YES; ?></label>
								&nbsp;&nbsp;
								<input type="radio" id="published2" name="published" value="0"<?php echo $row->published ? '' : ' checked="checked"'; ?> /><label for="published2"><?php echo _QI_DETAIL_NO; ?></label>
							</td>
						</tr>
						<tr>
							<td  align="right">
								<?php echo _QI_DETAIL_ORDER; ?>
							</td>
							<td align="left">
								<?php echo $lists['ordering']; ?>
							</td>
						</tr>
						<tr>
							<td align="right"><?php echo _QI_CMN_ACCESS; ?></td>
							<td align="left">
								<?php echo $lists['gid']; ?>
							</td>
						</tr>
					</table>
					<?php
					$tabs->endTab();
					$tabs->startTab( _QI_TABS_TEXT, 'text' ); ?>
					<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
						<tr>
							<td width="120" align="right">
								<?php echo _QI_DETAIL_PREFIX; ?>
							</td>
							<td align="left">
								<input class="inputbox" type="text" name="prefix" size="30" maxlength="100" value="<?php echo $row->prefix; ?>" />
							</td>
						</tr>
						<tr>
							<td align="right"><?php echo _QI_DETAIL_TEXT; ?></td>
							<td align="left">
								<input class="inputbox" type="text" name="text" size="50" maxlength="100" value="<?php echo $row->text; ?>" />
								&nbsp;&nbsp;
								<input type="checkbox" name="bold" id="bold" onclick="applyTag('b', this)"<?php if (strpos(($row->prefix), "&lt;b&gt;")!== false) echo ' checked="checked"'; ?> />
								<label for="bold"><strong><?php echo _QI_FONT_BOLD; ?></strong></label>
								<input type="checkbox" name="italic" id="italic" onclick="applyTag('i', this)"<?php if (strpos(($row->prefix), "&lt;i&gt;")!== false) echo ' checked="checked"'; ?> />
								<label for="italic"><i><?php echo _QI_FONT_ITALIC; ?></i></label>
								<input type="checkbox" name="underlined" id="underlined" onclick="applyTag('u', this)"<?php if (strpos(($row->prefix), "&lt;u&gt;")!== false) echo ' checked="checked"'; ?> />
								<label for="underlined"><u><?php echo _QI_FONT_UNDERLINE; ?></u></label>
								&nbsp;
								<?php
								$tip = _QI_TIP_FONT;
								echo mosToolTip( $tip ); ?>
							</td>
						</tr>
						<tr>
							<td align="right"><?php echo _QI_DETAIL_POSTFIX; ?></td>
							<td align="left">
								<input class="inputbox" type="text" name="postfix" size="30" maxlength="100" value="<?php echo $row->postfix; ?>" />
							</td>
						</tr>
						<tr>
							<td align="right"><?php echo _QI_ACCESSKEY; ?></td>
							<td align="left">
								<input class="inputbox" type="text" name="akey" size="1" maxlength="1" value="<?php echo $row->akey; ?>" />
								&nbsp;
								<?php
								$tip = _QI_TIP_ACCESSKEY;
								echo mosWarning( $tip ); ?>
							</td>
						</tr>
						<tr>
							<td align="right"><?php echo _QI_TITLE; ?></td>
							<td align="left">
								<input class="inputbox" type="text" name="title" size="50" maxlength="64" value="<?php echo $row->title; ?>" />
								&nbsp;
								<?php
								$tip = _QI_TIP_TITLE;
								echo mosToolTip( $tip ); ?>
							</td>
						</tr>
					</table>
					<?php
					$tabs->endTab();
					$tabs->startTab( _QI_TABS_DISPLAY, 'display' ); ?>
					<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td align="right"><?php echo _QI_DISPLAY; ?></td>
							<td align="left">
								<?php echo $lists['display']; ?>
							</td>
						</tr>
						<tr>
							<td align="right"><?php echo _QI_DETAIL_ICON; ?></td>
							<td align="left">
								<input class="inputbox" type="text" name="icon" size="100" maxlength="100" value="<?php echo $mosConfig_live_site . $row->icon; ?>" onblur="changeIcon(this.value)" />
								&nbsp;&nbsp;
								<a href="index2.php?option=<?php echo $option; ?>&amp;task=chooseIcon" target="_blank" title="<?php echo _QI_TIT_CHOOSE_ICON; ?>"><strong><?php echo _QI_DETAIL_CHOOSE_ICON; ?></strong></a>
								&nbsp;
								<?php
								$tip = _QI_TIP_ICON;
								echo mosToolTip( $tip ); ?>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td style="padding-top:10px">
								<?php
								if( empty( $row->icon )){
									$iconLink = 'blank.png';
								}else{
									$iconLink = $row->icon;
								} ?>
								<img id="iconImg" src="<?php echo $mosConfig_live_site . $iconLink; ?>" alt="" />
							</td>
						</tr>
					</table>
					<?php
					$tabs->endTab();
					$tabs->startTab( _QI_TABS_CHECK, 'check' ); ?>
					<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
						<tr>
							<td align="right" width="120">
								<label for="new_window"><?php echo _QI_CMT_CHECK; ?></label>
							</td>
							<td align="left">
								<input type="checkbox" name="cm_check" value="1" id="cm_check"<?php echo $row->cm_check ? ' checked="checked"' : ''; ?>/>
								&nbsp;
								<?php
								$tip = _QI_TIP_CMT_CHECK;
								echo mosToolTip( $tip ); ?>
							</td>
						</tr>
						<tr>
							<td colspan="2"><?php echo _QI_CMT_NAME_TO_CHECK; ?></td>
						</tr>
						<tr>
							<td align="left">
								../administrator/components/
							</td align="left">
							<td align="left">
								<input class="inputbox" type="text" name="cm_path" size="75" maxlength="255" value="<?php echo ( $row->cm_path ? $row->cm_path : '' ); ?>" />
								&nbsp;
								<?php
								$tip = _QI_TIP_CM_PATH;
								echo mosToolTip( $tip ); ?>
								<br />
								<?php echo $lists['components_check']; ?>
								&nbsp;
								<?php
								$tip = _QI_TIP_CM_PATH_CHECK;
								echo mosWarning( $tip ); ?>
							</td>
						</tr>
					</table>
					<?php
					$tabs->endTab();
					$tabs->endPane(); ?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
		HTML_QuickIcons::_qiFOOTER();
	}

	function quickiconButton( $image ) {
		global $mosConfig_absolute_path, $mosConfig_live_site;

		$image = str_replace( $mosConfig_absolute_path, $mosConfig_live_site, $image );
		$js_action = "window.opener.document.adminForm.icon.value='$image'; window.opener.changeIcon('$image'); window.close()"; ?>
		<div style="float:left;">
			<div class="icon">
				<a href="javascript:void(0);" onclick="<?php echo $js_action; ?>;">
					<?php /* echo mosAdminMenus::imageCheckAdmin( $image, '/administrator/images/', NULL, NULL, $image ); */ ?>
					<span><img src="<?php echo $image; ?>" alt="<?php echo $image; ?>" title="<?php echo $image; ?>" border="0" /></span>
				</a>
			</div>
		</div>
		<?php
	}

	function chooseIcon( $imgs, $option, $icons ){ ?>

		<table class="adminheading">
			<tr><th><?php echo _QI_DETAIL_CHOOSE_ICON; ?></th></tr>
		</table>

		<table class="adminform">
			<tr>
				<th>
					<div style="text-align:left;"><?php echo _QI_MSG_CHOOSE_ICON . ' [' . $icons . ']'; ?></div>
					<div style="text-align:right;">
						<a href="#" onclick="window.close()"><?php echo _PROMPT_CLOSE; ?></a>
					</div>
				</th>
			</tr>
			<tr>
				<td style="padding:30px">
					<div id="cpanel">
						<?php
						for( $i = 0; $i < count( $imgs ); $i++ ){
							HTML_QuickIcons::quickiconButton( $imgs[$i] );
						} ?>
					</div>
				</td>
			</tr>
		</table>
		<?php
		HTML_QuickIcons::_qiFOOTER();
	}

	function _qiFOOTER() { 
		return;	
	?>
		<div style="text-align:center; color:#666666; font-size:9px; margin-top:5px;">
			Version:
			<?php
			$QI_VERSION = CustomQuickIcons::_QI_version();
			echo $QI_VERSION['version'] . '&nbsp;-&nbsp;' . $QI_VERSION['date']; ?>
			&nbsp;|&nbsp;
			Copyright&nbsp;&copy;&nbsp;<?php echo date( 'Y' ); ?>&nbsp;-&nbsp;Custom QuickIcons powered by <a href="http://www.joomx.com" title="www.joomx.com - Professional Services Around Joomla!" target="_blank">www.joomx.com</a>
			&nbsp;|&nbsp;
			<a href="http://www.mgfi.info/index.php?option=com_versions&amp;ci=10&amp;mv=<?php echo $QI_VERSION['version']; ?>" target="_blank" title="<?php echo _QI_CHECK_VERSION; ?>"><?php echo _QI_CHECK_VERSION; ?><img border="0" src="http://www.mgfi.info/index.php?option=com_versions&amp;ci=10&amp;mv=<?php echo $QI_VERSION['version']; ?>" alt="" height="1" width="1" /></a>
		</div>
		<?php
	}

	function _support() {
		global $mainframe; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo _QI_LNG; ?>" xml:lang="<?php echo _QI_LNG; ?>">
	<head>
		<title><?php echo _QI_SUPP_HEAD_TITLE; ?></title>
		<link href="<?php echo $mainframe->getCfg( 'live_site' ); ?>/administrator/components/com_customquickicons/help/help.css"
			  rel="stylesheet"
			  type="text/css"
			  media="all" />
		<meta name="copyright"
			  content="(C) 2006/07 www.joomx.com All rights reserved." />
		<meta name="support"
			  content="http://www.joomx.com Support" />
		<meta http-equiv="Content-Type"
			  content="text/html; charset=UTF-8" />
	</head>
	<body>
		<div style="text-align:left; margin:20px; padding:20px; background-color:#F3F9FF; border:1px solid #006699;">
			<div>
				<?php echo _QI_SUPP1 . _QI_SUPP2; ?>
			</div>
			<div style="margin:20px; border-top:1px dotted #006699;"></div>
			<div style="text-align:center;">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_s-xclick" />
					<input type="image" src="https://www.paypal.com/<?php echo _QI_BTN_LNG; ?>/i/btn/x-click-butcc-donate.gif" name="submit" alt="<?php echo _QI_SUPP_BTN_TITLE; ?>" title="<?php echo _QI_SUPP_BTN_TITLE; ?>" />
					<img alt="" border="0" src="https://www.paypal.com/<?php echo _QI_BTN_LNG; ?>/i/scr/pixel.gif" width="1" height="1" />
					<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" />
				</form>
				<div style="margin:20px; border-top:1px dotted #006699;"></div>
				<a href="https://www.moneybookers.com/app/?rid=3150988" target="_blank" title="MoneyBookers"><img src="http://www.moneybookers.com/images/banners/flags.gif" alt="MoneyBookers" border="0" height="31" width="88" /></a>
				<br />
				<?php echo _QI_SUPP_TXT_PAY_W_MB; ?>
				<br />
				<form action="https://www.moneybookers.com/app/payment.pl" method="post" target="_blank">
					<input name="pay_to_email" value="info@joomx.com" type="hidden" />
					<input name="status_url" value="info@joomx.com" type="hidden" />
					<input name="language" value="de" type="hidden" />
					<input name="currency" value="EUR" type="hidden" />
					<input name="detail1_description" value="<?php echo _QI_SUPP_INP_TXT; ?>" type="hidden" />
					<input name="detail1_text" value="<?php echo _QI_SUPP_INP_TXT; ?>" type="hidden" />
					<input name="amount" class="inputbox" size="6" value="10" type="text" /> EUR
					<br />
					<input value="<?php echo _QI_SUPP_BTN_SUBMIT; ?>" type="submit" />
				</form>
			</div>
		</div>
	</body>
</html>
	<?php
	}
}
?>

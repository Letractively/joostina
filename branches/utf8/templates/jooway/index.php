<?php

defined( "_VALID_MOS" ) or die( "Прямой вызов файла запрещён." );

$iso = explode( '=', _ISO );

echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php mosShowHead(); ?>
<link href="<?php echo $mosConfig_live_site;?>/templates/jooway/css/template_css.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]>
<link href="<?php echo $mosConfig_live_site;?>/templates/jooway/css/ieonly.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
<body id="page_bg">
<div class="center" align="center">
	<div id="wrapper">
		<div id="wrapper_r">
			<div id="header">
				<div id="header_l">
					<div id="header_r">
						<a href="<?php echo $mosConfig_live_site;?>" id="logo">&nbsp;</a>
						<?php mosLoadModules ( 'top' ,-2); ?>
					</div>
				</div>
			</div>
			<div id="tabarea">
				<div id="tabarea_l">
					<div id="tabarea_r">
						<div id="tabmenu">
						<table cellpadding="0" cellspacing="0" class="pill">
							<tr>
								<td class="pill_l">&nbsp;</td>
								<td class="pill_m">
								<div id="pillmenu">
									<?php mosLoadModules ( 'user3',-1); ?>
								</div>
								</td>
								<td class="pill_r">&nbsp;</td>
							</tr>
						</table>
						</div>
					</div>
				</div>
			</div>

			<div id="search">
				<?php mosLoadModules ( 'user4' , -1); ?>
			</div>
			<div id="pathway">
				<?php mosPathWay(); ?>
			</div>
			<div class="clr"></div>
			<div id="whitebox">
				<div id="whitebox_t">
					<div id="whitebox_tl">
						<div id="whitebox_tr">&nbsp;</div>
					</div>
				</div>

				<div id="whitebox_m">
					<div id="area">
						<div id="leftcolumn">
							<?php mosLoadModules ( 'left' , -3); ?>
							<?php mosLoadModules ( 'user8' ); ?>
							<?php mosLoadModules ( 'user9' ); ?>
						</div>
						<div id="maincolumn">
				<?php if ( mosCountModules( 'user1' ) > 0 || mosCountModules( 'user2' ) > 0 ) {?>
							<table class="nopad user1user2">
								<tr valign="top">
									<td <?php if ( mosCountModules( 'user1' ) > 0 && mosCountModules( 'user2' ) > 0 ) {?>class="half1"<?php }?>>
										<?php mosLoadModules ('user1'); ?>
									</td>
									<?php if ( mosCountModules( 'user1' ) > 0 && mosCountModules( 'user2' ) > 0 ) {?>
										<td class="greyline">&nbsp;</td>
									<?php }?>
									<td>
										<?php if (mosCountModules( "user2" )) { mosLoadModules ('user2'); } ?>
									</td>
								</tr>
							</table>
							<div id="maindivider"></div>
				<?php }?>
							<table class="nopad content">
								<tr valign="top">
									<td>
										<?php mosLoadModules ( 'user7' ); ?>
										<?php mosMainBody(); ?>
										<?php if ( mosCountModules( 'user5' ) > 0 ) {?><div id="maindivider"></div>
										<?php }?>
										<?php mosLoadModules ( 'user5' ); ?>
									</td>
									<?php
										if(mosCountModules('right') && $task != 'edit' ) {
									?>
									<td class="greyline">&nbsp;</td>
									<td width="170">
										<?php mosLoadModules ( 'right' ); ?><?php mosLoadModules ( 'user6' ); ?>
									</td>
									<?php
									}?>
								</tr>
							</table>
						</div>
						<div class="clr">&nbsp;</div>
					</div>
					<div class="clr">&nbsp;</div>
				</div>
				<div id="whitebox_b">
					<div id="whitebox_bl">
						<div id="whitebox_br">&nbsp;</div>
					</div>
				</div>
			</div>
			<div id="footerspacer">&nbsp;</div>
		</div>

		<div id="footer">
			<div id="footer_l">
				<div id="footer_r">
					<?php mosLoadModules ( 'bottom',-1 ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php mosLoadModules ( 'debug' ); ?>
<?php mosLoadModules ( 'footer' ); ?>
</body>
</html>

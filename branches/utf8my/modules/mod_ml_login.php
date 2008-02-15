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
*
* Данный файл изменен Mitrich http://mitrichlab.ru
*/
require(dirname(__FILE__).'/../die.php');

global $mosConfig_frontend_login;

if ( $mosConfig_frontend_login != NULL && ($mosConfig_frontend_login === 0 || $mosConfig_frontend_login === '0')) {
	return;
}

// url of current page that user will be returned to after login
if ($query_string = mosGetParam( $_SERVER, 'QUERY_STRING', '' )) {
	$return = 'index.php?' . $query_string;
} else {
	$return = 'index.php';
}
$ml_login_def_text = _USERNAME;
$ml_pass_def_text = _PASSWORD;
// converts & to &amp; for xtml compliance
$return 				= str_replace( '&', '&amp;', $return );

$registration_enabled	= $mainframe->getCfg( 'allowUserRegistration' );
$message_login			= $params->def( 'login_message',	0 );
$message_logout			= $params->def( 'logout_message',	0 );
$login					= $params->def( 'login',			$return );
$logout					= $params->def( 'logout',			$return );
$name					= $params->def( 'name',				1 );
$greeting				= $params->def( 'greeting',			1 );
$pretext				= $params->get( 'pretext' );
$posttext				= $params->get( 'posttext' );
$field_width 			= $params->get( 'field_width',10 );
$show_login_text		= $params->get( 'show_login_text',	1 );
$show_pass_text			= $params->get( 'show_pass_text',	1 );
$show_remember			= $params->get( 'show_remember',	1 );
$show_lost_pass         = $params->get( 'show_lost_pass',	1 );
$show_not_registered    = $params->get( 'show_not_registered',1 );
$show_register          = $params->get( 'show_register',	1 );
$module_align           = $params->get( 'module_align','left' );
$orientation            = $params->get( 'orientation' );
$moduleclass_sfx        = $params->get( 'moduleclass_sfx' );
$module_height          = $params->get( 'module_height','100%' );
$module_width           = $params->get( 'module_width','100%' );
$ml_login_text          = $params->get( 'ml_login_text', _USERNAME );
$ml_pass_text           = $params->get( 'ml_pass_text', _PASSWORD );
$ml_rem_text            = $params->get( 'ml_rem_text', _REMEMBER_ME );
$ml_rem_pass_text       = $params->get( 'ml_rem_pass_text', _LOST_PASSWORD );
$ml_not_reg_text        = $params->get( 'ml_not_reg_text', _NO_ACCOUNT );
$ml_reg_text            = $params->get( 'ml_reg_text', _CREATE_ACCOUNT );
$show_login_tooltip     = $params->get( 'show_login_tooltip' );
$login_tooltip_text     = $params->get( 'login_tooltip_text' );
$show_pass_tooltip      = $params->get( 'show_pass_tooltip' );
$pass_tooltip_text      = $params->get( 'pass_tooltip_text' );
$ml_visibility          = $params->get( 'ml_visibility' );
$dr_login_text          = $params->get( 'dr_login_text' );
$dr_logout_text         = $params->get( 'dr_logout_text' );
$ml_show_button         = $params->get( 'ml_show_button' );
$ml_captionsize         = $params->get( 'ml_captionsize' );
$ml_bgcolor             = $params->get( 'ml_bgcolor' );
$ml_fgcolor             = $params->get( 'ml_fgcolor' );
$ml_textcolor           = $params->get( 'ml_textcolor' );
$ml_capcolor            = $params->get( 'ml_capcolor' );  
$ml_captionfont         = $params->get( 'ml_captionfont' );
$ml_textfont            = $params->get( 'ml_textfont' );
$ml_border              = $params->get( 'ml_border' );
$ml_over_height         = $params->get ( 'ml_over_height' );
$ml_over_width          = $params->get ( 'ml_over_width' );
$ml_textsize            = $params->get ( 'ml_textsize' );

if($show_login_tooltip==1 OR $show_pass_tooltip) mosCommonHTML::loadOverlib();

if ( $my->id ) {
	if ( $name ) {
		$name = $my->name;
	} else {
		$name = $my->username;
	}
?>
	<form action="<?php echo sefRelToAbs( 'index.php?option=logout' ); ?>" method="post" name="logout">	
	<?php
	if ( $greeting ) {
		echo _HI;
		echo $name;
	}
	?>
	<br />
	<div align="center">
		<input type="submit" name="Submit" class="button<?php echo $moduleclass_sfx; ?>" value="<?php echo _BUTTON_LOGOUT; ?>" />
	</div>
	<input type="hidden" name="option" value="logout" />
	<input type="hidden" name="op2" value="logout" />
	<input type="hidden" name="lang" value="<?php echo $mosConfig_lang; ?>" />
	<input type="hidden" name="return" value="<?php echo sefRelToAbs( $logout ); ?>" />
	<input type="hidden" name="message" value="<?php echo $message_logout; ?>" />
	</form>
<?php
	} else {
		$validate = josSpoofValue(1);
?>
	<form action="<?php echo sefRelToAbs( 'index.php' ); ?>" method="post" name="login" >
	<?php
echo $pretext;
if ($params->get( 'orientation' ) == 0) {
	if ($params->get( 'ml_visibility' ) == 1) {
?>
	<div id="layer_button" style="display:block;">
	<input class="button<?php echo $moduleclass_sfx; ?>" type="button" name="login" value="<?php echo $dr_login_text; ?>" onclick="document.getElementById('layer_login').style.display='block';document.getElementById('layer_button').style.display='none';" />
	</div>
	<div id="layer_login" style="display:none;" class="login"> 
	<input class="button<?php echo $moduleclass_sfx; ?>" type="button" name="login2" value="<?php echo $dr_logout_text; ?>" onclick="document.getElementById('layer_login').style.display='none';document.getElementById('layer_button').style.display='block';" />
	<?php } ?>
	<table width="<?php echo $module_width; ?>" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="<?php echo $module_align; ?>">
		<?php if ($show_login_text == 1) { ?>
			<label for="mod_login_username">
				<?php echo $ml_login_text; ?>
			</label> 
			<br /><?php }; ?>
			<input 
			<?php if ($show_login_tooltip == 1) { ?>
			onmouseover="return overlib('<?php echo $login_tooltip_text; ?>', CAPTION, '<?php echo $ml_login_text ;?>', BELOW, RIGHT,
			CAPTIONSIZE, '<?php echo $ml_captionsize; ?>',
			CAPTIONFONT, '<?php echo $ml_captionfont; ?>',
			CAPCOLOR, '<?php echo $ml_capcolor; ?>',
			TEXTCOLOR, '<?php echo $ml_textcolor; ?>',
			TEXTSIZE, '<?php echo $ml_textsize; ?>',
			TEXTFONT, '<?php echo $ml_textfont; ?>',
			BORDER, <?php echo $ml_border; ?>,
			HEIGHT, <?php echo $ml_over_height; ?>,
			WIDTH, <?php echo $ml_over_width; ?>
			);" onmouseout="return nd();"
		<?php } ?>
	<?php if ($show_login_text == 2) { ?>
		value="<?php echo $ml_login_text; ?>"
		onblur="if(this.value=='') this.value='<?php echo $ml_login_text; ?>';"
		onfocus="if(this.value=='<?php echo $ml_login_text; ?>') this.value='';"
	<?php } ?>
		name="username" id="mod_login_username" type="text" class="inputbox" alt="username" size="<?php echo $field_width ; ?>" /><br />
			<?php if ($show_pass_text == 1) { ?>
			<label for="mod_login_password">
				<?php echo $ml_pass_text; ?>
			</label>
			<br /><?php } ;?>
			<input 
		<?php if ($show_pass_tooltip == 1) { ?>
		onmouseover="return overlib('<?php echo $pass_tooltip_text; ?>', CAPTION, '<?php echo $ml_pass_text ;?>', BELOW, RIGHT,
			CAPTIONSIZE, '<?php echo $ml_captionsize; ?>',
			CAPTIONFONT, '<?php echo $ml_captionfont; ?>',
			CAPCOLOR, '<?php echo $ml_capcolor; ?>',
			TEXTCOLOR, '<?php echo $ml_textcolor; ?>',
			TEXTSIZE, '<?php echo $ml_textsize; ?>',
			TEXTFONT, '<?php echo $ml_textfont; ?>',
			BORDER, <?php echo $ml_border; ?>
		);" onmouseout="return nd();"
		<?php } ?>
	<?php if ($show_pass_text == 2) { ?>
		value="<?php echo $ml_pass_text; ?>"
		onblur="if(this.value=='') this.value='<?php echo $ml_pass_text; ?>';" 
		onfocus="if(this.value=='<?php echo $ml_pass_text; ?>') this.value='';"
	<?php } ?>
	type="password" id="mod_login_password" name="passwd" class="inputbox" size="<?php echo $field_width ; ?>" alt="password" />
			<br />
			<?php if ($show_remember == 1) { ?>
				<label for="mod_login_remember">
					<?php echo $ml_rem_text; ?>
				</label>
				<input type="checkbox" name="remember" id="mod_login_remember" class="inputbox" value="yes" alt="Remember Me" />
				<br />
			<?php }; ?>
			<input type="submit" name="Submit" class="button<?php echo $moduleclass_sfx; ?><?php echo $ml_show_button; ?>" value="<?php if($ml_show_button != '-hidden'){ echo _BUTTON_LOGIN;} ?>" />
		</td>
	</tr>
	<?php if ($show_lost_pass == 1) { ?>
	<tr>
		<td align="<?php echo $module_align; ?>">
 			<a class="ml_login" href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=lostPassword' ); ?>">
				<?php echo $ml_rem_pass_text; ?></a>
		</td>
	</tr>
	<?php };
	if ($show_register == 1) {
		if ( $registration_enabled ) {
			?>
			<tr>
				<td align="<?php echo $module_align; ?>">
					<?php if ($show_not_registered == 1) {  echo $ml_not_reg_text;} ?>
					<a class="ml_login" href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=register' ); ?>">
						<?php echo $ml_reg_text; ?></a>
				</td>
			</tr>
			<?php
		}
	}
	?>
	</table>
<?php
	if ($params->get( 'ml_visibility' ) == 1) {
?>
	</div>
<?php
	}
}
	// Вертикальный вывод модуля
	else {
	if ($params->get( 'ml_visibility' ) == 1) { ?>
	<div id="layer_b" style="display:block;">
	<input class="button<?php echo $moduleclass_sfx; ?>" type="button" name="login" value="<?php echo $dr_login_text; ?>" onclick="document.getElementById('layer_l').style.display='block';document.getElementById('layer_b').style.display='none';" />
	</div>
	<div id="layer_l" style="display:none;" class="login"> 
	<?php } ?>
<table width="<?php echo $module_width; ?>" height="<?php echo $module_height; ?>" border="0" cellspacing="1" cellpadding="0">
<?php if (($show_login_text == 1)||($show_pass_text == 1)){?>
	<tr>
	<?php if ($params->get( 'ml_visibility' ) == 1) { ?>
		<td rowspan="2"><input class="button<?php echo $moduleclass_sfx; ?>" type="button" name="login2" value="<?php echo $dr_logout_text; ?>" onclick="document.getElementById('layer_l').style.display='none';document.getElementById('layer_b').style.display='block';" /></td>
	<?php } ?>

	<td align="left" valign="bottom"><?php if ($show_login_text == 1) { ?>
		<label for="mod_login_username">
			<?php echo $ml_login_text; ?>
		</label>
		<?php }; ?></td>
	<td align="left" valign="bottom"><?php if ($show_pass_text == 1) { ?>
		<label for="mod_login_password">
			<?php echo $ml_pass_text; ?>
		</label>
	<?php } ;?></td>
	<td></td>
	<td></td>
	<td></td>
	<td valign="bottom"><?php if ($show_not_registered == 1) {  echo $ml_not_reg_text;} ?></td>
	</tr> <?php } ?>
	<tr>
	<td align="left" valign="top">
<input 
<?php if ($show_login_tooltip == 1) { ?>
			onmouseover="return overlib('<?php echo $login_tooltip_text; ?>', CAPTION, '<?php echo $ml_login_text ;?>', BELOW, RIGHT,
			CAPTIONSIZE, '<?php echo $ml_captionsize; ?>',
			CAPTIONFONT, '<?php echo $ml_captionfont; ?>',
			CAPCOLOR, '<?php echo $ml_capcolor; ?>',
			TEXTCOLOR, '<?php echo $ml_textcolor; ?>',
			TEXTSIZE, '<?php echo $ml_textsize; ?>',
			TEXTFONT, '<?php echo $ml_textfont; ?>',
			BORDER, <?php echo $ml_border; ?>
			);" onmouseout="return nd();"
		<?php } ?>
	<?php if ($show_login_text == 2) { ?>
		value="<?php echo $ml_login_text; ?>"
		onblur="if(this.value=='') this.value='<?php echo $ml_login_text; ?>';"
		onfocus="if(this.value=='<?php echo $ml_login_text; ?>') this.value='';"
	<?php } ?>
		name="username" id="mod_login_username" type="text" class="inputbox" alt="username" size="<?php echo $field_width ; ?>" /></td>
		<td align="left" valign="top">
	<input
<?php if ($show_pass_tooltip == 1) { ?>
	onmouseover="return overlib('<?php echo $pass_tooltip_text; ?>', CAPTION, '<?php echo $ml_pass_text ;?>', BELOW, RIGHT,
		CAPTIONSIZE, '<?php echo $ml_captionsize; ?>',
		CAPTIONFONT, '<?php echo $ml_captionfont; ?>',
		CAPCOLOR, '<?php echo $ml_capcolor; ?>',
		TEXTCOLOR, '<?php echo $ml_textcolor; ?>',
		TEXTSIZE, '<?php echo $ml_textsize; ?>',
		TEXTFONT, '<?php echo $ml_textfont; ?>',
		BORDER, <?php echo $ml_border; ?>
	);" onmouseout="return nd();"
	<?php } ?>
	<?php if ($show_pass_text == 2) { ?>
		value="<?php echo $ml_pass_text; ?>"
		onblur="if(this.value=='') this.value='<?php echo $ml_pass_text; ?>';" 
		onfocus="if(this.value=='<?php echo $ml_pass_text; ?>') this.value='';"
	<?php } ?>
	type="password" id="mod_login_password" name="passwd" class="inputbox" size="<?php echo $field_width ; ?>" alt="password" />
	</td>
	<td align="center" valign="top"><?php if ($show_remember == 1) { ?>
		<input type="checkbox" name="remember" id="mod_login_remember" class="inputbox" value="yes" alt="Remember Me" />
		<label for="mod_login_remember"><?php echo $ml_rem_text; ?></label>
		<br /> <?php }; ?></td>
	<td align="center" valign="top"><input type="submit" name="Submit" class="button<?php echo $moduleclass_sfx; ?><?php echo $ml_show_button; ?>"
	value="<?php if($ml_show_button != '-hidden'){ echo _BUTTON_LOGIN;} ?>" />
	</td>
	<td align="left" valign="middle"><?php if ($show_lost_pass == 1) { ?>
		<a class="ml_login" href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=lostPassword' ); ?>"><?php echo $ml_rem_pass_text; ?></a>
	<?php }; ?></td>
	<td align="left" valign="middle"><?php if ($show_register == 1) { 
	if ( $registration_enabled ) {
		?>
			<a class="ml_login" href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=register' ); ?>">
			<?php echo $ml_reg_text; ?></a>
		<?php
	}}
	?></td>
	</tr>
</table>
	<?php if ($params->get( 'ml_visibility' ) == 1) { ?>
	</div>
<?php  }}
	echo $posttext;
?>
	<input type="hidden" name="option" value="login" />
	<input type="hidden" name="op2" value="login" />
	<input type="hidden" name="lang" value="<?php echo $mosConfig_lang; ?>" />
	<input type="hidden" name="return" value="<?php echo sefRelToAbs( $login ); ?>" />
	<input type="hidden" name="message" value="<?php echo $message_login; ?>" />
	<input type="hidden" name="force_session" value="1" />
	<input type="hidden" name="<?php echo $validate; ?>" value="1" />
	</form>
	<?php
}
?>

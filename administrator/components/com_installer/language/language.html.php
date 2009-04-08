<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
* @package Joostina
* @subpackage Templates
*/
class HTML_language {
	
	// прорисовка кнопок управления
	function quickiconButton($link,$image,$text) {
		?>
		<span>
		<a href="<?php echo $link; ?>" title="<?php echo $text; ?>">
		<?php
		echo mosAdminMenus::imageCheckAdmin($image,'/'.ADMINISTRATOR_DIRECTORY.'/images/',null,null,$text);
		echo $text;
		?>
		</a>
		</span>
		<?php
	}
	function cPanel() {?>

		<table>
		<tr>
		<td width="100%" valign="top">
		<div class="cpicons">
		<?php

		$link = 'index2.php?option=com_installer&amp;element=installer';
		HTML_language::quickiconButton($link,'down.png', _INSTALLATION);

		$link = 'index2.php?option=com_installer&amp;element=component';
		HTML_language::quickiconButton($link,'db.png', _COMPONENTS);

		$link = 'index2.php?option=com_installer&amp;element=module';
		HTML_language::quickiconButton($link,'db.png', _MODULES);

		$link = 'index2.php?option=com_installer&amp;element=mambot';
		HTML_language::quickiconButton($link,'ext.png', _MAMBOTS);

		$link = 'index2.php?option=com_installer&amp;element=template';
		HTML_language::quickiconButton($link,'joostina.png', _MENU_SITE_TEMPLATES);
		
		$link = 'index2.php?option=com_installer&amp;element=template&client=admin';
		HTML_language::quickiconButton($link,'joostina.png', _MENU_ADMIN_TEMPLATES);

		$link = 'index2.php?option=com_installer&amp;element=language';
		HTML_language::quickiconButton($link,'log.png', _SITE_LANGUAGES);

		?>
		</div>
		</td>
		</tr>
		</table>
		<?php
	}
	
	/**
	* @param array An array of data objects
	* @param object A page navigation object
	* @param string The option
	*/
	function showLanguages($cur_lang,&$rows,&$pageNav,$option) {
		global $my;
?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="langmanager">
			<?php echo _LANGUAGE_PACKS?> <small><small>[ <?php echo _SITE?> ]</small></small>
			</th>
		</tr>
		<tr> 
			<?php HTML_language::cPanel(); ?>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="30">
			&nbsp;
			</th>
			<th width="25%" class="title">
			<?php echo _E_LANGUAGE?>
			</th>
			<th width="5%">
			<?php echo _USED_ON?>
			</th>
			<th width="10%">
			<?php echo _E_VERSION?>
			</th>
			<th width="10%">
			<?php echo _DATE?>
			</th>
			<th width="20%">
			<?php echo _AUTHOR_BY?>
			</th>
			<th width="25%">
			E-mail
			</th>
		</tr>
<?php
		$k = 0;
		for($i = 0,$n = count($rows); $i < $n; $i++) {
			$row = &$rows[$i];
?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20"><?php echo $pageNav->rowNumber($i); ?></td>
				<td width="20">
				<input type="radio" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $row->language; ?>" onClick="isChecked(this.checked);" />
				</td>
				<td width="25%">
				<a onclick="hideMainMenu();return listItemTask('cb<?php echo $i; ?>','edit_source')"><?php echo $row->name; ?></a></td>
				<td width="5%" align="center">
				<?php
			if($row->published == 1) { ?>
					<img src="images/tick.png" alt="<?php echo _CMN_PUBLISHED?>"/>
					<?php
			} else {
?>
					&nbsp;
				<?php
			}
?>
				</td>
				<td align=center>
				<?php echo $row->version; ?>
				</td>
				<td align=center>
				<?php echo $row->creationdate; ?>
				</td>
				<td align=center>
				<?php echo $row->author; ?>
				</td>
				<td align=center>
				<?php echo $row->authorEmail; ?>
				</td>
			</tr>
		<?php
		}
?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_installer" />
		<input type="hidden" name="element" value="language" />
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}
}
?>
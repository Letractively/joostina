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

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );

/**
* Writes the edit form for new and existing content item
*
* A new record is defined when <var>$row</var> is passed with the <var>id</var>
* property set to 0.
* @package Joostina
* @subpackage Menus
*/
class weblink_category_table_menu_html {

	function editCategory( &$menu, &$lists, &$params, $option ) {
		global $mosConfig_live_site;
		?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			if ( pressbutton == 'cancel' ) {
				submitform( pressbutton );
				return;
			}
			var form = document.adminForm;
			<?php
			if ( !$menu->id ) {
				?>
				if ( getSelectedValue( 'adminForm', 'componentid' ) < 1 ) {
					alert( 'Вы должны выбрать категорию' );
					return;
				}
				cat = getSelectedText( 'adminForm', 'componentid' );

				form.link.value = "index.php?option=com_weblinks&catid=" + form.componentid.value;
				if ( form.name.value == '' ) {
					form.name.value = cat;
				}
				submitform( pressbutton );
				<?php
			} else {
				?>
				if ( form.name.value == '' ) {
					alert( 'Этот пункт меню должен иметь название' );
				} else {
					submitform( pressbutton );
				}
				<?php
			}
			?>
		}
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="menus">
			<?php echo $menu->id ? 'Изменение -' : 'Добавление -';?> Пункт меню :: Таблица - Web-ссылки категории
			</th>
		</tr>
		</table>

		<table width="100%">
		<tr valign="top">
			<td width="60%">
				<table class="adminform">
				<tr>
					<th colspan="3">
					Детали
					</th>
				</tr>
				<tr>
					<td width="10%" align="right" valign="top">
					Название:
					</td>
					<td width="200px">
					<input type="text" name="name" size="30" maxlength="100" class="inputbox" value="<?php echo htmlspecialchars( $menu->name, ENT_QUOTES ); ?>"/>
					</td>
					<td>
					<?php
					if ( !$menu->id ) {
						echo mosToolTip( 'Если поле будет оставлено пустым, то автоматически будет использовано название категории' );
					}
					?>
					</td>
				</tr>
				<tr>
					<td width="10%" align="right" valign="top">
					title ссылки:
					</td>
					<td width="80%">
						<input class="inputbox" type="text" name="params[title]" size="50" maxlength="100" value="<?php echo htmlspecialchars( $params->get('title',''), ENT_QUOTES ); ?>" />
					</td>
				</tr>
				<tr>
					<td width="10%" align="right" valign="top">
					Категория:
					</td>
					<td colspan="2">
					<?php echo $lists['componentid']; ?>
					</td>
				</tr>
				<tr>
					<td align="right">
					URL:
					</td>
					<td colspan="2">
                    <?php echo ampReplace($lists['link']); ?>
					</td>
				</tr>
				<tr>
					<td align="right">
					Родительский пункт меню:
					</td>
					<td colspan="2">
					<?php echo $lists['parent'];?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					Порядок расположения:
					</td>
					<td colspan="2">
					<?php echo $lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					Уровень доступа:
					</td>
					<td colspan="2">
					<?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					Опубликовано:
					</td>
					<td colspan="2">
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="40%">
				<table class="adminform">
				<tr>
					<th>
					Параметры
					</th>
				</tr>
				<tr>
					<td>
					<?php echo $params->render();?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="id" value="<?php echo $menu->id; ?>" />
		<input type="hidden" name="menutype" value="<?php echo $menu->menutype; ?>" />
		<input type="hidden" name="type" value="<?php echo $menu->type; ?>" />
		<input type="hidden" name="link" value="<?php echo $menu->link; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<script language="Javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
		<?php
	}
}
?>

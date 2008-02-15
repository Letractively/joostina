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
* @package Joostina
* @subpackage Content
*/
class HTML_content {
	/**
	* Writes a list of the content items
	* @param array An array of content objects
	*/
	function showList( &$rows, $search, $pageNav, $option, $lists ) {
		global $my, $acl, $database;

		mosCommonHTML::loadOverlib();
		$nullDate = $database->getNullDate();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="frontpage" rowspan="2">
			Главная страница
			</th>
			<td width="right">
			<?php echo $lists['sectionid'];?>
			</td>
			<td width="right">
			<?php echo $lists['catid'];?>
			</td>
			<td width="right">
			<?php echo $lists['authorid'];?>
			</td>
		</tr>
		<tr>
			<td align="right" colspan="2">
			Фильтр:
			</td>
			<td>
			<input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="text_area" onChange="document.adminForm.submit();" />
			</td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="5">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th class="title">
			Заголовок
			</th>
			<th width="10%" class="jtd_nowrap">
			Опубликовано
			</th>
			<th colspan="2" class="jtd_nowrap" width="5%">
			Сортировка
			</th>
			<th width="2%">
			Порядок
			</th>
			<th width="1%">
			<a href="javascript: saveorder( <?php echo count( $rows )-1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="Сохранить порядок" /></a>
			</th>
			<th width="8%" class="jtd_nowrap">
			Доступ
			</th>
			<th width="10%" align="left">
			Раздел
			</th>
			<th width="10%" align="left">
			Категория
			</th>
			<th width="10%" align="left">
			Автор
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			mosMakeHtmlSafe($row);
			$link = 'index2.php?option=com_content&sectionid=0&task=edit&hidemainmenu=1&id='. $row->id;
			$row->sect_link = 'index2.php?option=com_sections&task=editA&hidemainmenu=1&id='. $row->sectionid;
			$row->cat_link 	= 'index2.php?option=com_categories&task=editA&hidemainmenu=1&id='. $row->catid;

			$now = _CURRENT_SERVER_TIME;
			if ( $now <= $row->publish_up && $row->state == '1' ) {
				$img = 'publish_y.png';
				$alt = 'Опубликовано';
			} else if (($now <= $row->publish_down || $row->publish_down == $nullDate) && $row->state == '1') {
				$img = 'publish_g.png';
				$alt = 'Опубликовано';
			} else if ( $now > $row->publish_down && $row->state == '1' ) {
				$img = 'publish_r.png';
				$alt = 'Время публикации истекло';
			} elseif ( $row->state == "0" ) {
				$img = "publish_x.png";
				$alt = 'Не опубликовано';
			}

			$times = '';
			if ( isset( $row->publish_up ) ) {
				  if ( $row->publish_up == $nullDate) {
						$times .= '<tr><td>Начало: Всегда</td></tr>';
				  } else {
						$times .= '<tr><td>Начало: '. $row->publish_up .'</td></tr>';
				  }
			}
			if ( isset( $row->publish_down ) ) {
				  if ($row->publish_down == $nullDate) {
						$times .= '<tr><td>Окончание: Без срока</td></tr>';
				  } else {
				  $times .= '<tr><td>Окончание: '. $row->publish_down .'</td></tr>';
				  }
			}

			$access 	= mosCommonHTML::AccessProcessing( $row, $i );
			$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );

			if ( $acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' ) ) {
				if ( $row->created_by_alias ) {
					$author = $row->created_by_alias;
				} else {
					$linkA 	= 'index2.php?option=com_users&task=editA&hidemainmenu=1&id='. $row->created_by;
					$author = '<a href="'. $linkA .'" title="Изменить данные пользователя">'. $row->author .'</a>';
				}
			} else {
				if ( $row->created_by_alias ) {
					$author = $row->created_by_alias;
				} else {
					$author = $row->author;
				}
			}
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->title;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="Изменить содержимое">
					<?php echo $row->title; ?>
					</a>
					<?php
				}
				?>
				</td>
				<?php
				if ( $times ) {
					?>
					<td align="center">
					<a href="javascript: void(0);" onmouseover="return overlib('<table><?php echo $times; ?></table>', CAPTION, 'Информация о публикации', BELOW, RIGHT);" onMouseOut="return nd();" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->state ? "unpublish" : "publish";?>')">
					<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt;?>" />
					</a>
					</td>
					<?php
				}
				?>
				<td>
				<?php echo $pageNav->orderUpIcon( $i ); ?>
				</td>
				<td>
				<?php echo $pageNav->orderDownIcon( $i, $n ); ?>
				</td>
				<td align="center" colspan="2">
				<input type="text" name="order[]" size="5" value="<?php echo $row->fpordering;?>" class="text_area" style="text-align: center" />
				</td>
				<td align="center">
				<?php echo $access;?>
				</td>
				<td>
				<a href="<?php echo $row->sect_link; ?>" title="Изменить раздел">
				<?php echo $row->sect_name; ?>
				</a>
				</td>
				<td>
				<a href="<?php echo $row->cat_link; ?>" title="Изменить категорию">
				<?php echo $row->name; ?>
				</a>
				</td>
				<td>
				<?php echo $author; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>

		<?php
		echo $pageNav->getListFooter();
		mosCommonHTML::ContentLegend();
		?>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}
}
?>

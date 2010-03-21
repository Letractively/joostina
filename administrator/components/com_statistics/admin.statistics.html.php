<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
 * @package Joostina
 * @subpackage Statistics
 */
class HTML_statistics {
	function pageImpressions(&$rows,$pageNav,$option,$task) {
		?>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
	<tr>
		<th width="100%" class="impressions"><?php echo _PAGES_HITS?></th>
	</tr>
</table>

<form action="index2.php" method="post" name="adminForm">
	<table class="adminlist">
		<tr>
			<th style="text-align:right">#</th>
			<th class="title"><?php echo _CONTENT_TITLE?></th>
			<th align="center" class="jtd_nowrap"><?php echo _HITS?></th>
		</tr>
				<?php
				$i = $pageNav->limitstart;
				$k = 0;
				foreach($rows as $row) {
					?>
		<tr class="row<?php echo $k; ?>">
			<td align="right">
							<?php echo ++$i; ?>
			</td>
			<td align="left">
				&nbsp;<?php echo $row->title." (".$row->created.")"; ?>&nbsp;
			</td>
			<td align="center"><?php echo $row->hits; ?></td>
		</tr>
					<?php
					$k = 1 - $k;
				}
				?>
	</table>
			<?php echo $pageNav->getListFooter(); ?>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="<?php echo $task; ?>" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}

	function showSearches(&$rows,$pageNav,$option,$task,$showResults) {
		global $mainframe;
		mosCommonHTML::loadOverlib();
		?>
<form action="index2.php" method="post" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
		<tr>
			<th class="searchtext">
						<?php echo _SEARCH_QUERIES?>:
				<span class="componentheading"><?php echo _LOG_SEARCH_QUERIES?>:
							<?php echo $mainframe->getCfg('enable_log_searches')?'<b><font color="green">'._ALLOWED.'</font></b>':'<b><font color="red">'._DISALLOWED.'</font></b>' ?>
				</span>
			</th>
			<td align="right">
						<?php
						if(!$showResults) {
							echo mosWarning(_LOG_LOW_PERFOMANCE);
						}
						?>
			</td>
			<td align="right">
						<?php
						if($showResults) {
							?>
				<input name="search_results" type="button" class="button" value="<?php echo _HIDE_SEARCH_RESULTS?>" onclick="submitbutton('searches');">
							<?php
						} else {
							?>
				<input name="search_results" type="button" class="button" value="<?php echo _SHOW_SEARCH_RESULTS?>" onclick="submitbutton('searchesresults');">
							<?php
						}
						?>
			</td>
		</tr>
	</table>

	<table class="adminlist">
		<tr>
			<th style="text-align:right" width="10">#</th>
			<th class="title"><?php echo _SEARCH_QUERY_TEXT?></th>
			<th class="jtd_nowrap"><?php echo _SEARCH_QUERY_COUNT?></th>
					<?php
					if($showResults) {
						?>
			<th class="jtd_nowrap"><?php echo _SHOW_RESULTS?></th>
						<?php
					}
					?>
		</tr>
				<?php
				$k = 0;
				$_n = count($rows);
				for($i = 0,$n = $_n; $i < $n; $i++) {
					$row = &$rows[$i];
					?>
		<tr class="row<?php echo $k; ?>">
			<td align="right"><?php echo $i + 1 + $pageNav->limitstart; ?></td>
			<td align="left"><?php echo $row->search_term; ?></td>
			<td align="center"><?php echo $row->hits; ?></td>
						<?php
						if($showResults) {
							?>
			<td align="center"><?php echo $row->returns; ?></td>
							<?php
						}
						?>
		</tr>
					<?php
					$k = 1 - $k;
				}
				?>
	</table>
			<?php echo $pageNav->getListFooter(); ?>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="<?php echo $task; ?>" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}
}
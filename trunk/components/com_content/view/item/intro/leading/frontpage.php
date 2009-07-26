<?php /**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die(); ?>
<?php if($params->get('item_title')) { ?>
	<div <?php echo $news_uid_css_title; ?> class="contentheading"><?php echo $row->title; ?></div>
<?php } ?>
<?php
	$loadbot_onAfterDisplayTitle;
	$loadbot_onBeforeDisplayContent;
?>
<?php if($params->get('createdate', 0)) { ?>
	<span class="date"><?php echo $create_date; ?></span>
<?php } ?>
<?php if($params->get('author', 0)) { ?>
	<span class="author"><?php echo $author; ?></span>
<?php } ?>
<?php if($params->get('section') || $params->get('category')) { ?>
	<div class="section_cat">
<?php if($params->get('section')) { ?>
		<span class="section_name"><?php echo $row->section; ?></span>
<?php } ?>
<?php if($params->get('category')) { ?>
		<span class="cat_name">&rarr; <?php echo $row->category; ?></span>
<?php } ?>
	</div>
<?php } ?>
	<div class="buttons_wrap">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="60" align="right">
					<div class="icons_c">
<?php if($params->get('print')) { ?>
					<?php mosHTML::PrintIcon($row, $params, $hide_js, $print_link); ?>
<?php } ?>
<?php if($params->get('email')) { ?>
					<?php HTML_content::EmailIcon($row, $params, $hide_js); ?>
<?php } ?>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div <?php echo $news_uid_css_body; ?>class="item_body">
<?php if($params->get('url') && $row->urls) { ?>
	<div class="blog_urls">
		<a href="http://<?php echo $row->urls; ?>" target="_blank"><?php echo $row->urls; ?></a>
	</div>
<?php } ?>
<?php if(isset($row->toc)) { ?>
	<div class="toc"><?php echo $row->toc; ?></div>
<?php } ?>
<?php if($params->get('view_introtext', 1)) { ?>
	<div class="item_text"><?php echo ampReplace($row->text); ?></div>
<?php } ?>
<?php if($params->get('view_tags')) { ?>
<?php if(isset($row->tags)) { ?>
		<span class="tags"><?php echo _TAGS ?> <?php echo $row->tags; ?></span>
<?php } else { ?>
		<span class="tags"><?php echo _TAGS_NOT_DEFINED ?></span>
<?php } ?>
<?php } ?>
<?php echo $row->rating; ?>
<?php if($params->get('modifydate')) { ?>
		<div class="modified_date">
			<strong><?php echo _LAST_UPDATED; ?> </strong> <?php echo $mod_date; ?>
		</div>
<?php } ?>
<?php if($params->get('readmore')) { ?>
		<div class="readmore"><?php echo $readmore; ?></div>
<?php } ?>
	</div>
<?php echo $loadbot_onAfterDisplayContent; ?>
	<div class="edit_item"><?php echo $edit; ?></div>
	<div class="article_seperator">&nbsp;</div>
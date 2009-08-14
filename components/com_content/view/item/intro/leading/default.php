<?php /**
 * @package Joostina
 * @copyright Àâòîğñêèå ïğàâà (C) 2008-2009 Joostina team. Âñå ïğàâà çàùèùåíû.
 * @license Ëèöåíçèÿ http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, èëè help/license.php
 * Joostina! - ñâîáîäíîå ïğîãğàììíîå îáåñïå÷åíèå ğàñïğîñòğàíÿåìîå ïî óñëîâèÿì ëèöåíçèè GNU/GPL
 * Äëÿ ïîëó÷åíèÿ èíôîğìàöèè î èñïîëüçóåìûõ ğàñøèğåíèÿõ è çàìå÷àíèé îá àâòîğñêîì ïğàâå, ñìîòğèòå ôàéë help/copyright.php.
 */

// çàïğåò ïğÿìîãî äîñòóïà
defined('_VALID_MOS') or die(); ?>

<?php if($params->get('item_title')) : ?>
	<div <?php echo $news_uid_css_title; ?> class="contentheading"><?php echo $row->title; ?></div>
<?php endif; ?>

<?php $loadbot_onAfterDisplayTitle; $loadbot_onBeforeDisplayContent; ?>

<?php if($params->get('createdate', 0)) : ?>
	<span class="date"><?php echo $create_date; ?></span>
<?php endif; ?>

<?php if($params->get('author', 0)) : ?>
	<span class="author"><?php echo $author; ?></span>
<?php endif; ?>

<?php if($params->get('section') || $params->get('category')) : ?>
	<div class="section_cat">
	
		<?php if($params->get('section')) : ?>
		<span class="section_name"><?php echo $row->section; ?></span>
		<?php endif; ?>
		
		<?php if($params->get('category')) : ?>
		<span class="cat_name">&rarr; <?php echo $row->category; ?></span>
		<?php endif; ?>
		
	</div>
<?php endif; ?>


<?php if($params->get('print') || $params->get('email')) : ?>
	<div class="buttons_wrap">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="60" align="right">
					<div class="icons_c">
					<?php if($params->get('print')) : ?>
						<?php mosHTML::PrintIcon($row, $params, $hide_js, $print_link); ?>
					<?php endif; ?>

					<?php if($params->get('email')) : ?>
						<?php HTML_content::EmailIcon($row, $params, $hide_js); ?>
					<?php endif; ?>
					</div>
				</td>
			</tr>
		</table>
	</div>
<?php endif; ?>	

	<div <?php echo $news_uid_css_body; ?>class="item_body">
		
		<?php if($params->get('url') && $row->urls) : ?>
		<div class="blog_urls">
			<a href="http://<?php echo $row->urls; ?>" target="_blank"><?php echo $row->urls; ?></a>
		</div>
		<?php endif; ?>
		
		<?php if(isset($row->toc)) : ?>
		<div class="toc"><?php echo $row->toc; ?></div>
		<?php endif; ?>
		
		<?php if($params->get('view_introtext', 1)) : ?>
		<div class="item_text"><?php echo ampReplace($row->text); ?></div>
		<?php endif; ?>
		
		
		<?php if($params->get('view_tags')) : ?>
			<?php if(isset($row->tags)) : ?>
				<span class="tags"><?php echo _TAGS ?> <?php echo $row->tags; ?></span>
			<?php else: ?>
				<span class="tags"><?php echo _TAGS_NOT_DEFINED ?></span>
			<?php endif; ?>
		<?php endif; ?>
		
				
		<?php if($params->get('rating')) : ?>		
		<div class="item_rating"><?php echo $row->rating; ?></div>
		<?php endif; ?>
		
		<?php if($params->get('modifydate')) : ?>
		<div class="modified_date">
			<strong><?php echo _LAST_UPDATED; ?> </strong> <?php echo $mod_date; ?>
		</div>
		<?php endif; ?>
		
		
		<?php if($params->get('readmore')) : ?>
		<span class="readmore"><?php echo $readmore; ?></span>
		<?php endif; ?>
		
	</div>
	
	<?php echo $loadbot_onAfterDisplayContent; ?>
	
	<?php if($access->canEdit) : ?>	
	<div class="edit_item"><?php echo $edit; ?></div>
	<?php endif; ?>

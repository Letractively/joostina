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

global $my;

?>
<div class="comment_list comments_list">
	<ul class="comments_list_ul comments_list_ul">
		<?php foreach($comments_list as $item) : // комментарии
			$linkuser = sefRelToAbs('index.php?option=com_user&id='.sprintf('%s:%s',$item->user_id, $item->name));
			?>
		<li>
			<a class="username user" id="<?php echo $item->user_id;?>" href="<?php echo $linkuser; ?>"><?php echo $item->name; ?></a>
			<span class="date"><?php echo $item->created_at; ?></span>
			<p>
					<?php echo $item->comment_text;?>
			</p>
				<?php if($my->gid==4): ?>
			<a class="del comments_del" href="#<?php echo $item->id; ?>">Удалить</a>
				<?php endif; ?>
		</li>

		<?php endforeach; // комментарии ?>
	</ul>
</div>
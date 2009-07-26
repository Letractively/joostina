<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

if(!$menu || $menu->published <= 0){
	echo _PAGE_ACCESS_DENIED;
	return;
}

//пагинация
if($users->total>0){
	mosMainFrame::addLib('pageNavigation');
	$link = sefRelToAbs($menu->link.'&amp;Itemid='.$menu->id);
	$paginate = new mosPageNav( $users->total, $limitstart, $limit );
}
?><div class="userlist">
<?php if( $params->get('header', '')) : ?>
	<div class="componentheading"><h1><?php echo $params->get('header', ''); ?></h1></div>
<?php endif;?>
	<ul>
<?php foreach($users->user_list as $user){
		$avatar_pic = '<img class="avatar" src="'.$mainframe->getCfg('live_site').DS.$users->get_avatar($user).'" />';
		$profile_link = $users->get_link($user); ?>
		<li>
			<a class="thumb" href="<?php echo $profile_link;?>"><?php echo $avatar_pic;?></a>
			<a href="<?php echo $profile_link;?>"><?php echo $user->name;?></a>
			<p><?php echo $user->about;?></p>
		</li>
<?php };?>
	</ul>
</div>
<?php if($users->total>0){
	echo '<br clear="all" /> '. $paginate->writePagesLinks($link);
}?>
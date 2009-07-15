<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

	if(!$menu || $menu->published <= 0){echo 'Извините, к этой странице доступ закрыт'; return;}

	//пагинация
	if($users->total>0){             	
		mosMainFrame::getInstance()->addLib('pageNavigation');
     	$link = sefRelToAbs($menu->link.'&amp;Itemid='.$menu->id);
		$paginate = new mosPageNav( $users->total, $limitstart, $limit );
     }

?>

	<div class="userlist">
	
		<?php if( $params->get('header', '')) : ?>
			<div class="componentheading"><h1><?php echo $params->get('header', ''); ?></h1></div>
		<?php endif;?>
		
		<ul>
			<?php foreach($users->user_list as $user):
						$avatar_pic = '<img class="avatar" src="'.$mainframe->getCfg('live_site').'/'.$users->get_avatar($user).'" />';
						$profile_link = $users->get_link($user); ?>
			<li>
				<a class="thumb" href="<?php echo $profile_link;?>"><?php echo $avatar_pic;?></a>
				<a href="<?php echo $profile_link;?>"><?php echo $user->name;?></a>
				<p><?php echo $user->about;?></p>
			</li>
			<?php endforeach;?>
		</ul>
		
	</div>

    <?php if($users->total>0){
        echo '<br clear="all" /> '. $paginate->writePagesLinks($link);
    }?>
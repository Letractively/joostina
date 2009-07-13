<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

if(!$menu || $menu->published == 0){echo 'Извините, к этой странице доступ закрыт'; return;}


foreach($users->user_list as $user){
	echo $user->name;	
}

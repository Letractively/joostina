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

$_MAMBOTS->registerFunction('userProfile','botUserInfo');

/**
*/
function botUserInfo(&$user) {
	global $database,$_MAMBOTS;

	$params = new mosParameters($_MAMBOTS->_mambot_params['user_info']);
	
	?>                        
	<div id="userInfo_area">
                 
		<?php if($params->get('show_header') && $params->get('header') ){
			?>
				<h6><?php echo $params->get('header') ?></h6>
			<?php
		} ?>
		
		<?php if($params->get('gender')){?>
        	<?php if(isset($user->user_extra->gender)) {?>
            	<strong>Пол:</strong> <?php echo $user->get_gender($user, $params);?>
        	<?php } else {?>
            	<strong>Пол:</strong> не указан
        	<?php }?>
		<?php } ?>
		
		<?php if($params->get('show_location')){?>
        	<?php if(isset($user->user_extra->location)) {?>
            	<strong>Откуда:</strong> <?php echo $user->user_extra->location;?>
        	<?php } else {?>
            	<strong>Откуда:</strong> не указано
        	<?php }?>
		<?php } ?>
		
		<?php if($params->get('show_about')){?>
	        <?php if(isset($user->user_extra->about)) {?>
	            <p><?php echo $user->user_extra->about;?></p>
	        <?php } else {?>
	            <p>Пользователь еще не рассказал о себе</p>
	        <?php }?>
  		<?php } ?>

	</div>
	
	<?php

	
}

?>

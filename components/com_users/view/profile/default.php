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


?>
 <script type="text/javascript">
$(document).ready(function() {
	$(".jstProfile_menu > ul> li > a#user_<?php echo $cur_plugin;?>_link").addClass("active");
});
</script>
	<div class="componentheading"><h1 class="profile"><?php echo $config->get('title');?></h1></div><br />

					<div class="jstProfile">

						<div class="jstProfile_info">
							<?php echo $avatar_pic;?>
							<h3><?php echo $user_real_name; ?>  <span class="blue">(<?php echo $user_nickname; ?>)</span></h3>
							<?php echo $user_status;?>
							<span class="last_visite"><strong>Последний визит:</strong> сегодня в 16:21  </span>

						</div>

							<?php if($owner){?>
								<span class="edit"><a class="edit" title="Редактировать" href="<?php echo $edit_info_link;?>">
									Редактировать профиль
								</a></span>
							<?php }  ?>

					<div class="jstProfile_menu">
						<ul class="menu_userInfo">
							<li>
								<a href="<?php echo sefRelToAbs("index.php?option=com_users&task=profile&user=$user_id");?>" id="user_user_info_link">информация</a>
							</li>
							<li>
								<a href="<?php echo sefRelToAbs("index.php?option=com_users&task=profile&view=user_content&user=$user_id");?>" id="user_user_content_link">Публикации</a>
							</li>
	   						<li>
								<a href="<?php echo sefRelToAbs("index.php?option=com_users&task=profile&view=user_contacts&user=$user_id");?>" id="user_user_contacts_link">Контакты</a>
							</li>
						</ul>
					</div>


					<div class="plugins_area">
						<?php
						//Вывод плагина
						$_MAMBOTS->call_mambot('userProfile', $plugin_page, $user);
						?>
					</div>

					
					<?php 

					?>



				</div>

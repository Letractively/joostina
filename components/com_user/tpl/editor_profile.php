<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/*
------Переменные для использования в шаблоне:-----
$avatar_pic - изображение аватара
$avatar_edit - область управления аватаром
*/
?>

    <div class="componentheading user_p"><h1 class="profile">Профиль пользователя</h1></div><br />

    <table id="user_profile" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
    <td>
	<table  cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td id="user_avatar">
               <?php echo $avatar_pic;?>
            </td>
			<td id="user_info">

                    <span class="user_name">
                        <?php echo $user_real_name; ?> (<?php echo $user_nickname; ?>)
                    </span>

                     <?php if($owner){?>
                     <a title="Редактировать" href="<?php echo $edit_info_link;?>">
                        <img src="../images/key.png" />
                     </a>
                     <?php }?>


                     <?php echo $user_status;?>
                      <br />

                    <div class="date">
                        <strong>Дата регистрации:</strong> <?php echo $registerDate;?>
                    </div>

                    <div class="date">
                        <strong>Последний визит: </strong><?php echo $lastvisitDate;?>
                    </div>

                <div class="user_info">
                    <?php echo $user_info;?>
                </div>


              <br />
             <!--<a href="<?php echo $user_content_href;?>" class="user_content_link">Публикации пользователя</a> <br />-->
             <?php  //mosLoadModules('profile', -2);?>
             </td>
            </tr>
        </table>

       </td>
      </tr>
      </table><br /><br />

      <?php include ($_SERVER['DOCUMENT_ROOT'].'/components/com_user/user_modules/user_content.php');?> <br /><br />


      <?php include ($_SERVER['DOCUMENT_ROOT'].'/components/com_user/user_modules/user_jcomments.php');?>



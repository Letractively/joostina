<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

?>

    <div class="componentheading"><h1 class="profile">Профиль пользователя</h1></div><br />

    <div id="user_profile">

	<table  cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td id="user_avatar">
               <?php echo $avatar_pic;?>
            </td>
			<td id="user_info">

                    <span class="user_name">
                        <?php echo $user_real_name; ?> <strong>(<?php echo $user_nickname; ?>) </strong>
                    </span>

                     <?php echo $user_status;?>
                      <br />

                       <?php include ($_SERVER['DOCUMENT_ROOT'].'/components/com_user/plugins/user_contacts/user_contacts.php');?>

<!--                    <div class="date">
                        <strong>Дата регистрации:</strong> <?php echo $registerDate;?>
                    </div>

                    <div class="date">
                        <strong>Последний визит: </strong><?php echo $lastvisitDate;?>
                    </div>-->

<?php if($user_info) {?>
                <div class="user_info">
                    <?php echo $user_info;?>
                </div>
<?php }?>

                             <?php if($owner || $my->usertype=='Super Administrator'){?>
                             <b>WMR: </b><?php if($user_wmr) { echo $user_wmr; } else {echo 'не указан ';}?>
                     <?php }?>

              <br />
             <!--<a href="<?php echo $user_content_href;?>" class="user_content_link">Публикации пользователя</a> <br />-->
             <?php  //mosLoadModules('profile', -2);?>
             </td>
             <td class="u_rating">
                <div class="u_rating">
                    <?php include ($_SERVER['DOCUMENT_ROOT'].'/components/com_user/plugins/user_rating.php');?>
                </div>
             </td>

            </tr>
        </table>

                             <?php if($owner){?>
                     <a style="float: right;" class="green" title="Редактировать" href="<?php echo $edit_info_link;?>">
                        Редактировать
                     </a>
                     <?php }?>

      </div><br />

      <?php include ($_SERVER['DOCUMENT_ROOT'].'/components/com_user/plugins/user_content/user_content.php');?> <br /><br />


      <?php include ($_SERVER['DOCUMENT_ROOT'].'/components/com_user/plugins/user_jcomments/user_jcomments.php');?>



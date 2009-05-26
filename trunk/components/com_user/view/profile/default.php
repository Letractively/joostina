<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

?>
 <script type="text/javascript">
$(document).ready(function() {
   $(".jstProfile_menu > ul> li > a#user_<?php echo $view;?>_link").addClass("active");
});
</script>
    <div class="componentheading"><h1 class="profile">Профиль пользователя</h1></div><br />

                    <div class="jstProfile">

                        <div class="jstProfile_info">
                            <?php echo $avatar_pic;?>
                            <h3><?php echo $user_real_name; ?>  <span class="blue">(<?php echo $user_nickname; ?>)</span></h3>
                            <?php echo $user_status;?>
                            <span class="last_visite"><strong>Последний визит:</strong> сегодня в 16:21  </span>

                            <br />

                            <?php include ($mosConfig_absolute_path.'/components/com_user/plugins/user_contacts/user_contacts.php');?>
                        </div>

                            <?php if($owner){?>
                                <span class="edit"><a class="edit" title="Редактировать" href="<?php echo $edit_info_link;?>">
                                    Редактировать данные
                                </a></span>
                            <?php }  ?>

                    <div class="jstProfile_menu">
                        <ul class="menu_userInfo">
                            <li><a href="<?php echo sefRelToAbs("index.php?option=com_user&task=profile&user=$user_id");?>" id="user_info_link">информация</a></li>
                            <li><a href="<?php echo sefRelToAbs("index.php?option=com_user&task=profile&view=content&user=$user_id");?>" id="user_content_link">публикации</a></li>
                        </ul>
                    </div>


                    <div class="plugins_area">
                        <?php include ($plugin_page);?>
                    </div>

                </div>

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
                    <div class="w_60 float_left">
                        <div class="jstProfile_info bg_green_b_r">
                            <?php echo $avatar_pic;?>
                            <h3><?php echo $user_real_name; ?>  <span class="blue">(<?php echo $user_nickname; ?>)</span></h3>
                            <span class="online">  <?php echo $user_status;?> </span>
                            <span class="last_visite"><strong>Последний визит:</strong> сегодня в 16:21  </span>

                            <br /><br />

                            <?php include ($mosConfig_absolute_path.'/components/com_user/plugins/user_contacts/user_contacts.php');?>


                            <?php if($owner){?>
                                <a class="add" title="Редактировать" href="<?php echo $edit_info_link;?>">
                                    Редактировать
                                </a>
                            <?php }  ?>

                        </div>
                    </div>

                    <div class="w_35 float_right">
                        <a href="#"><img src="images/gerbs/devTeam_gerb.png" /></a>
                    </div>
                    <br class="clear" />   <br class="clear" />
                    <div class="jstProfile_menu">

                        <ul class="menu_userInfo">
                            <li><a href="<?php echo sefRelToAbs("index.php?option=com_user&task=profile&user=$user_id");?>" id="user_info_link">информация</a></li>
                            <li><a href="<?php echo sefRelToAbs("index.php?option=com_user&task=profile&view=content&user=$user_id");?>" id="user_content_link">публикации</a></li>
                        </ul>

                    </div>


                    <div class="w_60 float_left">
                        <?php include ($plugin_page);?>
                    </div>

                </div>

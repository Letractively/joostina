<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

?>
                        <div id="userInfo_area">
                            <h6>О себе</h6>

                            <?php if($user->user_extra->about) {?>
                                <p><?php echo $user->user_extra->about;?></p>
                            <?php } else {?>
                                <p>Пользователь еще не рассказал о себе</p>
                            <?php }?>
                            <br />
                        </div>









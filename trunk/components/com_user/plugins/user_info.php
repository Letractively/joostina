<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

?>
                        <div id="userInfo_area">
                            <h6>О себе</h6>

                            <?php if($user_info) {?>
                                <p><?php echo $user_info;?></p>
                            <?php } else {?>
                                <p>Пользователь еще не рассказал о себе</p>
                            <?php }?>
                            <br />

                            <h6>Интересы</h6>
                            <?php if($user_interes) {?>
                                <p><?php echo $user_interes;?></p>
                            <?php } else {?>
                                <p>Ничего не известно об интересах пользователя</p>
                            <?php }?>


                        </div>









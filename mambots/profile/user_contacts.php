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

$_MAMBOTS->registerFunction('userProfile','botUserContacts');

/**
*/
function botUserContacts($user) {
	global $database,$_MAMBOTS;
	
 	//Подключение плагина всплывающего окна
    mosCommonHTML::loadJqueryPlugins('fancybox/jquery.fancybox', false, true); 
    //основной вывод
    UserContacts_output($user);	
}

 /**
	* Функция - оболочка вывода
	*/
    function UserContacts_output($user){
        global $mainframe, $mosConfig_live_site;
         $ajax_handler = 'ajax.index.php?option=com_users&task=request_from_plugin&plugin=user_contacts';

        ?>
        <!-- Всплывающее окно с формой отправки сообщения-->
         <script type="text/javascript">
            $(document).ready(function() {
                $(".fancy_inline").fancybox({
		            'hideOnContentClick': false
	            });
            });
        </script>

        <!--Значки ICQ, Skype-->
        <div class="messengers">
            <?php UserContacts_messengers($user);?>
        </div>

        <!--Сыылка, по нажатию на которую, появляется всплывающее окно с формой отправки сообщения -->
        <span class="email"><a class="fancy_inline email" href="<?php echo $mosConfig_live_site;?>/<?php echo $ajax_handler;?>&act=display_form&user_id=<?php echo $user->id;?>">
            <?php echo BOT_USER_CONTACTS_SEND_MESSAGE?>
        </a></span>
        <?php
    }

    /**
	* Вывод данных о мессенджерах
	*/
    function UserContacts_messengers($user){
        global $mosConfig_live_site;
		$img_url = $mosConfig_live_site."/images/system_images";

        if (isset($user->user_extra->icq))
		    {
		        ?>
    			<span class="icq">
                    <a href="javascript:void(window.open('http://www.icq.com/people/webmsg.php?to=<?php echo $user->user_extra->icq;?>','newWin','resizable=1,status=1,menubar=1,toolbar=1,scrollbars=1,location=1,directories=1,width=500,height=600,top=60,left=60'))">
                        <img src="http://web.icq.com/whitepages/online?icq=<?php echo $user->user_extra->icq;?>&img=5" align="absmiddle" border="0" alt="ICQ" title="ICQ">
                    </a>
                    &nbsp;
                    <?php echo $user->user_extra->icq  ;?>
                </span>
                <?php
		    }

		if (isset($user->user_extra->skype))
		    {
		        ?>
			    <span class="skype">
                    <a href="skype:<?php echo $user->user_extra->skype;?>?call">
                        <img name="Skypestatus" src="$img_url/skype.gif" align="absmiddle" border="0" alt="Skype" title="Skype">
                    </a>
                    <?php echo $user->user_extra->skype;?>
                </span>
                <?php
            }
    }

?>

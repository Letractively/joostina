<?php
 defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
 global $database;

    //Подключение плагина всплывающего окна
    mosCommonHTML::loadJqueryPlugins('fancybox/jquery.fancybox', false, true);

    //Дополнительная информация о пользователе
    $row->user_extra = $row->get_user_extra($row->id);

    //основной вывод
    UserContacts_output($row);


    /**
	* Функция - оболочка вывода
	*/
    function UserContacts_output($row){
        global $mainframe, $mosConfig_live_site;
         $ajax_handler = 'ajax.index.php?option=com_user&task=request_from_plugin&plugin=user_contacts';

        ?>
        <!-- Всплывающее окно с формой отправки сообщения-->
         <script type="text/javascript">
            $(document).ready(function() {
                $("a.fancy_inline").fancybox({
		            'hideOnContentClick': false
	            });
            });
        </script>

        <!--Значки ICQ, Skype-->
        <div class="messengers">
            <?php UserContacts_messengers($row);?>
        </div>

        <!--Сыылка, по нажатию на которую, появляется всплывающее окно с формой отправки сообщения -->
        <a class="fancy_inline email" href="<?php echo $mosConfig_live_site;?>/<?php echo $ajax_handler;?>&act=display_form&user_id=<?php echo $row->id;?>">
            Отправить сообщение
        </a>
        <?php
    }

    /**
	* Вывод данных о мессенджерах
	*/
    function UserContacts_messengers($row){
        global $mosConfig_live_site;
		$img_url = $mosConfig_live_site."/images/system_images";

        if ($row->user_extra->icq)
		    {
		        ?>
    			<span class="icq">
                    <a href="javascript:void(window.open('http://www.icq.com/people/webmsg.php?to=<?php echo $row->user_extra->icq;?>','newWin','resizable=1,status=1,menubar=1,toolbar=1,scrollbars=1,location=1,directories=1,width=500,height=600,top=60,left=60'))">
                        <img src="http://web.icq.com/whitepages/online?icq=<?php echo $row->user_extra->icq;?>&img=5" align="absmiddle" border="0" alt="ICQ" title="ICQ">
                    </a>
                    &nbsp;
                    <?php echo $row->user_extra->icq  ;?>
                </span>
                <?php
		    }

		if ($row->user_extra->skype)
		    {
		        ?>
			    <span class="skype">
                    <a href="skype:<?php echo $row->user_extra->skype;?>?call">
                        <img name="Skypestatus" src="$img_url/skype.gif" align="absmiddle" border="0" alt="Skype" title="Skype">
                    </a>
                    <?php echo $row->user_extra->skype;?>
                </span>
                <?php
            }
    }



?>
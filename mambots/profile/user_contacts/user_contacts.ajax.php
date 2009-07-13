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
require_once (Jconfig::getInstance()->config_absolute_path.'/mambots/profile/user_contacts/user_contacts.class.php');
$act = mosGetParam( $_REQUEST, 'act', '' );

switch ($act){
    case 'display_form':
        display_form();
        break;

    case 'user_sendmail':
        user_sendmail();
        break;

    default:
        echo 'error-act';
        return;
}

/**
* Форма отправки сообщения пользователю
*/
function display_form(){
    global $mosConfig_live_site, $my,  $database;

    $ajax_handler = 'ajax.index.php?option=com_users&task=request_from_plugin&plugin=user_contacts';
    $user_id = mosGetParam( $_REQUEST, 'user_id', 0 );

    //Подключение плагина валидации форм
    mosCommonHTML::loadJqueryPlugins('jquery.validate',1);
    //Подключение плагина ajax-форм
    mosCommonHTML::loadJqueryPlugins('jquery.form',1);

    //Параметры формы для отправки сообщения пользователю
    $form_params = new UserContactsEmail();

    ?>
        <!--Валидация формы отправки сообщения пользователю ajax-отправка данных-->
        <script type="text/javascript">
            $(document).ready(function() {

                var u_options = {
                    beforeSubmit: u_validate_this,
                    url:         '<?php echo $ajax_handler;?>',
                    //url:         'components/com_users/plugins/user_contacts/user_sendmail.php',
                    clearForm:   false,
                    success: showResponse
                };

                $('#UserContactsForm').submit(function() {
                    $(this).ajaxSubmit(u_options);
                    return false;
                });

                function showResponse(responseText, statusText) {
                    $('#resp').html(responseText);
                }

                function u_validate_this(){
                    $("#UserContactsForm").validate({
                        errorElement: "span",
                        messages:{
                            from_uname: {
                                required: ""
                            },
                            from_uemail:{
                                required: "",
                                email: ""
                            },
                            user_message:{
                                required: ""
                            }
                        }
                    });
                    if ($("#UserContactsForm").valid()==false){
                        /*alert('Проверьте правильность заполнения полей!');*/
                        return false;
                    }
                };

            });
        </script>
    <div id="UserForm">
        <div id="pretext"><?php echo $form_params->pretext; ?></div>
        <form  id="UserContactsForm" action="" class="validate" method="post" name="UserContactsForm">
        <div class="user_contact_form">

                    Представьтесь:<br />
                    <input type="text" name="from_uname" value="<?php echo $my->name;?>" class="inputbox required" />

                    <br />

                    Ваш e-mail:<br />
                    <input type="text" name="from_uemail" value="<?php echo $my->email;?>" class="inputbox required email" />

                   <br />

                   Текст сообщения:<br />
                   <textarea class="inputbox required"  name="user_message" rows="5" cols="50"></textarea>

        </div>

        <div class="button"><input type="submit" class="button" name="button"  value="Отправить" /></div>
        <input type="hidden" name="act" value="user_sendmail" />
        <input type="hidden" name="user_id" value="<?php echo $user_id;?>"  />
        </form>

        <div id="posttext"><?php echo $form_params->posttext;?></div>
        <div id="resp"></div>
    </div>

    <?php
}

function user_sendmail(){
    global $database;

    $user_id = mosGetParam( $_REQUEST, 'user_id', 0 );
    $user = new mosUser($database);
	$user->load((int)$user_id);

    $form_params = new UserContactsEmail();
    $form_params->recipient = $user->email;
    $form_params->from = $_POST['from_uemail'];
    $form_params->fromname = $_POST['from_uname'];
    $form_params->message = $form_params->clean_message($_POST['user_message']);

    if($form_params->send_message()){
        echo '<div class="info">Сообщение успешно отправлено</div>';
    }
     else{
        echo '<div class="error">Не удалось отправить сообщение '. $form_params->_error.'</div>';
    }


}



?>

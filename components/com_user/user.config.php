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
require_once ($mainframe->getPath('class','com_user'));

class configUser_registration extends dbConfig{
    /**
     * Заголовок страницы
     */
    var $title = 'Регистрация';
    /**
     * Текст перед формой регистрации
     */
    var $pre_text = 'Все поля, отмеченные символом (*), обязательны для заполнения!';
    /**
     * Текст после формы регистрации
     */
    var $post_text = '';
    /**
     * Ссылка для перехода после регистрации
     * По умолчанию: 
	 *     - если регистрация не требует активации аккаунта, редирект происходит в профиль пользователя;
	 *     - если требуется активация: редирект на страницу с информацией об активации (шаблон страницы: after_registration/default.php)
     */
    var $redirect_url = '';
    /**
     * Использовать единый шаблон формы регистрации для всех групп пользователей
     * да - один шаблон (view/tegistration/default.php)
     * нет - для каждой группы будет использован шаблон, имя которого формируется по следующему правилу:
     * view/tegistration/название_группы_без_пробелов.php
     * 
     * ВНИМАНИЕ! Эти шаблоны Вы должны создать саомостоятельно. Можете скопировать шаблон по-умолчанию и назвать
     * его согласно вышеописанному правилу.
     * 
     * Чтобы страница регистрации отобразилась с нужным шаблоном,
     * ссылка должна содержать параметр `type`, значением которого должно быть название группы пользователя
     * Например: index.php?option=com_registration&task=register&type=author
     * 
     * Результатом заполнения и отправки формы с "type=имя_группы" будет запись о новом пользователе, принадлежащем
     * к группе "имя_группы"
     */
    var $template = 1;
    /**
     * Группа пользователя по умолчанию. 
	 * Параметр работает в случае, если не используются шаблоны или
     * регистрация происходит с помощью шаблона "по умолчанию" (т.е. без параметра "type")
     */
    var $gid = '18';
    /**
     * Активация администратором. 
	 * Если в глобальных настройках сайта включена активация аакаунтов,
	 * пользователь должен подтвердить регистрацию с помощью ссылки в приходящем ему письме.
	 * Если же задействован данный параметр - письмо пользователю отправлено не будет, а будет показано
	 * сообщение об ожидающейся активации его аккаунта администратором сайта
     */
    var $admin_activation = 0;
    


    function configUser_registration(&$db, $group = 'com_user', $subgroup = 'registration')
    {
        $this->dbConfig($db, $group, $subgroup);
    }
	
	function display_config($option){
		global $acl;
		
		$gtree = $acl->get_group_children_tree(null,'USERS',false);
		?>
		<script language="javascript" type="text/javascript">
			function submitbutton(pressbutton) {
				var form = document.adminForm;
				if (pressbutton == 'cancel') {
					submitform( pressbutton );
					return;
				}
	
				// do field validation
				if  (form.gid.value == "") {
					alert( "<?php echo _ENTER_GROUP_PLEASE?>" );
				} 
				else if (form.gid.value == "29") {
					alert( "<?php echo _BAD_GROUP_1?>" );
				} 
				else if (form.gid.value == "30") {
					alert( "<?php echo _BAD_GROUP_2?>" );
				} else {
					submitform( pressbutton );
				}
	
			}	
		</script>
        <h1 class="config">Пользователи: настройки регистрации</h1>

        <form action="index2.php" method="post" name="adminForm">

            <table class="paramslist">
                <tr>
                    <th width="250">Заголовок страницы</th>
                    <td><input class="inputbox" type="text" name="title" value="<?php echo $this->title;?>" /></td>
                </tr>
                <tr>
                    <th>Текст перед формой регистрации</th>
                    <td><textarea class="inputbox" name="pre_text"><?php echo $this->pre_text;?></textarea></td>
                </tr>
                <tr>
                    <th>Текст после формы регистрации</th>
                    <td><textarea class="inputbox" name="post_text"><?php echo $this->post_text;?></textarea></td>
                </tr>
                <tr>
                    <th>Ссылка для перехода после регистрации</th>
                    <td><input class="inputbox" type="text" name="redirect_url" value="<?php echo $this->redirect_url;?>" /></td>
                </tr>
                <tr>
                    <th>Использовать единый шаблон формы регистрации для всех групп пользователей</th>
                    <td><?php echo mosHTML::yesnoRadioList('template','',$this->template ? 1:0);?></td>
                </tr> 
                <tr>
                    <th>Группа пользователя по умолчанию</th>
                    <td><?php echo mosHTML::selectList($gtree,'gid','size="1"','value','text',$this->gid);?></td>
                </tr> 
                <tr>
                    <th>Активация аккаунта администратором (работает совместно с глобальной настройкой "Использовать активацию нового аккаунта:")</th>
                    <td><?php echo mosHTML::yesnoRadioList('admin_activation','',$this->admin_activation ? 1:0);?></td>
                </tr> 
            </table>

            <input type="hidden" name="option" value="<?php echo $option; ?>" />
            <input type="hidden" name="act" value="registration" />
		    <input type="hidden" name="task" value="save_config" />
            <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
        </form>


        <?php
	}
	
	function save_config(){
	    if (!$this->bindConfig($_REQUEST)) {
        	echo "<script> alert('".$this->_error."'); window.history.go(-1); </script>";
        	exit();
    	}

    	if (!$this->storeConfig()) {
        	echo "<script> alert('".$this->_error."'); window.history.go(-1); </script>";
        	exit();
    	}	
	}	
}


?>
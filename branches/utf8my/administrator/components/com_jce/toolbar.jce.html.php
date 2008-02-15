<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, смотрите LICENSE.php
* Joostina! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/
require(dirname(__FILE__).'/../../die.php');

/**
* @package Mambo_4.5.1
*/
class TOOLBAR_JCE {
        /**
    	* Writes a common 'publish' button
    	* @param string An override for the task
    	* @param string An override for the alt text
    	*/
    	function accessButton( $task='applyaccess', $alt='Доступ' ) {
    		?>
    		<li>
    			<a class="tb-access" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Отметьте необходимое для назначения уровня доступа'); } else {submitbutton('<?php echo $task;?>', '');}">
    				<?php echo $alt; ?></a>
    		</li>
    	 	<?php
	   }
	   function helpButton( $section, $alt='Помощь') {
			?>
    	 	<li>
    			<a class="tb-help" href="javascript:void(0);" onclick="window.open('http://www.cellardoor.za.net/index2.php?option=com_content&amp;task=findkey&amp;pop=1&amp;keyref=<?php echo $section;?>', 'Помощь', 'width=750,height=500,top=20,left=20,scrollbars=yes,resizable=yes');">
    				<?php echo $alt; ?></a>
    		</li>
    	 	<?php
	   }
        function _CONFIG() {
                mosMenuBar::startTable();
                mosMenuBar::save();
                mosMenuBar::custom('main', '-back', '', 'Главная', false);
                mosMenuBar::spacer();
                mosMenuBar::cancel();
                mosMenuBar::endTable();
        }
        function _PLUGINS() {
    		mosMenuBar::startTable();
    		mosMenuBar::publishList();
    		mosMenuBar::spacer();
    		mosMenuBar::unpublishList();
    		mosMenuBar::spacer();
    		mosMenuBar::custom('newplugin', '-new', '', 'Новый', false);
    		mosMenuBar::spacer();
    		mosMenuBar::custom('installplugin', '-new', '', 'Установка',false);
    		mosMenuBar::spacer();
    		mosMenuBar::custom('editlayout', '-preview', '', 'Предпросмотр',false);
    		mosMenuBar::spacer();
    		TOOLBAR_JCE::accessButton();
    		mosMenuBar::spacer();
			TOOLBAR_JCE::helpButton('admin.plugins.view');
			mosMenuBar::spacer();
    		mosMenuBar::custom('cancel', '-cancel', '', 'Отмена', false);
    		mosMenuBar::endTable();
        }
        function _EDIT_PLUGINS() {
    		global $id;

    		mosMenuBar::startTable();
    		mosMenuBar::custom('saveplugin', '-save', '', 'Сохранить', false);
    		mosMenuBar::spacer();
    		if ( $id ) {
    			// for existing content items the button is renamed `close`
    			mosMenuBar::custom('canceledit', '-cancel', '', 'Закрыть', false);
    		} else {
                mosMenuBar::custom('canceledit', '-cancel', '', 'Отмена', false);
    		}
    		mosMenuBar::spacer();
    		mosMenuBar::endTable();
    	}
    	function _INSTALL( $element ) {
            if( $element == 'plugins' ){
                mosMenuBar::startTable();
                mosMenuBar::custom('showplugins', '-new', '', 'Плагины', false);
                mosMenuBar::spacer();
                mosMenuBar::custom('removeplugin', '-delete', '', 'Удаление', false);
                mosMenuBar::spacer();
				TOOLBAR_JCE::helpButton('admin.plugins.install');
				mosMenuBar::spacer();
                mosMenuBar::custom('cancel', '-cancel', '', 'Отмена', false);
    		    mosMenuBar::endTable();
            }
        }
        function _LAYOUT() {
    		mosMenuBar::startTable();
    		mosMenuBar::custom('savelayout', '-save', '', 'Сохранить', false);
    		mosMenuBar::spacer();
			TOOLBAR_JCE::helpButton('admin.layout');
			mosMenuBar::spacer();
    		mosMenuBar::custom('cancel', '-cancel', '', 'Отмена', false);
    		mosMenuBar::endTable();
        }
        function _LANGS() {
    		mosMenuBar::startTable();
    		mosMenuBar::publishList('publishlang');
    		mosMenuBar::spacer();
    		mosMenuBar::custom('removelang', '-delete', '', 'Удалить', false);
    		mosMenuBar::spacer();
    		mosMenuBar::custom('newlang', '-new', '', 'Установить',false);
			mosMenuBar::spacer();
			TOOLBAR_JCE::helpButton('admin.languages');
			mosMenuBar::spacer();
    		mosMenuBar::custom('cancel', '-cancel', '', 'Отмена', false);
    		mosMenuBar::endTable();
        }
}
?>

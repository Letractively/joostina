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

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );

class HTML_JCEAdmin {
    function showAdmin()
    {
        global $mainframe;
        ?>
        <form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="cpanel">
			Конфигурация JCE
			</th>
        </tr>
        <tr>
        <td width="55%" valign="top">
	    <div id="cpanel">
            <div style="float:left;">
        		<div class="icon">
        			<a href="index2.php?option=com_jce&task=config&hidemainmenu=1">
        				<div class="iconimage">
        					<img src="<?php echo $mainframe->getCfg('live_site');?>/administrator/images/config.png" alt="Конфигурация" align="middle" name="image" border="0" />				</div>
        				Конфигурация редактора</a>
        		</div>
    		</div>
    		<div style="float:left;">
        		<div class="icon">
        			<a href="index2.php?option=com_jce&task=showplugins">
        				<div class="iconimage">
        					<img src="<?php echo $mainframe->getCfg('live_site');?>/administrator/images/module.png" alt="Показать плагины" align="middle" name="image" border="0" />				</div>
        				Плагины</a>
        		</div>
    		</div>
    		<div style="float:left;">
        		<div class="icon">
        			<a href="index2.php?option=com_jce&task=install&element=plugins">
        				<div class="iconimage">
        					<img src="<?php echo $mainframe->getCfg('live_site');?>/administrator/images/install.png" alt="Установить плагины" align="middle" name="image" border="0" />				</div>
        				Установка плагинов</a>
        		</div>
    		</div>
    		<div style="float:left;">
        		<div class="icon">
        			<a href="index2.php?option=com_jce&task=editlayout&hidemainmenu=1">
        				<div class="iconimage">
        					<img src="<?php echo $mainframe->getCfg('live_site');?>/administrator/images/templatemanager.png" alt="Изменить расположение" align="middle" name="image" border="0" />				</div>
        				Расположение значков</a>
        		</div>
    		</div>
    		<div style="float:left;">
        		<div class="icon">
        			<a href="index2.php?option=com_jce&task=lang&hidemainmenu=1">
        				<div class="iconimage">
        					<img src="<?php echo $mainframe->getCfg('live_site');?>/administrator/images/langmanager.png" alt="Менеджер локализаций" align="middle" name="image" border="0" />				</div>
        				Менеджер локализаций</a>
        		</div>
    		</div>
		</div>
		</td>
        </tr>
        </table>
        <?php
    }
}
?>

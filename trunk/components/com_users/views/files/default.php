<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

mosMainFrame::addLib('files');

?>
<table width="100%">
    <?php foreach( $files as $file ):// файлы ?>
    <?php $filename = JPATH_SITE .'/attachments/'.$file->file_mime.'/'.File::makefilename( $file->id ).'/'.$file->file_name; ?>
    <tr>
        <td>
            <a href="<?php echo $filename  ?>"><?php echo $file->file_name ?></a>
            <br />
            <input style="width: 99%" type="text" value="&lt;img src=&quot;<?php echo $filename ?>&quot; /&gt;" />
        </td>
        <td><?php echo $file->file_mime ?></td>
        <td><?php echo $file->file_size ?> kb.</td>
    </tr>
    <?php endforeach;// файлы ?>
</table>
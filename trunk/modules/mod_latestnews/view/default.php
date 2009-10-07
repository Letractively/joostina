<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

if ($params->get('numrows',0)) {
?>
    <div class="mod_latestnews <?php echo $params->get('moduleclass_sfx', '');?>">
        <ul>
            <?php foreach ($rows as $row): ?>
                <?php $module->helper->prepare_row($row, $params);?>
                    <li>
                        <?php if($params->get('image','mosimage')): ?>
                            <?php echo $row->image;?>
                        <?php endif; ?>

                        <?php if($params->get('show_date',1)): ?>
                            <span class="date"><?php echo mosFormatDate($row->created); ?></span>
                        <?php endif; ?>

                        <?php if($params->get('show_author',0)): ?>
                            <span class="author"><?php echo $row->author;?></span>
                        <?php endif; ?>

                        <?php if($params->get('item_title',1)): ?>
                            <?php echo $row->title;?>
                        <?php endif; ?>

                        <?php if($params->get('text',0)): ?>
                            <?php echo $row->text;?>
                        <?php endif; ?>

                        <?php if($params->get('readmore', 0)):?>
                            <div class="readmore"><?php echo $row->readmore ;?></div>
                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>
        </ul>
    </div>
    <?php
}
<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

?>

<div class="page page_tags">
    <h1 class="ico_articles">
        <a href="<?php echo sefRelToAbs('index.php?option=com_tags&task=cloud',true) ?>">Тэги</a>
        &rarr; <span><?php echo $tag;?></span>
    </h1>
    <div class="pc">
        <?php foreach ( $search_results_nodes  as $node ) :// контент ?>
            <?php $node->original_title = $node->title ?>
            <?php $node->title = htmlspecialchars($node->title, ENT_QUOTES, 'UTF-8') ?>
        <div class="pc_articles">
            <h4><a href="<?php echo sefRelToAbs('index.php?option=com_pages&task=view&id='.sprintf('%s:%s',$node->id,$node->original_title) ) ?>" title="<?php echo $node->title ?>"><?php echo $node->title ?></a></h4>
            <p><?php echo Text::word_limiter( Text::strip_tags_smart( $node->text ), 50 ) ?></p>
            <a title="<?php echo $node->title ?>" class="readmore" href="<?php echo sefRelToAbs('index.php?option=com_pages&task=view&id='.sprintf('%s:%s',$node->id,$node->title) ) ?>">читать дальше</a>
        </div>
        <?php endforeach; // контент ?>
        <?php echo $pager->output; // постраничная навигация ?>
    </div>
</div>
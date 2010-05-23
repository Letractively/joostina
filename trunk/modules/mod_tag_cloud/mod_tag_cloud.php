<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

require_once ($mainframe->getPath('class','com_tags'));

$tags = new Tags;
$tag_arr = $tags->load_all();

$tags_cloud = new tagsCloud($tag_arr);
$tags_cloud = $tags_cloud->get_cloud('', 50);

shuffle($tags_cloud);

?>
<div class="mod_tag_cloud"><p><?php echo implode("\n", $tags_cloud);?></p></div>
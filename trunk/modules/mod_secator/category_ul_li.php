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

$moduleclass_sfx = $params->get( 'moduleclass_sfx', '' );
$target = $params->get( 'target', 0 );
$target = ($target==1) ? 'target="_blank"' : '';

function _secator_get_count_content(){
	$db = database::getInstance();
	$sql = 'SELECT catid,count(id) as count FROM #__content GROUP BY catid';
	$db->setQuery($sql);
	$cats = $db->loadObjectList();
	$ret = array();
	foreach($cats as $cat){
		$ret[$cat->catid]=$cat->count;
	}
	return $ret;
}

$all_counters = _secator_get_count_content();

?><ul class="secator<?php echo $moduleclass_sfx;?>">
<?php foreach ($rows as $row){ ?>
	<li><a href="<?php echo _get_secator_link($row,$params);?>" <?php echo $target; ?>><?php echo $row->title ?></a> [<?php echo $all_counters[$row->id] ?>]</li>
<?php } ?>
</ul>
<?php unset($rows,$row,$all_counters,$target,$moduleclass_sfx) ?>

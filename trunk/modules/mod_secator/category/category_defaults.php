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

$database = &database::getInstance();

$count			= intval($params->get( 'count', 5 ));
$order_asc_desc	= strval($params->get( 'order_asc_desc', 'ASC' ));
$order_category	= strval($params->get( 'order_category', 'order' ));

$_where = 'WHERE ';
$_where_published = mod_secator::get_where_published($params->get( 'show_published', 1 ),'#__categories.published');

$sql = 'SELECT #__categories.id,#__categories.title,#__categories.name,#__categories.image,#__categories.image_position,#__categories.description from #__categories
	INNER JOIN #__sections ON #__sections.id = #__categories.section
	'.$_where.$_where_published.' ORDER BY #__categories.'.$order_category.' '.$order_asc_desc.' ';
$database->setQuery( $sql,0,$count );
$rows = $database->loadObjectList();

?>
<div class="secator cat_list">
<?php foreach($rows as $row){ ?>
	<a href="<?php echo mod_secator::get_secator_link($row,$params);?>"><?php echo $row->title;?></a><br />
<?php } ?>
</div>
<?php
unset($database,$rows,$row,$params);
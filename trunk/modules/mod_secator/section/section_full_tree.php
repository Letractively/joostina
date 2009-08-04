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

$database		= &database::getInstance();

$count			= intval($params->get( 'count', 5 ));
$order_asc_desc	= strval($params->get( 'order_asc_desc', 'ASC' ));
$order_section	= strval($params->get( 'order_section', 'order' ));

$_where = 'WHERE ';
$_where_published = mod_secator::get_where_published($params->get( 'show_published', 1 ),'published');

$sql = 'SELECT * FROM #__sections '.$_where.$_where_published.' ORDER BY '.$order_section.' '.$order_asc_desc.' ';
$database->setQuery( $sql,0,$count );
$sections = $database->loadObjectList();

$sql = 'SELECT c.id,c.title,c.section FROM #__categories as c INNER JOIN #__sections AS s ON s.id = c.section WHERE c.published=1';
$database->setQuery( $sql,0,$count );
$categories = $database->loadObjectList();

$categories_array = array();
foreach($categories as $category){
	$categories_array[$category->section][]=$category;
}

?>
<ul class="secator sec_list">
<?php foreach($sections as $section){ ?>
	<li>
		<a href="<?php echo mod_secator::get_secator_link($section,$params);?>"  title="<?php echo $section->title ?>"><?php echo $section->title;?></a>
<?php
if(isset($categories_array[$section->id])){
	?><ul><?php
		foreach($categories_array[$section->id] as $category){
			?><li><a href="<?php echo mod_secator::get_secator_link($category,$params);?>" title="<?php echo $category->title ?>"><?php echo $category->title ?></a></li><?php
		}
	?></ul><?php
}
?>
	</li>
<?php } ?>
</ul>

<?php
unset($database,$rows,$row,$params,$categories,$category);
?>

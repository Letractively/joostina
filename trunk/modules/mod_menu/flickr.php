<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

$database	= database::getInstance();
$config		= Jconfig::getInstance();


if ($config->mosConfig_shownoauth) { // если в глобалконфиге разрешено показывать ссылке не авторизованным
	$sql = "SELECT m.* FROM #__menu AS m"
	. "\nWHERE menutype='$use_menu' AND published='1'"
	. "\nORDER BY parent,ordering";
} else {
	$sql = "SELECT id,name,parent FROM #__menu AS m"
	. "\nWHERE menutype='$use_menu' AND published='1' AND access <= '$my->gid'"
	. "\nORDER BY parent,ordering";
};
$database->setQuery($sql);
$rows = $database->loadObjectList( 'id' );

$children = array();
foreach ($rows as $v ) {
	$pt		= $v->parent;
	$list	= isset($children[$pt]) ? $children[$pt] : array();
	array_push( $list, $v );
	$children[$pt] = $list;
}

?>
<link href="http://localhost/joostina-extensions/modules/mod_menu/modules/mod_menu/style/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
<link href="http://localhost/joostina-extensions/modules/mod_menu/modules/mod_menu/style/flickr/default.ultimate.css" media="screen" rel="stylesheet" type="text/css" />
<!--[if lt IE 7]>
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/jquery/jquery.dropdown.js"></script>
<![endif]-->
<?php


echo "<ul id=\"nav\" class=\"dropdown dropdown-horizontal\">\n";
		menu_recurse( 0, 0, $children, $open, $indents, $params );
echo "</ul>\n";

	function menu_recurse( $id, $level, &$children, &$open, &$indents, &$params ) {
		if (isset($children[$id])) {
			foreach ($children[$id] as $row) {
				if (isset($children[$row->id])) {
					echo '<li>';
					echo '<span class="dir">'.$row->parent.':'.$row->name.':'.$row->id.'</span>';
//					echo '<a href="#" class="dir">'.$row->parent.':'.$row->name.':'.$row->id.'</a>';
					if (isset($children[$row->id])) {
						echo "<ul>";
						menu_recurse( $row->id, $level+1, $children, $open, $indents, $params );
						echo "</ul>\n";
					}
					echo "</li>";
				} else {
					echo '<li>';
					echo '<a href="#">'.$row->parent.':'.$row->name.':'.$row->id.'</a>';
					echo "</li>\n";
				}
			}
		}
	}
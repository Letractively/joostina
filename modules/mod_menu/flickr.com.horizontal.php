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

$access = $config->config_shownoauth ? '' : " AND access <= '{$my->gid}'";
$sql = "SELECT * FROM #__menu AS m WHERE menutype='{$use_menu}' AND published='1' {$access} ORDER BY parent,ordering";
$database->setQuery($sql);
$rows = $database->loadObjectList( 'id' );

$children = array();
foreach ($rows as $v ) {
	$pt		= $v->parent;
	$list	= isset($children[$pt]) ? $children[$pt] : array();
	array_push( $list, $v );
	$children[$pt] = $list;
}

unset($database,$access,$sql,$rows,$pt,$list,$v);

function menu_recurse( $id, $level, &$children) {
	if (isset($children[$id])) {
		foreach ($children[$id] as $row) {
			if($row->type=='separator'){
				$href = 'javascript:void(0)';
			}
			else{
				$href = sefRelToAbs($row->link.'&Itemid='.$row->id);	
			}
				
			if (isset($children[$row->id])) {
				_add_tab($level);

				echo '<li><span class="parent"><a href="'.$href.'" class="dir">'.$row->name."</a></span>\n";
				if (isset($children[$row->id])) {
					_add_tab($level);//
					echo '<ul class="dropdown2">';
					_add_tab($level);//
					menu_recurse( $row->id, $level+1, $children);
					_add_tab($level);//
					echo "</ul>\n";
				}
				_add_tab($level);//
				echo "</li>\n";
			} else {
				_add_tab($level);//
				echo '<li><span><a href="'.$href.'">'.$row->name."</a></span></li>\n";
			}
		}
	}
}

// красивости
function _add_tab($count){
	for ($i = 1; $i <= $count; $i++) {
		echo '  ';
	}
}

?>

<?php if($params->get('css')) : ?>
<link href="<?php echo $config->config_live_site ?>/modules/mod_menu/css/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo $config->config_live_site ?>/modules/mod_menu/flickr.com.horizontal/default.ultimate.css" media="screen" rel="stylesheet" type="text/css" />
<?php endif ?>
<!--[if lt IE 7]>
<?php
	mosCommonHTML::loadJquery();
	mosCommonHTML::loadJqueryPlugins('jquery.dropdown');
?>
<![endif]-->
<ul class="dropdown">
	<?php menu_recurse( 0, 0, $children ); ?>
</ul>
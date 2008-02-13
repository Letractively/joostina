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

include($mosConfig_absolute_path . '/administrator/components/com_customquickicons/lang/russian.php' );

function MOD_quickiconButton( $row, $newWindow ){
	global $mosConfig_live_site,$my;
	$title  = ( $row->akey ? $row->title . ' [ ' . _QI_MOD_ACCESSKEY . ' ' . $row->akey . ' ]' : ( $row->title ? $row->title : $row->text ));
	$accKey = $row->akey ? ' accesskey="' . $row->akey . '"' : ''; ?>
				<div>
				<a href="<?php echo htmlentities( $row->target ); ?>" title="<?php echo $title; ?>"<?php echo $accKey . $newWindow; ?>>
				<?php
				$icon = '<img src="' . $mosConfig_live_site . $row->icon . '" alt="" border="0" />';
				if( $row->display == 1 ){ ?>
					<span><?php echo $row->prefix . $row->text . $row->postfix; ?></span>
					<?php
					}elseif( $row->display == 2 ){
						echo $icon;
					}else{
						echo $icon;
						echo $row->prefix . $row->text . $row->postfix;
					} ?>
				</a>
				</div>
	<?php
}
?>
<div class="admin_front">
	<div class="cpicons">
	<?php
		$query = 'SELECT *'
		. ' FROM #__custom_quickicons'
		. ' WHERE published = 1'
		. ' AND gid <= ' . $my->gid
		. ' ORDER BY ordering'
		;
		$database->setQuery($query);
		$rows = $database->loadObjectList();
		foreach( $rows AS $row ){
			$callMenu = true;
			if( $row->cm_check ){
				if( !file_exists( $mosConfig_absolute_path . '/administrator/components/' . $row->cm_path )){
					$callMenu = false;
				}
			}
			if( $callMenu ){
				$newWindow = $row->new_window ? ' target="_blank"' : '';
				MOD_quickiconButton( $row, $newWindow );
			}
		} ?>
	</div>
			<?php
		$securitycheck = intval( $params->get( 'securitycheck', 1 ) );
		if( !empty( $securitycheck )) {
			// show security setting check
			josSecurityCheck('88%');
		} ?>
</div>
	<?php if($my->usertype=='Super Administrator'){?>
		<a href="index2.php?option=com_customquickicons" style="display: block; clear: both; text-align:left; padding-top:10px;"><img border="0" src="<?php echo $mosConfig_live_site;?>/administrator/images/shortcut.png" />Изменить кнопки быстрого доступа</a>
	<?php
}
?>

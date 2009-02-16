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
defined( '_VALID_MOS' ) or die( 'Доступ ограничен' );

global $mod_wrapper_count;

$params->def( 'url', '' );
$params->def( 'scrolling', 'auto' );
$params->def( 'height', '200' );
$params->def( 'height_auto', '0' );
$params->def( 'width', '100%' );
$params->def( 'add', '1' );

$url = $params->get( 'url' );
if ( $params->get( 'add' ) ) {
	// Добавить 'http://' , если отсутствует  
	if ( substr( $url, 0, 1 ) == '/' ) {
		// relative url in component. use server http_host.
		$url = 'http://'. $_SERVER['HTTP_HOST'] . $url;
	} elseif ( !strstr( $url, 'http' ) && !strstr( $url, 'https' ) ) {
		$url = 'http://'. $url;
	} else {
		$url = $url;
	}
}
 
// Create a unique ID for the IFrame, output the javascript function only once
if (!isset( $mod_wrapper_count )) {
	$mod_wrapper_count = 0;
	?>
<script language="javascript" type="text/javascript">
function iFrameHeightX( iFrameId ) {
	var h = 0;
	if ( !document.all ) {
		h = document.getElementById(iFrameId).contentDocument.height;
		document.getElementById(iFrameId).style.height = h + 60 + 'px';
	} else if( document.all ) {
		h = document.frames(iFrameId).document.body.scrollHeight;
		document.all[iFrameId].style.height = h + 20 + 'px';
	}
}
</script>
	<?php
}

// автоматический контроль высоты
if ( $params->def( 'height_auto' ) ) {
	$load = 'onload="iFrameHeightX(\'blockrandom' . $mod_wrapper_count . '\')" ';
} else {
	$load = '';
}
?>
<iframe
<?php echo $load; ?>
id="blockrandom<?php echo $mod_wrapper_count++; ?>"
src="<?php echo $url; ?>"
width="<?php echo $params->get( 'width' ); ?>"
height="<?php echo $params->get( 'height' ); ?>"
scrolling="<?php echo $params->get( 'scrolling' ); ?>"
align="top"
frameborder="0"
class="wrapper<?php echo $params->get( 'moduleclass_sfx' ); ?>">
<?php echo _CMN_IFRAMES; ?>
</iframe>
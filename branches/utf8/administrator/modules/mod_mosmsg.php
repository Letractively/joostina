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

$mosmsg = strval( ( stripslashes( strip_tags( mosGetParam( $_REQUEST, 'mosmsg', '' ) ) ) ) );

// Browser Check
$browserCheck = 0;
if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && isset( $_SERVER['HTTP_REFERER'] ) && strpos($_SERVER['HTTP_REFERER'], $mosConfig_live_site) !== false ) {
	$browserCheck = 1;
}

if ($mosmsg && $browserCheck ) {	
	// limit mosmsg to 200 characters
	if ( strlen( $mosmsg ) > 200 ) {
		$mosmsg = substr( $mosmsg, 0, 200 );
	}	
	?>
	<div id="message" class="message">
		<?php echo $mosmsg; ?>
	</div>
	<?php
}
?>

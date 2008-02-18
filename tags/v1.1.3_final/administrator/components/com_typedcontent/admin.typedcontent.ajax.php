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


global $mosConfig_absolute_path,$mosConfig_live_site,$my;
/* всех не авторизованных игнорируем */
if(!$my->id) exit;

$task	= mosGetParam( $_GET, 'task', 'publish');
$id		= intval(mosGetParam( $_GET, 'id', '0'));

switch($task){
	case "publish":
		$img = x_publish($id);
		echo '<img src="'.$mosConfig_live_site.'/administrator/images/'.$img.'" width="12" height="12" border="0" alt="" />';
		return ;
}

/* публикация объекта
$id - идентификатор объекта
 */
function x_publish($id=null){
	global $database,$my;
	// id содержимого для обработки не получен - выдаём ошибку	
	if(!$id) return 'Идентификатор не опознан.';

	$state = new stdClass();
	$query = "SELECT state, publish_up, publish_down"
	. "\n FROM #__content "
	. "\n WHERE id = " . (int) $id;
	$database->setQuery( $query );
	$row = $database->loadobjectList();
	$row = $row['0'];// результат запроса с элементами выбранных значений

	$now = _CURRENT_SERVER_TIME;
	$nullDate = $database->getNullDate();
	$ret_img = '';// сюда надо изображения ошибки выполнения аякс скрипта поместить	
	if ( $now <= $row->publish_up && $row->state == 1 ) {
		// снимаем с публикации, опубликовано, но еще не доступно  - возвращаем значок "Неопубликовано"
		$ret_img = 'publish_x.png';
		$state = '0'; // было опубликовано - снимаем с публикации
	} elseif( $now <= $row->publish_up && $row->state == 0 ) {
		// снимаем с публикации, не опубликовано, и еще не доступно  - возвращаем значок "Не активно"
		$ret_img = 'publish_y.png';
		$state = '1'; /* не было опубликовано - публикуем */
	} else if ( ( $now <= $row->publish_down || $row->publish_down == $nullDate ) && $row->state == 1 ) {
		// доступно и опубликовано, снимаем с публикации и возвращаем значок "Не опубликовано"
		$ret_img = 'publish_x.png';
		$state = '0'; // было опубликовано - снимаем с публикации
	} else if ( ( $now <= $row->publish_down || $row->publish_down == $nullDate ) && $row->state == 0 ) {
		// доступно и опубликовано, снимаем с публикации и возвращаем значок "Не опубликовано"
		$ret_img = 'publish_g.png';
		$state = '1'; /* не было опубликовано - публикуем */
	} else if ( $now > $row->publish_down && $row->state == 1 ) {
		// опубликовано, но срок публикации истёк, снимаем с публикации и возвращаем значок "Не опубликовано"
		$ret_img = 'publish_x.png';
		$state = '0'; /* не было опубликовано - публикуем */
	} else if ( $now > $row->publish_down && $row->state == 0 ) {
		// опубликовано, но срок публикации истёк, снимаем с публикации и возвращаем значок "Не опубликовано"
		$ret_img = 'publish_r.png';
		$state = '1'; /* не было опубликовано - публикуем */
	}
	
	$query = "UPDATE #__content"
	. "\n SET state = " . (int) $state . ", modified = " . $database->Quote( date( 'Y-m-d H:i:s' ) )
	. "\n WHERE id = " .$id. " AND ( checked_out = 0 OR (checked_out = " . (int) $my->id . ") )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		return 'error!';
	}else{
		return $ret_img;
	}
}
?>

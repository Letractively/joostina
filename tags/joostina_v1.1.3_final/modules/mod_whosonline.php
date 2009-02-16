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

$showmode 	= $params->get( 'showmode', 0 );

$output 	= '';

// show online count
if ($showmode==0 || $showmode==2) {
	$query = "SELECT guest, usertype"
	. "\n FROM #__session"
	;
	$database->setQuery( $query );
	$sessions = $database->loadObjectList();
  
	// calculate number of guests and members
	$user_array 	= 0;
	$guest_array 	= 0;
	foreach( $sessions as $session ) {
		// if guest increase guest count by 1
		if ( $session->guest == 1 && !$session->usertype ) {
			$guest_array++;
		}
		// if member increase member count by 1
		if ( $session->guest == 0 ) {
			$user_array++;
		}
	}

	// check if any guest or member is on the site
	if ($guest_array != 0 || $user_array != 0) {
		$output .= _WE_HAVE;
		// guest count handling
		if ($guest_array==1) {
		// 1 guest only
			$output .= sprintf( _GUEST_COUNT, $guest_array );
		} else if ($guest_array > 1) {
		// more than 1 guest
			$output .= sprintf( _GUESTS_COUNT, $guest_array );
		}
	
		// if there are guests and members online

	if ($guest_array != 0 && $user_array != 0) {
			$output .= _AND;
		}

		// member count handling
		if ($user_array==1) {
		// 1 member only
			$output .= sprintf( _MEMBER_COUNT, $user_array );
		} else if ($user_array > 1) {
		// more than 1 member
			$output .= sprintf( _MEMBERS_COUNT, $user_array );
		}

		$output .= _ONLINE;
	}
}

// show online member names
if ($showmode > 0) {
	$query = "SELECT DISTINCT a.username"
	."\n FROM #__session AS a"
	."\n WHERE a.guest = 0"
	;
	$database->setQuery($query);
	$rows = $database->loadObjectList();
	
	if ( count( $rows ) ) {
		// output
		$output .= '<ul>';
	foreach($rows as $row) {
			$output .= '<li>';
			$output .= '<strong>';
			$output .= $row->username;
			$output .= '</strong>';
			$output .= '</li>';
	}
		$output .= '</ul>';
	}
}

echo $output;
?>
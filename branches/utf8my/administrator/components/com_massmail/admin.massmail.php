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
require(dirname(__FILE__).'/../../die.php');

// ensure user has access to this function
if (!$acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_massmail' )) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );

switch ($task) {
	case 'send':
		sendMail();
		break;

	case 'cancel':
		mosRedirect( 'index2.php' );
		break;

	default:
		messageForm( $option );
		break;
}

function messageForm( $option ) {
	global $acl;

	$gtree = array(
	mosHTML::makeOption( 0, '- Все группы пользователей -' )
	);

	// get list of groups
	$lists = array();
	$gtree = array_merge( $gtree, $acl->get_group_children_tree( null, 'USERS', false ) );
	$lists['gid'] = mosHTML::selectList( $gtree, 'mm_group', 'size="10"', 'value', 'text', 0 );

	HTML_massmail::messageForm( $lists, $option );
}

function sendMail() {
	global $database, $my, $acl;
	global $mosConfig_sitename;
	global $mosConfig_mailfrom, $mosConfig_fromname;

	$mode				= intval( mosGetParam( $_POST, 'mm_mode', 0 ) );
	$subject			= strval( mosGetParam( $_POST, 'mm_subject', '' ) );
	$gou				= mosGetParam( $_POST, 'mm_group', NULL );
	$recurse			= strval( mosGetParam( $_POST, 'mm_recurse', 'NO_RECURSE' ) );
	// pulls message inoformation either in text or html format
	if ( $mode ) {
		$message_body	= $_POST['mm_message'];
	} else {
		// automatically removes html formatting
		$message_body	= strval( mosGetParam( $_POST, 'mm_message', '' ) );
	}
	$message_body 		= stripslashes( $message_body );

	if (!$message_body || !$subject || $gou === null) {
		mosRedirect( 'index2.php?option=com_massmail&mosmsg=Пожалуйста, заполните корректно форму' );
	}

	// get users in the group out of the acl
	$to = $acl->get_group_objects( $gou, 'ARO', $recurse );

	$rows = array();
	if ( count( $to['users'] ) || $gou === '0' ) {
		// Get sending email address
		$query = "SELECT email"
		. "\n FROM #__users"
		. "\n WHERE id = " . (int) $my->id
		;
		$database->setQuery( $query );
		$my->email = $database->loadResult();

		mosArrayToInts( $to['users'] );
		$user_ids = 'id=' . implode( ' OR id=', $to['users'] );

		// Get all users email and group except for senders
		$query = "SELECT email"
		. "\n FROM #__users"
		. "\n WHERE id != " . (int) $my->id
		. ( $gou !== '0' ? " AND ( $user_ids )" : '' )
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();

		// Build e-mail message format
		$message_header 	= sprintf( _MASSMAIL_MESSAGE, html_entity_decode($mosConfig_sitename, ENT_QUOTES) );
		$message 			= $message_header . $message_body;
		$subject 	= html_entity_decode($mosConfig_sitename, ENT_QUOTES) . ' / '. stripslashes( $subject);

		//Send email
		foreach ($rows as $row) {
			mosMail( $mosConfig_mailfrom, $mosConfig_fromname, $row->email, $subject, $message, $mode );
		}
	}

	$msg = 'E-mail отправлено '. count( $rows ) .' пользователю(ям)';
	mosRedirect( 'index2.php?option=com_massmail', $msg );
}
?>

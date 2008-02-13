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

define( '_VALID_MOS', 1 );

require_once('../../../globals.php');
require_once('../../../configuration.php');
require_once($mosConfig_absolute_path.'/includes/database.php');

if ( $mosConfig_db != "") {
	$database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );
}

switch($_GET['task']){
	case 'vote':recordVote(); break;
	case 'show':showVotes(); break;
}

function recordVote() {
	global $database;
	
	$user_rating 	= intval( $_GET['user_rating'] );
	$cid 			= intval( $_GET['cid'] );
	
	if (($user_rating >= 1) and ($user_rating <= 5)) {
		$currip = ( phpversion() <= '4.2.1' ? @getenv( 'REMOTE_ADDR' ) : $_SERVER['REMOTE_ADDR'] );
	
		$query = "SELECT *"
		. "\n FROM #__content_rating"
		. "\n WHERE content_id = " . (int) $cid
		;
		$database->setQuery( $query );
		$votesdb = NULL;
		if ( !( $database->loadObject( $votesdb ) ) ) {
			$query = "INSERT INTO #__content_rating ( content_id, lastip, rating_sum, rating_count )"
			. "\n VALUES ( " . (int) $cid . ", " . $database->Quote( $currip ) . ", " . (int) $user_rating . ", 1 )";
			$database->setQuery( $query );
			$database->query() or die( $database->stderr() );;
		} else {
			if ($currip != ($votesdb->lastip)) {
				$query = "UPDATE #__content_rating"
				. "\n SET rating_count = rating_count + 1, rating_sum = rating_sum + " . (int) $user_rating . ", lastip = " . $database->Quote( $currip )
				. "\n WHERE content_id = " . (int) $cid
				;
				$database->setQuery( $query );
				$database->query() or die( $database->stderr() );
			} else {
				echo 0;
				exit();
			}
		}
		echo 1;
	}
}

?>

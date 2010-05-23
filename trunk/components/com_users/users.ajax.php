<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

class actionsUsers {

	public static function index() {

	}

	public static function uploadavatar() {
		mosMainFrame::addLib('plupload');
		$file = Plupload::upload( 'original_avatar', 'avatars', User::current()->id, false );

		mosMainFrame::addLib('images');
		$avatar = dirname( $file['basename'] );
		Thumbnail::output( $file['basename'], $avatar.'/avatar.png', array( 'width'=>100,'height'=>100 ) );
		Thumbnail::output( $file['basename'], $avatar.'/avatar_25x25.png', array( 'width'=>25,'height'=>25 ) );

		echo json_encode( array( 'avatar'=>$file['location'] ) );

	}

	public static function uploadfiles() {
		mosMainFrame::addLib('plupload');
		Plupload::upload();
	}
}

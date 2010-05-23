<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

class userHTML {

	public static function index() {

	}

	/**
	 * Форма регистрации пользователя
	 */
	public static function register( User $user ) {
		require_once 'views/register/default.php';
	}

	public static function after_register() {
		?>Всё прекрасно - хорошо!<?php
	}

	public static function view( User $user ) {
		require_once 'views/view/default.php';
	}

	public static function edit( User $user ) {
		require_once 'views/edit/default.php';
	}

        public static function uploadform( $user ){
            require_once 'views/uploadform/default.php';
        }

        public static function files( array $files ){
            require_once 'views/files/default.php';
        }

}
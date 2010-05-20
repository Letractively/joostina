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

class attached {

	public $id;
	public $created_at;
	public $user_id;
	public $file_ext;
	public $file_mime;
	public $file_size;


	public function  __construct() {
		$this->mosDBTable('#__attached', 'id');
	}

	public static function upload(){
		mosMainFrame::addLib('files');
		
	}

}

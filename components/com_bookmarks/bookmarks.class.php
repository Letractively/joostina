<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
 * Модель Bookmarks - пользовательские закладки
 */
class Bookmarks extends mosDBTable {
	public $id;
	public $user_id;
	public $obj_id;
	public $obj_option;
	public $obj_task;
	public $created_at;

	function __construct() {
		$this->mosDBTable('#__bookmarks', 'id');
	}

	/**
	 * Добавление элемента в закладки
	 * @param string $option - название компонента
	 * @param integer $id - идентификатор элемента компонента
	 * @param string $task - задача компонента
	 * @return boolean or error obj - результат выполнения вставки или объект с данными о ошибке
	 */
	public static function add( $option, $id, $task='' ) {
		$bookmarks = new self;
		$bookmarks->user_id = User::current()->id;
		$bookmarks->obj_id = $id;
		$bookmarks->obj_option = $option;
		$bookmarks->obj_task = $task;

		if($bookmarks->find()){
			return json_encode( array('error'=>'Такая закладка уже есть') );
		}

		$bookmarks->created_at = _CURRENT_SERVER_TIME;

		return $bookmarks->store() ? json_encode( array('message'=>'Отлично, новая закладка добавлена!') ) : json_encode( array('error'=>'Упс, закладка уже есть, или у нас проблемы...') );
	}

	public static function addlink( $obj ) {
		return sprintf('<button class="to_bookmarks" obj_option="%s" obj_id="%s">в закладки!</button>', get_class($obj), $obj->id ) ;
	}

}
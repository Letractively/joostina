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

/*
 * Класс формирования представлений
*/
class commentsHTML {

	/**
	 * Вывод списка комментариев для заданного объекта
	 * @param array $comments_list массив объектов комментариев
	 * @param DooPager $pagenav объект постраничной навигации
	 */
	public static function lists( array $comments_list) {
		require_once 'views/comments/default.php';
	}

	/**
	 * Форма добавления комментария
	 */
	public static function addform() {
		global $my;
		require_once 'views/form/default.php';
	}

	/**
	 * Пагинация
	 */
	public static function pagination() {
		?><div class="pagenav comments_pagenav"></div><?php
	}
}

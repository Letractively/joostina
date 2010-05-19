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

require_once ($mainframe->getPath('front_html'));
require_once ($mainframe->getPath('class'));

class actionsComments {

	/**
	 * Вывод списка комментариев
	 */
	public static function comments_first_load( $option, $id, $page, $task ) {

		$comments = new Comments;
		$comments->obj_option = mosGetParam($_GET, 'obj_option', '');
		$comments->obj_id = (int) mosGetParam($_GET, 'obj_id', '');

		//Определяем общее количество комментариев
		$comments_count = $comments->count( 'WHERE obj_option = \''.$comments->obj_option.'\' AND obj_id='.$comments->obj_id );

		//первая страница
		$page = 1;
		//Подключаем библиотеку ajax-пагинации
		mosMainFrame::addLib('ajaxpager');
		$pager = new AjaxPager;
		$pager->first_load ( $into = 'comments_list',
				$callback = array(
				'option'=>'com_comments',
				'task'=>'get_comments',
				'obj_option'=>$comments->obj_option,
				'obj_id'=>$comments->obj_id
				),
				$comments_count, (int)mosGetParam($_GET, 'limit', 10), (int)mosGetParam($_GET, 'display', 5), 'comments_pagenav');

		$pager->ajaxPaginate($page);

		$comments_list = $comments->get_comments($pager->offset, $pager->limit);

		CommentsHTML::addform();

		if($comments_list) {
			//Область с пагинацией нам необходимо вывести всего один раз,
			//поэтому исключаем её из шаблона
			//Выводим пагинацию
			CommentsHTML::pagination ($pager);

			//Выводим список комментариев
			CommentsHTML::lists($comments_list);
		}
	}

	public static function get_comments( $option, $id, $page, $task ) {

		$comments = new Comments;
		$comments->obj_option = mosGetParam($_GET, 'obj_option', '');
		$comments->obj_id = mosGetParam($_GET, 'obj_id', '');

		//Подключаем библиотеку ajax-пагинации
		mosMainFrame::addLib('ajaxpager');

		$pager = new AjaxPager;
		$pager->other_load($_GET);

		$comments_list = $comments->get_comments($pager->offset, $pager->limit);

		//Выводим список комментариев
		CommentsHTML::lists($comments_list);
	}

	/**
	 * Добавление комментария
	 */
	public static function add_comment( $option, $task, $obj_id ) {
		global $my;

		$comment_arr = array();

		/*
		if( !$my->id ) {
			$comment_arr['error'] = '<div>Комментарии могут оставлять только авторизованные пользователи</div>';
			echo json_encode($comment_arr);
			return false;
		}
		*/
		mosMainFrame::addLib( 'text' );

		$comment = new Comments;
		$comment->obj_option = mosGetParam($_POST, 'obj_option', '');
		$comment->obj_id = (int) mosGetParam($_POST, 'obj_id', '');
		$comment->comment_text = mosGetParam($_POST, 'comment_text', '');
		$comment->comment_text = Text::word_limiter( Text::strip_tags_smart( $comment->comment_text ), 60 );
		$comment->user_id = $my->id;
		$comment->user_name = $my->id ? $my->username : _GUEST_USER;
		$comment->created_at = _CURRENT_SERVER_TIME;
		$comment->state = 1;

		if( trim($comment->comment_text == '' )) {
			$comment_arr['error'] = '<div>Введите текст комментария</div>';
			echo json_encode($comment_arr);
			return false;
		}
		else {
			$comment->check();
			$comment->store();
			echo json_encode($comment_arr);
		}

		return false;

	}

	/**
	 * Удаление комментария
	 */
	public static function del_comment( $option, $task, $id ) {
		global $my;

		$comment_arr = array();

		if(!$my->admin) {
			$comment_arr['error'] = '<div>Это могут только админы!</div>';
			echo json_encode($comment_arr);
			return false;
		}

		$comment = new Comments;

		if(!$comment->load( (int) mosGetParam($_GET, 'id', ''))) {
			$comment_arr['error'] = '<div>Нет такого комментария</div>';
			echo json_encode($comment_arr);
			return false;
		}
		else {
			$comment->delete();
			echo json_encode($comment_arr);
		}

		return false;
	}
}
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

class actionsUsers {

	/**
	 * Главная страница - список пользователей
	 * @param string $option - название текущего компонента
	 * @param integer $page - номер страницы
	 */
	public static function index($option, $id ,$page, $task  ) {
		// на главной странице постраничка - это id параметр
		$page = $id;

		$obj = new User;
		$obj_count = $obj->count('WHERE block=0');

		mosMainFrame::addLib('pager');
		$pager = new Pager(sefRelToAbs('index.php?option=com_files'.( $task=='index' ? '' : '&task='.$task )  ,true) , $obj_count, 3, 10);
		$pager->paginate($page);

		// небольшой хак для совместимости с Joostina
		$lo = explode(',', $pager->limit);

		$param = array(
				'select'=>'*',
				'where'=>'block=0',
				'offset'=>$pager->offset,
				'limit'=>$pager->limit,
				'order'=>'id DESC'
		);

		$user_list = $obj->get_list($param);

		_xdump($user_list);

	}

	/**
	 * Авторизация пользователя
	 */
	public static function login() {

		josSpoofCheck(null,1);

		mosMainFrame::getInstance()->login();

		$return	= strval(mosGetParam($_REQUEST,'return',null));
		if($return && !(strpos($return,'com_registration') || strpos($return,'com_login'))) {
			mosRedirect($return);
		} else {
			mosRedirect( JPATH_SITE );
		}
	}

	/**
	 * Разлогинивани епользователя
	 */
	public static function logout() {

		josSpoofCheck(null,1);

		mosMainFrame::getInstance()->logout();

		$return	= strval(mosGetParam($_REQUEST,'return',null));
		if($return && !(strpos($return,'com_registration') || strpos($return,'com_login'))) {
			mosRedirect($return);
		} else {
			mosRedirect( JPATH_SITE );
		}
	}

  public static function register(){
    $_POST ? self::save_register() : userHTML::register( new User );
  }

  public static function save_register(){
    $user = new User;
    $user->save($_POST);

   $user->id ? userHTML::after_register() : userHTML::register($user);

  }

}

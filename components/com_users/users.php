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
		$obj_count = $obj->count('WHERE state=1');

		mosMainFrame::addLib('pager');
		$pager = new Pager(sefRelToAbs('index.php?option=com_files'.( $task=='index' ? '' : '&task='.$task )  ,true) , $obj_count, 3, 10);
		$pager->paginate($page);

		// небольшой хак для совместимости с Joostina
		$lo = explode(',', $pager->limit);

		$param = array(
				'select'=>'*',
				'where'=>'state=1',
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

	public static function register() {
		$_POST ? self::save_register() : userHTML::register( new User );
	}

	private static function save_register() {
		$user = new User;
		$user->save($_POST);

		$user->id ? userHTML::after_register() : userHTML::register($user);

	}
	
	public static function view( $option, $id ) {
		$user = new User;
		$user->load($id);

		$user->id ? null : mosRedirect( JPATH_SITE, 'Такого пользователя у нас совсем нет' );

		userHTML::view($user);
	}

	public static function edit() {
		$my = User::current();

		$user = new User;
		$user->load( $my->id );

		$user->id ? null : mosRedirect( JPATH_SITE, 'Такого пользователя у нас совсем нет' );

		$_POST ? self::save_profile($user) : userHTML::edit($user);
	}

	private static function save_profile( $user ){

		josSpoofCheck();

		$user->email = mosGetParam($_POST, 'email', $user->email);
		$user->save();
	}

	public static function file(){

		mosMainFrame::addLib('files');

		$pach = JPATH_BASE.'/tmp/';

		$file = new File(0755);
		$uploadfile = $file->upload($pach, 'file');

		echo $f = $pach.$uploadfile;
echo '<br />';
		echo $file->mime_content_type( $f );
echo '<br />';
echo filesize($f);


		self::edit();

	}

}

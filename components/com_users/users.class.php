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

class User extends mosDBTable {

	public $id;
	public $username;
	public $email;
	public $openid;
	public $password;
	public $state;
	public $gid;
	public $groupname;
	public $registerDate;
	public $lastvisitDate;
	public $activation;
	public $bad_auth_count;

	function __construct() {
		$this->mosDBTable('#__users', 'id');
	}

	public function get_fieldinfo() {
		return array(
				'id' => array(
						'name' => 'ID',
						'editable' => false,
						'sortable' => false,
						'in_admintable' => true,
						'html_table_element' => 'value',
						'html_table_element_param' => array(
								'width' => '20px',
								'align' => 'center'
						)
				),
				'username' => array(
						'name' => 'Логин',
						'editable' => true,
						'sortable' => true,
						'in_admintable' => true,
						'html_edit_element' => 'edit',
						'html_table_element' => 'editlink',
				),
				'state' => array(
						'name' => 'Разрешен',
						'editable' => true,
						'sortable' => true,
						'in_admintable' => true,
						'editlink' => true,
						'html_edit_element' => 'checkbox',
						'html_table_element' => 'state_box',
						'html_edit_element_param' => array(
								'text' => 'Разрешён / Активирован',
						),
						'html_table_element' => 'statuschanger',
						'html_table_element_param' => array(
								'statuses' => array(
										0 => 'Разрешён',
										1 => 'Заблокирован',
								),
								'images' => array(
										0 => 'publish_g.png',
										1 => 'publish_x.png',
								),
								'align' => 'center',
								'class' => 'td-state-joiadmin',
						)
				),
				'email' => array(
						'name' => 'email адрес',
						'editable' => true,
						'in_admintable' => true,
						'html_edit_element' => 'edit',
						'html_table_element' => 'value',
				),
				'openid' => array(
						'name' => 'Адрес OpenID',
						'editable' => true,
						'in_admintable' => true,
						'html_edit_element' => 'edit',
						'html_table_element' => 'value',
				),
				'password' => array(
						'name' => 'Пароль',
						'editable' => true,
						'in_admintable' => true,
						'html_edit_element' => 'edit',
						'html_table_element' => 'value',
				),
				'gid' => array(
						'name' => 'Группа2',
						'editable' => true,
						'sortable' => true,
						'in_admintable' => true,
						'html_edit_element' => 'option',
						'html_edit_element_param' => array(
								'call_from' => 'mosUser::get_usergroup'
						),
						'html_table_element' => 'one_from_array',
						'html_table_element_param' => array(
								'call_from' => 'mosUser::get_usergroup'
						),
				),
				'registerDate' => array(
						'name' => 'Дата регистрации',
						'editable' => true,
						'in_admintable' => true,
						'html_edit_element' => 'edit',
						'html_table_element' => 'value',
				),
				'lastvisitDate' => array(
						'name' => 'Последнее посещение',
						'editable' => true,
						'sortable'=>true,
						'in_admintable' => true,
						'html_edit_element' => 'edit',
						'html_table_element' => 'value',
				),
				'activation' => array(
						'name' => 'Код активации',
						'editable' => true,
						'in_admintable' => true,
						'html_edit_element' => 'edit',
						'html_table_element' => 'value',
				),
		);
	}

	public function get_tableinfo() {
		return array(
				'header_list' => 'Пользователи',
				'header_new' => 'Создание пользователя',
				'header_edit' => 'Редактирование данных пользователя'
		);
	}

	public static function get_usergroup( $gid = false ) {
		$games = new Usergroup;
		$groups = $games->get_selector(array('key' => 'id', 'value' => 'title'), array('select' => 'id, title'));
		return $gid ? $groups[$gid] : $groups;
	}

	function check() {

		if (trim($this->username) == '') {
			$this->_error = addslashes(_REGWARN_USERNAME);
			return false;
		}

		$username = $this->username;
		if (Jstring::strlen($username) > 30) {
			$this->_error = 'Логин не должен быть длинее 30 символов';
			return false;
		}

		if ((trim($this->email == "")) || ( preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $this->email) == false)) {
			$this->_error = addslashes(_REGWARN_MAIL);
			return false;
		}

		$query = "SELECT id FROM #__users WHERE username = " . $this->_db->Quote($this->username) . " AND id != " . (int) $this->id;
		$xid = $this->_db->setQuery($query)->loadResult();
		if ($xid && $xid != $this->id) {
			$this->_error = addslashes(_REGWARN_INUSE);
			return false;
		}

		$query = "SELECT id FROM #__users WHERE email = " . $this->_db->Quote($this->email) . " AND id != " . (int) $this->id;
		$xid = $this->_db->setQuery($query)->loadResult();
		if ($xid && $xid != $this->id) {
			$this->_error = addslashes(_REGWARN_EMAIL_INUSE);
			return false;
		}

		if( !$this->id && $this->password != mosgetParam( $_POST, 'password2' ) ) {
			$this->_error = 'А пароли то - разные!';
			return false;
		}

		return true;
	}

	function before_store() {
		if (!$this->id) {
			$salt = mosMakePassword(16);
			$crypt = md5($this->password . $salt);
			$this->password = $crypt . ':' . $salt;
			$this->registerDate = _CURRENT_SERVER_TIME;
		}
		$this->groupname = self::get_usergroup( $this->gid );
	}

	public static function avatar($user) {

	}

	function get_link($user) {
		$url = 'index.php?option=com_users&task=user&user=' . sprintf('%s:%s', $user->id, $user->username);
		return sefRelToAbs($url);
	}

	/**
	 * Получение дополнительных данных пользователя
	 */
	function extra($uid=null) {
		$uid = ($uid) ? $uid : $this->id;

		$sql = "SELECT * FROM #__user_extra WHERE user_id = $uid";
		$r = null;
		$this->_db->setQuery($sql)->loadObject($r);
		return $r;
	}

	function get_gender($user, $params = null) {

		switch ($user->user_extra->gender) {
			case 'female':
				$gender = _USERS_FEMALE_S;
				break;

			case 'male':
				$gender = _USERS_MALE_S;
				break;

			case 'no_gender':
			default:
				$gender = _GENDER_NONE;
				break;
		}

		if ($params->get('gender')==1 || ! $params) {
			return $gender;
		} else {
			$gender = '<img alt="" title="' . $gender . '" src="' . JPATH_SITE . '/images/system/' . $user->extra->gender . '.png" />';
		}
		return $gender;
	}

	function get_birthdate($user, $params = null) {
		mosMainFrame::addLib('text');
		mosMainFrame::addLib('datetime');

		if ($params->get('show_birthdate')==1) {
			return mosFormatDate($user->user_extra->birthdate, '%d-%m-%Y', 0);
		} else {
			$delta = DateAndTime::getDelta(DateAndTime::mysql_to_unix($user->user_extra->birthdate), DateAndTime::mysql_to_unix(_CURRENT_SERVER_TIME));
			$age = $delta['year'];
			return $age . ' ' . Text::_declension($age, array(_YEAR, _YEAR_, _YEARS));
		}
	}

	public static function current(){
		global $my;
		return $my;
	}

}

/* расширенная информация о пользователе */

class UserExtra extends mosDBTable {

	public $user_id;
	public $gender;
	public $about;
	public $location;
	public $url;
	public $icq;
	public $skype;
	public $jabber;
	public $msn;
	public $yahoo;
	public $phone;
	public $fax;
	public $mobil;
	public $birthdate;

	function __construct() {
		$this->mosDBTable('#__user_extra', 'user_id');
	}

	function insert($id ) {
		$this->user_id = $id;
		return $this->_db->insertObject('#__user_extra', $this, 'user_id');
	}

}

class mosSession extends mosDBTable {

	public $session_id = null;
	public $time = null;
	public $userid = null;
	public $groupname = null;
	public $username = null;
	public $gid = null;
	public $guest = null;
	public $_session_cookie = null;

	function mosSession() {
		$this->mosDBTable('#__session', 'session_id');
	}

	function get($key, $default = null) {
		return mosGetParam($_SESSION, $key, $default);
	}

	function set($key, $value) {
		$_SESSION[$key] = $value;
		return $value;
	}

	function setFromRequest($key, $varName, $default = null) {
		if (isset($_REQUEST[$varName])) {
			return mosSession::set($key, $_REQUEST[$varName]);
		} elseif (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return mosSession::set($key, $default);
		}
	}

	function insert() {
		$ret = $this->_db->insertObject($this->_tbl, $this);
		if (!$ret) {
			$this->_error = strtolower(get_class($this)) . "::store failed <br />" . $this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}

	function update($updateNulls = false) {
		$ret = $this->_db->updateObject($this->_tbl, $this, 'session_id', $updateNulls);
		if (!$ret) {
			$this->_error = strtolower(get_class($this)) . "::update error <br />" . $this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}

	function generateId() {
		$failsafe = 20;
		$randnum = 0;
		while ($failsafe--) {
			$randnum = md5(uniqid(microtime(), 1));
			$new_session_id = mosMainFrame::sessionCookieValue($randnum);
			if ($randnum != '') {
				$query = "SELECT $this->_tbl_key FROM $this->_tbl WHERE $this->_tbl_key = " . $this->_db->Quote($new_session_id);
				$this->_db->setQuery($query);
				if (!$result = $this->_db->query()) {
					die($this->_db->stderr(true));
				}
				if ($this->_db->getNumRows($result) == 0) {
					break;
				}
			}
		}
		$this->_session_cookie = $randnum;
		$this->session_id = $new_session_id;
	}

	function getCookie() {
		return $this->_session_cookie;
	}

	function purge($inc = 1800, $and = '', $lifetime='') {

		if ($inc == 'core') {
			$past_logged = time() - $lifetime;
			$query = "DELETE FROM $this->_tbl WHERE time < '" . (int) $past_logged . "'";
		} else {
			// kept for backward compatability
			$past = time() - $inc;
			$query = "DELETE FROM $this->_tbl WHERE ( time < '" . (int) $past . "' )" . $and;
		}
		return $this->_db->setQuery($query)->query();
	}

}

class Usergroup extends mosDBTable {
	public $id;
	public $parent_id;
	public $title;

	function  __construct() {
		$this->mosDBTable('#__user_groups','id');
	}

}

class mosUser extends User {

}


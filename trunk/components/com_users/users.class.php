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

class mosUser extends mosDBTable {
	public $id;
	public $name;
	public $username;
	public $email;
	public $password;
	public $usertype;
	public $block;
	public $sendEmail;
	public $gid;
	public $registerDate;
	public $lastvisitDate;
	public $activation;
	public $params;
	public $avatar;

	function  __construct() {
		$this->mosDBTable('#__users','id');
	}

	function check() {

		// Validate user information
		if(trim($this->name) == '') {
			$this->_error = addslashes(_REGWARN_NAME);
			return false;
		}

		if(trim($this->username) == '') {
			$this->_error = addslashes(_REGWARN_USERNAME);
			return false;
		}

		// check that username is not greater than 25 characters
		$username = $this->username;
		if(strlen($username) > 25) {
			$this->username = substr($username,0,25);
		}

		// check that password is not greater than 50 characters
		$password = $this->password;
		if(strlen($password) > 50) {
			$this->password = substr($password,0,50);
		}

		if(eregi("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]",$this->username) || strlen($this->username) <3) {
			$this->_error = sprintf(addslashes(_VALID_AZ09),addslashes(_PROMPT_USERNAME),2);
			return false;
		}

		if((trim($this->email == "")) || (preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/",$this->email) == false)) {
			$this->_error = addslashes(_REGWARN_MAIL);
			return false;
		}

		// check for existing username
		$query = "SELECT id FROM #__users WHERE username = ".$this->_db->Quote($this->username)." AND id != ".(int)$this->id;
		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());
		if($xid && $xid != intval($this->id)) {
			$this->_error = addslashes(_REGWARN_INUSE);
			return false;
		}

		if(Jconfig::getInstance()->config_uniquemail) {
			// check for existing email
			$query = "SELECT id FROM #__users WHERE email = ".$this->_db->Quote($this->email)." AND id != ".(int)$this->id;
			$this->_db->setQuery($query);
			$xid = intval($this->_db->loadResult());
			if($xid && $xid != intval($this->id)) {
				$this->_error = addslashes(_REGWARN_EMAIL_INUSE);
				return false;
			}
		}

		return true;
	}

	public static function avatar($user) {

	}

	function get_link($user) {
		$url = 'index.php?option=com_users&task=user&user='.sprintf('%s:%s',$user->id,$user->username);
		return sefRelToAbs($url);
	}


	/**
	 * Получение дополнительных данных пользователя
	 */
	function extra($uid=null) {
		$uid = ($uid) ? $uid : $this->id;

		$sql = "SELECT * FROM #__user_extra WHERE user_id = $uid";
		$r = null;
		$this->_db->setQuery( $sql )->loadObject($r);
		return $r;
	}


	function get_gender($user, $params = null) {

		switch($user->user_extra->gender) {
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

		if($params->get('gender')==1 || !$params) {
			return $gender;
		}

		else {
			$gender = '<img alt="" title="'.$gender.'" src="'.JPATH_SITE.'/images/system/'.$user->extra->gender.'.png" />';
		}
		return $gender;
	}

	function get_birthdate($user, $params = null) {
		mosMainFrame::addLib('text');
		mosMainFrame::addLib('datetime');

		if($params->get('show_birthdate')==1) {
			return mosFormatDate($user->user_extra->birthdate, '%d-%m-%Y', 0);
		}else {
			$delta = DateAndTime::getDelta(DateAndTime::mysql_to_unix($user->user_extra->birthdate), DateAndTime::mysql_to_unix(_CURRENT_SERVER_TIME));
			$age = $delta['year'];
			return $age.' '.Text::_declension($age ,array(_YEAR, _YEAR_, _YEARS));
		}

	}
}

/* расширенная информация о пользователе */
class userUserExtra extends mosDBTable {
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

	function userUsersExtra(  ) {
		$this->mosDBTable('#__user_extra','user_id');
	}
	function insert( $id ) {
		$this->user_id = $id;
		return $this->_db->insertObject('#__user_extra', $this, 'user_id');
	}
}

class mosSession extends mosDBTable {
	public $session_id = null;
	public $time = null;
	public $userid = null;
	public $usertype = null;
	public $username = null;
	public $gid = null;
	public $guest = null;
	public $_session_cookie = null;

	function mosSession() {
		$this->mosDBTable('#__session','session_id');
	}

	function get($key,$default = null) {
		return mosGetParam($_SESSION,$key,$default);
	}

	function set($key,$value) {
		$_SESSION[$key] = $value;
		return $value;
	}

	function setFromRequest($key,$varName,$default = null) {
		if(isset($_REQUEST[$varName])) {
			return mosSession::set($key,$_REQUEST[$varName]);
		} else
		if(isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return mosSession::set($key,$default);
		}
	}

	function insert() {
		$ret = $this->_db->insertObject($this->_tbl,$this);
		if(!$ret) {
			$this->_error = strtolower(get_class($this))."::store failed <br />".$this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}

	function update($updateNulls = false) {
		$ret = $this->_db->updateObject($this->_tbl,$this,'session_id',$updateNulls);
		if(!$ret) {
			$this->_error = strtolower(get_class($this))."::update error <br />".$this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}

	function generateId() {
		$failsafe = 20;
		$randnum = 0;
		while($failsafe--) {
			$randnum = md5(uniqid(microtime(),1));
			$new_session_id = mosMainFrame::sessionCookieValue($randnum);
			if($randnum != '') {
				$query = "SELECT $this->_tbl_key FROM $this->_tbl WHERE $this->_tbl_key = ".$this->_db->Quote($new_session_id);
				$this->_db->setQuery($query);
				if(!$result = $this->_db->query()) {
					die($this->_db->stderr(true));
				}
				if($this->_db->getNumRows($result) == 0) {
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

	function purge($inc = 1800,$and = '',$lifetime='') {

		if($inc == 'core') {
			$past_logged = time() - $lifetime;
			$query = "DELETE FROM $this->_tbl WHERE time < '".(int)$past_logged."'";
		} else {
			// kept for backward compatability
			$past = time() - $inc;
			$query = "DELETE FROM $this->_tbl WHERE ( time < '".(int)$past."' )".$and;
		}
		$this->_db->setQuery($query);

		return $this->_db->query();
	}
}
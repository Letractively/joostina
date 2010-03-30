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

//Europe/Moscow // GMT0
function_exists('date_default_timezone_set') ? date_default_timezone_set('GMT0') : null;

// каталог администратора
DEFINE('JADMIN_BASE','administrator');
// параметр активации отладки
define('JDEBUG', (bool)$mosConfig_debug );
// формат даты
DEFINE('_CURRENT_SERVER_TIME_FORMAT','%Y-%m-%d %H:%M:%S');
// текущее время сервера
DEFINE('_CURRENT_SERVER_TIME',date('Y-m-d H:i:s',time()));
// схемы не http/https протоколов
DEFINE('_URL_SCHEMES','data:, file:, ftp:, gopher:, imap:, ldap:, mailto:, news:, nntp:, telnet:, javascript:, irc:, mms:');
// безоговорочное использование компонента контента
define('_USE_COM_CONTENT', true );

// языковые константы
DEFINE('_ISO2','utf-8');
DEFINE('_ISO','charset=UTF-8');

// пробуем устанавить более удобный режим работы
@set_magic_quotes_runtime(0);

// установка режима отображения ошибок
($mosConfig_error_reporting == 0) ? error_reporting(0) : error_reporting($mosConfig_error_reporting);

/* библиотека отладчика */
mosMainFrame::addLib('debug');
/* библиотека для работы с юникодом */
mosMainFrame::addLib('utf8');
/* библиотека фильтрации данных */
mosMainFrame::addLib('inputfilter');
/* библиотека работы с базой данных */
mosMainFrame::addLib('database');
/* класс оформления */
mosMainFrame::addClass('mosHTML');
/* класс дополнительного оформления */
mosMainFrame::addClass('mosCommonHTML');
/* класс парсинга параметров и работы с XML */
mosMainFrame::addClass('parameters');
/* класс - свалка админ меню */
mosMainFrame::addClass('mosAdminMenus');

/* файл данных версии */
require_once (JPATH_BASE.'/includes/version.php');

// TODO запретить к 1.3.2!!!
//$database = database::getInstance();

/* класс работы с правами пользователей */
//mosMainFrame::addLib('gacl');
// TODO запретить к 1.3.2!!!
//$acl = gacl::getInstance();

// TODO убрать к 1.3.3 корректировка работы с данными полученными от сервера
/*
if(isset($_SERVER['REQUEST_URI'])) {
	$request_uri = $_SERVER['REQUEST_URI'];
} else {
	$request_uri = $_SERVER['SCRIPT_NAME'];
	if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
		$request_uri .= '?'.$_SERVER['QUERY_STRING'];
	}
}
$_SERVER['REQUEST_URI'] = $request_uri;
unset($request_uri);
*/

/**
 * Joostina! Mainframe class
 *
 * Provide many supporting API functions
 * @package Joostina
 */
class mosMainFrame {

	private static $_instance;
	/**
	 @var database Internal database class pointer*/
	public $_db = null;
	/**
	 @var object An object of configuration variables*/
	//private $_config = null;
	public $config = null;
	/**
	 @var object An object of path variables*/
	private $_path = null;
	/**
	 @var mosSession The current session*/
	private $_session = null;
	/**
	 @var string The current template*/
	private $_template = null;
	/**
	 @var array An array to hold global user state within a session*/
	private $_userstate = null;
	/**
	 @var array An array of page meta information*/
	private $_head = null;
	/**
	 @var string Custom html string to append to the pathway*/
	private $_custom_pathway = null;
	/**
	 @var boolean True if in the admin client*/
	private $_isAdmin = false;
	/**
	 * флаг визуального редактора
	 */
	public $allow_wysiwyg = 0;
	/**
	 @var массив данных выводящися в нижней части страницы */
	protected $_footer = null;
	/**
	 * системное сообщение
	 */
	protected $mosmsg = '';
	/**
	 * текущий язык
	 */
	private $lang = null;

	public static $is_admin = false;

	/**
	 * Заглушка для запрета клонирования объекта
	 */
	private function __clone() {

	}

	/**
	 * Инициализация ядра
	 * @param boolen $isAdmin - инициализация в пространстве панели управления
	 */
	function __construct($isAdmin = false) {
		// объект конфигурации системы
		$this->config = Jconfig::getInstance();
		// объект работы с базой данных
		$this->_db = database::getInstance();

		if(!$isAdmin) {
			$current = $this->get_option();
			$this->option = $option = $current['option'];
			$this->Itemid = $current['Itemid'];
			//unset($current);
			$this->getCfg('components_access') ? $this->check_option($option): null;
			$this->_head = array();
			$this->_head['title'] = $this->getCfg('sitename');
			$this->_head['meta'] = array();
			$this->_head['custom'] = array();
		}else {// для панели управления работаем с меню напрямую
			$option = strval(strtolower(mosGetParam($_REQUEST,'option')));
			// указываем параметр работы в админ-панели унапрямую
			self::$is_admin = true;
		}

		$this->setTemplate($isAdmin);
		$this->_setAdminPaths($option,JPATH_BASE);

		$this->_isAdmin = (boolean)$isAdmin;

		if(isset($_SESSION['session_userstate'])) {
			$this->_userstate = &$_SESSION['session_userstate'];
		} else {
			$this->_userstate = null;
		}
	}


	/**
	 * Получение прямой ссылки на объект ядра
	 * @param boolen $isAdmin - инициализация ядра в пространстве панели управления
	 * @return mosMainFrame - объект ядра
	 */
	public static function getInstance($isAdmin = false) {

		JDEBUG ? jd_inc('mosMainFrame::getInstance()') : null;
/* ОТЛАДКА
		if(JDEBUG) {
			$d = debug_backtrace();
			jd_log( 'mosMainFrame::getInstance  '.$d[0]['file'].'::'.$d[0]['line'] );
		}
*/
		if (self::$_instance === NULL) {
			self::$_instance = new self($isAdmin);
		}

		return self::$_instance;
	}

	// подключение представление дял компонентов панели управления
	public function adminView($target) {
		global $option;

		$default = 'administrator'.DS.'components'.DS.$option.DS.'view'.DS.$target.'.php';
		$from_template = 'administrator'.DS.'templates'.DS.JTEMPLATE.DS.'html'.DS.$option.DS.$target.'.php';

		if(is_file($return = JPATH_BASE.DS.$from_template)) {
			return $return;
		}elseif(is_file($return = JPATH_BASE.DS.$default)) {
			return $return;
		}else {
			return false;
		}
	}

	/**
	 * @param string The name of the property
	 * @param mixed The value of the property to set
	 */
	function set($property,$value = null) {
		$this->$property = $value;
	}

	/**
	 * @param string The name of the property
	 * @param mixed  The default value
	 * @return mixed The value of the property
	 */
	function get($property,$default = null) {
		if(isset($this->$property)) {
			return $this->$property;
		} else {
			return $default;
		}
	}

	// получение объекта базы данных из текущего объекта
	public function getDBO() {
		return $this->_db;
	}

	/**
	 * Подключение библиотеки
	 * @param string $lib Название библиотеки. Может быть сформировано как: `lib_name`, `lib_name/lib_name.php`, `lib_name.php`
	 * @param string $dir Директория библиотеки. Необязательный параметр. По умолчанию, поиск файла осуществляется в 'includes/libraries'
	 */
	public static function addLib($lib, $dir = null) {
		$dir = $dir ? $dir : 'includes/libraries';

		$file_lib = JPATH_BASE.DS.$dir.DS.$lib.DS.$lib.'.php';
		is_file($file_lib) ? require_once($file_lib): null;
	}

	/**
	 * Подключение классов
	 * @param string $lib Название библиотеки. Может быть сформировано как: `class_name`, `class_name/class_name.php`, `class_name.php`
	 * @param string $dir Директория библиотеки. Необязательный параметр. По умолчанию, поиск файла осуществляется в 'includes/classes'
	 */
	public static function addClass($class, $dir = null) {
		$dir = $dir ? $dir : 'includes/classes';

		$file_class = JPATH_BASE.DS.$dir.DS.$class.'.class.php';
		is_file($file_class) ? require_once($file_class): null;
	}

	/**
	 *
	 * @global string $mosConfig_lang
	 * @param <type> $name
	 * @param <type> $mosConfig_lang
	 * @return <type>
	 */
	public function getLangFile($name = '',$mosConfig_lang='') {
		if(empty($mosConfig_lang)) {
			global $mosConfig_lang;
		}

		$lang = $mosConfig_lang;

		if(!$name) {
			return JPATH_BASE.DS.'language'.DS.$lang.DS.'system.php';
		}else {
			$file = $name;
		}

		if( mosMainFrame::$is_admin == true ) {
			if(is_file(JPATH_BASE.DS.'language'.DS.$lang.DS.'administrator'.DS.$file.'.php')) {
				return JPATH_BASE.DS.'language'.DS.$lang.DS.'administrator'.DS.$file.'.php';
			}else {
				if(is_file(JPATH_BASE.DS.'language'.DS.$lang.DS.'frontend'.DS.$file.'.php')) {
					return JPATH_BASE.DS.'language'.DS.$lang.DS.'frontend'.DS.$file.'.php';
				}
			}
		}else {
			if(is_file(JPATH_BASE.DS.'language'.DS.$lang.DS.'frontend'.DS.$file.'.php')) {
				return JPATH_BASE.DS.'language'.DS.$lang.DS.'frontend'.DS.$file.'.php';
			}
		}

		return null;
	}


	/**
	 *
	 * @param <type> $title
	 * @param <type> $pageparams
	 */
	function setPageTitle($title = null,$pageparams = null) {

		$sitename = $page_title = $this->getCfg('sitename');
		$config_tseparator = $this->getCfg('tseparator');

		if($this->getCfg('pagetitles')) {
			$title = Jstring::trim(strip_tags($title));
			// разделитель названия страницы и сайта
			$tseparator = $config_tseparator ? $config_tseparator : ' - ';
			if($pageparams != null) {
				// название страницы указанное в настройках пункта меню или свойствах содержимого
				$pageownname = Jstring::trim( htmlspecialchars( $pageparams->get('page_name') ) );
				$page_title = $pageparams->get('no_site_name') ?
						( $pageownname ? $pageownname : ( $title ? $title : $sitename )) :
						( $this->getCfg('pagetitles_first') ?
						(( $pageownname ? $pageownname : $title ) . $tseparator . $sitename)
						:
						( $this->getCfg('sitename'). $tseparator . ( $pageownname ? $pageownname : $title ))
				);
			} elseif($this->getCfg('pagetitles_first')==1) {
				$pageownname = null;
				$page_title = $title ? $title.$tseparator.$sitename : $sitename;
			}else {
				$pageownname = null;
				$page_title = $title ? $sitename.$tseparator.$title : $sitename;
			}
		}

		// название страницы, не title!
		$this->_head['pagename'] = isset($pageownname) ? $pageownname : $title;

		switch($this->getCfg('pagetitles_first')) {
			case 0:
			case 1:
			default:
				$this->_head['title'] = $page_title;
				break;

			case 2:
				$this->_head['title'] = $sitename;
				break;

			case 3:
				$this->_head['title'] = $pageownname ? $pageownname : $title;
				break;
		}
	}

	/**
	 * @param string The value of the name attibute
	 * @param string The value of the content attibute
	 * @param string Text to display before the tag
	 * @param string Text to display after the tag
	 */
	function addMetaTag($name,$content,$prepend = '',$append = '') {
		$name	= Jstring::trim(htmlspecialchars($name));
		$content  = Jstring::trim(htmlspecialchars($content));
		$prepend = Jstring::trim($prepend);
		$append	 = Jstring::trim($append);
		$this->_head['meta'][] = array($name,$content,$prepend,$append);
	}

	/**
	 * @param string The value of the name attibute
	 * @param string The value of the content attibute to append to the existing
	 * Tags ordered in with Site Keywords and Description first
	 */
	function appendMetaTag($name,$content) {
		$name = Jstring::trim( htmlspecialchars($name) );
		$n = count($this->_head['meta']);
		for($i = 0; $i < $n; $i++) {
			if($this->_head['meta'][$i][0] == $name) {
				$content = Jstring::trim(htmlspecialchars($content));
				if($content != '' & $this->_head['meta'][$i][1] == "") {
					$this->_head['meta'][$i][1] .= ' '.$content;
				};
				return;
			}
		}

		$this->addMetaTag($name,$content);
	}

	/**
	 * @param string The value of the name attibute
	 * @param string The value of the content attibute to append to the existing
	 */
	function prependMetaTag($name,$content) {
		$name = trim(htmlspecialchars($name));
		$n = count($this->_head['meta']);
		for($i = 0; $i < $n; $i++) {
			if($this->_head['meta'][$i][0] == $name) {
				$content = trim(htmlspecialchars($content));
				$this->_head['meta'][$i][1] = $content.$this->_head['meta'][$i][1];
				return;
			}
		}
		$this->addMetaTag($name,$content);
	}

	/**
	 * Расширенные мета-тэги для улучшенного SEO
	 * @param <type> $robots
	 */
	function set_robot_metatag($robots) {
		($robots == 0) ? $this->addMetaTag('robots','index, follow') : null;
		($robots == 1) ? $this->addMetaTag('robots','index, nofollow') : null;
		($robots == 2) ? $this->addMetaTag('robots','noindex, follow') : null;
		($robots == 3) ? $this->addMetaTag('robots','noindex, nofollow') : null;
	}

	/**
	 *
	 * @param <type> $html
	 */
	function addCustomHeadTag($html) {
		$this->_head['custom'][] = trim($html);
	}

	/**
	 *
	 * @param <type> $html
	 */
	function addCustomFooterTag($html) {
		$this->_footer['custom'][] = trim($html);
	}

	/**
	 *
	 * @param <type> $params
	 * @return <type>
	 */
	function getHead($params=array('js'=>1,'css'=>1,'jquery'=>0)) {
		$head = array();
		$head[] = '<title>'.$this->_head['title'].'</title>';

		foreach($this->_head['meta'] as $meta) {
			if($meta[2]) {
				$head[] = $meta[2];
			}
			$head[] = '<meta name="'.$meta[0].'" content="'.$meta[1].'" />';
			if($meta[3]) {
				$head[] = $meta[3];
			}
		}

		foreach($this->_head['custom'] as $html) {
			$head[] = $html;
		}

		if(isset($params['jquery']) && $params['jquery']==1) {
			$head[] = mosCommonHTML::loadJquery(true,true);
		}

		if(isset($params['js']) && $params['js']==1 && isset($this->_head['js']) ) {
			$i = 0;
			foreach($this->_head['js'] as $html) {
				$head[] = $html;
				//unset($this->_head['js'][$i]);
				$i++;
			}
		}

		if(isset($params['css']) && $params['css']==1 && isset($this->_head['css']) ) {
			foreach($this->_head['css'] as $html) {
				$head[] = $html;
			}
		}
		//unset($this->_head);
		return implode("\n",$head)."\n";
	}

	/**
	 *
	 * @param <type> $name
	 * @return <type>
	 */
	public function getHeadData($name) {
		return isset($this->_head[$name]) ? $this->_head[$name] : array();
	}

	/**
	 *
	 * @param <type> $params
	 * @return <type>
	 */
	function getFooter($params=array('fromheader'=>1,'custom'=>0,'js'=>1,'css'=>1)) {
		$footer = array();

		if(isset($params['fromheader']) && $params['fromheader']==1 ) {
			$this->_footer = $this->_head;
		}

		if(isset($params['custom']) && $params['custom']==1 && isset($this->_footer['custom'])) {
			foreach($this->_footer['custom'] as $html) {
				$footer[] = $html;
			}
		}

		if(isset($params['jquery']) && $params['jquery']==1 ) {
			$footer[] = mosCommonHTML::loadJquery(true,true);
		}

		if(isset($params['js']) && $params['js']==1 && isset($this->_footer['js'])) {
			foreach($this->_footer['js'] as $html) {
				$footer[] = $html;
			}
		}

		if(isset($params['css'])  && $params['css']==1 && isset($this->_footer['css']) ) {
			foreach($this->_footer['css'] as $html) {
				$footer[] = $html;
			}
		}
		//unset($this->_footer);
		return implode("\n",$footer)."\n";
	}

	/**
	 * добавление js файлов в шапку или футер страницы
	 * если $footer - скрипт будет добавлен в $mainframe->_footer
	 * возможные значения $footer:
	 * 	'js' - скрипт будет добавлен в $mainfrane->_footer['js'] (первый этап вывода футера)
	 * 	'custom' - скрипт будет добавлен в $mainfrane->_footer['custom'] (второй этап вывода футера)
	 * @param <type> $path
	 * @param <type> $footer
	 * @param <type> $def
	 */
	public function addJS($path, $footer = '', &$def = '') {
		mosMainFrame::addClass('mosHTML');
		$js = mosHTML::js_file($path);
		if($footer) {
			$this->_footer[$footer][] = $js;
		}else {
			$this->_head['js'][] = $js;
		}
	}

	/**
	 * Lобавление css файлов в шапку страницы
	 * @param <type> $path
	 */
	public function addCSS($path) {
		$this->_head['css'][] = '<link type="text/css" rel="stylesheet" href="'. $path .'" />';
	}

	/**
	 * Получение заголовка страницы
	 * @return string
	 */
	function getPageTitle() {
		return $this->_head['title'];
	}

	/**
	 * @return string
	 */
	function getCustomPathWay() {
		return $this->_custom_pathway;
	}

	/**
	 *
	 * @param <type> $html
	 */
	function appendPathWay($html) {
		$this->_custom_pathway[] = $html;
	}

	/**
	 * Gets the value of a user state variable
	 * @param string The name of the variable
	 */
	function getUserState($var_name) {
		return is_array($this->_userstate) ? mosGetParam($this->_userstate,$var_name,null) : null;
	}

	/**
	 * Gets the value of a user state variable
	 * @param string The name of the user state variable
	 * @param string The name of the variable passed in a request
	 * @param string The default value for the variable if not found
	 */
	function getUserStateFromRequest($var_name,$req_name,$var_default = null) {
		if(is_array($this->_userstate)) {
			if(isset($_REQUEST[$req_name])) {
				$this->setUserState($var_name,$_REQUEST[$req_name]);
			} else
			if(!isset($this->_userstate[$var_name])) {
				$this->setUserState($var_name,$var_default);
			}

			$this->_userstate[$var_name] = InputFilter::getInstance()->process($this->_userstate[$var_name]);
			return $this->_userstate[$var_name];
		} else {
			return null;
		}
	}

	/**
	 * Устанавливает переменную в сессию пользователя
	 * @param string названи е переменной
	 * @param string значение переменнной
	 */
	function setUserState($var_name,$var_value) {
		if(is_array($this->_userstate)) {
			$this->_userstate[$var_name] = $var_value;
		}
	}

	/**
	 *
	 * @return <type>
	 */
	function initSession() {
		if($this->getCfg('no_session_front')) return;

		// initailize session variables
		$session = &$this->_session;
		$session = new mosSession($this->_db);
		// purge expired sessions
		(rand(0,2)==1) ? $session->purge('core','',$this->config->config_lifetime) : null;

		// Session Cookie `name`
		$sessionCookieName = mosMainFrame::sessionCookieName();


		// Get Session Cookie `value`
		$sessioncookie = strval(mosGetParam($_COOKIE,$sessionCookieName,null));
		// Session ID / `value`
		$sessionValueCheck = mosMainFrame::sessionCookieValue($sessioncookie);

		// Check if existing session exists in db corresponding to Session cookie `value`
		// extra check added in 1.0.8 to test sessioncookie value is of correct length
		if($sessioncookie && strlen($sessioncookie) == 32 && $sessioncookie != '-' && $session->load($sessionValueCheck)) {
			// update time in session table
			$session->time = time();
			$session->update();
		} else {
			// Remember Me Cookie `name`
			$remCookieName = mosMainFrame::remCookieName_User();

			// test if cookie found
			$cookie_found = false;
			if(isset($_COOKIE[$sessionCookieName]) || isset($_COOKIE[$remCookieName]) || isset($_POST['force_session'])) {
				$cookie_found = true;
			}

			// check if neither remembermecookie or sessioncookie found
			if(!$cookie_found) {
				// create sessioncookie and set it to a test value set to expire on session end
				setcookie($sessionCookieName,'-',false,'/');
			} else {
				// otherwise, sessioncookie was found, but set to test val or the session expired, prepare for session registration and register the session
				$url = strval(mosGetParam($_SERVER,'REQUEST_URI',null));
				// stop sessions being created for requests to syndicated feeds
				if(strpos($url,'option=com_rss') === false && strpos($url,'feed=') === false) {
					$session->guest = 1;
					$session->username = '';
					$session->time = time();
					$session->gid = 0;
					// Generate Session Cookie `value`
					$session->generateId();
					if(!$session->insert()) {
						die($session->getError());
					}
					// create Session Tracking Cookie set to expire on session end
					setcookie($sessionCookieName,$session->getCookie(),false,'/');
				}
			}
			// Cookie used by Remember me functionality
			$remCookieValue = strval(mosGetParam($_COOKIE,$remCookieName,null));

			// test if cookie is correct length
			if(strlen($remCookieValue) > 64) {
				// Separate Values from Remember Me Cookie
				$remUser = substr($remCookieValue,0,32);
				$remPass = substr($remCookieValue,32,32);
				$remID = intval(substr($remCookieValue,64));

				// check if Remember me cookie exists. Login with usercookie info.
				if(strlen($remUser) == 32 && strlen($remPass) == 32) {
					$this->login($remUser,$remPass,1,$remID);
				}
			}
		}
	}

	/**
	 *
	 * @param <type> $option
	 * @param <type> $task
	 * @return <type>
	 */
	function initSessionAdmin($option,$task) {

		$_config = $this->get('config');

		// logout check
		if($option == 'logout') {
			require JPATH_BASE_ADMIN.DS.'logout.php';
			exit();
		}

		// check if session name corresponds to correct format
		if(session_name() != md5(JPATH_SITE)) {
			echo "<script>document.location.href='index.php'</script>\n";
			exit();
		}

		// restore some session variables
		$my = new mosUser($this->_db);
		$my->id = intval(mosGetParam($_SESSION,'session_user_id',''));
		$my->username = strval(mosGetParam($_SESSION,'session_USER',''));
		$my->usertype = strval(mosGetParam($_SESSION,'session_usertype',''));
		$my->gid = intval(mosGetParam($_SESSION,'session_gid',''));
		$my->params = mosGetParam($_SESSION,'session_user_params','');
		$my->bad_auth_count = mosGetParam($_SESSION,'session_bad_auth_count','');

		$session_id = mosGetParam($_SESSION,'session_id','');
		$logintime = mosGetParam($_SESSION,'session_logintime','');

		if($session_id != session_id()) {
			// session id does not correspond to required session format
			mosRedirect(JPATH_SITE.'/'.JADMIN_BASE.'/',_YOU_NEED_TO_AUTH);
			exit();
		}

		// check to see if session id corresponds with correct format
		if($session_id == md5($my->id.$my->username.$my->usertype.$logintime)) {
			// if task action is to `save` or `apply` complete action before doing session checks.
			if($task != 'save' && $task != 'apply') {
				// test for session_life_admin
				if($_config->config_session_life_admin) {
					$session_life_admin = $_config->config_session_life_admin;
				} else {
					$session_life_admin = 1800;
				}
				// если в настройка не указано что сессии админки не уничтожаются - выполняем запрос по очистке сессий
				if($_config->config_admin_autologout==1) {
					// purge expired admin sessions only
					$past = time() - $session_life_admin;
					$query = "DELETE FROM #__session WHERE time < '".(int)$past."' AND guest = 1 AND gid = 0 AND userid <> 0";
					$this->_db->setQuery($query)->query();
				}

				$current_time = time();

				// update session timestamp
				$query = "UPDATE #__session SET time = ".$this->_db->Quote($current_time)." WHERE session_id = ".$this->_db->Quote($session_id);
				$this->_db->setQuery($query);
				$_config->config_admin_autologout==1 ? $this->_db->query() : null;

				// set garbage cleaning timeout
				$this->setSessionGarbageClean();

				// check against db record of session
				$query = "SELECT COUNT( session_id ) FROM #__session WHERE session_id = ".$this->_db->Quote($session_id)." AND username = ".$this->_db->Quote($my->username)."\n AND userid = ".intval($my->id);
				$this->_db->setQuery($query);
				$count = ($_config->config_admin_autologout==1) ? $this->_db->loadResult() : 1;

				// если в таблице
				if($count == 0) {
					$link = null;
					if($_SERVER['QUERY_STRING']) {
						$link = 'index2.php?'.$_SERVER['QUERY_STRING'];
					}

					// check if site designated as a production site
					// for a demo site disallow expired page functionality
					// link must also be a Joomla link to stop malicious redirection
					if($link && strpos($link,'index2.php?option=com_') === 0 && coreVersion::get('SITE') == 1) {
						$now = time();

						$file = $this->getPath('com_xml','com_users');
						$params = new mosParameters($my->params,$file,'component');

						// return to expired page functionality
						$params->set('expired',$link);
						$params->set('expired_time',$now);

						// param handling
						if(is_array($params->toArray())) {
							$txt = array();
							foreach($params->toArray() as $k => $v) {
								$txt[] = "$k=$v";
							}
							$saveparams = implode("\n",$txt);
						}

						$query = "UPDATE #__users SET params = ".$this->_db->Quote($saveparams)." WHERE id = ".(int)$my->id." AND username = ".$this->_db->Quote($my->username)." AND usertype = ".$this->_db->Quote($my->usertype);
						$this->_db->setQuery($query)->query();
					}

					mosRedirect(JPATH_SITE.'/'.JADMIN_BASE.'/',_ADMIN_SESSION_ENDED);

				} else {
					// load variables into session, used to help secure /popups/ functionality
					$_SESSION['option'] = $option;
					$_SESSION['task'] = $task;
				}
			}
		} elseif($session_id == '') {
			// no session_id as user has not attempted to login, or session.auto_start is switched on
			if(ini_get('session.auto_start') || !ini_get('session.use_cookies')) {
				echo "<script>document.location.href='index.php?mosmsg="._YOU_NEED_TO_AUTH_AND_FIX_PHP_INI."'</script>\n";
			} else {
				echo "<script>document.location.href='index.php?mosmsg="._YOU_NEED_TO_AUTH."'</script>\n";
			}
			exit();
		} else {
			// session id does not correspond to required session format
			echo "<script>document.location.href='index.php?mosmsg="._WRONG_USER_SESSION."'</script>\n";
			exit();
		}

		return $my;
	}

	/*
	* Function used to set Session Garbage Cleaning
	* garbage cleaning set at configured session time + 600 seconds
	*/
	function setSessionGarbageClean() {
		/** ensure that funciton is only called once*/
		if(!defined('_JOS_GARBAGECLEAN')) {
			define('_JOS_GARBAGECLEAN',1);

			$garbage_timeout = $this->getCfg('session_life_admin') + 600;
			@ini_set('session.gc_maxlifetime',$garbage_timeout);
		}
	}

	/*
	* Static Function used to generate the Session Cookie Name
	* Added as of 1.0.8
	* Deperciated 1.1
	*/
	function sessionCookieName($site_name = '') {

		if(!$site_name) {
			$site_name = JPATH_SITE;
		}

		if(substr($site_name,0,7) == 'http://') {
			$hash = md5('site'.substr($site_name,7));
		} elseif(substr($site_name,0,8) == 'https://') {
			$hash = md5('site'.substr($site_name,8));
		} else {
			$hash = md5('site'.$site_name);
		}

		return $hash;
	}

	/*
	* Static Function used to generate the Session Cookie Value
	* Added as of 1.0.8
	* Deperciated 1.1
	*/
	public static function sessionCookieValue($id = null) {
		$config = Jconfig::getInstance();
		$type		= $config->config_session_type;
		$browser	= @$_SERVER['HTTP_USER_AGENT'];

		switch($type) {
			case 2:
			// 1.0.0 to 1.0.7 Compatibility
			// lowest level security
				$value = md5($id.$_SERVER['REMOTE_ADDR']);
				break;

			case 1:
			// slightly reduced security - 3rd level IP authentication for those behind IP Proxy
				$remote_addr = explode('.',$_SERVER['REMOTE_ADDR']);
				$ip = $remote_addr[0].'.'.$remote_addr[1].'.'.$remote_addr[2];
				$value = mosHash($id.$ip.$browser);
				break;

			default:
			// Highest security level - new default for 1.0.8 and beyond
				$ip = $_SERVER['REMOTE_ADDR'];
				$value = mosHash($id.$ip.$browser);
				break;
		}

		return $value;
	}

	/*
	* Static Function used to generate the Rememeber Me Cookie Name for Username information
	* Added as of 1.0.8
	* Depreciated 1.1
	*/
	public static function remCookieName_User() {
		return mosHash('remembermecookieusername'.mosMainFrame::sessionCookieName());
	}

	/*
	* Static Function used to generate the Rememeber Me Cookie Name for Password information
	* Added as of 1.0.8
	* Depreciated 1.1
	*/
	function remCookieName_Pass() {
		return mosHash('remembermecookiepassword'.mosMainFrame::sessionCookieName());
	}

	/*
	* Static Function used to generate the Remember Me Cookie Value for Username information
	* Added as of 1.0.8
	* Depreciated 1.1
	*/
	function remCookieValue_User($username) {
		return md5($username.mosHash(@$_SERVER['HTTP_USER_AGENT']));
	}

	/*
	* Static Function used to generate the Remember Me Cookie Value for Password information
	* Added as of 1.0.8
	* Depreciated 1.1
	*/
	function remCookieValue_Pass($passwd) {
		return md5($passwd.mosHash(@$_SERVER['HTTP_USER_AGENT']));
	}

	/**
	 * Функция авторизации пользователя
	 *
	 * Username and encoded password is compare to db entries in the jos_users
	 * table. A successful validation updates the current session record with
	 * the users details.
	 */
	public function login($username = null,$passwd = null,$remember = 0,$userid = null) {

		// если сесии на фронте отключены - прекращаем выполнение процедуры
		if($this->getCfg('no_session_front')) return;

		$return	= strval(mosGetParam($_REQUEST,'return',false));

		$return = $return ? $return : strval(mosGetParam($_SERVER,'HTTP_REFERER',null));

		// подключаем библиотеку работы с правами
		mosMainFrame::addLib('gacl');
		$acl = gacl::getInstance( true );

		$bypost = 0;
		$valid_remember = false;

		// if no username and password passed from function, then function is being called from login module/component
		if(!$username || !$passwd) {
			$username	= stripslashes(strval(mosGetParam($_POST,'username','')));
			$passwd		= stripslashes(strval(mosGetParam($_POST,'passwd','')));

			$bypost = 1;

			// extra check to ensure that Joomla! sessioncookie exists
			if(!$this->_session->session_id) {
				mosErrorAlert(_ALERT_ENABLED);
				return;
			}

			josSpoofCheck(null,1);
		}

		$row = null;
		if(!$username || !$passwd) {
			mosRedirect($return, _LOGIN_INCOMPLETE);
			//mosErrorAlert(_LOGIN_INCOMPLETE . "555" );
			exit();
		} else {
			if($remember && strlen($username) == 32 && $userid) {

				// query used for remember me cookie
				$harden = mosHash(@$_SERVER['HTTP_USER_AGENT']);

				$query = "SELECT id, name, username, password, usertype, block, gid FROM #__users WHERE id = ".(int)$userid;
				$user = null;

				$this->_db->setQuery($query)->loadObject($user);

				list($hash,$salt) = explode(':',$user->password);

				$check_USER = md5($user->username.$harden);
				$check_password = md5($hash.$harden);

				if($check_USER == $username && $check_password == $passwd) {
					$row = $user;
					$valid_remember = true;
				}
			} else {
				// query used for login via login module
				$query = "SELECT id, name, username, password, usertype, block, gid FROM #__users WHERE username = ".$this->_db->Quote($username);

				$this->_db->setQuery($query)->loadObject($row);
			}

			if(is_object($row)) {
				// user blocked from login
				if($row->block == 1) {
					mosRedirect($return, _LOGIN_BLOCKED);
					//mosErrorAlert(_LOGIN_BLOCKED);
					exit();

				}

				if(!$valid_remember) {
					// Conversion to new type
					if((strpos($row->password,':') === false) && $row->password == md5($passwd)) {
						// Old password hash storage but authentic ... lets convert it
						$salt = mosMakePassword(16);
						$crypt = md5($passwd.$salt);
						$row->password = $crypt.':'.$salt;

						// Now lets store it in the database
						$query = 'UPDATE #__users SET password = '.$this->_db->Quote($row->password).' WHERE id = '.(int)$row->id;

						if(!$this->_db->setQuery($query)->query()) {
							echo 'error';
						}

					}
					list($hash,$salt) = explode(':',$row->password);
					$cryptpass = md5($passwd.$salt);

					if($hash != $cryptpass) {
						if($bypost) {
							mosRedirect($return, _LOGIN_INCORRECT);
							//mosErrorAlert(_LOGIN_INCORRECT);
						} else {
							$this->logout();
							mosRedirect('index.php');
						}
						exit();
					}
				}

				// fudge the group stuff
				$grp = $acl->getAroGroup($row->id);
				$row->gid = 1;
				if($acl->is_group_child_of($grp->name,'Registered','ARO') || $acl->is_group_child_of($grp->name,'Public Backend','ARO')) {
					// fudge Authors, Editors, Publishers and Super Administrators into the Special Group
					$row->gid = 2;
				}
				$row->usertype = $grp->name;

				// initialize session data
				$session = &$this->_session;
				$session->guest = 0;
				$session->username = $row->username;
				$session->userid = intval($row->id);
				$session->usertype = $row->usertype;
				$session->gid = intval($row->gid);
				$session->update();

				// check to see if site is a production site
				// allows multiple logins with same user for a demo site
				if(coreVersion::get('SITE')) {
					// delete any old front sessions to stop duplicate sessions
					$query = "DELETE FROM #__session WHERE session_id != ".$this->_db->Quote($session->session_id)." AND username = ".$this->_db->Quote($row->username)." AND userid = ".(int)$row->id." AND gid = ".(int)$row->gid." AND guest = 0";
					$this->_db->setQuery($query)->query();
				}

				// update user visit data
				$currentDate = date("Y-m-d H:i:s");

				$query = "UPDATE #__users SET lastvisitDate = ".$this->_db->Quote($currentDate)." WHERE id = ".(int)$session->userid;

				if(!$this->_db->setQuery($query)->query()) {
					die($this->_db->stderr(true));
				}

				// set remember me cookie if selected
				$remember = strval(mosGetParam($_POST,'remember',''));
				if($remember == 'yes') {
					// cookie lifetime of 365 days
					$lifetime = time() + 365* 24* 60* 60;
					$remCookieName = mosMainFrame::remCookieName_User();
					$remCookieValue = mosMainFrame::remCookieValue_User($row->username).mosMainFrame::remCookieValue_Pass($hash).$row->id;
					setcookie($remCookieName,$remCookieValue,$lifetime,'/');
				}
			} else {
				if($bypost) {
					mosRedirect($return, _LOGIN_INCORRECT);
					//mosErrorAlert(_LOGIN_INCORRECT);
				} else {
					$this->logout();
					mosRedirect('index.php');
				}
				exit();
			}
		}
	}

	/**
	 * Разлогинивание пользователя
	 * Записывает в текущию сесиию гостевые параметры
	 */
	public function logout() {
		$session = &$this->_session;
		$session->guest = 1;
		$session->username = '';
		$session->userid = '';
		$session->usertype = '';
		$session->gid = 0;
		$session->update();
		// kill remember me cookie
		$lifetime = time() - 86400;
		$remCookieName = mosMainFrame::remCookieName_User();
		setcookie($remCookieName,' ',$lifetime,'/');
		@session_destroy();
	}

	/**
	 * @return mosUser возвращает объект пользовательской сессии
	 */
	public function getUser() {
		$user = new mosUser($this->_db);

		if($this->get('config')->config_no_session_front == 1) {
			// параметры id и gid при инициализации объявляются как null - это вредит некоторым компонентам, проинициализируем их в нули
			$user->id = 0;
			$user->gid = 0;
			return $user; // если сессии (авторизация) на фронте отключены - возвращаем пустой объект
		}

		$user->id = intval($this->_session->userid);
		$user->username = $this->_session->username;
		$user->usertype = $this->_session->usertype;
		$user->gid = intval($this->_session->gid);
		if($user->id) {
			$query = "SELECT id, name, email, avatar, block, sendEmail, registerDate, lastvisitDate, activation, params FROM #__users WHERE id = ".(int)$user->id;
			$this->_db->setQuery($query,0,1)->loadObject($my);

			$user->params = $my->params;
			$user->name = $my->name;
			$user->email = $my->email;
			$user->avatar = $my->avatar;
			$user->block = $my->block;
			$user->sendEmail = $my->sendEmail;
			$user->registerDate = $my->registerDate;
			$user->lastvisitDate = $my->lastvisitDate;
			$user->activation = $my->activation;
		}
		return $user;
	}

	/**
	 * @param string The name of the variable (from configuration.php)
	 * @return mixed The value of the configuration variable or null if not found
	 */
	public function getCfg($varname) {
		$varname = 'config_'.$varname;

		$config = $this->get('config');
		return (isset($config->$varname)) ? $config->$varname : null;

	}

	/**  функция определения шаблона, если в панели управления указано что использовать один шаблон - сразу возвращаем его название, функцию не проводим до конца*/
	public function setTemplate($isAdmin = false) {
		// если у нас в настройках указан шаблон и определение идёт не для панели управления - возвращаем название шаблона из глобальной конфигурации
		if(!$isAdmin and $this->getCfg('one_template') != '...') {
			$this->_template = $this->getCfg('one_template');
			return;
		}

		if($isAdmin) {
			if($this->getCfg('admin_template')=='...') {
				$query = 'SELECT template FROM #__templates_menu WHERE client_id = 1 AND menuid = 0';
				$cur_template = $this->_db->setQuery($query)->loadResult();
				$path = JPATH_BASE.DS.JADMIN_BASE.DS.'templates'.DS.$cur_template.DS.'index.php';
				if(!is_file($path)) {
					$cur_template = 'joostfree';
				}
			}else {
				$cur_template = 'joostfree';
			}
		} else {

			$Itemid = intval(mosGetParam($_REQUEST,'Itemid',null));
			$assigned = (!empty($Itemid) ? ' OR menuid = '.(int)$Itemid : '');

			$query = "SELECT template FROM #__templates_menu WHERE client_id = 0 AND ( menuid = 0 $assigned ) ORDER BY menuid DESC";
			$cur_template = $this->_db->setQuery($query,0,1)->loadResult();

			// TemplateChooser Start
			$jos_user_template		= strval(mosGetParam($_COOKIE,'jos_user_template',''));
			$jos_change_template	= strval(mosGetParam($_REQUEST,'jos_change_template',$jos_user_template));
			if($jos_change_template) {
				// clean template name
				$jos_change_template = preg_replace('#\W#','',$jos_change_template);
				if(strlen($jos_change_template) >= 40) {
					$jos_change_template = substr($jos_change_template,0,39);
				}

				// check that template exists in case it was deleted
				if(file_exists(JPATH_BASE.DS.'templates'.DS.$jos_change_template.DS.'index.php')) {
					$lifetime = 60* 10;
					$cur_template = $jos_change_template;
					setcookie('jos_user_template',$jos_change_template,time() + $lifetime);
				} else {
					setcookie('jos_user_template','',time() - 3600);
				}
			}
		}

		$this->_template = $cur_template;
	}

	/**
	 * Получение текущего шаблона
	 * @return string название шаблона
	 */
	public function getTemplate() {
		return $this->_template;
	}

	/**
	 * Установка переменных окружения для путей
	 * @param string $name - название переменной пути
	 * @param string $path  - непосредственно сам путь
	 */
	public function setPath($name, $path) {
		if (is_file($path)) {
			$this->_path->$name = $path;
		}
	}

	/**
	 * Determines the paths for including engine and menu files
	 * @param string The current option used in the url
	 * @param string The base path from which to load the configuration file
	 */
	private function _setAdminPaths($option,$basePath = '.') {
		$option = strtolower($option);

		$this->_path = new stdClass();

		// security check to disable use of `/`, `\\` and `:` in $options variable
		if(strpos($option,'/') !== false || strpos($option,'\\') !== false || strpos($option,':') !== false) {
			mosErrorAlert(_ACCESS_DENIED);
			return;
		}

		$prefix = substr($option,0,4);
		if($prefix != 'com_' && $prefix != 'mod_') {
			// ensure backward compatibility with existing links
			$name = $option;
			$option = 'com_'.$option;
		} else {
			$name = substr($option,4);
		}

		// components
		if(file_exists("$basePath/templates/$this->_template/components/$name.html.php")) {
			$this->_path->front = "$basePath/components/$option/$name.php";
			$this->_path->front_html = "$basePath/templates/$this->_template/components/$name.html.php";
		} else
		if(file_exists("$basePath/components/$option/$name.php")) {
			$this->_path->front = "$basePath/components/$option/$name.php";
			$this->_path->front_html = "$basePath/components/$option/$name.html.php";
		}

		$this->_path->config = "$basePath/components/$option/$name.config.php";

		if(file_exists("$basePath/".JADMIN_BASE."/components/$option/admin.$name.php")) {
			$this->_path->admin = "$basePath/".JADMIN_BASE."/components/$option/admin.$name.php";
			$this->_path->admin_html = "$basePath/".JADMIN_BASE."/components/$option/admin.$name.html.php";
		}

		if(file_exists("$basePath/administrator/components/$option/toolbar.$name.php")) {
			$this->_path->toolbar = "$basePath/".JADMIN_BASE."/components/$option/toolbar.$name.php";
			$this->_path->toolbar_html = "$basePath/".JADMIN_BASE."/components/$option/toolbar.$name.html.php";
			$this->_path->toolbar_default = "$basePath/".JADMIN_BASE."/includes/toolbar.html.php";
		}

		if(file_exists("$basePath/components/$option/$name.class.php")) {
			$this->_path->class = "$basePath/components/$option/$name.class.php";
		} elseif(file_exists("$basePath/".JADMIN_BASE."/components/$option/$name.class.php")) {
			$this->_path->class = "$basePath/".JADMIN_BASE."/components/$option/$name.class.php";
		} elseif(file_exists("$basePath/includes/$name.php")) {
			$this->_path->class = "$basePath/includes/$name.php";
		}

		if($prefix == 'mod_' && file_exists("$basePath/".JADMIN_BASE."/modules/$option.php")) {
			$this->_path->admin = "$basePath/".JADMIN_BASE."/modules/$option.php";
			$this->_path->admin_html = "$basePath/".JADMIN_BASE."/modules/mod_$name.html.php";
		} elseif(file_exists("$basePath/".JADMIN_BASE."/components/$option/admin.$name.php")) {
			$this->_path->admin = "$basePath/".JADMIN_BASE."/components/$option/admin.$name.php";
			$this->_path->admin_html = "$basePath/".JADMIN_BASE."/components/$option/admin.$name.html.php";
		} else {
			$this->_path->admin = "$basePath/".JADMIN_BASE."/components/com_admin/admin.admin.php";
			$this->_path->admin_html = "$basePath/".JADMIN_BASE."/components/com_admin/admin.admin.html.php";
		}
	}

	/**
	 * Получение пути окружения
	 * @param string $varname - название переменной
	 * @param string $option - название компонента дял которого получается переменные окружения
	 * @return string путь
	 */
	public function getPath($varname,$option = '') {

		if($option) {
			$temp = $this->_path;
			$this->_setAdminPaths($option,JPATH_BASE);
		}

		$result = null;
		if(isset($this->_path->$varname)) {
			$result = $this->_path->$varname;
		} else {
			switch($varname) {
				case 'com_xml':
					$name = substr($option,4);
					$path = JPATH_BASE.DS.JADMIN_BASE."/components/$option/$name.xml";
					if(file_exists($path)) {
						$result = $path;
					} else {
						$path = JPATH_BASE."/components/$option/$name.xml";
						if(file_exists($path)) {
							$result = $path;
						}
					}
					break;

				case 'mod0_xml':
				// Site modules
					if($option == '') {
						$path = JPATH_BASE.'/modules/custom.xml';
					} else {
						$path = JPATH_BASE."/modules/$option.xml";
					}
					if(file_exists($path)) {
						$result = $path;
					}
					break;

				case 'mod1_xml':
				// admin modules
					if($option == '') {
						$path = JPATH_BASE.DS.JADMIN_BASE.'/modules/custom.xml';
					} else {
						$path = JPATH_BASE.DS.JADMIN_BASE."/modules/$option.xml";
					}
					if(file_exists($path)) {
						$result = $path;
					}
					break;

				case 'bot_xml':
				// Site mambots
					$path = JPATH_BASE.DS.'mambots'.DS.$option.'.xml';
					if(file_exists($path)) {
						$result = $path;
					}
					break;

				case 'menu_xml':
					$path = JPATH_BASE.DS.JADMIN_BASE."/components/com_menus/$option/$option.xml";
					if(file_exists($path)) {
						$result = $path;
					}
					break;

				case 'installer_html':
					$path = JPATH_BASE.DS.JADMIN_BASE."/components/com_installer/$option/$option.html.php";
					if(file_exists($path)) {
						$result = $path;
					}
					break;

				case 'installer_class':
					$path = JPATH_BASE.DS.JADMIN_BASE."/components/com_installer/$option/$option.class.php";
					if(file_exists($path)) {
						$result = $path;
					}
					break;
			}
		}
		if($option) {
			$this->_path = $temp;
		}
		return $result;
	}

	/**
	 * @return правильный текущий Itemid для объектов содержимого
	 */
	public function getItemid($id,$typed = 1,$link = 1) {
		global $Itemid;

		// getItemid compatibility mode, holds maintenance version number
		$compat = (int)$this->getCfg('itemid_compat');
		$compat = ($compat == 0) ? 12 : $compat;
		$_Itemid = '';

		if($_Itemid == '' && $typed && $this->getStaticContentCount()) {
			$exists = 0;
			foreach($this->get('_ContentTyped',array()) as $key => $value) {
				// check if id has been tested before, if it is pull from class variable store
				if($key == $id) {
					$_Itemid = $value;
					$exists = 1;
					break;
				}
			}
			unset($key,$value);

			// if id hasnt been checked before initaite query
			if(!$exists) {
				$ContentTyped = $this->get('_ContentTyped',array());

				$all_menu_links = mosMenu::get_menu_links();

				if(isset($all_menu_links['index.php?option=com_content&task=view&id='.$id]) && $all_menu_links['index.php?option=com_content&task=view&id='.$id]['type']=='content_typed') {
					$ContentTyped[$id] =$all_menu_links['index.php?option=com_content&task=view&id='.$id]['id'];
				}else {
					// Search for typed link
					$query = "SELECT id FROM #__menu WHERE type = 'content_typed' AND published = 1 AND link = 'index.php?option=com_content&task=view&id=".(int)$id."'";
					$ContentTyped[$id] = $this->_db->setQuery($query)->loadResult();
				}
				// save temp array to main array storage
				$this->set('_ContentTyped',$ContentTyped);

				$_Itemid = $ContentTyped[$id];
			}
		}

		if($_Itemid == '' && $link && $this->getContentItemLinkCount()) {
			$exists = 0;
			foreach($this->get('_ContentItemLink',array()) as $key => $value) {
				// check if id has been tested before, if it is pull from class variable store
				if($key == $id) {
					$_Itemid = $value;
					$exists = 1;
					break;
				}
			}
			unset($key,$value);
			// if id hasnt been checked before initaite query
			if(!$exists) {
				// pull existing query storage into temp variable
				$ContentItemLink = $this->get('_ContentItemLink',array());
				// add query result to temp array storage
				$query = "SELECT id FROM #__menu WHERE type = 'content_item_link' AND published = 1 AND link = 'index.php?option=com_content&task=view&id=".(int)$id."'";
				$ContentItemLink[$id] = $this->_db->setQuery($query)->loadResult();
				// save temp array to main array storage
				$this->set('_ContentItemLink',$ContentItemLink);

				$_Itemid = $ContentItemLink[$id];
				unset($ContentItemLink);
			}
		}

		if($_Itemid == '') {
			$exists = 0;
			foreach($this->get('_ContentSection',array()) as $key => $value) {
				// check if id has been tested before, if it is pull from class variable store
				if($key == $id) {
					$_Itemid = $value;
					$exists = 1;
					break;
				}
			}
			// if id hasnt been checked before initaite query
			if(!$exists) {

				$query = "SELECT ms.id AS sid, ms.type AS stype, mc.id AS cid, mc.type AS ctype, i.id as sectionid, i.id As catid, ms.published AS spub, mc.published AS cpub"
						."\n FROM #__content AS i"
						."\n LEFT JOIN #__sections AS s ON i.sectionid = s.id"
						."\n LEFT JOIN #__menu AS ms ON ms.componentid = s.id "
						."\n LEFT JOIN #__categories AS c ON i.catid = c.id"
						."\n LEFT JOIN #__menu AS mc ON mc.componentid = c.id "
						."\n WHERE ( ms.type IN ( 'content_section', 'content_blog_section' ) OR mc.type IN ( 'content_blog_category', 'content_category' ) )"
						."\n AND i.id = ".(int)$id."\n ORDER BY ms.type DESC, mc.type DESC, ms.id, mc.id";
				$links = $this->_db->setQuery($query)->loadObjectList();
				;

				if(count($links)) {
					foreach($links as $link) {
						if($link->stype == 'content_section' && $link->sectionid == $id && $link->spub == 1) {
							$content_section = $link->sid;
						}

						if($link->stype == 'content_blog_section' && $link->sectionid == $id && $link->spub == 1) {
							$content_blog_section = $link->sid;
						}

						if($link->ctype == 'content_blog_category' && $link->catid == $id && $link->cpub == 1) {
							$content_blog_category = $link->cid;
						}

						if($link->ctype == 'content_category' && $link->catid == $id && $link->cpub == 1) {
							$content_category = $link->cid;
						}
					}
				}

				unset($links);

				if(!isset($content_section)) {
					$content_section = null;
				}

				// pull existing query storage into temp variable
				$ContentSection = $this->get('_ContentSection',array());
				// add query result to temp array storage
				$ContentSection[$id] = $content_section;
				// save temp array to main array storage
				$this->set('_ContentSection',$ContentSection);

				$_Itemid = $ContentSection[$id];
			}
		}

		if($compat <= 11 && $_Itemid == '') {
			$exists = 0;
			foreach($this->get('_ContentBlogSection',array()) as $key => $value) {
				// check if id has been tested before, if it is pull from class variable store
				if($key == $id) {
					$_Itemid = $value;
					$exists = 1;
					break;
				}
			}
			// if id hasnt been checked before initaite query
			if(!$exists) {
				if(!isset($content_blog_section)) {
					$content_blog_section = null;
				}
				// pull existing query storage into temp variable
				$ContentBlogSection = $this->get('_ContentBlogSection',array());
				// add query result to temp array storage
				$ContentBlogSection[$id] = $content_blog_section;
				// save temp array to main array storage
				$this->set('_ContentBlogSection',$ContentBlogSection);
				$_Itemid = $ContentBlogSection[$id];
			}
		}
		if($_Itemid == '') {
			$exists = 0;
			foreach($this->get('_ContentBlogCategory',array()) as $key => $value) {
				// check if id has been tested before, if it is pull from class variable store
				if($key == $id) {
					$_Itemid = $value;
					$exists = 1;
					break;
				}
			}
			// if id hasnt been checked before initaite query
			if(!$exists) {
				if(!isset($content_blog_category)) {
					$content_blog_category = null;
				}

				// pull existing query storage into temp variable
				$ContentBlogCategory = $this->get('_ContentBlogCategory',array());
				// add query result to temp array storage
				$ContentBlogCategory[$id] = $content_blog_category;
				// save temp array to main array storage
				$this->set('_ContentBlogCategory',$ContentBlogCategory);

				$_Itemid = $ContentBlogCategory[$id];
			}
		}

		if($_Itemid == '') {
			// ensure that query is only called once
			if(!$this->get('_GlobalBlogSection') && !defined('_JOS_GBS')) {
				define('_JOS_GBS',1);

				// Search in global blog section
				$query = "SELECT id FROM #__menu WHERE type = 'content_blog_section' AND published = 1 AND componentid = 0";
				$this->_db->setQuery($query);
				$this->set('_GlobalBlogSection',$this->_db->loadResult());
			}

			$_Itemid = $this->get('_GlobalBlogSection');
		}

		if($compat >= 12 && $_Itemid == '') {
			$exists = 0;
			foreach($this->get('_ContentBlogSection',array()) as $key => $value) {
				// check if id has been tested before, if it is pull from class variable store
				if($key == $id) {
					$_Itemid = $value;
					$exists = 1;
					break;
				}
			}
			// if id hasnt been checked before initaite query
			if(!$exists) {
				if(!isset($content_blog_section)) {
					$content_blog_section = null;
				}

				// pull existing query storage into temp variable
				$ContentBlogSection = $this->get('_ContentBlogSection',array());
				// add query result to temp array storage
				$ContentBlogSection[$id] = $content_blog_section;
				// save temp array to main array storage
				$this->set('_ContentBlogSection',$ContentBlogSection);

				$_Itemid = $ContentBlogSection[$id];
			}
		}

		if($_Itemid == '') {
			$exists = 0;
			foreach($this->get('_ContentCategory',array()) as $key => $value) {
				// check if id has been tested before, if it is pull from class variable store
				if($key == $id) {
					$_Itemid = $value;
					$exists = 1;
					break;
				}
			}
			// if id hasnt been checked before initaite query
			if(!$exists) {
				if(!isset($content_category)) {
					$content_category = null;
				}

				// pull existing query storage into temp variable
				$ContentCategory = $this->get('_ContentCategory',array());
				// add query result to temp array storage
				//$ContentCategory[$id]	= $this->_db->loadResult();
				$ContentCategory[$id] = $content_category;
				// save temp array to main array storage
				$this->set('_ContentCategory',$ContentCategory);

				$_Itemid = $ContentCategory[$id];
			}
		}

		if($_Itemid == '') {
			// ensure that query is only called once
			if(!$this->get('_GlobalBlogCategory') && !defined('_JOS_GBC')) {
				define('_JOS_GBC',1);

				// Search in global blog category
				$query = "SELECT id FROM #__menu WHERE type = 'content_blog_category' AND published = 1 AND componentid = 0";
				$this->_db->setQuery($query);
				$this->set('_GlobalBlogCategory',$this->_db->loadResult());
			}

			$_Itemid = $this->get('_GlobalBlogCategory');
		}

		if($_Itemid != '') {
			// if Itemid value discovered by queries, return this value
			return $_Itemid;
		} else
		if($compat >= 12 && $Itemid != 99999999 && $Itemid > 0) {
			// if queries do not return Itemid value, return Itemid of page - if it is not 99999999
			return $Itemid;
		} else
		if($compat <= 11 && $Itemid === 0) {
			// if queries do not return Itemid value, return Itemid of page - if it is not 99999999
			return $Itemid;
		}
		return 99999999;
	}

	/**
	 * @return number of Static Content
	 */
	public function getStaticContentCount() {
		// ensure that query is only called once
		if(!$this->get('_StaticContentCount') && !defined('_JOS_SCC')) {
			define('_JOS_SCC',1);

			$query = "SELECT COUNT( id ) FROM #__menu WHERE type = 'content_typed' AND published = 1";
			$this->_db->setQuery($query);
			// saves query result to variable
			$this->set('_StaticContentCount',$this->_db->loadResult());
		}

		return $this->get('_StaticContentCount');
	}

	/**
	 * @return number of Content Item Links
	 */
	public function getContentItemLinkCount() {
		// ensure that query is only called once
		if(!$this->get('_ContentItemLinkCount') && !defined('_JOS_CILC')) {
			define('_JOS_CILC',1);

			$query = "SELECT COUNT( id ) FROM #__menu WHERE type = 'content_item_link' AND published = 1";
			$this->_db->setQuery($query);
			// saves query result to variable
			$this->set('_ContentItemLinkCount',$this->_db->loadResult());
		}

		return $this->get('_ContentItemLinkCount');
	}

	/** Is admin interface?
	 * @return boolean
	 * @since 1.0.2
	 */
	public function isAdmin() {
		return $this->_isAdmin;
	}

	/**
	 * Установка системного сообщения
	 * @param string $msg - текст сообщения
	 */
	public static function set_mosmsg($msg='') {
		$msg = Jstring::trim($msg);

		if($msg!='') {
			if( mosMainFrame::$is_admin ) {
				$_s = session_id();
				if( empty($_s)) {
					session_name(md5(JPATH_SITE));
					session_start();
				}
			}else {
				session_name(mosMainFrame::sessionCookieName());
				session_start();
			}

			$_SESSION['joostina.mosmsg'] = $msg;
		}
	}

	/**
	 * Получение системного сообщения
	 * @return string - текст сообщения
	 */
	public function get_mosmsg() {

		$_s = session_id();

		if(!$this->_isAdmin &&empty($_s) ) {
			session_name(mosMainFrame::sessionCookieName());
			session_start();
		}

		$mosmsg_ss = trim(stripslashes(strval(mosGetParam($_SESSION,'joostina.mosmsg',''))));
		$mosmsg_rq = stripslashes(strval(mosGetParam($_REQUEST,'mosmsg','')));

		$mosmsg = ($mosmsg_ss!='') ? $mosmsg_ss : $mosmsg_rq;

		if($mosmsg!='' && Jstring::strlen($mosmsg) > 300) { // выводим сообщения не длинее 300 символов
			$mosmsg = Jstring::substr($mosmsg,0,300);
		}

		unset($_SESSION['joostina.mosmsg']);
		return $mosmsg;
	}

	/* проверка доступа к активному компоненту */
	public function check_option($option) {
		if($option=='com_content') return true;
		$sql = 'SELECT menuid FROM #__components WHERE #__components.option=\''.$option.'\' AND parent=0';

		($this->_db->setQuery($sql)->loadResult()==0) ? null : mosRedirect(JPATH_SITE);
		return true;
	}

	private function get_option() {

		$Itemid = intval(strtolower(mosGetParam($_REQUEST,'Itemid','')));
		$option = trim(strval(strtolower(mosGetParam($_REQUEST,'option',''))));

		if($option!='' && $Itemid!='') {
			return array('option'=>$option,'Itemid'=>$Itemid);
		}

		if($option!='') {
			return array('option'=>$option,'Itemid'=>99999999);
		}

		if($Itemid) {
			$query = "SELECT id, link"
					."\n FROM #__menu"
					."\n WHERE menutype = 'mainmenu'"
					."\n AND id = ".(int)$Itemid
					."\n AND published = 1";
			$menu = new mosMenu($database);
			$this->_db->setQuery($query)->loadObject($menu);
		} else {
			// получение пурвого элемента главного меню
			$menu = mosMenu::get_all();
			$menu = $menu['mainmenu'];
			$items = isset($menu) ? array_values($menu) : array();
			$menu = $items[0];
		}

		$Itemid = $menu->id;
		$link = $menu->link;

		//unset($menu);
		if(($pos = strpos($link,'?')) !== false) {
			$link = substr($link,$pos + 1).'&Itemid='.$Itemid;
		}

		parse_str($link,$temp);
		/** это путь, требуется переделать для лучшего управления глобальными переменными*/
		foreach($temp as $k => $v) {
			$GLOBALS[$k] = $v;
			$_REQUEST[$k] = $v;
			if($k == 'option') {
				$option = $v;
			}
			if($k == 'Itemid') {
				$Itemid = $v;
			}
		}

		return array('option'=>$option,'Itemid'=>$Itemid);
	}
}

// главный класс конфигурации системы
class JConfig {
	// закрытая переменная для хранения текущий инстанции
	private static $_instance;

	/** @public int*/
	public $config_offline = null;
	/** @public string*/
	public $config_offline_message = null;
	/** @public string*/
	public $config_error_message = null;
	/** @public string*/
	public $config_sitename = null;
	/** @public string*/
	public $config_editor = 'none';
	/** @public int*/
	public $config_list_limit = 30;
	/** @public string*/
	public $config_favicon = null;
	/** @public string*/
	public $config_frontend_login = 1;
	/** @public int*/
	public $config_debug = 0;
	/** @public string*/
	public $config_host = null;
	/** @public string*/
	public $config_user = null;
	/** @public string*/
	public $config_password = null;
	/** @public string*/
	public $config_db = null;
	/** @public string*/
	public $config_dbprefix = null;
	/** @public string*/
	public $config_absolute_path = null;
	/** @public string*/
	public $config_live_site = null;
	/** @public string*/
	public $config_secret = null;
	/** @public int*/
	public $config_gzip = 0;
	/** @public int*/
	public $config_lifetime = 900;
	/** @public int*/
	public $config_session_life_admin = 1800;
	/** @public int*/
	public $config_admin_expired = 1;
	/** @public int*/
	public $config_session_type = 0;
	/** @public int*/
	public $config_error_reporting = 0;
	/** @public string*/
	public $config_helpurl = 'http://help.joostina.ru';
	/** @public string*/
	public $config_fileperms = '0644';
	/** @public string*/
	public $config_dirperms = '0755';
	/** @public string*/
	public $config_locale = null;
	/** @public string*/
	public $config_lang = null;
	/** @public int*/
	public $config_offset = null;
	/** @public int*/
	public $config_offset_user = null;
	/** @public string*/
	public $config_mailer = null;
	/** @public string*/
	public $config_mailfrom = null;
	/** @public string*/
	public $config_fromname = null;
	/** @public string*/
	public $config_sendmail = '/usr/sbin/sendmail';
	/** @public string*/
	public $config_smtpauth = 0;
	/** @public string*/
	public $config_smtpuser = null;
	/** @public string*/
	public $config_smtppass = null;
	/** @public string*/
	public $config_smtphost = null;
	/** @public int*/
	public $config_caching = 0;
	/** @public string*/
	public $config_cachepath = null;
	/** @public string*/
	public $config_cachetime = null;
	/** @public int*/
	public $config_allowUserRegistration = 0;
	/** @public int*/
	public $config_useractivation = null;
	/** @public int*/
	public $config_uniquemail = null;
	/** @public int*/
	public $config_shownoauth = 0;
	/** @public int*/
	public $config_frontend_userparams = 1;
	/** @public string*/
	public $config_MetaDesc = null;
	/** @public string*/
	public $config_MetaKeys = null;
	/** @public int*/
	public $config_MetaTitle = null;
	/** @public int*/
	public $config_MetaAuthor = null;
	/** @public int*/
	public $config_enable_log_searches = null;
	/** @public int*/
	public $config_enable_log_items = null;
	/** @public int*/
	public $config_sef = 0;
	/** @public int*/
	public $config_pagetitles = 1;
	/** @public int*/
	public $config_link_titles = 0;
	/** @public int*/
	public $config_readmore = 1;
	/** @public int*/
	public $config_vote = 0;
	/** @public int*/
	public $config_showAuthor = 0;
	/** @public int*/
	public $config_showCreateDate = 0;
	/** @public int*/
	public $config_showModifyDate = 0;
	/** @public int*/
	public $config_hits = 1;
	/** @public int*/
	public $config_showPrint = 0;
	/** @public int*/
	public $config_showEmail = 0;
	/** @public int*/
	public $config_icons = 1;
	/** @public int*/
	public $config_back_button = 0;
	/** @public int*/
	public $config_item_navigation = 0;
	/** @public int*/
	public $config_multilingual_support = 0;
	/** @public int*/
	public $config_multipage_toc = 0;
	/** Режим работы с itemid, 0 - прежний режим*/
	public $config_itemid_compat = 0;
	/** @public int отключение ведения сессий на фронте*/
	public $config_no_session_front = 0;
	/** @public int отключение syndicate*/
	public $config_syndicate_off = 0;
	/** @public int отключение тега Generator*/
	public $config_generator_off = 0;
	/** @public int отключение мамботов группы system*/
	public $config_mmb_system_off = 0;
	/** @public str использование одного шаблона на весь сайт*/
	public $config_one_template = '...';
	/** @public int подсчет времени генерации страницы*/
	public $config_time_generate = 0;
	/** @public int индексация страницы печати*/
	public $config_index_print = 0;
	/** @public int расширенные теги индексации*/
	public $config_index_tag = 0;
	/** @public int использование ежесуточной оптимизации таблиц базы данных*/
	public $config_optimizetables = 1;
	/** @public int отключение мамботов группы content*/
	public $config_mmb_content_off = 0;
	/** @public int кэширование меню панели управления*/
	public $config_adm_menu_cache = 0;
	/** @public int расположение элементов title*/
	public $config_pagetitles_first = 1;
	/** @public string разделитель "заголовок страницы - Название сайта "*/
	public $config_tseparator = ' - ';
	/** @int отключение captcha*/
	public $config_captcha = 1;
	/** @int очистка ссылки на com_frontpage*/
	public $config_com_frontpage_clear = 1;
	/** @str корень для компонента управления медиа содержимым*/
	public $config_media_dir = 'images/stories';
	/** @int автоматическая установка "Публиковать на главной"*/
	public $config_auto_frontpage = 0;
	/** @int уникальные идентификаторы новостей*/
	public $config_uid_news = 0;
	/** @int подсчет прочтений содержимого*/
	public $config_content_hits = 1;
	/** @str формат даты*/
	public $config_form_date = '%d.%m.%Y г.';
	/** @str полный формат даты и времени*/
	public $config_form_date_full = '%d.%m.%Y г. %H:%M';
	/** @int не показывать "Главная" на первой странице*/
	public $config_pathway_clean = 1;
	/** @int автоматические разлогинивание в панели управления после окончания жизни сессии */
	public $config_admin_autologout = 1;
	/** @int отключение кнопки "Помощь"*/
	public $config_disable_button_help = 0;
	/** @int отключение блокировок объектов*/
	public $config_disable_checked_out = 0;
	/** @int отключение favicon*/
	public $config_disable_favicon = 1;
	/** @str смещение для rss*/
	public $config_feed_timeoffset = null;
	/** @int использовать расширенную отладку на фронте*/
	public $config_front_debug = 0;
	/** @public int отключение мамботов группы mainbody*/
	public $config_mmb_mainbody_off = 0;
	/** @public int автоматическая авторизация после подтверждения регистрации*/
	public $config_auto_activ_login = 0;
	/** @public int отключение вкладки 'Изображения'*/
	public $config_disable_image_tab = 0;
	/** @public int обрамлять заголовки тегом h1*/
	public $config_title_h1 = 0;
	/** @public int обрамлять заголовки тегом h1 только в режиме полного просмотра содержимого*/
	public $config_title_h1_only_view = 1;
	/** @public int отключить проверки публикаций по датам*/
	public $config_disable_date_state = 0;
	/** @public int отключить контроль доступа к содержимому*/
	public $config_disable_access_control = 0;
	/** @public int включение оптимизации функции кэширования*/
	public $config_cache_opt = 0;
	/** @public int captcha для регистрации*/
	public $config_captcha_reg = 0;
	/** @public int captcha для формы контактов*/
	public $config_captcha_cont = 0;
	/** @public int обработчик кэширования запросов базы данных */
	public $config_db_cache_handler = 'none';
	/** @public int время жизни кэша запросов базы данных */
	public $config_db_cache_time = 0;
	/** @public int вывод мета-тега baser */
	public $config_mtage_base = 1;
	/** @public int вывод мета-тега revisit в днях */
	public $config_mtage_revisit = 10;
	/** @public int использование страницы печати из каталога текущего шаблона */
	public $config_custom_print = 0;
	/** @public int использование совместимого вывода туллбара */
	public $config_old_toolbar = 0;
	/** @public int отключение предпросмотра шаблонов через &tp=1 */
	public $config_disable_tpreview = 0;
	/** @int включение кода безопасности для доступа к панели управления*/
	public $config_enable_admin_secure_code = 0;
	/** @int включение кода безопасности для доступа к панели управления*/
	public $config_admin_secure_code = 'admin';
	/** @int режим редиректа при включенном коде безопасноти*/
	public $config_admin_redirect_options = 0;
	/** @int адрес редиректа при включенном коде безопасноти*/
	public $config_admin_redirect_path = '404.html';
	/** @public int число попыток автооизации для входа в админку*/
	public $config_admin_bad_auth = 5;
	/** @public int обработчик кэширования */
	public $config_cache_handler = 'none';
	/** @public int ключ для кэш файлов */
	public $config_cache_key = '';
	/** @public array настройки memCached */
	public $config_memcache_persistent = 0;
	/** @public array настройки memCached */
	public $config_memcache_compression = 0;
	/** @public array настройки memCached */
	public $config_memcache_host = 'localhost';
	/** @public array настройки memCached */
	public $config_memcache_port = '11211';
	/** @public int тип вывода ника автора материала */
	public $config_author_name = 4;
	/** @public int использование неопубликованных мамботов */
	public $config_use_unpublished_mambots = 0;
	/** @public int использование мамботов удаления содержимого */
	public $config_use_content_delete_mambots = 0;
	/** @public str название шаблона панели управления */
	public $config_admin_template = '...';
	/** @public int режим сортировки содержимого в панели управления */
	public $config_admin_content_order_by = 2;
	/** @public str порядок сортировки содержимого в панели управления */
	public $config_admin_content_order_sort = 0;
	/** @public int активация блокировок компонентов */
	public $config_components_access = 0;
	/** @public int использование мамботов редактирования содержимого */
	public $config_use_content_edit_mambots = 0;
	/** @public int использование мамботов сохранения содержимого */
	public $config_use_content_save_mambots = 0;
	/** @public int чисто неудачный авторизаций для блокировки аккаунта */
	public $config_count_for_user_block = 10;
	/** @public int директория шаблонов содержимого по-умолчанию */
	public $config_global_templates = 0;
	/** @public int включение/выключение отображения тэгов содержимого */
	public $config_tags = 0;
	/** @public int включение/выключение мамботов группы onAjaxStart */
	public $config_mmb_ajax_starts_off = 1;

	// инициализация класса конфигурации - собираем переменные конфигурации
	private function  __construct() {
		$this->bindGlobals();
	}
	/**
	 * Запрет клонирования объекта
	 */
	private function __clone() {

	}

	// получение инстанции конфигурации системы
	public static function getInstance() {

		JDEBUG ? jd_inc('JConfig::getInstance()') : null;

		if (self::$_instance === NULL) {
			self::$_instance = new JConfig();
		}

		return self::$_instance;
	}

	/**
	 * @return array An array of the public vars in the class
	 */
	function getPublicVars() {
		$public = array();
		$vars = array_keys(get_class_vars('JConfig'));
		sort($vars);
		foreach($vars as $v) {
			if($v{0} != '_') {
				$public[] = $v;
			}
		}
		return $public;
	}
	/**
	 *	binds a named array/hash to this object
	 *	@param array $hash named array
	 *	@return null|string	null is operation was satisfactory, otherwise returns an error
	 */
	function bind($array,$ignore = '') {
		if(!is_array($array)) {
			$this->_error = strtolower(get_class($this)).'::'._CONSTRUCT_ERROR;
			return false;
		} else {
			return mosBindArrayToObject($array,$this,$ignore);
		}
	}

	/**
	 * Writes the configuration file line for a particular variable
	 * @return string
	 */
	function getVarText() {
		$txt = '';
		$vars = $this->getPublicVars();
		foreach($vars as $v) {
			$k = str_replace('config_','mosConfig_',$v);
			$txt .= "\$$k = '".addslashes($this->$v)."';\n";
		}
		return $txt;
	}

	/**
	 * заполнение данных класса данными из глобальных перменных
	 */
	function bindGlobals() {
		// странное место с двойным проходом по массиву переменных
		//$vars = $this->getPublicVars();
		$vars = array_keys(get_class_vars('JConfig'));
		foreach($vars as $v) {
			$k = str_replace('config_','mosConfig_',$v);
			if(isset($GLOBALS[$k])) $this->$v = $GLOBALS[$k];
		}
		/*
		* для корректной работы https://
		*/
		// TODO HTTPS - проверить правильность
		//require (JPATH_BASE.DS.'configuration.php');
		//if($mosConfig_live_site != $this->config_live_site) {
		//	$this->config_live_site = $mosConfig_live_site;
		//}
	}
}

/**
 * Module database table class
 * @package Joostina
 */
class mosMenu extends mosDBTable {
	/**
	 * Инстанция хранения всех пунктов меню
	 * @var instance
	 */
	private static $_all_menus_instance;

	/**
	 @var int Primary key*/
	public $id = null;
	/**
	 @var string*/
	public $menutype = null;
	/**
	 @var string*/
	public $name = null;
	/**
	 @var string*/
	public $link = null;
	/**
	 @var int*/
	public $type = null;
	/**
	 @var int*/
	public $published = null;
	/**
	 @var int*/
	public $componentid = null;
	/**
	 @var int*/
	public $parent = null;
	/**
	 @var int*/
	public $sublevel = null;
	/**
	 @var int*/
	public $ordering = null;
	/**
	 @var boolean*/
	public $checked_out = null;
	/**
	 @var datetime*/
	public $checked_out_time = null;
	/**
	 @var boolean*/
	public $pollid = null;
	/**
	 @var string*/
	public $browserNav = null;
	/**
	 @var int*/
	public $access = null;
	/**
	 @var int*/
	public $utaccess = null;
	/**
	 @var string*/
	public $params = null;

	/**
	 * @param database A database connector object
	 */
	function mosMenu(&$db) {
		$this->mosDBTable('#__menu','id',$db);
		$this->_menu = array();
	}

	// получение инстанции меню
	public static function get_all() {

		if( self::$_all_menus_instance === NULL ) {
			$database = database::getInstance();
			// ведёргиваем из базы все пункты меню, они еще пригодяться несколько раз
			$sql = 'SELECT id,menutype,name,link,type,parent,params,access,browserNav FROM #__menu WHERE published=1 ORDER BY parent, ordering ASC';
			$menus = $database->setQuery($sql)->loadObjectList();

			$all_menus = array();
			foreach($menus as $menu) {
				$all_menus[$menu->menutype][$menu->id]=$menu;
			}
			self::$_all_menus_instance = $all_menus;
		}

		return self::$_all_menus_instance;
	}

	/**
	 *
	 * @return array
	 */
	function all_menu() {
		// ведёргиваем из базы все пункты меню, они еще пригодяться несколько раз
		$sql = 'SELECT* FROM #__menu WHERE published=1 ORDER BY parent, ordering ASC';
		$menus = $this->_db->setQuery($sql)->loadObjectList();

		$m = array();
		foreach($menus as $menu) {
			$m[$menu->menutype][$menu->id]=$menu;
		}

		return $m;
	}

	/**
	 *
	 * @return boolean
	 */
	function check() {
		$this->id = (int)$this->id;
		$this->params = (string )trim($this->params.' ');
		$ignoreList = array('link');
		$this->filter($ignoreList);
		return true;
	}

	function getMenu($id = null, $type = '', $link = '') {

		$where = '';
		$and = array();
		if($id || $type || $link) {
			$where .= ' WHERE ';
		}
		if ($id) {
			$and[] = ' menu.id = '.$id;
		}
		if ($type) {
			$and[] = " menu.type = '".$type."'";
		}
		if ($link) {
			$and[] = "menu.link LIKE '%$link'";
		}
		$and = implode(' AND ', $and);

		$query = 'SELECT menu.* FROM #__menu AS menu '.$where.$and;
		$r=null;
		$this->_db->setQuery($query);
		$this->_db->loadObject($r);
		return $r;
	}

	// возвращает всё содержимое всех меню
	function get_menu() {
		return $this->_menu;
	}

	public static function get_menu_links() {
		$_all = self::get_all();

		$return = array();
		foreach($_all as $menus) {
			foreach($menus as $menu) {
				// тут еще можно будет сделать красивые sef-ссылки на пункты меню
				//$return[$menu->link]=array('id'=>$menu->id,'name'=>$menu->name);
				$return[$menu->link]=array('id'=>$menu->id,'type'=>$menu->type);
			}
		}
		unset($menu,$menuss);
		return $return;
	}
}

/**
 * Module database table class
 * @package Joostina
 */
class mosModule extends mosDBTable {
	/**
	 * Инстанция инициализации модулей
	 * @var instance
	 */
	private static $_instance;
	/**
	 @var int Primary key*/
	public $id = null;
	/**
	 @var string*/
	public $title = null;
	/**
	 @var string*/
	public $showtitle = null;
	/**
	 @var int*/
	public $content = null;
	/**
	 @var int*/
	public $ordering = null;
	/**
	 @var string*/
	public $position = null;
	/**
	 @var boolean*/
	public $checked_out = null;
	/**
	 @var time*/
	public $checked_out_time = null;
	/**
	 @var boolean*/
	public $published = null;
	/**
	 @var string*/
	public $module = null;
	/**
	 @var int*/
	public $numnews = null;
	/**
	 @var int*/
	public $access = null;
	/**
	 @var string*/
	public $params = null;
	/**
	 @var string*/
	public $iscore = null;
	/**
	 @var string*/
	public $client_id = null;
	/**
	 @var string*/
	public $template = null;
	/**
	 @var string*/
	public $helper = null;

	private $_all_modules = null;

	private $_view = null;

	private $_mainframe = null;

	/**
	 * @param database A database connector object
	 */
	public function mosModule(&$db, $mainframe = null) {
		$this->mosDBTable('#__modules','id',$db);
		if($mainframe) {
			$this->_mainframe = $mainframe;
		}
	}

	public static function getInstance() {

		JDEBUG ? jd_inc('mosModule') : null;

		if( self::$_instance === null ) {
			$mainframe = mosMainFrame::getInstance();

			$modules = new mosModule($mainframe->getDBO(), $mainframe);
			$modules->initModules();
			self::$_instance = $modules;
		}

		return self::$_instance;
	}

	// overloaded check function
	public function check() {
		// check for valid name
		if(trim($this->title) == '') {
			$this->_error = _PLEASE_ENTER_MODULE_NAME;
			return false;
		}

		return true;
	}

	public static function convert_to_object($module, $mainframe) {
		$database = $mainframe->getDBO();

		$module_obj = new mosModule($database, $mainframe);
		$rows = get_object_vars($module_obj);
		foreach($rows as $key => $value) {
			if (isset($module->$key)) {
				$module_obj->$key = $module->$key;
			}
		}
		unset($module_obj->_mainframe,$module_obj->_db);

		return $module_obj;
	}

	function set_template($params) {

		if($params->get('template', '') == '') {
			return false;
		}

		$default_template = 'modules'.DS.$this->module.'/view/default.php';

		if($params->get('template_dir',0) == 0) {
			$template_dir = 'modules'.DS.$this->module.'/view';
		}else {
			$template_dir = 'templates'.DS.JTEMPLATE.DS.'html/modules'.DS.$this->module;
		}

		if($params->get('template')) {
			$file = JPATH_BASE . DS . $template_dir . DS . $params->get('template');
			if (is_file($file)) {
				$this->template = $file;
				return true;
			}elseif (is_file(JPATH_BASE . DS . $default_template)) {
				$this->template = JPATH_BASE . DS . $default_template;
				return true;
			}
		}

		return false;
	}

	function set_template_custom($template) {

		$template_file = JPATH_BASE.DS.'templates'.DS. JTEMPLATE .DS.'html'.DS.'user_modules'.DS.$template;

		if (is_file($template_file)) {
			$this->template = $template_file;
			return true;
		}
		return false;
	}

	function get_helper($mainframe) {

		$file = JPATH_BASE. DS .'modules'.DS.$this->module.DS.'helper.php';

		if (is_file($file)) {
			require_once($file);
			$helper_class = $this->module.'_Helper';
			$this->helper = new $helper_class($mainframe);
			return true;
		}
		return false;
	}

	function load_module($name = '', $title = '') {
		$where = " m.module = '".$name."'";
		if(!$name || $title) {
			$where = " m.title = '".$title."'";
		}

		$query = 'SELECT * FROM #__modules AS m WHERE '.$where.' AND published=1';
		$row = null;

		$this->_view->_mainframe->getDBO()->setQuery($query)->loadObject($row);

		$rows = get_object_vars($this);

		foreach ($rows as $key => $value) {
			if (isset($row->$key)) {
				$this->$key = $row->$key;
			}
		}
		return true;
	}

	/**
	 * Cache some modules information
	 * @return array
	 */
	public function initModules() {
		global $my,$Itemid;

		// TODO спорно
		//$cache = mosCache::getCache('init_modules');
		//$this->_all_modules = $cache->call('mosModule::_initModules', $Itemid,$my->gid);
		$this->_all_modules = self::_initModules( $Itemid,$my->gid );
		require_once (JPATH_BASE.'/includes/frontend.html.php');
		$this->_view = new modules_html($this->_mainframe);
	}

	/**
	 * инициализация списка модулей
	 * @param <type> $Itemid
	 * @param <type> $my_gid
	 * @return <type>
	 */
	public static function _initModules( $Itemid, $my_gid ) {
		$mainframe = mosMainFrame::getInstance();

		$all_modules = array();

		$Itemid = intval($Itemid);
		$check_Itemid = ($Itemid) ? "OR mm.menuid = ".$Itemid:'';

		$where_ac = $mainframe->get('config')->config_disable_access_control ? '' : "\n AND (m.access=3 OR m.access <= ".(int)$my_gid.') ';

		$query = "SELECT id, title, module, position, content, showtitle, params,access FROM #__modules AS m"
				."\n INNER JOIN #__modules_menu AS mm ON mm.moduleid = m.id"
				."\n WHERE m.published = 1"
				.$where_ac
				."\n AND m.client_id != 1 AND ( mm.menuid = 0 $check_Itemid )"
				."\n ORDER BY ordering";

		$modules = $mainframe->getDBO()->setQuery($query)->loadObjectList();

		foreach($modules as $module) {
			if($module->access==3) {
				$my_gid ==0 ? $all_modules[$module->position][] = $module : null;
			}else {
				$all_modules[$module->position][] = $module;
			}
		}


		return $all_modules;
	}

	/**
	 * @param string the template position
	 */
	function mosCountModules($position = 'left') {
		if(intval(mosGetParam($_GET,'tp',0))) {
			return 1;
		}

		$allModules = $this->_all_modules;

		return (isset($allModules[$position])) ? count($allModules[$position]) : 0;
	}

	/**
	 * @param string The position
	 * @param int The style.  0=normal, 1=horiz, -1=no wrapper
	 */
	function mosLoadModules($position = 'left',$style = 0,$noindex = 0) {
		global $my,$Itemid;

		$tp = intval(mosGetParam($_GET,'tp',0));
		$style = intval($style);

		$config_absolute_path = JPATH_BASE;
		$config_caching = $this->_view->_mainframe->config->config_caching;

		if($tp && !$this->_view->_mainframe->config->config_disable_tpreview ) {
			echo '<div style="height:50px;background-color:#eee;margin:2px;padding:10px;border:1px solid #f00;color:#700;">'.$position.'</div>';
			return;
		}

		$allModules = $this->_all_modules;

		$modules = (isset($allModules[$position])) ? $modules = $allModules[$position]:array();

		echo ($noindex == 1) ? '<span style="display:none"><![CDATA[<noindex>]]></span>' : null;

		if(count($modules) < 1) {
			$style = 0;
		}

		echo ($style == 1) ? '<table cellspacing="1" cellpadding="0" border="0" width="100%"><tr>' : null;

		$prepend = ($style == 1)  ? '<td valign="top">' : '';
		$postpend = ($style == 1) ? '</td>'             : '';

		$count = 1;

		foreach($modules as $module) {

			$params = new mosParameters($module->params);
			$def_cachetime = ($params->get('cache_time',0)>0) ? $params->get('cache_time') : null;

			echo $prepend;

			if((substr($module->module,0,4)) == 'mod_') {
				// normal modules
				if(($params->get('cache',0) == 1 OR $def_cachetime>0) && $config_caching == 1) {
					// module caching
					$cache = mosCache::getCache($module->module.'_'.$module->id,'function',null,$def_cachetime, $this->_view);
					$cache->call('module2',$module,$params,$Itemid,$style,$my->gid);
				} else {
					$this->_view->module2($module,$params,$Itemid,$style,$count);
				}
			} else {
				// custom or new modules
				if($params->get('cache') == 1 && $config_caching == 1) {
					// module caching
					$cache = mosCache::getCache('mod_user_'.$module->id,'function',null,$def_cachetime, $this->_view);
					$cache->call('module',$module,$params,$Itemid,$style,0,$my->gid);
				} else {
					$this->_view->module($module,$params,$Itemid,$style);
				}
			}

			echo $postpend;

			$count++;
		}

		echo ($style   ==1 ) ? "</tr>\n</table>\n" : null;
		echo ($noindex == 1) ? '<span style="display:none"><![CDATA[</noindex>]]></span>' : null;

		return;
	}

	/**
	 * @param string The position
	 * @param int The style.  0=normal, 1=horiz, -1=no wrapper
	 */
	function mosLoadModule($name = '', $title = '', $style = 0, $noindex = 0, $inc_params = null) {
		global $my,$Itemid;

		$database = $this->_view->_mainframe->getDBO();
		$config = $this->_view->_mainframe->get('config');

		$tp = intval(mosGetParam($_GET,'tp',0));

		if($tp && !$config->config_disable_tpreview ) {
			echo '<div style="height:50px;background-color:#eee;margin:2px;padding:10px;border:1px solid #f00;color:#700;">'.$name.'</div>';
			return;
		}
		$style = intval($style);
		$module = $this;
		$module->load_module($name, $title);

		echo ($noindex == 1) ? '<del><![CDATA[<noindex>]]></del>' : null;

		echo ($style == 1) ? '<table cellspacing="1" cellpadding="0" border="0" width="100%"><tr>' : null;

		$prepend = ($style == 1) ? "<td valign=\"top\">\n":'';
		$postpend = ($style == 1) ? "</td>\n":'';

		$count = 1;

		$params = new mosParameters($module->params);
		if($inc_params) {
			foreach($inc_params as $key=>$val) {
				$params->set($key, $val);
			}
		}
		echo $prepend;

		if((substr($module->module,0,4)) == 'mod_') {
			// normal modules
			if($params->get('cache') == 1 && $config->config_caching == 1) {
				// module caching
				$cache = mosCache::getCache('modules', '', null, null, $this->_view);
				$cache->call('module2',$module,$params,$Itemid,$style,$my->gid);
			} else {
				$this->_view->module2($module,$params,$Itemid,$style,$count);
			}
		} else {
			// custom or new modules
			if($params->get('cache') == 1 && $config->config_caching == 1) {
				// module caching
				$cache->call('module',$module,$params,$Itemid,$style,0,$my->gid);
			} else {
				$this->_view->module($module,$params,$Itemid,$style);
			}
		}

		echo $postpend;
		$count++;

		echo ($style == 1) ? "</tr>\n</table>\n" : null;
		echo ($noindex == 1) ? '<del><![CDATA[</noindex>]]></del>' : null;
		return;
	}
}

/**
 * Class to support function caching
 * @package Joostina
 */
class mosCache {
	private static $_instance;

	/**
	 * @return object A function cache object
	 */
	function getCache($group = 'default', $handler = 'callback', $storage = null,$cachetime = null, $object = null) {

		jd_inc('cache');

		if( self::$_instance===null ) {
			$config = Jconfig::getInstance();

			self::$_instance = array();
			self::$_instance['config_caching'] = $config->config_caching;
			self::$_instance['config_cachetime'] = $config->config_cachetime;
			self::$_instance['config_cache_handler'] = $config->config_cache_handler;
			self::$_instance['config_cachepath'] = $config->config_cachepath;
			self::$_instance['config_lang'] = $config->config_lang;
			// подключаем библиотеку кэширования
			mosMainFrame::addLib('cache');
		}

		$handler = ($handler == 'function') ? 'callback' : $handler;

		$def_cachetime = (isset($cachetime)) ? $cachetime : self::$_instance['config_cachetime'];

		if(!isset($storage)) {
			$storage =(self::$_instance['config_cache_handler'] != '')? self::$_instance['config_cache_handler'] : 'file';
		}

		$options = array(
				'defaultgroup' 	=> $group,
				'cachebase' 	=> self::$_instance['config_cachepath'].DS,
				'lifetime' 		=> $def_cachetime,
				'language' 		=> self::$_instance['config_lang'],
				'storage'		=> $storage
		);

		$cache = JCache::getInstance( $handler, $options, $object );

		if($cache != NULL) {
			$cache->setCaching(self::$_instance['config_caching']);
		}
		return $cache;
	}
	/**
	 * Cleans the cache
	 */
	function cleanCache($group = false) {
		$cache = mosCache::getCache($group);
		if($cache != NULL) {
			$cache->clean($group);
		}
	}
}

/**
 * Utility function to return a value from a named array or a specified default
 * @param array A named array
 * @param string The key to search for
 * @param mixed The default value to give if no key found
 * @param int An options mask: _MOS_NOTRIM prevents trim, _MOS_ALLOWHTML allows safe html, _MOS_ALLOWRAW allows raw input
 */
define("_MOS_NOTRIM",0x0001);
define("_MOS_ALLOWHTML",0x0002);
define("_MOS_ALLOWRAW",0x0004);

function mosGetParam(&$arr,$name,$def = null,$mask = 0) {

	$return = null;
	if(isset($arr[$name])) {
		$return = $arr[$name];

		if(is_string($return)) {
			$return = (!($mask & _MOS_NOTRIM)) ? trim($return) : $return;

			$return = (!$mask && !_MOS_ALLOWRAW && !_MOS_ALLOWHTML) ? InputFilter::getInstance()->process($return) : $return;

			// account for magic quotes setting
			$return = (!get_magic_quotes_gpc()) ? addslashes($return) : $return;
		}

		return $return;
	} else {
		return $def;
	}
}

/**
 * Strip slashes from strings or arrays of strings
 * @param mixed The input string or array
 * @return mixed String or array stripped of slashes
 */
function mosStripslashes(&$value) {
	$ret = '';
	if(is_string($value)) {
		$ret = stripslashes($value);
	} else {
		if(is_array($value)) {
			$ret = array();
			foreach($value as $key => $val) {
				$ret[$key] = mosStripslashes($val);
			}
		} else {
			$ret = $value;
		}
	}
	return $ret;
}

/**
 * Copy the named array content into the object as properties
 * only existing properties of object are filled. when undefined in hash, properties wont be deleted
 * @param array the input array
 * @param obj byref the object to fill of any class
 * @param string
 * @param boolean
 */
function mosBindArrayToObject($array,&$obj,$ignore = '',$prefix = null,$checkSlashes = true) {
	if(!is_array($array) || !is_object($obj)) {
		return (false);
	}
	$ignore = ' '.$ignore.' ';
	foreach(get_object_vars($obj) as $k => $v) {
		if(substr($k,0,1) != '_') { // internal attributes of an object are ignored
			if(strpos($ignore,' '.$k.' ') === false) {
				if($prefix) {
					$ak = $prefix.$k;
				} else {
					$ak = $k;
				}
				if(isset($array[$ak])) {
					$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? mosStripslashes($array[$ak]): $array[$ak];
				}
			}
		}
	}
	return true;
}



/**
 * Utility function to read the files in a directory
 * @param string The file system path
 * @param string A filter for the names
 * @param boolean Recurse search into sub-directories
 * @param boolean True if to prepend the full path to the file name
 */
function mosReadDirectory($path,$filter = '.',$recurse = false,$fullpath = false) {
	$arr = array();
	if(!@is_dir($path)) {
		return $arr;
	}
	$handle = opendir($path);

	while($file = readdir($handle)) {
		$dir = mosPathName($path.'/'.$file,false);
		$isDir = is_dir($dir);
		if(($file != ".") && ($file != "..")) {
			if(preg_match("/$filter/",$file)) {
				if($fullpath) {
					$arr[] = trim(mosPathName($path.'/'.$file,false));
				} else {
					$arr[] = trim($file);
				}
			}
			if($recurse && $isDir) {
				$arr2 = mosReadDirectory($dir,$filter,$recurse,$fullpath);
				$arr = array_merge($arr,$arr2);
			}
		}
	}
	closedir($handle);
	asort($arr);
	return $arr;
}

/**
 * Utility function redirect the browser location to another url
 *
 * Can optionally provide a message.
 * @param string The file system path
 * @param string A filter for the names
 */
function mosRedirect($url,$msg = '') {
	// specific filters
	$iFilter = InputFilter::getInstance();
	$url = $iFilter->process($url);
	if(!empty($msg)) {
		$msg = $iFilter->process($msg);
		mosMainFrame::set_mosmsg($msg);
	}

	// Strip out any line breaks and throw away the rest
	$url = preg_split("/[\r\n]/",$url);
	$url = $url[0];
	if($iFilter->badAttributeValue(array('href',$url))) {
		$url = JPATH_SITE;
	}

	if(headers_sent()) {
		echo "<script>document.location.href='$url';</script>\n";
	} else {
		@ob_end_clean(); // clear output buffer
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: ".$url);
	}
	exit();
}

function mosErrorAlert($text,$action = 'window.history.go(-1);',$mode = 1) {
	$text = nl2br($text);
	$text = addslashes($text);
	$text = strip_tags($text);

	switch($mode) {
		case 2:
			echo "<script>$action</script> \n";
			break;

		case 1:
		default:
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; "._ISO."\" />";
			echo "<script>alert('$text'); $action</script> \n";
			break;
	}

	exit;
}

/**
 *
 * @param <type> $id
 * @param <type> $indent
 * @param <type> $list
 * @param <type> $children
 * @param <type> $maxlevel
 * @param <type> $level
 * @param <type> $type
 * @return <type>
 */
function mosTreeRecurse($id,$indent,$list,&$children,$maxlevel = 9999,$level = 0,$type = 1) {

	if(@$children[$id] && $level <= $maxlevel) {
		foreach($children[$id] as $v) {
			$id = $v->id;

			if($type) {
				$pre = '<sup>L</sup>&nbsp;';
				$spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			} else {
				$pre = '- ';
				$spacer = '&nbsp;&nbsp;';
			}

			if($v->parent == 0) {
				$txt = $v->name;
			} else {
				$txt = $pre.$v->name;
			}

			$list[$id] = $v;
			$list[$id]->treename = $indent.$txt;
			$list[$id]->children = count(@$children[$id]);

			$list = mosTreeRecurse($id,$indent.$spacer,$list,$children,$maxlevel,$level + 1,$type);
		}
	}
	return $list;
}

/**
 * Function to strip additional / or \ in a path name
 * @param string The path
 * @param boolean Add trailing slash
 */
function mosPathName($p_path,$p_addtrailingslash = true) {
	$retval = '';

	$isWin = (substr(PHP_OS,0,3) == 'WIN');

	if($isWin) {
		$retval = str_replace('/','\\',$p_path);
		if($p_addtrailingslash) {
			if(substr($retval,-1) != '\\') {
				$retval .= '\\';
			}
		}

		// Check if UNC path
		$unc = substr($retval,0,2) == '\\\\'?1:0;

		// Remove double \\
		$retval = str_replace('\\\\','\\',$retval);

		// If UNC path, we have to add one \ in front or everything breaks!
		if($unc == 1) {
			$retval = '\\'.$retval;
		}
	} else {
		$retval = str_replace('\\','/',$p_path);
		if($p_addtrailingslash) {
			if(substr($retval,-1) != '/') {
				$retval .= '/';
			}
		}

		// Check if UNC path
		$unc = substr($retval,0,2) == '//'?1:0;

		// Remove double //
		$retval = str_replace('//','/',$retval);

		// If UNC path, we have to add one / in front or everything breaks!
		if($unc == 1) {
			$retval = '/'.$retval;
		}
	}

	return $retval;
}

/**
 *
 * @param <type> $p_obj
 * @return <type>
 */
function mosObjectToArray($p_obj) {
	$retarray = null;
	if(is_object($p_obj)) {
		$retarray = array();
		foreach(get_object_vars($p_obj) as $k => $v) {
			if(is_object($v)) $retarray[$k] = mosObjectToArray($v);
			else $retarray[$k] = $v;
		}
	}
	return $retarray;
}

/**
 * Makes a variable safe to display in forms
 *
 * Object parameters that are non-string, array, object or start with underscore
 * will be converted
 * @param object An object to be parsed
 * @param int The optional quote style for the htmlspecialchars function
 * @param string|array An optional single field name or array of field names not
 * to be parsed (eg, for a textarea)
 */
function mosMakeHtmlSafe(&$mixed,$quote_style = ENT_QUOTES,$exclude_keys = '') {
	if(is_object($mixed)) {
		foreach(get_object_vars($mixed) as $k => $v) {
			if(is_array($v) || is_object($v) || $v == null || substr($k,1,1) == '_') {
				continue;
			}
			if(is_string($exclude_keys) && $k == $exclude_keys) {
				continue;
			} else
			if(is_array($exclude_keys) && in_array($k,$exclude_keys)) {
				continue;
			}
			$mixed->$k = htmlspecialchars($v,$quote_style);
		}
	}
}

/**
 * Checks whether a menu option is within the users access level
 * @param int Item id number
 * @param string The menu option
 * @param int The users group ID number
 * @param database A database connector object
 * @return boolean True if the visitor's group at least equal to the menu access
 */
function mosMenuCheck($Itemid,$menu_option,$task,$gid,$mainframe) {

	$results = array();
	$access = 0;

	if($Itemid != '' && $Itemid != 0 && $Itemid != 99999999) {
		$all_menus = mosMenu::get_all();
		foreach($all_menus as $menu) {
			if(isset($menu[$Itemid])) {
				$results[0]=$menu[$Itemid];
				$access = $results[0]->access;
			}
		}
		unset($all_menus);
	} else {
		$database = $mainframe->getDBO();
		$dblink = "index.php?option=".$database->getEscaped($menu_option, true);
		if($task != '') {
			$dblink .= "&task=".$database->getEscaped($task, true);
		}
		$query = "SELECT* FROM #__menu WHERE published = 1 AND link LIKE '$dblink%'";
		$results = $database->setQuery($query)->loadObjectList();
		foreach($results as $result) {
			$access = max($access,$result->access);
		}
	}

	// save menu information to global mainframe
	if(isset($results[0])) {
		// loads menu info of particular Itemid
		$mainframe->set('menu',$results[0]);
	} else {
		// loads empty Menu info
		$mainframe->set('menu',new mosMenu($database));
	}
	return ($access <= $gid);
}

/**
 * Returns formated date according to current local and adds time offset
 * @param string date in datetime format
 * @param string format optional format for strftime
 * @param offset time offset if different than global one
 * @returns formated date
 */
function mosFormatDate($date,$format = '',$offset = null) {
	static $config_offset;

	if(!isset($config_offset)) {
		$config_offset = Jconfig::getInstance()->config_offset;
	}

	if($date == '0000-00-00 00:00:00') return $date;//database::$_nullDate - при ошибках парсера

	if($format == '') {
		// %Y-%m-%d %H:%M:%S
		$format = _DATE_FORMAT_LC;
	}
	if(is_null($offset)) {
		$offset = $config_offset;
	}
	if($date && ereg("([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})",$date,$regs)) {
		$date = mktime($regs[4],$regs[5],$regs[6],$regs[2],$regs[3],$regs[1]);
		$date = strftime($format,$date + ($offset* 60* 60));
	}

	return $date;
}

/**
 * Returns current date according to current local and time offset
 * @param string format optional format for strftime
 * @returns current date
 */
function mosCurrentDate($format = "") {
	static $config_offset;

	if(!isset($config_offset)) {
		$config_offset = Jconfig::getInstance()->config_offset;
	}

	if($format == '') {
		$format = _DATE_FORMAT_LC;
	}

	$date = strftime($format,time() + ($config_offset* 60* 60));
	return $date;
}

/**
 * Utility function to provide ToolTips
 * @param string ToolTip text
 * @param string Box title
 * @returns HTML code for ToolTip
 */
function mosToolTip($tooltip,$title = '',$width = '',$image = 'tooltip.png',$text ='',$href = '#',$link = 1) {

	if($width) {
		$width = ', WIDTH, \''.$width.'\'';
	}
	if($title) {
		$title = ', CAPTION, \''.$title.'\'';
	}
	if(!$text) {
		$image = JPATH_SITE.'/includes/js/ThemeOffice/'.$image;
		$text = '<img src="'.$image.'" border="0" alt="tooltip"/>';
	}
	$style = 'style="text-decoration: none; color: #333;"';
	if($href) {
		$style = '';
	} else {
		$href = '#';
	}

	$mousover = 'return overlib(\''.$tooltip.'\''.$title.', BELOW, RIGHT'.$width.');';

	$tip = "";
	if($link) {
		$tip .= '<a href="'.$href.'" onmouseover="'.$mousover.'" onmouseout="return nd();" '.$style.'>'.$text.'</a>';
	} else {
		$tip .= '<span onmouseover="'.$mousover.'" onmouseout="return nd();" '.$style.'>'.$text.'</span>';
	}

	return $tip;
}

/**
 * Utility function to provide Warning Icons
 * @param string Warning text
 * @param string Box title
 * @returns HTML code for Warning
 */
function mosWarning($warning,$title = _MOS_WARNING) {
	$mouseover = 'return overlib(\''.$warning.'\', CAPTION, \''.$title.'\', BELOW, RIGHT);';
	$tip = '<a href="javascript: void(0)" onmouseover="'.$mouseover.'" onmouseout="return nd();">';
	$tip .= '<img src="'.JPATH_SITE.'/includes/js/ThemeOffice/warning.png" border="0" alt="'._WARNING.'"/></a>';
	return $tip;
}

/**
 * Function to create a mail object for futher use (uses phpMailer)
 * @param string From e-mail address
 * @param string From name
 * @param string E-mail subject
 * @param string Message body
 * @return object Mail object
 */
function mosCreateMail($from = '',$fromname = '',$subject='',$body='') {

	mosMainFrame::addLib('phpmailer');
	$mail = new mosPHPMailer();

	$config = Jconfig::getInstance();

	$mail->PluginDir = JPATH_BASE.DS.'includes/libraries/phpmailer/';
	$mail->SetLanguage(_LANGUAGE,JPATH_BASE.DS.'includes/libraries/phpmailer/language/');
	$mail->CharSet = substr_replace(_ISO,'',0,8);
	$mail->IsMail();
	$mail->From = $from?$from:$config->config_mailfrom;
	$mail->FromName = $fromname ? $fromname : $config->config_fromname;
	$mail->Mailer = $config->config_mailer;

	// Add smtp values if needed
	if($config->config_mailer == 'smtp') {
		$mail->SMTPAuth = $config->config_smtpauth;
		$mail->Username = $config->config_smtpuser;
		$mail->Password = $config->config_smtppass;
		$mail->Host = $config->config_smtphost;
	} else // Set sendmail path
		if($config->config_mailer == 'sendmail') {
			if(isset($config->config_sendmail)) $mail->Sendmail = $config->config_sendmail;
		} // if

	$mail->Subject = $subject;
	$mail->Body = $body;

	return $mail;
}

/**
 * Mail function (uses phpMailer)
 * @param string From e-mail address
 * @param string From name
 * @param string/array Recipient e-mail address(es)
 * @param string E-mail subject
 * @param string Message body
 * @param boolean false = plain text, true = HTML
 * @param string/array CC e-mail address(es)
 * @param string/array BCC e-mail address(es)
 * @param string/array Attachment file name(s)
 * @param string/array ReplyTo e-mail address(es)
 * @param string/array ReplyTo name(s)
 * @return boolean
 */
function mosMail($from,$fromname,$recipient,$subject,$body,$mode = 0,$cc = null,$bcc = null,$attachment = null,$replyto = null,$replytoname = null) {
	$config = Jconfig::getInstance();

	// Allow empty $from and $fromname settings (backwards compatibility)
	if($from == '') {
		$from = $config->config_mailfrom;
	}
	if($fromname == '') {
		$fromname = $config->config_fromname;
	}

	// Filter from, fromname and subject
	if(!JosIsValidEmail($from) || !JosIsValidName($fromname) || !JosIsValidName($subject)) {
		return false;
	}

	$mail = mosCreateMail($from,$fromname,$subject,$body);

	// activate HTML formatted emails
	if($mode) {
		$mail->IsHTML(true);
	}

	if(is_array($recipient)) {
		foreach($recipient as $to) {
			if(!JosIsValidEmail($to)) {
				return false;
			}
			$mail->AddAddress($to);
		}
	} else {
		if(!JosIsValidEmail($recipient)) {
			return false;
		}
		$mail->AddAddress($recipient);
	}
	if(isset($cc)) {
		if(is_array($cc)) {
			foreach($cc as $to) {
				if(!JosIsValidEmail($to)) {
					return false;
				}
				$mail->AddCC($to);
			}
		} else {
			if(!JosIsValidEmail($cc)) {
				return false;
			}
			$mail->AddCC($cc);
		}
	}
	if(isset($bcc)) {
		if(is_array($bcc)) {
			foreach($bcc as $to) {
				if(!JosIsValidEmail($to)) {
					return false;
				}
				$mail->AddBCC($to);
			}
		} else {
			if(!JosIsValidEmail($bcc)) {
				return false;
			}
			$mail->AddBCC($bcc);
		}
	}
	if($attachment) {
		if(is_array($attachment)) {
			foreach($attachment as $fname) {
				$mail->AddAttachment($fname);
			}
		} else {
			$mail->AddAttachment($attachment);
		}
	}
	//Important for being able to use mosMail without spoofing...
	if($replyto) {
		if(is_array($replyto)) {
			reset($replytoname);
			foreach($replyto as $to) {
				$toname = ((list($key,$value) = each($replytoname))?$value:'');
				if(!JosIsValidEmail($to) || !JosIsValidName($toname)) {
					return false;
				}
				$mail->AddReplyTo($to,$toname);
			}
		} else {
			if(!JosIsValidEmail($replyto) || !JosIsValidName($replytoname)) {
				return false;
			}
			$mail->AddReplyTo($replyto,$replytoname);
		}
	}
	$mailssend = $mail->Send();
	return $mailssend;
} // mosMail

/**
 * Checks if a given string is a valid email address
 *
 * @param	string	$email	String to check for a valid email address
 * @return	boolean
 */
function JosIsValidEmail($email) {
	$valid = preg_match('/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/',$email);
	return $valid;
}

/**
 * Checks if a given string is a valid (from-)name or subject for an email
 *
 * @since		1.0.11
 * @deprecated	1.5
 * @param		string		$string		String to check for validity
 * @return		boolean
 */
function JosIsValidName($string) {
	/*
	* The following regular expression blocks all strings containing any low control characters:
	* 0x00-0x1F, 0x7F
	* These should be control characters in almost all used charsets.
	* The high control chars in ISO-8859-n (0x80-0x9F) are unused (e.g. http://en.wikipedia.org/wiki/ISO_8859-1)
	* Since they are valid UTF-8 bytes (e.g. used as the second byte of a two byte char),
	* they must not be filtered.
	*/
	$invalid = preg_match('/[\x00-\x1F\x7F]/',$string);
	return ($invalid) ? false : true;
}

/**
 * Initialise GZIP
 */
function initGzip() {
	global $do_gzip_compress;

	$do_gzip_compress = false;

	if(Jconfig::getInstance()->config_gzip == 1) {
		$phpver = phpversion();
		$useragent = mosGetParam($_SERVER,'HTTP_USER_AGENT','');
		$canZip = mosGetParam($_SERVER,'HTTP_ACCEPT_ENCODING','');
		$gzip_check = 0;
		$zlib_check = 0;
		$gz_check = 0;
		$zlibO_check = 0;
		$sid_check = 0;
		if(strpos($canZip,'gzip') !== false) {
			$gzip_check = 1;
		}
		if(extension_loaded('zlib')) {
			$zlib_check = 1;
		}
		if(function_exists('ob_gzhandler')) {
			$gz_check = 1;
		}
		if(ini_get('zlib.output_compression')) {
			$zlibO_check = 1;
		}
		if(ini_get('session.use_trans_sid')) {
			$sid_check = 1;
		}
		if($phpver >= '4.0.4pl1' && (strpos($useragent,'compatible') !== false || strpos($useragent,'Gecko') !== false)) {
			if(($gzip_check || isset($_SERVER['---------------'])) && $zlib_check && $gz_check && !$zlibO_check && !$sid_check) {
				ob_start('ob_gzhandler');
				return;
			}
		} elseif($phpver > '4.0') {
			if($gzip_check) {
				if($zlib_check) {
					$do_gzip_compress = true;
					ob_start();
					ob_implicit_flush(0);
					header('Content-Encoding: gzip');
					return;
				}
			}
		}
	}
	ob_start();
}

/**
 * Perform GZIP
 */
function doGzip() {
	global $do_gzip_compress;

	if($do_gzip_compress) {
		$gzip_contents = ob_get_contents();
		ob_end_clean();
		$gzip_size = strlen($gzip_contents);
		$gzip_crc = crc32($gzip_contents);
		$gzip_contents = gzcompress($gzip_contents,9);
		$gzip_contents = substr($gzip_contents,0,strlen($gzip_contents) - 4);
		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $gzip_contents;
		echo pack('V',$gzip_crc);
		echo pack('V',$gzip_size);
	} else {
		ob_end_flush();
	}
}

/**
 * Random password generator
 * @return password
 */
function mosMakePassword($length = 8) {
	$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$makepass = '';
	mt_srand(10000000* (double)microtime());
	for($i = 0; $i < $length; $i++) $makepass .= $salt[mt_rand(0,61)];
	return $makepass;
}


/**
 * Class mosMambot
 * @package Joostina
 */
class mosMambot extends mosDBTable {
	/**
	 @var int*/
	var $id = null;
	/**
	 @var varchar*/
	var $name = null;
	/**
	 @var varchar*/
	var $element = null;
	/**
	 @var varchar*/
	var $folder = null;
	/**
	 @var tinyint unsigned*/
	var $access = null;
	/**
	 @var int*/
	var $ordering = null;
	/**
	 @var tinyint*/
	var $published = null;
	/**
	 @var tinyint*/
	var $iscore = null;
	/**
	 @var tinyint*/
	var $client_id = null;
	/**
	 @var int unsigned*/
	var $checked_out = null;
	/**
	 @var datetime*/
	var $checked_out_time = null;
	/**
	 @var text*/
	var $params = null;

	function mosMambot(&$db) {
		$this->mosDBTable('#__mambots','id',$db);
	}
}

/**
 * Plugin handler
 * @package Joostina
 */
class mosMambotHandler {
	/**
	 @var array An array of functions in event groups*/
	var $_events = null;
	/**
	 @var array An array of lists*/
	var $_lists = null;
	/**
	 @var array An array of mambots*/
	var $_bots = null;
	/**
	 @var int Index of the mambot being loaded*/
	var $_loading = null;
	/**
	 @var array An array of the content mambots in the system*/
	var $_content_mambots = null;
	/**
	 @var array An array of the content mambot params*/
	var $_content_mambot_params = array();
	/**
	 @var array An array of the search mambot params*/
	var $_search_mambot_params = array();
	/**
	 * @var array An array of the  mambot params
	 */
	var $_mambot_params = array();

	private $_config = null;
	private $_db = null;
	private $_admin = false;
	/**
	 * Constructor
	 */
	function mosMambotHandler() {
		$this->_db = database::getInstance();
		$config = Jconfig::getInstance();
		$this->_config = array('config_disable_access_control'=>$config->config_disable_access_control,'config_use_unpublished_mambots'=>$config->config_use_unpublished_mambots);
		$this->_events = array();
		unset($config);
	}

	/**
	 * Loads all the bot files for a particular group
	 * @param string The group name, relates to the sub-directory in the mambots directory
	 */
	function loadBotGroup($group, $load = 0) {
		global $my;

		$config = $this->_config;
		$database = $this->_db;

		if(is_object($my)) {
			$gid = $my->gid;
		} else {
			$gid = 0;
		}
		$group = trim($group);

		$where_ac = ($config['config_disable_access_control']==0) ? ' AND access <= '.(int)$gid : '';

		switch($group) {
			case 'content':
				if(!defined('_JOS_CONTENT_MAMBOTS')) {
					/** ensure that query is only called once*/
					define('_JOS_CONTENT_MAMBOTS',1);
					$where_ac_2 = $where_ac.($config['config_use_unpublished_mambots']==1) ? ' published=1 AND':'';
					$query = 'SELECT folder, element, published, params FROM #__mambots WHERE '.$where_ac_2.' folder = \'content\' AND client_id=0 ORDER BY ordering ASC';
					$database->setQuery($query);
					// load query into class variable _content_mambots
					if(!($this->_content_mambots = $database->loadObjectList())) {
						return false;
					}
					foreach($this->_content_mambots as $bot) {
						$this->_content_mambot_params[$bot->element] = new stdClass();
						$this->_content_mambot_params[$bot->element]->params = $bot->params;
					}
				}
				// pull bots to be processed from class variable
				$bots = $this->_content_mambots;
				break;

			case 'search':
				if(!defined('_JOS_SEARCH_MAMBOTS')) {
					define('_JOS_SEARCH_MAMBOTS',1);

					$query = 'SELECT folder, element, published, params FROM #__mambots WHERE published = 1'.$where_ac.' AND folder = \'search\' ORDER BY ordering ASC';
					$database->setQuery($query);
					if(!($this->_search_mambot = $database->loadObjectList())) {
						return false;
					}

					foreach($this->_search_mambot as $bot) {
						$this->_search_mambot_params[str_replace('.searchbot','',$bot->element)]->params = $bot->params;
					}
				}

				// pull bots to be processed from class variable
				$bots = $this->_search_mambot;
				break;

			default:
				$query = 'SELECT folder, element, published, params FROM #__mambots WHERE published = 1'.$where_ac.' AND folder = '.$database->Quote($group).' AND client_id=0 ORDER BY ordering ASC';
				$database->setQuery($query);
				if(!($bots = $database->loadObjectList())) {
					return false;
				}
				break;
		}

		// load bots found by queries
		$n = count($bots);
		for($i = 0; $i < $n; $i++) {
			$this->loadBot($bots[$i]->folder,$bots[$i]->element,$bots[$i]->published,$bots[$i]->params);
		}

		return (!$load) ? true : $bots;
	}
	/**
	 * Loads the bot file
	 * @param string The folder (group)
	 * @param string The elements (name of file without extension)
	 * @param int Published state
	 * @param string The params for the bot
	 */
	function loadBot($folder,$element,$published,$params = '') {
		global $_MAMBOTS;

		$path_bot = JPATH_BASE.DS.'mambots';

		$path = $path_bot.DS.$folder.DS.$element.'.php';
		if(file_exists($path)) {
			$this->_loading = count($this->_bots);
			$bot = new stdClass;
			$bot->folder = $folder;
			$bot->element = $element;
			$bot->published = $published;
			$bot->lookup = $folder.DS.$element;
			$bot->params = $params;
			$this->_bots[] = $bot;
			$this->_mambot_params[$element] = $params;
			$lang = mosMainFrame::getLangFile('bot_'.$element);
			if($lang) {
				include_once($lang);
			}
			require_once ($path);
			$this->_loading = null;
		}
		return true;
	}
	/**
	 * Registers a function to a particular event group
	 * @param string The event name
	 * @param string The function name
	 */
	function registerFunction($event,$function) {
		$this->_events[$event][] = array($function,$this->_loading);
	}
	/**
	 * Makes a option for a particular list in a group
	 * @param string The group name
	 * @param string The list name
	 * @param string The value for the list option
	 * @param string The text for the list option
	 */
	function addListOption($group,$listName,$value,$text = '') {
		$this->_lists[$group][$listName][] = mosHTML::makeOption($value,$text);
	}
	/**
	 * @param string The group name
	 * @param string The list name
	 * @return array
	 */
	function getList($group,$listName) {
		return $this->_lists[$group][$listName];
	}
	/**
	 * Calls all functions associated with an event group
	 * @param string The event name
	 * @param array An array of arguments
	 * @param boolean True is unpublished bots are to be processed
	 * @return array An array of results from each function call
	 */
	function trigger($event,$args = null,$doUnpublished = false) {
		$result = array();
		if($args === null) {
			$args = array();
		}
		if($doUnpublished) {
			// prepend the published argument
			array_unshift($args,null);
		}
		if(isset($this->_events[$event])) {
			foreach($this->_events[$event] as $func) {
				if(function_exists($func[0])) {
					if($doUnpublished) {
						$args[0] = $this->_bots[$func[1]]->published;
						$result[] = call_user_func_array($func[0], $args);
					} elseif($this->_bots[$func[1]]->published) {
						$result[] = call_user_func_array($func[0], $args);
					}
				}
			}
		}

		return $result;
	}
	/**
	 * Same as trigger but only returns the first event and
	 * allows for a variable argument list
	 * @param string The event name
	 * @return array The result of the first function call
	 */
	function call($event) {
		$args = &func_get_args();
		array_shift($args);
		if(isset($this->_events[$event])) {
			foreach($this->_events[$event] as $func) {
				if(function_exists($func[0])) {
					if($this->_bots[$func[1]]->published) {
						return call_user_func_array($func[0], array(&$args) );
					}
				}
			}
		}
		return null;
	}

	//Адресный вызов мамбота
	function call_mambot($event, $element, $args) {

		if(isset($this->_events[$event])) {
			foreach($this->_events[$event] as $func) {
				if($this->_bots[$func[1]]->element == $element && function_exists($func[0])) {
					$this->_mambot_params[$element] = $this->_bots[$func[1]]->params;
					if($this->_bots[$func[1]]->published) {
						return call_user_func_array($func[0], array(&$args) );
					}
				}
			}
		}
		return null;
	}
}

/*
* Includes pathway file
*/
function mosPathWay() {
	require_once (JPATH_BASE.'/includes/pathway.php');
}

/**
 * Displays a not authorised message
 *
 * If the user is not logged in then an addition message is displayed.
 */
function mosNotAuth() {
	global $my;
	echo _NOT_AUTH;
	if($my->id < 1) {
		echo "<br />"._DO_LOGIN;
	}
}

/**
 * Replaces &amp; with & for xhtml compliance
 *
 * Needed to handle unicode conflicts due to unicode conflicts
 */
function ampReplace($text) {
	$text = str_replace('&&','*--*',$text);
	$text = str_replace('&#','*-*',$text);
	$text = str_replace('&amp;','&',$text);
	$text = preg_replace('|&(?![\w]+;)|','&amp;',$text);
	$text = str_replace('*-*','&#',$text);
	$text = str_replace('*--*','&&',$text);
	return $text;
}

/**
 * Function to convert array to integer values
 * @param array
 * @param int A default value to assign if $array is not an array
 * @return array
 */
function mosArrayToInts(&$array,$default = null) {
	if(is_array($array)) {
		foreach($array as $key => $value) {
			$array[$key] = (int)$value;
		}
	} else {
		if(is_null($default)) {
			$array = array();
			return array(); // Kept for backwards compatibility
		} else {
			$array = array((int)$default);
			return array($default); // Kept for backwards compatibility
		}
	}
}

/*
* Получение массива значений
* $name - название переменной
*/
function josGetArrayInts($name,$type = null) {
	if($type == null) {
		$type = $_POST;
	}

	$array = mosGetParam($type,$name,array(0));

	mosArrayToInts($array);

	if(!is_array($array)) {
		$array = array(0);
	}

	return $array;
}

/**
 * Provides a secure hash based on a seed
 * @param string Seed string
 * @return string
 */
function mosHash($seed) {
	return md5($GLOBALS['mosConfig_secret'].md5($seed));
}

function josSpoofCheck( $header=NULL, $alt=NULL , $method = 'post') {

	switch(strtolower($method)) {
		case 'get':
			$validate 	= mosGetParam( $_GET, josSpoofValue($alt), 0 );
			break;
		case 'request':
			$validate 	= mosGetParam( $_REQUEST, josSpoofValue($alt), 0 );
			break;
		case 'post':
		default:
			$validate 	= mosGetParam( $_POST, josSpoofValue($alt), 0 );
			break;
	}

	// probably a spoofing attack
	if (!$validate) {
		header( 'HTTP/1.0 403 Forbidden' );
		mosErrorAlert( _NOT_AUTH );
		return;
	}

	// First, make sure the form was posted from a browser.
	// For basic web-forms, we don't care about anything
	// other than requests from a browser:
	if (!isset( $_SERVER['HTTP_USER_AGENT'] )) {
		header( 'HTTP/1.0 403 Forbidden' );
		mosErrorAlert( _NOT_AUTH );
		return;
	}

	// Make sure the form was indeed POST'ed:
	//  (requires your html form to use: action="post")
	if (!$_SERVER['REQUEST_METHOD'] == 'POST' ) {
		header( 'HTTP/1.0 403 Forbidden' );
		mosErrorAlert( _NOT_AUTH );
		return;
	}

	if ($header) {
		// Attempt to defend against header injections:
		$badStrings = array(
				'Content-Type:',
				'MIME-Version:',
				'Content-Transfer-Encoding:',
				'bcc:',
				'cc:'
		);

		// Loop through each POST'ed value and test if it contains
		// one of the $badStrings:
		_josSpoofCheck( $_POST, $badStrings );
	}
}

function _josSpoofCheck($array,$badStrings) {
	// Loop through each $array value and test if it contains
	// one of the $badStrings
	foreach($array as $v) {
		if(is_array($v)) {
			_josSpoofCheck($v,$badStrings);
		} else {
			foreach($badStrings as $v2) {
				if(stripos($v,$v2) !== false) {
					header('HTTP/1.0 403 Forbidden');
					mosErrorAlert(_NOT_AUTH);
					exit(); // mosErrorAlert dies anyway, double check just to make sure
				}
			}
		}
	}
}

/**
 * Method to determine a hash for anti-spoofing variable names
 *
 * @return	string	Hashed var name
 * @static
 */
function josSpoofValue($alt=NULL) {
	global $my;

	if ($alt) {
		$random = ( $alt == 1 ) ? $random = date( 'Ymd' ) : $alt . date( 'Ymd' );
	} else {
		$random	= date( 'dmY' );
	}

	return 'j' . mosHash( JPATH_BASE . $random . $my->id );
}
/**
 * A simple helper function to salt and hash a clear-text password.
 *
 * @since	1.0.13
 * @param	string	$password	A plain-text password
 * @return	string	An md5 hashed password with salt
 */
// TODO использщвать этуфункцию активнее!
function josHashPassword($password) {
	// Salt and hash the password
	$salt = mosMakePassword(16);
	$crypt = md5($password.$salt);
	return $crypt.':'.$salt;
}

/**
 * Объединение расширений системы в одно пространство имён
 *
 */
class joostina_api {
	/**
	 * Оптимизация таблиц базы данных
	 * Основано на мамботе OptimizeTables - smart (C) 2006, Joomlaportal.ru. All rights reserved
	 */
	public static function optimizetables() {
		// 1 раз из 50 вызовем провреку и оптиммизацию таблиц
		if(mt_rand(1,50)==1) {
			register_shutdown_function('joostina_api::_optimizetables');
		}
	}

	// Непосредственно оптимизация таблиц базы данных
	public static function _optimizetables() {

		$config = Jconfig::getInstance();

		$flag = $config->config_cachepath.'/optimizetables.flag';
		$filetime = @filemtime($flag);
		$currenttime = time();
		if($filetime + 86400 > $currenttime) {
			return;
		}
		$f = fopen($flag,'w+');
		@fwrite($f,time());
		fclose($f);
		@chmod($flag,0777);

		$database = database::getInstance();
		$database->setQuery("OPTIMIZE TABLE `". implode('`,`', $database->getTableList() ) ."`;")->query();
	}
}

// отладка определённой переменной
function _xdump( $var, $text='<pre>' ) {
	echo $text;
	print_r( $var );
	echo "\n";
}


// класс работы с контентом
_USE_COM_CONTENT ? require_once(JPATH_BASE.'/components/com_content/content.class.php') : null;

// класс работы с пользователями
require_once(JPATH_BASE.'/components/com_users/users.class.php');

/**
 @global mosPlugin $_MAMBOTS*/
$_MAMBOTS = new mosMambotHandler();
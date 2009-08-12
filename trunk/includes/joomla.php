<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

// флаг использования ядра
DEFINE('_MOS_MAMBO_INCLUDED',1);
// разделитель каталогов
DEFINE('DS',DIRECTORY_SEPARATOR );
// каталог администратора
DEFINE('ADMINISTRATOR_DIRECTORY','administrator');
// формат даты
DEFINE('_CURRENT_SERVER_TIME_FORMAT','%Y-%m-%d %H:%M:%S');
// текущее время сервера
DEFINE('_CURRENT_SERVER_TIME',date('Y-m-d H:i',time()));
// схемы не http/https протоколов
DEFINE('_URL_SCHEMES','data:, file:, ftp:, gopher:, imap:, ldap:, mailto:, news:, nntp:, telnet:, javascript:, irc:, mms:');

// пробуем устанавить более удобный режим работы
@set_magic_quotes_runtime(0);

// установка режима отображения ошибок
if($mosConfig_error_reporting == 0) {
	error_reporting(0);
}elseif($mosConfig_error_reporting > 0) {
	error_reporting($mosConfig_error_reporting);
}
/* ядро отладчика */
mosMainFrame::addLib('debug');
/* ядро для работы с юникодом */
mosMainFrame::addLib('utf8');
/* файл данных версии */
require_once ($mosConfig_absolute_path.'/includes/version.php');
/* ядро работы с XML */
require_once ($mosConfig_absolute_path.'/includes/joomla.xml.php');
/* класс фильтрации данных */
mosMainFrame::addLib('inputfilter');
/* класс работы с базой данных */
mosMainFrame::addLib('database');
$database = &database::getInstance();

/* класс работы с правами пользователей */
mosMainFrame::addLib('gacl');
$acl = &gacl::getInstance();

/* вспомогательная библиотека работы с массивами */
mosMainFrame::addLib('array');

// корректировка работы с данными полученными от сервера
if(isset($_SERVER['REQUEST_URI'])) {
	$request_uri = $_SERVER['REQUEST_URI'];
} else {
	$request_uri = $_SERVER['SCRIPT_NAME'];
	if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
		$request_uri .= '?'.$_SERVER['QUERY_STRING'];
	}
}
$_SERVER['REQUEST_URI'] = $request_uri;

/**
* Joostina! Mainframe class
*
* Provide many supporting API functions
* @package Joostina
*/
class mosMainFrame {
	/**
	@var database Internal database class pointer*/
	var $_db = null;
	/**
	@var object An object of configuration variables*/
	var $_config = null;
	/**
	@var object An object of path variables*/
	var $_path = null;
	/**
	@var mosSession The current session*/
	var $_session = null;
	/**
	@var string The current template*/
	var $_template = null;
	/**
	@var array An array to hold global user state within a session*/
	var $_userstate = null;
	/**
	@var array An array of page meta information*/
	var $_head = null;
	/**
	@var string Custom html string to append to the pathway*/
	var $_custom_pathway = null;
	/**
	@var boolean True if in the admin client*/
	var $_isAdmin = false;
	/**
	 * флаг визуального редактора
	 */
	var $allow_wysiwyg = 0;
	/**
	@var массив данных выводящися в нижней части страницы */
	var $_footer = null;
	/**
    * системное сообщение
    */ 
	var $mosmsg = '';	
	/**
	 * текущий язык
	 */
	var $lang = null;
	
	var $_multisite = 0;
	var $_multisite_params = null;

	/**
	* Class constructor
	* @param database A database connection object
	* @param string The url option
	* @param string The path of the mos directory
	*/
	function mosMainFrame($db,$option,$basePath=null,$isAdmin = false) {
		unset($db,$option);

		$this->_db = &database::getInstance();

		if(!$isAdmin){ // работаем с меню в один запрос
			$menu = &mosMenu::getInstance();
			$this->set('all_menu',$menu->get_menu());
			$this->set('all_menu_links',$menu->get_menu_links());
			$option = $this->get_option();
			unset($menu);
		}else{// для панели управления работаем с меню напрямую
			$option = strval(strtolower(mosGetParam($_REQUEST,'option')));
		}

		$this->_setTemplate($isAdmin);
		$this->_setAdminPaths($option,$this->getCfg('absolute_path'));
		$this->_isAdmin = (boolean)$isAdmin;
		$this->set('now',date('Y-m-d H:i:s',time()));

		if(isset($_SESSION['session_userstate'])) {
			$this->_userstate = &$_SESSION['session_userstate'];
		} else {
			$this->_userstate = null;
		}

		if(!$isAdmin){
			$this->getCfg('components_access') ? $this->check_option($option): null;
			$this->_head = array();
			$this->_head['title'] = $this->getCfg('sitename');
			$this->_head['meta'] = array();
			$this->_head['custom'] = array();
			$this->detect();
			$this->set('loadOverlib',false);
		}
	}

	function &getInstance($isAdmin = false){
		static $instance;

		if (!is_object( $instance )) {
			$instance = new mosMainFrame(null,null,null,$isAdmin);
		}

		return $instance;
	}

	/**
	* Gets the id number for a client
	* @param mixed A client identifier
	*/
	function getClientID($client) {
		switch($client) {
			case '2':
			case 'installation':
				return 2;
				break;
			case '1':
			case 'admin':
			case 'administrator':
				return 1;
				break;
			case '0':
			case 'site':
			case 'front':
			default:
				return 0;
				break;
		}
		return 0;
	}

	/**
	* Gets the client name
	* @param int The client identifier
	* @return strint The text name of the client
	*/
	function getClientName($client_id) {
		// do not translate
		$clients = array('site','admin','installer');
		return mosGetParam($clients,$client_id,'unknown');
	}

	/**
	* Gets the base path for the client
	* @param mixed A client identifier
	* @param boolean True (default) to add traling slash
	*/
	function getBasePath($client = 0,$addTrailingSlash = true) {

		switch($client) {
			case '0':
			case 'site':
			case 'front':
			default:
				return mosPathName(Jconfig::getInstance()->config_absolute_path,$addTrailingSlash);
				break;

			case '2':
			case 'installation':
				return mosPathName(Jconfig::getInstance()->config_absolute_path.'/installation',$addTrailingSlash);
				break;

			case '1':
			case 'admin':
			case 'administrator':
				return mosPathName(Jconfig::getInstance()->config_absolute_path.'/'.ADMINISTRATOR_DIRECTORY,$addTrailingSlash);
				break;
		}
	}
	
	/**
	* Подключение библиотеки
	* @param string $lib Название библиотеки. Может быть сформировано как: `lib_name`, `lib_name/lib_name.php`, `lib_name.php`
	* @param string $dir Директория библиотеки. Необязательный параметр. По умолчанию, поиск файла осуществляется в 'includes/libraries' 
	*/
	function addLib($lib, $dir = ''){
		
		if(!$dir){
			$dir = 'includes/libraries';
		}
		$config = &Jconfig::getInstance();
		if(is_file($config->config_absolute_path.DS.$dir.DS.$lib)){
			$lib = $config->config_absolute_path.DS.$dir.DS.$lib;
		}else if(is_file($config->config_absolute_path.DS.$dir.DS.$lib.DS.$lib.'.php')){
			$lib = $config->config_absolute_path.DS.$dir.DS.$lib.DS.$lib.'.php';
		}else{
			return false;
		}
		require_once($lib);
	}

	
	function getLangFile($name = ''){
		$absolute_path = $this->getCfg('absolute_path');
		if(!$name){
			$file = 'system';
			return $absolute_path.DS.'language'.DS.$this->lang.DS.'system.php';
		}else{
			$file = $name;
		}
		if($this->_isAdmin){
			if(is_file($absolute_path.DS.'language'.DS.$this->lang.DS.'administrator'.DS.$file.'.php')){
				return $absolute_path.DS.'language'.DS.$this->lang.DS.'administrator'.DS.$file.'.php';
			}
			else{
				if(is_file($absolute_path.DS.'language'.DS.$this->lang.DS.'frontend'.DS.$file.'.php')){
					return $absolute_path.DS.'language'.DS.$this->lang.DS.'frontend'.DS.$file.'.php';
				}
			}	
		}
		else{
			if(is_file($absolute_path.DS.'language'.DS.$this->lang.DS.'frontend'.DS.$file.'.php')){
				return $absolute_path.DS.'language'.DS.$this->lang.DS.'frontend'.DS.$file.'.php';
			}
		}		

		return null;

	}
	
	
	/**
	* установка title страницы
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
			}else{
				$pageownname = null;
				$page_title = $title ? $sitename.$tseparator.$title : $sitename;
			}
		}

		// название страницы, не title!
		$this->_head['pagename'] = $pageownname ? $pageownname : $title;

		switch($this->getCfg('pagetitles_first')) {
			case '0':
			case '1':
			default:
				$this->_head['title'] = $page_title;
				break;
			case '2':
				$this->_head['title'] = $sitename;
				break;
			case '3':
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
		$name		= Jstring::trim(htmlspecialchars($name));
		$content	= Jstring::trim(htmlspecialchars($content));
		$prepend	= Jstring::trim($prepend);
		$append		= Jstring::trim($append);
		$this->_head['meta'][] = array($name,$content,$prepend,$append);
	}
	/**
	* @param string The value of the name attibute
	* @param string The value of the content attibute to append to the existing
	* Tags ordered in with Site Keywords and Description first
	*/
	function appendMetaTag($name,$content) {
		$name = Jstring::trim(htmlspecialchars($name));
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
	* Adds a custom html string to the head block
	* @param string The html to add to the head
	*/
	function addCustomHeadTag($html) {
		$this->_head['custom'][] = trim($html);
	}
	/**
	* @return string
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

		if(isset($params['jquery']) && $params['jquery']==1){
			$head[] = mosCommonHTML::loadJquery(true,true);
		}

		if(isset($params['js']) && $params['js']==1 && isset($this->_head['js']) ){
			$i = 0;
			foreach($this->_head['js'] as $html) {
				$head[] = $html;
				unset($this->_head['js'][$i]);
				$i++;
			}
		}

		if(isset($params['css']) && $params['css']==1 && isset($this->_head['css']) ){
			foreach($this->_head['css'] as $html) {
				$head[] = $html;
			}
		}
		//unset($this->_head);
		return implode("\n",$head)."\n";
	}

	function getFooter($params=array('fromheader'=>1,'custom'=>0,'js'=>1,'css'=>1)) {
		$footer = array();

		if(isset($params['fromheader']) && $params['fromheader']==1 ){
			$this->_footer = $this->_head; 
		}

		if(isset($params['custom']) && $params['custom']==1 && isset($this->_footer['custom'])){
			foreach($this->_footer['custom'] as $html) {
				$footer[] = $html; 
			}
		}

		if(isset($params['jquery']) && $params['jquery']==1 ){
			$footer[] = mosCommonHTML::loadJquery(true,true);
		}

		if(isset($params['js']) && $params['js']==1 && isset($this->_footer['js'])){
			foreach($this->_footer['js'] as $html) {
				$footer[] = $html;  
			} 
		}

		if(isset($params['css'])  && $params['css']==1 && isset($this->_footer['css']) ){
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
	*/
	function addJS($path, $footer = ''){
		if($footer){
			$this->_footer[$footer][] = '<script language="JavaScript" src="'. $path .'" type="text/javascript"></script>';
		}
		else{
			$this->_head['js'][] = '<script language="JavaScript" src="'. $path .'" type="text/javascript"></script>';
				}
				
	}
	/**
	* добавление css файлов в шапку страницы
	*/
	function addCSS($path){
		$this->_head['css'][] = '<link type="text/css" rel="stylesheet" href="'. $path .'" />';
	}

	/**
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

	function appendPathWay($html) {
		$this->_custom_pathway[] = $html;
	}

	/**
	* Gets the value of a user state variable
	* @param string The name of the variable
	*/
	function getUserState($var_name) {
		if(is_array($this->_userstate)) {
			return mosGetParam($this->_userstate,$var_name,null);
		} else {
			return null;
		}
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

			// filter input
			$iFilter = new InputFilter();
			$this->_userstate[$var_name] = $iFilter->process($this->_userstate[$var_name]);
			return $this->_userstate[$var_name];
		} else {
			return null;
		}
	}
	/**
	* Sets the value of a user state variable
	* @param string The name of the variable
	* @param string The value of the variable
	*/
	function setUserState($var_name,$var_value) {
		if(is_array($this->_userstate)) {
			$this->_userstate[$var_name] = $var_value;
		}
	}
	/**
	* Initialises the user session
	*
	* Old sessions are flushed based on the configuration value for the cookie
	* lifetime. If an existing session, then the last access time is updated.
	* If a new session, a session id is generated and a record is created in
	* the jos_sessions table.
	*/
	function initSession() {
		if($this->getCfg('session_front')) return;

		// initailize session variables
		$session = &$this->_session;
		$session = new mosSession($this->_db);
		// purge expired sessions
		(rand(0,1)==1) ? $session->purge('core') : null;
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

	/*
	* Function used to conduct admin session duties
	* Added as of 1.0.8
	* Deperciated 1.1
	*/
	function initSessionAdmin($option,$task) {
		global $_VERSION;

		$_config = &Jconfig::getInstance();

		// logout check
		if($option == 'logout') {
			require $_config->config_absolute_path.'/'.ADMINISTRATOR_DIRECTORY.'/logout.php';
			exit();
		}

		$site = $_config->config_live_site;

		// check if session name corresponds to correct format
		if(session_name() != md5($site)) {
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
			mosRedirect($_config->config_live_site.'/'.ADMINISTRATOR_DIRECTORY.'/',_YOU_NEED_TO_AUTH);
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
					$this->_db->setQuery($query);
					$this->_db->query();
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
					if($link && strpos($link,'index2.php?option=com_') === 0 && $_VERSION->SITE == 1) {
						$now = time();

						$file = $this->getPath('com_xml','com_users');
						$params = &new mosParameters($my->params,$file,'component');

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
						$this->_db->setQuery($query);
						$this->_db->query();
					}

					mosRedirect($_config->config_live_site.'/'.ADMINISTRATOR_DIRECTORY.'/',_ADMIN_SESSION_ENDED);

				} else {
					// load variables into session, used to help secure /popups/ functionality
					$_SESSION['option'] = $option;
					$_SESSION['task'] = $task;
				}
			}
		} else
			if($session_id == '') {
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
	* Added as of 1.0.8
	* Deperciated 1.1
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
		$_config = &Jconfig::getInstance();
		$mainframe = &mosMainFrame::getInstance();
		
		if(!$site_name){
			$site_name = $_config->config_live_site;
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
	function sessionCookieValue($id = null) {
		$mainframe	= &mosMainFrame::getInstance();
		$type		= $mainframe->getCfg('session_type');
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
	function remCookieName_User() {
		$value = mosHash('remembermecookieusername'.mosMainFrame::sessionCookieName());

		return $value;
	}

	/*
	* Static Function used to generate the Rememeber Me Cookie Name for Password information
	* Added as of 1.0.8
	* Depreciated 1.1
	*/
	function remCookieName_Pass() {
		$value = mosHash('remembermecookiepassword'.mosMainFrame::sessionCookieName());

		return $value;
	}

	/*
	* Static Function used to generate the Remember Me Cookie Value for Username information
	* Added as of 1.0.8
	* Depreciated 1.1
	*/
	function remCookieValue_User($username) {
		$value = md5($username.mosHash(@$_SERVER['HTTP_USER_AGENT']));

		return $value;
	}

	/*
	* Static Function used to generate the Remember Me Cookie Value for Password information
	* Added as of 1.0.8
	* Depreciated 1.1
	*/
	function remCookieValue_Pass($passwd) {
		$value = md5($passwd.mosHash(@$_SERVER['HTTP_USER_AGENT']));

		return $value;
	}

	/**
	* Login validation function
	*
	* Username and encoded password is compare to db entries in the jos_users
	* table. A successful validation updates the current session record with
	* the users details.
	*/
	function login($username = null,$passwd = null,$remember = 0,$userid = null) {
		global $_VERSION;

		// если сесии на фронте отключены - прекращаем выполнение процедуры
		if($this->getCfg('session_front')) return;

		$acl = &gacl::getInstance();

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
			mosErrorAlert(_LOGIN_INCOMPLETE);
			exit();
		} else {
			if($remember && strlen($username) == 32 && $userid) {

				// query used for remember me cookie
				$harden = mosHash(@$_SERVER['HTTP_USER_AGENT']);

				$query = "SELECT id, name, username, password, usertype, block, gid FROM #__users WHERE id = ".(int)$userid;
				$this->_db->setQuery($query);
				$this->_db->loadObject($user);

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

				$this->_db->setQuery($query);
				$this->_db->loadObject($row);
			}

			if(is_object($row)) {
				// user blocked from login
				if($row->block == 1) {
					mosErrorAlert(_LOGIN_BLOCKED);
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
						$this->_db->setQuery($query);
						if(!$this->_db->query()) {
							echo 'error';
						}

					}
					list($hash,$salt) = explode(':',$row->password);
					$cryptpass = md5($passwd.$salt);

					if($hash != $cryptpass) {
						if($bypost) {
							mosErrorAlert(_LOGIN_INCORRECT);
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
				if($_VERSION->SITE) {
					// delete any old front sessions to stop duplicate sessions
					$query = "DELETE FROM #__session WHERE session_id != ".$this->_db->Quote($session->session_id)." AND username = ".$this->_db->Quote($row->username)." AND userid = ".(int)$row->id." AND gid = ".(int)$row->gid." AND guest = 0";
					$this->_db->setQuery($query);
					$this->_db->query();
				}

				// update user visit data
				$currentDate = date("Y-m-d H:i:s");

				$query = "UPDATE #__users SET lastvisitDate = ".$this->_db->Quote($currentDate)." WHERE id = ".(int)$session->userid;
				$this->_db->setQuery($query);
				if(!$this->_db->query()) {
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
				// а зачем чистить кэш после каждой авторизации?
				//mosCache::cleanCache();
			} else {
				if($bypost) {
					mosErrorAlert(_LOGIN_INCORRECT);
				} else {
					$this->logout();
					mosRedirect('index.php');
				}
				exit();
			}
		}
	}

	/**
	* User logout
	*
	* Reverts the current session record back to 'anonymous' parameters
	*/
	function logout() {
		// тоже странный момент, при при разлогинивании пользователя - чистить ВЕСЬ кэш...
		//mosCache::cleanCache();
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
	* @return mosUser A user object with the information from the current session
	* + хак для отключения ведения сессий на фронте
	*/
	function getUser() {

		if($this->_multisite == 2){
			$m_s = new stdClass();
			$m_s = $this->get('_multisite_params');
			if(isset($_COOKIE[$this->sessionCookieName($m_s->main_site)])){
				return $this->getUser_from_sess($_COOKIE[$this->sessionCookieName($m_s->main_site)]);
			}
		}

		$database = &database::getInstance();

		$user = new mosUser($database);

		if(Jconfig::getInstance()->config_session_front == 1) {
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
			$database->setQuery($query);
			$database->loadObject($my);
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
		/* чистка памяти */
		unset($user->_db);
		return $user;
	}
	
	
	function getUser_from_sess($sess_id) {
		
		$mainframe = &mosMainFrame::getInstance();
		$sess_id = mosMainFrame::sessionCookieValue($sess_id);
		
		$m_s = new stdClass();
		$m_s = $mainframe->get('_multisite_params');

		$database = new database($m_s->db_host,$m_s->db_user,$m_s->db_pass,$m_s->db_name,$m_s->table_preffix);
		$user = new mosUser($database);
		$user->id = 0; $user->gid = 0;
		
		// и кто это у нас тут такой, залогиненныыый
		$sql = "SELECT * FROM #__session WHERE session_id = '".$sess_id."' AND guest = 0";
		
		$row = null;
		$database->setQuery($sql);
		$database->loadObject($row);
		
		if($row){
			$user->id = $row->userid;
			
			$query = "SELECT id, name, username, usertype, email, avatar, block, sendEmail, registerDate, lastvisitDate, activation, params
			FROM #__users WHERE id = ".(int)$user->id;

			$database->setQuery($query);
			$database->loadObject($my);
			$user->params = $my->params;
			$user->name = $my->name;
			$user->username = $my->username;
			$user->email = $my->email;
			$user->avatar = $my->avatar;
			$user->block = $my->block;
			$user->sendEmail = $my->sendEmail;
			$user->registerDate = $my->registerDate;
			$user->lastvisitDate = $my->lastvisitDate;
			$user->activation = $my->activation;
			$user->usertype = $my->usertype; 
			
		}
		/* чистка памяти */
		unset($user->_db);	
		return $user;
	}
	
	/**
	* @param string The name of the variable (from configuration.php)
	* @return mixed The value of the configuration variable or null if not found
	*/
	function getCfg($varname) {
		$config = &Jconfig::getInstance();
		$varname = 'config_'.$varname;
		if(isset($config->$varname)) {
			return $config->$varname;
		} else {
			return null;
		}
	}
	/**  функция определения шаблона, если в панели управления указано что использовать один шаблон - сразу возвращаем его название, функцию не проводим до конца*/
	function _setTemplate($isAdmin = false) {
		global $Itemid;

		$config = &Jconfig::getInstance();

		// если у нас в настройках указан шаблон и определение идёт не для панели управления - возвращаем название шаблона из глобальной конфигурации
		if(!$isAdmin and $config->config_one_template != '...') {
			$this->_template = $config->config_one_template;
			return;
		}

		if($isAdmin) {
			if($config->config_admin_template=='...'){
				$query = 'SELECT template FROM #__templates_menu WHERE client_id = 1 AND menuid = 0';
				$this->_db->setQuery($query);
				$cur_template = $this->_db->loadResult();
				$path = $config->config_absolute_path.DS.ADMINISTRATOR_DIRECTORY.DS.'templates'.DS.$cur_template.DS.'index.php';
				if(!file_exists($path)) {
					$cur_template = 'joostfree';
				}
			}else{
				$cur_template = 'joostfree';
			}
		} else {

			$assigned = (!empty($Itemid) ? ' OR menuid = '.(int)$Itemid : '');

			static $_all_templates;

			if(!isset($_all_templates[$assigned])){
				$query = 'SELECT template FROM #__templates_menu WHERE client_id = 0 AND ( menuid = 0 '.$assigned.' ) ORDER BY menuid DESC';
				$this->_db->setQuery($query,0,1);
				$_all_templates["$assigned"] = $this->_db->loadResult();
			}

			$cur_template = $_all_templates["$assigned"];

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
				if(file_exists($config->config_absolute_path.DS.'templates'.DS.$jos_change_template.DS.'index.php')) {
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

	function getTemplate() {
		return $this->_template;
	}

	/**
	* Determines the paths for including engine and menu files
	* @param string The current option used in the url
	* @param string The base path from which to load the configuration file
	*/
	function _setAdminPaths($option,$basePath = '.') {
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
			$option = "com_$option";
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

		if(file_exists("$basePath/".ADMINISTRATOR_DIRECTORY."/components/$option/admin.$name.php")) {
			$this->_path->admin = "$basePath/".ADMINISTRATOR_DIRECTORY."/components/$option/admin.$name.php";
			$this->_path->admin_html = "$basePath/".ADMINISTRATOR_DIRECTORY."/components/$option/admin.$name.html.php";
		}

		if(file_exists("$basePath/administrator/components/$option/toolbar.$name.php")) {
			$this->_path->toolbar = "$basePath/".ADMINISTRATOR_DIRECTORY."/components/$option/toolbar.$name.php";
			$this->_path->toolbar_html = "$basePath/".ADMINISTRATOR_DIRECTORY."/components/$option/toolbar.$name.html.php";
			$this->_path->toolbar_default = "$basePath/".ADMINISTRATOR_DIRECTORY."/includes/toolbar.html.php";
		}

		if(file_exists("$basePath/components/$option/$name.class.php")) {
			$this->_path->class = "$basePath/components/$option/$name.class.php";
		} else
			if(file_exists("$basePath/".ADMINISTRATOR_DIRECTORY."/components/$option/$name.class.php")) {
				$this->_path->class = "$basePath/".ADMINISTRATOR_DIRECTORY."/components/$option/$name.class.php";
			} else
				if(file_exists("$basePath/includes/$name.php")) {
					$this->_path->class = "$basePath/includes/$name.php";
				}

		if($prefix == 'mod_' && file_exists("$basePath/".ADMINISTRATOR_DIRECTORY."/modules/$option.php")) {
			$this->_path->admin = "$basePath/".ADMINISTRATOR_DIRECTORY."/modules/$option.php";
			$this->_path->admin_html = "$basePath/".ADMINISTRATOR_DIRECTORY."/modules/mod_$name.html.php";
		} else
			if(file_exists("$basePath/".ADMINISTRATOR_DIRECTORY."/components/$option/admin.$name.php")) {
				$this->_path->admin = "$basePath/".ADMINISTRATOR_DIRECTORY."/components/$option/admin.$name.php";
				$this->_path->admin_html = "$basePath/".ADMINISTRATOR_DIRECTORY."/components/$option/admin.$name.html.php";
			} else {
				$this->_path->admin = "$basePath/".ADMINISTRATOR_DIRECTORY."/components/com_admin/admin.admin.php";
				$this->_path->admin_html = "$basePath/".ADMINISTRATOR_DIRECTORY."/components/com_admin/admin.admin.html.php";
			}
	}
	/**
	* Returns a stored path variable
	*
	*/
	function getPath($varname,$option = '') {
		$config = Jconfig::getInstance();


		if($option) {
			$temp = $this->_path;
			$this->_setAdminPaths($option,$this->getCfg('absolute_path'));
		}
		$result = null;
		if(isset($this->_path->$varname)) {
			$result = $this->_path->$varname;
		} else {
			switch($varname) {
				case 'com_xml':
					$name = substr($option,4);
					$path = $config->config_absolute_path.'/'.ADMINISTRATOR_DIRECTORY."/components/$option/$name.xml";
					if(file_exists($path)) {
						$result = $path;
					} else {
						$path = $config->config_absolute_path."/components/$option/$name.xml";
						if(file_exists($path)) {
							$result = $path;
						}
					}
					break;

				case 'mod0_xml':
					// Site modules
					if($option == '') {
						$path = $config->config_absolute_path.'/modules/custom.xml';
					} else {
						$path = $config->config_absolute_path."/modules/$option.xml";
					}
					if(file_exists($path)) {
						$result = $path;
					}
					break;

				case 'mod1_xml':
					// admin modules
					if($option == '') {
						$path = $config.DS.ADMINISTRATOR_DIRECTORY.'/modules/custom.xml';
					} else {
						$path = $config->config_absolute_path.DS.ADMINISTRATOR_DIRECTORY."/modules/$option.xml";
					}
					if(file_exists($path)) {
						$result = $path;
					}
					break;

				case 'bot_xml':
					// Site mambots
					$path = $config->config_absolute_path.DS.'mambots'.DS.$option.'.xml';
					if(file_exists($path)) {
						$result = $path;
					}
					break;

				case 'menu_xml':
					$path = $config->config_absolute_path.DS.ADMINISTRATOR_DIRECTORY."/components/com_menus/$option/$option.xml";
					if(file_exists($path)) {
						$result = $path;
					}
					break;

				case 'installer_html':
					$path = $config->config_absolute_path.DS.ADMINISTRATOR_DIRECTORY."/components/com_installer/$option/$option.html.php";
					if(file_exists($path)) {
						$result = $path;
					}
					break;

				case 'installer_class':
					$path = $config->config_absolute_path.DS.ADMINISTRATOR_DIRECTORY."/components/com_installer/$option/$option.class.php";
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
	* Detects a 'visit'
	*
	* This function updates the agent and domain table hits for a particular
	* visitor.  The user agent is recorded/incremented if this is the first visit.
	* A cookie is set to mark the first visit.
	*/
	function detect() {

		if(Jconfig::getInstance()->config_enable_stats == 1) {
			if(mosGetParam($_COOKIE,'mosvisitor',0)) {
				return;
			}
			setcookie('mosvisitor',1);

			if(phpversion() <= '4.2.1') {
				$agent = getenv('HTTP_USER_AGENT');
				$domain = @gethostbyaddr(getenv("REMOTE_ADDR"));
			} else {
				if(isset($_SERVER['HTTP_USER_AGENT'])) {
					$agent = $_SERVER['HTTP_USER_AGENT'];
				} else {
					$agent = 'Unknown';
				}

				$domain = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
			}

			$browser = mosGetBrowser($agent);

			$query = "SELECT COUNT(*) FROM #__stats_agents WHERE agent = ".$this->_db->Quote($browser)." AND type = 0";
			$this->_db->setQuery($query);
			if($this->_db->loadResult()) {
				$query = "UPDATE #__stats_agents SET hits = ( hits + 1 ) WHERE agent = ".$this->_db->Quote($browser)." AND type = 0";
				$this->_db->setQuery($query);
			} else {
				$query = "INSERT INTO #__stats_agents ( agent, type ) VALUES ( ".$this->_db->Quote($browser).", 0 )";
				$this->_db->setQuery($query);
			}
			$this->_db->query();

			$os = mosGetOS($agent);

			$query = "SELECT COUNT(*) FROM #__stats_agents WHERE agent = ".$this->_db->Quote($os)." AND type = 1";
			$this->_db->setQuery($query);
			if($this->_db->loadResult()) {
				$query = "UPDATE #__stats_agents SET hits = ( hits + 1 ) WHERE agent = ".$this->_db->Quote($os)." AND type = 1";
				$this->_db->setQuery($query);
			} else {
				$query = "INSERT INTO #__stats_agents ( agent, type )"." VALUES ( ".$this->_db->Quote($os).", 1 )";
				$this->_db->setQuery($query);
			}
			$this->_db->query();

			// tease out the last element of the domain
			$tldomain = split("\.",$domain);
			$tldomain = $tldomain[count($tldomain) - 1];

			if(is_numeric($tldomain)) {
				$tldomain = "Unknown";
			}

			$query = "SELECT COUNT(*) FROM #__stats_agents WHERE agent = ".$this->_db->Quote($tldomain)." AND type = 2";
			$this->_db->setQuery($query);
			if($this->_db->loadResult()) {
				$query = "UPDATE #__stats_agents SET hits = ( hits + 1 ) WHERE agent = ".$this->_db->Quote($tldomain)." AND type = 2";
				$this->_db->setQuery($query);
			} else {
				$query = "INSERT INTO #__stats_agents ( agent, type ) VALUES ( ".$this->_db->Quote($tldomain).", 2 )";
				$this->_db->setQuery($query);
			}
			$this->_db->query();
		}
	}

	/**
	* @return correct Itemid for Content Item
	*/

	function getItemid($id,$typed = 1,$link = 1) {
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

				if(isset($this->all_menu_links['index.php?option=com_content&task=view&id='.$id]) && $this->all_menu_links['index.php?option=com_content&task=view&id='.$id]['type']=='content_typed'){
					$ContentTyped[$id] =$this->all_menu_links['index.php?option=com_content&task=view&id='.$id]['id'];
				}else{
					// Search for typed link
					$query = "SELECT id FROM #__menu WHERE type = 'content_typed' AND published = 1 AND link = 'index.php?option=com_content&task=view&id=".(int)$id."'";
					$this->_db->setQuery($query);
					$ContentTyped[$id] = $this->_db->loadResult();
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
				// Search for item link
				$query = "SELECT id FROM #__menu WHERE type = 'content_item_link' AND published = 1 AND link = 'index.php?option=com_content&task=view&id=".(int)$id."'";
				$this->_db->setQuery($query);
				// pull existing query storage into temp variable
				$ContentItemLink = $this->get('_ContentItemLink',array());
				// add query result to temp array storage
				$ContentItemLink[$id] = $this->_db->loadResult();
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

				// пытаемся исправить недоработки Joomla...
				static $_links;
				if(!isset($_links)){
					$query = "SELECT ms.id AS sid, ms.type AS stype, mc.id AS cid, mc.type AS ctype, i.id as sectionid, i.id As catid, ms.published AS spub, mc.published AS cpub"
						."\n FROM #__content AS i"
						."\n LEFT JOIN #__sections AS s ON i.sectionid = s.id"
						."\n LEFT JOIN #__menu AS ms ON ms.componentid = s.id "
						."\n LEFT JOIN #__categories AS c ON i.catid = c.id"
						."\n LEFT JOIN #__menu AS mc ON mc.componentid = c.id "
						."\n WHERE ( ms.type IN ( 'content_section', 'content_blog_section' ) OR mc.type IN ( 'content_blog_category', 'content_category' ) )"
						//."\n AND i.id = ".(int)$id."\n ORDER BY ms.type DESC, mc.type DESC, ms.id, mc.id";
						."\n ORDER BY ms.type DESC, mc.type DESC, ms.id, mc.id";
					$this->_db->setQuery($query);
					$links = $bbad = $this->_db->loadObjectList();
					$_links = array();
					foreach($bbad as $bad){
						$_links[$bad->sectionid][]=$bad;
					}
					unset($bbad,$bad);
				}
				$links = $_links[$id];

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
	* @return number of Published Blog Sections
	* Kept for Backward Compatability
	*/
	function getBlogSectionCount() {
		return 1;
	}

	/**
	* @return number of Published Blog Categories
	* Kept for Backward Compatability
	*/
	function getBlogCategoryCount() {
		return 1;
	}

	/**
	* @return number of Published Global Blog Sections
	* Kept for Backward Compatability
	*/
	function getGlobalBlogSectionCount() {
		return 1;
	}

	/**
	* @return number of Static Content
	*/
	function getStaticContentCount() {
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
	function getContentItemLinkCount() {
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

	/** Is admin interface?
	* @return boolean
	* @since 1.0.2
	*/
	function isAdmin() {
		return $this->_isAdmin;
	}

	// указание системного сообщения
	function set_mosmsg($msg=''){
		$msg = Jstring::trim($msg);

		if($this->_isAdmin){
			if(session_name()!=md5($this->getCfg('live_site'))) {
				session_name(md5($this->getCfg('live_site')));
				session_start();
			}
		}else{
			session_name($this->_session->session_id);
			session_start();
		}

		if($msg!=''){
			$_SESSION['joostina.mosmsg'] = $msg;
		}
		return;
	}
	// получение системного сообщения
	function get_mosmsg(){

		if(!$this->_isAdmin && !$this->_session->session_id){
			session_name($this->_session->session_id);
			session_start();
		}

		$mosmsg_ss = trim(stripslashes(strval(mosGetParam($_SESSION,'joostina.mosmsg',''))));
		$mosmsg_rq = stripslashes(strval(mosGetParam($_REQUEST,'mosmsg','')));

		if(!$this->_isAdmin && !$this->_session->session_id){
			session_destroy();
		}

		$mosmsg = ($mosmsg_ss!='') ? $mosmsg_ss : $mosmsg_rq;

		if(Jstring::strlen($mosmsg) > 300) { // выводим сообщения не длинее 300 символов
			$mosmsg = Jstring::substr($mosmsg,0,300);
		}

		unset($_SESSION['joostina.mosmsg']);
		return $mosmsg;
	}

	/* проверка доступа к активному компоненту */
	function check_option($option){
		if($option=='com_content') return true;
		$sql = 'SELECT menuid FROM #__components WHERE #__components.option=\''.$option.'\' AND parent=0';
		$this->_db->setQuery($sql);
		($this->_db->loadResult()==0) ? null : mosRedirect($this->getCfg('live_site'));
		return true;
	}

	function get_option(){

		$Itemid = intval(strtolower(mosGetParam($_REQUEST,'Itemid',null)));
		$option = trim(strval(strtolower(mosGetParam($_REQUEST,'option',null))));

		if(isset($option) && $option!='') {
			return $option;
		}

		if($Itemid) {
			$query = "SELECT id, link"
			."\n FROM #__menu"
			."\n WHERE menutype = 'mainmenu'"
			."\n AND id = ".(int)$Itemid
			."\n AND published = 1";
			$this->_db->setQuery($query);
			$menu = new mosMenu($database);
			$this->_db->loadObject($menu);
		} else {
			// получение пурвого элемента главного меню
			$menu = $this->get('all_menu');
			$menu = $menu['mainmenu'];
			$items = array_values($menu);
			$menu = $items[0];
		}

		$Itemid = $menu->id;
		$link = $menu->link;

		unset($menu);
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
		}

		return $option;
	}

}

// главный класс конфигурации системы
class JConfig {
	/** @var int*/
	var $config_offline = null;
	/** @var string*/
	var $config_offline_message = null;
	/** @var string*/
	var $config_error_message = null;
	/** @var string*/
	var $config_sitename = null;
	/** @var string*/
	var $config_editor = 'jce';
	/** @var int*/
	var $config_list_limit = 30;
	/** @var string*/
	var $config_favicon = null;
	/** @var string*/
	var $config_frontend_login = 1;
	/** @var int*/
	var $config_debug = 0;
	/** @var string*/
	var $config_host = null;
	/** @var string*/
	var $config_user = null;
	/** @var string*/
	var $config_password = null;
	/** @var string*/
	var $config_db = null;
	/** @var string*/
	var $config_dbprefix = null;
	/** @var string*/
	var $config_absolute_path = null;
	/** @var string*/
	var $config_live_site = null;
	/** @var string*/
	var $config_secret = null;
	/** @var int*/
	var $config_gzip = 0;
	/** @var int*/
	var $config_lifetime = 900;
	/** @var int*/
	var $config_session_life_admin = 1800;
	/** @var int*/
	var $config_admin_expired = 1;
	/** @var int*/
	var $config_session_type = 0;
	/** @var int*/
	var $config_error_reporting = 0;
	/** @var string*/
	var $config_helpurl = 'http://help.joostina.ru';
	/** @var string*/
	var $config_fileperms = '0644';
	/** @var string*/
	var $config_dirperms = '0755';
	/** @var string*/
	var $config_locale = null;
	/** @var string*/
	var $config_lang = null;
	/** @var int*/
	var $config_offset = null;
	/** @var int*/
	var $config_offset_user = null;
	/** @var string*/
	var $config_mailer = null;
	/** @var string*/
	var $config_mailfrom = null;
	/** @var string*/
	var $config_fromname = null;
	/** @var string*/
	var $config_sendmail = '/usr/sbin/sendmail';
	/** @var string*/
	var $config_smtpauth = 0;
	/** @var string*/
	var $config_smtpuser = null;
	/** @var string*/
	var $config_smtppass = null;
	/** @var string*/
	var $config_smtphost = null;
	/** @var int*/
	var $config_caching = 0;
	/** @var string*/
	var $config_cachepath = null;
	/** @var string*/
	var $config_cachetime = null;
	/** @var int*/
	var $config_allowUserRegistration = 0;
	/** @var int*/
	var $config_useractivation = null;
	/** @var int*/
	var $config_uniquemail = null;
	/** @var int*/
	var $config_shownoauth = 0;
	/** @var int*/
	var $config_frontend_userparams = 1;
	/** @var string*/
	var $config_MetaDesc = null;
	/** @var string*/
	var $config_MetaKeys = null;
	/** @var int*/
	var $config_MetaTitle = null;
	/** @var int*/
	var $config_MetaAuthor = null;
	/** @var int*/
	var $config_enable_log_searches = null;
	/** @var int*/
	var $config_enable_stats = null;
	/** @var int*/
	var $config_enable_log_items = null;
	/** @var int*/
	var $config_sef = 0;
	/** @var int*/
	var $config_pagetitles = 1;
	/** @var int*/
	var $config_link_titles = 0;
	/** @var int*/
	var $config_readmore = 1;
	/** @var int*/
	var $config_vote = 0;
	/** @var int*/
	var $config_showAuthor = 0;
	/** @var int*/
	var $config_showCreateDate = 0;
	/** @var int*/
	var $config_showModifyDate = 0;
	/** @var int*/
	var $config_hits = 1;
	/** @var int*/
	var $config_showPrint = 0;
	/** @var int*/
	var $config_showEmail = 0;
	/** @var int*/
	var $config_icons = 1;
	/** @var int*/
	var $config_back_button = 0;
	/** @var int*/
	var $config_item_navigation = 0;
	/** @var int*/
	var $config_multilingual_support = 0;
	/** @var int*/
	var $config_multipage_toc = 0;
	/** Режим работы с itemid, 0 - прежний режим*/
	var $config_itemid_compat = 0;
	/** @var int отключение ведения сессий на фронте*/
	var $config_session_front = 0;
	/** @var int отключение syndicate*/
	var $config_syndicate_off = 0;
	/** @var int отключение тега Generator*/
	var $config_generator_off = 0;
	/** @var int отключение мамботов группы system*/
	var $config_mmb_system_off = 0;
	/** @var str использование одного шаблона на весь сайт*/
	var $config_one_template = '...';
	/** @var int подсчет времени генерации страницы*/
	var $config_time_gen = 0;
	/** @var int индексация страницы печати*/
	var $config_index_print = 0;
	/** @var int расширенные теги индексации*/
	var $config_index_tag = 0;
	/** @var int отключение модулей на странице редактирования с фронта*/
	var $config_module_on_edit_off = 0;
	/** @var int использование ежесуточной оптимизации таблиц базы данных*/
	var $config_optimizetables = 1;
	/** @var int отключение мамботов группы content*/
	var $config_mmb_content_off = 0;
	/** @var int кэширование меню панели управления*/
	var $config_adm_menu_cache = 0;
	/** @var int расположение элементов title*/
	var $config_pagetitles_first = 1;
	/** @var string разделитель "заголовок страницы - Название сайта "*/
	var $config_tseparator = ' - ';
	/** @int отключение captcha*/
	var $config_captcha = 1;
	/** @int очистка ссылки на com_frontpage*/
	var $config_com_frontpage_clear = 1;
	/** @str корень для компонента управления медиа содержимым*/
	var $config_media_dir = 'images/stories';
	/** @str корень файлового менеджера*/
	var $config_joomlaxplorer_dir = null;
	/** @int автоматическая установка "Публиковать на главной"*/
	var $config_auto_frontpage = 0;
	/** @int уникальные идентификаторы новостей*/
	var $config_uid_news = 0;
	/** @int подсчет прочтений содержимого*/
	var $config_content_hits = 1;
	/** @str формат даты*/
	var $config_form_date = '%d.%m.%Y г.';
	/** @str полный формат даты и времени*/
	var $config_form_date_full = '%d.%m.%Y г. %H:%M';
	/** @int не показывать "Главная" на первой странице*/
	var $config_pathway_clean = 1;
	/** @int автоматические разлогинивание в панели управления после окончания жизни сессии */
	var $config_admin_autologout = 1;
	/** @int отключение кнопки "Помощь"*/
	var $config_disable_button_help = 0;
	/** @int отключение блокировок объектов*/
	var $config_disable_checked_out = 0;
	/** @int отключение favicon*/
	var $config_disable_favicon = 1;
	/** @str смещение для rss*/
	var $config_feed_timeoffset = null;
	/** @int использовать расширенную отладку на фронте*/
	var $config_front_debug = 0;
	/** @var int отключение мамботов группы mainbody*/
	var $config_mmb_mainbody_off = 0;
	/** @var int автоматическая авторизация после подтверждения регистрации*/
	var $config_auto_activ_login = 0;
	/** @var int отключение вкладки 'Изображения'*/
	var $config_disable_image_tab = 0;
	/** @var int обрамлять заголовки тегом h1*/
	var $config_title_h1 = 0;
	/** @var int обрамлять заголовки тегом h1 только в режиме полного просмотра содержимого*/
	var $config_title_h1_only_view = 1;
	/** @var int отключить проверки публикаций по датам*/
	var $config_disable_date_state = 0;
	/** @var int отключить контроль доступа к содержимому*/
	var $config_disable_access_control = 0;
	/** @var int включение оптимизации функции кэширования*/
	var $config_cache_opt = 0;
	/** @var int включение сжатия css и js файлов*/
	var $config_gz_js_css = 0;
	/** @var int captcha для регистрации*/
	var $config_captcha_reg = 0;
	/** @var int captcha для формы контактов*/
	var $config_captcha_cont = 0;
	/** @var int визуальный редактор для правки html и css*/
	var $config_codepress = 0;
	/** @var int обработчик кэширования запросов базы данных */
	var $config_db_cache_handler = 'none';
	/** @var int время жизни кэша запросов базы данных */
	var $config_db_cache_time = 0;
	/** * @var int использование одного редактора при создании содержимого */
	var $config_one_editor = 0;
	/** @var int переадресация с не WWW адресов */
	var $config_www_redir = 0;
	/** @var int автоматическая очистка кэша */
	var $config_clearCache = 0;
	/** @var int вывод мета-тега baser */
	var $config_mtage_base = 1;
	/** @var int вывод мета-тега revisit в днях */
	var $config_mtage_revisit = 10;
	/** @var int использование страницы печати из каталога текущего шаблона */
	var $config_custom_print = 0;
	/** @var int использование совместимого вывода туллбара */
	var $config_old_toolbar = 0;
	/** @var int отключение предпросмотра шаблонов через &tp=1 */
	var $config_disable_tpreview = 0;
	/** @int включение кода безопасности для доступа к панели управления*/
	var $config_enable_admin_secure_code = 0;
	/** @int включение кода безопасности для доступа к панели управления*/
	var $config_admin_secure_code = 'admin';
	/** @int режим редиректа при включенном коде безопасноти*/
	var $config_admin_redirect_options = 0;
	/** @int адрес редиректа при включенном коде безопасноти*/
	var $config_admin_redirect_path = '404.html';
	/** @var int число попыток автооизации для входа в админку*/
	var $config_admin_bad_auth = 5;
	/** @var int обработчик кэширования */
	var $config_cache_handler = 'none';
	/** @var array настройки memCached */
	var $config_memcache_persistent = 0;
	/** @var array настройки memCached */
	var $config_memcache_compression = 0;
	/** @var array настройки memCached */
	var $config_memcache_host = 'localhost';
	/** @var array настройки memCached */
	var $config_memcache_port = '11211';
	/** @var int тип вывода ника автора материала */
	var $config_author_name = 4;
	/** @var int использование неопубликованных мамботов */
	var $config_use_unpublished_mambots = 1;
	/** @var int использование мамботов удаления содержимого */
	var $config_use_content_delete_mambots = 0;
	/** @var str название шаблона панели управления */
	var $config_admin_template = '...';
	/** @var int режим сортировки содержимого в панели управления */
	var $config_admin_content_order_by = 2;
	/** @var str порядок сортировки содержимого в панели управления */
	var $config_admin_content_order_sort = 0;
	/** @var int активация блокировок компонентов */
	var $config_components_access = 0;
	/** @var int использование мамботов редактирования содержимого */
	var $config_use_content_edit_mambots = 0;
	/** @var int использование мамботов сохранения содержимого */
	var $config_use_content_save_mambots = 0;
	/** @var int чисто неудачный авторизаций для блокировки аккаунта */
	var $config_count_for_user_block = 10;
	/** @var int директория шаблонов содержимого по-умолчанию */
	var $config_global_templates = 0;



	// инициализация класса конфигурации - собираем переменные конфигурации
	function JConfig(){
		$this->bindGlobals();
	}

	function &getInstance(){
		static $instance;

		if (!is_object( $instance )) {
			$instance = new JConfig();
		}

		return $instance;
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
		$vars = $this->getPublicVars();
		foreach($vars as $v) {
			$k = str_replace('config_','mosConfig_',$v);
			if(isset($GLOBALS[$k])) $this->$v = $GLOBALS[$k];
		}
		/*
		* для корректной работы https://
		*/
		$_patch = dirname(dirname( __FILE__ ));
		require ($_patch.'/configuration.php');
		if($mosConfig_live_site != $this->config_live_site){
			$this->config_live_site = $mosConfig_live_site;
		}
	}
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
* Module database table class
* @package Joostina
*/
class mosModule extends mosDBTable {
	/**
	@var int Primary key*/
	var $id = null;
	/**
	@var string*/
	var $title = null;
	/**
	@var string*/
	var $showtitle = null;
	/**
	@var int*/
	var $content = null;
	/**
	@var int*/
	var $ordering = null;
	/**
	@var string*/
	var $position = null;
	/**
	@var boolean*/
	var $checked_out = null;
	/**
	@var time*/
	var $checked_out_time = null;
	/**
	@var boolean*/
	var $published = null;
	/**
	@var string*/
	var $module = null;
	/**
	@var int*/
	var $numnews = null;
	/**
	@var int*/
	var $access = null;
	/**
	@var string*/
	var $params = null;
	/**
	@var string*/
	var $iscore = null;
	/**
	@var string*/
	var $client_id = null;
	/**
	@var string*/
	var $template = null;
	/**
	@var string*/
	var $helper = null;


	/**
	* @param database A database connector object
	*/
	function mosModule(&$db) {
		$this->mosDBTable('#__modules','id',$db);
	}
	// overloaded check function
	function check() {
		// check for valid name
		if(trim($this->title) == '') {
			$this->_error = _PLEASE_ENTER_MODULE_NAME;
			return false;
		}

		return true;
	}
	
	function convert_to_object($module){
		$database = &database::getInstance();
		
		$module_obj = new mosModule($database);
		$rows = get_object_vars($module_obj);
		foreach($rows as $key => $value){
				if (isset($module->$key)) {
					$module_obj->$key = $module->$key;	
				}		
		}

		return $module_obj;
	}
	
	function set_template($params){
		$mainframe = &mosMainFrame::getInstance();
		$config = &Jconfig::getInstance();

		if($params->get('template', '') == ''){
			return false;
		}

		$default_template = 'modules'.DS.$this->module.'/view/default.php';

		if($params->get('template_dir',0) == 0){
			$template_dir = 'modules'.DS.$this->module.'/view';
		}
		else{
			$template_dir = 'templates'.DS. $mainframe->getTemplate() .DS.'html/modules'.DS.$this->module;
		}
		
		if($params->get('template')){
			if (is_file($config->config_absolute_path . DS . $template_dir . DS . $params->get('template'))){
				$this->template = $config->config_absolute_path . DS . $template_dir . DS . $params->get('template');
				return true;
			}else if (is_file($config->config_absolute_path . DS . $default_template)){
				$this->template = $config->config_absolute_path . DS . $default_template;
				return true;
			}
		}
		return false;
	}

	function set_template_custom($template){
		$mainframe = &mosMainFrame::getInstance();
		$config = &Jconfig::getInstance();

		$template_file = $config->config_absolute_path .DS.'templates'.DS. $mainframe->getTemplate() .DS.'html/user_modules'.DS.$template;

		if (is_file($template_file)) {
			$this->template = $template_file;
			return true;
		}
		return false;
	}

	function get_helper(){
		$config = &Jconfig::getInstance();
		$help_file = 'modules'.DS.$this->module.DS.'helper.php';

		if (is_file($config->config_absolute_path . DS . $help_file)) {
			require_once($config->config_absolute_path . DS . $help_file);
			$helper_class = $this->module.'_Helper';
			$this->helper = new $helper_class();
			return true;
		}
		return false;
	}

	function load_module($name = '', $title = ''){
		$where = " m.module = '".$name."'";
		if(!$name || $title){
			$where = " m.title = '".$title."'";
		}

		$query = "SELECT * FROM #__modules AS m WHERE ".$where;
		$row = null;	
		$this->_db->setQuery($query);
		$this->_db->loadObject($row);

		$rows = get_object_vars($this);

		foreach ($rows as $key => $value) {
			if (isset($row->$key)) {
				$this->$key = $row->$key;
			}
		}
		return true;
	}

}

/**
* Component database table class
* @package Joostina
*/
class mosComponent extends mosDBTable {
	/**
	@var int Primary key*/
	var $id = null;
	/**
	@var string*/
	var $name = null;
	/**
	@var string*/
	var $link = null;
	/**
	@var int*/
	var $menuid = null;
	/**
	@var int*/
	var $parent = null;
	/**
	@var string*/
	var $admin_menu_link = null;
	/**
	@var string*/
	var $admin_menu_alt = null;
	/**
	@var string*/
	var $option = null;
	/**
	@var string*/
	var $ordering = null;
	/**
	@var string*/
	var $admin_menu_img = null;
	/**
	@var int*/
	var $iscore = null;
	/**
	@var string*/
	var $params = null;
	/*@var int права доступа к компоненту */
	#var $access = null;

	/**
	* @param database A database connector object
	*/
	function mosComponent(&$db=null) {
		$this->mosDBTable('#__components','id',$db);
	}
}

/**
* Template Table Class
*
* Provides access to the jos_templates table
* @package Joostina
*/
class mosTemplate extends mosDBTable {
	/**
	@var int*/
	var $id = null;
	/**
	@var string*/
	var $cur_template = null;
	/**
	@var int*/
	var $col_main = null;

	/**
	* @param database A database connector object
	*/
	function mosTemplate(&$database) {
		$this->mosDBTable('#__templates','id',$database);
	}
}

/**
* Module database table class
* @package Joostina
*/
class mosMenu extends mosDBTable {
	/**
	@var int Primary key*/
	var $id = null;
	/**
	@var string*/
	var $menutype = null;
	/**
	@var string*/
	var $name = null;
	/**
	@var string*/
	var $link = null;
	/**
	@var int*/
	var $type = null;
	/**
	@var int*/
	var $published = null;
	/**
	@var int*/
	var $componentid = null;
	/**
	@var int*/
	var $parent = null;
	/**
	@var int*/
	var $sublevel = null;
	/**
	@var int*/
	var $ordering = null;
	/**
	@var boolean*/
	var $checked_out = null;
	/**
	@var datetime*/
	var $checked_out_time = null;
	/**
	@var boolean*/
	var $pollid = null;
	/**
	@var string*/
	var $browserNav = null;
	/**
	@var int*/
	var $access = null;
	/**
	@var int*/
	var $utaccess = null;
	/**
	@var string*/
	var $params = null;

	/**
	* @param database A database connector object
	*/
	function mosMenu(&$db) {
		$this->mosDBTable('#__menu','id',$db);
		$this->_menu = array();
	}

	function &getInstance(){
		static $instance;

		if (!is_object( $instance )) {
			$db = &database::getInstance();
			$instance = new mosMenu($db);

			// ведёргиваем из базы все пункты меню, они еще пригодяться несколько раз
			$sql = 'SELECT* FROM #__menu WHERE published=1 ORDER BY parent, ordering ASC';
			$this->_db->setQuery($sql);
			$menus = $this->_db->loadObjectList();

			$m = array();
			foreach($menus as $menu){
				$m[$menu->menutype][$menu->id]=$menu;
			}
			$instance->_menu = $m;
			unset($m,$sql,$menus,$menu);
		}

		return $instance;
	}

	function check() {
		$this->id = (int)$this->id;
		$this->params = (string )trim($this->params.' ');
		$ignoreList = array('link');
		$this->filter($ignoreList);
		return true;
	}
	
	function getMenu($id = null, $type = '', $link = ''){
		$mainframe = &mosMainFrame::getInstance();

		$where = ''; $and = array();
		if($id || $type || $link){
			$where .= ' WHERE ';
		}
		if ($id){
			$and[] = ' menu.id = '.$id;	
		}
		if ($type){
			$and[] = " menu.type = '".$type."'";
		}
		if ($link){
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
	function get_menu(){
		return $this->_menu;
	}

	function get_menu_links(){
		$_all = $this->_menu;
		$return = array();
		foreach($_all as $menus){
			foreach($menus as $menu){
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
* Class to support function caching
* @package Joostina
*/
class mosCache {
	/**
	* @return object A function cache object
	*/
	function &getCache($group = 'default', $handler = 'callback', $storage = null,$cachetime = null){

		jd_inc('getCache');
		jd_log($group);

		$handler = ($handler == 'function') ? 'callback' : $handler;

		$config = &Jconfig::getInstance();

		$def_cachetime = (isset($cachetime)) ? $cachetime : $config->config_cachetime;

		if(!isset($storage)) {
			$storage =($config->config_cache_handler != '')? $config->config_cache_handler : 'file';
		}

		$options = array(
			'defaultgroup' 	=> $group,
			'cachebase' 	=> $config->config_cachepath.'/',
			'lifetime' 		=> $def_cachetime,// minutes to seconds
			'language' 		=> $config->config_lang,
			'storage'		=> $storage
		);

		mosMainFrame::addLib('cache');
		$cache =&JCache::getInstance( $handler, $options );

		if($cache != NULL){
			$cache->setCaching($config->config_caching);
		}
		return $cache;
	}
	/**
	* Cleans the cache
	*/
	function cleanCache($group = false) {
		$cache = &mosCache::getCache($group);
		if($cache != NULL){
			$cache->clean($group);
		}
	}
}

/**
* Utility class for all HTML drawing classes
* @package Joostina
*/
class mosHTML {

	function makeOption($value,$text = '',$value_name = 'value',$text_name = 'text') {
		$obj = new stdClass;
		$obj->$value_name = $value;
		$obj->$text_name = trim($text)?$text:$value;
		return $obj;
	}

	function writableCell($folder,$relative = 1,$text = '',$visible = 1) {

		$writeable		= '<b><font color="green">'._WRITEABLE.'</font></b>';
		$unwriteable	= '<b><font color="red">'._UNWRITEABLE.'</font></b>';

		echo '<tr>';
		echo '<td class="item">';
		echo $text;
		if($visible) {
			echo $folder.'/';
		}
		echo '</td>';
		echo '<td align="left">';
		if($relative) {
			echo is_writable("../$folder")?$writeable:$unwriteable;
		} else {
			echo is_writable($folder)?$writeable:$unwriteable;
		}
		echo '</td>';
		echo '</tr>';
	}

	/**
	* Generates an HTML select list
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @param mixed The key that is selected
	* @returns string HTML for the select list
	*/
	function selectList(&$arr,$tag_name,$tag_attribs,$key,$text,$selected = null, $first_el_key = '*000', $first_el_text = '*000') {
		// check if array
		if(is_array($arr)) {
			reset($arr);
		}

		$html = "\n<select name=\"$tag_name\" $tag_attribs>";
		$count = count($arr);
		
		if ($first_el_key!='*000' && $first_el_text!='*000') {
			$html .= "\n\t<option value=\"$first_el_key\">$first_el_text</option>";
		}

		for($i = 0,$n = $count; $i < $n; $i++) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = (isset($arr[$i]->id)?@$arr[$i]->id:null);

			$extra = '';
			$extra .= $id?" id=\"".$arr[$i]->id."\"":'';
			if(is_array($selected)) {
				foreach($selected as $obj) {
					$k2 = $obj->$key;
					if($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected?" selected=\"selected\"":'');
			}
			$html .= "\n\t<option value=\"".$k."\"$extra>".$t."</option>";
		}
		$html .= "\n</select>\n";

		return $html;
	}

	/**
	* Writes a select list of integers
	* @param int The start integer
	* @param int The end integer
	* @param int The increment
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The printf format to be applied to the number
	* @returns string HTML for the select list
	*/
	function integerSelectList($start,$end,$inc,$tag_name,$tag_attribs,$selected,$format ="") {
		$start = intval($start);
		$end = intval($end);
		$inc = intval($inc);
		$arr = array();

		for($i = $start; $i <= $end; $i += $inc) {
			$fi = $format ? sprintf("$format",$i):"$i";
			$arr[] = mosHTML::makeOption($fi,$fi);
		}

		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	/**
	* Writes a select list of month names based on Language settings
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the select list values
	*/
	function monthSelectList($tag_name,$tag_attribs,$selected,$type = 0) {
		// месяца для выбора
		$arr_1 = array(
			mosHTML::makeOption('01',_JAN),
			mosHTML::makeOption('02',_FEB),
			mosHTML::makeOption('03',_MAR),
			mosHTML::makeOption('04',_APR),
			mosHTML::makeOption('05',_MAY),
			mosHTML::makeOption('06',_JUN),
			mosHTML::makeOption('07',_JUL),
			mosHTML::makeOption('08',_AUG),
			mosHTML::makeOption('09',_SEP),
			mosHTML::makeOption('10',_OCT),
			mosHTML::makeOption('11',_NOV),
			mosHTML::makeOption('12',_DEC)
		);
		// месяца с правильным склонением
		$arr_2 = array(
			mosHTML::makeOption('01',_JAN_2),
			mosHTML::makeOption('02',_FEB_2),
			mosHTML::makeOption('03',_MAR_2),
			mosHTML::makeOption('04',_APR_2),
			mosHTML::makeOption('05',_MAY_2),
			mosHTML::makeOption('06',_JUN_2),
			mosHTML::makeOption('07',_JUL_2),
			mosHTML::makeOption('08',_AUG_2),
			mosHTML::makeOption('09',_SEP_2),
			mosHTML::makeOption('10',_OCT_2),
			mosHTML::makeOption('11',_NOV_2),
			mosHTML::makeOption('12',_DEC_2)
		);
		$arr = $type ? $arr_2 : $arr_1;
		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	function daySelectList($tag_name,$tag_attribs,$selected) {
		$arr = array();

		for($i = 1; $i <= 31; $i++){
			$pref = '';
			if($i <= 9){
				$pref = '0';
			}
			$arr[] = mosHTML::makeOption($pref.$i,$pref.$i);
		}

		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	function yearSelectList($tag_name,$tag_attribs,$selected, $min=1930, $max=2009) {
		$arr = array();
		for($i = $min; $i <= $max; $i++){
			$arr[] = mosHTML::makeOption($i,$i);
		}
		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	function genderSelectList($tag_name,$tag_attribs,$selected) {
		$arr = array(
			mosHTML::makeOption('no_gender',_GENDER_NONE),
			mosHTML::makeOption('male',_MALE),
			mosHTML::makeOption('female',_FEMALE)
		);
		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}


	/**
	* Generates an HTML select list from a tree based query list
	* @param array Source array with id and parent fields
	* @param array The id of the current list item
	* @param array Target array.  May be an empty array.
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @param mixed The key that is selected
	* @returns string HTML for the select list
	*/
	function treeSelectList(&$src_list,$src_id,$tgt_list,$tag_name,$tag_attribs,$key,
		$text,$selected) {
		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach($src_list as $v) {
			$pt = $v->parent;
			$list = @$children[$pt]?$children[$pt]:array();
			array_push($list,$v);
			$children[$pt] = $list;
		}
		// second pass - get an indent list of the items
		$ilist = mosTreeRecurse(0,'',array(),$children);

		// assemble menu items to the array
		$this_treename = '';
		foreach($ilist as $item) {
			if($this_treename) {
				if($item->id != $src_id && strpos($item->treename,$this_treename) === false) {
					$tgt_list[] = mosHTML::makeOption($item->id,$item->treename);
				}
			} else {
				if($item->id != $src_id) {
					$tgt_list[] = mosHTML::makeOption($item->id,$item->treename);
				} else {
					$this_treename = "$item->treename/";
				}
			}
		}
		// build the html select list
		return mosHTML::selectList($tgt_list,$tag_name,$tag_attribs,$key,$text,$selected);
	}

	/**
	* Writes a yes/no select list
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the select list values
	*/
	function yesnoSelectList($tag_name,$tag_attribs,$selected,$yes = _YES,$no =_NO) {
		$arr = array(mosHTML::makeOption('0',$no),mosHTML::makeOption('1',$yes),);

		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	/**
	* Generates an HTML radio list
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @returns string HTML for the select list
	*/
	function radioList(&$arr,$tag_name,$tag_attribs,$selected = null,$key = 'value',$text = 'text') {
		reset($arr);
		$html = '';
		for($i = 0,$n = count($arr); $i < $n; $i++) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = (isset($arr[$i]->id)?@$arr[$i]->id:null);

			$extra = '';
			$extra .= $id?" id=\"".$arr[$i]->id."\"":'';
			if(is_array($selected)) {
				foreach($selected as $obj) {
					$k2 = $obj->$key;
					if($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected?" checked=\"checked\"":'');
			}
			$html .= "\n\t<input type=\"radio\" name=\"$tag_name\" id=\"$tag_name$k\" value=\"".$k."\"$extra $tag_attribs />";
			$html .= "\n\t<label for=\"$tag_name$k\">$t</label>";
		}
		$html .= "\n";

		return $html;
	}

	/**
	* Writes a yes/no radio list
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the radio list
	*/
	function yesnoRadioList($tag_name,$tag_attribs,$selected,$yes = _YES,$no = _NO) {
		$arr = array(
			mosHTML::makeOption('0',$no),
			mosHTML::makeOption('1',$yes)
		);

		return mosHTML::radioList($arr,$tag_name,$tag_attribs,$selected);
	}

	/**
	* @param int The row index
	* @param int The record id
	* @param boolean
	* @param string The name of the form element
	* @return string
	*/
	function idBox($rowNum,$recId,$checkedOut = false,$name = 'cid') {
		if($checkedOut) {
			return '';
		} else {
			return '<input type="checkbox" id="cb'.$rowNum.'" name="'.$name.'[]" value="'.$recId.'" onclick="isChecked(this.checked);" />';
		}
	}

	function sortIcon($base_href,$field,$state = 'none') {
		$alts = array('none' => _SORT_NONE,'asc' => _SORT_ASC,'desc' =>_SORT_DESC,);
		$next_state = 'asc';
		if($state == 'asc') {
			$next_state = 'desc';
		} else
			if($state == 'desc') {
				$next_state = 'none';
			}

		$html = '<a href="'.$base_href.'&field='.$field.'&order='.$next_state.'"><img src="'.Jconfig::getInstance()->config_live_site.'/'.ADMINISTRATOR_DIRECTORY.'/images/sort_'.$state.'.png" width="12" height="12" border="0" alt="'.$alts[$next_state].'" /></a>';
		return $html;
	}

	/**
	* Writes Close Button
	*/
	function CloseButton(&$params,$hide_js = null) {
		// displays close button in Pop-up window
		if($params->get('popup') && !$hide_js) {
?>
			<script language="javascript" type="text/javascript">
			<!--
			document.write('<div align="center" style="margin-top: 30px; margin-bottom: 30px;">');
			document.write('<a class="print_button" href="#" onclick="javascript:window.close();"><span class="small"><?php echo _PROMPT_CLOSE; ?></span></a>');
			document.write('</div>');
			//-->
			</script>
<?php
		}
	}

	/**
	* Writes Back Button
	* Сыылка "Вернуться" отображается в следующих случаях:
	* - не переданы параметры (если, например, нет необходимости проверять значения настроек, а нужно принудительно вывести ссылку);
	* - параметры переданы и имеют соответствующие значения (используется в com_content)
	* - параметры переданы, но настройка `back_button` не задана (т.е. должно использоваться глобальное значение параметра) 
	* 	и в глобальных настройках включено отображение ссылки	 
	* 
	*/
	//TODO: справка - Back Button
	function BackButton(&$params = null,$hide_js = null) {
		$config = &Jconfig::getInstance();

		if( !$params ||  ($params->get('back_button')==1 && !$params->get('popup') && !$hide_js) || ($params->get('back_button') == -1 && $config->config_back_button == 1 ) ) 
		{
			include_once($config->config_absolute_path.'/templates/system/back_button.php');
		 }
		 else{
		 	return false;
		 }
	}

	/**
	* Cleans text of all formating and scripting code
	*/
	function cleanText(&$text) {
		$text = preg_replace("'<script[^>]*>.*?</script>'si",'',$text);
		$text = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2 (\1)',$text);
		$text = preg_replace('/<!--.+?-->/','',$text);
		$text = preg_replace('/{.+?}/','',$text);
		$text = preg_replace('/&nbsp;/',' ',$text);
		$text = preg_replace('/&amp;/',' ',$text);
		$text = preg_replace('/&quot;/',' ',$text);
		$text = strip_tags($text);
		$text = htmlspecialchars($text);

		return $text;
	}

	/**
	* Вывод значка печати, встроен хак индексации печатной версии
	*/
	function PrintIcon($row,&$params,$hide_js,$link,$status = null) {
		global $cpr_i;

		if(!isset($cpr_i)) {
			$cpr_i = '';
		}

		if($params->get('print') && !$hide_js) {
			// use default settings if none declared
			if(!$status) {
				$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
			}
			// checks template image directory for image, if non found default are loaded
			if($params->get('icons')) {
				$image = mosAdminMenus::ImageCheck('printButton.png','/images/M_images/',null,null,_PRINT,'print'.$cpr_i);
				$cpr_i++;
			} else {
				$image = _ICON_SEP.'&nbsp;'._PRINT.'&nbsp;'._ICON_SEP;
			}
			if($params->get('popup') && !$hide_js) {
?>
			<script language="javascript" type="text/javascript">
			<!--
			document.write('<a href="#" class="print_button" onclick="javascript:window.print(); return false;" title="<?php echo _PRINT; ?>">');
			document.write('<?php echo $image; ?>');
			document.write('</a>');
			//-->
			</script>
<?php
			} else {
?>

<?php if(!Jconfig::getInstance()->config_index_print) { ?>
			<noindex><a href="#" target="_blank" onclick="window.open('<?php echo $link; ?>','win2','<?php echo $status; ?>'); return false;" title="<?php echo _PRINT; ?>"><?php echo $image; ?></a></noindex>
<?php } else { ?>
			<a href="<?php echo $link; ?>" target="_blank" title="<?php echo _PRINT; ?>"><?php echo $image; ?></a>
<?php } ; ?>

<?php
			}
		}
	}

	/**
	* simple Javascript Cloaking
	* email cloacking
	* by default replaces an email with a mailto link with email cloacked
	*/
	function emailCloaking($mail,$mailto = 1,$text = '',$email = 1) {
		// convert text
		$mail = mosHTML::encoding_converter($mail);
		// split email by @ symbol
		$mail = explode('@',$mail);
		$mail_parts = explode('.',$mail[1]);
		// random number
		$rand = rand(1,100000);
		$replacement = "\n <script language='JavaScript' type='text/javascript'>";
		$replacement .= "\n <!--";
		$replacement .= "\n var prefix = '&#109;a' + 'i&#108;' + '&#116;o';";
		$replacement .= "\n var path = 'hr' + 'ef' + '=';";
		$replacement .= "\n var addy".$rand." = '".@$mail[0]."' + '&#64;';";
		$replacement .= "\n addy".$rand." = addy".$rand." + '".implode("' + '&#46;' + '",
			$mail_parts)."';";
		if($mailto) {
			// special handling when mail text is different from mail addy
			if($text) {
				if($email) {
					// convert text
					$text = mosHTML::encoding_converter($text);
					// split email by @ symbol
					$text = explode('@',$text);
					$text_parts = explode('.',$text[1]);
					$replacement .= "\n var addy_text".$rand." = '".@$text[0]."' + '&#64;' + '".
						implode("' + '&#46;' + '",@$text_parts)."';";
				} else {
					$replacement .= "\n var addy_text".$rand." = '".$text."';";
				}
				$replacement .= "\n document.write( '<a ' + path + '\'' + prefix + ':' + addy".
					$rand." + '\'>' );";
				$replacement .= "\n document.write( addy_text".$rand." );";
				$replacement .= "\n document.write( '<\/a>' );";
			} else {
				$replacement .= "\n document.write( '<a ' + path + '\'' + prefix + ':' + addy".
					$rand." + '\'>' );";
				$replacement .= "\n document.write( addy".$rand." );";
				$replacement .= "\n document.write( '<\/a>' );";
			}
		} else {
			$replacement .= "\n document.write( addy".$rand." );";
		}
		$replacement .= "\n //-->";
		$replacement .= '\n </script>';
		// XHTML compliance `No Javascript` text handling
		$replacement .= "<script language='JavaScript' type='text/javascript'>";
		$replacement .= "\n <!--";
		$replacement .= "\n document.write( '<span style=\'display: none;\'>' );";
		$replacement .= "\n //-->";
		$replacement .= "\n </script>";
		$replacement .= _CLOAKING;
		$replacement .= "\n <script language='JavaScript' type='text/javascript'>";
		$replacement .= "\n <!--";
		$replacement .= "\n document.write( '</' );";
		$replacement .= "\n document.write( 'span>' );";
		$replacement .= "\n //-->";
		$replacement .= "\n </script>";

		return $replacement;
	}

	function encoding_converter($text) {
		// replace vowels with character encoding
		$text = str_replace('a','&#97;',$text);
		$text = str_replace('e','&#101;',$text);
		$text = str_replace('i','&#105;',$text);
		$text = str_replace('o','&#111;',$text);
		$text = str_replace('u','&#117;',$text);
		return $text;
	}
}

// класс работы с контентом
require_once(Jconfig::getInstance()->config_absolute_path.'/components/com_content/content.class.php');

// класс работы с пользователями
require_once(Jconfig::getInstance()->config_absolute_path.'/components/com_users/users.class.php');

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
	static $noHtmlFilter = null;

	$return = null;
	if(isset($arr[$name])) {
		$return = $arr[$name];

		if(is_string($return)) {
			// trim data
			if(!($mask & _MOS_NOTRIM)) {
				$return = trim($return);
			}

			if($mask & _MOS_ALLOWRAW) {
				// do nothing
			} else
				if($mask & _MOS_ALLOWHTML) {
					// do nothing - compatibility mode
				} else {
					// send to inputfilter
					if(is_null($noHtmlFilter)) {
						$noHtmlFilter = new InputFilter( /* $tags, $attr, $tag_method, $attr_method, $xss_auto*/);
					}
					$return = $noHtmlFilter->process($return);

					if (!empty($return) && is_numeric($def)) {
						// if value is defined and default value is numeric set variable type to integer
						$return = intval($return);
					}
				}

				// account for magic quotes setting
				if(!get_magic_quotes_gpc()) {
					$return = addslashes($return);
				}
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
					$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? mosStripslashes($array[$ak]):
						$array[$ak];
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
	$iFilter = new InputFilter();
	$url = $iFilter->process($url);
	if(!empty($msg)) {
		$msg = $iFilter->process($msg);
		$mainframe = &mosMainFrame::getInstance();
		$mainframe->set_mosmsg($msg);
	}

	// Strip out any line breaks and throw away the rest
	$url = preg_split("/[\r\n]/",$url);
	$url = $url[0];
	if($iFilter->badAttributeValue(array('href',$url))) {
		$url = $GLOBALS['mosConfig_live_site'];
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
* Checks the user agent string against known browsers
*/
function mosGetBrowser($agent) {
	mosMainFrame::addLib('phpSniff');
	$client = new phpSniff($agent);

	$client_long_name = $client->property('long_name');
	if(array_key_exists($client_long_name,$client->browsersAlias)) {
		$name = $client->browsersAlias[$client_long_name];
	} else {
		$name = $client_long_name;
	}
	$name .= ' '.$client->property('version');
	return ($name);
}

/**
* Checks the user agent string against known operating systems
*/
function mosGetOS($agent) {
	mosMainFrame::addLib('phpSniff');
	$client = new phpSniff($agent);
	$osSearchOrder = $client->osSearchOrder;

	foreach($osSearchOrder as $key) {
		if(preg_match("/$key/i",$agent)) {
			return $client->osAlias[$key];
			break;
		}
	}
	return 'Unknown';
}

/**
* @param string SQL with ordering As value and 'name field' AS text
* @param integer The length of the truncated headline
*/
function mosGetOrderingList($sql,$chop = '30') {
	$database = &database::getInstance();

	$order = array();
	$database->setQuery($sql);
	if(!($orders = $database->loadObjectList())) {
		if($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} else {
			$order[] = mosHTML::makeOption(1,_FIRST);
			return $order;
		}
	}
	$order[] = mosHTML::makeOption(0,'0 '._FIRST);
	for($i = 0,$n = count($orders); $i < $n; $i++) {
		if(strlen($orders[$i]->text) > $chop) {
			$text = Jstring::substr($orders[$i]->text,0,$chop)."...";
		} else {
			$text = $orders[$i]->text;
		}
		$order[] = mosHTML::makeOption($orders[$i]->value,$orders[$i]->value.' ('.$text.')');
	}
	$order[] = mosHTML::makeOption($orders[$i - 1]->value + 1,($orders[$i - 1]->value +1).' '._LAST);
	return $order;
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

function mosMenuCheck($Itemid,$menu_option,$task,$gid) {
	$database = &database::getInstance();
	$mainframe = &mosMainFrame::getInstance();

	$results = array();
	$access = 0;

	if($Itemid != '' && $Itemid != 0 && $Itemid != 99999999) {
		$query = "SELECT* FROM #__menu WHERE id = ".(int)$Itemid;
		$all_menus = $mainframe->get('all_menu');
		foreach($all_menus as $menu){
			if(isset($menu[$Itemid])){
				$results[0]=$menu[$Itemid];
				$access = $results[0]->access;
			}
		}
	} else {
		$dblink = "index.php?option=".$database->getEscaped($menu_option, true);
		if($task != '') {
			$dblink .= "&task=".$database->getEscaped($task, true);
		}
		$query = "SELECT* FROM #__menu WHERE published = 1 AND link LIKE '$dblink%'";
		$database->setQuery($query);
		$results = $database->loadObjectList();
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
function mosFormatDate($date,$format = "",$offset = null) {

	if ($date == '0000-00-00 00:00:00') return $date;//database::$_nullDate - при ошибках парсера

	if($format == '') {
		// %Y-%m-%d %H:%M:%S
		$format = _DATE_FORMAT_LC;
	}
	if(is_null($offset)) {
		$offset = Jconfig::getInstance()->config_offset;
	}
	if($date && ereg("([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})",$date,$regs)) {
		$date = mktime($regs[4],$regs[5],$regs[6],$regs[2],$regs[3],$regs[1]);
		$date = $date > -1?strftime($format,$date + ($offset* 60* 60)):'-';
	}
	return $date;
}

/**
* Returns current date according to current local and time offset
* @param string format optional format for strftime
* @returns current date
*/
function mosCurrentDate($format = "") {
	if($format == '') {
		$format = _DATE_FORMAT_LC;
	}
	$date = strftime($format,time() + (Jconfig::getInstance()->config_offset* 60* 60));
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
		$image = Jconfig::getInstance()->config_live_site.'/includes/js/ThemeOffice/'.$image;
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
	$tip .= '<img src="'.Jconfig::getInstance()->config_live_site.'/includes/js/ThemeOffice/warning.png" border="0" alt="предупреждение"/></a>';
	return $tip;
}

function mosCreateGUID() {
	srand((double)microtime()* 1000000);
	$r = rand();
	$u = uniqid(getmypid().$r.(double)microtime()* 1000000,1);
	$m = md5($u);
	return ($m);
}

function mosCompressID($ID) {
	return (Base64_encode(pack("H*",$ID)));
}

function mosExpandID($ID) {
	return (implode(unpack("H*",Base64_decode($ID)),''));
}

/**
* Function to create a mail object for futher use (uses phpMailer)
* @param string From e-mail address
* @param string From name
* @param string E-mail subject
* @param string Message body
* @return object Mail object
*/
function mosCreateMail($from = '',$fromname = '',$subject,$body) {

	mosMainFrame::addLib('phpmailer');
	$mail = new mosPHPMailer();

	$config = &Jconfig::getInstance();

	$mail->PluginDir = $config->config_absolute_path.DS.'includes/libraries/phpmailer/';
	$mail->SetLanguage(_LANGUAGE,$config->config_absolute_path.DS.'includes/libraries/phpmailer/language/');
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
	$config = &Jconfig::getInstance();

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
	if($config->config_debug) {
		//$mosDebug->message( "Письма отправлены: $mailssend");
	}
	if($mail->error_count > 0) {
		//$mosDebug->message( "The mail message $fromname <$from> about $subject to $recipient <b>failed</b><br /><pre>$body</pre>", false );
		//$mosDebug->message( "Mailer Error: " . $mail->ErrorInfo . "" );
	}
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
	if($invalid) {
		return false;
	} else {
		return true;
	}
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

if(!function_exists('html_entity_decode')) {
	/**
	* html_entity_decode function for backward compatability in PHP
	* @param string
	* @param string
	*/
	function html_entity_decode($string,$opt = ENT_COMPAT) {
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		if($opt & 1) { // Translating single quotes
			$trans_tbl["&apos;"] = "'";
		}
		if(!($opt & 2)) { // Not translating double quotes
			unset($trans_tbl["&quot;"]);
		}
		return strtr($string,$trans_tbl);
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
	/**
	* Constructor
	*/
	function mosMambotHandler() {
		$this->_events = array();
	}

	/**
	* Loads all the bot files for a particular group
	* @param string The group name, relates to the sub-directory in the mambots directory
	*/
	function loadBotGroup($group, $load = 0) {
		global $my;

		$database = &database::getInstance();
		$config = &Jconfig::getInstance();

		if(is_object($my)) {
			$gid = $my->gid;
		} else {
			$gid = 0;
		}
		$group = trim($group);
		
		$where_ac = ($config->config_disable_access_control==0) ? ' AND access <= '.(int)$gid : '';

		switch($group) {
			case 'content':
				if(!defined('_JOS_CONTENT_MAMBOTS')) {
					/** ensure that query is only called once*/
					define('_JOS_CONTENT_MAMBOTS',1);
					$where_ac .= ($config->config_use_unpublished_mambots==1) ? '' : ' AND published=1';
					$query = 'SELECT folder, element, published, params FROM #__mambots WHERE folder = \'content\''.$where_ac.' ORDER BY ordering';
					$database->setQuery($query);
					// load query into class variable _content_mambots
					if(!($this->_content_mambots = $database->loadObjectList())) {
						return false;
					}
					foreach($this->_content_mambots as $bot) {
						$this->_content_mambot_params[$bot->element]->params = $bot->params;
					}
				}
				// pull bots to be processed from class variable
				$bots = $this->_content_mambots;
				break;

			case 'search':
				if(!defined('_JOS_SEARCH_MAMBOTS')) {
					define('_JOS_SEARCH_MAMBOTS',1);

					$query = 'SELECT folder, element, published, params FROM #__mambots WHERE published = 1'.$where_ac.' AND folder = \'search\' ORDER BY ordering, id DESC';
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
				$query = 'SELECT folder, element, published, params FROM #__mambots WHERE published = 1'.$where_ac.' AND folder = '.$database->Quote($group).' ORDER BY ordering, id DESC';
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
		if(!$load){
			return true;
		}else{
			return $bots;
		}
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

		$path = Jconfig::getInstance()->config_absolute_path.DS.'mambots'.DS.$folder.DS.$element.'.php';
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
			$lang = mosMainFrame::getInstance()->getLangFile('bot_'.$element);
			if($lang){
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
						$result[] = call_user_func_array($func[0],$args);
					} elseif($this->_bots[$func[1]]->published) {
							$result[] = call_user_func_array($func[0],$args);
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
						return call_user_func_array($func[0],$args);
					}
				}
			}
		}
		return null;
	}
	
	//Адресный вызов мамбота
	function call_mambot($event, $element, $args){

			if(isset($this->_events[$event])) { 
			foreach($this->_events[$event] as $func) {
				if($this->_bots[$func[1]]->element == $element && function_exists($func[0])) {
					$this->_mambot_params[$element] = $this->_bots[$func[1]]->params;
					if($this->_bots[$func[1]]->published) {
						return call_user_func($func[0],$args);
					}
				}
			}
		}
		return null;
	}
}

/**
* Создание табов
* @package Joostina
*/
class mosTabs {
	/**
	@var int Use cookies*/
	var $useCookies = 0;
	/**
	* Constructor
	* Includes files needed for displaying tabs and sets cookie options
	* @param int useCookies, if set to 1 cookie will hold last used tab between page refreshes
	*/
	function mosTabs($useCookies,$xhtml = 0) {
		$config = &Jconfig::getInstance();
		$mainframe = &MosMainFrame::getInstance();

		// активация gzip сжатия css и js файлов
		if($config->config_gz_js_css) {
			$css_f = 'joostina.tabs.css.php';
			$js_f = 'joostina.tabs.js.php';
		} else {
			$css_f = 'tabpane.css';
			$js_f = 'tabpane_mini.js';
		}
		$css = '<link rel="stylesheet" type="text/css" media="all" href="'.$config->config_live_site.'/includes/js/tabs/'.$css_f.'" id="luna-tab-style-sheet" />';
		$js = '<script type="text/javascript" src="'.$config->config_live_site.'/includes/js/tabs/'.$js_f.'"></script>';
		/* boston, хак, запрет повторного включения css и js файлов в документ*/
		if(!defined('_MTABS_LOADED')) {
			define('_MTABS_LOADED',1);

			if($xhtml) {
				$mainframe->addCustomHeadTag($css);
				$mainframe->addCustomHeadTag($js);
			} else {
				echo $css."\n\t";
				echo $js."\n\t";
			}
			$this->useCookies = $useCookies;
		}
	}
	/**
	* creates a tab pane and creates JS obj
	* @param string The Tab Pane Name
	*/
	function startPane($id) {
		echo '<div class="tab-page" id="'.$id.'">';
		echo '<script type="text/javascript">var tabPane1 = new WebFXTabPane( document.getElementById( "'.$id.'" ), '.$this->useCookies.' )</script>';
	}
	/**
	* Ends Tab Pane
	*/
	function endPane() {
		echo '</div>';
	}
	/*
	* Creates a tab with title text and starts that tabs page
	* @param tabText - This is what is displayed on the tab
	* @param paneid - This is the parent pane to build this tab on
	*/
	function startTab($tabText,$paneid) {
		echo '<div class="tab-page" id="'.$paneid.'">';
		echo '<h2 class="tab">'.$tabText.'</h2>';
		echo '<script type="text/javascript">tabPane1.addTabPage( document.getElementById( "'.$paneid.'" ) );</script>';
	}
	/*
	* Ends a tab page
	*/
	function endTab() {
		echo '</div>';
	}
}

/**
* Common HTML Output Files
* @package Joostina
*/
class mosAdminMenus {
	/**
	* build the select list for Menu Ordering
	*/
	function Ordering(&$row,$id) {
		$database = &database::getInstance();

		if($id) {
			$query = "SELECT ordering AS value, name AS text"
					." FROM #__menu"
					."\n WHERE menutype = ".$database->Quote($row->menutype)
					."\n AND parent = ".(int)$row->parent."\n AND published != -2"
					."\n ORDER BY ordering";
			$order = mosGetOrderingList($query);
			$ordering = mosHTML::selectList($order,'ordering','class="inputbox" size="1"','value','text',intval($row->ordering));
		} else {
			$ordering = '<input type="hidden" name="ordering" value="'.$row->ordering.'" />'._NEW_ITEM_LAST;
		}
		return $ordering;
	}
	/**
	* build the select list for access level
	*/
	function Access(&$row,$guest=false) {
		$database = &database::getInstance();

		$query = "SELECT id AS value, name AS text FROM #__groups ORDER BY id";
		$database->setQuery($query);
		$groups = $database->loadObjectList();
		$guest ? $groups[]=mosHTML::makeOption(3,_COM_MODULES_GUEST) : null;
		$access = mosHTML::selectList($groups,'access','class="inputbox" size="4"','value','text',intval($row->access));
		return $access;
	}
	/**
	* build the select list for parent item
	*/
	function Parent(&$row) {
		$database = &database::getInstance();

		$id = '';
		if($row->id) $id = "\n AND id != ".(int)$row->id;
		// get a list of the menu items
		// excluding the current menu item and its child elements
		$query = "SELECT m.* FROM #__menu m WHERE menutype = ".$database->Quote($row->menutype)." AND published != -2".$id." ORDER BY parent, ordering";
		$database->setQuery($query);
		$mitems = $database->loadObjectList();
		// establish the hierarchy of the menu
		$children = array();
		if($mitems) {
			// first pass - collect children
			foreach($mitems as $v) {
				$pt = $v->parent;
				$list = @$children[$pt]?$children[$pt]:array();
				array_push($list,$v);
				$children[$pt] = $list;
			}
		}
		// second pass - get an indent list of the items
		$list = mosTreeRecurse(0,'',array(),$children,20,0,0);
		// assemble menu items to the array
		$mitems = array();
		$mitems[] = mosHTML::makeOption('0','Top');
		foreach($list as $item) {
			$mitems[] = mosHTML::makeOption($item->id,'&nbsp;&nbsp;&nbsp;'.$item->treename);
		}
		$output = mosHTML::selectList($mitems,'parent','class="inputbox" size="10"','value','text',$row->parent);
		return $output;
	}

	/**
	* build a radio button option for published state
	*/
	function Published(&$row) {
		$published = mosHTML::yesnoRadioList('published','class="inputbox"',$row->published);
		return $published;
	}

	/**
	* build the link/url of a menu item
	*/
	function Link(&$row,$id,$link = null) {
		global $mainframe;

		if($id) {
			switch($row->type) {
				case 'content_item_link':
				case 'content_typed':
					// load menu params
					$params = new mosParameters($row->params,$mainframe->getPath('menu_xml',$row->type),'menu');

					if($params->get('unique_itemid')) {
						$row->link .= '&Itemid='.$row->id;
					} else {
						$temp = split('&task=view&id=',$row->link);
						$row->link .= '&Itemid='.$mainframe->getItemid($temp[1],0,0);
					}

					$link = $row->link;
					break;

				default:
					if($link) {
						$link = $row->link;
					} else {
						$link = $row->link.'&amp;Itemid='.$row->id;
					}
					break;
			}
		} else {
			$link = null;
		}

		return $link;
	}

	/**
	* build the select list for target window
	*/
	function Target(&$row) {
		$click[] = mosHTML::makeOption('0',_ADM_MENUS_TARGET_CUR_WINDOW);
		$click[] = mosHTML::makeOption('1',_ADM_MENUS_TARGET_NEW_WINDOW_WITH_PANEL);
		$click[] = mosHTML::makeOption('2',_ADM_MENUS_TARGET_NEW_WINDOW_WITHOUT_PANEL);
		$target = mosHTML::selectList($click,'browserNav','class="inputbox" size="4"','value','text',intval($row->browserNav));
		return $target;
	}

	/**
	* build the multiple select list for Menu Links/Pages
	*/
	function MenuLinks(&$lookup,$all = null,$none = null,$unassigned = 1) {
		$database = &database::getInstance();

		// get a list of the menu items
		$query = "SELECT m.* FROM #__menu AS m WHERE m.published = 1 ORDER BY m.menutype, m.parent, m.ordering";
		$database->setQuery($query);
		$mitems = $database->loadObjectList();
		$mitems_temp = $mitems;

		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach($mitems as $v) {
			$pt = $v->parent;
			$list = @$children[$pt]?$children[$pt]:array();
			array_push($list,$v);
			$children[$pt] = $list;
		}
		// second pass - get an indent list of the items
		$list = mosTreeRecurse(intval($mitems[0]->parent),'',array(),$children,20,0,0);

		// Code that adds menu name to Display of Page(s)
		$text_count = 0;
		$mitems_spacer = $mitems_temp[0]->menutype;
		foreach($list as $list_a) {
			foreach($mitems_temp as $mitems_a) {
				if($mitems_a->id == $list_a->id) {
					// Code that inserts the blank line that seperates different menus
					if($mitems_a->menutype != $mitems_spacer) {
						$list_temp[] = mosHTML::makeOption(-999,'----');
						$mitems_spacer = $mitems_a->menutype;
					}

					// do not display `url` menu item types that contain `index.php` and `Itemid`
					if(!($mitems_a->type == 'url' && strpos($mitems_a->link,'index.php') !== false &&
						strpos($mitems_a->link,'Itemid=') !== false)) {
						$text = $mitems_a->menutype.' : '.$list_a->treename;
						$list_temp[] = mosHTML::makeOption($list_a->id,$text);

						if(strlen($text) > $text_count) {
							$text_count = strlen($text);
						}
					}
				}
			}
		}
		$list = $list_temp;

		$mitems = array();
		if($all) {
			// prepare an array with 'all' as the first item
			$mitems[] = mosHTML::makeOption(0,_ALL);
			// adds space, in select box which is not saved
			$mitems[] = mosHTML::makeOption(-999,'----');
		}
		if($none) {
			// prepare an array with 'all' as the first item
			$mitems[] = mosHTML::makeOption(-999,_NOT_EXISTS);
			// adds space, in select box which is not saved
			$mitems[] = mosHTML::makeOption(-999,'----');
		}
		if($unassigned) {
			// prepare an array with 'all' as the first item
			$mitems[] = mosHTML::makeOption(99999999,_WITH_UNASSIGNED);
			// adds space, in select box which is not saved
			$mitems[] = mosHTML::makeOption(-999,'----');
		}

		// append the rest of the menu items to the array
		foreach($list as $item) {
			$mitems[] = mosHTML::makeOption($item->value,$item->text);
		}
/*
		// добавляем в список типы страниц "по умолчанию"
		$pages = array(
			mosHTML::makeOption(0,'----'),
			mosHTML::makeOption(0,_PAGES.' : '._CREATE_ACCOUNT),
			mosHTML::makeOption(0,_PAGES.' : '._LOST_PASSWORD),
		);
		$mitems = array_merge($mitems,$pages);
*/

		$pages = mosHTML::selectList($mitems,'selections[]','class="inputbox" size="26" multiple="multiple"','value','text',$lookup);
		return $pages;
	}


	/**
	* build the select list to choose a category
	*/
	function Category(&$menu,$id,$javascript = '') {
		$database = &database::getInstance();

		$query = "SELECT c.id AS `value`, c.section AS `id`, CONCAT_WS( ' / ', s.title, c.title) AS `text` FROM #__sections AS s INNER JOIN #__categories AS c ON c.section = s.id WHERE s.scope = 'content' ORDER BY s.name, c.name";
		$database->setQuery($query);
		$rows = $database->loadObjectList();
		$category = '';
		if($id) {
			foreach($rows as $row) {
				if($row->value == $menu->componentid) {
					$category = $row->text;
				}
			}
			$category .= '<input type="hidden" name="componentid" value="'.$menu->componentid.'" />';
			$category .= '<input type="hidden" name="link" value="'.$menu->link.'" />';
		} else {
			$category = mosHTML::selectList($rows,'componentid','class="inputbox" size="10"'.$javascript,'value','text');
			$category .= '<input type="hidden" name="link" value="" />';
		}
		return $category;
	}

	/**
	* build the select list to choose a section
	*/
	function Section(&$menu,$id,$all = 0) {
		$database = &database::getInstance();

		$query = "SELECT s.id AS `value`, s.id AS `id`, s.title AS `text` FROM #__sections AS s WHERE s.scope = 'content' ORDER BY s.name";
		$database->setQuery($query);
		if($all) {
			$rows[] = mosHTML::makeOption(0,'- Все разделы -');
			$rows = array_merge($rows,$database->loadObjectList());
		} else {
			$rows = $database->loadObjectList();
		}

		if($id) {
			foreach($rows as $row) {
				if($row->value == $menu->componentid) {
					$section = $row->text;
				}
			}
			$section .= '<input type="hidden" name="componentid" value="'.$menu->componentid.'" />';
			$section .= '<input type="hidden" name="link" value="'.$menu->link.'" />';
		} else {
			$section = mosHTML::selectList($rows,'componentid','class="inputbox" size="10"','value','text');
			$section .= '<input type="hidden" name="link" value="" />';
		}
		return $section;
	}

	/**
	* build the select list to choose a component
	*/
	function Component(&$menu,$id) {
		$database = &database::getInstance();

		$query = "SELECT c.id AS value, c.name AS text, c.link FROM #__components AS c WHERE c.link != '' ORDER BY c.name";
		$database->setQuery($query);
		$rows = $database->loadObjectList();

		if($id) {
			// existing component, just show name
			foreach($rows as $row) {
				if($row->value == $menu->componentid) {
					$component = $row->text;
				}else{
					$component = $menu->name;
				}
			}
			$component .= '<input type="hidden" name="componentid" value="'.$menu->componentid.'" />';
		} else {
			$component = mosHTML::selectList($rows,'componentid','class="inputbox" size="10"','value','text');
		}

		return $component;
	}

	/**
	* build the select list to choose a component
	*/
	function ComponentName(&$menu) {
		$database = &database::getInstance();

		$query = "SELECT c.id AS value, c.name AS text, c.link"
				."\n FROM #__components AS c"
				."\n WHERE c.link != ''"
				."\n ORDER BY c.name";
		$database->setQuery($query);
		$rows = $database->loadObjectList();

		$component = 'Component';
		foreach($rows as $row) {
			if($row->value == $menu->componentid) {
				$component = $row->text;
			}
		}

		return $component;
	}

	/**
	* build the select list to choose an image
	*/
	function Images($name,&$active,$javascript = null,$directory = null) {

		if(!$directory) {
			$directory = '/images/stories';
		}

		if(!$javascript) {
			$javascript = "onchange=\"javascript:if (document.forms[0].image.options[selectedIndex].value!='') {document.imagelib.src='..$directory/' + document.forms[0].image.options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
		}

		$imageFiles = mosReadDirectory(Jconfig::getInstance()->config_absolute_path.$directory);
		$images = array(mosHTML::makeOption('','- '._CHOOSE_IMAGE.' -'));
		foreach($imageFiles as $file) {
			if(eregi("bmp|gif|jpg|png",$file)) {
				$images[] = mosHTML::makeOption($file);
			}
		}
		$images = mosHTML::selectList($images,$name,'class="inputbox" size="1" '.$javascript,'value','text',$active);

		return $images;
	}

	/**
	* build the select list for Ordering of a specified Table
	*/
	function SpecificOrdering(&$row,$id,$query,$neworder = 0,$limit = 30) {
		if($neworder) {
			$text = _NEW_ITEM_FIRST;
		} else {
			$text = _NEW_ITEM_LAST;
		}

		if($id) {
			$order = mosGetOrderingList($query,$limit);
			$ordering = mosHTML::selectList($order,'ordering','class="inputbox" size="1"','value','text',intval($row->ordering));
		} else {
			$ordering = '<input type="hidden" name="ordering" value="'.$row->ordering.'" />'.$text;
		}
		return $ordering;
	}

	/**
	* Select list of active users
	*/
	function UserSelect($name,$active,$nouser = 0,$javascript = null,$order = 'name',$reg = 1) {
		$database = &database::getInstance();

		$and = '';
		if($reg) {
			// does not include registered users in the list
			$and = "\n AND gid > 18";
		}

		$query = "SELECT id AS value, name AS text FROM #__users WHERE block = 0 $and ORDER BY $order";
		$database->setQuery($query);
		if($nouser) {
			$users[] = mosHTML::makeOption('0','- '._NO_USER.' -');
			$users = array_merge($users,$database->loadObjectList());
		} else {
			$users = $database->loadObjectList();
		}

		$users = mosHTML::selectList($users,$name,'class="inputbox" size="1" '.$javascript,'value','text',$active);

		return $users;
	}

	/**
	* Select list of positions - generally used for location of images
	*/
	function Positions($name,$active = null,$javascript = null,$none = 1,$center = 1,
		$left = 1,$right = 1) {
		if($none) {
			$pos[] = mosHTML::makeOption('',_NONE);
		}
		if($center) {
			$pos[] = mosHTML::makeOption('center',_CENTER);
		}
		if($left) {
			$pos[] = mosHTML::makeOption('left',_LEFT);
		}
		if($right) {
			$pos[] = mosHTML::makeOption('right',_RIGHT);
		}

		$positions = mosHTML::selectList($pos,$name,'class="inputbox" size="1"'.$javascript,'value','text',$active);

		return $positions;
	}

	/**
	* Select list of active categories for components
	*/
	function ComponentCategory($name,$section,$active = null,$javascript = null,$order ='ordering',$size = 1,$sel_cat = 1) {
		$database = &database::getInstance();

		$query = "SELECT id AS value, name AS text"
				."\n FROM #__categories"
				."\n WHERE section = ".$database->Quote($section)
				."\n AND published = 1"
				."\n ORDER BY $order";
		$database->setQuery($query);
		if($sel_cat) {
			$categories[] = mosHTML::makeOption('0',_SEL_CATEGORY);
			$categories = array_merge($categories,$database->loadObjectList());
		} else {
			$categories = $database->loadObjectList();
		}

		if(count($categories) < 1) {
			mosRedirect('index2.php?option=com_categories&section='.$section,_CREATE_CATEGORIES_FIRST);
		}

		$category = mosHTML::selectList($categories,$name,'class="inputbox" size="'.$size.'" '.$javascript,'value','text',$active);

		return $category;
	}

	/**
	* Select list of active sections
	*/
	function SelectSection($name,$active = null,$javascript = null,$order ='ordering',$scope='content') {
		$database = &database::getInstance();

		$categories[] = mosHTML::makeOption('0',_SEL_SECTION);
		$query = "SELECT id AS value, title AS text"
				."\n FROM #__sections"
				."\n WHERE published = 1 AND scope='$scope'"
				."\n ORDER BY $order";
		$database->setQuery($query);
		$sections = array_merge($categories,$database->loadObjectList());

		$category = mosHTML::selectList($sections,$name,'class="inputbox" size="1" '.$javascript,'value','text',$active);

		return $category;
	}

	/**
	* Select list of menu items for a specific menu
	*/
	function Links2Menu($type,$and) {
		$database = &database::getInstance();

		$query = "SELECT* FROM #__menu WHERE type = ".$database->Quote($type)." AND published = 1".$and;
		$database->setQuery($query);
		$menus = $database->loadObjectList();
		return $menus;
	}

	/**
	* Select list of menus
	* @param string The control name
	* @param string Additional javascript
	* @return string A select list
	*/
	function MenuSelect($name = 'menuselect',$javascript = null) {
		$database = &database::getInstance();

		$query = "SELECT params FROM #__modules WHERE module = 'mod_mainmenu' OR module = 'mod_mljoostinamenu'";
		$database->setQuery($query);
		$menus = $database->loadObjectList();
		$i=0;
		$menuselect = array();
		$menus_arr=array();
		foreach($menus as $menu) {
			$params = mosParseParams($menu->params);
			if(!in_array($params->menutype, $menus_arr)){
				$menuselect[$i]->value = $params->menutype;
				$menuselect[$i]->text = $params->menutype;
				$menus_arr[$i]= $params->menutype;
				$i++;
			}
		}
		SortArrayObjects($menuselect,'text',1);
		$menus = mosHTML::selectList($menuselect,$name,'class="inputbox" size="10" '.$javascript,'value','text');
		return $menus;
	}

	/**
	* Internal function to recursive scan the media manager directories
	* @param string Path to scan
	* @param string root path of this folder
	* @param array  Value array of all existing folders
	* @param array  Value array of all existing images
	*/
	function ReadImages($imagePath,$folderPath,&$folders,&$images) {
		$imgFiles = mosReadDirectory($imagePath);

		foreach($imgFiles as $file) {
			$ff_ = $folderPath.$file.'/';
			$ff = $folderPath.$file;
			$i_f = $imagePath.'/'.$file;

			if(is_dir($i_f) && $file != 'CVS' && $file != '.svn') {
				$folders[] = mosHTML::makeOption($ff_);
				mosAdminMenus::ReadImages($i_f,$ff_,$folders,$images);
			} else
				if(eregi("bmp|gif|jpg|png",$file) && is_file($i_f)) {
					// leading / we don't need
					$imageFile = substr($ff,1);
					$images[$folderPath][] = mosHTML::makeOption($imageFile,$file);
				}
		}
	}

	/**
	* Internal function to recursive scan the media manager directories
	* @param string Path to scan
	* @param string root path of this folder
	* @param array  Value array of all existing folders
	* @param array  Value array of all existing images
	*/
	function ReadImagesX(&$folders,&$images) {

		if($folders[0]->value != '*0*') {
			foreach($folders as $folder) {
				$imagePath = Jconfig::getInstance()->config_absolute_path.'/images/stories'.$folder->value;
				$imgFiles = mosReadDirectory($imagePath);
				$folderPath = $folder->value.'/';

				foreach($imgFiles as $file) {
					$ff = $folderPath.$file;
					$i_f = $imagePath.'/'.$file;

					if(eregi("bmp|gif|jpg|png",$file) && is_file($i_f)) {
						// leading / we don't need
						$imageFile = substr($ff,1);
						$images[$folderPath][] = mosHTML::makeOption($imageFile,$file);
					}
				}
			}
		} else {
			$folders = array();
			$folders[] = mosHTML::makeOption('None');
		}
	}

	function GetImageFolders(&$temps) {
		if($temps[0]->value != 'None') {
			foreach($temps as $temp) {
				if(substr($temp->value,-1,1) != '/') {
					$temp = $temp->value.'/';
					$folders[] = mosHTML::makeOption($temp,$temp);
				} else {
					$temp = $temp->value;
					$temp = ampReplace($temp);
					$folders[] = mosHTML::makeOption($temp,$temp);
				}
			}
		} else {
			$folders[] = mosHTML::makeOption(_NOT_CHOOSED);
		}

		$javascript = "onchange=\"changeDynaList( 'imagefiles', folderimages, document.adminForm.folders.options[document.adminForm.folders.selectedIndex].value, 0, 0);\"";
		$getfolders = mosHTML::selectList($folders,'folders','class="inputbox" size="1" '.$javascript,'value','text','/');

		return $getfolders;
	}

	function GetImages(&$images,$path,$base = '/') {
		if(is_array($base) && count($base) > 0) {
			if($base[0]->value != '/') {
				$base = $base[0]->value.'/';
			} else {
				$base = $base[0]->value;
			}
		} else {
			$base = '/';
		}

		if(!isset($images[$base])) {
			$images[$base][] = mosHTML::makeOption('');
		}

		$javascript = "onchange=\"previewImage( 'imagefiles', 'view_imagefiles', '$path/' )\" onfocus=\"previewImage( 'imagefiles', 'view_imagefiles', '$path/' )\"";
		$getimages = mosHTML::selectList($images[$base],'imagefiles','class="inputbox" size="10" multiple="multiple" '.$javascript,'value','text',null);

		return $getimages;
	}

	function GetSavedImages(&$row,$path) {
		$images2 = array();
		foreach($row->images as $file) {
			$temp = explode('|',$file);
			if(strrchr($temp[0],'/')) {
				$filename = substr(strrchr($temp[0],'/'),1);
			} else {
				$filename = $temp[0];
			}
			$images2[] = mosHTML::makeOption($file,$filename);
		}
		$javascript = "onchange=\"previewImage( 'imagelist', 'view_imagelist', '$path/' ); showImageProps( '$path/' ); \"";
		$imagelist = mosHTML::selectList($images2,'imagelist','class="inputbox" size="10" '.$javascript,'value','text');
		return $imagelist;
	}

	/**
	* Checks to see if an image exists in the current templates image directory
	* if it does it loads this image.  Otherwise the default image is loaded.
	* Also can be used in conjunction with the menulist param to create the chosen image
	* load the default or use no image
	*/
	function ImageCheck($file,$directory = '/images/M_images/',$param = null,$param_directory ='/images/M_images/',$alt = null,$name = null,$type = 1,$align = 'middle',$title = null,$admin = null) {
		$mainframe = &MosMainFrame::getInstance();
		$config = &Jconfig::getInstance();

		$cur_template = $mainframe->getTemplate();
		$id		= $name ? ' id="'.$name.'"':'';
		$name	= $name ? ' name="'.$name.'"':'';
		$title	= $title ? ' title="'.$title.'"':'';
		$alt	= $alt ? ' alt="'.$alt.'"':' alt=""';
		$align	= $align ? ' align="'.$align.'"':'';
		// change directory path from frontend or backend
		if($admin) {
			$path = DS.ADMINISTRATOR_DIRECTORY.'/templates/'.$cur_template.'/images/ico/';
		} else {
			$path = '/templates/'.$cur_template.'/images/ico/';
		}
		if($param) {
			$image = $config->config_live_site.$param_directory.$param;
			if($type) {
				$image = '<img src="'.$image.'" '.$alt.$id.$name.$align.' border="0" />';
			}
		} else
			if($param == -1) {
				$image = '';
			} else {
				if(file_exists($config->config_absolute_path.$path.$file)) {
					$image = $config->config_live_site.$path.$file;
				} else {
					$image = $config->config_live_site.$directory.$file;
				}
				if($type) {
					$image = '<img src="'.$image.'" '.$alt.$id.$name.$title.$align.' border="0" />';
				}
			}
			return $image;
	}

	/**
	* Checks to see if an image exists in the current templates image directory
	* if it does it loads this image.  Otherwise the default image is loaded.
	* Also can be used in conjunction with the menulist param to create the chosen image
	* load the default or use no image
	*/
	function ImageCheckAdmin($file,$directory = '/administrator/images/',$param = null,
		$param_directory = '/administrator/images/',$alt = null,$name = null,$type = 1,
		$align = 'middle',$title = null) {
		$image = mosAdminMenus::ImageCheck($file,$directory,$param,$param_directory,$alt,$name,$type,$align,$title,1);
		return $image;
	}

	function menutypes() {
		$database = &database::getInstance();

		$query = "SELECT params FROM #__modules WHERE module = 'mod_mainmenu' OR module = 'mod_mljoostinamenu' ORDER BY title";
		$database->setQuery($query);
		$modMenus = $database->loadObjectList();

		$query = "SELECT menutype FROM #__menu GROUP BY menutype ORDER BY menutype";
		$database->setQuery($query);
		$menuMenus = $database->loadObjectList();
		$menuTypes = '';
		foreach($modMenus as $modMenu) {
			$check = 1;
			mosMakeHtmlSafe($modMenu);
			$modParams = mosParseParams($modMenu->params);
			$menuType = @$modParams->menutype;
			if(!$menuType) {
				$menuType = 'mainmenu';
			}
			// stop duplicate menutype being shown
			if(!is_array($menuTypes)) {
				// handling to create initial entry into array
				$menuTypes[] = $menuType;
			} else {
				$check = 1;
				foreach($menuTypes as $a) {
					if($a == $menuType) {
						$check = 0;
					}
				}
				if($check) {
					$menuTypes[] = $menuType;
				}
			}
		}
		// add menutypes from jos_menu
		foreach($menuMenus as $menuMenu) {
			$check = 1;
			foreach($menuTypes as $a) {
				if($a == $menuMenu->menutype) {
					$check = 0;
				}
			}
			if($check) {
				$menuTypes[] = $menuMenu->menutype;
			}
		}
		// sorts menutypes
		asort($menuTypes);
		return $menuTypes;
	}

	/*
	* loads files required for menu items
	*/
	function menuItem($item) {
		//global $mosConfig_absolute_path;
		$path = Jconfig::getInstance()->config_absolute_path.DS.ADMINISTRATOR_DIRECTORY.'/components/com_menus/'.$item.'/';
		include_once ($path.$item.'.class.php');
		include_once ($path.$item.'.menu.html.php');
	}
}


class mosCommonHTML {
	function ContentLegend() {
		$mainframe = &mosMainFrame::getInstance();
		$cur_file_icons_path = $mainframe->getCfg('live_site').'/'.ADMINISTRATOR_DIRECTORY.'/templates/'.$mainframe->getTemplate().'/images/ico';
?>
		<table cellspacing="0" cellpadding="4" border="0" align="center">
			<tr align="center">
				<td><img src="<?php echo $cur_file_icons_path;?>/publish_y.png" border="0" /></td>
				<td><?php echo _PUBLISHED_VUT_NOT_ACTIVE?> |</td>
				<td><img src="<?php echo $cur_file_icons_path;?>/publish_g.png" border="0" /></td>
				<td><?php echo _PUBLISHED_AND_ACTIVE?> |</td>
				<td><img src="<?php echo $cur_file_icons_path;?>/publish_r.png" border="0" /></td>
				<td><?php echo _PUBLISHED_BUT_DATE_EXPIRED?> |</td>
				<td><img src="<?php echo $cur_file_icons_path;?>/publish_x.png" border="0" /></td>
				<td><?php echo _NOT_PUBLISHED?></td>
			</tr>
		</table>
<?php
	}

	function menuLinksContent(&$menus) {
?>
			<script language="javascript" type="text/javascript">
			function go2( pressbutton, menu, id ) {
				var form = document.adminForm;
			// assemble the images back into one field
			var temp = new Array;
			for (var i=0, n=form.imagelist.options.length; i < n; i++) {
				temp[i] = form.imagelist.options[i].value;
			}
			form.images.value = temp.join( '\n' );

						if (pressbutton == 'go2menu') {
								form.menu.value = menu;
								submitform( pressbutton );
								return;
						}

						if (pressbutton == 'go2menuitem') {
								form.menu.value		 = menu;
								form.menuid.value		 = id;
								submitform( pressbutton );
								return;
						}
				}
				</script>
<?php
		foreach($menus as $menu) {
?>
						<tr>
								<td colspan="2">
								<hr />
								</td>
						</tr>
						<tr>
								<td width="90px" valign="top">
								<?php echo _MENU?>
								</td>
								<td>
								<a href="javascript:go2( 'go2menu', '<?php echo $menu->menutype; ?>' );"><?php echo $menu->menutype; ?></a>
								</td>
						</tr>
						<tr>
								<td width="90px" valign="top">
								<?php echo _LINK_NAME?>
								</td>
								<td>
								<strong>
								<a href="javascript:go2( 'go2menuitem', '<?php echo $menu->menutype; ?>', '<?php echo $menu->id; ?>' );" >
								<?php echo $menu->name; ?>
								</a>
								</strong>
								</td>
						</tr>
						<tr>
							<td width="90px" valign="top">
								<?php echo _O_STATE?>
							</td>
							<td>
<?php
			switch($menu->published) {
				case - 2:
					echo '<font color="red">'._MENU_EXPIRED.'</font>';
					break;
				case 0:
					echo _UNPUBLISHED;
					break;
				case 1:
				default:
					echo '<font color="green">'._PUBLISHED.'</font>';
					break;
			}
?>
								</td>
						</tr>
<?php
		}
?>
				<input type="hidden" name="menu" value="" />
				<input type="hidden" name="menuid" value="" />
<?php
	}

	function menuLinksSecCat(&$menus) {
?>
				<script language="javascript" type="text/javascript">
				function go2( pressbutton, menu, id ) {
						var form = document.adminForm;

						if (pressbutton == 'go2menu') {
								form.menu.value = menu;
								submitform( pressbutton );
								return;
						}

						if (pressbutton == 'go2menuitem') {
								form.menu.value		 = menu;
								form.menuid.value	 = id;
								submitform( pressbutton );
								return;
						}
				}
				</script>
<?php
		foreach($menus as $menu) {
?>
						<tr>
							<td colspan="2"><hr /></td>
						</tr>
						<tr>
							<td width="90px" valign="top"><?php echo _MENU?></td>
							<td>
							<a href="javascript:go2( 'go2menu', '<?php echo $menu->menutype; ?>' );" ><?php echo $menu->menutype; ?></a>
							</td>
						</tr>
						<tr>
								<td width="90px" valign="top"><?php echo _TYPE?></td>
								<td><?php echo $menu->type; ?></td>
						</tr>
						<tr>
							<td width="90px" valign="top"><?php echo _MENU_ITEM_NAME?></td>
							<td>
								<strong>
								<a href="javascript:go2( 'go2menuitem', '<?php echo $menu->menutype; ?>', '<?php echo $menu->id; ?>' );"><?php echo $menu->name; ?></a>
								</strong>
							</td>
						</tr>
						<tr>
							<td width="90px" valign="top"><?php echo _O_STATE?></td>
							<td>
<?php
			switch($menu->published) {
				case - 2:
					echo '<font color="red">'._MENU_EXPIRED.'</font>';
					break;
				case 0:
					echo _UNPUBLISHED;
					break;
				case 1:
				default:
					echo '<font color="green">'._PUBLISHED.'</font>';
					break;
			}
?>
							</td>
						</tr>
<?php
		}
?>
				<input type="hidden" name="menu" value="" />
				<input type="hidden" name="menuid" value="" />
<?php
	}

	function checkedOut(&$row,$overlib = 1) {
		$mainframe = &mosMainFrame::getInstance();
		$cur_file_icons_path = $mainframe->getCfg('live_site').'/'.ADMINISTRATOR_DIRECTORY.'/templates/'.$mainframe->getTemplate().'/images/ico';
		$hover = '';
		if($overlib) {
			$date = mosFormatDate($row->checked_out_time,'%A, %d %B %Y');
			$time = mosFormatDate($row->checked_out_time,'%H:%M');
			$editor = addslashes(htmlspecialchars(html_entity_decode($row->editor,ENT_QUOTES)));
			$checked_out_text = '<table>';
			$checked_out_text = '<tr><td>'.$editor.'</td></tr>';
			$checked_out_text .= '<tr><td>'.$date.'</td></tr>';
			$checked_out_text .= '<tr><td>'.$time.'</td></tr>';
			$checked_out_text .= '</table>';
			$hover = 'onMouseOver="return overlib(\''.$checked_out_text.'\', CAPTION, \''._CHECKED_OUT.'\', BELOW, RIGHT);" onMouseOut="return nd();"';
		}
		$checked = '<img src="'.$cur_file_icons_path.'/checked_out.png" '.$hover.'/>';
		return $checked;
	}

	/* подключение библиотеки всплывающих подсказок */
	function loadOverlib($ret = false) {
		$mainframe = &MosMainFrame::getInstance();
		if(!$mainframe->get('loadOverlib') &&!$ret ) {
			$mainframe->addJS(Jconfig::getInstance()->config_live_site.'/includes/js/overlib_full.js');
			// установка флага о загруженной библиотеке всплывающих подсказок
			$mainframe->set('loadOverlib',true);
		}
		if(!$mainframe->get('loadOverlib') && $ret==true){?>
			<script language="javascript" type="text/javascript" src="<?php echo Jconfig::getInstance()->config_live_site;?>/includes/js/overlib_full.js"></script>
<?php
		}
		

	}

	/*
	* Подключение JS файлов Календаря
	*/
	function loadCalendar() {
		if(!defined('_CALLENDAR_LOADED')) {
			$config = &Jconfig::getInstance();
			define('_CALLENDAR_LOADED',1);
			$mainframe = MosMainFrame::getInstance();
			$mainframe->addCSS($config->config_live_site.'/includes/js/calendar/calendar-mos.css');
			$mainframe->addJS($config->config_live_site.'/includes/js/calendar/calendar_mini.js');
			$_lang_file = $config->config_absolute_path.'/includes/js/calendar/lang/calendar-'._LANGUAGE.'.js';
			$_lang_file = (is_file($_lang_file)) ? $config->config_live_site.'/includes/js/calendar/lang/calendar-'._LANGUAGE.'.js' : $config->config_live_site.'/includes/js/calendar/lang/calendar-ru.js';
			$mainframe->addJS($_lang_file);
		}
	}
	/* подключение mootools*/
	function loadMootools($ret = false) {
		if(!defined('_MOO_LOADED')) {
			define('_MOO_LOADED',1);
			$config = &Jconfig::getInstance();
			MosMainFrame::getInstance()->addJS($config->config_live_site.'/includes/js/mootools/mootools.js');
		}
		if($ret==true)?>
			<script language="javascript" type="text/javascript" src="<?php echo $config->config_live_site;?>/includes/js/mootools/mootools.js"></script>
<?php
	}
	/* подключение prettyTable*/
	function loadPrettyTable() {
		if(!defined('_PRT_LOADED')) {
			define('_PRT_LOADED',1);
			MosMainFrame::getInstance()->addJS(Jconfig::getInstance()->config_live_site.'/includes/js/jsfunction/jrow.js');
		}
	}
	/* подключение Fullajax*/
	function loadFullajax($ret = false) {
		if(!defined('_FAX_LOADED')) {
			define('_FAX_LOADED',1);
			if($ret){?>
				<script language="javascript" type="text/javascript" src="<?php echo Jconfig::getInstance()->config_live_site;?>/includes/js/fullajax/fullajax.js"></script>
<?php
			}else{
				MosMainFrame::getInstance()->addJS(Jconfig::getInstance()->config_live_site.'/includes/js/fullajax/fullajax.js');
			}
		}
	}


	/* подключение Jquery*/
	function loadJquery($ret = false,$all = false) {
		if(!defined('_JQUERY_LOADED')) {
			define('_JQUERY_LOADED',1);
			if($ret){
				return '<script language="javascript" type="text/javascript" src="'.Jconfig::getInstance()->config_live_site.'/includes/js/jquery/jquery.js"></script>';
			}else{
				MosMainFrame::getInstance()->addJS(Jconfig::getInstance()->config_live_site.'/includes/js/jquery/jquery.js');
				return true;
			}
		}
	}
	/* подключение расширений Jquery*/
	function loadJqueryPlugins($name,$ret = false, $css = false, $footer = '') {
		$mainframe = &MosMainFrame::getInstance();
		$config = &Jconfig::getInstance();

		$name = trim($name);

		// если само ядро Jquery не загружено - сначала грузим его
		if(!defined('_JQUERY_LOADED')) {
			mosCommonHTML::loadJquery();
		}
		// формируем константу-флаг для исключения повтороной загрузки
		$const = '_JQUERY_PL_'.strtoupper($name).'_LOADED';
		if(!defined($const)) {
			define($const,1);
			if($ret){
			?><script language="javascript" type="text/javascript" src="<?php echo Jconfig::getInstance()->config_live_site;?>/includes/js/jquery/plugins/<?php echo $name; ?>.js"></script>
			<script language="JavaScript" type="text/javascript">_js_defines.push('<?php echo $name; ?>');</script>
<?php
		if($css){
			?><link type="text/css" rel="stylesheet" href="<?php echo $config->config_live_site;?>/includes/js/jquery/plugins/<?php echo $name; ?>.css" />
<?php
			}?>
			<?php }else{
				$mainframe->addJS($config->config_live_site.'/includes/js/jquery/plugins/'.$name.'.js', $footer);
				$mainframe->addCustomHeadTag('<script language="JavaScript" type="text/javascript">_js_defines.push("'.$name.'");</script>');
				if($css){
					$mainframe->addCSS($config->config_live_site.'/includes/js/jquery/plugins/'.$name.'.css');
				}
			}
		}
		return true;
	}
	/* подключение файла Jquery UI*/
	function loadJqueryUI($ret = false) {
		if(!defined('_JQUERY_UI_LOADED')) {
			define('_JQUERY_UI_LOADED',1);
			if($ret){?>
				<script language="javascript" type="text/javascript" src="<?php echo Jconfig::getInstance()->config_live_site;?>/includes/js/jquery/ui.js"></script>
			<?php }else{
				MosMainFrame::getInstance()->addJS(Jconfig::getInstance()->config_live_site.'/includes/js/jquery/ui.js');
			}
		}
		return true;
	}

	/* подключение codepress*/
	function loadCodepress() {
		if(!defined('_CODEPRESS_LOADED')) {
			define('_CODEPRESS_LOADED',1);
			$config = &Jconfig::getInstance();
			MosMainFrame::getInstance()->addJS($config->config_live_site.'/includes/js/codepress/codepress.js');
?><script language="JavaScript">
CodePress.run = function() {
	CodePress.path = '<?php echo $config->config_live_site; ?>/includes/js/codepress/';
	t = document.getElementsByTagName('textarea');
	for(var i=0,n=t.length;i<n;i++) {
		if(t[i].className.match('codepress')) {
			id = t[i].id;
			t[i].id = id+'_cp';
			eval(id+' = new CodePress(t[i])');
			t[i].parentNode.insertBefore(eval(id), t[i]);
		}
	}
}
if(window.attachEvent){
	window.attachEvent('onload',CodePress.run);
}else{
	window.addEventListener('DOMContentLoaded',CodePress.run,false);
}</script>
<?php
		}
	}

	/* подключение dTree*/
	function loadDtree() {
		if(!defined('_DTR_LOADED')) {
			define('_DTR_LOADED',1);
			$mainframe = &MosMainFrame::getInstance();
			$config = &Jconfig::getInstance();
			$mainframe->addCSS($config->config_live_site.'/includes/js/dtree/dtree.css');
			$mainframe->addJS($config->config_live_site.'/includes/js/dtree/dtree.js');
		}
	}

	/* подключение jwondow*/
	function loadJWin() {
		if(!defined('_JWIN_LOADED')) {
			define('_JWIN_LOADED',1);
			$mainframe = &MosMainFrame::getInstance();
			$config = &Jconfig::getInstance();
			$mainframe->addCSS($config->config_live_site.'/includes/js/jwindow/jwindow.css');
			$mainframe->addJS($config->config_live_site.'/includes/js/jwindow/jwindow.js');
		}
	}

	function AccessProcessing(&$row,$i,$ajax=null) {
		$option = strval(mosGetParam($_REQUEST,'option',''));
		if(!$row->access) {
			$color_access = 'style="color: green;"';
			$task_access = 'accessregistered';
		} elseif($row->access == 1) {
			$color_access = 'style="color: red;"';
			$task_access = 'accessspecial';
		} else {
			$color_access = 'style="color: black;"';
			$task_access = 'accesspublic';
		}
		if(!$ajax){
			$href = '<a href="javascript: void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$task_access.'\')" '.$color_access.'>'.$row->groupname.'</a>';
		}else{
			$href = '<a href="#" onclick="ch_access('.$row->id.',\''.$task_access.'\',\''.$option.'\');" '.$color_access.'>'.$row->groupname.'</a>';
		}
		return $href;
	}

	/*
	* Проверка блокировки объекта
	*/
	function CheckedOutProcessing(&$row,$i) {
		global $my;
		if($row->checked_out) {
			$checked = mosCommonHTML::checkedOut($row);
		} else {
			$checked = mosHTML::idBox($i,$row->id,($row->checked_out && $row->checked_out !=$my->id));
		}
		return $checked;
	}

	function PublishedProcessing(&$row,$i) {
		$mainframe = &mosMainFrame::getInstance();
		$cur_file_icons_path = $mainframe->getCfg('live_site').'/'.ADMINISTRATOR_DIRECTORY.'/templates/'.$mainframe->getTemplate().'/images/ico';
		$img = $row->published?'publish_g.png':'publish_x.png';
		$task = $row->published?'unpublish':'publish';
		$alt = $row->published?_PUBLISHED:_UNPUBLISHED;
		$action = $row->published?_UNPUBLISH_ON_FRONTPAGE:_PUBLISH_ON_FRONTPAGE;
		$href = '<a href="javascript: void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.$action.'"><img src="'.$cur_file_icons_path.'/'.$img.'" border="0" alt="'.$alt.'" /></a>';
		return $href;
	}

	/*
	* Special handling for newfeed encoding and possible conflicts with page encoding and PHP version
	* Added 1.0.8
	* Static Function
	*/
	function newsfeedEncoding($rssDoc,$text,$utf8enc=null) {
		if(!defined('_JOS_FEED_ENCODING')) {
			// determine encoding of feed
			$feed = $rssDoc->toNormalizedString(true);
			$feed = strtolower(substr($feed,0,150));
			$feedEncoding = strpos($feed,'encoding=&quot;utf-8&quot;');

			if($feedEncoding !== false) {
				// utf-8 feed
				$utf8 = 1;
			} else {
				// non utf-8 page
				$utf8 = 0;
			}

			define('_JOS_FEED_ENCODING',$utf8);
		}

		if(!defined('_JOS_SITE_ENCODING')) {
			// determine encoding of page
			if(strpos(strtolower(_ISO),'utf') !== false) {
				// utf-8 page
				$utf8 = 1;
			} else {
				// non utf-8 page
				$utf8 = 0;
			}

			define('_JOS_SITE_ENCODING',$utf8);

		}
		if(phpversion() >= 5) {
			// handling for PHP 5
			if(_JOS_FEED_ENCODING) {
				// handling for utf-8 feed
				if(_JOS_SITE_ENCODING) {
					// utf-8 page
					$encoding = 'html_entity_decode';
				} else {
					// non utf-8 page
					$encoding = 'utf8_decode';
				}
			} else {
				// handling for non utf-8 feed
				if(_JOS_SITE_ENCODING) {
					// utf-8 page
					$encoding = '';
				} else {
					// non utf-8 page
					$encoding = 'utf8_decode';
				}
			}
		} else {
			// handling for PHP 4
			if(_JOS_FEED_ENCODING) {
				// handling for utf-8 feed
				if(_JOS_SITE_ENCODING) {
					// utf-8 page
					$encoding = '';
				} else {
					// non utf-8 page
					$encoding = 'utf8_decode';
				}
			} else {
				// handling for non utf-8 feed
				if(_JOS_SITE_ENCODING) {
					// utf-8 page
					$encoding = 'utf8_encode';
				} else {
					// non utf-8 page
					$encoding = 'html_entity_decode';
				}
			}
		}

		if($encoding && !$utf8enc) {
			$text = $encoding($text);
		}elseif($utf8enc){
			$text = joostina_api::convert($text);
		}

		$text = str_replace('&apos;',"'",$text);

		return $text;
	}
	
	function get_element($file){
		$mainframe = &mosMainFrame::getInstance();
		
		$file_templ = 'templates/'.$mainframe->getTemplate().'/images/elements/'.$file;
		$file_system = 'M_images/'.$file;
		
		$return = $file_templ;
		if(!is_file(Jconfig::getInstance()->config_absolute_path.'/'.$file_templ)){
			$return = $file_system;	
		}
		
		return $return;
	}
}

/**
* Sorts an Array of objects
*/
function SortArrayObjects_cmp(&$a,&$b) {
	global $csort_cmp;
	if($a->$csort_cmp['key'] > $b->$csort_cmp['key']) {
		return $csort_cmp['direction'];
	}
	if($a->$csort_cmp['key'] < $b->$csort_cmp['key']) {
		return - 1* $csort_cmp['direction'];
	}
	return 0;
}

/**
* Sorts an Array of objects
* sort_direction [1 = Ascending] [-1 = Descending]
*/
function SortArrayObjects(&$a,$k,$sort_direction = 1) {
	global $csort_cmp;
	$csort_cmp = array('key' => $k,'direction' => $sort_direction);
	usort($a,'SortArrayObjects_cmp');
	unset($csort_cmp);
}

/**
* Sends mail to admin
*/
function mosSendAdminMail($adminName,$adminEmail,$email,$type,$title='',$author='' ) {
	$subject = _MAIL_SUB." '$type'";
	$message = _MAIL_MSG;
	eval("\$message = \"$message\";");
	mosMail(Jconfig::getInstance()->config_mailfrom,Jconfig::getInstance()->config_fromname,$adminEmail,$subject,$message);
}

/*
* Includes pathway file
*/
function mosPathWay() {
	require_once (Jconfig::getInstance()->config_absolute_path.'/includes/pathway.php');
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
* Prepares results from search for display
* @param string The source string
* @param int Number of chars to trim
* @param string The searchword to select around
* @return string
*/
function mosPrepareSearchContent($text,$length = 200,$searchword) {
	// strips tags won't remove the actual jscript
	$text = preg_replace("'<script[^>]*>.*?</script>'si","",$text);
	$text = preg_replace('/{.+?}/','',$text);
	//$text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2', $text );
	// replace line breaking tags with whitespace
	$text = preg_replace("'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si",' ',$text);
	$text = mosSmartSubstr(strip_tags($text),$length,$searchword);
	return $text;
}

/**
* returns substring of characters around a searchword
* @param string The source string
* @param int Number of chars to return
* @param string The searchword to select around
* @return string
*/
function mosSmartSubstr($text,$length = 200,$searchword) {
	$wordpos = Jstring::strpos(Jstring::strtolower($text),Jstring::strtolower($searchword));
	$halfside = intval($wordpos - $length / 2 - Jstring::strlen($searchword));
	if($wordpos && $halfside > 0) {
		return '...'.Jstring::substr($text,$halfside,$length).'...';
	} else {
		return Jstring::substr($text,0,$length);
	}
}

/**
* Chmods files and directories recursively to given permissions. Available from 1.0.0 up.
* @param path The starting file or directory (no trailing slash)
* @param filemode Integer value to chmod files. NULL = dont chmod files.
* @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
* @return TRUE=all succeeded FALSE=one or more chmods failed
*/
function mosChmodRecursive($path,$filemode = null,$dirmode = null) {
	$ret = true;
	if(is_dir($path)) {
		$dh = opendir($path);
		while($file = readdir($dh)) {
			if($file != '.' && $file != '..') {
				$fullpath = $path.'/'.$file;
				if(is_dir($fullpath)) {
					if(!mosChmodRecursive($fullpath,$filemode,$dirmode)) $ret = false;
				} else {
					if(isset($filemode))
						if(!@chmod($fullpath,$filemode)) $ret = false;
				} // if
			} // if
		} // while
		closedir($dh);
		if(isset($dirmode))
			if(!@chmod($path,$dirmode)) $ret = false;
	} else {
		if(isset($filemode)) $ret = @chmod($path,$filemode);
	} // if
	return $ret;
} // mosChmodRecursive

/**
* Chmods files and directories recursively to mos global permissions. Available from 1.0.0 up.
* @param path The starting file or directory (no trailing slash)
* @param filemode Integer value to chmod files. NULL = dont chmod files.
* @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
* @return TRUE=all succeeded FALSE=one or more chmods failed
*/
function mosChmod($path) {
	$config = &Jconfig::getInstance();

	$config->config_fileperms = trim($config->config_fileperms);
	$config->config_dirperms = trim($config->config_fileperms);
	$filemode = null;
	if($config->config_fileperms != ''){
		$filemode = octdec($config->config_fileperms);
	}
	$dirmode = null;
	if($config->config_dirperms != ''){
		$dirmode = octdec($config->config_dirperms);
	}
	if(isset($filemode) || isset($dirmode)){
		return mosChmodRecursive($path,$filemode,$dirmode);
	}
	return true;
} // mosChmod

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
* Function to handle an array of integers
* Added 1.0.11
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
* Utility class for helping with patTemplate
*/
class patHTML {
	/**
	* Converts a named array to an array or named rows suitable to option lists
	* @param array The source array[key] = value
	* @param mixed A value or array of selected values
	* @param string The name for the value field
	* @param string The name for selected attribute (use 'checked' for radio of box lists)
	*/
	function selectArray(&$source,$selected = null,$valueName = 'value',$selectedAttr ='selected') {
		if(!is_array($selected)) {
			$selected = array($selected);
		}
		foreach($source as $i => $row) {
			if(is_object($row)) {
				$source[$i]->selected = in_array($row->$valueName,$selected)?$selectedAttr.'="true"':'';
			} else {
				$source[$i]['selected'] = in_array($row[$valueName],$selected)?$selectedAttr.'="true"':'';
			}
		}
	}
	/**
	* Converts a named array to an array or named rows suitable to checkbox or radio lists
	* @param array The source array[key] = value
	* @param mixed A value or array of selected values
	* @param string The name for the value field
	*/
	function checkArray(&$source,$selected = null,$valueName = 'value') {
		patHTML::selectArray($source,$selected,$valueName,'checked');
	}
	/**
	* @param mixed The value for the option
	* @param string The text for the option
	* @param string The name of the value parameter (default is value)
	* @param string The name of the text parameter (default is text)
	*/
	function makeOption($value,$text,$valueName = 'value',$textName = 'text') {
		return array($valueName => $value,$textName => $text);
	}
	/**
	* Writes a radio pair
	* @param object Template object
	* @param string The template name
	* @param string The field name
	* @param int The value of the field
	* @param array Array of options
	* @param string Optional template variable name
	*/
	function radioSet(&$tmpl,$template,$name,$value,$a,$varname = null) {
		patHTML::checkArray($a,$value);
		$tmpl->addVar('radio-set','name',$name);
		$tmpl->addRows('radio-set',$a);
		$tmpl->parseIntoVar('radio-set',$template,is_null($varname)?$name:$varname);
	}
	/**
	* Writes a radio pair
	* @param object Template object
	* @param string The template name
	* @param string The field name
	* @param int The value of the field
	* @param string Optional template variable name
	*/
	function yesNoRadio(&$tmpl,$template,$name,$value,$varname = null) {
		$a = array(
			patHTML::makeOption(0,'Нет'),
			patHTML::makeOption(1,'Да')
		);
		patHTML::radioSet($tmpl,$template,$name,$value,$a,$varname);
	}
}

/**
* Provides a secure hash based on a seed
* @param string Seed string
* @return string
*/
function mosHash($seed) {
	return md5($GLOBALS['mosConfig_secret'].md5($seed));
}

/**
* Format a backtrace error
* @since 1.0.5
*/
function mosBackTrace() {
	if(function_exists('debug_backtrace')) {
		echo '<div align="left">';
		foreach(debug_backtrace() as $back) {
			if(@$back['file']) {
				echo '<br />'.str_replace($GLOBALS['mosConfig_absolute_path'],'',$back['file']).':'.$back['line'];
			}
		}
		echo '</div>';
	}
}

function josSpoofCheck( $header=NULL, $alt=NULL , $method = 'post'){
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
function josSpoofValue($alt=NULL)
{
	global $mainframe, $my;

	if ($alt) {
		if ( $alt == 1 ) {
			$random	= date( 'Ymd' );
		} else {
			$random	= $alt . date( 'Ymd' );
		}
	} else {
		$random		= date( 'dmY' );
	}
	// the prefix ensures that the hash is non-numeric
	// otherwise it will be intercepted by globals.php
	$validate 	= 'j' . mosHash( $mainframe->getCfg( 'db' ) . $random . $my->id );

	return $validate;
}
/**
* A simple helper function to salt and hash a clear-text password.
*
* @since	1.0.13
* @param	string	$password	A plain-text password
* @return	string	An md5 hashed password with salt
*/
function josHashPassword($password) {
	// Salt and hash the password
	$salt = mosMakePassword(16);
	$crypt = md5($password.$salt);
	$hash = $crypt.':'.$salt;
	return $hash;
}


/**
* Page generation time
* @package Joostina
*/
class mosProfiler {
	/**
	@var int Start time stamp*/
	var $start = 0;
	/**
	@var string A prefix for mark messages*/
	var $prefix = '';
	/**
	* Constructor
	* @param string A prefix for mark messages
	*/
	function mosProfiler($prefix = '') {
		$this->start = $this->getmicrotime();
		$this->prefix = $prefix;
	}
	/**
	* @return string A format message of the elapsed time
	*/
	function mark($label) {
		return sprintf("\n<div class=\"profiler\">$this->prefix %.3f $label</div>",$this->getmicrotime() - $this->start);
	}
	/**
	* @return float The current time in milliseconds
	*/
	function getmicrotime() {
		list($usec,$sec) = explode(' ',microtime());
		return ((float)$usec + (float)$sec);
	}
}


/**
* @package Joostina
* @abstract
*/
class mosAbstractLog {
	/**
	@var array*/
	var $_log = null;
	/**
	* Constructor
	*/
	function mosAbstractLog() {
		$this->__constructor();
	}
	/**
	* Generic constructor
	*/
	function __constructor() {
		$this->_log = array();
	}
	/**
	* @param string Log message
	* @param boolean True to append to last message
	*/
	function log($text,$append = false) {
		$n = count($this->_log);
		if($append && $n > 0) {
			$this->_log[count($this->_log) - 1] .= $text;
		} else {
			$this->_log[] = $text;
		}
	}
	/**
	* @param string The glue for each log item
	* @return string Returns the log
	*/
	function getLog($glue = '<br/>',$truncate = 9000,$htmlSafe = false) {
		$logs = array();
		foreach($this->_log as $log) {
			if($htmlSafe) {
				$log = htmlspecialchars($log);
			}
			$logs[] = substr($log,0,$truncate);
		}
		return implode($glue,$logs);
	}
}

class errorCase{
	var $type = null;
	var $message = null;

	function errorCase($type = 1){
		$this->type = $type;
		self::_display_error();
	}

	function _display_error(){
		switch ($this->type){
			case 1:
			default:
				$this->message = _MESSAGE_ERROR_404;
			break;

			case 2:
				$this->message = _MESSAGE_ERROR_403;
			break;
		}
		echo $this->message;
	}
}

/**
* Task routing class
* @package Joostina
* @abstract
*/
class mosAbstractTasker {
	/**
	@var array An array of the class methods to call for a task*/
	var $_taskMap = null;
	/**
	@var string The name of the current task*/
	var $_task = null;
	/**
	@var array An array of the class methods*/
	var $_methods = null;
	/**
	@var string A url to redirect to*/
	var $_redirect = null;
	/**
	@var string A message about the operation of the task*/
	var $_message = null;
	/**
	@var string The ACO Section*/
	var $_acoSection = null;
	/**
	@var string The ACO Section value*/
	var $_acoSectionValue = null;
	/**
	* Constructor
	* @param string Set the default task
	*/
	function mosAbstractTasker($default = '') {
		$this->_taskMap = array();
		$this->_methods = array();
		foreach(get_class_methods(get_class($this)) as $method) {
			if(substr($method,0,1) != '_') {
				$this->_methods[] = strtolower($method);
				// auto register public methods as tasks
				$this->_taskMap[strtolower($method)] = $method;
			}
		}
		$this->_redirect = '';
		$this->_message = '';
		if($default) {
			$this->registerDefaultTask($default);
		}
	}
	/**
	* Sets the access control levels
	* @param string The ACO section (eg, the component)
	* @param string The ACO section value (if using a constant value)
	*/
	function setAccessControl($section,$value = null) {
		$this->_acoSection = $section;
		$this->_acoSectionValue = $value;
	}
	/**
	* Access control check
	*/
	function accessCheck($task) {
		global $my;

		$acl = &gacl::getInstance();

		// only check if the derived class has set these values
		if($this->_acoSection) {
			// ensure user has access to this function
			if($this->_acoSectionValue) {
				// use a 'constant' task for this task handler
				$task = $this->_acoSectionValue;
			}
			return $acl->acl_check($this->_acoSection,$task,'users',$my->usertype);
		} else {
			return true;
		}
	}

	/**
	* Set a URL to redirect the browser to
	* @param string A URL
	*/
	function setRedirect($url,$msg = null) {
		$this->_redirect = $url;
		if($msg !== null) {
			$this->_message = $msg;
		}
	}
	/**
	* Redirects the browser
	*/
	function redirect() {
		if($this->_redirect) {
			mosRedirect($this->_redirect,$this->_message);
		}
	}
	/**
	* Register (map) a task to a method in the class
	* @param string The task
	* @param string The name of the method in the derived class to perform for this task
	*/
	function registerTask($task,$method) {
		if(in_array(strtolower($method),$this->_methods)) {
			$this->_taskMap[strtolower($task)] = $method;
		} else {
			$this->methodNotFound($method);
		}
	}
	/**
	* Register the default task to perfrom if a mapping is not found
	* @param string The name of the method in the derived class to perform if the task is not found
	*/
	function registerDefaultTask($method) {
		$this->registerTask('__default',$method);
	}
	/**
	* Perform a task by triggering a method in the derived class
	* @param string The task to perform
	* @return mixed The value returned by the function
	*/
	function performTask($task) {
		$this->_task = $task;

		$task = strtolower($task);
		if(isset($this->_taskMap[$task])) {
			$doTask = $this->_taskMap[$task];
		} else
			if(isset($this->_taskMap['__default'])) {
				$doTask = $this->_taskMap['__default'];
			} else {
				return $this->taskNotFound($this->_task);
			}

			if($this->accessCheck($doTask)) {
				return call_user_func(array(&$this,$doTask));
			} else {
				return $this->notAllowed($task);
			}
	}
	/**
	* Get the last task that was to be performed
	* @return string The task that was or is being performed
	*/
	function getTask() {
		return $this->_task;
	}
	/**
	* Basic method if the task is not found
	* @param string The task
	* @return null
	*/
	function taskNotFound($task) {
		echo 'Задача '.$task.' не найдена';
		return null;
	}
	/**
	* Basic method if the registered method is not found
	* @param string The name of the method in the derived class
	* @return null
	*/
	function methodNotFound($name) {
		echo 'Метод '.$name.' не обнаружен';
		return null;
	}
	/**
	* Basic method if access is not permitted to the task
	* @param string The name of the method in the derived class
	* @return null
	*/
	function notAllowed() {
		echo _NOT_AUTH;
		return null;
	}
}

class myFunctions{

	var $func = null;
	var $params = null;
	var $obj = null;

	function myFunctions($func, $params){
		$this->func = $func;
		$this->params = $params;
		$this->bind();
	}

	function bind(){
		
		$obj = new stdClass();
		foreach($this->params as $key=>$val){
			$obj->$key = $val;
		}
		$this->obj = $obj;
	}

	function check_user_function(){
		return false; // опционально, до начала использования библиотеки в системе
		mosMainFrame::addLib('myLib');
		$methods = get_class_methods('myLib');
		if(in_array($this->func, $methods)){
			return true;
		}
		return false;
	}

	function start_user_function(){
		return call_user_func(array('myLib', $this->func), $this->obj);	
	}
	
}

/**
* Объединение расширений системы в одно пространство имён
*
*/
class joostina_api {
	/**
	* Конвертирование текста из юникода в кириллицу
	* Чаще всего используется для Аякс функций.
	* В качестве параметра принимает строковое значение в кодировке UTF-8, возвращает строковое значение в кодировке windows-1251
	* $type - параметр конвертации, по умолчанию конвертируется из utf-8.
	**/
	// это больше НЕ требуется
	function convert($text,$type = null) {
		return $text;
	}

	/**
	* Оптимизация таблиц базы данных
	* Основано на мамботе OptimizeTables - smart (C) 2006, Joomlaportal.ru. All rights reserved
	*/
	function optimizetables() {
		if(mt_rand(1,50)==1) {
			register_shutdown_function('_optimizetables');
		}
	}
	/**
	* Редирект с не WWW адреса
	* Основано на мамботе seo_bot_redir - Alecfyz (C) Gorsk.net Studio Dec 2006
	*/
	function check_host() {
		register_shutdown_function('_check_host');
	}
	/**
	* Очистка кэша
	* Основано на мамботе botClearCache - (C) 2008 Denis Ryabov ( http://physicist.phpnet.us/ )
	*/
	function clear_cache(){
		if(mt_rand(1,100)==1) {
			register_shutdown_function('_clear_cache');
		}
	}
}


function _check_host() {
	$config = &Jconfig::getInstance();

	if(!$config->config_www_redir) {
		return;
	}
	$uri = mosgetparam($_SERVER,'REQUEST_URI','');
	if (strpos($uri,'/') === 0) {
		$uri = substr($uri,1);
	}
	$realhost = mosgetparam($_SERVER,'SERVER_NAME',$config->config_live_site);

	preg_match("/^(http:\/\/)?([^\/]+)/i", $config->config_live_site, $matches);
	$confhost = $matches[2];
	if (preg_match ("'^www.'si",$confhost) && !preg_match ("'^www.'si",$realhost)){
		mosredirect(sefRelToAbs($uri));
	}
	if (!preg_match ("'^www.'si",$confhost) && preg_match ("'^www.'si",$realhost)){
		mosredirect(sefRelToAbs($uri));
	}
}

// очистка каталога кэша
function _clear_cache(){
	flush();
	$config = &Jconfig::getInstance();

	$cacheDir = $config->config_cachepath.'/';
	$refreshTime=time() - $config->config_cachetime;
	if(!($dh=opendir($cacheDir))){
		return false;
	}
	while($file=readdir($dh)){
		if(strpos($file,'cache_',0)===0){
			$file=$cacheDir.$file;
			if(is_file($file)&&(@filemtime($file)<$refreshTime)){
				@unlink($file);
			}
		}
	}
}

function _optimizetables() {
	$database = &database::getInstance();
	$config = &Jconfig::getInstance();

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
	$database->setQuery('SHOW TABLES FROM `'.$config->config_db.'`');
	$tables = $database->loadResultArray();
	foreach($tables as $table) {
		$database->setQuery('OPTIMIZE TABLE `$table`;');
		$database->query();
	}
	return;
}

//
function _xdump( $var, $text='<pre>' ){
	echo $text;
	print_r( $var );
	echo "\n";
}

/**
 @global mosPlugin $_MAMBOTS*/
$_MAMBOTS = new mosMambotHandler();
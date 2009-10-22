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

/**
* Displays the capture output of the main element
*/
function mosMainBody() {
	$page = &PageModel::getInstance();
	echo $page->MainBody();
}
/**
* Utility functions and classes
* not used?
*/
function mosLoadComponent($name) {
	global $my,$task,$Itemid,$id,$option,$gid;

	$mainframe = &mosMainFrame::getInstance();
	$database = &$mainframe->_db;
	include (JPATH_BASE.DS."components/com_$name/$name.php");
}
/**
* Cache some modules information
* @return array
*/

//Добавлено в класс mosModules
function &initModules() {
	
}
/**
* @param string the template position
*/
function mosCountModules($position = 'left') {
	$modules =& mosModule::getInstance();
	return $modules->mosCountModules($position);
}
/**
* @param string The position
* @param int The style.  0=normal, 1=horiz, -1=no wrapper
*/
//Скопировано в класс
function mosLoadModules($position = 'left',$style = 0,$noindex = 0) {
	$modules =& mosModule::getInstance();
	return $modules->mosLoadModules($position,$style,$noindex);
}

/**
* @param string The position
* @param int The style.  0=normal, 1=horiz, -1=no wrapper
*/
function mosLoadModule($name = '', $title = '', $style = 0, $noindex = 0, $inc_params = null) {
	$modules =& mosModule::getInstance();
	return $modules->mosLoadModule($name,$title,$style,$noindex,$inc_params);
}

/**
* Шапка страницы
*/
function mosShowHead($params=array('js'=>1,'css'=>1)) {
	$page = &PageModel::getInstance();
	// загружаем верхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
	$page->ShowHead($params);
}

function mosShowFooter($params=array('fromheader'=>1,'js'=>1)) {
	$page = &PageModel::getInstance();
	// загружаем верхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
	$page->ShowFooter($params);
}

// установка мета-тэгов для поисковика
function set_robot_metatag($robots) {

}

// выводк лент RSS
function syndicate_header(){}


/**
* @package Joostina
*/

class PageModel{

	var $_mainframe = null;
	var $_view = null;

	function PageModel($mainframe){
		$this->_mainframe = $mainframe;
	}

	function getInstance(){
		static $page_model;
		if(!is_object($page_model) ){
			$mainframe = &mosMainFrame::getInstance();
			unset($mainframe->menu,$mainframe->_session);
			$page_model = new PageModel($mainframe);
		}

		return $page_model;
	}

	function _body(){
		$this->MainBody();
	}

	function _header($params){
		$this->ShowHead($params);
	}

	function _footer($params){
		$this->ShowFooter($params);
	}

	function MainBody() {
		$mainframe = $this->_mainframe;

		$popMessages = false;

		// Browser Check
		$browserCheck = 0;
		if(isset($_SERVER['HTTP_USER_AGENT']) && isset($_SERVER['HTTP_REFERER']) &&strpos($_SERVER['HTTP_REFERER'],JPATH_SITE) !== false) {
			$browserCheck = 1;
		}

		// Session Check
		$sessionCheck = 0;
		// Session Cookie `name`
		$sessionCookieName = $mainframe->sessionCookieName();
		// Get Session Cookie `value`
		$sessioncookie = mosGetParam($_COOKIE,$sessionCookieName,null);
		if((strlen($sessioncookie) == 32 || $sessioncookie == '-')) {
			$sessionCheck = 1;
		}

		$mosmsg = $mainframe->get_mosmsg();
		if($mosmsg && !$popMessages && $browserCheck && $sessionCheck) {
			echo '<div class="message info">'.$mosmsg.'</div>';
		}

		$_body = $GLOBALS['_MOS_OPTION']['buffer'];

		// активация мамботов группы mainbody
		if($mainframe->getCfg('mmb_mainbody_off') == 0) {
			global $_MAMBOTS;
			$_MAMBOTS->loadBotGroup('mainbody');
			$_MAMBOTS->trigger('onMainbody',array(&$_body));
		}

		echo $_body;

		unset($GLOBALS['_MOS_OPTION']['buffer']);

		// mosmsg outputed in JS Popup
		if($mosmsg && $popMessages && $browserCheck && $sessionCheck) {
			echo "\n<script language=\"javascript\">alert('".addslashes($mosmsg)."');</script>";
		}
	}

	function ShowHead($params=array('js'=>1,'css'=>1)) {
		global $option,$my,$task,$id;

		$mainframe = $this->_mainframe;

		$description = '';
		$keywords = '';

		$_meta_keys_index = -1;
		$_meta_desc_index = -1;

		$n = count($mainframe->_head['meta']);
		for($i = 0; $i < $n; $i++) {
			if($mainframe->_head['meta'][$i][0] == 'keywords') {
				$_meta_keys_index = $i;
				$keywords = $mainframe->_head['meta'][$i][1];
			} else{
				if($mainframe->_head['meta'][$i][0] == 'description') {
					$_meta_desc_index = $i;
					$description = $mainframe->_head['meta'][$i][1];
				}
			}
		}

		if(!$description) {
			$mainframe->appendMetaTag('description',$mainframe->getCfg('MetaDesc'));
		}

		if(!$keywords) {
			$mainframe->appendMetaTag('keywords',$mainframe->getCfg('MetaKeys'));
		}

		// отключение тега Generator
		if($mainframe->getCfg('generator_off') == 0) {
			$mainframe->addMetaTag('Generator',joomlaVersion::get('CMS').' - '.joomlaVersion::get('COPYRIGHT'));
		}


		if($mainframe->getCfg('index_tag') == 1) {
			$mainframe->addMetaTag('distribution','global');
			$mainframe->addMetaTag('rating','General');
			$mainframe->addMetaTag('document-state','Dynamic');
			$mainframe->addMetaTag('documentType','WebDocument');
			$mainframe->addMetaTag('audience','all');
			$mainframe->addMetaTag('revisit',$mainframe->getCfg('mtage_revisit').' days');
			$mainframe->addMetaTag('revisit-after',$mainframe->getCfg('mtage_revisit').' days');
			$mainframe->addMetaTag('allow-search','yes');
			$mainframe->addMetaTag('language',$mainframe->getCfg('lang'));
		}

		echo $mainframe->getHead($params);

		// очистка ссылки на главную страницу даже при отключенном sef
		if ( $mainframe->getCfg('mtage_base') == 1) {
			// вычисление ткущего адреса страницы. Код взят из Joomla 1.5.x
			if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
				$https = 's://';
			} else {
				$https = '://';
			}
			if (!empty ($_SERVER['PHP_SELF']) && !empty ($_SERVER['REQUEST_URI'])) {
				$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			} else {
				$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
				if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
					$theURI .= '?' . $_SERVER['QUERY_STRING'];
				}
			}
			$theURI = str_replace(JPATH_SITE.'/','',$theURI);
			echo '<base href="'.sefRelToAbs($theURI).'" />'."\r\n";
		}

		if($my->id || $mainframe->get('joomlaJavascript')) {
			?><script src="<?php echo JPATH_SITE; ?>/includes/js/joomla.javascript.js" type="text/javascript"></script>
			<?php
		}

		// отключение RSS вывода в шапку

		if($mainframe->getCfg('syndicate_off')==0) {
			if($mainframe->getCfg('caching')==1){
				$cache = &mosCache::getCache('header');
				echo $cache->call('syndicate_header');
			}else{
				echo $this->syndicate_header();
			}
			echo "\r\n";
		}

		// favourites icon
		if(!$mainframe->getCfg('disable_favicon')) {
			if(!$mainframe->getCfg('favicon')) {
				$favicon = 'favicon.ico';
			}else{
				$favicon = $mainframe->getCfg('favicon');
			}
			$icon = JPATH_BASE.'/images/'.$favicon;
			if(!file_exists($icon)) {
				$icon = JPATH_SITE.'/images/favicon.ico';
			} else {
				$icon = JPATH_SITE.'/images/'.$favicon;
			}
			echo '<link rel="shortcut icon" href="'.$icon.'" />';
		}
	}


	function ShowFooter($params=array('fromheader'=>1,'js'=>1)) {
		echo $this->_mainframe->getFooter($params);
	}


	// выводк лент RSS
	function syndicate_header(){
		$mainframe = $this->_mainframe;

		$row = new mosComponent();
		$query = "SELECT a.params, a.option FROM #__components AS a WHERE ( a.admin_menu_link = 'option=com_syndicate' OR a.admin_menu_link = 'option=com_syndicate&hidemainmenu=1' ) AND a.option = 'com_syndicate'";
		$mainframe->_db->setQuery($query);
		$mainframe->_db->loadObject($row);

		// get params definitions
		$syndicateParams = new mosParameters($row->params,$mainframe->getPath('com_xml',$row->option),'component');

		//$GLOBALS['syndicateParams'] = $syndicateParams;
		$live_bookmark = $syndicateParams->get('live_bookmark',0);

		// and to allow disabling/enabling of selected feed types
		switch($live_bookmark) {
			case 'RSS0.91':
				if(!$syndicateParams->get('rss091',1)) {
					$live_bookmark = 0;
				}
				break;

			case 'RSS1.0':
				if(!$syndicateParams->get('rss10',1)) {
					$live_bookmark = 0;
				}
				break;

			case 'RSS2.0':
				if(!$syndicateParams->get('rss20',1)) {
					$live_bookmark = 0;
				}
				break;

			case 'ATOM0.3':
				if(!$syndicateParams->get('atom03',1)) {
					$live_bookmark = 0;
				}
				break;
		}

		// support for Live Bookmarks ability for site syndication
		if($live_bookmark) {
			$show = 1;

			$link_file = JPATH_SITE.'/index2.php?option=com_rss&feed='.$live_bookmark.'&no_html=1';

			// xhtml check
			$link_file = ampReplace($link_file);

			// security chcek
			$check = $syndicateParams->def('check',1);
			if($check) {
				// проверяем, не опубликован ли уже модуль с RSS
				$query = "SELECT m.id FROM #__modules AS m WHERE m.module = 'mod_rssfeed' AND m.published = 1 LIMIT 1";
				$mainframe->_db->setQuery($query);
				$check = $mainframe->_db->loadResult();
				if($check>0) {
					$show = 0;
				}
			}
			if($show) {
				?><link rel="alternate" type="application/rss+xml" title="<?php echo $mainframe->getCfg('sitename'); ?>" href="<?php echo $link_file; ?>" /><?php
			}
		}
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
	var $_model = null;
	var $_controller = null;
	var $_view = null;
	var $_mainframe = null;

	/**
	* @param database A database connector object
	*/
	function mosComponent(&$db=null) {
		$this->mosDBTable('#__components','id',$db);
	}

	function _init($option, $mainframe){

		$this->option = $option;
		$this->_mainframe = $mainframe;

		$component = str_replace('com_', '', $this->option);

		$controller = $component.'Controller';
		$view = $component.'View';

		if(class_exists($view)){
			$this->_view = 	new $view($this->_mainframe) ;
		}

	}
}
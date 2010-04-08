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

function mosMainBody() {
	echo PageModel::getInstance()->MainBody();
}

function mosLoadComponent($name) {
	global $my,$task,$Itemid,$id,$option,$gid;

	$mainframe = mosMainFrame::getInstance();
	$database = $mainframe->getDBO();
	include (JPATH_BASE.DS."components/com_$name/$name.php");
}


//Добавлено в класс mosModules
function initModules() {

}

function mosCountModules($position = 'left') {
	return mosModule::getInstance()->mosCountModules($position);
}

function mosLoadModules($position = 'left',$style = 0,$noindex = 0) {
	return mosModule::getInstance()->mosLoadModules($position,$style,$noindex);
}

function mosLoadModule($name = '', $title = '', $style = 0, $noindex = 0, $inc_params = null) {
	return mosModule::getInstance()->mosLoadModule($name,$title,$style,$noindex,$inc_params);
}

function mosShowHead($params=array('js'=>1,'css'=>1)) {
	// загружаем верхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
	PageModel::getInstance()->ShowHead($params);
}

function mosShowFooter($params=array('fromheader'=>1,'js'=>1)) {
	// загружаем верхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
	PageModel::getInstance()->ShowFooter($params);
}

// установка мета-тэгов для поисковика
function set_robot_metatag($robots) {
	mosMainFrame::getInstance()->set_robot_metatag($robots);
}

class PageModel {
	private static $_instance;

	private $_mainframe;
	private $_view;

	private function __clone() {

	}

	function PageModel() {
		$this->_mainframe = mosMainFrame::getInstance();
	}

	public static function getInstance() {

		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function _body() {
		$this->MainBody();
	}

	function _header($params) {
		$this->ShowHead($params);
	}

	function _footer($params) {
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

		$meta = $mainframe->getHeadData('meta');
		$n = count($meta);
		for($i = 0; $i < $n; $i++) {
			if($meta[$i][0] == 'keywords') {
				$_meta_keys_index = $i;
				$keywords = $meta[$i][1];
			} else {
				if($meta[$i][0] == 'description') {
					$_meta_desc_index = $i;
					$description = $meta[$i][1];
				}
			}
		}

		$description ? null : $mainframe->appendMetaTag('description',$mainframe->getCfg('MetaDesc'));
		$keywords  ? null : $mainframe->appendMetaTag('keywords',$mainframe->getCfg('MetaKeys'));
		($mainframe->getCfg('generator_off') == 0) ? $mainframe->addMetaTag('Generator',coreVersion::$CMS.' - '.coreVersion::$COPYRIGHT) : null;

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
		/* TODO это вообще надо?
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
		*/
		if($my->id || $mainframe->get('joomlaJavascript')) {
			echo JHTML::js_file( JPATH_SITE.'/includes/js/joomla.javascript.js' );
		}

		// отключение RSS вывода в шапку

		if($mainframe->getCfg('syndicate_off')==0) {
			echo $this->syndicate_header();
			echo "\r\n";
		}

		// favourites icon
		if(!$mainframe->getCfg('disable_favicon')) {
			if(!$mainframe->getCfg('favicon')) {
				$favicon = 'favicon.ico';
			}else {
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
	function syndicate_header() {
		$mainframe = $this->_mainframe;

		$row = new stdClass();
		$row->params = '';
		$row->option = '';
		$query = "SELECT a.params, a.option FROM #__components AS a WHERE ( a.admin_menu_link = 'option=com_syndicate' OR a.admin_menu_link = 'option=com_syndicate&hidemainmenu=1' ) AND a.option = 'com_syndicate'";
		$mainframe->getDBO()->setQuery($query)->loadObject($row);

		// get params definitions
		$syndicateParams = new mosParameters($row->params,$mainframe->getPath('com_xml',$row->option),'component');

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

		if( $live_bookmark ) {
			$show = 1;

			if( $syndicateParams->def('check',1) ) {
				// проверяем, не опубликован ли уже модуль с RSS
				$query = "SELECT m.id FROM #__modules AS m WHERE m.module = 'mod_rssfeed' AND m.published = 1 LIMIT 1";
				$check = $mainframe->getDBO()->setQuery($query)->loadResult();
				if($check>0) {
					$show = 0;
				}
			}

			if($show) {
				$link_file = JPATH_SITE.'/index2.php?option=com_rss&feed='.$live_bookmark.'&no_html=1';
				$link_file = ampReplace($link_file);
				?><link rel="alternate" type="application/rss+xml" title="<?php echo $mainframe->getCfg('sitename'); ?>" href="<?php echo $link_file; ?>" /><?php
			}
		}
	}
}
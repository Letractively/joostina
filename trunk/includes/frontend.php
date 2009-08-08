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
	$mainframe = &mosMainFrame::getInstance();
	$mosConfig_live_site = &Jconfig::getInstance()->config_live_site;
	$config = &Jconfig::getInstance();

	$popMessages = false;

	// Browser Check
	$browserCheck = 0;
	if(isset($_SERVER['HTTP_USER_AGENT']) && isset($_SERVER['HTTP_REFERER']) &&strpos($_SERVER['HTTP_REFERER'],$mosConfig_live_site) !== false) {
		$browserCheck = 1;
	}

	// Session Check
	$sessionCheck = 0;
	// Session Cookie `name`
	$sessionCookieName = mosMainFrame::sessionCookieName();
	// Get Session Cookie `value`
	$sessioncookie = mosGetParam($_COOKIE,$sessionCookieName,null);
	if((strlen($sessioncookie) == 32 || $sessioncookie == '-')) {
		$sessionCheck = 1;
	}

	$mosmsg = $mainframe->get_mosmsg();
	if($mosmsg && !$popMessages && $browserCheck && $sessionCheck) {
		echo '<div class="message">'.$mosmsg.'</div>';
	}

	$_body = $GLOBALS['_MOS_OPTION']['buffer'];

	// активация мамботов группы mainbody
	if($config->config_mmb_mainbody_off == 0) {
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
/**
* Utility functions and classes
*/
function mosLoadComponent($name) {
	// set up some global variables for use by frontend components
	global $my,$acl;
	global $task,$Itemid,$id,$option,$gid;
	$mainframe = &mosMainFrame::getInstance();
	$database = &database::getInstance();
	include ($mainframe->getCfg('absolute_path').DS."components/com_$name/$name.php");
}
/**
* Cache some modules information
* @return array
*/
function &initModules() {
	global $my,$Itemid;

	static $all_modules;

	if(!isset($all_modules)) {
		$database = &database::getInstance();
		$config = &Jconfig::getInstance();
		
		$all_modules = array();

		$Itemid = intval($Itemid);
		$check_Itemid = '';
		if($Itemid) {
			$check_Itemid = "OR mm.menuid = ".(int)$Itemid;
		}

		$where_ac = $config->config_disable_access_control ? '' : "\n AND (m.access=3 OR m.access <= ".(int)$my->gid.') ';

		$query = "SELECT id, title, module, position, content, showtitle, params,access FROM #__modules AS m"
				."\n INNER JOIN #__modules_menu AS mm ON mm.moduleid = m.id"
				."\n WHERE m.published = 1"
				.$where_ac
				."\n AND m.client_id != 1 AND ( mm.menuid = 0 $check_Itemid )"
				."\n ORDER BY ordering";

		$database->setQuery($query);
		$modules = $database->loadObjectList();

		foreach($modules as $module) {
			if($module->access==3){
				$my->gid==0 ? $GLOBALS['_MOS_MODULES'][$module->position][] = $module : null;
			}else{
				$GLOBALS['_MOS_MODULES'][$module->position][] = $module;
				$all_modules[$module->position][] = $module;
			}
		}
		unset($modules,$module);
		require_once ($config->config_absolute_path.'/includes/frontend.html.php');
	}

	return $all_modules;
}
/**
* @param string the template position
*/
function mosCountModules($position = 'left') {
	global $my,$Itemid;

	$database = &database::getInstance();

	$tp = intval(mosGetParam($_GET,'tp',0));
	if($tp) {
		return 1;
	}

	$allModules = &initModules();
	if(isset($allModules[$position])) {
		return count($allModules[$position]);
	} else {
		return 0;
	}
}
/**
* @param string The position
* @param int The style.  0=normal, 1=horiz, -1=no wrapper
*/
function mosLoadModules($position = 'left',$style = 0,$noindex = 0) {
	global $my,$Itemid;

	$tp = intval(mosGetParam($_GET,'tp',0));

	if($tp && !$config->config_disable_tpreview ) {
		echo '<div style="height:50px;background-color:#eee;margin:2px;padding:10px;border:1px solid #f00;color:#700;">'.$position.'</div>';
		return;
	}

	$database = &database::getInstance();
	$config = &Jconfig::getInstance();

	$style = intval($style);

	$allModules = &initModules();

	$modules = (isset($allModules[$position])) ? $modules = $allModules[$position]:array();

	echo ($noindex == 1) ? '<noindex>' : null;

	if(count($modules) < 1) {
		$style = 0;
	}
	if($style == 1) {
		echo '<table cellspacing="1" cellpadding="0" border="0" width="100%"><tr>';
	}
	$prepend = ($style == 1) ? '<td valign="top">' : '';
	$postpend = ($style == 1) ? '</td>' : '';

	$count = 1;

	foreach($modules as $module) {

		$params = new mosParameters($module->params);
		$def_cachetime = ($params->get('cache_time',0)>0) ? $params->get('cache_time') : null;

		echo $prepend;

		if((substr($module->module,0,4)) == 'mod_') {

			$cache = &mosCache::getCache($module->module.'_'.$module->id,'function',null,$def_cachetime);
			// normal modules
			if(($params->get('cache',0) == 1 OR $def_cachetime>0) && $config->config_caching == 1) {
				// module caching
				$cache->call('modules_html::module2',$module,$params,$Itemid,$style,$my->gid);
			} else {
				modules_html::module2($module,$params,$Itemid,$style,$count);
			}
		} else {

			$cache = &mosCache::getCache('mod_user_'.$module->id,'function',null,$def_cachetime);
			// custom or new modules
			if($params->get('cache') == 1 && $config->config_caching == 1) {
				// module caching
				$cache->call('modules_html::module',$module,$params,$Itemid,$style,0,$my->gid);
			} else {
				modules_html::module($module,$params,$Itemid,$style);
			}
		}

		echo $postpend;

		$count++;
		unset($cache);
	}
	if($style == 1) {
		echo "</tr>\n</table>\n";
	}

	if($noindex == 1) echo '</noindex>';

	return;
}

/**
* @param string The position
* @param int The style.  0=normal, 1=horiz, -1=no wrapper
*/
function mosLoadModule($name = '', $title = '', $style = 0, $noindex = 0) {
	global $my,$Itemid;

	$database = &database::getInstance();
	$config = &Jconfig::getInstance();

	$tp = intval(mosGetParam($_GET,'tp',0));

	if($tp && !$config->config_disable_tpreview ) {
		echo '<div style="height:50px;background-color:#eee;margin:2px;padding:10px;border:1px solid #f00;color:#700;">';
		echo $position;
		echo '</div>';
		return;
	}
	$style = intval($style);
	$cache = &mosCache::getCache('modules');

	$module = new mosModule($database);
	$module->load_module($name, $title);


	if($noindex == 1) echo '<noindex>';

	if($style == 1) {
		echo '<table cellspacing="1" cellpadding="0" border="0" width="100%"><tr>';
	}
	$prepend = ($style == 1)?"<td valign=\"top\">\n":'';
	$postpend = ($style == 1)?"</td>\n":'';

	$count = 1;

	$params = new mosParameters($module->params);
	echo $prepend;

	if((substr($module->module,0,4)) == 'mod_') {
		// normal modules
		if($params->get('cache') == 1 && $config->config_caching == 1) {
			// module caching
			$cache->call('modules_html::module2',$module,$params,$Itemid,$style,$my->gid);
		} else {
			modules_html::module2($module,$params,$Itemid,$style,$count);
		}
	} else {
		// custom or new modules
		if($params->get('cache') == 1 && $config->config_caching == 1) {
			// module caching
			$cache->call('modules_html::module',$module,$params,$Itemid,$style,0,$my->gid);
		} else {
			modules_html::module($module,$params,$Itemid,$style);
		}
	}

	echo $postpend;
	$count++;

	if($style == 1) {
		echo "</tr>\n</table>\n";
	}
	if($noindex == 1) echo '</noindex>';
	return;
}

/**
* Шапка страницы
*/
function mosShowHead($params=array('js'=>1,'css'=>1)) {
	global $option,$my,$_VERSION,$task,$id;

	$config = &Jconfig::getInstance();
	$database = &database::getInstance();
	$mainframe = &mosMainFrame::getInstance();

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
		$mainframe->appendMetaTag('description',$config->config_MetaDesc);
	}

	if(!$keywords) {
		$mainframe->appendMetaTag('keywords',$config->config_MetaKeys);
	}
/* этот участок делает что-то странное с мета-тэгом keywords, он уберает дубли слов, очень толсто...
	if($_meta_keys_index != -1) {
		$keys = $mainframe->_head['meta'][$_meta_keys_index][1];
		$keys = preg_replace("/\,+/is",", ",$keys);
		$keys = preg_replace("/\s+/is"," ",$keys);
		$keys = Jstring::strtolower($keys);
		$keys = implode(', ',array_unique(split(', ',$keys)));
		$mainframe->_head['meta'][$_meta_keys_index][1] = $keys;
	}
*/
	// отключение тега Generator
	if($config->config_generator_off == 0) {
		$mainframe->addMetaTag('Generator',$_VERSION->CMS.' - '.$_VERSION->COPYRIGHT);
	}


	if($config->config_index_tag == 1) {
		$mainframe->addMetaTag('distribution','global');
		$mainframe->addMetaTag('rating','General');
		$mainframe->addMetaTag('document-state','Dynamic');
		$mainframe->addMetaTag('documentType','WebDocument');
		$mainframe->addMetaTag('audience','all');
		$mainframe->addMetaTag('revisit',$config->config_mtage_revisit.' days');
		$mainframe->addMetaTag('revisit-after',$config->config_mtage_revisit.' days');
		$mainframe->addMetaTag('allow-search','yes');
		$mainframe->addMetaTag('language',$config->config_lang);
	}

	echo $mainframe->getHead($params);

	// очистка ссылки на главную страницу даже при отключенном sef
	if ( $config->config_mtage_base == 1) {
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
		$theURI = str_replace($config->config_live_site.'/','',$theURI);
		echo '<base href="'.sefRelToAbs($theURI).'" />'."\r\n";
	}



	if($my->id || $mainframe->get('joomlaJavascript')) {
		?><script src="<?php echo $config->config_live_site; ?>/includes/js/joomla.javascript.js" type="text/javascript"></script>
		<?php
	}

	// отключение RSS вывода в шапку

	if($config->config_syndicate_off==0) {
		$cache = &mosCache::getCache('header');
		echo $cache->call('syndicate_header');
		echo "\r\n";
	}

	// favourites icon
	if(!$config->config_disable_favicon) {
		if(!$config->config_favicon) {
			$config->config_favicon = 'favicon.ico';
		}
		$icon = $config->config_absolute_path.'/images/'.$config->config_favicon;
		if(!file_exists($icon)) {
			$icon = $config->config_live_site.'/images/favicon.ico';
		} else {
			$icon = $config->config_live_site.'/images/'.$config->config_favicon;
		}
		echo '<link rel="shortcut icon" href="'.$icon.'" />';
	}
}

function mosShowFooter($params=array('fromheader'=>1,'js'=>1)) {
	$mainframe = &mosMainFrame::getInstance();
	echo $mainframe->getFooter($params);
}

// установка мета-тэгов для поисковика
function set_robot_metatag($robots) {
	$mainframe = &mosMainFrame::getInstance();

	if($robots == 0) {
		$mainframe->addMetaTag('robots','index, follow');
	}
	if($robots == 1) {
		$mainframe->addMetaTag('robots','index, nofollow');
	}
	if($robots == 2) {
		$mainframe->addMetaTag('robots','noindex, follow');
	}
	if($robots == 3) {
		$mainframe->addMetaTag('robots','noindex, nofollow');
	}
}

// выводк лент RSS
function syndicate_header(){
	$mainframe =  &mosMainFrame::getInstance();
	$database = &database::getInstance();
	$config = &Jconfig::getInstance();

	$row = new mosComponent();
	$query = "SELECT a.params, a.option FROM #__components AS a WHERE ( a.admin_menu_link = 'option=com_syndicate' OR a.admin_menu_link = 'option=com_syndicate&hidemainmenu=1' ) AND a.option = 'com_syndicate'";
	$database->setQuery($query);
	$database->loadObject($row);

	// get params definitions
	$syndicateParams = new mosParameters($row->params,$mainframe->getPath('com_xml',$row->option),'component');


	// needed to reduce query
	$GLOBALS['syndicateParams'] = $syndicateParams;

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

		$link_file = $config->config_live_site.'/index2.php?option=com_rss&feed='.$live_bookmark.'&no_html=1';

		// xhtml check
		$link_file = ampReplace($link_file);

		// security chcek
		$check = $syndicateParams->def('check',1);
		if($check) {
			// проверяем, не опубликован ли уже модель с RSS
			$query = "SELECT m.id FROM #__modules AS m WHERE m.module = 'mod_rssfeed' AND m.published = 1 LIMIT 1";
			$database->setQuery($query);
			$check = $database->loadResult();
			if($check>0) {
				$show = 0;
			}
		}
		if($show) {
			?><link rel="alternate" type="application/rss+xml" title="<?php echo $config->config_sitename; ?>" href="<?php echo $link_file; ?>" /><?php
		}
	}
}

?>

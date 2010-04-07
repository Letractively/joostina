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

class mosCommonHTML {

	public static function ContentLegend() {
		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
		?>
<table cellspacing="0" cellpadding="4" border="0" align="center">
	<tr align="center">
		<td><img src="<?php echo $cur_file_icons_path;?>/publish_y.png" alt="<?php echo _PUBLISHED_VUT_NOT_ACTIVE?>" border="0" /></td>
		<td><?php echo _PUBLISHED_VUT_NOT_ACTIVE?> |</td>
		<td><img src="<?php echo $cur_file_icons_path;?>/publish_g.png" alt="<?php echo _PUBLISHED_AND_ACTIVE?>" border="0" /></td>
		<td><?php echo _PUBLISHED_AND_ACTIVE?> |</td>
		<td><img src="<?php echo $cur_file_icons_path;?>/publish_r.png" alt="<?php echo _PUBLISHED_BUT_DATE_EXPIRED?>" border="0" /></td>
		<td><?php echo _PUBLISHED_BUT_DATE_EXPIRED?> |</td>
		<td><img src="<?php echo $cur_file_icons_path;?>/publish_x.png" alt="<?php echo _UNPUBLISHED?>" border="0" /></td>
		<td><?php echo _UNPUBLISHED?></td>
	</tr>
</table>
		<?php
	}

	public static function menuLinksContent(&$menus) {
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
	<td width="90px" valign="top"><?php echo _MENU?></td>
	<td><a href="javascript:go2( 'go2menu', '<?php echo $menu->menutype; ?>' );"><?php echo $menu->menutype; ?></a></td>
</tr>
<tr>
	<td width="90px" valign="top"><?php echo _LINK_NAME?></td>
	<td>
		<strong><a href="javascript:go2( 'go2menuitem', '<?php echo $menu->menutype; ?>', '<?php echo $menu->id; ?>' );" ><?php echo $menu->name; ?></a></strong>
	</td>
</tr>
<tr>
	<td width="90px" valign="top"><?php echo _E_STATE?></td>
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

	public static function menuLinksSecCat(&$menus) {
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
		<?php foreach($menus as $menu) { ?>
<tr>
	<td colspan="2"><hr /></td>
</tr>
<tr>
	<td width="90px" valign="top"><?php echo _MENU?></td>
	<td><a href="javascript:go2( 'go2menu', '<?php echo $menu->menutype; ?>' );" ><?php echo $menu->menutype; ?></a></td>
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
	<td width="90px" valign="top"><?php echo _E_STATE?></td>
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
			<?php } ?>
<input type="hidden" name="menu" value="" />
<input type="hidden" name="menuid" value="" />
		<?php
	}

	public static function checkedOut(&$row,$overlib = 1) {
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
		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
		return '<img src="'.$cur_file_icons_path.'/checked_out.png" '.$hover.'/>';
	}

	public static function loadOverlib($ret = false) {
		if(!defined('_LOADOVERLIB')) {
			// установка флага о загруженной библиотеке всплывающих подсказок
			define('_LOADOVERLIB',1);
			MosMainFrame::getInstance()->addJS(JPATH_SITE.'/includes/js/overlib_full.js');
			return true;
		}

		if( $ret ) {
			mosMainFrame::addClass('mosHTML');
			echo mosHTML::js_file( JPATH_SITE.'/includes/js/overlib_full.js' );
		}
	}

	public static function loadCalendar() {
		if(!defined('_CALLENDAR_LOADED')) {
			define('_CALLENDAR_LOADED',1);
			$mainframe = MosMainFrame::getInstance();
			$mainframe->addCSS(JPATH_SITE.'/includes/js/calendar/calendar.css');
			$mainframe->addJS(JPATH_SITE.'/includes/js/calendar/calendar.js');
			$_lang_file = JPATH_BASE.'/includes/js/calendar/lang/calendar-'._LANGUAGE.'.js';
			$_lang_file = (is_file($_lang_file)) ? JPATH_SITE.'/includes/js/calendar/lang/calendar-'._LANGUAGE.'.js' : JPATH_SITE.'/includes/js/calendar/lang/calendar-ru.js';
			$mainframe->addJS($_lang_file);
		}
	}

	// TODO убрать к 1.3.2
	public static function loadMootools($ret = false) {
		if(!defined('_MOO_LOADED')) {
			define('_MOO_LOADED',1);
			MosMainFrame::getInstance()->addJS(JPATH_SITE.'/includes/js/mootools/mootools.js');
			return true;
		}
		if($ret==true) {
			mosMainFrame::addClass('mosHTML');
			echo mosHTML::js_file( JPATH_SITE.'/includes/js/mootools/mootools.js' );
		}
	}

	// TODO убрать к 1.3.2
	public static function loadPrettyTable() {
		if(!defined('_PRT_LOADED')) {
			define('_PRT_LOADED',1);
			mosMainFrame::getInstance()->addJS(JPATH_SITE.'/includes/js/jsfunction/jrow.js');
		}
	}

	// TODO убрать к 1.3.5
	public static function loadFullajax($ret = false) {
		if(!defined('_FAX_LOADED')) {
			define('_FAX_LOADED',1);
			if($ret) {
				mosMainFrame::addClass('mosHTML');
				echo mosHTML::js_file( JPATH_SITE.'/includes/js/fullajax/fullajax.js' );
			}else {
				mosMainFrame::getInstance()->addJS(JPATH_SITE.'/includes/js/fullajax/fullajax.js');
			}
		}
	}

	public static function loadJquery($ret = false) {
		if(!defined('_JQUERY_LOADED')) {
			define('_JQUERY_LOADED',1);
			if($ret) {
				mosMainFrame::addClass('mosHTML');
				echo mosHTML::js_file( JPATH_SITE.'/includes/js/jquery/jquery.js' );
			}else {
				mosMainFrame::getInstance()->addJS(JPATH_SITE.'/includes/js/jquery/jquery.js');
			}
		}
	}

	public static function loadJqueryPlugins($name,$ret = false, $css = false, $footer = '') {
		$name = trim($name);

		// если само ядро Jquery не загружено - сначала грузим его
		defined('_JQUERY_LOADED') ? null : mosCommonHTML::loadJquery($ret);

		// формируем константу-флаг для исключения повтороной загрузки
		$const = '_JQUERY_PL_'.strtoupper($name).'_LOADED';

		if(!defined($const)) {
			define($const,1);
			if($ret) {
				mosMainFrame::addClass('mosHTML');
				echo mosHTML::js_file( JPATH_SITE.'/includes/js/jquery/plugins/'. $name.'.js' );
				echo ($css) ? mosHTML::css_file( JPATH_SITE.'/includes/js/jquery/plugins/'. $name.'.css' ) : '';
			}else {
				$mainframe = mosMainFrame::getInstance();
				$mainframe->addJS(JPATH_SITE.'/includes/js/jquery/plugins/'.$name.'.js', $footer);
				$css ? $mainframe->addCSS(JPATH_SITE.'/includes/js/jquery/plugins/'.$name.'.css'): null;
			}
		}
	}

	public static function loadJqueryUI($ret = false) {
		if(!defined('_JQUERY_UI_LOADED')) {
			define('_JQUERY_UI_LOADED',1);

			if($ret) {
				mosMainFrame::addClass('mosHTML');
				echo mosHTML::js_file( JPATH_SITE.'/includes/js/jquery/ui.js' );
			}else {
				mosMainFrame::getInstance()->addCSS(JPATH_SITE.'/includes/js/jquery/ui.js');
			}
		}
	}

	public static function loadDtree() {
		if(!defined('_DTR_LOADED')) {
			define('_DTR_LOADED',1);
			$mainframe = mosMainFrame::getInstance();
			$mainframe->addCSS(JPATH_SITE.'/includes/js/dtree/dtree.css');
			$mainframe->addJS(JPATH_SITE.'/includes/js/dtree/dtree.js');
		}
	}

	public static function AccessProcessing(&$row,$i,$ajax=null) {
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
		if(!$ajax) {
			$href = '<a href="javascript: void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$task_access.'\')" '.$color_access.'>'.$row->groupname.'</a>';
		}else {
			$option = strval(mosGetParam($_REQUEST,'option',''));
			$href = '<a href="#" onclick="ch_access('.$row->id.',\''.$task_access.'\',\''.$option.'\');" '.$color_access.'>'.$row->groupname.'</a>';
		}
		return $href;
	}

	public static function CheckedOutProcessing(&$row,$i) {
		if($row->checked_out) {
			$checked = mosCommonHTML::checkedOut($row);
		} else {
			global $my;
			$checked = mosHTML::idBox($i,$row->id,($row->checked_out && $row->checked_out !=$my->id));
		}
		return $checked;
	}

	public static function PublishedProcessing(&$row,$i) {
		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
		$img = $row->published ? 'publish_g.png':'publish_x.png';
		$task = $row->published ? 'unpublish':'publish';
		$alt = $row->published ? _PUBLISHED:_UNPUBLISHED;
		$action = $row->published ? _HIDE:_PUBLISH_ON_FRONTPAGE;
		return '<a href="javascript: void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.$action.'"><img src="'.$cur_file_icons_path.'/'.$img.'" border="0" alt="'.$alt.'" /></a>';
	}

	public static function newsfeedEncoding($rssDoc,$text,$utf8enc=null) {

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

		return str_replace('&apos;',"'",$text);
	}

	public static function get_element($file) {

		$file_templ = 'templates/'.JTEMPLATE.'/images/elements/'.$file;
		$file_system = 'M_images/'.$file;

		$return = $file_templ;
		if(!is_file(JPATH_BASE.DS.$file_templ)) {
			$return = $file_system;
		}

		return $return;
	}


	/**
	 * @param string SQL with ordering As value and 'name field' AS text
	 * @param integer The length of the truncated headline
	 */
	public static function mosGetOrderingList($sql,$chop = '30') {
		$database = database::getInstance();

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
}
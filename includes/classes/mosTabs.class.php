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

		/* запрет повторного включения css и js файлов в документ*/
		if(!defined('_MTABS_LOADED')) {
			define('_MTABS_LOADED',1);

			mosMainFrame::addClass('mosHTML');
			$js  = mosHTML::js_file( JPATH_SITE.'/includes/js/tabs/tabpane.js' );
			$css = mosHTML::css_file( JPATH_SITE.'/includes/js/tabs/tabpane.css' );

			if($xhtml) {
				$mainframe = mosMainFrame::getInstance();
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
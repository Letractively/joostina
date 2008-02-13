<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, смотрите LICENSE.php
* Joostina! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );

/**
* @package Joostina
* @subpackage Admin
*/
class HTML_admin_misc {

	/**
	* Control panel
	*/
	function controlPanel() {
		global $mosConfig_absolute_path, $mainframe;
		?>
		<table class="adminheading" border="0">
		<tr>
			<th class="cpanel">
			Панель управления
			</th>
		</tr>
		</table>
		<?php
		$path = $mosConfig_absolute_path . '/administrator/templates/' . $mainframe->getTemplate() . '/cpanel.php';
		if (file_exists( $path )) {
			require $path;
		} else {
			echo '<br />';
			mosLoadAdminModules( 'cpanel', 1 );
		}
	}

	function get_php_setting($val, $colour=0, $yn=1) {
		$r =  (ini_get($val) == '1' ? 1 : 0);

		if ($colour) {
			if ($yn) {
				$r = $r ? '<span style="color: green;">ON</span>' : '<span style="color: red;">OFF</span>';
			} else {
				$r = $r ? '<span style="color: red;">ON</span>' : '<span style="color: green;">OFF</span>';
			}

			return $r;
		} else {
		return $r ? 'ON' : 'OFF';
	}
	}

	function get_server_software() {
		if (isset($_SERVER['SERVER_SOFTWARE'])) {
			return $_SERVER['SERVER_SOFTWARE'];
		} else if (($sf = phpversion() <= '4.2.1' ? getenv('SERVER_SOFTWARE') : $_SERVER['SERVER_SOFTWARE'])) {
			return $sf;
		} else {
			return 'n/a';
		}
	}

	function system_info( $version ) {
		global $mosConfig_absolute_path, $database, $mosConfig_cachepath, $mainframe,$mosConfig_live_site;

		mosCommonHTML::loadPquery();
		$pquery= new PQuery();

		$width = 400;	// width of 100%
		$tabs = new mosTabs(0);
		?>
		<script language="JavaScript" src="<?php echo $mosConfig_live_site;?>/includes/js/jquery/jquery.innerfade.js" type="text/javascript"></script>
		<script type="text/javascript">
		$(document).ready( function(){
			$('#about').innerfade({
				speed: 2000,
				timeout: 3000,
				containerheight: '120px',
			});
		} );</script>
		<table class="adminheading">
		<tr>
			<th class="info">
			Информация
			</th>
		</tr>
		</table>

		<?php
		$tabs->startPane("sysinfo");
		$tabs->startTab("О Joostina","joostina-page");
		?>
		<div>
			<ul style="list-style:none;" id="about">
<li><span class="joost_info">Joostina! включает в себя следующие программные продукты:</span></li>
<li><span class="joost_info">Joomla!</span><br /><br />
Авторские права: (C) 2005-2007 Open Source Matters.
Лицензия:        GNU General Public License (GPL)</li>
<li><span class="joost_info">Joomla RE</span><br /><br />
Авторские права (C) 2005-2007 Joom.Ru - Русский дом Joomla
Лицензия:        GNU General Public License (GPL)</li>
<li><span class="joost_info">jQuery</span><br /><br />
Авторские права: (c) 2007 John Resig (jquery.com)
Лицензия:        GNU General Public License (GPL)</li>
<li><span class="joost_info">PQuery</span><br /><br />
Авторские права: (c) 2006, ngcoders
Лицензия:        GNU General Public License (GPL)</li>
<li><span class="joost_info">xAjax</span><br /><br />
Авторские права: (c) 2005 by Jared White & J. Max Wilson
Лицензия:        GNU Lesser General Public License (LGPL)</li>
<li><span class="joost_info">PHP Client Sniffer (phpsniff)</span><br /><br />
Авторские права: (с) 2002-2004 Roger Raymond
Лицензия:        GNU Lesser General Public License (LGPL)</li>
<li><span class="joost_info">ConvertCharset</span><br /><br />
Авторские права: (с) 2003-2004 Mikolaj Jedrzejak 2003-2004
<li><span class="joost_info">Значки системы: nuoveXT-kde-1.6</span><br /><br />
Лицензия:        GNU General Public License (GPL)</li>
<li><span class="joost_info">CodePress</span><br /><br />-
Авторское право: Copyright (C) 2006 Fernando M.A.d.S. fermads@gmail.com - http://codepress.org/
Лицензия:        GNU Lesser General Public License</li>
<li><span class="joost_info">Расширения системы</span></li>
<li><span class="joost_info">Мамбот "Русская типографика"</span><br /><br />Рябов Денис ( http://sanrsu.org.ru/ )</li>
<li><span class="joost_info">ebackup</span><br /><br />Harald Baer ( www.mambobaer.de )</li>
<li><span class="joost_info">JCE</span><br /><br />Ryan Demmer ( www.cellardoor.za.net )</li>
<li><span class="joost_info">mycheckin</span><br /><br />Bart Eversdijk ( www.eversdijk.com )</li>
<li><span class="joost_info">JoomlaPack</span><br /><br />Nicholas K. Dionysopoulos ( www.joomlapack.net )</li>
<li><span class="joost_info">joomlaXplorer</span><br /><br />soeren, QuiX Project ( www.virtuemart.net )</li>
<li><span class="joost_info">JW MMXTD</span><br /><br />JoomlaWorks ( www.joomlaworks.gr )</li>
<li><span class="joost_info">Link Editor</span><br /><br />Soner Ekici ( www.joomlaturkiye.org )</li>
<li><span class="joost_info">Custom QuickIcon</span><br /><br />mic ( www.joomx.com )</li>
<li><span class="joost_info">Xmap</span><br /><br />Guillermo Vargas ( joomla.vargas.co.cr )</li>
<li><span class="joost_info">Cache</span><br /><br />
Авторское право:        Fabien MARTY
Лицензия:        GNU Lesser General Public License (LGPL)</li>
<li><span class="joost_info">DOMIT!</span><br /><br />-
Авторское право:        2004 John Heinstein. All rights reserved
Лицензия:        GNU Lesser General Public License (LGPL)</li>
<li><span class="joost_info">feedcreator</span><br /><br />
Авторское право:        Kai Blankenhorn
Лицензия:        GNU General Public License (GPL)</li>
<li><span class="joost_info">js-calendar</span><br /><br />
Авторское право:        Mihai Bazon, 2002
Лицензия:        GNU Lesser General Public License (LGPL)</li>
<li><span class="joost_info">js-jscook-menu</span><br /><br />
Авторское право:        2002-2005 by Heng Yuan
Лицензия:        Custom open source license
<li><span class="joost_info">js-overlib</span><br /><br />
Авторское право:        Erik Bosrup 1998-2004
Лицензия:        Artistic (see http://www.bosrup.com/web/overlib/?License)
<li><span class="joost_info">js-tabs</span><br /><br />
Авторское право:        1998 - 2003 Erik Arvidsson
Лицензия:        GNU General Public License (GPL)</li>
<li><span class="joost_info">Mambo</span><br /><br />
Авторское право:        2000 - 2004 Miro International Pty Ltd
Лицензия:        GNU General Public License (GPL)</li>
<li><span class="joost_info">patTemplate, patError</span><br /><br />
Авторское право:        Stephan Schmidt
Лицензия:        GNU Lesser General Public License (LGPL)</li>
<li><span class="joost_info">PEAR</span><br /><br />
Авторское право:        1997-2004 The PHP Group
Лицензия:        PHP license</li>
<li><span class="joost_info">phpGACL</span><br /><br />
Авторское право:        2002,2003 Mike Benoit
Лицензия:        GNU Lesser General Public License (LGPL)</li>
<li><span class="joost_info">phpInputfilter</span><br /><br />
Авторское право: Daniel Morris
Лицензия:        GNU General Public License (GPL)</li>
<li><span class="joost_info">phpMailer</span><br /><br />
Авторское право: 2001 - 2003  Brent R. Matzelle
Лицензия:        GNU Lesser General Public License (LGPL)</li>
<li><span class="joost_info">tar-archive</span><br /><br />
Авторское право: 1997-2003 The PHP Group
Лицензия:        PHP License</li>
<li><span class="joost_info">TinyMCE:</span><br /><br />
Авторское право: 2004 Moxiecode Systems AB
Лицензия:        GNU Lesser General Public License (LGPL)</li>
<li><span class="joost_info">vcard</span><br /><br />
Авторское право: Kai Blankenhorn
Лицензия:        GNU General Public License (GPL)</li>
<li><span class="joost_info">wz-tooltip</span><br /><br />
Авторское право: 2002-2004 Walter Zorn
Лицензия:        GNU Lesser General Public License (LGPL)</li>
<li><span class="joost_info">Значки FOOOD (Панель управления администратора)</span><br /><br />-
Авторское право: 2004 FOOOD's Icons
Лицензия:        http://www.foood.net/joomla.htm
Примечание:      Please note these icons are NOT OPEN SOURCE but used with permission</li>
			</ul>
		</div>
		<?php
		$tabs->endTab();
		$tabs->startTab("О системе","system-page");
		?>
			<table class="adminform">
			<tr>
				<td colspan="2">
					<?php
					// show security setting check
					josSecurityCheck();
					?>
				</td>
			</tr>
			<tr>
				<td valign="top" width="250">
					<strong>Система:</strong>
				</td>
				<td>
					<?php echo php_uname(); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Версия базы данных:</strong>
				</td>
				<td>
					<?php echo $database->getVersion(); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Версия PHP:</strong>
				</td>
				<td>
					<?php echo phpversion(); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Веб-сервер:</strong>
				</td>
				<td>
					<?php echo HTML_admin_misc::get_server_software(); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Интерфейс между веб-сервером и PHP:</strong>
				</td>
				<td>
					<?php echo php_sapi_name(); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Версия Joostina!:</strong>
				</td>
				<td>
					<?php echo $version; ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Браузер (User Agent):</strong>
				</td>
				<td>
					<?php echo phpversion() <= '4.2.1' ? getenv( 'HTTP_USER_AGENT' ) : $_SERVER['HTTP_USER_AGENT'];?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="height: 10px;">
				</td>
			</tr>
			<tr>
				<td valign="top">
					<strong>Важные настройки PHP:</strong>
				</td>
				<td>
					<table cellspacing="1" cellpadding="1" border="0">
					<tr>
						<td width="250">
							Эмуляция Register Globals!:
						</td>
						<td style="font-weight: bold;" width="50">
							<?php echo ((RG_EMULATION) ? '<span style="color: red;">ON</span>' : '<span style="color: green;">OFF</span>'); ?>
						</td>
						<td>
							<?php $img = ((RG_EMULATION) ? 'publish_x.png' : 'tick.png'); ?>
							<img src="../images/<?php echo $img; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							Register Globals - регистрация глобальных переменных:
						</td>
						<td style="font-weight: bold;">
							<?php echo HTML_admin_misc::get_php_setting('register_globals',1,0); ?>
						</td>
						<td>
							<?php $img = ((ini_get('register_globals')) ? 'publish_x.png' : 'tick.png'); ?>
							<img src="../images/<?php echo $img; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							Параметр Magic Quotes:
						</td>
						<td style="font-weight: bold;">
							<?php echo HTML_admin_misc::get_php_setting('magic_quotes_gpc',1,1); ?>
						</td>
						<td>
							<?php $img = (!(ini_get('magic_quotes_gpc')) ? 'publish_x.png' : 'tick.png'); ?>
							<img src="../images/<?php echo $img; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							Безопасный режим - Safe Mode:
						</td>
						<td style="font-weight: bold;">
							<?php echo HTML_admin_misc::get_php_setting('safe_mode',1,0); ?>
						</td>
						<td>
							<?php $img = ((ini_get('safe_mode')) ? 'publish_x.png' : 'tick.png'); ?>
							<img src="../images/<?php echo $img; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							Загрузка файлов:
						</td>
						<td style="font-weight: bold;">
							<?php echo HTML_admin_misc::get_php_setting('file_uploads',1,1); ?>
						</td>
						<td>
							<?php $img = ((!ini_get('file_uploads')) ? 'publish_x.png' : 'tick.png'); ?>
							<img src="../images/<?php echo $img; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							Обработка сессий:
						</td>
						<td style="font-weight: bold;">
							<?php echo HTML_admin_misc::get_php_setting('session.auto_start',1,0); ?>
						</td>
						<td>
							<?php $img = ((ini_get('session.auto_start')) ? 'publish_x.png' : 'tick.png'); ?>
							<img src="../images/<?php echo $img; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							Каталог хранения сессий - Session save path:
						</td>
						<td style="font-weight: bold;" colspan="2">
							<?php echo (($sp=ini_get('session.save_path'))?$sp:'none'); ?>
						</td>
					</tr>
					<tr>
						<td>
							Спецтеги php:
						</td>
						<td style="font-weight: bold;">
							<?php echo HTML_admin_misc::get_php_setting('short_open_tag'); ?>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
							Буферизация:
						</td>
						<td style="font-weight: bold;">
							<?php echo HTML_admin_misc::get_php_setting('output_buffering'); ?>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
							Разрешенные/открытые каталоги:
						</td>
						<td style="font-weight: bold;" colspan="2">
							<?php echo (($ob = ini_get('open_basedir')) ? $ob : 'none'); ?>
						</td>
					</tr>
					<tr>
						<td>
							Отображение ошибок:
						</td>
						<td style="font-weight: bold;" colspan="2">
							<?php echo HTML_admin_misc::get_php_setting('display_errors'); ?>
						</td>
					</tr>
					<tr>
						<td>
							Поддержка XML:
						</td>
						<td style="font-weight: bold;" colspan="2">
						<?php echo extension_loaded('xml')?'Yes':'No'; ?>
						</td>
					</tr>
					<tr>
						<td>
							Поддержка Zlib:
						</td>
						<td style="font-weight: bold;" colspan="2">
							<?php echo extension_loaded('zlib')?'Yes':'No'; ?>
						</td>
					</tr>
					<tr>
						<td>
							Запрещенные функции:
						</td>
						<td style="font-weight: bold;" colspan="2">
							<?php echo (($df=ini_get('disable_functions'))?$df:'none'); ?>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="height: 10px;">
				</td>
			</tr>
			<tr>
				<td valign="top">
					<strong>Файл конфигурации:</strong>
				</td>
				<td>
				<?php
				$cf = file( $mosConfig_absolute_path . '/configuration.php' );
				foreach ($cf as $k=>$v) {
					if (eregi( 'mosConfig_host', $v)) {
						$cf[$k] = '$mosConfig_host = \'xxxxxx\'';
					} else if (eregi( 'mosConfig_user ', $v)) {
						$cf[$k] = '$mosConfig_user = \'xxxxxx\'';
					} else if (eregi( 'mosConfig_password', $v)) {
						$cf[$k] = '$mosConfig_password = \'xxxxxx\'';
					} else if (eregi( 'mosConfig_db ', $v)) {
						$cf[$k] = '$mosConfig_db = \'xxxxxx\'';
					}
				}
				foreach ($cf as $k=>$v) {
					$k = htmlspecialchars( $k );
					$v = htmlspecialchars( $v );
					$cf[$k]=$v;
				}
				echo implode( "<br />", $cf );
				?>
				</td>
			</tr>
			</table>
		<?php
		$tabs->endTab();
		$tabs->startTab("PHP Info","php-page");
		?>
			<table class="adminform">
			<tr>
				<td>
				<?php
				ob_start();
				phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
				$phpinfo = ob_get_contents();
				ob_end_clean();
				preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output);
				$output = preg_replace('#<table#', '<table class="adminlist" align="center"', $output[1][0]);
				$output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
				$output = preg_replace('#border="0" cellpadding="3" width="600"#', 'border="0" cellspacing="1" cellpadding="4" width="95%"', $output);
				$output = preg_replace('#<hr />#', '', $output);
				echo $output;
				?>
				</td>
			</tr>
			</table>
		<?php
		$tabs->endTab();
		$tabs->startTab('Права доступа','perms');
		?>
			<table class="adminform">
			<tr>
				<td>
					<strong>Для работы ВСЕХ функций и возможностей Joostina, ВСЕ указанные ниже каталоги должны быть доступны для записи:</strong><br />   <br />
					<?php
					$sp = ini_get('session.save_path');

					mosHTML::writableCell( 'administrator/backups' );
					mosHTML::writableCell( 'administrator/components' );
					mosHTML::writableCell( 'administrator/modules' );
					mosHTML::writableCell( 'administrator/templates' );
					mosHTML::writableCell( 'components' );
					mosHTML::writableCell( 'images' );
					mosHTML::writableCell( 'images/banners' );
					mosHTML::writableCell( 'images/stories' );
					mosHTML::writableCell( 'language' );
					mosHTML::writableCell( 'mambots' );
					mosHTML::writableCell( 'mambots/content' );
					mosHTML::writableCell( 'mambots/editors' );
					mosHTML::writableCell( 'mambots/editors-xtd' );
					mosHTML::writableCell( 'mambots/search' );
					mosHTML::writableCell( 'mambots/system' );
					mosHTML::writableCell( 'media' );
					mosHTML::writableCell( 'modules' );
					mosHTML::writableCell( 'templates' );
					mosHTML::writableCell( $mosConfig_cachepath, 0, '<strong>Каталог кэша</strong> ' );
					mosHTML::writableCell( $sp, 0, '<strong>Каталог сессий</strong> ' );
					?>
				</td>
			</tr>
			</table>
		<?php
		$tabs->endTab();
		$tabs->startTab('База данных','db');
		?>
			<table class="adminform">
			<tr>
				<th>Название таблицы:</th>
				<th>Кодировка:</th>
				<th>Записей:</th>
				<th>Размер:</th>
			</tr>
<?php
	$db_info = HTML_admin_misc::db_info();
	$k = 0;
	foreach($db_info as $table){
	if($table->Collation!='cp1251_general_ci') $table->Collation = '<font color="red"><b>'.$table->Collation.'</b></font>';
		echo '<tr class="row'.$k.'"><td><b>'.$table->Name.'</b></td><td>'.$table->Collation.'</td><td>'.$table->Rows.'</td><td>'.$table->Data_length.'</td></tr>';
		$k = 1 - $k;
	}
?>

			</table>
		<?php
		$tabs->endTab();
		$tabs->endPane();
		?>
		<?php
	}
	// получение информации о базе данных
	function db_info(){
		global $database,$mosConfig_db;
		$sql = 'SHOW TABLE STATUS FROM '.$mosConfig_db;
		$database->setQuery($sql);
		return $database->loadObjectList();
	}

	function ListComponents() {
		global $database;

		$query = "SELECT params"
		. "\n FROM #__modules "
		. "\n WHERE module = 'mod_components'"
		;
		$database->setQuery( $query );
		$row = $database->loadResult();
		$params = new mosParameters( $row );

		mosLoadAdminModule( 'components', $params );
	}

	/**
	 * Display Help Page
	 */
	function help() {
		global $mosConfig_live_site;
		$helpurl 	= strval( mosGetParam( $GLOBALS, 'mosConfig_helpurl', '' ) );

		if ( $helpurl == 'http://help.mamboserver.com' ) {
			$helpurl = 'http://help.joomla.org';
		}

		$fullhelpurl = $helpurl . '/index2.php?option=com_content&amp;task=findkey&pop=1&keyref=';

		$helpsearch = strval( mosGetParam( $_REQUEST, 'helpsearch', '' ) );
		$helpsearch = addslashes(htmlspecialchars($helpsearch));

		$page 		= strval( mosGetParam( $_REQUEST, 'page', 'joomla.whatsnew100.html' ) );
		$toc 		= getHelpToc( $helpsearch );
		if (!eregi( '\.html$', $page )) {
			$page .= '.xml';
		}

		echo $helpsearch;
		?>
		<style type="text/css">
		.helpIndex {
			border: 0px;
			width: 95%;
			height: 100%;
			padding: 0px 5px 0px 10px;
			overflow: auto;
		}
		.helpFrame {
			border-left: 0px solid #222;
			border-right: none;
			border-top: none;
			border-bottom: none;
			width: 100%;
			height: 700px;
			padding: 0px 5px 0px 10px;
		}
		</style>
		<form name="adminForm">
		<table class="adminform" border="1">
			<tr>
				<th colspan="2" class="title">
					Помощь
				</th>
			</tr>
			<tr>
			<td colspan="2">
				<table width="100%">
					<tr>
						<td>
							<strong>Поиск:</strong>
							<input class="text_area" type="hidden" name="option" value="com_admin" />
							<input type="text" name="helpsearch" value="<?php echo $helpsearch;?>" class="inputbox" />
							<input type="submit" value="Найти" class="button" />
							<input type="button" value="Очистить" class="button" onclick="f=document.adminForm;f.helpsearch.value='';f.submit()" />
							</td>
							<td style="text-align:right">
							<?php
							if ($helpurl) {
							?>
							<a href="<?php echo $fullhelpurl;?>joomla.glossary" target="helpFrame">
								Глоссарий</a>
							|
							<a href="<?php echo $fullhelpurl;?>joomla.credits" target="helpFrame">
								Разработчики</a>
							|
							<a href="<?php echo $fullhelpurl;?>joomla.support" target="helpFrame">
								Поддержка</a>
							<?php
							} else {
							?>
							<a href="<?php echo $mosConfig_live_site;?>/help/joomla.glossary.html" target="helpFrame">
								Глоссарий</a>
							|
							<a href="<?php echo $mosConfig_live_site;?>/help/joomla.credits.html" target="helpFrame">
								Разработчики</a>
							|
							<a href="<?php echo $mosConfig_live_site;?>/help/joomla.support.html" target="helpFrame">
								Поддержка</a>
							<?php
							}
							?>
							|
							<a href="http://www.gnu.org/copyleft/gpl.html" target="helpFrame">
								Лицензия</a>
							|
							<a href="http://help.joomla.org" target="_blank">
								help.joomla.org</a>
							|
							<a href="http://Joom.Ru" target="_blank">
								Joom.Ru</a>
							<br />
							<a href="<?php echo $mosConfig_live_site;?>/administrator/index3.php?option=com_admin&task=changelog" target="helpFrame">
								Журнал изменений</a>
							|
							<a href="<?php echo $mosConfig_live_site;?>/administrator/index3.php?option=com_admin&task=sysinfo" target="helpFrame">
								Системная информация</a>
							|
							<a href="http://joom.ru/" target="_blank">
								Проверить версию Joomla! RE</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr valign="top">
			<td width="20%" valign="top">
				<strong>Оглавление</strong>
				<div class="helpIndex">
				<?php
				foreach ($toc as $k=>$v) {
					if ($helpurl) {
						echo '<br /><a href="' . $fullhelpurl . urlencode( $k ) . '" target="helpFrame">' . $v . '</a>';
					} else {
						echo '<br /><a href="' . $mosConfig_live_site . '/help/' . $k . '" target="helpFrame">' . $v . '</a>';
					}
				}
				?>
				</div>
			</td>
			<td valign="top">
				<iframe name="helpFrame" src="<?php echo $mosConfig_live_site . '/help/' . $page;?>" class="helpFrame" frameborder="0" /></iframe>
			</td>
		</tr>
		</table>

		<input type="hidden" name="task" value="help" />
		</form>
		<?php
	}

	/**
	* Preview site
	*/
	function preview( $tp=0 ) {
		global $mosConfig_live_site;
		$tp = intval( $tp );
		?>
		<style type="text/css">
		.previewFrame {
			border: none;
			width: 95%;
			height: 600px;
			padding: 0px 5px 0px 10px;
		}
		</style>
		<table class="adminform">
		<tr>
			<th width="50%" class="title">
			Предпросмотр сайта
			</th>
			<th width="50%" style="text-align:right">
			<a href="<?php echo $mosConfig_live_site . '/index.php?tp=' . $tp;?>" target="_blank">
			Открыть в новом окне
			</a>
			</th>
		</tr>
		<tr>
			<td width="100%" valign="top" colspan="2">
			<iframe name="previewFrame" src="<?php echo $mosConfig_live_site . '/index.php?tp=' . $tp;?>" class="previewFrame" /></iframe>
			</td>
		</tr>
		</table>
		<?php
	}

	/*
	* Displays contents of Changelog.php file
	*/
	function changelog() {
		?>
		<pre>
			<?php
			readfile( $GLOBALS['mosConfig_absolute_path'].'/CHANGELOG.php' );
			?>
		</pre>
		<?php
	}
}

/**
 * Compiles the help table of contents
 * @param string A specific keyword on which to filter the resulting list
 */
function getHelpTOC( $helpsearch ) {
	global $mosConfig_absolute_path;
	$helpurl = strval( mosGetParam( $GLOBALS, 'mosConfig_helpurl', '' ) );

	$files = mosReadDirectory( $mosConfig_absolute_path . '/help/', '\.xml$|\.html$' );

	require_once( $mosConfig_absolute_path . '/includes/domit/xml_domit_lite_include.php' );

	$toc = array();
	foreach ($files as $file) {
		$buffer = file_get_contents( $mosConfig_absolute_path . '/help/' . $file );
		if (preg_match( '#<title>(.*?)</title>#', $buffer, $m )) {
			$title = trim( $m[1] );
			if ($title) {
				if ($helpurl) {
					// strip the extension
					$file = preg_replace( '#\.xml$|\.html$#', '', $file );
				}
				if ($helpsearch) {
					if (strpos( strip_tags( $buffer ), $helpsearch ) !== false) {
						$toc[$file] = $title;
					}
				} else {
					$toc[$file] = $title;
				}
			}
		}
	}
	asort( $toc );
	return $toc;
}
?>

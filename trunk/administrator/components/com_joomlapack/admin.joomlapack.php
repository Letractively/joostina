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

// Restrict to Super Administrators only
if (!$acl->acl_check( 'administration', 'config', 'users', $my->usertype )) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

// Make sure $option is populated
global $option;
if (!isset($option)) { $option = mosGetParam( $_REQUEST, 'option', 'com_jpack' ); } // Just in case...
// Get parameters for the task at hand
$act = mosGetParam( $_REQUEST, 'act', 'default' );
$task = mosGetParam( $_REQUEST, 'task', '' );

// Some bureaucracy is only useful for non-AJAX calls. For AJAX calls, it's just a waste of CPU and memory :)
if ($act != "ajax") {
	/** Get the component version from the XML file */
	require_once( $mosConfig_absolute_path . '/includes/domit/xml_domit_lite_include.php' );
	// Parse JoomlaPack XML installation file to get version
	$xmlDoc = new DOMIT_Lite_Document();
	$xmlDoc->resolveErrors( true );
	if ($xmlDoc->loadXML( $mosConfig_absolute_path."/administrator/components/$option/joomlapack.xml", false, true )) {
		$root = &$xmlDoc->documentElement;
		$e = &$root->getElementsByPath('version', 1);
		define("_JP_VERSION", $e->getText()) ;
		$root = &$xmlDoc->documentElement;
		$e = &$root->getElementsByPath('creationDate', 1);
		define("_JP_DATE", $e->getText()) ;
	} else {
		define("_JP_VERSION", "1.1 Series");
	}

	// Default HTML support library (that's the front-end library of JoomlaPack)
	require_once( $mainframe->getPath( 'admin_html' ) );

	/** load the language files */
	global $mosConfig_lang, $mosConfig_absolute_path, $JPLang;

	$langEnglish = parse_ini_file($mosConfig_absolute_path . "/administrator/components/$option/lang/russian.ini", true);
	if (file_exists( $mosConfig_absolute_path . "/administrator/components/$option/lang/$mosConfig_lang.ini" )) {
		$langLocal = parse_ini_file($mosConfig_absolute_path . "/administrator/components/$option/lang/$mosConfig_lang.ini", true);
		$JPLang = array_merge($langEnglish, $langLocal);
		unset( $langEnglish );
		unset( $langLocal );
	} else {
		$JPLang = $langEnglish;
		unset( $langEnglish );
	}
}


// Configuration class
require_once( $mosConfig_absolute_path . "/administrator/components/$option/includes/CConfiguration.php" );


/** handle the task */
switch ($act) {
    case "config":
    	echo '<link rel="stylesheet" href="components/'.$option.'/css/jpcss.css" type="text/css" />';
    	// Configuration screen
    	switch ($task) {
    		case "apply":
    			processSave();
    			jpackScreens::fConfig();
    			jpackScreens::CommonFooter();
    			break;
    		case "save":
    			processSave();
    			jpackScreens::fMain();
    			jpackScreens::CommonFooter();
    			break;
    		case "cancel":
    			jpackScreens::fMain();
    			jpackScreens::CommonFooter();
    			break;
    		default:
    			jpackScreens::fConfig();
    			jpackScreens::CommonFooter();
    			break;
    	}
		break;
    case "pack":
    	echo '<link rel="stylesheet" href="components/'.$option.'/css/jpcss.css" type="text/css" />';
    	// Packing screen - that's where the actual backup takes place
    	require_once( $mosConfig_absolute_path . "/administrator/components/$option/includes/sajax.php" );
    	require_once( $mosConfig_absolute_path . "/administrator/components/$option/includes/ajaxtool.php" );
        jpackScreens::fPack();
        jpackScreens::CommonFooter();
        break;
    case "backupadmin":
        jpackScreens::fBUAdmin();
        switch( $task ) {
        	case "downloadfile":
        		break;
        	default:
        		jpackScreens::CommonFooter();
        		break;
        }
    	break;

	case "def" :
		// Directory exclusion filters
		require_once( $mosConfig_absolute_path . "/administrator/components/$option/includes/CDirExclusionFilter.php" );
		jpackScreens::fDirExclusion();
		jpackScreens::CommonFooter();
		break;

    case "ajax":
    	// AJAX helper functions
		require_once( $mosConfig_absolute_path . "/administrator/components/$option/includes/sajax.php" );
		require_once( $mosConfig_absolute_path . "/administrator/components/$option/includes/ajaxtool.php" );
    	break;

    case "test":
		jpackScreens::fDebug();
        jpackScreens::CommonFooter();
    	break;

    case "log":
		jpackScreens::fLog();
        jpackScreens::CommonFooter();
    	break;

    default:
    	echo '<link rel="stylesheet" href="components/'.$option.'/css/jpcss.css" type="text/css" />';
    	// Application status check
        jpackScreens::fMain();
        jpackScreens::CommonFooter();
        break;
}

function processSave() {
	global $JPConfiguration;
	$outdir				= mosGetParam( $_REQUEST, 'outdir', '' );
	$tempdir			= mosGetParam( $_REQUEST, 'tempdir', '' );
	$sqlcompat			= mosGetParam( $_REQUEST, 'sqlcompat', '' );
	$compress			= mosGetParam( $_REQUEST, 'compress', '' );
	$tarname			= mosGetParam( $_REQUEST, 'tarname', '' );
	$fileListAlgorithm	= mosGetParam( $_REQUEST, 'fileListAlgorithm', 'smart' );
	$dbAlgorithm		= mosGetParam( $_REQUEST, 'dbAlgorithm', 'smart' );
	$packAlgorithm		= mosGetParam( $_REQUEST, 'packAlgorithm', 'smart' );
	$altInstaller		= mosGetParam( $_REQUEST, 'altInstaller', 'jpi.xml' );
	$logLevel			= mosGetParam( $_REQUEST, 'logLevel', '3' );

	$JPConfiguration->OutputDirectory = $outdir;
	$JPConfiguration->TempDirectory = $tempdir;
	$JPConfiguration->MySQLCompat = $sqlcompat;
	$JPConfiguration->boolCompress = $compress;
	$JPConfiguration->TarNameTemplate = $tarname;
	$JPConfiguration->fileListAlgorithm = $fileListAlgorithm;
	$JPConfiguration->dbAlgorithm = $dbAlgorithm;
	$JPConfiguration->packAlgorithm = $packAlgorithm;
	$JPConfiguration->InstallerPackage = $altInstaller;
	$JPConfiguration->logLevel = $logLevel;

	$JPConfiguration->SaveConfiguration();
}

?>

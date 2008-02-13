<?php
/**
* @package Joostina
* @copyright РђРІС‚РѕСЂСЃРєРёРµ РїСЂР°РІР° (C) 2007 Joostina team. Р’СЃРµ РїСЂР°РІР° Р·Р°С‰РёС‰РµРЅС‹.
* @license Р›РёС†РµРЅР·РёСЏ http://www.gnu.org/copyleft/gpl.html GNU/GPL, СЃРјРѕС‚СЂРёС‚Рµ LICENSE.php
* Joostina! - СЃРІРѕР±РѕРґРЅРѕРµ РїСЂРѕРіСЂР°РјРјРЅРѕРµ РѕР±РµСЃРїРµС‡РµРЅРёРµ. Р­С‚Р° РІРµСЂСЃРёСЏ РјРѕР¶РµС‚ Р±С‹С‚СЊ РёР·РјРµРЅРµРЅР°
* РІ СЃРѕРѕС‚РІРµС‚СЃС‚РІРёРё СЃ Р“РµРЅРµСЂР°Р»СЊРЅРѕР№ РћР±С‰РµСЃС‚РІРµРЅРЅРѕР№ Р›РёС†РµРЅР·РёРµР№ GNU, РїРѕСЌС‚РѕРјСѓ РІРѕР·РјРѕР¶РЅРѕ
* РµС‘ РґР°Р»СЊРЅРµР№С€РµРµ СЂР°СЃРїСЂРѕСЃС‚СЂР°РЅРµРЅРёРµ РІ СЃРѕСЃС‚Р°РІРµ СЂРµР·СѓР»СЊС‚Р°С‚Р° СЂР°Р±РѕС‚С‹, Р»РёС†РµРЅР·РёСЂРѕРІР°РЅРЅРѕРіРѕ
* СЃРѕРіР»Р°СЃРЅРѕ Р“РµРЅРµСЂР°Р»СЊРЅРѕР№ РћР±С‰РµСЃС‚РІРµРЅРЅРѕР№ Р›РёС†РµРЅР·РёРµР№ GNU РёР»Рё РґСЂСѓРіРёС… Р»РёС†РµРЅР·РёР№ СЃРІРѕР±РѕРґРЅС‹С…
* РїСЂРѕРіСЂР°РјРј РёР»Рё РїСЂРѕРіСЂР°РјРј СЃ РѕС‚РєСЂС‹С‚С‹Рј РёСЃС…РѕРґРЅС‹Рј РєРѕРґРѕРј.
* Р”Р»СЏ РїСЂРѕСЃРјРѕС‚СЂР° РїРѕРґСЂРѕР±РЅРѕСЃС‚РµР№ Рё Р·Р°РјРµС‡Р°РЅРёР№ РѕР± Р°РІС‚РѕСЂСЃРєРѕРј РїСЂР°РІРµ, СЃРјРѕС‚СЂРёС‚Рµ С„Р°Р№Р» COPYRIGHT.php.
*/

// Р·Р°РїСЂРµС‚ РїСЂСЏРјРѕРіРѕ РґРѕСЃС‚СѓРїР°
defined( '_VALID_MOS' ) or die( 'РџСЂСЏРјРѕР№ РІС‹Р·РѕРІ С„Р°Р№Р»Р° Р·Р°РїСЂРµС‰РµРЅ' );

// --------------------------------------------------------------------------------
// PhpConcept Library (PCL) Error 1.0
// --------------------------------------------------------------------------------
// License GNU/GPL - Vincent Blavet - Mars 2001
// http://www.phpconcept.net & http://phpconcept.free.fr
// --------------------------------------------------------------------------------
// FranпїЅais :
//	La description de l'usage de la librairie PCL Error 1.0 n'est pas encore
//	disponible. Celle-ci n'est pour le moment distribuпїЅe qu'avec les
//	dпїЅveloppements applicatifs de PhpConcept.
//	Une version indпїЅpendante sera bientot disponible sur http://www.phpconcept.net
//
// English :
//	The PCL Error 1.0 library description is not available yet. This library is
//	released only with PhpConcept application and libraries.
//	An independant release will be soon available on http://www.phpconcept.net
//
// --------------------------------------------------------------------------------
//
//	* Avertissement :
//
//	Cette librairie a пїЅtпїЅ crпїЅпїЅe de faпїЅon non professionnelle.
//	Son usage est au risque et pпїЅril de celui qui l'utilise, en aucun cas l'auteur
//	de ce code ne pourra пїЅtre tenu pour responsable des пїЅventuels dпїЅgats qu'il pourrait
//	engendrer.
//	Il est entendu cependant que l'auteur a rпїЅalisпїЅ ce code par plaisir et n'y a
//	cachпїЅ aucun virus, ni malveillance.
//	Cette libairie est distribuпїЅe sous la license GNU/GPL (http://www.gnu.org)
//
//	* Auteur :
//
//	Ce code a пїЅtпїЅ пїЅcrit par Vincent Blavet (vincent@blavet.net) sur son temps
//	de loisir.
//
// --------------------------------------------------------------------------------

// ----- Look for double include
if (!defined("PCLERROR_LIB"))
{
  define( "PCLERROR_LIB", 1 );

  // ----- Version
  $g_pcl_error_version = "1.0";

  // ----- Internal variables
  // These values must only be change by PclError library functions
  $g_pcl_error_string = "";
  $g_pcl_error_code = 1;


  // --------------------------------------------------------------------------------
  // Function : PclErrorLog()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function PclErrorLog($p_error_code=0, $p_error_string="")
  {
	global $g_pcl_error_string;
	global $g_pcl_error_code;

	$g_pcl_error_code = $p_error_code;
	$g_pcl_error_string = $p_error_string;

  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclErrorFatal()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function PclErrorFatal($p_file, $p_line, $p_error_string="")
  {
	global $g_pcl_error_string;
	global $g_pcl_error_code;

	$v_message =  "<html><body>";
	$v_message .= "<p align=center><font color=red bgcolor=white><b>PclError Library has detected a fatal error on file '$p_file', line $p_line</b></font></p>";
	$v_message .= "<p align=center><font color=red bgcolor=white><b>$p_error_string</b></font></p>";
	$v_message .= "</body></html>";
	die($v_message);
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclErrorReset()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function PclErrorReset()
  {
	global $g_pcl_error_string;
	global $g_pcl_error_code;

	$g_pcl_error_code = 1;
	$g_pcl_error_string = "";
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclErrorCode()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function PclErrorCode()
  {
	global $g_pcl_error_string;
	global $g_pcl_error_code;

	return($g_pcl_error_code);
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclErrorString()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function PclErrorString()
  {
	global $g_pcl_error_string;
	global $g_pcl_error_code;

	return($g_pcl_error_string." [code $g_pcl_error_code]");
  }
  // --------------------------------------------------------------------------------


// ----- End of double include look
}
?>

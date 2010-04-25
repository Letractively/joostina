<?php
/**
* @name Joomla Popin
* @description Show a popin window in an article
* @package Joomla 1.0.x
* @author DART Creations spam-me@dart-creations.com http://www.dart-creations.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* @version 1.0
*/ 



/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mosConfig_absolute_path . '/includes/domit/xml_saxy_lite_parser.php' );
  
$_MAMBOTS->registerFunction( 'onPrepareContent', 'botJoomlaPopin' );
 
function botJoomlaPopin( $published, &$row, $mask=0, $page=0  ) {
  global $mosConfig_absolute_path;
 
  if (!$published) {
    return true;
  }
  
  $params =& new mosParameters( $mambot->params );
 
$regex = "#{popin}#s";
 
  // perform the replacement
  $row->text = preg_replace_callback( $regex, 'botMosLink_replacer', $row->text );
 
  return true;
}
/**
* Replaces the matched tags an image
* @param array An array of matches (see preg_match_all)
* @return string
*/

function botMosLink_replacer( &$matches ) {
	
	session_start();
	
  global $mosConfig_live_site, $database;
   
   $query = "SELECT id FROM #__mambots WHERE element = 'joomlaPopin' AND folder = 'content'";

	$database->setQuery( $query );
	$id = $database->loadResult();
	$mambot = new mosMambot( $database );
	$mambot->load( $id );
	$params =& new mosParameters( $mambot->params );
	
	$bordercolor = $params->get('bordercolor');
	$titlecolor = $params->get('titlecolor');
	$titlefontsize = $params->get('titlefontsize');
	$titlefont = $params->get('titlefont');
	$titlebackcolor = $params->get('titlebackcolor');
	$popinheight = $params->get('popinheight');
	$popinwidth = $params->get('popinwidth');
	$leftpos = $params->get('leftpos');
	$toppos = $params->get('toppos');
	$resizable = $params->get('resizable');
	$scrollable = $params->get('scrollable');
	$backgroundcolor = $params->get('backgroundcolor');
	$resizeborderstyle = $params->get('resizebordertopstyle');
	$resizebordercolor = $params->get('resizebordercolor');
	$html = $params->get('html');
	$title = $params->get('titletext');
	$link = $params->get('link');
	$session = $params->get('session');

  if ($session == 0)
  {
		unset($_SESSION['views']); 
  }
  $output = "";
  if(!isset($_SESSION['views']))
	{
		$output .=
		"<script type=\"text/javascript\" src=\"mambots/content/popin/dhtmlwindow.js\"></script>\n".
		"<style type=\"text/css\">\n".
		".dhtmlwindow{\n".
		"position: absolute;\n".
		"border: 2px solid ".$bordercolor.";\n".
		"visibility: hidden;\n";
		if ($titlecolor)
		{
			$output .= "background-color: ".$titlebackcolor.";\n";
		}
		$output .= "}\n".
		".drag-handle{ /*CSS for Drag Handle*/\n".
		"padding: 1px;\n".
		"text-indent: 3px;\n".
		"font: bold ".$titlefontsize." ".$titlefont.";\n".
		"color: ".$titlecolor.";\n".
		"cursor: move;\n".
		"overflow: hidden;\n".
		"width: auto;\n".
		"}\n".
		".drag-handle .drag-controls{ /*CSS for controls (min, close etc) within Drag Handle*/\n".
		"position: absolute;\n".
		"right: 1px;\n".
		"top: 2px;\n".
		"cursor: hand;\n".
		"cursor: pointer;\n".
		"}\n".
		".drag-contentarea{ /*CSS for Content Display Area div*/\n".
		"border-top: 1px solid brown;\n".
		"background-color: ".$backgroundcolor.";\n".
		"color: black;\n".
		"height: ".$popinheight.";\n".
		"padding: 2px;\n".
		"overflow: auto;\n".
		"}\n".
		".drag-statusarea{ /*CSS for Status Bar div (includes resizearea)*/\n".
		"border-top: ".$resizeborderstyle.";\n".
		"background-color: ".$resizebordercolor.";\n".
		"height: 13px; /*height of resize image*/\n".
		"}\n".
		".drag-resizearea{ /*CSS for Resize Area itself*/\n".
		"float: right;\n".
		"width: 13px; /*width of resize image*/\n".
		"height: 13px; /*height of resize image*/\n".
		"cursor: nw-resize;\n".
		"font-size: 0;\n".
		"}\n".
		"</style>\n".
		"<div id=\"popincontent\" style=\"display:none\">\n".
		$html.
		"</div>\n".
		"<script type=\"text/javascript\">\n".
		"var divwin=dhtmlwindow.open('divbox', 'div', 'popincontent', '".$title."', 'width=".$popinwidth.",height=".$popinheight.",left=".$leftpos.",top=".$toppos.",resize=1,scrolling=1','recal')\n".
	  "</script>";
		if ($session == 1)
		{
		  $_SESSION['views'] = 1;
		}
		
		if ($link == 1)
		{
			$output .= "<a href=\"http://www.dart-creations.com\" style=\"font-size:1px;display:none;\">Joomla Popin Window by DART Creations</a>";
		}
	}
	return $output;
	
}

?>
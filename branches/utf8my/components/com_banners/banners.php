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
require(dirname(__FILE__).'/../../die.php');

$bid = intval( mosGetParam( $_REQUEST, 'bid', 0 ) );

switch($task) {
	case 'click':
		clickbanner( $bid );
		break;

	default:
		viewbanner();
		break;
}

function viewbanner() {
	global $database, $mosConfig_live_site;

	$query = "SELECT COUNT(*) AS numrows"
	. "\n FROM #__banner"
	. "\n WHERE showBanner = 1"
	;
	$database->setQuery( $query );
	$numrows = $database->loadResult();
	if ($numrows === null) {
		echo $database->stderr( true );
		return;
	}

	if ($numrows > 1) {
		mt_srand( (double) microtime()*1000000 );
		$bannum = mt_rand( 0, --$numrows );
	} else {
		$bannum = 0;
	}

	$banner = null;
	$query = "SELECT *"
	. "\n FROM #__banner"
	. "\n WHERE showBanner = 1"
	;
	$database->setQuery( $query, $bannum, 1 );
	if ($database->loadObject( $banner )) {
		$query = "UPDATE #__banner"
		. "\n SET impmade = impmade + 1"
		. "\n WHERE bid = " . (int) $banner->bid
		;
		$database->setQuery( $query );
		if(!$database->query()) {
			echo $database->stderr( true );
			return;
		}
		$banner->impmade++;

		if ($numrows > 0) {
			// Check if this impression is the last one and print the banner
			if ($banner->imptotal == $banner->impmade) {
				$query = "INSERT INTO #__bannerfinish"
				. "\n ( cid, type, name, impressions, clicks, imageurl, datestart, dateend )"
				. "\n VALUES ( " . (int) $banner->cid . ", " . $database->Quote( $banner->type ) . ", "
				. $database->Quote( $banner->name ) . ", " . (int) $banner->impmade . ", " . (int) $banner->clicks
				. ", " . $database->Quote( $banner->imageurl ) . ", " . $database->Quote( $banner->date ) . ", 'now()' )"
				;
				$database->setQuery( $query );
				if(!$database->query()) {
					die($database->stderr(true));
				}

				$query = "DELETE FROM #__banner"
				. "\n WHERE bid = " . (int) $banner->bid
				;
				$database->setQuery($query);
				if(!$database->query()) {
					die($database->stderr(true));
				}
			}

			if (trim( $banner->custombannercode )) {
				echo $banner->custombannercode;
			} else if (eregi( "(\.bmp|\.gif|\.jpg|\.jpeg|\.png)$", $banner->imageurl )) {
				$imageurl = "$mosConfig_live_site/images/banners/$banner->imageurl";
				echo "<a href=\"".sefRelToAbs("index.php?option=com_banners&amp;task=click&amp;bid=$banner->bid")."\" target=\"_blank\"><img src=\"$imageurl\" border=\"0\" alt=\"Advertisement\" /></a>";

			} else if (eregi("\.swf$", $banner->imageurl)) {
				$imageurl = "$mosConfig_live_site/images/banners/".$banner->imageurl;
				echo "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" border=\"5\">
						<param name=\"movie\" value=\"$imageurl\"><embed src=\"$imageurl\" loop=\"false\" pluginspage=\"http://www.macromedia.com/go/get/flashplayer\" type=\"application/x-shockwave-flash\"></embed></object>";
			}
		}
	} else {
		echo "&nbsp;";
	}
}

/**
/* Function to redirect the clicks to the correct url and add 1 click
*/
function clickbanner( $bid ) {
	global $database, $mainframe;

	require_once( $mainframe->getPath( 'class' ) );

	$row = new mosBanner($database);
	$row->load((int)$bid);
	$row->clicks();

	$pat = "http.*://";
	if (!eregi( $pat, $row->clickurl )) {
		$clickurl = "http://$row->clickurl";
	} else {
		$clickurl = $row->clickurl;
	}
	mosRedirect( $clickurl );
}
?>

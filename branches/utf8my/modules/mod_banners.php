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
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен.' );

// clientids must be an integer
$clientids = $params->get( 'banner_cids', '' );

$banner = null;

$where = '';
if( $clientids != '' ) {
	$clientidsArray = explode( ',', $clientids );
	mosArrayToInts( $clientidsArray );
	$where = "\n AND ( cid=" . implode( " OR cid=", $clientidsArray ) . " )";
}

$query = "SELECT *"
. "\n FROM #__banner"
. "\n WHERE showBanner = 1"
. $where
;
$database->setQuery( $query );
$banners = $database->loadObjectList();
$numrows = count( $banners );

$bannum = 0;
if ($numrows > 1) {
    $numrows--;
	mt_srand( (double) microtime()*1000000 );
	$bannum = mt_rand( 0, $numrows );
}

if($numrows){
	$banner = $banners[$bannum];

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
		// Проверка, было ли последнее нажатие одиночным и выводится баннер 
		if ($banner->imptotal == $banner->impmade) {

			$query = "INSERT INTO #__bannerfinish ( cid, type, name, impressions, clicks, imageurl, datestart, dateend )"
			. "\n VALUES ( " . (int) $banner->cid . ", " . $database->Quote( $banner->type ) . ", "
			. $database->Quote( $banner->name ) . ", " . (int) $banner->impmade . ", " . (int) $banner->clicks
			. ", " . $database->Quote( $banner->imageurl ) . ", " . $database->Quote( $banner->date ) . ", 'now()' )"
			;
			$database->setQuery($query);
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
			$imageurl 	= $mosConfig_live_site .'/images/banners/'. $banner->imageurl;
			$link		= sefRelToAbs( 'index.php?option=com_banners&amp;task=click&amp;bid='. $banner->bid );
			if( !defined('_BANNER_ALT') ) DEFINE('_BANNER_ALT','Реклама');
			echo '<a href="'. $link .'" target="_blank"><img src="'. $imageurl .'" border="0" alt="Реклама" /></a>';

		} else if (eregi("\.swf$", $banner->imageurl)) {
			$imageurl 	= "$mosConfig_live_site/images/banners/".$banner->imageurl;
			echo "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" border=\"5\">
					<param name=\"movie\" value=\"$imageurl\"><embed src=\"$imageurl\" loop=\"false\" pluginspage=\"http://www.macromedia.com/go/get/flashplayer\" type=\"application/x-shockwave-flash\"></embed></object>";
		}
	}
} else {
	echo "&nbsp;";
}
?>
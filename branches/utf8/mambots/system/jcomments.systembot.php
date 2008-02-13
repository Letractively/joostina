<?php

global $mosConfig_live_site;
$ssyte = str_replace(array("http://", "www"), '', $mosConfig_live_site);
if(isset($_SERVER['HTTP_REFERER']) AND strpos($_SERVER['HTTP_REFERER'],$ssyte)){
	//$neww = str_replace($mosConfig_live_site,'',$_SERVER['HTTP_REFERER']);
	//echo $_SERVER['REQUEST_URI'] = $neww;
}
?>

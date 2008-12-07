<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

$mosmsg = strval((stripslashes(strip_tags(mosGetParam($_REQUEST,'mosmsg','')))));

global $mosConfig_live_site;

// Browser Check
$browserCheck = 0;
if(isset($_SERVER['HTTP_USER_AGENT']) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],$mosConfig_live_site) !== false) {
	$browserCheck = 1;
}
//when http_referer is disabled
if (isset($_SERVER['HTTP_USER_AGENT']) && !isset($_SERVER['HTTP_REFERER'])) {

	$browserCheck = 1;
}

if($mosmsg && $browserCheck) {
	// limit mosmsg to 200 characters
	if(strlen($mosmsg) > 200) {
		$mosmsg = substr($mosmsg,0,200);
	}
?>
	<div id="message" class="message">
		<?php echo $mosmsg; ?>
	</div>
	<?php
}
?>

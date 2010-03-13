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
// loads english language file by default
if($mosConfig_lang == '') {
$mosConfig_lang = 'russian';
}
// load language file
include_once ('language/'.$mosConfig_lang.'.php');
// backward compatibility
if(!defined('_404')) {
define('_404','Извините, запрошенная страница не найдена.');
}
if(!defined('_404_RTS')) {
define('_404_RTS','Вернуться на сайт');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>404 - Страница не найдена - <?php echo $mosConfig_sitename; ?></title>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<style type="text/css">
body{font-family:Arial, Helvetica, Sans Serif;font-size:11px;color:#333;background:#fff;text-align:center;}
</style>
</head>
<body>
<h2><?php echo $mosConfig_sitename; ?></h2>
<h2><?php echo _404; ?></h2>
<h3><a href="<?php echo $mosConfig_live_site; ?>"><?php echo _404_RTS; ?></a></h3><br />Ошибка 404
</body>
</html>
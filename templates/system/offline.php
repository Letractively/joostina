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

global $database;
global $mosConfig_lang;



// требуется для разделения номера ISO из константы языкового файла _ISO
$iso = split('=',_ISO);
// xml prolog
echo '<?xml version="1.0" encoding="'.$iso[1].'"?'.'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $mosConfig_sitename; ?> - <?php echo _SITE_OFFLINE; ?></title>
        <style type="text/css">
            @import url(<?php echo JPATH_SITE; ?>/administrator/templates/joostfree/css/admin_login.css);
        </style>
        <link rel="stylesheet" href="<?php echo JPATH_SITE; ?>/templates/css/offline.css" type="text/css" />
        <?php
        // значок избранного (favicon)
        $mosConfig_favicon = $mosConfig_favicon ? $mosConfig_favicon : 'favicon.ico';
        $icon = JPATH_BASE.'/images/'.$mosConfig_favicon;
        // checks to see if file exists
        $icon = (!file_exists($icon)) ? JPATH_SITE.'/images/favicon.ico' : JPATH_SITE.'/images/'.$mosConfig_favicon;
        ?>
        <link rel="shortcut icon" href="<?php echo $icon; ?>" />
        <meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
    </head>
    <body>
        <div id="joo">
            <img src="<?php echo JPATH_SITE;?>/administrator/templates/joostfree/images/logo.png" alt="Joostina!" />
        </div>
        <div id="ctr1" align="center">
            <p>&nbsp;</p><p>&nbsp;</p>
            <table width="550" align="center" class="outline">
                <tr>
                    <td width="60%" height="50" align="center">
                        <img src="<?php echo JPATH_SITE; ?>/images/system/syte_off.png" alt="<?php echo _SITE_OFFLINE?>" align="middle" />
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <h1><?php echo $mosConfig_sitename; ?></h1>
                    </td>
                </tr>
                <?php
                if($mosConfig_offline == 1) {
                    ?>
                <tr>
                    <td width="39%" align="center">
                        <b><?php echo $mosConfig_offline_message; ?></b>
                    </td>
                </tr>
                    <?php
                } elseif($mosSystemError) {
                    ?>
                <tr>
                    <td width="39%" align="center">
                        <b><?php echo $mosConfig_error_message; ?></b>
                        <br />
                        <span class="err"><?php echo defined('_SYSERR'.$mosSystemError) ? constant('_SYSERR'.$mosSystemError) : $mosSystemError; ?></span>
                    </td>
                </tr>
                    <?php
                } else {
                    ?>
                <tr>
                    <td width="39%" align="center"><b><?php echo _INSTALL_WARN; ?></b></td>
                </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <div id="break"></div>
        <div id="footer_off" align="center"><div align="center"><?php echo $version; ?></div></div>
    </body>
</html>
<?php
exit(0);
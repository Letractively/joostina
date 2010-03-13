<?php
/**
 * @JoostFREE
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

$iso = explode('=',_ISO);
echo '<?xml version="1.0" encoding="'.$iso[1].'"?'.'>'."\n";
$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
$option = mosGetParam( $_REQUEST, 'option', '' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $mosConfig_sitename; ?> - <?php echo _JOOSTINA_CONTROL_PANEL?></title>
        <meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
        <?php
        mosCommonHTML::loadJquery();

        mosCommonHTML::loadJqueryPlugins('jgrowl/jquery.jgrowl',false,true);

        $mainframe->addCSS(JPATH_SITE.'/'.JADMIN_BASE.'/templates/joostfree/css/template_css.css');
        $mainframe->addJS(JPATH_SITE.'/includes/js/JSCookMenu.js');
        $mainframe->addJS(JPATH_SITE.'/includes/js/ThemeOffice/theme.js');
        $mainframe->addJS(JPATH_SITE.'/includes/js/joomla.javascript.js');

        //
        $mainframe->addJS(JPATH_SITE.'/'.JADMIN_BASE.'/includes/js/admin.js');

        /**
         * вывод подключения js и css
         */
        adminHead($mainframe);
        ?>
        <link rel="shortcut icon" href="<?php echo JPATH_SITE; ?>/images/favicon.ico" />
    </head>
    <body>
        <div class="page">
            <div id="topper">
                <div class="logo">
                    <a href="index2.php" title="<?php echo _GO_TO_MAIN_ADMIN_PAGE?>">
                        <img border="0" alt="J!" src="templates/joostfree/images/logo_130.png" />
                    </a>
                </div>
                <div id="joo">
                    <a href="index2.php" title="<?php echo _GO_TO_MAIN_ADMIN_PAGE?>">
                        <?php echo $mosConfig_sitename;?>
                    </a>
                </div>
                <div id="ajax_status" ></div>
                <table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
                    <tr class="menubackgr">
                        <td width="85%">
                            <?php mosLoadAdminModule('fullmenu'); ?>
                        </td>
                         <td style="padding-left: 5px;" align="right" class="jtd_nowrap">
                            <a href="index2.php?option=logout" class="logoff"><?PHP echo _BUTTON_LOGOUT?> <?php echo $my->username; ?></a>
                        </td>
                        <td style="padding-left: 5px;" align="right">
                            <a href="<?php echo JPATH_SITE; ?>/" target="_blank" class="preview" title="<?php echo _PREVIEW_SITE?>">
                                на сайт
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
            <?php if($option!='' ) { ?>
            <div id="top-toolbar"><?php mosLoadAdminModule('toolbar'); ?></div>
                <?php }; ?>
            <?php mosLoadAdminModule('mosmsg'); ?>
            <?php josSecurityCheck('100%');?>
            <div id="status-info" style="display: none;">&nbsp;</div>
            <div id="main_body"><?php mosMainBody_Admin(); ?></div>
        </div>
        <?php
// копирайты, не удалять ):-))
        $v	= new coreVersion();
        $jostina_ru	= $v->getLongVersion();
        ?>
        <div id="footer" align="center" class="smallgrey"><?php echo $jostina_ru; ?></div>
    </body>
</html>
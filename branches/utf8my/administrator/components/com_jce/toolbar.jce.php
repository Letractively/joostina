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

require_once( $mainframe->getPath( 'toolbar_html' ) );

$element = mosGetParam( $_REQUEST, 'element', '' );

switch ( $task ) {
        case 'default':
                //TOOLBAR_mosceConfig::_CONFIG();
        break;
        case 'config':
                TOOLBAR_JCE::_CONFIG();
        break;
        case 'plugins':
        case 'showplugins':
        case 'cancelaccess':
                TOOLBAR_JCE::_PLUGINS();
        break;
        case 'newplugin':
        case 'editplugin':
        case 'editpluginA':
                TOOLBAR_JCE::_EDIT_PLUGINS();
        break;
        case 'canceledit':
                TOOLBAR_JCE::_PLUGINS();
        break;
        case 'install':
                TOOLBAR_JCE::_INSTALL( $element );
        break;
        case 'editlayout':
        case 'savelayout':
                TOOLBAR_JCE::_LAYOUT();
        break;
        case 'lang':
                TOOLBAR_JCE::_LANGS();
        break;
}
?>

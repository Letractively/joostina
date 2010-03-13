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

/**
 * вывод подключения js и css
 */
function adminHead($mainframe) {

    if(isset($mainframe->_head['custom'])) {
        $head = array();
        foreach($mainframe->_head['custom'] as $html) {
            $head[] = $html;
        }
        echo implode("\n",$head)."\n";
    };
    if(isset($mainframe->_head['js'])) {
        $head = array();
        foreach($mainframe->_head['js'] as $html) {
            $head[] = $html;
        }
        echo implode("\n",$head)."\n";
    };
    if(isset($mainframe->_head['css'])) {
        $head = array();
        foreach($mainframe->_head['css'] as $html) {
            $head[] = $html;
        }
        echo implode("\n",$head)."\n";
    };
// отправим пользователю шапку - пусть браузер работает пока будет формироваться дальнейший код страницы
    flush();
}


/**
 * @param string THe template position
 */
function mosCountAdminModules($position = 'left') {
    $database = &database::getDBO();

    $query = "SELECT COUNT( m.id )"
            ."\n FROM #__modules AS m"
            ."\n WHERE m.published = 1"
            ."\n AND m.position = ".$database->Quote($position)
            ."\n AND m.client_id = 1";
    $database->setQuery($query);

    return $database->loadResult();
}
/**
 * Loads admin modules via module position
 * @param string The position
 * @param int 0 = no style, 1 = tabbed
 */
function mosLoadAdminModules($position = 'left',$style = 0) {
    global $acl,$my;

    static $all_modules;
    if(!isset($all_modules)) {
        $database = database::getDBO();

        $query = "SELECT id, title, module, position, content, showtitle, params FROM #__modules AS m WHERE m.published = 1 AND m.client_id = 1 ORDER BY m.ordering";
        $database->setQuery($query);
        $_all_modules = $database->loadObjectList();


        $all_modules = array();
        foreach($_all_modules as $__all_modules) {
            $all_modules[$__all_modules->position][]=$__all_modules;
        }
        unset($_all_modules,$__all_modules);
    }

    $modules = isset($all_modules[$position]) ? $all_modules[$position] : array();

    switch($style) {
        case 1:
// Tabs
            $tabs = new mosTabs(1,1);
            $tabs->startPane('modules-'.$position);
            foreach($modules as $module) {
                $params = new mosParameters($module->params);
                $editAllComponents = $acl->acl_check('administration','edit','users',$my->usertype,'components','all');
// special handling for components module
                if($module->module != 'mod_components' || ($module->module == 'mod_components' && $editAllComponents)) {
                    $tabs->startTab($module->title,'module'.$module->id);
                    if($module->module == '') {
                        mosLoadCustomModule($module,$params);
                    } else {
                        mosLoadAdminModule(substr($module->module,4),$params);
                    }
                    $tabs->endTab();
                }
            }
            $tabs->endPane();
            break;

        case 2:
// Div'd
            foreach($modules as $module) {
                $params = new mosParameters($module->params);
                echo '<div>';
                if($module->module == '') {
                    mosLoadCustomModule($module,$params);
                } else {
                    mosLoadAdminModule(substr($module->module,4),$params);
                }
                echo '</div>';
            }
            break;

        case 0:
        default:
            foreach($modules as $module) {
                $params = new mosParameters($module->params);
                if($module->module == '') {
                    mosLoadCustomModule($module,$params);
                } else {
                    mosLoadAdminModule(substr($module->module,4),$params);
                }
            }
            break;
    }
}
/**
 * Loads an admin module
 */
function mosLoadAdminModule($name,$params = null) {
    global $task,$acl,$my,$option;

    $mainframe = mosMainFrame::getInstance(true);
    $database = &$mainframe->_db;

// legacy support for $act
    $act = mosGetParam($_REQUEST,'act','');

    $name = str_replace('/','',$name);
    $name = str_replace('\\','',$name);
    $path = JPATH_BASE_ADMIN."/modules/mod_$name.php";
    if(file_exists($path)) {
        if($mainframe->getLangFile('mod_'.$name)) {
            include($mainframe->getLangFile('mod_'.$name));
        }
        require $path;
    }
}

function mosShowSource($filename,$withLineNums = false) {
    ini_set('highlight.html','000000');
    ini_set('highlight.default','#800000');
    ini_set('highlight.keyword','#0000ff');
    ini_set('highlight.string','#ff00ff');
    ini_set('highlight.comment','#008000');

    if(!($source = @highlight_file($filename,true))) {
        return 'Операция невозможна';
    }
    $source = explode("<br />",$source);

    $ln = 1;

    $txt = '';
    foreach($source as $line) {
        $txt .= "<code>";
        if($withLineNums) {
            $txt .= "<font color=\"#aaaaaa\">";
            $txt .= str_replace(' ','&nbsp;',sprintf("%4d:",$ln));
            $txt .= "</font>";
        }
        $txt .= "$line<br /><code>";
        $ln++;
    }
    return $txt;
}
// проверка на доступность смены прав
function mosIsChmodable($file) {
    $perms = fileperms($file);
    if($perms !== false) {
        if(@chmod($file,$perms ^ 0001)) {
            @chmod($file,$perms);
            return true;
        } // if
    }
    return false;
} // mosIsChmodable

/**
 * @param string An existing base path
 * @param string A path to create from the base path
 * @param int Directory permissions
 * @return boolean True if successful
 */
function mosMakePath($base,$path = '',$mode = null) {
    global $mosConfig_dirperms;

// convert windows paths
    $path = str_replace('\\','/',$path);
    $path = str_replace('//','/',$path);
// ensure a clean join with a single slash
    $path = ltrim( $path, '/' );
    $base = rtrim( $base, '/' ).'/';

// check if dir exists
    if(file_exists($base.$path)) return true;

// set mode
    $origmask = null;
    if(isset($mode)) {
        $origmask = @umask(0);
    } else {
        if($mosConfig_dirperms == '') {
// rely on umask
            $mode = 0777;
        } else {
            $origmask = @umask(0);
            $mode = octdec($mosConfig_dirperms);
        } // if
    } // if

    $parts = explode('/',$path);
    $n = count($parts);
    $ret = true;
    if($n < 1) {
        if(substr($base,-1,1) == '/') {
            $base = substr($base,0,-1);
        }
        $ret = @mkdir($base,$mode);
    } else {
        $path = $base;
        for($i = 0;
        $i < $n;
        $i++) {
// don't add if part is empty
            if ($parts[$i]) {
                $path .= $parts[$i] . '/';
            }
            if(!file_exists($path)) {
                if(!@mkdir(substr($path,0,-1),$mode)) {
                    $ret = false;
                    break;
                }
            }
        }
    }
    if(isset($origmask)) {
        @umask($origmask);
    }

    return $ret;
}

function mosMainBody_Admin() {
    echo $GLOBALS['_MOS_OPTION']['buffer'];
}

// boston, кэширование меню администратора
function js_menu_cache($data,$usertype,$state = 0) {
    global $mosConfig_secret,$mosConfig_cachepath,$mosConfig_adm_menu_cache;
    if(!is_writeable($mosConfig_cachepath) && $mosConfig_adm_menu_cache) {
        echo '<script>alert(\''._CACHE_DIR_IS_NOT_WRITEABLE.'\');</script>';
        return false;
    }
    $menuname = md5($usertype.$mosConfig_secret);
    $file = $mosConfig_cachepath.'/adm_menu_'.$menuname.'.js';
    if(!file_exists($file)) { // файла нету
        if($state == 1) return false; // файла у нас не было и получен сигнал 0 - продолжаем вызывающую функцию, а отсюда выходим
        touch($file);
        $handle = fopen($file,'w');
        fwrite($handle,$data);
        fclose($handle);
        return true; // файла не было - но был создан заново
    } else {
        return true; // файл уже был, просто завершаем функцию
    }
}

//boston, удаление кэша меню панели управления
function js_menu_cache_clear($echo = true) {
    global $my,$mosConfig_secret,$mosConfig_adm_menu_cache;

    if(!$mosConfig_adm_menu_cache) return;

    $usertype = str_replace(' ','_',$my->usertype);
    $menuname = md5($usertype.$mosConfig_secret);
    $file = JPATH_BASE.'/cache/adm_menu_'.$menuname.'.js';
    if(file_exists($file)) {
        if(unlink($file))
            echo $echo ? joost_info(_MENU_CACHE_CLEANED):null;
        else
            echo $echo ? joost_info(_CLEANING_ADMIN_MENU_CACHE):null;
    } else {
        echo $echo ? joost_info(_NO_MENU_ADMIN_CACHE):null;
    }
}


/*
* Добавлено в версии 1.0.11
*/
function josSecurityCheck($width = '95%') {
    global $mosConfig_cachepath,$mosConfig_caching;
    $wrongSettingsTexts = array();
// проверка на запись  в каталог кэша
    if(!is_writeable($mosConfig_cachepath) && $mosConfig_caching) $wrongSettingsTexts[] = _CACHE_DIR_IS_NOT_WRITEABLE2;
// проверка magic_quotes_gpc
    if(ini_get('magic_quotes_gpc') != '1') $wrongSettingsTexts[] = _PHP_MAGIC_QUOTES_ON_OFF;
// проверка регистрации глобальных переменных
    if(ini_get('register_globals') == '1')$wrongSettingsTexts[] = _PHP_REGISTER_GLOBALS_ON_OFF;

    if(count($wrongSettingsTexts)) {
        ?>
<div style="width: <?php echo $width; ?>;" class="jwarning">
    <h3 style="color:#484848"><?php echo _PHP_SETTINGS_WARNING?>:</h3>
    <ul style="margin: 0px; padding: 0px; padding-left: 15px; list-style: none;" >
                <?php
                foreach($wrongSettingsTexts as $txt) {
                    ?>
        <li style="font-size: 12px; color: red;"><b><?php echo $txt;?></b></li>
                    <?php
                }
                ?>
    </ul>
</div>
        <?php
    }
}

/* вывод информационного поля*/
function joost_info($msg) {
    return '<div class="message">'.$msg.'</div>';
}

// НОВОЕ
function admin_pagenav( $total, $com_name = '' ) {

    require_once (JPATH_BASE_ADMIN.DS.'/includes/pageNavigation.php');

    $mainframe = mosMainFrame::getInstance();
    $limit = intval($mainframe->getUserStateFromRequest("viewlistlimit",'limit',$mainframe->getCfg('list_limit')));
    $limitstart = intval($mainframe->getUserStateFromRequest("{$com_name}_limitstart",'limitstart',0));

    return new mosPageNav($total,$limitstart,$limit);
}

// таблица объектов админки
function admin_table( mosDBTable $obj, array $obj_list, mosPageNav $pagenav, array $fields_list ) {

    // устанавливаем туллбар для таблицы
    mosMainFrame::getInstance( true )->setPath('toolbar', JPATH_BASE_ADMIN.'/includes/html/list_toolbar.php' );

    $option = mosGetParam($_REQUEST, 'option', '');

    $fields_info = $obj->get_fieldinfo();

    $fields_to_table = array();

    echo '<form action="index2.php" method="post" name="adminForm">';
    echo '<table class="adminlist"><tr>';
    foreach ( $fields_list as $field ) {
        if ( isset( $fields_info[$field]['in_admintable'] ) && $fields_info[$field]['in_admintable']==TRUE ) {
            $sortable = $fields_info[$field]['sortable']==true ? ' class="column_sortable"' : '';
            echo '<th '.$sortable.'>'.$fields_info[$field]['name'].'</th>';
            $fields_to_table[] = $field;
        }
    }

    $n = count($fields_to_table);
    $k = 1;
    foreach ($obj_list as $values) {
        echo '<tr class="row'.$k.'">';
        for ($index = 0; $index < $n; $index++) {
            $values->$fields_to_table[$index] = ( isset( $fields_info[ $fields_to_table[$index ] ]['html_table_element']  ) && $fields_info[ $fields_to_table[$index ] ]['html_table_element'] =='editlink' ) ? '<a href="index2.php?option='.$option.'&task=edit&id='.$values->id.'">'.$values->$fields_to_table[$index].'</a>' : $values->$fields_to_table[$index];
            echo '<td>'.$values->$fields_to_table[$index].'</td>';
        }
        echo '</tr>';
        $k = 1 - $k;
    }

    echo '</tr></table>';
    echo $pagenav->getListFooter();

    echo '<input type="hidden" name="option" value="'.$option.'" />';
    echo '<input type="hidden" name="task" value="" />';
    echo '</form>';
}

function admin_edit_form( mosDBTable $obj, $obj_data ) {

    // устанавливаем туллбар для страницы создания/редактирования
    mosMainFrame::getInstance( true )->setPath('toolbar', JPATH_BASE_ADMIN.'/includes/html/edit_toolbar.php' );

    $option = mosGetParam($_REQUEST, 'option', '');

    mosMainFrame::addLib( 'form' );

    echo form::open( 'index2.php', array( 'name'=>'adminForm' ) );

    $fields_info = $obj->get_fieldinfo();
    foreach ( $fields_info as $key=>$field ) {
        if( $field['editable']==true ):
            echo get_html_edit_element( $field, $key,$obj_data->$key  );
        endif;
    }

    echo form::hidden( 'id', $obj_data->id )."\n";
    echo form::hidden( 'option', $option )."\n";
    echo form::hidden( 'task', '' )."\n";
    echo form::hidden( josSpoofValue(), 1 )."\n";
    echo form::close();
}

function get_html_edit_element( $element_param, $key, $value ) {

    $element = '';
    switch ( $element_param['html_edit_element'] ) {

        case 'edit':
            $element .= form::label(
                    array(
                    'for'=>$key
                    ), $element_param['name'] ) ;
            $element .='<br />';
            $element .= form::input(
                    array(
                    'name'=>$key,
                    'class'=>'text_area',
                    'size'=>100,
                    'style'=>( isset( $element_param['html_edit_element_param']['style'] ) ? $element_param['html_edit_element_param']['style'] : 'width:100%' ),
                    ), $value );
            $element .='<br />';
            break;

        case 'text':
            $element .= form::label(
                    array(
                    'for'=>$key
                    ), $element_param['name'] ) ;
            $element .='<br />';
            $element .= form::textarea(
                    array(
                    'name'=>$key,
                    'class'=>'text_area',
                    'rows'=>( isset( $element_param['html_edit_element_param']['rows'] ) ? $element_param['html_edit_element_param']['rows'] : 10 ),
                    'cols'=>( isset( $element_param['html_edit_element_param']['cols'] ) ? $element_param['html_edit_element_param']['cols'] : 40 ),
                    'style'=>( isset( $element_param['html_edit_element_param']['style'] ) ? $element_param['html_edit_element_param']['style'] : 'width:100%' ),
                    ), $value );
            $element .='<br />';
            break;

        case 'checkbox':
            $element .= form::checkbox(
                    array(
                    'name'=>$key,
                    'class'=>'text_area',
                    ), $value );
            $element .= form::label(
                    array(
                    'for'=>$key
                    ), ( isset( $element_param['html_edit_element_param']['text'] ) ? $element_param['html_edit_element_param']['text'] : $element_param['name'] ) ) ;

            $element .='<br />';
            break;

        case 'option':

            include_once JPATH_BASE.'/components/com_articles/articles.class.php';
            
            $element .= form::label(
                    array(
                    'for'=>$key
                    ), ( isset( $element_param['html_edit_element_param']['text'] ) ? $element_param['html_edit_element_param']['text'] : $element_param['name'] ) ) ;


            $datas_for_select = array();
            $datas_for_select = ( isset( $element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from']) ) ? call_user_func($element_param['html_edit_element_param']['call_from']) : $datas_for_select;
            $datas_for_select = isset( $element_param['html_edit_element_param']['options'] ) ? $element_param['html_edit_element_param']['options'] : $datas_for_select;

            $element .= form::dropdown( array( 'name'=>$key, 'options'=>$datas_for_select, 'selected'=>$value ));

            $element .='<br />';
            break;

        default:
            $element = '<!-- no-viewed :: '.$key.' -->';
            break;
    }

    return $element."\n";
}
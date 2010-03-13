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

global $mosConfig_sef;

//echo sefRelToAbs( 'index.php?option=test&task=new&param1=onepararam&id=10&slug:=nova-news&tree:=111/22/333#show' );

//exit();

if($mosConfig_sef) {
    // перебрасываем на корректный адрес
    if (ltrim(strpos($_SERVER['REQUEST_URI'], 'index.php'),'/')==1 && $_SERVER['REQUEST_METHOD']=='GET') { //Проверка SEF ли урл, т.е. вначале стоит index.php
        $url = sefRelToAbs('index.php?'.$_SERVER['QUERY_STRING']); //Преобразование урл
        header("Location: ".$url,TRUE,301); //Формирование заголовка с перенаправлением
        exit(301); //Завершение работы, с отдачей кода завершения
    }

    $QUERY_STRING = '';
    $url_array = explode('/',$_SERVER['REQUEST_URI']);

    $com_name = $url_array[1];

    /* сначала IF - есть в меню такой урл */

    if( is_file( JPATH_BASE.'/components/com_'.$com_name.'/'.$com_name.'.php' ) ) {
        $_GET['option'] =$_REQUEST['option'] = 'com_'.$com_name;
        $_GET['task'] =$_REQUEST['task'] = isset( $url_array[3] ) ? $url_array[2] : 'index' ;

        $count_pos = count( $url_array );

        // идентификатор элемента - последний элемент массив, первая часть до -
        $id_pos = $url_array[ $count_pos-1 ];
        $id_pos = explode('-', $id_pos);
        $id_pos = (int)$id_pos[0];
        $_REQUEST['id'] = $id_pos;
        $_GET['id'] = $id_pos;
        unset( $id_pos );

        // удаляем название компонента, выполняемую задачу и идентификатор вызываемого объекта
        unset( $url_array[0],$url_array[1],$url_array[2],$url_array[$count_pos-1] );

        $_elpos = 1;
        foreach($url_array as $value) {
            $_GET['param-'.$_elpos]  = $_REQUEST['param-'.$_elpos] = $value;
            ++$_elpos;
        }

        foreach ($_GET as $key => $value) {
            $QUERY_STRING .= '&'.$key.'='.$value;
        }

        $_SERVER['QUERY_STRING'] = $QUERY_STRING;
        $REQUEST_URI = 'index.php?'.$QUERY_STRING;
        $_SERVER['REQUEST_URI'] = $REQUEST_URI;

    }elseif(in_array('component',$url_array)) {


        $uri = explode('component/',$_SERVER['REQUEST_URI']);
        $uri_array = explode('/',$uri[1]);
        $QUERY_STRING = '';

        foreach($uri_array as $value) {
            $temp = explode(',',$value);
            if(isset($temp[0]) && $temp[0] != '' && isset($temp[1]) && $temp[1] != '') {
                $_GET[$temp[0]] = $temp[1];
                $_REQUEST[$temp[0]] = $temp[1];

                // проверка на сущестрование каталога запрашиваемого компонента
                if($temp[0] == 'option') {
                    if(!is_dir(JPATH_BASE.'/components/'.$temp[1])) {
                        header('HTTP/1.0 404 Not Found');
                        require_once (JPATH_BASE.'/templates/system/404.php');
                        exit(404);
                    }
                }

                if($QUERY_STRING == '') {
                    $QUERY_STRING .= "$temp[0]=$temp[1]";
                } else {
                    $QUERY_STRING .= "&$temp[0]=$temp[1]";
                }
            }
        }

        $_SERVER['QUERY_STRING'] = $QUERY_STRING;
        $REQUEST_URI = $uri[0].'index.php?'.$QUERY_STRING;
        $_SERVER['REQUEST_URI'] = $REQUEST_URI;

    } else {

        $jdir = str_replace('index.php','',$_SERVER['PHP_SELF']);
        $juri = str_replace($jdir,'',$_SERVER['REQUEST_URI']);
        if($juri != '' && $juri != '/' && !preg_match("/index.php/i",$_SERVER['REQUEST_URI']) && !preg_match("/index2.php/i",$_SERVER['REQUEST_URI']) && !preg_match("/\?/i",$_SERVER['REQUEST_URI']) && $_SERVER['QUERY_STRING'] == '') {
            header('HTTP/1.0 404 Not Found');
            require_once (JPATH_BASE.'/templates/system/404.php');
            exit(404);
        }
    }
}

unset($url_array,$jdir,$juri);

/**
 * Converts an absolute URL to SEF format
 * @param string The URL
 * @return string
 */
function sefRelToAbs($string) {

    // Replace all &amp; with &
    $string = str_replace('&amp;','&',$string);

    // Home index.php
    if($string == 'index.php') {
        $string = '';
    }

    // break link into url component parts
    $url = parse_url($string);

    // check if link contained fragment identifiers (ex. #foo)
    $fragment = '';
    if(isset($url['fragment'])) {
        // ensure fragment identifiers are compatible with HTML4
        if(preg_match('@^[A-Za-z][A-Za-z0-9:_.-]*$@',$url['fragment'])) {
            $fragment = '#'.$url['fragment'];
        }
    }

    // check if link contained a query component
    if(isset($url['query'])) {
        // special handling for javascript
        $url['query'] = stripslashes(str_replace('script','s+cript',$url['query']));

        // break url into component parts
        parse_str($url['query'],$parts);

        // строка формирования итоговой ссылки
        $sefstring = '';

        if( isset ( $parts['option'] ) ) {
            $parts['option'] = str_replace('com_', '', $parts['option']);
            $sefstring .='/'.$parts['option'];
            unset( $parts['option'] ) ;
        }

        if( isset ( $parts['task'] ) ) {
            $sefstring .='/'.$parts['task'];
            unset( $parts['task'] ) ;
        }

        // идентификационная строка номер-название
        $id_string = '';
        if( isset ( $parts['tree:'] ) ) {
            $id_string .='/'.$parts['tree:'];
        }
        // указатель дерева уничтожаем в любом случае
        unset( $parts['tree:'] ) ;

        if( isset ( $parts['id'] ) ) {
            $id_string .='/'.$parts['id'];
            unset( $parts['id'] ) ;
            if( isset ( $parts['slug:'] ) ) {
                $id_string .='-'.$parts['slug:'];
            }
        }
        // slug уничтожаем в любом случае
        unset( $parts['slug:'] ) ;

        foreach($parts as $key => $value) {
            //$value = stripslashes($value);
            $sefstring .= '/'.$key.':'.$value;
        }

        //$sefstring = str_replace('=',',',$sefstring);
        $sefstring .= $id_string.'.html';
    } else {
        $string = '';
    }

    return JPATH_SITE.$sefstring.$fragment;
}
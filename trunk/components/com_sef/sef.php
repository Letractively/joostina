<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

/**
 * Справка
 * Добавление в ссылку параметра :antisuf=true удаляет из ссылки преффикс .html
 * В $_GET  параметр :cleanid записывается идентификатор в чистом виде
 *
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

global $mosConfig_sef;

// редиректить ли с не-sef адресов
DEFINE('_SEF_REDIRECT', true);
// удалять из ссылок парамтер ItemId
DEFINE('_SEF_DELETE_ITEMID', true);

//echo sefRelToAbs( 'index.php?dd=цукцуку&aaa=bbb&id=2010:Новый 2010 год опасносте!&task=task_current_new&option=com_component_name&param1=onepararam&tree:=111/22/333#show' );
//exit();

if($mosConfig_sef) {
	// перебрасываем на корректный адрес
	/*    if( _SEF_REDIRECT ) {
        if (ltrim(strpos($_SERVER['REQUEST_URI'], 'index.php'),'/')==1 && $_SERVER['REQUEST_METHOD']=='GET') { //Проверка SEF ли урл, т.е. вначале стоит index.php
            $url = sefRelToAbs('index.php?'.$_SERVER['QUERY_STRING']); //Преобразование урл
            header("Location: ".$url,TRUE,301); //Формирование заголовка с перенаправлением
            exit(301); //Завершение работы, с отдачей кода завершения
        }
    }
	*/
	$QUERY_STRING = array();
	// TODO изврат
	if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
		$https = 's://';
	} else {
		$https = '://';
	}
	if (!empty ($_SERVER['PHP_SELF']) && !empty ($_SERVER['REQUEST_URI'])) {
		$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	} else {
		$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
		if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
			$theURI .= '?' . $_SERVER['QUERY_STRING'];
		}
	}
	$theURI = str_replace(JPATH_SITE,'',$theURI);


	$url_array = explode('/', $theURI );

	$com_name = $url_array[1];

	/* сначала IF - есть в меню такой урл */

	//$all_menu_links = mosMenu::get_menu_links();
	//_xdump($all_menu_links);
	//exit();

	if( is_file( JPATH_BASE.'/components/com_'.$com_name.'/'.$com_name.'.php' ) ) {

		$_GET['option'] = $_REQUEST['option'] = 'com_'.$com_name;
		//$_GET['task']   = $_REQUEST['task']   = isset( $url_array[3] ) ? $url_array[2] : 'index' ;
		$_GET['task']   = $_REQUEST['task']   = isset( $url_array[2] ) ? $url_array[2] : 'index' ;

		$count_pos = count( $url_array );

		// идентификатор элемента - последний элемент массив, первая часть до -
		$id_pos = $url_array[ $count_pos-1 ];
		$id_pos = explode('-', $id_pos);
		$id_pos = $_GET[':cleanid']  = $id_pos[0];
		$_GET['id']  = $_REQUEST['id'] = (int) $id_pos;

		unset( $id_pos );

		// удаляем название компонента, выполняемую задачу и идентификатор вызываемого объекта
		unset( $url_array[0],$url_array[1],$url_array[2],$url_array[$count_pos-1] );

		$_elpos = 1;
		foreach($url_array as $value) {
			$_GET['param-'.$_elpos]  = $_REQUEST['param-'.$_elpos] = $value;
			++$_elpos;
		}

		foreach ($_GET as $key => $value) {
			$QUERY_STRING []= '&'.$key.'='.$value;
		}

		$QUERY_STRING = implode('', $QUERY_STRING);
		$_SERVER['QUERY_STRING'] = $QUERY_STRING;
		$REQUEST_URI = 'index.php?'.$QUERY_STRING;
		$_SERVER['REQUEST_URI'] = $REQUEST_URI;

	}else {
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
// $user_pref - использовать ли преффикс .html
function sefRelToAbs($string,$anti_pref = false) {

	// Replace all &amp; with &
	$string = str_replace('&amp;','&',$string);

	$sefstring = '';

	// Home index.php
	if($string == 'index.php') {
		$string = '';
	}

	// TODO жесткое извращение
	$string = str_replace(JPATH_SITE, '', $string );
	$string = str_replace('?&', '?', $string );
	$string = str_replace('index.php', '/index.php', $string );

	$url = @parse_url( $string );

	// check if link contained fragment identifiers (ex. #foo)
	$fragment = '';
	if(isset($url['fragment'])) {
		// ensure fragment identifiers are compatible with HTML4
		if(preg_match('@^[A-Za-z][A-Za-z0-9:_.-]*$@',$url['fragment'])) {
			$fragment = '#'.$url['fragment'];
		}
	}

	// массив формирования итоговой ссылки
	$sefstring = array();

	if(isset($url['query'])) {
		// special handling for javascript
		$url['query'] = stripslashes(str_replace('script','s+cript',$url['query']));

		// break url into component parts
		parse_str($url['query'],$parts);

		// TODO удаляем Itemid
		if(_SEF_DELETE_ITEMID==true) {
			unset($parts['Itemid'], $parts['ItemId'] );
		}

		if( isset ( $parts['option'] ) ) {
			$parts['option'] = str_replace('com_', '', $parts['option']);
			$sefstring[] ='/'.$parts['option'];
			unset( $parts['option'] ) ;
		}

		if( isset ( $parts['task'] ) ) {
			$sefstring[] ='/'.$parts['task'];
			unset( $parts['task'] ) ;
		}

		if( isset ( $parts['id'] ) ) {
			$id_data = explode(':', $parts['id']);
			if(isset( $id_data[1] )) {
				$id_data[1] = str_replace( array( '?','&','-',',','.','"',"'",'/','\\','(',')','[',']','{','}','+','`','_' ) , '', $id_data[1]);
				$id_data[1]  = trim($id_data[1] );
				$id_data[1] = str_replace(' ', '-', $id_data[1]);
				$id_data[1] = Jstring::strtolower( $id_data[1] );
				$id_string = '/'.$id_data[0].'-'.$id_data[1];
			}else {
				$id_string = '/'.$id_data[0];
			}
			unset( $parts['id'] ) ;
		}else {
			$id_string='';
		}

		// добавлять ли преффикс
		$antisuf = (bool) (isset( $parts[':antisuf'] ) || $anti_pref) ;
		unset( $parts[':antisuf'] );

		foreach($parts as $key => $value) {
			//$value = stripslashes($value);
			$sefstring[] = '/'.$value;
		}

		$sefstring []= $antisuf ?  $id_string : $id_string.'.html';
	} else {
		if (_SEF_DELETE_ITEMID) {
			$string = str_replace('&amp;', '&', $string);
			$string = @parse_url($string);

			if (isset($string['host'])) {
				return isset($string['scheme']) ? $string['scheme'] . '://' . $string['host'] : $string['host'];
			}

			isset($string['query']) ? parse_str($string['query'], $q) : ( $q = array() );
			// TODO удаляем Itemid !!!
			unset($q['Itemid'], $q['ItemId']);

			$sefstring[] = isset($string['path']) ? $string['path'] : '';
			$sefstring[] = (trim($sefstring[0]) =='') ? '?' . http_build_query($q) : '';
		}
		//$string = '';
	}

	return JPATH_SITE.implode('',$sefstring).$fragment;
}
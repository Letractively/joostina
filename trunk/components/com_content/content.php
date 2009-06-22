<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();
require_once ($mainframe->getPath('front_html','com_content'));
require_once ($mainframe->getPath('config','com_content'));

global $task,$Itemid,$option,$my;

//Подключаем js com_content-а
contentHelper::_load_core_js();

$id	= intval(mosGetParam($_REQUEST,'id',0));

// cache activation
$cache = &mosCache::getCache('com_content');

// loads function for frontpage component
if($option == 'com_frontpage') {
	$cache->call('frontpage');	
	return;
}

switch($task) {

	case 'ucontent':
		showUserItems();
		break;

	case 'view':
	case 'preview':
		if($mosConfig_enable_stats) {
			showFullItem($id);
		} else {
			$cache->call('showFullItem',$id);
		}
		break;

	case 'section':
		$cache->call('showSection_catlist',$id);
		break;

	case 'category':
		$cache->call('showTableCategory',$id);
		break;

	case 'blogsection':
		// Itemid is a dummy value to cater for caching
		$cache->call('showBlogSection',$id);
		break;

	case 'blogcategorymulti':
	case 'blogcategory':
		// Itemid is a dummy value to cater for caching
		$cache->call('showBlogCategory',$id);
		break;

	case 'archivesection':
		// Itemid is a dummy value to cater for caching
		$cache->call('showArchiveSection',$id);
		break;

	case 'archivecategory':
		// Itemid is a dummy value to cater for caching
		$cache->call('showArchiveCategory',$id);
		break;

	case 'edit':
		editItem($task);
		break;

	case 'new': 
		editItem($task);
		break;

	case 'save':
	case 'apply':
	case 'apply_new':
		mosCache::cleanCache('com_content');
		saveContent($task);
		break;

	case 'cancel':
		cancelContent();
		break;

	case 'emailform':
		emailContentForm($id,$my->gid);
		break;

	case 'emailsend':
		emailContentSend($id,$my->gid);
		break;

	case 'vote':
		recordVote();
		break;

	default:
		header("HTTP/1.0 404 Not Found");
		echo _NOT_EXIST;
		break;
}


/**
* Страница с перечнем материалов пользователя
*/
function showUserItems() {
	global $database, $mainframe, $Itemid, $my, $acl;

    $limit		= intval(mosGetParam($_REQUEST,'limit',0));
    $limitstart	= intval(mosGetParam($_REQUEST,'limitstart',0));
   
    $user_id	= intval(mosGetParam($_REQUEST,'user',0));

    //права доступа
    $access = new contentAccess();
    // Paramters
    $params = new configContent_ucontent($database);
    $limit = $limit ? $limit : $params->get('display_num');
    $params->set('limitstart', $limitstart);
    $params->set('limit', $limit);

    $user_items = new mosContent($database);
    //Получаем количество записей пользователя
    $user_items->total = $user_items->_get_count_user_items($user_id, $params);
    //Получаем все записи пользователя
    $user_items->items = $user_items->_load_user_items($user_id, $params);
    

    if(!$user_items->items){
        $user = new mosUser($database);
        if($user->load($user_id)){
            $user_items->user = $user;
        }
        else{
            $user_items = null;
        }
    }

    if($user_items){

        //Постраничная навигация
    	if ($user_items->total <= $limit) $limitstart = 0;
    	require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
    	$pageNav = new mosPageNav($user_items->total, $limitstart, $limit);

    	$check = 0;
    	if ( $params->get( 'date' ) ) {
    		$order[] = mosHTML::makeOption( 'date', _ORDER_DROPDOWN_DA );
    		$order[] = mosHTML::makeOption( 'rdate', _ORDER_DROPDOWN_DD );
    		$check .= 1;
    	}
    	if ( $params->get( 'title' ) ) {
    		$order[] = mosHTML::makeOption( 'alpha', _ORDER_DROPDOWN_TA );
    		$order[] = mosHTML::makeOption( 'ralpha', _ORDER_DROPDOWN_TD );
    		$check .= 1;
    	}
    	if ( $params->get( 'hits' ) ) {
    		$order[] = mosHTML::makeOption( 'hits', _ORDER_DROPDOWN_HA );
    		$order[] = mosHTML::makeOption( 'rhits', _ORDER_DROPDOWN_HD );
    		$check .= 1;
    	}
    	if ( $params->get( 'section' ) ) {
    		$order[] = mosHTML::makeOption( 'section', 'Раздел / Категория по возрастанию' );
    		$order[] = mosHTML::makeOption( 'rsection',  'Раздел / Категория по убыванию' );
    		$check .= 1;
    	}
    	$order[] = mosHTML::makeOption( 'order', _ORDER_DROPDOWN_O );
    	$lists['order'] = mosHTML::selectList( $order, 'order', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $params->get('orderby') );
    	if ( $check < 1 ) {
    		$lists['order'] = '';
    		$params->set( 'order_select', 0 );
    	}

    	$lists['task'] = 'category';
    	$lists['filter'] = $params->get('filter_value');
    	$lists['limit'] = $limit;
    	$lists['limitstart'] = $limitstart;

    }

    $pagetitle = '';
	if ( $Itemid ) {
		$menu = new mosMenu( $database );
		$menu->load( $Itemid );
		$pagetitle = $menu->name;
	}
	// Dynamic Page Title
	$mainframe->SetPageTitle( $pagetitle );


	HTML_content::showUserContent( $user_items, $access, $params, $pageNav, $lists, $params->get('orderby'));
}

function frontpage() {
	global $database,$mainframe,$mosConfig_MetaDesc,$mosConfig_MetaKeys, $my;

    $pop = intval(mosGetParam($_REQUEST,'pop',0));
    $limit		= intval(mosGetParam($_REQUEST,'limit',0));
    $limitstart	= intval(mosGetParam($_REQUEST,'limitstart',0));

    //права доступа
    $access = new contentAccess();

    //Установка параметров страницы блога раздела
    $params = contentPageConfig::setup_frontpage();
    $limit = $params->get('intro') + $params->get('leading') + $params->get('link');

    $frontpage = new mosContent($database);
    //Получаем общее количество записей на главной странице
    $frontpage->total = $frontpage->_get_result_frontpage($params, $access);

	if($frontpage->total <= $limit) {
		$limitstart = 0;
	}
    $params->set('limitstart', $limitstart);
    $params->set('limit', $limit);

     //Выбираем все нужные записи
    $frontpage->content = $frontpage->_load_frontpage($params, $access);

    $params->def('pop', $pop);
    $params->page_type = 'frontpage';

    // Мета-данные страницы
    $meta = new contentMeta($params);
    $meta->set_meta();

	BlogOutput($frontpage,$params,$access);
}


function showSection_catlist($id) {
	global $database,$mainframe,$Itemid, $my;
	
    if(!$id){
        $error = new errorCase(1);
        return;
    }

    //права доступа
    $access = new contentAccess();
    
    //Получаем данные раздела
	$section = new mosSection($database);
	$section->load((int)$id);
	
    //Проверяем права доступа к разделу 
    if(!$section->published || $section->access > $my->gid) {
        $error = new errorCase(2);
        return;
    }
    
    //Установка параметров страницы таблицы раздела
    $params = contentPageConfig::setup_section_catlist_page($section);    

		
	//Выбираем категории, принадлежащие текущему разделу
    $section->content = $section->_load_table_section($section, $params, $access);
	$categories = $section->content; 
	
	//Получаем общее количество опубликованных категорий в разделе,
	//чтобы выяснить - можно ли выводить кнопку добавления материалов
	$section->categories_exist = false;
	if($access->canEdit) {
		$section->categories_exist = $section->get_count_all_cats($section, $params, $access);
	}

	// remove slashes
	$section->name = stripslashes($section->name);

    $params->section_data = $section;
    $params->page_type = 'section_catlist';

	// Мета-данные страницы
    $meta = new contentMeta($params);
    $meta->set_meta();  

	HTML_content::showSection_catlist($section,$access,$params);
}


/**
* @param int The category id
* @param int The group id of the user
* @param int The access level of the user
* @param int The section id
* @param int The number of items to dislpay
* @param int The offset for pagination
*/
function showTableCategory($id) {
	global $database,$mainframe,$Itemid,$mosConfig_list_limit, $my;

    $limit		= intval(mosGetParam($_REQUEST,'limit',0));
    $limitstart	= intval(mosGetParam($_REQUEST,'limitstart',0));    
    $sectionid	= intval(mosGetParam($_REQUEST,'sectionid',0));
	    
	$selected = strval(mosGetParam($_REQUEST,'order',''));
	$selected = preg_replace('/[^a-z]/i','',$selected);
	
	$filter = stripslashes(strval(mosGetParam($_REQUEST,'filter','')));

    if(!$id){
        $error = new errorCase(1);
        return;
    }

    //права доступа
    $access = new contentAccess();

    //Грузим данные категории
    $category = new mosCategory($database);
	if(!($category->load((int)$id))){
        $error = new errorCase(1);
        return;
	}
    //Грузим данные раздела
    $section = new mosSection($database);
	if(!($section->load((int)$category->section))){
        $error = new errorCase(1);
        return;
	}
    $category->section_data = $section;

    //Проверяем права доступа к разделу и категории
    if(!$section->published || !$category->published) {
        $error = new errorCase(2);
        return;
    }
    if($section->access > $my->gid || $category->access > $my->gid) {
        $error = new errorCase(2);
        return;
    }

    //Установка параметров страницы таблицы категории
    $params = contentPageConfig::setup_table_category_page($category);
    
	$lists['order_value'] = '';
	if($selected) {
		$orderby = $selected;
		$lists['order_value'] = $selected;
	} else {
		$orderby = $params->get('orderby','rdate');
		$selected = $orderby;
	}	
	$params->def('orderby', $orderby);
	$params->def('cur_filter', $filter);
	$params->def('selected', $selected);
	
	if($sectionid == 0) {
		$sectionid = $category->section;
	}
	
	//Список других категорий раздела
	$category->other_categories = $category->get_other_cats($category, $access, $params);

	$content = new mosContent($database); 

	// query to determine total number of records
	//Получаем общее количество записей в таблице категории
    $category->total = $content->_get_result_table_category($category, $params, $access);

	$limit = $limit ? $limit : $params->get('display_num');
	if($category->total <= $limit) {
		$limitstart = 0;
	}
    $params->set('limitstart', $limitstart);
    $params->set('limit', $limit);
    
    //Выбираем все нужные записи 
    $category->content = $content->_load_table_category($category, $params, $access);
	
	// remove slashes
	$category->name = stripslashes($category->name);
	
 	$params->category_data = $category;
    $params->section_data = $section;
    $params->page_type = 'category_table';

	// Мета-данные страницы
    $meta = new contentMeta($params);
    $meta->set_meta();    
    
	HTML_content::showContentList($category, $access, $params);
	
}


function showBlogSection($id = 0) {
	global $database,$mainframe,$Itemid, $my;

    $pop = intval(mosGetParam($_REQUEST,'pop',0));
    $limit		= intval(mosGetParam($_REQUEST,'limit',0));
    $limitstart	= intval(mosGetParam($_REQUEST,'limitstart',0));

    if(!$id){
        $error = new errorCase(1);
        return;
    }

    //права доступа
    $access = new contentAccess();

    //Установка параметров страницы блога раздела
    $params = contentPageConfig::setup_blog_section_page($id);
    $params->def('pop', $pop);
    $params->page_type = 'section_blog';


    //Количество записей на странице
	$limit = $limit ? $limit : ($params->get('intro') + $params->get('leading') + $params->get('link'));

    //Грузим данные раздела
    $section = new mosSection($database);
	if(!($section->load((int)$id))){
        $error = new errorCase(1);
        return;
	}

    //Проверяем права доступа к разделу
    //Если раздел не опубликован или группа пользователя ниже группы доступа
    //- выдаём сообщение о невозможности доступа
    if(!$section->published) {
        $error = new errorCase(2);
        return;
    }
    if($section->access > $my->gid) {
        $error = new errorCase(2);
        return;
    }

    $content = new mosContent($database);
    //Получаем общее количество записей в блоге
    $section->total = $content->_get_result_blog_section($section, $params, $access);

    //Устанавливаем окончательные параметры $limit и $limitstart
	if($section->total <= $limit) {
		$limitstart = 0;
	}
    $params->set('limitstart', $limitstart);
    $params->set('limit', $limit);

    //Выбираем все нужные записи сблога
    $section->content = $content->_load_blog_section($section, $params, $access);

    $params->section_data = $section;


	// Мета-данные страницы
 	if(!$params->get('header')){
        $params->set('header',  $section->name);
    }
    $meta = new contentMeta($params);
    $meta->set_meta();

	BlogOutput($section,$params,$access);
}

function showBlogCategory($id = 0) {
	global $database, $mainframe, $Itemid, $my;

    $pop = intval(mosGetParam($_REQUEST,'pop',0));
    $limit		= intval(mosGetParam($_REQUEST,'limit',0));
    $limitstart	= intval(mosGetParam($_REQUEST,'limitstart',0));

    if(!$id){
        $error = new errorCase(1);
        return;
    }

    //права доступа
    $access = new contentAccess();

    //Установка параметров страницы блога категории
    $params = contentPageConfig::setup_blog_category_page($id);

    //Количество записей на странице
	$limit = $limit ? $limit : ($params->get('intro') + $params->get('leading') + $params->get('link'));


    //Грузим данные категории
    $category = new mosCategory($database);
	if(!($category->load((int)$id))){
        $error = new errorCase(1);
        return;
	}
    //Грузим данные раздела
    $section = new mosSection($database);
	if(!($section->load((int)$category->section))){
        $error = new errorCase(1);
        return;
	}
    $category->section = $section;

    //Проверяем права доступа к разделу и категории
    //Если раздел/категория не опубликованы или группа пользователя ниже группы доступа
    //- выдаём сообщение о невозможности доступа
    if(!$section->published || !$category->published) {
        $error = new errorCase(2);
        return;
    }
    if($section->access > $my->gid || $category->access > $my->gid) {
        $error = new errorCase(2);
        return;
    }

     $content = new mosContent($database);
    //Получаем общее количество записей в блоге
    $category->total = $content->_get_result_blog_category($category, $params, $access);

	if($category->total <= $limit) {
		$limitstart = 0;
	}
    $params->set('limitstart', $limitstart);
    $params->set('limit', $limit);

    //Выбираем все нужные записи сблога
    $category->content = $content->_load_blog_category($category, $params, $access);


    $params->category_data = $category;
    $params->section_data = $section;
    $params->def('pop', $pop);
    $params->page_type = 'category_blog';

	// Мета-данные страницы
    $meta = new contentMeta($params);
    $meta->set_meta();


	BlogOutput($category,$params,$access);

}

function showArchiveSection($id = null) {
	global $database, $mainframe, $Itemid, $my;
	
	$year		= intval(mosGetParam($_REQUEST,'year',date('Y')));
	$month		= intval(mosGetParam($_REQUEST,'month',date('m')));
	$module		= intval(mosGetParam($_REQUEST,'module',0));
	
	$pop = intval(mosGetParam($_REQUEST,'pop',0));
    $limit		= intval(mosGetParam($_REQUEST,'limit',0));
    $limitstart	= intval(mosGetParam($_REQUEST,'limitstart',0));
    
    if(!$id){
        $error = new errorCase(1);
        return;
    }    
    
    //права доступа
    $access = new contentAccess();

    //Установка параметров страницы блога раздела
    $params = contentPageConfig::setup_blog_archive_section_page($id);
   	$params->set('year',$year);
	$params->set('month',$month);
	$params->set('intro_only',1);

    //Количество записей на странице
	$limit = $limit ? $limit : ($params->get('intro') + $params->get('leading') + $params->get('link'));


    //Грузим данные раздела
    $section = new mosSection($database);
	if(!($section->load((int)$id))){
        $error = new errorCase(1);
        return;
	}

    //Проверяем права доступа к разделу
    //Если раздел не опубликован или группа пользователя ниже группы доступа
    //- выдаём сообщение о невозможности доступа
    if(!$section->published) {
        $error = new errorCase(2);
        return;
    }
    if($section->access > $my->gid) {
        $error = new errorCase(2);
        return;
    }    
    
    $content = new mosContent($database);
    //Получаем общее количество записей в блоге
    $section->total = $content->_get_result_archive_section($section, $params, $access);

    //Устанавливаем окончательные параметры $limit и $limitstart
	if($section->total <= $limit) {
		$limitstart = 0;
	}
    $params->set('limitstart', $limitstart);
    $params->set('limit', $limit);

    //Выбираем все нужные записи сблога
    $section->content = $content->_load_archive_section($section, $params, $access);

    $params->section_data = $section;
    $params->def('pop', $pop);
    $params->page_type = 'section_archive';

	// Мета-данные страницы
    $meta = new contentMeta($params);
    $meta->set_meta();

	BlogOutput($section,$params,$access);


}


function showArchiveCategory($id = 0) {
	global $database, $mainframe, $Itemid, $my;
	
	$year		= intval(mosGetParam($_REQUEST,'year',date('Y')));
	$month		= intval(mosGetParam($_REQUEST,'month',date('m')));
	$module		= intval(mosGetParam($_REQUEST,'module',0));
	
	$pop = intval(mosGetParam($_REQUEST,'pop',0));
    $limit		= intval(mosGetParam($_REQUEST,'limit',0));
    $limitstart	= intval(mosGetParam($_REQUEST,'limitstart',0));


    if(!$module &&!$id){
        $error = new errorCase(1);
        return;
    }
 
    //права доступа
    $access = new contentAccess();

    //Установка параметров страницы блога категории
    $params = contentPageConfig::setup_blog_archive_category_page($id);
   	$params->set('year',$year);
	$params->set('month',$month);
	$params->def('module',$module);

    //Количество записей на странице
	$limit = $limit ? $limit : ($params->get('intro') + $params->get('leading') + $params->get('link'));

	

	if(!$params->get('module')){
	    //Грузим данные категории
	    $category = new mosCategory($database);
		if(!($category->load((int)$id))){
	        $error = new errorCase(1);
	        return;
		} 
	    //Грузим данные раздела
	    $section = new mosSection($database);
		if(!($section->load((int)$category->section))){
	        $error = new errorCase(1);
	        return;
		}
	    $category->section = $section;
	
	    //Проверяем права доступа к разделу и категории
	    //Если раздел/категория не опубликованы или группа пользователя ниже группы доступа
	    //- выдаём сообщение о невозможности доступа
	    if(!$section->published || !$category->published) {
	        $error = new errorCase(2);
	        return;
	    }
	    if($section->access > $my->gid || $category->access > $my->gid) {
	        $error = new errorCase(2);
	        return;
	    }
	    $params->page_type = 'category_archive';
    }
    else{
    	$category = new stdClass();
    	
    	$section = new stdClass();
    	$params->page_type = 'archive_by_month';
    	$params->def('header','Архив');
    }

	$content = new mosContent($database);
    
	//Получаем общее количество записей в блоге архива категории
    $category->total = $content->_get_result_blog_archive_category($category, $params, $access);
    

	if($category->total <= $limit) {
		$limitstart = 0;
	}
    $params->set('limitstart', $limitstart);
    $params->set('limit', $limit);
    
    //Проверяем, есть ли вообще в базе архивные материалы
    $category->archives = $content->check_archives_categories($category, $params);
    
    //Выбираем все нужные записи сблога
    $category->content = $content->_load_blog_archive_category($category, $params, $access); 
    

	$params->def('pop', $pop);
    $params->category_data = $category;
    $params->section_data = $section;
    

	// Мета-данные страницы
    $meta = new contentMeta($params);
    $meta->set_meta();


	BlogOutput($category,$params,$access);
}


function BlogOutput(&$obj,$params,&$access) {
	global $mainframe,$Itemid,$task,$id,$option,$database,$mosConfig_live_site, $mosConfig_absolute_path, $my;

    $rows = $obj->content;
    $total = $obj->total;
    
    $gid = $my->gid;
    
    $menu = $params->menu;
    $limitstart = $params->get('limitstart');
    $limit = $params->get('limit');    
    $pop = $params->get('pop');
    

    $archive = null; $archive_page = null;
    if($params->page_type == 'section_archive' || $params->page_type == 'category_archive' || $params->page_type == 'archive_by_month'){
        $archive = 1;
    }
    
    $i = 0;
    $header = '';
    $display_desc = 0;
    $display_desc_img = 0;
    $display_desc_text = 0;
    $display_pagination = 0;
    $display_pagination_results = 0;
    $display_blog_more = 0;
    $tpl = '';
    
    $header = $params->get('header');
    if(!$header){
        $header = $obj->name;
    }

	$columns = $params->get('columns');
	if($columns == 0) {
		$columns = 1;
	}
	
	$intro = $params->get('intro');
	$leading = $params->get('leading');
	$links = $params->get('link');
	$pagination = $params->get('pagination');
	$pagination_results = $params->get('pagination_results');
	$descrip = $params->get('description');
	$descrip_image = $params->get('description_image');

	 //группировка по категориям
	 $group_cat=$params->get('group_cat');
	 $groupcat_limit=$params->get('groupcat_limit');
	 $cats_arr=array(); $k=0;

	 $sfx = $params->get('pageclass_sfx');


     //Установка параметров записей в блоге
    $params = contentPageConfig::setup_blog_item($params);

	// used to display section/catagory description text and images
	// currently not supported in Archives
	if($params->get('description') && ($descrip || $descrip_image)) {
	    $display_desc = 1;

		switch($params->page_type) {
			case 'section_blog':
            case 'category_blog':
				$description = $obj->description;
                $description_img = $obj->image;
				break;

            case 'frontpage':
			default:
				$menu->componentid = 0;
                $description = '';
                $description_img = '';
				break;
		}

	    if($params->get('description_image') && $description_img) {
	    	$display_desc_img = 1;
	    }
	    if($params->get('description') && $description) {
            $display_desc_text = 1;
		}
	}

    // checks to see if there are there any items to display
	if($total) { 
		$col_with = 100 / $columns; // width of each column
		$width = 'width="'.intval($col_with).'%"';

		if($archive) { 
			// Search Success message
			$msg = sprintf(_ARCHIVE_SEARCH_SUCCESS,$params->get('month'),$params->get('year'));
		}

        // Links output
		if($links && ($i < $total - $limitstart)) {
		    $display_blog_more = 1;
			$showmore = $leading + $intro;
		}

		// Pagination output
		if($pagination) {
			if(($pagination == 2) && ($total <= $limit)) {
				// not visible when they is no 'other' pages to display
			} else {
			    $display_pagination = 1;
				require_once ($GLOBALS['mosConfig_absolute_path'].'/includes/pageNavigation.php');
				$limitstart = $limitstart?$limitstart:0;
				$pageNav = new mosPageNav($total,$limitstart,$limit);

				if($Itemid && $Itemid != 99999999) {
					// where Itemid value is returned, do not add Itemid to url
					$Itemid_link = '&amp;Itemid='.$Itemid;
				} else {
					// where Itemid value is NOT returned, do not add Itemid to url
					$Itemid_link = '';
				}

				if($option == 'com_frontpage') {
					$link = 'index.php?option=com_frontpage'.$Itemid_link;
				} else
					if($archive_page) {
						$year = $params->get('year');
						$month = $params->get('month');

						if(!$archive) {
							// used when access via archive module
							$pid = '&amp;id=0';
							$module = '&amp;module=1';
						} else {
							// used when access via menu item
							$pid = '&amp;id='.$id;
							$module = '';
						}

						$link = 'index.php?option=com_content&amp;task='.$task.$pid.$Itemid_link.'&amp;year='.$year.'&amp;month='.$month.$module;
					} else {
						$link = 'index.php?option=com_content&amp;task='.$task.'&amp;id='.$id.$Itemid_link;
					}

				if($pagination_results) {
				    $display_pagination_results = 1;
				}
			}
		}
	}

    else if($archive && !$total) {
	    $msg = sprintf(_ARCHIVE_SEARCH_FAILURE,$params->get('month'),$params->get('year'));
    }


    //Тэги
    $tags = new contentTags($database);
    $all_tags = $tags->load_by_type('com_content');
    $tags_arr = array();
    foreach ($all_tags as $tag){
        $tag->tag = '<a class="tag" href="'.$tags->get_tag_url($tag->tag).'">'.$tag->tag.'</a>';
        if(array_key_exists($tag->obj_id, $tags_arr)){
            $tags_arr[$tag->obj_id] .=  ', '.$tag->tag;
        }
        else{
            $tags_arr[$tag->obj_id] =  $tag->tag;
        }
    }

    $template = new jstContentTemplate();
     $templates = null;
    //Определяем шаблон вывода страницы

     //Если это архив
    if($archive) { 
        switch ($task){
            //Архив раздела
            case 'archivesection':
            default:
                $page_type = 'section_archive';
                $templates = $params->section_data->templates;
            break;

            //Архив категории
            case 'archivecategory':
                $page_type = 'category_archive';
                
                if($params->get('module')){
                	include_once($mosConfig_absolute_path.'/components/com_content/view/category/archive_by_month/default.php');
        			return;	
                }

                if($template->isset_settings($page_type, $params->category_data->templates)){
                    $templates = $params->category_data->templates;
                }
                elseif($template->isset_settings($page_type, $params->section_data->templates)){
                    $templates = $params->section_data->templates;
                }
            break;
        }
    }



    //Если это главная страница - компонент 'com_frontpage'
   else if($_REQUEST['option']=='com_frontpage'){
        include_once($mosConfig_absolute_path.'/components/com_content/view/frontpage/default.php');
        return;
    }

    //Не главная страница и не архив - обычный блог раздела или категории
    else{
        switch ($task){
            case 'blogcategory':
            default:
                $page_type = 'category_blog';
                //проверяем настройки категории на предмет  заданного шаблона
                if($template->isset_settings($page_type, $params->category_data->templates)){
                    $templates = $params->category_data->templates;
                }
                //иначе - проверяем настройки раздела
                elseif($template->isset_settings($page_type, $params->section_data->templates)){
                    $templates = $params->section_data->templates;
                }

            break;

            case 'blogsection':
                //Если группировка по категориям отключена - оставляем вывод как и был прежде
                if(!$group_cat){
                    $page_type = 'section_blog';
                }
                //Если включена группировка по категориям
                else{
                    $page_type = 'section_groupcats';
                    	$counts = array(); $k=0;
						foreach($rows as $row){
							if(!key_exists($row->catid, $counts)){
								$counts[$row->catid] = 1;
							}
							if($counts[$row->catid] <= $groupcat_limit){
								$cats_arr[$row->catid]['obj'][] = $row;
								$cats_arr[$row->catid]['cat_name'] = $row->category;
								$counts[$row->catid]++;	$k++;
							}							
						}
                }
                $templates = $params->section_data->templates;
                break;
        }
    } 
    $template->set_template($page_type, $templates);
    include_once($template->template_file); 
}


function showFullItem($id) {
	global $database,$mainframe,$mosConfig_disable_date_state,$mosConfig_disable_access_control;
    global $task, $my;
    
    $pop = intval(mosGetParam($_REQUEST,'pop',0));
    
    //права доступа
    $access = new contentAccess();



    $where = mosContent::_construct_where_for_fullItem($access);
    
    // voting control
    $params = new mosParameters('');
    $params->def('rating', $mainframe->getCfg('vote'));
    $voting = new contentVoiting($params);
    $voting = $voting->_construct_sql();
        
	// main query
	$query = "SELECT a.*,
                    cc.name AS category, cc.templates as c_templates, cc.access AS cat_access, cc.id as cat_id, cc.published AS cat_pub,
                    s.name AS section, s.published AS sec_pub, s.id AS sec_id, s.templates as s_templates, s.access AS sec_access,
                    u.name AS author, u.usertype, u.username,
                    g.name AS groups
                    " . $voting['select'] . "
            FROM #__content AS a
            LEFT JOIN #__categories AS cc ON cc.id = a.catid
            LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = 'content'
            LEFT JOIN #__users AS u ON u.id = a.created_by
            LEFT JOIN #__groups AS g ON a.access = g.id
            ". $voting['join'] ."
            WHERE a.id = ".(int)$id. $where;
	$database->setQuery($query);
	$row = null;

	if($database->loadObject($row)) {

        if($task=='preview' && $my->id!=$row->created_by){
            mosNotAuth();
			return;
        }

		/*
		* check whether category is published
		*/
		if(!$row->cat_pub && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section is published
		*/
		if(!$row->sec_pub && $row->sectionid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether category access level allows access
		*/
		if(($row->cat_access > $my->gid) && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section access level allows access
		*/
		if(($row->sec_access > $my->gid) && $row->sectionid) {
			mosNotAuth();
			return;
		}

        //Устанавливаем необходимые параметры страницы
    	$params = contentPageConfig::setup_full_item_page($row);
		$params->def('pop', $pop);
		
		
		// loads the links for Next & Previous Button
		if($params->get('item_navigation')) {
            $row->prev = '';
			$row->next = '';
            $row = mosContent::get_prev_next($row, $where, $access, $params);
		}

        //Тэги
        $tags = new contentTags($database);
        $row->tags = $tags->load_by($row);
        $row->tags = $tags->arr_to_links($row->tags, ', ');

        //$row->rating=$row->total_rate;
		_showItem($row,$params,$my->gid,$access,$pop);


		// Мета-данные страницы
		$params->object = $row;		
    	$meta = new contentMeta($params);
    	$meta->set_meta();
	

	} else {
		mosNotAuth();
		return;
	}
}


function _showItem($row,$params,$gid,&$access,$pop, $template='') {
	global $database,$mainframe,$mosConfig_content_hits;
	global $cache;

	$noauth = !$mainframe->getCfg('shownoauth');

	if($access->canEdit) {
		if($row->id === null || $row->access > $gid) {
			mosNotAuth();
			return;
		}
	} else {
		if($row->id === null || $row->state == 0) {
			mosNotAuth();
			return;
		}
		if($row->access > $gid) {
			if($noauth) {
				mosNotAuth();
				return;
			} else {
				if(!($params->get('intro_only'))) {
					mosNotAuth();
					return;
				}
			}
		}
	}



    $limit_introtext=$params->get('introtext_limit', 0);



	// if a popup item (e.g. print page) set popup param to correct value
	if($pop) {
		$params->set('popup',1);
	}
	
	if(!$params->get('rating')) {
		$row->rating = null;
		$row->rating_count = null;
	}

	$row->category = htmlspecialchars(stripslashes($row->category),ENT_QUOTES);

    //Ссылка на раздел/категорию
	if($params->get('section_link') || $params->get('category_link')) {
		// loads the link for Section name
		if($params->get('section_link') && $row->sectionid) {
			// pull values from mainframe
			$secLinkID = $mainframe->get('secID_'.$row->sectionid,-1);
			$secLinkURL = $mainframe->get('secURL_'.$row->sectionid);

			// check if values have already been placed into mainframe memory
			if($secLinkID == -1) {
				$query = "SELECT id, link FROM #__menu WHERE published = 1 AND type IN ( 'content_section', 'content_blog_section' ) AND componentid = ".(int)$row->sectionid."\n ORDER BY type DESC, ordering";
				$database->setQuery($query);
				//$secLinkID = $database->loadResult();
				$result = $database->loadRow();

				$secLinkID = $result[0];
				$secLinkURL = $result[1];

				if($secLinkID == null) {
					$secLinkID = 0;
					// save 0 query result to mainframe
					$mainframe->set('secID_'.$row->sectionid,0);
				} else {
					// save query result to mainframe
					$mainframe->set('secID_'.$row->sectionid,$secLinkID);
					$mainframe->set('secURL_'.$row->sectionid,$secLinkURL);
				}
			}

			$_Itemid = '';
			// use Itemid for section found in query
			if($secLinkID != -1 && $secLinkID) {
				$_Itemid = '&amp;Itemid='.$secLinkID;
			}
			if($secLinkURL) {
				$secLinkURL = ampReplace($secLinkURL);
				$link = sefRelToAbs($secLinkURL.$_Itemid);
			} else {
                $params->sectionid = $row->sectionid;
                $params->Itemid = $_Itemid;
                if($params->get('section_link_type')=='blog'){
                    $link = mosSection::get_section_blog_url($params);
                }
                else{
                    $link = mosSection::get_section_table_url($params);
                }

			}
			$row->section = '<a href="'.$link.'">'.$row->section.'</a>';
		}

		// loads the link for Category name
		if($params->get('category_link') && $row->catid) {
			// pull values from mainframe
			$catLinkID = $mainframe->get('catID_'.$row->catid,-1);
			$catLinkURL = $mainframe->get('catURL_'.$row->catid);

			// check if values have already been placed into mainframe memory
			if($catLinkID == -1) {
				$query = "	SELECT id, link 
							FROM #__menu 
							WHERE published = 1 
							AND type IN ( 'content_category', 'content_blog_category' ) 
							AND componentid = ".(int)$row->catid."\n 
							ORDER BY type DESC, ordering";
				$database->setQuery($query);
				//$catLinkID = $database->loadResult();
				$result = $database->loadRow();

				$catLinkID = $result[0];
				$catLinkURL = $result[1];

				if($catLinkID == null) {
					$catLinkID = 0;
					// save 0 query result to mainframe
					$mainframe->set('catID_'.$row->catid,0);
				} else {
					// save query result to mainframe
					$mainframe->set('catID_'.$row->catid,$catLinkID);
					$mainframe->set('catURL_'.$row->catid,$catLinkURL);
				}
			}

			$_Itemid = '';
			// use Itemid for category found in query
			if($catLinkID != -1 && $catLinkID) {
				$_Itemid = '&amp;Itemid='.$catLinkID;
			} else
				if(isset($secLinkID) && $secLinkID != -1 && $secLinkID) {
					// use Itemid for section found in query
					$_Itemid = '&amp;Itemid='.$secLinkID;
				}
			if($catLinkURL) {
				$link = sefRelToAbs($catLinkURL.$_Itemid);
			} else {
			    $params->sectionid = $row->sectionid;
                $params->catid = $row->catid;
                $params->Itemid = $_Itemid;
                if($params->get('cat_link_type')=='blog'){
                    $link = mosCategory::get_category_blog_url($params);
                }
                else{
                    $link = mosCategory::get_category_table_url($params);
                }

			}
			$row->category = '<a href="'.$link.'">'.$row->category.'</a>';
		}
	}

	// show/hides the intro text
	if($params->get('introtext')) {
		if($params->get('jeditable'))
			$row->text = $row->introtext.($params->get('intro_only')?'':chr(13).'</div><div id="jneditf-'.$row->id.'">'.chr(13).$row->fulltext.chr(13).chr(13).$row->notetext);
		else
			$row->text = $row->introtext.($params->get('intro_only')?'':chr(13).chr(13).$row->fulltext.chr(13).chr(13).$row->notetext);
	} else {
		$row->text = $row->fulltext;
	}

    if($limit_introtext){
         $row->text=mosHTML::cleanText($row->text);
         $row->text = implode(" ", array_slice(preg_split("/\s+/", $row->text), 0, $limit_introtext)).'...';
    }

	// deal with the {mospagebreak} mambots
	// only permitted in the full text area
	$page = intval(mosGetParam($_REQUEST,'limitstart',0));

	// запись счетчика прочтения
	if(!$params->get('intro_only') && ($page == 0) && ($mosConfig_content_hits)) {
		$obj = new mosContent($database);
		$obj->hit($row->id);
	}

	// needed for caching purposes to stop different cachefiles being created for same item
	// does not affect anything else as hits data not outputted
	$row->hits = 0;


	$cache->call('HTML_content::show',$row,$params,$access,$page, $template);
}


function editItem($task) {
	global $database,$my, $gid, $mainframe, $acl;
	global $mosConfig_absolute_path,$mosConfig_live_site,$mosConfig_offset;
	// boston, при редактировании материала с фронта отключаем показ всех модулей - пользователю будет не повадно переходить по ссылкам без сохранения, и место освободим
	global $mosConfig_module_on_edit_off;

    if($mosConfig_module_on_edit_off == 1) {
        $GLOBALS['_MOS_MODULES'] = '';
    }

    $nullDate = $database->getNullDate();
    $lists = array();

    //$id обнаруживается в адресной строке только в том случае, если пользователь редактирует материал
    $id	= intval(mosGetParam($_REQUEST,'id',0));
    //$section может присутствовать в ссылке, если форма настроена для какого-то конкретного раздела  и это добавление записи
    $section = intval(mosGetParam($_REQUEST,'section',0));

    //По-умолчанию в '__menus' содержится запись о пункте меню,
    //с помощью которого настраивается добавление/редактирование всех записей (независимо от раздела)
    $link = 'index.php?option=com_content&task=new';
    $special_params=0;

    // Editor usertype check
    $access = new stdClass();
    $access->canEdit	= $acl->acl_check('action','edit','users',$my->usertype,'content','all');
    $access->canEditOwn	= $acl->acl_check('action','edit','users',$my->usertype,'content','own');
    $access->canPublish	= $acl->acl_check('action','publish','users',$my->usertype,'content','all');

    //Создаем объект
    $content = new mosContent($database);


    //Если это добавление новой записи
    if($task=='new'){
         $row = $content;
        //запрещаем доступ тем, кому низя - у кого нет прав ни на редактирование вообще, ни на редактирование своего контента
		if(!($access->canEdit || $access->canEditOwn)) {
			HTML_content::_no_access();
			return;
        }

        //если в ссылке, по которой пользователь пришел добавлять контент, обнаруживается 'section' -
        //ищем в базе пункт меню, с помощью которого настраивается форма именно для текущего раздела
        if($section){
            $link = 'index.php?option=com_content&task=new&section='.(int)$section;
            $special_params=1;
        }

        //запрос на данные о пункте меню
        $query = "SELECT id, params FROM #__menu WHERE (link LIKE '%$link') AND published = 1";
        $r=null;
	    $database->setQuery($query);
	    $database->loadObject($r);
        $exists = $r;
	}

    //Если это редактирование записи
    else if($task=='edit'){
       $row = $content->get_item((int)$id);

       $section = $row->sectionid;
		// запрещаем доступ
		if(!($access->canEdit || ($access->canEditOwn && $row->created_by == $my->id))) {
			mosNotAuth();
			return;
		}

	    // выводим сообщение, если данная запись сейчас редактируется кем-то другим
	    if($content->isCheckedOut($my->id)) {
		    mosErrorAlert("[ ".$row->title." ] "._CONTENT_IS_BEING_EDITED_BY_OTHER_PEOPLE);
	    }

        //два варианта, в которых могут существовать ссылки на добавление/редактирование
        $link1 = 'index.php?option=com_content&task=new&section='.(int)$section;
        $link2 = 'index.php?option=com_content&task=new';

        //запрос на данные о пункте меню
        $query = "  SELECT a.id AS menu_id2, a.params AS menu_params2 , b.id AS menu_id1, b.params AS menu_params1
                    FROM #__menu AS a
                    LEFT JOIN  #__menu AS b  ON  b.link LIKE '%$link1' AND b.published = 1
                    WHERE  a.link LIKE '%$link2' AND a.published = 1
                  ";
	    $database->setQuery($query);
	    $exists = $database->loadObjectList();
    }

    if(!$exists) {
	    mosNotAuth();
		return;
	}

    //если это добавление новой записи - все просто, передаем параметры для парсинга
    if($task=='new'){
        $params = new mosParameters($exists->params);
    }
    //если же это редактирование - то нужно определить, какие именно параметры будем передавать
    //- из настроек ссылки по-умолчанию, или же есть настройки для текущего раздела
    else{
        //проверим, есть ли специальные настройки
        if(isset($exists[0]->menu_id1)){
            $params = new mosParameters($exists[0]->menu_params1);
            $special_params=1;
        }
        //иначе проверим, существуют ли настройки по-умолчанию
        else if(isset($exists[0]->menu_id2)){
            $params = new mosParameters($exists[0]->menu_params2);
        }
        //ну и так, на всякий случай. А вдруг!
        else{
            $menu = $mainframe->get('menu');
	        $params = new mosParameters($menu->params);
        }
    }


    // параметры полученные из настроек ссылки в меню
	$ids_user = $params->get('ids_user',0);// введенные значения ID
    $ids_action = $params->get('ids_action',0);// тип обработки введенных ID

    $where_c = "";
    $where_s = "";
    if($ids_action && $ids_user){
        switch($ids_action){
            case '1':  //разрешить публикацию только в указанных РАЗДЕЛАХ
            default:
                $where_s = " AND ( s.id IN (". $ids_user .") )";
                //если есть специальные настройки для раздела - сбрасываем перечень ID разделов, которе задал пользователь
                //поскольку если есть специальные настройки -  запись может быть добавлена только в определенный раздел
                if($special_params){
                    $where_s = " ";
                }
            break;

            case '2': //разрешить публикацию только в указанных КАТЕГОРИЯХ
                $where_c = " AND ( c.id IN (". $ids_user .") )";
            break;

            case '3':  //запретить публикацию в указанных РАЗДЕЛАХ
                $where_s = " AND ( s.id NOT IN (". $ids_user .") )";
                if($special_params){
                    $where_s = " ";
                }
            break;

            case '4':  //запретить публикацию в указанных КАТЕГОРИЯХ
                $where_c = " AND ( c.id NOT IN (". $ids_user .") )";
            break;
        }
    }


	if($task=='edit') {
		$content->checkout($my->id);

		$row->created = mosFormatDate($row->created,_CURRENT_SERVER_TIME_FORMAT);
		$row->modified = $row->modified == $nullDate?'':mosFormatDate($row->modified,_CURRENT_SERVER_TIME_FORMAT);
		$row->publish_up = mosFormatDate($row->publish_up,_CURRENT_SERVER_TIME_FORMAT);

		if(trim($row->publish_down) == $nullDate || trim($row->publish_down) == '' ||
			trim($row->publish_down) == '-') {
			$row->publish_down = 'Никогда';
		}
		$row->publish_down = mosFormatDate($row->publish_down,_CURRENT_SERVER_TIME_FORMAT);


		$row->creator = $row->author_nickname;
        $row->modifier = $row->modifier_nickname;
		if($row->created_by == $row->modified_by) {
			$row->modifier = $row->creator;
		}

		$query = "SELECT content_id FROM #__content_frontpage WHERE content_id = ".(int)$row->id;
		$database->setQuery($query);
		$row->frontpage = $database->loadResult();

        if($row->sectionid){
            $sec = new mosSection($database);
            $sec->templates = $row->s_templates;
            $params->section_data = $sec;
        }


	} else {
		$row->sectionid = $section;
		$row->version = 0;
		$row->ordering = 0;
		$row->images = array();
		$row->publish_up = date('Y-m-d H:i:s',time() + ($mosConfig_offset* 60* 60));
		$row->publish_down = 'Никогда';
		$row->creator = 0;
		$row->modifier = 0;
		$row->frontpage = 0;
        $params->section_data = null;
        //публикация контента
        // Publishing state hardening for Authors
        $auto_publish = $params->get('auto_publish',0);

        if(!$auto_publish){ //Если выбран первый параметр - права по группам
        	if(!$access->canPublish) {
        	    $row->state = 0;
            }
            else{
                 $row->state = 1;
            }
       }
        else{
            $row->state = 1;
        }

        if($section){
            $sec = new mosSection($database);
            $sec->load((int)$section);
            $params->section_data = $sec;
        }

	}



	// make the select list for the states
	$states[] = mosHTML::makeOption(0,_CMN_UNPUBLISHED);
	$states[] = mosHTML::makeOption(1,_CMN_PUBLISHED);
	$lists['state'] = mosHTML::selectList($states,'state','class="inputbox" size="1"','value','text',intval($row->state));

	// build the html select list for ordering
	$query = "SELECT ordering AS value, title AS text FROM #__content WHERE catid = ".(int)$row->catid."\n ORDER BY ordering";
	$lists['ordering'] = mosAdminMenus::SpecificOrdering($row,$id,$query,1);

	//$database->setQuery("SELECT CONCAT(s.id,'/',c.id) as value, CONCAT(s.name,'/',c.name) as text  FROM #__categories AS c ,#__sections AS s where s.id=c.section  ");
	//$z_cats_main = $database->loadObjectList();
	//$lists['catid'] = mosHTML::selectList($rows,'catid','class="inputbox" size="1"','value','text',intval($row->catid));


    //--->>>Строим selectlist для выбора категории, к которой будет принадлежать материал: BEGIN---<<<

    //Если есть специальные настройки для добавления материала в раздел
    //выбираем только категории, принадлежашие данному разделу
    if($special_params){
        $database->setQuery(" SELECT  CONCAT(c.section,'*',c.id) AS cid , c.title AS c_title, c.section  FROM   #__categories AS c WHERE c.section=$section AND c.published=1 $where_c ORDER BY c.title ASC ");
	    $cids = $database->loadObjectList();
        //$cats[] = mosHTML::makeOption('-1','Выберите категорию','id','c_title');
		//$cats = array_merge($cats,$cids);
		$lists['catid'] = mosHTML::selectList($cids,'catid','class="inputbox" size="1"','cid','c_title',intval($row->catid).'*'.($row->sectionid));
    }
    //если же это настройки по умолчанию - нужно построить селект, в котором присутствуют и разделы, и категории
    else{
    	$database->setQuery(" SELECT  c.id AS cid , c.title AS c_name, c.section  FROM   #__categories AS c WHERE c.published=1 $where_c ORDER BY title ASC ");
	    $cids = $database->loadObjectList();

        $database->setQuery(" SELECT   s.id, s.title  FROM  #__sections AS s WHERE s.published=1 $where_s ORDER BY title ASC");
	    $sids = $database->loadObjectList();

        //Здесь страшно, надо бы переписать ((
        $return="<select name=\"catid\" class=\"inputbox\" size=\"1\">";
        $cids_arr=array();
        $i2=0;  $i3=0;
    	foreach($cids as $row2) {
            $cids_arr[$i2]['cat_name']=$row2->c_name;
            $cids_arr[$i2]['parent']=$row2->section;
            $cids_arr[$i2]['cid']=$row2->cid;
            $i2++;
    	}

        foreach($sids as $row3) {
            $return .= "<option value=\"\" disabled=\"disabled\" style=\"color:#EF3527;\">" . $row3->title . "</option>";
            foreach($cids_arr as $v){
                if($v['parent']==$row3->id){
                    if($v['cid']==$row->catid){
                        $extra = " selected=\"selected\"";
                    } else {
                        $extra ="";
                    }
                  $return .= "<option value=\"".$row3->id."*".$v['cid']."\"$extra>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- " . $v['cat_name'] . "</option>";
                }
            }
            $i3++;
    	}

        $return .="</select>";
        $lists['catid']=$return;
    }
    //--->>>Строим selectlist для выбора категории, к которой будет принадлежать материал: END---<<<


	// build the html select list for the group access
	$lists['access'] = mosAdminMenus::Access($row);

    $page = new stdClass();
    $page->params = $params;
    $page->access = $access;
    $page->params->page_type = 'item_edit';

    $row->lists = $lists;

    $tags = new contentTags($database);
    $row->tags = implode(',', $tags->load_by($row));

	HTML_content::editContent($row,$page,$task);
}


/**
* Saves the content item an edit form submit
*/
function saveContent($task) {
	global $database,$mainframe,$my;
	global $mosConfig_absolute_path,$mosConfig_offset,$Itemid;

	// simple spoof check security
	josSpoofCheck();
	
 	//права доступа
    $access = new contentAccess();

	$nullDate = $database->getNullDate();
    $row = new mosContent($database);

	if(!$row->bind($_POST)) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// sanitise id field
	$row->id = (int)$row->id;

	$isNew = $row->id < 1;
	if($isNew) {
		// new record
		if(!($access->canEdit || $access->canEditOwn)) {
			mosNotAuth();
			return;
		}

		$row->created = date('Y-m-d H:i:s');
		$row->created_by = $my->id;
	} else {
		// existing record
		if(!($access->canEdit || ($access->canEditOwn && $row->created_by == $my->id))) {
			mosNotAuth();
			return;
		}

		$row->modified = date('Y-m-d H:i:s');
		$row->modified_by = $my->id;
	}



	if(strlen(trim($row->publish_up)) <= 10) {
		$row->publish_up .= ' 00:00:00';
	}
	$row->publish_up = mosFormatDate($row->publish_up,_CURRENT_SERVER_TIME_FORMAT,-
		$mosConfig_offset);

	if(trim($row->publish_down) == 'Никогда' || trim($row->publish_down) == '') {
		$row->publish_down = $nullDate;
	} else {
		if(strlen(trim($row->publish_down)) <= 10) {
			$row->publish_down .= ' 00:00:00';
		}
		$row->publish_down = mosFormatDate($row->publish_down,
			_CURRENT_SERVER_TIME_FORMAT,-$mosConfig_offset);
	}

	// code cleaner for xhtml transitional compliance
	$row->introtext = str_replace('<br>','<br />',$row->introtext);
	$row->fulltext = str_replace('<br>','<br />',$row->fulltext);

	// remove <br /> take being automatically added to empty fulltext
	$length = strlen($row->fulltext) < 9;
	$search = strstr($row->fulltext,'<br />');
	if($length && $search) {
		$row->fulltext = null;
	}

	$row->title = ampReplace($row->title);

	// Publishing state hardening for Authors
    //Участок перенесен в функцию редактирования

    if(isset($_POST['catid'])){
      $catid0=explode('*', $_POST['catid']);
      $row->catid=$catid0[1];
      $row->sectionid=$catid0[0];
    }

	if(!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

    //Подготовка тэгов
    $tags = explode(',', $_POST['tags']);
    $tag = new contentTags($database);
    $tags = $tag->clear_tags($tags);
    $row->metakey = implode(',', $tags);

	$row->version++;
	if(!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

    //Запись тэгов
    $row->obj_type = 'com_content';
    $tag->update($tags, $row);

	// manage frontpage items
	require_once ($mainframe->getPath('class','com_frontpage'));
	$fp = new mosFrontPage($database);

	if(intval(mosGetParam($_REQUEST,'frontpage',0))) {
		// toggles go to first place
		if(!$fp->load((int)$row->id)) {
			// new entry
			$query = "INSERT INTO #__content_frontpage VALUES ( ".(int)$row->id.", 1 )";
			$database->setQuery($query);
			if(!$database->query()) {
				echo "<script> alert('".$database->stderr()."');</script>\n";
				exit();
			}
			$fp->ordering = 1;
		}
	} else {
		// no frontpage mask
		if(!$fp->delete((int)$row->id)) {
			$msg .= $fp->stderr();
		}
		$fp->ordering = 0;
	}
	$fp->updateOrder();

	$row->checkin();
	$row->updateOrder("catid = ".(int)$row->catid);



	$msg = $isNew?_THANK_SUB:_E_ITEM_SAVED;

    if($my->usertype == 'Publisher' || $row->state==1){
        $msg = _THANK_SUB_PUB;
    }

    $page = new stdClass();
    $page->access = $access;
    $page->task = $task;

    if($isNew){
        _after_create_content($row, $page);
    }
    else{
        _after_update_content($row, $page);
    }
}

function _after_create_content($row, $page){
    global $my, $database, $mosConfig_absolute_path;

	// gets section name of item
	$query = "SELECT s.title"
			."\n FROM #__sections AS s"
			."\n WHERE s.scope = 'content'"
			."\n AND s.id = ".(int)$row->sectionid;
	$database->setQuery($query);
	// gets category name of item
	$section = $database->loadResult();

	$query = "SELECT c.title"
			."\n FROM #__categories AS c"
			."\n WHERE c.id = ".(int)$row->catid;
	$database->setQuery($query);
	$category = $database->loadResult();
	$category = stripslashes($category);


		// Отправка сообщения админам о новой записе
		require_once ($mosConfig_absolute_path.'/components/com_messages/messages.class.php');
		$query = "SELECT id"
				."\n FROM #__users"
				."\n WHERE sendEmail = 1";
		$database->setQuery($query);
		$users = $database->loadResultArray();
		foreach($users as $user_id) {
			$msg = new mosMessage($database);
			$msg->send($my->id,$user_id,"Новый объект",sprintf(_ON_NEW_CONTENT,$my->username,$row->title,$section,$category));
		}


    	switch($page->task) {

        //если "Применить"
		case 'apply':
            //возвращаемся на страницу редактирования
            $msg = 'Изменения сохранены. Здесь (ссылка) страница для предпросмотра';
			$link = $_SERVER['HTTP_REFERER'];
			break;

       //если "Сохранить"
		case 'save':
		default:
            //если запись опубликована, даем ссылку на просмотр в обычном режиме
            if($row->state==1){
                $msg = 'Спасибо и все такое';
                $link = 'index.php?option=com_content&task=view&id='.$row->id;
            }
            //иначе - формируем ссылку на предварительный просмотр статьи
            else{
                $msg = 'Материал был успешно добавлен и будет доступен для общего просмотра после проверки модератором. А пока повпечатляйтесь версией для предпросмотра.';
                $link = 'index.php?option=com_content&task=preview&id='.$row->id;
            }

		    break;
	}

    mosRedirect($link,$msg);

}

function _after_update_content($row, $page){
    global $my;

    	switch($page->task) {
		case 'apply':
            $msg = 'Изменения сохранены.';
			$link = $_SERVER['HTTP_REFERER'];
			break;

		case 'save':
		default:
            //если запись опубликована, даем ссылку на просмотр в обычном режиме
            if($row->state==1){
                $msg = 'Все изменения были успешно сохранены';
                $link = 'index.php?option=com_content&task=view&id='.$row->id;
            }
            //иначе - формируем ссылку на предварительный просмотр статьи
            else{
                $msg = 'Внимание! Это версия для предпросмотра. Материал еще не был опубликован на сайте, вероятно, ожидается проверка модератором.';
                $link = 'index.php?option=com_content&task=preview&id='.$row->id;
            }

	}

    mosRedirect($link,$msg);

}


/**
* Cancels an edit operation
* @param database A database connector object
*/
function cancelContent() {
	global $database,$my,$task;
	
 	//права доступа
    $access = new contentAccess();

	$row = new mosContent($database);
	$row->bind($_POST);

	if($access->canEdit || ($access->canEditOwn && $row->created_by == $my->id)) {
		$row->checkin();
	}

	$Itemid = intval(mosGetParam($_POST,'Returnid','0'));

	$referer = strval(mosGetParam($_POST,'referer',''));
	$parts = parse_url($referer);
	parse_str($parts['query'],$query);

	if($task == 'edit' || $task == 'cancel') {
		$Itemid = mosGetParam($_POST,'Returnid','');
		$referer = 'index.php?option=com_content&task=view&id='.$row->id.'&Itemid='.$Itemid;
	}

	if($referer && $row->id) {
		mosRedirect($referer);
	} else {
		mosRedirect('index.php');
	}
}

/**
* Shows the email form for a given content item.
* @param int The content item id
*/
function emailContentForm($uid,$gid) {
	global $database,$mosConfig_showEmail;

	$id = intval(mosGetParam($_REQUEST,'id',0));
	if($id) {
		$query = 'SELECT attribs FROM #__content WHERE `id`='.$id;
		$database->setQuery($query);
		$params = new mosParameters($database->loadResult());
	} else {
		$params = new mosParameters('');
	}
	$email = intval($params->get('email',0));

 	if(!$mosConfig_showEmail && !$email) {
 		echo _NOT_AUTH;
 		return;
	}


	$itemid = intval(mosGetParam($_GET,'itemid',0));

	$now = _CURRENT_SERVER_TIME;
	$nullDate = $database->getNullDate();

	// query to check for state and access levels
	$query = "SELECT a.*, cc.name AS category, s.name AS section, s.published AS sec_pub, cc.published AS cat_pub,".
		"\n  s.access AS sec_access, cc.access AS cat_access, s.id AS sec_id, cc.id as cat_id".
		"\n FROM #__content AS a"."\n LEFT JOIN #__categories AS cc ON cc.id = a.catid".
		"\n LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = 'content'"."\n WHERE a.id = ".(int)
		$uid."\n AND a.state = 1 OR a.state = -1"."\n AND a.access <= ".(int)$gid."\n AND ( a.publish_up = ".
		$database->Quote($nullDate)." OR a.publish_up <= ".$database->Quote($now)." )".
		"\n AND ( a.publish_down = ".$database->Quote($nullDate).
		" OR a.publish_down >= ".$database->Quote($now)." )";
	$database->setQuery($query);
	$row = null;

	if($database->loadObject($row)) {
		/*
		* check whether category is published
		*/
		if(!$row->cat_pub && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section is published
		*/
		if(!$row->sec_pub && $row->sectionid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether category access level allows access
		*/
		if(($row->cat_access > $gid) && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section access level allows access
		*/
		if(($row->sec_access > $gid) && $row->sectionid) {
			mosNotAuth();
			return;
		}

		$query = "SELECT template"."\n FROM #__templates_menu"."\n WHERE client_id = 0".
			"\n AND menuid = 0";
		$database->setQuery($query);
		$template = $database->loadResult();

		HTML_content::emailForm($row->id,$row->title,$template,$itemid);
	} else {
		mosNotAuth();
		return;
	}
}

/**
* Shows the email form for a given content item.
* @param int The content item id
*/
function emailContentSend($uid,$gid) {
	global $database,$mainframe;
	global $mosConfig_sitename,$mosConfig_showEmail;

	$id = intval(mosGetParam($_REQUEST,'id',0));
	if($id) {
		$query = 'SELECT attribs FROM #__content WHERE `id`='.$id;
		$database->setQuery($query);
		$params = new mosParameters($database->loadResult());
	} else {
		$params = new mosParameters('');
	}
	$paramEmail = intval($params->get('email',0));
	if(!$mosConfig_showEmail && !$paramEmail) {
		echo _NOT_AUTH;
		return;
	}

	// simple spoof check security
	josSpoofCheck(1);

	// check for session cookie
	// Session Cookie `name`
	$sessionCookieName = mosMainFrame::sessionCookieName();
	// Get Session Cookie `value`
	$sessioncookie = mosGetParam($_COOKIE,$sessionCookieName,null);

	if(!(strlen($sessioncookie) == 32 || $sessioncookie == '-')) {
		mosErrorAlert(_NOT_AUTH);
	}

	$itemid = intval(mosGetParam($_POST,'itemid',0));
	$now = _CURRENT_SERVER_TIME;
	$nullDate = $database->getNullDate();

	// query to check for state and access levels
	$query = "SELECT a.*, cc.name AS category, s.name AS section, s.published AS sec_pub, cc.published AS cat_pub,".
		"\n  s.access AS sec_access, cc.access AS cat_access, s.id AS sec_id, cc.id as cat_id".
		"\n FROM #__content AS a"."\n LEFT JOIN #__categories AS cc ON cc.id = a.catid".
		"\n LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = 'content'"."\n WHERE a.id = ".(int)
		$uid."\n AND a.state = 1"."\n AND a.access <= ".(int)$gid."\n AND ( a.publish_up = ".
		$database->Quote($nullDate)." OR a.publish_up <= ".$database->Quote($now)." )".
		"\n AND ( a.publish_down = ".$database->Quote($nullDate).
		" OR a.publish_down >= ".$database->Quote($now)." )";
	$database->setQuery($query);
	$row = null;

	if($database->loadObject($row)) {
		/*
		* check whether category is published
		*/
		if(!$row->cat_pub && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section is published
		*/
		if(!$row->sec_pub && $row->sectionid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether category access level allows access
		*/
		if(($row->cat_access > $gid) && $row->catid) {
			mosNotAuth();
			return;
		}
		/*
		* check whether section access level allows access
		*/
		if(($row->sec_access > $gid) && $row->sectionid) {
			mosNotAuth();
			return;
		}

		$email = strval(mosGetParam($_POST,'email',''));
		$yourname = strval(mosGetParam($_POST,'yourname',''));
		$youremail = strval(mosGetParam($_POST,'youremail',''));
		$subject = strval(mosGetParam($_POST,'subject',''));
		if(empty($subject)) {
			$subject = _EMAIL_INFO.' '.$yourname;
		}

		if($uid < 1 || !$email || !$youremail || (JosIsValidEmail($email) == false) || (JosIsValidEmail
			($youremail) == false)) {
			mosErrorAlert(_EMAIL_ERR_NOINFO);
		}

		$query = "SELECT template"."\n FROM #__templates_menu"."\n WHERE client_id = 0".
			"\n AND menuid = 0";
		$database->setQuery($query);
		$template = $database->loadResult();

		// determine Itemid for Item
		if($itemid) {
			$_itemid = '&Itemid='.$itemid;
		} else {
			$itemid = $mainframe->getItemid($uid,0,0);
			$_itemid = '&Itemid='.$itemid;
		}

		// link sent in email
		$link = sefRelToAbs('index.php?option=com_content&task=view&id='.$uid.$_itemid);

		// message text
		$msg = sprintf(_EMAIL_MSG,html_entity_decode($mosConfig_sitename,ENT_QUOTES),$yourname,
			$youremail,$link);

		// mail function
		$success = mosMail($youremail,$yourname,$email,$subject,$msg);
		if(!$success) {
			mosErrorAlert(_EMAIL_ERR_NOINFO);
		}

		HTML_content::emailSent($email,$template);
	} else {
		mosNotAuth();
		return;
	}
}



?>

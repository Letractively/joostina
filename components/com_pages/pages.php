<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

require_once ($mainframe->getPath('front_html'));
require_once ($mainframe->getPath('class'));

class actionsPages {

	public static function index( ) {

		$menu = mosMainFrame::getInstance()->get('menu');
		$params = new mosParameters($menu->params);

		$page = new Pages();
		$page->load( $params->get('page_id',0) );

		mosMainFrame::getInstance()->addMetaTag('description',  $page->meta_description );
		mosMainFrame::getInstance()->addMetaTag('keywords',  $page->meta_keywords );
		mosMainFrame::getInstance()->setPageTitle( $page->title_page );

		pagesHTML::index($page);

		// если для текущего действия аквирован счетчик хитов - то обновим его
		Jhit::$hook['pages::view'] = true;
		Jhit::allow('pages::view') ? Jhit::add('pages', $page->id, 'view') : null;

		
		require_once (mosMainFrame::getInstance()->getPath('class','com_bookmarks'));
		echo Bookmarks::addlink( $page );
	}

	public static function blog( $option, $id, $page, $task ) {
		$obj = new Pages;
		$obj_count = $obj->count( 'WHERE state=1' );

		mosMainFrame::addLib('pager');
		$pager = new Pager( sefRelToAbs('index.php?option=com_pages&task=blog', true ) , $obj_count, 2, 5, '&larr;', '&rarr;' );
		$pager->paginate( $page );

		$param = array(
				'select'=>'id,title',
				'where'=>'state=1',
				'offset'=>$pager->offset,
				'limit'=>$pager->limit,
				'order'=>'id DESC'
		);
		$obj_list = $obj->get_list($param);
		/*
				echo $pager->output;
				echo $pager->defaultCss;
				echo $pager->components['jump_menu'];

				foreach ($obj_list as $obj) {
					echo $obj->title.'<br />';
				}
		*/
	}


	public static function cache() {

		mosMainFrame::addLib('doocache');

		// кеширование php блока
		if (!Doo::cache('front')->getPart('cache', 5)):
			Doo::cache('front')->start('cache');

			echo time(); // тут любые операции echo и прямой вывод HTML кода

			Doo::cache('front')->end();
		endif;
		

		// прямое кеширование модели как реального объекта
		$cache = Doo::cache('php');
		if( !($obj_final = $cache->get('555')))	 {
			$obj_final = new Pages;
			$obj_final->load(8);
			$obj_cachind = $obj_final->tocache();
			$cache->set('555', $obj_cachind, 300);
		}
		
		_xdump($obj_final);

		// кеширование переменной
		$cache = Doo::cache('file');

		$m = new stdClass();
		$m->s = array( 1,2,3 );
		$m->ttttttttttt = 'sdfsdfsdfsd';
		$s = array( 562=>array( 'one'=>'two', 5=>$m ) );

		$cache->setIn('system', 321, $s, 50); // кеш с группой
		$cache->set('123', $m, 300); // прямой кеш

		$r = $cache->get('123'); // получение кеша
	}

}
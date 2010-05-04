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

require_once ($mainframe->getPath('class'));
require_once ($mainframe->getPath('front_html'));

// управлятор
mosMainFrame::addLib('joiadmin');
JoiAdmin::dispatch();

class actionsTags {

	/**
	 * Вывод страниц тэгов
	 * @param <type> $option
	 * @param <type> $page
	 * @param <type> $task
	 * @param <type> $tag
	 */
	public static function index($option, $page, $task, $tag ) {

		$tag = (string) mosGetParam($_GET, 'tag', '');
		$tag = Jstring::clean( urldecode($tag) );

		mosMainFrame::getInstance()->setPageTitle( $tag );

		$tags = new Tags;
		$com_nodes_params = array(
				'group_name' => 'com_pages',
				'group_title' => 'Cnhfybws',
				'table'=>'pages',
				'id'=>'id',
				'title'=>'title',
				'text'=>'text',
				'date'=>'created_at',
				'image'=>'',
				'task'=>'view',
				'url_params'=>'',
				'select'=>'',
				'join'=>'',
				'where'=>'',
				'order'=>'id DESC'
		);
		$search_results_nodes_count = $tags->search_by_type_count($com_nodes_params, $tag);

		// постраничная навигация
		mosMainFrame::addLib('pager');
		$pager = new Pager( sefRelToAbs('index.php?option=com_tags&tag='.$tag, true) , $search_results_nodes_count, 10, 15 );
		$pager->paginate( $page );

		$search_results_nodes = $tags->search_by_type($com_nodes_params, $tag, $pager->offset, $pager->limit );

		tagsHTML::tag_search($tag, $search_results_nodes, $pager);
	}

	/**
	 * Формирование облака тэгов
	 */
	public static function cloud() {

		$tags = new Tags;
		$tags->obj_type = 'com_nodes';
		$tag_arr = $tags->load_by_type();

		$tags_cloud = new tagsCloud($tag_arr);
		$tags_cloud = $tags_cloud->get_cloud('', 400);

		tagsHTML::full_cloud($tags_cloud);
	}
}
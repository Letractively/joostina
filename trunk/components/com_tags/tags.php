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

		$tag = Jstring::clean(urldecode($tag));

		// постраничная навигация
		mosMainFrame::addLib('pager');


		$tags = new joiTags;

		$com_nodes_params = array(
				'group_name' => 'com_nodes',
				'group_title' => 'Статьи',
				'table'=>'node_contents',
				'id'=>'id',
				'title'=>'title',
				'text'=>'introtext',
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

		$pager = new DooPager( sefRelToAbs('index.php?:antisuf=true&option=com_joitags&tag='.$tag, true) , $search_results_nodes_count, 10, 15 );
		$pager->paginate( $page );
		$lo = explode(',', $pager->limit);

		$search_results_nodes = $tags->search_by_type($com_nodes_params, $tag, $lo[0], $lo[1]);


		mosMainFrame::getInstance()->setPageTitle( sprintf('Статьи про %s', $tag)  );

		joiTagsHTML::tag_search($tag, $search_results_nodes, $pager);
	}

	/**
	 * Формирование облака тэгов
	 */
	public static function cloud() {

		$tags = new joiTags;
		$tags->obj_type = 'com_nodes';
		$tag_arr = $tags->load_by_type();

		$tags_cloud = new joiTagsCloud($tag_arr);
		$tags_cloud = $tags_cloud->get_cloud('', 400);

		joiTagsHTML::full_cloud($tags_cloud);

	}

}
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

/**
 * Модель Comments
 */
class Comments extends mosDBTable {
	public $id;
	public $obj_id;
	public $obj_option;
	public $user_id;
	public $user_name;
	public $user_email;
	public $user_ip;
	public $comment_text;
	public $created_at;
	public $state;

	function __construct() {
		$this->mosDBTable('#__comments', 'id');
	}

	public function get_fieldinfo() {
		return array(
				'sid' => array(
						'name' => 'ID',
						'editable' => false,
						'sortable' => false,
						'in_admintable' => true,
						'html_table_element' => 'value',
						'html_table_element_param' => array(
								'width' => '20px',
								'align' => 'center'
						)
				),
				'obj_id' => array(
						'name' => 'ID объекта',
						'editable' => false,
						'sortable' => false,
						'in_admintable' => true,
						'html_table_element' => 'value',
						'html_table_element_param' => array(
								'width' => '20px',
								'align' => 'center'
						)
				),
				'obj_option' => array(
						'name' => 'Тип объекта',
						'editable' => false,
						'sortable' => false,
						'in_admintable' => true,
						'html_table_element' => 'value',
						'html_table_element_param' => array(
								'width' => '50px',
								'align' => 'center'
						)
				),
				'user_id' => array(
						'name' => 'Логин / ID пользователя',
						'editable' => false,
						'sortable' => false,
						'in_admintable' => true,
						'html_table_element' => 'one_from_array',
						'html_table_element_param' => array(
								'align' => 'center',
								'call_from' => 'Comments::get_users_array'
						),
				),
				'user_name' => array(
						'name' => 'Имя пользователя (для неавторизованных)',
						'editable' => false,
						'sortable' => false,
						'in_admintable' => true,
						'html_table_element' => 'value',
						'html_table_element_param' => array(
								'align' => 'center'
						)
				),
				'user_email' => array(
						'name' => 'Email пользователя (для неавторизованных)',
						'editable' => false,
						'sortable' => false,
						'in_admintable' => true,
						'html_table_element' => 'value',
						'html_table_element_param' => array(
								'width' => '20px',
								'align' => 'center'
						)
				),
				'user_ip' => array(
						'name' => 'IP адрес пользователя',
						'editable' => false,
						'sortable' => false,
						'in_admintable' => true,
						'html_table_element' => 'value',
						'html_table_element_param' => array(
								'width' => '20px',
								'align' => 'center'
						)
				),
				'comment_text' => array(
						'name' => 'Текст комментария',
						'editable' => true,
						'sortable' => true,
						'in_admintable' => true,
						'editlink' => true,
						'html_edit_element' => 'text_area',
						'html_edit_element_param' => array(
								'height' => 100,
						),
						'html_table_element' => 'editlink',
						'html_table_element_param' => array(
								'text_limit'=>50,
						)
				),
				'state' => array(
						'name' => 'Состояние',
						'editable' => true,
						'sortable' => true,
						'in_admintable' => true,
						'editlink' => true,
						'html_edit_element' => 'checkbox',
						'html_table_element' => 'state_box',
						'html_edit_element_param' => array(
								'text' => 'Опубликовано',
						),
						'html_table_element' => 'statuschanger',
						'html_table_element_param' => array(
								'statuses' => array(
										0 => 'Скрыто',
										1 => 'Опубликовано'
								),
								'images' => array(
										0 => 'publish_x.png',
										1 => 'publish_g.png',
								),
								'align' => 'center',
								'class' => 'td-state-joiadmin',
						)
				),
		);
	}

	/**
	 * Информация для страниц вывода данных о комментариях
	 * @return array массив информации дял построителя интерфейса
	 */
	public function get_tableinfo() {
		return array(
				'header_list' => 'Комментарии',
				'header_new' => 'Создание комментария',
				'header_edit' => 'Редактирование комментария'
		);
	}

	public function after_insert() {
		$this->update_counters();
		return true;
	}

	public static function get_users_array() {
		$obj = new mosUser();
		return $obj->get_selector(array('key' => 'id', 'value' => 'username'), array('select' => 'id, title'));
	}

	/**
	 * Первая загрузка комментариев
	 * Загружаем первую страницу с комментариями и инициализируем пагинацию
	 * @var string $obj_option Тип объекта (компонент)
	 * @var integer $obj_id ID комментируемого объекта
	 * @var integer $limit Количество комменариев на страницу
	 * @var integer $visible_pages Количество кнопок с номерами страниц в видимой части пагинатора
	 */
	public function load_comments( $obj, $limit = 10, $visible_pages = 10) {

		$mf = mosMainFrame::getInstance();

		$this->obj_option = get_class($obj);
		$this->obj_id = $obj->{$obj->_tbl_key}; // настоящая уличная магия

		//Подключаем пагинацию
		$mf->addJS(JPATH_SITE . '/includes/libraries/ajaxpager/media/js/jquery.paginate.js');
		$mf->addCSS(JPATH_SITE . '/includes/libraries/ajaxpager/media/css/ajaxpager.css');

		//JS объявления, необходимые для загрузки первой страницы комментариев
		$script = "<script type=\"text/javascript\">
	        var _comments_objoption = '$this->obj_option';
	        var _comments_objid = $this->obj_id;
            var _comments_limit = $limit;
            var _comments_display = $visible_pages;
		</script>";
		$mf->addCustomHeadTag($script);

		//В подключаемом скрипте находится get запрос на вывод первой страницы комментариев
		//Запрос идет в функцию `com_joicomments()` в joicomments.ajax.php
		$mf->addJS(JPATH_SITE . '/components/com_comments/media/js/comments.js');
	}

	/**
	 * Получение списка комментариев
	 * @param integer $offset смещение
	 * @param integer $limit лимит для постранички
	 * @return array массив объектов комментариев
	 */
	public function get_comments($offset = 0, $limit = 0) {
		$sql = 'SELECT c.*, u.username
            FROM #__comments AS c
            LEFT JOIN `#__users` AS u ON (u.id = c.user_id)
            WHERE  c.state=1 AND c.obj_option = \''.$this->obj_option.'\' AND c.obj_id = \''.$this->obj_id.'\'
            GROUP BY c.id
            ORDER BY c.created_at DESC';
		return $this->_db->setQuery($sql, $offset, $limit)->loadObjectList();
	}

	private function update_counters() {
		$sql = sprintf("INSERT INTO `#__comments_counter` (`obj_id`, `obj_option`, `last_user_id`, `last_comment_id`,`counter`)
            VALUES (%s, '%s', %s, %s,1)
            ON DUPLICATE KEY UPDATE counter=counter+1;",
				$this->obj_id, $this->obj_option, $this->user_id, $this->id);
		return database::getInstance()->setQuery($sql)->query();
	}

	public static function get_counters($obj) {
		$r = new stdClass(); // new self
		$r->count = rand(1,1000);
		$r->last_user_id = rand(1,10);
		$r->last_comment_id = rand(1,1000);
		
		mosMainFrame::addLib('text');
		$r->count_text = Text::declension($r->count, array('комментарий','комментария','комментариев') );
		
		return $r;
	}

}
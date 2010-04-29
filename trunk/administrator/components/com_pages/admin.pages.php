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

require_once ($mainframe->getPath('admin_html'));
require_once ($mainframe->getPath('class'));

mosMainFrame::addLib('joiadmin');

JoiAdmin::dispatch();

/**
 * Содержимое
 */
class actionsPages {

    /**
     * Название обрабатываемой модели
     * @var mosDBTable модель
     */
    public static $model = 'Pages';

    /**
     * Список объектов
     */
    public static function index( $option ) {
        $obj = new self::$model;
        $obj_count = $obj->count();

        $pagenav = JoiAdmin::pagenav( $obj_count , $option );

        $param = array(
                'offset'=>$pagenav->limitstart,
                'limit'=>$pagenav->limit,
                'order'=>'id DESC'
        );
        $obj_list = $obj->get_list($param);

        // передаём данные в представление
        thisHTML::index( $obj, $obj_list, $pagenav );
    }

    /**
     * Редактирование
     */
    public static function create( $option ) {
        self::edit( $option, 0 );
    }

    /**
     * Редактирование объекта
     * @param integer $id - номер редактируемого объекта
     */
    public static function edit( $option, $id ) {
        $obj_data = new self::$model;
        $obj_data->load( $id );

        thisHTML::edit( $obj_data, $obj_data);
    }

    /**
     * Сохранение отредактированного или созданного объекта
     */
    public static function save( $option ) {
        josSpoofCheck();

        $obj_data = new self::$model;
        $obj_data->save($_POST);

        $create_new ? mosRedirect( 'index2.php?option='.$option.'&task=new', 'Сохранено успешно!, Создаём новое' ) : mosRedirect( 'index2.php?option='.$option , 'Сохранено успешно!');
    }

    /**
     * Удаление одного или группы объектов
     */
    public static function remove( $option ) {
        josSpoofCheck();

        // идентификаторы удаляемых объектов
        $cid = (array) josGetArrayInts('cid');
        
        $obj_data = new self::$model;
        $obj_data->delete_array( $cid, 'id') ?  mosRedirect( 'index2.php?option='.$option, 'Удалено успешно!') : mosRedirect( 'index2.php?option='.$option , 'Ошибка удаления');
    }
}
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

/*
 * Класс формирования представлений
 */
class thisHTML {

    /**
     * Список объектов
     * @param mosDBTable $obj - основной объект отображения
     * @param array $obj_list - список объектов вывода
     * @param mosPageNav $pagenav - объект постраничной навигации
     */
    public static function index( $obj, $obj_list, $pagenav) {
        // массив названий элементов для отображения в таблице списка
        $fields_list = array( 'id', 'username', 'lastvisitDate', 'state');
        // передаём информацию о объекте и настройки полей в формирование представления
        JoiAdmin::listing( $obj, $obj_list, $pagenav, $fields_list );
    }

    /**
     * Редактирование-создание объекта
     * @param mosDBTable $obj - объект  редактирования с данными, либо пустой - при создании
     * @param stdClass $obj_data - свойства объекта
     */
    public static function edit( mosDBTable $obj, $obj_data ) {
        // передаём данные в формирование представления
        JoiAdmin::edit($obj, $obj_data);
    }
}

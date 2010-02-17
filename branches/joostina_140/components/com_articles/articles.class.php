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

class Articles extends mosDBTable {
    public $id;
    public $cat_id;
    public $game_id;
    public $title;
    public $slug;
    public $anons;
    public $image;
    public $text;
    public $source;
    public $created;
    public $user_id;
    public $state;
    public $game_score;
    public $recommend;
    public $meta_description;
    public $meta_keywords;

    function  __construct() {
        $this->mosDBTable('#__articles','id');
    }

    public function get_fieldinfo() {
        return array(
                'id'=>array(
                        'name'=>'ID',
                        'editable'=>false,
                        'sortable'=>false,
                        'in_admintable'=>true
                ),
                'cat_id'=>array(
                        'name'=>'Категория',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'html_edit_element'=>'option',
                        'html_edit_element_param'=>array(
                                'options'=>array(
                                        0=>'Action',
                                        1=>'Аркада',
                                        2=>'Головоломка',
                                        3=>'Гонки',
                                        4=>'Инди',
                                        5=>'MMO',
                                        6=>'Приключения',
                                        7=>'РПГ',
                                        8=>'Симулятор',
                                        9=>'Спорт',
                                        10=>'Стратегия'
                                )
                        ),
                ),
                'game_id'=>array(
                        'name'=>'Игра',
                        'editable'=>true,
                        'sortable'=>false,
                        'in_admintable'=>true,
                        'html_edit_element'=>'option',
                        'html_edit_element_param'=>array(
                                'call_from'=>'Articles::get_games_array'
                        ),
                ),
                'title'=>array(
                        'name'=>'Название',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'html_edit_element'=>'edit',
                        'html_table_element'=>'editlink',
                ),
                'slug'=>array(
                        'name'=>'Псевдоним (ссылка)',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'edit',
                ),
                'anons'=>array(
                        'name'=>'Анонс',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'text_area',
                ),
                'image'=>array(
                        'name'=>'Изображение',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'image_view_upload',
                ),
                'text'=>array(
                        'name'=>'Описание',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'text_area',
                ),
                'source'=>array(
                        'name'=>'Источник',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'text',
                        'html_edit_element_param'=>array(
                                'rows'=>7,
                                'cols'=>100
                        )
                ),
                'created'=>array(
                        'name'=>'Создано',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'html_edit_element'=>'dateselect',
                        'html_edit_element_param'=>array(
                                'year_start'=>2000,
                                'year_stop'=>2010
                        )
                ),
                'user_id'=>array(
                        'name'=>'Автор',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'user_selector',
                        'html_edit_element_param'=>array(
                                'user_groop'=>'editor',
                        ),
                ),
                'state'=>array(
                        'name'=>'Состояние',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'checkbox',
                        'html_table_element'=>'state_box',
                        'html_edit_element_param'=>array(
                                'text'=>'Опубликовано',
                        ),
                ),
                'game_score'=>array(
                        'name'=>'Оценка игры',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'text_area',
                ),
                'recommend'=>array(
                        'name'=>'Рекомендации',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'text_area',
                ),
                'meta_description'=>array(
                        'name'=>'META::description',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'text',
                        'html_edit_element_param'=>array(
                                'rows'=>2,
                                'cols'=>100
                        )
                ),
                'meta_keywords'=>array(
                        'name'=>'META::keywords',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'text',
                        'html_edit_element_param'=>array(
                                'rows'=>2,
                                'cols'=>100
                        )
                ),
        );
    }

    public static function get_games_array() {
        return array( 1=>'Игра раз', 2=>'Игра два' );
    }

}

function get_games_array() {
    return array( 1=>'Игра раз', 2=>'Игра два' );
}
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

class Games extends mosDBTable {
    public $id;
    public $name;
    public $name_rus;
    public $slug;
    public $desc;
    public $date;
    public $date_rus;
    public $developer;
    public $publisher;
    public $publisher_rus;
    public $localizer;
    public $site;
    public $site_rus;
    public $min_req;
    public $recom_req;
    public $state;
    public $our_choise;
    public $meta_description;
    public $meta_keywords;

    function  __construct() {
        $this->mosDBTable('#__games','id');
    }

    public function get_fieldinfo() {
        return array(
                'id'=>array(
                        'name'=>'ID',
                        'editable'=>false,
                        'sortable'=>false,
                        'in_admintable'=>true
                ),
                'name'=>array(
                        'name'=>'Название',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'html_edit_element'=>'edit',
                        'html_table_element'=>'editlink',
                ),
                'name_rus'=>array(
                        'name'=>'Название в России',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'html_edit_element'=>'edit',
                ),
                'slug'=>array(
                        'name'=>'Псевдоним (ссылка)',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'edit',
                ),
                'desc'=>array(
                        'name'=>'Описание',
                        'editable'=>true,
                        'sortable'=>true,
                        'in_admintable'=>true,
                        'editlink'=>true,
                        'html_edit_element'=>'text_area',
                ),
                'date'=>array(
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
                'date_rus'=>array(
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

}
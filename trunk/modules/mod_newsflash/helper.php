<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();


class mod_newsflash_Helper{

    function prepare_row($row, $params){
    	
		mosMainFrame::getInstance()->addLib('text');

        if($params->get('Itemid', '29')){
            $row->Itemid_link = '&amp;Itemid='.$params->get('Itemid');
        }
        else{
            $row->Itemid_link = '';
        }

        $row->link_on = sefRelToAbs('index.php?option=com_content&amp;task=view&amp;id='.$row->id.$row->Itemid_link);
        $row->link_text = $params->get('link_text', _READ_MORE);
        $readmore = mosContent::ReadMore($row,$params);

        $text = $row->introtext;
        
        //Очищаем текст
        $allowable_tags = array();
        $pair_tags = array('script', 'style', 'map', 'iframe', 'frameset', 'object', 'applet', 'comment', 'button', 'textarea', 'select', 'img');
        $para_tags = array('p', 'td', 'th', 'li','div', 'form', 'title', 'pre');
        $text = Text::strip_tags_smart($text, $allowable_tags, true, $pair_tags, $para_tags);
        
        if($params->get('crop_text')){            
            switch ($params->get('crop_text')) {
                case 'simbol':
                default:
                    $text = Text::character_limiter($text, $params->get('text_limit', 250), '');
                    break;

                case 'word':
                    $text = Text::word_limiter($text, $params->get('text_limit', 25), '');
                    break;
            }
        }
        if($params->get('text')==2){
            $text = '<a href="'.$row->link_on.'">'.$text.'</a>';
        }


        $row->image = '';
        if($params->get('image')){
            mosMainFrame::getInstance()->addLib('images');
            $text_with_image = $row->introtext;
            if($params->get('image')=='mosimage'){
                $text_with_image = $row->images;
            }
            $img = Image::get_image_from_text($text_with_image, $params->get('image', 1), $params->get('image_default',0));
            $row->image = '<img title="'.$row->title.'" alt="" src="'.$img.'" />';

            if($params->get('image_link',0) && $row->image){
                $row->image =  '<a class="thumb" href="'.$row->link_on.'">'.$row->image.'</a>';
            }
        }

        $row->author =  mosContent::Author($row,$params);
        $row->title = HTML_content::Title($row,$params);
        $row->text = $text;
        $row->readmore = $readmore;

        return $row;
    }
}
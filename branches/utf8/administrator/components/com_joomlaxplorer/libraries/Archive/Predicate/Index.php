<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, смотрите LICENSE.php
* Joostina! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Evaluates to true if the index is in a given array of indexes
 * The array has the indexes in key (so you may want to call
 * array_flip if your array has indexes as value)
 *
 * PHP versions 4 and 5
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330,Boston,MA 02111-1307 USA
 *
 * @category   File Formats
 * @package    File_Archive
 * @author     Vincent Lascaux <vincentlascaux@php.net>
 * @copyrieht  1997-2005 The PHP Group
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL
 * @version    CVS: $Id:Index.php 13 2007-05-13 07:10:43Z soeren $
 * @link       http://pear.php.net/package/File_Archive
 */

require_once dirname(__FILE__)."/../Predicate.php";

/**
 * Evaluates to true if the index is in a given array of indexes
 * The array has the indexes in key (so you may want to call
 * array_flip if your array has indexes as value)
 */
class File_Archive_Predicate_Index extends File_Archive_Predicate
{
    var $indexes;
    var $pos = 0;

    /**
     * @param $extensions array or comma separated string of allowed extensions
     */
    function File_Archive_Predicate_Index($indexes)
    {
        $this->indexes = $indexes;
    }
    /**
     * @see File_Archive_Predicate::isTrue()
     */
    function isTrue(&$source)
    {
        return isset($this->indexes[$this->pos++]);
    }
}

?>
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
global $mosConfig_absolute_path;
require_once ($mosConfig_absolute_path . '/includes/libraries/dbconfig/dbconfig.php');



/**
 * Category database table class
 * @package Joostina
 */
class mosCategory extends mosDBTable
{
    /**
     *  *  * @var int Primary key*/
    var $id = null;
    /**
     *  *  * @var int*/
    var $parent_id = null;
    /**
     *  *  * @var string The menu title for the Category (a short name)*/
    var $title = null;
    /**
     *  *  * @var string The full name for the Category*/
    var $name = null;
    /**
     *  *  * @var string*/
    var $image = null;
    /**
     *  *  * @var string*/
    var $section = null;
    /**
     *  *  * @var int*/
    var $image_position = null;
    /**
     *  *  * @var string*/
    var $description = null;
    /**
     *  *  * @var boolean*/
    var $published = null;
    /**
     *  *  * @var boolean*/
    var $checked_out = null;
    /**
     *  *  * @var time*/
    var $checked_out_time = null;
    /**
     *  *  * @var int*/
    var $ordering = null;
    /**
     *  *  * @var int*/
    var $access = null;
    /**
     *  *  * @var string*/
    var $params = null;

    var $templates = null;

    /**
     * @param database A database connector object
     */
    function mosCategory(&$db)
    {
        $this->mosDBTable('#__categories', 'id', $db);
    }
    // overloaded check function
    function check()
    {
        // check for valid name
        if (trim($this->title) == '')
        {
            $this->_error = _ENTER_CATEGORY_TITLE;
            return false;
        }
        if (trim($this->name) == '')
        {
            $this->_error = _ENTER_CATEGORY_NAME;
            return false;
        }
        $ignoreList = array('description');
        $this->filter($ignoreList);
        // check for existing name
        $query = "SELECT id" . "\n FROM #__categories " . "\n WHERE name = " . $this->_db->Quote($this->name) . "\n AND section = " . $this->
            _db->Quote($this->section);
        $this->_db->setQuery($query);

        $xid = intval($this->_db->loadResult());
        if ($xid && $xid != intval($this->id))
        {
            $this->_error = _CATEGORY_ALREADY_EXISTS;
            return false;
        }
        return true;
    }

    function get_category($id)
    {
        $query = 'SELECT cc.* FROM #__categories AS cc WHERE cc.id = ' . $id;
        $r = null;
        $this->_db->setQuery($query);
        $this->_db->loadObject($r);
        return $r;
    }

    function get_category_table_url($params)
    {
        $link = sefRelToAbs('index.php?option=com_content&amp;task=category&amp;sectionid=' . $params->sectionid . '&amp;id=' . $params->
            catid . $params->Itemid);
        return $link;
    }

    function get_category_blog_url($params)
    {
        $link = sefRelToAbs('index.php?option=com_content&amp;task=blogcategory&amp;id=' . $params->catid . $params->Itemid);
        return $link;
    }

    function get_other_cats($category, $access, $params)
    {
        global $my;

        $xwhere = contentSqlHelper::construct_where_table_category($category, $access, $params);
        $xwhere2 = contentSqlHelper::construct_where_other_cats($category, $access, $params);


        // show/hide empty categories
        $empty = '';
        if (!$params->get('empty_cat')) $empty = " HAVING COUNT( a.id ) > 0";

        // get the list of other categories
        $query = "	SELECT c.*, COUNT( a.id ) AS numitems
					FROM #__categories AS c
					LEFT JOIN #__content AS a ON a.catid = c.id 
					" . $xwhere2 . "
					WHERE c.section = '" . (int)$category->section . "'  
					GROUP BY c.id
					" . $empty . "
					ORDER BY c.ordering";
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    function get_lists($params)
    {
        $check = 0;

        $lists['order_value'] = '';
        if ($params->get('selected'))
        {
            $lists['order_value'] = $params->get('selected');
        }

        if ($params->get('date'))
        {
            $order[] = mosHTML::makeOption('date', _ORDER_DROPDOWN_DA);
            $order[] = mosHTML::makeOption('rdate', _ORDER_DROPDOWN_DD);
            $check .= 1;
        }
        if ($params->get('title'))
        {
            $order[] = mosHTML::makeOption('alpha', _ORDER_DROPDOWN_TA);
            $order[] = mosHTML::makeOption('ralpha', _ORDER_DROPDOWN_TD);
            $check .= 1;
        }
        if ($params->get('hits'))
        {
            $order[] = mosHTML::makeOption('hits', _ORDER_DROPDOWN_HA);
            $order[] = mosHTML::makeOption('rhits', _ORDER_DROPDOWN_HD);
            $check .= 1;
        }
        if ($params->get('author'))
        {
            $order[] = mosHTML::makeOption('author', _ORDER_DROPDOWN_AUA);
            $order[] = mosHTML::makeOption('rauthor', _ORDER_DROPDOWN_AUD);
            $check .= 1;
        }

        $order[] = mosHTML::makeOption('order', _ORDER_DROPDOWN_O);
        $lists['order'] = mosHTML::selectList($order, 'order', 'class="inputbox" size="1"  onchange="document.adminForm.submit();"',
            'value', 'text', $params->get('selected'));
        if ($check < 1)
        {
            $lists['order'] = '';
            $params->set('order_select', 0);
        }

        $lists['task'] = 'category';
        $lists['filter'] = $params->get('cur_filter');

        return $lists;

    }
}


/**
 * Section database table class
 * @package Joostina
 */
class mosSection extends mosDBTable
{
    /**
     *  *  * @var int Primary key*/
    var $id = null;
    /**
     *  *  * @var string The menu title for the Section (a short name)*/
    var $title = null;
    /**
     *  *  * @var string The full name for the Section*/
    var $name = null;
    /**
     *  *  * @var string*/
    var $image = null;
    /**
     *  *  * @var string*/
    var $scope = null;
    /**
     *  *  * @var int*/
    var $image_position = null;
    /**
     *  *  * @var string*/
    var $description = null;
    /**
     *  *  * @var boolean*/
    var $published = null;
    /**
     *  *  * @var boolean*/
    var $checked_out = null;
    /**
     *  *  * @var time*/
    var $checked_out_time = null;
    /**
     *  *  * @var int*/
    var $ordering = null;
    /**
     *  *  * @var int*/
    var $access = null;
    /**
     *  *  * @var string*/
    var $params = null;

    var $templates = null;

    /**
     * @param database A database connector object
     */
    function mosSection(&$db)
    {
        $this->mosDBTable('#__sections', 'id', $db);
    }
    // overloaded check function
    function check()
    {
        // check for valid name
        if (trim($this->title) == '')
        {
            $this->_error = _ENTER_SECTION_TITLE;
            return false;
        }
        if (trim($this->name) == '')
        {
            $this->_error = _ENTER_SECTION_NAME;
            return false;
        }
        $ignoreList = array('description');
        $this->filter($ignoreList);
        // check for existing name
        $query = "SELECT id" . "\n FROM #__sections " . "\n WHERE name = " . $this->_db->Quote($this->name) . "\n AND scope = " . $this->
            _db->Quote($this->scope);
        $this->_db->setQuery($query);
        $xid = intval($this->_db->loadResult());
        if ($xid && $xid != intval($this->id))
        {
            $this->_error = _SECTION_ALREADY_EXISTS;
            return false;
        }
        return true;
    }
    
    function _load_table_section($section, $params, $access)
    {
        global $my, $database, $mainframe;  
		
		$gid = $my->gid;
        $noauth = !$mainframe->getCfg('shownoauth');
        $nullDate = $database->getNullDate();
        $now = _CURRENT_SERVER_TIME;
        
        $xwhere = ''; $xwhere2 = '';
       	$empty = '';  $empty_sec = '';
		$access_check = ''; $access_check_content = '';
		        
        //Параметры сортировки
        // Ordering control
        $orderby = contentSqlHelper::_orderby_sec($params->get('orderby'));
		
		//Дополнительные условия
		if($access->canEdit) {
			if($params->get('unpublished')) {
				// shows unpublished items for publishers and above
				$xwhere2 = " AND (b.state >= 0 or b.state is null)";
			} else {
				// unpublished items NOT shown for publishers and above
				$xwhere2 = " AND (b.state = 1 or b.state is null)";
			}
		} else {
			$xwhere = " AND a.published = 1";
			$xwhere2 = "	AND b.state = 1
							AND ( b.publish_up = ".$database->Quote($nullDate)." OR b.publish_up <= ".$database->Quote($now)." )
							AND ( b.publish_down = ".$database->Quote($nullDate)." OR b.publish_down >= ".$database->Quote($now)." )";
		}	
		if($params->get('type') == 'section') {
			// show/hide empty categories in section
			if(!$params->get('empty_cat_section')) {
				$empty_sec = " HAVING numitems > 0";
			}
		}	
		if($noauth) {
			$access_check = " AND a.access <= ".(int)$gid;
			$access_check_content = " AND ( b.access <= ".(int)$gid." OR b.access is null)";
		}
	
		// Query of categories within section
		$query = "	SELECT a.*, COUNT( b.id ) AS numitems
					FROM #__categories AS a
					LEFT JOIN #__content AS b ON b.catid = a.id
					".$xwhere2."
					WHERE a.section = '".(int)$section->id."'
					".$xwhere
					.$access_check
					.$access_check_content."
					GROUP BY a.id"
					.$empty
					.$empty_sec."
					ORDER BY ". $orderby;
		$this->_db->setQuery($query);
	 	return $this->_db->loadObjectList();
    }
    
    function get_count_all_cats($section, $access, $params){
    	global $my, $mainframe; 
    	
    	$gid = $my->gid;
        $noauth = !$mainframe->getCfg('shownoauth');
        
   		if($noauth) {
			$access_check = " AND a.access <= ".(int)$gid;
			$access_check_content = " AND ( b.access <= ".(int)$gid." OR b.access is null)";
		}
    	
	    $query = "	SELECT count(*) as numCategories
					FROM #__categories as a
					WHERE a.section = '".(int)$section->id."'
					".$access_check;
			$this->_db->setQuery($query);
			return ($this->_db->loadResult()) > 0;	
    }

    function get_section($id)
    {
        $query = 'SELECT s.* FROM #__sections AS s WHERE s.id = ' . $id;
        $r = null;
        $this->_db->setQuery($query);
        $this->_db->loadObject($r);
        return $r;
    }

    function get_section_table_url($params)
    {
        $link = sefRelToAbs('index.php?option=com_content&amp;task=section&amp;id=' . $params->sectionid . $params->Itemid);
        return $link;
    }

    function get_section_blog_url($params)
    {
        $link = sefRelToAbs('index.php?option=com_content&amp;task=blogsection&amp;id=' . $params->sectionid . $params->Itemid);
        return $link;
    }
}

/**
 * Module database table class
 * @package Joostina
 */
class mosContent extends mosDBTable
{
    /**
     *  *  * @var int Primary key*/
    var $id = null;
    /**
     *  *  * @var string*/
    var $title = null;
    /**
     *  *  * @var string*/
    var $title_alias = null;
    /**
     *  *  * @var string*/
    var $introtext = null;
    /**
     *  *  * @var string*/
    var $fulltext = null;
    /**
     *  *  * @var int*/
    var $state = null;
    /**
     *  *  * @var int The id of the category section*/
    var $sectionid = null;
    /**
     *  *  * @var int DEPRECATED*/
    var $mask = null;
    /**
     *  *  * @var int*/
    var $catid = null;
    /**
     *  *  * @var datetime*/
    var $created = null;
    /**
     *  *  * @var int User id*/
    var $created_by = null;
    /**
     *  *  * @var string An alias for the author*/
    var $created_by_alias = null;
    /**
     *  *  * @var datetime*/
    var $modified = null;
    /**
     *  *  * @var int User id*/
    var $modified_by = null;
    /**
     *  *  * @var boolean*/
    var $checked_out = null;
    /**
     *  *  * @var time*/
    var $checked_out_time = null;
    /**
     *  *  * @var datetime*/
    var $frontpage_up = null;
    /**
     *  *  * @var datetime*/
    var $frontpage_down = null;
    /**
     *  *  * @var datetime*/
    var $publish_up = null;
    /**
     *  *  * @var datetime*/
    var $publish_down = null;
    /**
     *  *  * @var string*/
    var $images = null;
    /**
     *  *  * @var string*/
    var $urls = null;
    /**
     *  *  * @var string*/
    var $attribs = null;
    /**
     *  *  * @var int*/
    var $version = null;
    /**
     *  *  * @var int*/
    var $parentid = null;
    /**
     *  *  * @var int*/
    var $ordering = null;
    /**
     *  *  * @var string*/
    var $metakey = null;
    /**
     *  *  * @var string*/
    var $metadesc = null;
    /**
     *  *  * @var int*/
    var $access = null;
    /**
     *  *  * @var int*/
    var $hits = null;
    /**
     *  *  * @var string*/
    var $notetext = null;

    var $templates = null;
    /**
     * @param database A database connector object
     */
    function mosContent(&$db)
    {
        $this->mosDBTable('#__content', 'id', $db);
    }

    /**
     * Validation and filtering
     */
    function check()
    {
        // filter malicious code
        $ignoreList = array('introtext', 'fulltext');
        $this->filter($ignoreList);

        /*
        * TODO: This filter is too rigorous,
        * need to implement more configurable solution
        * // specific filters
        * $iFilter = new InputFilter( null, null, 1, 1 );
        * $this->introtext = trim( $iFilter->process( $this->introtext ) );
        * $this->fulltext =  trim( $iFilter->process( $this->fulltext ) );
        */
        if (trim(str_replace('&nbsp;', '', $this->fulltext)) == '')
        {
            $this->fulltext = '';
        }
        return true;
    }

    function get_item($id)
    {

        $sql = 'SELECT  item.*,
                        s.name AS section_name, s.params AS section_params, s.templates as s_templates,
                        c.name AS cat_name, c.params AS cat_params,
                        author.username AS author_nickname, author.name AS author_name,
                        modifier.username AS modifier_nickname, modifier.name AS modifier_name
                FROM #__content AS item
                LEFT JOIN #__sections AS s ON s.id = item.sectionid
                LEFT JOIN #__categories AS c ON c.id = item.catid
                LEFT JOIN #__users AS author ON author.id = item.created_by
                LEFT JOIN #__users AS modifier ON modifier.id = item.modified_by
                WHERE item.id=' . $id;
        $r = null;
        $this->_db->setQuery($sql);
        $this->_db->loadObject($r);
        return $r;
    }

    function load_user_items($user_id, $limitstart = 0, $limit = 50, $orderby = 'a.created DESC', $and = '')
    {
        $query = "  SELECT  a.sectionid, a.checked_out, a.id, a.state AS published,
                        a.title, a.hits, a.created_by, a.created_by_alias,
                        a.created AS created, a.access, a.state,
                        u.name AS author, u.usertype, u.username,
                        g.name AS groups,
                        c.name AS category,
                        s.name AS section
                FROM #__content AS a
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__groups AS g ON a.access = g.id
                LEFT JOIN #__categories AS c on a.catid = c.id
                LEFT JOIN #__sections AS s on s.id = c.section
                WHERE   a.created_by = $user_id
                        AND a.state > -1
                        " . $and . "
                ORDER BY $orderby
            ";

        $this->_db->setQuery($query, $limitstart, $limit);
        return $this->_db->loadObjectList();
    }

    function load_count_user_items($user_id, $and = '')
    {

        $query = "  SELECT COUNT(a.id)
                    FROM #__content AS a
                    LEFT JOIN #__users AS u ON u.id = a.created_by
                    LEFT JOIN #__groups AS g ON a.access = g.id
                    LEFT JOIN #__categories AS c on a.catid = c.id
                    LEFT JOIN #__sections AS s on s.id = c.section
                    WHERE a.created_by = $user_id AND a.state > -1
                    " . $and;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    /**
     * Converts record to XML
     * @param boolean Map foreign keys to text values
     */
    function toXML($mapKeysToText = false)
    {
        global $database;

        if ($mapKeysToText)
        {
            $query = "SELECT name FROM #__sections WHERE id = " . (int)$this->sectionid;
            $database->setQuery($query);
            $this->sectionid = $database->loadResult();

            $query = "SELECT name FROM #__categories WHERE id = " . (int)$this->catid;
            $database->setQuery($query);
            $this->catid = $database->loadResult();

            $query = "SELECT name FROM #__users WHERE id = " . (int)$this->created_by;
            $database->setQuery($query);
            $this->created_by = $database->loadResult();
        }

        return parent::toXML($mapKeysToText);
    }

    function ReadMore(&$row, &$params, $template = '')
    {
        $return = '';
        if ($params->get('readmore'))
        {
            if ($params->get('intro_only') && $row->link_text)
            {
                $return = '<a href="' . $row->link_on . '" title="' . $row->title . '" class="readon">' . $row->link_text . '</a>';
            }
        }
        return $return;
    }

    function _construct_where_for_fullItem($access)
    {
        global $database, $gid, $task, $mosConfig_disable_date_state, $mosConfig_disable_access_control;

        $now = _CURRENT_SERVER_TIME;
        $nullDate = $database->getNullDate();

        $where_ac = '';

        if ($access->canEdit || $task == 'preview')
        {
            $xwhere = '';
        } else
        {
            $xwhere = ' AND ( a.state = 1 OR a.state = -1 ) ';
            if (!$mosConfig_disable_date_state)
            {
                $xwhere .= " AND ( a.publish_up = " . $database->Quote($nullDate) . " OR a.publish_up <= " . $database->Quote($now) . " )";
                $xwhere .= " AND ( a.publish_down = " . $database->Quote($nullDate) . " OR a.publish_down >= " . $database->Quote($now) . " )";
            }
        }

        if (!$mosConfig_disable_access_control)
        {
            $where_ac = ' AND a.access <= ' . (int)$gid;
        }

        return $xwhere . $where_ac;
    }

    function get_prev_next($row, $where, $access, $params)
    {
        global $mainframe, $mosConfig_disable_access_control, $gid, $database;

        // Paramters for menu item as determined by controlling Itemid
        
		if($params->get('pop')){
  			$row->prev = '';
        	$row->next = '';
        	return $row;		
        }
		
		$menu = $mainframe->get('menu');
 		$mparams = new mosParameters($menu->params);
        // the following is needed as different menu items types utilise a different param to control ordering
        // for Blogs the `orderby_sec` param is the order controlling param
        // for Table and List views it is the `orderby` param
        $mparams_list = $mparams->toArray();
        if (array_key_exists('orderby_sec', $mparams_list))
        {
            $order_method = $mparams->get('orderby_sec', '');
        } else
        {
            $order_method = $mparams->get('orderby', '');
        }

        // additional check for invalid sort ordering
        if ($order_method == 'front')
        {
            $order_method = '';
        }
        
        $orderby = contentSqlHelper::_orderby_sec($order_method);

        $uname = '';
        $ufrom = '';
        if ($order_method == 'author' or $order_method == 'rauthor')
        {
            $uname = ', u.name ';
            $ufrom = ', #__users AS u ';
        }

        // array of content items in same category correctly ordered
        $query = "  SELECT a.id, a.title $uname
                        FROM #__content AS a $ufrom
                        WHERE a.catid = " . (int)$row->catid . " AND a.state = " . (int)$row->state . $where . "
                        ORDER BY $orderby";
        $database->setQuery($query);
        $list = $database->loadObjectList();

        $prev = null;
        $current = array_shift($list);
        $next = array_shift($list);
        while ($current->id != $row->id)
        {
            $prev = $current;
            $current = $next;
            $next = array_shift($list);
        }
        $row->prev = '';
        $row->next = '';
        if (!empty($prev))
        {
            $row->prev = $prev->id;
            $row->prev_title = $prev->title;
        }
        if (!empty($next))
        {
            $row->next = $next->id;
            $row->next_title = $next->title;
        }
        unset($list);

        return $row;
    }

    function Author(&$row, &$params = '')
    {
        global $mosConfig_absolute_path, $database, $mainframe, $mosConfig_author_name;
        $author_name = '';
        if (!$params)
        {
            return $row->username;
        }

        if ($row->author != '')
        {
            if (!$row->created_by_alias)
            {

                if ($params->get('author_name', 0))
                {
                    $switcher = $params->get('author_name');
                } else
                {
                    $switcher = $mosConfig_author_name;
                }

                switch ($switcher)
                {
                    case '1':
                    case '3':
                        $author_name = $row->author;
                        break;

                    case '2':
                    case '4':
                    default;
                        $author_name = $row->username;
                        break;
                }

                if ($switcher == '3' || $switcher == '4')
                {
                    $uid = $row->created_by;
                    $author_link = 'index.php?option=com_user&amp;task=profile&amp;user=' . $uid;
                    $author_seflink = sefRelToAbs($author_link);
                    $author_name = '<a href="' . $author_seflink . '">' . $author_name . '</a>';
                }

            } else
            {
                $author_name = $row->created_by_alias;
            }

        }
        return $author_name;
    }

    function EditIcon2(&$row, &$params, &$access, $text='')
    {
        global $my;

        if ($params->get('popup'))
        {
            return;
        }
        if ($row->state < 0)
        {
            return;
        }
        if (!$access->canEdit && !($access->canEditOwn && $row->created_by == $my->id))
        {
            return;
        }

        mosCommonHTML::loadJqueryPlugins('tooltip/jquery.tooltip', false, true);
        ?>
		<script language="JavaScript" type="text/javascript">
            _comcontent_defines.push('load_tooltip');
        </script>
		<?php
		
        $link = 'index.php?option=com_content&amp;task=edit&amp;id=' . $row->id . $row->Itemid_link . '&amp;Returnid=' . $row->_Itemid;
        $image = mosCommonHTML::get_element('edit.png');
        $image = Jconfig::getInstance()->config_live_site.'/'.$image;

        if ($row->state == 0)
        {
            $info = _CMN_UNPUBLISHED;
        } else
        {
            $info = _CMN_PUBLISHED;
        }
        $date = mosFormatDate($row->created);
        $author = $row->created_by_alias ? $row->created_by_alias : $row->author;


        $info .= ' - ';
        $info .= $date;
        $info .= '&rarr;';
        $info .= $author;
        

        $return = '<span class="button"><a class="button edit_button" href="' . sefRelToAbs($link) . '" title="'.$info.'" ><img src="' . $image . '" /> '.$text.'</a></span>';

        return $return;
    }
    
    function check_archives_categories($category, $params){
   
	if($params->get('module')) {
		$check = '';
	} else {
		$check = " AND a.catid = ".(int)$category->id;
	}
	
	$query = "SELECT COUNT(a.id)"."\n FROM #__content as a"."\n WHERE a.state = -1".$check;
	$this->_db->setQuery($query);
	return $this->_db->loadResult();	
    }

    function _load_blog_section($section, $params, $access)
    {

        // voting control
        $voting = new contentVoiting($params);
        $voting = $voting->_construct_sql();

        //Дополнительные условия
        $where = contentSqlHelper::construct_where_blog(1, $section, $access, $params);
        $where = (count($where) ? "\n WHERE " . implode("\n AND ", $where) : '');

        //Параметры сортировки
        $order_sec = contentSqlHelper::_orderby_sec($params->get('orderby_sec'));
        $order_pri = contentSqlHelper::_orderby_pri($params->get('orderby_pri'));

        //Основной запрос
        $query = '  SELECT  a.id, a.attribs , a.title, a.title_alias, a.introtext, a.sectionid,
                        a.state, a.catid, a.created, a.created_by, a.created_by_alias, a.modified, a.modified_by,
                        a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, a.images, a.urls, a.ordering,
                        a.fulltext, a.notetext,
                        a.metakey, a.metadesc, a.access, CHAR_LENGTH( a.fulltext ) AS readmore,
                        u.name AS author, u.usertype, u.username,
                        s.name AS section,
                        cc.name AS category,
                        g.name AS groups
                        ' . $voting['select'] . '
                FROM #__content AS a
                INNER JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                ' . $voting['join'] . $where . '
                ORDER BY ' . $order_pri . $order_sec;

        $this->_db->setQuery($query, $params->get('limitstart'), $params->get('limit'));
        return $this->_db->loadObjectList();
    }

    function _get_result_blog_section($section, $params, $access)
    {
        $where = contentSqlHelper::construct_where_blog(1, $section, $access, $params);
        $where = (count($where) ? "\n WHERE " . implode("\n AND ", $where) : '');

        $query = "  SELECT COUNT(a.id)
                FROM #__content AS a
                INNER JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                " . $where;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    function _load_blog_category($category, $params, $access)
    {

        // voting control
        $voting = new contentVoiting($params);
        $voting = $voting->_construct_sql();

        //Дополнительные условия
        $where = contentSqlHelper::construct_where_blog(2, $category, $access, $params);
        $where = (count($where) ? " WHERE " . implode("\n AND ", $where) : '');

        //Параметры сортировки
        $order_sec = contentSqlHelper::_orderby_sec($params->get('orderby_sec'));
        $order_pri = contentSqlHelper::_orderby_pri($params->get('orderby_pri'));

        //Основной запрос
        $query = '  SELECT a.id, a.notetext,a.attribs, a.title, a.title_alias, a.introtext,
                    a.sectionid, a.state, a.catid, a.created, a.created_by, a.created_by_alias,
                    a.modified, a.modified_by, a.checked_out, a.checked_out_time,
                    a.publish_up, a.publish_down, a.images, a.urls, a.ordering, a.metakey, a.metadesc, a.access,
                    CHAR_LENGTH( a.fulltext ) AS readmore,
                    s.published AS sec_pub, s.name AS section,
                    cc.published AS sec_pub, cc.name AS category,
                    u.name AS author, u.usertype, u.username,
                    g.name AS groups
                    ' . $voting['select'] . '
                FROM #__content AS a
                LEFT JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                ' . $voting['join'] . $where . '
                ORDER BY ' . $order_pri . $order_sec;

        $this->_db->setQuery($query, $params->get('limitstart'), $params->get('limit'));
        return $this->_db->loadObjectList();
    }

    function _get_result_blog_category($category, $params, $access)
    {

        $where = contentSqlHelper::construct_where_blog(2, $category, $access, $params);
        $where = (count($where) ? "\n WHERE " . implode("\n AND ", $where) : '');

        $query = '  SELECT COUNT(a.id)
                    FROM #__content AS a
                    LEFT JOIN #__categories AS cc ON cc.id = a.catid
                    LEFT JOIN #__users AS u ON u.id = a.created_by
                    LEFT JOIN #__sections AS s ON a.sectionid = s.id
                    LEFT JOIN #__groups AS g ON a.access = g.id
                    ' . $where;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();

    }
    
    function _get_result_archive_section($section, $params, $access)
    {
        $where = contentSqlHelper::construct_where_blog(-1, $section, $access, $params);
        $where = (count($where) ? " WHERE " . implode(" AND ", $where) : '');

       	// query to determine total number of records
		$query = "	SELECT COUNT(a.id)
					FROM #__content AS a
					INNER JOIN #__categories AS cc ON cc.id = a.catid
					LEFT JOIN #__users AS u ON u.id = a.created_by
					LEFT JOIN #__sections AS s ON a.sectionid = s.id
					LEFT JOIN #__groups AS g ON a.access = g.id
					".$where;
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
    }
    
    function _load_archive_section($section, $params, $access)
    {

        // voting control
        $voting = new contentVoiting($params);
        $voting = $voting->_construct_sql();

        //Дополнительные условия
        $where = contentSqlHelper::construct_where_blog(-1, $section, $access, $params);
        $where = (count($where) ? " WHERE " . implode(" AND ", $where) : '');

        //Параметры сортировки
        $order_sec = contentSqlHelper::_orderby_sec($params->get('orderby_sec'));
        $order_pri = contentSqlHelper::_orderby_pri($params->get('orderby_pri'));

        //Основной запрос
       	// Main Query
		$query = "	SELECT 	a.id, a.title, a.title_alias, a.introtext, a.sectionid, 
							a.state, a.catid, a.created, a.created_by, a.created_by_alias, 
							a.modified, a.modified_by, a.checked_out, a.checked_out_time, 
							a.publish_up, a.publish_down, a.images, a.urls, a.ordering, 
							a.metakey, a.metadesc, a.access, a.attribs,
							CHAR_LENGTH( a.fulltext ) AS readmore, 
							u.name AS author, u.usertype, 
							s.name AS section, 
							cc.name AS category, 
							g.name AS groups
							".$voting['select']."
					FROM #__content AS a
					INNER JOIN #__categories AS cc ON cc.id = a.catid
					LEFT JOIN #__users AS u ON u.id = a.created_by
					LEFT JOIN #__sections AS s ON a.sectionid = s.id
					LEFT JOIN #__groups AS g ON a.access = g.id
					".$voting['join']
					.$where."
					ORDER BY ". $order_pri.$order_sec;
        $this->_db->setQuery($query, $params->get('limitstart'), $params->get('limit'));
        return $this->_db->loadObjectList();
    }
    
    function _get_result_blog_archive_category($category, $params, $access)
    {

        $where = contentSqlHelper::construct_where_blog(-2, $category, $access, $params);
        $where = (count($where) ? "\n WHERE " . implode("\n AND ", $where) : '');


       	// query to determine total number of records
		$query = "  SELECT COUNT(a.id)
                FROM #__content AS a
                INNER JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                ".$where;
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
    }
    
    function _load_blog_archive_category($category, $params, $access)
    {

        // voting control
        $voting = new contentVoiting($params);
        $voting = $voting->_construct_sql();

        //Дополнительные условия
        $where = contentSqlHelper::construct_where_blog(-2, $category, $access, $params);
        $where = (count($where) ? " WHERE " . implode("\n AND ", $where) : '');

        //Параметры сортировки
        $order_sec = contentSqlHelper::_orderby_sec($params->get('orderby'));

        //Основной запрос
        // main query
		$query = " SELECT   a.id, a.title, a.title_alias, a.introtext, a.sectionid, a.state, a.catid,
                        a.created, a.created_by, a.created_by_alias, a.modified, a.modified_by,
                        a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, a.images,
                        a.urls, a.ordering, a.metakey, a.metadesc, a.access, a.attribs,
                        CHAR_LENGTH( a.fulltext ) AS readmore,
                        u.name AS author, u.usertype, u.username,
                        s.name AS section,
                        cc.name AS category,
                        g.name AS groups
                        ".$voting['select']."
                FROM #__content AS a
                INNER JOIN #__categories AS cc ON cc.id = a.catid
                LEFT JOIN #__users AS u ON u.id = a.created_by
                LEFT JOIN #__sections AS s ON a.sectionid = s.id
                LEFT JOIN #__groups AS g ON a.access = g.id
                ".$voting['join']
                .$where."
                ORDER BY ". $order_sec;
        $this->_db->setQuery($query, $params->get('limitstart'), $params->get('limit'));
        return $this->_db->loadObjectList();
    }

    function _load_table_category($category, $params, $access)
    {
        global $my;

        //Дополнительные условия
        $xwhere = contentSqlHelper::construct_where_table_category($category, $access, $params);
        $and = contentSqlHelper::construct_filter_table_category($category, $access, $params);

        //Параметры сортировки
        // Ordering control
        $orderby = contentSqlHelper::_orderby_sec($params->get('orderby'));

        //Основной запрос
        // get the list of items for this category
        $query = '  SELECT  a.id, a.title, a.hits, a.created_by, a.created_by_alias,
                        a.created AS created, a.access, a.state,
                        u.name AS author, u.username,
                        g.name AS groups
                	FROM #__content AS a
                	LEFT JOIN #__users AS u ON u.id = a.created_by
                	LEFT JOIN #__groups AS g ON a.access = g.id
                	WHERE 	a.catid = ' . (int)$category->id . $xwhere . '
							AND ' . (int)$category->access . ' <= ' . (int)$my->gid . $and . '
					ORDER BY ' . $orderby;
        $this->_db->setQuery($query, $params->get('limitstart'), $params->get('limit'));
        return $this->_db->loadObjectList();
    }

    function _get_result_table_category($category, $params, $access)
    {

        $xwhere = contentSqlHelper::construct_where_table_category($category, $access, $params);
        $and = contentSqlHelper::construct_filter_table_category($category, $access, $params);

        $query = '	SELECT COUNT(a.id) as numitems
					FROM #__content AS a
					LEFT JOIN #__users AS u ON u.id = a.created_by
					LEFT JOIN #__groups AS g ON a.access = g.id
					WHERE a.catid = ' . (int)$category->id . $xwhere . $and;
        $this->_db->setQuery($query);
        $counter = $this->_db->loadObjectList();
        $total = $counter[0]->numitems;

        return $total;
    }


    function _load_frontpage($params, $access)
    {

        // voting control
        $voting = new contentVoiting($params);
        $voting = $voting->_construct_sql();

        //Дополнительные условия
        $where = contentSqlHelper::construct_where_blog(1, null, $access, $params);
        $where = (count($where) ? "\n WHERE " . implode("\n AND ", $where) : '');

        //Параметры сортировки
        $order_sec = contentSqlHelper::_orderby_sec($params->get('orderby_sec'));
        $order_pri = contentSqlHelper::_orderby_pri($params->get('orderby_pri'));

        //Основной запрос
        $query = '  SELECT
                        a.attribs, a.notetext, a.id, a.title, a.title_alias,
                        a.introtext, a.sectionid, a.state, a.catid, a.created,
                        a.created_by, a.created_by_alias, a.modified, a.modified_by,
                        a.checked_out, a.checked_out_time, a.publish_up, a.publish_down,
                        a.images, a.urls, a.ordering, a.metakey, a.metadesc, a.access, a.hits,
                        CHAR_LENGTH( a.fulltext ) AS readmore,
                        u.name AS author, u.usertype, u.username,
                        s.name AS section, s.id AS sec_id,
                        cc.name AS category, cc.id as cat_id,
                        g.name AS groups
                        ' . $voting['select'] . '
                    FROM #__content AS a
                    INNER JOIN #__content_frontpage AS f ON f.content_id = a.id
                    INNER JOIN #__categories AS cc ON cc.id = a.catid
                    INNER JOIN #__sections AS s ON s.id = a.sectionid
                    LEFT JOIN #__users AS u ON u.id = a.created_by
                    LEFT JOIN #__groups AS g ON a.access = g.id
                    ' . $voting['join'] . $where . '
                    ORDER BY ' . $order_pri . $order_sec;

        $this->_db->setQuery($query, $params->get('limitstart'), $params->get('limit'));
        return $this->_db->loadObjectList();
    }

    function _get_result_frontpage($params, $access)
    {
        $where = contentSqlHelper::construct_where_blog(1, null, $access, $params);
        $where = (count($where) ? "\n WHERE " . implode("\n AND ", $where) : '');

        $query = '  SELECT COUNT(a.id)
                    FROM #__content AS a
                    INNER JOIN #__content_frontpage AS f ON f.content_id = a.id
                    INNER JOIN #__categories AS cc ON cc.id = a.catid
                    INNER JOIN #__sections AS s ON s.id = a.sectionid
                    LEFT JOIN #__users AS u ON u.id = a.created_by
                    LEFT JOIN #__groups AS g ON a.access = g.id
                    ' . $where;

        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }
}

class contentMeta
{
    var $_params = null;


    function contentMeta($params)
    {
        $this->_params = $params;
    }

    function set_meta()
    {

        switch ($this->_params->page_type)
        {
            case 'section_blog':
            case 'category_blog':
            case 'frontpage':
            default:
                $this->_meta_blog();
                break;
                
            case 'item_full':
            case 'item_static':
            	$this->_meta_item();
            	break;

        }
    }

    function _meta_blog()
    {
        global $mainframe, $mosConfig_MetaDesc, $mosConfig_MetaKeys;

        if ($this->_params->menu)
        {
            if (trim($this->_params->get('page_name')))
            {
                $mainframe->SetPageTitle($this->_params->menu->name, $this->_params);
            } else
                if ($this->_params->get('header') != '')
                {
                    $mainframe->SetPageTitle($this->_params->get('header', 1), $this->_params);
                } else
                {
                    $mainframe->SetPageTitle($this->_params->menu->name, $this->_params);
                }
        }

        set_robot_metatag($this->_params->get('robots'));

        if ($this->_params->get('meta_description') != "")
        {
            $mainframe->addMetaTag('description', $this->_params->get('meta_description'));
        } else
        {
            $mainframe->addMetaTag('description', $mosConfig_MetaDesc);
        }
        if ($this->_params->get('meta_keywords') != "")
        {
            $mainframe->addMetaTag('keywords', $this->_params->get('meta_keywords'));
        } else
        {
            $mainframe->addMetaTag('keywords', $mosConfig_MetaKeys);
        }
        if ($this->_params->get('meta_author') != "")
        {
            $mainframe->addMetaTag('author', $this->_params->get('meta_author'));
        }
    }
    
    function _meta_item(){
    	global $mainframe, $mosConfig_MetaDesc, $mosConfig_MetaKeys, $mosConfig_MetaTitle, $mosConfig_MetaAuthor;
    	$row = $this->_params->object;
    	
		$mainframe->setPageTitle($row->title,$this->_params);

		if($mosConfig_MetaTitle == '1') {
			$mainframe->addMetaTag('title',$row->title);
		}
		if($mosConfig_MetaAuthor == '1') {
			if($row->created_by_alias != "") {
				$mainframe->addMetaTag('author',$row->created_by_alias);
			} else {
				$mainframe->addMetaTag('author',$row->author);
			}

		}
		if($this->_params->get('robots') == 0) {
			$mainframe->addMetaTag('robots','index, follow');
		}
		if($this->_params->get('robots') == 1) {
			$mainframe->addMetaTag('robots','index, nofollow');
		}
		if($this->_params->get('robots') == 2) {
			$mainframe->addMetaTag('robots','noindex, follow');
		}
		if($this->_params->get('robots') == 3) {
			$mainframe->addMetaTag('robots','noindex, nofollow');
		}	
    }
}

class contentVoiting
{

    var $active = null;

    function contentVoiting($params)
    {
        if ($params->get('rating'))
        {
            $this->active = $params->get('rating');
        }
    }

    function _construct_sql()
    {
        global $mainframe;

        $voting = ($this->active ? $this->active : $mainframe->getCfg('vote'));

        if ($voting)
        {
            $select = ', ROUND( v.rating_sum / v.rating_count ) AS rating, v.rating_count';
            $join = ' LEFT JOIN #__content_rating AS v ON a.id = v.content_id';
        } else
        {
            $select = '';
            $join = '';
        }
        $results = array('select' => $select, 'join' => $join);
        return $results;
    }
}

class contentHelper{
	
	function _load_core_js(){
        global $mosConfig_live_site, $mainframe;
        $mainframe->addJS($mosConfig_live_site.'/components/com_content/js/com_content.js','custom'); 
	}
}

class contentSqlHelper
{

    /*
    * @param int 0 = Archives, 1 = Section, 2 = Category
    */
    function construct_where_blog($type = 1, $obj = null, $access, $params = null)
    {
        global $database, $mainframe, $mosConfig_disable_date_state, $mosConfig_disable_access_control, $my;


        $id = 0;
        if ($obj && isset($obj->id))
        {
            $id = $obj->id;
        }

        $gid = $my->gid;
        $noauth = !$mainframe->getCfg('shownoauth');
        $nullDate = $database->getNullDate();
        $now = _CURRENT_SERVER_TIME;
        $where = array();
        $unpublished = 0;

        if (isset($params))
        {
            // param controls whether unpublished items visible to publishers and above
            $unpublished = $params->def('unpublished', 0);
        }

        // normal
        if ($type > 0)
        {
            if (isset($params) && $unpublished)
            {
                // shows unpublished items for publishers and above
                if ($access->canEdit)
                {
                    $where[] = "a.state >= 0";
                } else
                {
                    $where[] = "a.state = 1";
                    if (!$mosConfig_disable_date_state)
                    {
                        $where[] = "( a.publish_up = " . $database->Quote($nullDate) . " OR a.publish_up <= " . $database->Quote($now) . " )";
                        $where[] = "( a.publish_down = " . $database->Quote($nullDate) . " OR a.publish_down >= " . $database->Quote($now) . " )";
                    }
                }
            } else
            {
                // unpublished items NOT shown for publishers and above
                $where[] = "a.state = 1";
                if (!$mosConfig_disable_date_state)
                {
                    $where[] = "( a.publish_up = " . $database->Quote($nullDate) . " OR a.publish_up <= " . $database->Quote($now) . " )";
                    $where[] = "( a.publish_down = " . $database->Quote($nullDate) . " OR a.publish_down >= " . $database->Quote($now) . " )";
                }
            }

            // add query checks for category or section ids
            if ($id > 0)
            {
                $ids = explode(',', $id);
                mosArrayToInts($ids);
                if ($type == 1)
                {
                    $where[] = '( a.sectionid=' . implode(' OR a.sectionid=', $ids) . ' )';
                } else
                    if ($type == 2)
                    {
                        $where[] = '( a.catid=' . implode(' OR a.catid=', $ids) . ' )';
                    }
            }
        }

        // archive
        if ($type < 0)
        {
            $where[] = "a.state = -1";
            if ($params->get('year'))
            {
                $where[] = "YEAR( a.created ) = " . $database->Quote($params->get('year'));
            }
            if ($params->get('month'))
            {
                $where[] = "MONTH( a.created ) = " . $database->Quote($params->get('month'));
            }
            if ($id > 0)
            {
                if ($type == -1)
                {
                    $where[] = "a.sectionid = " . (int)$id;
                } else
                    if ($type == -2 && !$params->get('module'))
                    {
                        $where[] = "a.catid = " . (int)$id;
                    }
            }
        }

        $where[] = "s.published = 1";
        $where[] = "cc.published = 1";
        /* если сессии на фронте отключены - то значит авторизация не возможна, и проверять доступ по авторизации бесполезно*/
        if ($noauth and !$mosConfig_disable_access_control)
        {
            $where[] = "a.access <= " . (int)$gid;
            $where[] = "s.access <= " . (int)$gid;
            $where[] = "cc.access <= " . (int)$gid;
        }

        return $where;
    }

    function construct_where_table_category($category, $access, $params)
    {
        global $database, $mainframe, $my;

        $gid = $my->gid;
        $noauth = !$mainframe->getCfg('shownoauth');
        $nullDate = $database->getNullDate();
        $now = _CURRENT_SERVER_TIME;

        $xwhere = '';

        //where
        if ($access->canEdit)
        {
            if ($params->get('unpublished'))
            {
                // shows unpublished items for publishers and above
                $xwhere .= "\n AND a.state >= 0";
            } else
            {
                // unpublished items NOT shown for publishers and above
                $xwhere .= "\n AND a.state = 1";
            }
        } else
        {
            //$xwhere .= ' AND c.published = 1';
            $xwhere .= "\n AND a.state = 1" . "\n AND ( publish_up = " . $database->Quote($nullDate) . " OR publish_up <= " . $database->
                Quote($now) . " )" . "\n AND ( publish_down = " . $database->Quote($nullDate) . " OR publish_down >= " . $database->Quote($now) .
                " )";
        }

        if ($noauth)
        {
            $xwhere .= ' AND a.access <=' . (int)$gid;
        }

        return $xwhere;

    }

    function construct_filter_table_category($category, $access, $params)
    {
        global $database;

        // filter functionality
        $and = null;
        if ($params->get('filter'))
        {
            if ($params->get('cur_filter'))
            {
                // clean filter variable
                $filter = strtolower($params->get('cur_filter'));

                switch ($params->get('filter_type'))
                {
                    case 'title':
                        $and = "\n AND LOWER( a.title ) LIKE '%" . $database->getEscaped($filter, true) . "%'";
                        break;

                    case 'author':
                        $and = "\n AND ( ( LOWER( u.name ) LIKE '%" . $database->getEscaped($filter, true) .
                            "%' ) OR ( LOWER( a.created_by_alias ) LIKE '%" . $database->getEscaped($filter, true) . "%' ) )";
                        break;

                    case 'hits':
                        $and = "\n AND a.hits LIKE '%" . $database->getEscaped($filter, true) . "%'";
                        break;
                }
            }
        }

        return $and;
    }

    function construct_where_other_cats($category, $access, $params)
    {
        global $database, $mainframe, $my;

        $gid = $my->gid;
        $noauth = !$mainframe->getCfg('shownoauth');
        $nullDate = $database->getNullDate();
        $now = _CURRENT_SERVER_TIME;
        $xwhere2 = '';

        if ($access->canEdit)
        {
            if ($params->get('unpublished'))
            {
                // shows unpublished items for publishers and above
                $xwhere2 = "\n AND a.state >= 0";
            } else
            {
                // unpublished items NOT shown for publishers and above
                $xwhere2 = "\n AND a.state = 1";
            }
        } else
        {

            $xwhere2 = "\n AND a.state = 1" . "\n AND ( a.publish_up = " . $database->Quote($nullDate) . " OR a.publish_up <= " . $database->
                Quote($now) . " )" . "\n AND ( a.publish_down = " . $database->Quote($nullDate) . " OR a.publish_down >= " . $database->Quote($now) .
                " )";
        }

        if ($noauth)
        {
            $xwhere2 .= " AND a.access <= " . (int)$gid;
        }

        return $xwhere2;
    }

    function _orderby_pri($orderby)
    {
        switch ($orderby)
        {
            case 'alpha':
                $orderby = 'cc.title, ';
                break;

            case 'ralpha':
                $orderby = 'cc.title DESC, ';
                break;

            case 'order':
                $orderby = 'cc.ordering, ';
                break;

            default:
                $orderby = '';
                break;
        }

        return $orderby;
    }


    function _orderby_sec($orderby)
    {
        switch ($orderby)
        {
            case 'date':
                $orderby = 'a.created';
                break;

            case 'rdate':
                $orderby = 'a.created DESC';
                break;

            case 'alpha':
                $orderby = 'a.title';
                break;

            case 'ralpha':
                $orderby = 'a.title DESC';
                break;

            case 'hits':
                $orderby = 'a.hits';
                break;

            case 'rhits':
                $orderby = 'a.hits DESC';
                break;

            case 'order':
                $orderby = 'a.ordering';
                break;

            case 'author':
                $orderby = 'a.created_by_alias, u.name';
                break;

            case 'rauthor':
                $orderby = 'a.created_by_alias DESC, u.name DESC';
                break;

            case 'section':
                $orderby = 's.name, c.name, a.created DESC';
                break;

            case 'rsection':
                $orderby = 's.name DESC, c.name DESC, a.created DESC';
                break;

            case 'front':
                $orderby = 'f.ordering';
                break;

            default:
                $orderby = 'a.ordering';
                break;
        }

        return $orderby;
    }
}

class jstContentTemplate
{

    var $page_type = null;
    var $template_dir = null;
    var $template_file = null;

    function get_template_dir($page_type)
    {

        $dir = str_replace('_', '/', $page_type);

        /*            switch($page_type){
        case 'blog_section':
        $dir = 'section/blog';
        break;

        case 'groupcats_section':
        $dir = 'section/groupcats';
        break;

        case 'table_cats_section':
        $dir = 'section/table_cats';
        break;

        case 'table_items_section':
        $dir = 'section/table_items';
        break;

        case 'blog_category':
        $dir = 'category/blog';
        break;

        case 'table_category':
        $dir = 'category/table';
        break;

        case 'archive':
        $dir = 'archive';
        break;

        case 'item':
        $dir = 'item/full_view';
        break;

        case 'item_static':
        $dir = 'item/static_content';
        break;

        case 'item_editform':
        $dir = 'item/edit_form';
        break;

        default:
        $dir = null;
        break;

        }*/
        return $dir;
    }

    function set_template($page_type, $templates = null)
    {
        $this->page_type = $page_type;

        $this->template_dir = self::get_system_path($this->page_type);
        $this->template_file = Jconfig::getInstance()->config_absolute_path . '/' . $this->template_dir . '/default.php';

        if ($templates)
        {
            $tpl_arr = self::parse_curr_templates($templates);
            $template_file = $tpl_arr[$page_type];

            if (isset($template_file))
            {
                $template_pref = substr($template_file, 0, 3);
                $template_file = str_replace($template_pref, '', $template_file);

                switch ($template_pref)
                {
                    case '[t]':
                        $this->template_dir = self::get_currtemplate_path($page_type);
                        break;

                    default:
                        break;
                }
                if (is_file(Jconfig::getInstance()->config_absolute_path . '/' . $this->template_dir . '/' . $template_file))
                {
                    $this->template_file = Jconfig::getInstance()->config_absolute_path . '/' . $this->template_dir . '/' . $template_file;
                }

            }
        }

    }


    function get_system_path($page_type)
    {
        $template_dir = self::get_template_dir($page_type);
        $system_path = 'components/com_content/view/' . $template_dir;
        return $system_path;
    }

    function get_currtemplate_path($page_type)
    {
        $mainframe = new mosMainFrame(null, null, null, false);

        $template_dir = self::get_template_dir($page_type);
        $currtemplate_path = 'templates/' . $mainframe->getTemplate() . '/html/com_content/' . $template_dir;
        return $currtemplate_path;
    }

    function templates_select_list($page_type, $curr_value_arr = null)
    {
        $curr_value = null;

        $system_path = self::get_system_path($page_type);
        $currtemplate_path = self::get_currtemplate_path($page_type);

        $files_system = mosReadDirectory(Jconfig::getInstance()->config_absolute_path . '/' . $system_path, '\.php$');
        $files_from_currtemplate = mosReadDirectory(Jconfig::getInstance()->config_absolute_path . '/' . $currtemplate_path, '\.php$');

        $options = array();
        $options[] = mosHTML::makeOption('0', 'По умолчанию');
        foreach ($files_system as $file)
        {
            $options[] = mosHTML::makeOption('[s]' . $file, '[s]' . $file);
        }
        foreach ($files_from_currtemplate as $file)
        {
            $options[] = mosHTML::makeOption('[t]' . $file, '[t]' . $file);
        }
        //return $options;

        if ($curr_value_arr && isset($curr_value_arr[$page_type]))
        {
            $curr_value = $curr_value_arr[$page_type];
        }
        return mosHTML::selectList($options, 'templates[' . $page_type . ']', 'class="inputbox"', 'value', 'text', $curr_value);

    }

    function prepare_for_save($templates)
    {
        $txt = array();
        foreach ($templates as $k => $v)
        {
            if ($v)
            {
                $txt[] = "$k=$v";
            }
        }
        return implode('|', $txt);
    }

    function parse_curr_templates($templates)
    {
        if ($templates)
        {
            $tpls = array();
            $tpls = explode('|', $templates);

            $return = array();

            foreach ($tpls as $tpl)
            {
                $arr = explode('=', $tpl);
                $key = $arr[0];
                $value = $arr[1];
                $return[$key] = $value;
            }
            return $return;
        }
        return null;
    }

    function isset_settings($page_type, $templates)
    {
        if ($page_type && $templates)
        {
            $templates = self::parse_curr_templates($templates);
            if (isset($templates[$page_type]))
            {
                return true;
            }
        }

        return false;
    }

}

class jstContentUserpageConfig extends dbConfig
{

    /**
     * Заголовок страницы
     */
    var $title = 'Содержимое пользователяm';
    /**
     * Отображать дату
     */
    var $date = 1;
    /**
     * Отображать количество просмотров
     */
    var $hits = 1;
    /**
     * Отображать раздел/категорию
     */
    var $section = 1;
    /**
     * Поле фильтра
     */
    var $filter = 1;
    /**
     * Выбор типа сортировки
     */
    var $order_select = 1;
    /**
     * Выпадающий список для выбора количества записей на странице
     */
    var $display = 1;
    /**
     * Количество записей на сранице по умолчанию
     */
    var $display_num = 50;
    /**
     * Заголовки таблицы
     */
    var $headings = 1;
    /**
     * Постраничная навигация
     */
    var $navigation = 1;


    function jstContentUserpageConfig(&$db, $group = 'com_content', $subgroup = 'user_page')
    {
        $this->dbConfig($db, $group, $subgroup);
    }

}


class jstContentAccess
{

    var $canView = null;
    var $canCreate = null;
    var $canEditOwn = null;
    var $canEdit = null;
    var $canPublish = null;

    function jstContentAccess()
    {
        global $acl, $my;

        $this->canEdit = $acl->acl_check('action', 'edit', 'users', $my->usertype, 'content', 'all');
        $this->canEditOwn = $acl->acl_check('action', 'edit', 'users', $my->usertype, 'content', 'own');
        $this->canPublish = $acl->acl_check('action', 'publish', 'users', $my->usertype, 'content', 'all');
    }

    function set($group, $value)
    {
        $this->$group = $value;
    }

}

class contentPageConfig
{
	
    /**
     * contentPageConfig::setup_full_item_page()
     * 
     * Установка дефолтных параметров для вывода страницы полного текста записи
     * xml-файл для генерации формы установки параметров: administrator/components/com_content/content.xml
     * 
     * @return object $params
     */
    function setup_full_item_page($row)
    {
        global $mainframe, $database;

        $params = new mosParameters($row->attribs);        

        if ($row->sectionid == 0)
        {
            $params->set('item_navigation', 0);
        } else
        {
            $params->set('item_navigation', $mainframe->getCfg('item_navigation'));
        }

        $params->section_data = null;
        $params->category_data = null;
        if (!$row->sectionid)
        {
            $params->page_type = 'item_static';
        } 
		else
        {
            $section = new mosSection($database);
            $section->load((int)$row->sectionid);
            $category = new mosCategory($database);
            $category->load((int)$row->catid);

            $params->page_type = 'item_full';
            $params->section_data = $section;
            $params->category_data = $category;
        }
        
        $params->set('intro_only', 0);       

        //Название страницы, отображаемое в заголовке браузера (тег title): string
        $params->def('page_name', '');
        //Показать/скрыть название сайта в title страницы (заголовке браузера): bool
        $params->def('no_site_name', 0);
        //Суффикс CSS-класса страницы
        $params->def('pageclass_sfx', '');
        //Уникальный идентификатор CSS стиля используемый для оформления только этого материала. Полный идентификатор будет '#pageclass_uid_{введённое значение}'
        $params->def('pageclass_uids', '');
        //Преимущества. Использовать введённый идентификатор, даже если активированы автоматические уникальные идентификаторы стилей новостей
        $params->def('pageclass_uids_full', 1);
        //Показать/Скрыть кнопку Назад (Вернуться), возвращающую на предыдущую просмотренную страницу
        $params->def('back_button', $mainframe->getCfg('back_button'));
        //Показать/Спрятать заголовок объекта
        $params->def('item_title', '');        
        //Сделать заголовок объекта в виде ссылки на него
        $params->def('link_titles', $mainframe->getCfg('link_titles'));
        //Показать/Спрятать вводный текст
        $params->def('introtext', 1);        
        //Показать/Спрятать название раздела, к которому относится объект
        $params->def('section', 1);
        //Сделать названия разделов ссылками
        $params->def('section_link', 1);
        //Показать/Спрятать название категории, к которой относится объект
        $params->def('category', 1);
        //Сделать названия категорий ссылками
        $params->def('category_link', 1);
        //Показать/Спрятать рейтинг
        $params->def('rating', $mainframe->getCfg('vote'));
        //Показать/Спрятать имя автора
        $params->def('author', !$mainframe->getCfg('hideAuthor'));
        //Показать/Спрятать дату создания
        $params->def('createdate', !$mainframe->getCfg('hideCreateDate'));
        //Показать/Спрятать дату изменения
        $params->def('modifydate', !$mainframe->getCfg('hideModifyDate'));
        //Показать/Скрыть кнопку печати
        $params->def('print', !$mainframe->getCfg('hidePrint'));
        //Показать/Спрятать кнопку e-mail
        $params->def('email', !$mainframe->getCfg('hideEmail'));
        //Отображать ссылки "Печать" и "Email" иконками
        $params->def('icons', $mainframe->getCfg('icons'));
        //Ключевая ссылка. Текст ключа, по которому можно ссылаться на этот объект (например, в системе справки)
        $params->def('keyref', '');
        
        $params->set('page_name', $row->title);

        return $params;
    }
    
    /**
     * contentPageConfig::setup_blog_section_page()
     * 
     * Установка дефолтных параметров для вывода страницы блога раздела
     * xml-файл для генерации формы установки параметров: 
	 * administrator/components/com_menus/content_blog_section/content_blog_section.xml
     * 
     * @return object $params
     */

    function setup_blog_section_page($id)
    {
        global $mainframe, $Itemid;

        //Отучаем com_content брать параметры из первого попавшегося пункта меню
        //Мысль - если пункт меню для текущего раздела не создан,
        // значит, - так надо, и нет необходимости приписывать разделу ненужные ему параметры ))
        //есть параметры по умолчанию - вот их и будем использовать
        $menu = $mainframe->get('menu');

        if ($menu && strpos($menu->link, 'task=blogsection&id=' . $id) !== false)
        {
            $params = new mosParameters($menu->params);
        } else
        {
            $menu = '';
            $params = new mosParameters('');
        }

        $params->menu = $menu;

        if (!$id)
        {
            $id = $params->def('sectionid', 0);
        }
        
        //Название страницы, отображаемое в заголовке браузера (тег title)
        $params->def('page_name', '');
        //показать/скрыть название сайта в title страницы (заголовке браузера)
        $params->def('no_site_name', 1);
        
        //Мета-тег robots, используемый на странице:
        //int [-1,0,1,2,3]=['Не отображать', 'Index, follow', 'Index, NoFollow', 'NoIndex, Follow', 'NoIndex, NoFollow']
        $params->def('robots', -1);
        //META-тег: Description: string
        $params->def('meta_description', '');
        //ETA-тег keywords: string
        $params->def('meta_keywords', '');
        //META-тег author: string
        $params->def('meta_author', '');
        //Изображение меню
        $params->def('menu_image', '');
        //Суффикс CSS-класса страницы
        $params->def('pageclass_sfx', '');
        //Заголовок страницы (контентной области)
        $params->def('header', '');
        //Показать/Скрыть заголовок страницы
        $params->def('page_title', '');
        //Показать/Скрыть кнопку Назад (Вернуться), возвращающую на предыдущую просмотренную страницу
        $params->def('back_button', $mainframe->getCfg('back_button'));
        //Количество главных объектов (на всю ширину). При 0 главные объекты отображаться не будут.
        $params->def('leading', 1);
        //Количество объектов, у которых показывается вступительный (intro) текст
        $params->def('intro', 4);
        //Сколько колонок в строке использовать при отображении вводного текста
        $params->def('columns', 2);
        //Количество объектов, отображаемых в виде ссыло
        $params->def('link', 4);
        //Сортировка объектов в категории
        $params->def('orderby_pri', '');
        //Порядок, в котором будут отображаться объекты
        $params->def('orderby_sec', '');
        //Показать/Скрыть постраничную навигацию
        $params->def('pagination', 2);
        //Показать/Скрыть информацию о результатах разбиения на страницы ( например, 1-4 из 4 )
        $params->def('pagination_results', 1);
        //Показывать {mosimages}
        $params->def('image', 1);
        //Показать/Скрыть названия разделов, к которым принадлежат объекты
        $params->def('section', 0);
        //Сделать названия разделов ссылками на страницу текущего раздела
        $params->def('section_link', 0);
        //Показать/Скрыть названия категорий, которым принадлежат объекты
        $params->def('category', 0);
        //Сделать названия категорий ссылками на страницу текущей категории
        $params->def('category_link', 0);
        //Тип ссылки на категорию: 'blog' / 'table'
        $params->def('cat_link_type', 'blog'); //TODO:вынести в xml
        //Показать/Скрыть заголовки объектов
        $params->def('item_title', 1);
        //Сделать заголовки объектов в виде ссылок на объекты
        $params->def('link_titles', $mainframe->getCfg('link_titles'));
        //Показать/Скрыть ссылку [Подробнее...]
        $params->def('readmore', $mainframe->getCfg('readmore'));
        //Показать/Скрыть возможность оценки объектов
        $params->def('rating', $mainframe->getCfg('rating'));
        //Показать/Скрыть имена авторов объектов
        $params->def('author', !$mainframe->getCfg('hideAuthor'));
        //Тип отображения имен авторов
        $params->def('author_name', $mainframe->getCfg('author_name'));
        //Показать/Скрыть дату создания объекта
        $params->def('createdate', !$mainframe->getCfg('hideCreateDate'));
        //оказать/Скрыть дату изменения объекта
        $params->def('modifydate', !$mainframe->getCfg('hideModifyDate'));
        //Показать/Скрыть кнопку печати объекта
        $params->def('print', !$mainframe->getCfg('hidePrint'));
        //Показать/Скрыть кнопку отправки объекта на e-mail
        $params->def('email', !$mainframe->getCfg('hideEmail'));
        //Показать/Скрыть неопубликованные объекты для группы пользователей `Publisher` и выше
        $params->def('unpublished', 0);        
        //Группировка по категориям
        $params->def('group_cat', 0);
        //Количество записей в группе
        $params->def('groupcat_limit', 5);
        
        //Показать/Скрыть описание раздела
        $params->def('description', 0);
        //Показать/Скрыть изображение описания раздела
        $params->def('description_image', 0);
        
        //Показать/Скрыть вводный текст
        $params->def('view_introtext', 1);
         //Лимит слов для интротекста. Если текст не нуждается в обрезке - оставьте поле пустым
        $params->def('introtext_limit', '');  
        
        $params->def('intro_only', 1);
        

        if ($params->get('page_title', 1) && $menu)
        {
            $header = $params->def('header', $menu->name);
        }

        return $params;

    }

	//TODO:Описать все параметры из xml 
    function setup_blog_category_page($id)
    {
        global $mainframe, $Itemid;

        $menu = $mainframe->get('menu');

        if ($menu && strpos($menu->link, 'task=blogcategory&id=' . $id) !== false)
        {
            $params = new mosParameters($menu->params);
        } else
        {
            $menu = '';
            $params = new mosParameters('');
        }

        $params->menu = $menu;

        if (!$id)
        {
            $id = $params->def('categoryid', 0);
        }

         //Название страницы, отображаемое в заголовке браузера (тег title)
        $params->def('page_name', '');
        //показать/скрыть название сайта в title страницы (заголовке браузера)
        $params->def('no_site_name', 1);
        
        //Мета-тег robots, используемый на странице:
        //int [-1,0,1,2,3]=['Не отображать', 'Index, follow', 'Index, NoFollow', 'NoIndex, Follow', 'NoIndex, NoFollow']
        $params->def('robots', -1);
        //META-тег: Description: string
        $params->def('meta_description', '');
        //ETA-тег keywords: string
        $params->def('meta_keywords', '');
        //META-тег author: string
        $params->def('meta_author', '');
        //Изображение меню
        $params->def('menu_image', '');
        //Суффикс CSS-класса страницы
        $params->def('pageclass_sfx', '');
        //Заголовок страницы (контентной области)
        $params->def('header', '');
        //Показать/Скрыть заголовок страницы
        $params->def('page_title', '');
        //Показать/Скрыть кнопку Назад (Вернуться), возвращающую на предыдущую просмотренную страницу
        $params->def('back_button', $mainframe->getCfg('back_button'));
        //Количество главных объектов (на всю ширину). При 0 главные объекты отображаться не будут.
        $params->def('leading', 1);
        //Количество объектов, у которых показывается вступительный (intro) текст
        $params->def('intro', 4);
        //Сколько колонок в строке использовать при отображении вводного текста
        $params->def('columns', 2);
        //Количество объектов, отображаемых в виде ссыло
        $params->def('link', 4);
        //Сортировка объектов в категории
        $params->def('orderby_pri', '');
        //Порядок, в котором будут отображаться объекты
        $params->def('orderby_sec', '');
        //Показать/Скрыть постраничную навигацию
        $params->def('pagination', 2);
        //Показать/Скрыть информацию о результатах разбиения на страницы ( например, 1-4 из 4 )
        $params->def('pagination_results', 1);
        //Показывать {mosimages}
        $params->def('image', 1);
        //Показать/Скрыть названия разделов, к которым принадлежат объекты
        $params->def('section', 0);
        //Сделать названия разделов ссылками на страницу текущего раздела
        $params->def('section_link', 0);
        //Показать/Скрыть названия категорий, которым принадлежат объекты
        $params->def('category', 0);
        //Сделать названия категорий ссылками на страницу текущей категории
        $params->def('category_link', 0);
        //Тип ссылки на категорию: 'blog' / 'table'
        $params->def('cat_link_type', 'blog'); //TODO:вынести в xml
        //Показать/Скрыть заголовки объектов
        $params->def('item_title', 1);
        //Сделать заголовки объектов в виде ссылок на объекты
        $params->def('link_titles', $mainframe->getCfg('link_titles'));
        //Показать/Скрыть ссылку [Подробнее...]
        $params->def('readmore', $mainframe->getCfg('readmore'));
        //Показать/Скрыть возможность оценки объектов
        $params->def('rating', $mainframe->getCfg('vote'));
        //Показать/Скрыть имена авторов объектов
        $params->def('author', !$mainframe->getCfg('hideAuthor'));
        //Тип отображения имен авторов
        $params->def('author_name', $mainframe->getCfg('author_name'));
        //Показать/Скрыть дату создания объекта
        $params->def('createdate', !$mainframe->getCfg('hideCreateDate'));
        //оказать/Скрыть дату изменения объекта
        $params->def('modifydate', !$mainframe->getCfg('hideModifyDate'));
        //Показать/Скрыть кнопку печати объекта
        $params->def('print', !$mainframe->getCfg('hidePrint'));
        //Показать/Скрыть кнопку отправки объекта на e-mail
        $params->def('email', !$mainframe->getCfg('hideEmail'));
        //Показать/Скрыть неопубликованные объекты для группы пользователей `Publisher` и выше
        $params->def('unpublished', 0);        
        //Группировка по категориям
        $params->def('group_cat', 0);
        //Количество записей в группе
        $params->def('groupcat_limit', 5);
        
        //Показать/Скрыть описание категории
        $params->def('description', 0);
        //Показать/Скрыть изображение описания категории
        $params->def('description_image', 0);
        
        //Показать/Скрыть вводный текст
        $params->def('view_introtext', 1);
         //Лимит слов для интротекста. Если текст не нуждается в обрезке - оставьте поле пустым
        $params->def('introtext_limit', '');  
        
        $params->def('intro_only', 1);


        $params->def('cat_link_type', 'blog'); //TODO:вынести в xml
        $params->def('section_link_type', 'blog'); //TODO:вынести в xml

        if ($params->get('page_title', 1) && $menu)
        {
            $header = $params->def('header', $menu->name);
        }

        return $params;

    }
    
    function setup_blog_archive_section_page($id)
    {
        global $mainframe, $Itemid;

        $menu = $mainframe->get('menu');

        if ($menu && strpos($menu->link, 'task=archivesection&id=' . $id) !== false)
        {
            $params = new mosParameters($menu->params);
        } else
        {
            $menu = '';
            $params = new mosParameters('');
        }

        $params->menu = $menu;

        if (!$id)
        {
            $id = $params->def('sectionid', 0);
        }
        $params->def('pop', 0);
        $params->def('orderby_sec', 'rdate');
        $params->def('orderby_pri', '');
        $params->def('intro', 4);
        $params->def('leading', 1);
        $params->def('link', 4);
        $params->def('limitstart', '0');
        $params->def('limit', '10');
        $params->def('rating', '');
        $params->def('group_cat', 0);
        $params->def('groupcat_limit', 0);
        $params->def('columns', 2);
        $params->def('pagination', 2);
        $params->def('pagination_results', 1);
        $params->def('description', 1);
        $params->def('description_image', 1);
        $params->def('back_button', $mainframe->getCfg('back_button'));
        $params->def('pageclass_sfx', '');
        $params->def('intro_only', 0);


        $params->def('cat_link_type', 'blog'); //TODO:вынести в xml

        if ($params->get('page_title', 1) && $menu)
        {
            $header = $params->def('header', $menu->name);
        }

        return $params;

    }
    
    function setup_blog_archive_category_page($id)
    {
        global $mainframe, $Itemid;

        $menu = $mainframe->get('menu');

        if ($menu && strpos($menu->link, 'task=archivecategory&id=' . $id) !== false)
        {
            $params = new mosParameters($menu->params);
        } else
        {
            $menu = '';
            $params = new mosParameters('');
        }

        $params->menu = $menu;

        $params->def('pop', 0);
        $params->def('orderby', 'rdate');
        $params->def('intro', 4);
        $params->def('leading', 1);
        $params->def('link', 4);
        $params->def('limitstart', '0');
        $params->def('limit', '10');
        $params->def('rating', '');
        $params->def('columns', 2);
        $params->def('pagination', 2);
        $params->def('pagination_results', 1);
        $params->def('description', 1);
        $params->def('description_image', 1);
        $params->def('back_button', $mainframe->getCfg('back_button'));
        $params->def('pageclass_sfx', '');
        $params->def('intro_only', 0);


        $params->def('cat_link_type', 'blog'); //TODO:вынести в xml

        if ($params->get('page_title', 1) && $menu)
        {
            $header = $params->def('header', $menu->name);
        }

        return $params;

    }

	//TODO:Описать все параметры из xml 
    function setup_table_category_page($category)
    {
        global $mainframe, $Itemid, $mosConfig_list_limit;

        $menu = $mainframe->get('menu');

        if (($menu && strpos($menu->link, 'task=category&sectionid=' . $category->section . '&id=' . $category->id) !== false) || (strpos($menu->link, 'task=section&amp;id=' . $category->section) !== false))
        {
            $params = new mosParameters($menu->params);
        } 
		
		else
        {
            $menu = '';
            $params = new mosParameters('');
        }

        $params->menu = $menu;

        $params->def('description_cat', 1);
        $params->def('description_cat_image', 1);
        $params->def('page_title', 1);
        $params->def('title', 1);
        $params->def('hits', $mainframe->getCfg('hits'));
        $params->def('author', !$mainframe->getCfg('hideAuthor'));
        $params->def('date', !$mainframe->getCfg('hideCreateDate'));
        $params->def('date_format', _DATE_FORMAT_LC);
        $params->def('navigation', 2);
        $params->def('display', 1);
        $params->def('display_num', $mosConfig_list_limit);
        $params->def('other_cat', 1);
        $params->def('empty_cat', 0);
        $params->def('cat_items', 1);
        $params->def('cat_description', 0);
        $params->def('back_button', $mainframe->getCfg('back_button'));
        $params->def('pageclass_sfx', '');
        $params->def('headings', 1);
        $params->def('order_select', 1);
        $params->def('filter', 1);
        $params->def('filter_type', 'title');
        $params->def('unpublished', 1);


        // Description & Description Image control
        $params->def('description', $params->get('description_cat'));
        $params->def('description_image', $params->get('description_cat_image'));

        $params->set('type', 'category');

        return $params;

    }
    
	//TODO:Описать все параметры из xml      
    function setup_section_catlist_page($section){
    	global $mainframe, $Itemid;
    	
    	$menu = $mainframe->get('menu');
    	
		if($menu && strpos($menu->link, 'task=section&id=' . $section->id) !== false)
        {
            $params = new mosParameters($menu->params);
        } else
        {
            $menu = '';
            $params = new mosParameters('');
            //$params = new mosEmpty();
        }

        $params->menu = $menu;

		$params->def('orderby','');	
		$params->def('page_title',1);
		$params->def('pageclass_sfx','');
		$params->def('description_sec',1);
		$params->def('description_sec_image',1);
		$params->def('other_cat_section',1);
		$params->def('empty_cat_section',0);
		$params->def('other_cat',1);
		$params->def('empty_cat',0);
		$params->def('cat_items',1);
		$params->def('cat_description',1);
		$params->def('back_button',$mainframe->getCfg('back_button'));
		$params->def('pageclass_sfx','');
		$params->def('unpublished',1);
		$params->def('description',$params->get('description_sec'));
		$params->def('description_image',$params->get('description_sec_image'));	
		$params->set('type','section');
		
		return $params;
    }

    /**
     * contentPageConfig::setup_frontpage()
     * 
     * Установка дефолтных параметров для вывода страницы компонента "com_frontpage"
     * xml-файл для генерации формы установки параметров: administrator/components/com_frontpage/frontpage.xml
     * 
     * @return object $params
     */
    function setup_frontpage()
    {
        global $mainframe, $Itemid;
        
        if(!isset($mainframe->menu->id)){
        	$menu = $mainframe->get('menu');	
        }
        else{
        	$menu = $mainframe->menu;
        }

        
        $params = new mosParameters($menu->params);

        $params->menu = $menu;

        $params->def('title', '');
        //Название страницы, отображаемое в заголовке браузера (тег title): string
        $params->def('page_name', '');
        //Показать/скрыть название сайта в title страницы (заголовке браузера): bool
        $params->def('no_site_name', 0);
        //Мета-тег robots, используемый на странице:
        //int [-1,0,1,2,3]=['Не отображать', 'Index, follow', 'Index, NoFollow', 'NoIndex, Follow', 'NoIndex, NoFollow']
        $params->def('robots', -1);
        //META-тег: Description: string
        $params->def('meta_description', '');
        //ETA-тег keywords: string
        $params->def('meta_keywords', '');
        //META-тег author: string
        $params->def('meta_author', '');
        //Изображение меню
        $params->def('menu_image', '');
        //Суффикс CSS-класса страницы
        $params->def('pageclass_sfx', '');
        //Заголовок страницы (контентной области)
        $params->def('header', '');
        //Показать/Скрыть заголовок страницы
        $params->def('page_title', '');
        //Показать/Скрыть кнопку Назад (Вернуться), возвращающую на предыдущую просмотренную страницу
        $params->def('back_button', 0);
        //Количество главных объектов (на всю ширину). При 0 главные объекты отображаться не будут.
        $params->def('leading', 1);
        //Количество объектов, у которых показывается вступительный (intro) текст
        $params->def('intro', 4);
        //Сколько колонок в строке использовать при отображении вводного текста
        $params->def('columns', 2);
        //Количество объектов, отображаемых в виде ссыло
        $params->def('link', 4);
        //Сортировка объектов в категории
        $params->def('orderby_pri', '');
        //Порядок, в котором будут отображаться объекты
        $params->def('orderby_sec', '');
        //Показать/Скрыть постраничную навигацию
        $params->def('pagination', 2);
        //Показать/Скрыть информацию о результатах разбиения на страницы ( например, 1-4 из 4 )
        $params->def('pagination_results', 1);
        //Показывать {mosimages}
        $params->def('image', 1);
        //Показать/Скрыть названия разделов, к которым принадлежат объекты
        $params->def('section', 0);
        //Сделать названия разделов ссылками на страницу текущего раздела
        $params->def('section_link', 0);
        //Показать/Скрыть названия категорий, которым принадлежат объекты
        $params->def('category', 0);
        //Сделать названия категорий ссылками на страницу текущей категории
        $params->def('category_link', 0);
        //Показать/Скрыть заголовки объектов
        $params->def('item_title', 1);
        //Сделать заголовки объектов в виде ссылок на объекты
        $params->def('link_titles', '');
        //Показать/Скрыть ссылку [Подробнее...]
        $params->def('readmore', '');
        //Показать/Скрыть возможность оценки объектов
        $params->def('rating', $mainframe->getCfg('vote'));
        //Показать/Скрыть имена авторов объектов
        $params->def('author', !$mainframe->getCfg('hideAuthor'));
        //Тип отображения имен авторов
        $params->def('author_name', 0);
        //Показать/Скрыть дату создания объекта
        $params->def('createdate', !$mainframe->getCfg('hideCreateDate'));
        //оказать/Скрыть дату изменения объекта
        $params->def('modifydate', !$mainframe->getCfg('hideModifyDate'));
        //Показать/Скрыть кнопку печати объекта
        $params->def('print', !$mainframe->getCfg('hidePrint'));
        //Показать/Скрыть кнопку отправки объекта на e-mail
        $params->def('email', !$mainframe->getCfg('hideEmail'));
        //Показать/Скрыть неопубликованные объекты для группы пользователей `Publisher` и выше
        $params->def('unpublished', 0);


        $params->def('pop', 0);
        $params->def('limitstart', '0');
        $params->def('limit', '10');
        $params->def('description', 0);
        $params->def('description_image', 0);
        $params->def('back_button', $mainframe->getCfg('back_button'));
       

        //Тип ссылки на категорию
        $params->def('cat_link_type', 'table');

        if ($params->get('page_title', 1) && $menu)
        {
            $header = $params->def('header', $menu->name);
        }

        return $params;

    }

    function setup_blog_item($params)
    {
        global $mainframe, $Itemid;

        $params->def('link_titles', $mainframe->getCfg('link_titles'));
        $params->def('author', !$mainframe->getCfg('hideAuthor'));
        $params->def('createdate', !$mainframe->getCfg('hideCreateDate'));
        $params->def('modifydate', !$mainframe->getCfg('hideModifyDate'));
        $params->def('print', !$mainframe->getCfg('hidePrint'));
        $params->def('email', !$mainframe->getCfg('hideEmail'));
        $params->def('rating', $mainframe->getCfg('vote'));
        $params->def('icons', $mainframe->getCfg('icons'));
        $params->def('readmore', $mainframe->getCfg('readmore'));
        $params->def('image', 1);
        $params->def('section', 0);
        $params->def('section_link', 0);
        $params->def('category', 0);
        $params->def('category_link', 0);
        $params->def('introtext', 1);
        $params->def('view_introtext', 1);
        $params->def('item_title', 1);
        $params->def('jeditable', 0);
        $params->def('intro_only', 1);
        $params->def('url', 1);

        $params->set('intro_only', 1);

        return $params;

    }


}

?>

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
require_once($mosConfig_absolute_path.'/includes/libraries/database/config/jstconfig.class.php');

/**
* Category database table class
* @package Joostina
*/
class mosCategory extends mosDBTable {
	/**
	@var int Primary key*/
	var $id = null;
	/**
	@var int*/
	var $parent_id = null;
	/**
	@var string The menu title for the Category (a short name)*/
	var $title = null;
	/**
	@var string The full name for the Category*/
	var $name = null;
	/**
	@var string*/
	var $image = null;
	/**
	@var string*/
	var $section = null;
	/**
	@var int*/
	var $image_position = null;
	/**
	@var string*/
	var $description = null;
	/**
	@var boolean*/
	var $published = null;
	/**
	@var boolean*/
	var $checked_out = null;
	/**
	@var time*/
	var $checked_out_time = null;
	/**
	@var int*/
	var $ordering = null;
	/**
	@var int*/
	var $access = null;
	/**
	@var string*/
	var $params = null;

    var $templates = null;

	/**
	* @param database A database connector object
	*/
	function mosCategory(&$db) {
		$this->mosDBTable('#__categories','id',$db);
	}
	// overloaded check function
	function check() {
		// check for valid name
		if(trim($this->title) == '') {
			$this->_error = _ENTER_CATEGORY_TITLE;
			return false;
		}
		if(trim($this->name) == '') {
			$this->_error = _ENTER_CATEGORY_NAME;
			return false;
		}
		$ignoreList = array('description');
		$this->filter($ignoreList);
		// check for existing name
		$query = "SELECT id"
				."\n FROM #__categories "
				."\n WHERE name = ".$this->_db->Quote($this->name)
				."\n AND section = ".$this->_db->Quote($this->section);
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
		if($xid && $xid != intval($this->id)) {
			$this->_error = _CATEGORY_ALREADY_EXISTS;
			return false;
		}
		return true;
	}

    function get_category($id){
        $query = 'SELECT cc.* FROM #__categories AS cc WHERE cc.id = '.$id;
        $r = null;
        $this->_db->setQuery($query);
        $this->_db->loadObject($r);
        return $r;
    }
}

/**
* Section database table class
* @package Joostina
*/
class mosSection extends mosDBTable {
	/**
	@var int Primary key*/
	var $id = null;
	/**
	@var string The menu title for the Section (a short name)*/
	var $title = null;
	/**
	@var string The full name for the Section*/
	var $name = null;
	/**
	@var string*/
	var $image = null;
	/**
	@var string*/
	var $scope = null;
	/**
	@var int*/
	var $image_position = null;
	/**
	@var string*/
	var $description = null;
	/**
	@var boolean*/
	var $published = null;
	/**
	@var boolean*/
	var $checked_out = null;
	/**
	@var time*/
	var $checked_out_time = null;
	/**
	@var int*/
	var $ordering = null;
	/**
	@var int*/
	var $access = null;
	/**
	@var string*/
	var $params = null;

    var $templates = null;

	/**
	* @param database A database connector object
	*/
	function mosSection(&$db) {
		$this->mosDBTable('#__sections','id',$db);
	}
	// overloaded check function
	function check() {
		// check for valid name
		if(trim($this->title) == '') {
			$this->_error = _ENTER_SECTION_TITLE;
			return false;
		}
		if(trim($this->name) == '') {
			$this->_error = _ENTER_SECTION_NAME;
			return false;
		}
		$ignoreList = array('description');
		$this->filter($ignoreList);
		// check for existing name
		$query = "SELECT id"
				."\n FROM #__sections "
				."\n WHERE name = ".$this->_db->Quote($this->name)
				."\n AND scope = ".$this->_db->Quote($this->scope);
		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());
		if($xid && $xid != intval($this->id)) {
			$this->_error = _SECTION_ALREADY_EXISTS;
			return false;
		}
		return true;
	}

    function get_section($id){
        $query = 'SELECT s.* FROM #__sections AS s WHERE s.id = '.$id;
        $r = null;
        $this->_db->setQuery($query);
        $this->_db->loadObject($r);
        return $r;
    }
}

/**
* Module database table class
* @package Joostina
*/
class mosContent extends mosDBTable {
	/**
	@var int Primary key*/
	var $id = null;
	/**
	@var string*/
	var $title = null;
	/**
	@var string*/
	var $title_alias = null;
	/**
	@var string*/
	var $introtext = null;
	/**
	@var string*/
	var $fulltext = null;
	/**
	@var int*/
	var $state = null;
	/**
	@var int The id of the category section*/
	var $sectionid = null;
	/**
	@var int DEPRECATED*/
	var $mask = null;
	/**
	@var int*/
	var $catid = null;
	/**
	@var datetime*/
	var $created = null;
	/**
	@var int User id*/
	var $created_by = null;
	/**
	@var string An alias for the author*/
	var $created_by_alias = null;
	/**
	@var datetime*/
	var $modified = null;
	/**
	@var int User id*/
	var $modified_by = null;
	/**
	@var boolean*/
	var $checked_out = null;
	/**
	@var time*/
	var $checked_out_time = null;
	/**
	@var datetime*/
	var $frontpage_up = null;
	/**
	@var datetime*/
	var $frontpage_down = null;
	/**
	@var datetime*/
	var $publish_up = null;
	/**
	@var datetime*/
	var $publish_down = null;
	/**
	@var string*/
	var $images = null;
	/**
	@var string*/
	var $urls = null;
	/**
	@var string*/
	var $attribs = null;
	/**
	@var int*/
	var $version = null;
	/**
	@var int*/
	var $parentid = null;
	/**
	@var int*/
	var $ordering = null;
	/**
	@var string*/
	var $metakey = null;
	/**
	@var string*/
	var $metadesc = null;
	/**
	@var int*/
	var $access = null;
	/**
	@var int*/
	var $hits = null;
	/**
	@var string*/
	var $notetext = null;

    var $templates = null;
	/**
	* @param database A database connector object
	*/
	function mosContent(&$db) {
		$this->mosDBTable('#__content','id',$db);
	}

	/**
	* Validation and filtering
	*/
	function check() {
		// filter malicious code
		$ignoreList = array('introtext','fulltext');
		$this->filter($ignoreList);

		/*
		* TODO: This filter is too rigorous,
		* need to implement more configurable solution
		* // specific filters
		* $iFilter = new InputFilter( null, null, 1, 1 );
		* $this->introtext = trim( $iFilter->process( $this->introtext ) );
		* $this->fulltext =  trim( $iFilter->process( $this->fulltext ) );
		*/
		if(trim(str_replace('&nbsp;','',$this->fulltext)) == '') {
			$this->fulltext = '';
		}
		return true;
	}

    function get_item($id){

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
                WHERE item.id='.$id;
        $r=null;
		$this->_db->setQuery($sql);
		$this->_db->loadObject($r);
        return $r;
	}

    function load_user_items($user_id, $limitstart = 0, $limit = 50, $orderby = 'a.created DESC', $and = ''){
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
                        ". $and . "
                ORDER BY $orderby
            ";

	$this->_db->setQuery($query, $limitstart, $limit);
	return  $this->_db->loadObjectList();
    }

    function load_count_user_items($user_id, $and = ''){

        $query = "  SELECT COUNT(a.id)
                    FROM #__content AS a
                    LEFT JOIN #__users AS u ON u.id = a.created_by
                    LEFT JOIN #__groups AS g ON a.access = g.id
                    LEFT JOIN #__categories AS c on a.catid = c.id
                    LEFT JOIN #__sections AS s on s.id = c.section
                    WHERE a.created_by = $user_id AND a.state > -1
                    ". $and;
	    $this->_db->setQuery($query);
	    return $this->_db->loadResult();
    }

	/**
	* Converts record to XML
	* @param boolean Map foreign keys to text values
	*/
	function toXML($mapKeysToText = false) {
		global $database;

		if($mapKeysToText) {
			$query = "SELECT name FROM #__sections WHERE id = ".(int)$this->sectionid;
			$database->setQuery($query);
			$this->sectionid = $database->loadResult();

			$query = "SELECT name FROM #__categories WHERE id = ".(int)$this->catid;
			$database->setQuery($query);
			$this->catid = $database->loadResult();

			$query = "SELECT name FROM #__users WHERE id = ".(int)$this->created_by;
			$database->setQuery($query);
			$this->created_by = $database->loadResult();
		}

		return parent::toXML($mapKeysToText);
	}

	function ReadMore(&$row,&$params, $template='') {
	    $return='';
         if($params->get('readmore')) {
			if($params->get('intro_only') && $row->link_text) {
			    $return='<a href="'.$row->link_on.'" title="'.$row->readmore.'" class="readon">'.$row->link_text.'</a>';
			}
		}
        return $return;
   }

    function Author(&$row,&$params='') {
        global $mosConfig_absolute_path, $database, $mainframe, $mosConfig_author_name;
        $author_name='';
        if(!$params){
            return $row->username;
        }

        if($row->author != '') {
            if(!$row->created_by_alias){

                if ($params->get('author_name',0)){
                    $switcher=$params->get('author_name');
                }

                else{
                    $switcher=$mosConfig_author_name;
                }

                switch($switcher){
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

                if($switcher=='3' || $switcher=='4'){
                    $uid=$row->created_by;
                    $author_link = 'index.php?option=com_user&amp;task=profile&amp;user='.$uid;
                    $author_seflink = sefRelToAbs($author_link);
                    $author_name='<a href="'.$author_seflink.'">'.$author_name.'</a>';
                }

            }


            else{
                $author_name=$row->created_by_alias;
            }

        }
        return $author_name;
    }

    	function EditIcon2(&$row,&$params,&$access) {
       	global $my;

		if($params->get('popup')) {
			return;
		}
		if($row->state < 0) {
			return;
		}
		if(!$access->canEdit && !($access->canEditOwn && $row->created_by == $my->id)) {
			return;
		}

		mosCommonHTML::loadOverlib();

		$link = 'index.php?option=com_content&amp;task=edit&amp;id='.$row->id.$row->Itemid_link.'&amp;Returnid='.$row->_Itemid;
		$image = mosAdminMenus::ImageCheck('edit.png','/images/M_images/',null,null,_E_EDIT,_E_EDIT);

		if($row->state == 0) {
			$overlib = _CMN_UNPUBLISHED;
		} else {
			$overlib = _CMN_PUBLISHED;
		}
		$date = mosFormatDate($row->created);
		$author = $row->created_by_alias?$row->created_by_alias:$row->author;

		$overlib .= '<br />';
		$overlib .= $row->groups;
		$overlib .= '<br />';
		$overlib .= $date;
		$overlib .= '<br />';
		$overlib .= $author;


		$return="<a class=\"joo_ico edit_button\" href=\"".sefRelToAbs($link)."\" onmouseover=\"return overlib('".$overlib."', CAPTION, '". _E_EDIT.", BELOW, RIGHT);\" onmouseout=\"return nd();\">".$image."</a>";

        return $return;
	}
}

    class jstContentTemplate{

        var $page_type = null;
        var $template_dir = null;
        var $template_file = null;

        function get_template_dir($page_type){

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

        function set_template($page_type, $templates=null){
            $this->page_type = $page_type;

            $this->template_dir = self::get_system_path($this->page_type);
            $this->template_file = Jconfig::getInstance()->config_absolute_path.'/'.$this->template_dir.'/default.php';

            if($templates){
                $tpl_arr = self::parse_curr_templates($templates);
                $template_file = $tpl_arr[$page_type];

                if(isset($template_file)){
                    $template_pref =  substr($template_file, 0, 3);
                    $template_file =  str_replace($template_pref, '', $template_file);

                    switch ($template_pref){
                        case '[t]':
                            $this->template_dir = self::get_currtemplate_path($page_type);
                        break;

                        default:
                        break;
                    }
                    if(is_file(Jconfig::getInstance()->config_absolute_path.'/'.$this->template_dir.'/'.$template_file)){
                        $this->template_file = Jconfig::getInstance()->config_absolute_path.'/'.$this->template_dir.'/'.$template_file;
                    }

                }
            }

        }



        function get_system_path($page_type){
            $template_dir = self::get_template_dir($page_type);
            $system_path = 'components/com_content/view/'.$template_dir;
            return $system_path;
        }

        function get_currtemplate_path($page_type){
            $mainframe = new mosMainFrame(null,null,null,false);

            $template_dir = self::get_template_dir($page_type);
            $currtemplate_path = 'templates/'.$mainframe->getTemplate().'/html/com_content/'.$template_dir;
            return $currtemplate_path;
        }

        function templates_select_list($page_type, $curr_value_arr=null){
            $curr_value = null;

            $system_path = self::get_system_path($page_type);
            $currtemplate_path = self::get_currtemplate_path($page_type);

            $files_system  = mosReadDirectory(Jconfig::getInstance()->config_absolute_path.'/'.$system_path,'\.php$');
            $files_from_currtemplate= mosReadDirectory(Jconfig::getInstance()->config_absolute_path.'/'.$currtemplate_path,'\.php$');

            $options = array();
            $options[] = mosHTML::makeOption('0','По умолчанию');
		    foreach($files_system as $file) {
			    $options[] = mosHTML::makeOption('[s]'.$file,'[s]'.$file);
		    }
		    foreach($files_from_currtemplate as $file) {
			    $options[] = mosHTML::makeOption('[t]'.$file,'[t]'.$file);
		    }
            //return $options;

            if($curr_value_arr && isset($curr_value_arr[$page_type])){
                $curr_value = $curr_value_arr[$page_type];
            }
            return mosHTML::selectList($options,'templates['.$page_type.']','class="inputbox"','value','text',$curr_value);

        }

        function prepare_for_save($templates){
            $txt = array();
	        foreach($templates as $k => $v) {
	            if($v){
                    $txt[] = "$k=$v";
	            }
	        }
            return implode('|',$txt);
        }

        function parse_curr_templates($templates){
            if($templates){
                $tpls = array();
                $tpls = explode('|', $templates);

                $return=array();

                foreach($tpls as $tpl){
                    $arr = explode('=',$tpl);
                    $key = $arr[0]; $value = $arr[1];
                    $return[$key] = $value;
                }
                return $return;
            }
            return null;
        }

        function isset_settings($page_type, $templates){
            if($page_type && $templates){
                $templates = self::parse_curr_templates($templates);
                if(isset($templates[$page_type])){
                    return true;
                }
            }

            return false;
        }

    }

    class jstContentUserpageConfig extends jstConfig{

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


        function jstContentUserpageConfig(&$db, $group = 'com_content', $subgroup = 'user_page') {
            $this->jstConfig($db, $group, $subgroup);
        }

    }

    class jstContentAccess{

        var $canView = 1;
        var $canCreate = 1;
        var $canEditOwn = 1;
        var $canEdit = 1;

        function jstContentAccess(){
            global $acl, $my;

            $this->canEdit	= $acl->acl_check('action','edit','users',$my->usertype,'content','all');
            $this->canEditOwn	= $acl->acl_check('action','edit','users',$my->usertype,'content','own');
            $this->canPublish	= $acl->acl_check('action','publish','users',$my->usertype,'content','all');
        }

        function set($group, $value){
            $this->$group = $value;
        }

    }



?>
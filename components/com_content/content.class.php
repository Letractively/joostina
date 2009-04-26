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
        global $mosConfig_absolute_path, $database, $mainframe;
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
                    $switcher=$mosConfig_AuthorName;
                    //$switcher='4';
                }

                switch($switcher){
                    case '1':
                    case '3':
                        $author_name=$row->author;
                    break;

                    case '2':
                    case '4':
                    default;
                        $author_name=$row->username;
                    break;
                }

                if($switcher=='3' || $switcher=='4'){
                    $uid=$row->created_by;
                    $author_link = 'index.php?option=com_user&amp;task=Profile&amp;user='.$uid;
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


		$return="<a class=\"joo_ico edit_button\"  href=\"".sefRelToAbs($link)."\" onmouseover=\"return overlib('".$overlib."', CAPTION, '". _E_EDIT.", BELOW, RIGHT);\" onmouseout=\"return nd();\">".$image."</a>";

        return $return;
	}
}

?>
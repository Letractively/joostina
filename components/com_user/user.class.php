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
* Users Table Class
*
* Provides access to the jos_user table
* @package Joostina
*/
class mosUser extends mosDBTable {
	/**
	@var int Unique id*/
	var $id = null;
	/**
	@var string The users real name (or nickname)*/
	var $name = null;
	/**
	@var string The login name*/
	var $username = null;
	/**
	@var string email*/
	var $email = null;
	/**
	@var string MD5 encrypted password*/
	var $password = null;
	/**
	@var string*/
	var $usertype = null;
	/**
	@var int*/
	var $block = null;
	/**
	@var int*/
	var $sendEmail = null;
	/**
	@var int The group id number*/
	var $gid = null;
	/**
	@var datetime*/
	var $registerDate = null;
	/**
	@var datetime*/
	var $lastvisitDate = null;
	/**
	@var string activation hash*/
	var $activation = null;
	/**
	@var string*/
	var $params = null;
	/**
	@var string*/
	var $avatar = null;

	/**
	* @param database A database connector object
	*/
	function mosUser(&$database) {
		$this->mosDBTable('#__users','id',$database);
	}

	/**
	* Validation and filtering
	* @return boolean True is satisfactory
	*/
	function check() {
		global $mosConfig_uniquemail;

		// Validate user information
		if(trim($this->name) == '') {
			$this->_error = addslashes(_REGWARN_NAME);
			return false;
		}

		if(trim($this->username) == '') {
			$this->_error = addslashes(_REGWARN_UNAME);
			return false;
		}

		// check that username is not greater than 25 characters
		$username = $this->username;
		if(strlen($username) > 25) {
			$this->username = substr($username,0,25);
		}

		// check that password is not greater than 50 characters
		$password = $this->password;
		if(strlen($password) > 50) {
			$this->password = substr($password,0,50);
		}

		if(eregi("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]",$this->username) || strlen($this->username) <3) {
			$this->_error = sprintf(addslashes(_VALID_AZ09),addslashes(_PROMPT_UNAME),2);
			return false;
		}

		if((trim($this->email == "")) || (preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/",$this->email) == false)) {
			$this->_error = addslashes(_REGWARN_MAIL);
			return false;
		}

		// check for existing username
		$query = "SELECT id FROM #__users WHERE username = ".$this->_db->Quote($this->username)." AND id != ".(int)$this->id;
		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());
		if($xid && $xid != intval($this->id)) {
			$this->_error = addslashes(_REGWARN_INUSE);
			return false;
		}

		if($mosConfig_uniquemail) {
			// check for existing email
			$query = "SELECT id FROM #__users WHERE email = ".$this->_db->Quote($this->email)." AND id != ".(int)$this->id;
			$this->_db->setQuery($query);
			$xid = intval($this->_db->loadResult());
			if($xid && $xid != intval($this->id)) {
				$this->_error = addslashes(_REGWARN_EMAIL_INUSE);
				return false;
			}
		}

		return true;
	}

	function store($updateNulls = false) {
		global $acl,$migrate;
		$section_value = 'users';

		$k = $this->_tbl_key;
		$key = $this->$k;
		if($key && !$migrate) {
			// existing record
			$ret = $this->_db->updateObject($this->_tbl,$this,$this->_tbl_key,$updateNulls);
			// syncronise ACL
			// single group handled at the moment
			// trivial to expand to multiple groups
			$groups = $acl->get_object_groups($section_value,$this->$k,'ARO');
			if(isset($groups[0])) $acl->del_group_object($groups[0],$section_value,$this->$k,'ARO');
			$acl->add_group_object($this->gid,$section_value,$this->$k,'ARO');

			$object_id = $acl->get_object_id($section_value,$this->$k,'ARO');
			$acl->edit_object($object_id,$section_value,$this->_db->getEscaped($this->name),$this->$k,0,0,'ARO');
		} else {
			// new record
			$ret = $this->_db->insertObject($this->_tbl,$this,$this->_tbl_key);
			// syncronise ACL
			$acl->add_object($section_value,$this->_db->getEscaped($this->name),$this->$k,null,null,'ARO');
			$acl->add_group_object($this->gid,$section_value,$this->$k,'ARO');
		}
		if(!$ret) {
			$this->_error = strtolower(get_class($this))."::store failed <br />".$this->_db->getErrorMsg();
			return false;
		} else {
			return true;
		}
	}

	function delete($oid = null) {
		global $acl;

		$k = $this->_tbl_key;
		if($oid) {
			$this->$k = intval($oid);
		}
		$aro_id = $acl->get_object_id('users',$this->$k,'ARO');
		$acl->del_object($aro_id,'ARO',true);

		$query = "DELETE FROM $this->_tbl WHERE $this->_tbl_key = ".(int)$this->$k;
		$this->_db->setQuery($query);

		if($this->_db->query()) {
			// cleanup related data

			// :: private messaging
			$query = "DELETE FROM #__messages_cfg WHERE user_id = ".(int)$this->$k;
			$this->_db->setQuery($query);
			if(!$this->_db->query()) {
				$this->_error = $this->_db->getErrorMsg();
				return false;
			}
			$query = "DELETE FROM #__messages WHERE user_id_to = ".(int)$this->$k;
			$this->_db->setQuery($query);
			if(!$this->_db->query()) {
				$this->_error = $this->_db->getErrorMsg();
				return false;
			}

			return true;
		} else {
			$this->_error = $this->_db->getErrorMsg();
			return false;
		}
	}

	/**
	* Gets the users from a group
	* @param string The value for the group (not used 1.0)
	* @param string The name for the group
	* @param string If RECURSE, will drill into child groups
	* @param string Ordering for the list
	* @return array
	*/
	function getUserListFromGroup($value,$name,$recurse = 'NO_RECURSE',$order ='name') {
		global $acl;
		$group_id = $acl->get_group_id($name, 'ARO');
		$objects = $acl->get_group_objects($group_id,'ARO','RECURSE');

		if(isset($objects['users'])) {
			mosArrayToInts($objects['users']);
			$gWhere = '(id ='.implode(' OR id =',$objects['users']).')';

			$query = "SELECT id AS value, name AS text FROM #__users WHERE block = '0' AND ".$gWhere."\n ORDER BY ".$order;
			$this->_db->setQuery($query);
			$options = $this->_db->loadObjectList();
			return $options;
		} else {
			return array();
		}
	}
	/**
	* функция получения аватара пользователя, возвращает путь к изображения аватара от корня сайта
	*/
	function avatar($user,$size='normal'){
		global $mosConfig_absolute_path;

		switch($size) {
			case 'big':
				$pach = '';
				break;

			case 'mini':
				$pach = 'mini/';
				break;

			default:
			case 'normal':
				$pach = 'normal/';
				break;
		}

		if(file_exists($mosConfig_absolute_path.'/images/avatars/'.$pach.$user->id.'.jpg')){
			$img = '/images/avatars/'.$pach.$user->id.'.jpg';
		}else{
			$img = '/images/avatars/'.$pach.'none.jpg';
		}
		return $img;
	}
	/**
	* функция получения мини - аватара пользователя, возвращает путь к изображения аватара от корня сайта
	*/
	function miniavatar($id){
		global $mosConfig_absolute_path;
			if(file_exists($mosConfig_absolute_path.'/images/avatars/mini/'.$id.'.jpg'))
				$img = '/images/avatars/mini/'.$id.'.jpg';
			else
				$img = '/images/avatars/mini/none.jpg';
		return $img;
	}

	/**
	* Получение статуса пользователя
	*/
    function get_user_status($uid){

        $qq = "SELECT * FROM #__session WHERE userid=$uid AND guest=0  ";
        $this->_db->setQuery( $qq );
        $sessions = $this->_db->loadObjectList();
        $sess=& $sessions[0];

        $status = 0;
        if(isset($sess->userid)){
            $status = 1;
        }
        return $status;
    }

	/**
	* Получение дополнительных данных пользователя
	*/
    function get_user_extra($uid){

        $qq = "SELECT * FROM #__users_extra WHERE user_id=$uid   ";
        $r = null;
        $this->_db->setQuery( $qq );
        $this->_db->loadObject($r);
        return $r;
    }
    
	/**
	* Смена аватара
	*/
    
    function update_avatar($id, $img){
		$sql = 'UPDATE #__users SET avatar = \''.$img.'\' WHERE id='.$id;
		$this->_db->setQuery($sql);
		$this->_db->query();
	}
}

class userUsersExtra extends mosDBTable{

  	 var $user_id = null;
	 var $gender = null;
	 var $about = null;
	 var $location = null;
	 var $url = null;
	 var $icq = null;
	 var $skype = null;
	 var $jabber = null;
	 var $msn = null;
	 var $yahoo = null;
	 var $phone = null;
	 var $fax = null;
	 var $mobil = null;
     var $birthdate = null;

	/**
	* @param database A database connector object
	*/
	function userUsersExtra(&$db) {
		$this->mosDBTable('#__users_extra','user_id',$db);
	}
}

class userHelper{
	
	function _load_core_js(){
        global $mosConfig_live_site, $mainframe;
        $mainframe->addJS($mosConfig_live_site.'/components/com_user/js/com_user.js','custom'); 
	}
	
	function _load_jquery_form(){
        mosCommonHTML::loadJqueryPlugins('jquery.form', false, false, 'js');
        ?>
        <script language="JavaScript" type="text/javascript">
            _js_defines.push('load_jquery_form');
        </script>
        <?php
	}
	
 	
	 function _build_img_upload_area($obj, $form_params, $state){
        global $mosConfig_live_site,$mosConfig_absolute_path;
    	$field = $form_params->img_field;
    ?>
                <script type="text/javascript">
                $(document).ready(function() {
                    $("a#reupload_<?php echo $form_params->img_field;?>").click(function () {
                        $(".upload_area_<?php echo $form_params->img_field;?>").css("display", "block");
                        $("#<?php echo $form_params->img_field;?>").addClass("required");
                        return false;
                    });
                });
            </script>

            <?php if($state!='upload'){?>
                    <div id="current_<?php echo $form_params->img_field;?>">
                        <img class="site_img" src="<?php echo $mosConfig_live_site;?>/<?php echo $form_params->img_path;?>/<?php echo $obj->$field;?>" />
                        <a  href="#" id="reupload_<?php echo $form_params->img_field;?>">Сменить</a>
                    </div>
                    <div class="upload_area_<?php echo $form_params->img_field;?> hidden" style="display:none;">
                        <?php echo self::_build_img_upload_form($obj, $form_params);?>
                    </div>
            <?php } else {
            ?>
                    <div id="current_<?php echo $form_params->img_field;?>">
                        <img class="site_img" src="<?php echo $mosConfig_live_site;?>/<?php echo $form_params->default_img;?>" />
                    </div>
                    <div class="upload_area_<?php echo $form_params->img_field;?>">
                        <?php echo self::_build_img_upload_form($obj, $form_params);?>
                    </div>
            <?php
            } ?>
    <?php
    }

function _build_img_upload_form(&$obj, $form_params){

	    global $mosConfig_live_site,$mosConfig_absolute_path;
        self::_load_jquery_form();

	?>

        <script type="text/javascript">
        $(document).ready(function(){

            $('#<?php echo $form_params->img_field;?>_uploadForm').ajaxForm({
                beforeSubmit: function(a,f,o) {
                    o.dataType = "html";
                    $('#<?php echo $form_params->img_field;?>_uploadOutput').html('Загрузка...');
                    if(!$('#upload_<?php echo $form_params->img_field;?>').val()){
                        $('#<?php echo $form_params->img_field;?>_uploadOutput').html('Выберите изображение');
                        return false;
                    }
                },
                success: function(data) {
                    var $out = $('#<?php echo $form_params->img_field;?>_uploadOutput');
                    $out.html('');

                   if(data){
                        if (typeof data == 'object' && data.nodeType)
                        data = elementToString(data.documentElement, true);
                        else if (typeof data == 'object')
                        data = objToString(data);
                        $('#current_<?php echo $form_params->img_field;?>').html('<img class="site_img" src="<?php echo $mosConfig_live_site;?>/<?php echo $form_params->img_path;?>/'+data+'" />');
                        $('#new_<?php echo $form_params->img_field;?>').val(data);
                   }

                }
            });
        });
        </script>

		<form name="<?php echo $form_params->img_field;?>_uploadForm" class="ajaxForm" enctype="multipart/form-data" method="post" action="ajax.index.php" id="<?php echo $form_params->img_field;?>_uploadForm">
     	    <input name="<?php echo $form_params->img_field;?>"  id="upload_<?php echo $form_params->img_field;?>"  type="file" />

			<input type="submit" name="Submit" value="Загрузить" />
			<input type="hidden" name="task" value="upload_<?php echo $form_params->img_field;?>" />
			<input type="hidden" name="id" value="<?php echo $obj->id;?>" />
			<input type="hidden" name="option" value="com_user" />
		</form>

        <img id="loading_<?php echo $form_params->img_field;?>" src="images/system_image/loader.gif" style="display:none;"/>
        <div id="<?php echo $form_params->img_field;?>_uploadOutput"></div>

	<?php

    }
}

/**
* Session database table class
* @package Joostina
*/
class mosSession extends mosDBTable {
	/**
	@var int Primary key*/
	var $session_id = null;
	/**
	@var string*/
	var $time = null;
	/**
	@var string*/
	var $userid = null;
	/**
	@var string*/
	var $usertype = null;
	/**
	@var string*/
	var $username = null;
	/**
	@var time*/
	var $gid = null;
	/**
	@var int*/
	var $guest = null;
	/**
	@var string*/
	var $_session_cookie = null;

	/**
	* @param database A database connector object
	*/
	function mosSession(&$db) {
		$this->mosDBTable('#__session','session_id',$db);
	}

	/**
	* @param string Key search for
	* @param mixed Default value if not set
	* @return mixed
	*/
	function get($key,$default = null) {
		return mosGetParam($_SESSION,$key,$default);
	}

	/**
	* @param string Key to set
	* @param mixed Value to set
	* @return mixed The new value
	*/
	function set($key,$value) {
		$_SESSION[$key] = $value;
		return $value;
	}

	/**
	* Sets a key from a REQUEST variable, otherwise uses the default
	* @param string The variable key
	* @param string The REQUEST variable name
	* @param mixed The default value
	* @return mixed
	*/
	function setFromRequest($key,$varName,$default = null) {
		if(isset($_REQUEST[$varName])) {
			return mosSession::set($key,$_REQUEST[$varName]);
		} else
			if(isset($_SESSION[$key])) {
				return $_SESSION[$key];
			} else {
				return mosSession::set($key,$default);
			}
	}

	/**
	* Insert a new row
	* @return boolean
	*/
	function insert() {
		$ret = $this->_db->insertObject($this->_tbl,$this);
		if(!$ret) {
			$this->_error = strtolower(get_class($this))."::store failed <br />".$this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}

	/**
	* Update an existing row
	* @return boolean
	*/
	function update($updateNulls = false) {
		$ret = $this->_db->updateObject($this->_tbl,$this,'session_id',$updateNulls);
		if(!$ret) {
			$this->_error = strtolower(get_class($this))."::update error <br />".$this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}
	/**
	* Generate a unique session id
	* @return string
	*/
	function generateId() {
		$failsafe = 20;
		$randnum = 0;
		while($failsafe--) {
			$randnum = md5(uniqid(microtime(),1));
			$new_session_id = mosMainFrame::sessionCookieValue($randnum);
			if($randnum != '') {
				$query = "SELECT $this->_tbl_key FROM $this->_tbl WHERE $this->_tbl_key = ".
					$this->_db->Quote($new_session_id);
				$this->_db->setQuery($query);
				if(!$result = $this->_db->query()) {
					die($this->_db->stderr(true));
				}
				if($this->_db->getNumRows($result) == 0) {
					break;
				}
			}
		}
		$this->_session_cookie = $randnum;
		$this->session_id = $new_session_id;
	}

	/**
	* @return string The name of the session cookie
	*/
	function getCookie() {
		return $this->_session_cookie;
	}

	/**
	* Purge lapsed sessions
	* @return boolean
	*/
	function purge($inc = 1800,$and = '') {
		global $mainframe;

		if($inc == 'core') {
			$past_logged = time() - $mainframe->getCfg('lifetime');
			$past_guest = time() - 900;

			$query = "DELETE FROM $this->_tbl"."\n WHERE ("
				// purging expired logged sessions
				."\n ( time < '".(int)$past_logged."' ) AND guest = 0 AND gid > 0 ) OR ("
				// purging expired guest sessions
				."\n ( time < '".(int)$past_guest."' ) AND guest = 1 AND userid = 0".
				"\n )";
		} else {
			// kept for backward compatability
			$past = time() - $inc;
			$query = "DELETE FROM $this->_tbl WHERE ( time < '".(int)$past."' )".$and;
		}
		$this->_db->setQuery($query);

		return $this->_db->query();
	}
}

class mosUserParameters extends mosParameters {
	/**
	* @param string The name of the form element
	* @param string The value of the element
	* @param object The xml element for the parameter
	* @param string The control name
	* @return string The html for the element
	*/
	function _form_editor_list($name,$value,&$node,$control_name) {
		global $database;
		// compile list of the editors
		$query = "SELECT element AS value, name AS text"
				."\n FROM #__mambots"
				."\n WHERE folder = 'editors'"
				."\n AND published = 1"
				."\n ORDER BY ordering, name";
		$database->setQuery($query);
		$editors = $database->loadObjectList();
		array_unshift($editors,mosHTML::makeOption('',_SELECT_EDITOR));
		return mosHTML::selectList($editors,''.$control_name.'['.$name.']','class="inputbox"','value','text',$value);
	}
}

?>

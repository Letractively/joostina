<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008-2009 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

// ������ ������� �������
defined('_VALID_MOS') or die();

/**
* @package Joostina
* @subpackage Users
*/
class HTML_user {
	function frontpage() {
?>
	<div class="componentheading"><?php echo _WELCOME; ?></div>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td><?php echo _WELCOME_DESC; ?></td>
		</tr>
	</table>
<?php
	}

	function profile($user,$option, &$params, $config){
		global $my, $_MAMBOTS,$Itemid;

		$mainframe = &mosMainFrame::getInstance();
		$database = &database::getInstance();

		$owner=0;
		$admin = 0;

		if($my->user_type = 'Super Administrator'){
			$admin = 1;
		}

		if($my->id && $user->id==$my->id){
			$owner=1;
			$editable=' editable';
			$edit_info_link=sefRelToAbs('index.php?option=com_users&task=UserDetails&Itemid='.$Itemid);
		}else{
			$editable='';
			$avatar_edit='';
		}

		//���������� ��� �������
		$avatar_pic = '<img class="avatar" src="'.$mainframe->getCfg('live_site').'/'.$user->get_avatar($user).'" />';
		$user_id= $user->id;
		$user_real_name = $user->name;
		$user_nickname = $user->username;

		$user_status = $user->get_user_status($user->id);
		if($user_status){
			$user_status='<span class="online">'._USER_ON_LINE.'</span>';
		}else{
			$user_status='<span class="offline">'._USER_OFF_LINE.'</span>';
		}

		$registerDate = mosFormatDate($user->registerDate);
		
		$lastvisitDate = ($user->lastvisitDate !='0000-00-00 00:00:00') ? mosFormatDate($user->lastvisitDate) : _USER_NONE_LAST_VISIT;

		$user_content_href=sefRelToAbs('index.php?option=com_content&task=mycontent&user='.$user_id.'&Itemid='.$Itemid);

		//������
		$template = 'default.php';
		$template_dir = 'components/com_users/view/profile';

		//���� ������������ ������ ������� ��� ������ ����� �������������
		if(!$config->get('template')){
			$template=strtolower(str_replace(' ', '', $user->usertype )).'.php';
		}
		
		if($config->get('template_dir')){
			$template_dir = 'templates'.DS. $mainframe->getTemplate() . '/html/com_users/profile';
		}
		$template_file = $mainframe->getCfg('absolute_path').DS.$template_dir.DS.$template;

		//������� ������� ������� ������������
		$plugins = new userPlugins();
		$profile_plugins = $plugins->get_plugins('profile');

		$plugin_page = '';
		$cur_plugin = mosGetParam( $_REQUEST, 'view', '' );
		//���� ������� ����������� 
		if($profile_plugins){
			//��������� ������ ������ � ������ ��� ������ ��-���������
			$plugin_page = $profile_plugins[0]->element;
			//��������� � �������� �������
			if($cur_plugin){
				//��������� ������������� ������ �� �����������
				if($plugins->allow_plugin($cur_plugin)){
					$plugin_page = $cur_plugin;
				}
			}else{
				$cur_plugin = $plugin_page;
			}
			//���������� ������
			$_MAMBOTS->loadBot('profile',$plugin_page,1);
		
		}
		include ($template_file);
	}

	function userEdit($user,$option,$submitvalue,&$params, $user_config) {
		// used for spoof hardening
		$validate = josSpoofValue();
		$config = &Jconfig::getInstance();

		require_once ($config->config_absolute_path.'/includes/HTML_toolbar.php');

		$user_extra = $user->user_extra;
		$bday_date = mosFormatDate($user_extra->birthdate, '%d', '0') ;
		$bday_month = mosFormatDate($user_extra->birthdate, '%m') ;
		$bday_year = mosFormatDate($user_extra->birthdate, '%Y', '0') ;

		//������
		$template_file='default.php';
		if(!$user_config->get('template_edit')){
			if(is_file($config->config_absolute_path.DS.'components'.DS.'com_users'.DS.'view'.DS.'edit'.DS.strtolower(str_replace(' ', '', $user->usertype )).'.php')){
				$template_file=strtolower(str_replace(' ', '', $user->usertype )).'.php';
			}
		}

		include ($config->config_absolute_path.DS.'components'.DS.'com_users'.DS.'view'.DS.'edit'.DS.$template_file);

	}

	function confirmation() {
	?><div class="componentheading"><?php echo _SUBMIT_SUCCESS; ?></div>
	<table>
		<tr>
			<td><?php echo _SUBMIT_SUCCESS_DESC; ?></td>
		</tr>
	</table>
<?php
	}
}
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

$_MAMBOTS->registerFunction('userProfile','botUserContacts');
$_MAMBOTS->registerFunction('userProfileTab','botUserContacts_tab');

/* ��������� ������� ������� */
function botUserContacts_tab(&$user){
	return array(
		'name'=>_USER_CONTACTS,
		'title'=>_USER_CONTACTS,
		'href'=>'index.php?option=com_users&task=profile&view=user_contacts&user='.$user->id,
		'id'=>'user_user_contacts_link',
		'class'=>''
	);
}

/**
*/
function botUserContacts($user) {
	global $_MAMBOTS;

	$database = &database::getInstance();

	//����������� ������� ������������ ����
	mosCommonHTML::loadJqueryPlugins('fancybox/jquery.fancybox', false, true);
	//�������� �����
	UserContacts_output($user);
	unset($user,$database);
}

/** ������� - �������� ������ */
function UserContacts_output($user){
	$mainframe = &mosMainFrame::getInstance();
	$ajax_handler = 'ajax.index.php?option=com_users&task=request_from_plugin&plugin=user_contacts';
?><!-- ����������� ���� � ������ �������� ���������-->
	<script type="text/javascript">
		$(document).ready(function() {
			$(".fancy_inline").fancybox({'hideOnContentClick': false});
		});
	</script>
	<!--������ ICQ, Skype-->
	<div class="messengers">
		<?php UserContacts_messengers($user);?>
	</div>
	<!--������, �� ������� �� �������, ���������� ����������� ���� � ������ �������� ��������� -->
	<span class="email">
		<a class="fancy_inline email" href="<?php echo $mainframe->getCfg('live_site');?>/<?php echo $ajax_handler;?>&act=display_form&user_id=<?php echo $user->id;?>">
			<?php echo BOT_USER_CONTACTS_SEND_MESSAGE?>
		</a>
	</span>
<?php
	}
/** ����� ������ � ������������ */
function UserContacts_messengers($user){
	$img_url = Jconfig::getInstance()->config_live_site.'/images/system';

	if (isset($user->user_extra->icq) && trim($user->user_extra->icq)!='' ){?>
		<span class="icq">
			<img src="http://web.icq.com/whitepages/online?icq=<?php echo $user->user_extra->icq;?>&img=5" align="absmiddle" border="0" alt="ICQ" title="ICQ">
			<a href="javascript:void(window.open('http://www.icq.com/people/webmsg.php?to=<?php echo $user->user_extra->icq;?>','newWin','resizable=1,status=1,menubar=1,toolbar=1,scrollbars=1,location=1,directories=1,width=500,height=600,top=60,left=60'))">
				<?php echo $user->user_extra->icq;?>
			</a>
		</span>
		<br />
<?php } if (isset($user->user_extra->skype) && trim($user->user_extra->skype)!='' ){ ?>
		<span class="skype">
			<img src="http://mystatus.skype.com/smallclassic/<?php echo $user->user_extra->skype;?>" align="absmiddle" border="0" alt="Skype" title="Skype">
			<a href="skype:<?php echo $user->user_extra->skype;?>?call">
				<?php echo $user->user_extra->skype;?>
			</a>
		</span>
<?php } ?>
<?php
}
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

$_MAMBOTS->registerFunction('userProfile','botUserContent');
$_MAMBOTS->registerFunction('userProfileTab','botUserContent_tab');

/* добавляем вкладку профиля */
function botUserContent_tab(&$user){
	return array(
		'name'=>_USER_CONTENTS,
		'title'=>_USER_CONTENTS,
		'href'=>'index.php?option=com_users&task=profile&view=user_content&user='.$user->id,
		'id'=>'user_user_content_link',
		'class'=>''
	);
}

/**
*/
function botUserContent(&$user) {
	global $_MAMBOTS,$Itemid;

	$mainframe = &mosMainFrame::getInstance();
	$database = &database::getInstance();
	$config = &Jconfig::getInstance();

	require_once ($mainframe->getPath('class','com_content'));
	require_once ($config->config_absolute_path.'/components/com_content/content.html.php');
	require_once ($mainframe->getPath('config','com_content'));

	$k = 0;

	$params = new configContent_ucontent($database);
	$params->set('limitstart', 0);
	$params->set('limit', 5);
	$params->def('show_link', 1);
	
	$user_items = new mosContent($database);
	$user_items = $user_items->_load_user_items($user->id, $params);
	
	$access = new contentAccess();
	
	if(!$user_items){?>
		<div id="userContent_area">
			<div class="error"><?php echo USER_CONTENT_NO_USER_CONTENTS?></div>
		</div><?php
		return;
	}
	?><div id="userContent_area">
	<br />
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
	foreach ($user_items as $row) {
		$row->created = mosFormatDate ($row->created,$config->config_form_date_full,'0');
		$link	= sefRelToAbs( 'index.php?option=com_content&amp;task=view&amp;id='. $row->id);
		$img	= $row->published ? 'publish_g.png' : 'publish_x.png';
		$img	= $config->config_live_site.'/'.ADMINISTRATOR_DIRECTORY.'/images/'.$img;
		$row->Itemid_link = '&amp;Itemid='.$Itemid;
		$row->_Itemid = $Itemid;
		// раздел / категория
		$section_cat = $row->section.' / '.$row->category;
		if($row->sectionid==0){
			$section_cat = USER_CONTENT_TYPED_CONTENT;
		}
?>
		<tr class="sectiontableentry<?php echo ($k+1);?>">
<?php if($access->canPublish){?>
			<td align="center" <?php echo ($access->canPublish) ? 'onclick="ch_publ('.$row->id.');" class="td-state"' : null ;?>>
				<img class="img-mini-state" src="<?php echo $img;?>" id="img-pub-<?php echo $row->id;?>" alt="<?php echo _PUBLISHING?>" />
			</td>
<?php }?>
<?php if($access->canEdit){?>
			<td>
				<?php mosContent::EditIcon2($row, $params, $access);?>
			</td>
<?php }?>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
				<br />
				<span class="small"><?php  echo $section_cat; ?></span>
			</td>
			<td><?php echo $row->created; ?></td>
			<td align="center"><?php echo $row->hits ? $row->hits : 0; ?></td>
		</tr>
		<?php $k = 1 - $k; ?>
<?php } ?>
	</table>
<?php if ( $params->get( 'show_link' ) ) {
	$user_content_link = sefRelToAbs( 'index.php?option=com_content&amp;task=user_content&amp;id='. $user->id);
?>
		<a class="readon" href="<?php echo $user_content_link; ?>"><?php echo USER_CONTENT_ALL_USER_CONTENTS ?></a>
<?php }?>
	</div>
<?php
}
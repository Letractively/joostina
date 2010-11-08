<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/
// ������ ������� �������
defined('_VALID_MOS') or die();
$moduleclass_sfx = $params->get('moduleclass_sfx');
$button_vis= $params->get('button', 1);
$button_pos= $params->get('button_pos', 'left');
$button_text= $params->get('button_text', _SEARCH_TITLE);
$width= intval( $params->get('width', 20));
$text= $params->get('text', _SEARCH_BOX);
$text_pos= $params->get('text_pos', 'inside');
$set_Itemid= intval($params->get('set_itemid', 0));
switch ($text_pos){
case 'iside':
default:
$output = '<input name="searchword" id="mod_search_searchword" maxlength="100" alt="search" class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" value="'.$text.'" onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';" />';
//�������������� ���� ����� (in work)
//$searchword = strval( mosGetParam( $_REQUEST, 'searchword', '' ) );
//$searchword = trim( stripslashes( $searchword ) );
//if ($searchword)
//$mtext=' value="'.$searchword.'" ';
//else 
//$mtext='value="'.$text.'" onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';" ';
//$output = '<input name="searchword" id="mod_search_searchword" maxlength="20" alt="search" class="inputbox'. $moduleclass_sfx .'" type="text" size="'.$width.'" '.$mtext.' />'; 
break;
case 'left':
$output = '<strong>'.$text.'</strong>&nbsp;<input name="searchword" id="mod_search_searchword" maxlength="100" alt="search" class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" value="" />';
break;
case 'top':
$output = '<strong>'.$text.'</strong><br /><input name="searchword" id="mod_search_searchword" maxlength="100" alt="search" class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" value="" />';
break;
case 'hidden':
$output = '<input name="searchword" id="mod_search_searchword" maxlength="100" alt="search" class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" value="" />';
break;
}
if ( $button_vis ) {
$button = '<input type="submit" value="'.$button_text.'" class="button'.$moduleclass_sfx.'" />';
}else{
$button = '';
}
switch ( $button_pos ) {
case 'top':
$button = $button .'<br />';
$output = $button . $output;
break;
case 'bottom':
$button = '<br />'. $button;
$output = $output . $button;
break;
case 'right':
$output = $output . $button;
break;
case 'left':
default:
$output = $button . $output;
break;
}
// set Itemid id for links
if ($set_Itemid) {
// use param setting
$_Itemid= $set_Itemid;
$link= 'index.php?option=com_search&amp;Itemid='.$set_Itemid;
} else {
$query = "SELECT id"
. "\n FROM #__menu"
. "\n WHERE link = 'index.php?option=com_search'"
. "\n AND published = 1"
;
$database->setQuery($query);
$rows = $database->loadObjectList(); 
// try to auto detect search component Itemid
if ( count( $rows ) ) {
$_Itemid= $rows[0]->id;
$link= 'index.php?option=com_search&amp;Itemid='.$_Itemid;
} else {
// Assign no Itemid
$_Itemid= '';
$link= 'index.php?option=com_search';
}
}
?>
<form action="<?php echo $link; ?>" method="get">
<div class="search<?php echo $moduleclass_sfx; ?>"><?php echo $output; ?></div>
<input type="hidden" name="option" value="com_search" />
<input type="hidden" name="Itemid" value="<?php echo $_Itemid; ?>" />
</form>
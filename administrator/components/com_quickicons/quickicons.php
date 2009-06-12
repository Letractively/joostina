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

function quickiconButton($row,$newWindow) {
	global $mosConfig_live_site;
	$title = $row->title ? $row->title : $row->text;
	?>
	<span>
		<a href="<?php echo htmlentities($row->target); ?>" title="<?php echo $title; ?>"<?php echo $newWindow; ?>><?php
			$icon = '<img src="'.$mosConfig_live_site.$row->icon.'" alt="'.$title.'" border="0" />';
			if($row->display == 1) {
				?><p><?php echo $row->text; ?></p><?php
			} elseif($row->display == 2) {
				echo $icon; // ������ ������
			} else {
				echo $icon.$row->text; // ������ � �����
			} ?>
		</a>
	</span>
<?php
}
?>

<?php
$securitycheck = intval($params->get('securitycheck',1));
if(!empty($securitycheck)) {
	josSecurityCheck('100%');
} ?>

<div class="cpicons"><?php
		$query = 'SELECT* FROM #__quickicons WHERE published = 1 AND gid <= '.$my->gid.' ORDER BY ordering';
		$database->setQuery($query);
		$rows = $database->loadObjectList();
		foreach($rows as $row) {
			$newWindow = $row->new_window ? ' target="_blank"':'';
			quickiconButton($row,$newWindow);
		}
		unset($query,$rows);
?></div>
<div style="display: block; clear: both; text-align:left; padding-top:10px;">
<?php if($my->usertype == 'Super Administrator') { ?>
	<a href="index2.php?option=com_quickicons">
		<img border="0" src="<?php echo $mosConfig_live_site; ?>/<?php echo ADMINISTRATOR_DIRECTORY?>/templates/joostfree/images/shortcut.png" />
		<?php echo _CHANGE_QUICK_BUTTONS?>
	</a>
<?php } ?>
</div>

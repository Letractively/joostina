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
 * @subpackage Search
 */
class search_html {


	function openhtml($params) {
		if($params->get('page_title')) {
?>
	<div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>"><h1><?php echo $params->get('header'); ?></h1></div>
<?php
		}
	}

	function searchbox($searchword, &$lists, $params) {
		global $Itemid;
?>
<br />
<form action="index.php" method="get">
	<input type="hidden" name="option" value="com_search" />
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
	<div class="contentpaneopen<?php echo $params->get('pageclass_sfx'); ?>">
		<label for="search_searchword"><?php echo _PROMPT_KEYWORD; ?>:</label>
		<br />
		<input type="text" name="searchword" id="search_searchword" size="30" maxlength="20" value="<?php echo stripslashes($searchword);?>" class="inputbox" />
		<input type="submit" name="submit" value="<?php echo _SEARCH_TITLE; ?>" class="button" />
		<br />
		<?php echo $lists['searchphrase']; ?>
		<br />
		<br />
		<h3><?php echo _SEARCH_RESULTS ?></h3>
		<label for="search_ordering"><?php echo _CMN_ORDERING; ?>:</label>
		<?php echo $lists['ordering']; ?>
	</div>
</form>
<?php
	}

	function searchintro($searchword, $params) {
?>
	<div class="searchintro<?php echo $params->get('pageclass_sfx'); ?>">
	<h4><?php echo _PROMPT_KEYWORD , ' <span>' , stripslashes($searchword) , '</span>'; ?></h4>
<?php
	}

	function message($message) {
		echo $message;
	}

	function displaynoresult() {

	}

	function display(&$rows, $params, $pageNav, $limitstart, $limit, $total, $totalRows, $searchword) {
		global $mosConfig_hideCreateDate;
		global $mosConfig_live_site, $option, $Itemid;
		$image = mosAdminMenus::ImageCheck('google.png', '/components/com_search/images/', null, null, 'Google', 'Google', 1);
		$image1 = mosAdminMenus::ImageCheck('yandex.gif', '/components/com_search/images/', null, null, 'Yandex', 'Yandex', 1);
		$image2 = mosAdminMenus::ImageCheck('rambler.gif', '/components/com_search/images/', null, null, 'Rambler', 'Rambler', 1);
		$image3 = mosAdminMenus::ImageCheck('mail.gif', '/components/com_search/images/', null, null, 'Mail', 'Mail', 1);
		$image4 = mosAdminMenus::ImageCheck('aport.gif', '/components/com_search/images/', null, null, 'Aport', 'Aport', 1);
		$image5 = mosAdminMenus::ImageCheck('gogo.gif', '/components/com_search/images/', null, null, 'GoGo', 'GoGo', 1);
		$searchword = urldecode($searchword);
		$searchword = htmlspecialchars($searchword, ENT_QUOTES);
?>
	</div>
	<br />
<?php
		echo $pageNav->writePagesCounter();
		$ordering = strtolower(strval(mosGetParam($_REQUEST, 'ordering', 'newest')));
		$searchphrase = strtolower(strval(mosGetParam($_REQUEST, 'searchphrase', 'any')));
		$searchphrase = htmlspecialchars($searchphrase);
		$cleanWord = htmlspecialchars($searchword);
		$link = $mosConfig_live_site . "/index.php?option=$option&amp;Itemid=$Itemid&amp;searchword=$cleanWord&amp;searchphrase=$searchphrase&amp;ordering=$ordering";
		//if($total>0){
			echo $pageNav->getLimitBox($link);
		//}
?>
<br /><br />
<table class="contentpaneopen<?php echo $params->get('pageclass_sfx'); ?>">
	<tr class="<?php echo $params->get('pageclass_sfx'); ?>">
		<td><h4><?php eval('echo "' . _CONCLUSION . '";'); ?></h4>
<?php
		$z = $limitstart + 1;
		$end = $limit + $z;
		if($end > $total) {
			$end = $total + 1;
		}
		for($i = $z; $i < $end; $i++) {
			$row = $rows[$i - 1];
			if($row->created) {
				$created = mosFormatDate($row->created, _DATE_FORMAT_LC);
			} else {
				$created = '';
			}
?>
<fieldset>
<div>
<span class="small<?php echo $params->get('pageclass_sfx'); ?>"><?php echo $i . '. '; ?></span>
<?php
	if($row->href) {
		$row->href = ampReplace($row->href);
		if($row->browsernav == 1) {
?>
<a href="<?php echo sefRelToAbs($row->href); ?>" target="_blank">
<?php
		} else {
?>
<a href="<?php echo sefRelToAbs($row->href); ?>">
<?php
		}
	}
	echo $row->title;
	if($row->href) {
?>
</a>
<?php
	}
	if($row->section) {
?>
<br />
<span class="small<?php echo $params->get('pageclass_sfx'); ?>">(<?php echo $row->section; ?>)</span>
<?php
	}
?>
</div>
<div><?php echo ampReplace($row->text); ?></div>
<?php
	if(!$mosConfig_hideCreateDate) {
?>
<div class="small<?php echo $params->get('pageclass_sfx'); ?>"><?php echo $created; ?></div>
<?php
	}
?>
</fieldset>
<br />
<?php
}
?>
		</td>
	</tr>
</table>
<br />
<a href="http://www.google.com/search?q=<?php echo $searchword; ?>" target="_blank"><?php echo $image; ?></a>
<a href="http://www.yandex.ru/yandsearch?text=<?php echo $searchword; ?>" target="_blank"><?php echo $image1; ?></a>
<a href="http://www.rambler.ru/srch?set=www&amp;words=<?php echo $searchword; ?>" target="_blank"><?php echo $image2; ?></a>
<a href="http://go.mail.ru/search?lfilter=y&amp;q=<?php echo $searchword; ?>" target="_blank"><?php echo $image3; ?></a>
<a href="http://sm.aport.ru/scripts/template.dll?That=std&amp;r=<?php echo $searchword; ?>" target="_blank"><?php echo $image4; ?></a>
<a href="http://gogo.ru/go?q=<?php echo $searchword; ?>" target="_blank"><?php echo $image5; ?></a>
<br />
<?php
	}

	function conclusion($searchword, $pageNav) {
		global $mosConfig_live_site, $option, $Itemid;
		$ordering = strtolower(strval(mosGetParam($_REQUEST, 'ordering', 'newest')));
		$searchphrase = strtolower(strval(mosGetParam($_REQUEST, 'searchphrase', 'any')));
		$searchphrase = htmlspecialchars($searchphrase);
		$link = $mosConfig_live_site . "/index.php?option=$option&amp;Itemid=$Itemid&amp;searchword=$searchword&amp;searchphrase=$searchphrase&amp;ordering=$ordering";
		echo $pageNav->writePagesLinks($link);
	}
}

class search_by_tag_HTML{

    function tag_page ($items, $params, $groups){
        ?>
            <div class="tag_page">
                <div class="contentpagetitle">
                     <h1><?php echo $params->title;?>  "<?php echo $items->tag;?>" </h1>
                     <div class="search_result">
                        <?php echo self::view_group($items, $params, $groups);?>
                     </div>
                </div>
            </div>
        <?php
    }

    function view_group($items, $params, $groups){
        global $mosConfig_live_site;
		?>

		<?php if(count($items)>0){?>
			<?php
                foreach($groups as $key=>$group){
                    foreach($items->items[$key] as $item){
                        $item->link = searchByTag::construct_url($item, $group);
                    ?>

                    <div class="search_item">
                        <a href="<?php echo $item->link;?>"><?php echo $item->title;?></a> <br />
                        <span class="date"><?php echo $item->date;?></span> <br />
                        <p><?php echo $item->text;?></p>
                    </div>

                <?php }
            }



		}else{?>
			<div>��... � ���� ����� ������ �� �������</div>
		<?php };?>
	<?php
    }
}
?>

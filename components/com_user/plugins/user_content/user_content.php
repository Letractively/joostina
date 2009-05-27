<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
global $database, $mosConfig_live_site, $mosConfig_form_date_full, $mainframe, $mosConfig_absolute_path;
require_once ($mainframe->getPath('class','com_content'));
require_once ($mosConfig_absolute_path.'/components/com_content/content.html.php');

$k = 0;

$user_items = new mosContent($database);
$user_items = $user_items->load_user_items($user->id, 0, 10);

$access = new jstContentAccess();

if(!$user_items){
    ?>
    <div id="userContent_area">
        <div class="error">Пользователь еще ничего не опубликовал</div>
    </div>
    <?php
    return;
}
?>

<div id="userContent_area">
<br />
    <table width="100%" border="0" cellspacing="0" cellpadding="0">

<?php

		foreach ($user_items as $row) {
			$row->created = mosFormatDate ($row->created,$mosConfig_form_date_full,'0');
			$link	= sefRelToAbs( 'index.php?option=com_content&amp;task=view&amp;id='. $row->id);
			$img	= $row->published ? 'publish_g.png' : 'publish_x.png';
			$img	= $mosConfig_live_site.'/'.ADMINISTRATOR_DIRECTORY.'/images/'.$img;

            // раздел / категория
            $section_cat = $row->section.' / '.$row->category;
            if($row->sectionid==0){
                $section_cat = 'Статичное содержимое';
            }

			?>
			<tr class="sectiontableentry<?php echo ($k+1);?>">

                <?php if($access->canPublish){?>
				<td align="center" <?php echo ($access->canPublish) ? 'onclick="ch_publ('.$row->id.');" class="td-state"' : null ;?>>
				    <img class="img-mini-state" src="<?php echo $img;?>" id="img-pub-<?php echo $row->id;?>" alt="Публикация" />
				</td>
                <?php }?>

                <?php if($access->canEdit){?>
                <td>
                    <?php HTML_content::EditIcon( $row, $params, $access );?>
                </td>
                <?php }?>

				<td>
				    <a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
                    <br />
					<span class="small"><?php  echo $section_cat; ?></span>
				</td>
				<td>
                    <?php echo $row->created; ?>
                </td>
				<td align="center">
                    <?php echo $row->hits ? $row->hits : 0; ?>
                </td>

			</tr>
            <?php $k = 1 - $k; ?>
        <?php }  ?>
    </table>
</div>








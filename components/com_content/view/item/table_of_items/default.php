<?php
    global $Itemid;
    $k = 0;

	$link = 'index.php?option=com_content&amp;task=category&amp;sectionid='.$sectionid.'&amp;id='.$catid.'&amp;Itemid='.$Itemid;

    $display_form = 0;
    $display_filter = 0;
    $display_order = 0;
    $display_num_of_items = 0;

    $navigation = 0;

    if($params->get('filter') || $params->get('order_select') || $params->get('display')) {
        $display_form = 1;
    }
    if($params->get('filter')){
        $display_filter = 1;
    }
    if($params->get('order_select')) {
        $display_order = 1;
    }
    if($params->get('display')) {
        $display_num_of_items = 1;
        $order = '';  $filter = '';

        if($lists['order_value']) {
            $order = '&amp;order='.$lists['order_value'];
        }
        if($lists['filter']) {
		    $filter = '&amp;filter='.$lists['filter'];
		}

		$link = 'index.php?option=com_content&amp;task=category&amp;sectionid='.$sectionid.'&amp;id='.$catid.'&amp;Itemid='.$Itemid.$order.$filter;
    }
    if ($params->get('navigation')){
        $navigation = 1;
        $order = '';  $filter = '';
		if($lists['order_value']) {
		    $order = '&amp;order='.$lists['order_value'];
		}

		if($lists['filter']) {
		    $filter = '&amp;filter='.$lists['filter'];
		}

		$link = 'index.php?option=com_content&amp;task=category&amp;sectionid='.$sectionid.'&amp;id='.$catid.'&amp;Itemid='.$Itemid.$order.$filter;
    }



?>

    <form action="<?php echo sefRelToAbs($link); ?>" method="post" name="adminForm">

        <?php if($display_form) { ?>
		    <table class="filters" cellpadding="0" cellspacing="0" width="100%">
				<tr>

                <?php if($display_filter) { ?>
				    <td align="right" width="100%" class="jtd_nowrap">
                        <?php echo _FILTER.'&nbsp;';?>
						<br />
						<input type="text" name="filter" value="<?php echo $lists['filter']; ?>" class="inputbox" onchange="document.adminForm.submit();" />
					</td>
                <?php }?>


                <?php if($display_order) { ?>
				    <td align="right" width="100%" class="jtd_nowrap">
                        <?php echo _ORDER_DROPDOWN; ?>
			            <br />
				        <?php echo $lists['order']; ?>
					</td>
                <?php } ?>

                <?php if($display_num_of_items) {   ?>
				    <td align="right" width="100%" class="jtd_nowrap">
                        <?php echo _PN_DISPLAY_NR; ?>
    				    <br />
				        <?php echo $pageNav->getLimitBox($link);?>
					</td>
                <?php } ?>

				</tr>
			</table>
         <?php } ?>
        <input type="hidden" name="id" value="<?php echo $catid; ?>" />
		<input type="hidden" name="sectionid" value="<?php echo $sectionid; ?>" />
		<input type="hidden" name="task" value="<?php echo $lists['task']; ?>" />
		<input type="hidden" name="option" value="com_content" />
	</form>


    <table class="table_of_items" width="100%" border="0" cellspacing="0" cellpadding="0">

        <?php if($params->get('headings')) { ?>
		    <tr>

            <?php if($params->get('date')) { ?>
			    <th class="sectiontableheader" width="15%"><?php echo _DATE; ?></th>
            <?php } ?>

            <?php if($params->get('title')) { ?>
			    <th class="sectiontableheader"><?php echo _HEADER_TITLE; ?></th>
            <?php } ?>

            <?php if($params->get('author')) { ?>
			    <th class="sectiontableheader" align="left"><?php echo _HEADER_AUTHOR; ?></th>
            <?php } ?>

            <?php if($params->get('hits')) { ?>
			    <th align="center" class="sectiontableheader" width="5%"><?php echo _HEADER_HITS; ?></th>
            <?php } ?>

            </tr>
        <?php } ?>


        <?php
        foreach($items as $row) {
            $row->created = mosFormatDate($row->created,$params->get('date_format'));
            HTML_content::_Itemid($row);
            ?>

            <tr class="sectiontableentry<?php echo ($k + 1); ?>">

            <?php if($params->get('date')) { ?>
				<td><?php echo $row->created; ?></td>
            <?php } ?>

            <?php
            if($params->get('title')) {
				if($row->access <= $gid) {
				    $link = sefRelToAbs('index.php?option=com_content&amp;task=view&amp;id='.$row->id.'&amp;Itemid='.$Itemid);

                    ?>
                    <td>
                        <?php HTML_content::EditIcon($row,$params,$access); ?>
					    <a href="<?php echo $link; ?>" title="<?php echo $row->title; ?>"><?php echo $row->title; ?></a>
				    </td>
                <?php } else { ?>
				    <td>
                        <?php
                        echo $row->title.' : ';
					    $link = sefRelToAbs('index.php?option=com_registration&amp;task=register');
                        ?>
					    <a href="<?php echo $link; ?>" title="<?php echo $row->title; ?>"><?php echo _READ_MORE_REGISTER; ?></a>
				    </td>
                <?php }?>
            <?php } ?>

            <?php if($params->get('author')) { ?>
			    <td align="left"><?php echo $row->created_by_alias ? $row->created_by_alias : $row->author; ?></td>
		    <?php } ?>

            <?php if($params->get('hits')) { ?>
				<td align="center"><?php echo $row->hits?$row->hits:'-'; ?></td>
            <?php } ?>

            </tr>

            <?php
            $k = 1 - $k;
        } ?>
    </table>


    <?php if($navigation) { ?>
    <div class="pagenav">
        <?php echo $pageNav->writePagesLinks($link); ?>
        <?php echo $pageNav->writePagesCounter(); ?>
    </div>
    <?php } ?>

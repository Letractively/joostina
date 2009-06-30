
    <?php if(!count($categories)) return; ?>

    <ul class="cat_list">
    <?php foreach($categories as $row) {
    	
   		$params->set('catid', $row->id);
   		$params->set('sectionid', $row->section);
    	$params->set('Itemid', '&Itemid='.$Itemid);
    	
    	
    	
	    $row->name = htmlspecialchars(stripslashes(ampReplace($row->name)),ENT_QUOTES);
		if($catid != $row->id) { ?>

        <li>

            <?php if($row->access <= $gid) {

            	$link = mosCategory::get_category_url($params); ?>
                <a href="<?php echo $link; ?>" class="category" title="<?php echo $row->name; ?>"><?php echo $row->name; ?></a>

                <?php if($params->get('cat_items')) { ?>
                    &nbsp;<i>( <?php echo $row->numitems; echo _CHECKED_IN_ITEMS; ?> )</i>
			    <?php } ?>


                <?php if($params->get('cat_description') && $row->description) { ?>
			        <br />
				    <?php echo $row->description; ?>
                <?php } ?>

            <?php } else { ?>

                <?php echo $row->name; ?>
				<a href="<?php echo sefRelToAbs('index.php?option=com_registration&amp;task=register'); ?>">( <?php echo _E_REGISTERED; ?> )</a>

		   <?php } ?>


        </li>


    <?php } } ?>


    </ul>

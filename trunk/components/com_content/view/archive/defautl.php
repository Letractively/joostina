
    <div class="page_archive">

        <div class="form">
            <?php echo  mosHTML::monthSelectList('month','size="1" class="inputbox"',$params->get('month'));?>
            <?php echo mosHTML::integerSelectList(2000,2010,1,'year','size="1" class="inputbox"',$params->get('year'),"%04d"); ?>
            <input type="submit" class="button" value="<?php echo _SUBMIT_BUTTON;?>" />
        </div>

    <?php if($total) { ?>
        <div class="contentdescription">
            <?php echo $msg;?>
         </div>

        <div class="blog">

    		<?php if($leading) { ?>
    			<div class="leading_block">

    			<?php for($z = 0; $z < $leading; $z++) { if($i >= ($total - $limitstart)) { break; } ?>

    			    <div class="intro leading" id="leading_<?php echo $z;?>">
    				    <?php  show($rows[$i],$params,$gid,$access,$pop);?>
    			    </div>

                <?php $i++; } ?>

                </div>
    		<?php } ?>


            <?php if($intro && ($i < $total)) { ?>
			<table class="intro_table" width="100%"  cellpadding="0" cellspacing="0">

            <?php for($z = 0; $z < $intro; $z++) {

                if($i >= ($total - $limitstart)) { break; } if(!($z % $columns) || $columns == 1) { ?>
			    <tr>
			    <?php } ?>

                    <td valign="top" <?php echo $width;?>>

                        <?php if($z < $intro) { ?>

                        <div class="intro" id="intro_<?php echo $z;?>">
					        <?php show($rows[$i],$params,$gid,$access,$pop); ?>
                        </div>

			            <?php } else { echo '</td></tr>'; break; } ?>
				    </td>

			   <?php $i++;  if(  (!(($z + 1) % $columns) || $columns == 1) ||  ($i >= $total)   ||   ((($z + 1) == $intro) && ($intro % $columns))  ) { ?>
               </tr>
               <?php } ?>

		   <?php } ?>
			</table>

		<?php } ?>


        <?php if($display_blog_more){ ?>
			<div class="blog_more">
			    <?php  HTML_content::showLinks($rows,$links,$total,$i,$showmore);;?>
			</div>
       <?php } ?>

       <?php if($display_pagination){
	        echo $pageNav->writePagesLinks($link);
            if($display_pagination_results){
    			echo $pageNav->writePagesCounter();
            }
       } ?>


       </div>

    <?php } ?>

    <?php else{ ?>
        <div class="contentdescription">
            <?php echo $msg;?>
        </div>
    <?php } ?>

    <?php  mosHTML::BackButton($params); ?>


    </div>
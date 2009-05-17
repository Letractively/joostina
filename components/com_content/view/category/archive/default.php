<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>
    <div class="page_archive">

        <?php $link = ampReplace('index.php?option=com_content&task=archivecategory&id='.$id.'&Itemid='.$Itemid); ?>
        <form action="<?php echo sefRelToAbs($link); ?>" method="post">


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
    				    <?php  show($rows[$i],$params,$gid,$access,$pop, 'intro/leading/default.php');?>
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
					        <?php show($rows[$i],$params,$gid,$access,$pop, 'intro/simple/default.php'); ?>
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

    <?php } else { ?>

        <div class="contentdescription">
            <?php echo $msg;?>
        </div>
    <?php } ?>



	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
	<input type="hidden" name="task" value="archivecategory" />
    <input type="hidden" name="option" value="com_content" />
	</form>



    <?php  mosHTML::BackButton($params); ?>


    </div>
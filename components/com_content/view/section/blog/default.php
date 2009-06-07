<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>
    <div class="page_sectionblog<?php echo $sfx ;?>">

        <?php if($header) { ?>
		    <div class="componentheading"><?php echo $header;?></div>
	    <?php }?>

        <?php if($total) { ?>
            <div class="blog">

            <?php if($display_desc) { ?>
                <div class="contentdescription">

        			<?php if($display_desc_img) { ?>
        			    <img src="<?php echo $mosConfig_live_site;?>/images/stories/<?php echo $obj->image;?>" align="<?php echo $obj->image_position;?>"  alt="" />
        			<?php } ?>

                    <?php if($display_desc_text) { ?>
        				<p> <?php echo $obj->description;?> </p>
        			<?php } ?>

    			</div>
		    <?php } ?>



    		<?php if($leading) { ?>
    			<div class="leading_block">

    			<?php  for($z = 0; $z < $leading; $z++) {
    			    if($i >= ($total - $limitstart)) { break; }
                    if(array_key_exists($rows[$i]->id, $tags_arr)){
                        $rows[$i]->tags = $tags_arr[$rows[$i]->id];
                    }
                    ?>

    			    <div class="intro leading" id="leading_<?php echo $i;?>">
    				    <?php  _showItem($rows[$i],$params,$gid,$access,$pop, 'intro/leading/default.php');?>
    			    </div>

                <?php $i++; } ?>

                </div>
    		<?php } ?>


            <?php if($intro && ($i < $total)) { ?>
			<table class="intro_table" width="100%"  cellpadding="0" cellspacing="0">

            <?php for($z = 0; $z < $intro; $z++) {

                if(isset($rows[$i]) && array_key_exists($rows[$i]->id, $tags_arr)){
                    $rows[$i]->tags = $tags_arr[$rows[$i]->id];
                }

                if($i >= ($total - $limitstart)) { break; } if(!($z % $columns) || $columns == 1) { ?>
			    <tr>
			    <?php } ?>

                    <td valign="top" <?php echo $width;?>>

                        <?php if($z < $intro) { ?>

                        <div class="intro" id="intro_<?php echo $i;?>">
					       <?php _showItem($rows[$i],$params,$gid,$access,$pop, 'intro/simple/default.php'); ?>
                        </div>

			            <?php } else { echo '</td></tr>'; break; } ?>
				    </td>

			   <?php $i++;  if(  (!(($z + 1) % $columns) || $columns == 1) ||  ($i >= $total)   ||   ((($z + 1) == $intro) && ($intro % $columns))  ) { ?>

                </tr>

               <?php }

			}?>

			</table>

		<?php } ?>


        <?php if($display_blog_more){ ?> 
			<div class="blog_more">
			    <?php  HTML_content::showLinks($rows,$links,$total,$i,$showmore);?>
			</div>
       <?php } ?>

       <?php if($display_pagination){
	        echo $pageNav->writePagesLinks($link);
            if($display_pagination_results){
    			echo $pageNav->writePagesCounter();
            }
       } ?>


       </div>

       <?php } else { echo _EMPTY_BLOG; }

     mosHTML::BackButton($params);

        ?>

    </div>
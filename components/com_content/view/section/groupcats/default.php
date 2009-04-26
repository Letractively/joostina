
    <div class="page_sectionblog<?php echo $sfx ;?>">

        <?php if($header) { ?>
		    <div class="componentheading"><h1><?php echo $header;?></h1></div>
	    <?php }?>

        <?php if($total) { ?>
            <div class="blog">

            <?php if($display_desc) { ?>
                <div class="contentdescription">

        			<?php if($display_desc_img) { ?>
        			    <img src="<?php echo $mosConfig_live_site;?>/images/stories/<?php echo $description->image;?>" align="<?php echo $description->image_position;?>"  alt="" />
        			<?php } ?>

                    <?php if($display_desc_text) { ?>
        				<p> <?php echo $description->description;?> </p>
        			<?php } ?>

    			</div>
		    <?php } ?>



    		<?php if($leading) { ?>
    			<div class="leading_block">

    			<?php for($z = 0; $z < $leading; $z++) { if($i >= ($total - $limitstart)) { break; } ?>

    			    <div class="intro leading" id="leading_<?php echo $i;?>">
    				    <?php  show($rows[$i],$params,$gid,$access,$pop, 'intro_view/leading/default.php');?>
    			    </div>

                <?php $i++; } ?>

                </div>
    		<?php } ?>


            <?php if($intro && ($i < $total)) { ?>


            <?php for($z = 0; $z < $intro; $z++) {
                if($groupcat_limit && ($i < $total)) {
                    if(isset($rows[$i])  && !in_array($rows[$i]->catid, $cats_arr)){
                        $cats_arr[$k]=$rows[$i]->catid;
                        $k++;

                        echo '<h2 class="category_name">'.$rows[$i]->category.'</h2> ';
                        echo '<table width="100%" class="group_cat">';
                        $kk=0;
                     }


                     if($kk<$groupcat_limit){
                         if( $kk % $columns== 0) echo "<tr>";
                        echo '<td width="50%">';

					     show($rows[$i],$params,$gid,$access,$pop, 'intro_view/simple/default.php');
                        echo '</td>' ;


                      }

                      $i++; $kk++;
                    if( $kk % $columns == 0) echo "</tr>";



             if((isset($rows[$i])  && !in_array($rows[$i]->catid, $cats_arr)) || (!isset($rows[$i])) ){
                        echo '</table>';
                        //Выводим ссылку на все статьи категории
                        //$cat_link=HTML_content::get_cat_link($rows[$i-1]);
                        $cat_link = sefRelToAbs('index.php?option=com_content&amp;task=blogcategory&amp;id='.$rows[$i-1]->catid.'&amp;Itemid='.$_REQUEST['Itemid']);
                        echo '<div class="readmore"><a class="readmore" href="'.$cat_link.'">все статьи</a></div>';

                    } ?>



               <?php }

			}?>



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